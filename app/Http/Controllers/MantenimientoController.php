<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumos;
use App\Models\Maquinaria;
use App\Models\Mantenimiento;

class MantenimientoController extends Controller
{
    public function index()
    {
        $insumos=Insumos::all();
        $maquinarias=Maquinaria::all();

        return view('mantenimiento.index',compact('insumos','maquinarias'));
    }

    public function store(Request $request)
    {
      $Mantenimiento= new Mantenimiento;
      $Mantenimiento->fecha=$request->input('fecha');
      $Mantenimiento->id_maquinaria=$request->input('maquina');
      $Mantenimiento->save();
      $Mantenimiento->insumos()->sync($request->input('insumos',[]));
        
      return redirect()->route('mantenimiento.index');
      
    }

}
