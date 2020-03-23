<?php

namespace App;
use Carbon\Carbon;
use Auth;
use Auditoria;
use Tickets;
use \DB;
use Illuminate\Database\Eloquent\Model;

class Ticket_Programacion extends Model
{
    protected $table = 'ticket_programacion';
    protected $primaryKey = 'idProgramado';

    protected function Guardar($request,$empresa)
    {    	
        try {
            DB::beginTransaction(); 

            $asignarTicket  = new \App\Ticket_Programacion();
            $asignarTicket->setConnection($empresa);
	        $now = Carbon::now();

			$asignarTicket->nroTicket     = $request->nroTicket;
			$asignarTicket->idUsuAdmin    = Auth::id();
			$asignarTicket->ctaUsuAdmin   = Auth::user()->email;
			$asignarTicket->idUsuAgen     = $request->IdAgente;
			$asignarTicket->ctaUsuAgen    = $request->CorreoAgente;
			$asignarTicket->estado        = $request->estado;
			$asignarTicket->estadoAnt     = $request->estadoTickecAnt;
			$asignarTicket->fechaRegistro = $now;
			$asignarTicket->save();

			$ticket = \App\Tickets::on($empresa)->find($request->nroTicket);
			$ticket->estado = $request->estado;
			$ticket->fechaEstado = $now;
			$ticket->idUsuEjecutor = $request->IdAgente;
			$ticket->ctaUsuEjecutor = $request->CorreoAgente;
			$ticket->save();

			return true;
	        //return \App\Auditoria::guardar($ticket->nroTicket,$empresa,'Ticket','Anular Ticket');
	        		     
		}catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }

    }

}
