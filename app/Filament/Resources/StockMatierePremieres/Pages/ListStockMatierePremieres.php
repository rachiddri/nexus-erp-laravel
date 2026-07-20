<?php

namespace App\Filament\Resources\StockMatierePremieres\Pages;

use App\Filament\Resources\StockMatierePremieres\StockMatierePremiereResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockMatierePremieres extends ListRecords
{
    protected static string $resource = StockMatierePremiereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
