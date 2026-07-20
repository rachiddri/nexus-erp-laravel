<?php

namespace App\Filament\Resources\StockMatierePremieres\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockMatierePremiereForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('matiere_premiere_id')
                    ->relationship('matierePremiere', 'nom')
                    ->required(),
                Select::make('depot_id')
                    ->relationship('depot', 'nom')
                    ->required(),
                TextInput::make('quantite_disponible')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('quantite_reservee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
