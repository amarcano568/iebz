<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activos extends Model
{
    protected $table = 'activo';  
    protected $primaryKey = 'idActivo';

     protected function Guardar($request,$empresa)
    {    	
        
        $activo = new Activos;
        $activo->setConnection($empresa);

		$activo->codigoActivo        = $request->CodActivo;
		$activo->nroSerie            = $request->SerActivo;	
		$activo->tipoActivo          = $request->tipActivo;		
		$activo->descripcion         = $request->DesActivo;
		$activo->descripcionCompleta = $request->DescActCompleta;	
		$activo->activo              = 1;
        $activo->save();

        $id = $activo->idActivo;

        \App\Auditoria::guardar($id,$empresa,'activo','insertar');

        return $id;


    }
}
