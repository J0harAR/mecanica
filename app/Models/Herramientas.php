<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Docente;
class Herramientas extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_herramientas';
    protected $keyType = 'string';


    protected $fillable = [
        'id_herramientas',
        'condicion',
        'dimension',
    ];

    
    public function Articulo_inventariados(){
        return $this->belongsTo(Articulo_inventariado::class, 'id_herramientas');
    }

    public function docentes(){
        return $this->belongsToMany(Docente::class,'prestamo','id_herramientas','id_docente');

    }


    //Validaciones del modelo
       //Create
       public static $createRules = [
            'id_articulo'=>'required',
            'estatus' => 'required',
            'cantidad' => 'required',
            'condicion_herramienta' =>'required',
        ];

      //Update
      public static $updateRules = [
            'estatus' => 'required',
            'condicion_herramienta' =>'required',
      ];

    //Mensajes personalizados para las validaciones
    public static function messages()
    {
        return [
            'id_articulo.required' => 'Articulo obligatorio',
            'estatus.required' => 'Estatus de la herramienta obligatorio.',
            'cantidad.required' => 'Cantidad obligatoria.',
            'condicion_herramienta.required' => 'Condicion de la herramienta obligatoria.',
        ];
    }

}
