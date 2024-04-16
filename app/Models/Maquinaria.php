<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquinaria extends Model
{
    use HasFactory;
    protected $table = "maquinaria";
    protected $primaryKey = 'id_maquinaria';
    protected $keyType = 'string';

    public function Articulo_inventariados(){
        return $this->belongsTo(Articulo_inventariado::class, 'id_maquinaria');
    }
}
