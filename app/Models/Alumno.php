<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;
    protected $table = "alumno";
    protected $primaryKey = 'no_control';
    protected $keyType = 'string';
    
    public function persona(){
        return $this->belongsTo(Persona::class, 'curp');
    }
}
