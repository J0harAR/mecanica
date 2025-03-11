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
        return $this->belongsToMany(Insumos::class, 'insumos_mantenimiento', 'mantenimiento_id', 'insumo_id')
        ->withPivot(['cantidad']);
    }

    //Validaciones del modelo

    //create
      public static $createRules = [
        'maquina' => 'required',
        'fecha' => 'required',
        'insumos' => 'required',
    ];



     //Mensajes personalizados para las validaciones
     public static function messages()
     {
         return [
             'maquina.required' => 'Seleccione una maquinaria.',
             'fecha.required' => 'La fecha es obligatoria.',
             'insumos.required' => 'Insumos obligatorios',
             
         ];
     }

}
