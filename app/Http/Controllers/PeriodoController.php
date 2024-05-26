<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Periodo;
class PeriodoController extends Controller
{
   
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
