<?php

namespace App\Filament\Resources\MatierePremieres\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MatierePremiereForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('unite')
                    ->required()
                    ->default('unite'),
                TextInput::make('cout_unitaire_moyen')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('cout_unitaire_actuel')
                    ->numeric()
                    ->default(null),
                TextInput::make('stock_alerte_min')
                    ->numeric()
                    ->default(0.0),
                Textarea::make('fiche_technique')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('actif')
                    ->required(),
            ]);
    }
}
