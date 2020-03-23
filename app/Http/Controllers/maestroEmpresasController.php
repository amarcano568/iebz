<?php

namespace App\Http\Controllers;
use Empresas;
use Tickets;
use \Auth;
use Carbon\Carbon;
use \DB;
use App\Traits\funcGral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class maestroEmpresasController extends Controller
{
	use funcGral;

    public function listarMaestroEmpresa()
    {
    	$conn = $this->BaseDatosEmpresa();
    	$areas = \App\Areas::on($conn)->get();
        $cargos = \App\Cargos::on($conn)->get();
        $subAreas = \App\SubAreas::on($conn)->get();
		$data = array(  
                        'areas' => $areas,
                        'subAreas' => $subAreas,
                        'cargos' => $cargos
                     );
        return view('mantenimiento.maestroEmpresaView',$data);
    }

    /**
     *      Lista Maestro de Empresas
     */
    public function cargaMaestroEmpresas()
    {
  
        $conn = $this->BaseDatosEmpresa();

        $empresas = \App\Empresas::select('empresas.id','NombreEmpresa','ruc','nameBd','usuariosPermitidos','name','lastName','empresas.status')->join('users','users.id','userAdmin')->get();

        $empresas->map(function($empresa){
            $tickets = \App\Tickets::on($empresa->nameBd)->count();
            $empresa->ticket = $tickets;
        });
              
        $dataSet = array (
            "sEcho"                 =>  0,
            "iTotalRecords"         =>  1,
            "iTotalDisplayRecords"  =>  1,
            "aaData"                =>  array () 
        );

        foreach($empresas as $empresa)
        {
            if ( $empresa->status == 1){
                $status = '<span class="badge badge-pill badge-success"><i class="fas fa-check"></i> Activa</span>';
                $candado  = '<i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Bloquear empresa (<strong>'.$empresa->NombreEmpresa.'</strong>)."  class="text-success fas fa-lock-open"></i>';
            }else{
                $status = '<span class="badge badge-pill badge-danger"><i class="fas fa-ban"></i> Inactiva</span>';
                $candado  = '<i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Desbloquear empresa (<strong>'.$empresa->NombreEmpresa.'</strong>)."  class="text-danger fas fa-lock"></i>';
            }

            $dataSet['aaData'][] = array(  $empresa->id,
                                           $empresa->NombreEmpresa,
                                           $empresa->ruc,
                                           $empresa->nameBd,
                                           $empresa->usuariosPermitidos,
                                           $empresa->name.' '.$empresa->lastName,
                                           '<span data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="La empresa <strong>'.$empresa->NombreEmpresa.'</strong> hasta el día de hoy a generado '.$empresa->ticket.' ticket(s)."><i class="text-primary fas fa-ticket-alt"></i> <i class="fas fa-equals"></i> '.$empresa->ticket.'</span>',
                                           $status,
                                           '<div class="icono-action">
                                                <a href="" data-accion="editarEmpresa" idempresa="'.$empresa->id.'" >
                                                    <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Editar empresa (<strong>'.$empresa->NombreEmpresa.'</strong>)." class="icono-action text-primary far fa-edit">
                                                    </i>
                                                </a>
                                                <a href="" data-accion="bloquearEmpresa" idempresa="'.$empresa->id.'" status="'.$empresa->status.'">
                                                    '.$candado.'
                                                </a>
                                            </div>');
        }

        $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
    
        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
    }

    public function registrarMaestroEmpresa(Request $request)
    {

        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa(); 
            if ( is_null($request->idEmpresa) ){  

	            $rules = [
	                        'emailEmpresa' => ['required', 'email', 'unique:empresas,correo' ],
	                        'baseDatos' => ['required', 'unique:empresas,nameBd' ],
	                        'correoUsuario' => ['required', 'email', 'unique:users,email' ],
	                    ];

	            $customMessages =   [
	                                    'emailEmpresa.unique' => '<i class="fas fa-exclamation-triangle"></i> Existe otra Empresa con ese <strong>Correo</strong>',
	                                    'baseDatos.unique'  => '<i class="fas fa-exclamation-triangle"></i> <strong>Base de Datos</strong> existe.',
	                                    'correoUsuario.unique'  => '<i class="fas fa-exclamation-triangle"></i> Existe otro Usuario con el mismo correo.',
	                                ];                                

	            $v =  $this->validate($request, $rules, $customMessages);

	        }

	        $validaEnv = env($request->baseDatos.'_DB_CONNECTION');
            
	        if ( trim($validaEnv) == "" ){
	        	return response()->json( array('success' => false, 'mensaje'=> 'Aún no ha sido creada la conexión en el archivo <strong>.env => '.$validaEnv.'</strong> y en el archivo <strong>config/database.php</strong>') );
	        }

	        $idNextEmpresa = $this->getNextID('empresas');

            \App\Empresas::Guardar($request,$conn,$request->idEmpresa,$idNextEmpresa);

            DB::commit();

            return response()->json( array('success' => true, 'mensaje'=> '') );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function obtenerInformacionEmpresa(Request $request)
    {

        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa(); 
            
            $empresa = \App\Empresas::join('users','users.id','empresas.userAdmin')->find($request->idEmpresa);

            return response()->json( array(	'success' => true, 
            								'mensaje'=> 'Query ejecutado exitosamente', 
            								'data' =>$empresa) 
        									);

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function actualizarDatosEmpresa(Request $request)
    {

        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa(); 
          
            \App\Empresas::Actualizar($request,$conn,$request->idEmpresa);

            DB::commit();

            return response()->json( array('success' => true, 'mensaje'=> '') );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

     public function ActDesEmpresa(Request $request)
    {

        try {
            DB::beginTransaction();   
            $conn = $this->BaseDatosEmpresa(); 
          
            $empresa = \App\Empresas::find($request->idEmpresa);
            $empresa->status = $empresa->status == 1 ? 0 : 1;
            $empresa->save();

            DB::commit();

            return response()->json( array('success' => true, 'mensaje'=> 'Status de la Empresas actualizado exitosamente.') );

        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

}
