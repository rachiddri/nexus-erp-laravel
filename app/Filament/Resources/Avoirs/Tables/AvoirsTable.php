<?php

namespace App\Filament\Resources\Avoirs\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AvoirsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_avoir')
                    ->label('N° avoir')
                    ->searchable(),
                TextColumn::make('facture.numero_facture')->label('Facture')
                    ->searchable(),
                TextColumn::make('client.raison_sociale')->label('Client')
                    ->searchable(),
                TextColumn::make('date_avoir')
                    ->date()
                    ->sortable(),
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
                TextColumn::make('statut')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
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
                    ->url(fn ($record) => route('avoirs.pdf', $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
