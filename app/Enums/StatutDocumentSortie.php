<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum StatutDocumentSortie: string implements HasLabel
{
    case Prepare = 'prepare';
    case Effectue = 'effectue';
    case Annule = 'annule';

    public function getLabel(): string
    {
        return match ($this) {
            self::Prepare => 'Préparé',
            self::Effectue => 'Effectué',
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
