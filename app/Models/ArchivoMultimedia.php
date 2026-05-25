<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivoMultimedia extends Model
{
    protected $table = 'archivos_multimedia';
    
    // protected $fillable = [
    //     'ruta_archivo',
    //     'tipo_archivo',
    // ];

    protected $fillable = [
        'fileable_type',   // Estándar de Laravel
        'fileable_id',     // Estándar de Laravel
        'contenido_texto',
        'ruta_archivo',
        'tipo_archivo',
        'peso_bytes'
    ];

    // Relación polimórfica principal
    public function fileable()
    {
        return $this->morphTo();
    }
}
