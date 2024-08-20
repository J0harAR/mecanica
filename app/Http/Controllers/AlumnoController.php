<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Alumno;
use  App\Models\Persona;
use  App\Models\Docente;
use  App\Models\Grupo;
use  App\Models\Periodo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AlumnoController extends Controller
{

    function _construct()
    {
        $this->middleware('permission:ver-alumnos', ['only' => ['index','filtraGrupo']]);
        $this->middleware('permission:crear-alumno', ['only' => ['store','checkNoControl']]);
        $this->middleware('permission:editar-alumno', ['only' => ['update']]);
        $this->middleware('permission:borrar-alumno', ['only' => ['destroy']]);
        $this->middleware('permission:asigar-grupo-alumno', ['only' => ['asignarGrupo']]);
        $this->middleware('permission:desasigar-grupo-alumno', ['only' => ['desasignarGrupo']]);
    }



        public function index()
        {
            $alumnos = Alumno::with('persona', 'grupos')->get();
            $grupos = Grupo::all();
            $TodosAlumnos=Alumno::all();
            $periodos=Periodo::all();
           

           $alumnosPorGrupo = [];
            foreach ($grupos as $grupo) {
               $alumnosPorGrupo[$grupo->clave_grupo] = $alumnos->filter(function ($alumno) use ($grupo) {
            
                return $alumno->grupos->contains('clave_grupo', $grupo->clave_grupo);
               });
            }

            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;

            $periodo = Periodo::whereYear('created_at',$currentYear)
            ->whereMonth('fecha_inicio', '>=',  $currentMonth)
            ->first();
            
            if(!$periodo){
                $mensaje="Registre algún periodo para acceder a esta sección";
                return view('blank',compact('mensaje'));
            }
            $grupos_permitidos=Grupo::where('clave_periodo',$periodo->clave)->get();
           
         

         return view('alumnos.index', compact('alumnosPorGrupo', 'grupos','TodosAlumnos','periodos','grupos_permitidos'));
        }


        public function store(Request $request){
            
        
            $validated = $request->validate([
                'curp' => 'required|unique:alumno|max:255',
                'no_control' => 'required|unique:alumno|max:255',
            ]);  


            $curp = $request->input('curp');
            $nombre=$request->input('nombre');   
            $apellido_p=$request->input('apellido_p');   
            $apellido_m=$request->input('apellido_m');   
            $no_control=$request->input('no_control');


            $persona_existente=Docente::where('curp',$curp)->first();

            //Validacion de inclusion
            if($persona_existente){
                return redirect()->route('alumnos.index')->with('error','Curp le pertenece a un docente');
            }
           

            $persona=new Persona;
            $persona->curp=$curp;
            $persona->nombre=$nombre;
            $persona->apellido_p=$apellido_p;
            $persona->apellido_m=$apellido_m;
           
            
            $alumno=new Alumno;
            $alumno->no_control=$no_control;
            $alumno->curp=$persona->curp;


            $persona->save();
            $alumno->save();

    
            return redirect()->route('alumnos.index')->with('success','Alumno agregado correctamente');;


        }



        public function update(Request $request,$id){
           
         
            $curp = $request->input('curp');
            $nombre=$request->input('nombre');   
            $apellido_p=$request->input('apellido_p');   
            $apellido_m=$request->input('apellido_m');   
            $no_control=$request->input('no_control');

            $persona_existente=Docente::where('curp',$curp)->first();

            //Validacion de inclusion
            if($persona_existente){
                return redirect()->route('alumnos.index')->with('error','Curp le pertenece a un docente');
            }
            $persona_curp=Persona::find($curp);
            
            $alumno=Alumno::find($id);
            
            if($persona_curp){
                    if($persona_curp->curp !==$alumno->curp){
                       
                        return redirect()->route('alumnos.index')->with('error','Curp duplicada');
                    }
                
            }

            $alumno_existente=Alumno::find($no_control);
            if($alumno_existente){
                if($alumno_existente->no_control !==$alumno->no_control){
                   
                    return redirect()->route('alumnos.index')->with('error','Numero de control duplicado');
                }
                
            
            }

        
            if($alumno){
              
                $persona=Persona::find($alumno->persona->curp);
                $alumno->no_control=$no_control;
                $alumno->curp=$persona->curp;

                $persona->curp=$curp;
                $persona->nombre=$nombre;
                $persona->apellido_p=$apellido_p;
                $persona->apellido_m=$apellido_m;
                
                $alumno->save();
                $persona->save();  

            
            }
            
            return redirect()->route('alumnos.index')->with('success','Alumno actualizado correctamente');

        }

        public function destroy($id){
            $alumno=Alumno::find($id);
            $persona = Persona::find($alumno->curp);
            $persona->delete();
            

            return redirect()->route('alumnos.index')->with('success','Alumno eliminado correctamente');

        }




        public function asignarGrupo(Request $request)
        {
            $selectedAlumnosString = $request->input('selected_alumnos');
            if (!$selectedAlumnosString) {
                return redirect()->route('alumnos.index')->with('error', 'No se seleccionó ningún alumno');
            }

            $selectedAlumnos = explode(',', $selectedAlumnosString);

            $grupo = Grupo::find($request->input('grupo'));

            if (!$grupo) {
                return redirect()->route('alumnos.index')->with('error', 'Grupo no encontrado');
            }

           
            $grupo->alumnos()->syncWithoutDetaching($selectedAlumnos);

            return redirect()->route('alumnos.index')->with('success', 'Grupo asignado correctamente');
        }

        public function desasignarGrupo(Request $request){

            $selectedAlumnos = $request->input('selected_alumnos', []);          
            $grupo=Grupo::find($request->input('clave_grupo'));

            if(!$grupo){
                return redirect()->route('alumnos.index')->with('error','Grupo no encontrado');
            }
            if(empty($selectedAlumnos)){
                return redirect()->route('alumnos.index')->with('error','Ningún alumno seleccionado');
            }

            $grupo->alumnos()->detach($selectedAlumnos);

            return redirect()->route('alumnos.index')->with('success','Alumnos desasignado correctamente del grupo');

        }

        public function filtraGrupo(Request $request){

            if(!$request->input('periodo') and !$request->input('grupo')){
               
                $alumnos=Alumno::all();
                return redirect()->route('alumnos.index')->with(['alumnos' => $alumnos]);
            }

            $grupo = Grupo::where('clave_periodo', $request->input('periodo'))
            ->where('clave_grupo', $request->input('grupo'))
            ->first();

            if(!$grupo){
                return redirect()->route('alumnos.index')->with('error','Grupo no encontrado');
            }

            $alumnos=$grupo->alumnos;

             return redirect()->route('alumnos.index')->with(['alumnos' => $alumnos,'grupo'=>$grupo]);

        }

        public function checkNoControl($no_control)
        {
            $exists = Alumno::where('no_control', $no_control)->exists();
            return response()->json(['exists' => $exists]);
        }

  

}
