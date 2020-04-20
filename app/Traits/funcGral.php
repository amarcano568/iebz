<?php
 
namespace App\Traits;
use \Auth;
use \DB;

trait funcGral {
 
    public function BaseDatosEmpresa() {
		return Auth::user()->BaseDatos;
    }

    public function getNextID($table) 
	{
	 $statement = DB::select("show table status like '".$table."'");
	 return $statement[0]->Auto_increment;
	}
 
}