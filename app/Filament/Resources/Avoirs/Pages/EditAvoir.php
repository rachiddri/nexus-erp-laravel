<?php

namespace App\Filament\Resources\Avoirs\Pages;

use App\Filament\Resources\Avoirs\AvoirResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAvoir extends EditRecord
{
    protected static string $resource = AvoirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
