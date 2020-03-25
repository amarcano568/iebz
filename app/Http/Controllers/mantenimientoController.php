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
use Correos;
use File;
use Illuminate\Http\Request;
use App\Traits\funcGral;

class mantenimientoController extends Controller
{
    use funcGral;

    public function verificaLicencia()
    {
        $conn = $this->BaseDatosEmpresa();
        $usuarios = \App\Usuarios::where('BaseDatos','=',$conn)->get();        

    	$idEmpresa = Auth::user()->id_Empresa;
    	$empresa = \App\Empresas::find($idEmpresa);
        $TotUsers = $usuarios->count();

        $valida = $TotUsers >= $empresa->usuariosPermitidos ? false : true;
        $mensaje = $TotUsers >= $empresa->usuariosPermitidos ? 'No puede agragar más usuarios, llego al limite de su licencia.' : 'Permitido agregar usuario';
        
        return response()->json( array('success' => $valida, 'mensaje'=> $mensaje) );
    }

    public function loadUsuarios()
    {
        $cargos = \App\Cargos::all();
        $data = array (
            'cargos' => $cargos
        );
        return view('mantenimiento.usuarios',$data);
    }

    /**
     *      Lista Usuarios del Sistema.
     */
    public function cargaUsuarios()
    {
        
        $usuarios = \App\Usuarios::get();
        
        $dataSet = array (
            "sEcho"                 =>  0,
            "iTotalRecords"         =>  1,
            "iTotalDisplayRecords"  =>  1,
            "aaData"                =>  array () 
        );

        foreach($usuarios as $usuario)
        {
            if ( $usuario->status == 1){
                $status = '<span class="badge badge-pill badge-success"><i class="fas fa-check"></i> Activo</span>';
                $candado  = '<i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Bloquear Usuario (<strong>'.$usuario->name.'</strong>)."  class="text-success fas fa-lock-open"></i>';
            }else{
                $status = '<span class="badge badge-pill badge-danger"><i class="fas fa-ban"></i> Inactivo</span>';
                $candado  = '<i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Desbloquear Usuario (<strong>'.$usuario->name.'</strong>)."  class="text-danger fas fa-lock"></i>';
            }

            
            $dataSet['aaData'][] = array(  $usuario->id,
                                           $usuario->name,
                                           $usuario->lastName,
                                           $usuario->email,
                                           $usuario->rol,
                                           $status,
                                           '<div class="icono-action">
                                                <a href="" data-accion="editarUsuario" idUsuario="'.$usuario->id.'" >
                                                    <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Editar Usuario (<strong>'.$usuario->name.'</strong>)." class="icono-action text-primary far fa-edit">
                                                    </i>
                                                </a>
                                                <a href="" data-accion="bloquearUsuario" idUsuario="'.$usuario->id.'" status="'.$usuario->status.'">
                                                    '.$candado.'
                                                </a>
                                                <a data-accion="editarRole" urlRole="users/'. $usuario->id.'/edit" href="users/'. $usuario->id.'/edit">          
                                                    <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Editar Rol (<strong>'.$usuario->name.'</strong>)." class="icono-action text-info fab fa-r-project">
                                                    </i>
                                                </a>
                                            </div>');
        }

        $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
    
        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
    }


    /**
     * [registrarMotivo description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function registrarUsuario(Request $request)
    {

        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa();
            $idEmpresa = Auth::user()->id_Empresa;   
            if ( is_null($request->idUsuario) ){  

	            $rules = [
	                        'correo' => ['required', 'email', 'unique:users,email' ],
	                        // 'Username' => ['required', 'unique:users,userName' ],
	                    ];

	            $customMessages =   [
	                                    'correo.unique' => '<i class="fas fa-exclamation-triangle"></i> Existe otro Usuario con ese <strong>Correo</strong>',
	                                    // 'Username.unique'  => '<i class="fas fa-exclamation-triangle"></i> Existe otro Usuario con ese <strong>UserName</strong>',
	                                ];                                

	            $v =  $this->validate($request, $rules, $customMessages);

	        }    
            $save = \App\Usuarios::Guardar($request,$conn,$idEmpresa);
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
    
    public function buscarUsuario(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $usuarios = \App\Usuarios::find($request->idUsuario);
        
        $data = array(  
                        'data' => $usuarios
                     );

       return response()->json( array('success' => true, 'mensaje'=> '', 'data' => $usuarios) );
    }

    public function bloquearUsuario(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $usuario = \App\Usuarios::find($request->idUsuario);
        
        $usuario->status = $usuario->status == 1 ? 0 : 1;
        if ( $usuario->save() ){
            $success = true;
            $mensaje = 'Status cambiado exitosamente';
        }else{
            $success = false;
            $mensaje = 'El Status no pudo ser cambiado.';
        }

        
       return response()->json( array('success' => $success, 'mensaje'=> $mensaje, 'data' => '') );
    }

    public function interactuaCayro(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $usuario = \App\Usuarios::find($request->idUsuario);
        
        if ( $usuario->cayro == 1 ){
            $usuario->cayro = 0;
            $usuario->BaseDatosAux = '';
        }else{
            $usuario->cayro = 1;
            $usuario->BaseDatosAux = $usuario->BaseDatos;
        }
        
        if ( $usuario->save() ){
            $success = true;
            $mensaje = 'Interacción Cayro cambiado exitosamente';
        }else{
            $success = false;
            $mensaje = 'La Interacción Cayro no pudo ser cambiada.';
        }

        
       return response()->json( array('success' => $success, 'mensaje'=> $mensaje, 'data' => '') );
    }

    public function getSubAreas(Request $request){
       
       $conn = $this->BaseDatosEmpresa();
    
       $iArea = $request->iArea;

       $sAreas = \App\SubAreas::on($conn)->where([['activo',1],['idArea',$iArea]])->get();


        return response()->json( array('success' => true, 'mensaje'=> '', 'data' => $sAreas) );

    }
    /**
     * 
     */
    
    public function loadEmpresa()
    {
        $conn = $this->BaseDatosEmpresa();
        
        $idEmpresa = Auth::user()->id_Empresa;
        $empresa = \App\Empresas::find($idEmpresa);
        
        $data = array(  
                        'empresa' => $empresa
                     );

        return view('mantenimiento.empresa',$data);
    }

    public function configCorreo()
    {
        $conn = $this->BaseDatosEmpresa();
        
        $idEmpresa = Auth::user()->id_Empresa;
        $correos = \App\Correos::on($conn)->find(1);
        
        $data = array(  
                        'correo' => $correos
                     );

        return view('mantenimiento.correos',$data);
    }

    public function actualizaCorreo(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        
        $correo = \App\Correos::on($conn)->find(1);
        
        // se valido para agregar nuevo correo 
        
        if(is_null($correo)){

            $correo     = new \App\Correos;
            $correo     = $correo->setConnection($conn);
            $correo->id = 1; 
         }

        $correo->nombre     = $request->nombreEmail;
        $correo->smtp       = $request->smtpEmail;
        $correo->port       = $request->portEmail;
        $correo->encryption = $request->encryptionEmail;
        $correo->correo       = $request->correoEmail;
        $correo->password = $request->passwordEmail;
       
        if ( $correo->save() ){
            $success = true;
            $mensaje = 'Información del Correo actualizada exitosamente';
        }else{
            $success = false;
            $mensaje = 'Información del Correo no pudo ser actualizada.';
        }
        
       return response()->json( array('success' => $success, 'mensaje'=> $mensaje, 'data' => '') );
    }

    public function actualizaEmpresa(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $empresa = \App\Empresas::find(1);
        
        $empresa->NombreEmpresa = $request->empresa;
        $empresa->ruc = $request->ruc;
        $empresa->telefono1 = $request->telefono1;
        $empresa->telefono2 = $request->telefono2;
        $empresa->direccion = $request->direccion;
        $empresa->correo = $request->correo;
        $empresa->representante = $request->representante;

        if ( $empresa->save() ){
            $success = true;
            $mensaje = 'Información de la Empresa actualizada exitosamente';
        }else{
            $success = false;
            $mensaje = 'Información de la Empresa no pudo ser actualizada.';
        }
        
       return response()->json( array('success' => $success, 'mensaje'=> $mensaje, 'data' => '') );
    }
 /**
     *      Mantenimiento de Tickets
     */
    public function loadMantTicket()
    {

        return view('mantenimiento.tickets');
    }

    public function cargaTiposTickets(){
        $conn = $this->BaseDatosEmpresa();
        $tipTickets = \App\Tablas::on($conn)->where('tipo','=','TPTK')->get();

        $dataSet = array (
            "sEcho"                 =>  0,
            "iTotalRecords"         =>  1,
            "iTotalDisplayRecords"  =>  1,
            "aaData"                =>  array () 
        );
        $contador = 1;
        foreach($tipTickets as $tipTicket)
        {

            $tipo       = $tipTicket->tipo;
            $idTabla    = $tipTicket->idTabla;  
            $desTabla   = $tipTicket->desTabla;
            $activo     = $tipTicket->activo;
            $valString3 = $tipTicket->valString3;
            $interno    = $tipTicket->interno;
            $externo    = $tipTicket->externo;

            $botones    = '<div class="icono-action">
            <td>
            <a idTabla="'.$idTabla.'" desTabla="'.$desTabla.'" interno="'.$interno.'" externo="'.$externo.'" data-accion="EditarTipo"  href="" status="'.$activo.'" areas="'.$valString3.'">
            <i  data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Editar Tipo Ticket (<strong>'.$desTabla.'</strong>)."  class="far fa-edit"></i>
            </a>
            <a id="'.$idTabla.$tipo.'" style="display: none;">
            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
            </a>
            </td>
            </div>';

            if ($activo == '1'){
                $ActDes = '<span class="badge badge-pill badge-success">Activo</span>';                
            }else{
                $ActDes = '<span class="badge badge-pill badge-danger">Inactivo</span>';
            }

            $dataSet['aaData'][] = array(   $tipo,
                $idTabla,
                $desTabla,
                $ActDes,
                $this->buscarArea($valString3,$conn),
                $botones
            );  
            $contador++;            

        }

        $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
        
        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
        
    }

    public function buscarArea($valString3,$conn){

        $areas = explode('|', $valString3);
        $salida = '';
        foreach ($areas as $area) {

            if ( $area != "" || $area != null ){
                $areas = \App\Areas::on($conn)->find($area);
                $idArea     =   $areas->idArea;
                $descArea   =   $areas->descArea;
                $activo     =   $areas->activo;

                if ($activo == '1'){
                    $ActDes = '<span class="badge badge-success"><i class="fa fa-check-circle" aria-hidden="true"></i> Activo</span>';
                    
                }else{
                    $ActDes = '<span class="badge badge-danger"><i class="fa fa-ban" aria-hidden="true"></i> Inactivo</span>';
                }

                $salida .=  '<tr>
                <td>'.$idArea.'</td>
                <td>'.$descArea.'</td>  
                <td>'.$ActDes.'</td>
                </tr>';     
            }

        }
        $salida = ( $salida == '' )? '<tr>
                                        <td colspan="3" style="text-align: center">Este tipo no tiene Áreas asignadas</td>
                                      </tr>' : $salida;
        return '<table id="TableListadoSubCategoria" class="table table-striped table-bordered table-hover table-condensed">
        <thead>
        <tr>
        <th nowrap style="width:10%;text-align: center">Id</th>
        <th style="width:60%;text-align: center;">Descripción</th>
        <th style="width:15%;text-align: center;">Status</th>
        </tr>
        </thead>
        <tbody id="listaSubCategoriaOK">'.$salida.'</tbody>
        </table>';

    }

    public function llenarChosenAreas(Request $request){
        $conn = $this->BaseDatosEmpresa();
        $AREAS = $request->areas;
        $arreglo = array();
        $i = 0; 

        $areas = \App\Areas::on($conn)->get();

        foreach($areas as $area)
        {

            $buscar = trim($area->idArea);
            $resultado = strpos($AREAS, '|'.$buscar.'|');
            $Selecciona = ($resultado !== FALSE) ? 'selected="true"' : '';   

            $arreglo[$i] =  array('opcion' => $area->idArea, 'valor' => $area->descArea, 'seleccionable' => $Selecciona);
            $i++;
            
        }
      
        return json_encode($arreglo);
    }

    public function registrarMantTicket(Request $request){

        $conn = $this->BaseDatosEmpresa();
        $areasAll='|';
        foreach ($request->chosenAreas as $area) {
            $areasAll .= $area.'|';
        }
    
        $save = \App\Tablas::Guardar($request,$conn,$areasAll);
        if(!$save){
            App::abort(500, 'Error');
         }

        return response()->json( array('success' => true, 'mensaje'=> 'Tipo de Ticket guardado exitosamente..!') );
    }

    /**
     *      Mantenimiento de Categorias y subcategorias.
     */
    public function loadMantCategorias()
    {
        //SELECT idTabla,desTabla FROM Helpdesk..Tablas where Tipo='TPTK' order by desTabla"

        $conn = $this->BaseDatosEmpresa();
        $areas = \App\Areas::on($conn)->where("activo","=",1)->orderBy('descArea', 'ASC')->get();
        $tipTickets = \App\Tablas::on($conn)->where('Tipo','=','TPTK')->where("activo","=",1)->orderBy('desTabla', 'ASC')->get();

        $data = array(  
                        'areas' => $areas,
                        'tipTickets' => $tipTickets
                     );

        return view('mantenimiento.categorias',$data);
    }

    public function listarCategoria(){

        $conn = $this->BaseDatosEmpresa();
        $conectar = DB::connection($conn);
        $categorias = $conectar->select("call listar_categoria_mant(0)");

        $dataSet = array (
            "sEcho"                 =>  0,
            "iTotalRecords"         =>  1,
            "iTotalDisplayRecords"  =>  1,
            "aaData"                =>  array () 
        );
        $contador = 1;
        foreach ($categorias as $categoria) {

            $idCategoria   = $categoria->idCategoria;
            $descCategoria = $categoria->descCategoria; 
            $idArea        = $categoria->idArea;
            $DesArea       = $categoria->descArea;
            $Tipo          = $categoria->Tipo;
            $desTabla      = $categoria->desTabla;
            $activo        = $categoria->activo;
            $idTabla       = $categoria->idTabla;
                
            if ($activo == '1'){
                $ActDes = '<span class="badge badge-pill badge-success">Activo</span>';
                $etiqueta = '<a data-accion="inactivar" class="blue" href="">
                                <i class="ace-icon fa fa-unlock bigger-130" title="Inactivar Categoría"></i>
                            </a>';
            }else{
                $ActDes = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                $etiqueta = '<a data-accion="activar" class="purple" href="">
                                <i class="ace-icon fa fa-lock bigger-130" title="Activar Categoría"></i>
                            </a>';
            }

            $botones    = '<div class="action-buttons">
                                <td>
                                    <a idTabla="'.$idTabla.'" tipTicket="'.$Tipo.'" idArea="'.$idArea.'" statusCat="'.$activo.'" data-accion="EditarCategoria" class="green" href="">
                                        <i class="far fa-edit" title="Editar Detalle de la Categoría"></i>                                        
                                    </a>
                                    '.$etiqueta.'
                                    <a data-accion="AgregarSubCategoria" class="red" href="">
                                        <i class="ace-icon fa fa-plus bigger-120" title="Agregar SubCategoría"></i>
                                    </a>
                                    
                                    <a id="'.$idCategoria.'" class="red" style="display: none;">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    </a>
                                </td>
                            </div>';


            $dataSet['aaData'][] = array(   $idCategoria,
                                            $descCategoria,
                                            $DesArea,
                                            $desTabla,
                                            $ActDes,
                                            $this->DetalleSubCategoria($conn,$idCategoria),
                                            $this->Aplicacion($conn,$idCategoria,$descCategoria),
                                            $botones
                                        );  
            $contador++;            
            
        }       

        $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
    
        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
    
    }

    function Aplicacion($conn,$idCategoria,$descCategoria){
        return $idCategoria;
    }

    function DetalleSubCategoria($conn,$Categoria){

        return '<div style="height: 150px; overflow-y: scroll;"><table id="TableListadoSubCategoria" class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th nowrap style="width:10%;text-align: center;display: none;">Id Categoría</th>
                        <th style="width:10%;text-align: center;">Id SubCategoría</th>
                        <th style="width:55%;text-align: center;">Nombre de la SubCategoría</th>
                        <th style="width:25%;text-align: center;">Status</th>
                        <th style="width:10%;text-align: center;">Opciones</th>             
                    </tr>
                </thead>
                <tbody id="listaSubCategoriaOK">'.$this->consultaSubCategoria($conn,$Categoria).'</tbody>
        </table></div>';

    }

    function consultaSubCategoria($conn,$Categoria){
        $subCategorias = \App\SubCategorias::on($conn)->select('subcategoria.idCategoria','subcategoria.idSubCategoria','subcategoria.desSubCategoria','subcategoria.activo')->join('categoria', 'categoria.idCategoria', '=', 'subcategoria.idCategoria')->where('subcategoria.idCategoria','=',$Categoria)->get();
        $salida =  ''; 

        
    foreach ($subCategorias as $subCategoria) {
                                                  
        $idCategoria        = $subCategoria->idCategoria;
        $idSubCategoria     = $subCategoria->idSubCategoria;
        $descSubCategoria   = $subCategoria->desSubCategoria;    
        $activo             = $subCategoria->activo;

        if (trim($subCategoria->activo) == '1'){
            $ActDes = '<span class="badge badge-success">Activo</span>';
            $etiqueta = '<a status="'.$activo.'" id-subCat="'.$idSubCategoria.'" nomCat="'.$descSubCategoria.'"   id-Cat="'.$idCategoria.'"  data-accion="inactivarSubCategoria" class="blue" href="">
                 <i class="ace-icon fa fa-unlock bigger-130" title="Inactivar SubCategoría"></i>
                </a>';
            $etiStatus = 'Activo';
        }else{
            $ActDes = '<span class="badge badge-danger">Inactivo</span>';
            $etiqueta = '<a status="'.$activo.'" id-subCat="'.$idSubCategoria.'" nomCat="'.$descSubCategoria.'"   id-Cat="'.$idCategoria.'"  data-accion="activarSubCategoria" class="purple" href="">
                <i class="ace-icon fa fa-lock bigger-130" title="Activar SubCategoría"></i>
                </a>';
            $etiStatus = 'Inactivo';
        }
                        
        $salida .=  '<tr>
                        <td style="display: none;">'.$idCategoria.'</td>
                        <td >'.$idSubCategoria.'</td>
                        <td>'.$descSubCategoria.'</td>  
                        <td style="text-align: center;">'.$ActDes.'</td>                        
                        <div class="action-buttons">
                            <td>
                               <a id-subCat="'.$idSubCategoria.'" id-Cat="'.$idCategoria.'" nomCat="'.$descSubCategoria.'" status="'.$etiStatus.'" data-accion="EditarSubCategoria" class="green" href="">
                                    <i class="far fa-edit" title="Editar Detalle de la SubCategoría"></i>                                   
                                </a>
                                '.$etiqueta.'
                                <a id-subCat="'.$idSubCategoria.'" id-Cat="'.$idCategoria.'" nomSubCat="'.$descSubCategoria.'" status="'.$etiStatus.'" data-accion="verAplicacion" class="text-warning" href="">
                                    <i class="ace-icon fa fa-search bigger-120" title="Ver listado de Aplicación."></i>
                                </a>
                                <a id="'.$idSubCategoria.'" class="red" style="display: none;">
                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                </a>
                            </td>
                        </div>
                    </tr>';                 
                }
    
            return $salida;
    }

    public function listarAplicacion(Request $request){
        return '<table id="TableAplicacion" class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th nowrap style="width:20%;text-align: center;">Id</th>
                            <th style="width:65%;text-align: center;">Descripción</th>
                            <th style="width:65%;text-align: center;">Status</th>
                            <th style="width:15%;text-align: center;">Opciones</th>             
                        </tr>
                    </thead>
                    <tbody class="BodyAplicacionOK">'.$this->cargaListadoAplicacion($request->idSubCategoria).'</tbody>
                </table>';
    }

    public function cargaListadoAplicacion($idSubCategoria){
        $conn = $this->BaseDatosEmpresa();

        $soluFrecuentes = \App\SolucionFrecuente::on($conn)->select('solufrecuente.activo', 'solufrecuente.descripcion', 'solufrecuente.idSoluFrecuente')->join('subcategoria', 'subcategoria.idSubCategoria', '=', 'solufrecuente.idSubCategoria')->where('subcategoria.idSubCategoria','=',$idSubCategoria)->get();

        $salida = '';
        $contador = 0;


        foreach ($soluFrecuentes as $soluFrecuente) {
                                                  
            $contador += 1;
            $idPrbFrecuente  = $soluFrecuente->idSoluFrecuente;
            $desPrbFrecuente = $soluFrecuente->descripcion;
            $activo = $soluFrecuente->activo;

                    if ($activo == 1){
                        $ActDes = '<span class="badge badge-success">Activo</span>';
                        
                    }else{
                        $ActDes = '<span class="badge badge-danger">Inactivo</span>';
                        
                    }

                        $salida .=  '<tr>
                                        <td >'.$idPrbFrecuente.'</td>
                                        <td >'.$desPrbFrecuente.'</td>
                                        <td >'.$ActDes.'</td>
                                        <td>
                                            <div class="action-buttons">                                    
                                                <a idSubCat="'.$idSubCategoria.'" descripcion="'.$desPrbFrecuente.'" idAplicacion="'.$idPrbFrecuente.'" status="'.$activo.'" data-accion="EditarPrbFrecuente" class="green" href="">
                                                    <i class="far fa-edit" title="Editar Detalle de la Aplicación"></i>   
                                                </a>
                                                <a id="'.$idPrbFrecuente.'" class="red" style="display: none;">
                                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>';                 
        
                    
                }
    
                return $salida == '' ? '<tr><td style="text-align: center;" colspan="3">No tiene Aplicación.</td></tr>' : $salida;
    }

    public function registrarSubCategoria(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();

        $save = \App\SubCategorias::Guardar($request,$conn);
        if(!$save){
            App::abort(500, 'Error');
         }

        return response()->json( array('success' => true, 'mensaje'=> 'Subcategoría guardada exitosamente..!') );
    }

    public function ActDesSubCategoria(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();

        $SubCategoria = \App\SubCategorias::on($conn)->find($request->IdSubCat);
 
        $SubCategoria->activo = $SubCategoria->activo == 1 ? 0 : 1;
        if(!$SubCategoria->save()){
            App::abort(500, 'Error');
         }

        return response()->json( array('success' => true, 'mensaje'=> 'Status cambiado exitosamente..!') );
    }

    public function registrarAplicacion(Request $request)
    {

        $conn = $this->BaseDatosEmpresa();

        $solFrecuente = \App\SolucionFrecuente::guardar($request,$conn);
         
        if(!$solFrecuente){
            App::abort(500, 'Error');
         }

        return response()->json( array('success' => true, 'mensaje'=> 'Aplicación registrada exitosamente..!') );
    }

    public function ActDesCategoria(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();

        $Categoria = \App\Categorias::on($conn)->find($request->idCategoria);
 
        $Categoria->activo = $Categoria->activo == 1 ? 0 : 1;
        if(!$Categoria->save()){
            App::abort(500, 'Error');
         }

        return response()->json( array('success' => true, 'mensaje'=> 'Status cambiado exitosamente..!') );
    }

    public function registrarCategoria(Request $request)
    {
        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa();
   
            $save = \App\Categorias::Guardar($request,$conn);
            DB::commit();
            if(!$save){
                App::abort(500, 'Error');
            }

            return response()->json( array('success' => true, 'mensaje'=> 'Categoría guardada exitosamente..!') );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function perfilUsuario()
    {
        $idUser = Auth::id();
        $usuario = \App\Usuarios::find($idUser);
        $cargo = \App\Cargos::find($usuario->cargo);
        $usuario->nomCargo = $cargo->descCargo;


        $data = array(  
                        'usuario' => $usuario,
                     );

        return view('mantenimiento.perfil',$data);
    }

    public function listarTicketsPerfil(Request $request)
    {
        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa();
            $idUser = Auth::id();
            $conectar = DB::connection($conn);
            $ticketsUsuarios = $conectar->select("call lista_ticketsUsuariosTodos($idUser)");
            
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

            if ($ticket->prioridad == 1){
                $labelPrioridad = '<span class="badge badge-pill badge-warning">Baja</span>';
            }else if ($ticket->prioridad == 2){
                $labelPrioridad = '<span class="badge badge-pill badge-success">Normal</span>';
            }else if ($ticket->prioridad == 3){
                $labelPrioridad = '<span class="badge badge-pill badge-danger">Alta</span>';
            }

            $dataSet['aaData'][] = array(   $ticket->nroTicket,
                                            $ticket->ejecutor,
                                            $ticket->Titulo,
                                            $ticket->descCategoria,
                                            $labelPrioridad);
        }

        $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
    
        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
           
        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function buscarImagenUsuario()
    {
       
        return response()->json( array( 'success' => true, 
                                        'mensaje' => 'Query realizado..!',
                                        'photo' => Auth::user()->photo
                                    ) 
                                );
    }

    public function subirFoto(Request $request)
    {

        $conn = $this->BaseDatosEmpresa();
        $ruta     = '/Empresas/'.$conn.'/fotos/';
        $path     = public_path().$ruta;
        $files    = $request->file('file');
        $ext      = explode('/',$request->file('file')->getMimeType());
        $fileName = $files->getClientOriginalName();
        $files->move($path, $fileName);

        rename($path.$fileName, $path.'foto-'.Auth::id().'.'.$ext[1]);
        
        DB::beginTransaction();   
        $Usuarios = \App\Usuarios::find(Auth::id());
        $Usuarios->photo = "Empresas\\".$conn."\\fotos\\".'foto-'.Auth::id().'.'.$ext[1];
        $Usuarios->save();
        DB::commit();
        //Storage::move($path.$fileName, $path.'usuario-'.$request->idSucursal);
        return "Empresas\\".$conn."\\fotos\\".'foto-'.Auth::id().'.'.$ext[1];
        
    }

    /**
     *      Mantenimiento de Areas y subareas.
     */
    public function loadMantAreas()
    {
        
        $conn = $this->BaseDatosEmpresa();
        $areas = \App\Areas::on($conn)->orderBy('descArea', 'ASC')->get();
        $tipTickets = \App\Tablas::on($conn)->where('Tipo','=','TPTK')->orderBy('desTabla', 'ASC')->get();

        $data = array(  
                        'areas' => $areas,
                        'tipTickets' => $tipTickets
                     );

        return view('mantenimiento.areas',$data);
    }

    public function listarAreas(){

        $conn = $this->BaseDatosEmpresa();
        $areas = \App\Areas::on($conn)->get();

        $dataSet = array (
            "sEcho"                 =>  0,
            "iTotalRecords"         =>  1,
            "iTotalDisplayRecords"  =>  1,
            "aaData"                =>  array () 
        );
        $contador = 1;
        foreach ($areas as $area) {

            $idArea     = $area->idArea;
            $descArea   = $area->descArea; 
            $status     = $area->activo;
                  
            if ($status == '1'){
                $ActDes = '<span class="badge badge-pill badge-success">Activo</span>';
                $etiqueta = '<a data-accion="inactivar" class="blue" href="">
                                <i class="ace-icon fa fa-unlock bigger-130" title="Inactivar Área"></i>
                            </a>';
            }else{
                $ActDes = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                $etiqueta = '<a data-accion="activar" class="purple" href="">
                                <i class="ace-icon fa fa-lock bigger-130" title="Activar Área"></i>
                            </a>';
            }

            $botones    = '<div class="action-buttons">
                                <td>
                                    <a idArea="'.$idArea.'" status="'.$status.'" data-accion="EditarArea" class="green" href="">
                                        <i class="far fa-edit" title="Editar Detalle del Área"></i>                                        
                                    </a>
                                    '.$etiqueta.'
                                    <a data-accion="AgregarSubArea" class="red" href="">
                                        <i class="ace-icon fa fa-plus bigger-120" title="Agregar SubAreaa"></i>
                                    </a>
                                    
                                    <a id="'.$idArea.'" class="red" style="display: none;">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    </a>
                                </td>
                            </div>';


            $dataSet['aaData'][] = array(   $idArea,
                                            $descArea,
                                            $ActDes,
                                            $this->subAreas($conn,$idArea),
                                            $botones
                                        );  
            $contador++;            
            
        }       

        $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
    
        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
    
    }

    function subAreas($conn,$Area){

        $subAreas = \App\SubAreas::on($conn)->join('area','area.idArea','=','subarea.idArea')->where('area.idArea',$Area)->select('subarea.idSubArea','subarea.descSubArea', 'subarea.idArea', 'area.descArea', 'subarea.activo' )->get();

        $salida = '';
        $contador = 0;
            
        foreach ($subAreas as $subArea) {

            $contador += 1;
            $idArea         = $subArea->idArea;
            $idSubArea      = $subArea->idSubArea;
            $descSubArea    = $subArea->descSubArea;    
            $activo         = $subArea->activo;

            if ($activo == '1'){
                $ActDes = '<span class="badge badge-pill badge-success">Activo</span>';
                $etiqueta = '<a idArea="'.$idArea.'" idSubArea="'.$idSubArea.'" data-accion="inactivarSubArea" class="blue" href="">
                                <i class="ace-icon fa fa-unlock bigger-130" title="Inactivar SubArea"></i>
                            </a>';
             }else{
                $ActDes = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                $etiqueta = '<a idArea="'.$idArea.'" idSubArea="'.$idSubArea.'" data-accion="activarSubArea" class="purple" href="">
                                <i class="ace-icon fa fa-lock bigger-130" title="Activar SubArea"></i>
                            </a>';
            }
                        
            $salida .=  '<tr>
                            <td style="display: none;">'.$idArea.'</td>
                            <td style="display: none;">'.$idSubArea.'</td>
                            <td>'.$descSubArea.'</td>   
                            <td>'.$ActDes.'</td>
                                        
                            <div class="action-buttons">
                                <td>
                                    <a status="'.$activo.'" idSubArea="'.$idSubArea.'" idArea="'.$idArea.'" nombreSubArea="'.$descSubArea.'" data-accion="EditarSubArea" class="green" href="">
                                        <i class="fas fa-edit" title="Editar Detalle de la SubÁrea"></i>                              
                                    </a>
                                    '.$etiqueta.'
                                    <a id="'.$idSubArea.'" class="red" style="display: none;">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                     </a>
                                </td>
                            </div>                                                  
                        </tr>';                 
        
                    
        }
   
        return '<br><table id="TableListadoSubArea" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th style="width:10%;text-align: center;display: none;">Id Área</th>
                    <th style="width:10%;text-align: center;display: none;">Id SubÁrea</th>
                    <th style="width:55%;text-align: center;">Nombre de la SubÁrea</th>
                    <th style="width:25%;text-align: center;">Status</th>
                    <th style="width:10%;text-align: center;">Opciones</th>             
                </tr>
            </thead>
            <tbody id="listaSubAreaOK">'.$salida.'</tbody>
        </table>';
    }

    public function ActDesArea(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();

        $Area = \App\Areas::on($conn)->find($request->idArea);
 
        $Area->activo = $Area->activo == 1 ? 0 : 1;
        if(!$Area->save()){
            App::abort(500, 'Error');
         }

        return response()->json( array('success' => true, 'mensaje'=> 'Status cambiado exitosamente..!') );
    }

    public function registrarArea(Request $request)
    {
        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa();
   
            $save = \App\Areas::Guardar($request,$conn);
            DB::commit();
            if(!$save){
                App::abort(500, 'Error');
            }

            return response()->json( array('success' => true, 'mensaje'=> 'Área guardada exitosamente..!') );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function registrarSubArea(Request $request)
    {
        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa();
   
            $save = \App\SubAreas::Guardar($request,$conn);
            DB::commit();
            if(!$save){
                App::abort(500, 'Error');
            }

            return response()->json( array('success' => true, 'mensaje'=> 'Área guardada exitosamente..!') );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function ActDesSubArea(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();

        $subArea = \App\SubAreas::on($conn)->find($request->idSubArea);
 
        $subArea->activo = $subArea->activo == 1 ? 0 : 1;
        if(!$subArea->save()){
            App::abort(500, 'Error');
         }

        return response()->json( array('success' => true, 'mensaje'=> 'Status cambiado exitosamente..!') );
    }

    /**
     *      Mantenimiento de Profesiones.
     */
    
    public function Profesiones(){

        return view('mantenimiento.profesiones');
    }

    public function listarProfesiones(){

        $profesiones = \App\Profesiones::get();
        
        
        $dataSet = array (
            "sEcho"                 =>  0,
            "iTotalRecords"         =>  1,
            "iTotalDisplayRecords"  =>  1,
            "aaData"                =>  array () 
        );
        $contador = 1;
        foreach ($profesiones as $profesion) {

            $id     = $profesion->id;
            $nombre = $profesion->nombre; 
            $status = $profesion->status;
                  
            if ($status == 1){
                $ActDes = '<span class="badge badge-pill badge-success">Activo</span>';
                $etiqueta = '<a data-accion="inactivar" class="blue" href="" idProfesion="'.$id.'">
                                <i class="ace-icon fa fa-unlock bigger-130" title="Inactivar Profesión"></i>
                            </a>';
            }else{
                $ActDes = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                $etiqueta = '<a data-accion="activar" class="purple" href="" idProfesion="'.$id.'">
                                <i class="ace-icon fa fa-lock bigger-130" title="Activar Profesión"></i>
                            </a>';
            }

            $botones    = '<div class="action-buttons">
                                <td>
                                    <a idProfesion="'.$id.'" status="'.$status.'" data-accion="editarProfesion" class="green" href="">
                                        <i class="far fa-edit" title="Editar Detalle de la Profesión"></i>                                        
                                    </a>
                                    '.$etiqueta.'
                                    
                                    <a id="'.$id.'" class="red" style="display: none;">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    </a>
                                </td>
                            </div>';


            $dataSet['aaData'][] = array(   $id,
                                            $nombre,
                                            $ActDes,
                                            $botones
                                        );  
            $contador++;            
            
        }       

        $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
    
        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
    }

    public function actualizarStatusProfesion(Request $request)
    {

        $profesion = \App\Profesiones::find($request->idProfesion);
 
        $profesion->status = $profesion->status == 1 ? 0 : 1;
        if(!$profesion->save()){
            App::abort(500, 'Error');
         }

        return response()->json( array('success' => true, 'mensaje'=> 'Status cambiado exitosamente..!') );
    }

    public function registrarProfesion(Request $request){
        //return $request;
        try {
            DB::beginTransaction();   
   
            $save = \App\Profesiones::Guardar($request);
            DB::commit();
            if(!$save){
                App::abort(500, 'Error');
            }

            return response()->json( array('success' => true, 'mensaje'=> 'Profesión guardada exitosamente..!') );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
       
    }

    public function EditarProfesion(Request $request){
        //return $request;
        try {
            DB::beginTransaction();   
            
            $profesion = \App\Profesiones::find($request->idProfesion);

            return response()->json( array('success' => true, 'mensaje'=> 'Cargo guardado exitosamente..!', 'data' => $profesion) );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
       
    }

    /**
     *      Mantenimiento de Paises.
     */
    
    public function paises(){

        return view('mantenimiento.paises');
    }

    public function listarPaises(){

        $paises = \App\Paises::get();
        
        
        $dataSet = array (
            "sEcho"                 =>  0,
            "iTotalRecords"         =>  1,
            "iTotalDisplayRecords"  =>  1,
            "aaData"                =>  array () 
        );
        $contador = 1;
        foreach ($paises as $pais) {

            $id     = $pais->id;
            $nombre = $pais->nombre; 
            $status = $pais->status;
                  
            if ($status == 1){
                $ActDes = '<span class="badge badge-pill badge-success">Activo</span>';
                $etiqueta = '<a data-accion="inactivar" class="blue" href="" idPais="'.$id.'">
                                <i class="ace-icon fa fa-unlock bigger-130" title="Inactivar Pais"></i>
                            </a>';
            }else{
                $ActDes = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                $etiqueta = '<a data-accion="activar" class="purple" href="" idPais="'.$id.'">
                                <i class="ace-icon fa fa-lock bigger-130" title="Activar Pais"></i>
                            </a>';
            }

            $botones    = '<div class="action-buttons">
                                <td>
                                    <a idPais="'.$id.'" status="'.$status.'" data-accion="editarPais" class="green" href="">
                                        <i class="far fa-edit" title="Editar Detalle del Pais"></i>
                                    </a>
                                    '.$etiqueta.'
                                    
                                    <a id="'.$id.'" class="red" style="display: none;">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    </a>
                                </td>
                            </div>';


            $dataSet['aaData'][] = array(   $id,
                                            $nombre,
                                            $ActDes,
                                            $botones
                                        );  
            $contador++;            
            
        }       

        $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
    
        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
    }


    public function actualizarStatusPais(Request $request)
    {

        $pais = \App\Paises::find($request->idPais);
 
        $pais->status = $pais->status == 1 ? 0 : 1;
        if(!$pais->save()){
            App::abort(500, 'Error');
         }

        return response()->json( array('success' => true, 'mensaje'=> 'Status cambiado exitosamente..!') );
    }

    public function EditarPais(Request $request){
        //return $request;
        try {
            DB::beginTransaction();   
            
            $pais = \App\Paises::find($request->idPais);

            return response()->json( array('success' => true, 'mensaje'=> 'Cargo guardado exitosamente..!', 'data' => $pais) );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
       
    }

    public function registrarPais(Request $request){
        
        try {
            DB::beginTransaction();   
   
            $save = \App\Paises::Guardar($request);
            DB::commit();
            if(!$save){
                App::abort(500, 'Error');
            }

            return response()->json( array('success' => true, 'mensaje'=> 'País guardado exitosamente..!') );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
       
    }

    
}

