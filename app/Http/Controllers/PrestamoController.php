<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Docente;
use App\Models\Herramientas;
use App\Models\Articulo_inventariado;
class PrestamoController extends Controller
{


    function _construct()
    {
        $this->middleware('permission:ver-prestamos', ['only' => ['index']]);
        $this->middleware('permission:crear-prestamo', ['only' => ['store']]);
        $this->middleware('permission:editar-prestamo', ['only' => ['update']]);
        $this->middleware('permission:borrar-prestamo', ['only' => ['destroy']]);
        $this->middleware('permission:finalizar-prestamo', ['only' => ['finalizar']]);
    }

    public function index() {
        $prestamos = Docente::with(['persona', 'herramientas' => function ($query) {
            $query->withPivot(['id', 'fecha_prestamo', 'fecha_devolucion', 'estatus']);
        }])->get();
        
        // Filtrar solo las herramientas disponibles
        $herramientas = Herramientas::whereHas('Articulo_inventariados', function($query) {
            $query->where('estatus', 'Disponible');
        })->with('Articulo_inventariados.Catalogo_articulos')->get();
    
        $docentes = Docente::with('persona')->get();
    
        return view('prestamos.index', compact('prestamos', 'herramientas', 'docentes'));
    }
    
    
    

    public function store(Request $request)
{
    $request->validate([
        'rfc' => 'required',
        'herramienta' => 'required',
        'fecha_prestamo' => 'required|date',
        'fecha_devolucion' => 'required|date|after_or_equal:fecha_prestamo',
    ], [
        'rfc.required' => 'Debe seleccionar un docente.',
        'herramienta.required' => 'Debe seleccionar una herramienta.',
        'fecha_prestamo.required' => 'Debe seleccionar una fecha de préstamo.',
        'fecha_devolucion.required' => 'Debe seleccionar una fecha de devolución.',
        'fecha_devolucion.after_or_equal' => 'La fecha de devolución debe ser igual o posterior a la fecha de préstamo.',
    ]);

    $id_docente = $request->input('rfc');
    $id_herramienta = $request->input('herramienta');
    $fecha_prestamo = $request->input('fecha_prestamo');
    $fecha_devolucion = $request->input('fecha_devolucion');

    $docente = Docente::find($id_docente);
    if (!$docente) {
        return redirect()->route('prestamos.index')->withErrors(['docente_no_encontrado' => 'El docente no encontrado.'])->withInput();
    }

    $herramienta = Herramientas::find($id_herramienta);
    if ($herramienta->Articulo_inventariados->estatus != "Disponible") {
        return redirect()->route('prestamos.index')->withErrors(['herramienta_no_disponible' => 'La herramienta no está disponible.'])->withInput();
    }

    $herramienta->Articulo_inventariados->estatus = "No disponible";
    $herramienta->Articulo_inventariados->save();

    $docente->herramientas()->attach($id_herramienta, [
        'fecha_prestamo' => $fecha_prestamo,
        'fecha_devolucion' => $fecha_devolucion,
        'estatus' => "Pendiente"
    ]);

    return redirect()->route('prestamos.index')->with('success', 'Préstamo registrado correctamente.');
}




    public function update(Request $request ,$id){
      
        $id_docente=$request->input('rfc');
    
        $docente=Docente::find($id_docente);

        $prestamo = DB::table('prestamo')->where('id', $id)->first();

        DB::table('prestamo')
        ->where('id', $id)
        ->update(['fecha_devolucion' => $request->input('fecha_devolucion')]);
     
        return redirect()->route('prestamos.index')->with('success', 'Fecha de devolución actualizada correctamente.');

    }

    public function destroy($id){
        $prestamo = DB::table('prestamo')->where('id', $id)->first();

        $herramienta=Articulo_inventariado::find($prestamo->id_herramientas);
        
        $herramienta->estatus="Disponible";
        $herramienta->save();

        DB::table('prestamo')->where('id', $id)->delete();

        return redirect()->route('prestamos.index')->with('success', 'Préstamo eliminado correctamente.');

    }

    public function finalizar($id){

        $prestamo = DB::table('prestamo')->where('id', $id)->first();


        DB::table('prestamo')
        ->where('id', $id)
        ->update(['estatus' => "Finalizado"]);

       $herarmienta_prestada=Articulo_inventariado::find($prestamo->id_herramientas);
       $herarmienta_prestada->estatus="Disponible";
       $herarmienta_prestada->save();
     

        return redirect()->route('prestamos.index')->with('success', 'Préstamo finalizado correctamente.');
    }



}
