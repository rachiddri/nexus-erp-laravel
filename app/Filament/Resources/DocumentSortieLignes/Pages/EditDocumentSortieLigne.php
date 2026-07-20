<?php

namespace App\Filament\Resources\DocumentSortieLignes\Pages;

use App\Filament\Resources\DocumentSortieLignes\DocumentSortieLigneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentSortieLigne extends EditRecord
{
    protected static string $resource = DocumentSortieLigneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
