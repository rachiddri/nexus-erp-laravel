<?php

namespace App\Filament\Resources\MatierePremieres\Pages;

use App\Filament\Resources\MatierePremieres\MatierePremiereResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMatierePremieres extends ListRecords
{
    protected static string $resource = MatierePremiereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
