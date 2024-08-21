<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Docente;
use  App\Models\Practica;
use  App\Models\Catalogo_articulo;
use  App\Models\Articulo_inventariado;
use  App\Models\Alumno;
use  App\Models\Grupo;
use  App\Models\Persona;
use App\Models\Asignatura;
use Illuminate\Support\Facades\DB;

class PracticaController extends Controller
{
    
    function _construct()
    {
        $this->middleware('permission:ver-practicas', ['only' => ['index','filtrar','practicasAlumnos','obtener_alumnos_practica']]);
        $this->middleware('permission:crear-practica', ['only' => ['create','store']]);
        $this->middleware('permission:ver-practica', ['only' => ['show']]);
        $this->middleware('permission:editar-practica', ['only' => ['edit','update']]);
        $this->middleware('permission:borrar-practica', ['only' => ['destroy']]);
        $this->middleware('permission:completar-practica', ['only' => ['completar_practica']]);
        $this->middleware('permission:crear-practica-alumno', ['only' => ['create_practica_alumno','store_practica_Alumno']]);
   
    }


    public function index() {
        $practicas = Practica::with(['catalogo_articulos'])->get();
        $docentes = Docente::with('persona')->get(); // Obtener los docentes con su relación a persona
        $asignaturas = Asignatura::all(); // Obtener todas las asignaturas
        return view('practicas.index', compact('practicas', 'docentes', 'asignaturas'));
    }


    public function create(){
        $catalogo_articulos=Catalogo_articulo::all();
        $docentes=Docente::all();
        $grupos=Grupo::all();
        return view('practicas.crear',compact('docentes','catalogo_articulos','grupos'));
      
    }

    public function store(Request $request){
        $id_practica = $request->input('codigo_practica');
    
        // Verificar si la práctica ya existe
        $existingPractica = Practica::find($id_practica);
        if ($existingPractica) {
            return redirect()->route('practicas.create')->with('error', 'La práctica ya existe.');
        }
    
        $id_docente = $request->input('docente');
        $clave_grupo=$request->input('grupo');
        $nombre = $request->input('nombre_practica');
        $objetivo = $request->input('objetivo');
        $introduccion = $request->input('introduccion');
        $fundamento = $request->input('fundamento');
        $referencias = $request->input('referencias');
    
       

        $practica = new Practica;
    
        $practica->id_practica = $id_practica;
        $practica->id_docente = $id_docente;
        $practica->clave_grupo=$clave_grupo;
        $practica->nombre = $nombre;
        $practica->objetivo = $objetivo;
        $practica->introduccion = $introduccion;
        $practica->fundamento = $fundamento;
        $practica->referencias = $referencias;
    
        $practica->estatus = 0;
        $practica->save();
        $practica=Practica::find($id_practica);
        $practica->catalogo_articulos()->sync($request->input('articulos', []));
        
        return redirect()->route('practicas.index')->with('success', 'La práctica ha sido creada exitosamente.');
    }
    
    public function show($id){
        $practica=Practica::find($id);
        $docentes=Docente::all();
        $articulos=Catalogo_articulo::all();
       return view('practicas.mostrar',compact('practica','docentes','articulos'));
    }


    public function edit($id){
       $practica=Practica::find($id);
       $docentes=Docente::all();
       $articulos=Catalogo_articulo::all();
       $grupos=Grupo::all();
       return view('practicas.editar',compact('practica','docentes','articulos','grupos'));
    }

    public function update(Request $request, $id){
        $id_practica = $request->input('codigo_practica');
        $id_docente = $request->input('docente');
        $clave_grupo=$request->input('grupo');
        $nombre = $request->input('nombre_practica');
        $objetivo = $request->input('objetivo');
        $introduccion = $request->input('introduccion');
        $fundamento = $request->input('fundamento');
        $referencias = $request->input('referencias');
        
        

        $practica = Practica::firstOrNew(['id_practica' => $id_practica]);  
            $practica->id_practica = $id_practica;
            $practica->id_docente = $id_docente;
            $practica->clave_grupo=$clave_grupo;
            $practica->nombre = $nombre;
            $practica->objetivo = $objetivo;
            $practica->introduccion = $introduccion;
            $practica->fundamento = $fundamento;
            $practica->referencias = $referencias;
            $practica->catalogo_articulos()->sync($request->input('articulos', []));
            $practica->save();

        return redirect()->route('practicas.index')->with('success', 'La práctica ha sido actualizada exitosamente.');   
    }

    public function destroy($id){
        $practica = Practica::find($id);
        $practica->delete();

        return redirect()->route('practicas.index')->with('success', 'La práctica ha sido eliminada exitosamente.');   

    }

    
    
