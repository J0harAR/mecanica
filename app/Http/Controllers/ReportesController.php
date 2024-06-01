<?php

namespace App\Http\Controllers;
use App\Models\Catalogo_articulo;
use Illuminate\Http\Request;
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


}
