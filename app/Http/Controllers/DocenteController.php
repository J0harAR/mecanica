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

class DocenteController extends Controller
{

    function _construct()
    {
        $this->middleware('permission:ver-docentes', ['only' => ['index']]);
        $this->middleware('permission:crear-docente', ['only' => ['create','store']]);
        $this->middleware('permission:ver-docente', ['only' => ['show']]);
        $this->middleware('permission:editar-docente', ['only' => ['update']]);
        $this->middleware('permission:borrar-docente', ['only' => ['destroy']]);
        $this->middleware('permission:asignar-grupos-docente', ['only' => ['asigna','filtrar_asignaturas','asignar']]);
        $this->middleware('permission:eliminar-grupos-docente', ['only' => ['eliminacion_asignacion','filtrar','eliminar_asignacion']]);
    }

    public function index(){
        $docentes=Docente::all();
        return view('docentes.index',compact('docentes'));
    }

    public function create(){


        return view('docentes.create');
    }   


    public function store(Request $request){
     
        $curp = $request->input('curp');
        $nombre=$request->input('nombre');   
        $apellido_p=$request->input('apellido_p');   
        $apellido_m=$request->input('apellido_m');   
        $rfc=$request->input('rfc');   
        $area=$request->input('area');   

        $telefono=$request->input('telefono');
        
        $persona_existente=Alumno::where('curp',$curp)->first();
        //Validacion de inclusion
        if($persona_existente){

            return redirect()->route('docentes.index')->with('error','Curp le pertenece a un alumno');
        }

        //validacion rfc 
        $docente=Docente::find($rfc);
        if($docente){
            return redirect()->route('docentes.index')->with('error','RFC duplicado');
        }

      
        if($request->has('foto')){

            $file=$request->file('foto');
            $extension=$file->getClientOriginalExtension();

           
            $filename=time().'.'.$extension;
            $path='uploads/docentes/';

            $file->move($path,$filename);

        }

        $persona=Persona::find($curp);
        
        if($persona){
            Docente::create([
                'rfc'=>$rfc,
                'curp'=>$persona->curp,
                'area'=>$area,
                'foto'=>$path.$filename,
                'telefono'=>$telefono,
    
            ]);
        }else{

            Persona::create([
                'curp'=>$curp,
                'nombre'=>$nombre,
                'apellido_p'=>$apellido_p,
                'apellido_m'=>$apellido_m,
                
            ]);
            $persona=Persona::find($curp);
            Docente::create([
                'rfc'=>$rfc,
                'curp'=>$persona->curp,
                'area'=>$area,
                'foto'=>$path.$filename,
                'telefono'=>$telefono,
    
            ]);
            
        }

 
        return redirect()->route('docentes.index')->with('success','Docente agregado correctamente');
       
    }

    public function show($id){
        $docente=Docente::find($id);

        $asignaturas=Grupo::where('id_docente',$docente->rfc)->get();
        return view('docentes.show',compact('docente','asignaturas'));

    }   

    public function update(Request $request ,$id){

        $nombre=$request->input('nombre');
        $apellido_p=$request->input('apellido_p');
        $apellido_m=$request->input('apellido_m');
        $curp=$request->input('curp');
        $area=$request->input('area');
        $telefono=$request->input('telefono');
        
    
    
        $persona_existente=Alumno::where('curp',$curp)->first();
        
        //Validacion de inclusion
        if($persona_existente){

            return redirect()->route('docentes.index')->with('error','Curp le pertenece a un alumno');
        }

        $docente=Docente::find($id);
        
        if($docente){
            $persona=Persona::find($docente->persona->curp);
          
            $docente->area=$area;

            if($request->has('foto')){
                       
                $file=$request->file('foto');
                $extension=$file->getClientOriginalExtension();
                $filename=time().'.'.$extension;
                $path='uploads/docentes/';
                $file->move($path,$filename);
                $docente->foto=$path.$filename;
            }
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
        $docente = Docente::findOrFail($id);
        $docente->delete();

        return redirect()->route('docentes.index')->with('success', 'Docente eliminado correctamente');
    } catch (QueryException $e) {
        if ($e->getCode() == 23000) {
            return redirect()->route('docentes.index')->with('error', 'No es posible eliminar el docente porque tiene prácticas asociadas');
        }
       
    }
}

