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
        $this->middleware('permission:borrar-periodo', ['only' => ['destroy']]);
    }
    
   
        public function index(){
            $periodos=Periodo::all();

            return view('periodos.index',compact('periodos'));
        }

        public function store(Request $request){

            $this->validate($request, [
                'periodo' => 'required',
                'fecha_inicio' => 'required',
                'fecha_final' => 'required',
            ]);
            $duplicado=Periodo::find($request->input('periodo'));
            
            if($duplicado){
                return redirect()->route('periodos.index')->with('error','Periodo con clave duplicada');
            }

            $periodo=new Periodo;
            $periodo->clave=$request->input('periodo');
            $periodo->fecha_inicio=$request->input('fecha_inicio');
            $periodo->fecha_final=$request->input('fecha_final');
            $periodo->save();


            return redirect()->route('periodos.index')->with('success','Periodo agregado correctamente');

        }

        public function update(Request $request ,$id){
            $periodo=Periodo::find($id);
            if($periodo){
                $periodo->fecha_inicio=$request->input('fecha_inicio');
                $periodo->fecha_final=$request->input('fecha_final');
                $periodo->save();
                return redirect()->route('periodos.index')->with('success','Periodo actualizado correctamente');
            }

        }

        public function destroy($id){
            

            $periodo=Periodo::find($id);      
            $periodo->delete();

            return redirect()->route('periodos.index')->with('success','Periodo eliminado correctamente');
        }



}
