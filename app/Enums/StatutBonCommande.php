<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum StatutBonCommande: string implements HasLabel
{
    case Devis = 'devis';
    case Brouillon = 'brouillon';
    case Confirmee = 'confirmee';
    case EnProduction = 'en_production';
    case PartiellementPrete = 'partiellement_prete';
    case Prete = 'prete';
    case PartiellementLivree = 'partiellement_livree';
    case Livree = 'livree';
    case Annulee = 'annulee';

    public function getLabel(): string
    {
        return match ($this) {
            self::Devis => 'Devis',
            self::Brouillon => 'Brouillon',
            self::Confirmee => 'Confirmée',
            self::EnProduction => 'En production',
            self::PartiellementPrete => 'Partiellement prête',
            self::Prete => 'Prête',
            self::PartiellementLivree => 'Partiellement livrée',
            self::Livree => 'Livrée',
            self::Annulee => 'Annulée',
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
