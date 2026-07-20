<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum StatutFacture: string implements HasLabel
{
    case Brouillon = 'brouillon';
    case Emise = 'emise';
    case PartiellementPayee = 'partiellement_payee';
    case Payee = 'payee';
    case AnnuleeParAvoir = 'annulee_par_avoir';

    public function getLabel(): string
    {
        return match ($this) {
            self::Brouillon => 'Brouillon',
            self::Emise => 'Émise',
            self::PartiellementPayee => 'Partiellement payée',
            self::Payee => 'Payée',
            self::AnnuleeParAvoir => 'Annulée par avoir',
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
