<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;
    protected $table = "periodo";
    protected $primaryKey = 'clave';
    protected $keyType = 'string';

    protected $fillable = [
        'clave',
    ];
}
