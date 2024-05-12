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

    public function docentes(){
        return $this->belongsToMany(Docente::class,'asignatura_docente','clave_asignatura','id_docente');
    }



    public function grupos(){

        return $this->belongsToMany(Grupo::class, 'asignatura_grupo', 'clave_asignatura', 'clave_grupo');
      }
}
