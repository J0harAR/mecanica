<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Catalogo_articulo;

class Articulo_inventariado extends Model
{
    use HasFactory;
    protected $table = "Articulo_inventariado";
    protected $primaryKey = 'id_inventario';
    protected $keyType = 'string';

    public function Catalogo_articulos(){
        return $this->belongsTo(Catalogo_articulo::class, 'id_articulo');
    }
}