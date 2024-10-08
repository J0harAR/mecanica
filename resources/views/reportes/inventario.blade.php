<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .header {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            height: 80px; /* Ajusta el tamaño de las imágenes según sea necesario */
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: middle;
        }
        .left, .right {
            width: 20%;
        }
        .center {
            width: 60%;
            text-align: center;
        }
        /* Center the table in the body */
        .content-table {
            margin: 0 auto;
            width: 80%; /* Adjust the width as needed */
            border-collapse: collapse;
        }
        .content-table th, .content-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            text-transform: uppercase;
        }
        .content-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    @can('generar_reporte_inventario')
        
  
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="left">
                    <img src="{{ public_path('assets/img/TecNM_logo.png') }}" alt="Logo TecNM">
                </td>
                <td class="center">
                    <p>TECNOLÓGICO NACIONAL DE MÉXICO</p>
                    <p>Instituto Tecnológico de Oaxaca</p>
                    <p>Reporte de inventario del periodo {{mb_strtoupper(\Carbon\Carbon::parse($periodo->fecha_inicio)->locale('es')->isoFormat('MMMM')) }} -
                    {{ mb_strtoupper(\Carbon\Carbon::parse($periodo->fecha_final)->locale('es')->isoFormat('MMMM')) }}/{{$año}}</p>
                    
                </td>
                <td class="right">
                    <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo ITO">
                </td>

               
            </tr>
        </table>
    </div>

    <table class="content-table">
        <tr>
            <th>Id articulo</th>
            <th>Nombre del articulo</th>
            <th>Cantidad</th>
            <th>Seccion</th>
            <th>Tipo</th>
        </tr>
        @foreach ($inventario as $i)
        <tr>
            <td>{{$i->id_articulo}}</td>
            <td>{{$i->nombre}}</td>
            <td>{{$i->cantidad}}</td> 
                @if ($i->seccion)
                    <td>{{$i->seccion}}</td>
                @else
                    <td>N/A</td>
                @endif              
            
            <td>{{$i->tipo}}</td>
        </tr>
        @endforeach
    </table>
    @endcan
</body>
</html>
