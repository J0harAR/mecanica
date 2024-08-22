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
    
    }

    public function getInsumosPorMaquinaria(Request $request)
    {
        //Guardamos el id de la maquinaria
        $maquinariaId = $request->input('id');
        
       
       //Buscamos la maquinaria 
        $maquinaria = Maquinaria::find($maquinariaId);
        $insumos=[];
      
        //Interamos entre los insumos de la maquina para regresar la cantidad y capacidad de los insumos de la maquinaria
        foreach ($maquinaria->insumos as  $insumo_maquina) {
    
          $insumos[] = Articulo_inventariado::with(['catalogo_articulos'])
          ->where('tipo', 'Insumos')
          ->where('id_articulo', $insumo_maquina->id_articulo)
          ->get();
        }
        
        return response()->json($insumos);
    }

    public function obtenerDatosMaquinaria(Request $request){
        //Guardamos el id de la maquinaria
        $maquinariaId = $request->input('id');
        //Regresaremos solo los datos generales de la maquina
        $maquinaria = Maquinaria::with(['insumos','Articulo_inventariados.catalogo_articulos'])->find($maquinariaId);
        return response()->json($maquinaria);
        
    }


    public function index()
    {
      //Retornaremos todos los insumos y maquinarias que nos serviran para el modal de create
        $insumos=Insumos::all();
        $maquinarias=Maquinaria::all();
        $mantenimientos=Mantenimiento::all();//Retornamos tambien todos los mantenimientos
        return view('mantenimiento.index',compact('insumos','maquinarias','mantenimientos'));
    }



    public function store(Request $request)
    {
      //Revisamos que no se deje ningun campo en blanco si es asi retornara un error
      if (empty($request->input('maquina')) || empty($request->input('fecha')) || empty($request->input('insumos'))) {
         return redirect()->route('mantenimiento.index')->with('error', 'Todos los campos son requeridos.');             
    }

      //Creamos un mantenimiento
      $Mantenimiento= new Mantenimiento;
      $Mantenimiento->fecha=$request->input('fecha');
      $Mantenimiento->id_maquinaria=$request->input('maquina');
      
      //Buscamos la maquina que se le realizara mantenimiento
      $maquinaria = Maquinaria::find($request->input('maquina'));
   
    //COnvertimos los insumos de la request en una collecion donde la key sera el id del insumo es decir IA,cantidad=5
      $insumos=collect($request->input('insumos',[]))
      ->map(function($insumo){
        return ['cantidad'=>$insumo];
     });

    
     //Inicializamos el array por si hay algun error
     $errores = [];

      //Iteramos entre los insumos que recibimos
      foreach ($insumos as $key => $insumo) {
          //Revisamos que exista el insumo y lo guardamos en insumo_temp
          $insumo_temp = Insumos::find($key);
          //Revisamos que la cantidad de insumo que se ingreso es mayor al que hay de ese insumo en el inventario
          if ($insumo_temp->capacidad < $insumo['cantidad']) {
              $errores[] = 'No hay suficientes litros de insumo para ' . $insumo_temp->id_insumo;//Lo guardamos en errores
          }
      }
      //Si existe algun error retornamos el error de cantidad
      if (!empty($errores)) {
          return redirect()->route('mantenimiento.index')->with('errores_cantidad', $errores);
      }

      //Seguimos iterando entre los insumos para verificar las cantidades
       foreach ($insumos as $key => $insumo) {
          //Revisamos que exista el insumo y lo guardamos en insumo_temp
           $insumo_temp = Insumos::find($key);
         //Guardamos los insumos de la maquinaria que coincidan con los insumos que se ingresaron
           $insumo_maquinaria=$maquinaria->insumos()->where('insumo_id',$insumo_temp->Articulo_inventariados->Catalogo_articulos->id_articulo)->first();
           
            $cantidad_actual=$insumo_maquinaria->pivot->cantidad_actual; //La cantidad actual sera la cantidad actual del insumo de la maquina
            $capacidad_insumo=$insumo_maquinaria->pivot->capacidad;//La capacidad  sera la capacidad  del insumo de la maquina
            $cantidad_final=$cantidad_actual+$insumo['cantidad'];//La cantidad final sera la suma de la cantidad actual + la cantidad nueva que se ingreso

              //Validamos que si cantidad final es mayor a la capacidad del insumo retorne un error
             if($cantidad_final > $capacidad_insumo){
                return redirect()->route('mantenimiento.index')->with('error', 'Cantidad no valida , excedió la capacidad.');
              } 
            
            //Disminuiremos la capacidad del insumo pero del invetario y no el de la maquina
           $insumo_temp->capacidad -= $insumo['cantidad'];
           $insumo_temp->save();
            //aqui hago el actualizado del aumento de insumo en la maquina
          $maquinaria->insumos()->syncWithoutDetaching([$insumo_temp->Articulo_inventariados->Catalogo_articulos->id_articulo => ['cantidad_actual' => $cantidad_final]]);
       }
      


          $Mantenimiento->save();
          $Mantenimiento->insumos()->sync($insumos);//Se asigna la nueva cantidad del insumo a la maquinaria

      return redirect()->route('mantenimiento.index')->with('success', 'El registro del mantenimiento ha sido creado exitosamente,');
    
    }


}
