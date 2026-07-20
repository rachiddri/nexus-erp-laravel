<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum TypeDepot: string implements HasLabel
{
    case Matiere = 'matiere';
    case ProduitFini = 'produit_fini';
    case Mixte = 'mixte';

    public function getLabel(): string
    {
        return match ($this) {
            self::Matiere => 'Matière',
            self::ProduitFini => 'Produit fini',
            self::Mixte => 'Mixte',
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
