<?php

namespace App\Filament\Resources\Lotes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
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

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'aprobado'  => 'success',
                        'rechazado' => 'danger',
                        default     => 'gray',
                    })
                    ->disabled(fn () => ! auth()->user()->isSuperAdmin()),

                TextColumn::make('created_at')
                    ->label('Fecha de Registro')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('finca_id')
                    ->relationship('finca', 'nombre')
                    ->label('Filtrar por Finca'),
            ])
            ->actions([
                Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->estado === 'pendiente')
                    ->action(fn ($record) => $record->update(['estado' => 'aprobado']))
                    ->visible(fn () => auth()->user()->isSuperAdmin()),

                Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->estado === 'pendiente')
                    ->action(fn ($record) => $record->update(['estado' => 'rechazado']))
                    ->visible(fn () => auth()->user()->isSuperAdmin()),

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