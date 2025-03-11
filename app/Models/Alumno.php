<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Grupo;
use  App\Models\Practica;
use Illuminate\Database\Eloquent\Relations\Pivot;
class Alumno extends Model
{
    use HasFactory;
    protected $table = "alumno";
    protected $primaryKey = 'no_control';
    protected $keyType = 'string';


    protected $fillable = [
      'no_control',
      'curp',
    ];

    
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'curp');
    }

      //Relacion n a n con catalogo articulo
    public function grupos()
    {
          return $this->belongsToMany(Grupo::class, 'alumno_grupo', 'id_alumno', 'clave_grupo');
    }

      //Relacion n a n con practica

    public function practicas(){
        return $this->belongsToMany(Practica::class,'alumno_practica','alumno_id','practica_id');
    }

      //Validaciones del modelo 

      //Create
    public static $createRules = [
        'curp' => 'required|unique:persona|max:255',
        'no_control' => 'required|unique:alumno|max:255',
        'nombre' => 'required',
        'apellido_p' => 'required',
        'apellido_m' => 'required',
    ];
      //Update
    public static function updateRules($id) {
        return [
            'curp'=>'required|unique:persona',
            'no_control' => 'required|unique:alumno,no_control,' . $id . ',no_control|max:255',
            'nombre' => 'required',
            'apellido_p' => 'required',
            'apellido_m' => 'required',
        ];
    }
      //Asignar Rules y desasignar
      public static $AsignarRules = [
        'selected_alumnos' => 'required',
        'grupo'=>'required',
       
      ];

      public static $DesasignarRules=[
        'selected_alumnos' => 'required',
        'clave_grupo'=>'required'
      ];

      //Mensajes personalizados para las validaciones
    public static function messages()
        {
          return [
              'curp.required' => 'Curp es obligatorio.',
              'curp.unique' => 'Curp duplicado.',
              'no_control.required' => 'Numero de control obligatorio.',
              'no_control.unique' => 'Este número de control ya está registrado.',             
              'selected_alumnos.required' => 'No se seleccionó ningún alumno.',             
              'grupo.required' => 'Grupo no encontrado.',             
              'clave_grupo.required' => 'Grupo no encontrado.',
              'nombre.required' => 'El nombre es obligatorio.',
              'apellido_p.required' => 'El apellido paterno es obligatorio.',
              'apellido_m.required' => 'El apellido materno es obligatorio.',             
            ];
    }

}
class AlumnoGrupo extends Pivot
{
    protected $table = 'alumno_grupo';
    public $incrementing = false; // Indica que no hay columna autoincremental en esta tabla pivot
}