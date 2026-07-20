<?php

namespace App\Services;

use App\Enums\StatutFacture;
use App\Models\Client;
use App\Models\Facture;
use App\Models\FactureLigne;
use App\Models\MouvementSoldeClient;
use App\Models\Paiement;
use App\Models\PaiementImputation;
use Illuminate\Support\Facades\DB;

class FactureError extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->name = 'FactureError';
    }
}

/**
 * FactureService — Cycle de vie des factures (§4.5, §6.9, F11).
 */
class FactureService
{
    /**
     * Crée une facture brouillon depuis des documents de sortie (F11).
     * @param int $clientId
     * @param int[] $documentSortieIds
     */
    public static function creerFactureDepuisDocuments(int $clientId, array $documentSortieIds, int $userId): Facture
    {
        return DB::transaction(function () use ($clientId, $documentSortieIds, $userId) {
            $documents = \App\Models\DocumentsSortie::with([
                'bonCommande.lignes',
                'lignes.produitPhysique.produit',
            ])->whereIn('id', $documentSortieIds)->get();

            if ($documents->isEmpty()) {
                throw new FactureError('Aucun document valide');
            }

            $bonCommandeId = $documents->first()->bon_commande_id;

            $lignesGroupees = [];
            foreach ($documents as $doc) {
                foreach ($doc->lignes as $ligne) {
                    $pp = $ligne->produitPhysique;
                    $produit = $pp->produit;
                    $key = $pp->id;
                    if (isset($lignesGroupees[$key])) {
                        $lignesGroupees[$key]['quantite'] += 1;
                    } else {
                        $lignesGroupees[$key] = [
                            'produit_physique_id' => $pp->id,
                            'produit_id' => $pp->produit_id,
                            'designation' => $produit->nom,
                            'quantite' => 1,
                            'prix_unitaire' => (float) $produit->prix_vente,
                        ];
                    }
                }
            }

            $montantHt = 0.0;
            $lignesData = [];
            foreach ($lignesGroupees as $l) {
                $ligneHt = $l['quantite'] * $l['prix_unitaire'];
                $montantHt += $ligneHt;
                $lignesData[] = [
                    'produit_id' => $l['produit_id'],
                    'designation' => $l['designation'],
                    'quantite' => $l['quantite'],
                    'prix_unitaire' => $l['prix_unitaire'],
                    'montant_total' => $ligneHt, // HT, sans TVA (TVA globale sur la facture)
                ];
            }

            $facture = Facture::create([
                'numero_facture' => '',
                'client_id' => $clientId,
                'bon_commande_id' => $bonCommandeId,
                'date_facture' => now(),
                'taux_tva' => 19.00,
                'montant_ht' => 0,
                'montant_tva' => 0,
                'montant_ttc' => 0,
                'statut' => StatutFacture::Brouillon->value,
            ]);
            $facture->factureLignes()->createMany($lignesData);

            // Recalcul conforme : HT = somme lignes, TVA = HT × 19 %, TTC = HT + TVA
            $taux = 19.0;
            $tva = $montantHt * $taux / 100;
            $ttc = $montantHt + $tva;
            $facture->update([
                'montant_ht' => $montantHt,
                'montant_tva' => $tva,
                'montant_ttc' => $ttc,
                'montant_ttc_lettres' => \App\Helpers\NumberToWords::enLettres($ttc),
            ]);

            return $facture->load('factureLignes');
        });
    }

    /**
     * Émet une facture (brouillon → emise) avec numéro fiscal sans trou.
     */
    public static function emettreFacture(int $factureId, int $userId): Facture
    {
        return DB::transaction(function () use ($factureId, $userId) {
            $facture = Facture::with('client')->findOrFail($factureId);

            if ($facture->statut !== StatutFacture::Brouillon->value) {
                throw new FactureError('Seule une facture brouillon peut être émise');
            }

            $annee = (int) now()->format('Y');
            $n = SequenceService::prochainNumero(\App\Services\PrefixeDocument::FAC, $annee);
            $numeroFacture = SequenceService::formatNumero('facture', $annee, $n);

            $facture->update([
                'numero_facture' => $numeroFacture,
                'statut' => StatutFacture::Emise->value,
                'emise_par' => $userId,
                'emise_le' => now(),
            ]);

            $nouveauSolde = (float) $facture->client->solde + (float) $facture->montant_ttc;
            MouvementSoldeClient::create([
                'client_id' => $facture->client_id,
                'type_mouvement' => 'facture',
                'montant' => $facture->montant_ttc,
                'reference' => "facture:{$facture->id}",
                'solde_avant' => $facture->client->solde,
                'solde_apres' => $nouveauSolde,
            ]);
            $facture->client->update(['solde' => $nouveauSolde]);

            return $facture;
        });
    }

    /**
     * Impute un paiement encaissé sur des factures (F12).
     * @param int $paiementId
     * @param array<int,array{factureId:int,montant:float}> $imputations
     */
    public static function imputerPaiement(int $paiementId, array $imputations): void
    {
        DB::transaction(function () use ($paiementId, $imputations) {
            $paiement = Paiement::findOrFail($paiementId);

            if ($paiement->statut !== 'encaisse') {
                throw new FactureError('Le paiement doit être encaissé avant imputation');
            }

            $totalImpute = collect($imputations)->sum('montant');
            if ($totalImpute > (float) $paiement->montant) {
                throw new FactureError("Le total des imputations ($totalImpute) dépasse le montant du paiement ({$paiement->montant})");
            }

            foreach ($imputations as $imp) {
                $facture = Facture::findOrFail($imp['factureId']);

                $totalImputeFacture = (float) $facture->montant_paye + $imp['montant'];
                if ($totalImputeFacture > (float) $facture->montant_ttc) {
                    throw new FactureError("L'imputation dépasse le montant TTC de la facture {$facture->numero_facture}");
                }

                PaiementImputation::create([
                    'paiement_id' => $paiementId,
                    'facture_id' => $imp['factureId'],
                    'montant' => $imp['montant'],
                ]);

                $facture->increment('montant_paye', $imp['montant']);

                $updated = Facture::findOrFail($imp['factureId']);
                $totalPaye = (float) $updated->montant_paye;
                $totalTtc = (float) $updated->montant_ttc;

                if ($totalPaye >= $totalTtc) {
                    $nouveauStatut = StatutFacture::Payee->value;
                } elseif ($totalPaye > 0) {
                    $nouveauStatut = StatutFacture::PartiellementPayee->value;
                } else {
                    $nouveauStatut = StatutFacture::Emise->value;
                }

                $client = Client::findOrFail($updated->client_id);
                $nouveauSolde = (float) $client->solde - $imp['montant'];
                MouvementSoldeClient::create([
                    'client_id' => $updated->client_id,
                    'type_mouvement' => 'paiement',
                    'montant' => -$imp['montant'],
                    'reference' => "paiement:{$paiementId}",
                    'solde_avant' => $client->solde,
                    'solde_apres' => $nouveauSolde,
                ]);
                $client->update(['solde' => $nouveauSolde]);

                $updated->update(['statut' => $nouveauStatut]);
            }
        });
    }
}
