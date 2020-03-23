<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Usuarios extends Model
{
    protected $table = 'users';
   // protected $primaryKey = 'idMotivos';
    
    protected function Guardar($request,$empresa,$idEmpresa)
    {    	
        if ( is_null($request->idUsuario) ){     
            $user  = new \App\Usuarios();
            $user->password           = Hash::make('12345678');            
			$user->changePassword     = 'S';
			$user->email              = $request->correo;
        }else{
            $user  = \App\Usuarios::find($request->idUsuario);    
        }

		$user->name       = $request->nombres;
		$user->lastName   = $request->apellidos;
		$user->Telefono   = $request->telefono;	
		$user->cargo      = $request->cargo;		
		$user->rol        = $request->rol;		
		$user->status     = $request->estado;
        return $user->save();

    }

}
