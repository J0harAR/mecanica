<?php

namespace App\Http\Controllers;
use  App\Models\Docente;
use  App\Models\Persona;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function index(){
      
        return view('docentes.index');
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

    public function update(Request $request , $id){

       

    }

    
    public function destroy($id){
       

    }

}
