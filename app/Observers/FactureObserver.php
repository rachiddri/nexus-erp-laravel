<?php

namespace App\Observers;

use App\Models\Facture;

/**
 * FactureObserver — recalcul automatique et conforme des montants.
 *
 * Règle comptable : les lignes sont HT (sans TVA).
 *   HT    = somme des montants des lignes
 *   TVA   = HT × taux_tva / 100   (taux éditable, défaut 19 %)
 *   TTC   = HT + TVA
 *   TTC en lettres = montant TTC converti en toutes lettres
 */
class FactureObserver
{
    public function saved(Facture $facture): void
    {
        self::recompute($facture);
    }

    public static function recompute(Facture $facture): void
    {
        // Pas encore de lignes (création en deux temps) : on conserve les valeurs fournies.
        if (! $facture->factureLignes()->exists()) {
            return;
        }

        $ht = (float) $facture->factureLignes()->sum('montant_total');
        $taux = (float) ($facture->taux_tva ?? 19);
        $tva = $ht * $taux / 100;
        $ttc = $ht + $tva;
        $lettres = \App\Helpers\NumberToWords::enLettres($ttc);

        if (
            (float) $facture->montant_ht !== $ht ||
            (float) $facture->montant_tva !== $tva ||
            (float) $facture->montant_ttc !== $ttc ||
            (string) $facture->montant_ttc_lettres !== (string) $lettres
        ) {
            $facture->montant_ht = $ht;
            $facture->montant_tva = $tva;
            $facture->montant_ttc = $ttc;
            $facture->montant_ttc_lettres = $lettres;
            $facture->saveQuietly();
        }
    }
}
