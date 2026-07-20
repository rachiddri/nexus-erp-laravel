<?php

namespace App\Filament\Resources\Inventaires\Schemas;

use Filament\Forms\Components\DatePicker;
use App\Enums\StatutInventaire;
use App\Enums\TypeInventaire;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InventaireForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero')
                    ->required(),
                Select::make('type')->options(\App\Enums\TypeInventaire::options())
                    ->required(),
                Select::make('depot_id')
                    ->relationship('depot', 'nom')
                    ->required(),
                DatePicker::make('date_inventaire')
                    ->required(),
                Select::make('statut')->options(\App\Enums\StatutInventaire::options())
                    ->required()
                    ->default('brouillon'),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('cree_par')
                    ->numeric()
                    ->default(null),
                TextInput::make('valide_par')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('date_validation'),
            ]);
    }
}
