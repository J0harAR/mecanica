<?php

namespace App\Http\Controllers;
use  App\Models\Docente;
use  App\Models\Persona;
use  App\Models\Alumno;
use  App\Models\Asignatura;
use  App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocenteController extends Controller
{
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


        return redirect()->route('docentes.index');
       
    }

    public function show($id){
        $docente=Docente::find($id);
        return view('docentes.show',compact('docente'));

    }   


    public function update(Request $request , $id){

       

    }

    
    public function destroy($id){
       

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
        $grupos=$asignatura->grupos; 
        return redirect()->route('docentes.asigna')->with(['grupos' => $grupos, 'docente' => $docente, 'periodo' => $periodo]);

    }


    public function asignar(Request $request){
          $clave_periodo=$request->input('clave_periodo');
          $grupos=$request->input('grupos');
        
            $docente=Docente::find($request->input('rfc_docente'));
            $periodo=Periodo::find($clave_periodo);
            
        
            foreach($grupos as $clave_grupo=>$datos_grupo){

                $registro_duplicado=DB::table('docente_grupo')                   
                        ->Where('clave_asignatura', $datos_grupo['asignatura'])
                        ->where('clave_grupo',$clave_grupo)
                        ->first();

                            if($registro_duplicado!=null){
                                return redirect()->route('docentes.asigna')->with('error','El grupo ya cuenta con docente');

                            }
                           
                $docente->grupos()->attach($docente->rfc,
                ['clave_grupo'=>$clave_grupo,'clave_asignatura'=>$datos_grupo['asignatura'],
                'clave_periodo'=>$periodo->clave]);
               
            }
         
            return redirect()->route('docentes.index');
    }

    public function eliminacion_asignacion(){
        $docentes=Docente::all();
        $asignaturas=Asignatura::all();
        $periodos=Periodo::all();
        return view('docentes.desasignar',compact('docentes','asignaturas','periodos'));
    }

    public function filtrar(Request $request ){

        $docente=Docente::find($request->input('rfc'));
        $asignatura=Asignatura::find($request->input('id_asignatura'));
        $periodo=Periodo::find($request->input('periodo'));

        $grupos=$docente->grupos()->where('clave_asignatura',$asignatura->clave)->get();


        return redirect()->route('docentes.eliminacion_asignacion')->with(['grupos' => $grupos,'asignatura'=>$asignatura,'docente'=>$docente,'periodo'=>$periodo]);
        
    }



    public function eliminar_asignacion(Request $request){
      
            $grupos=$request->input('grupos');
            $docente=Docente::find($request->input('rfc'));
            $clave_periodo=$request->input('periodo');
            foreach($grupos as $clave_grupo=>$datos_grupo){

                DB::table('docente_grupo')
                ->where('id_docente', $docente->rfc)
                ->Where('clave_asignatura', $datos_grupo['asignatura'])
                ->where('clave_grupo',$clave_grupo)              
                ->where('clave_periodo',$clave_periodo)              
                ->delete();
               
            }
            return redirect()->route('docentes.index');
    }

}
