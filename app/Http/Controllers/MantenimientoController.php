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
      
    // Obtener los insumos asociados a la maquinaria
    $maquinaria = Maquinaria::find($request->input('maquina'));
    $insumos_maquinaria = $maquinaria->insumos()->pluck('id_insumo')->toArray();
      
  
      $insumos=collect($request->input('insumos',[]))
      ->map(function($insumo){
        return ['cantidad'=>$insumo];
     });


     $insumo_presente = false;
     foreach ($insumos as $key => $insumo) {

          foreach ($insumos_maquinaria as $insumo_maquinaria) {
                if ($key == $insumo_maquinaria) {               
                      $insumo_presente = true;
                      break;             
                }
          }
      
          if (!$insumo_presente) {         
               return redirect()->route('mantenimiento.index')->with('error', 'Insumos no están asociados a la maquinaria.');
        }

    

      }

      $Mantenimiento->save();
      $Mantenimiento->insumos()->sync($insumos);



    
      return redirect()->route('mantenimiento.index');
    
    }

    public function destroy($id){
      $mantenimiento=Mantenimiento::find($id);
      $mantenimiento->delete();
      
      return redirect()->route('mantenimiento.index');

    }

}
