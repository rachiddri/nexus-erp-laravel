<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum StatutOrdreProduction: string implements HasLabel
{
    case Brouillon = 'brouillon';
    case Confirme = 'confirme';
    case EnCours = 'en_cours';
    case Termine = 'termine';
    case Annule = 'annule';

    public function getLabel(): string
    {
        return match ($this) {
            self::Brouillon => 'Brouillon',
            self::Confirme => 'Confirmé',
            self::EnCours => 'En cours',
            self::Termine => 'Terminé',
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
