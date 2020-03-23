<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    protected $table = 'categoria';  
    protected $primaryKey = 'idCategoria';

     protected function Guardar($request,$empresa)
    {    	
        if ( is_null($request->IdCategoria) ){     
            $categoria = new Categorias;
        	$categoria->setConnection($empresa);
        }else{
            $categoria  = \App\Categorias::on($empresa)->find($request->IdCategoria);    
        }

		$categoria->descCategoria = $request->InputNombreCategoria;
		$categoria->idArea        = $request->SelectArea;	
		$categoria->activo        = $request->SelectStatus;		
		$categoria->Tipo          = 'TPTK'.$request->SelectTipo;	
		
        return $categoria->save();

    }
}
