<?php

namespace App\Filament\Resources\Actividads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ActividadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lote.nombre')
                    ->label('Lote')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tipo_actividad')
                    ->label('Actividad')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Fertilizacion' => 'success',
                        'Control Plagas' => 'danger',
                        'Cosecha' => 'warning',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('costo')
                    ->label('Costo')
                    ->money('COP') // Puedes cambiar 'COP' por 'USD' o tu moneda local
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tipo_actividad')
                    ->label('Filtrar por Tipo')
                    ->options([
                        'Fertilizacion' => 'Fertilización',
                        'Poda' => 'Poda',
                        'Control Plagas' => 'Control de Plagas',
                        'Cosecha' => 'Cosecha',
                        'Riego' => 'Riego',
                    ]),
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