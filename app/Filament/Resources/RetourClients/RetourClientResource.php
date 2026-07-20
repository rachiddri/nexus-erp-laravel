<?php

namespace App\Filament\Resources\RetourClients;

use App\Filament\Resources\RetourClients\Pages\CreateRetourClient;
use App\Filament\Resources\RetourClients\Pages\EditRetourClient;
use App\Filament\Resources\RetourClients\Pages\ListRetourClients;
use App\Filament\Resources\RetourClients\Schemas\RetourClientForm;
use App\Filament\Resources\RetourClients\Tables\RetourClientsTable;
use App\Models\RetourClient;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RetourClientResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Retours clients';

    protected static ?string $model = RetourClient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RetourClientForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RetourClientsTable::configure($table);
    }

        public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\RetourClients\RelationManagers\RetourClientLignesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRetourClients::route('/'),
            'create' => CreateRetourClient::route('/create'),
            'edit' => EditRetourClient::route('/{record}/edit'),
        ];
    }
}
