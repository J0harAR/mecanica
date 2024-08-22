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
    
        //Se retornan las maquinarias con la relacion de catalogo articulo
      $maquinaria = Maquinaria::with(['Articulo_inventariados.Catalogo_articulos','insumos'])->get();
      $insumos=Articulo_inventariado::where('tipo','Insumos')->get();
      $articulos=Catalogo_articulo::where('tipo',"Maquinaria")->get();//Se retorna solo los articulos de tipo maquinaria
      $periodos=Periodo::all();

      
      return view('maquinaria.index',compact('maquinaria','insumos','periodos','articulos'));
  }

  public function store(Request $request){
    set_time_limit(180);//Delimitamos a 180 la respueta por si se agregan en masa
    //guardamos los requests
    $estatus=$request->input('estatus');
    $cantidad_articulo=$request->input('cantidad');
    $tipo="Maquinaria";//Especificamos que sera de tipo insumo

    //Al momento de crear una maquinaria se le puede asignar sus insumos y su cantidades 
    $insumosCapacidad = $request->input('insumos', []);
    $insumosCantidadActual = $request->input('insumos-cantidad-actual', []);
    $insumosCantidadMinima = $request->input('insumos-cantidad-minima', []);

   

      //Guardamos el codigo del articulo
    $codigo=$request->input('id_articulo'); 
    //Verificamos que existe en el catalogo
      if(Catalogo_articulo::where('id_articulo',$codigo)->exists()){
        $articulo = Catalogo_articulo::find($codigo);
        $articulo->id_articulo=$codigo;
        $codigos_inventario=$this->generadorCodigoInventario($articulo,$cantidad_articulo);//Aqui vamos a generar el codigo de inventario a partir de la cantidad
        //Vamos a iterar sobre la cantidad de los articulo
        for ($i = 0; $i < $cantidad_articulo; $i++) {
            $articulo_inventariado=new Articulo_inventariado;//Vamos a crear un articulo inventario por la cantidad
            $maquinaria=new Maquinaria;//Creamos la maquinaria
            $auditoria=new Auditoria;//Creamos la auditoria
            //Creamos el articulo inventariado
            $articulo_inventariado->id_inventario=$codigos_inventario[$i];
            $articulo_inventariado->id_articulo=$codigo;
            $articulo_inventariado->estatus=$estatus;
            $articulo_inventariado->tipo=$tipo; 

            $maquinaria->id_maquinaria=$codigos_inventario[$i];   
            
            //Esta data nos permite llevar el registro de lo que se agrego 
            $data = [
                'id_inventario' => $codigos_inventario[$i],
                'id_articulo' => $codigo,
                'estatus' => $estatus,
                'tipo'=>$tipo
            ];

            //Se guardan el articulo inventariado y el insumo
            $articulo_inventariado->save();

            $id_maquina=$codigos_inventario[$i];
            $maquinaria->save();
            
            //Buscamos la maquinaria 
            $maquinaria=Maquinaria::find($id_maquina);
            //Aqui vamos asigar los insumos y vamos iterar entre la cantidad actual
            //El $id en el for each significa que es el Id del insumo que se ingreso
            foreach ($insumosCantidadActual as $id => $cantidadActual) {
                if (isset($insumosCapacidad[$id])) {
                    $capacidad = $insumosCapacidad[$id];
        
                    //Revisamos que la cantidad actual no sea mayor a la capacidad
                    if ($cantidadActual > $capacidad) {
                        return redirect()->route('maquinaria.index')->with('error', 'La cantidad actual no puede ser mayor que la capacidad para el insumo con ID: ' . $id);
                    }
                    
                    if (isset($insumosCantidadMinima[$id])) {
                        $cantidadMinima = $insumosCantidadMinima[$id];
                        //Revisamos que la cantidad minima no sea mayor a la capacidad
                        if ($cantidadMinima > $capacidad) {
                            return redirect()->route('maquinaria.index')->with('error', 'La cantidad mínima no puede ser mayor que la capacidad para el insumo con ID: ' . $id);
                        }
        
                        //Revisamos  que la cantidad actual no sea mayot a la cantidad minima
                        if ($cantidadActual < $cantidadMinima) {
                            return redirect()->route('maquinaria.index')->with('error', 'La cantidad actual no puede ser menor que la cantidad mínima para el insumo con ID: ' . $id);
                        }
                    } else {//En este caso se valida que si se haya ingresado una cantidad minima
                        return redirect()->route('maquinaria.index')->with('error', 'Cantidad mínima no definida para el insumo con ID: ' . $id);
                    }
                } else {//En este caso se valida que si se haya ingresado una capacidad 
                    return redirect()->route('maquinaria.index')->with('error', 'Capacidad no definida para el insumo con ID: ' . $id);
                }
            }

            //Vamos a juntar los que es las capacidades en una coleccion y poder asignarla a la maquinaria
            $insumos = collect($insumosCapacidad)->mapWithKeys(function ($capacidad, $id) use ($insumosCantidadActual, $insumosCantidadMinima) {
                return [
                    $id => [
                        'capacidad' => $capacidad,
                        'cantidad_actual' => $insumosCantidadActual[$id] ?? null,
                        'cantidad_minima' => $insumosCantidadMinima[$id] ?? null,
                    ],
                ];
            });
           
            $maquinaria->insumos()->sync($insumos);//Aqui asignamos la coleccion a la maquinaria

            $auditoria->event='created';
            $auditoria->subject_type=Articulo_inventariado::class;
            $auditoria->subject_id=$codigos_inventario[$i];
            $auditoria->cause_id=auth()->id();
            $auditoria->old_data=json_encode([]);
            $auditoria->new_data=json_encode($data);
            $auditoria->save();//Guardamos la auditoria para que quede registros
          
        }
        $articulo->cantidad+=$cantidad_articulo;//Aumentamos la cantidad del catalogo de esa maquinaria
        $articulo->save();



      
        return redirect()->route('maquinaria.index')->with('success', 'Maquinaria registrada exitosamente: ' . $articulo->nombre);
    }
   
  }


  public function update(Request $request,$id_maquinaria)
  {
      //Guardamos el estatus de la request
      $estatus_maquinaria=$request->input('estatus');
    //Creamos una auditoria
      $auditoria_inventario=new Auditoria;
        //Buscamos el articulo que vamos a actualizar
      $articulo_inventariado=Articulo_inventariado::find($id_maquinaria);      
        //Si el estatus cambia creamos la auditoria
        if($articulo_inventariado->estatus !== $estatus_maquinaria){
            $auditoria_inventario->event='updated';
            $auditoria_inventario->subject_type=Articulo_inventariado::class;
            $auditoria_inventario->subject_id=$articulo_inventariado->id_inventario;
            $auditoria_inventario->cause_id=auth()->id();
            $auditoria_inventario->old_data=json_encode($articulo_inventariado);

            $articulo_inventariado->estatus=$estatus_maquinaria;//Se actualiza el estatus
            $articulo_inventariado->save();

            $auditoria_inventario->new_data=json_encode($articulo_inventariado);
            $auditoria_inventario->save();
        }

        //Buscamos la maquinaria que se va actualizar
        $maquinaria=Maquinaria::find($id_maquinaria);

       
        //Asignacion de los insumos a la maquinaria
        $insumosCapacidad = $request->input('insumos', []);//Esta variable sera la capacidad del insumo
        $insumosCantidadMinima = $request->input('insumos-cantidad-minima', []);
        
        //Aqui vamos asigar los insumos y vamos iterar entre la cantidad actual
        //El $id en el for each significa que es el Id del insumo que se ingreso
        foreach ($insumosCantidadMinima as $id => $cantidadMinima) {
            if (isset($insumosCapacidad[$id])) {
                $capacidad = $insumosCapacidad[$id];
        
                // Validar que la cantidad mínima no sea mayor que la capacidad
                if ($cantidadMinima > $capacidad) {
                    return redirect()->route('maquinaria.index')->with('error', 'La cantidad mínima no puede ser mayor que la capacidad para el insumo con ID: ' . $id);
                }
            } else {
                //Validacion si no se establecio la capacidad
                return redirect()->route('maquinaria.index')->with('error', 'Capacidad no definida para el insumo con ID: ' . $id);
            }
        }


        //Vamos a crear la coleccion para juntar el insumo con las capacidades
        $insumos = collect($insumosCapacidad)->mapWithKeys(function ($capacidad, $id) use ($insumosCantidadMinima) {
          return [
              $id => [
                  'capacidad' => $capacidad,
                  'cantidad_minima' => $insumosCantidadMinima[$id] ?? null,
              ],
          ];
      });
      //Aqui asignamos los insumos a la maquinaria
        $maquinaria->insumos()->sync($insumos);
             
        

      return redirect()->route('maquinaria.index')->with('success', 'La maquina: ' . $id_maquinaria . ' ha sido actualizada exitosamente.');
  }


  public function asignar_insumos( Request $request,$id_maquinaria){

    //Revisamos que se haya seleccionado algun insumo si no se retornara un error
    if(!$request->input('insumos',[])){
        return redirect()->route('maquinaria.index')->with('error',' No se selecciono ningun insumo');
    }
    //Buscamos la maquina que vamos asignar los insumos
    $maquina=Maquinaria::find($id_maquinaria);
   
    $maquina->insumos()->syncWithoutDetaching($request->input('insumos',[])); //Asignamos los insumos  a la maquinaria
  
    
    return redirect()->route('maquinaria.index')->with('success', 'Insumo asignado correctamente a la máquina: ' . $id_maquinaria . '.');
      

  }

  public function desasignar_insumo(Request $request,$id_maquinaria){
    
    //Buscamos la maquina que vamos desasignar los insumos
    $maquina=Maquinaria::find($id_maquinaria);
    //Revisamos que si no selecciono ningun insumo solo lo regrese no le retorne error
    if(!$request->input('insumos',[])){
        return redirect()->route('maquinaria.index');
    }
    $maquina->insumos()->detach($request->input('insumos',[]));//Aqui desasignamos los insumos a la maquinaria
    return redirect()->route('maquinaria.index')->with('success', 'Insumo desasignado correctamente a la máquina: ' . $id_maquinaria . '.');
  }



  public function destroy($id_maquinaria)
  {
    //Buscamos la maquinaria que vamos a eliminar
      $maquinaria=Articulo_inventariado::find($id_maquinaria);
      //Creamos la auditoria para que quede registro
      $auditoria=new Auditoria;
      $auditoria->event='deleted';  
      $auditoria->subject_type=Maquinaria::class;
      $auditoria->subject_id=$maquinaria->id_inventario;
      $auditoria->cause_id=auth()->id();
      $auditoria->old_data=json_encode($maquinaria);
      $auditoria->new_data=json_encode([]);
      $auditoria->save();



     //Vamos atualizar Catalogo para ir disminuyendo por cada maquinaria que se elimine
      $catalogo_articulo=Catalogo_articulo::find($maquinaria->id_articulo);
      if($catalogo_articulo->cantidad>0){//Validamos que va disminuir siempre y cuando sea mayor a 0 asi evitamos el -1 o numeros negativos
          $catalogo_articulo->cantidad-=1;//Diminuimos la cantidad en el catalogo
          $catalogo_articulo->save();

      }

      $maquinaria->delete();
      return redirect()->route('maquinaria.index')->with('success', 'La maquina: ' . $id_maquinaria . ' ha sido eliminada exitosamente.');

     
  }

    //Este metodo generara el codigo del inventario por cantidad
