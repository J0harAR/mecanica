<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $table = "persona";
    protected $primaryKey = 'curp';
    protected $keyType = 'string';

    protected $fillable = [
        'curp',
        'nombre',
        'apellido_p',
        'apellido_m',
    ];
}
