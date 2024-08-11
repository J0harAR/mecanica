<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Mantenimiento;
use  App\Models\Maquinaria;
class Insumos extends Model
{
    use HasFactory;
    //protected $table = "insumos";
    protected $primaryKey = 'id_insumo';
    protected $keyType = 'string';

    public function Articulo_inventariados(){
        return $this->belongsTo(Articulo_inventariado::class, 'id_insumo');
    }

    //Relacion N a N
    public function mantenimientos()
    {
        return $this->belongsToMany(Mantenimiento::class, 'insumos_mantenimiento', 'insumo_id', 'mantenimiento_id');
    }
    //Relacion N a N
    public function maquinarias()
    {
        return $this->belongsToMany(Maquinaria::class, 'insumos_maquinaria', 'insumo_id', 'maquinaria_id');
    }


}
