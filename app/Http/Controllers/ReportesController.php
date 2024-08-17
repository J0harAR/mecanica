<?php

namespace App\Http\Controllers;
use App\Models\Catalogo_articulo;
use App\Models\Practica;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Docente;
use App\Models\Herramientas;
use App\Models\Insumos;
use App\Models\Periodo;
use App\Models\Maquinaria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ReportesController extends Controller
{
   
    function _construct()
    {
        $this->middleware('permission:generar_reporte_prestamo', ['only' => ['generar_reporte_prestamo']]);
        $this->middleware('permission:generar_reporte_inventario', ['only' => ['generar_reporte_inventario']]);
        $this->middleware('permission:generar_reporte_herramientas', ['only' => ['generar_reporte_herramientas']]);
        $this->middleware('permission:generar_reporte_maquinaria', ['only' => ['generar_reporte_maquinaria']]);
        $this->middleware('permission:generar_reporte_insumos', ['only' => ['generar_reporte_insumos']]);
        $this->middleware('permission:generar_reporte_practicas', ['only' => ['generar_reporte_practicas_completas']]);
    }


    public function generar_reporte_prestamo(Request $request){
        $prestamos = DB::table('prestamo')->get();
        

        $pdf = Pdf::loadView('reportes.prestamos',['prestamos'=>$prestamos]);
        return $pdf->stream();



    }

    public function generar_reporte_inventario(Request $request) {
        //2024-1,2024-2,2024-3
    
    
        $inventario = collect(); 
    
        $periodo=Periodo::find($request->input('periodo'));
       
        if($periodo){
          
            $inicioMes =Carbon::parse($periodo->fecha_inicio)->month;
            $finMes = Carbon::parse($periodo->fecha_final)->month;
            $año = explode("-", $periodo->clave);
            
            $inventario = Catalogo_articulo::whereYear('created_at', $año[0])
            ->whereMonth('created_at', '>=', $inicioMes)
            ->whereMonth('created_at', '<=', $finMes)
            ->get();

            $pdf = Pdf::loadView('reportes.inventario', ['inventario' => $inventario, 'periodo' => $periodo,'año'=>$año[0]]);
            return $pdf->stream();

           
        }
        return redirect()->route('inventario.index')->with('error', 'No se selecciono el periodo ');

       
    }


    public function generar_reporte_herramientas(Request $request){
      
        $herramientas = collect();
        $periodo=Periodo::find($request->input('periodo'));
        

        if($periodo){
           
            $inicioMes =Carbon::parse($periodo->fecha_inicio)->month;
            $finMes = Carbon::parse($periodo->fecha_final)->month;
            $año = explode("-", $periodo->clave);

            
            $herramientas = Herramientas::whereYear('created_at', $año[0])
            ->whereMonth('created_at', '>=', $inicioMes)
            ->whereMonth('created_at', '<=', $finMes)
            ->get();
            $pdf = Pdf::loadView('reportes.herramientas',['herramientas'=>$herramientas,'periodo'=>$periodo ,'año'=>$año[0]]);
            return $pdf->stream();
        }
    
        return redirect()->route('herramientas.index')->with('error', 'No se selecciono el periodo ');
       
      

    }

    public function generar_reporte_maquinaria(Request $request){


        $maquinarias = collect();
        $periodo=Periodo::find($request->input('periodo'));

        if($periodo){
            $inicioMes =Carbon::parse($periodo->fecha_inicio)->month;
            $finMes = Carbon::parse($periodo->fecha_final)->month;
            $año = explode("-", $periodo->clave);

            $maquinarias = Maquinaria::whereYear('created_at', $año[0])
                    ->whereMonth('created_at', '>=', $inicioMes)
                    ->whereMonth('created_at', '<=', $finMes)
                    ->get();
            $pdf = Pdf::loadView('reportes.maquinaria',['maquinarias'=>$maquinarias,'periodo'=>$periodo ,'año'=>$año[0]]);
            return $pdf->stream();
        }

        

        return redirect()->route('maquinaria.index')->with('error', 'No se selecciono el periodo ');

    }

    public function generar_reporte_insumos(Request $request){
 
        $Insumos = collect();
        $periodo=Periodo::find($request->input('periodo'));
        if ($periodo) {

            $inicioMes =Carbon::parse($periodo->fecha_inicio)->month;
            $finMes = Carbon::parse($periodo->fecha_final)->month;
            $año = explode("-", $periodo->clave);

            $Insumos = Insumos::whereYear('created_at', $año[0])
                    ->whereMonth('created_at', '>=', $inicioMes)
                    ->whereMonth('created_at', '<=', $finMes)
                    ->get();
            $pdf = Pdf::loadView('reportes.insumos',['Insumos'=>$Insumos,'periodo'=>$periodo ,'año'=>$año[0]]);
            return $pdf->stream();
        }

        
        return redirect()->route('insumos.index')->with('error', 'No se selecciono el periodo ');
    }


    public function generar_reporte_practicas_completas(Request $request){
        $todas_practicas = Practica::with(['alumnos', 'docente', 'grupo'])->where('estatus', 1)->get();

        $todas_practicas=$todas_practicas->groupby('grupo.clave_grupo');
        $pdf = Pdf::loadView('reportes.practicas',['todas_practicas'=>$todas_practicas]);
        $pdf->setPaper('A4','landscape');
        return $pdf->stream();

    }


}
