<?php

namespace App\Helpers;

/**
 * Conversion d'un montant numérique en lettres (français / comptabilité).
 * Utilise l'extension intl (NumberFormatter SPELLOUT fr_FR).
 */
class NumberToWords
{
    public static function enLettres(float $montant): string
    {
        $fmt = new \NumberFormatter('fr_FR', \NumberFormatter::SPELLOUT);

        $arrondi = round($montant, 2);
        $entier = (int) floor($arrondi);
        $centimes = (int) round(($arrondi - $entier) * 100);

        $mots = $fmt->format($entier);

        if ($centimes > 0) {
            $mots .= ' dinars et ' . $fmt->format($centimes) . ' centimes';
        } else {
            $mots .= ' dinars';
        }

        return ucfirst($mots);
    }
}
