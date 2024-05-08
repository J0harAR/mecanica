<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Grupo;
class Alumno extends Model
{
    use HasFactory;
    protected $table = "alumno";
    protected $primaryKey = 'no_control';
    protected $keyType = 'string';
    
    public function persona(){
        return $this->belongsTo(Persona::class, 'curp');
    }

      //Relacion n a n con catalogo articulo
      public function grupos()
      {
          return $this->belongsToMany(Grupo::class, 'alumno_grupo', 'id_alumno', 'clave_grupo');
      }
}
