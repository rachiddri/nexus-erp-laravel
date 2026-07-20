<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum DecisionRetour: string implements HasLabel
{
    case RemiseEnStock = 'remise_en_stock';
    case Rebut = 'rebut';
    case Retouche = 'retouche';

    public function getLabel(): string
    {
        return match ($this) {
            self::RemiseEnStock => 'Remise en stock',
            self::Rebut => 'Rebut',
            self::Retouche => 'Retouche',
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
