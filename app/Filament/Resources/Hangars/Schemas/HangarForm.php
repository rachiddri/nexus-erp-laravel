<?php

namespace App\Filament\Resources\Hangars\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HangarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('depot_id')
                    ->relationship('depot', 'nom')
                    ->required(),
                TextInput::make('nom')
                    ->required(),
                Select::make('responsable_id')
                    ->relationship('responsable', 'name')
                    ->default(null),
                Toggle::make('actif')
                    ->required(),
            ]);
    }
}
