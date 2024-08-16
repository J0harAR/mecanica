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
        $articulos_inventariados=Articulo_inventariado::all();   
        $catalogo_articulo=Catalogo_articulo::all();
        $insumos=Insumos::all();
        $historial=Auditoria::all(); 
        $periodos=Periodo::all();
        return view('inventarios.index',compact('catalogo_articulo','historial','insumos','periodos'));
    }


    public function store(Request $request){
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
    
        $tipo = $request->input('tipo');
        $nombre = strtolower($request->input('nombre'));
        $dimension_herramienta = $request->input('dimension_herramienta');
    
        // Resto de la lógica para registrar el artículo
        switch ($tipo) {
            case 'Herramientas':
                $tipo_herramienta=$request->input('tipo_herramienta');
         
             
                if(!$tipo_herramienta){
                    return redirect()->route('inventario.index')->with('error', 'Seleccione el tipo de herramienta que desea registrar ');
                }

                $codigo=$this->generateCodigoHerramientas($nombre,$tipo_herramienta,$dimension_herramienta);

                $catalogo_articulo = Catalogo_articulo::firstOrNew([
                    'id_articulo' => $codigo,
                    'nombre' => $nombre,
                ]);

                $catalogo_articulo->id_articulo=$codigo;
                $catalogo_articulo->nombre=$nombre;
                $catalogo_articulo->cantidad=0;
                $catalogo_articulo->seccion=null;
                $catalogo_articulo->tipo="Herramientas";
                $catalogo_articulo->save();
                break;

            case 'Maquinaria':
                $seccion=$request->input('seccion');
                
                
                if(!$seccion){
                    return redirect()->route('inventario.index')->with('error', 'Seleccione la seccion de la maquinaria que desea registrar ');
                }

                $codigo=$this->generateCodigoMaquinaria($nombre,$seccion);
                

                $catalogo_articulo = Catalogo_articulo::firstOrNew([
                    'id_articulo' => $codigo,
                    'nombre' => $nombre,
                ]);

                $catalogo_articulo->id_articulo=$codigo;
                $catalogo_articulo->nombre=$nombre;
                $catalogo_articulo->cantidad=0;
                $catalogo_articulo->seccion=$seccion;
                $catalogo_articulo->tipo="Maquinaria";
                $catalogo_articulo->save();
                break;
            case 'Insumos':
               
                $codigo=$this->generateCodigoInsumos($nombre);

                $catalogo_articulo = Catalogo_articulo::firstOrNew([
                    'id_articulo' => $codigo,
                    'nombre' => $nombre,
                ]);

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

                    //MA-FP-0635-01
                   $numero = $dimension;
                   $numero_formateado = str_pad($numero, 4, "0", STR_PAD_LEFT);
                
                    $codigo = $iniciales_tipo_herramienta."-".$iniciales_nombre."-".$numero_formateado;
            
                return $codigo;

    }

    public function generateCodigoMaquinaria(String $nombre,String $seccion){

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



    public function generateCodigoInsumos(String $nombre){
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


}
