<?php

namespace App\Filament\Resources\MouvementStockMatieres\Pages;

use App\Filament\Resources\MouvementStockMatieres\MouvementStockMatiereResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMouvementStockMatieres extends ListRecords
{
    protected static string $resource = MouvementStockMatiereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
