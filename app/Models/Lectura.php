<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    use HasFactory;


    protected $table = "lectura";

    public function Maquinarias(){
        return $this->belongsTo(Maquinaria::class, 'id_maquinaria');
    }


    public function insumos()
    {
        return $this->belongsToMany(Catalogo_articulo::class, 'insumos_lectura', 'id_lectura', 'id_insumo')
        ->withPivot(['cantidad', 'cantidad_anterior', 'cantidad_nueva']);
    }
    public function user(){
        return $this->belongsTo(User::class, 'id');

    }

}
