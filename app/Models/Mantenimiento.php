<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Maquinaria;
use  App\Models\Insumos;
class Mantenimiento extends Model
{
    use HasFactory;
    protected $table = "mantenimiento";

    public function Maquinarias(){
        return $this->belongsTo(Maquinaria::class, 'id_maquinaria');
    }


    public function insumos()
    {
        return $this->belongsToMany(Insumos::class, 'insumos_mantenimiento', 'mantenimiento_id', 'insumo_id');
    }

}
