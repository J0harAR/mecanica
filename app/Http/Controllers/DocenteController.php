<?php

namespace App\Http\Controllers;
use  App\Models\Docente;
use  App\Models\Persona;
use  App\Models\Grupo;
use  App\Models\Alumno;
use  App\Models\Asignatura;
use  App\Models\Periodo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DocenteController extends Controller
{

    function _construct()
    {
        $this->middleware('permission:ver-docentes', ['only' => ['index']]);
        $this->middleware('permission:crear-docente', ['only' => ['create','store']]);
        $this->middleware('permission:ver-docente', ['only' => ['show','obtenerDatosDocente']]);
        $this->middleware('permission:editar-docente', ['only' => ['update']]);
        $this->middleware('permission:borrar-docente', ['only' => ['destroy']]);
        $this->middleware('permission:asignar-grupos-docente', ['only' => ['asigna','filtrar_asignaturas','asignar']]);
        $this->middleware('permission:eliminar-grupos-docente', ['only' => ['eliminacion_asignacion','filtrar','eliminar_asignacion']]);
    }

    public function index(){

        //Filtramos todos los docentes
        $docentes=Docente::all();
        return view('docentes.index',compact('docentes'));
    }

    public function create(){
            //Se retorna solo la vista para crear un docente

        return view('docentes.create');
    }   


    public function store(Request $request){
        //Se valida que no exista un curp existente
        $validated = $request->validate([
            'curp' => 'required|unique:docente|max:255',
        ]); 

        //Guaramos los requests
        $curp = $request->input('curp');
        $nombre=$request->input('nombre');   
        $apellido_p=$request->input('apellido_p');   
        $apellido_m=$request->input('apellido_m');   
        $rfc=$request->input('rfc');   
        $area=$request->input('area');
        
        $telefono=$request->input('telefono');
        //Buscamos a un alumno con ese curp
        $persona_existente=Alumno::where('curp',$curp)->first();
        //Validacion de inclusion si se encuentra ese alumno retornara un error
        if($persona_existente){

            return redirect()->route('docentes.create')->with('error','Curp le pertenece a un alumno');
        }

        //validacion rfc , buscamos un docente con el rfc
        $docente=Docente::find($rfc);
        //Si se encuentra el docente retornara un error de duplicado
        if($docente){
            return redirect()->route('docentes.create')->with('error','RFC duplicado');
        }

        //Si hay un foto que se subio se verifica el tipo y el peso
        if($request->has('foto')){

            $request->validate([
                'foto' => 'file|mimes:jpg,png|max:512',//Verificacion de peso y tipo
            ]);
          

            //Guardamos la foto en file
            $file=$request->file('foto');
            $extension=$file->getClientOriginalExtension();//Obtenemos su extension de la foto

           
            $filename=time().'.'.$extension;//Le adjuntamos la extension y la variable time() para su nombre
            $path='uploads/docentes/';//Especificamos donde la vamos a guardar

            $file->move($path,$filename);//Guardamos la imagen

        }

        //Se crea un persona 
            Persona::create([
                'curp'=>$curp,
                'nombre'=>$nombre,
                'apellido_p'=>$apellido_p,
                'apellido_m'=>$apellido_m,
                
            ]);
            $persona=Persona::find($curp);//Validacion para comprobar que si se guardo correctamente
            //Se crea el docente
            Docente::create([
                'rfc'=>$rfc,
                'curp'=>$persona->curp,
                'area'=>$area,
                'foto'=>$path.$filename,
                'telefono'=>$telefono,
    
            ]);
            
 
        return redirect()->route('docentes.index')->with('success','Docente agregado correctamente');
       
    }

    public function obtenerDatosDocente(Request $request){
        //Se obtiene los datos del docente a partir de la request id 
        $docente_id = $request->input('id');
        $docente = Docente::find($docente_id);//Se busca el docente

        $grupos=Grupo::where('id_docente',$docente_id)->get(); //Se filtran los grupos que le pertenecen al docente
        //Se retornan en json
        return response()->json($grupos);

    }

    public function show($id){
        //Buscamos al docente por el $id es decir por el rfc
        $docente=Docente::find($id);
        //Mostramos tadas las asignaturas del docente filtrada por grupos
        $asignaturas = Grupo::where('id_docente', $docente->rfc)
        ->whereNotNull('clave_asignatura')
        ->get();
        //Si tiene asignaturas se regresan 
        if($asignaturas){
            return view('docentes.show',compact('docente','asignaturas'));

        }//Si no tiene nos mostrara nada en la tabla 
      
    }   

    public function update(Request $request ,$id){
        
    
        //Guardamos las requests
        $nombre=$request->input('nombre');
        $apellido_p=$request->input('apellido_p');
        $apellido_m=$request->input('apellido_m');
        $curp=$request->input('curp');
        $area=$request->input('area');
        $telefono=$request->input('telefono');
        
        //Buscamos si existe un alumno con ese curp
    
        $persona_existente=Alumno::where('curp',$curp)->first();
        
        //Validacion de inclusion si se encuentra un alumno con ese curp retornara un error
        if($persona_existente){

            return redirect()->route('docentes.index')->with('error','Curp le pertenece a un alumno');
        }
       
        $persona_curp=Persona::find($curp); //Buscamos si se encuentra una persona con el curp
        $docente=Docente::find($id);
            //Si se encuentra entonces retorna un error ya que esta duplicada
            if($persona_curp){
                if($persona_curp->curp !==$docente->curp){
                
                    return redirect()->route('docentes.index')->with('error','Curp duplicada');
                }
            
        }

        //Si el docente existe entonces actualizamos sus datos
        if($docente){
            $persona=Persona::find($docente->persona->curp);
          
            $docente->area=$area;
            //Si se sube una foto
            if($request->has('foto')){
                
                $request->validate([
                    'foto' => 'file|mimes:jpg,png|max:512',//Validacion de tipo y peso
                ]);
                       
                $file=$request->file('foto');
                $extension=$file->getClientOriginalExtension();//Extension de la foto
                $filename=time().'.'.$extension;//Se le ponen en el nombre juntando la extension
                $path='uploads/docentes/';//Ruta donde se va guardar
                $file->move($path,$filename);//Se guarda la foto
                $docente->foto=$path.$filename; //Se le asigna la foto al docente
            }
            //Se actualizan el resto de los datos del docente
            $docente->telefono=$telefono;
            $docente->save();

            $persona->curp=$curp;
            $persona->nombre=$nombre;
            $persona->apellido_p=$apellido_p;
            $persona->apellido_m=$apellido_m;
            $persona->save();          
        }
        return redirect()->route('docentes.index')->with('success','Docente actualizado correctamente');



    }

    public function destroy($id){
    try {
        //Buscamos el docente y a la persona para que se pueda eliminar en cascada
        $docente = Docente::findOrFail($id);
        $persona = Persona::find($docente->curp);
        
        $persona->delete();//Se borra la persona ya que es en cascada

        return redirect()->route('docentes.index')->with('success', 'Docente eliminado correctamente');
    } catch (QueryException $e) {
        if ($e->getCode() == 23000) {
            //Este error se pone ya que si algun docente cuanta con alguna practica asignada no va dejar
            return redirect()->route('docentes.index')->with('error', 'No es posible eliminar el docente porque tiene prácticas asociadas');
        }
       
    }
}

    public function asigna(){
        //Obtenemos el año y mes
        $currentYear = Carbon::now()->year;//año
        
        $currentMonth = Carbon::now()->month;//mes
           
        //Filtramos los periodos que se encuentren en el rango de fechas que se esta logueado
        $periodos = Periodo::whereYear('created_at',$currentYear)
            ->whereMonth('fecha_inicio', '>=',  $currentMonth)
            ->get();
           
            
        //Filtramos todos los docentes y asinaturas
        $docentes=Docente::all();
        $asignaturas=Asignatura::all();
        return view('docentes.asignar',compact('docentes','asignaturas','periodos'));
    }

    //Este filtro es para la asignacion de asignaturas
    public function filtrar_asignaturas(Request $request){
        
        //Guaradmos las requests
        $clave_asignatura=$request->input('asignatura');
        $id_docente=$request->input('docente');
        $periodo=$request->input('periodo');
        //Revisamos que no se dejen campos vacios
        if (empty($clave_asignatura) || empty($id_docente) || empty($periodo)) {
            return redirect()->route('docentes.asigna')->with('error', 'Todos los campos son requeridos.');             
        }
        
        //Buscamos el docente, asignatura y periodo que se selecciono
        $docente=Docente::find($id_docente);
        $asignatura=Asignatura::find($clave_asignatura);
        $periodo=Periodo::find($periodo);
        //Filtramos aquellos grupos que no tengan docente asignado y sean de la misma asignatura
        $grupos=Grupo::where('clave_asignatura',$asignatura->clave)->where('id_docente',null)->get();
        
       
        return redirect()->route('docentes.asigna')->with(['grupos' => $grupos, 'docente' => $docente, 'periodo' => $periodo]);

    }

//Este metodo hace le metodo post para asignar las asignaturas
    public function asignar(Request $request){
        //Guardamos los requests
            $clave_periodo=$request->input('clave_periodo');
            $grupos=$request->input('grupos');

            //Buscamos el docente y el periodo 
            $docente=Docente::find($request->input('rfc_docente'));
            $periodo=Periodo::find($clave_periodo);
            
            //Verificamos que se hayan seleccionando algun grupo si no se retorna un error
            if(!$grupos){
                return redirect()->route('docentes.asigna')->with('error','No se selecciono ningun grupo');
            }   
            //Aqui vamos a intear entre los grupos que se seleccionaron
            foreach($grupos as $clave_grupo=>$datos_grupo){
                //Checar si tiene docente asignado el grupo
                $grupo_disponible=DB::table('grupo')                   
                        ->Where('clave_asignatura', $datos_grupo['asignatura'])
                        ->where('clave_grupo',$clave_grupo)
                        ->where('id_docente',null)
                        ->where('clave_periodo',$periodo->clave)
                        ->first();

                    //Si ese grupo no tiene grupo asignado se le asigna el docente de la request
                    if($grupo_disponible){
                        $grupo=Grupo::Where('clave_asignatura', $datos_grupo['asignatura'])
                         ->where('clave_grupo',$clave_grupo)
                        ->update(['id_docente'=>$docente->rfc]);  
                    }else{
                        //Si ya tiene un docente asignado retornara un error
                        return redirect()->route('docentes.asigna')->with('error','El grupo ya cuenta con docente');
                    }
                   
           
            }
         
          return redirect()->route('docentes.index')->with('success','Asignatura añadida a docente correctamente');
    }

    //Este metodo es el que va renderizar la view de desasignar asignaturas
    public function eliminacion_asignacion(){
        //Guarda todos los docentes y asignaturas
        $docentes=Docente::all();
        $asignaturas=Asignatura::all();
       
        //Se obtiene el año y mes del usuario que esta logueado
        $currentYear = Carbon::now()->year;//año
        
        $currentMonth = Carbon::now()->month;//Mes
        //Filtamos los periodos que se encuentren dentro del rango de año y mes
        $periodos = Periodo::whereYear('created_at',$currentYear)
            ->whereMonth('fecha_inicio', '>=',  $currentMonth)
            ->get();
        return view('docentes.desasignar',compact('docentes','asignaturas','periodos'));
    }

    //Este metodo es el filtro para desasignar asignaturas
    public function filtrar(Request $request) {
        //Buscamos el docente ,asignatura y periodo que se selecciono
        $docente=Docente::find($request->input('rfc'));
        $asignatura=Asignatura::find($request->input('id_asignatura'));
        $periodo=Periodo::find($request->input('periodo'));
        
        //Validamos que no se dejen campos sin relllenar
        if (!$docente || !$asignatura || !$periodo) {
            return redirect()->route('docentes.eliminacion_asignacion')->with('error', 'Todos los campos son requeridos');
        }
    
        //Filtamos los grupos por asignatura ,docente y periodo
        $grupos=Grupo::where('clave_asignatura',$asignatura->clave)
                       ->where('id_docente',$docente->rfc)
                       ->where('clave_periodo',$periodo->clave)
                       ->get();
        
        //Retornamos los grupos con datos extras como el periodo y docente
        return redirect()->route('docentes.eliminacion_asignacion')->with([
            'grupos' => $grupos,
            'asignatura' => $asignatura,
            'docente' => $docente,
            'periodo' => $periodo
        ]);
    }
    


    //Este es el metodo post que desasigna la asignatura
    public function eliminar_asignacion(Request $request){
            //Guardamos las rquests
            $grupos=$request->input('grupos');
            $docente=Docente::find($request->input('rfc'));//Buscamos si existe el docente
            $clave_periodo=$request->input('periodo');

            //Validamos que se seleccionen los grupos si no retorna error
            if(!$grupos){
                return redirect()->route('docentes.eliminacion_asignacion')->with('error','No se selecciono ningun grupo');
            }
            //Vamos interar sobre los grupos y vamos ir asignando a cada grupo null en docente eso significa que se desasigno correctamente
            foreach($grupos as $clave_grupo=>$datos_grupo){

                DB::table('grupo')
                ->where('id_docente', $docente->rfc)
                ->Where('clave_asignatura', $datos_grupo['asignatura'])
                ->where('clave_grupo',$clave_grupo)              
                ->where('clave_periodo',$clave_periodo)              
                ->update(['id_docente'=>null]);
             
            }
            return redirect()->route('docentes.index')->with('success','Asignatura removida del docente correctamente');
    }

}
