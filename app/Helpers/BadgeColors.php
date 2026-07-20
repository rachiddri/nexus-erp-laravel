<?php

namespace App\Helpers;

/**
 * Résolution centralisée de la couleur des badges (colonnes statut/type/…).
 * Mappe la valeur (string) d'un enum vers une couleur Filament.
 */
class BadgeColors
{
    /** @var array<string, string> */
    private static array $map = [
        // neutres / brouillons
        'devis' => 'gray',
        'brouillon' => 'gray',
        'nouveau' => 'gray',
        'ouvert' => 'gray',
        'planifiee' => 'gray',
        'en_attente' => 'gray',
        // info / validé / émis
        'confirmee' => 'info',
        'validee' => 'info',
        'emise' => 'info',
        'traite' => 'info',
        'automatique' => 'info',
        'manual' => 'info',
        'reutilisation' => 'info',
        'immediat' => 'info',
        // warning / en cours
        'en_cours' => 'warning',
        'en_production' => 'warning',
        'partiellement_livree' => 'warning',
        'partiellement_payee' => 'warning',
        'reserve' => 'warning',
        'retourne' => 'warning',
        'replanish' => 'warning',
        'replanifie' => 'warning',
        'a_terme' => 'warning',
        'leger' => 'warning',
        'legere' => 'warning',
        'moyenne' => 'warning',
        // success / terminé
        'livree' => 'success',
        'payee' => 'success',
        'stocke' => 'success',
        'disponible' => 'success',
        'ferme' => 'success',
        'resolu' => 'success',
        'accepte' => 'success',
        'vendu' => 'success',
        // danger / annulé / défaut
        'annulee' => 'danger',
        'refusee' => 'danger',
        'defectueux' => 'danger',
        'rebut' => 'danger',
        'annule' => 'danger',
        'bloquante' => 'danger',
        'critique' => 'danger',
        'grave' => 'danger',
        // catégories de documents (type)
        'bon_livraison' => 'info',
        'bon_enlevement' => 'indigo',
        'facture' => 'success',
        'avoir' => 'danger',
        'virement' => 'info',
        'cheque' => 'warning',
        'especes' => 'success',
        'effet' => 'warning',
        'carte' => 'indigo',
        'traite' => 'warning',
        'anticipe' => 'success',
    ];

    /**
     * @param  \BackedEnum|string|null  $state
     */
    public static function for(mixed $state): string
    {
        if ($state === null) {
            return 'gray';
        }

        if ($state instanceof \BackedEnum) {
            $value = $state->value;
        } elseif (is_object($state) && method_exists($state, 'value')) {
            $value = $state->value;
        } elseif (is_string($state) || is_int($state)) {
            $value = (string) $state;
        } else {
            return 'gray';
        }

        return self::$map[$value] ?? 'gray';
    }
}
