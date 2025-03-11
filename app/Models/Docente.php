<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Persona;
use  App\Models\Asignatura;
use  App\Models\Herramientas;
use  App\Models\Grupo;
class Docente extends Model
{
    use HasFactory;
    protected $table = "docente";
    protected $primaryKey = 'rfc';
    protected $keyType = 'string';
   
    protected $fillable = [
        'rfc',
        'curp',
        'area',
        'foto',
        'telefono',
    ];

    public function persona(){
        return $this->belongsTo(Persona::class, 'curp');
    }

    //Relacion n a n con asignaturas
    
    public function asignaturas(){
        return $this->belongsToMany(Asignatura::class,'grupo','id_docente','clave_asignatura');
    }


    //Relacion n a n con herramientas
    public function herramientas(){
        return $this->belongsToMany(Herramientas::class,'prestamo','id_docente','id_herramientas')
        ->withPivot(['id','fecha_prestamo','fecha_devolucion','estatus']);

    }

    public function grupos(){
        return $this->belongsToMany(Grupo::class,'grupo','id_docente','clave_grupo');

    }


 
     //Validaciones del modelo 

      //Create
      public static $createRules = [
        'curp' => 'required|unique:persona,curp|max:255',
        'rfc' => 'required|unique:docente,rfc|max:255',
        'foto' => 'file|mimes:jpg,png|max:512',
        'nombre' => 'required',
        'apellido_p' => 'required',
        'apellido_m' => 'required',
    ];
      //Update
    public static function updateRules($id) {
        return [
            'curp' => 'required|unique:persona,curp,' . $id . ',curp|max:255',
            'foto' => 'file|mimes:jpg,png|max:512',
            'nombre' => 'required',
            'apellido_p' => 'required',
            'apellido_m' => 'required',
           
        ];
    }
      //Asignar Rules y desasignar
    public static $AsignarRules = [
        'clave_periodo' => 'required',
        'grupos'=>'required',
        'rfc_docente'=>'required',
       
      ];

    public static $DesasignarRules=[
        'grupos'=>'required',
        'rfc'=>'required',
        'periodo'=>'required',
      ];

      //Mensajes personalizados para las validaciones
    public static function messages()
        {
          return [
              'curp.required' => 'Curp es obligatorio.',
                'curp.unique' => 'Curp duplicado.',
              'rfc.required' => 'rfc  obligatorio.',
              'rfc_docente.required' => 'rfc  obligatorio.',
              'rfc.unique' => 'Este rfc ya está registrado.', 
              'foto.file' => 'El campo de la foto debe ser un archivo.',
              'foto.mimes' => 'El archivo de la foto debe ser de tipo: jpg, png.',
              'foto.max' => 'El tamaño de la foto no debe exceder los 512 KB.',
              'nombre.required' => 'El nombre es obligatorio.',
               'apellido_p.required' => 'El apellido paterno es obligatorio.',
               'apellido_m.required' => 'El apellido materno es obligatorio.',                                 
               'clave_periodo.required' => 'El periodo es obligatorio.',  
               'periodo.required' => 'El periodo es obligatorio.',  
               'grupos.required' => 'No se selecciono ningun grupo.',  

            ];
    }

  
}