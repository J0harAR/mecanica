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

        //Validaciones del modelo 

      //Create
      public static $createRules = [
        'tipo' => 'required',
        'nombre' => 'required|string',
       
    ];

    //Mensajes personalizados para las validaciones
    public static function messages(){
        return [
            'tipo.required' => 'Seleccione el tipo de artículo que desea registrar.',
            'nombre.required' => 'Ingrese el nombre del artículo.',
        ];
}

    
}
