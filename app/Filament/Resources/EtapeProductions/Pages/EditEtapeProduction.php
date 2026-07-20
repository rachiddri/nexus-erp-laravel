<?php

namespace App\Filament\Resources\EtapeProductions\Pages;

use App\Filament\Resources\EtapeProductions\EtapeProductionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEtapeProduction extends EditRecord
{
    protected static string $resource = EtapeProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
