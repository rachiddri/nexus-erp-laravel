<?php

namespace App\Filament\Resources\Emplacements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmplacementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hangar.nom')->label('Hangar')
                    ->searchable(),
                TextColumn::make('code_emplacement')
                    ->searchable(),
                TextColumn::make('emplacement_able_type')
                    ->searchable(),
                TextColumn::make('emplacement_able_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('zone')
                    ->searchable(),
                TextColumn::make('capacite_max')
                    ->numeric()
                    ->sortable(),
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
