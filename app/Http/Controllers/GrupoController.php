<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Grupo;
use  App\Models\Asignatura;
class GrupoController extends Controller
{
    public  function index(){
        $grupos=Grupo::all();
        $asignaturas=Asignatura::all();
        return view('grupos.index',compact('grupos','asignaturas'));
    }

    public function create(){
        $asignaturas=Asignatura::all();
        return view('grupos.create',compact('asignaturas'));

    }

    public function store(Request $request){

        $validated = $request->validate([
            'clave_grupo' => 'required',
            'asignatura' => 'required',
        ]);
            $grupo=new Grupo;
            $grupo->clave_grupo=$request->input('clave_grupo');
            $grupo->clave_asignatura=$request->input('asignatura');
            $grupo->save();

        
    

        return redirect()->route('grupos.index');
    }

    public function destroy($id){

        $grupo=Grupo::find($id);

        if ($grupo) {
          
            $alumnos = $grupo->alumnos;
            foreach ($alumnos as $alumno) {
                $grupo->alumnos()->updateExistingPivot($alumno->no_control, ['clave_grupo' => null]);
            }
    
        
            $grupo->delete();
        }

        return redirect()->route('grupos.index');
    }

}
