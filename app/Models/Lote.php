<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $fillable = [
        'finca_id',
        'nombre',
        'hectareas',
        'tipo_cultivo', // <-- Nueva
        'variedad',     // <-- Nueva
        'fecha_siembra',
        'latitud',
        'longitud',
    ];

    // Relación Inversa: Un lote pertenece a una finca
    public function finca()
    {
        return $this->belongsTo(Finca::class);
    }

    // ¡Conexión al Data Lake! Un lote puede recibir fotos de plagas o audios
    public function archivos()
    {
        return $this->morphMany(ArchivoMultimedia::class, 'fileable');
    }

    // Relación Directa: Una finca tiene muchos lotes
    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }

    // Relación Directa: Un lote tiene muchas actividades agrícolas
    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }

    // Relación: Un Lote tiene muchas lecturas de sensores IoT
    public function lecturasIot()
    {
        return $this->hasMany(LecturaIot::class);
    }
}