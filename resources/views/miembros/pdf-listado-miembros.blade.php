<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ asset('css/bootstrap-3-simple.min.css') }}" rel="stylesheet" />

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
            <div class="row" style="margin-top:-1.5em;">
                <div class="col-xs-2 " >
                    @if($orden == 1)
                        <h1 style="font-size: 10px;" class="h6 text-left">NOMBRE Y APELLIDOS</h1>
                    @else
                        <h1 style="font-size: 10px;" class="h6 text-left">APELLIDOS Y NOMBRES</h1>
                    @endif
                </div>
                <div class="col-xs-1 " >
                    <h1 style="font-size: 10px;margin-left:3em;" class="h6 text-left">GENERO</h1>
                </div>
                <div class="col-xs-1 " >
                    <h1 style="font-size: 10px;" class="h6 text-left">F. NAC.</h1>
                </div>
                <div class="col-xs-1 " >
                    <h1 style="font-size: 10px;margin-left:-5em;" class="h6 text-left">Nro. DOC.</h1>
                </div>
                <div class="col-xs-1 " >
                    <h1 style="font-size: 10px;margin-left:-6.5em;" class="h6 text-left">TEL. MOVIL</h1>
                </div>
                <div class="col-xs-1 " >
                    <h1 style="font-size: 10px;margin-left:-8.5em;" class="h6 text-left">EMAIL</h1>
                </div>
                <div class="col-xs-1 " >
                    <h1 style="font-size: 10px;margin-left:-5em;" class="h6 text-left">PROFESION</h1>
                </div>
            </div>
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