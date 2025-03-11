<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Grupo;
use  App\Models\Docente;
class Asignatura extends Model
{
    use HasFactory;

    protected $table = "asignatura";
    protected $primaryKey = 'clave';
    protected $keyType = 'string';


    protected $fillable = [
        'clave',
        'nombre',
    ];
    //Relacion n a n con docente
    public function docentes(){
        return $this->belongsToMany(Docente::class,'asignatura_docente','clave_asignatura','id_docente');
    }


 //Relacion n a n con grupo
    public function grupos(){

        return $this->belongsToMany(Grupo::class, 'grupo', 'clave_asignatura', 'clave_grupo');
    }


      //Validaciones del modelo 

      //create
      public static $createRules = [
        'nombre' => 'required|unique:asignatura,nombre|max:255',
        'clave' => 'required|unique:asignatura,clave|max:255',
      ];
    //update
      public static $updateRules = [
        'nombre' => 'required|unique:asignatura,nombre|max:255',
      ];
      
      //Mensajes personalizados para las validaciones
      public static function messages()
      {
          return [
              'nombre.required' => 'El nombre de la asignatura es obligatorio.',
              'nombre.unique' => 'El nombre de la asignatura ya está en uso.',
              'clave.required' => 'La clave de la asignatura es obligatoria.',
              'clave.unique' => 'La clave de la asignatura ya está en uso.',
          ];
      }
      
    
}
