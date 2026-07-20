<?php

namespace App\Filament\Resources\ProduitPhysiques\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProduitPhysiquesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code_affiche')
                    ->searchable(),
                TextColumn::make('produit.nom')->label('Produit')
                    ->searchable(),
                TextColumn::make('lot.numero_lot')->label('Lot')
                    ->searchable(),
                TextColumn::make('lotProduit.id')->label('Lot produit')->label('Lot produit')
                    ->searchable(),
                TextColumn::make('etapeActuelle.nom')->label('Étape')
                    ->searchable(),
                TextColumn::make('emplacement.code_emplacement')->label('Emplacement')
                    ->searchable(),
                TextColumn::make('statut')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('date_creation')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_sortie')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('cree_par')
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
