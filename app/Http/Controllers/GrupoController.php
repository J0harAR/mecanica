<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Grupo;
use  App\Models\Asignatura;
class GrupoController extends Controller
{
    public  function index(){
        $grupos=Grupo::all();

        return view('grupos.index',compact('grupos'));
    }

    public function create(){
        $asignaturas=Asignatura::all();
        return view('grupos.create',compact('asignaturas'));

    }

    public function store(Request $request){
        
        $grupo=Grupo::find($request->input('clave_grupo'));

        if(!$grupo){
            $grupo=new grupo;
            $grupo->clave=$request->input('clave_grupo');
            $grupo->save();
            
            $grupo=Grupo::find($request->input('clave_grupo'));
            $grupo->asignaturas()->sync([$request->input('asignatura')]);

        }else{
            $grupo->asignaturas()->sync([$request->input('asignatura')]);
            $grupo->save();
        }
    

        return redirect()->route('grupos.index');
    }

}
