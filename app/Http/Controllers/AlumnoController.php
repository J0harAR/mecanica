<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Alumno;
use  App\Models\Persona;
use  App\Models\Docente;
use  App\Models\Grupo;


class AlumnoController extends Controller
{

        public function index()
        {
            $alumnos = Alumno::with('persona', 'grupos')->get();
            $grupos = Grupo::all();
          
           

           $alumnosPorGrupo = [];
            foreach ($grupos as $grupo) {
               $alumnosPorGrupo[$grupo->clave_grupo] = $alumnos->filter(function ($alumno) use ($grupo) {
            
                return $alumno->grupos->contains('clave_grupo', $grupo->clave_grupo);
               });
            }
         

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


           $grupos=$request->input('grupos',[]);
            foreach ($grupos as $grupo) {

                $alumno->grupos()->attach($alumno->no_control,['clave_grupo'=>$grupo]);
            }

    
        
            return redirect()->route('alumnos.index');


        }



        public function update(Request $request,$id){
           
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

            $alumno=Alumno::find($no_control);

            if($alumno){
               
                $persona=Persona::find($alumno->persona->curp);
                $alumno->no_control=$no_control;
                $alumno->curp=$persona->curp;

                $persona->curp=$curp;
                $persona->nombre=$nombre;
                $persona->apellido_p=$apellido_p;
                $persona->apellido_m=$apellido_m;
                
                $alumno->save();
                $persona->save();  

                $grupos = $request->input('grupos', []);
                $grupos_alumno = $alumno->grupos->pluck('clave_grupo')->toArray();

              
                foreach ($grupos as $grupo) {
                    if (!in_array($grupo, $grupos_alumno)) {
                        $alumno->grupos()->attach($grupo, ['clave_grupo' => $grupo]);
                    }
                }

            }
            
            return redirect()->route('alumnos.index');

        }

        public function destroy($id){
            $alumno=Alumno::find($id);
            $alumno->delete();

            return redirect()->route('alumnos.index');

        }





}
