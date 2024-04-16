<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Herramientas extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_herramientas';
    protected $keyType = 'string';
    
    public function Articulo_inventariados(){
        return $this->belongsTo(Articulo_inventariado::class, 'id_herramientas');
    }
}
