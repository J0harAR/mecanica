<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Alumno;
use  App\Models\Asignatura;
use  App\Models\Docente;
class Grupo extends Model
{
    use HasFactory;
    protected $table = "grupo";
    protected $primaryKey = 'clave';
    protected $keyType = 'string';

    
    public function alumnos()
      {
          return $this->belongsToMany(Alumno::class, 'alumno_grupo', 'clave_grupo', 'id_alumno');
      }


      public function asignaturas(){

        return $this->belongsToMany(Asignatura::class, 'asignatura_grupo', 'clave_grupo', 'clave_asignatura');
      }

      public function docentes(){

        return $this->belongsToMany(Docente::class, 'docente_grupo', 'clave_grupo', 'id_docente');
      }
      
}
