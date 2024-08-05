<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Grupo;
use  App\Models\Asignatura;
use  App\Models\Periodo;
class GrupoController extends Controller
{

    function _construct()
    {
        $this->middleware('permission:ver-grupos', ['only' => ['index']]);
        $this->middleware('permission:crear-grupo', ['only' => ['create','store']]);
        $this->middleware('permission:borrar-grupo', ['only' => ['destroy']]);
    }

    public  function index(){
        $grupos=Grupo::all();
        $asignaturas=Asignatura::all();
        return view('grupos.index',compact('grupos','asignaturas'));
    }

    public function create(){
        $asignaturas=Asignatura::all();
        $periodos=Periodo::all();
        return view('grupos.create',compact('asignaturas','periodos'));

    }

    public function store(Request $request){

        $validated = $request->validate([
            'clave_grupo' => 'required',
            'asignatura' => 'required',
            'periodo' => 'required',
        ]);
            $grupo=new Grupo;
            $grupo->clave_grupo=$request->input('clave_grupo');
            $grupo->clave_asignatura=$request->input('asignatura');
            $grupo->clave_periodo=$request->input('periodo');
            $grupo->save();

    
        return redirect()->route('grupos.index')->with('success','Grupo agregado correctamente');
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

        return redirect()->route('grupos.index')->with('success','Grupo eliminado correctamente');;
    }

}
