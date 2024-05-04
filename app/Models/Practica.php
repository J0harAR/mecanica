<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Docente;

class Practica extends Model
{
    use HasFactory;
    public function docente(){
        return $this->belongsTo(Docente::class, 'id_docente');
    }
}
