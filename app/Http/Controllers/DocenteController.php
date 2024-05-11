<?php

namespace App\Http\Controllers;
use  App\Models\Docente;
use  App\Models\Persona;
use  App\Models\Asignatura;
use  App\Models\Periodo;
use Illuminate\Http\Request;

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
            'rfc'=>$nombre,
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


        $docente=Docente::find($id_docente);
        $asignatura=Asignatura::find($clave_asignatura);
        $periodo=Periodo::find($periodo);
        $grupos=$asignatura->grupos;
        

        
        return redirect()->route('docentes.asigna')->with(['grupos' => $grupos, 'docente' => $docente, 'periodo' => $periodo]);

    }


    public function asignar(Request $request){

    }

}
