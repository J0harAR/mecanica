<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maquinaria;
use App\Models\Catalogo_articulo;
use App\Models\Lectura;
use Carbon\Carbon;
class LectorController extends Controller
{

    function _construct()
    {
        $this->middleware('permission:ver-lecturas', ['only' => ['index','obtenerComportamientoInsumos']]);
        $this->middleware('permission:crear-lectura', ['only' => ['store']]);
    }


   
    public function index(){
        //Vamos a retornar toodas las maquinarias y las lecturas para la tabla y el modal
        $maquinarias=Maquinaria::all();
        $lecturas = Lectura::with('insumos')->get();
        return view('lector_niveles.index',compact('maquinarias','lecturas'));
    }


    public function store(Request $request){
        $usuario = auth()->user(); // Obtenemos la instancia del usuario logueado

        //Reviamos que se seleccione una maquinaria si no retornara error
        if(!$request->input('maquina')){
            return redirect()->route('lector.index')->with('error', 'Seleccione una maquinaria');
        }
        //Reviamos que se ingrese una observacion si no retornara error
        if(!$request->input('observaciones')){
            return redirect()->route('lector.index')->with('error', 'No dejar en blanco la observación');
        }
        //Creamos la lectura 
        $lectura = new Lectura;
        $lectura->fecha = $request->input('fecha');
        $lectura->id_usuario = $usuario->id;//ASignaremos el usuario que realizo la lectura para llevar control
        $lectura->id_maquinaria = $request->input('maquina');
        $lectura->observaciones = $request->input('observaciones');


        //Asignamos una colleccion que llevara como key el id del insumo y cantidad
        $insumos = collect($request->input('insumos', []))
            ->map(function($insumo) {
                return ['cantidad' => $insumo];
            });

        //Buscamos la maquinaria que vamos a realizar la lectura
        $maquinaria = Maquinaria::find($request->input('maquina'));

        //Esta variable nos servira para checar si hay exceso de capacidad
        $excede_capacidad = false;

        $insumo_data = []; //Este array almacenara posteriormente los datos para el sync

        //Iteramos entre los insumos que se seleccionaron con su cantidad
        foreach ($insumos as $key => $insumo) {
            //Buscamos si ese insumo tem existe en el catalogo
            $insumo_temp = Catalogo_articulo::find($key);
            //Retornamos o guardamos los insumos de la maquinaria por cada insumo_temp
            $insumo_maquinaria = $maquinaria->insumos()->where('insumo_id', $insumo_temp->id_articulo)->first();

            //Si ese insumo esta dentro de los que compone la maquinaria se asigan las cantidades
            if ($insumo_maquinaria) {
                $cantidad_anterior = $insumo_maquinaria->pivot->cantidad_actual;//La cantidad anterior sera la cantidad actual del insumo de la maquina
                $capacidad_insumo = $insumo_maquinaria->pivot->capacidad;//La capacidad sera la capacidad que se establecio al crearla
                $cantidad_nueva = $insumo['cantidad'];//La cantida nueva sera la que se ingreso en el form

                //Revisamos que la cantida neuva no exceda de la capacidad el insumo
                if ($cantidad_nueva > $capacidad_insumo) {
                    $excede_capacidad = true;//Cambios a true si ocurre el exceso
                    break;
                }

                // Almacenar datos necesarios para attach y sync despues de calidad las cantidades
                $insumo_data[] = [
                    'insumo_temp' => $insumo_temp,
                    'cantidad' => $insumo['cantidad'],
                    'cantidad_anterior' => $cantidad_anterior,
                    'cantidad_nueva' => $cantidad_nueva
                ];
            }
        }

        //Revisamos si se excedio la capacidad si es asi retornara error
        if ($excede_capacidad) {
            return redirect()->route('lector.index')->with('error', 'Cantidad no válida, excedió la capacidad.');
        }
    
        //Primero se guarda la lectura para despues asignar el insumo
        $lectura->save();

        //Iteramos entre el insumo data que guardamos cuando comparamos las cantidades
        foreach ($insumo_data as $data) {
            //Empezamos la asignacion o attach a la lectura
            $lectura->insumos()->attach($data['insumo_temp']->id_articulo, [
                'cantidad' => $data['cantidad'],
                'cantidad_anterior' => $data['cantidad_anterior'],
                'cantidad_nueva' => $data['cantidad_nueva'],
            ]);

            //Actualizamos los datos de los insumos de la maquinaria con la cantidad nueva
            $maquinaria->insumos()->syncWithoutDetaching([
                $data['insumo_temp']->id_articulo => ['cantidad_actual' => $data['cantidad_nueva']]
            ]);
        }
            
        return redirect()->route('lector.index')->with('success', 'Lectura registrada correctamente');

    }

    public function obtenerComportamientoInsumos(Request $request){
        //Se guarda la maquina y un rango de fechas para generar una grafica
        $maquinariaId = $request->input('maquinaria_id');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
    
        //Filtramos la lecturas y mostraremos los insumos por dia entre el rango de fechas
        $data = Lectura::where('id_maquinaria', $maquinariaId)
        ->whereBetween('fecha', [Carbon::parse($fechaInicio), Carbon::parse($fechaFin)])
        ->with('insumos') // Incluye la relación insumos
        ->get();
        //Retornamos la lectura con una relacon de insumos en json
        return response()->json($data);



        
    }

}
