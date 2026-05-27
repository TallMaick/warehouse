<?php

namespace App\Filament\Resources\Fincas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class FincaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Relación con el Usuario (Propietario)
                Select::make('user_id')
                    ->relationship('user', 'name') // Busca la relación 'user' y muestra el 'name'
                    ->required()
                    ->label('Propietario (Agricultor)'),

                // Datos de la Finca
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->label('Nombre de la Finca'),

                TextInput::make('ubicacion_gps')
                    ->maxLength(255)
                    ->label('Coordenadas GPS')
                    ->placeholder('Ej: 8.234, -73.352'),

                TextInput::make('hectareas_totales')
                    ->numeric()
                    ->label('Hectáreas Totales')
                    ->placeholder('Ej: 5.5'),

                TextInput::make('tipo_suelo')
                    ->maxLength(255)
                    ->label('Tipo de Suelo (Opcional)'),
            ]);
    }
}
