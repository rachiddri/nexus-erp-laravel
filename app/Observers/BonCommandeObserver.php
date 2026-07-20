<?php

namespace App\Observers;

use App\Models\BonCommande;

/**
 * BonCommandeObserver — recalcul automatique des montants depuis les lignes.
 *
 * Le bon de commande est HT (sans TVA) : montant_ht = montant_ttc = montant_total
 * = somme des montants des lignes.
 *
 * Garde-fou : si le bon n'a pas encore de lignes (création en deux temps, ou
 * creerDevis qui renseigne déjà les montants en mémoire), on conserve les
 * valeurs fournies au lieu de les écraser à 0. Le recalcul définitif est assuré
 * par BonCommandeLigneObserver quand une ligne est ajoutée/modifiée/supprimée.
 */
class BonCommandeObserver
{
    public function saved(BonCommande $bc): void
    {
        self::recompute($bc);
    }

    public static function recompute(BonCommande $bc): void
    {
        if (! $bc->bonCommandeLignes()->exists()) {
            return;
        }

        $ht = (float) $bc->bonCommandeLignes()->sum('montant_total');

        if (
            (float) $bc->montant_ht !== $ht ||
            (float) $bc->montant_ttc !== $ht ||
            (float) $bc->montant_total !== $ht
        ) {
            $bc->montant_ht = $ht;
            $bc->montant_ttc = $ht;
            $bc->montant_total = $ht;
            $bc->saveQuietly();
        }
    }
}
