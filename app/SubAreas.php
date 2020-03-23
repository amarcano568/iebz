<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubAreas extends Model
{
    protected $table = 'subarea';
    protected $primaryKey = 'idSubArea';

    protected function Guardar($request,$empresa)
    {    	
        if ( is_null($request->IdSubArea) ){     
            $subArea = new SubAreas;
        	$subArea->setConnection($empresa);
        	$subArea->idArea = $request->IdAreaAux;
        }else{
            $subArea  = \App\SubAreas::on($empresa)->find($request->IdSubArea);    
        }
		
		$subArea->descSubArea = $request->InputNombreSubArea;
		$subArea->activo      = $request->SelectStatusSub;	
		
        return $subArea->save();

    }
}
