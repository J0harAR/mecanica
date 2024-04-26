<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Mantenimiento;
class Insumos extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_insumo';
    protected $keyType = 'string';

    public function Articulo_inventariados(){
        return $this->belongsTo(Articulo_inventariado::class, 'id_insumo');
    }


    public function mantenimientos(){
        return $this->belongsToMany(Mantenimiento::class);
    }
}
