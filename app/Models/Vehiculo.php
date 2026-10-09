<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehiculo extends Model
{
    protected $fillable = [
        'placa',
        'linea',
        'marca',
        'modelo',
        'id_propietario',
    ];

    public function propietario(): BelongsTo
    {
        return $this->belongsTo(Propietario::class, 'id_propietario');
    }
}
