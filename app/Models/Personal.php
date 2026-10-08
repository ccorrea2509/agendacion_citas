<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
        protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'email',
        'direccion',
        'especialidad',
        'horario_inicio',
        'horario_fin',
        'disponibilidad',
    ];
}
