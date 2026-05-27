<?php

namespace App\Filament\Resources\ArchivoMultimedia;

use App\Filament\Resources\ArchivoMultimedia\Pages\CreateArchivoMultimedia;
use App\Filament\Resources\ArchivoMultimedia\Pages\EditArchivoMultimedia;
use App\Filament\Resources\ArchivoMultimedia\Pages\ListArchivoMultimedia;
use App\Filament\Resources\ArchivoMultimedia\Schemas\ArchivoMultimediaForm;
use App\Filament\Resources\ArchivoMultimedia\Tables\ArchivoMultimediaTable;
use App\Models\ArchivoMultimedia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ArchivoMultimediaResource extends Resource
{
    protected static ?string $model = ArchivoMultimedia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ArchivoMultimediaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArchivoMultimediaTable::configure($table);
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
            'index' => ListArchivoMultimedia::route('/'),
            'create' => CreateArchivoMultimedia::route('/create'),
            'edit' => EditArchivoMultimedia::route('/{record}/edit'),
        ];
    }
}
