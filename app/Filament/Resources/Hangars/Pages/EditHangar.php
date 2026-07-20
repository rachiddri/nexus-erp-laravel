<?php

namespace App\Filament\Resources\Hangars\Pages;

use App\Filament\Resources\Hangars\HangarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHangar extends EditRecord
{
    protected static string $resource = HangarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
