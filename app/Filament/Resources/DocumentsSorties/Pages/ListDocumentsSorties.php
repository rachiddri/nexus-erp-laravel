<?php

namespace App\Filament\Resources\DocumentsSorties\Pages;

use App\Filament\Resources\DocumentsSorties\DocumentsSortieResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentsSorties extends ListRecords
{
    protected static string $resource = DocumentsSortieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
