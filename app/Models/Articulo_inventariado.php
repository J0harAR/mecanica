<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Catalogo_articulo;

class Articulo_inventariado extends Model
{
    use HasFactory;

    public function Catalogos_arituculos(){
        return $this->hasMany(Catalogo_articulo);
    }
}
