<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalogo_articulo;
use App\Models\Articulo_inventariado;
use App\Models\Herramientas;
use App\Models\Maquinaria;
use App\Models\Insumos;
use App\Models\Periodo;
use App\Models\Auditoria;

class InventarioController extends Controller
{

    function _construct()
    {
        $this->middleware('permission:ver-inventario', ['only' => ['index']]);
        $this->middleware('permission:crear-articulo', ['only' => ['store']]);
        $this->middleware('permission:borrar-inventario', ['only' => ['destroy']]);
    }



   
    public function index()
    {
        //Vamos retornar todo el catalogo y los insumos que nos van a servir en maquinaria
        $articulos_inventariados=Articulo_inventariado::all();   
        $catalogo_articulo=Catalogo_articulo::all();
        $insumos=Insumos::all();
        $historial=Auditoria::all(); 
        $periodos=Periodo::all();
        return view('inventarios.index',compact('catalogo_articulo','historial','insumos','periodos'));
    }


    public function store(Request $request){

        //Aqui vamos a validar que no se dejen nada en blanco y se seleccione todo por cada tipo de articulo
        $request->validate([
            'tipo' => 'required',
            'nombre' => 'required|string',
            'tipo_herramienta' => 'required_if:tipo,Herramientas',
            'dimension_herramienta' => 'required_if:tipo,Herramientas|nullable|numeric',
            'seccion' => 'required_if:tipo,Maquinaria',
        ], [
            'tipo.required' => 'Seleccione el tipo de artículo que desea registrar.',
            'nombre.required' => 'Ingrese el nombre del artículo.',
            'tipo_herramienta.required_if' => 'Seleccione el tipo de herramienta que desea registrar.',
            'dimension_herramienta.required_if' => 'Ingrese la dimensión de la herramienta.',
            'seccion.required_if' => 'Seleccione la sección de la maquinaria que desea registrar.',
        ]);
    
        //Guardamos el tipo que no servira para cada tipo de articulo
        $tipo = $request->input('tipo');
        $nombre = strtolower($request->input('nombre'));//Guardamos el nombre general del articulo
        $dimension_herramienta = $request->input('dimension_herramienta');//Guardamos la dimension de la herramienta
    
        //Creamos un switch para poder crear cada tipo de articulo
        switch ($tipo) {
            case 'Herramientas'://Caso de herramienta
                $tipo_herramienta = $request->input('tipo_herramienta');//Guaradmos el tipo de herramienta
                //Validamos que se seleccione el tipo de herramienta si no retornara un error
                if (!$tipo_herramienta) {
                    return redirect()->route('inventario.index')->with('error', 'Seleccione el tipo de herramienta que desea registrar');
                }
                //Vamos generar el codigo de la herramienta sin numeros consecutivos 
                $codigoBase = $this->generateCodigoHerramientas($nombre, $tipo_herramienta, $dimension_herramienta);

                //Si no existe el articulo crearemos uno nuevo
               if(!Catalogo_articulo::where('id_articulo',$codigoBase)->exists()){
                    //Asignamos los valores al articulo del catalogo
                    $catalogo_articulo = new Catalogo_articulo;
                    $catalogo_articulo->id_articulo = $codigoBase;
                    $catalogo_articulo->nombre = $nombre;
                    $catalogo_articulo->cantidad = 0;
                    $catalogo_articulo->seccion = null;
                    $catalogo_articulo->tipo = "Herramientas";
                    $catalogo_articulo->save();

               }else{//En este caso existe dentro del catalogo
                    //Revisaremos si el articulo que se va registrar no tiene el mismo nombre ya que generar un codigo similar 
                    if(!Catalogo_articulo::where('nombre', $nombre)->exists()){

                        $segmentos = explode('-', $codigoBase);//Vamos a separar la herrmaienta por guiones medios
                        $codigoBaseSinNumero = implode('-', array_slice($segmentos, 0, 2));//Aqui sera el codigo base sin numero es decir HY-PC
                        $numeroBase = implode('-', array_slice($segmentos, 2)); //Aqui me guarda el numero base es decir 234 o la dimension 
                        
                        //Vamosa verificar si existe algun articulo con ese codigo sin numero 
                        $codigosExistentes = Catalogo_articulo::where('id_articulo', 'like', $codigoBaseSinNumero . '%')
                            ->pluck('id_articulo')
                            ->toArray();
        
                        //Inicializmoas el contador en 1 a que de ahi se va tomar para asignar el numero al nombre
                        $contador = 1;
                        $codigo = $codigoBaseSinNumero . $contador . '-' . $numeroBase;//El codigo nuevo seria como HY-PC1-234
        
                        //Iteramos con un while y si encontramos el codigo con algun otro ira aumentando el contador entonces se podria generar esto:HY-PC2-234
                        while (in_array($codigo, $codigosExistentes)) {
                            $contador++;
                            $codigo = $codigoBaseSinNumero . $contador . '-' . $numeroBase;//Aqui retornaria algo asi si hubiera varios:HY-PC2-234
                        }
                    
                        //Guardamos el articulo en el catalogo 
                        $catalogo_articulo = new Catalogo_articulo;
                        $catalogo_articulo->id_articulo = $codigo;
                        $catalogo_articulo->nombre = $nombre;
                        $catalogo_articulo->cantidad = 0;
                        $catalogo_articulo->seccion = null;
                        $catalogo_articulo->tipo = "Herramientas";
                        $catalogo_articulo->save();
                    }else{
                        //Si al momento de compara el nombre es el mismo significara que el codigo esta duplicado
                        return redirect()->route('inventario.index')->with('error', 'Articulo duplicado ');
                    }
                   
               }


                break;

            case 'Maquinaria'://Caso de maquinaria
                $seccion=$request->input('seccion');//Se guarda la seccio de la mquinaria
                
                //Validamos que la seccion se seleccione si no retornara un error
                if(!$seccion){
                    return redirect()->route('inventario.index')->with('error', 'Seleccione la seccion de la maquinaria que desea registrar ');
                }
                //Revisamos que si se va registrar una maquinaria con el mismo nombre y seccion retorne un error ya que se duplica
                if(Catalogo_articulo::where('nombre', $nombre)->where('seccion', $seccion)->first()){

                     return redirect()->route('inventario.index')->with('error', 'Articulo duplicado ');
                }
                //Aqui vamos a generar el codigo de maquina que usa la seccion para el codigo
                $codigoBase =$this->generateCodigoMaquinaria($nombre,$seccion);
                
               //Vamosa verificar si existe algun articulo con ese codigo sin numero 
                $codigosExistentes = Catalogo_articulo::where('id_articulo', 'like', $codigoBase . '%')
                ->pluck('id_articulo')
                ->toArray();

                //Inicializmoas el contador en 1 a que de ahi se va tomar para asignar el numero al nombre
                $contador = 1;
                $codigo = $codigoBase;//El codigo nuevo seria como 03MI
               
                //Iteramos con un while y si encontramos el codigo con algun otro ira aumentando el contador entonces se podria generar esto:03MI1
                while (in_array($codigo, $codigosExistentes)) {
                    $codigo = $codigoBase  . $contador;
                    $contador++;
                }

                 //Guardamos el articulo en el catalogo 
                $catalogo_articulo=new Catalogo_articulo;
                $catalogo_articulo->id_articulo=$codigo;
                $catalogo_articulo->nombre=$nombre;
                $catalogo_articulo->cantidad=0;
                $catalogo_articulo->seccion=$seccion;
                $catalogo_articulo->tipo="Maquinaria";
                $catalogo_articulo->save();
                break;
            case 'Insumos'://Caso de insumo
               
                //Revisamos que no exista un articulo con ese mismo nomnbre si no retorna duplicado
                if(Catalogo_articulo::where('nombre', $nombre)->first()){

                    return redirect()->route('inventario.index')->with('error', 'Articulo duplicado ');
               }

               //Aqui generaremos el codigo de insumo que solo consta de sus iniciales de su nombre
               $codigoBase=$this->generateCodigoInsumos($nombre);
                 
               //Vamosa verificar si existe algun articulo con ese codigo sin numero 
               $codigosExistentes = Catalogo_articulo::where('id_articulo', 'like', $codigoBase . '%')
               ->pluck('id_articulo')
               ->toArray();

                //Inicializmoas el contador en 1 a que de ahi se va tomar para asignar el numero al nombre
               $contador = 1;
               $codigo = $codigoBase;//El codigo nuevo seria como AI
                //Iteramos con un while y si encontramos el codigo con algun otro ira aumentando el contador entonces se podria generar esto:AIM1
               while (in_array($codigo, $codigosExistentes)) {
                   $codigo = $codigoBase  . $contador;
                   $contador++;
               }

                //Guardamos el articulo en el catalogo 
                $catalogo_articulo=new Catalogo_articulo;
                $catalogo_articulo->id_articulo=$codigo;
                $catalogo_articulo->nombre=$nombre;
                $catalogo_articulo->cantidad=0;
                $catalogo_articulo->seccion=null;
                $catalogo_articulo->tipo="Insumos";
                $catalogo_articulo->save();
                break;
    
            default:
                return redirect()->route('inventario.index')->withErrors(['tipo' => 'Seleccione el tipo de artículo que desea registrar.']);
                break;
        }
    
        return redirect()->route('inventario.index')->with('success', 'El artículo ha sido registrado exitosamente: ' . $nombre);
    }
    

   


