<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum StatutBonTransfert: string implements HasLabel
{
    case Prepare = 'prepare';
    case Expedie = 'expedie';
    case Recu = 'recu';
    case Annule = 'annule';

    public function getLabel(): string
    {
        return match ($this) {
            self::Prepare => 'Préparé',
            self::Expedie => 'Expédié',
            self::Recu => 'Reçu',
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
