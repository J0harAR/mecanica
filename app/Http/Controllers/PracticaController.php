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

class PracticaController extends Controller
{
    
    function _construct()
    {
        $this->middleware('permission:ver-practicas', ['only' => ['index','filtrar']]);
        $this->middleware('permission:crear-practica', ['only' => ['create','store']]);
        $this->middleware('permission:ver-practica', ['only' => ['show']]);
        $this->middleware('permission:editar-practica', ['only' => ['edit','update']]);
        $this->middleware('permission:borrar-practica', ['only' => ['destroy']]);
        $this->middleware('permission:completar-practica', ['only' => ['completar_practica']]);
        $this->middleware('permission:crear-practica-alumno', ['only' => ['create_practica_alumno','store_practica_Alumno']]);
   
    }


    public function index(){

        $practicas=Practica::with(['catalogo_articulos'])->get();
      
        
        return view('practicas.index',compact('practicas'));   
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
        
        $practica = Practica::find($id);

        if($id_practica === $practica->id_practica ){
            $practica->id_practica = $id_practica;
            $practica->id_docente = $id_docente;
            $practica->clave_grupo=$clave_grupo;
            $practica->nombre = $nombre;
            $practica->objetivo = $objetivo;
            $practica->introduccion = $introduccion;
            $practica->fundamento = $fundamento;
            $practica->referencias = $referencias;
            $practica->estatus = 0;
            $practica->catalogo_articulos()->sync($request->input('articulos', []));
            $practica->save();
        }else{
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

            $practica->catalogo_articulos()->sync($request->input('articulos', []));
        }

    
        return redirect()->route('practicas.index')->with('success', 'La práctica ha sido actualizada exitosamente.');   
    }

    public function destroy($id){
        $practica = Practica::find($id);
        $practica->delete();

        return redirect()->route('practicas.index')->with('success', 'La práctica ha sido eliminada exitosamente.');   

    }

    public function filtrar(Request $request){
        $nombre_docente=$request->input('docente');
        $nombre_asignatura=$request->input('asignatura');
        $estatus = $request->input('estatus');
       
        $query = Practica::query();

      
        if (!empty($nombre_docente)) {
            $persona = Persona::where('nombre', $nombre_docente)->first();
            if ($persona) {
                $docente = Docente::where('curp', $persona->curp)->first();
                if ($docente) {
                    $query->where('id_docente', $docente->rfc);
                }
            }
            else{
                $query->where('id_docente', null);
            }
          
           
        }

         
        if (!empty($nombre_asignatura)) {
         
            $asignatura = Asignatura::where('nombre', $nombre_asignatura)->first();
    
            if ($asignatura) {
              $grupos = Grupo::where('clave_asignatura', $asignatura->clave)->pluck('clave_grupo');                   
                if ($grupos) {

                    foreach ($grupos as $key => $grupo) {
                        if($grupo!=null){
                          
                            //$query->where('clave_grupo', $grupo);
                        }
                       
                    }
                                    
                }
            } else{
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
        $practicas=Practica::with(['catalogo_articulos'])->get();
        $articulos_inventariados=Articulo_inventariado::all();
        $docentes=Docente::all();
        return view('practicas.alumnos',compact('practicas','articulos_inventariados','docentes'));
    }


    public function store_practica_Alumno(Request $request){
        $alumno=Alumno::find($request->input('no_control'));
        $practica=Practica::find($request->input('practica'));
        $practica_articulos=$practica->catalogo_articulos()->pluck('id_articulo')->toArray();
        $articulos_inventariados=$request->input('articulos');
        $articulos_extra=$request->input('articulos-extras');
        $fecha=$request->input('fecha');
        $no_equipo=$request->input('no_equipo');
        $hora_entrada=$request->input('hora_entrada');
        $hora_salida=$request->input('hora_salida');

        if($alumno==null){
            return redirect()->route('practicasAlumno.create')->with('alumno_no_encontrado', 'Alumno no encontrado.');
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

   
        $practica->alumnos()->attach($alumno->no_control,['fecha'=>$fecha,'no_equipo'=>$no_equipo,'hora_entrada'=>$hora_entrada,'hora_salida'=>$hora_salida]);
        $practica->articulo_inventariados()->sync($articulos_inventariados);
     

        if($articulos_extra!=null){
            foreach ($articulos_extra as $articulo) {

                $practica->articulo_inventariados()->attach($practica->id_practica,['inventario_id'=>$articulo]);
            }

        }
        
        $practica->save();

        return redirect()->route('practicas.index');
       
    }

}
