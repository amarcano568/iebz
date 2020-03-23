<?php

namespace App\Http\Controllers;
use Usuarios;
use Empresas;
use Areas;
use SubAreas;
use Cargos;
use \Auth;
use Carbon\Carbon;
use \DB;
use Tablas;
use Categorias;
use SubCategorias;
use SolucionFrecuente;
use Personal;
use FileStore;
use Tickets;
use Ticket_Anulacion;
use Ticket_Programacion;
use Ticket_Reasignacion;
use ProblemaFrecuente;
use Ticket_Mensajes;
use Activos;
use File;
use App\Traits\funcGral;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;

class SoporteController extends Controller
{
    use funcGral;

    public function solicitudSoporte()
    {
        $conn = $this->BaseDatosEmpresa();
        $areas = \App\Areas::on($conn)->where('activo',"=",1)->get();
        $personal = \App\Personal::on($conn)->get();
        $usuarios = \App\Usuarios::where('BaseDatos',$conn)->where('status','=',1)->get();
        $prioridades = \App\Tablas::on($conn)->where('tipo','=','PRIO')->where('activo','=',1)->orderBy('idTabla', 'ASC')->get();

        $data = array(  
                        'areas' => $areas,
                        'prioridades' => $prioridades,
                        'usuarios' => $usuarios,
                        'personal' => $personal
                     );

        return view('solicitudSoporte.solicitudSoporte',$data);
    }

