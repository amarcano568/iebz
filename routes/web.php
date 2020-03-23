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
	Route::get('listar-miembros', 'miembrosController@listarMiembros')->name('listar-miembros')->middleware('permission:listar-miembros');
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
	Route::get('report-cumpleanos', 'miembrosController@reportCumpleanos');
	Route::get('listar-cumpleanos', 'miembrosController@listarCumpleanos');
	Route::get('report-rango-edades', 'miembrosController@reportRangoEdades');
	Route::get('listar-rango-edad', 'miembrosController@listarRangoEdad');
	Route::get('mantUsuarios', 'mantenimientoController@loadUsuarios')->name('mantUsuarios')->middleware('permission:mantUsuarios');
	Route::get('carga-Usuarios', 'mantenimientoController@cargaUsuarios');
	Route::post('subir-foto-perfil', 'miembrosController@subirFotoPerfil');
	Route::get('buscar-imagen-usuario', 'mantenimientoController@buscarImagenUsuario');
	Route::get('profesiones', 'mantenimientoController@Profesiones');
	Route::get('listar-profesiones', 'mantenimientoController@listarProfesiones');
	Route::get('actualizar-status-profesion', 'mantenimientoController@actualizarStatusProfesion');
	Route::get('editar-profesion', 'mantenimientoController@editarProfesion');
	Route::post('registrar-profesion', 'mantenimientoController@registrarProfesion');










	/**
	 *   Usuarios
	 */
	//Route::get('mantUsuarios', 'mantenimientoController@loadUsuarios');
	
	
	Route::post('registrar-usuario', 'mantenimientoController@registrarUsuario');
	Route::get('buscar_usuario', 'mantenimientoController@buscarUsuario');
	Route::get('verifica-licencia', 'mantenimientoController@verificaLicencia');
	Route::get('bloquear_usuario', 'mantenimientoController@bloquearUsuario');
	Route::get('interactua-cayro', 'mantenimientoController@interactuaCayro');
	//se agrego para obtener sub_areas
	Route::get('get-sub-area-usuarios', 'mantenimientoController@getSubAreas');
	
	/**
	 *   Empresa
	 */
	//Route::get('mantEmpresa', 'mantenimientoController@loadEmpresa');
	Route::get('mantEmpresa', 'mantenimientoController@loadEmpresa')->name('mantEmpresa')
		->middleware('permission:mantEmpresa');
	Route::post('actualiza-empresa', 'mantenimientoController@actualizaEmpresa');	

	/**
	 *   Mnatenimiento tipo de Tickets
	 */
	//Route::get('mantTickets', 'mantenimientoController@loadMantTicket');
	Route::get('mantTickets', 'mantenimientoController@loadMantTicket')->name('mantTickets')->middleware('permission:mantTickets');
	Route::get('carga-tipos-tickets', 'mantenimientoController@cargaTiposTickets');
	Route::get('llenar-chosen-areas', 'mantenimientoController@llenarChosenAreas');
	Route::post('registrar-mant-ticket', 'mantenimientoController@registrarMantTicket');

	/**
	 *   Mnatenimiento de Categorías y Subcategorías
	 */
	//Route::get('mantCategorias', 'mantenimientoController@loadMantCategorias');
	Route::get('mantCategorias', 'mantenimientoController@loadMantCategorias')->name('mantCategorias')->middleware('permission:mantCategorias');
	Route::get('listar-categoria', 'mantenimientoController@listarCategoria');
	Route::get('listar-aplicacion', 'mantenimientoController@listarAplicacion');
	Route::post('registrar-sub-categoria', 'mantenimientoController@registrarSubCategoria');
	Route::get('Act-Des-SubCategoria', 'mantenimientoController@ActDesSubCategoria');
	Route::post('registrar-aplicacion', 'mantenimientoController@registrarAplicacion');
	Route::get('Act-Des-Categoria', 'mantenimientoController@ActDesCategoria');
	Route::post('registrar-categoria', 'mantenimientoController@registrarCategoria');

	/**
	 *   mantemimiento de Areas y Subareas
	 */
	Route::get('mantAreas', 'mantenimientoController@loadMantAreas')->name('mantAreas')->middleware('permission:mantAreas');
	Route::get('listar-areas', 'mantenimientoController@listarAreas');
	Route::get('Act-Des-Area', 'mantenimientoController@ActDesArea');
	Route::post('registrar-area', 'mantenimientoController@registrarArea');
	Route::post('registrar-sub-area', 'mantenimientoController@registrarSubArea');
	Route::get('Act-Des-SubArea', 'mantenimientoController@ActDesSubArea');


	/**
	 *   Solicitud de Soporte
	 */
	//Route::get('solicitudSoporte', 'soporteController@solicitudSoporte');
	Route::get('solicitudSoporte', 'soporteController@solicitudSoporte')->name('solicitudSoporte')
		->middleware('permission:solicitudSoporte');
	Route::get('listar-tipo_ticket', 'soporteController@listarTipoTicket');
	Route::get('listar-categoria-ticket', 'soporteController@listarCategoriaTicket');
	Route::get('carga-sub-categoria', 'soporteController@cargaSubCategoria');
	Route::get('carga-sub-problemas', 'soporteController@cargaSubProblemas');
	Route::get('listar_resumen_ticket_usuarios', 'soporteController@listarResumenTicketUsuarios');
	Route::post('up-files-support', 'soporteController@upFilesSupport');
	Route::post('enviar-ticket', 'soporteController@enviarTicket');
	Route::get('listar-tickets-usuarios', 'soporteController@listarTicketsUsuarios');
	Route::get('obtener-detalle-ticket', 'soporteController@obtenerDetalleTicket');
	Route::get('anular-ticket', 'soporteController@anularTicket');
	Route::post('encuesta-ticket', 'soporteController@encuestaTicket');

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
	Route::get('admin-Soporte', 'soporteController@adminSoporte')->name('admin-Soporte')
		->middleware('permission:admin-Soporte');
	Route::get('listar-tickets-Gestores', 'soporteController@listarTicketsGestores');
	Route::get('listar_resumen_ticket_gestores', 'soporteController@listarResumenTicket_gestores');
	Route::get('asignar-ticket', 'soporteController@asignarTicket');
	Route::get('iniciar-ticket', 'soporteController@iniciarTicket');
	Route::get('pausar-ticket', 'soporteController@pausarTicket');
	Route::get('pbr-frecuente', 'soporteController@pbrFrecuente');
	Route::get('terminar-ticket', 'soporteController@terminarTicket');
	Route::get('new-solution', 'soporteController@newSolution');
	Route::post('registrar-nuevo-activo', 'soporteController@registrarNuevoActivo');
	Route::post('up-files-support-2', 'soporteController@upFilesSupport2');
	Route::post('cambiar-area-ticket', 'soporteController@cambiarAreaTicket');
	Route::post('reapertura-ticket', 'soporteController@reaperturaTicket');

	/**
	 *  Mensajes
	 */	
	Route::get('enviar-mensaje-ticket', 'soporteController@enviarMensajeTicket');
	Route::get('listar-mensaje-ticket', 'soporteController@listarMensajeTicket');

	/**
	 * 			Perfil del Usuario
	 */
	Route::get('perfil-usuario', 'mantenimientoController@perfilUsuario');
	Route::get('listar-tickets-perfil', 'mantenimientoController@listarTicketsPerfil');
	
	

	

	/**
	 * 				Reportes
	 */
	//Route::get('report-ticket-enviados', 'reportesController@reportTicketEnviados');
	Route::get('report-ticket-enviados', 'reportesController@reportTicketEnviados')->name('report-ticket-enviados')->middleware('permission:report-ticket-enviados');
	Route::get('listados-tickets-enviados', 'reportesController@listadosTicketsEnviados');
	Route::get('grafico-reporte-ticket-recibido', 'reportesController@graficoReporteTicketRecibido');
	//Route::get('report-estadisticas', 'reportesController@reporteEstadisticas');
	Route::get('report-estadisticas', 'reportesController@reporteEstadisticas')->name('report-estadisticas')->middleware('permission:report-estadisticas');

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
