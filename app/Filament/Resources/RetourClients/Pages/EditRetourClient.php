<?php

namespace App\Filament\Resources\RetourClients\Pages;

use App\Filament\Resources\RetourClients\RetourClientResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRetourClient extends EditRecord
{
    protected static string $resource = RetourClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
