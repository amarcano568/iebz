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


class reportesController extends Controller
{
	use funcGral;

    public function reportTicketEnviados()
    {
        $conn = $this->BaseDatosEmpresa();
        $areas = \App\Areas::on($conn)->get();      
        $usuarios = \App\Usuarios::where('BaseDatos',$conn)->where('status','=',1)->get();
        
        $data = array(  
                        'areas' => $areas,
                        'usuarios' => $usuarios
                     );

        return view('reportes.reporte-ticktes-enviados',$data);
    }

    public function listadosTicketsEnviados(Request $request)
    {
        try {
            DB::beginTransaction();   
			$conn    = $this->BaseDatosEmpresa();
			$idUser = $request->idIuser;
			$grupo   = $request->grupo;
			$desde   = \Carbon\Carbon::createFromFormat('d/m/Y', $request->desde)->format('Y-m-d');
			$hasta   = \Carbon\Carbon::createFromFormat('d/m/Y', $request->hasta)->format('Y-m-d');
		
            $conectar = DB::connection($conn);
            $ticketsUsuarios = $conectar->select("call lista_ticketsUsuariosReportes($idUser,$grupo,'$desde','$hasta')");
            
            $dataSet = array (
                "sEcho"                 =>  0,
                "iTotalRecords"         =>  1,
                "iTotalDisplayRecords"  =>  1,
                "aaData"                =>  array () 
            );

	        foreach($ticketsUsuarios as $ticket)
	        {
	        	

	            if ($ticket->prioridad == 1){
	                $labelPrioridad = '<span class="badge badge-pill badge-warning">Baja</span>';
	            }else if ($ticket->prioridad == 2){
	                $labelPrioridad = '<span class="badge badge-pill badge-success">Normal</span>';
	            }else if ($ticket->prioridad == 3){
	                $labelPrioridad = '<span class="badge badge-pill badge-danger">Alta</span>';
	            }

	            $dataSet['aaData'][] = array(   '<h6 class="text-primary">TICKETS CON ESTADO '.strtoupper($ticket->des2Tabla).'</h6>',
	            								$ticket->nroTicket,
	            								$ticket->solicitante,
	                                            $ticket->ejecutor,
	                                            substr($ticket->Titulo,0,70),
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

    public function graficoReporteTicketRecibido(Request $request)
    {
        $conn    = $this->BaseDatosEmpresa();
		$idUser = $request->idIuser;
		$desde   = \Carbon\Carbon::createFromFormat('d/m/Y', $request->desde)->format('Y-m-d');
		$hasta   = \Carbon\Carbon::createFromFormat('d/m/Y', $request->hasta)->format('Y-m-d');
		
        $conectar = DB::connection($conn);
        $ticketsRecibidos = $conectar->select("call lista_ticketsRecibidosResumen($idUser,'$desde','$hasta')");
       
        return response()->json( array('success' => true, 'mensaje'=> 'Procesado con exito', 'data' => $ticketsRecibidos) );
    }

    public function reporteEstadisticas()
    {
        $conn = $this->BaseDatosEmpresa();
        $areas = \App\Areas::on($conn)->get();      
        $usuarios = \App\Usuarios::where('BaseDatos',$conn)->where('status','=',1)->get();
        
        $data = array(  
                        'areas' => $areas,
                        'usuarios' => $usuarios
                     );

        return view('reportes.reporte-estadistica',$data);
    }

}
