<?php

namespace App\Http\Controllers;
use App\Models\Catalogo_articulo;
use App\Models\Practica;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Docente;
use App\Models\Herramientas;
use App\Models\Insumos;
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
    
        $clave_periodo = $request->input('periodo');
        $no_semestre = explode("-", $clave_periodo);
        $inventario = collect(); 
    
        switch ($no_semestre[1]) {
            // Febrero-junio
            case 1:
                $mes_inicial = 2;
                $mes_final = 6;
                $inventario = Catalogo_articulo::whereYear('created_at', $no_semestre[0])
                    ->whereMonth('created_at', '>=', $mes_inicial)
                    ->whereMonth('created_at', '<=', $mes_final)
                    ->get();
                break;
            // Julio
            case 2:
                $mes = 7;
                $inventario = Catalogo_articulo::whereYear('created_at', $no_semestre[0])
                    ->whereMonth('created_at', $mes)
                    ->get();
                break;
            // Agosto-diciembre
            case 3:
                $mes_inicial = 8;
                $mes_final = 12;
                $inventario = Catalogo_articulo::whereYear('created_at', $no_semestre[0])
                    ->whereMonth('created_at', '>=', $mes_inicial)
                    ->whereMonth('created_at', '<=', $mes_final)
                    ->get();
                break;
        }
    
        $pdf = Pdf::loadView('reportes.inventario', ['inventario' => $inventario, 'periodo' => $clave_periodo]);
        return $pdf->stream();
    }


    public function generar_reporte_herramientas(Request $request){
        $clave_periodo = $request->input('periodo');
        $no_semestre = explode("-", $clave_periodo);
        $herramientas = collect();
       
        switch ($no_semestre[1]) {
            // Febrero-junio
            case 1:
                $mes_inicial = 2;
                $mes_final = 6;
                $herramientas = Herramientas::whereYear('created_at', $no_semestre[0])
                    ->whereMonth('created_at', '>=', $mes_inicial)
                    ->whereMonth('created_at', '<=', $mes_final)
                    ->get();
                break;
            // Julio
            case 2:
                $mes = 7;
                $herramientas = Herramientas::whereYear('created_at', $no_semestre[0])
                    ->whereMonth('created_at', $mes)
                    ->get();
                break;
            // Agosto-diciembre
            case 3:
                $mes_inicial = 8;
                $mes_final = 12;
                $herramientas = Herramientas::whereYear('created_at', $no_semestre[0])
                    ->whereMonth('created_at', '>=', $mes_inicial)
                    ->whereMonth('created_at', '<=', $mes_final)
                    ->get();
                break;
        }
       
        $pdf = Pdf::loadView('reportes.herramientas',['herramientas'=>$herramientas,'periodo'=>$clave_periodo]);
        return $pdf->stream();

    }

    public function generar_reporte_maquinaria(Request $request){

        $clave_periodo = $request->input('periodo');
        $no_semestre = explode("-", $clave_periodo);
        $maquinarias = collect();
        
        switch ($no_semestre[1]) {
            // Febrero-junio
            case 1:
                $mes_inicial = 2;
                $mes_final = 6;
                $maquinarias = Maquinaria::whereYear('created_at', $no_semestre[0])
                    ->whereMonth('created_at', '>=', $mes_inicial)
                    ->whereMonth('created_at', '<=', $mes_final)
                    ->get();
                break;
            // Julio
            case 2:
                $mes = 7;
                $maquinarias = Maquinaria::whereYear('created_at', $no_semestre[0])
                    ->whereMonth('created_at', $mes)
                    ->get();
                break;
            // Agosto-diciembre
            case 3:
                $mes_inicial = 8;
                $mes_final = 12;
                $maquinarias = Maquinaria::whereYear('created_at', $no_semestre[0])
                    ->whereMonth('created_at', '>=', $mes_inicial)
                    ->whereMonth('created_at', '<=', $mes_final)
                    ->get();
                break;
        }


        $pdf = Pdf::loadView('reportes.maquinaria',['maquinarias'=>$maquinarias,'periodo'=>$clave_periodo]);
        return $pdf->stream();

    }

    public function generar_reporte_insumos(Request $request){
        $clave_periodo = $request->input('periodo');
        $no_semestre = explode("-", $clave_periodo);
        $Insumos = collect();

        switch ($no_semestre[1]) {
            // Febrero-junio
            case 1:
                $mes_inicial = 2;
                $mes_final = 6;
                $Insumos = Insumos::whereYear('created_at', $no_semestre[0])
                    ->whereMonth('created_at', '>=', $mes_inicial)
                    ->whereMonth('created_at', '<=', $mes_final)
                    ->get();
                break;
            // Julio
            case 2:
                $mes = 7;
                $Insumos = Insumos::whereYear('created_at', $no_semestre[0])
                    ->whereMonth('created_at', $mes)
                    ->get();
                break;
            // Agosto-diciembre
            case 3:
                $mes_inicial = 8;
                $mes_final = 12;
                $Insumos = Insumos::whereYear('created_at', $no_semestre[0])
                    ->whereMonth('created_at', '>=', $mes_inicial)
                    ->whereMonth('created_at', '<=', $mes_final)
                    ->get();
                break;
        }

      
        $pdf = Pdf::loadView('reportes.insumos',['Insumos'=>$Insumos,'periodo'=>$clave_periodo]);
        return $pdf->stream();

    }



    public function generar_reporte_practicas_completas(Request $request){
        $todas_practicas = Practica::with(['alumnos', 'docente', 'grupo'])->where('estatus', 1)->get();

        $todas_practicas=$todas_practicas->groupby('grupo.clave_grupo');
        $pdf = Pdf::loadView('reportes.practicas',['todas_practicas'=>$todas_practicas]);
        $pdf->setPaper('A4','landscape');
        return $pdf->stream();

    }


}
