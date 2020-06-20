<?php

namespace App\Http\Controllers;

use \Auth;
use Carbon\Carbon;
use \DB;
use File;
use \Miembros;
use \Comunidades;
use \Provincias;
use \Paises;
use \Iglesias;
use \Profesiones;
use \Status;
use \Usuarios;
use \App\SubMinisterios;
use Uuid;
use PDF;
use Illuminate\Http\Request;
use App\Traits\funcGral;
use \DataTables;
use App\Exports\MiembrosExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Illuminate\Support\Facades\Storage;




class miembrosController extends Controller
{
    use funcGral;

    public function loadMiembros()
    {
        $comunidades = \App\Comunidades::get();
        $provincias = \App\Provincias::get();
        $paises = \App\Paises::get();
        $profesiones = \App\Profesiones::get();
        $status = \App\Status::get();
        $iglesias = \App\Iglesias::get();

        $data = array(  
                        'comunidades' => $comunidades,
                        'provincias'  => $provincias,
                        'paises'      => $paises,
                        'profesiones' => $profesiones,
                        'status'      => $status,
                        'iglesias'    => $iglesias
                     );

        return view('miembros.verMiembros',$data);
    }

    /**
     *      Lista Miembros del Sistema.
     */
    public function listarMiembros()
    {

        $miembros = \App\Miembros::join('iglesias','miembros.idIglesia','=','iglesias.id')
            ->join('comunidades','miembros.comunidad','=','comunidades.id')
            ->join('provincias','miembros.provincia','=','provincias.id')
            ->join('paises','miembros.paisNacimiento','=','paises.id')
            ->leftjoin('profesiones','miembros.profesion','=','profesiones.id')
            ->join('status','miembros.status','=','status.id')
            ->select('miembros.id', 'miembros.idIglesia', 'miembros.nombre', 'miembros.apellido1', 'miembros.apellido2', 'miembros.tipoDocumento','miembros.nroDocumento', 'miembros.direccion', 'miembros.codigoPostal', 'miembros.comunidad', 'miembros.provincia', 'miembros.telefonoFijo', 'miembros.telefonoMovil', 'miembros.email', 'miembros.fecNacimiento', 'miembros.lugarNacimiento', 'miembros.paisNacimiento', 'miembros.profesion', 'miembros.fecBautismo', 'miembros.iglesiaBautismo', 'miembros.fecCartaTraslado', 'miembros.iglesiaProcedencia', 'miembros.otrosDatos', 'miembros.foto', 'miembros.relacionFamilia', 'miembros.status', 'miembros.poblacion','iglesias.nombreCorto','comunidades.nombre AS comunidad','provincias.nombre AS provincia','paises.nombre AS pais','profesiones.nombre AS profesion','status.nombre AS statusNombre')
            ->get();
        $miembros->map(function($miembro){           
            $edad = Carbon::parse($miembro->fecNacimiento)->age;
            $miembro->edad = $edad;
        });

        return Datatables::of($miembros)
                        ->setRowId('id')
                        ->addIndexColumn()
                        ->addColumn('detalle', function($row){
	                            return $this->detalleMiembro($row);
	                    })
	                    ->addColumn('action', function($row){
                                    if ( $row->foto == '' ){
                                        $foto = '/images/user.png';
                                    }else if( file_exists('img/fotos/'.$row->foto) ){
                                        $foto = '/img/fotos/'.$row->foto;
                                    }else{
                                        $foto = '/images/user.png';
                                    }
                               $btn =  '<div class="icono-action">
                                    <a href="" data-accion="editarMiembro" idMiembro="'.$row->id.'" >
                                        <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Editar miembro (<strong>'.$row->nombre.'</strong>)." class="icono-action text-primary far fa-edit">
                                        </i>
                                    </a>
                                    <a href="" data-accion="eliminarMiembro" idMiembro="'.$row->id.'" status="'.$row->status.'" nombreMiembro="'.$row->nombre.' '.
                                            $row->apellido1.' '.$row->apellido2.'" foto="'.$foto.'">
                                        <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Eliminar Ficha miembro (<strong>'.$row->nombre.'</strong>)." class="text-danger far fa-trash-alt"></i>
                                    </a>
                                </div>';
	                            return $btn;
                        })
	                    ->rawColumns(['detalle','action'])
                        ->make(true);
                                
        // $dataSet = array (
        //     "sEcho"                 =>  0,
        //     "iTotalRecords"         =>  1,
        //     "iTotalDisplayRecords"  =>  1,
        //     "aaData"                =>  array () 
        // );

        // foreach($miembros as $miembro)
        // {
        //     if ( $miembro->foto == '' ){
        //         $foto = '/images/user.png';
        //     }else if( file_exists('img/fotos/'.$miembro->foto) ){
        //         $foto = '/img/fotos/'.$miembro->foto;
        //     }else{
        //         $foto = '/images/user.png';
        //     }

        //     $dataSet['aaData'][] = array(  $miembro->id,
        //                                    $miembro->nombreCorto,
        //                                    $miembro->nombre,
        //                                    $miembro->apellido1,
        //                                    $miembro->apellido2,
        //                                    $miembro->edad,
        //                                    $miembro->telefonoMovil,
        //                                    $miembro->statusNombre,                                           
        //                                    $this->detalleMiembro($miembro),
        //                                    '<div class="icono-action">
        //                                         <a href="" data-accion="editarMiembro" idMiembro="'.$miembro->id.'" >
        //                                             <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Editar miembro (<strong>'.$miembro->nombre.'</strong>)." class="icono-action text-primary far fa-edit">
        //                                             </i>
        //                                         </a>
        //                                         <a href="" data-accion="eliminarMiembro" idMiembro="'.$miembro->id.'" status="'.$miembro->status.'" nombreMiembro="'.$miembro->nombre.' '.
        //                                    $miembro->apellido1.' '.$miembro->apellido2.'" foto="'.$foto.'">
        //                                             <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Eliminar Ficha miembro (<strong>'.$miembro->nombre.'</strong>)." class="text-danger far fa-trash-alt"></i>
        //                                         </a>
        //                                     </div>'
        //                                 );
        // }

        // $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
    
        // /* SE DEVUELVE LA SALIDA */
        // echo $salidaDeDataSet;
    }

