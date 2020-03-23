<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <!-- <link href="{{ asset('css/bootstrap4.css') }}" rel="stylesheet" /> -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ asset('css/bootstrap-3-simple.min.css') }}" rel="stylesheet" />
        
    </head>
    <body>
        <header>
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
               max-height: 2px !important;
               height: 2px !important;
            }

            </style>
            <header>
                <div class="x_title">
                    <h2>Ficha del Miembro</h2>
                    
                    <div class="clearfix"></div>
                </div>
                <br>
                @if( $miembro->foto != null || $miembro->foto != '' )
                    <img src="img/fotos/{{  $miembro->foto }}" alt="Foto Miembro" width="100" height="150" class="img-thumbnail">
                @else
                    <img src="images/user.png" alt="Foto Miembro" width="100" height="150" class="img-thumbnail">
                @endif
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr class="text-center"  >
                             <td class="text-center" ><div class="alto">Nombre</div></td>
                             <td >Apellido 1</td>
                             <td >Apellido 2</td>
                        </tr>
                        <tr  >
                             <td >{{  $miembro->nombre }}</td>
                             <td >{{  $miembro->apellido1 }}</td>
                             <td >{{  $miembro->apellido2 }}</td>
                        </tr>
                        <tr class="text-center">
                             <td >Email</td>
                             <td >Nro. de Identificación</td>
                             <td >Código Postal</td>
                        </tr>
                        <tr  >
                             <td >{{  $miembro->email }}</td>
                             <td >{{  $miembro->tipoDocumento.' - '.$miembro->nroDocumento }}</td>
                             <td >{{  $miembro->codigoPostal }}</td>
                        </tr>
                        <tr class="text-center"> <td  colspan="3">Dirección</td></tr>
                        <tr  >
                             <td  colspan="3">{{  $miembro->direccion }}</td>
                        </tr>
                        <tr class="text-center">
                             <td >Comunidad Autónoma</td>
                             <td >Provincia</td>
                             <td >Población</td>
                        </tr>
                        <tr  >
                             <td >{{  $miembro->comunidad }}</td>
                             <td >{{  $miembro->provincia }}</td>
                             <td >{{  $miembro->poblacion }}</td>
                        </tr>
                        <tr class="text-center">
                             <td >Teléfono</td>
                             <td >Movil</td>
                             <td >F. Nacimiento</td>
                        </tr>
                        <tr  >
                             <td >{{  $miembro->telefonoFijo }}</td>
                             <td >{{  $miembro->telefonoMovil }}</td>
                             <td >{{  \Carbon\Carbon::parse($miembro->fecNacimiento)->format('d/m/Y') }} Edad: {{  $miembro->edad }}</td>
                        </tr>
                        <tr class="text-center">
                             <td >Lugar Nacimiento</td>
                             <td >País</td>
                             <td >Profesión</td>
                        </tr>
                        <tr  >
                             <td >{{  $miembro->lugarNacimiento }}</td>
                             <td >{{  $miembro->pais }}</td>
                             <td >{{  $miembro->profesion }}</td>
                        </tr>
                        <tr class="text-center">
                             <td >Fecha Bautismo</td>
                             <td  colspan="2">Iglesia Bautismo</td>
                        </tr>
                        <tr  >
                             <td >{{  \Carbon\Carbon::parse($miembro->fecBautismo)->format('d/m/Y') }}</td>
                             <td  colspan="2">{{  $miembro->iglesiaBautismo }}</td>
                        </tr>
                        <tr class="text-center">
                             <td >Fecha Carta Traslado</td>
                             <td  colspan="2">Iglesia Procedencia</td>
                        </tr>
                        <tr  >
                             <td >{{  \Carbon\Carbon::parse($miembro->fecCartaTraslado)->format('d/m/Y') }}</td>
                             <td  colspan="2">{{  $miembro->iglesiaProcedencia }}</td>
                        </tr>
                        <tr class="text-center">
                             <td  colspan="3">Otros datos de interes</td>
                        </tr>
                        <tr  >
                            <td  colspan="3">{{  $miembro->otrosDatos }}</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="ln_solid"></div>
            </form>
        </div>
    </div>
</header>

<br><br><br>
<table style="width: 100%">
    <thead>
    </thead>
    
</table>


</header>
</body>
</html>