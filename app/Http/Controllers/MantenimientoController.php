<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumos;
use App\Models\Maquinaria;
use App\Models\Mantenimiento;
use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;
use Illuminate\Support\Facades\DB;
class MantenimientoController extends Controller
{

  function _construct()
    {
        $this->middleware('permission:ver-mantenimientos', ['only' => ['index']]);
        $this->middleware('permission:crear-mantenimiento', ['only' => ['store','obtenerDatosMaquinaria','getInsumosPorMaquinaria']]);
        $this->middleware('permission:borrar-mantenimiento', ['only' => ['destroy']]);
    }

    public function getInsumosPorMaquinaria(Request $request)
    {
       
        $maquinariaId = $request->input('id');
        
       
       
        $maquinaria = Maquinaria::find($maquinariaId);
        $insumos=[];
      
     


        foreach ($maquinaria->insumos as  $insumo_maquina) {
    
          //$insumos[] = Insumos::with('Articulo_inventariados','Articulo_inventariados.Catalogo_articulos')->find($insumo_maquina->id_articulo);
          $insumos[] = Articulo_inventariado::with(['catalogo_articulos'])
          ->where('tipo', 'Insumos')
          ->where('id_articulo', $insumo_maquina->id_articulo)
          ->get();
        }
        
       //dd($insumos);
        return response()->json($insumos);
    }

    public function obtenerDatosMaquinaria(Request $request){
        $maquinariaId = $request->input('id');
        $maquinaria = Maquinaria::with(['insumos','Articulo_inventariados.catalogo_articulos'])->find($maquinariaId);
        return response()->json($maquinaria);
        
    }


    public function index()
    {
        $insumos=Insumos::all();
        $maquinarias=Maquinaria::all();
        $mantenimientos=Mantenimiento::all();
        return view('mantenimiento.index',compact('insumos','maquinarias','mantenimientos'));
    }



    public function store(Request $request)
    {

      if (empty($request->input('maquina')) || empty($request->input('fecha')) || empty($request->input('insumos'))) {
         return redirect()->route('mantenimiento.index')->with('error', 'Todos los campos son requeridos.');             
    }


      $Mantenimiento= new Mantenimiento;
      $Mantenimiento->fecha=$request->input('fecha');
      $Mantenimiento->id_maquinaria=$request->input('maquina');
      
 
      $maquinaria = Maquinaria::find($request->input('maquina'));
   

      $insumos=collect($request->input('insumos',[]))
      ->map(function($insumo){
        return ['cantidad'=>$insumo];
     });

    

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

        $pruebas=[];
       foreach ($insumos as $key => $insumo) {

           $insumo_temp = Insumos::find($key);
         
           $insumo_maquinaria=$maquinaria->insumos()->where('insumo_id',$insumo_temp->Articulo_inventariados->Catalogo_articulos->id_articulo)->first();
           
            $cantidad_actual=$insumo_maquinaria->pivot->cantidad_actual;
            $capacidad_insumo=$insumo_maquinaria->pivot->capacidad;
            $cantidad_final=$cantidad_actual+$insumo['cantidad'];

             if($cantidad_final > $capacidad_insumo){
                return redirect()->route('mantenimiento.index')->with('error', 'Cantidad no valida , excedió la capacidad.');
              } 
            
           $insumo_temp->capacidad -= $insumo['cantidad'];
           $insumo_temp->save();
            //aqui hago el actualizado del aumento de insumo en la maquina
          $maquinaria->insumos()->syncWithoutDetaching([$insumo_temp->Articulo_inventariados->Catalogo_articulos->id_articulo => ['cantidad_actual' => $cantidad_final]]);
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
