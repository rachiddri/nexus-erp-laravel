<?php

namespace App\Filament\Resources\DocumentsSorties\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DocumentsSortieForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero')
                    ->required(),
                TextInput::make('type')
                    ->required()
                    ->default('livraison'),
                Select::make('client_id')
                    ->relationship('client', 'id')
                    ->required(),
                Select::make('bon_commande_id')
                    ->relationship('bonCommande', 'id')
                    ->required(),
                DatePicker::make('date_sortie')
                    ->required(),
                Textarea::make('adresse_livraison')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('statut')
                    ->required()
                    ->default('brouillon'),
                TextInput::make('valide_par')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('valide_le'),
                TextInput::make('cree_par')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
