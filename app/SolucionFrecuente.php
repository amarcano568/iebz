<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolucionFrecuente extends Model
{
    protected $table = 'solufrecuente';  
    protected $primaryKey = 'idSoluFrecuente';

    protected function Guardar($request,$empresa)
    {    	
        if ( is_null($request->idAplicacion) ){     
            $solFrecuente = new SolucionFrecuente;
        	$solFrecuente->setConnection($empresa);
        }else{
            $solFrecuente  = \App\SolucionFrecuente::on($empresa)->find($request->idAplicacion);    
        }

		$solFrecuente->descripcion    = $request->InputNombreAplicacion;
		$solFrecuente->idSubCategoria = $request->IdSubCategoriaAplicacion;	
		$solFrecuente->activo         = $request->SelectStatusAplicacion;		
	
        return $solFrecuente->save();

    }
}