public function generadorCodigoInventario(Catalogo_articulo $catalogo_articulo,$Cantidad) {
//Obtendra los ultimos digitos del inventario es decir 03MI01 eso regresara el 01
  $ultimo_codigo = Articulo_inventariado::where('id_articulo', $catalogo_articulo->id_articulo)
  ->orderBy('id_inventario', 'desc')
  ->value('id_inventario');
   //Si no existe codigo entonces significa que sera el primero
  if($ultimo_codigo==null){
        $ultimo_codigo=$catalogo_articulo->id_articulo."00";//Se le asigna el 00 ya que de ahi aumentara 1 el codigo
  }
  $ultimo_numero = intval(substr($ultimo_codigo, -2)); //Aqui vamos a extraer los ultimos 2 caracteres del ultimo digito
  
  $nuevo_codigos = [];//Inicializamos el array para los codigos
  $cantidad_productos = $Cantidad;//Especificamos la cantidad de productos que se reciben en el parametro
 //Iteramos  desde el ultimo nuemro ya parceado hasta el ultimo numero + cantidad de los productos
    for ($i = $ultimo_numero + 1; $i <= $ultimo_numero + $cantidad_productos; $i++) {
          $numero_formateado = str_pad($i, 2, "0", STR_PAD_LEFT);//Aqui se crea el 01 o 02 es decir se le agrega el 0
          $nuevo_codigo = substr($ultimo_codigo, 0, -2) . $numero_formateado;//Se concatena el numero ya formateado es decir el  03MI00 pasa a 03MI01
          $nuevo_codigos[] = $nuevo_codigo;//Se guarda los codigos y se retornan
    }
      
  return $nuevo_codigos;
}



}