    /**
     *      Listar los tipo de Ticket
     */
    public function listarTipoTicket(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $conectar = DB::connection($conn);
        $tipTickets = $conectar->select("call listar_tipoTicketNew($request->idArea)");
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $tipTickets) );
    }

    /**
     *      Listar las categorías del Ticket
     */
    public function listarCategoriaTicket(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $conectar = DB::connection($conn);
        $categorias = $conectar->select("call listar_categoria($request->idArea,$request->tipoTicket)");
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $categorias) );
    }
    
    /**
     *      Listar las sub-categorías del Ticket
     */
    public function cargaSubCategoria(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $conectar = DB::connection($conn);
        $categorias = $conectar->select("call listar_subcategoria($request->IdCat,1)");
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $categorias) );
    }

    /**
     *      Listar las cargaSubProblemas (Aplicación) del Ticket
     */
    public function cargaSubProblemas(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $subCategorias = \App\SolucionFrecuente::on($conn)->join('subcategoria', 'solufrecuente.idSubCategoria', '=', 'subcategoria.idSubCategoria')->where('solufrecuente.idSubCategoria','=',$request->IdSubCat)->get();
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $subCategorias) );
    }

    /**
     *      Listar las Resumen Tickets Usuarios
     */
    public function listarResumenTicketUsuarios(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        //echo $conn;
        $conectar = DB::connection($conn);
        $idUser = Auth::id();
        $resumenTickets = $conectar->select("call listar_ResumenTicketsUsuarios($idUser)");
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $resumenTickets) );
    }

    public function upFilesSupport(Request $request){

        $conn = $this->BaseDatosEmpresa();
        $carpeta  = explode("@",Auth::user()->email);
        $ruta     = '/Empresas/'.$conn.'/AdjuntosTickets/'.$carpeta[0];
        $path     = public_path().$ruta;
        
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        $files    = $request->file('file');
        //$ext      = explode('/',$request->file('file')->getMimeType());
        
        $fileName = $files->getClientOriginalName();
        $ext = explode('.',$fileName);
        $myFile = date('mdYHis') . uniqid() . $request->fileName;
        $files->move($path, $myFile.'.'.$ext[1]);

        DB::beginTransaction();   

        $fileStore = new \App\FileStore;
        $fileStore->setConnection($conn);
        $fileStore->nombreOriginal = $fileName;
        $fileStore->nombreFile = $myFile.'.'.$ext[1];
        $fileStore->ext = $ext[1];
        $fileStore->nroTicketTmp = Auth::id();
        $fileStore->save();
        DB::commit();
        //Storage::move($path.$fileName, $path.'usuario-'.$request->idSucursal);
        return 'Archivo '.$fileName.' subido con Exito!';
        
    }

    public function enviarTicket(Request $request)
    {
        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa();
          
            $nroTicket = \App\Tickets::Guardar($request,$conn);
            DB::commit();
            
            $carpeta  = explode("@",Auth::user()->email);
            $ruta     = 'Empresas/'.$conn.'/AdjuntosTickets/'.$carpeta[0];
            $path     = public_path().$ruta;

            if (file_exists($ruta)) {
                rename($ruta,'Empresas/'.$conn.'/AdjuntosTickets/Ticket-'.$nroTicket);
            }
        
           \App\FileStore::on($conn)->where('nroTicketTmp', '=', Auth::id())->update(['nroTicket' => $nroTicket,'nroTicketTmp'=>0]);

            $ticketNro = $this->CrearGrafico('ticket.png',$nroTicket,300,140,30);

            return response()->json( array('success' => true, 'mensaje'=> 'Ticket enviado exitosamente..!','data' => $ticketNro,'ticketNro'=>$nroTicket) );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function CrearGrafico($ImagenOrigen,$nroTicket,$ancho,$alto,$size){
        $num_aleatorio = rand(1,100000);
        $NameTmp = trim($nroTicket).'_'.$num_aleatorio;
        $string = 'Ticket-'.$nroTicket;
        // Create the image
        $im = imagecreatetruecolor($ancho,$alto);
        imagesavealpha($im, true);
        $trans_background = imagecolorallocatealpha($im, 0, 0, 0, 127);
        // Create some colors
        $white = imagecolorallocate($im, 255, 255, 255);
        $grey = imagecolorallocate($im, 128, 128, 128);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 300, 140, $trans_background);
        imagefill($im, 0, 0, $trans_background);
        // The text to draw

        // Replace path by your own font path
        $font = public_path('fonts/helvetica-neue-lt-std-roman.ttf');

        // Add some shadow to the text
        imagettftext($im, $size, 0, 18, 91, $grey, $font, $string);

        // Add the text
        imagettftext($im, $size, 0, 17, 90, $black, $font, $string);

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($im,$NameTmp.'.png');
        imagedestroy($im);

        $WIDTH  = 496;
        $HEIGHT = 266;

        $dest_image = imagecreatetruecolor($WIDTH, $HEIGHT);
        //asegúrese de guardar la información de transparencia.
        imagesavealpha($dest_image, true);

        //llene la imagen con un fondo transparente.
        imagefill($dest_image, 0, 0, $trans_background);

        //tomar recursos de imagen de creación de los 2 pngs que queremos fusionar en la imagen de destino.
        $a = imagecreatefrompng(asset('img/'.$ImagenOrigen));
        $b = imagecreatefrompng($NameTmp.'.png');
                
        imagecopy($dest_image, $a, 0, 0, 0, 0, $WIDTH, $HEIGHT);
        imagecopy($dest_image, $b, 100, 60, 0, 0, 300, 140);
            
        imagepng($dest_image,$NameTmp.'.png');

        imagedestroy($a);
        imagedestroy($b);
        imagedestroy($dest_image);

        return $NameTmp.'.png';
    }
    
    
    public function listarTicketsUsuarios(Request $request)
    {
        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa();
            $idUser = Auth::id();
            $conectar = DB::connection($conn);
            $ticketsUsuarios = $conectar->select("call lista_ticketsUsuarios($idUser,$request->EstadoTickes)");
            
            $dataSet = array (
                "sEcho"                 =>  0,
                "iTotalRecords"         =>  1,
                "iTotalDisplayRecords"  =>  1,
                "aaData"                =>  array () 
            );

            

        foreach($ticketsUsuarios as $ticket)
        {
            $ruta     = '/Empresas/'.$conn.'/AdjuntosTickets/Ticket-'.$ticket->nroTicket;
            $path     = public_path().$ruta;

            $icono = '';
            if (file_exists($path)) {
                 $files = File::files($path);            
                if ( count($files) > 0 ){
                    $icono = '<i class="fas fa-paperclip"></i> ';
                }                
            }

            $icono .= $this->statusMsg($idUser,Auth::user()->email,$ticket->nroTicket,$conn);

            if ($ticket->prioridad == 1){
                $labelPrioridad = '<span class="badge badge-pill badge-warning">Baja</span>';
            }else if ($ticket->prioridad == 2){
                $labelPrioridad = '<span class="badge badge-pill badge-success">Normal</span>';
            }else if ($ticket->prioridad == 3){
                $labelPrioridad = '<span class="badge badge-pill badge-danger">Alta</span>';
            }

            $dataSet['aaData'][] = array(   $ticket->nroTicket,
                                            $icono,
                                            $ticket->solicitante,
                                            $ticket->ejecutor,
                                            $ticket->Titulo,
                                            $ticket->descCategoria,
                                            $ticket->desEstado,
                                            $ticket->fechaEstado,
                                            $labelPrioridad,
                                           '<div class="icono-action">
                                                <a href="" estado="'.$ticket->estado.'" data-accion="DetalleTicket" nroTicket="'.$ticket->nroTicket.'" >
                                                    <span class="badge badge-pill badge-primary"><i class="fab fa-searchengin"></i> Detalle</span>
                                                </a>
                                                
                                            </div>');
        }

        $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
    
        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
           

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }
    
    public function obtenerDetalleTicket(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $rol = strtoupper(Auth::user()->rol);
        $idUser = Auth::id();
        /*  Obtener información del ticket   */
        $conectar = DB::connection($conn);
        $detalleTicket = $conectar->select("call obtener_ticket($request->nroTicket)");        
        /*  Acciones del Ticket (Botones)   */   
        if ( is_null($request->rol) ){
            $rol2 = $rol;
        }else{
            $rol2 = $request->rol;    
        }          
        
        $accionesTicket = $conectar->select("call listar_accionesTicket($request->estadoTickecAnt,'$rol','$rol2')");
        /*  Historial de Eventos Ticket */        
        $historialTickets = $conectar->select("call historial_tickets($request->nroTicket)");
        $detalleTicket = $conectar->select("call obtener_ticket($request->nroTicket)");        
        $detTicket = new Collection($detalleTicket);

        foreach ($detTicket as $ticket) {
            $NivelEncuesta = $ticket->idNivelEncuesta;
            $fechaEncuesta = $ticket->fechaEncuesta;
            $comentarioEncuesta = $ticket->comentarioEncuesta;
        }

        $eventos = $this->generaEventos($historialTickets,$NivelEncuesta,$fechaEncuesta,$comentarioEncuesta);

        $totAdjuntos = 0;
        $ruta     = '/Empresas/'.$conn.'/AdjuntosTickets/Ticket-'.$request->nroTicket;
        $path     = public_path().$ruta;
        $miniaturasAdjuntas = '';
        if (file_exists($path)) {
            $files = File::files($path);            
            $totAdjuntos = count($files);
            $miniaturasAdjuntas = $this->archivosAdjuntos($files,$ruta,$request->nroTicket,$conn);
        }

        $totAdjuntosResultados = 0;
        $ruta     = '/Empresas/'.$conn.'/AdjuntosTickets/TicketResultado-'.$request->nroTicket;
        $path     = public_path().$ruta;
        $miniAdjResultados = '';
        if (file_exists($path)) {
            $files = File::files($path);            
            $totAdjuntosResultados = count($files);
            $miniAdjResultados = $this->archivosAdjuntos($files,$ruta,$request->nroTicket,$conn);
        }

       return response()->json( [
                                    'success' => true, 
                                    'mensaje'=> 'Detalle del Ticket Obtenido exitosamente..!', 
                                    'data' => $detalleTicket, 
                                    'accionesTicket' => $accionesTicket,
                                    'eventosTickets' => $eventos,
                                    'totalAdjuntos' => $totAdjuntos,
                                    'totalAdjuntosResultados' => $totAdjuntosResultados,
                                    'miniaturasAdjuntas' => $miniaturasAdjuntas,
                                    'minAdjResultados' => $miniAdjResultados
                                ] );
   
    }

    public function archivosAdjuntos($archivos,$ruta,$nroTicket,$conn)
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
            $infoFile = \App\FileStore::on($conn)->where('nroTicket','=',$nroTicket)->where('nombreFile','=',$archi[$pos])->take(1)->get();

            //dd($infoFile);
            foreach ($infoFile as $info) {
                $nombreOriginal = $info->nombreOriginal;
                $created_at = $info->created_at;
            }

            $iconos = '<a href="'.$archivo.'" download="'.$archi[$pos].'"><i class="fas fa-download"></i></a>';
            if (str_contains('PNG*JPG*JPEG*GIF*BMP*jpg', strtoupper($ext[1]))){
                $iconos .= '<a href="#" class="clickPicture" nameDate="'.$created_at.'" nameShort="'.$nombreOriginal.'" nameFile="'.$archivo.'"><i class="fas fa-search"></i></a>
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

            $created_at = \Carbon\Carbon::parse($created_at)->format('d/m/Y h:i A');
            $salida .= '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
              <div class="thumbnail img-thumbnail">
                <div class="image view view-first">
                  <img style="width: 100%;height: 100%; display: block;" src="'.$archivo.'" alt="image" />
                  <div class="mask">
                    <p>Adjunto</p>
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

    public function generaEventos($HistoriasTicket,$idNivelEncuesta,$fechaEncuesta,$comentarioEncuesta){
        $salida = '';
        $Encuesta = '';
        $estrellas = '';
         for ($i=1; $i <= $idNivelEncuesta ; $i++) { 
            $estrellas .= '<i class="text-success fas fa-star"></i>';
         }
            if ($idNivelEncuesta != '') {
                $Encuesta = '<li>
                            <div class="block">
                                <div class="tags" data-toggle="tooltip" data-placement="left" title="Evaluación de la encuesta.">
                                  <a href="" class="tag" >
                                    <span>Encuesta</span>
                                  </a>
                                </div>
                                <div class="block_content">
                                    <h2 class="title">
                                        <a>
                                        <i class="far fa-calendar-alt"></i> 
                                                '.$fechaEncuesta.'<br>
                                           '.$estrellas.'<br><article>'.$comentarioEncuesta.'</article>
                                        </a>

                                    </h2> 
                                </div>
                            </div>
                        </li>';
            }
        foreach ($HistoriasTicket as $Ticket ) {
            $Asignado = '';

            if ($Ticket->asignado != '') {
                $Asignado = '<hr><div class="block_content"><p style="margin-top: -1em;"  class="text-info text-center"><strong>Asignado</strong></p><h6 style="margin-top: -1em;" class="title"><a><i class="far fa-user"></i> '.$Ticket->asignado .'<br>'.$Ticket->cargoAsignado.'</a></h6></div>';
            }            

            $salida .= '<li>
                            <div class="block">
                                <div class="tags" data-toggle="tooltip" data-placement="left" title="'.$Ticket->preTabla.'">
                                  <a href="" class="tag" >
                                    <span>'.$Ticket->desEstado.'</span>
                                  </a>
                                </div>
                                <div class="block_content">
                                    <h2 class="title">
                                        <a>
                                            <i class="far fa-calendar-alt"></i> 
                                                '.$Ticket->fecharegistro.'
                                            <br>
                                            <p style="font-size: 13px;" class="text-info text-center">
                                                <strong>Responsable</strong>
                                            </p>
                                            <div style="margin-top: -1em">
                                                <i class="far fa-user"></i> 
                                                    '.$Ticket->responsable.'<br>
                                                <span style="font-size: 13px;">
                                                    '.$Ticket->cargoResponsable.'
                                                </span>
                                            </div>
                                        </a>
                                        <span style="font-size: 13px;">'.$Asignado.'</span>
                                    </h2> 
                                    <hr>                                  
                                    <p class="excerpt"><article>'.$Ticket->glosa.'</article>
                                    </p>
                                </div>
                            </div>
                        </li>';
        }
        if ($salida == ''){
            $salida = '<h5 class="text-center text-info">Sin eventos aún..!</h5>';
        }

        return $Encuesta.$salida;
        
    }

    public function anularTicket(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $rol = strtoupper(Auth::user()->rol);
        
        $save = \App\Ticket_Anulacion::Guardar($request,$conn);
            
        if(!$save){
            App::abort(500, 'Error');
        }

        return response()->json( array('success' => true, 'mensaje'=> 'Ticket anulado exitosamente..!') );
    }

    public function listarTicketsGestores(Request $request)
    {
        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa();
            $idUser = Auth::id();
            $idArea = Auth::user()->idArea;
            $rol = Auth::user()->rol;
            $grupoTicket = $request->EstadoTickes;
            $conectar = DB::connection($conn);
            $ticketsGestor = $conectar->select("call lista_ticketsGestores($idUser,$idArea,'$rol',$grupoTicket)");
            
            $dataSet = array (
                "sEcho"                 =>  0,
                "iTotalRecords"         =>  1,
                "iTotalDisplayRecords"  =>  1,
                "aaData"                =>  array () 
            );

            

        foreach($ticketsGestor as $ticket)
        {
            $ruta     = '/Empresas/'.$conn.'/AdjuntosTickets/Ticket-'.$ticket->nroTicket;
            $path     = public_path().$ruta;

            $icono = '';
            if (file_exists($path)) {
                 $files = File::files($path);            
                if ( count($files) > 0 ){
                    $icono = '<i class="fas fa-paperclip"></i> ';
                }                
            }

            $icono .= $this->statusMsg($idUser,Auth::user()->email,$ticket->nroTicket,$conn);  

            if ($ticket->prioridad == 1){
                $labelPrioridad = '<span class="badge badge-pill badge-warning">Baja</span>';
            }else if ($ticket->prioridad == 2){
                $labelPrioridad = '<span class="badge badge-pill badge-success">Normal</span>';
            }else if ($ticket->prioridad == 3){
                $labelPrioridad = '<span class="badge badge-pill badge-danger">Alta</span>';
            }

            $dataSet['aaData'][] = array(   $ticket->nroTicket,
                                            $icono,
                                            $ticket->solicitante,
                                            $ticket->ejecutor,
                                            $ticket->Titulo,
                                            $ticket->descCategoria,
                                            $ticket->desEstado,
                                            $ticket->fechaEstado,
                                            $labelPrioridad,
                                           '<div class="icono-action">
                                                <a href="" estado="'.$ticket->estado.'" data-accion="DetalleTicket" nroTicket="'.$ticket->nroTicket.'" >
                                                    <span class="badge badge-pill badge-primary"><i class="fab fa-searchengin"></i> Detalle</span>
                                                </a>
                                                
                                            </div>');
        }

        $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
    
        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
           

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function statusMsg($idUsuTicket,$CtaUsuario,$nroTicket,$conn){        

        $msgLeido   = \App\Ticket_Mensajes::on($conn)->where('nroTicket',$nroTicket)->where('ctaUsu', '<>',$CtaUsuario)->where('leido',1)->count();
        $msgSinLeer = \App\Ticket_Mensajes::on($conn)->where('nroTicket',$nroTicket)->where('ctaUsu', '<>',$CtaUsuario)->where(function($q){
                        $q->where('leido',0)
                        ->orWhere('leido',null);})->count();
        
        if ( $msgLeido == 0 && $msgSinLeer == 0 ){
            $icono = '';
        }else if( $msgSinLeer >= 1 ){
            $icono = '<a class="verChatTicket" href="" nroTicket="'.$nroTicket.'"><i class="far fa-envelope"></i></a>';
        }else if( $msgLeido >= 1 ){
            $icono = '<a class="verChatTicket" href="" nroTicket="'.$nroTicket.'"><i class="fas fa-envelope-open-text"></i></a>';
        }

        return $icono;


    }


    // Route::get('admin-Soporte', function () {
    //
    // });
    public function adminSoporte()
    {
        $conn = $this->BaseDatosEmpresa();
        $idArea = Auth::user()->idArea;
        $tipoAtencion = \App\Tablas::on($conn)->select('idTabla', 'desTabla')->where('tipo','TATE')->where('activo', 1)->get();
        $areas = \App\Areas::on($conn)->get();

        $equipos = \App\Activos::on($conn)->get();
        $agentes = \App\Usuarios::where(function($q){
                   $q->where('rol', 'AGE')
                     ->orWhere('rol','ADM');
                 })->where('idArea',$idArea)->where('status',1)->where('BaseDatos',$conn)->get();
        
        $data = array(  
                        'agentes' => $agentes,
                        'equipos' => $equipos,
                        'areas' => $areas,
                        'tipoAtencion' => $tipoAtencion
                     );

       return view('adminSoporte.adminSoporte',$data);
    }

    /**
     *      Listar las Resumen Tickets Usuarios
     */
    public function listarResumenTicket_gestores(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $conectar = DB::connection($conn);
        $idUser = Auth::id();
        $idArea = Auth::user()->idArea;
        $rol = Auth::user()->rol;
        $resumenTicketsGestor = $conectar->select("call lista_ticketsGestoresResumen($idUser,$idArea,'$rol')");
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $resumenTicketsGestor) );
    }

    public function asignarTicket(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $rol = strtoupper(Auth::user()->rol);
        
        $save = \App\Ticket_Programacion::Guardar($request,$conn);
            
        if(!$save){
            App::abort(500, 'Error');
        }

        return response()->json( array('success' => true, 'mensaje'=> 'Ticket asignado exitosamente..!') );
    }

    public function iniciarTicket(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $ctaUsu = Auth::user()->email;

        $nroTicket = $request->nroTicket;
        $idUser = Auth::id();
        $tipoAtencion = 0;

        $ticket = \App\Tickets::on($conn)->find($nroTicket);

        $categoria = $ticket->idCategoria;
        $subCategoria = $ticket->idSubCategoria;
        $soluFrecuente = 0;
        $comentario = '';

        $estadoTickecAnt = $request->estadoTickecAnt;
        $estado = $request->estado;

        $conectar = DB::connection($conn);
        $tipTickets = $conectar->select("call atender_ticket( $nroTicket,$idUser,$tipoAtencion,$categoria,$subCategoria,$soluFrecuente,'$comentario',$estado,$estadoTickecAnt,$idUser,'$ctaUsu',0)");
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $tipTickets) );
    }


    public function pausarTicket(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $ctaUsu = Auth::user()->email;

        $nroTicket = $request->nroTicket;
        $idUser = Auth::id();
        $tipoAtencion = 0;

        $ticket = \App\Tickets::on($conn)->find($nroTicket);

        $categoria = $ticket->idCategoria;
        $subCategoria = $ticket->idSubCategoria;
        $soluFrecuente = 0;
        $comentario = $ticket->Comentario;

        $estadoTickecAnt = $request->estadoTickecAnt;
        $estado = $request->estado;

        $conectar = DB::connection($conn);
        $tipTickets = $conectar->select("call atender_ticket( $nroTicket,$idUser,$tipoAtencion,$categoria,$subCategoria,$soluFrecuente,'$comentario',$estado,$estadoTickecAnt,$idUser,'$ctaUsu',0)");
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $tipTickets) );
    }
