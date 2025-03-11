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

    protected $fillable = [
        'id_insumo',
        'capacidad',
  
    ];
 
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

    
    //Validaciones del modelo
       //Create
    public static $createRules = [
            'id_articulo'=>'required',
            'estatus' => 'required',
            'cantidad' => 'required',
            'capacidad_insumo' =>'required',
        ];

    //Update
    public static $updateRules = [
            'estatus' => 'required',
            'capacidad' =>'required',
    ];

    //Mensajes personalizados para las validaciones
    public static function messages()
    {
        return [
            'id_articulo.required' => 'Articulo obligatorio',
            'estatus.required' => 'Estatus del insumo obligatorio.',
            'cantidad.required' => 'Cantidad obligatoria.',
            'capacidad_insumo.required' => 'La capacidad es obligatoria.',
            'capacidad.required' => 'La capacidad es obligatoria.',
        ];
    }


}
