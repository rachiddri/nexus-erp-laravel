<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum MotifAvoir: string implements HasLabel
{
    case Annulation = 'annulation';
    case RetourClient = 'retour_client';
    case ErreurFacturation = 'erreur_facturation';
    case GesteCommercial = 'geste_commercial';

    public function getLabel(): string
    {
        return match ($this) {
            self::Annulation => 'Annulation',
            self::RetourClient => 'Retour client',
            self::ErreurFacturation => 'Erreur de facturation',
            self::GesteCommercial => 'Geste commercial',
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
