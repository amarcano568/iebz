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
use Tickets;
use Ticket_Anulacion;
use Ticket_Programacion;
use Ticket_Reasignacion;
use ProblemaFrecuente;
use Correos;
use Ticket_Atencion;
use File;
use App\Traits\funcGral;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Container\Container;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Mailable;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;

class correoController extends Controller
{
	use funcGral;
	
    public function enviarCorreo(Request $request)
    {
       	try {
	        $conn = $this->BaseDatosEmpresa();
	        $empresa = \App\Empresas::find(Auth::user()->id_Empresa);
	        $Area = Auth::user()->idArea;
	        $ticket = \App\Tickets::on($conn)->find($request->ticketNro);
	        $estado = \App\Tablas::on($conn)->where('tipo','EST')->find($request->tipo);
	        
	        $usuario = \App\Usuarios::find($ticket->idUsuTicket);
	        // Busca Agente asignado.
	        if (!is_null($ticket->idUsuEjecutor)){
	        	$agente = \App\Usuarios::find($ticket->idUsuEjecutor);
	        	$ticket['nombreAgente'] = $agente->name.' '.$agente->lastName;
	        }
	        
	        $categoria = \App\Categorias::on($conn)->find($ticket->idCategoria);
	        $ticket['nombreSolicitante'] = $usuario->name.' '.$usuario->lastName;
	        $ticket['nombreCategoria'] = $categoria->descCategoria;
	        $ticket['estado'] = $estado->des2Tabla;

	        $emailTo = Auth::user()->email;

	        $adminArea = \App\Usuarios::where('idArea',$ticket->idArea)->where('rol','ADM')->get();
	       
	        $configEmail = \App\Correos::on($conn)->find(1);			
			$smtp_host_ip = gethostbyname($configEmail->smtp);
	        $transport = (new Swift_SmtpTransport($smtp_host_ip, $configEmail->port, $configEmail->encryption))
	            ->setUsername($configEmail->correo)
	            ->setPassword($configEmail->password);
	        $mailer = new Swift_Mailer($transport);

	        switch ($request->tipo) {
			    case 1: //Solicitud Soporte
			        $dataEmail = $this->solicitudSoporte($request,$empresa,$Area,$ticket);
			        break;
		    	case 2: //Asignar Ticket
			        $dataEmail = $this->asignarAgente($request,$empresa,$Area,$ticket);
			        break;
			    case 3: //Anular Ticket
			    	$anulado = \App\Ticket_Anulacion::on($conn)->find($request->ticketNro);
			    	$ticket['motivoAnulacion'] = $anulado->comentario;
			        $dataEmail = $this->anularTicket($request,$empresa,$Area,$ticket);
			        break;
			    case 4: //Derivar Ticket
			        $dataEmail = $this->derivarTicket($request,$empresa,$Area,$ticket);
			        break;
			    case 5: //Iniciar atención.
			        $dataEmail = $this->iniciarAtencion($request,$empresa,$Area,$ticket);
			        break;
			    case 8: //Ticket terminado.
			    	$atencion = \App\Ticket_Atencion::on($conn)->where('nroTicket',$request->ticketNro)->where('estado',8)->get()->first();
		    		$ticket['solucion'] = $atencion->comentarioAtencion;			
			        $dataEmail = $this->ticketTerminado($request,$empresa,$Area,$ticket);
			        break;
			}

	        $mensaje=array( 
	        	'cabezera' => $dataEmail['cabezera'],
	        	'cuerpo' => $dataEmail['cuerpo'],
	        	'piePagina' => $dataEmail['piePagina']
	        );

	        $data=array('information'=>$mensaje);

	        $message   = (new Swift_Message($dataEmail['asunto']))
	            ->setFrom($configEmail->correo, 'SERVICE DESK')
	            ->setTo($emailTo)
	            ->setBody(view('correos.plantilla', $data)->render(),'text/html');

	        foreach ($adminArea as $admin )
			{
			    $message->setCc([$admin->email => $admin->name.' '.$admin->lastName]);
			}

	        return $mailer->send($message);

		} catch (Exception $e) {
			echo $e->getMessage();
		}

    }

