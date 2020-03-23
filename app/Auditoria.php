<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
class Auditoria extends Model
{
    protected $table = 'auditoria';  

    protected function Guardar($idAuditoria,$empresa,$tabla,$evento)
    {    	
        
        $auditoria = new Auditoria;
        $auditoria->setConnection($empresa);

        $now = Carbon::now();        
        
		$auditoria->tabla        = $tabla;
		$auditoria->evento       = $evento;
		$auditoria->idInt        = $idAuditoria;
		$auditoria->idUsuario    = Auth::id();
		$auditoria->idCtaUsuario = Auth::user()->email;
		$auditoria->fechaEvento  = $now;
	
        return $auditoria->save();

    }
}
