<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuestoTrabajo extends Model
{
    public function tecnico()
    {
        return $this->belongsTo(Personal::class, 'id_tecnico', 'id');
    }
}
