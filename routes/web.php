<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;
Auth::routes();

Route::group(['middleware' => 'auth'], function (){
	
    //Route::get('/', 'inicioController@getInicio');
    Route::get('/', 'inicioController@getInicio')->name('dashboard')
		->middleware('permission:dashboard');
	//Route::get('/home', 'inicioController@getInicio');
	Route::get('/home', 'inicioController@getInicio')->name('dashboard')
		->middleware('permission:dashboard');
	
	Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout'); 
	Route::get('default-base-datos', 'inicioController@defaultBaseDatos'); 
	Route::get('usuario-bloqueado', 'mantenimientoController@usuarioBloqueado');
	Route::get('usuario-bloqueado', function () {		
	    return view('auth.usuario-bloqueado');
	});

	/**
	 *   			Miembros
	 */
	Route::get('miembros', 'miembrosController@loadMiembros')->name('miembros')->middleware('permission:miembros');
	Route::get('listar-miembros', 'miembrosController@listarMiembros');
	//Route::get('listar-miembros', 'miembrosController@listarMiembros')->name('listar-miembros')->middleware('permission:listar-miembros');
	Route::get('get-provincias', 'miembrosController@listarProvincias');
	Route::get('calcular-fecha-nacimiento', 'miembrosController@calcularFechaNacimiento');
	Route::get('editar-miembro', 'miembrosController@editarMiembro');
	Route::post('registrar-miembro', 'miembrosController@registrarMiembro');
	Route::post('subir-foto', 'miembrosController@subirFotoMiembro');
	Route::post('subir-documento', 'miembrosController@subirDocumento');
	Route::get('borrar-documento', 'miembrosController@borrarDocumento');
	Route::get('agregar-familiar-existente', 'miembrosController@agregarFamiliarExistente');
	Route::get('eliminar-parentesco', 'miembrosController@eliminarParentesco');
	Route::get('asignar-foto', 'miembrosController@asignarFoto');
	Route::get('imprimir-ficha', 'miembrosController@imprimirFicha');
	Route::get('eliminar-miembro', 'miembrosController@eliminarMiembro');
	Route::get('generar-excel-miembros', 'miembrosController@generarExcelMiembros');

	
	
	
	/**
	 *  		Mantenimiento
	 */
	Route::get('mantUsuarios', 'mantenimientoController@loadUsuarios')->name('mantUsuarios')->middleware('permission:mantUsuarios');
	Route::get('carga-Usuarios', 'mantenimientoController@cargaUsuarios');
	Route::post('subir-foto-perfil', 'miembrosController@subirFotoPerfil');
	Route::get('buscar-imagen-usuario', 'mantenimientoController@buscarImagenUsuario');
	Route::get('profesiones', 'mantenimientoController@Profesiones')->name('profesiones')->middleware('permission:profesiones');
	Route::get('listar-profesiones', 'mantenimientoController@listarProfesiones');
	Route::get('actualizar-status-profesion', 'mantenimientoController@actualizarStatusProfesion');
	Route::get('editar-profesion', 'mantenimientoController@editarProfesion');
	Route::post('registrar-profesion', 'mantenimientoController@registrarProfesion');
	Route::post('registrar-profesion-miembros', 'mantenimientoController@registrarProfesionMiembros');
	Route::get('paises', 'mantenimientoController@paises')->name('paises')->middleware('permission:paises');
	Route::get('listar-paises', 'mantenimientoController@listarPaises');
	Route::get('actualizar-status-pais', 'mantenimientoController@actualizarStatusPais');
	Route::get('editar-pais', 'mantenimientoController@editarPais');
	Route::post('registrar-pais', 'mantenimientoController@registrarPais');


	/**
	 * Informes
	 */
	Route::get('report-cumpleanos', 'informesController@reportCumpleanos')->name('report-cumpleanos')->middleware('permission:report-cumpleanos');
	Route::get('listar-cumpleanos', 'informesController@listarCumpleanos');
	Route::get('report-rango-edades', 'informesController@reportRangoEdades')->name('report-rango-edades')->middleware('permission:report-rango-edades');
	Route::get('report-nacionalidad', 'informesController@reportNacionalidad')->name('report-nacionalidad')->middleware('permission:report-nacionalidad');
	Route::get('listar-rango-edad', 'informesController@listarRangoEdad');
	Route::get('listar-nacionalidades', 'informesController@listarNacionalidades');
	Route::get('informe-miembros', 'informesController@informeMiembros')->name('informe-miembros')->middleware('permission:informe-miembros');
	Route::get('listado-miembros', 'informesController@listarMiembros');
	Route::get('informe-ministerios', 'ministeriosController@informeMinisterios')->name('informe-ministerios')->middleware('permission:informe-ministerios');
	Route::get('listar-informe-ministerios', 'informesController@listarInformeMinisterios');
	Route::get('delete-file-pdf', function (Request $request) {
		unlink($request->fileDelete);
	});

	/****** Prueb */
	
	/**
	 * 					Ministerios
	 */
	//Route::get('ministerios', 'ministeriosController@Ministerios');
	Route::get('ministerios', 'ministeriosController@Ministerios')->name('ministerios')->middleware('permission:ministerios');
	Route::get('listar-ministerios', 'ministeriosController@listarMinisterios');
	Route::get('excluir-miembro', 'ministeriosController@excluirMiembro');
	Route::get('agregar-miembro-ministerio', 'ministeriosController@agregarMiembroMinisterio');
	Route::get('buscar-foto-miembro', 'ministeriosController@buscarFotoMiembro');
	Route::get('incluir-miembro-ministerio', 'ministeriosController@incluirMiembroMinisterio');
	Route::get('bloquear-ministerio', 'ministeriosController@bloquearMinisterio');
	Route::get('buscar-ministerio', 'ministeriosController@buscarMinisterio');
	Route::post('registrar-ministerio', 'ministeriosController@registrarMinisterio');
	Route::get('informe-ministerios', 'ministeriosController@informeMinisterios');
	Route::get('listar-informe-ministerios', 'informesController@listarInformeMinisterios');
	Route::get('crear-organigrama', 'ministeriosController@crearOrganigrama');
	Route::get('borrar-ministerio', 'ministeriosController@borrarMinisterio');


	/**
	 * Relacionar Generos
	 */
	//Route::get('relacionar-generos', 'miembrosController@relacionarGeneros');
	Route::get('relacionar-generos', 'miembrosController@relacionarGeneros')->name('relacionar-generos')->middleware('permission:relacionar-generos');
	Route::get('listar-miembros-generos', 'miembrosController@listarMiembrosGeneros');
	Route::get('asignar-genero', 'miembrosController@asignarGenero');

	/**
	 *  	Info pagina Inicial
	 */
	Route::get('load-info', 'mantenimientoController@loadInfo');






	/**
	 *   Usuarios
	 */
	//Route::get('mantUsuarios', 'mantenimientoController@loadUsuarios');
	
	
	Route::post('registrar-usuario', 'mantenimientoController@registrarUsuario');
	Route::get('buscar_usuario', 'mantenimientoController@buscarUsuario');
	Route::get('verifica-licencia', 'mantenimientoController@verificaLicencia');
	Route::get('bloquear_usuario', 'mantenimientoController@bloquearUsuario');
	
	/**
	 *   Empresa
	 */
	//Route::get('mantEmpresa', 'mantenimientoController@loadEmpresa');
	Route::get('mantEmpresa', 'mantenimientoController@loadEmpresa')->name('mantEmpresa')
		->middleware('permission:mantEmpresa');
	Route::post('actualiza-empresa', 'mantenimientoController@actualizaEmpresa');	


	

	/**
	 *   Administración de Soporte
	 */

	// Route::get('admin-Soporte', function () {
	// 	$usuarios = \App\Usuarios::->where(function($q) use ($variable){
	// 		          $q->where('Cab', 'AGE')
	// 		            ->orWhere('Cab','ADM');
	// 		      	})->where('idArea',$idArea)->where('status',1)->get();
	//     return view('adminSoporte.adminSoporte',$data);
	// });

	//Route::get('admin-Soporte', 'soporteController@adminSoporte');
	

	/**
	 * 			Perfil del Usuario
	 */
	Route::get('perfil-usuario', 'mantenimientoController@perfilUsuario');
	Route::get('listar-tickets-perfil', 'mantenimientoController@listarTicketsPerfil');
	
	

	/**
	 * 		Roles
	 */
	Route::post('roles/store', 'RoleController@store')->name('roles.store')
		->middleware('permission:roles.create');
	Route::get('roles', 'RoleController@index')->name('roles.index')
		->middleware('permission:roles.index');
	Route::get('roles/create', 'RoleController@create')->name('roles.create')
		->middleware('permission:roles.create');
	Route::put('roles/{role}', 'RoleController@update')->name('roles.update')
		->middleware('permission:roles.edit');
	Route::get('roles/{role}', 'RoleController@show')->name('roles.show')
		->middleware('permission:roles.show');
	Route::delete('roles/{role}', 'RoleController@destroy')->name('roles.destroy')
		->middleware('permission:roles.destroy');
	Route::get('roles/{role}/edit', 'RoleController@edit')->name('roles.edit')
		->middleware('permission:roles.edit');
	Route::get('role.buscar', 'RoleController@burcarRole')->name('role.buscar')
		->middleware('permission:roles.index');	

	/**
	 * 		Roles de Usuarios
	 */
	Route::get('users', 'UserController@index')->name('users.index')
		->middleware('permission:users.index');
	Route::put('users/{user}', 'UserController@update')->name('users.update')
		->middleware('permission:users.edit');
	Route::get('users/{user}', 'UserController@show')->name('users.show')
		->middleware('permission:users.show');
	Route::delete('users/{user}', 'UserController@destroy')->name('users.destroy')
		->middleware('permission:users.destroy');
	Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit')
		->middleware('permission:users.edit');
});
