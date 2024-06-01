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
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="left">
                <img src="{{ public_path('assets/img/TecNM_logo.png') }}"  alt="Logo TecNM">
                </td>
                <td class="center">
                    <p>TECNOLÓGICO NACIONAL DE MÉXICO</p>
                    <p>Instituto Tecnológico de Oaxaca</p>
                </td>
                <td class="right">
                <img src="{{ public_path('assets/img/logo.png') }}"  alt="Logo ITO">
                </td>
            </tr>
        </table>
    </div>


    <table>
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
    <td>{{$i->seccion}}</td>
    <td>{{$i->tipo}}</td>
  </tr>
  @endforeach
</table>
 
       
    
</body>
</html>