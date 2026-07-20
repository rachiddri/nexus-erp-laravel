<?php

namespace App\Filament\Resources\ProduitPhysiqueHistoriques\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProduitPhysiqueHistoriquesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('produitPhysique.code_affiche')->label('Produit Physique')
                    ->searchable(),
                TextColumn::make('type_mouvement')
                    ->searchable(),
                TextColumn::make('etape_origine')
                    ->searchable(),
                TextColumn::make('etape_destination')
                    ->searchable(),
                TextColumn::make('emplacement_origine')
                    ->searchable(),
                TextColumn::make('emplacement_destination')
                    ->searchable(),
                TextColumn::make('utilisateur.name')
                    ->searchable(),
                TextColumn::make('date_mouvement')
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
