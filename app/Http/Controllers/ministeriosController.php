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
use \Ministerios;
use Uuid;
use PDF;
use Illuminate\Http\Request;
use App\Traits\funcGral;

class ministeriosController extends Controller
{
    public function Ministerios()
    {
        return view('miembros.verMinisterios');
    }

    /**
     *      Lista ministerios del Sistema.
     */
    public function listarMinisterios()
    {

        $ministerios = \App\Ministerios::get();
        $ministerios->map(function($ministerio){           
            $miembro = explode(';',$ministerio->miembros);
            $ministerio->total = count($miembro)-1;
        });
        
        $dataSet = array (
            "sEcho"                 =>  0,
            "iTotalRecords"         =>  1,
            "iTotalDisplayRecords"  =>  1,
            "aaData"                =>  array () 
        );

        foreach($ministerios as $ministerio)
        {
        	if ( $ministerio->status == 1){
                $status = '<span class="badge badge-pill badge-success"><i class="fas fa-check"></i> Activo</span>';
                $candado  = '<i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Bloquear Ministerio (<strong>'.$ministerio->nombre.'</strong>)."  class="text-success fas fa-lock-open"></i>';
            }else{
                $status = '<span class="badge badge-pill badge-danger"><i class="fas fa-ban"></i> Inactivo</span>';
                $candado  = '<i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Desbloquear Ministerio (<strong>'.$ministerio->nombre.'</strong>)."  class="text-danger fas fa-lock"></i>';
            }
            
            $dataSet['aaData'][] = array(  $ministerio->id,
                                           $ministerio->nombre,
                                           $ministerio->total,
                                           $status,                                          
                                           $this->detalleMinisterio($ministerio->miembros,$ministerio->id),
                                           '<div class="icono-action">
                                                <a href="" data-accion="editarMinisterio" idMinisterio="'.$ministerio->id.'">
                                                    <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Editar ministerio (<strong>'.$ministerio->nombre.'</strong>)." class="icono-action text-primary far fa-edit">
                                                    </i>
                                                </a>                                                
												<a href="" data-accion="agregarMiembro" idMinisterio="'.$ministerio->id.'" ministerio="'.$ministerio->nombre.'">
                                                    <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Incluir Miembro al Ministerio de <strong>'.$ministerio->nombre.'</strong>." class="fas fa-user-plus text-primary"></i>
                                                </a>
                                                <a href="" data-accion="bloquearMinisterio" idMinisterio="'.$ministerio->id.'" status="'.$ministerio->status.'">
                                                    '.$candado.'
                                                </a>

                                            </div>'
                                        );
        }

        $salidaDeDataSet = json_encode ($dataSet, JSON_HEX_QUOT);
    
        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
    }

    public function detalleMinisterio($personas,$idMinisterio)
    {
    	$miembros = explode(';', $personas);
    	$salida = '';
    	foreach ($miembros as $idMiembro ) {
    		if ($idMiembro != null || $idMiembro!= ''){
	    		$persona = \App\Miembros::find($idMiembro);
	    		$salida .= '<tr>
	    						<td>'.$persona->id.'</td>
	    						<td>'.$persona->nombre.' '.$persona->apellido1.' '.$persona->apellido2.'</td>
	    						<td>
	    							<a href="" idMiembro="'.$idMiembro.'" idMinisterio="'.$idMinisterio.'" class="excluirMiembro">
	                                    <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Excluir del Ministerio."  class="text-danger fas fa-user-times"></i>
	                                </a>
	    						</td>
	    					</tr>';
	    	}
    	}

    	 return '<table id="TableMiembros" class="table table-striped table-bordered table-hover table-condensed">
        <thead>
        <tr>
        <th nowrap style="width:10%;text-align: center">Id</th>
        <th style="width:60%;text-align: center;">Nombre</th>
        <th style="width:15%;text-align: center;"></th>
        </tr>
        </thead>
        <tbody id="body-miembros">'.$salida.'</tbody>
        </table>';
    }

    public function excluirMiembro(Request $request)
    {
        $ministerio = \App\Ministerios::find($request->idMinisterio);

        $miembros = $ministerio->miembros;

        $newMiembros = str_replace($request->idMiembro.';', "", $miembros);

        $ministerio->miembros = $newMiembros;

        $ministerio->save();

        return response()->json( array('success' => true, 'mensaje'=> 'Ministerio actualizado correctamente') );
    }


	public function agregarMiembroMinisterio(Request $request)
    {

    	$idMinisterio = $request->idMinisterio;
    	$ministerio = \App\Ministerios::find($idMinisterio);
        $lista = substr(str_replace(';', ",", $ministerio->miembros), 0, -1);

    	$miembros = \App\Miembros::whereNotIn('id',explode(',', $lista))->get();

    	return response()->json( array('success' => true, 'mensaje'=> 'Ministerio actualizado correctamente', 'data' => $miembros ) );


    }

    public function buscarFotoMiembro(Request $request)
    {

    	$miembro = \App\Miembros::select('foto')->find($request->idMiembro);

    	if ( $miembro->foto == '' || $miembro->foto === null){
    		$foto = '<img src="images/user.png" alt="Foto Miembro" width="100px" height="125px" class="img-thumbnail center">';
    	}else{
    		$foto = '<img src="/img/fotos/'.$miembro->foto.'" alt="Foto Miembro" width="100px" height="125px" class="img-thumbnail center">';
    	}
    	

    	return response()->json( array(	'success' => true, 
    									'mensaje'=> 'Foto recuperada correctamente', 
    									'foto' => $foto ) );

    }


