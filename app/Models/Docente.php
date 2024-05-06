<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Persona;
class Docente extends Model
{
    use HasFactory;
    protected $table = "docente";
    protected $primaryKey = 'rfc';
    protected $keyType = 'string';

    public function persona(){
        return $this->belongsTo(Persona::class, 'curp');
    }


    public function asignaturas(){
        return $this->belongsToMany(Asignatura::class);
    }
}