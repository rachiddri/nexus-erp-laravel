<?php

namespace App\Filament\Resources\DocumentSortieLignes\Pages;

use App\Filament\Resources\DocumentSortieLignes\DocumentSortieLigneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentSortieLignes extends ListRecords
{
    protected static string $resource = DocumentSortieLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
