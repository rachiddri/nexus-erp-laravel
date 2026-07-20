<?php

namespace App\Observers;

use App\Models\Client;

/**
 * ClientObserver — valeurs par défaut et garde-fous côté client.
 */
class ClientObserver
{
    public function creating(Client $client): void
    {
        if ($client->actif === null) {
            $client->actif = true;
        }
        if ($client->plafond_credit === null) {
            $client->plafond_credit = 0;
        }
        if ($client->solde === null) {
            $client->solde = 0;
        }
    }
}
