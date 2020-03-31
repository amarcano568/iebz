<!DOCTYPE html>
<html>
    <head>
        <title></title>
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
        height: 40px;
        /** Extra personal styles **/
        color: black;
        text-align: center;
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
            <img src="img/logo.png" alt="" width="15%" height="50%" style="float: left;">
            <span style="text-align: center;" class="text-center">INFORME DE MIEMBROS </span>
            <hr>
            <table  style="font-size: 10px;">
                <tr >
                    <td class="alto" style="width: 20%">
                        @if ($orden == 1)
                            NOMBRE Y APELLIDOS
                        @else
                            APELLIDOS Y NOMBRES
                        @endif
                    </td>
                    <td>&nbsp;</td>
                    <td class="alto">
                        &nbsp;&nbsp;&nbsp;&nbsp;GENERO
                    </td>
                    <td class="alto">
                        F. NACIMIENTO
                    </td>
                    <td class="alto">
                        Nro. DOC.
                    </td>
                    <td class="alto">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TEL. MOVIL
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="alto" style="width: 20%">
                        EMAIL
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class="alto" style="width: 20%">
                        PROFESION
                    </td>
                </tr>
            </table>
            <hr>
        </header>
        <br>
        <table>
             
            <tbody style="font-size: 10px;">
                @foreach( $miembros as $miembro )
                <tr >
                    <td class="alto">
                        @if ($orden == 1)
                            {{ $miembro->nombre}} {{ $miembro->apellido1 }} {{ $miembro->apellido2}}
                        @else
                            {{ $miembro->apellido1 }} {{ $miembro->apellido2}}, {{ $miembro->nombre}} 
                        @endif
                    </td>
                    <td class="alto">
                        {{ $miembro->sexo }}
                    </td>
                    <td class="alto">
                        {{ \Carbon\Carbon::parse($miembro->fecNacimiento)->format('d/m/Y')  }}
                    </td>
                    <td class="alto">{{ $miembro->tipoDocumento }} {{ $miembro->nroDocumento }}</td>
                    <td class="alto">
                        {{ $miembro->telefonoMovil }}
                    </td>
                    <td class="alto">
                        {{ $miembro->email }}
                    </td>
                    <td class="alto">
                        {{ $miembro->profesion }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>