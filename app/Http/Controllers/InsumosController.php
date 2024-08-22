<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumos;
use App\Models\Catalogo_articulo;
use App\Models\Articulo_inventariado;
use App\Models\Auditoria;
use App\Models\Periodo;

class InsumosController extends Controller
{

    function _construct()
    {
        $this->middleware('permission:ver-insumos', ['only' => ['index']]);
        $this->middleware('permission:crear-insumo', ['only' => ['store']]);
        $this->middleware('permission:editar-insumo', ['only' => ['update']]);
        $this->middleware('permission:borrar-insumo', ['only' => ['destroy']]);
    }



    public function index()
    {
      
        //Se retornan todos los insumos con su relacion de catalogo articulos y se retornan tambien los periodos
        $insumos = Insumos::with('Articulo_inventariados.Catalogo_articulos')->get();
        $periodos=Periodo::all();
        $articulos=Catalogo_articulo::where('tipo',"Insumos")->get();//Se retorna solo los articulos de tipo insumo
     
        return view('insumos.index',compact('insumos','periodos','articulos'));
    }



    public function store(Request $request){
        set_time_limit(180);//Delimitamos a 180 la respueta por si se agregan en masa
        
        //guardamos los requests
        $estatus=$request->input('estatus');
        $cantidad_articulo=$request->input('cantidad');
        $capacidad_insumo=$request->input('capacidad_insumo');
        $tipo="Insumos";//Especificamos que sera de tipo insumo

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
                $insumo=new Insumos;//Creamos el insumo
                $auditoria=new Auditoria;//Creamos la auditoria

                //Creamos el articulo inventariado
                $articulo_inventariado->id_inventario=$codigos_inventario[$i];//Asignamos el codigo que se genero en el metodo de generador
                $articulo_inventariado->id_articulo=$codigo;
                $articulo_inventariado->estatus=$estatus;
                $articulo_inventariado->tipo=$tipo; 
                //Creamos el insumo
                $insumo->id_insumo=$codigos_inventario[$i];   
                $insumo->capacidad=$capacidad_insumo;


                 //Esta data nos permite llevar el registro de lo que se agrego 
                $data = [
                    'id_inventario' => $codigos_inventario[$i],
                    'id_articulo' => $codigo,
                    'estatus' => $estatus,
                    'tipo'=>$tipo
                ];
                //Se guardan el articulo inventariado y el insumo
                $articulo_inventariado->save();
                $insumo->save();

                $auditoria->event='created';
                $auditoria->subject_type=Articulo_inventariado::class;
                $auditoria->subject_id=$codigos_inventario[$i];
                $auditoria->cause_id=auth()->id();
                $auditoria->old_data=json_encode([]);
                $auditoria->new_data=json_encode($data);//Aqui se guarda  la data
                $auditoria->save();
            
            }

             //Esta auditoria se hace para registrar el cambio en la cantidad del catalogo
            $auditoria=new Auditoria;
            $auditoria->event='updated';
            $auditoria->subject_type=Catalogo_articulo::class;
            $auditoria->subject_id=$articulo->id_articulo;
            $auditoria->cause_id=auth()->id();
            $auditoria->old_data=json_encode($articulo->toArray());


            $articulo->cantidad+=$cantidad_articulo;// Se actualiza la cantidad general del catalogo 
            $articulo->save();

            $auditoria->new_data=json_encode($articulo->toArray());
            $auditoria->save();
            
        return redirect()->route('insumos.index')->with('success', 'El insumo ha sido registrado exitosamente: ' . $articulo->nombre);
            
            
    }


    }

    public function update(Request $request,$id_insumo)
    {
        //Guardamos los requests
        $estatus_insumo=$request->input('estatus');
        $capacidad=$request->input('capacidad');
        $auditoria_insumo=new Auditoria;

        //Buscamos el articulo por el id de insumo
        $articulo_inventariado=Articulo_inventariado::find($id_insumo); 
        $insumo=Insumos::find($id_insumo);
        //Actualizamos la capacidad del insumo
        $insumo->capacidad=$capacidad;
        $insumo->save();

       
        if($articulo_inventariado->estatus!==$estatus_insumo){ //Si el estatus cambia  se guardara en la auditoria
            $auditoria_insumo->event='updated';
            $auditoria_insumo->subject_type=Articulo_inventariado::class;
            $auditoria_insumo->subject_id=$articulo_inventariado->id_inventario;
            $auditoria_insumo->cause_id=auth()->id();
            $auditoria_insumo->old_data=json_encode($articulo_inventariado);
            
            $articulo_inventariado->estatus=$estatus_insumo;//Se actualiza el estatus
            $articulo_inventariado->save();

            $auditoria_insumo->new_data=json_encode($articulo_inventariado);
            $auditoria_insumo->save();
        }



  
        return redirect()->route('insumos.index')->with('success', 'El insumo con id: ' . $id_insumo . ' ha sido actualizado exitosamente.');
    }




    public function destroy($id_insumo)
    {

        //Buscamos el insumo que vamos a eliminar
        $insumo=Articulo_inventariado::find($id_insumo);
        $auditoria=new Auditoria;//Vamos a crear la auditoria para tener el registro
        $auditoria->event='deleted';  
        $auditoria->subject_type=Insumos::class;
        $auditoria->subject_id=$insumo->id_inventario;
        $auditoria->cause_id=auth()->id();
        $auditoria->old_data=json_encode($insumo);
        $auditoria->new_data=json_encode([]);
        $auditoria->save();

        //Vamos atualizar Catalogo para ir disminuyendo por cada insumo que se elimine
        $catalogo_articulo=Catalogo_articulo::find($insumo->id_articulo);
        if($catalogo_articulo->cantidad>0){//Validamos que va disminuir siempre y cuando sea mayor a 0 asi evitamos el -1 o numeros negativos
            $catalogo_articulo->cantidad-=1;//Diminuimos la cantidad en el catalogo
            $catalogo_articulo->save();

        }
        $insumo->delete();
        return redirect()->route('insumos.index')->with('success', 'El insumo con id: ' . $id_insumo. ' ha sido eliminado exitosamente.');

       
    }


public function generadorCodigoInventario(Catalogo_articulo $catalogo_articulo,$Cantidad) {
      //Obtendra los ultimos digitos del inventario es decir AI01 eso regresara el 01
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
            $nuevo_codigo = substr($ultimo_codigo, 0, -2) . $numero_formateado;//Se concatena el numero ya formateado es decir el  AI00 pasa a AI01
            $nuevo_codigos[] = $nuevo_codigo;//Se guarda los codigos y se retornan
        }
        
    return $nuevo_codigos;
}



}
