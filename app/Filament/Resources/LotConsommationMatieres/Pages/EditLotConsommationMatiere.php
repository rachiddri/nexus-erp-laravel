<?php

namespace App\Filament\Resources\LotConsommationMatieres\Pages;

use App\Filament\Resources\LotConsommationMatieres\LotConsommationMatiereResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLotConsommationMatiere extends EditRecord
{
    protected static string $resource = LotConsommationMatiereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
