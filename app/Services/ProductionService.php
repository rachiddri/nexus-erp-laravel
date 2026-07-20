<?php

namespace App\Services;

use App\Enums\OrigineOrdreProduction;
use App\Enums\StatutOrdreProduction;
use App\Enums\TypeMouvementPhysique;
use App\Models\Lot;
use App\Models\LotProduit;
use App\Models\OrdreProduction;
use App\Models\ProduitEtape;
use App\Models\ProduitPhysique;
use App\Models\ProduitPhysiqueHistorique;
use Illuminate\Support\Facades\DB;

class TransitionInterditeError extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->name = 'TransitionInterditeError';
    }
}

/**
 * ProductionService — Ordres de production & lancement (§6.3, F3, F4, F5).
 */
class ProductionService
{
    /**
     * Crée un ordre de production depuis une commande (F3).
     */
    public static function lancerOrdreDepuisCommande(int $bonCommandeId, array $lignes, int $depotMatiereId, int $userId): OrdreProduction
    {
        if (empty($lignes)) {
            throw new \Exception('Au moins une ligne requise');
        }

        return DB::transaction(function () use ($bonCommandeId, $lignes, $depotMatiereId, $userId) {
            $annee = (int) now()->format('Y');
            $n = SequenceService::prochainNumero(\App\Services\PrefixeDocument::OP, $annee);
            $numeroOp = SequenceService::formatNumero('op', $annee, $n);

            $opLignesData = collect($lignes)->map(fn ($l) => [
                'produit_id' => $l['produitId'],
                'quantite' => $l['quantite'],
            ])->all();

            $op = OrdreProduction::create([
                'numero_op' => $numeroOp,
                'origine' => OrigineOrdreProduction::Commande->value,
                'bon_commande_id' => $bonCommandeId,
                'depot_matiere_id' => $depotMatiereId,
                'statut' => StatutOrdreProduction::Brouillon->value,
                'priorite' => 'normale',
                'cree_par' => $userId,
            ]);
            $op->ordreProductionLignes()->createMany($opLignesData);

            return $op->load('ordreProductionLignes');
        });
    }

    /**
     * Confirme un ordre de production : lot + lot_produits + réservation matière.
     */
    public static function confirmerOrdre(int $ordreProductionId, int $userId): OrdreProduction
    {
        return DB::transaction(function () use ($ordreProductionId, $userId) {
            $op = OrdreProduction::with(['lignes', 'depotMatiere'])
                ->findOrFail($ordreProductionId);

            if ($op->statut !== StatutOrdreProduction::Brouillon->value) {
                throw new TransitionInterditeError("Seul un brouillon peut être confirmé (statut actuel: {$op->statut})");
            }

            $annee = (int) now()->format('Y');
            $nLot = SequenceService::prochainNumero(\App\Services\PrefixeDocument::LOT, $annee);
            $numeroLot = SequenceService::formatNumero('lot', $annee, $nLot);

            $lot = Lot::create([
                'numero_lot' => $numeroLot,
                'ordre_production_id' => $op->id,
                'statut' => 'en_cours',
            ]);

            foreach ($op->lignes as $ligne) {
                $lp = LotProduit::create([
                    'lot_id' => $lot->id,
                    'ordre_production_ligne_id' => $ligne->id,
                    'produit_id' => $ligne->produit_id,
                    'quantite_theorique' => $ligne->quantite,
                ]);

                StockMatiereService::reserverPourLotProduit($lp->id, $op->depot_matiere_id);
            }

            $op->update([
                'statut' => StatutOrdreProduction::Confirme->value,
                'valide_par' => $userId,
                'valide_le' => now(),
            ]);

            return $op->load('lots');
        });
    }

