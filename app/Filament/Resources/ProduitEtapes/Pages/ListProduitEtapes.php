<?php

namespace App\Filament\Resources\ProduitEtapes\Pages;

use App\Filament\Resources\ProduitEtapes\ProduitEtapeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProduitEtapes extends ListRecords
{
    protected static string $resource = ProduitEtapeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
