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
      

        $herramientas = Herramientas::with('Articulo_inventariados.Catalogo_articulos')->get();
        $insumos=Insumos::all();
        $periodos=Periodo::all();
        $articulos=Catalogo_articulo::all();
        //foreach ($herramientas as $herramienta) {
          //  echo $herramienta->Articulo_inventariados->Catalogo_articulos->nombre;
      //  }
        
        return view('herramientas.index',compact('herramientas','insumos','periodos','articulos'));
    }

    public function store(Request $request){   
        
        set_time_limit(180);

        $estatus=$request->input('estatus');
        $cantidad_articulo=$request->input('cantidad');
        $condicion_herramienta=$request->input('condicion_herramienta');
        $tipo="Herramientas";
        

        $codigo=$request->input('id_articulo');     
        
        $dimension=$this->obtenerDimension($codigo);

     

        if(Catalogo_articulo::where('id_articulo',$codigo)->exists()){

            $articulo = Catalogo_articulo::find($codigo);
            $articulo->id_articulo=$codigo;
                      
            $codigos_inventario=$this->generadorCodigoInventario($articulo,$cantidad_articulo);
           
        
            for ($i = 0; $i < $cantidad_articulo; $i++) {
                 $articulo_inventariado=new Articulo_inventariado;   
                 $herramienta=new Herramientas;
                 $auditoria=new Auditoria;



                 $articulo_inventariado->id_inventario=$codigos_inventario[$i];
                 $articulo_inventariado->id_articulo=$codigo;
                 $articulo_inventariado->estatus=$estatus;
                 $articulo_inventariado->tipo=$tipo;
                 
                 $herramienta->id_herramientas=$codigos_inventario[$i];
                 $herramienta->condicion=$condicion_herramienta;
                 $herramienta->dimension=$dimension;

                 $data = [
                    'id_inventario' => $codigos_inventario[$i],
                    'id_articulo' => $codigo,
                    'estatus' => $estatus,
                    'tipo'=>$tipo
                ];

                 

                 $articulo_inventariado->save();
                 $herramienta->save();

                 $auditoria->event='created';
                 $auditoria->subject_type=Articulo_inventariado::class;
                 $auditoria->subject_id=$codigos_inventario[$i];
                 $auditoria->cause_id=auth()->id();
                 $auditoria->old_data=json_encode([]);
                 $auditoria->new_data=json_encode($data);
                 $auditoria->save();

             }

            $auditoria=new Auditoria;
            $auditoria->event='updated';
            $auditoria->subject_type=Catalogo_articulo::class;
            $auditoria->subject_id=$articulo->id_articulo;
            $auditoria->cause_id=auth()->id();
            $auditoria->old_data=json_encode($articulo->toArray());
    
            $articulo->cantidad+=$cantidad_articulo;
            $articulo->save();
        
            $auditoria->new_data=json_encode($articulo->toArray());
            $auditoria->save();
            
                return redirect()->route('herramientas.index')->with('success', 'Herramienta registrada exitosamente: ' . $articulo->nombre);
            }   

            

    }

    public function update(Request $request,$id_herramientas)
    {


        $this->validate($request, [
            'condicion_herramienta' => 'required',
            'estatus' => 'required',
        ]);

        

        //Request
        $condicion_herramienta=$request->input('condicion_herramienta');
        $estatus_herramienta=$request->input('estatus');


        $auditoria_herramienta=new Auditoria;
        $auditoria_inventario=new Auditoria;
       

        $articulo_inventariado=Articulo_inventariado::find($id_herramientas);   

        if($articulo_inventariado->estatus !== $estatus_herramienta) {
            //Parte del historial 
            $auditoria_inventario->event='updated';
            $auditoria_inventario->subject_type=Articulo_inventariado::class;
            $auditoria_inventario->subject_id=$articulo_inventariado->id_inventario;
            $auditoria_inventario->cause_id=auth()->id();
            $auditoria_inventario->old_data=json_encode($articulo_inventariado);
            
            $articulo_inventariado->estatus=$estatus_herramienta;
            $articulo_inventariado->save();        
            
            $auditoria_inventario->new_data=json_encode($articulo_inventariado);
            $auditoria_inventario->save();
         
        }

       
        $herramienta=Herramientas::find($id_herramientas);

        if($herramienta->condicion!== $condicion_herramienta){
            $auditoria_herramienta->event='updated';
            $auditoria_herramienta->subject_type=Herramientas::class;
            $auditoria_herramienta->subject_id=$herramienta->id_herramientas;
            $auditoria_herramienta->cause_id=auth()->id();
            $auditoria_herramienta->old_data=json_encode($herramienta);

            $herramienta->condicion=$condicion_herramienta;
            $herramienta->save();

            $auditoria_herramienta->new_data=json_encode($herramienta);
            $auditoria_herramienta->save();

        }

        
        return redirect()->route('herramientas.index')->with('success', 'La herramienta: ' . $id_herramientas . ' ha sido actualizada exitosamente.');

    }




    public function destroy($id_herramientas)
    {
        $herramienta=Articulo_inventariado::find($id_herramientas);
        $auditoria=new Auditoria;
        $auditoria->event='deleted';  
        $auditoria->subject_type=Herramientas::class;
        $auditoria->subject_id=$herramienta->id_inventario;
        $auditoria->cause_id=auth()->id();
        $auditoria->old_data=json_encode($herramienta);
        $auditoria->new_data=json_encode([]);
        $auditoria->save();

       //Actualizar Catalogo articulos
        $catalogo_articulo=Catalogo_articulo::find($herramienta->id_articulo);
        if($catalogo_articulo->cantidad>0){
            $catalogo_articulo->cantidad-=1;
            $catalogo_articulo->save();

        }
        
        $herramienta->delete();
        return redirect()->route('herramientas.index')->with('success', 'La herramienta: ' . $id_herramientas . ' ha sido eliminada exitosamente.');


       
    }

   

    public function obtenerDimension($cadenaCompleta){
        
        $partes = explode('-', $cadenaCompleta);
         if (isset($partes[2])) {
                return (int) $partes[2];
        }
    }

public function generadorCodigoInventario(Catalogo_articulo $catalogo_articulo,$Cantidad) {
      

    $ultimo_codigo = Articulo_Inventariado::where('id_articulo', $catalogo_articulo->id_articulo)
    ->orderBy('id_inventario', 'desc')
    ->value('id_inventario');

    
    if($ultimo_codigo==null){       
            //HC-M-0234-00
            $ultimo_codigo=$catalogo_articulo->id_articulo."-00";        
    }

    $ultimo_numero = intval(substr($ultimo_codigo, -2)); 
    
    $nuevo_codigos = [];
    $cantidad_productos = $Cantidad;

    for ($i = $ultimo_numero + 1; $i <= $ultimo_numero + $cantidad_productos; $i++) {
        $numero_formateado = str_pad($i, 2, "0", STR_PAD_LEFT);
        $nuevo_codigo = substr($ultimo_codigo, 0, -2). $numero_formateado;
        $nuevo_codigos[] = $nuevo_codigo;
    }

    
    return $nuevo_codigos;
}
















}
