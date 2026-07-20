<?php

namespace App\Filament\Resources\OrdreProductions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdreProductionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_op')
                    ->searchable(),
                TextColumn::make('bonCommande.numero_bc')->label('Bon de commande')
                    ->searchable(),
                TextColumn::make('depotMatiere.nom')->label('Dépôt matière')
                    ->searchable(),
                TextColumn::make('date_lancement')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_prevue_fin')
                    ->date()
                    ->sortable(),
                TextColumn::make('statut')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('priorite')
                    ->searchable(),
                TextColumn::make('origine')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('valide_par')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('valide_le')
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
