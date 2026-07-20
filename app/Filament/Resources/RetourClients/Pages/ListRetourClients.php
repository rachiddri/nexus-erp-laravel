<?php

namespace App\Filament\Resources\RetourClients\Pages;

use App\Filament\Resources\RetourClients\RetourClientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRetourClients extends ListRecords
{
    protected static string $resource = RetourClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
