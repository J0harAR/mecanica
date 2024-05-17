<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Catalogo_articulo;
use  App\Models\Practica;

class Articulo_inventariado extends Model
{
    use HasFactory;
    protected $table = "Articulo_inventariado";
    protected $primaryKey = 'id_inventario';
    protected $keyType = 'string';

    public function Catalogo_articulos(){
        return $this->belongsTo(Catalogo_articulo::class, 'id_articulo');
    }

    //Relacion n a n con practica
    public function practicas()
    {
        return $this->belongsToMany(Practica::class, 'inventariado_practica', 'inventario_id', 'practica_id');
    }

}