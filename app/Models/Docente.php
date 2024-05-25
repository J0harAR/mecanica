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
        return $this->belongsToMany(Asignatura::class,'docente_grupo','id_docente','clave_asignatura');
    }


    //Relacion n a n con herramientas
    public function herramientas(){
        return $this->belongsToMany(Herramientas::class,'prestamo','id_docente','id_herramientas')
        ->withPivot(['id','fecha_prestamo','fecha_devolucion','estatus']);

    }

    public function grupos(){
        return $this->belongsToMany(Grupo::class,'docente_grupo','id_docente','clave_grupo') 
        ->withPivot(['clave_asignatura','clave_periodo']);

    }


  
}