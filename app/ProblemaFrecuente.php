<?php

namespace App;
use SubCategoria_PrbFrecuente;
use \DB;
use Illuminate\Database\Eloquent\Model;

class ProblemaFrecuente extends Model
{
    protected $table = 'prbfrecuente';  
    protected $primaryKey = 'idPrbFrecuente';

    protected function Guardar($request,$empresa,$subCategoria)
    {    	
        try {
            DB::beginTransaction(); 

            $pbrFrecuente  = new \App\ProblemaFrecuente();
            $pbrFrecuente->setConnection($empresa);

			$pbrFrecuente->desPrbFrecuente = $request->newSolution;
			$pbrFrecuente->activo          = 1;
			$pbrFrecuente->idSubCategoria  = $subCategoria;
			$pbrFrecuente->save();
			$id = $pbrFrecuente->idPrbFrecuente;

			$sub_cat_pbrFrecuente  = new \App\SubCategoria_PrbFrecuente();
            $sub_cat_pbrFrecuente->setConnection($empresa);
			$sub_cat_pbrFrecuente->idSubCategoria = $subCategoria;
			$sub_cat_pbrFrecuente->idPrbFrecuente = $id;
			$sub_cat_pbrFrecuente->activo         = 1;
			$sub_cat_pbrFrecuente->save();

			\App\Auditoria::guardar($id,$empresa,'ProblemaFrecuente','insertar');

	        return $id;
	        		     
		}catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }

    }
}
