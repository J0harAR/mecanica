<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Periodo;
class PeriodoController extends Controller
{

    function _construct()
    {
        $this->middleware('permission:ver-periodos', ['only' => ['index']]);
        $this->middleware('permission:crear-periodo', ['only' => ['store']]);
        $this->middleware('permission:editar-periodo', ['only' => ['update']]);
        $this->middleware('permission:borrar-periodo', ['only' => ['destroy']]);
    }
    
   
        public function index(){
            //Retornamos todos los periodos
            $periodos=Periodo::all();
            return view('periodos.index',compact('periodos'));
        }

        public function store(Request $request){
            //Validamos que no se ingresen datos en blanco
            $this->validate($request, [
                'periodo' => 'required',
                'fecha_inicio' => 'required',
                'fecha_final' => 'required',
            ]);
            //Buscamos si hay un periodo con la clave del periodo
            $duplicado=Periodo::find($request->input('periodo'));
            
            //Si se encuentra se retornara un error
            if($duplicado){
                return redirect()->route('periodos.index')->with('error','Periodo con clave duplicada');
            }
            //Creamos el periodo
            $periodo=new Periodo;
            $periodo->clave=$request->input('periodo');
            $periodo->fecha_inicio=$request->input('fecha_inicio');
            $periodo->fecha_final=$request->input('fecha_final');
            $periodo->save();//Guaramos el periodo


            return redirect()->route('periodos.index')->with('success','Periodo agregado correctamente');

        }

        public function update(Request $request ,$id){
            //Buscamos el periodo que vamos actualizar
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
