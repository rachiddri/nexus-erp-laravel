<?php

namespace App\Filament\Resources\Produits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProduitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->searchable(),
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('categorie')
                    ->searchable(),
                TextColumn::make('prix_vente')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tva_taux')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock_alerte_min')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gamme')
                    ->searchable(),
                TextColumn::make('longueur')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('largeur')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('hauteur')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('poids')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('image_principale'),
                IconColumn::make('actif')
                    ->boolean(),
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
