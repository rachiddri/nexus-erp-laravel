<?php

namespace App\Filament\Resources\LotProduits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LotProduitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lot.numero_lot')->label('Lot')
                    ->searchable(),
                TextColumn::make('ordreProductionLigne.id')->label('Ordre Production Ligne')->label('Ordre Production Ligne')
                    ->searchable(),
                TextColumn::make('produit.nom')->label('Produit')
                    ->searchable(),
                TextColumn::make('quantite_theorique')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quantite_produite')
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
