<?php

namespace App\Filament\Resources\BonCommandes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BonCommandesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_bc')
                    ->searchable(),
                TextColumn::make('client.raison_sociale')->label('Client')
                    ->searchable(),
                TextColumn::make('date_commande')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_livraison_souhaitee')
                    ->date()
                    ->sortable(),
                TextColumn::make('statut')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('montant_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('montant_ht')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('montant_ttc')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('remise_globale')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approuve_prix_plancher_par')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approuve_prix_plancher_le')
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
