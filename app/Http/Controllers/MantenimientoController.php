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
        $mantenimientos=Mantenimiento::all();
        return view('mantenimiento.index',compact('insumos','maquinarias','mantenimientos'));
    }


    public function create(){

    }

    public function store(Request $request)
    {
      $Mantenimiento= new Mantenimiento;
      $Mantenimiento->fecha=$request->input('fecha');
      $Mantenimiento->id_maquinaria=$request->input('maquina');
      $Mantenimiento->save();
      
      $insumos=collect($request->input('insumos',[]))
      ->map(function($insumo){
        return ['cantidad'=>$insumo];
      });

      $Mantenimiento->insumos()->sync($insumos);
        
      return redirect()->route('mantenimiento.index');
      
    }

}
