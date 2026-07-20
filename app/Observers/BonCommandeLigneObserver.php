<?php

namespace App\Observers;

use App\Models\BonCommandeLigne;

/**
 * BonCommandeLigneObserver — recalcule le bon de commande parent
 * quand une ligne change (corrige le bug de timing du BonCommandeObserver).
 */
class BonCommandeLigneObserver
{
    public function saved(BonCommandeLigne $ligne): void
    {
        $this->recalcParent($ligne);
    }

    public function deleted(BonCommandeLigne $ligne): void
    {
        $this->recalcParent($ligne);
    }

    private function recalcParent(BonCommandeLigne $ligne): void
    {
        if ($ligne->bon_commande_id) {
            $bc = \App\Models\BonCommande::find($ligne->bon_commande_id);
            if ($bc) {
                BonCommandeObserver::recompute($bc);
            }
        }
    }
}
