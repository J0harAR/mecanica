<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Maquinaria;
class Mantenimiento extends Model
{
    use HasFactory;

    public function Maquinarias(){
        return $this->belongsTo(Maquinaria::class, 'id_maquinaria');
    }

}
