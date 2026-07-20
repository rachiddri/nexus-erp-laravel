<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum ResultatInventairePhysique: string implements HasLabel
{
    case Present = 'present';
    case Introuvable = 'introuvable';
    case TrouveNonAttendu = 'trouve_non_attendu';

    public function getLabel(): string
    {
        return match ($this) {
            self::Present => 'Présent',
            self::Introuvable => 'Introuvable',
            self::TrouveNonAttendu => 'Trouvé non attendu',
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
