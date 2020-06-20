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
use \SubMinisterios;
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
												<a href="" data-accion="agregarMiembro" idMinisterio="'.$ministerio->id.'" ministerio="'.$ministerio->nombre.'" nivel="1">
                                                    <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Incluir Miembro al Ministerio de <strong>'.$ministerio->nombre.'</strong>." class="fas fa-user-plus text-primary"></i>
                                                </a>
                                                <a href="" data-accion="bloquearMinisterio" idMinisterio="'.$ministerio->id.'" status="'.$ministerio->status.'">
                                                    '.$candado.'
                                                </a>
                                                <a href="" class="borrarMinisterio" idMinisterio="'.$ministerio->id.'" Nombre="'.$ministerio->nombre.'" nivel="1">
                                                    <i style="cursor: pointer;" class="text-danger far fa-trash-alt" nivel="1"></i>
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
	    							<a href="" idMiembro="'.$idMiembro.'" idMinisterio="'.$idMinisterio.'" class="excluirMiembro" nivel="1">
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
        if ($request->nivel == 1){
            $ministerio = \App\Ministerios::find($request->idMinisterio);
            $miembros = $ministerio->miembros;
            $newMiembros = str_replace($request->idMiembro.';', "", $miembros);
            $ministerio->miembros = $newMiembros;
            $ministerio->save();
        }else{
            $subMinisterio = \App\SubMinisterios::find($request->idMinisterio);
            $miembros = $subMinisterio->miembros;
            $newMiembros = str_replace($request->idMiembro.';', "", $miembros);
            $subMinisterio->miembros = $newMiembros;
            $subMinisterio->save();
        }

        return response()->json( array('success' => true, 'mensaje'=> 'Ministerio actualizado correctamente') );
    }


	public function agregarMiembroMinisterio(Request $request)
    {
        $idMinisterio = $request->idMinisterio;
        if ($request->nivel == 1){
        	$ministerio = \App\Ministerios::find($idMinisterio);
            $lista = substr(str_replace(';', ",", $ministerio->miembros), 0, -1);
        }else{
            $subMinisterio = \App\SubMinisterios::find($idMinisterio);
            $lista = substr(str_replace(';', ",", $subMinisterio->miembros), 0, -1);
        }

    	$miembros = \App\Miembros::whereNotIn('id',explode(',', $lista))->get();

    	return response()->json( array('success' => true, 'mensaje'=> 'Ministerios listados correctamente', 'data' => $miembros ) );


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
        if ($request->nivel == 1){
            $ministerio = \App\Ministerios::find($request->idMinisterio);
            if ($ministerio->status == 1){
            	$miembros = $ministerio->miembros.$request->idMiembro.';';
            	$ministerio->miembros = $ministerio->miembros === null ? ';'.$miembros : $miembros;
            	$ministerio->save();
            	return response()->json( array('success' => true, 'mensaje'=> 'Ministerio actualizado correctamente') );
            }else{
            	return response()->json( array('success' => false, 'mensaje'=> 'No se puede Incluir Miembro a este Ministerio por que se encuentra inactivo') );
            }
        }else{
            $subMinisterio = \App\SubMinisterios::find($request->idMinisterio);
            if ($subMinisterio->status == 1){
                $miembros = $subMinisterio->miembros.$request->idMiembro.';';
                $subMinisterio->miembros = $subMinisterio->miembros === null ? ';'.$miembros : $miembros;
                $subMinisterio->save();
                return response()->json( array('success' => true, 'mensaje'=> 'Ministerio actualizado correctamente') );
            }else{
                return response()->json( array('success' => false, 'mensaje'=> 'No se puede Incluir Miembro a este Ministerio por que se encuentra inactivo') );
            }
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
        if ($request->nivelNuevoMinisterio == 1){
        	if ( is_null($request->idMinisterioAgregar) ){     
                $ministerio = new \App\Ministerios;
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
        }else{
            if ( is_null($request->idMinisterioAgregar) ){     
                $subMinisterio = new \App\SubMinisterios;
                $subMinisterio->idMinisterio = $request->idNuevoMinisterio;
            }else{
                $subMinisterio  = \App\subMinisterios::find($request->idMinisterioAgregar);    
            }
            $subMinisterio->nombre = $request->nombreMinisterio;
            $subMinisterio->status = $request->statusMinisterio;

            if(!$subMinisterio->save()){
                return response()->json( array('success' => false, 'mensaje'=> 'Hubo un problema tratando de actualizar los datos del Ministerio..!') );
             }else{
                return response()->json( array('success' => true, 'mensaje'=> 'Ministerio actualizado exitosamente..!') );
             }
        }

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
          <a >Ministerios&nbsp;&nbsp;&nbsp;<i style="cursor: pointer;" nivel="1" class="btnAgregarMinisterio text-primary fas fa-plus"></i></a>
            <ul>';
        
        foreach ($ministerios as $ministerio) {
            $status = $ministerio->status == 1 ? '<br><span class="badge badge-pill badge-success">&nbsp;Activo&nbsp;</span>' : '<br><span class="badge badge-pill badge-danger">&nbsp;Inactivo&nbsp;</span>';
            $total = count(explode(';',$ministerio->miembros))-1;
            $salida .= '<li>
                            <a>
                                <span class="text-info">
                                    <strong>
                                        '.$ministerio->nombre.'
                                    </strong>
                                    <span class="text-success">
                                        &nbsp;&nbsp;&nbsp;
                                        <i nivel="1" style="cursor: pointer;" class="fas fa-user-plus agregarMiembro" Nombre="'.$ministerio->nombre.'" idMinisterio="'.$ministerio->id.'" ></i>
                                        &nbsp;&nbsp;&nbsp;
                                        <i  style="cursor: pointer;" Nombre="'.$ministerio->nombre.'" idMinisterio="'.$ministerio->id.'"  class="borrarMinisterio text-danger far fa-trash-alt" nivel="1"></i>
                                        &nbsp;&nbsp;
                                        <i style="cursor: pointer;" nivel="2"  idMinisterio="'.$ministerio->id.'" class="btnAgregarMinisterio text-info fas fa-network-wired"></i>
                                    </span>
                                </span>
                                '.$status.'<br>Miembros # '.$total
                                .'<br><br>'.$this->miembrosMinisterio($ministerio->miembros,$ministerio->id,1).
                            '</a>
                            '.$this->subMinisterios($ministerio->id).'
                        </li>';
        }

        $salida .='</ul>
                        </li>
                    </ul>';

        return response()->json( array('success' => true, 'mensaje'=> 'Orgnigrama creado exitosamente..!', 'data' => $salida) );

    }

    public function subMinisterios($idMinisterio)
    {
        $subMinisterios = \App\SubMinisterios::where('idMinisterio',$idMinisterio)->get();

        $salida = '';

        if ($subMinisterios->count() > 0){
            foreach ($subMinisterios as $subMinisterio) {
                $status = $subMinisterio->status == 1 ? '<br><span class="badge badge-pill badge-success">&nbsp;Activo&nbsp;</span>' : '<br><span class="badge badge-pill badge-danger">&nbsp;Inactivo&nbsp;</span>';
                $total = count(explode(';',$subMinisterio->miembros))-1;
                $salida .= '<li>
                                <a>
                                    <span class="text-info">
                                        <strong>
                                            '.$subMinisterio->nombre.'
                                        </strong>
                                        <span class="text-success">
                                            &nbsp;&nbsp;&nbsp;
                                            <i nivel="2" style="cursor: pointer;" class="fas fa-user-plus agregarMiembro" Nombre="'.$subMinisterio->nombre.'" idMinisterio="'.$subMinisterio->id.'" ></i>
                                            &nbsp;&nbsp;&nbsp;
                                            <i  style="cursor: pointer;" Nombre="'.$subMinisterio->nombre.'" idMinisterio="'.$subMinisterio->id.'"  class="borrarMinisterio text-danger far fa-trash-alt" nivel="2"></i>
                                        </span>
                                    </span>
                                    '.$status.'<br>Miembros # '.$total
                                    .'<br><br>
                                    '.$this->miembrosMinisterio($subMinisterio->miembros,$subMinisterio->id,2).'
                                </a>
                            </li>';
            }

            return '<ul>
                    '.$salida.'      
                    </ul>';
        }

        return '';

        
    }

    public function miembrosMinisterio($miembros,$idMinisterio,$nivel)
    {
        $lista = substr(str_replace(';', ",", $miembros), 0, -1);
        $listaMiembros = \App\Miembros::select('id','nombre','apellido1','apellido2','telefonoFijo','telefonoMovil','email')->whereIn('id',explode(',', $lista))->get();
        $salida = '';    
        foreach ($listaMiembros as $miembro) {
            $salida .= '<span class="float-left">'.$miembro->nombre.' '.$miembro->apellido1.'&nbsp; <i style="cursor: pointer;" idMiembro="'.$miembro->id.'" idMinisterio="'.$idMinisterio.'"  nivel="'.$nivel.'" class="excluirMiembro text-danger fas fa-user-times"></i>'.'</span><br>';
        }

        return $salida;
    }


    public function borrarMinisterio(Request $request)
    {   
        if ($request->nivel == 1){
            \App\Ministerios::destroy($request->idMinisterio);
        }else{
            \App\SubMinisterios::destroy($request->idMinisterio);
        }
        
        return response()->json( array('success' => true, 'mensaje'=> 'Ministerio borrado exitosamente') );

    } 


}
