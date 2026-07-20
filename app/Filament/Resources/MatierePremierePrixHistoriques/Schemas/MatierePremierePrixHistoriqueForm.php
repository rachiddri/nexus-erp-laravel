<?php

namespace App\Filament\Resources\MatierePremierePrixHistoriques\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MatierePremierePrixHistoriqueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('matiere_premiere_id')
                    ->relationship('matierePremiere', 'nom')
                    ->required(),
                TextInput::make('prix_avant')
                    ->required()
                    ->numeric(),
                TextInput::make('prix_apres')
                    ->required()
                    ->numeric(),
                TextInput::make('motif')
                    ->default(null),
                DateTimePicker::make('date_debut')
                    ->required(),
                DateTimePicker::make('date_fin'),
                Select::make('utilisateur_id')
                    ->relationship('utilisateur', 'name')
                    ->default(null),
            ]);
    }
}
