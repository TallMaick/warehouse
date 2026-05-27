<?php

namespace App\Filament\Resources\Lotes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('finca.nombre')
                    ->label('Finca')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nombre')
                    ->label('Lote')
                    ->searchable(),

                TextColumn::make('tipo_cultivo')
                    ->label('Cultivo')
                    ->badge() 
                    ->color(fn (string $state): string => match ($state) {
                        'Cacao' => 'warning',
                        'Café' => 'danger',
                        'Aguacate' => 'success',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('hectareas')
                    ->label('Hectáreas')
                    ->numeric(2)
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Fecha de Registro')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro rápido para ver solo lotes de cierta finca
                Tables\Filters\SelectFilter::make('finca_id')
                    ->relationship('finca', 'nombre')
                    ->label('Filtrar por Finca'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}