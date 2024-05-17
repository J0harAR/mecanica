<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Docente;
use  App\Models\Practica;
use  App\Models\Catalogo_articulo;
use  App\Models\Articulo_inventariado;
use  App\Models\Alumno;

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

    //PARTE DE PRACTICAS DONDE SE VAN A REALIZAR EN EL LABORATORIO





    public function create_practica_alumno (){
        $practicas=Practica::with(['catalogo_articulos'])->get();
        $articulos_inventariados=Articulo_inventariado::all();
        $docentes=Docente::all();
        return view('practicas.alumnos',compact('practicas','articulos_inventariados','docentes'));
    }


    public function store_practica_Alumno(Request $request){
        $alumno=Alumno::find($request->input('no_control'));
        $practica=Practica::find($request->input('practica'));
        $practica_articulos=$practica->catalogo_articulos()->pluck('id_articulo')->toArray();
        $articulos_inventariados=$request->input('articulos');
        $fecha=$request->input('fecha');
        $no_equipo=$request->input('no_equipo');
        $hora_entrada=$request->input('hora_entrada');
        $hora_salida=$request->input('hora_salida');

        if($alumno==null){
            return redirect()->route('practicasAlumno.create')->with('alumno_no_encontrado', 'Alumno no encontrado.');
        }

        $articulo_presente = false;
        foreach ($articulos_inventariados as $id_articulo_inventariado) {
            $articulo_presente = false;
            $articulo=Articulo_inventariado::find($id_articulo_inventariado);
        
                foreach ($practica_articulos as $practica_articulo) {

                   if($articulo->Catalogo_articulos->id_articulo == $practica_articulo){
                    $articulo_presente = true;
                    break; 
                   }
                }
                if (!$articulo_presente) {         
                    return redirect()->route('practicasAlumno.create')->with('error', 'Articulos no están asociados a la practica.');
               }
        }


        foreach ($articulos_inventariados as $id_articulo_inventariado) {
            $articulo=Articulo_inventariado::find($id_articulo_inventariado);
            $articulo->estatus="Ocupado";
            $articulo->save();
        }

     $practica->alumnos()->attach($alumno->no_control,['fecha'=>$fecha,'no_equipo'=>$no_equipo,'hora_entrada'=>$hora_entrada,'hora_salida'=>$hora_salida]);
     $practica->articulo_inventariados()->sync($articulos_inventariados);
     $practica->save();
      
       
    }

}
