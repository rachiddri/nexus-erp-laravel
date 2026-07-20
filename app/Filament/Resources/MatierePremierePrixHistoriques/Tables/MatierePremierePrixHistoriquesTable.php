<?php

namespace App\Filament\Resources\MatierePremierePrixHistoriques\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MatierePremierePrixHistoriquesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('matierePremiere.nom')->label('Matière')
                    ->searchable(),
                TextColumn::make('prix_avant')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('prix_apres')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('motif')
                    ->searchable(),
                TextColumn::make('date_debut')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_fin')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('utilisateur.name')
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
