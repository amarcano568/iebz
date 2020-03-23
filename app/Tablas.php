<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tablas extends Model
{
    protected $table = 'tablas';
    protected $primaryKey = 'idTabla';
    
    protected function Guardar($request,$BD,$areas)
    {    	
        if ( $request->idTipo == 0 ){     
            $tipTicket = new Tablas;
        	$tipTicket->setConnection($BD);
        	$tipTicket->tipo   = 'TPTK';
        	$tipTicket->idTabla   = \App\Tablas::on($BD)->where('tipo','=','TPTK')->count()+1;
        }else{
            $tipTicket  = \App\Tablas::on($BD)->where('tipo','=','TPTK')->where('idTabla','=',$request->idTipo)->get()->first();
        }

		$tipTicket->desTabla   = $request->descTipo;
		$tipTicket->activo     = $request->statusTicket;	
		$tipTicket->valString3 = $areas;		
		$tipTicket->interno    = $request->interno2;
		$tipTicket->externo    = $request->externo2;	
		
        return $tipTicket->save();

    }
}
