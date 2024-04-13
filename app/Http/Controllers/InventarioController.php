<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalogo_articulo;
use App\Models\Articulo_inventariado;

class InventarioController extends Controller
{
   
    public function index()
    {
            
        return view('inventarios.index');
    }


    public function store(Request $request){

        $catalogo_articulo=new Catalogo_articulo;  

        $articulo_inventariado=new Articulo_inventariado;  

        $tipo=$request->input('tipo');
        $catalogo_articulo->nombre=$request->input('nombre');
        $catalogo_articulo->cantidad=$request->input('cantidad');
        

        if($tipo==="Insumos"){  
           //Agregar en la tabla de catalogo articulo 
            $catalogo_articulo->tipo=$request->input('tipo_insumo');   
            $catalogo_articulo->id_articulo=$this->generadorCodigoArticulo($catalogo_articulo->nombre,"",$tipo,"");
            $catalogo_articulo->seccion=null;
            $catalogo_articulo->save();

          
          
           
        }

        if($tipo==="Maquinaria"){
           //Agregar en la tabla de catalogo articulo 
           $catalogo_articulo->tipo=$request->input('tipo_maquina');  
           $catalogo_articulo->seccion=$request->input('seccion'); 
           $catalogo_articulo->id_articulo=$this->generadorCodigoArticulo($catalogo_articulo->nombre,$catalogo_articulo->seccion,$tipo,"");
           $catalogo_articulo->save();


            //Agregar en la tabla de articulo inventariado
            $articulo_inventariado->id_articulo=$catalogo_articulo->id_articulo;
            echo($articulo_inventariado);
           //$articulo_inventariado->id_inventario='111';
           
        }

        if($tipo==="Herramientas"){
            //Agregar en la tabla de catalogo articulo 
            $catalogo_articulo->tipo=$request->input('tipo_herramienta');
            $catalogo_articulo->id_articulo=$this->generadorCodigoArticulo($catalogo_articulo->nombre,"",$tipo,$catalogo_articulo->tipo);
            $catalogo_articulo->seccion=null;
            $catalogo_articulo->save();
            
        }

 
    }





    public function generadorCodigoArticulo(String $nombre,String $seccion,String $tipo,String $tipoHerramienta){
            $codigo="";
            $iniciales="";
            $iniciales_herramienta="";
            $ignorar = ["de"];


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
                $codigo = $iniciales_tipo_herramienta.$iniciales_nombre;
            }

            return $codigo;
    }


        public function generadorCodigoInventario(Articulo_inventariado $articulo_inventariado){



        }

}
