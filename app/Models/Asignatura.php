<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Grupo;
class Asignatura extends Model
{
    use HasFactory;

    protected $table = "asignatura";
    protected $primaryKey = 'clave';
    protected $keyType = 'string';

    public function docentes(){
        return $this->belongsToMany(Docente::class);
    }



    public function grupos(){

        return $this->belongsToMany(Grupo::class, 'asignatura_grupo', 'clave_asignatura', 'clave_grupo');
      }
}
