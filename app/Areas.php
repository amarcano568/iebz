<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    protected $table = 'area';  
    protected $primaryKey = 'idArea';

    protected function Guardar($request,$empresa)
    {    	
        if ( is_null($request->IdAreaX) ){     
            $area = new Areas;
        	$area->setConnection($empresa);
        }else{
            $area  = \App\Areas::on($empresa)->find($request->IdAreaX);    
        }

		$area->descArea = $request->InputNombreArea;
		$area->activo   = $request->SelectStatus;	
		
        return $area->save();

    }
}
