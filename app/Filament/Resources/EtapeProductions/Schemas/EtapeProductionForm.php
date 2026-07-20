<?php

namespace App\Filament\Resources\EtapeProductions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EtapeProductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->required(),
                TextInput::make('ordre')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('type_controle')
                    ->default(null),
                TextInput::make('seuil_conformite')
                    ->numeric()
                    ->default(null),
                Toggle::make('actif')
                    ->required(),
            ]);
    }
}
