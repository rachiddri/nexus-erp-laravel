<?php

namespace App\Filament\Resources\LotProduits\Pages;

use App\Filament\Resources\LotProduits\LotProduitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLotProduits extends ListRecords
{
    protected static string $resource = LotProduitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
