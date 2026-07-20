<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum OrigineOrdreProduction: string implements HasLabel
{
    case Commande = 'commande';
    case Stock = 'stock';

    public function getLabel(): string
    {
        return match ($this) {
            self::Commande => 'Commande',
            self::Stock => 'Stock',
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
