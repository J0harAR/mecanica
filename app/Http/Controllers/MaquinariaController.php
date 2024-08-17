<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maquinaria;
use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;
use App\Models\Auditoria;
use App\Models\Periodo;
use App\Models\Insumos;
class MaquinariaController extends Controller
{
    function _construct()
    {
        $this->middleware('permission:ver-maquinarias', ['only' => ['index']]);
        $this->middleware('permission:crear-maquinaria', ['only' => ['store']]);
        $this->middleware('permission:editar-maquinaria', ['only' => ['update']]);
        $this->middleware('permission:asignar-insumos-maquinaria', ['only' => ['asignar_insumos']]);
        $this->middleware('permission:desasignar-insumos-maquinaria', ['only' => ['desasignar_insumo']]);
        $this->middleware('permission:borrar-maquinaria', ['only' => ['destroy']]);
    }



  public function index()
  {
    

      $maquinaria = Maquinaria::with(['Articulo_inventariados.Catalogo_articulos','insumos'])->get();
      $insumos=Articulo_inventariado::where('tipo','Insumos')->get();
      $articulos=Catalogo_articulo::where('tipo',"Maquinaria")->get();
      $periodos=Periodo::all();

      
      return view('maquinaria.index',compact('maquinaria','insumos','periodos','articulos'));
  }

  public function store(Request $request){
    set_time_limit(180);
    //Obtengo los inputs
    $estatus=$request->input('estatus');
    $cantidad_articulo=$request->input('cantidad');
    $tipo="Maquinaria";

    $insumosCapacidad = $request->input('insumos', []);
    $insumosCantidadActual = $request->input('insumos-cantidad-actual', []);
    $insumosCantidadMinima = $request->input('insumos-cantidad-minima', []);

   

    //Agregar en la tabla de catalogo articulo 
    $codigo=$request->input('id_articulo'); 

      if(Catalogo_articulo::where('id_articulo',$codigo)->exists()){
        $articulo = Catalogo_articulo::find($codigo);
        $articulo->id_articulo=$codigo;
        $codigos_inventario=$this->generadorCodigoInventario($articulo,$cantidad_articulo);

        for ($i = 0; $i < $cantidad_articulo; $i++) {
            $articulo_inventariado=new Articulo_inventariado;
            $maquinaria=new Maquinaria;
            $auditoria=new Auditoria;

            $articulo_inventariado->id_inventario=$codigos_inventario[$i];
            $articulo_inventariado->id_articulo=$codigo;
            $articulo_inventariado->estatus=$estatus;
            $articulo_inventariado->tipo=$tipo; 

            $maquinaria->id_maquinaria=$codigos_inventario[$i];   
            
            $data = [
                'id_inventario' => $codigos_inventario[$i],
                'id_articulo' => $codigo,
                'estatus' => $estatus,
                'tipo'=>$tipo
            ];


            $articulo_inventariado->save();

            $id_maquina=$codigos_inventario[$i];
            $maquinaria->save();
            
            $maquinaria=Maquinaria::find($id_maquina);
            //Aqui vamos asigar los insumos

            foreach ($insumosCantidadActual as $id => $cantidadActual) {
                if (isset($insumosCapacidad[$id])) {
                    $capacidad = $insumosCapacidad[$id];
        
                  
                    if ($cantidadActual > $capacidad) {
                        return redirect()->route('maquinaria.index')->with('error', 'La cantidad actual no puede ser mayor que la capacidad para el insumo con ID: ' . $id);
                    }
        
                    if (isset($insumosCantidadMinima[$id])) {
                        $cantidadMinima = $insumosCantidadMinima[$id];
                        if ($cantidadMinima > $capacidad) {
                            return redirect()->route('maquinaria.index')->with('error', 'La cantidad mínima no puede ser mayor que la capacidad para el insumo con ID: ' . $id);
                        }
        
                      
                        if ($cantidadActual < $cantidadMinima) {
                            return redirect()->route('maquinaria.index')->with('error', 'La cantidad actual no puede ser menor que la cantidad mínima para el insumo con ID: ' . $id);
                        }
                    } else {
                        return redirect()->route('maquinaria.index')->with('error', 'Cantidad mínima no definida para el insumo con ID: ' . $id);
                    }
                } else {
                    return redirect()->route('maquinaria.index')->with('error', 'Capacidad no definida para el insumo con ID: ' . $id);
                }
            }

            $insumos = collect($insumosCapacidad)->mapWithKeys(function ($capacidad, $id) use ($insumosCantidadActual, $insumosCantidadMinima) {
                return [
                    $id => [
                        'capacidad' => $capacidad,
                        'cantidad_actual' => $insumosCantidadActual[$id] ?? null,
                        'cantidad_minima' => $insumosCantidadMinima[$id] ?? null,
                    ],
                ];
            });
           
            $maquinaria->insumos()->sync($insumos);

            $auditoria->event='created';
            $auditoria->subject_type=Articulo_inventariado::class;
            $auditoria->subject_id=$codigos_inventario[$i];
            $auditoria->cause_id=auth()->id();
            $auditoria->old_data=json_encode([]);
            $auditoria->new_data=json_encode($data);
            $auditoria->save();
          
        }
        $articulo->cantidad+=$cantidad_articulo;
        $articulo->save();



      
        return redirect()->route('maquinaria.index')->with('success', 'Maquinaria registrada exitosamente: ' . $articulo->nombre);
    }
   
  }


