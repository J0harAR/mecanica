<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Herramientas;
use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;
use App\Models\Auditoria;
use App\Models\Periodo;
use App\Models\Insumos;
use Illuminate\Support\Str;
class HerramientasController extends Controller
{
    function _construct()
    {
        $this->middleware('permission:ver-herramientas', ['only' => ['index']]);
        $this->middleware('permission:crear-herramienta', ['only' => ['store']]);
        $this->middleware('permission:editar-herramienta', ['only' => ['update']]);
        $this->middleware('permission:borrar-herramienta', ['only' => ['destroy']]);
    }


    public function index()
    {
      
        //Se filtran todas las herramientas pero con la relacion de catalogo articulos
        $herramientas = Herramientas::with('Articulo_inventariados.Catalogo_articulos')->get();
        $insumos=Insumos::all();//Se retornan todos los insumos
        $periodos=Periodo::all();//Se retornan todos los periodos
        $articulos=Catalogo_articulo::where('tipo',"Herramientas")->get();//Todos los articulos solo de tipo herramientas
       
        return view('herramientas.index',compact('herramientas','insumos','periodos','articulos'));
    }

    public function store(Request $request){   
        
        set_time_limit(180);//se especifica 180 segundos de tiempo de espera por si se agregan en masa
        //Guardamos las requests
        $estatus=$request->input('estatus');
        $cantidad_articulo=$request->input('cantidad');
        $condicion_herramienta=$request->input('condicion_herramienta');
        $tipo="Herramientas";//Especificamos el tipo para la tabla
        
        //Guardamos el codigo del articulo y su dimension 
        $codigo=$request->input('id_articulo');     
        
        $dimension=$this->obtenerDimension($codigo);//Aqui se usa un metodo para obtener la dimension directo del codigo

     
        //Revismos primero que el articulo exista 
        if(Catalogo_articulo::where('id_articulo',$codigo)->exists()){
            //Guaramos ese articulo
            $articulo = Catalogo_articulo::find($codigo);
            $articulo->id_articulo=$codigo;
            
            //Aqui vamos a generar el codigo del inventario con el metodo que recibe un objeto articulo y la cantidad 
            $codigos_inventario=$this->generadorCodigoInventario($articulo,$cantidad_articulo);//Este retornara unos codigos dependiendo de la cantidad
           
            //Interamos sobre la cantidad de articulo
            for ($i = 0; $i < $cantidad_articulo; $i++) {
                //Es decir se va ir guardando cada articulo inventariado por cada iteracion es decir por la cantidad
                 $articulo_inventariado=new Articulo_inventariado;   
                 $herramienta=new Herramientas;//Creamos la herramienta
                 $auditoria=new Auditoria;//Creamos la auditoria
                //Se guarda en el inventariado
                 $articulo_inventariado->id_inventario=$codigos_inventario[$i];//Se le asigna el codigo que se genero en el metodo
                 $articulo_inventariado->id_articulo=$codigo;
                 $articulo_inventariado->estatus=$estatus;
                 $articulo_inventariado->tipo=$tipo;
                //Se guarda la herramienta
                 $herramienta->id_herramientas=$codigos_inventario[$i];
                 $herramienta->condicion=$condicion_herramienta;
                 $herramienta->dimension=$dimension;
                //Esta data nos permite llevar el registro de lo que se agrego 
                 $data = [
                    'id_inventario' => $codigos_inventario[$i],
                    'id_articulo' => $codigo,
                    'estatus' => $estatus,
                    'tipo'=>$tipo
                ];

                 
                 $articulo_inventariado->save();
                 $herramienta->save();
                //Se guarda la auditoria
                 $auditoria->event='created';
                 $auditoria->subject_type=Articulo_inventariado::class;
                 $auditoria->subject_id=$codigos_inventario[$i];
                 $auditoria->cause_id=auth()->id();
                 $auditoria->old_data=json_encode([]);
                 $auditoria->new_data=json_encode($data);//La data se guarda en formato json
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
            
                return redirect()->route('herramientas.index')->with('success', 'Herramienta registrada exitosamente: ' . $articulo->nombre);
            }   

            

    }

    public function update(Request $request,$id_herramientas)
    {

        //Validamos que no se dejen campos en blanco
        $this->validate($request, [
            'condicion_herramienta' => 'required',
            'estatus' => 'required',
        ]);

        

        //Se guaradan las requests de las unicas que se van a poder actualizar
        $condicion_herramienta=$request->input('condicion_herramienta');
        $estatus_herramienta=$request->input('estatus');

        //Creamos auditoria tanto para herramienta y para inventario
        $auditoria_herramienta=new Auditoria;
        $auditoria_inventario=new Auditoria;
       
        //Revisamos que exista el articulo
        $articulo_inventariado=Articulo_inventariado::find($id_herramientas);   

        if($articulo_inventariado->estatus !== $estatus_herramienta) {//Si cambia el estatus de la herramienta lo registramos en el auditoria
            //Parte del historial para el estatus
            $auditoria_inventario->event='updated';
            $auditoria_inventario->subject_type=Articulo_inventariado::class;
            $auditoria_inventario->subject_id=$articulo_inventariado->id_inventario;
            $auditoria_inventario->cause_id=auth()->id();
            $auditoria_inventario->old_data=json_encode($articulo_inventariado);
            
            $articulo_inventariado->estatus=$estatus_herramienta;//Actualizamos el estatus
            $articulo_inventariado->save();        
            
            $auditoria_inventario->new_data=json_encode($articulo_inventariado);
            $auditoria_inventario->save();
         
        }

        //Buscamos la herramienta 
        $herramienta=Herramientas::find($id_herramientas);

        if($herramienta->condicion!== $condicion_herramienta){//Si la condicion de la herramienta cambia lo registramos en la auditoria
            $auditoria_herramienta->event='updated';
            $auditoria_herramienta->subject_type=Herramientas::class;
            $auditoria_herramienta->subject_id=$herramienta->id_herramientas;
            $auditoria_herramienta->cause_id=auth()->id();
            $auditoria_herramienta->old_data=json_encode($herramienta);

            $herramienta->condicion=$condicion_herramienta;//Actualizamos la condicion
            $herramienta->save();

            $auditoria_herramienta->new_data=json_encode($herramienta);
            $auditoria_herramienta->save();

        }

        
        return redirect()->route('herramientas.index')->with('success', 'La herramienta: ' . $id_herramientas . ' ha sido actualizada exitosamente.');

    }




    public function destroy($id_herramientas)
    {
        //Buscamos la herramienta que vamos a eliminar
        $herramienta=Articulo_inventariado::find($id_herramientas);
        $auditoria=new Auditoria;//Vamos a registrar en auditoria la eliminacion
        $auditoria->event='deleted';  
        $auditoria->subject_type=Herramientas::class;
        $auditoria->subject_id=$herramienta->id_inventario;
        $auditoria->cause_id=auth()->id();
        $auditoria->old_data=json_encode($herramienta);
        $auditoria->new_data=json_encode([]);
        $auditoria->save();

       //Vamos atualizar Catalogo para in disminuyendo por cada herramienta que se elimine
        $catalogo_articulo=Catalogo_articulo::find($herramienta->id_articulo);
        if($catalogo_articulo->cantidad>0){//Validamos que va disminuir siempre y cuando sea mayor a 0 asi evitamos el -1 o numeros negativos
            $catalogo_articulo->cantidad-=1;//Diminuimos la cantidad en el catalogo
            $catalogo_articulo->save();

        }
        
        $herramienta->delete();
        return redirect()->route('herramientas.index')->with('success', 'La herramienta: ' . $id_herramientas . ' ha sido eliminada exitosamente.');


       
    }

   
    //Este metodo obtiene la dimension del codigo de la herramienta es decir 
    //TC-GT-234 ese es un ejemplo lo que retorna es el 234 que corresponde a la dimension
    public function obtenerDimension($cadenaCompleta){
        
        $partes = explode('-', $cadenaCompleta);//Aqui va separar por guion medio
         if (isset($partes[2])) {
                return (int) $partes[2];//Parse la dimesion 
        }
    }
    
    //Este metodo generara el codigo del inventario por cantidad
public function generadorCodigoInventario(Catalogo_articulo $catalogo_articulo,$Cantidad) {
      
    //Obtendra los ultimos digitos del inventario es decir TC-GT-234-01 eso regresara el 01
    $ultimo_codigo = Articulo_Inventariado::where('id_articulo', $catalogo_articulo->id_articulo)
    ->orderBy('id_inventario', 'desc')
    ->value('id_inventario');

    //Si no existe codigo entonces significa que sera el primero
    if($ultimo_codigo==null){       
            //HC-M-0234-00
            $ultimo_codigo=$catalogo_articulo->id_articulo."-00";//Se le asigna el 00 ya que de ahi aumentara 1 el codigo    
    }

    $ultimo_numero = intval(substr($ultimo_codigo, -2)); //Aqui vamos a extraer los ultimos 2 caracteres del ultimo digito
    
    $nuevo_codigos = [];//Inicializamos el array para los codigos
    $cantidad_productos = $Cantidad;//Especificamos la cantidad de productos que se reciben en el parametro

    //Iteramos  desde el ultimo nuemro ya parceado hasta el ultimo numero + cantidad de los productos
    for ($i = $ultimo_numero + 1; $i <= $ultimo_numero + $cantidad_productos; $i++) {
        $numero_formateado = str_pad($i, 2, "0", STR_PAD_LEFT);//Aqui se crea el 01 o 02 es decir se le agrega el 0
        $nuevo_codigo = substr($ultimo_codigo, 0, -2). $numero_formateado;//Se concatena el numero ya formateado es decir el  SM-PC-0041 pasa a SM-PC-0042
        $nuevo_codigos[] = $nuevo_codigo;//Se guarda los codigos y se retornan
    }

    
    return $nuevo_codigos;
}
















}
