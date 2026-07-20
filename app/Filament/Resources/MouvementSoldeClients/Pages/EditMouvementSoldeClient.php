<?php

namespace App\Filament\Resources\MouvementSoldeClients\Pages;

use App\Filament\Resources\MouvementSoldeClients\MouvementSoldeClientResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMouvementSoldeClient extends EditRecord
{
    protected static string $resource = MouvementSoldeClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
