<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum StatutClient: string implements HasLabel
{
    case Actif = 'actif';
    case Bloque = 'bloque';

    public function getLabel(): string
    {
        return match ($this) {
            self::Actif => 'Actif',
            self::Bloque => 'Bloqué',
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
