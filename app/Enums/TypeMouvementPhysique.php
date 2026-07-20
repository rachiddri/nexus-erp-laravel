<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum TypeMouvementPhysique: string implements HasLabel
{
    case PassageEtape = 'passage_etape';
    case TransfertDepot = 'transfert_depot';
    case Reservation = 'reservation';
    case Livraison = 'livraison';
    case Retour = 'retour';
    case Defaut = 'defaut';
    case Annulation = 'annulation';

    public function getLabel(): string
    {
        return match ($this) {
            self::PassageEtape => 'Passage étape',
            self::TransfertDepot => 'Transfert dépôt',
            self::Reservation => 'Réservation',
            self::Livraison => 'Livraison',
            self::Retour => 'Retour',
            self::Defaut => 'Défaut',
            self::Annulation => 'Annulation',
        };
    }

    /** @return array<string,string> */
    public static function options(): array
    {
        return Collection::make(self::cases())
            ->mapWithKeys(fn (self $c) => [$c->value => $c->getLabel()])
            ->all();
    }
}
