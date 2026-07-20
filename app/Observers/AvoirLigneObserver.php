<?php

namespace App\Observers;

use App\Models\AvoirLigne;

/**
 * AvoirLigneObserver — recalcule l'avoir parent quand une ligne change.
 */
class AvoirLigneObserver
{
    public function saved(AvoirLigne $ligne): void
    {
        $this->recalcParent($ligne);
    }

    public function deleted(AvoirLigne $ligne): void
    {
        $this->recalcParent($ligne);
    }

    private function recalcParent(AvoirLigne $ligne): void
    {
        if ($ligne->avoir_id) {
            $avoir = \App\Models\Avoir::find($ligne->avoir_id);
            if ($avoir) {
                AvoirObserver::recompute($avoir);
            }
        }
    }
}
