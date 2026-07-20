<?php

namespace App\Filament\Resources\Emplacements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EmplacementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('hangar_id')
                    ->relationship('hangar', 'nom')
                    ->required(),
                TextInput::make('code_emplacement')
                    ->required(),
                TextInput::make('emplacement_able_type')
                    ->default(null),
                TextInput::make('emplacement_able_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('zone')
                    ->default(null),
                TextInput::make('capacite_max')
                    ->numeric()
                    ->default(null),
                Toggle::make('actif')
                    ->required(),
            ]);
    }
}