    public function generarExcelMiembros() 
    {
        $header = ['Id', 'Nombre', 'Apellido1', 'Apellido2', 'Tipo Documento','Nro. Documento', 'Dirección', 'CP', 'Comunidad', 'Provincia', 'Telefono Fijo', 'Telefono Movil', 'Email', 'Fecha Nacimiento', 'Lugar Nacimiento', 'Pais de Nacimiento', 'Profesion', 'Fecha Bautismo', 'Iglesia Bautismo', 'Fecha Carta Traslado', 'Iglesia Procedencia', 'Otros Datos', 'Status', 'Poblacion','Status']; //headers
        Excel::store(new MiembrosExport($header,'A6'), 'miembros.xlsx','public');
        return asset('storage/miembros.xlsx');
    }


    public function detalleMiembro($miembro)
    {
        if ( $miembro->status == 1 ){
            $alert = 'alert-success';
        }else if ( $miembro->status == 2 ){
            $alert = 'alert-warning';
        }else if ( $miembro->status == 3 ){
            $alert = 'alert-info';
        }else{
            $alert = 'alert-danger';
        }

        $formulario = '<div class="x_panel">
                  <div class="x_title">
                    <h2>Miembro <small>Datos Personales</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="foto-miembro" foto="img\\fotos\\'.$miembro->foto.'"><i class="fas fa-camera-retro"></i></a>
                      </li>
                      <li><a class="imprimir-ficha" idMiembro="'.$miembro->id.'"><i class="fas fa-print"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                          </div>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">                
                    <form class="form-label-left input_mask">
                        <div class="alert '.$alert.' col-lg-12 col-md-12 col-sm-12 col-xs-12" role="alert">
                          El status de esta persona es <strong>'.$miembro->statusNombre.'</strong>
                        </div>
                        <div class="input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="far fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Nombre" aria-label="Nombre" aria-describedby="basic-addon1" readonly value="'.$miembro->nombre.'">
                        </div>
                        <div class="input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="far fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Apellido 1" aria-label="Apellido 1" aria-describedby="basic-addon1" readonly value="'.$miembro->apellido1.'">
                        </div>
                        <div class="input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="far fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Apellido 2" aria-label="Apellido 2" aria-describedby="basic-addon1" readonly value="'.$miembro->apellido2.'">
                        </div>

                        <div class="input-group  col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" readonly value="'.$miembro->email.'">
                        </div>

                        <div class="input-group  col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-id-card"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Nro. de Identificación" aria-label="Nro. de Identificación" aria-describedby="basic-addon1" readonly value="'.$miembro->tipoDocumento.' - '.$miembro->nroDocumento.'">
                        </div>
                        <div class="input-group  col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-list-ol"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Código Postal" aria-label="Código Postal" aria-describedby="basic-addon1" readonly value="'.$miembro->codigoPostal.'">
                        </div>

                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-map-signs"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Dirección" aria-label="Dirección" aria-describedby="basic-addon1" readonly value="'.$miembro->direccion.'">
                        </div>

                        <div class="input-group col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Comunidad Autónoma</span>
                            </div>
                            <input type="text" class="form-control" placeholder="Comunidad Autónoma" aria-label="Comunidad Autónoma" aria-describedby="basic-addon1" readonly value="'.$miembro->comunidad.'">
                        </div>
                        <div class="input-group  col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Provincia</span>
                            </div>
                            <input type="text" class="form-control" placeholder="Provincia" aria-label="Provincia" aria-describedby="basic-addon1" readonly value="'.$miembro->provincia.'">
                        </div>
                        <div class="input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Población</span>
                            </div>
                            <input type="text" class="form-control" placeholder="Población" aria-label="Población" aria-describedby="basic-addon1" readonly value="'.$miembro->poblacion.'">
                        </div>

                        <div class="input-group  col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Teléfono" aria-label="Teléfono" aria-describedby="basic-addon1" readonly value="'.$miembro->telefonoFijo.'">
                        </div>
                        <div class="input-group  col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-mobile-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Movil" aria-label="Movil" aria-describedby="basic-addon1" readonly value="'.$miembro->telefonoMovil.'">
                        </div>
                        <div class="input-group  col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="F. Nacimiento" aria-label="F. Nacimiento" aria-describedby="basic-addon1" readonly value="'.\Carbon\Carbon::parse($miembro->fecNacimiento)->format('d/m/Y').'">
                        </div>
                        <div class="input-group  col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-birthday-cake"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Edad" aria-label="F. Nacimiento" aria-describedby="basic-addon1" readonly value="'.$miembro->edad.'">
                        </div>

                        <div class="input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Lugar Nac.</span>
                            </div>
                            <input type="text" class="form-control" placeholder="Lugar de Nacimiento" aria-label="Lugar de Nacimiento" aria-describedby="basic-addon1" readonly value="'.$miembro->lugarNacimiento.'">
                        </div>
                        <div class="input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">País</span>
                            </div>
                            <input type="text" class="form-control" placeholder="País" aria-label="País" aria-describedby="basic-addon1" readonly value="'.$miembro->pais.'">
                        </div>
                        <div class="input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Profesión</span>
                            </div>
                            <input type="text" class="form-control" placeholder="Profesión" aria-label="Profesión" aria-describedby="basic-addon1" readonly value="'.$miembro->profesion.'">
                        </div>

                        <div class="input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i>&nbsp;Bautismo</span>
                            </div>
                            <input type="text" class="form-control" placeholder="F. Bautismo" aria-label="F. Bautismo" aria-describedby="basic-addon1" readonly value="'.\Carbon\Carbon::parse($miembro->fecBautismo)->format('d/m/Y').'">
                        </div>
                        <div class="input-group  col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-church"></i>&nbsp;Bautismo</span>
                            </div>
                            <input type="text" class="form-control" placeholder="Iglesia Bautismo" aria-label="Iglesia Bautismo" aria-describedby="basic-addon1" readonly value="'.$miembro->iglesiaBautismo.'">
                        </div>

                        <div class="input-group  col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i>&nbsp;Carta Traslado</span>
                            </div>
                            <input type="text" class="form-control" placeholder="F. Traslado" aria-label="F. Traslado" aria-describedby="basic-addon1" readonly value="'.\Carbon\Carbon::parse($miembro->fecCartaTraslado)->format('d/m/Y').'">
                        </div>
                        <div class="input-group  col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-church"></i>&nbsp;Procedencia</span>
                            </div>
                            <input type="text" class="form-control" placeholder="Iglesia Procedencia" aria-label="Iglesia Procedencia" aria-describedby="basic-addon1" readonly value="'.$miembro->iglesiaProcedencia.'">
                        </div>
                        
                        <div class="form-group col-lg-12">
                            <label for="exampleFormControlTextarea1">Otros datos de interes</label>
                            <textarea class="form-control" rows="3" readonly>'.$miembro->otrosDatos.'</textarea>
                        </div>
                      <div class="ln_solid"></div>
                    </form>
                  </div>
                </div>';
            return $formulario;
    }

