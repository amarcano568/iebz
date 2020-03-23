<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargos extends Model
{
    protected $table = 'cargo';
    protected $primaryKey = 'idCargo';
    public $timestamps = false;

     protected function Guardar($request,$empresa)
    {    	
        if ( is_null($request->idCargo) ){     
            $cargo = new Cargos;
        	$cargo->setConnection($empresa);
        	//$cargo->descCargo= $request->descCargo;
        }else{
            $cargo  = \App\Cargos::on($empresa)->find($request->idCargo);    
        }
		
		$cargo->descCargo = $request->descCargo;
		$cargo->idSubArea =	0;
		$cargo->activo    = $request->statusCargo;	
		
        return $cargo->save();

    }
}
