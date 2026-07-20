<?php

namespace App\Filament\Resources\DefautsProductions\Pages;

use App\Filament\Resources\DefautsProductions\DefautsProductionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDefautsProductions extends ListRecords
{
    protected static string $resource = DefautsProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
