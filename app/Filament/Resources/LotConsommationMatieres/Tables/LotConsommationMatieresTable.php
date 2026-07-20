<?php

namespace App\Filament\Resources\LotConsommationMatieres\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LotConsommationMatieresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lotProduit.id')->label('Lot produit')->label('Lot produit')
                    ->searchable(),
                TextColumn::make('matierePremiere.nom')->label('Matière')
                    ->searchable(),
                TextColumn::make('quantite_consommee')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quantite_rebutee')
                    ->numeric()
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
