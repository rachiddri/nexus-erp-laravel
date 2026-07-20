<?php

namespace App\Filament\Resources\RetourClients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RetourClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.raison_sociale')->label('Client')
                    ->searchable(),
                TextColumn::make('date_retour')
                    ->date()
                    ->sortable(),
                TextColumn::make('decision')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('statut')->badge()->color(fn ($state) => \App\Helpers\BadgeColors::for($state))
                    ->searchable(),
                TextColumn::make('documentSortie.id')->label('Document')->label('Document')
                    ->searchable(),
                TextColumn::make('cree_par')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('traite_par')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('traite_le')
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