    private function derivarTicket($request,$empresa,$Area,$ticket)
    {	
    	
    	$asunto = 'Help Desk Ticket: '.$request->ticketNro.' derivado.';
    	$cabezera = 'Su solicitud con el Ticket: <b>' .$request->ticketNro. '</b> ha sido derivado.';

		$cuerpo = '<table>
				        <tbody>
				            <tr>
				            	<td>Usuario Solicitante:</td>
				            	<td><b>'.$ticket->nombreSolicitante.'</b></td>
				            </tr>
				            <tr>
				            	<td>Agente de atención:</td>
				            	<td><b>'.$ticket->nombreAgente.'</b></td>
				            </tr>
				            <tr>
				            	<td>Descripción de la solicitud:</td>
				            	<td><b>'.$ticket->titulo.'</b></td>
				            </tr>
				            <tr>
				            	<td>Categoría:</td>
				            	<td><b>'.$ticket->nombreCategoria.'</b></td>
				            </tr>
				            <tr>
				            	<td>Estado:</td>
				            	<td><b>'.$ticket->estado.'</b></td>
				            </tr>
				        </tbody>
				    </table>';

		$piePagina = 'Comunicarse con el personal solicitante de la atención, para brindarle el soporte requerido.';

		return array(	'asunto' => $asunto,
						'cabezera' => $cabezera,
						'cuerpo' => $cuerpo,
						'piePagina' => $piePagina
					);
    }

    private function ticketTerminado($request,$empresa,$Area,$ticket)
    {	
    
    	$asunto = 'Help Desk Ticket: '.$request->ticketNro.' terminado.';
    	$cabezera = 'Su solicitud con el Ticket: <b>' .$request->ticketNro. '</b> ha sido actualizado.';

		$cuerpo = '<table>
				        <tbody>
				            <tr>
				            	<td>Usuario Solicitante:</td>
				            	<td><b>'.$ticket->nombreSolicitante.'</b></td>
				            </tr>
				            <tr>
				            	<td>Agente de atención:</td>
				            	<td><b>'.$ticket->nombreAgente.'</b></td>
				            </tr>
				            <tr>
				            	<td>Descripción de la solicitud:</td>
				            	<td><b>'.$ticket->titulo.'</b></td>
				            </tr>
				            <tr>
				            	<td>Categoría:</td>
				            	<td><b>'.$ticket->nombreCategoria.'</b></td>
				            </tr>
				            <tr>
				            	<td>Estado:</td>
				            	<td><b>'.$ticket->estado.'</b></td>
				            </tr>
				            <tr>
				            	<td>Solución:</td>
				            	<td><b>'.$ticket->solucion.'</b></td>
				            </tr>
				        </tbody>
				    </table>';

		$piePagina = 'Comunicarse con el personal solicitante de la atención, para brindarle el soporte requerido.';

		return array(	'asunto' => $asunto,
						'cabezera' => $cabezera,
						'cuerpo' => $cuerpo,
						'piePagina' => $piePagina
					);
    }

    private function anularTicket($request,$empresa,$Area,$ticket)
    {	
    	$asunto = 'Help Desk Ticket: '.$request->ticketNro.' anulado.';
    	$cabezera = 'El siguiente Ticket: <b>'.$request->ticketNro.'</b> ,ha sido anulado.';

		$cuerpo = '<table>
				        <tbody>
				            <tr>
				            	<td>Motivo:</td>
				            	<td><b>'.$ticket->motivoAnulacion.'</b></td>
				            </tr>
				            <tr>
				            	<td>Estado:</td>
				            	<td><b>'.$ticket->estado.'</b></td>
				            </tr>
				        </tbody>
				    </table>';

		$piePagina = 'Comunicarse con el personal solicitante de la atención, para brindarle el soporte requerido.';

		return array(	'asunto' => $asunto,
						'cabezera' => $cabezera,
						'cuerpo' => $cuerpo,
						'piePagina' => $piePagina
					);
    }

