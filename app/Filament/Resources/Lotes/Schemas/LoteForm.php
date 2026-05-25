<?php

namespace App\Filament\Resources\Lotes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class LoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('finca_id')
                    ->relationship(
                        name: 'finca',
                        titleAttribute: 'nombre',
                        modifyQueryUsing: function (Builder $query) {
                            /** @var \App\Models\User $user */
                            $user = auth()->user();

                            // 🚀 CORRECCIÓN: Filtrar estrictamente para que la finca deba estar aprobada
                            $query->where('estado', 'aprobado');

                            // Si NO es el superadmin, filtramos para que solo salgan sus fincas
                            if (! $user->isSuperAdmin()) {
                                $query->where('user_id', $user->id);
                            }
                        }
                    )
                    ->label('Finca a la que pertenece')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(), // Hace que este selector ocupe todo el ancho para destacar

                TextInput::make('nombre')
                    ->label('Nombre del Lote / Sector')
                    ->placeholder('Ej: Lote El Mirador')
                    ->required()
                    ->maxLength(255),

                Select::make('tipo_cultivo')
                    ->label('Tipo de Cultivo')
                    ->options([
                        'Cacao' => 'Cacao',
                        'Café' => 'Café',
                        'Aguacate' => 'Aguacate',
                        'Maíz' => 'Maíz',
                        'Otro' => 'Otro',
                    ])
                    ->required()
                    ->searchable(),

                TextInput::make('variedad')
                    ->label('Variedad (Opcional)')
                    ->placeholder('Ej: CCN51, Hass, Caturra')
                    ->maxLength(255),

                TextInput::make('hectareas')
                    ->label('Hectáreas')
                    ->numeric()
                    ->required()
                    ->minValue(0.01),

                DatePicker::make('fecha_siembra')
                    ->label('Fecha de Siembra')
                    ->maxDate(now()),

                Fieldset::make('Coordenadas GPS (Opcional)')
                    ->schema([
                        TextInput::make('latitud')
                            ->numeric()
                            ->label('Latitud (Decimal)'),
                        TextInput::make('longitud')
                            ->numeric()
                            ->label('Longitud (Decimal)'),
                    ])->columns(2),
            ]);
    }
}
