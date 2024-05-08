<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Alumno;
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
}
