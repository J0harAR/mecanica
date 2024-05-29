<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Insumos;
class Maquinaria extends Model
{
    use HasFactory;
    protected $table = "maquinaria";
    protected $primaryKey = 'id_maquinaria';
    protected $keyType = 'string';

    public function Articulo_inventariados(){
        return $this->belongsTo(Articulo_inventariado::class, 'id_maquinaria');
    }

      //Relacion N a N
      public function insumos()
      {
          return $this->belongsToMany(Insumos::class, 'insumos_maquinaria', 'maquinaria_id', 'insumo_id')
          ->withPivot(['capacidad','cantidad_actual','cantidad_minima']);
      }
}