    public function asigna(){
        $periodos=Periodo::all();
        $docentes=Docente::all();
        $asignaturas=Asignatura::all();
        return view('docentes.asignar',compact('docentes','asignaturas','periodos'));
    }


    public function filtrar_asignaturas(Request $request){
       
        $clave_asignatura=$request->input('asignatura');
        $id_docente=$request->input('docente');
        $periodo=$request->input('periodo');
        
        if (empty($clave_asignatura) || empty($id_docente) || empty($periodo)) {
            return redirect()->route('docentes.asigna')->with('error', 'Todos los campos son requeridos.');             
        }




        $docente=Docente::find($id_docente);
        $asignatura=Asignatura::find($clave_asignatura);
        $periodo=Periodo::find($periodo);
        $grupos=Grupo::where('clave_asignatura',$asignatura->clave)->get();
        
       
        return redirect()->route('docentes.asigna')->with(['grupos' => $grupos, 'docente' => $docente, 'periodo' => $periodo]);

    }


    public function asignar(Request $request){
            $clave_periodo=$request->input('clave_periodo');
            $grupos=$request->input('grupos');
        
            $docente=Docente::find($request->input('rfc_docente'));
            $periodo=Periodo::find($clave_periodo);
            
           
        
            foreach($grupos as $clave_grupo=>$datos_grupo){
                //Checar si tiene docente asignado el grupo
                $grupo_disponible=DB::table('grupo')                   
                        ->Where('clave_asignatura', $datos_grupo['asignatura'])
                        ->where('clave_grupo',$clave_grupo)
                        ->where('id_docente',null)
                        ->where('clave_periodo',null)
                        ->first();

           
                    if($grupo_disponible){
                        $grupo=Grupo::Where('clave_asignatura', $datos_grupo['asignatura'])
                         ->where('clave_grupo',$clave_grupo)
                        ->update(['id_docente'=>$docente->rfc,'clave_periodo'=>$periodo->clave]);  
                    }else{
                        return redirect()->route('docentes.asigna')->with('error','El grupo ya cuenta con docente');
                    }
                   
           
            }
         
          return redirect()->route('docentes.index')->with('success','Asignatura añadida a docente correctamente');
    }

    public function eliminacion_asignacion(){
        $docentes=Docente::all();
        $asignaturas=Asignatura::all();
        $periodos=Periodo::all();
        return view('docentes.desasignar',compact('docentes','asignaturas','periodos'));
    }

    public function filtrar(Request $request) {

        $docente=Docente::find($request->input('rfc'));
        $asignatura=Asignatura::find($request->input('id_asignatura'));
        $periodo=Periodo::find($request->input('periodo'));
    
        if (!$docente || !$asignatura || !$periodo) {
            return redirect()->route('docentes.eliminacion_asignacion')->with('error', 'Todos los campos son requeridos');
        }
    
        $grupos=Grupo::where('clave_asignatura',$asignatura->clave)
                       ->where('id_docente',$docente->rfc)
                       ->where('clave_periodo',$periodo->clave)
                       ->get();
    
        return redirect()->route('docentes.eliminacion_asignacion')->with([
            'grupos' => $grupos,
            'asignatura' => $asignatura,
            'docente' => $docente,
            'periodo' => $periodo
        ]);
    }
    



    public function eliminar_asignacion(Request $request){
      
            $grupos=$request->input('grupos');
            $docente=Docente::find($request->input('rfc'));
            $clave_periodo=$request->input('periodo');

            if(!$grupos){
                return redirect()->route('docentes.eliminacion_asignacion')->with('error','No se selecciono ningun grupo');
            }
            foreach($grupos as $clave_grupo=>$datos_grupo){

                DB::table('grupo')
                ->where('id_docente', $docente->rfc)
                ->Where('clave_asignatura', $datos_grupo['asignatura'])
                ->where('clave_grupo',$clave_grupo)              
                ->where('clave_periodo',$clave_periodo)              
                ->update(['id_docente'=>null,'clave_periodo'=>null]);
             
            }
            return redirect()->route('docentes.index')->with('success','Asignatura removida del docente correctamente');
    }

}
