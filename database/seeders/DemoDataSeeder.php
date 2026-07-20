<?php

namespace Database\Seeders;

use App\Models\BonCommande;
use App\Models\BonCommandeLigne;
use App\Models\Client;
use App\Models\Depot;
use App\Models\DocumentsSortie;
use App\Models\DocumentSortieLigne;
use App\Models\Lot;
use App\Models\LotProduit;
use App\Models\OrdreProduction;
use App\Models\OrdreProductionLigne;
use App\Models\Produit;
use App\Models\ProduitPhysique;
use App\Models\User;
use App\Services\FactureService;
use App\Services\PrefixeDocument;
use App\Services\SequenceService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Jeu de données de démonstration — chaîne complète :
 * Client → Bon de commande → Ordre de production → Lot → Produits physiques
 * → Document de sortie (livraison) → Facture (émise).
 *
 * Idempotent : ne s'exécute que si aucun bon de commande n'existe déjà.
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (BonCommande::count() > 0) {
            $this->command->info('ℹ️ Données de démo déjà présentes — seeding ignoré.');
            return;
        }

        DB::transaction(function () {
        $annee = (int) now()->format('Y');
        $user = User::first();
        $client = Client::first();
        $depot = Depot::first();
        $produits = Produit::take(2)->get();

        if ($produits->count() < 2) {
            $this->command->warn('Pas assez de produits pour la démo — seeding ignoré.');
            return;
        }

        // 1) Bon de commande (confirmé) + lignes
        $nBc = SequenceService::prochainNumero(PrefixeDocument::BC, $annee);
        $bc = BonCommande::create([
            'numero_bc' => SequenceService::formatNumero(PrefixeDocument::BC->prefixe(), $annee, $nBc),
            'client_id' => $client->id,
            'date_commande' => now()->subDays(10),
            'date_livraison_souhaitee' => now()->addDays(5),
            'statut' => 'confirmee',
            'montant_ht' => 0,
            'montant_ttc' => 0,
            'montant_total' => 0,
            'cree_par' => $user->id,
        ]);
        $bcLignes = [];
        foreach ($produits as $index => $p) {
            $qte = $index === 0 ? 5 : 3;
            $bcLignes[] = [
                'produit_id' => $p->id,
                'quantite' => $qte,
                'prix_unitaire' => (float) $p->prix_vente,
                'montant_total' => $qte * (float) $p->prix_vente,
                'description' => $p->nom,
            ];
        }
        $bc->bonCommandeLignes()->createMany($bcLignes);

        // 2) Ordre de production (lié au BC) + lignes
        $nOp = SequenceService::prochainNumero(PrefixeDocument::OP, $annee);
        $op = OrdreProduction::create([
            'numero_op' => SequenceService::formatNumero(PrefixeDocument::OP->prefixe(), $annee, $nOp),
            'bon_commande_id' => $bc->id,
            'depot_matiere_id' => $depot->id,
            'date_lancement' => now()->subDays(8),
            'date_prevue_fin' => now()->addDays(2),
            'statut' => 'en_cours',
            'origine' => 'commande',
            'cree_par' => $user->id,
        ]);
        $opLignes = [];
        foreach ($produits as $index => $p) {
            $qte = $index === 0 ? 5 : 3;
            $opLignes[] = [
                'produit_id' => $p->id,
                'quantite' => $qte,
                'quantite_produite' => 0,
                'quantite_rebutee' => 0,
            ];
        }
        $op->ordreProductionLignes()->createMany($opLignes);

        // 3) Lot de l'OP
        $nLot = SequenceService::prochainNumero(PrefixeDocument::LOT, $annee);
        $lot = Lot::create([
            'numero_lot' => SequenceService::formatNumero(PrefixeDocument::LOT->prefixe(), $annee, $nLot),
            'ordre_production_id' => $op->id,
            'statut' => 'en_production',
            'date_ouverture' => now()->subDays(6),
        ]);

        // 4) LotProduit + 5) Produits physiques (unités produites)
        $phyIdx = 0;
        foreach ($op->ordreProductionLignes as $opl) {
            $lp = LotProduit::create([
                'lot_id' => $lot->id,
                'ordre_production_ligne_id' => $opl->id,
                'produit_id' => $opl->produit_id,
                'quantite_theorique' => $opl->quantite,
                'quantite_produite' => $opl->quantite,
                'quantite_rebutee' => 0,
            ]);
            for ($i = 0; $i < $opl->quantite; $i++) {
                $phyIdx++;
                ProduitPhysique::create([
                    'code_affiche' => 'PHY-' . $annee . '-' . str_pad($phyIdx, 4, '0', STR_PAD_LEFT),
                    'produit_id' => $opl->produit_id,
                    'lot_id' => $lot->id,
                    'lot_produit_id' => $lp->id,
                    'statut' => 'disponible',
                    'date_creation' => now()->subDays(4),
                    'cree_par' => $user->id,
                ]);
            }
        }

        // 6) Document de sortie (livraison) + lignes (produits physiques)
        $nDs = SequenceService::prochainNumero(PrefixeDocument::BL, $annee);
        $ds = DocumentsSortie::create([
            'numero' => SequenceService::formatNumero(PrefixeDocument::BL->prefixe(), $annee, $nDs),
            'type' => 'livraison',
            'client_id' => $client->id,
            'bon_commande_id' => $bc->id,
            'date_sortie' => now()->subDays(1),
            'adresse_livraison' => $client->adresse ?? 'Alger Centre',
            'statut' => 'effectue',
            'cree_par' => $user->id,
        ]);
        $dsLignes = [];
        foreach (ProduitPhysique::where('lot_id', $lot->id)->get() as $pp) {
            $dsLignes[] = [
                'produit_physique_id' => $pp->id,
                'numero_lot' => $lot->numero_lot,
            ];
        }
        $ds->documentSortieLignes()->createMany($dsLignes);

        // 7) Facture générée depuis le document de sortie, puis émise
        $facture = FactureService::creerFactureDepuisDocuments($client->id, [$ds->id], $user->id);
        try {
            FactureService::emettreFacture($facture->id, $user->id);
        } catch (\Throwable $e) {
            $this->command->warn('Facture non émise : ' . $e->getMessage());
        }

        $nbPhys = ProduitPhysique::where('lot_id', $lot->id)->count();
        $this->command->info("✅ Démo : BC {$bc->numero_bc} → OP {$op->numero_op} → Lot {$lot->numero_lot} → {$nbPhys} produits physiques → DS {$ds->numero} → Facture #{$facture->id}");
        });
    }
}