//        

    public function pbrFrecuente(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $ctaUsu = Auth::user()->email;

        $nroTicket = $request->nroTicket;
        $ticket = \App\Tickets::on($conn)->find($nroTicket);
        $categoria = $ticket->idCategoria;
        $subCategoria = $ticket->idSubCategoria;

        $pbrFrecuente = \App\ProblemaFrecuente::on($conn)->where('idSubCategoria',$subCategoria)->get();      
        
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $pbrFrecuente) );
    }

    public function terminarTicket(Request $request)
    {

        $conn = $this->BaseDatosEmpresa();
        $ctaUsu = Auth::user()->email;

        $nroTicket = $request->nroTicket;
        $idUser = Auth::id();
        $tipoAtencion = $request->TipoAtencion;

        $ticket = \App\Tickets::on($conn)->find($nroTicket);

        $categoria = $ticket->idCategoria;
        $subCategoria = $ticket->idSubCategoria;

        $soluFrecuente = $request->SolFrecuente;
        $comentario = $request->Comentario;
        $Equipo = $request->Equipo == '' ? 0 : $request->Equipo;
        $estadoTickecAnt = $request->estadoTickecAnt;
        $estado = $request->estado;


        $conectar = DB::connection($conn);
        $tipTickets = $conectar->select("call atender_ticket( $nroTicket,$idUser,$tipoAtencion,$categoria,$subCategoria,$soluFrecuente,'$comentario',$estado,$estadoTickecAnt,$idUser,'$ctaUsu',$Equipo)");
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $tipTickets) );
    }

    public function newSolution(Request $request)
    {

        $conn = $this->BaseDatosEmpresa();
        $nroTicket = $request->nroTicket;
        $ticket = \App\Tickets::on($conn)->find($nroTicket);
        $categoria = $ticket->idCategoria;
        $subCategoria = $ticket->idSubCategoria;
        
        $id = \App\ProblemaFrecuente::Guardar($request,$conn,$subCategoria);

        return response()->json( array('success' => true, 'mensaje'=> 'Nueva solución guardada exitosamente..!','data' => $id) );
    }

    public function registrarNuevoActivo(Request $request)
    {

        $conn = $this->BaseDatosEmpresa();
        
        $id = \App\Activos::Guardar($request,$conn);
        $data = \App\Activos::on($conn)->get();

        return response()->json( array( 
                                    'success' => true, 
                                    'mensaje' => 'Nuevo activo registrado exitosamente..!',
                                    'data' => $data,
                                    'id' => $id
                                    ) 
                                );
    }

    public function upFilesSupport2(Request $request){

        $nroTicket = $request->nroTicket;
        $tipoAdjunto = $request->tipoAdjunto;
        $conn = $this->BaseDatosEmpresa();
        $tipo = $request->tipoAdjunto != '' ? 'TicketResultado-' : 'Ticket-';
        $ruta     = '/Empresas/'.$conn.'/AdjuntosTickets/'.$tipo.$nroTicket;
        $path     = public_path().$ruta;
        
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        $files    = $request->file('file');
        //dd($files);
        //$ext      = explode('/',$request->file('file')->getMimeType());
        $fileName = $files->getClientOriginalName();
        $ext = explode('.',$fileName);

        $myFile = date('mdYHis') . uniqid() . $request->fileName;
        $files->move($path, $myFile.'.'.$ext[1]);

        DB::beginTransaction();   

        $fileStore = new \App\FileStore;
        $fileStore->setConnection($conn);
        $fileStore->nombreOriginal = $fileName;
        $fileStore->nombreFile = $myFile.'.'.$ext[1];
        $fileStore->ext = $ext[1];
        $fileStore->nroTicket =  $nroTicket;
        $fileStore->save();

        $totAdjuntos = 0;
        $ruta     = '/Empresas/'.$conn.'/AdjuntosTickets/'.$tipo.$nroTicket;
        $path     = public_path().$ruta;
        $miniaturasAdjuntas = '';
        if (file_exists($path)) {
            $files = File::files($path);            
            $totAdjuntos = count($files);
            $miniaturasAdjuntas = $this->archivosAdjuntos($files,$ruta,$request->nroTicket,$conn);
        }

        DB::commit();

        //Storage::move($path.$fileName, $path.'usuario-'.$request->idSucursal);
        return $miniaturasAdjuntas;
        
    }

    public function cambiarAreaTicket(Request $request)
    {

        $conn = $this->BaseDatosEmpresa();
        $nroTicket = $request->nroTicket;
        $ticket = \App\Tickets::on($conn)->find($nroTicket);
        
        $id = \App\Ticket_Reasignacion::cambiarAreaTicket($request,$conn,$ticket);
        
        return response()->json( array( 
                                    'success' => true, 
                                    'mensaje' => 'Ticket reasignado exitosamente..!',
                                    ) 
                                );
    }

    public function reaperturaTicket(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $ctaUsu = Auth::user()->email;

        $nroTicket = $request->nroTicket;
        $idUser = Auth::id();
        $tipoAtencion = 0;

        $ticket = \App\Tickets::on($conn)->find($nroTicket);

        $categoria = $ticket->idCategoria;
        $subCategoria = $ticket->idSubCategoria;
        $soluFrecuente = 0;
        $comentario = $ticket->DescripcionReapertura;

        $estadoTickecAnt = $request->estadoTickecAnt;
        $estado = $request->estado;


        $conectar = DB::connection($conn);
        $tipTickets = $conectar->select("call atender_ticket( $nroTicket,$idUser,$tipoAtencion,$categoria,$subCategoria,$soluFrecuente,'$comentario',$estado,$estadoTickecAnt,$idUser,'$ctaUsu',0)");
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $tipTickets) );
    }

    public function encuestaTicket(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $conectar            = DB::connection($conn);
        $nroTicket           =$request->nroTicket;
        $estrellas           =$request->estrellas;
        $DescEncuesta =$request->DescripcionEncuesta;
        $idUser              = Auth::id();
        $ctaUser             = Auth::user()->email;
        
        $tipTickets = $conectar->select("call registrarEncuesta_ticket($nroTicket,$estrellas,'$DescEncuesta',$idUser,'$ctaUser')");
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $tipTickets) );
    }

    public function enviarMensajeTicket(Request $request)
    {
        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa();
          
            \App\Ticket_Mensajes::Guardar($request,$conn);
            DB::commit();
            
            return response()->json( array('success' => true, 'mensaje'=> 'Ticket enviado exitosamente..!') );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function listarMensajeTicket(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $ctaUser = Auth::user()->email;
        $conectar = DB::connection($conn);
        $listarMsg = $conectar->select("call listar_ticketMensajes($request->nroTicket,'$ctaUser')");
        $listarMsg = $this->MsgDesign($listarMsg);
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $listarMsg) );
    }

    function MsgDesign($listarMsg)
    {
        $salida = '';
        foreach ($listarMsg as $Msg ) {
            if ($Msg->rol == 'Administrador' or $Msg->rol == 'Agente' ){
                $avatar='<span class="fa-stack fa-2x">
                          <i class="text-info fas fa-circle fa-stack-2x"></i>
                          <i class="fas fa-user-tie fa-stack-1x fa-inverse"></i>
                        </span>';
            }else{
                $avatar='<span class="fa-stack fa-2x">
                          <i class="text-warning fas fa-circle fa-stack-2x"></i>
                          <i class="far fa-user fa-stack-1x fa-inverse"></i>
                        </span>';
            }
            $salida .= '<div class="row">
                    <div class="col-lg-2 col-md-1 col-sm-1 col-xs-1">
                      '.$avatar.'
                    </div>
                    <div class="col-lg-10 col-md-11 col-sm-11 col-xs-11">
                      <div class="chat-body clearfix">
                        <div class="header">
                          <strong class="primary-font">'.$Msg->nomusu.' <span class="badge badge-pill badge-success">'.$Msg->rol.'</span>'.'</strong>
                          <small style="float: right;" class="text-muted">
                          <span class="glyphicon glyphicon-time"></span>'.$Msg->fechaMsg.'
                          </small>
                        </div>
                        <p>
                          '.$Msg->mensaje.'
                        </p>
                      </div>
                    </div>
                  </div><hr>';
        }

        return $salida;
    }

}
