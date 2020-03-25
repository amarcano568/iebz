<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profesiones extends Model
{
    protected $table = 'profesiones';  

    protected function Guardar($request)
    {    	
        if ( is_null($request->idProfesion) ){     
            $profesion = new Profesiones;
        	//$profesion->descprofesion= $request->descprofesion;
        }else{
            $profesion  = \App\Profesiones::find($request->idProfesion);    
        }
		
		$profesion->nombre = $request->nombre;
		$profesion->status = $request->status;	
		
        return $profesion->save();

    }

}
