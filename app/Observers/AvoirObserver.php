<?php

namespace App\Observers;

use App\Models\Avoir;

/**
 * AvoirObserver — recalcul automatique et conforme des montants.
 *
 * Même principe que FactureObserver :
 *   HT    = somme des montants des lignes (HT, sans TVA)
 *   TVA   = HT × taux_tva / 100   (taux éditable, défaut 19 %)
 *   TTC   = HT + TVA
 *   TTC en lettres = montant TTC converti en toutes lettres
 */
class AvoirObserver
{
    public function saved(Avoir $avoir): void
    {
        self::recompute($avoir);
    }

    public static function recompute(Avoir $avoir): void
    {
        if (! $avoir->avoirLignes()->exists()) {
            return;
        }

        $ht = (float) $avoir->avoirLignes()->sum('montant_total');
        $taux = (float) ($avoir->taux_tva ?? 19);
        $tva = $ht * $taux / 100;
        $ttc = $ht + $tva;
        $lettres = \App\Helpers\NumberToWords::enLettres($ttc);

        if (
            (float) $avoir->montant_ht !== $ht ||
            (float) $avoir->montant_tva !== $tva ||
            (float) $avoir->montant_ttc !== $ttc ||
            (string) $avoir->montant_ttc_lettres !== (string) $lettres
        ) {
            $avoir->montant_ht = $ht;
            $avoir->montant_tva = $tva;
            $avoir->montant_ttc = $ttc;
            $avoir->montant_ttc_lettres = $lettres;
            $avoir->saveQuietly();
        }
    }
}
