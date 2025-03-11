<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Grupo;
use  App\Models\Asignatura;
use  App\Models\Periodo;
use Carbon\Carbon;  
use Illuminate\Support\Facades\Auth;
class GrupoController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:ver-grupos', ['only' => ['index']]);
        $this->middleware('permission:crear-grupo', ['only' => ['store']]);
        $this->middleware('permission:borrar-grupo', ['only' => ['destroy']]);      
        $this->middleware('auth');//Aqui se valida que el usuario este autentica para todo el controlador

    }

    public  function index(){

        //Retornamos todos los grupos y asignaturas para la tabla de la vista
        $grupos=Grupo::all();
        $asignaturas=Asignatura::all();//Filtramos todas las asignaturas
        
     
        // Obtengo la fecha actual con día, mes y año
        $currentDate = Carbon::now();
        $currentYear = Carbon::now()->year;

        // Buscar el período que contenga completamente la fecha actual
        $periodos= Periodo::whereDate('fecha_inicio', '<=', $currentDate)  // Empezó antes o justo el primer día del mes
            ->whereDate('fecha_final', '>=', $currentDate)
            ->whereYear('created_at', $currentYear)  // Termina después o justo el último día del mes
            ->get();
        return view('grupos.index',compact('grupos','asignaturas','periodos'));
    }



    public function store(Request $request){
         //Validacion desde el modelo
        $validatedData = $request->validate(Grupo::$createRules,Grupo::messages());
    
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
