<?php

namespace App\Filament\Resources\Avoirs\Schemas;

use App\Enums\StatutAvoir;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AvoirForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations')
                    ->schema([
                        TextInput::make('numero_avoir')
                            ->label('N° avoir')
                            ->required(),
                        Select::make('facture_id')
                            ->label('Facture d\'origine')
                            ->relationship('facture', 'numero_facture'),
                        Select::make('client_id')
                            ->label('Client')
                            ->relationship('client', 'raison_sociale')
                            ->required()
                            ->searchable()
                            ->preload(),
                        DatePicker::make('date_avoir')
                            ->label('Date de l\'avoir')
                            ->required(),
                        Textarea::make('motif')
                            ->label('Motif')
                            ->columnSpanFull(),
                        Select::make('statut')
                            ->label('Statut')
                            ->options([
                                'brouillon' => 'Brouillon',
                                'emise' => 'Émise',
                                'valide' => 'Validé',
                                'annule' => 'Annulé',
                            ])
                            ->default('brouillon')
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
                        TextInput::make('valide_par')
                            ->label('Validé par (user id)')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ]);
    }
}
