<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Grupo;
use  App\Models\Practica;
class Alumno extends Model
{
    use HasFactory;
    protected $table = "alumno";
    protected $primaryKey = 'no_control';
    protected $keyType = 'string';
    
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

}
