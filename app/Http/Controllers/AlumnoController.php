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
            //Listamos todos los datos para poder ingresar un alumno
            $alumnos = Alumno::with('persona', 'grupos')->get();
            $grupos = Grupo::all();
            $TodosAlumnos=Alumno::all();
            $periodos=Periodo::all();
           
            //Se agrupan los alumnos por grupo para la vista
           $alumnosPorGrupo = [];
            foreach ($grupos as $grupo) {
               $alumnosPorGrupo[$grupo->clave_grupo] = $alumnos->filter(function ($alumno) use ($grupo) {
            
                return $alumno->grupos->contains('clave_grupo', $grupo->clave_grupo);
               });
            }

            //Obtengo los el mes y el año en el que esta logueado el usuario
            $currentYear = Carbon::now()->year;//año
            $currentMonth = Carbon::now()->month;//mes

            //Se filtran solo los periodos que esten dentro del rango de año y mes
            $periodo = Periodo::whereYear('created_at',$currentYear)
            ->whereMonth('fecha_inicio', '>=',  $currentMonth)
            ->first();
            
            //Si no existe ningun periodo registrado no mostrara los alumnos
            if(!$periodo){
                $mensaje="Registre algún periodo para acceder a esta sección";
                return view('blank',compact('mensaje'));
            }
            //Aqui se va retornar los grupos permitidos que esten dentro del rango de las fechas del periodo
            $grupos_permitidos=Grupo::where('clave_periodo',$periodo->clave)->get();
           
         

         return view('alumnos.index', compact('alumnosPorGrupo', 'grupos','TodosAlumnos','periodos','grupos_permitidos'));
        }


        public function store(Request $request){
            
            //Validamos que el alumno tenga un curp y no de control unicos
            $validated = $request->validate([
                'curp' => 'required|unique:alumno|max:255',
                'no_control' => 'required|unique:alumno|max:255',
            ]);  

            //Se guardan las request 
            $curp = $request->input('curp');
            $nombre=$request->input('nombre');   
            $apellido_p=$request->input('apellido_p');   
            $apellido_m=$request->input('apellido_m');   
            $no_control=$request->input('no_control');

            //Verificamos que si existe un docente con ese curp
            $persona_existente=Docente::where('curp',$curp)->first();

            //Validacion de inclusion si es que se encuentra ese docente se retorna un mensaje de error
            if($persona_existente){
                return redirect()->route('alumnos.index')->with('error','Curp le pertenece a un docente');
            }
           
            //Creamos primero a la persona
            $persona=new Persona;
            $persona->curp=$curp;
            $persona->nombre=$nombre;
            $persona->apellido_p=$apellido_p;
            $persona->apellido_m=$apellido_m;
           
            //Se crea despues el alumno
            $alumno=new Alumno;
            $alumno->no_control=$no_control;
            $alumno->curp=$persona->curp;
        
            //Se guardan en la base de datos
            $persona->save();
            $alumno->save();

    
            return redirect()->route('alumnos.index')->with('success','Alumno agregado correctamente');;


        }



        public function update(Request $request,$id){
           
             //Se guardan las request 
            $curp = $request->input('curp');
            $nombre=$request->input('nombre');   
            $apellido_p=$request->input('apellido_p');   
            $apellido_m=$request->input('apellido_m');   
            $no_control=$request->input('no_control');

             //Verificamos que si existe un docente con ese curp
            $persona_existente=Docente::where('curp',$curp)->first();

            //Validacion de inclusion si es que se encuentra ese docente se retorna un mensaje de error
            if($persona_existente){
                return redirect()->route('alumnos.index')->with('error','Curp le pertenece a un docente');
            }
            
            $persona_curp=Persona::find($curp);//Consultamos que si existe una persona con ese curp
            
            $alumno=Alumno::find($id);//Consultamos que si se encuentre el alumno que se paso como parametro el $id

            //Validamos en caso de que si exista ya una persona con el curp
            if($persona_curp){
                    if($persona_curp->curp !==$alumno->curp){
                       
                        return redirect()->route('alumnos.index')->with('error','Curp duplicada');
                    }
                
            }

            $alumno_existente=Alumno::find($no_control);//Consultamos que si existe un alumno con ese numero de control
            
            //Validamos que si existe un alumno con ese numero de control retorne un error 
            if($alumno_existente){
                if($alumno_existente->no_control !==$alumno->no_control){
                   
                    return redirect()->route('alumnos.index')->with('error','Numero de control duplicado');
                }
                
            
            }

            //Actualizamos el alumno siempre y cuando exista
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
            //Se guardan las request 
            $alumno=Alumno::find($id);
            $persona = Persona::find($alumno->curp);
            $persona->delete();//Se elimina la persona ya que se elimina en cascada 
            

            return redirect()->route('alumnos.index')->with('success','Alumno eliminado correctamente');

        }




        public function asignarGrupo(Request $request)
        {
            //Se guardan los alumnos que se seleccionaron en el checkbox multiple
            $selectedAlumnosString = $request->input('selected_alumnos');
            if (!$selectedAlumnosString) {//Si en dado caso no se selecciono ninguno retornara un error
                return redirect()->route('alumnos.index')->with('error', 'No se seleccionó ningún alumno');
            }
            //Separamos los numeros de control convirtiendolo en un array ya que estan de esta forma : 19161220,193232
            $selectedAlumnos = explode(',', $selectedAlumnosString);

            //Revisamos que el grupo se encuentre
            $grupo = Grupo::find($request->input('grupo'));
            //Validamos que si el grupo no esta retorne un error
            if (!$grupo) {
                return redirect()->route('alumnos.index')->with('error', 'Grupo no encontrado');
            }

           //Aqui se sincroniza es decir se asigan los numeros de control al grupo
            $grupo->alumnos()->syncWithoutDetaching($selectedAlumnos);

            return redirect()->route('alumnos.index')->with('success', 'Grupo asignado correctamente');
        }

        public function desasignarGrupo(Request $request){
            //Se guardan los alumnos que se seleccionaron en el checkbox multiple
            $selectedAlumnos = $request->input('selected_alumnos', []);  
            
            //Revisamos que el grupo se encuentre
            $grupo=Grupo::find($request->input('clave_grupo'));

             //Validamos que si el grupo no esta retorne un error
            if(!$grupo){
                return redirect()->route('alumnos.index')->with('error','Grupo no encontrado');
            }

            if(empty($selectedAlumnos)){//Si en dado caso no se selecciono ninguno retornara un error
                return redirect()->route('alumnos.index')->with('error','Ningún alumno seleccionado');
            }
            //Se eliminan o desasignan del grupo los alumnos que se seleccionaron
            $grupo->alumnos()->detach($selectedAlumnos);

            return redirect()->route('alumnos.index')->with('success','Alumnos desasignado correctamente del grupo');

        }

        public function filtraGrupo(Request $request){
            //Se valida que si en dado caso que no se seleccione periodo ni grupo este retornara todos los alumnos
            if(!$request->input('periodo') and !$request->input('grupo')){
               
                $alumnos=Alumno::all();
                return redirect()->route('alumnos.index')->with(['alumnos' => $alumnos]);
            }
            //Filtramos y buscamos el grupo que coincidan con nuestras requests
            $grupo = Grupo::where('clave_periodo', $request->input('periodo'))
            ->where('clave_grupo', $request->input('grupo'))
            ->first();

            //Si no se encuentra el grupo retornara un error
            if(!$grupo){
                return redirect()->route('alumnos.index')->with('error','Grupo no encontrado');
            }

            //Aqui asignamos los alumnos que se encuentran dentro del grupo
            $alumnos=$grupo->alumnos;

             return redirect()->route('alumnos.index')->with(['alumnos' => $alumnos,'grupo'=>$grupo]);

        }

        public function checkNoControl($no_control)
        {   
            //Retornamos un json para confirmar si ya hay un alumno con el numero de control que se pasa de parametro
            $exists = Alumno::where('no_control', $no_control)->exists();
            return response()->json(['exists' => $exists]);
        }

  

}
