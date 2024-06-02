<?php

namespace App\Http\Controllers;
use App\Models\Catalogo_articulo;
use App\Models\Practica;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Docente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
class ReportesController extends Controller
{
   

    public function generar_reporte_prestamo(Request $request){
        $prestamos = DB::table('prestamo')->get();
     

        $pdf = Pdf::loadView('reportes.prestamos',['prestamos'=>$prestamos]);
        return $pdf->stream();



    }

    public function generar_reporte_inventario(Request $request){
        $inventario=Catalogo_articulo::all();
     

        $pdf = Pdf::loadView('reportes.inventario',['inventario'=>$inventario]);
        return $pdf->stream();

    }

    public function generar_reporte_practicas_completas(Request $request){
        $todas_practicas=Practica::all();
        dd($todas_practicas->alumnos()->get);

      //  $pdf = Pdf::loadView('reportes.practicas',['practicas'=>$practicas]);
       // $pdf->setPaper('A4','landscape');
       // return $pdf->stream();

    }


}
