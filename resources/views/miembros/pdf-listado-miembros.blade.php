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
        .anchoNombre{
            min-width: 200px !important;
            width: 200px !important;
        }
        .anchoGenero{
            min-width: 45px !important;
            width: 45px !important;
        }
        .anchoFecNac{
            min-width: 60px !important;
            width: 60px !important;
        }
        .anchoEmail{
            min-width: 160px !important;
            width: 160px !important;
        }
        </style>
    </head>
    <body>
        <header>
            <img src="img/logo_horizontal.png" alt="" height="75%" style="float: left;">
            <h5 style="text-align:center!important;">INFORME DE MIEMBROS </h5>
            <hr>
            <table style="font-size: 10px;font-weight: bold;">
            <tr>
                <td class="alto anchoNombre">
                    @if($orden == 1)
                        NOMBRE Y APELLIDOS
                    @else
                        APELLIDOS Y NOMBRES
                    @endif
                </td>
                <td class="alto anchoGenero">
                    GENERO
                </td>
                <td class="alto anchoFecNac">
                    F. NAC.
                </td>
                <td class="alto">
                    Nro. DOC.
                </td>
                <td class="alto">
                    TEL. MOVIL
                </td>
                <td class="alto anchoEmail">
                    EMAIL
                </td>
                <td class="alto">
                    PROFESION
                </td>
            </tr>
        </table>
            <hr style="margin-top: 0">
        </header>
        <br>
        <table class="">
             
            <tbody style="font-size: 10px;">
                @foreach( $miembros as $miembro )
                <tr >
                    <td nowrap class="alto">
                        @if ($orden == 1)
                            {{ $miembro->Nombre }}
                        @else
                            {{ $miembro->Apellido }} 
                        @endif
                    </td>
                    <td class="alto">
                        {{ trim($miembro->sexo) }}
                    </td>
                    <td class="alto">
                        {{ \Carbon\Carbon::parse($miembro->fecNacimiento)->format('d/m/Y')  }}
                    </td>
                    <td nowrap class="alto">{{ $miembro->tipoDocumento }} {{ $miembro->nroDocumento }}</td>
                    <td class="alto">
                        {{ $miembro->telefonoMovil }}
                    </td>
                    <td class="alto">
                        {{ $miembro->email }}
                    </td>
                    <td class="alto">
                        @if(substr($miembro->profesion,0,10) != 'Seleccione')
                        {{ $miembro->profesion }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>