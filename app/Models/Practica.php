<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Docente;
use  App\Models\Catalogo_articulo;
use  App\Models\Alumno;
use  App\Models\Grupo;
use  App\Models\Articulo_inventariado;
use  App\Models\Asignatura;
class Practica extends Model
{


    protected $table = "practica";
    protected $primaryKey = 'id_practica';
    protected $keyType = 'string';
    use HasFactory;

    protected $fillable = 
    [
      'id_practica',
      'id_docente',
      'clave_grupo',
      'nombre',
      'objetivo',
      'introduccion',
      'fundamento',
      'referencias',
      'estatus',
      ];

    public function docente(){
        return $this->belongsTo(Docente::class, 'id_docente');
    }

    public function grupo(){
        return $this->belongsTo(Grupo::class, 'clave_grupo');
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
            return $this->belongsToMany(Alumno::class,'alumno_practica','practica_id','alumno_id')
            ->withPivot(['fecha','no_equipo','hora_entrada','hora_salida']);
      }
}
