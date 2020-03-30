<?php

namespace App\Http\Controllers;
use Usuarios;

use \Auth;
use Carbon\Carbon;
use \DB;
use File;
use \Miembros;
use \Status;
use Illuminate\Http\Request;
use App\Traits\funcGral;

class mantenimientoController extends Controller
{
    use funcGral;

 
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

    public function registrarProfesionMiembros(Request $request){
        //return $request; 
        $profesion = new \App\Profesiones;       
        $profesion->nombre = $request->nombreProfesion;
        $profesion->status = 1;  
        $profesion->save();
        $id = $profesion->id;
        
        return response()->json( array('success' => true, 'mensaje'=> 'Profesión guardada exitosamente..!', 'idNuevaProfesion' => $id) );
       
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

    public function loadInfo()
    {
        $miembros = \App\Miembros::get();
        $total = $miembros->count();

        $status = \App\Miembros::groupBy('status')
        ->selectRaw('count(*) as total, status')
        ->get();

        $generos = \App\Miembros::groupBy('sexo')
        ->selectRaw('count(*) as total, sexo')
        ->get();

        $values = array();
        $labels = array();
        $colores = array();
        $i = 0;

        $salida = '<div class="col-lg-2 col-md-2 col-sm-4  tile_stats_count" style="width: 25em!important">
                      <span class="count_top"><i class="fas fa-users"></i> Miembros</span>
                      <div class="count" id="total">'.$total.'</div>
                    </div>';
        foreach ($status as $statu) {
            
            $rowStatus = \App\Status::find($statu->status);

            $salida .= '<div class="col-lg-2 col-md-2 col-sm-4  tile_stats_count">
                          <span class="count_top">'.$rowStatus->fontAwesome.' '.$rowStatus->nombre.'</span>
                          <div class="count" style="color:'.$rowStatus->color.'">'.$statu->total.'</div>
                        </div>';

            $values[$i] = $statu->total;
            $labels[$i] = $rowStatus->nombre;
            $colores[$i] = $rowStatus->color;

            $i++;
        }

        $valuesGenero = array();
        $labelsGenero = array();
        $coloresGenero = array();
        $i=0;

        foreach ($generos as $genero) {

            if ($genero->sexo == 'M'){
                $generoIcono = '<i class="fas fa-male"></i>';
                $generoNombre = 'Masculino';
                $color = '#A6D2AE';
            }else if ($genero->sexo == 'F'){
                $generoIcono = '<i class="fas fa-female"></i>';
                $generoNombre = 'Femenino';
                $color = '#FECFD7';
            }else{
                $generoIcono = '<i class="fas fa-question"></i>';
                $generoNombre = 'Sin asignar Género';
                $color = '#CFCFC3';
            }

            $salida .= '<div class="col-lg-2 col-md-2 col-sm-4 tile_stats_count">
              <span class="count_top">'.$generoIcono.' '.$generoNombre.'</span>
              <div class="count " style="color: '.$color.'">'.$genero->total.'</div>
            </div>';

            $valuesGenero[$i] = $genero->total;
            $labelsGenero[$i] = $generoNombre;
            $coloresGenero[$i] = $color;

            $i++;
        }

        $data = array(
                        'graficoMiembros' => array(
                                                        'values' => $values,
                                                        'labels' => $labels,
                                                        'colores' => $colores
                                                    ),
                        'graficoGenero' => array(
                                                        'values' => $valuesGenero,
                                                        'labels' => $labelsGenero,
                                                        'colores' => $coloresGenero
                                                    ),
                        'tableroInfo' => $salida
                    );
        return response()->json( array('success' => true, 'mensaje'=> 'Información generada exitosamente..!', 'data' => $data) );
       
    }

    
}

