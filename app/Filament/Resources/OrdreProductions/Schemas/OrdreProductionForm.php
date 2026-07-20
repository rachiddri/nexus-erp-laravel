<?php

namespace App\Filament\Resources\OrdreProductions\Schemas;

use Filament\Forms\Components\DatePicker;
use App\Enums\StatutOrdreProduction;
use App\Enums\OrigineOrdreProduction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrdreProductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero_op')
                    ->required(),
                Select::make('bon_commande_id')
                    ->relationship('bonCommande', 'numero_bc')
                    ->default(null),
                Select::make('depot_matiere_id')
                    ->relationship('depotMatiere', 'nom')
                    ->default(null),
                DatePicker::make('date_lancement'),
                DatePicker::make('date_prevue_fin'),
                Select::make('statut')->options(\App\Enums\StatutOrdreProduction::options())
                    ->required()
                    ->default('brouillon'),
                TextInput::make('priorite')
                    ->default('normale'),
                Select::make('origine')->options(\App\Enums\OrigineOrdreProduction::options())
                    ->required()
                    ->default('stock'),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
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
