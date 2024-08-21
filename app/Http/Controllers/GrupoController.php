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

        //Retornamos todos los grupos y asignaturas para la tabla de la vista
        $grupos=Grupo::all();
        $asignaturas=Asignatura::all();
        return view('grupos.index',compact('grupos','asignaturas'));
    }

    public function create(){
        
        $asignaturas=Asignatura::all();//Filtramos todas las asignaturas
        
        //Obtenemos el año y mes  del usuario logueado
        $currentYear = Carbon::now()->year;//año
        $currentMonth = Carbon::now()->month;//mes
            
        //Filtramos los periodos que se encuentraan entre el año y el mes
        $periodos = Periodo::whereYear('created_at',$currentYear)
            ->whereMonth('fecha_inicio', '>=',  $currentMonth)
            ->get();
        
        return view('grupos.create',compact('asignaturas','periodos'));

    }

    public function store(Request $request){
        //Validamos que los requests sean requeridos y no dejar en blanco
        $validated = $request->validate([
            'clave_grupo' => 'required',
            'asignatura' => 'required',
            'periodo' => 'required',
        ]);
    
        //Verificmaos que si existe un grupo con una clave y un asigatura con la misma clave es decir no deber haber un grupo con la misma asignatura
        $grupo = Grupo::where('clave_grupo', $request->input('clave_grupo'))
        ->where('clave_asignatura', $request->input('asignatura'))
        ->first();//Ejemplo IA-Simulacion y ISA no debe existir grupo y asignatura iguales

        
        //Si ese grupo existe se retorna un error
        if($grupo){
            return redirect()->route('grupos.index')->with('error','Grupo duplicado');
        }
            //Se crea el grupo
            $grupo=new Grupo;
            $grupo->clave_grupo=$request->input('clave_grupo');
            $grupo->clave_asignatura=$request->input('asignatura');
            $grupo->clave_periodo=$request->input('periodo');
            $grupo->save();

    
        return redirect()->route('grupos.index')->with('success','Grupo agregado correctamente');
    }


 
    

    public function destroy($id){
        //Se busca si existe el grupo
        $grupo=Grupo::find($id);
        //Si existe el grupo vamos obtener los alumnos  que pertencen a ese grupo
        if ($grupo) {
          
            $alumnos = $grupo->alumnos;
            foreach ($alumnos as $alumno) {//Interamos por cada alumno del grupo
                $grupo->alumnos()->updateExistingPivot($alumno->no_control, ['clave_grupo' => null]);//Se pondra null en la tabla de los alumnos es decir 13131 null
            }
    
        
            $grupo->delete();//Se elimina
        }

        return redirect()->route('grupos.index')->with('success','Grupo eliminado correctamente');;
    }

}
