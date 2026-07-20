<?php

namespace App\Filament\Resources\PaiementImputations\Pages;

use App\Filament\Resources\PaiementImputations\PaiementImputationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaiementImputations extends ListRecords
{
    protected static string $resource = PaiementImputationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
