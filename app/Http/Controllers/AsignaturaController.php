<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Asignatura;
use  App\Models\Grupo;
use  App\Models\Docente;
class AsignaturaController extends Controller
{
    function _construct()
    {
        $this->middleware('permission:ver-asignaturas', ['only' => ['index']]);
        $this->middleware('permission:crear-asignatura', ['only' => ['create','store']]);
        $this->middleware('permission:editar-asignatura', ['only' => ['edit','update']]);
        $this->middleware('permission:borrar-asignatura', ['only' => ['destroy']]);
    }


    public function index(){
       
        $asignaturas=Asignatura::all(); //Se retornan todas las asignaturas
        return view('asignatura.index',compact('asignaturas'));
    }

    public function create(){
        //Se retornan los grupos y docentes para crear una asignatura
        $grupos=Grupo::all();
        $docentes=Docente::all();


        return view('asignatura.create',compact('grupos','docentes'));

    }   


    public function store(Request $request)
{
    //Guardamos los request
    $nombre = $request->input('nombre');
    $clave = $request->input('clave');
    //Validamos que el nombre de la asignatura sea unico
    $request->validate([
        'nombre' => 'required|unique:asignatura,nombre',
    ], [
        'nombre.unique' => 'La asignatura ya existe.'//Retornamos error si es que no es unico
    ]);
    //Buscamos la asignatura por clave
    $asignatura=Asignatura::find($clave);
    //Si la asignatura existe retornamos error ya que esta duplicada la clave
    if($asignatura){
        return redirect()->route('asignatura.index')->with('error', 'Clave de la asignatura duplicada');
    }

    // Crear nueva asignatura
    $asignatura = new Asignatura;
    $asignatura->clave = $clave;
    $asignatura->nombre = $nombre;
    $asignatura->save();
    return redirect()->route('asignatura.index')->with('success', 'La asignatura ha sido registrada exitositosamente.');

}

    public function edit($id){
        //Para editar buscamos la asignatura que coincida con el $id
        $asignatura=Asignatura::find($id);

        return view('asignatura.editar',compact('asignatura'));


    }


    public function update(Request $request , $id){
        //Validamos que no se repita el nombre de la asignatura
        $request->validate([
            'nombre' => 'required',
        ]);
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
