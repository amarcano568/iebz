<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategorias extends Model
{
    protected $table = 'subcategoria';  
    protected $primaryKey = 'idSubCategoria';

     protected function Guardar($request,$empresa)
    {    	
        if ( is_null($request->IdSubCategoria) ){     
            $subCategoria  = new SubCategorias();
            $subCategoria->setConnection($empresa);
            
        }else{
            $subCategoria  = \App\SubCategorias::on($empresa)->find($request->IdSubCategoria);    
        }

		$subCategoria->desSubCategoria = $request->InputNombreSubCategoria;
		$subCategoria->idCategoria     = $request->IdCategoriaAux;	
		$subCategoria->activo          = $request->SelectStatusSub;		
		
        return $subCategoria->save();

    }
}
