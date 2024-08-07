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
        $asignaturas=Asignatura::all();
        return view('asignatura.index',compact('asignaturas'));
    }

    public function create(){
        $grupos=Grupo::all();
        $docentes=Docente::all();


        return view('asignatura.create',compact('grupos','docentes'));

    }   


    public function store(Request $request)
{
    $nombre = $request->input('nombre');
    $clave = $request->input('clave');

    $request->validate([
        'nombre' => 'required|unique:asignatura,nombre',
    ], [
        'nombre.unique' => 'La asignatura ya existe.'
    ]);

    $asignatura=Asignatura::find($clave);
    
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
        
        $asignatura=Asignatura::find($id);

        return view('asignatura.editar',compact('asignatura'));


    }


    public function update(Request $request , $id){
        $request->validate([
            'nombre' => 'required',
        ]);
        
        $nombre=$request->input('nombre');
        $asignatura=Asignatura::find($id);
        $asignatura->nombre=$nombre;

        $asignatura->save();

        return redirect()->route('asignatura.index')->with('success', 'La asignatura ha sido actualizada exitosamente.');        

    }


    // AsignaturaController.php

public function destroy($id)
{
    $asignatura = Asignatura::findOrFail($id);

    $asignatura->delete();

    return redirect()->route('asignatura.index')->with('success', 'Asignatura eliminada exitosamente.');
}



  

}