    public function generateCodigoHerramientas(String $nombre,String $tipoHerramienta,$dimension){
        $codigo="";//Vamos almencenar el codigo en esta variable
                $iniciales="";//Vamos almencenar las iniciales del tipo de herramienta  en esta variable
                $iniciales_herramienta="";//Vamos almencenar las iniciales  de la  herramienta en esta variable
                $ignorar = ["de","para"];//Palabras que vamos a ignorar

               
                $palabras = explode(' ', $nombre); // Separar el nombre de la herramienta en palabras individuales
                $tipo_herramientas = explode(' ', $tipoHerramienta);// Separar el tipo de herramienta en palabras individuales
                
                // Recorrer cada palabra del nombre de la herramienta
                foreach ($palabras as $palabra) {
                
                    if (!in_array(strtolower($palabra), $ignorar)) {
                        $iniciales .= substr($palabra, 0, 1); // Tomar la primera letra de la palabra y agregarla a las iniciales del nombre es decir HC
                    
                    }
                }
            // Recorrer cada palabra del tipo de herramienta
                foreach ($tipo_herramientas as $tipo_herramienta) {
                  // Si la palabra no está en la lista de palabras a ignorar
                    if (!in_array(strtolower($tipo_herramienta), $ignorar)) {
                        $iniciales_herramienta .= substr($tipo_herramienta, 0, 1); // Tomar la primera letra de la palabra y agregarla a las iniciales del tipo de herramienta es decir HY
                    }
                }
                // Convertir las iniciales del nombre a mayúsculas.
                $iniciales_nombre = strtoupper($iniciales);
                $iniciales_tipo_herramienta=strtoupper($iniciales_herramienta);// Convertir las iniciales del tipo de herramienta a mayúsculas.

                    //MA-FP-0635-01
                    // Formatear el número de la dimensión para que tenga al menos 4 dígitos, rellenando con ceros a la izquierda (0635)
                   $numero = $dimension;
                   $numero_formateado = str_pad($numero, 4, "0", STR_PAD_LEFT);
                    //Concatenamos todo el codigo generando algo asi MA-FP-0635-01
                    $codigo = $iniciales_tipo_herramienta."-".$iniciales_nombre."-".$numero_formateado;
            
                return $codigo;

    }

