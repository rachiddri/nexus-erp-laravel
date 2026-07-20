<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum StatutDefaut: string implements HasLabel
{
    case Ouvert = 'ouvert';
    case Traite = 'traite';

    public function getLabel(): string
    {
        return match ($this) {
            self::Ouvert => 'Ouvert',
            self::Traite => 'Traité',
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
