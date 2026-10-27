<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionCita extends Model
{
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id');
    }
    public function usuario_creador()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
