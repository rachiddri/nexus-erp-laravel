<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum StatutProduitPhysique: string implements HasLabel
{
    case EnProduction = 'en_production';
    case Disponible = 'disponible';
    case Reserve = 'reserve';
    case Vendu = 'vendu';
    case Livre = 'livre';
    case Defectueux = 'defectueux';
    case Rebut = 'rebut';
    case Retourne = 'retourne';
    case Annule = 'annule';

    public function getLabel(): string
    {
        return match ($this) {
            self::EnProduction => 'En production',
            self::Disponible => 'Disponible',
            self::Reserve => 'Réservé',
            self::Vendu => 'Vendu',
            self::Livre => 'Livré',
            self::Defectueux => 'Défectueux',
            self::Rebut => 'Rebut',
            self::Retourne => 'Retourné',
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