    public function listarProvincias(Request $request)
    {
        $idComunidad = $request->idComunidad;

        $provincias = \App\Provincias::where('idComunidad',$idComunidad)->get();

        return response()->json( array('success' => true, 'mensaje'=> '', 'data' => $provincias) );
    }
    
    public function calcularFechaNacimiento(Request $request)
    {
        $fecNac = $request->fecNac;

        $edad = Carbon::parse($fecNac)->age;

        return response()->json( array('success' => true, 'mensaje'=> '', 'data' => $edad) );
    }
    

    public function editarMiembro(Request $request)
    {
        $miembro = \App\Miembros::join('iglesias','miembros.idIglesia','=','iglesias.id')
        ->join('comunidades','miembros.comunidad','=','comunidades.id')
        ->join('provincias','miembros.provincia','=','provincias.id')
        ->join('paises','miembros.paisNacimiento','=','paises.id')
        ->leftjoin('profesiones','miembros.profesion','=','profesiones.id')
        ->join('status','miembros.status','=','status.id')
        ->select('miembros.id', 'miembros.idIglesia', 'miembros.nombre', 'miembros.apellido1', 'miembros.apellido2', 'miembros.tipoDocumento','miembros.nroDocumento', 'miembros.direccion', 'miembros.codigoPostal', 'miembros.comunidad', 'miembros.provincia', 'miembros.telefonoFijo', 'miembros.telefonoMovil', 'miembros.email', 'miembros.fecNacimiento', 'miembros.lugarNacimiento', 'miembros.paisNacimiento', 'miembros.profesion', 'miembros.fecBautismo', 'miembros.iglesiaBautismo', 'miembros.fecCartaTraslado', 'miembros.iglesiaProcedencia', 'miembros.otrosDatos', 'miembros.foto', 'miembros.relacionFamilia', 'miembros.status', 'miembros.poblacion','iglesias.nombreCorto','comunidades.nombre AS comunidadNombre','provincias.nombre AS provinciaNombre','paises.nombre AS pais','profesiones.nombre AS profesionNombre','status.nombre AS statusNombre','miembros.sexo','miembros.parentesco')
        ->find($request->idMiembro);

        /**
         * Documentos Adjuntos
         */
        $totAdjuntos = 0;
        $ruta     = 'documentos/miembro-'.$request->idMiembro;
        $path     = public_path().'/'.$ruta;
        $miniaturasAdjuntas = '<center><img src="img/documentos.png" height="100" width="100"><br><h3 class="text-center">No tiene Documentos Adjuntos...</h3></center>';
        if (file_exists($path)) {
            
            $files = File::files($path);   
            //dd($files);         
            $totAdjuntos = count($files);
            $miniaturasAdjuntas = $this->archivosAdjuntos($files,$ruta,$request->idMiembro);
        }

        /**
         * Grupo Familiar
         */
        if ($miembro->relacionFamilia == '' || $miembro->relacionFamilia === null){
            $grupoFamiliar = '<center><img src="img/familia.png" height="100" width="100"><br><h3 class="text-center">No tiene grupo Familiar...</h3></center>';
        }else{
            $grupoFamiliar = $this->grupoFamiliar($miembro->id,$miembro->relacionFamilia);

        }
        
        /**
         *      Ministerios
         */
        $ministerios = $this->ministeriosDondeSirve($miembro->id);


        $miembroToRelacion = \App\Miembros::where('id','!=',$request->idMiembro)->get();

        $edad = Carbon::parse($miembro->fecNacimiento)->age;
        $miembro['edad'] = $edad;

        return response()->json( array('success' => true, 'mensaje'=> '', 'data' => $miembro, 'archivosAdjuntos' => $miniaturasAdjuntas, 'grupoFamiliar' => $grupoFamiliar, 'miembroToRelacion' => $miembroToRelacion, 'ministerios' => $ministerios) );
    }

