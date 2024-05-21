<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumos;
use App\Models\Maquinaria;
use App\Models\Mantenimiento;
use Illuminate\Support\Facades\DB;
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
      
 
    $maquinaria = Maquinaria::find($request->input('maquina'));
    $insumos_maquinaria = $maquinaria->insumos()->pluck('id_insumo')->toArray();
      
  
      $insumos=collect($request->input('insumos',[]))
      ->map(function($insumo){
        return ['cantidad'=>$insumo];
     });


     $insumo_presente = false;
     foreach ($insumos as $key => $insumo) {
          $insumo_presente = false;
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


     $errores = [];

      foreach ($insumos as $key => $insumo) {
          $insumo_temp = Insumos::find($key);
          
          if ($insumo_temp->capacidad < $insumo['cantidad']) {
              $errores[] = 'No hay suficientes litros de insumo para ' . $insumo_temp->id_insumo;
          }
      }

      if (!empty($errores)) {
          return redirect()->route('mantenimiento.index')->with('errores_cantidad', $errores);
      }

      
      foreach ($insumos as $key => $insumo) {
          $insumo_temp = Insumos::find($key);
          $insumo_temp->capacidad -= $insumo['cantidad'];
          $insumo_temp->save();
          //aqui hago el actualizado del aumento de insumo en la maquina
          $maquinaria->insumos()->syncWithoutDetaching([$insumo_temp->id_insumo => ['cantidad' => DB::raw('cantidad + ' . $insumo['cantidad'])]]);
      }


      $Mantenimiento->save();
      $Mantenimiento->insumos()->sync($insumos);

      return redirect()->route('mantenimiento.index')->with('success', 'El registro del mantenimiento ha sido creado exitosamente,');
    
    }

    public function destroy($id){
      $mantenimiento=Mantenimiento::find($id);
      $mantenimiento->delete();
      
      return redirect()->route('mantenimiento.index')->with('success', 'El registro del mantenimiento ha sido eliminado exitosamente.');


    }

}
