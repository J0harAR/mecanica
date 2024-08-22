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
        //Vamos a retornar todas las practicas
        $practicas = Practica::with(['catalogo_articulos'])->get();
        $docentes = Docente::with('persona')->get(); // Obtener los docentes con su relación a persona
        $asignaturas = Asignatura::all(); // Obtener todas las asignaturas
        return view('practicas.index', compact('practicas', 'docentes', 'asignaturas'));
    }


    public function create(){
        //Vamos renderizar la view con los articulos, los docentes  y grupos
        $catalogo_articulos=Catalogo_articulo::all();
        $docentes=Docente::all();
        $grupos=Grupo::all();
        return view('practicas.crear',compact('docentes','catalogo_articulos','grupos'));
      
    }

    public function store(Request $request){
        //Guardamos el codigo de la practica
        $id_practica = $request->input('codigo_practica');
    
        // Verificar si la práctica ya existe si existe retornar error
        $existingPractica = Practica::find($id_practica);
        if ($existingPractica) {
            return redirect()->route('practicas.create')->with('error', 'La práctica ya existe.');
        }
        //Guaramos las requests
        $id_docente = $request->input('docente');
        $clave_grupo=$request->input('grupo');
        $nombre = $request->input('nombre_practica');
        $objetivo = $request->input('objetivo');
        $introduccion = $request->input('introduccion');
        $fundamento = $request->input('fundamento');
        $referencias = $request->input('referencias');
    
    
        //Creamos la practica
        $practica = new Practica;
    
        $practica->id_practica = $id_practica;
        $practica->id_docente = $id_docente;
        $practica->clave_grupo=$clave_grupo;
        $practica->nombre = $nombre;
        $practica->objetivo = $objetivo;
        $practica->introduccion = $introduccion;
        $practica->fundamento = $fundamento;
        $practica->referencias = $referencias;
    
        $practica->estatus = 0;//estatus 0=no completadad 1=completada
        $practica->save();//Guardamos la practica

        $practica=Practica::find($id_practica);
        $practica->catalogo_articulos()->sync($request->input('articulos', []));//Se asignamos los articulso que necesita la practica
        
        return redirect()->route('practicas.index')->with('success', 'La práctica ha sido creada exitosamente.');
    }
    
    public function show($id){
       
        $practica=Practica::find($id); //Renderizamos la view con los datos de la practica que encontramos con el ID
        $docentes=Docente::all();
        $articulos=Catalogo_articulo::all();
       return view('practicas.mostrar',compact('practica','docentes','articulos'));
    }


    public function edit($id){
         //Buscamos la practica que vamos a editar
       $practica=Practica::find($id);
       $docentes=Docente::all();//Tambien retornamos los docentes por si se actualiza ese campo
       $articulos=Catalogo_articulo::all();//Tambien retornamos los articulos por si quiere cambiar los articulos que ocupa la practica
       $grupos=Grupo::all();
       return view('practicas.editar',compact('practica','docentes','articulos','grupos'));
    }

    public function update(Request $request, $id){
        //Guardamos los requestss
        $id_practica = $request->input('codigo_practica');
        $id_docente = $request->input('docente');
        $clave_grupo=$request->input('grupo');
        $nombre = $request->input('nombre_practica');
        $objetivo = $request->input('objetivo');
        $introduccion = $request->input('introduccion');
        $fundamento = $request->input('fundamento');
        $referencias = $request->input('referencias');
        
        
        //Buscamos la practica que vamos a actualizar si la encuentra que actualice y si no que se cree una nueva
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
            $practica->save();//Guardamos la practica

        return redirect()->route('practicas.index')->with('success', 'La práctica ha sido actualizada exitosamente.');   
    }

    public function destroy($id){
        //Buscamos la practica que vamos a eliminar
        $practica = Practica::find($id);
        $practica->delete();//Eliminamos la practica

        return redirect()->route('practicas.index')->with('success', 'La práctica ha sido eliminada exitosamente.');   

    }

    
    
    public function filtrar(Request $request) {
    // Guardamos las request para poder filtrar las practicas
    $id_docente = $request->input('docente');
    $clave_asignatura = $request->input('asignatura');
    $estatus = $request->input('estatus');

    // Crear una nueva consulta de práctica (query) sin condiciones iniciales
    $query = Practica::query();

    // Si se seleccionó un docente
    if (!empty($id_docente)) {
        // Agregar una condición a la consulta para filtrar por el ID del docente
        $query->where('id_docente', $id_docente);
    }

    // Si se seleccionó una asignatura.
    if (!empty($clave_asignatura)) {
        // Obtener los grupos asociados a la asignatura seleccionada
        $grupos = Grupo::where('clave_asignatura', $clave_asignatura)->pluck('clave_grupo');
        
        // Si se encontraron grupos para la asignatura.
        if ($grupos) {
            // Filtrar la consulta para incluir solo prácticas que pertenezcan a los grupos encontrados
            $query->whereIn('clave_grupo', $grupos);
        } else {
            // Si no se encontraron grupos, filtrar la consulta para prácticas que no tengan grupo asignado
            $query->where('clave_grupo', null);
        }
    }

    // Si se ha especificado un estatus en la solicitud
    if (isset($estatus)) {
        // Agregar una condición a la consulta para filtrar por el estatus
        $query->where('estatus', $estatus);
    }

    // Ejecutar la consulta y obtener los resultados de las prácticas
    $practicas = $query->get();

    // Redirigir a la ruta 'practicas.index' con los resultados de las prácticas obtenidas
    return redirect()->route('practicas.index')->with(['practicas' => $practicas]);
    }

    public function completar_practica($id){
        //Vamos a buscar la practica que vamos completar o dar completar
        $practica=Practica::find($id);
        $practica->estatus=1;//Cambia mi estado 1 es decir completada
        $practica->save();

        $practicas=Practica::where('estatus',1)->get(); //Retornamos las practica pero solo las completadas     
        return redirect()->route('practicas.index')->with(['practicas' => $practicas]);
    }



    //PARTE DE PRACTICAS DONDE SE VAN A REALIZAR EN EL LABORATORIO


    public function create_practica_alumno (){
        //Renderizaremos la view para crear un alumno practica
        $alumnos=Alumno::all();//Retornamos los alumnos
        $practicas=Practica::with(['catalogo_articulos'])->get();//Retornamos las practicas con la relacion catalogo
        $articulos_inventariados=Articulo_inventariado::where('estatus','Disponible')->get();//Retornamos los articulos que estan disponibles
        $docentes=Docente::all();//Retornamos todos los docentes
        return view('practicas.alumnos',compact('practicas','articulos_inventariados','docentes','alumnos'));
    }


    public function store_practica_Alumno(Request $request){
        //Guardamos las request para para practicas
        $alumnos=$request->input('alumnos');
        $practica=Practica::find($request->input('practica'));
        $practica_articulos=$practica->catalogo_articulos()->pluck('id_articulo')->toArray();
        $articulos_inventariados=$request->input('articulos');
        $articulos_extra=$request->input('articulos-extras');
        $fecha=$request->input('fecha');
        $no_equipo=$request->input('no_equipo');
        $hora_entrada=$request->input('hora_entrada');
        $hora_salida=$request->input('hora_salida');

        //Si no se selecciono ningun alumno retornara un error
        if(!$alumnos){
            return redirect()->route('practicasAlumno.create')->with('error', 'Ningun alumno seleccionado');
        }
        //Si no se selecciono ningun articulo retornara un error
        if(!$articulos_inventariados){
            return redirect()->route('practicasAlumno.create')->with('error', 'Ningun articulo seleccionado');
        }

        //iteramos entre los alumnos que se seleccionaron
        foreach ($alumnos as $alumno) { 
            $aparece_grupo=0; //No servica para ver si pertenece al grupo de la practica

            //Buscmaos al alumno
            $alumno_encontrado=Alumno::find($alumno);  
            
            //Revisamos que la practica tenga grupo si no retornara un error
            if(!$practica->grupo){
                return redirect()->route('practicasAlumno.create')->with('error', 'Practica sin grupo asignado');
            }  
            //Iteramos entre los grupos del alumno
            foreach ($alumno_encontrado->grupos as $grupo_alumno) {
                if($grupo_alumno->clave_grupo === $practica->grupo->clave_grupo){//Si el grupo del alumno es igual al grupo de la practica aumentara ++
                        $aparece_grupo++;
                }
            }

            if($aparece_grupo == 0){//Si la variable no aumentara significara que no pertece al mismo grupo de la practica
                return redirect()->route('practicasAlumno.create')->with('error', 'Alumno no pertenece al grupo');
            }
            $aparece_grupo=0;
        }
        
        //Inicializamos la variable en false para verificar que articulos estan presentes
        $articulo_presente = false;
        //Iteramos entre los articulos que selecciono
        foreach ($articulos_inventariados as $id_articulo_inventariado) {
            $articulo_presente = false;
            //Buscmaos el articulo en el inventario
            $articulo=Articulo_inventariado::find($id_articulo_inventariado);
        
                //Iteramos ahora entre los articulos de la practica que le pertenecen
                foreach ($practica_articulos as $practica_articulo) {

                   if($articulo->Catalogo_articulos->id_articulo == $practica_articulo){//Checamos que si el articulo es del mismo tipo de los articulos de la practica
                    $articulo_presente = true;//Si se cumple significa que si esta presente el articulo y cambia a true
                    break; 
                   }
                }
                //Si la variable sigue en false significa que no esta asociado a la practica el articulo que se selecciono y retornara error
                if (!$articulo_presente) {    
                  
                    return redirect()->route('practicasAlumno.create')->with('error', 'Articulos no están asociados a la practica.');
               }
        }

        //Cambiamos el estatus de los articulos a no disponible
        foreach ($articulos_inventariados as $id_articulo_inventariado) {
            $articulo=Articulo_inventariado::find($id_articulo_inventariado);
            $articulo->estatus="No disponible";
            $articulo->save();
        }


        //Asignamos a la practica los alumnos que la realizaron
        $practica->alumnos()->syncWithPivotValues($alumnos,['fecha'=>$fecha,'no_equipo'=>$no_equipo,'hora_entrada'=>$hora_entrada,'hora_salida'=>$hora_salida]);
       //Asignamos los articulos a la practica
        $practica->articulo_inventariados()->sync($articulos_inventariados);
     
        //Si se realizo algun prestamo dentro de la practica seran los articulos extras y estos pueden o no pertencer al mismo tipo de articulos de la practica
        if($articulos_extra!=null){
            foreach ($articulos_extra as $articulo) {
                //Asignamos los articulos extra si es que se eligieron
                $practica->articulo_inventariados()->attach($practica->id_practica,['inventario_id'=>$articulo]);
            }

        }
        
        $practica->save();//Guardamos la practica

        return redirect()->route('practicas.alumnos.index')->with('success', 'Practica del alumno registrada correctamente');
       
    }


    public function practicasAlumnos(Request $request){
        //Retornamos todas las practicas para que se usaen en una check list
        $practicas=Practica::all();
        return view('practicas.practicas_alumnos',compact('practicas'));
    }

    public function obtener_alumnos_practica(Request $request){
        //Obtenemos la practica que queremos saber sus alumnos
        $practica = Practica::with('alumnos.persona')->find($request->input('id'));
        //Guardamos los alumnos que realizaron la practica por ID y lo retornamos
        $alumnos=$practica->alumnos;
        return response()->json($alumnos);
    }


}