    private function iniciarAtencion($request,$empresa,$Area,$ticket)
    {	
    	$now = Carbon::now();
    	$asunto = 'Ticket de atención iniciado N°: '.$request->ticketNro;
    	$cabezera = 'Atención del ticket iniciada a las '.$now;

		$cuerpo = '<table>
				        <tbody>
				            <tr>
				            	<td>Usuario Solicitante:</td>
				            	<td><b>'.$ticket->nombreSolicitante.'</b></td>
				            </tr>
				            <tr>
				            	<td>Agente de atención:</td>
				            	<td><b>'.$ticket->nombreAgente.'</b></td>
				            </tr>
				            <tr>
				            	<td>Descripción de la solicitud:</td>
				            	<td><b>'.$ticket->titulo.'</b></td>
				            </tr>
				            <tr>
				            	<td>Categoría:</td>
				            	<td><b>'.$ticket->nombreCategoria.'</b></td>
				            </tr>
				            <tr>
				            	<td>Estado:</td>
				            	<td><b>'.$ticket->estado.'</b></td>
				            </tr>
				        </tbody>
				    </table>';

		$piePagina = 'Comunicarse con el personal solicitante de la atención, para brindarle el soporte requerido.';

		return array(	'asunto' => $asunto,
						'cabezera' => $cabezera,
						'cuerpo' => $cuerpo,
						'piePagina' => $piePagina
					);
    }

    private function asignarAgente($request,$empresa,$Area,$ticket)
    {
    	$asunto = 'Help Desk Ticket: '.$request->ticketNro.' asignado.';
    	$cabezera = 'El ticket # '.$request->ticketNro.' ha sido asignado para su atención.';

		$cuerpo = '<table>
				        <tbody>
				            <tr>
				            	<td>Usuario Solicitante:</td>
				            	<td><b>'.$ticket->nombreSolicitante.'</b></td>
				            </tr>
				            <tr>
				            	<td>Agente de atención:</td>
				            	<td><b>'.$ticket->nombreAgente.'</b></td>
				            </tr>
				            <tr>
				            	<td>Descripción de la solicitud:</td>
				            	<td><b>'.$ticket->titulo.'</b></td>
				            </tr>
				            <tr>
				            	<td>Categoría:</td>
				            	<td><b>'.$ticket->nombreCategoria.'</b></td>
				            </tr>
				            <tr>
				            	<td>Estado:</td>
				            	<td><b>'.$ticket->estado.'</b></td>
				            </tr>
				        </tbody>
				    </table>';

		$piePagina = 'Comunicarse con el personal solicitante de la atención, para brindarle el soporte requerido.';

		return array(	'asunto' => $asunto,
						'cabezera' => $cabezera,
						'cuerpo' => $cuerpo,
						'piePagina' => $piePagina
					);
    }

    private function solicitudSoporte($request,$empresa,$Area,$ticket)
    {
    	$asunto = 'Help Desk Ticket: '.$request->ticketNro.' registrado.';
    	$cabezera = 'Gracias por ponerse en contacto con la mesa de ayuda de <strong>'.$empresa->NombreEmpresa.'</strong>, para esta solicitud de servicio se asignó el <strong>TICKET</strong>: '.$request->ticketNro;

		$cuerpo = '<table id="tableCategorias" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
		        <tbody id="bodyCategorias">
		            <tr>
		            	<td>Usuario Solicitante:</td>
		            	<td>'.Auth::user()->name.' '.Auth::user()->lastName.'</td>
		            </tr>
		            <tr>
		            	<td>Descripción de la solicitud:</td>
		            	<td>'.$ticket->titulo.'</td>
		            </tr>
		        </tbody>
		    </table>';

		$piePagina = 'En breve nuestro personal se pondrá en contacto con usted, para brindarle el soporte requerido.';

		return array(	'asunto' => $asunto,
						'cabezera' => $cabezera,
						'cuerpo' => $cuerpo,
						'piePagina' => $piePagina
					);
    }

		
}
