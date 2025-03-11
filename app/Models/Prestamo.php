<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;
    protected $table = "prestamo";

    protected $fillable = 
    [
      'id_docente',
      'id_herramientas',
      'fecha_prestamo',
      'fecha_devolucion',
      'estatus',
      ];

 public function docente(){

        return $this->belongsTo(Docente::class,'id_docente');
      }

      //Create
      public static $createRules = [
        'rfc' => 'required',
        'herramienta' => 'required',
        'fecha_prestamo' => 'required|date',
        'fecha_devolucion' => 'required|date|after_or_equal:fecha_prestamo',
    ];

    public static $updateRules = [
        'rfc' => 'required',
        'fecha_devolucion' => 'required|date|after_or_equal:fecha_prestamo',
    ];


    //Mensajes personalizados para las validaciones
    public static function messages()
    {
        return [
            'rfc.required' => 'Debe seleccionar un docente.',
            'herramienta.required' => 'Debe seleccionar una herramienta.',
            'fecha_prestamo.required' => 'Debe seleccionar una fecha de préstamo.',
            'fecha_devolucion.required' => 'Debe seleccionar una fecha de devolución.',
            'fecha_devolucion.after_or_equal' => 'La fecha de devolución debe ser igual o posterior a la fecha de préstamo.',
        ];
    }


  
 

}
