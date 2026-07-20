<?php

namespace App\Filament\Resources\BonCommandeLignes\Pages;

use App\Filament\Resources\BonCommandeLignes\BonCommandeLigneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBonCommandeLignes extends ListRecords
{
    protected static string $resource = BonCommandeLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
