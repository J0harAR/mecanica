<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Grupo;
use  App\Models\Asignatura;
use  App\Models\Periodo;
use Carbon\Carbon;  
class GrupoController extends Controller
{

    function _construct()
    {
        $this->middleware('permission:ver-grupos', ['only' => ['index']]);
        $this->middleware('permission:editar-grupo', ['only' => ['update']]);
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
        $currentYear = Carbon::now()->year;
        
        $currentMonth = Carbon::now()->month;
            
        $periodos = Periodo::whereYear('created_at',$currentYear)
            ->whereMonth('fecha_inicio', '>=',  $currentMonth)
            ->get();
        
        return view('grupos.create',compact('asignaturas','periodos'));

    }

    public function store(Request $request){

        $validated = $request->validate([
            'clave_grupo' => 'required',
            'asignatura' => 'required',
            'periodo' => 'required',
        ]);
    
        $grupo = Grupo::where('clave_grupo', $request->input('clave_grupo'))
        ->where('clave_asignatura', $request->input('asignatura'))
        ->first();

        

        if($grupo){
            return redirect()->route('grupos.index')->with('error','Grupo duplicado');
        }

            $grupo=new Grupo;
            $grupo->clave_grupo=$request->input('clave_grupo');
            $grupo->clave_asignatura=$request->input('asignatura');
            $grupo->clave_periodo=$request->input('periodo');
            $grupo->save();

    
        return redirect()->route('grupos.index')->with('success','Grupo agregado correctamente');
    }


    public function update(Request $request,$id){

        $grupo=Grupo::find($id);

       
        if($request->input('asignatura')=== null){
            return redirect()->route('grupos.index')->with('error','Seleccione al menos una asignatura');
        }
        $asignatura=Asignatura::find($request->input('asignatura'));


        if($asignatura){
            
            $grupo_existente=Grupo::where('clave_grupo',$id)
            ->where('clave_asignatura', $request->input('asignatura'))
            ->first();

            if($grupo_existente){
                return redirect()->route('grupos.index')->with('error','Grupo duplicado');
            }
           
            $grupo->clave_asignatura=$request->input('asignatura');
            $grupo->save();
        }
        

        return redirect()->route('grupos.index')->with('success','Grupo actualizado correctamente');
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
