<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Practica;
class Catalogo_articulo extends Model
{
    use HasFactory;
    protected $table = "catalogo_articulo";
    protected $primaryKey = 'id_articulo';
    protected $keyType = 'string';

    protected $fillable = [
        'id_articulo',
        'nombre',
        'cantidad',
        'seccion',
        'tipo',
    ];
    
    //Relacion n a n con practica
    public function practicas()
    {
        return $this->belongsToMany(Practica::class, 'catalogo_practica', 'articulo_id', 'practica_id');
    }
    
}
