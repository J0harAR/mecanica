<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalogo_articulo;
use App\Models\Articulo_inventariado;
use App\Models\Herramientas;
use App\Models\Maquinaria;
use App\Models\Insumos;
use App\Models\Auditoria;

class InventarioController extends Controller
{
   
    public function index()
    {
        $articulos_inventariados=Articulo_inventariado::all();   
        $catalogo_articulo=Catalogo_articulo::all();
        $insumos=Insumos::all();
        $historial=Auditoria::all(); 
        return view('inventarios.index',compact('catalogo_articulo','historial','insumos'));
    }


    public function store(Request $request){
        //Obtengo los inputs
        $nombre_articulo=$request->input('nombre');
        $seccion_articulo=$request->input('seccion');
        $estatus=$request->input('estatus');
        $tipo=$request->input('tipo');
        $cantidad_articulo=$request->input('cantidad');
        $tipo_maquina=$request->input('tipo_maquina');
        $tipo_herramienta=$request->input('tipo_herramienta');
        $dimension=$request->input('dimension_herramienta'); 
        $condicion_herramienta=$request->input('condicion_herramienta');
        $capacidad_insumo=$request->input('capacidad_insumo');
        $tipo_insumo=$request->input('tipo_insumo');

        if($tipo==="Insumos"){  
            $codigo=$this->generadorCodigoArticulo($nombre_articulo,"",$tipo,"","");
 
                if(Catalogo_articulo::where('id_articulo',$codigo)->exists()){
                    $articulo = Catalogo_articulo::find($codigo);
                    $articulo->id_articulo=$codigo;
                    $codigos_inventario=$this->generadorCodigoInventario($articulo,$tipo,$cantidad_articulo);

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
            

                $codigos_inventario=$this->generadorCodigoInventario($articulo,$tipo,$cantidad_articulo);
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
            return redirect()->route('inventario.index')->with('success', 'El insumo ha sido registrado exitosamente: ' . $nombre_articulo);
        
        }

        if($tipo==="Maquinaria"){
            //Agregar en la tabla de catalogo articulo 
             $codigo=$this->generadorCodigoArticulo($nombre_articulo,$seccion_articulo,$tipo,"","");
             
               //Agregar en la tabla de articulo_inventariado 
             if(Catalogo_articulo::where('id_articulo',$codigo)->exists()){
                    $articulo = Catalogo_articulo::find($codigo);
                    $articulo->id_articulo=$codigo;
                    $codigos_inventario=$this->generadorCodigoInventario($articulo,$tipo,$cantidad_articulo);
     
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
                        $maquinaria->insumos()->sync($request->input('insumos',[]));
                        $maquinaria->save();
                       

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
                 $articulo_nuevo->seccion=$seccion_articulo;
                 $articulo_nuevo->tipo=$tipo_maquina;
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
             

                 $codigos_inventario=$this->generadorCodigoInventario($articulo,$tipo,$cantidad_articulo);
                 for ($i = 0; $i < $articulo->cantidad; $i++) {
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
                     $maquinaria->insumos()->sync($request->input('insumos',[]));
                     $maquinaria->save();
                    



                    $auditoria->event='created';
                    $auditoria->subject_type=Articulo_inventariado::class;
                    $auditoria->subject_id=$codigos_inventario[$i];
                    $auditoria->cause_id=auth()->id();
                    $auditoria->old_data=json_encode([]);
                    $auditoria->new_data=json_encode($data);
                    $auditoria->save();
                }
     
             }
             return redirect()->route('inventario.index')->with('success', 'Maquinaria registrada exitosamente: ' . $nombre_articulo);
         }
     

        if($tipo==="Herramientas"){
         
            $codigo=$this->generadorCodigoArticulo($nombre_articulo,"",$tipo,$tipo_herramienta,$dimension);     
             //Agregar en la tabla de articulo_inventariado           
           if(Catalogo_articulo::where('id_articulo',$codigo)->exists()){

            $articulo = Catalogo_articulo::find($codigo);
            $articulo->id_articulo=$codigo;
            $codigos_inventario=$this->generadorCodigoInventario($articulo,$tipo,$cantidad_articulo);

        
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
   

            }else{
                
                $articulo_nuevo = new Catalogo_articulo;
                $articulo_nuevo->id_articulo= $codigo;
                $articulo_nuevo->nombre=$nombre_articulo;
                $articulo_nuevo->cantidad=$cantidad_articulo;
                $articulo_nuevo->seccion=null;
                $articulo_nuevo->tipo=$tipo_herramienta;
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

            

                $codigos_inventario=$this->generadorCodigoInventario($articulo,$tipo,$cantidad_articulo);
                for ($i = 0; $i < $articulo->cantidad; $i++) {
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
                    //Parte de auditoria

                    $auditoria->event='created';
                    $auditoria->subject_type=Articulo_inventariado::class;
                    $auditoria->subject_id=$codigos_inventario[$i];
                    $auditoria->cause_id=auth()->id();
                    $auditoria->old_data=json_encode([]);
                    $auditoria->new_data=json_encode($data);
                    $auditoria->save();

                   
               }
    
            }   
                                         
        }
        return redirect()->route('inventario.index')->with('success', 'Herramienta registrada exitosamente: ' . $nombre_articulo);


    }

    public function destroy($id_articulo)
    {

    
        $catalogo_articulo=Catalogo_articulo::find($id_articulo); 
        
        $auditoria=new Auditoria;
        $auditoria->event='deleted';  
        $auditoria->subject_type=Catalogo_articulo::class;
        $auditoria->subject_id=$catalogo_articulo->id_articulo;
        $auditoria->cause_id=auth()->id();
        $auditoria->old_data=json_encode($catalogo_articulo);
        $auditoria->new_data=json_encode([]);
        $auditoria->save();
        
        $catalogo_articulo->delete();
        return redirect()->route('inventario.index')->with('success', 'El articulo con el ID: ' . $id_articulo . ' ha sido eliminado exitosamente.');


       
    }


  


    public function generadorCodigoArticulo(String $nombre,String $seccion,String $tipo,String $tipoHerramienta,$dimension){
            $codigo="";
            $iniciales="";
            $iniciales_herramienta="";
            $ignorar = ["de","para"];


            $palabras = explode(' ', $nombre);
            $tipo_herramientas = explode(' ', $tipoHerramienta);

            foreach ($palabras as $palabra) {
             
                if (!in_array(strtolower($palabra), $ignorar)) {
                    $iniciales .= substr($palabra, 0, 1);
                   
                }
            }

            foreach ($tipo_herramientas as $tipo_herramienta) {
               
                if (!in_array(strtolower($tipo_herramienta), $ignorar)) {
                    $iniciales_herramienta .= substr($tipo_herramienta, 0, 1);
                }
            }

            $iniciales_nombre = strtoupper($iniciales);
            $iniciales_tipo_herramienta=strtoupper($iniciales_herramienta);

            if($tipo==="Maquinaria"){
                $codigo=$seccion.$iniciales_nombre;                          
            }

            if($tipo==="Insumos"){
                $codigo=$iniciales_nombre;
            }

            if($tipo=="Herramientas"){
                //MA-FP-0635-01
                $numero = $dimension;
                $numero_formateado = str_pad($numero, 4, "0", STR_PAD_LEFT);
                $codigo = $iniciales_tipo_herramienta."-".$iniciales_nombre."-".$numero_formateado;
            }
          

            return $codigo;
    }


    public function generadorCodigoInventario(Catalogo_articulo $catalogo_articulo, String $tipo,$Cantidad) {
      
        $ultimo_codigo = Articulo_inventariado::where('id_articulo', $catalogo_articulo->id_articulo)
        ->orderBy('id_inventario', 'desc')
        ->value('id_inventario');

        if($ultimo_codigo==null){

            if($tipo==="Maquinaria" or $tipo==="Insumos"){
                $ultimo_codigo=$catalogo_articulo->id_articulo."00";
            }

            if($tipo==="Herramientas"){
                $ultimo_codigo=$catalogo_articulo->id_articulo."-00";
            }

            
            
        }
        $ultimo_numero = intval(substr($ultimo_codigo, -2)); 
        
        $nuevo_codigos = [];
        $cantidad_productos = $Cantidad;
    
        
        if($tipo==="Maquinaria" or $tipo==="Insumos"){
            for ($i = $ultimo_numero + 1; $i <= $ultimo_numero + $cantidad_productos; $i++) {
                $numero_formateado = str_pad($i, 2, "0", STR_PAD_LEFT);
                $nuevo_codigo = substr($ultimo_codigo, 0, -2) . $numero_formateado;
                $nuevo_codigos[] = $nuevo_codigo;
            }
            
        }  

        if($tipo==="Herramientas"){
            //Revisar si es el primer registro 
                if($this->contarGuionesMedios($ultimo_codigo)==2){          

                    for ($i = $ultimo_numero + 1; $i <= $ultimo_numero + $cantidad_productos; $i++) {
                        $numero_formateado = str_pad($i, 2, "0", STR_PAD_LEFT);
                        $nuevo_codigo = substr($ultimo_codigo, 0, -2). $numero_formateado;
                        $nuevo_codigos[] = $nuevo_codigo;
                    }
                    
                }else{
 
                    for ($i = $ultimo_numero + 1; $i <= $ultimo_numero + $cantidad_productos; $i++) {
                        $numero_formateado = str_pad($i, 2, "0", STR_PAD_LEFT);
                        $nuevo_codigo = substr($ultimo_codigo, 0, -2) . $numero_formateado;           
                        $nuevo_codigos[] = $nuevo_codigo;
                    }
                    
                }

        }  

        return $nuevo_codigos;
    }

   public function contarGuionesMedios($cadena) {
        $conteo = substr_count($cadena, "-");
        return $conteo;
    }

}