    public function filtrar(Request $request) {
        $id_docente = $request->input('docente');
        $clave_asignatura = $request->input('asignatura');
        $estatus = $request->input('estatus');
    
        $query = Practica::query();
    
        if (!empty($id_docente)) {
            $query->where('id_docente', $id_docente);
        }
    
        if (!empty($clave_asignatura)) {
            $grupos = Grupo::where('clave_asignatura', $clave_asignatura)->pluck('clave_grupo');
            if ($grupos) {
                $query->whereIn('clave_grupo', $grupos);
            } else {
                $query->where('clave_grupo', null);
            }
        }
    
        if (isset($estatus)) {
            $query->where('estatus', $estatus);
        }
    
        $practicas = $query->get();
    
        return redirect()->route('practicas.index')->with(['practicas' => $practicas]);
    }

    public function completar_practica($id){
        $practica=Practica::find($id);
        $practica->estatus=1;
        $practica->save();

        $practicas=Practica::where('estatus',1)->get();        
        return redirect()->route('practicas.index')->with(['practicas' => $practicas]);
    }



    //PARTE DE PRACTICAS DONDE SE VAN A REALIZAR EN EL LABORATORIO





    public function create_practica_alumno (){
        $alumnos=Alumno::all();
        $practicas=Practica::with(['catalogo_articulos'])->get();
        $articulos_inventariados=Articulo_inventariado::where('estatus','Disponible')->get();
        $docentes=Docente::all();
        return view('practicas.alumnos',compact('practicas','articulos_inventariados','docentes','alumnos'));
    }


    public function store_practica_Alumno(Request $request){
        
        $alumnos=$request->input('alumnos');
        $practica=Practica::find($request->input('practica'));
        $practica_articulos=$practica->catalogo_articulos()->pluck('id_articulo')->toArray();
        $articulos_inventariados=$request->input('articulos');
        $articulos_extra=$request->input('articulos-extras');
        $fecha=$request->input('fecha');
        $no_equipo=$request->input('no_equipo');
        $hora_entrada=$request->input('hora_entrada');
        $hora_salida=$request->input('hora_salida');

        if(!$alumnos){
            return redirect()->route('practicasAlumno.create')->with('error', 'Ningun alumno seleccionado');
        }
        if(!$articulos_inventariados){
            return redirect()->route('practicasAlumno.create')->with('error', 'Ningun articulo seleccionado');
        }

        
        foreach ($alumnos as $alumno) { 
            $aparece_grupo=0; 

            $alumno_encontrado=Alumno::find($alumno);  
            
            if(!$practica->grupo){
                return redirect()->route('practicasAlumno.create')->with('error', 'Practica sin grupo asignado');
            }  

            foreach ($alumno_encontrado->grupos as $grupo_alumno) {
                if($grupo_alumno->clave_grupo === $practica->grupo->clave_grupo){
                        $aparece_grupo++;
                }
            }

            if($aparece_grupo == 0){
                return redirect()->route('practicasAlumno.create')->with('error', 'Alumno no pertenece al grupo');
            }
            $aparece_grupo=0;
        }
        
       
        $articulo_presente = false;
        foreach ($articulos_inventariados as $id_articulo_inventariado) {
            $articulo_presente = false;
            $articulo=Articulo_inventariado::find($id_articulo_inventariado);
        
                foreach ($practica_articulos as $practica_articulo) {

                   if($articulo->Catalogo_articulos->id_articulo == $practica_articulo){
                    $articulo_presente = true;
                    break; 
                   }
                }
                if (!$articulo_presente) {    
                  
                    return redirect()->route('practicasAlumno.create')->with('error', 'Articulos no están asociados a la practica.');
               }
        }


        foreach ($articulos_inventariados as $id_articulo_inventariado) {
            $articulo=Articulo_inventariado::find($id_articulo_inventariado);
            $articulo->estatus="No disponible";
            $articulo->save();
        }


   
        $practica->alumnos()->syncWithPivotValues($alumnos,['fecha'=>$fecha,'no_equipo'=>$no_equipo,'hora_entrada'=>$hora_entrada,'hora_salida'=>$hora_salida]);
        $practica->articulo_inventariados()->sync($articulos_inventariados);
     

        if($articulos_extra!=null){
            foreach ($articulos_extra as $articulo) {

                $practica->articulo_inventariados()->attach($practica->id_practica,['inventario_id'=>$articulo]);
            }

        }
        
        $practica->save();

        return redirect()->route('practicas.alumnos.index')->with('success', 'Practica del alumno registrada correctamente');
       
    }


    public function practicasAlumnos(Request $request){
        $practicas=Practica::all();
        return view('practicas.practicas_alumnos',compact('practicas'));
    }

    public function obtener_alumnos_practica(Request $request){
        $practica = Practica::with('alumnos.persona')->find($request->input('id'));

        $alumnos=$practica->alumnos;
        return response()->json($alumnos);
    }


}
