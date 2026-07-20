<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum TypeMouvementStockMatiere: string implements HasLabel
{
    case EntreeAchat = 'entree_achat';
    case SortieProduction = 'sortie_production';
    case AjustementInventaire = 'ajustement_inventaire';
    case Transfert = 'transfert';

    public function getLabel(): string
    {
        return match ($this) {
            self::EntreeAchat => 'Entrée achat',
            self::SortieProduction => 'Sortie production',
            self::AjustementInventaire => 'Ajustement inventaire',
            self::Transfert => 'Transfert',
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
