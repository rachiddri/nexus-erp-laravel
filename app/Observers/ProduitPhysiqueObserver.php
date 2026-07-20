<?php

namespace App\Observers;

use App\Models\ProduitPhysique;

/**
 * ProduitPhysiqueObserver — valeurs par défaut à la création d'une unité physiques.
 */
class ProduitPhysiqueObserver
{
    public function creating(ProduitPhysique $pp): void
    {
        if ($pp->statut === null) {
            $pp->statut = 'en_production';
        }
        if ($pp->date_creation === null) {
            $pp->date_creation = now();
        }
    }
}
