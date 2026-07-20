<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum GraviteDefaut: string implements HasLabel
{
    case Mineur = 'mineur';
    case Majeur = 'majeur';
    case Critique = 'critique';

    public function getLabel(): string
    {
        return match ($this) {
            self::Mineur => 'Mineur',
            self::Majeur => 'Majeur',
            self::Critique => 'Critique',
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
