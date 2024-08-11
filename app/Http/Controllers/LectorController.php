<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maquinaria;
use App\Models\Catalogo_articulo;
use App\Models\Lectura;
class LectorController extends Controller
{

    function _construct()
    {
        $this->middleware('permission:ver-lecturas', ['only' => ['index']]);
        $this->middleware('permission:crear-lectura', ['only' => ['store']]);
    }


   
    public function index(){
        $maquinarias=Maquinaria::all();
        $lecturas = Lectura::with('insumos')->get();
        return view('lector_niveles.index',compact('maquinarias','lecturas'));
    }


    public function store(Request $request){
        
        $usuario = auth()->user(); // Obtenemos la instancia del usuario logueado

        if(!$request->input('maquina')){
            return redirect()->route('lector.index')->with('error', 'Seleccione una maquinaria');
        }

        $lectura = new Lectura;
        $lectura->fecha = $request->input('fecha');
        $lectura->id_usuario = $usuario->id;
        $lectura->id_maquinaria = $request->input('maquina');
        $lectura->observaciones = $request->input('observaciones');



        $insumos = collect($request->input('insumos', []))
            ->map(function($insumo) {
                return ['cantidad' => $insumo];
            });

        $maquinaria = Maquinaria::find($request->input('maquina'));

    
        $excede_capacidad = false;

        $insumo_data = []; 

        foreach ($insumos as $key => $insumo) {
            $insumo_temp = Catalogo_articulo::find($key);
            $insumo_maquinaria = $maquinaria->insumos()->where('insumo_id', $insumo_temp->id_articulo)->first();

            if ($insumo_maquinaria) {
                $cantidad_anterior = $insumo_maquinaria->pivot->cantidad_actual;
                $capacidad_insumo = $insumo_maquinaria->pivot->capacidad;
                $cantidad_nueva = $cantidad_anterior + $insumo['cantidad'];

              
                if ($cantidad_nueva > $capacidad_insumo) {
                    $excede_capacidad = true;
                    break;
                }

                // Almacenar datos necesarios para attach y sync
                $insumo_data[] = [
                    'insumo_temp' => $insumo_temp,
                    'cantidad' => $insumo['cantidad'],
                    'cantidad_anterior' => $cantidad_anterior,
                    'cantidad_nueva' => $cantidad_nueva
                ];
            }
        }

       
        if ($excede_capacidad) {
            return redirect()->route('lector.index')->with('error', 'Cantidad no válida, excedió la capacidad.');
        }

     
        $lectura->save();

        
        foreach ($insumo_data as $data) {
            
            $lectura->insumos()->attach($data['insumo_temp']->id_articulo, [
                'cantidad' => $data['cantidad'],
                'cantidad_anterior' => $data['cantidad_anterior'],
                'cantidad_nueva' => $data['cantidad_nueva'],
            ]);

            
            $maquinaria->insumos()->syncWithoutDetaching([
                $data['insumo_temp']->id_articulo => ['cantidad_actual' => $data['cantidad_nueva']]
            ]);
        }
            
        return redirect()->route('lector.index')->with('success', 'Lectura registrada correctamente');

    }

}