    public function incluirMiembroMinisterio(Request $request)
    {
        $ministerio = \App\Ministerios::find($request->idMinisterio);

        if ($ministerio->status == 1){
        	$miembros = $ministerio->miembros.$request->idMiembro.';';
        	$ministerio->miembros = $miembros;
        	$ministerio->save();
        	return response()->json( array('success' => true, 'mensaje'=> 'Ministerio actualizado correctamente') );
        }else{
        	return response()->json( array('success' => false, 'mensaje'=> 'No se puede Incluir Miembro a este Ministerio por que se encuentra inactivo') );
        }

    }


    public function bloquearMinisterio(Request $request)
    {

        $ministerio = \App\Ministerios::find($request->idMinisterio);
 
        $ministerio->status = $ministerio->status == 1 ? 0 : 1;
        if(!$ministerio->save()){
            App::abort(500, 'Error');
         }

        return response()->json( array('success' => true, 'mensaje'=> 'Status cambiado exitosamente..!') );
    }

    public function buscarMinisterio(Request $request)
    {

        $ministerio = \App\Ministerios::find($request->idMinisterio);

        return response()->json( array('success' => true, 'mensaje'=> 'Status cambiado exitosamente..!', 'data' => $ministerio) );
    }

    public function registrarMinisterio(Request $request)
    {

    	if ( is_null($request->idMinisterioAgregar) ){     
            $ministerio = new \App\Ministerios;
        	//$profesion->descprofesion= $request->descprofesion;
        }else{
            $ministerio  = \App\Ministerios::find($request->idMinisterioAgregar);    
        }
        $ministerio->nombre = $request->nombreMinisterio;
        $ministerio->status = $request->statusMinisterio;

        if(!$ministerio->save()){
            return response()->json( array('success' => false, 'mensaje'=> 'Hubo un problema tratando de actualizar los datos del Ministerio..!') );
         }else{
         	return response()->json( array('success' => true, 'mensaje'=> 'Ministerio actualizado exitosamente..!') );
         }

        return response()->json( array('success' => true, 'mensaje'=> 'Status cambiado exitosamente..!', 'data' => $ministerio) );
    }

    public function informeMinisterios()
    {
    	$ministerios = \App\Ministerios::get();

    	$data = array(
    					'ministerios' => $ministerios
    	);

    	return view('miembros.ministerios',$data);

    }

    public function crearOrganigrama()
    {
        $ministerios = \App\Ministerios::get();
        $salida = '<ul>
        <li>
          <a >Ministerios&nbsp;&nbsp;&nbsp;<i style="cursor: pointer;" class="btnAgregarMinisterio text-primary fas fa-plus"></i></a>
            <ul>';
        
        foreach ($ministerios as $ministerio) {
            $status = $ministerio->status == 1 ? '<br><span class="badge badge-pill badge-success">&nbsp;Activo&nbsp;</span>' : '<br><span class="badge badge-pill badge-danger">&nbsp;Inactivo&nbsp;</span>';
            $total = count(explode(';',$ministerio->miembros))-1;
            $salida .= '<li>
                            <a><span class="text-info"><strong>'
                                .$ministerio->nombre.'</strong><span class="text-success">&nbsp;&nbsp;&nbsp;<i style="cursor: pointer;" class="fas fa-user-plus agregarMiembro" Nombre="'.$ministerio->nombre.'" idMinisterio="'.$ministerio->id.'" ></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i  style="cursor: pointer;" Nombre="'.$ministerio->nombre.'" idMinisterio="'.$ministerio->id.'"  class="borrarMinisterio text-danger far fa-trash-alt"></i></span></span>'.$status
                                .'<br>Miembros # '.$total
                                .'<br><br>'.$this->miembrosMinisterio($ministerio->miembros,$ministerio->id).
                            '</a>
                        </li>';
        }

        $salida .='</ul>
                        </li>
                    </ul>';

        return response()->json( array('success' => true, 'mensaje'=> 'Orgnigrama creado exitosamente..!', 'data' => $salida) );

    }

    public function miembrosMinisterio($miembros,$idMinisterio)
    {
        $lista = substr(str_replace(';', ",", $miembros), 0, -1);
        $listaMiembros = \App\Miembros::select('id','nombre','apellido1','apellido2','telefonoFijo','telefonoMovil','email')->whereIn('id',explode(',', $lista))->get();
        $salida = '';    
        foreach ($listaMiembros as $miembro) {
            $salida .= '<span class="float-left">'.$miembro->nombre.' '.$miembro->apellido1.'&nbsp; <i style="cursor: pointer;" idMiembro="'.$miembro->id.'" idMinisterio="'.$idMinisterio.'"  class="excluirMiembro text-danger fas fa-user-times"></i>'.'</span><br>';
        }

        return $salida;
    }


    public function borrarMinisterio(Request $request)
    {
        \App\Ministerios::destroy($request->idMinisterio);
        return response()->json( array('success' => true, 'mensaje'=> 'Ministerio borrado exitosamente') );

    } 


}
