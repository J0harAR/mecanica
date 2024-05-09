<?php

namespace App\Http\Controllers;

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
     

      
    }

    public function update(Request $request , $id){

       

    }

    
    public function destroy($id){
       

    }

}
