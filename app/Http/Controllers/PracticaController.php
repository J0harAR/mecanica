<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Docente;
use  App\Models\Practica;
use  App\Models\Catalogo_articulo;
class PracticaController extends Controller
{
   

    public function index(){
        $practicas=Practica::with(['catalogo_articulos'])->get();

        

        return view('practicas.index',compact('practicas'));
      
    }


    public function create(){
        $catalogo_articulos=Catalogo_articulo::all();
        $docentes=Docente::all();
        return view('practicas.crear',compact('docentes','catalogo_articulos'));
      
    }

    public function store(Request $request){
        $id_practica=$request->input('codigo_practica');
        $id_docente=$request->input('docente');
        $nombre=$request->input('nombre_practica');
        $objetivo=$request->input('objetivo');
        $introduccion=$request->input('introduccion');
        $fundamento=$request->input('fundamento');
        $referencias=$request->input('referencias');

        $practica=new Practica;

        $practica->id_practica=$id_practica;
        $practica->id_docente=$id_docente;
        $practica->nombre=$nombre;
        $practica->objetivo=$objetivo;
        $practica->introduccion=$introduccion;
        $practica->fundamento=$fundamento;
        $practica->referencias=$referencias;
      
        $practica->estatus=0;
        $practica->save();


        $practica=Practica::find($id_practica);
        $practica->catalogo_articulos()->sync($request->input('articulos',[]));
        
    
        return redirect()->route('practicas.index');
      
    }
    public function show($id){
        $practica=Practica::find($id);
        $practica=Practica::find($id);
        $docentes=Docente::all();
        $articulos=Catalogo_articulo::all();
       return view('practicas.mostrar',compact('practica','docentes','articulos'));
    }


    public function edit($id){
       $practica=Practica::find($id);
       $docentes=Docente::all();
       $articulos=Catalogo_articulo::all();
       return view('practicas.editar',compact('practica','docentes','articulos'));
    }

    public function update(Request $request, $id){
        $id_practica = $request->input('codigo_practica');
        $id_docente = $request->input('docente');
        $nombre = $request->input('nombre_practica');
        $objetivo = $request->input('objetivo');
        $introduccion = $request->input('introduccion');
        $fundamento = $request->input('fundamento');
        $referencias = $request->input('referencias');
        
        $practica = Practica::find($id);

        if($id_practica === $practica->id_practica ){
            $practica->id_practica = $id_practica;
            $practica->id_docente = $id_docente;
            $practica->nombre = $nombre;
            $practica->objetivo = $objetivo;
            $practica->introduccion = $introduccion;
            $practica->fundamento = $fundamento;
            $practica->referencias = $referencias;
            $practica->estatus = 0;
            $practica->catalogo_articulos()->sync($request->input('articulos', []));
            $practica->save();
        }else{
            $practica->id_practica = $id_practica;
            $practica->id_docente = $id_docente;
            $practica->nombre = $nombre;
            $practica->objetivo = $objetivo;
            $practica->introduccion = $introduccion;
            $practica->fundamento = $fundamento;
            $practica->referencias = $referencias;
            $practica->estatus = 0;
            $practica->save();

            $practica->catalogo_articulos()->sync($request->input('articulos', []));
        }

    
        return redirect()->route('practicas.index');
    }

    public function destroy($id){
        $practica = Practica::find($id);
        $practica->delete();
        
        return redirect()->route('practicas.index');

    }

}
