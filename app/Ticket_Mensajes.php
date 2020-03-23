<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use Auditoria;

class Ticket_Mensajes extends Model
{
    protected $table = 'ticket_mensajes';
    protected $primaryKey = 'idMsg';

    protected function Guardar($request,$empresa)
    {    	
 
        $msg = new Ticket_Mensajes;
        $msg->setConnection($empresa);

        $now = Carbon::now();
     
		$msg->nroTicket = $request->nroTicket;
		$msg->fechaMsg  = $now;
		$msg->mensaje   = $request->mensaje;
		$msg->idUsuCta  = Auth::id();
		$msg->ctaUsu    = Auth::user()->email;
		$msg->rol       = Auth::user()->rol;;
					
        if ( $msg->save() ){
        	return \App\Auditoria::guardar($msg->nroTicket,$empresa,'ticket-mensaje','insertar');
        	
        }else{
        	return false;
        }

    }

}
