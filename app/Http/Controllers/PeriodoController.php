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
            $periodo=new Periodo;
            $periodo->clave=$request->input('periodo');
            $periodo->save();

            return redirect()->route('periodos.index');

        }

        public function destroy($id){
            

            $periodo=Periodo::find($id);      
            $periodo->delete();

            return redirect()->route('periodos.index');
        }



}