    public function ministeriosDondeSirve($idMiembro)
    {
        $ministerios = \App\Ministerios::get();
        $salida = '';
        foreach ($ministerios as $ministerio) {
            $miembros = $ministerio->miembros;
            $idBusqueda = ';'.(string)$idMiembro.';';
            
            $pos = strpos($miembros, $idBusqueda);
            if ($pos !== false){
                $salida .= '<li>
                                <a>
                                <span class="image">
                                <i class="fa-2x fas fa-network-wired"></i>
                                </span>
                                <span>
                                <span style="font-size: 18px;">'.$ministerio->nombre.'</span>
                                </span>
                                </a>
                            </li>';
            }

            $salida .= $this->subMinisteriosDondeSirve($idMiembro,$ministerio->id);
        }

        if ($salida == ''){
            $salida = '<h4 class="text-info">No sirve en ningún Ministerio</h4>';
        }else{
            $salida = '<ul class="list-unstyled msg_list">
                            '.$salida.'
                        </ul>';
        }

        return $salida;

    }

    public function subMinisteriosDondeSirve($idMiembro,$idMinisterio)
    {
   
        $subministerios = SubMinisterios::where('idMinisterio',$idMinisterio)->get();
        $salida = '';
        foreach ($subministerios as $subministerio) {
            //$miembros = $subministerios->miembros;
            $idBusqueda = ';'.(string)$idMiembro.';';
            
            $pos = strpos($subministerio->miembros, $idBusqueda);
            if ($pos !== false){
                $salida .= '<li>
                                <a>
                                <span class="image">
                                <i class="fa-2x fas fa-network-wired"></i>
                                </span>
                                <span>
                                <span style="font-size: 18px;">'.$subministerio->nombre.'</span>
                                </span>
                                </a>
                            </li>';
            }

        }

        if ($salida != ''){
            $salida = '<ul class="list-unstyled msg_list">
                            '.$salida.'
                        </ul>';
        }

        return $salida;

    }