    /**
     * Annule un ordre de production confirmé (si aucun scan effectué).
     */
    public static function annulerOrdreConfirme(int $ordreProductionId, string $motif, int $userId): OrdreProduction
    {
        return DB::transaction(function () use ($ordreProductionId, $motif, $userId) {
            $op = OrdreProduction::with(['lots.produitsPhysiques.historique'])
                ->findOrFail($ordreProductionId);

            if ($op->statut !== StatutOrdreProduction::Confirme->value) {
                throw new TransitionInterditeError('Seul une OP confirmée sans scan peut être annulée');
            }

            foreach ($op->lots as $lot) {
                foreach ($lot->produitsPhysiques as $pp) {
                    $aUnScan = $pp->historique->contains(
                        fn ($h) => $h->type_mouvement === TypeMouvementPhysique::PassageEtape->value
                    );
                    if ($aUnScan) {
                        throw new TransitionInterditeError('Impossible d\'annuler : des unités ont été scannées');
                    }
                }
            }

            foreach ($op->lots as $lot) {
                $lotProduits = LotProduit::where('lot_id', $lot->id)->get();
                foreach ($lotProduits as $lp) {
                    \App\Models\StockMatierePremiere::where('depot_id', $op->depot_matiere_id)
                        ->whereIn('matiere_premiere_id',
                            $lp->produit->produitMatierePremiere->pluck('matiere_premiere_id')->all())
                        ->decrement('quantite_reservee',
                            (float) $lp->quantite_theorique * $lp->produit->produitMatierePremiere->sum('quantite'));
                }
                ProduitPhysique::where('lot_id', $lot->id)->update(['statut' => 'annule']);
                $lot->update(['statut' => 'annule']);
            }

            return $op->update(['statut' => StatutOrdreProduction::Annule->value]);
        });
    }

    /**
     * Calcule l'insuffisance de stock pour une commande (F3 pré-remplissage).
     */
    public static function calculerInsuffisance(int $bonCommandeId): array
    {
        $bc = \App\Models\BonCommande::with('lignes.produit')->findOrFail($bonCommandeId);

        $insuffisances = [];
        foreach ($bc->lignes as $ligne) {
            $disponibles = ProduitPhysique::where('produit_id', $ligne->produit_id)
                ->where('statut', 'disponible')
                ->count();
            $commandee = $ligne->quantite;
            $manquant = max(0, $commandee - $disponibles);
            if ($manquant > 0) {
                $insuffisances[] = [
                    'produit_id' => $ligne->produit_id,
                    'produit_nom' => $ligne->produit->nom,
                    'quantite_commandee' => $commandee,
                    'quantite_disponible' => $disponibles,
                    'manquant' => $manquant,
                ];
            }
        }

        return $insuffisances;
    }

    /**
     * Scanne et transfert d'étape (F5).
     */
    public static function scannerTransfertEtape(int $produitPhysiqueId, int $etapeArriveeId, int $userId): ProduitPhysique
    {
        return DB::transaction(function () use ($produitPhysiqueId, $etapeArriveeId, $userId) {
            $pp = ProduitPhysique::with('etapeActuelle')->findOrFail($produitPhysiqueId);

            $etapeDepartId = $pp->etape_actuelle_id;

            $gamme = ProduitEtape::where('produit_id', $pp->produit_id)
                ->where('etape_production_id', $etapeDepartId)
                ->first();
            if (! $gamme) {
                throw new \Exception('Cette étape ne fait pas partie de la gamme du produit');
            }

            $pp->update([
                'etape_actuelle_id' => $etapeArriveeId,
                'date_sortie' => now(),
            ]);

            ProduitPhysiqueHistorique::create([
                'produit_physique_id' => $pp->id,
                'etape_origine' => $etapeDepartId ? (string) $etapeDepartId : '',
                'etape_destination' => (string) $etapeArriveeId,
                'type_mouvement' => TypeMouvementPhysique::PassageEtape->value,
                'utilisateur_id' => $userId,
            ]);

            return ProduitPhysique::with('etapeActuelle')->findOrFail($pp->id);
        });
    }
}
