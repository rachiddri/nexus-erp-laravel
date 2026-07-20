<?php

namespace App\Filament\Resources\Lots\Schemas;

use Filament\Forms\Components\DateTimePicker;
use App\Enums\StatutLot;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero_lot')
                    ->required(),
                Select::make('ordre_production_id')
                    ->relationship('ordreProduction', 'numero_op')
                    ->required(),
                Select::make('statut')->options(\App\Enums\StatutLot::options())
                    ->required()
                    ->default('en_cours'),
                DateTimePicker::make('date_ouverture')
                    ->required(),
                DateTimePicker::make('date_cloture'),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