    public function grupoFamiliar($idMiembro,$uuidFamilia)
    {
        $familiares = \App\Miembros::where('relacionFamilia',$uuidFamilia)->get();
        $salida = '';
        foreach($familiares as $familiar)
        {
            $sexo = $familiar->sexo=='F' ? '<i class="fas fa-female"></i> Femenino ' : '<i class="fas fa-male"></i> Masculino ';

            $check = $familiar->id==$idMiembro ? '<i style="float: right;" class="text-success fas fa-check"></i> ' : '<a href=""><i idMiembroParentesco="'.$familiar->id.'" style="float: right;" data-toggle="tooltip" data-placement="top" title="Eliminar parentesco."  class="btnEliminarParentesco text-danger fas fa-user-times"></i></a>';
          

            if ($familiar->foto === null || $familiar->foto == ''  ){
                $foto = '<i class="far fa-user fa-4x img-circle "></i>';
            }else{
                $foto =  '<img src="img/fotos/'.$familiar->foto.'" alt="" class="img-circle img-fluids" style="width: 190%">';
            }

            $edad = Carbon::parse($familiar->fecNacimiento)->age;

            $salida .= '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card  ">
                        <div class="card-header">
             -               <h4 class="brief"><i>'.$familiar->parentesco.'</i>'.$check.'</h4> 
                        </div>
                          <div class="card-body">
                          
                            <div class="col-lg-12 col-sm-12">
                            
                                    <div class="left col-lg-8 col-md-8 col-sm-8">
                                        <h2>'.trim($familiar->nombre).' '.trim($familiar->apellido1).'</h2>
                                        
                                        <ul class="list-unstyled">
                                        <li>'.$sexo.'</li>
                                          <li><i class="fas fa-birthday-cake"></i> '.$edad.' </li>
                                          <li><i class="fa fa-phone"></i> '.$familiar->telefonoMovil.' </li>
                                        </ul>
                                    </div>
                                    <div class="right col-lg-4 col-md-4 col-sm-4 text-center">
                                        '.$foto.'
                                    </div>
                                </div>
                                
                          </div>
                        </div>
                      </div>';
        }

        return $salida;

    }

