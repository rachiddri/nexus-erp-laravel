<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum EtatProduit: string implements HasLabel
{
    case Defectueux = 'defectueux';
    case Conforme = 'conforme';
    case Rebut = 'rebut';

    public function getLabel(): string
    {
        return match ($this) {
            self::Defectueux => 'Défectueux',
            self::Conforme => 'Conforme',
            self::Rebut => 'Rebut',
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
