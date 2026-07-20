<?php

namespace App\Observers;

use App\Models\FactureLigne;

/**
 * FactureLigneObserver — recalcule la facture parente quand une ligne change.
 */
class FactureLigneObserver
{
    public function saved(FactureLigne $ligne): void
    {
        $this->recalcParent($ligne);
    }

    public function deleted(FactureLigne $ligne): void
    {
        $this->recalcParent($ligne);
    }

    private function recalcParent(FactureLigne $ligne): void
    {
        if ($ligne->facture_id) {
            $facture = \App\Models\Facture::find($ligne->facture_id);
            if ($facture) {
                FactureObserver::recompute($facture);
            }
        }
    }
}
