<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Asignatura;
use  App\Models\Grupo;
use  App\Models\Docente;
class AsignaturaController extends Controller
{

    public function index(){
        $asignaturas=Asignatura::all();
        return view('asignatura.index',compact('asignaturas'));
    }

    public function create(){
        $grupos=Grupo::all();
        $docentes=Docente::all();


        return view('asignatura.create',compact('grupos','docentes'));

    }   


    public function store(Request $request){
        $nombre=$request->input('nombre');
        $clave=$request->input('clave');

        $asignatura=new Asignatura;
        $asignatura->clave=$clave;
        $asignatura->nombre=$nombre;
        $asignatura->save();

        return redirect()->route('asignatura.index');
    }


    public function edit($id){

        $asignatura=Asignatura::find($id);

        return view('asignatura.editar',compact('asignatura'));


    }


    public function update(Request $request , $id){

        $nombre=$request->input('nombre');
        $asignatura=Asignatura::find($id);
        $asignatura->nombre=$nombre;

        $asignatura->save();

        return redirect()->route('asignatura.index');

    }


    public function destroy($id){
        $asignatura=Asignatura::find($id);
        $asignatura->delete();

        return redirect()->route('asignatura.index');

    }


  

}
