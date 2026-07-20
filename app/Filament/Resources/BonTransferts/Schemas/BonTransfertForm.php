<?php

namespace App\Filament\Resources\BonTransferts\Schemas;

use Filament\Forms\Components\DatePicker;
use App\Enums\StatutBonTransfert;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BonTransfertForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero')
                    ->required(),
                Select::make('depot_origine_id')
                    ->relationship('depotOrigine', 'nom')
                    ->required(),
                Select::make('depot_destination_id')
                    ->relationship('depotDestination', 'nom')
                    ->required(),
                DatePicker::make('date_transfert')
                    ->required(),
                TextInput::make('motif')
                    ->default('transfert_stock'),
                Select::make('statut')->options(\App\Enums\StatutBonTransfert::options())
                    ->required()
                    ->default('brouillon'),
                TextInput::make('cree_par')
                    ->numeric()
                    ->default(null),
                TextInput::make('valide_par')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('valide_le'),
            ]);
    }
}
