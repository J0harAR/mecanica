<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Docente;
use  App\Models\Practica;
class PracticaController extends Controller
{
   

    public function index(){
        
        return view('practicas.index');
      
    }


    public function create(){

        $docentes=Docente::all();
        return view('practicas.crear',compact('docentes'));
      
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
        $practica->rfc=$id_docente;
        $practica->nombre=$nombre;
        $practica->objetivo=$objetivo;
        $practica->introduccion=$introduccion;
        $practica->fundamento=$fundamento;
        $practica->referencias=$referencias;
        $practica->estatus=0;
    

        $practica->save();

        return redirect()->route('practicas.index');
      
    }




}
