<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Alumno;
use  App\Models\Persona;
use  App\Models\Docente;
use  App\Models\Grupo;


class AlumnoController extends Controller
{
   
        /*public function index(){
            $alumnos=Alumno::all();
            $grupos=Grupo::all();
            return view('alumnos.index',compact('alumnos','grupos'));
        }*/

        public function index()
        {
            $alumnos = Alumno::with('persona', 'grupos')->get();
        
            $grupos = Grupo::all();
        
            $alumnosPorGrupo = $alumnos->groupBy(function($alumno) {
                return $alumno->grupos->pluck('clave')->first();
            });
            return view('alumnos.index', compact('alumnosPorGrupo', 'grupos'));
        }


        public function store(Request $request){
            
        
            $validated = $request->validate([
                'curp' => 'required|unique:alumno|max:255',
                'no_control' => 'required|unique:alumno|max:255',
            ]);  


            $curp = $request->input('curp');
            $nombre=$request->input('nombre');   
            $apellido_p=$request->input('apellido_p');   
            $apellido_m=$request->input('apellido_m');   
            $no_control=$request->input('no_control');


            $persona_existente=Docente::where('curp',$curp)->first();

            //Validacion de inclusion
            if($persona_existente){
                return redirect()->route('alumnos.index')->with('error','Curp le pertenece a un docente');
            }


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

            
            $alumno=Alumno::find($no_control);
           
            $alumno->grupos()->sync($request->input('grupos',[]));

            return redirect()->route('alumnos.index');

        }






}
