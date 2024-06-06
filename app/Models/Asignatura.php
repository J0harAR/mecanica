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

    public function docentes(){
        return $this->belongsToMany(Docente::class,'asignatura_docente','clave_asignatura','id_docente');
    }



    public function grupos(){

        return $this->belongsToMany(Grupo::class, 'grupo', 'clave_asignatura', 'clave_grupo');
      }
}
