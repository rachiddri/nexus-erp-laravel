<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum TypeInventaire: string implements HasLabel
{
    case Matiere = 'matiere';
    case ProduitFini = 'produit_fini';

    public function getLabel(): string
    {
        return match ($this) {
            self::Matiere => 'Matière',
            self::ProduitFini => 'Produit fini',
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
