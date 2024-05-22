<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Docente;
use App\Models\Herramientas;
use App\Models\Articulo_inventariado;
class PrestamoController extends Controller
{
   
    public function index(){
        $prestamos = Docente::with(['persona', 'herramientas' => function ($query) {
            $query->withPivot(['id','fecha_prestamo', 'fecha_devolucion', 'estatus']);
        }])->get();
        
        $herramientas = Herramientas::with('Articulo_inventariados.Catalogo_articulos')->get();

        return view('prestamos.index',compact('prestamos','herramientas'));

    }

    public function store(Request $request){
            $id_docente=$request->input('rfc');
            $id_herramienta=$request->input('herramienta');
            $fecha_prestamo=$request->input('fecha_prestamo');
            $fecha_devolucion=$request->input('fecha_devolucion');

            $docente=Docente::find($id_docente);
            if($docente==null){
                return redirect()->route('prestamos.index')->with('docente_no_encontrado', 'El docente no encontrado');
            }
        
            $herramienta=Herramientas::find($id_herramienta);

            if($herramienta->Articulo_inventariados->estatus != "Disponible"){
                return redirect()->route('prestamos.index')->with('herramienta_no_disponible', 'La herramienta '.$herramienta->id_herramientas.' / '.$herramienta->Articulo_inventariados->Catalogo_articulos->nombre.' no se encuentra disponible');        

            }

            $herramienta=Articulo_inventariado::find($id_herramienta);
            if($herramienta==null){
                return redirect()->route('prestamos.index')->with('herramienta_no_encontrada', 'No se encontro la herramienta ');

            }


            $herramienta->estatus="No disponible";
            $herramienta->save();
            $docente->herramientas()->attach($id_herramienta,['fecha_prestamo'=>$fecha_prestamo,'fecha_devolucion'=>$fecha_devolucion,'estatus'=>"Pendiente"]);
           
            return redirect()->route('prestamos.index')->with('success', 'Prestamo registrado correctamente');

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

        return redirect()->route('prestamos.index')->with('success', 'Préstamo finalizado correctamente.');
    }



}
