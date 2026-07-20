<?php

namespace App\Filament\Resources\Avoirs\Pages;

use App\Filament\Resources\Avoirs\AvoirResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAvoirs extends ListRecords
{
    protected static string $resource = AvoirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
