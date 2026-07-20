<?php

namespace App\Observers;

use App\Models\BonCommande;

/**
 * BonCommandeObserver — recalcul automatique des montants depuis les lignes.
 * Se déclenche quel que soit le point d'entrée (Filament, API, service).
 */
class BonCommandeObserver
{
    public function saved(BonCommande $bc): void
    {
        $ht = (float) $bc->bonCommandeLignes()->sum('montant_total');
        if ((float) $bc->montant_ht !== $ht || (float) $bc->montant_ttc !== $ht) {
            $bc->montant_ht = $ht;
            $bc->montant_ttc = $ht;
            $bc->saveQuietly();
        }
    }
}
