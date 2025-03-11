<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Insumos;
use  App\Models\Catalogo_articulo;


class Maquinaria extends Model
{
    use HasFactory;
    protected $table = "maquinaria";
    protected $primaryKey = 'id_maquinaria';
    protected $keyType = 'string';


    protected $fillable = [
        'id_maquinaria',
    ];


    public function Articulo_inventariados(){
        return $this->belongsTo(Articulo_inventariado::class, 'id_maquinaria');
    }

      //Relacion N a N
      public function insumos()
      {
          return $this->belongsToMany(Catalogo_articulo::class, 'insumos_maquinaria', 'maquinaria_id', 'insumo_id')
          ->withPivot(['capacidad','cantidad_actual','cantidad_minima']);
      }


      //Validaciones modelo

       //Create
       public static $createRules = [
        'id_articulo'=>'required',
        'estatus' => 'required',
        'cantidad' => 'required',
        ];

        //Update
        public static $updateRules = [
            'estatus' => 'required',
            'insumos' => 'required',
        ];


         //Asignar Rules y desasignar
      public static $AsignarRules = [
        'insumos' => 'required',
       
      ];

      public static $DesasignarRules=[
        'insumos' => 'required',
      ];

        //Mensajes personalizados para las validaciones
        public static function messages()
        {
            return [
                'id_articulo.required' => 'Articulo obligatorio',
                'estatus.required' => 'Estatus de la maquinaria obligatorio.',
                'cantidad.required' => 'Cantidad obligatoria.',
                'insumos.required' => 'Insumos obligatorios.',
            ];
        }



}
