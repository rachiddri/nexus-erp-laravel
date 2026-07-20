<?php

namespace App\Filament\Resources\DocumentsSorties\Pages;

use App\Filament\Resources\DocumentsSorties\DocumentsSortieResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentsSortie extends EditRecord
{
    protected static string $resource = DocumentsSortieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
