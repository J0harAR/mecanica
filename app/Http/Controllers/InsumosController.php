<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insumos;
use App\Models\Catalogo_articulo;
use App\Models\Articulo_inventariado;
use App\Models\Auditoria;

class InsumosController extends Controller
{
    public function index()
    {
      
  
        $insumos = Insumos::with('Articulo_inventariados.Catalogo_articulos')->get();
  
        //foreach ($herramientas as $herramienta) {
          //  echo $herramienta->Articulo_inventariados->Catalogo_articulos->nombre;
      //  }
        
        return view('insumos.index',compact('insumos'));
    }

    public function update(Request $request,$id_insumo)
    {
        //Request
        $estatus_insumo=$request->input('estatus');
        $capacidad=$request->input('capacidad');
        $auditoria_insumo=new Auditoria;

        $articulo_inventariado=Articulo_inventariado::find($id_insumo); 
        $insumo=Insumos::find($id_insumo);
        
        $insumo->capacidad=$capacidad;
        $insumo->save();
        
        if($articulo_inventariado->estatus!==$estatus_insumo){
            $auditoria_insumo->event='updated';
            $auditoria_insumo->subject_type=Articulo_inventariado::class;
            $auditoria_insumo->subject_id=$articulo_inventariado->id_inventario;
            $auditoria_insumo->cause_id=auth()->id();
            $auditoria_insumo->old_data=json_encode($articulo_inventariado);
            
            $articulo_inventariado->estatus=$estatus_insumo;
            $articulo_inventariado->save();

            $auditoria_insumo->new_data=json_encode($articulo_inventariado);
            $auditoria_insumo->save();
        }



  
        return redirect()->route('insumos.index')->with('success', 'El insumo con id: ' . $id_insumo . ' ha sido actualizado exitosamente.');
    }




    public function destroy($id_insumo)
    {
        $insumo=Articulo_inventariado::find($id_insumo);
        $auditoria=new Auditoria;
        $auditoria->event='deleted';  
        $auditoria->subject_type=Insumos::class;
        $auditoria->subject_id=$insumo->id_inventario;
        $auditoria->cause_id=auth()->id();
        $auditoria->old_data=json_encode($insumo);
        $auditoria->new_data=json_encode([]);
        $auditoria->save();

       //Actualizar Catalogo articulos
        $catalogo_articulo=Catalogo_articulo::find($insumo->id_articulo);
        if($catalogo_articulo->cantidad>0){
            $catalogo_articulo->cantidad-=1;
            $catalogo_articulo->save();

        }else{
            $catalogo_articulo->cantidad=0;
            $catalogo_articulo->save();
        }
        $insumo->delete();
        return redirect()->route('insumos.index')->with('success', 'El insumo con id: ' . $id_insumo. ' ha sido eliminado exitosamente.');

       
    }
}
