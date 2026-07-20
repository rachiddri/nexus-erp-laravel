<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum StatutPaiement: string implements HasLabel
{
    case EnAttente = 'en_attente';
    case Encaisse = 'encaisse';
    case Rejete = 'rejete';
    case Annule = 'annule';

    public function getLabel(): string
    {
        return match ($this) {
            self::EnAttente => 'En attente',
            self::Encaisse => 'Encaissé',
            self::Rejete => 'Rejeté',
            self::Annule => 'Annulé',
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
