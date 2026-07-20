<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum StatutInventaire: string implements HasLabel
{
    case EnCours = 'en_cours';
    case Valide = 'valide';
    case Annule = 'annule';

    public function getLabel(): string
    {
        return match ($this) {
            self::EnCours => 'En cours',
            self::Valide => 'Validé',
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
