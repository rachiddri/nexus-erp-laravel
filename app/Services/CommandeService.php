<?php

namespace App\Services;

use App\Enums\StatutBonCommande;
use App\Models\BonCommande;
use App\Models\BonCommandeLigne;
use App\Models\Client;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class CommandeValidationError extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->name = 'CommandeValidationError';
    }
}

/**
 * CommandeService — Cycle de vie des bons de commande (§4.4, F2).
 */
class CommandeService
{
    /**
     * Crée un devis / bon de commande.
     * @param array{clientId:int, commercialId:int, lignes:array, notes?:string} $data
     */
    public static function creerDevis(array $data): BonCommande
    {
        if (empty($data['lignes'])) {
            throw new CommandeValidationError('Au moins une ligne requise');
        }

        return DB::transaction(function () use ($data) {
            $annee = (int) now()->format('Y');
            $n = SequenceService::prochainNumero(\App\Services\PrefixeDocument::BC, $annee);
            $numeroBc = SequenceService::formatNumero('bc', $annee, $n);

            $montantHt = 0.0;
            $lignesData = [];
            foreach ($data['lignes'] as $l) {
                $ligneHt = (float) $l['quantite'] * (float) $l['prixUnitaire'];
                $montantHt += $ligneHt;
                $lignesData[] = [
                    'produit_id' => $l['produitId'],
                    'quantite' => $l['quantite'],
                    'prix_unitaire' => $l['prixUnitaire'],
                    'montant_total' => $ligneHt,
                ];
            }

            $bc = BonCommande::create([
                'numero_bc' => $numeroBc,
                'client_id' => $data['clientId'],
                'cree_par' => $data['commercialId'],
                'date_commande' => now(),
                'statut' => StatutBonCommande::Devis->value,
                'montant_ht' => $montantHt,
                'montant_ttc' => $montantHt,
                'notes' => $data['notes'] ?? null,
            ]);
            $bc->bonCommandeLignes()->createMany($lignesData);

            return $bc->load('bonCommandeLignes.produit');
        });
    }

    /**
     * Confirme un devis/BC (devis/brouillon → confirmee) avec contrôles.
     */
    public static function confirmerCommande(int $bonCommandeId, int $userId): BonCommande
    {
        return DB::transaction(function () use ($bonCommandeId, $userId) {
            $bc = BonCommande::with(['client', 'lignes.produit'])->findOrFail($bonCommandeId);

            if (! in_array($bc->statut, [StatutBonCommande::Devis->value, StatutBonCommande::Brouillon->value])) {
                throw new CommandeValidationError("Impossible de confirmer un BC au statut \"{$bc->statut}\"");
            }

            if (! $bc->client->actif) {
                throw new CommandeValidationError('Client bloqué — impossible de confirmer la commande');
            }

            if ($bc->client->plafond_credit > 0) {
                $nouveauSolde = (float) $bc->client->solde + (float) $bc->montant_ttc;
                if ($nouveauSolde > (float) $bc->client->plafond_credit) {
                    throw new CommandeValidationError(
                        "Plafond de crédit dépassé ({$bc->client->plafond_credit}). Solde après commande: $nouveauSolde"
                    );
                }
            }

            $lignesSousPrix = $bc->lignes->filter(
                fn ($l) => $l->produit->prix_vente && (float) $l->prix_unitaire < (float) $l->produit->prix_vente
            );
            if ($lignesSousPrix->count() > 0 && ! $bc->approuve_prix_plancher_par) {
                throw new CommandeValidationError('Des lignes sont sous le prix plancher — approbation requise');
            }

            $bc->update(['statut' => StatutBonCommande::Confirmee->value]);

            return $bc->load('lignes', 'client');
        });
    }

    /**
     * Annule une commande (si aucun document de sortie actif).
     */
    public static function annulerCommande(int $bonCommandeId, string $motif, int $userId): BonCommande
    {
        return DB::transaction(function () use ($bonCommandeId, $motif, $userId) {
            $bc = BonCommande::with(['lignes', 'documentsSorties' => fn ($q) => $q->where('statut', '!=', 'annule')])
                ->findOrFail($bonCommandeId);

            if ($bc->documentsSorties->count() > 0) {
                throw new CommandeValidationError('Impossible d\'annuler : des documents de sortie sont en cours');
            }

            $bc->update(['statut' => StatutBonCommande::Annulee->value]);

            return $bc;
        });
    }

    /**
     * Met à jour le statut dérivé d'un BC (conservé à confirmee côté BC).
     */
    public static function recalculerStatutCommande(int $bonCommandeId): BonCommande
    {
        $bc = BonCommande::with('lignes')->findOrFail($bonCommandeId);

        return $bc;
    }

    /**
     * Approuve les prix plancher pour une commande.
     */
    public static function approuverPrixPlancher(int $bonCommandeId, int $userId): BonCommande
    {
        return BonCommande::where('id', $bonCommandeId)
            ->update(['approuve_prix_plancher_par' => $userId]);
    }
}
