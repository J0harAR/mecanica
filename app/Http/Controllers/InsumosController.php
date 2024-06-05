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
    public function index()
    {
      
  
        $insumos = Insumos::with('Articulo_inventariados.Catalogo_articulos')->get();
        $periodos=Periodo::all();
        //foreach ($herramientas as $herramienta) {
          //  echo $herramienta->Articulo_inventariados->Catalogo_articulos->nombre;
      //  }
        
        return view('insumos.index',compact('insumos','periodos'));
    }



    public function store(Request $request){
        $nombre_articulo=$request->input('nombre');
        $seccion_articulo=$request->input('seccion');
        $estatus=$request->input('estatus');
        $cantidad_articulo=$request->input('cantidad');
        $capacidad_insumo=$request->input('capacidad_insumo');
        $tipo_insumo=$request->input('tipo_insumo');
        $tipo="Insumos";

       
        $codigo=$this->generadorCodigoArticulo($nombre_articulo);

        if(Catalogo_articulo::where('id_articulo',$codigo)->exists()){
            $articulo = Catalogo_articulo::find($codigo);
            $articulo->id_articulo=$codigo;
            $codigos_inventario=$this->generadorCodigoInventario($articulo,$cantidad_articulo);

            for ($i = 0; $i < $cantidad_articulo; $i++) {
                $articulo_inventariado=new Articulo_inventariado;
                $insumo=new Insumos;
                $auditoria=new Auditoria;

                $articulo_inventariado->id_inventario=$codigos_inventario[$i];
                $articulo_inventariado->id_articulo=$codigo;
                $articulo_inventariado->estatus=$estatus;
                $articulo_inventariado->tipo=$tipo; 

                $insumo->id_insumo=$codigos_inventario[$i];   
                $insumo->capacidad=$capacidad_insumo;


                $data = [
                    'id_inventario' => $codigos_inventario[$i],
                    'id_articulo' => $codigo,
                    'estatus' => $estatus,
                    'tipo'=>$tipo
                ];

                $articulo_inventariado->save();
                $insumo->save();

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
            
            
    }else{
        
        $articulo_nuevo = new Catalogo_articulo;
        $articulo_nuevo->id_articulo= $codigo;
        $articulo_nuevo->nombre=$nombre_articulo;
        $articulo_nuevo->cantidad=$cantidad_articulo;
        $articulo_nuevo->seccion=null;
        $articulo_nuevo->tipo=$tipo_insumo;
        $articulo_nuevo->save();
        $articulo = Catalogo_articulo::find($codigo);


         //Aqui creamos el historial de agregar cuando aun no existe ninguno en base de datos
         $auditoria=new Auditoria;
         $auditoria->event='created';
         $auditoria->subject_type=Catalogo_articulo::class;
         $auditoria->subject_id=$articulo->id_articulo;
         $auditoria->cause_id=auth()->id();
         $auditoria->old_data=json_encode([]);
         $auditoria->new_data=json_encode($articulo->toArray());
         $auditoria->save();
    

        $codigos_inventario=$this->generadorCodigoInventario($articulo,$cantidad_articulo);
        for ($i = 0; $i < $articulo->cantidad; $i++) {
            $articulo_inventariado=new Articulo_inventariado;
            $insumo=new Insumos;
            $auditoria=new Auditoria;

            $articulo_inventariado->id_inventario=$codigos_inventario[$i];
            $articulo_inventariado->id_articulo=$codigo;
            $articulo_inventariado->estatus=$estatus;
            $articulo_inventariado->tipo=$tipo;   
                        
            $insumo->id_insumo=$codigos_inventario[$i];   
            $insumo->capacidad=$capacidad_insumo;

            $data = [
                'id_inventario' => $codigos_inventario[$i],
                'id_articulo' => $codigo,
                'estatus' => $estatus,
                'tipo'=>$tipo
            ];
                                
            $articulo_inventariado->save();
            $insumo->save();

            $auditoria->event='created';
            $auditoria->subject_type=Articulo_inventariado::class;
            $auditoria->subject_id=$codigos_inventario[$i];
            $auditoria->cause_id=auth()->id();
            $auditoria->old_data=json_encode([]);
            $auditoria->new_data=json_encode($data);
            $auditoria->save();

        }

    }

    return redirect()->route('insumos.index')->with('success', 'El insumo ha sido registrado exitosamente: ' . $nombre_articulo);

    }

    public function update(Request $request,$id_insumo)
    {
        //Request
        $estatus_insumo=$request->input('estatus');
        $capacidad=$request->input('capacidad');
        $auditoria_insumo=new Auditoria;

        $articulo_inventariado=Articulo_inventariado::find($id_insumo); 
        $insumo=Insumos::find($id_insumo);
        
        $insumo->capacidad=$capacidad;
        $insumo->save();
        
        if($articulo_inventariado->estatus!==$estatus_insumo){
            $auditoria_insumo->event='updated';
            $auditoria_insumo->subject_type=Articulo_inventariado::class;
            $auditoria_insumo->subject_id=$articulo_inventariado->id_inventario;
            $auditoria_insumo->cause_id=auth()->id();
            $auditoria_insumo->old_data=json_encode($articulo_inventariado);
            
            $articulo_inventariado->estatus=$estatus_insumo;
            $articulo_inventariado->save();

            $auditoria_insumo->new_data=json_encode($articulo_inventariado);
            $auditoria_insumo->save();
        }



  
        return redirect()->route('insumos.index')->with('success', 'El insumo con id: ' . $id_insumo . ' ha sido actualizado exitosamente.');
    }




    public function destroy($id_insumo)
    {
        $insumo=Articulo_inventariado::find($id_insumo);
        $auditoria=new Auditoria;
        $auditoria->event='deleted';  
        $auditoria->subject_type=Insumos::class;
        $auditoria->subject_id=$insumo->id_inventario;
        $auditoria->cause_id=auth()->id();
        $auditoria->old_data=json_encode($insumo);
        $auditoria->new_data=json_encode([]);
        $auditoria->save();

       //Actualizar Catalogo articulos
        $catalogo_articulo=Catalogo_articulo::find($insumo->id_articulo);
        if($catalogo_articulo->cantidad>0){
            $catalogo_articulo->cantidad-=1;
            $catalogo_articulo->save();

        }else{
            $catalogo_articulo->cantidad=0;
            $catalogo_articulo->save();
        }
        $insumo->delete();
        return redirect()->route('insumos.index')->with('success', 'El insumo con id: ' . $id_insumo. ' ha sido eliminado exitosamente.');

       
    }


    public function generadorCodigoArticulo(String $nombre){
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
        $codigo=$iniciales_nombre;
     
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

    public function contarGuionesMedios($cadena) {
        $conteo = substr_count($cadena, "-");
        return $conteo;
    }

}
