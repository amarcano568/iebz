<?php

namespace App;
use Carbon\Carbon;
use Auth;
use Auditoria;
use Tickets;
use \DB;
use Illuminate\Database\Eloquent\Model;

class Ticket_Anulacion extends Model
{
    protected $table = 'ticket_anulacion';
    protected $primaryKey = 'nroTicket';

    protected function Guardar($request,$empresa)
    {    	
        try {
            DB::beginTransaction(); 

            $anularTicket  = new \App\Ticket_Anulacion();
            $anularTicket->setConnection($empresa);
	        $now = Carbon::now();

			$anularTicket->nroTicket      = $request->nroTicket;
			$anularTicket->idUsu          = Auth::id();
			$anularTicket->ctaUsu         = Auth::user()->email;
			$anularTicket->fecharegistro  = $now;
			$anularTicket->comentario     = $request->DescripcionAnular;
			$anularTicket->estado         = 3;
			$anularTicket->estadoAnterior = $request->estadoTickecAnt;
			$anularTicket->save();

			$ticket = \App\Tickets::on($empresa)->find($request->nroTicket);
			$ticket->estado = 3;
			$ticket->save();

	        return \App\Auditoria::guardar($ticket->nroTicket,$empresa,'Ticket','Anular Ticket');
	        		     
		}catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }

    }
}
