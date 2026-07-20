<?php

namespace App\Filament\Resources\MouvementSoldeClients\Pages;

use App\Filament\Resources\MouvementSoldeClients\MouvementSoldeClientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMouvementSoldeClients extends ListRecords
{
    protected static string $resource = MouvementSoldeClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