    public function generateCodigoMaquinaria(String $nombre,String $seccion){

        $codigo="";//Vamos almencenar el codigo en esta variable
        $iniciales="";//Vamos almencenar las iniciales de la maquinaria
        $ignorar = ["de","para"];//Palabras que vamos a ignorar
    
    
        $palabras = explode(' ', $nombre); // Separar el nombre de la maquinaria en palabras individuales
       
     // Recorrer cada palabra del nombre de la maquinaria
        foreach ($palabras as $palabra) {
         
            if (!in_array(strtolower($palabra), $ignorar)) {
                $iniciales .= substr($palabra, 0, 1);// Tomar la primera letra de la palabra y agregarla a las iniciales del nombre es decir MI
               
            }
        }
        $iniciales_nombre = strtoupper($iniciales);  // Convertir las iniciales del nombre a mayúsculas.
    
        $codigo=$seccion.$iniciales_nombre;//Concatenar la seccion con el nombre 03MI                          
      
        return $codigo;

    }



    public function generateCodigoInsumos(String $nombre){
        $codigo="";//Vamos almencenar el codigo en esta variable
        $iniciales="";//Vamos almencenar las iniciales del insumo
        $ignorar = ["de","para"];//Palabras que vamos a ignorar

        $palabras = explode(' ', $nombre);// Separar el nombre del insumo en palabras individuales
        // Recorrer cada palabra del nombre de la maquinaria
        foreach ($palabras as $palabra) {
         
            if (!in_array(strtolower($palabra), $ignorar)) {
                $iniciales .= substr($palabra, 0, 1);// Tomar la primera letra de la palabra y agregarla a las iniciales del nombre es decir MI
               
            }
        }

        $iniciales_nombre = strtoupper($iniciales);// Convertir las iniciales del nombre a mayúsculas.
        $codigo=$iniciales_nombre;//El codigo seran las iniciales del insumo y se le asigna a codigo
     
        return $codigo;

    }


    public function destroy($id_articulo)
    {

        //Buscamos el articulo que vamos a eliminar
        $catalogo_articulo=Catalogo_articulo::find($id_articulo); 
        //Creamos una auditodiria para que exista un registro del delete
        $auditoria=new Auditoria;
        $auditoria->event='deleted';  
        $auditoria->subject_type=Catalogo_articulo::class;
        $auditoria->subject_id=$catalogo_articulo->id_articulo;
        $auditoria->cause_id=auth()->id();
        $auditoria->old_data=json_encode($catalogo_articulo);
        $auditoria->new_data=json_encode([]);
        $auditoria->save();
        //Eliminanmos
        $catalogo_articulo->delete();
        return redirect()->route('inventario.index')->with('success', 'El articulo con el ID: ' . $id_articulo . ' ha sido eliminado exitosamente.');


       
    }


}
