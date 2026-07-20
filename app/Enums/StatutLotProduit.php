<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum StatutLotProduit: string implements HasLabel
{
    case EnAttente = 'en_attente';
    case EnProduction = 'en_production';
    case Partiel = 'partiel';
    case Termine = 'termine';
    case TermineAvecRebut = 'termine_avec_rebut';
    case Rebute = 'rebute';

    public function getLabel(): string
    {
        return match ($this) {
            self::EnAttente => 'En attente',
            self::EnProduction => 'En production',
            self::Partiel => 'Partiel',
            self::Termine => 'Terminé',
            self::TermineAvecRebut => 'Terminé avec rebut',
            self::Rebute => 'Rebuté',
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
