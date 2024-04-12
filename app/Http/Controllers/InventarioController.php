<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalogo_articulo;
class InventarioController extends Controller
{
   
    public function index()
    {
            
        return view('inventarios.index');
    }


    public function store(Request $request){

        $catalogo_articulo=new Catalogo_articulo;  
        $tipo=$request->input('tipo');
        
        $catalogo_articulo->nombre=$request->input('nombre');
        $catalogo_articulo->cantidad=$request->input('cantidad');
        

        if($tipo==="Insumos"){  
              
            $catalogo_articulo->tipo=$request->input('tipo_insumo');   
            $catalogo_articulo->id_articulo=$this->generadorCodigoArticulo($catalogo_articulo->nombre,"",$catalogo_articulo->tipo);
            $catalogo_articulo->seccion=null;
            $catalogo_articulo->save();
               
        }

        if($tipo==="Maquinaria"){
       
           $catalogo_articulo->tipo=$request->input('tipo_maquina');  
           $catalogo_articulo->seccion=$request->input('seccion'); 
           $catalogo_articulo->id_articulo=$this->generadorCodigoArticulo($catalogo_articulo->nombre,$catalogo_articulo->seccion,$tipo);
           $catalogo_articulo->save();
        }

        if($tipo==="Herramientas"){

            $catalogo_articulo->tipo=$request->input('tipo_herramienta');
            $catalogo_articulo->id_articulo=$this->generadorCodigoHerramientas($catalogo_articulo->nombre,$catalogo_articulo->tipo);
            $catalogo_articulo->seccion=null;
            $catalogo_articulo->save();
          
        }

 
    }


    public function generadorCodigoArticulo(String $nombre,String $seccion,String $tipo){
            $codigo="";
            $iniciales="";
            $palabras = explode(' ', $nombre);

            foreach ($palabras as $palabra) {
                
                $iniciales .= substr($palabra, 0, 1);
            }
          
            $iniciales_nombre=strtoupper($iniciales);
            
            if($tipo==="Maquinaria"){
                $codigo=$seccion.$iniciales_nombre;
              
            }

            if($tipo==="Insumos"){
                $codigo=$iniciales_nombre;

            }

            return $codigo;
    }


    public function generadorCodigoHerramientas(String $nombre,String $tipo){
            $codigo = "";
            $iniciales = "";
            $iniciales_herramienta="";
           
            $ignored_words = ["de"];
        
            
            $palabras = explode(' ', $nombre);
            $tipo_herramientas = explode(' ', $tipo);
        
            foreach ($palabras as $palabra) {
             
                if (!in_array(strtolower($palabra), $ignored_words)) {
                    $iniciales .= substr($palabra, 0, 1);
                   
                }
            }

            foreach ($tipo_herramientas as $tipo_herramienta) {
               
                if (!in_array(strtolower($tipo_herramienta), $ignored_words)) {
                    $iniciales_herramienta .= substr($tipo_herramienta, 0, 1);
                }
            }


        
            $iniciales_nombre = strtoupper($iniciales);
            $iniciales_tipo_herramienta=strtoupper($iniciales_herramienta);
            $codigo = $iniciales_tipo_herramienta.$iniciales_nombre;
          
            return $codigo;

    }



}
