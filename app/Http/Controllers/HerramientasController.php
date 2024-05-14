<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Herramientas;
use App\Models\Articulo_inventariado;
use App\Models\Catalogo_articulo;
use App\Models\Auditoria;
use App\Models\Insumos;
class HerramientasController extends Controller
{
    public function index()
    {
      

        $herramientas = Herramientas::with('Articulo_inventariados.Catalogo_articulos')->get();
        $insumos=Insumos::all();
        //foreach ($herramientas as $herramienta) {
          //  echo $herramienta->Articulo_inventariados->Catalogo_articulos->nombre;
      //  }
        
        return view('herramientas.index',compact('herramientas','insumos'));
    }

    public function update(Request $request,$id_herramientas)
    {


        //Request
        $condicion_herramienta=$request->input('condicion_herramienta');
        $estatus_herramienta=$request->input('estatus');


        $auditoria_herramienta=new Auditoria;
        $auditoria_inventario=new Auditoria;
       

        $articulo_inventariado=Articulo_inventariado::find($id_herramientas);   

        if($articulo_inventariado->estatus !== $estatus_herramienta) {
            //Parte del historial 
            $auditoria_inventario->event='updated';
            $auditoria_inventario->subject_type=Articulo_inventariado::class;
            $auditoria_inventario->subject_id=$articulo_inventariado->id_inventario;
            $auditoria_inventario->cause_id=auth()->id();
            $auditoria_inventario->old_data=json_encode($articulo_inventariado);
            
            $articulo_inventariado->estatus=$estatus_herramienta;
            $articulo_inventariado->save();        
            
            $auditoria_inventario->new_data=json_encode($articulo_inventariado);
            $auditoria_inventario->save();
         
        }

       
        $herramienta=Herramientas::find($id_herramientas);

        if($herramienta->condicion!== $condicion_herramienta){
            $auditoria_herramienta->event='updated';
            $auditoria_herramienta->subject_type=Herramientas::class;
            $auditoria_herramienta->subject_id=$herramienta->id_herramientas;
            $auditoria_herramienta->cause_id=auth()->id();
            $auditoria_herramienta->old_data=json_encode($herramienta);

            $herramienta->condicion=$condicion_herramienta;
            $herramienta->save();

            $auditoria_herramienta->new_data=json_encode($herramienta);
            $auditoria_herramienta->save();

        }

        
        return redirect()->route('herramientas.index')->with('success', 'La herramienta: ' . $id_herramientas . ' ha sido actualizada exitosamente.');

    }




    public function destroy($id_herramientas)
    {
        $herramienta=Articulo_inventariado::find($id_herramientas);
        $auditoria=new Auditoria;
        $auditoria->event='deleted';  
        $auditoria->subject_type=Herramientas::class;
        $auditoria->subject_id=$herramienta->id_inventario;
        $auditoria->cause_id=auth()->id();
        $auditoria->old_data=json_encode($herramienta);
        $auditoria->new_data=json_encode([]);
        $auditoria->save();

       //Actualizar Catalogo articulos
        $catalogo_articulo=Catalogo_articulo::find($herramienta->id_articulo);
        if($catalogo_articulo->cantidad>0){
            $catalogo_articulo->cantidad-=1;
            $catalogo_articulo->save();

        }else{
            $catalogo_articulo->cantidad=0;
            $catalogo_articulo->save();
        }
        $herramienta->delete();
        return redirect()->route('herramientas.index')->with('success', 'La herramienta: ' . $id_herramientas . ' ha sido eliminada exitosamente.');


       
    }
}
