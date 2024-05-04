<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Docente;
use  App\Models\Catalogo_articulo;
class Practica extends Model
{
    use HasFactory;
    public function docente(){
        return $this->belongsTo(Docente::class, 'id_docente');
    }
      //Relacion n a n con catalogo articulo
      public function catalogo_articulos()
      {
          return $this->belongsToMany(Catalogo_articulo::class, 'catalogo_practica', 'practica_id', 'articulo_id');
      }
}
