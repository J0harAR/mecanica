<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogo_articulo extends Model
{
    use HasFactory;
    protected $table = "catalogo_articulo";
    protected $primaryKey = 'id_articulo';
    protected $keyType = 'string';
    

    
}
