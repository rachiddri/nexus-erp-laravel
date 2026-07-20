<?php

namespace App\Services;

use App\Enums\TypeMouvementStockMatiere;
use App\Models\LotProduit;
use App\Models\MatierePremiere;
use App\Models\MatierePremierePrixHistorique;
use App\Models\MouvementStockMatiere;
use App\Models\StockMatierePremiere;
use Illuminate\Support\Facades\DB;

class InsuffisanceMatiereError extends \Exception
{
    public function __construct(
        public int $matiereId,
        public string $matiereNom,
        public float $besoin,
        public float $disponible
    ) {
        parent::__construct("Matière insuffisante: \"$matiereNom\" — besoin: $besoin, disponible: $disponible");
        $this->name = 'InsuffisanceMatiereError';
    }
}

class StockNegatifError extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->name = 'StockNegatifError';
    }
}

/**
 * StockMatiereService — Gestion des stocks de matières premières (§6.2, F1).
 */
class StockMatiereService
{
    /**
     * Quantité disponible (hors réservé) d'une matière dans un dépôt.
     */
    public static function quantiteDisponible(int $matierePremiereId, int $depotId): float
    {
        $stock = StockMatierePremiere::where('matiere_premiere_id', $matierePremiereId)
            ->where('depot_id', $depotId)
            ->first();

        if (! $stock) {
            return 0.0;
        }

        return (float) $stock->quantite_disponible - (float) $stock->quantite_reservee;
    }

    /**
     * Réserve la matière nécessaire pour un lot_produit (dans une transaction).
     */
    public static function reserverPourLotProduit(int $lotProduitId, int $depotId): void
    {
        $lotProduit = LotProduit::with(['produit.produitMatierePremiere.matierePremiere'])
            ->findOrFail($lotProduitId);

        $besoin = (float) $lotProduit->quantite_theorique;

        foreach ($lotProduit->produit->produitMatierePremiere as $bom) {
            $quantiteNecessaire = $besoin * (float) $bom->quantite * (1 + (float) ($bom->rebut ?? 0) / 100);

            $stock = StockMatierePremiere::where('matiere_premiere_id', $bom->matiere_premiere_id)
                ->where('depot_id', $depotId)
                ->lockForUpdate()
                ->first();

            if (! $stock) {
                throw new InsuffisanceMatiereError($bom->matiere_premiere_id, $bom->matierePremiere->nom, $quantiteNecessaire, 0);
            }

            $disponible = (float) $stock->quantite_disponible - (float) $stock->quantite_reservee;
            if ($disponible < $quantiteNecessaire) {
                throw new InsuffisanceMatiereError($bom->matiere_premiere_id, $bom->matierePremiere->nom, $quantiteNecessaire, $disponible);
            }

            $stock->increment('quantite_reservee', $quantiteNecessaire);
        }
    }

    /**
     * Libère les réservations matière pour un lotProduit.
     */
    public static function libererReservation(int $lotProduitId, int $depotId): void
    {
        $lotProduit = LotProduit::with(['produit.produitMatierePremiere'])
            ->findOrFail($lotProduitId);

        $besoin = (float) $lotProduit->quantite_theorique;

        foreach ($lotProduit->produit->produitMatierePremiere as $bom) {
            $quantiteReservee = $besoin * (float) $bom->quantite * (1 + (float) ($bom->rebut ?? 0) / 100);
            StockMatierePremiere::where('matiere_premiere_id', $bom->matiere_premiere_id)
                ->where('depot_id', $depotId)
                ->decrement('quantite_reservee', $quantiteReservee);
        }
    }

    /**
     * Consomme RÉELLEMENT la matière pour un lotProduit (à la clôture).
     */
    public static function consommerPourLotProduit(int $lotProduitId, int $depotId, int $userId): void
    {
        $lotProduit = LotProduit::with(['produit.produitMatierePremiere.matierePremiere'])
            ->findOrFail($lotProduitId);

        $quantiteProduite = (float) $lotProduit->quantite_produite;

        foreach ($lotProduit->produit->produitMatierePremiere as $bom) {
            $consoReelle = $quantiteProduite * (float) $bom->quantite * (1 + (float) ($bom->rebut ?? 0) / 100);

            $stock = StockMatierePremiere::where('matiere_premiere_id', $bom->matiere_premiere_id)
                ->where('depot_id', $depotId)
                ->lockForUpdate()
                ->firstOrFail();

            $coutUnitaire = (float) $bom->matierePremiere->cout_unitaire_moyen;

            $stock->decrement('quantite_reservee', $consoReelle);
            $stock->decrement('quantite_disponible', $consoReelle);

            MouvementStockMatiere::create([
                'matiere_premiere_id' => $bom->matiere_premiere_id,
                'depot_id' => $depotId,
                'type_mouvement' => TypeMouvementStockMatiere::SortieProduction->value,
                'quantite' => -$consoReelle,
                'cout_unitaire' => $coutUnitaire,
                'reference' => "lot_produit:{$lotProduitId}",
                'cree_par' => $userId,
            ]);

            \App\Models\LotConsommationMatiere::create([
                'lot_produit_id' => $lotProduitId,
                'matiere_premiere_id' => $bom->matiere_premiere_id,
                'quantite_consommee' => $consoReelle,
                'quantite_rebutee' => 0,
            ]);
        }
    }

    /**
     * Entrée d'achat matière (F1) avec recalcul du coût unitaire moyen pondéré.
     */
    public static function entreeAchat(int $matierePremiereId, int $depotId, float $quantite, float $prixUnitaire, int $userId): void
    {
        $stock = StockMatierePremiere::lockForUpdate()
            ->where('matiere_premiere_id', $matierePremiereId)
            ->where('depot_id', $depotId)
            ->first();

        if (! $stock) {
            $stock = StockMatierePremiere::create([
                'matiere_premiere_id' => $matierePremiereId,
                'depot_id' => $depotId,
                'quantite_disponible' => $quantite,
                'quantite_reservee' => 0,
            ]);
        } else {
            $stock->increment('quantite_disponible', $quantite);
        }

        $matiere = MatierePremiere::lockForUpdate()->findOrFail($matierePremiereId);

        $stockVal = (float) $stock->quantite_disponible * (float) $matiere->cout_unitaire_moyen;
        $entreeVal = $quantite * $prixUnitaire;
        $nouveauCout = ($stockVal + $entreeVal) / ((float) $stock->quantite_disponible + $quantite);

        $matiere->update(['cout_unitaire_moyen' => $nouveauCout]);

        MouvementStockMatiere::create([
            'matiere_premiere_id' => $matierePremiereId,
            'depot_id' => $depotId,
            'type_mouvement' => TypeMouvementStockMatiere::EntreeAchat->value,
            'quantite' => $quantite,
            'cout_unitaire' => $prixUnitaire,
            'reference' => 'achat',
            'cree_par' => $userId,
        ]);

        $prixCourant = MatierePremierePrixHistorique::where('matiere_premiere_id', $matierePremiereId)
            ->whereNull('date_fin')
            ->orderByDesc('date_debut')
            ->first();

        if ($prixCourant && (float) $prixCourant->prix_apres !== $prixUnitaire) {
            $prixCourant->update(['date_fin' => now()]);
        }

        MatierePremierePrixHistorique::create([
            'matiere_premiere_id' => $matierePremiereId,
            'prix_avant' => $prixCourant ? (float) $prixCourant->prix_apres : $prixUnitaire,
            'prix_apres' => $prixUnitaire,
            'date_debut' => now(),
        ]);
    }
}