    /**
     * [registrarMiembro description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function registrarMiembro(Request $request)
    {

        try {
            DB::beginTransaction();   
  
            if ( is_null($request->idMiembro) ){  

                $rules = [
                            'email' => ['required', 'email', 'unique:miembros,email' ],
                            // 'Username' => ['required', 'unique:users,userName' ],
                        ];

                $customMessages =   [
                                        'email.unique' => '<i class="fas fa-exclamation-triangle"></i> Existe otro Miembro con ese <strong>Correo</strong>',
                                        // 'Username.unique'  => '<i class="fas fa-exclamation-triangle"></i> Existe otro Usuario con ese <strong>UserName</strong>',
                                    ];                                

                $v =  $this->validate($request, $rules, $customMessages);

            }    
            $save = \App\Miembros::Guardar($request);
            DB::commit();
            if(!$save){
                App::abort(500, 'Error');
            }

            return response()->json( array('success' => true, 'mensaje'=> '') );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }


    public function subirFotoMiembro(Request $request)
    {

        $ruta     = '/img/fotos/';
        $path     = public_path().$ruta;
        $files    = $request->file('file');
        $ext      = explode('/',$request->file('file')->getMimeType());
        $fileName = $files->getClientOriginalName();

        $files->move($path, $fileName);

        rename($path.$fileName, $path.'foto-'.$request->idMiembro.'.'.$ext[1]);
        
        DB::beginTransaction();   
        $Miembros = \App\Miembros::find($request->idMiembro);
        $Miembros->foto = 'foto-'.$request->idMiembro.'.'.$ext[1];
        $Miembros->save();
        DB::commit();
        //Storage::move($path.$fileName, $path.'usuario-'.$request->idSucursal);
        return response()->json( array('success' => true, 'mensaje'=> 'Foto cargada exitosamente','data' => 'foto-'.$request->idMiembro.'.'.$ext[1]) );
        
    }

    public function subirFotoPerfil(Request $request)
    {

        $ruta     = '/img/fotos/';
        $path     = public_path().$ruta;
        $files    = $request->file('file');
        $ext      = explode('/',$request->file('file')->getMimeType());
        $fileName = $files->getClientOriginalName();

        $files->move($path, $fileName);

        $idUser = Auth::id();

        rename($path.$fileName, $path.'foto-perfil-'.$idUser.'.'.$ext[1]);
        
        DB::beginTransaction();   
        $usuario = \App\Usuarios::find($idUser);
        $usuario->photo = 'foto-perfil-'.$idUser.'.'.$ext[1];
        $usuario->save();
        DB::commit();
        //Storage::move($path.$fileName, $path.'usuario-'.$request->idSucursal);
        return response()->json( array('success' => true, 'mensaje'=> 'Foto cargada exitosamente','data' => '/img/fotos/foto-perfil-'.$idUser.'.'.$ext[1]) );
        
    }


    public function archivosAdjuntos($archivos,$ruta,$idMiembro)
    {   
        $salida = '';
        foreach ($archivos as $archivo) {
            
            $archi = explode('/',$archivo);            
            $s_o = PHP_OS;
            if ($s_o=="WINNT"){ 
                $archi = explode('\\',$archivo);
            }        
            $pos = count($archi)-1;
            $archivo = $ruta.'/'.$archi[$pos];
            $ext = explode('.', $archi[$pos]);
            $created_at='';
            $nombreOriginal='';
            $infoFile = \App\FileStore::where('idMiembro','=',$idMiembro)->where('nombreFile','=',$archi[$pos])->first();
           

                $nombreOriginal = $infoFile->nombreOriginal;
                $nombreFile = $infoFile->nombreFile;
                $created_at = $infoFile->created_at;
        

            $iconos = '<a href="'.$archivo.'" download="'.$archi[$pos].'"><i class="text-success fas fa-download"></i></a>';
            if (str_contains('PNG*JPG*JPEG*GIF*BMP*jpg', strtoupper($ext[1]))){
                $iconos .= '<a href="#" class="clickPicture" nameDate="'.$created_at.'" nameShort="'.$nombreOriginal.'" nameFile="'.$archivo.'"><i class="text-primary fas fa-search"></i></a>
                      ';                
            }else{
                switch (strtoupper($ext[1])) {

                    case 'PDF':
                            $archivo = 'img/pdf.png';
                            break;
                    case 'XLSX':
                            $archivo = 'img/excel.png';
                            break;
                    case 'DOCX':
                            $archivo = 'img/word.png';
                            break;
                    default:
                            $archivo = 'img/generico.png';
                }
            }

            $iconos .= '<a href="" class="clickDelete" nameDate="'.$created_at.'" nameShort="'.$nombreOriginal.'" nameFile="'.$nombreFile.'" archivo="'.$archivo.'"><i class="text-danger far fa-trash-alt"></i></a>';

            $created_at = \Carbon\Carbon::parse($created_at)->format('d/m/Y h:i A');
            $salida .= '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
              <div class="thumbnail img-thumbnail">
                <div class="image view view-first">
                  <img style="width: 100%;height: 100%; display: block;" src="'.$archivo.'" alt="image" />
                  <div class="mask">
                    <p>'.$nombreOriginal.'</p>
                    <div class="tools tools-bottom">
                      '.$iconos.'
                    </div>
                  </div>
                </div>
                <div class="caption">
                    <p class="">'.$nombreOriginal.'<br><i class="far fa-calendar-alt"></i> '.$created_at.'</p>
                </div>
              </div>
            </div>';
        }

        return $salida;
        
    }    


    public function subirDocumento(Request $request)
    {

        $ruta     = '/documentos/miembro-'.$request->idMiembro.'/';
        $path     = public_path().$ruta;
        $files    = $request->file('file');
        $ext      = explode('/',$request->file('file')->getMimeType());
        $fileName = $files->getClientOriginalName();

        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        $files->move($path, $fileName);
        $myFile = date('mdYHis') . uniqid() . $request->fileName;
        rename($path.$fileName, $path.$myFile.'.'.$ext[1]);
        
       // DB::beginTransaction();   

        $fileStore = new \App\FileStore;
        $fileStore->nombreOriginal = $fileName;
        $fileStore->nombreFile = $myFile.'.'.$ext[1];
        $fileStore->ext = $ext[1];
        $fileStore->idMiembro =  $request->idMiembro;
        $fileStore->save();

        $totAdjuntos = 0;
        $ruta     = 'documentos/miembro-'.$request->idMiembro;
        $path     = public_path().'/'.$ruta;
        $miniaturasAdjuntas = '';
        if (file_exists($path)) {
            $files = File::files($path);            
            $totAdjuntos = count($files);
            $miniaturasAdjuntas = $this->archivosAdjuntos($files,$ruta,$request->idMiembro);
        }

       // DB::commit();
        //Storage::move($path.$fileName, $path.'usuario-'.$request->idSucursal);
        return response()->json( array('success' => true, 'mensaje'=> 'Foto cargada exitosamente','data' => $miniaturasAdjuntas) );
        
    }

   public function borrarDocumento(Request $request)
   {
        $archivo = $request->nameFile;
        $archi = explode('/',$archivo);            
        $s_o = PHP_OS;
        if ($s_o=="WINNT"){ 
            $archi = explode('/',$archivo);
        } 
        $pos = count($archi)-1;

        $archivo = 'documentos/miembro-'.$request->idMiembro.'//'.$archi[$pos];
       
        unlink($archivo);

        $totAdjuntos = 0;
        $ruta     = 'documentos/miembro-'.$request->idMiembro;
        $path     = public_path().'/'.$ruta;
        $miniaturasAdjuntas = '';
        if (file_exists($path)) {
            $files = File::files($path);            
            $totAdjuntos = count($files);
            $miniaturasAdjuntas = $this->archivosAdjuntos($files,$ruta,$request->idMiembro);
        }
        
        return response()->json( array('success' => true, 'mensaje'=> 'Foto eliminada exitosamente','data' => $miniaturasAdjuntas) );
     
    }
    
    public function agregarFamiliarExistente(Request $request)
   {
        $miembroPrincipal = \App\Miembros::find($request->idMiembro);
        $uuid = $miembroPrincipal->relacionFamilia ;

        $miembroToRelacion = \App\Miembros::find($request->miembroToRelacion);
        $uuidToRelacion = $miembroToRelacion->relacionFamilia ;

        if ($uuid === null || $uuid == ''){
            if( $uuidToRelacion != '' ){
                $uuid = $uuidToRelacion;
            }else{
                $uuid = Uuid::generate()->string;
            }
            
            $miembroPrincipal->relacionFamilia =  $uuid;
            $miembroPrincipal->save();
        }

        
        $miembroToRelacion->relacionFamilia = $uuid;
        $miembroToRelacion->parentesco = $request->parentesco;
        $miembroToRelacion->save();

        $grupoFamiliar = $this->grupoFamiliar($request->idMiembro,$uuid);

        return response()->json( array('success' => true, 'mensaje'=> '', 'grupoFamiliar' => $grupoFamiliar) );

   }

   public function eliminarParentesco(Request $request)
   {

        $miembroToRelacion = \App\Miembros::find($request->idMiembroParentesco);
        $uuid = $miembroToRelacion->relacionFamilia;
        $miembroToRelacion->relacionFamilia = '';
        $miembroToRelacion->parentesco = '';
        $miembroToRelacion->save();

        $grupoFamiliar = $this->grupoFamiliar($request->idMiembro,$uuid);

        return response()->json( array('success' => true, 'mensaje'=> '', 'grupoFamiliar' => $grupoFamiliar) );

   }

   public function asignarFoto()
   {
        $ruta     = '\img\fotos\\';
        $path     = public_path().$ruta;
        $archivos = File::files($path);   
      
        foreach ($archivos as $archivo => $value) {
            $fileName = explode('\\', $value);
            $name = $fileName[7];
            $idMiembro = explode('.', $name);
            
            DB::table('miembros')->where('id',$idMiembro)->update(array(
                                 'foto'=>$name,
            ));
            // $miembro->foto = $name;
            // $miembro->save();
        }
   }

   /**
     * [imprimirFicha description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function imprimirFicha(Request $request)
    {
        $miembro = \App\Miembros::join('iglesias','miembros.idIglesia','=','iglesias.id')->join('comunidades','miembros.comunidad','=','comunidades.id')->join('provincias','miembros.provincia','=','provincias.id')->join('paises','miembros.paisNacimiento','=','paises.id')->join('profesiones','miembros.profesion','=','profesiones.id')->join('status','miembros.status','=','status.id')->select('miembros.id', 'miembros.idIglesia', 'miembros.nombre', 'miembros.apellido1', 'miembros.apellido2', 'miembros.tipoDocumento','miembros.nroDocumento', 'miembros.direccion', 'miembros.codigoPostal', 'miembros.comunidad', 'miembros.provincia', 'miembros.telefonoFijo', 'miembros.telefonoMovil', 'miembros.email', 'miembros.fecNacimiento', 'miembros.lugarNacimiento', 'miembros.paisNacimiento', 'miembros.profesion', 'miembros.fecBautismo', 'miembros.iglesiaBautismo', 'miembros.fecCartaTraslado', 'miembros.iglesiaProcedencia', 'miembros.otrosDatos', 'miembros.foto', 'miembros.relacionFamilia', 'miembros.status', 'miembros.poblacion','iglesias.nombreCorto','comunidades.nombre AS comunidad','provincias.nombre AS provincia','paises.nombre AS pais','profesiones.nombre AS profesion','status.nombre AS statusNombre')->find($request->idMiembro);
        $edad = Carbon::parse($miembro->fecNacimiento)->age;
        $miembro['edad'] = $edad;     


        $data = array(
                        'miembro' => $miembro
                    );

        $pdf = PDF::loadView('miembros.ficha-miembro',$data);  
        $pdf->setPaper('A4', 'portrait');
        $file_to_save = base_path()."\public\pdf\\ficha-".$request->idMiembro.'.pdf';
        //save the pdf file on the server
        file_put_contents($file_to_save, $pdf->stream('invoice'));

        return "pdf/ficha-".$request->idMiembro.'.pdf';

    }


    public function relacionarGeneros()
    {
        return view('miembros.relacion-generos');
    }

    public function listarMiembrosGeneros()
    {

        $miembros = \App\Miembros::get();
        
        $dataSet = array (
            "sEcho"                 =>  0,
            "iTotalRecords"         =>  1,
            "iTotalDisplayRecords"  =>  1,
            "aaData"                =>  array () 
        );

        foreach($miembros as $miembro)
        {
            if ( $miembro->sexo == 'M' ){
                $genero = '<span class="badge badge-pill badge-primary"><i class="fas fa-mars"></i> Masculino </span>';
            }else if ( $miembro->sexo == 'F' ){ 
                $genero = '<span class="badge badge-pill badge-danger"><i class="fas fa-venus"></i> Femenino </span>';
            }else{
                $genero = '';
            }
            $dataSet['aaData'][] = array(  $miembro->id,
                                           $miembro->nombre,
                                           $miembro->apellido1,
                                           $miembro->apellido2,
                                           $genero,
                                           '<div class="icono-action">
                                                <a href="" data-accion="Masculino" idMiembro="'.$miembro->id.'" >
                                                    <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="cambiar genero a Masculino al miembro (<strong>'.$miembro->nombre.'</strong>)." class="fa-2x icono-action text-success fas fa-male">
                                                    </i>
                                                </a>
                                                <a href="" data-accion="Femenino" idMiembro="'.$miembro->id.'" >
                                                    <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Cambiar genero Femenino al 
                                                     miembro (<strong>'.$miembro->nombre.'</strong>)." class="fa-2x icono-action text-danger fas fa-female">
                                                    </i>
                                                </a>

                                            </div>'
                                        );
        }

        $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
    
        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
    }

    public function asignarGenero(Request $request)
    {
        $idMiembro = $request->idMiembro;
        $genero = $request->genero;
        $miembro = \App\Miembros::find($idMiembro);
        $miembro->sexo = substr($genero, 0,1);
        $miembro->save();
    }


    /**
     *      Eliminar Miembro
     */
    public function eliminarMiembro(Request $request)
    {

        try {

            DB::beginTransaction();   
            
            \App\Miembros::destroy($request->idMiembro);
            $ruta     = '/documentos/miembro-'.$request->idMiembro;
            $path     = public_path().$ruta;
            if (file_exists($path)) {
                 File::deleteDirectory($path);
            }

            $deletedRows = \App\FileStore::where('idMiembro', $request->idMiembro)->delete();

            $ministerios = \App\Ministerios::get();
            foreach ($ministerios as $ministerioFind) {
                $ministerio = \App\Ministerios::find($ministerioFind->id);
                $miembros = $ministerio->miembros;
                $newMiembros = str_replace($request->idMiembro.';', "", $miembros);
                $ministerio->miembros = $newMiembros;
                $ministerio->save();
            }
            
            $subMinisterios = \App\SubMinisterios::get();
            foreach ($subMinisterios as $ministerioFind) {
                $subMinisterio = \App\SubMinisterios::find($ministerioFind->id);
                $miembros = $subMinisterio->miembros;
                $newMiembros = str_replace($request->idMiembro.';', "", $miembros);
                $subMinisterio->miembros = $newMiembros;
                $subMinisterio->save();
            }
        
            DB::commit();

            return response()->json( array( 'success' => true, 
                                            'mensaje'=> 'Miembro borrado exitosamente',
                                        ) );

        } catch (Exception $e) {
            DB::rollback();
            return response()->json( array( 'success' => false, 
                                            'mensaje'=> 'Hubo un error intentando borrar la Ficha del Membro.',
                                        ) );
        }


    }


}

