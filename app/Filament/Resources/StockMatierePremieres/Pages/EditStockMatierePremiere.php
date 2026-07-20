<?php

namespace App\Filament\Resources\StockMatierePremieres\Pages;

use App\Filament\Resources\StockMatierePremieres\StockMatierePremiereResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockMatierePremiere extends EditRecord
{
    protected static string $resource = StockMatierePremiereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
