
<form action="{{route('maquinaria.cantidad',$maquinaria->id_maquinaria)}}" method="POST">
@csrf
@method('PATCH')


<table>
@foreach ($maquinaria->insumos as $insumo)
    <tr>
        <td>
            <input type="checkbox"name="{{$insumo->id_insumo}}" >
        </td> 

        <td>
            {{$insumo->Articulo_inventariados->Catalogo_articulos->nombre}}
        </td>
        <td>
            <input type="text" name="insumos[{{$insumo->id_insumo}}]" placeholder="cantidad">
        </td>
    </tr>


@endforeach

</table>

<button>s</button>



</form>