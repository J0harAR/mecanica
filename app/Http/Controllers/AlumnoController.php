<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Alumno;
use  App\Models\Persona;


class AlumnoController extends Controller
{
   
        public function index(){
            $alumnos=Alumno::all();

            return view('alumnos.index',compact('alumnos'));
        }


        public function store(Request $request){
            $curp = $request->input('curp');
            $nombre=$request->input('nombre');   
            $apellido_p=$request->input('apellido_p');   
            $apellido_m=$request->input('apellido_m');   
            $no_control=$request->input('no_control');



            $persona=new Persona;
            $persona->curp=$curp;
            $persona->nombre=$nombre;
            $persona->apellido_p=$apellido_p;
            $persona->apellido_m=$apellido_m;
           
            
            $alumno=new Alumno;
            $alumno->no_control=$no_control;
            $alumno->curp=$persona->curp;


            $persona->save();
            $alumno->save();
            return redirect()->route('alumnos.index');
        }


}
