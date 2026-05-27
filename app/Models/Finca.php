<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Finca extends Model
{
    protected $fillable = [
        'user_id',
        'estado',
        'nombre',
        'latitud',
        'longitud',
        'hectareas_totales',
        'tipo_suelo'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Una finca puede tener MUCHOS archivos en el Data Lake (Fotos, audios)
    public function archivos()
    {
        return $this->morphMany(ArchivoMultimedia::class, 'fileable');
    }

    // Relación Directa: Una finca tiene muchos lotes
    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }
}
