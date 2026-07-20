<?php

namespace App\Filament\Resources\DefautsProductions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use App\Enums\StatutDefaut;
use App\Enums\GraviteDefaut;
use App\Enums\DecisionDefaut;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DefautsProductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lot_produit_id')
                    ->relationship('lotProduit', 'id')
                    ->required(),
                Select::make('etape_production_id')
                    ->relationship('etapeProduction', 'nom')
                    ->required(),
                TextInput::make('type_defaut')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('gravite')->options(\App\Enums\GraviteDefaut::options())
                    ->required()
                    ->default('mineur'),
                TextInput::make('quantite_impactee')
                    ->required()
                    ->numeric()
                    ->default(1),
                Textarea::make('cause_racine')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('action_immediate')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('decision')->options(\App\Enums\DecisionDefaut::options())
                    ->default(null),
                Select::make('statut')->options(\App\Enums\StatutDefaut::options())
                    ->required()
                    ->default('ouvert'),
                TextInput::make('signale_par')
                    ->numeric()
                    ->default(null),
                TextInput::make('resolu_par')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('date_resolution'),
            ]);
    }
}
