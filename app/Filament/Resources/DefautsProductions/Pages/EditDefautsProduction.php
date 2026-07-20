<?php

namespace App\Filament\Resources\DefautsProductions\Pages;

use App\Filament\Resources\DefautsProductions\DefautsProductionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDefautsProduction extends EditRecord
{
    protected static string $resource = DefautsProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
