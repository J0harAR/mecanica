<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Periodo;
use Illuminate\Support\Facades\Auth;
class PeriodoController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:ver-periodos', ['only' => ['index']]);
        $this->middleware('permission:crear-periodo', ['only' => ['store']]);
        $this->middleware('permission:editar-periodo', ['only' => ['update']]);
        $this->middleware('permission:borrar-periodo', ['only' => ['destroy']]);
        $this->middleware('auth');//Aqui se valida que el usuario este autentica para todo el controlador
    }
    
   
        public function index(){
            //Retornamos todos los periodos
            $periodos=Periodo::all();
            return view('periodos.index',compact('periodos'));
        }

        public function store(Request $request){
            //Validaciones del modelo

            $validatedData = $request->validate(Periodo::$createRules,Periodo::messages());

            //Creamos el periodo
            $periodo=new Periodo;
            $periodo->clave=$request->input('periodo');
            $periodo->fecha_inicio=$request->input('fecha_inicio');
            $periodo->fecha_final=$request->input('fecha_final');
            $periodo->save();//Guardamos el periodo


            return redirect()->route('periodos.index')->with('success','Periodo agregado correctamente');

        }

        public function update(Request $request ,$id){
            //Validacioens del modelo
            
            $validatedData = $request->validate(Periodo::updateRules($id),Periodo::messages());


            $periodo=Periodo::find($id);
            //Si se encuentra el periodo se actualiza con las requests
            if($periodo){
                $periodo->fecha_inicio=$request->input('fecha_inicio');
                $periodo->fecha_final=$request->input('fecha_final');
                $periodo->save();
                return redirect()->route('periodos.index')->with('success','Periodo actualizado correctamente');
            }

        }

        public function destroy($id){
            
            //Buscamos el peridoo que vamos a borrar
            $periodo=Periodo::find($id);      
            $periodo->delete();//Borramos el periodo

            return redirect()->route('periodos.index')->with('success','Periodo eliminado correctamente');
        }



}
