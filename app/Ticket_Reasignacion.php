<?php

namespace App;
use Carbon\Carbon;
use Auth;
use \DB;
use Tickets;

use Illuminate\Database\Eloquent\Model;

class Ticket_Reasignacion extends Model
{
    protected $table = 'ticket_reasignacion';
    protected $primaryKey = 'idReasigna';

    protected function cambiarAreaTicket($request,$empresa,$ticket)
    {    	
        
         try {
            DB::beginTransaction(); 
            $now = Carbon::now();

	        $reasigna_ticket = new Ticket_Reasignacion;
	        $reasigna_ticket->setConnection($empresa);

			$reasigna_ticket->nroTicket     = $request->nroTicket;
			$reasigna_ticket->idUsuAdmin    = Auth::id();	
			$reasigna_ticket->ctaUsuAdmin   = Auth::user()->email;		
			$reasigna_ticket->idArea        = $ticket->idArea;
			$reasigna_ticket->idAreaDest    = $request->Area;
			$reasigna_ticket->comentario    = $request->DescripcionReAsignar;
			$reasigna_ticket->fechaRegistro = $now;
			$reasigna_ticket->estado        = $request->estado;	
			$reasigna_ticket->estadoAnt     = $request->estadoTickecAnt;	
			$reasigna_ticket->save();

			$ticket = \App\Tickets::on($empresa)->find($request->nroTicket);
			$ticket->estado         = $request->estado;
			$ticket->fechaEstado    = $now;
			$ticket->idArea         = $request->Area;
			$ticket->idCategoria    = $request->CategoriaX;
			$ticket->idSubCategoria = $request->SubCategoria;
			$ticket->tipo           = $request->TipoTicket;
			$ticket->save();
         
		}catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }


    }
}
