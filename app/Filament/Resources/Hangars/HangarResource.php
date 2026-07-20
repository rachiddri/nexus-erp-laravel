<?php

namespace App\Filament\Resources\Hangars;

use App\Filament\Resources\Hangars\Pages\CreateHangar;
use App\Filament\Resources\Hangars\Pages\EditHangar;
use App\Filament\Resources\Hangars\Pages\ListHangars;
use App\Filament\Resources\Hangars\Schemas\HangarForm;
use App\Filament\Resources\Hangars\Tables\HangarsTable;
use App\Models\Hangar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HangarResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Stock';

    protected static ?string $model = Hangar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return HangarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HangarsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHangars::route('/'),
            'create' => CreateHangar::route('/create'),
            'edit' => EditHangar::route('/{record}/edit'),
        ];
    }
}
