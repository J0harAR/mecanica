<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Docente;
use App\Models\Herramientas;
use App\Models\Articulo_inventariado;
use App\Models\Prestamo;
use App\Models\Periodo;
use Illuminate\Support\Facades\Auth;
class PrestamoController extends Controller
{


    function __construct()
    {
        $this->middleware('permission:ver-prestamos', ['only' => ['index']]);
        $this->middleware('permission:crear-prestamo', ['only' => ['store']]);
        $this->middleware('permission:editar-prestamo', ['only' => ['update']]);
        $this->middleware('permission:finalizar-prestamo', ['only' => ['finalizar']]);  
        $this->middleware('auth');//Aqui se valida que el usuario este autentica para todo el controlador
    }

    public function index() {
        //Vamos a retornar los prestamos con la relacion persona y las herramientas 
        $prestamos =Prestamo::all();
        $periodos=Periodo::all();
        // Filtrar solo las herramientas disponibles
        $herramientas=Articulo_inventariado::where('tipo','Herramientas')
        ->where('estatus','Disponible')
        ->get();

        $docentes = Docente::with('persona')->get();//Retornamos ahora todos los docentes para el modal
    
        return view('prestamos.index', compact('prestamos', 'herramientas', 'docentes','periodos'));
    }
    
    
    

    public function store(Request $request)
{
    //Validacion del modelo

    $validatedData = $request->validate(Prestamo::$createRules,Prestamo::messages());
    //Guardamos las requests
    $id_docente = $request->input('rfc');
    $id_herramienta = $request->input('herramienta');
    $fecha_prestamo = $request->input('fecha_prestamo');
    $fecha_devolucion = $request->input('fecha_devolucion');

    //Buscamos tanto el docente y la herramienta que se va prestar
    $docente = Docente::find($id_docente);
    $herramienta = Articulo_inventariado::find($id_herramienta);
 
    //Cambiamos el estatus de la herramienta
    $herramienta->estatus = "No disponible";
    $herramienta->save();

    //Creamos el prestamo 
    Prestamo::create([
        'id_docente'=>$docente->rfc,
        'id_herramientas'=>$herramienta->id_inventario,
        'estatus' => "Pendiente",
        'fecha_prestamo' => $fecha_prestamo,
        'fecha_devolucion' => $fecha_devolucion,
    ]);

    return redirect()->route('prestamos.index')->with('success', 'Préstamo registrado correctamente.');
}


    public function update(Request $request ,$id){

        $validatedData = $request->validate(Prestamo::$updateRules,Prestamo::messages());
        //Guardamos el id del docente
        $id_docente=$request->input('rfc');
        //Buscamos el docente que se le actualizara el prestamo
        $docente=Docente::find($id_docente);
        //Buscamos el prestamo por ID
        $prestamo=Prestamo::find($id);
        //Actualizamos el prestamo con la nueva fecha de devolucion
        $prestamo->fecha_devolucion=$request->input('fecha_devolucion');
        $prestamo->save();
        return redirect()->route('prestamos.index')->with('success', 'Fecha de devolución actualizada correctamente.');

    }

 
    public function finalizar($id){
        //Buscamos el prestamo para poder finalizarlo
        $prestamo=Prestamo::find($id);
        //Cambiamos el estatus del prestamo a finalizado
        $prestamo->estatus='Finalizado';
        $prestamo->save();

        //Cambiamos el estatus de la herramienta a disponible
       $herarmienta_prestada=Articulo_inventariado::find($prestamo->id_herramientas);
       $herarmienta_prestada->estatus="Disponible";
       $herarmienta_prestada->save();


        return redirect()->route('prestamos.index')->with('success', 'Préstamo finalizado correctamente.');
    }



}
