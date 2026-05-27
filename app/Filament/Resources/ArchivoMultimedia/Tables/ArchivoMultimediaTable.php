<?php

namespace App\Filament\Resources\ArchivoMultimedia\Tables;
;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ArchivoMultimediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fileable_type')
                    ->label('Asociado a')
                    ->formatStateUsing(fn (string $state): string => class_basename($state)) // Muestra "Finca", "Lote", etc.
                    ->badge()
                    ->color('info'),

                TextColumn::make('tipo_archivo')
                    ->label('Formato')
                    ->default('Nota de Texto')
                    ->searchable(),

                TextColumn::make('peso_bytes')
                    ->label('Peso')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state / 1024 / 1024, 2) . ' MB' : '-')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Fecha de Subida')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}