  public function update(Request $request,$id_maquinaria)
  {
      //Request
      $estatus_maquinaria=$request->input('estatus');

      $auditoria_inventario=new Auditoria;

      $articulo_inventariado=Articulo_inventariado::find($id_maquinaria);      
    
        if($articulo_inventariado->estatus !== $estatus_maquinaria){
            $auditoria_inventario->event='updated';
            $auditoria_inventario->subject_type=Articulo_inventariado::class;
            $auditoria_inventario->subject_id=$articulo_inventariado->id_inventario;
            $auditoria_inventario->cause_id=auth()->id();
            $auditoria_inventario->old_data=json_encode($articulo_inventariado);

            $articulo_inventariado->estatus=$estatus_maquinaria;
            $articulo_inventariado->save();

            $auditoria_inventario->new_data=json_encode($articulo_inventariado);
            $auditoria_inventario->save();
        }

        $maquinaria=Maquinaria::find($id_maquinaria);

       
        //Asignacion de los insumos a la maquinaria
        $insumosCapacidad = $request->input('insumos', []);
        $insumosCantidadMinima = $request->input('insumos-cantidad-minima', []);
  
        foreach ($insumosCantidadMinima as $id => $cantidadMinima) {
            if (isset($insumosCapacidad[$id])) {
                $capacidad = $insumosCapacidad[$id];
        
                // Validar que la cantidad mínima no sea mayor que la capacidad
                if ($cantidadMinima > $capacidad) {
                    return redirect()->route('maquinaria.index')->with('error', 'La cantidad mínima no puede ser mayor que la capacidad para el insumo con ID: ' . $id);
                }
            } else {
                return redirect()->route('maquinaria.index')->with('error', 'Capacidad no definida para el insumo con ID: ' . $id);
            }
        }



        $insumos = collect($insumosCapacidad)->mapWithKeys(function ($capacidad, $id) use ($insumosCantidadMinima) {
          return [
              $id => [
                  'capacidad' => $capacidad,
                  'cantidad_minima' => $insumosCantidadMinima[$id] ?? null,
              ],
          ];
      });
      $deletedInsumos = $request->input('deleted_insumos', []);
     
        $maquinaria->insumos()->sync($insumos);
             
        

      return redirect()->route('maquinaria.index')->with('success', 'La maquina: ' . $id_maquinaria . ' ha sido actualizada exitosamente.');
  }


  public function asignar_insumos( Request $request,$id_maquinaria){

    if(!$request->input('insumos',[])){
        return redirect()->route('maquinaria.index')->with('error',' No se selecciono ningun insumo');
    }

    $maquina=Maquinaria::find($id_maquinaria);

    $maquina->insumos()->syncWithoutDetaching($request->input('insumos',[]));
  
    
    return redirect()->route('maquinaria.index')->with('success', 'Insumo asignado correctamente a la máquina: ' . $id_maquinaria . '.');
      

  }

  public function desasignar_insumo(Request $request,$id_maquinaria){
    
    $maquina=Maquinaria::find($id_maquinaria);
    if(!$request->input('insumos',[])){
        return redirect()->route('maquinaria.index');
    }
    $maquina->insumos()->detach($request->input('insumos',[]));
    return redirect()->route('maquinaria.index')->with('success', 'Insumo desasignado correctamente a la máquina: ' . $id_maquinaria . '.');
  }



  public function destroy($id_maquinaria)
  {
      $maquinaria=Articulo_inventariado::find($id_maquinaria);
      
      $auditoria=new Auditoria;
      $auditoria->event='deleted';  
      $auditoria->subject_type=Maquinaria::class;
      $auditoria->subject_id=$maquinaria->id_inventario;
      $auditoria->cause_id=auth()->id();
      $auditoria->old_data=json_encode($maquinaria);
      $auditoria->new_data=json_encode([]);
      $auditoria->save();



     //Actualizar Catalogo articulos
      $catalogo_articulo=Catalogo_articulo::find($maquinaria->id_articulo);
      if($catalogo_articulo->cantidad>0){
          $catalogo_articulo->cantidad-=1;
          $catalogo_articulo->save();

      }

      $maquinaria->delete();
      return redirect()->route('maquinaria.index')->with('success', 'La maquina: ' . $id_maquinaria . ' ha sido eliminada exitosamente.');

     
  }



  public function generadorCodigoArticulo(String $nombre,String $seccion){
    $codigo="";
    $iniciales="";
    $ignorar = ["de","para"];


    $palabras = explode(' ', $nombre);
   

    foreach ($palabras as $palabra) {
     
        if (!in_array(strtolower($palabra), $ignorar)) {
            $iniciales .= substr($palabra, 0, 1);
           
        }
    }
    $iniciales_nombre = strtoupper($iniciales);

    $codigo=$seccion.$iniciales_nombre;                          
  
    return $codigo;
}


public function generadorCodigoInventario(Catalogo_articulo $catalogo_articulo,$Cantidad) {
      
  $ultimo_codigo = Articulo_inventariado::where('id_articulo', $catalogo_articulo->id_articulo)
  ->orderBy('id_inventario', 'desc')
  ->value('id_inventario');

  if($ultimo_codigo==null){
        $ultimo_codigo=$catalogo_articulo->id_articulo."00";
  }
  $ultimo_numero = intval(substr($ultimo_codigo, -2)); 
  
  $nuevo_codigos = [];
  $cantidad_productos = $Cantidad;

    for ($i = $ultimo_numero + 1; $i <= $ultimo_numero + $cantidad_productos; $i++) {
          $numero_formateado = str_pad($i, 2, "0", STR_PAD_LEFT);
          $nuevo_codigo = substr($ultimo_codigo, 0, -2) . $numero_formateado;
          $nuevo_codigos[] = $nuevo_codigo;
    }
      
  return $nuevo_codigos;
}



}
