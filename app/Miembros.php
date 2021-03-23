<?php

namespace App;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Miembros extends Model
{
    protected $table = 'miembros';

    protected function Guardar($request)
    {    	
        if ( is_null($request->idMiembro) ){     
            $miembro  = new \App\Miembros();
			$miembro->email = $request->email;
        }else{
            $miembro  = \App\Miembros::find($request->idMiembro);    
        }
		$miembro->idIglesia = $request->idIglesia;
		$miembro->nombre = $request->nombre;
		$miembro->apellido1 = $request->apellido1;
		$miembro->apellido2 = $request->apellido2;
		$miembro->email = $request->email;
		$miembro->tipoDocumento = $request->tipoDoc;
		$miembro->nroDocumento = $request->nroDocumento	;
		$miembro->direccion = $request->direccion;
		$miembro->codigoPostal = $request->codPostal;
		$miembro->sexo = $request->sexo;
		$miembro->comunidad = $request->comunidad;
		$miembro->provincia = $request->provincia;
		$miembro->poblacion = $request->poblacion;
		$miembro->telefonoFijo = $request->telFijo;
		$miembro->telefonoMovil = $request->telMovil;		
		$miembro->fecNacimiento = $request->fecNacimiento;
		$miembro->fecha_alta = $request->fecAlta;
		$miembro->fecha_baja = $request->fecBaja;
		$miembro->lugarNacimiento = $request->lugNacimiento;
		$miembro->paisNacimiento = $request->pais;
		$miembro->profesion = $request->profesion;
		$miembro->fecBautismo = $request->fecBautismo;
		$miembro->iglesiaBautismo = $request->iglesiaBautismo;
		$miembro->fecCartaTraslado = $request->cartaTraslado;
		$miembro->iglesiaProcedencia = $request->iglesiaProcedencia;
		$miembro->otrosDatos = $request->otrosDatos;
		$miembro->status = $request->status;
		$miembro->datos_personales = $request->has("datos_personales") ? 1 : 0;
		$miembro->imagenes_personales =$request->has("imagenes_personales") ? 1 : 0;

        return $miembro->save();

    }

	public static function age($birth)
    {
        
        return Carbon::parse($birth)->age;

    }
    
}
