<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Propietario extends Model
{
    protected $fillable = [
        'user_id',
        'tipo_documento',
        'documento',
        'nombre',
        'apellido',
        'telefono',
        'email',
        'direccion',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'id_propietario');
    }
}
