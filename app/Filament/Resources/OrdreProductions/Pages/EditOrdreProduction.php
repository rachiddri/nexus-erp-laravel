<?php

namespace App\Filament\Resources\OrdreProductions\Pages;

use App\Filament\Resources\OrdreProductions\OrdreProductionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrdreProduction extends EditRecord
{
    protected static string $resource = OrdreProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
