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
        $this->middleware('permission:ver-alumnos', ['only' => ['index']]);
        $this->middleware('permission:crear-alumno', ['only' => ['store']]);
        $this->middleware('permission:editar-alumno', ['only' => ['update']]);
        $this->middleware('permission:borrar-alumno', ['only' => ['destroy']]);
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

            $alumno=Alumno::find($no_control);

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

               // $grupos = $request->input('grupos', []);
               // $grupos_alumno = $alumno->grupos->pluck('clave_grupo')->toArray();

               
            //    $grupos=$request->input('grupos',[]);
            //     foreach ($grupos as $grupo) {
    
            //         $alumno->grupos()->attach($alumno->no_control,['clave_grupo'=>$grupo]);
            //     }
              
                /*
                foreach ($grupos as $grupo) {
                    if (!in_array($grupo, $grupos_alumno)) {
                        $alumno->grupos()->attach($grupo, ['clave_grupo' => $grupo]);
                    }
                }
                */
            }
            
            return redirect()->route('alumnos.index')->with('success','Alumno actualizado correctamente');

        }

        public function destroy($id){
            $alumno=Alumno::find($id);
            $alumno->delete();

            return redirect()->route('alumnos.index')->with('success','Alumno eliminado correctamente');

        }




        public function asignarGrupo(Request $request){

          

            $selectedAlumnosString = $request->input('selected_alumnos');
            
    
            $selectedAlumnos = explode(',', $selectedAlumnosString);
          

            $grupo=Grupo::find($request->input('grupo'));
            
        
            if(!$grupo){
                return redirect()->route('alumnos.index')->with('error','Grupo no encontrado');
            }
            
            
            $grupo->alumnos()->attach($selectedAlumnos);
            

            return redirect()->route('alumnos.index')->with('success','Grupo asignado correctamente');

        }
        public function desasignarGrupo(Request $request){


            $alumno=Alumno::find($request->input('no_control'));
            $grupo=Grupo::find($request->input('grupo'));
            if(!$alumno){
                return redirect()->route('alumnos.index')->with('error','Alumno no encontrado');
            }

            if(!$grupo){
                return redirect()->route('alumnos.index')->with('error','Grupo no encontrado');
            }

            $grupos_alumno=$alumno->grupos->pluck('clave_grupo')->toArray();


            if($grupos_alumno){
                
                foreach ($grupos_alumno as $grupo_alumno) {
                    if ($grupo_alumno === $grupo->clave_grupo) {    
                        $alumno->grupos()->detach($grupo->clave_grupo);
                        
                        return redirect()->route('alumnos.index')->with('success','Grupo desasignado correctamente');
                    }                        
                }

            }
            return redirect()->route('alumnos.index')->with('error','Alumno no pertenece a ese grupo');

             
        }

        public function filtraGrupo(Request $request){

            $grupo = Grupo::where('clave_periodo', $request->input('periodo'))
            ->where('clave_grupo', $request->input('grupo'))
            ->first();

            if(!$grupo){
                return redirect()->route('alumnos.index')->with('error','Grupo no encontrado');
            }

            $alumnos=$grupo->alumnos;

             return redirect()->route('alumnos.index')->with(['alumnos' => $alumnos,'grupo'=>$grupo]);

        }

}
