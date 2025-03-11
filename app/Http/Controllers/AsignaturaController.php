<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Asignatura;
use  App\Models\Grupo;
use  App\Models\Docente;
use Illuminate\Support\Facades\Auth;
class AsignaturaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-asignaturas', ['only' => ['index']]);
        $this->middleware('permission:crear-asignatura', ['only' => ['store']]);
        $this->middleware('permission:editar-asignatura', ['only' => ['update']]);
        $this->middleware('permission:borrar-asignatura', ['only' => ['destroy']]);
        $this->middleware('auth');//Aqui se valida que el usuario este autentica para todo el controlador

    }


    public function index(){
       
        $asignaturas=Asignatura::all(); //Se retornan todas las asignaturas
        return view('asignatura.index',compact('asignaturas'));
    }


    public function store(Request $request){

        //Validaciones desde el modelo
        $validatedData = $request->validate(Asignatura::$createRules,Asignatura::messages());
        //Guardamos los request
        $nombre = $request->input('nombre');
        $clave = $request->input('clave');
        
        //Buscamos la asignatura por clave
        $asignatura=Asignatura::find($clave);
    
        // Crear nueva asignatura
        $asignatura = new Asignatura;
        $asignatura->clave = $clave;
        $asignatura->nombre = $nombre;
        $asignatura->save();
        return redirect()->route('asignatura.index')->with('success', 'La asignatura ha sido registrada exitositosamente.');

    }

    public function update(Request $request , $id){
         //Validaciones desde el modelo
        $validatedData = $request->validate(Asignatura::$updateRules,Asignatura::messages());
        
        //Guaramos los requests
        $nombre=$request->input('nombre');
        $asignatura=Asignatura::find($id);//Buscamos la asignatura con el id es decir la clave
        $asignatura->nombre=$nombre;

        $asignatura->save();

        return redirect()->route('asignatura.index')->with('success', 'La asignatura ha sido actualizada exitosamente.');        

    }


    // AsignaturaController.php

public function destroy($id)
{
    //Buscamos la asignatura con el findorFail y se elimina
    $asignatura = Asignatura::findOrFail($id);

    $asignatura->delete();

    return redirect()->route('asignatura.index')->with('success', 'Asignatura eliminada exitosamente.');
}



  

}
