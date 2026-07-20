<?php

namespace App\Filament\Resources\LotConsommationMatieres\Pages;

use App\Filament\Resources\LotConsommationMatieres\LotConsommationMatiereResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLotConsommationMatieres extends ListRecords
{
    protected static string $resource = LotConsommationMatiereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
