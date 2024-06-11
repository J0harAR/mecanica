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
            
            for ($i = $ultimo_numero + 1; $i <= $ultimo_numero + $cantidad_productos; $i++) {
                $numero_formateado = str_pad($i, 2, "0", STR_PAD_LEFT);
                $nuevo_codigo = substr($ultimo_codigo, 0, -2) . $numero_formateado;           
                $nuevo_codigos[] = $nuevo_codigo;
            }
                    
            
        }  

        return $nuevo_codigos;
    }



}
