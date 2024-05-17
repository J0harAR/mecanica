<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Docente;
use  App\Models\Catalogo_articulo;
use  App\Models\Alumno;
use  App\Models\Articulo_inventariado;
class Practica extends Model
{


    protected $table = "practica";
    protected $primaryKey = 'id_practica';
    protected $keyType = 'string';
    use HasFactory;
    
    public function docente(){
        return $this->belongsTo(Docente::class, 'id_docente');
    }
      //Relacion n a n con catalogo articulo
      public function catalogo_articulos()
      {
          return $this->belongsToMany(Catalogo_articulo::class, 'catalogo_practica', 'practica_id', 'articulo_id');
      }

      //Relacion n a n con articulo_inventariado
      public function articulo_inventariados()
      {
            return $this->belongsToMany(Articulo_inventariado::class, 'inventariado_practica','practica_id','inventario_id');
      }

      //Relacion n a n con alumno
      public function alumnos()
      {
            return $this->belongsToMany(Alumno::class,'alumno_practica','practica_id','alumno_id');
      }
}
