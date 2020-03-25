<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <!-- <link href="{{ asset('css/bootstrap4.css') }}" rel="stylesheet" /> -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <style>
        /** Define the margins of your page **/
        @page {
        margin: 100px 25px;
        }
        header {
        position: fixed;
        top: -80px;
        left: 0px;
        right: 0px;
        height: 50px;
        /** Extra personal styles **/
        color: black;
        text-align: center;
        line-height: 35px;
        }
        footer {
        position: fixed;
        bottom: -60px;
        left: 0px;
        right: 0px;
        height: 50px;
        /** Extra personal styles **/
        background-color: #03a9f4;
        color: white;
        text-align: center;
        line-height: 35px;
        }
        .alto{
        max-height: 2em !important;
        height: 2em !important;
        }
        .ancho{
        max-width: : 20px !important;
        width: : 20px !important;
        }

        </style>
    </head>
    <body>
        <header>
            <img src="img/logo.png" alt="" width="15%" height="75%">
            <br>
            <span class="text-center">Informe de Miembros por Rango de Edad ({{ $edadDesde }} años hasta {{ $edadHasta }} años) con status {{ $status }}</span>
            <hr>
        </header>
        <br>
        <table>
            <tbody>
                @foreach( $miembros as $miembro )
                <tr >
                    <td class="alto">
                        {{ $miembro->nombre}} {{ $miembro->apellido1 }} {{ $miembro->apellido2}}
                    </td>
                    <td class="alto">
                        {{ \Carbon\Carbon::parse($miembro->fecNacimiento)->format('d/m/Y')  }}
                    </td>
                    <td class="alto">&nbsp;&nbsp;&nbsp;&nbsp; </td>
                    <td class="alto" style="float: right;">
                        {{ $miembro->edad }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <br>

        <h4 class="caja">Total Miembros en el rango de Edad: <strong>{{ $total }} </strong></h4>
    </body>
</html>