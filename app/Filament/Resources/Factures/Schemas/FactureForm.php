<?php

namespace App\Filament\Resources\Factures\Schemas;

use App\Enums\ModePaiement;
use App\Enums\StatutFacture;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FactureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations')
                    ->schema([
                        Select::make('client_id')
                            ->label('Client')
                            ->relationship('client', 'raison_sociale')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('bon_commande_id')
                            ->label('Bon de commande')
                            ->relationship('bonCommande', 'numero_bc')
                            ->searchable()
                            ->preload(),
                        TextInput::make('numero_facture')
                            ->label('N° facture')
                            ->disabled()
                            ->dehydrated(false),
                        DatePicker::make('date_facture')
                            ->label('Date de facture')
                            ->required(),
                        DatePicker::make('date_echeance')
                            ->label('Date d\'échéance'),
                        Select::make('statut')
                            ->label('Statut')
                            ->options(StatutFacture::options())
                            ->required(),
                    ]),

                Section::make('Montants')
                    ->description('Calcul automatique et conforme : HT = somme des lignes, TVA = HT × taux, TTC = HT + TVA.')
                    ->schema([
                        TextInput::make('montant_ht')
                            ->label('Total HT (somme des lignes)')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('taux_tva')
                            ->label('Taux de TVA')
                            ->numeric()
                            ->required()
                            ->default(19)
                            ->suffix('%'),
                        TextInput::make('montant_tva')
                            ->label('Montant TVA')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('montant_ttc')
                            ->label('Total TTC')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('montant_ttc_lettres')
                            ->label('Total TTC en lettres')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                        Select::make('mode_reglement')
                            ->label('Mode de règlement')
                            ->options(ModePaiement::options())
                            ->default('virement')
                            ->required(),
                        TextInput::make('montant_paye')
                            ->label('Déjà payé')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('remise')
                            ->label('Remise')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }
}
