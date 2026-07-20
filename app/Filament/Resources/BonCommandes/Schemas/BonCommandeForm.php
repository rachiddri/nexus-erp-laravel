<?php

namespace App\Filament\Resources\BonCommandes\Schemas;

use Filament\Forms\Components\DatePicker;
use App\Enums\StatutBonCommande;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BonCommandeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero_bc')
                    ->required(),
                Select::make('client_id')
                    ->relationship('client', 'raison_sociale')
                    ->required(),
                DatePicker::make('date_commande')
                    ->required(),
                DatePicker::make('date_livraison_souhaitee'),
                Textarea::make('adresse_livraison')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('statut')->options(\App\Enums\StatutBonCommande::options())
                    ->required()
                    ->default('brouillon'),
                TextInput::make('montant_total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('montant_ht')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('montant_ttc')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('remise_globale')
                    ->numeric()
                    ->default(0.0),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('approuve_prix_plancher_par')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('approuve_prix_plancher_le'),
                TextInput::make('cree_par')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
