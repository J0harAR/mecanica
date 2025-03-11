<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Docente;
use  App\Models\Catalogo_articulo;
use  App\Models\Alumno;
use  App\Models\Grupo;
use  App\Models\Articulo_inventariado;
use  App\Models\Asignatura;
class Practica extends Model
{


    protected $table = "practica";
    protected $primaryKey = 'id_practica';
    protected $keyType = 'string';
    use HasFactory;

    protected $fillable = 
    [
      'id_practica',
      'id_docente',
      'clave_grupo',
      'nombre',
      'objetivo',
      'introduccion',
      'fundamento',
      'referencias',
      'estatus',
      ];

    public function docente(){
        return $this->belongsTo(Docente::class, 'id_docente');
    }

    public function grupo(){
        return $this->belongsTo(Grupo::class, 'clave_grupo');
    }

      //Relacion n a n con catalogo articulo
      public function catalogo_articulos()
      {
          return $this->belongsToMany(Catalogo_articulo::class, 'catalogo_practica', 'practica_id', 'articulo_id');
      }

      //Relacion n a n con articulo_inventariado
      public function articulo_inventariados()
      {
            return $this->belongsToMany(Articulo_inventariado::class, 'inventariado_practica','practica_id','inventario_id');
      }

      //Relacion n a n con alumno
      public function alumnos()
      {
            return $this->belongsToMany(Alumno::class,'alumno_practica','practica_id','alumno_id')
            ->withPivot(['fecha','no_equipo','hora_entrada','hora_salida']);
      }


      //Validaciones del modelo

      //Create
      public static $createRules = [
        'codigo_practica' => 'required|unique:practica,id_practica',
        'docente' => 'required',
        'grupo' => 'required',
        'nombre_practica' => 'required',
        'objetivo' => 'required',
        'introduccion' => 'required',
        'fundamento' => 'required',
        'referencias' => 'required',
        'articulos' => 'required',
    ];
      //Update
    public static $updateRules= [
        'docente' => 'required',
        'grupo' => 'required',
        'nombre_practica' => 'required',
        'objetivo' => 'required',
        'introduccion' => 'required',
        'fundamento' => 'required',
        'referencias' => 'required',
        'articulos' => 'required'
    ];

    //Create practica alumno
    public static $createRulesAlumno = [
        'alumnos' => 'required',
        'practica' => 'required',
        'articulos' => 'required',
        'fecha' => 'required',
        'no_equipo' => 'required',
        'hora_entrada' => 'required',
        'hora_salida' => 'required',
    ];


      //Mensajes personalizados para las validaciones
      public static function messages()
      {
          return [
            'codigo_practica.unique'=>'Codigo de practica duplicado.',
            'codigo_practica.required'=>'Codigo de practica obligatorio.',
            'docente.required' => 'Docente obligatorio.',
            'grupo.required' => 'Grupo obligatorio.',
            'nombre_practica.required' => 'Nombre de la practica obligatorio.',
            'objetivo.required' => 'Objectivo obligatorio.',
            'introduccion.required' => 'Introducción obligatoria.',
            'fundamento.required' => 'Fundamento obligatorio.',
            'referencias.required' => 'Referencias obligatorias.',
            'articulos.required' => 'Articulos obligatorios.',

            //Mensajes para practica alumnos
            'alumnos.required' => 'Alumnos obligatorios.',
            'practica.required' => 'Practica obligatoria.',
            'fecha.required' => 'Fecha obligatoria.',
            'no_equipo.required' => 'Numero de equipo obligatorio.',
            'hora_entrada.required' => 'Hora de entrada obligatoria',
            'hora_salida.required' => 'Hora de salida obligatoria',

          ];
      }
    


}
