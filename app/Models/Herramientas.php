<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Docente;
class Herramientas extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_herramientas';
    protected $keyType = 'string';


    protected $fillable = [
        'id_herramientas',
        'condicion',
        'dimension',
    ];

    
    public function Articulo_inventariados(){
        return $this->belongsTo(Articulo_inventariado::class, 'id_herramientas');
    }

    public function docentes(){
        return $this->belongsToMany(Docente::class,'prestamo','id_herramientas','id_docente');

    }


}
