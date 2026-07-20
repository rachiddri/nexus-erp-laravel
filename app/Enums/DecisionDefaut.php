<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum DecisionDefaut: string implements HasLabel
{
    case EnAttente = 'en_attente';
    case Rebut = 'rebut';
    case Retouche = 'retouche';
    case Declasse = 'declasse';

    public function getLabel(): string
    {
        return match ($this) {
            self::EnAttente => 'En attente',
            self::Rebut => 'Rebut',
            self::Retouche => 'Retouche',
            self::Declasse => 'Déclasse',
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
