<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum TypeDocumentSortie: string implements HasLabel
{
    case Livraison = 'livraison';
    case Enlevement = 'enlevement';

    public function getLabel(): string
    {
        return match ($this) {
            self::Livraison => 'Livraison',
            self::Enlevement => 'Enlèvement',
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
