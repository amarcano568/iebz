<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use Auditoria;
class Tickets extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'nroTicket';

    protected function Guardar($request,$empresa)
    {    	
 
        $ticket = new Tickets;
        $ticket->setConnection($empresa);

        $now = Carbon::now();
        
		$ticket->tipo               = $request->TipoTicket;
		$ticket->idCategoria        = $request->CategoriaX;
		$ticket->idArea             = $request->Area;
		$ticket->idSubCategoria     = $request->SubCategoria;
		$ticket->idPrbFrecuente     = $request->Problemas;
		$ticket->prioridad          = $request->TipoPrioridad;
		$ticket->estado             = 1;
		$ticket->fechaEstado        = $now;
		$ticket->idUsuTicket        = Auth::id();
		$ticket->mailUsuTicket      = Auth::user()->email;
		$ticket->mailUsuCopia       = $request->ConCopia;
		$ticket->titulo             = $request->Titulo;
		//$ticket->referencia         = $request->Referencia;
		$ticket->descripcion        = $request->DescPro;
		$ticket->fechaRegistro      = $now;
			
        if ( $ticket->save() ){
        	\App\Auditoria::guardar($ticket->nroTicket,$empresa,'Ticket','Insertar');
        	return $ticket->nroTicket;
        }else{
        	return false;
        }

    }


}
