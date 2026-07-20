<?php

namespace App\Filament\Resources\OrdreProductionLignes\Pages;

use App\Filament\Resources\OrdreProductionLignes\OrdreProductionLigneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrdreProductionLigne extends EditRecord
{
    protected static string $resource = OrdreProductionLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
