<?php

namespace App\Filament\Resources\Paiements\Schemas;

use Filament\Forms\Components\DatePicker;
use App\Enums\StatutPaiement;
use App\Enums\ModePaiement;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaiementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero')
                    ->required(),
                Select::make('client_id')
                    ->relationship('client', 'raison_sociale')
                    ->required(),
                TextInput::make('montant')
                    ->required()
                    ->numeric(),
                Select::make('mode_paiement')->options(\App\Enums\ModePaiement::options())
                    ->required(),
                Select::make('statut')->options(\App\Enums\StatutPaiement::options())
                    ->required()
                    ->default('en_attente'),
                DatePicker::make('date_paiement')
                    ->required(),
                TextInput::make('reference_piece')
                    ->default(null),
                Textarea::make('motif_rejet')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('saisi_par')
                    ->numeric()
                    ->default(null),
                TextInput::make('encaisse_par')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('encaisse_le'),
            ]);
    }
}
