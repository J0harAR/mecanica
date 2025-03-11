<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;
    protected $table = "periodo";
    protected $primaryKey = 'clave';
    protected $keyType = 'string';

    protected $fillable = [
        'clave',
        'fecha_inicio',
        'fecha_final',
    ];

    //Validaciones del modelo

      //Create
      public static $createRules = [
        'periodo'=>'required|unique:periodo,clave',
        'fecha_inicio' => 'required',
        'fecha_final' => 'required',
        ];



        //Update
        public static function updateRules($id) {
            return [
                'fecha_inicio'=>'required',
                'fecha_final' => 'required',
            ];
        }

          //Mensajes personalizados para las validaciones
      public static function messages()
      {
          return [
              'periodo.required' => 'El periodo es obligatorio',
              'periodo.unique' => 'Periodo duplicado',
              'fecha_inicio.required' => 'Fecha de inicio obligatoria.',
              'fecha_final.required' => 'Fecha de fin obligatoria.',
          ];
      }
}
