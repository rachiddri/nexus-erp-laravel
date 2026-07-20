<?php

namespace App\Filament\Resources\Hangars\Pages;

use App\Filament\Resources\Hangars\HangarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHangars extends ListRecords
{
    protected static string $resource = HangarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
