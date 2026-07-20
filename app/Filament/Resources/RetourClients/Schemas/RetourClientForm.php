<?php

namespace App\Filament\Resources\RetourClients\Schemas;

use Filament\Forms\Components\DatePicker;
use App\Enums\StatutRetour;
use App\Enums\DecisionRetour;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RetourClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('client_id')
                    ->relationship('client', 'raison_sociale')
                    ->required(),
                DatePicker::make('date_retour')
                    ->required(),
                Textarea::make('motif_global')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('decision')->options(\App\Enums\DecisionRetour::options())
                    ->default(null),
                Select::make('statut')->options(\App\Enums\StatutRetour::options())
                    ->required()
                    ->default('ouvert'),
                Select::make('document_sortie_id')
                    ->relationship('documentSortie', 'id')
                    ->default(null),
                Textarea::make('motif_rejet')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('cree_par')
                    ->numeric()
                    ->default(null),
                TextInput::make('traite_par')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('traite_le'),
            ]);
    }
}
