<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Finca extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'ubicacion_gps',
        'hectareas_totales',
        'tipo_suelo'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
