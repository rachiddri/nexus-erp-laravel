<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum ModePaiement: string implements HasLabel
{
    case Espece = 'espece';
    case Cheque = 'cheque';
    case Virement = 'virement';
    case Traite = 'traite';

    public function getLabel(): string
    {
        return match ($this) {
            self::Espece => 'Espèce',
            self::Cheque => 'Chèque',
            self::Virement => 'Virement',
            self::Traite => 'Traite',
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
