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
use Illuminate\Support\Facades\Auth;

class AlumnoController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:ver-alumnos', ['only' => ['index','filtraGrupo']]);
        $this->middleware('permission:crear-alumnos', ['only' => ['store','checkNoControl']]);
        $this->middleware('permission:editar-alumnos', ['only' => ['update']]);
        $this->middleware('permission:borrar-alumnos', ['only' => ['destroy']]);
        $this->middleware('permission:asigar-grupo-alumno', ['only' => ['asignarGrupo']]);
        $this->middleware('permission:desasigar-grupo-alumno', ['only' => ['desasignarGrupo']]);
        $this->middleware('auth');//Aqui se valida que el usuario este autentica para todo el controlador
    }



        public function index()
        {   
           
            //Listamos todos los datos para poder ingresar un alumno
            $alumnos = Alumno::with('persona', 'grupos')->get();
            $grupos = Grupo::all();
            $TodosAlumnos=Alumno::all();
            $periodos=Periodo::all();
           
           
            // Obtengo la fecha actual con día, mes y año
            $currentDate = Carbon::now();
            $currentYear = Carbon::now()->year;

            // Buscar el período que contenga completamente la fecha actual
            $periodo = Periodo::whereDate('fecha_inicio', '<=', $currentDate)  // Empezó antes o justo el primer día del mes
                ->whereDate('fecha_final', '>=', $currentDate)
                ->whereYear('created_at', $currentYear)  // Termina después o justo el último día del mes
                ->first();

            
            //Si no existe ningun periodo registrado no mostrara los alumnos
            if(!$periodo){
                $mensaje="Registre algún periodo para acceder a esta sección";
                return view('blank',compact('mensaje'));
            }
            //Aqui se va retornar los grupos permitidos que esten dentro del rango de las fechas del periodo
            $grupos_permitidos=Grupo::where('clave_periodo',$periodo->clave)->get();
           
         

         return view('alumnos.index', compact('grupos','TodosAlumnos','periodos','grupos_permitidos'));
        }


        public function store(Request $request){
           
            //Validaciones desde el modelo
            $validatedData = $request->validate(Alumno::$createRules,Alumno::messages());

            //Se guardan las request 
            $curp = $request->input('curp');
            $nombre=$request->input('nombre');   
            $apellido_p=$request->input('apellido_p');   
            $apellido_m=$request->input('apellido_m');   
            $no_control=$request->input('no_control');

           
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
          
            //Validaciones desde el modelo
            $validatedData = $request->validate(Alumno::updateRules($id),Alumno::messages());

             //Se guardan las request 
            $curp = $request->input('curp');
            $nombre=$request->input('nombre');   
            $apellido_p=$request->input('apellido_p');   
            $apellido_m=$request->input('apellido_m');   
            $no_control=$request->input('no_control');

            
            $alumno=Alumno::find($id);//Consultamos que si se encuentre el alumno que se paso como parametro el $id

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

            //Validaciones desde el modelo
            $validatedData = $request->validate(Alumno::$AsignarRules,Alumno::messages());
            //Se guardan los alumnos que se seleccionaron en el checkbox multiple
            $selectedAlumnosString = $request->input('selected_alumnos');
            
            //Separamos los numeros de control convirtiendolo en un array ya que estan de esta forma : 19161220,193232
            $selectedAlumnos = explode(',', $selectedAlumnosString);

            //Revisamos que el grupo se encuentre
            $grupo = Grupo::find($request->input('grupo'));

            if (!$grupo) {
                return redirect()->route('alumnos.index')->withErrors([
                    'grupo' => 'Grupo no encontrado'
                ]);
            }
           
           //Aqui se sincroniza es decir se asigan los numeros de control al grupo
            $grupo->alumnos()->syncWithoutDetaching($selectedAlumnos);

            return redirect()->route('alumnos.index')->with('success', 'Grupo asignado correctamente');
        }

        public function desasignarGrupo(Request $request){
            //Validaciones desde el modelo
            $validatedData = $request->validate(Alumno::$DesasignarRules,Alumno::messages());
            //Se guardan los alumnos que se seleccionaron en el checkbox multiple
            $selectedAlumnos = $request->input('selected_alumnos', []);  
            
            
            //Revisamos que el grupo se encuentre
            $grupo=Grupo::find($request->input('clave_grupo'));

            if (!$grupo) {
                return redirect()->route('alumnos.index')->withErrors([
                    'clave_grupo' => 'Grupo no encontrado'
                ]);
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
            ->where('id', $request->input('grupo'))
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
