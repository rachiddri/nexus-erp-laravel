<?php

namespace App\Filament\Resources\Factures\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FacturesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_facture')
                    ->searchable(),
                TextColumn::make('client.raison_sociale')->label('Client')
                    ->searchable(),
                TextColumn::make('bonCommande.numero_bc')->label('Bon de commande')
                    ->searchable(),
                TextColumn::make('date_facture')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_echeance')
                    ->date()
                    ->sortable(),
                TextColumn::make('mode_reglement')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('montant_ht')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('montant_tva')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('taux_tva')
                    ->label('TVA %')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('montant_ttc')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('montant_paye')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('remise')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('statut')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('emise_par')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('emise_le')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => route('factures.pdf', $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
