<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paises extends Model
{
    protected $table = 'paises';

     protected function Guardar($request)
    {    	
        if ( is_null($request->idPais) ){     
            $pais = new Paises;
        	//$pais->descpais= $request->descpais;
        }else{
            $pais  = \App\Paises::find($request->idPais);    
        }
		
		$pais->nombre = $request->nombre;
		$pais->status = $request->status;	
		
        return $pais->save();

    }

}
