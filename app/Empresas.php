<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Usuarios;
use \Auth;
use \DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class Empresas extends Model
{
    protected $table = 'empresas';

    protected function Actualizar($request,$empresa,$idEmpresa)
    {   

        $user  = \App\Usuarios::find($request->idUsuario);    

        $empresa  = \App\Empresas::find($idEmpresa); 
        
		$user->name       = $request->nombresUsuario;
		$user->lastName   = $request->apellidosUsuario;	
		$user->Telefono   = $request->telefonoUsuario;			
        $user->save();

		$empresa->NombreEmpresa      = $request->nombreEmpresa;
		$empresa->ruc                = $request->nroRuc;	
		$empresa->usuariosPermitidos = $request->usuariosPermitidos;
		$empresa->telefono1          = $request->telFijo;
		$empresa->telefono2          = $request->telMovil;
		$empresa->direccion          = $request->direccion;	
		$empresa->representante      = $request->representante;

		$empresa->save();		

    }

    protected function Guardar($request,$conn,$idEmpresa,$idNextEmpresa)
    {   

        if ( is_null($idEmpresa) ){     
            $user  = new \App\Usuarios();
            $user->password           = Hash::make('12345678');            
			$user->changePassword     = 'S';
			$user->email              = $request->correoUsuario;
			$user->rol                = 'adm';

			$empresa  = new \App\Empresas();
			$empresa->created_by = Auth::id();

        }else{
            $user  = \App\Usuarios::find($request->idUsuario);    

            $empresa  = \App\Empresas::find($idEmpresa); 
            $empresa->updated_by = Auth::id();
        }

		$user->name       = $request->nombresUsuario;
		$user->lastName   = $request->apellidosUsuario;	
		$user->id_Empresa = $idNextEmpresa;		
		$user->BaseDatos  = $request->baseDatos;
		$user->Telefono   = $request->telefonoUsuario;			
		$user->status     = 1;
        $user->save();

        $idUser = $user->id;

		$empresa->NombreEmpresa      = $request->nombreEmpresa;
		$empresa->ruc                = $request->nroRuc;	
		$empresa->nameBd             = $request->baseDatos;
		$empresa->usuariosPermitidos = $request->usuariosPermitidos;
		$empresa->telefono1          = $request->telFijo;
		$empresa->telefono2          = $request->telMovil;
		$empresa->direccion          = $request->direccion;	
		$empresa->correo             = $request->emailEmpresa;
		$empresa->representante      = $request->representante;
		$empresa->userAdmin          = $idUser;
		$empresa->status             = 1;

		$empresa->save();

		//se agrego nueva insert para la tabla role_user por defecto al crear se pondra al administrador 
		

		$role_admin  		 = new \App\Role;
        $role_admin->role_id = 1;
        $role_admin->user_id = $idUser; 
        $role_admin->save();

		Artisan::call('make:database '.$request->baseDatos.' mysql latin1_swedish');
		

        $this->crearTablas($request);
        $this->crearSp($request);
        $this->crearDirectorios($request);
        $this->insertarRegistrosTabla($request);
    }

    protected function crearDirectorios($request)
    {
    	$ruta     = '/Empresas/'.$request->baseDatos;
        $path     = public_path().$ruta;        
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        $ruta     = '/Empresas/'.$request->baseDatos.'/AdjuntosTickets';
        $path     = public_path().$ruta;
        
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        $ruta     = '/Empresas/'.$request->baseDatos.'/fotos';
        $path     = public_path().$ruta;
        
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

    }

    protected function crearSp($request)
    {
    	$this->sp_atender_ticket($request->baseDatos);
    	$this->sp_historial_tickets($request->baseDatos);
    	$this->sp_lista_ticketsGestores($request->baseDatos);
    	$this->sp_lista_ticketsGestoresResumen($request->baseDatos);
    	$this->sp_lista_ticketsUsuarios($request->baseDatos);
    	$this->sp_lista_ticketsUsuariosTodos($request->baseDatos);
    	$this->sp_listar_accionesTicket($request->baseDatos);
    	$this->sp_listar_categoria($request->baseDatos);
    	$this->sp_listar_categoria_mant($request->baseDatos);
    	$this->sp_listar_ResumenTicketsUsuarios($request->baseDatos);
    	$this->sp_listar_subCategoria($request->baseDatos);
    	$this->sp_listar_ticketMensajes($request->baseDatos);
    	$this->sp_listar_tipoTicketNew($request->baseDatos);
    	$this->sp_obtener_ticket($request->baseDatos);
    	$this->sp_registrarEncuesta_ticket($request->baseDatos);
    	$this->sp_lista_ticketsUsuariosReportes($request->baseDatos);
    	$this->sp_lista_ticketsRecibidosResumen($request->baseDatos);
        
    }
    protected function sp_lista_ticketsRecibidosResumen($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `lista_ticketsRecibidosResumen`(
				in idUsuarioIn int,
				in fechaDesde date,
				in fechaHasta date
				)
				BEGIN

						select 1 as estado, (
							select count(*)
							from tickets t0
							inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
							where (t0.idUsuTicket = idUsuarioIn or idUsuarioIn = 0)  and (t0.estado=1)
				             and (t0.fechaRegistro BETWEEN fechaDesde AND fechaHasta)
						) as cantidad
				        union all
				        select 2 as estado, (
							select count(*)
							from tickets t0
							inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
							where (t0.idUsuTicket = idUsuarioIn or idUsuarioIn = 0)  and (t0.estado=2)
				             and (t0.fechaRegistro BETWEEN fechaDesde AND fechaHasta)
						) as cantidad
				        union all
				        select 3 as estado, (
							select count(*)
							from tickets t0
							inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
							where (t0.idUsuTicket = idUsuarioIn or idUsuarioIn = 0)  and (t0.estado=3)
				             and (t0.fechaRegistro BETWEEN fechaDesde AND fechaHasta)
						) as cantidad
				        union all
				        select 4 as estado, (
							select count(*)
							from tickets t0
							inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
							where (t0.idUsuTicket = idUsuarioIn or idUsuarioIn = 0)  and (t0.estado=4)
				             and (t0.fechaRegistro BETWEEN fechaDesde AND fechaHasta)
						) as cantidad
				        union all
				        select 5 as estado, (
							select count(*)
							from tickets t0
							inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
							where (t0.idUsuTicket = idUsuarioIn or idUsuarioIn = 0)  and (t0.estado=5)
				             and (t0.fechaRegistro BETWEEN fechaDesde AND fechaHasta)
						) as cantidad
				        union all
				        select 6 as estado, (
							select count(*)
							from tickets t0
							inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
							where (t0.idUsuTicket = idUsuarioIn or idUsuarioIn = 0)  and (t0.estado=6)
				             and (t0.fechaRegistro BETWEEN fechaDesde AND fechaHasta)
						) as cantidad
				        union all
				        select 7 as estado, (
							select count(*)
							from tickets t0
							inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
							where (t0.idUsuTicket = idUsuarioIn or idUsuarioIn = 0)  and (t0.estado=7)
				             and (t0.fechaRegistro BETWEEN fechaDesde AND fechaHasta)
						) as cantidad
				        union all
				        select 8 as estado, (
							select count(*)
							from tickets t0
							inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
							where (t0.idUsuTicket = idUsuarioIn or idUsuarioIn = 0)  and (t0.estado=8)
				             and (t0.fechaRegistro BETWEEN fechaDesde AND fechaHasta)
						) as cantidad
				        union all
				        select 9 as estado, (
							select count(*)
							from tickets t0
							inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
							where (t0.idUsuTicket = idUsuarioIn or idUsuarioIn = 0)  and (t0.estado=9)
				             and (t0.fechaRegistro BETWEEN fechaDesde AND fechaHasta)
						) as cantidad;
					

				END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_lista_ticketsUsuariosReportes($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `lista_ticketsUsuariosReportes`(
			in idUsuarioIn int,
			in idGrupoIn int,
			in fechaDesde date,
			in fechaHasta date
			)
			BEGIN
					select estado,t6.des2Tabla,t0.idUsuTicket,t0.ctaUsuEjecutor,t0.nroTicket, concat(t4.name,' ',t4.lastName) solicitante,
					t0.Titulo, t0.idCategoria, t1.descCategoria, t0.idSubCategoria, t0.prioridad, t2.desTabla as desPrioridad,
					t0.fechaEstado, t0.estado,t0.fechaRegistro ,
			        CASE WHEN isnull(t5.name) THEN 'Pendiente por asignar' ELSE concat(t5.name,' ',t5.lastName) END as ejecutor,
					t0.tipoOrigen Origen,t0.tipoDestino Destino
					from tickets t0  
					inner join categoria t1   on t0.idCategoria = t1.idCategoria
					inner join tablas t2 on  t2.tipo ='PRIO' and t0.prioridad = t2.idTabla
			        inner join tablas t6 on t6.tipo='EST' and t6.idTabla=t0.estado
					inner join `help-desk`.users t4 on t0.idUsuTicket = t4.id
					left join `help-desk`.users t5 on t0.idUsuEjecutor = t5.id  and t0.ctaUsuEjecutor = t5.email
					where (t0.idUsuTicket = idUsuarioIn or idUsuarioIn = 0)  and (idGrupoIn = 0 or t0.estado=idGrupoIn)
			        and (t0.fechaRegistro BETWEEN fechaDesde AND fechaHasta)
			        order by t6.des2Tabla;
			END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_registrarEncuesta_ticket($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarEncuesta_ticket`(
			in nroTicketIn int,
			in idNivelEncuestaIn int,
			in comentarioEncuestaIn text,
			in idUsuarioIn int,
			in CtaUsuarioIn varchar(100)
			)
			BEGIN
				declare fechaRegistro datetime;
				declare fechaAtencionAnt datetime;
				declare tiempoDias int;
				declare tiempoHoras int;
				declare tiempoMin int;
				set fechaRegistro = now();
				set tiempoDias=0;
				set tiempoHoras =0;
				set tiempoMin = 0;
			        
			    update tickets set idNivelEncuesta = idNivelEncuestaIn, comentarioEncuesta = comentarioEncuestaIn,
			    fechaEncuesta = NOW() where nroTicket = nroTicketIn;
			    
			    if exists(select max(idAtencion) from ticket_atencion where nroTicket = nroTicketIn) then		
					select fechaAtencionAnt = fechaAtencionAnt from ticket_atencion where 
					idAtencion = (select max(idAtencion) from ticket_atencion where nroTicket = nroTicketIn);
					set tiempoDias= DATEDIFF (fechaAtencionAnt, fechaRegistro);
					set tiempoHoras = TIMESTAMPDIFF(HOUR,  fechaAtencionAnt, fechaRegistro); -- % 24
					set tiempoMin =  TIMESTAMPDIFF(Minute,  fechaAtencionAnt, fechaRegistro); -- % 60	;
				end if;

				insert into ticket_atencion (nroTicket, idUsuAtencion, ctaUsuAtencion, 
				fechaRegistro, fechaAtencionAnt, tiempoDias,
				tiempoHoras, tiempoMin, tipoAtencion,idSoluFrecuente,comentarioAtencion,estado,estadoAnt)
				values (nroTicketIn, idUsuarioin, CtaUsuarioIn, fechaRegistro, fechaAtencionAnt, tiempoDias,
				tiempoHoras, tiempoMin, 0, 0,'',9,8);

				update tickets set estado = 9, fechaEstado = fechaRegistro,
				idUsuEjecutor = idUsuarioIn, ctaUsuEjecutor = CtaUsuarioIn
				where nroTicket = nroTicketIn;
			    
			END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_obtener_ticket($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_ticket`(
		in nroTicket int
		)
		BEGIN
			select t0.nroTicket, t0.tipo, t1.desTabla, t0.idCategoria, t2.descCategoria, t0.idArea, t3.descArea, 
		t0.idSubCategoria, t4.desSubCategoria, t0.idPrbFrecuente, t5.desPrbFrecuente,
		t0.prioridad, t6.desTabla as desPrioridad, t0.estado, t7.des2Tabla as desEstado,
		t0.fechaEstado, t0.idUsuTicket, t0.mailUsuTicket, concat(t8.name,' ',t8.lastName) as nomUsuTicket,
		t0.idUsuCopia, t0.mailUsuCopia, concat(t9.name,' ',t9.lastName) as nomUsuCopia,
		t0.titulo  as titulo, t0.referencia, t0.descripcion, t0.fechaRegistro, t0.idUsuEjecutor, t0.ctaUsuEjecutor, concat(t10.name,' ',t10.lastName) as nomUsuEjecutor,
		t0.idNivelEncuesta, t11.desTabla as desNivelEncuesta,
		t0.comentarioEncuesta,t0.fechaEncuesta from tickets t0 
		inner join tablas t1  on t1.tipo = 'TPTK' and t0.tipo =  t1.idTabla
		inner join categoria t2  on t0.idCategoria = t2.idCategoria
		inner join area t3  on t0.idArea = t3.idArea 
		inner join tablas t6  on t6.tipo = 'PRIO' and t0.prioridad =  t6.idTabla
		inner join tablas t7 on t7.tipo = 'EST' and t0.estado =  t7.idTabla
		inner join `help-desk`.users t8 on t0.idUsuTicket = t8.id and t0.mailUsuTicket = t8.email
		left join subCategoria t4 on t0.idSubCategoria = t4.idSubCategoria
		left join prbFrecuente t5 on t0.idPrbFrecuente = t5.idPrbFrecuente
		left join `help-desk`.users t9 on t0.idUsuCopia = t9.id and t0.mailUsuCopia = t9.email
		left join `help-desk`.users t10 on 
		t0.idUsuEjecutor = t10.id and t0.ctaUsuEjecutor = t10.email
		left join tablas t11 on t6.tipo = 'ENC' and t0.idNivelEncuesta =  t11.idTabla
		where t0.nroTicket = nroTicket;
		END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_listar_tipoTicketNew($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_tipoTicketNew`(
			in AreaIn int 
			)
			BEGIN
				select distinct T1.idTabla,t1.desTabla from categoria T0
				inner join tablas T1 on CONCAT(t1.tipo,t1.idTabla) = T0.Tipo
				where t0.idArea=AreaIn and T1.activo=1 and t1.tipo ='TPTK';
			END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_listar_ticketMensajes($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_ticketMensajes`(
		in nroTicket int,
		in UserMail varchar(200)
		)
		BEGIN
			declare fechaHoy datetime;
			set fechaHoy = now();
		    
			update ticket_mensajes set leido = 1, fecHorLeido = fechaHoy
			where ctaUsu <> UserMail and nroTicket = nroTicket and leido is NULL;

			select t0.nroTicket, t0.fechaMsg,t0.mensaje, t0.idUsuCta, t0.ctaUsu,t0.leido,t0.fecHorLeido,
			concat(t1.name,' ',t1.lastName) as nomusu,fechaHoy,case t0.rol when 'usu' then '' when 'adm' then 'Administrador' else 'Agente' end rol
			from ticket_mensajes t0
			inner join `help-desk`.users t1 on t0.idUsuCta = t1.id and t0.ctaUsu = t1.email
			where t0.nroTicket = nroTicket
			order by t0.idMsg desc;

		END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_listar_subCategoria($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_subCategoria`(
				in idCategoria int,
				in tipoSelect int
				)
				BEGIN
					if tipoSelect = 1 then
					select t0.idSubCategoria, t0.desSubCategoria, t0.idCategoria, t1.descCategoria, t0.activo 
					from subcategoria t0 
					inner join categoria t1  on t0.idCategoria = t1.idCategoria
					where T0.idCategoria = idCategoria and t0.activo = 1 
					order by t0.desSubCategoria;
				else
					select t0.idSubCategoria, t0.desSubCategoria, t0.idCategoria, t1.descCategoria, t0.activo 
					from subcategoria t0 
					inner join categoria t1 on t0.idCategoria = t1.idCategoria
					where T0.idCategoria = idCategoria
					order by t0.desSubCategoria;
				END IF;
				END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_listar_ResumenTicketsUsuarios($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_ResumenTicketsUsuarios`(
			in idUsuarioIn int 
			)
			BEGIN
				select 1 as grupo, (select count(*) 
			from tickets t0  
			inner join tablas t3  on t3.tipo ='EST' and t0.estado = t3.idTabla
			where ((t0.idUsuTicket = idUsuarioIn) or 
			(t0.idUsuCopia = idUsuarioIn ))
			and t3.valint = 1) as cantidad
			union all 
			select 2 as grupo, (select count(*)
			from tickets t0  
			inner join tablas t3  on t3.tipo ='EST' and t0.estado = t3.idTabla
			where ((t0.idUsuTicket = idUsuarioIn ) or 
			(t0.idUsuCopia = idUsuarioIn ))
			and t3.valint = 2) as cantidad
			union all
			select 3 as grupo, (select count(*)
			from tickets t0  
			inner join tablas t3  on t3.tipo ='EST' and t0.estado = t3.idTabla
			where ((t0.idUsuTicket = idUsuarioIn ) or 
			(t0.idUsuCopia = idUsuarioIn ))
			and t3.valint =3) as cantidad
			union all
			select 4 as grupo, (select count(*)
			from tickets t0  
			inner join tablas t3  on t3.tipo ='EST' and t0.estado = t3.idTabla
			where ((t0.idUsuTicket = idUsuarioIn ) or 
			(t0.idUsuCopia = idUsuarioIn ))
			and t3.valint =4) as cantidad
			union all
			select 5 as grupo, (select count(*)
			from tickets t0  
			inner join tablas t3  on t3.tipo ='EST' and t0.estado = t3.idTabla
			where ((t0.idUsuTicket = idUsuarioIn ) or 
			(t0.idUsuCopia = idUsuarioIn ))
			and t3.valint =5) as cantidad
			union all
			select 6 as grupo, (select count(*)
			from tickets t0 
			inner join tablas t3   on t3.tipo ='EST' and t0.estado = t3.idTabla
			where ((t0.idUsuTicket = idUsuarioIn ) or 
			(t0.idUsuCopia = idUsuarioIn ))
			and t3.valint =6) as cantidad;
			END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_listar_categoria_mant($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_categoria_mant`(
			in AreaIn int 
			)
			BEGIN
				SELECT t0.idCategoria,t0.descCategoria,t0.idArea,t0.activo,t1.descArea,t2.idTabla,t2.desTabla,t0.Tipo
				FROM categoria t0  
				inner join area t1  
				on t0.idArea = t1.idArea 
				inner join tablas t2  
				on t0.Tipo = CONCAT(t2.tipo,t2.idTabla)
				where ((AreaIn > 0 and t0.idArea = AreaIn) or (AreaIn =0))
				order by t0.descCategoria;
			END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_listar_categoria($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_categoria`(
			in AreaIn int ,
			in tipoIn int
			)
			BEGIN
				SELECT t0.idCategoria,t0.descCategoria,t0.idArea,t0.activo,t1.descArea,t2.desTabla,t0.Tipo
				FROM categoria t0 
				inner join area t1  on t0.idArea = t1.idArea 
				inner join tablas t2 on t0.Tipo = concat(t2.tipo,t2.idTabla) 
				where ((AreaIn > 0 and t0.idArea = AreaIn and convert( SUBSTRING(t0.Tipo,5,5), SIGNED INTEGER)=tipoIn) or (AreaIn =0)) and t0.activo =1
				order by t0.descCategoria;
			END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_listar_accionesTicket($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `listar_accionesTicket`(
			in estadoActual int,
			in Rol char(3), 
			in RolAcceso char(3) 
			)
			BEGIN
				declare estados varchar(20);
				if RolAcceso = 'USU' then
					set Rol = RolAcceso;
				end if;

			set estados ='1';
			set estados := (select case when Rol ='USU' then valString3 else postTabla end
			from tablas where tipo='EST' and idTabla = estadoActual);

			select idTabla, desTabla,icono from tablas 
			where tipo ='EST' and activo =1 and  FIND_IN_SET(tablas.idTabla, estados)
			order by idTabla;

			END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_lista_ticketsUsuariosTodos($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `lista_ticketsUsuariosTodos`(
			in idUsuario int
			)
			BEGIN
					select t0.idUsuTicket,t0.ctaUsuEjecutor,t0.nroTicket, concat(t4.name,' ',t4.lastName) solicitante,
					t0.Titulo, t0.idCategoria, t1.descCategoria, t0.idSubCategoria, t0.prioridad, t2.desTabla as desPrioridad,
					t0.fechaEstado, t0.estado, 
			        CASE WHEN isnull(t5.name) THEN 'Pendiente por asignar' ELSE concat(t5.name,' ',t5.lastName) END as ejecutor,
					t0.tipoOrigen Origen,t0.tipoDestino Destino
					from tickets t0  
					inner join categoria t1   on t0.idCategoria = t1.idCategoria
					inner join tablas t2   on t2.tipo ='PRIO' and t0.prioridad = t2.idTabla 
					inner join `help-desk`.users t4 on t0.idUsuTicket = t4.id
					left join `help-desk`.users t5 on t0.idUsuEjecutor = t5.id  and t0.ctaUsuEjecutor = t5.email
					where ((t0.idUsuTicket = idUsuario) or 
					(t0.idUsuCopia = idUsuario)) 
					order by t0.estado;

			END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_lista_ticketsUsuarios($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `lista_ticketsUsuarios`(
			in idUsuario int,
			in GrupoEstados int
			)
			BEGIN
				if (GrupoEstados < 7) then
					select t0.idUsuTicket,t0.ctaUsuEjecutor,t0.nroTicket, concat(t4.name,' ',t4.lastName) solicitante,
					t0.Titulo, t0.idCategoria, t1.descCategoria, t0.idSubCategoria, t0.prioridad, t2.desTabla as desPrioridad,
					t0.fechaEstado, t0.estado, t3.des2Tabla as desEstado, 
			        CASE WHEN isnull(t5.name) THEN 'Pendiente por asignar' ELSE concat(t5.name,' ',t5.lastName) END as ejecutor,
					t3.valNum as porcAvance, t3.valString as colorEstado,
					t3.valString2 as ColorTexto,t0.tipoOrigen Origen,t0.tipoDestino Destino
					from tickets t0  
					inner join categoria t1   on t0.idCategoria = t1.idCategoria
					inner join tablas t2   on t2.tipo ='PRIO' and t0.prioridad = t2.idTabla 
					inner join tablas t3   on t3.tipo ='EST' and t0.estado = t3.idTabla and t3.valint = GrupoEstados
					inner join `help-desk`.users t4 on t0.idUsuTicket = t4.id
					left join `help-desk`.users t5 on t0.idUsuEjecutor = t5.id  and t0.ctaUsuEjecutor = t5.email
					where ((t0.idUsuTicket = idUsuario) or 
					(t0.idUsuCopia = idUsuario)) 
					order by t0.estado;
			else
					select t0.nroTicket, t4.UserNombre solicitante,
					t0.Titulo, t0.idCategoria, t1.descCategoria, t0.idSubCategoria, t0.prioridad, t2.desTabla as desPrioridad,
					t0.fechaEstado, t0.estado, t3.des2Tabla as desEstado,  
			        CASE WHEN isnull(t5.UserNombre) THEN 'Pendiente por asignar' ELSE t5.UserNombre END as ejecutor,
					t3.valNum as porcAvance, t3.valString as colorEstado,t3.valString2 as ColorTexto,t6.GrupNombre
					from tickets t0  
					inner join categoria t1   on t0.idCategoria = t1.idCategoria
					inner join tablas t2   on t2.tipo ='PRIO' and t0.prioridad = t2.idTabla 
					inner join tablas t3   on t3.tipo ='EST' and t0.estado = t3.idTabla
					inner join ClienteUsuarios t4 on t0.idUsuTicket = t4.Id  and t0.mailUsuTicket = t4.UserMail
					left join ClienteUsuarios t5 on t0.idUsuEjecutor = t5.Id  and t0.ctaUsuEjecutor = t5.UserMail
					where ((t0.idUsuTicket = idUsuario) or 
					(t0.idUsuCopia = idUsuario))
					order by t0.estado;
			end if;
			END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_lista_ticketsGestoresResumen($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `lista_ticketsGestoresResumen`(
			in idUsuario int,
			in idArea int,
			in rolUsuario char(3)
			)
			BEGIN
				if(rolUsuario = 'ADM') then
					select 1 as grupo, (
						select count(*)
						from tickets t0
						inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
						where (t0.idArea = idArea) 
						and t3.valint = 1) as cantidad
					union all
					select 2 as grupo, (
						select count(*)
						from tickets t0
						inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
						where (t0.idArea = idArea) 
						and t3.valint = 2) as cantidad
					union all
					select 3 as grupo, (
						select count(*)
						from tickets t0 
						inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
						where (t0.idArea =  idArea) 
						and t3.valint = 3) as cantidad
					union all
				select 4 as grupo, (
					select count(*)
					from tickets t0  
					inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
					where (t0.idArea =  idArea) 
					and t3.valint = 4) as cantidad
				union all
				select 5 as grupo, (
					select count(*)
					from tickets t0 
					inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
					where (t0.idArea =  idArea) 
					and t3.valint = 5) as cantidad
				union all
				select 6 as grupo, (
					select count(*)
					from tickets t0 
					inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
					where (t0.idArea =  idArea)  
					and t3.valint = 6) as cantidad;
			elseif ( rolUsuario = 'AGE') then
				select 1 as grupo, ( 
					select count(*) as cantidad
					from tickets t0 
					inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
					where t0.idUsuEjecutor =  idUsuario and t3.valint = 1) as cantidad
				union all
				select 2 as grupo, ( 
					select count(*) as cantidad
					from tickets t0 
					inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
					where t0.idUsuEjecutor =  idUsuario and t3.valint = 2) as cantidad
				union all
				select 3 as grupo, ( 
					select count(*) as cantidad
					from tickets t0 
					inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
					where t0.idUsuEjecutor =  idUsuario and t3.valint = 3) as cantidad
				union all
				select 4 as grupo, ( 
					select count(*) as cantidad
					from tickets t0 
					inner join tablas t3  on t3.tipo ='EST' and t0.estado = t3.idTabla
					where t0.idUsuEjecutor =  idUsuario and t3.valint = 4) as cantidad
				union all
				select 5 as grupo, ( 
					select count(*) as cantidad
					from tickets t0 
					inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
					where t0.idUsuEjecutor =  idUsuario and t3.valint = 5) as cantidad
				union all
				select 6 as grupo, ( 
					select count(*) as cantidad
					from tickets t0 
					inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
					where t0.idUsuEjecutor =  idUsuario and t3.valint = 6) as cantidad;
			end if;
			END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_lista_ticketsGestores($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `lista_ticketsGestores`(
		in idUsuario int,
		in idAreaIn int,
		in rolUsuario char(3),
		in GrupoEstados int
		)
		BEGIN
			if(rolUsuario = 'ADM') then
				select t0.idUsuTicket,t0.ctaUsuEjecutor,t0.nroTicket,t0.tipo, concat(t4.name,' ',t4.lastName) solicitante,
				t0.Titulo, t0.idCategoria, t1.descCategoria, t0.idSubCategoria, t0.prioridad, t2.desTabla as desPrioridad,
				t0.fechaEstado, t0.estado, t3.des2Tabla as desEstado, 
		        CASE WHEN isnull(t5.name) THEN 'Pendiente por asignar' ELSE concat(t5.name,' ',t5.lastName) END as ejecutor,
				t3.valNum as porcAvance, t3.valString as colorEstado,
				t3.valString2 as ColorTexto,t0.tipoOrigen Origen,t0.tipoDestino Destino
				from tickets t0 
				inner join categoria t1 on t0.idCategoria = t1.idCategoria
				inner join tablas t2 on t2.tipo ='PRIO' and t0.prioridad = t2.idTabla 
				inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
				inner join `help-desk`.users t4 on t0.idUsuTicket = t4.id  and t0.mailUsuTicket = t4.email
				left join `help-desk`.users t5 on t0.idUsuEjecutor = t5.id  and t0.ctaUsuEjecutor = t5.email
		        where ( t0.idArea = idAreaIn)
				and ((GrupoEstados <7 and t3.valint = GrupoEstados) or (GrupoEstados = 7))
				order by t0.estado;
			elseif (rolUsuario = 'AGE') then
				select t0.nroTicket,t0.tipo, concat(t4.name,' ',t4.lastName) solicitante, 
				t0.Titulo, t0.idCategoria, t1.descCategoria, t0.idSubCategoria, t0.prioridad, t2.desTabla as desPrioridad,
				t0.fechaEstado, t0.estado, t3.des2Tabla as desEstado, 
				CASE WHEN isnull(t5.name) THEN 'Pendiente por asignar' ELSE concat(t5.name,' ',t5.lastName) END as ejecutor,
				t3.valNum as porcAvance, t3.valString as colorEstado,t3.valString2 as ColorTexto
				from tickets t0 
				inner join categoria t1 on t0.idCategoria = t1.idCategoria
				inner join tablas t2 on t2.tipo ='PRIO' and t0.prioridad = t2.idTabla 
				inner join tablas t3 on t3.tipo ='EST' and t0.estado = t3.idTabla
				inner join `help-desk`.users t4 on t0.idUsuTicket = t4.id  and t0.mailUsuTicket = t4.email
				left join `help-desk`.users t5 on t0.idUsuEjecutor = t5.id  and t0.ctaUsuEjecutor = t5.email
				where t0.idUsuEjecutor = idUsuario and 
				((GrupoEstados < 7 and t3.valint = GrupoEstados)or (GrupoEstados = 7))
				order by t0.estado;
			end if;
		END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_historial_tickets($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `historial_tickets`(
			in nroTicket int
			)
			BEGIN
				/*anulación de tickets*/
			select t0.fecharegistro, te.des2Tabla as desEstado, concat(t1.name,' ',t1.lastName) as responsable, t2.descCargo as cargoResponsable,
			'' asignado, '' cargoAsignado, t0.comentario as glosa,te.preTabla 
			from ticket_anulacion t0 
			inner join tablas te on te.tipo ='EST' and t0.estado = te.idTabla
			inner join `help-desk`.users t1 on t0.idUsu =  t1.id and t0.ctaUsu = t1.email
			inner join cargo t2  on t1.cargo = t2.idCargo
			where t0.nroTicket = nroTicket
			union all /* programación / asignación */
			select t0.fechaRegistro, te.des2Tabla as desEstado, concat(t1.name,' ',t1.lastName) as responsable, t2.descCargo cargoResponsable,
			concat(t3.name,' ',t3.lastName) as asignado, t4.descCargo cargoAsignado, '' glosa,te.preTabla 
			from ticket_programacion t0 
			inner join tablas te on te.tipo ='EST' and t0.estado = te.idTabla
			inner join `help-desk`.users t1 
			on t0.idUsuAdmin =  t1.id and t0.ctaUsuAdmin = t1.email
			inner join cargo t2 on t1.cargo = t2.idCargo
			inner join `help-desk`.users t3 on t0.idUsuAgen =  t3.id and t0.ctaUsuAgen = t3.email
			inner join cargo t4 on t3.cargo = t4.idCargo
			where t0.nroTicket = nroTicket
			union all /* -- reasignado */
			select t0.fechaRegistro, te.des2Tabla as desEstado, concat(t1.name,' ',t1.lastName) as responsable, t2.descCargo cargoResponsable,
			t3.descArea asignado, '' cargoAsignado, '' glosa,te.preTabla  from ticket_reasignacion t0 
			inner join tablas te on te.tipo ='EST' and t0.estado = te.idTabla
			inner join `help-desk`.users t1  
			on t0.idUsuAdmin =  t1.id and t0.ctaUsuAdmin = t1.email
			inner join cargo t2 on t1.cargo = t2.idCargo
			inner join area t3  on t0.idAreaDest = t3.idArea
			where t0.nroTicket = nroTicket
			union all
			select t9.fechaRegistro, te.des2Tabla as desEstado, concat(t1.name,' ',t1.lastName) as responsable, t2.descCargo cargoResponsable,
			'' asignado, '' cargoAsignado, t9.comentarioAtencion glosa,te.preTabla  from ticket_atencion t9 
			inner join tablas te on te.tipo ='EST' and t9.estado = te.idTabla
			inner join `help-desk`.users t1 on t9.idUsuAtencion =  t1.id and t9.ctaUsuAtencion = t1.email
			inner join cargo t2 on t1.cargo = t2.idCargo
			where t9.nroTicket = nroTicket
			order by fecharegistro desc;


			END";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function sp_atender_ticket($BD)
    {	
    	$sql="CREATE DEFINER=`root`@`localhost` PROCEDURE `atender_ticket`(
		in nroTicketIn int,
		in idUsuAtencion int,
		in tipoAtencion int,
		in idCategoria int,
		in idSubCategoria int,
		in idSoluFrecuente int,
		in comentarioAtencion text,
		in estado int,
		in estadoAnt int,
		in idUsuario int,
		in CtaUsuario varchar(100),
		in idActivo int
		)
		BEGIN
			declare fechaRegistro datetime;
			declare fechaAtencionAnt datetime;
			declare tiempoDias int;
			declare tiempoHoras int;
			declare tiempoMin int;
			set fechaRegistro = now();
			set tiempoDias=0;
			set tiempoHoras =0;
			set tiempoMin = 0;
		    
		    if exists(select max(idAtencion) from ticket_atencion where nroTicket = nroTicketIn) then		
				select fechaAtencionAnt = fechaAtencionAnt from ticket_atencion where 
				idAtencion = (select max(idAtencion) from ticket_atencion where nroTicket = nroTicketIn);
				set tiempoDias= DATEDIFF (fechaAtencionAnt, fechaRegistro);
				set tiempoHoras = TIMESTAMPDIFF(HOUR,  fechaAtencionAnt, fechaRegistro); -- % 24
				set tiempoMin =  TIMESTAMPDIFF(Minute,  fechaAtencionAnt, fechaRegistro); -- % 60	;
			end if;

			insert into ticket_atencion (nroTicket, idUsuAtencion, ctaUsuAtencion, 
			fechaRegistro, fechaAtencionAnt, tiempoDias,
			tiempoHoras, tiempoMin, tipoAtencion,idSoluFrecuente,idActivo,comentarioAtencion,estado,estadoAnt)
			values (nroTicketIn,idUsuAtencion,CtaUsuario,fechaRegistro,fechaAtencionAnt, tiempoDias,
			tiempoHoras, tiempoMin, tipoAtencion,idSoluFrecuente,idActivo,comentarioAtencion,estado,estadoAnt);

			update tickets set estado = estado, fechaEstado = now(), 
			idUsuEjecutor = idUsuario, ctaUsuEjecutor = CtaUsuario,
			idCategoria = idCategoria,idSubCategoria = idSubCategoria
			where nroTicket = nroTicketIn;
		    
		END;";
    	DB::connection($BD)->getPdo()->exec($sql);
    }

    protected function crearTablas($request)
    {
    	$this->creaTable_activo($request->baseDatos);
        $this->creaTable_area($request->baseDatos);
        $this->creaTable_auditoria($request->baseDatos);
        $this->creaTable_cargatickets($request->baseDatos);
        $this->creaTable_cargatickets2($request->baseDatos);
        $this->creaTable_cargo($request->baseDatos);
        $this->creaTable_categoria($request->baseDatos);
        $this->creaTable_correos($request->baseDatos);
        $this->creaTable_filestore($request->baseDatos);
        $this->creaTable_local($request->baseDatos);
        $this->creaTable_personal($request->baseDatos);
        $this->creaTable_personal_synlab($request->baseDatos);
        $this->creaTable_prbfrecuente($request->baseDatos);
        $this->creaTable_solufrecuente($request->baseDatos);
        $this->creaTable_subarea($request->baseDatos);
        $this->creaTable_subcategoria($request->baseDatos);
        $this->creaTable_subcategoria_prbfrecuente($request->baseDatos);
        $this->creaTable_tablas($request->baseDatos);
        $this->creaTable_ticket_anulacion($request->baseDatos);
        $this->creaTable_ticket_atencion($request->baseDatos);
        $this->creaTable_ticket_mensajes($request->baseDatos);
        $this->creaTable_ticket_programacion($request->baseDatos);
        $this->creaTable_ticket_reasignacion($request->baseDatos);
        $this->creaTable_tickets($request->baseDatos);
        $this->creaTable_tickets_adjuntos($request->baseDatos);
        $this->creaTable_usercarga($request->baseDatos);
    }

    protected function creaTable_usercarga($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`usercarga` (
		  `ID` varchar(50) DEFAULT NULL,
		  `Nombrecompleto` varchar(50) DEFAULT NULL,
		  `Documento` varchar(50) DEFAULT NULL,
		  `Descripcion Sucursal` varchar(50) DEFAULT NULL,
		  `Correo` varchar(50) DEFAULT NULL,
		  `USER Tipo` varchar(50) DEFAULT NULL,
		  `UserContraseña` varchar(50) DEFAULT NULL,
		  `orden` int(11) DEFAULT NULL,
		  `total` int(11) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_tickets_adjuntos($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`tickets_adjuntos` (
		  `nroTicket` bigint(20) NOT NULL,
		  `archivo` varchar(300) NOT NULL,
		  `pesoMb` decimal(18,2) DEFAULT NULL,
		  `activo` tinyint(1) DEFAULT NULL,
		  PRIMARY KEY (`nroTicket`,`archivo`(255))
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_tickets($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`tickets` (
		  `nroTicket` bigint(20) NOT NULL AUTO_INCREMENT,
		  `tipo` int(11) DEFAULT NULL COMMENT "1 = Incidencia , 2 = Requerimiento",
		  `idCategoria` int(11) DEFAULT NULL,
		  `idArea` int(11) DEFAULT NULL,
		  `idSubCategoria` int(11) DEFAULT NULL,
		  `idPrbFrecuente` int(11) DEFAULT NULL,
		  `prioridad` int(11) NOT NULL,
		  `estado` int(11) NOT NULL,
		  `fechaEstado` datetime(6) DEFAULT NULL,
		  `idUsuTicket` int(11) NOT NULL,
		  `mailUsuTicket` varchar(100) DEFAULT NULL,
		  `idUsuCopia` int(11) DEFAULT NULL,
		  `mailUsuCopia` varchar(1000) DEFAULT NULL,
		  `titulo` varchar(200) DEFAULT NULL,
		  `referencia` varchar(100) DEFAULT NULL,
		  `descripcion` longtext,
		  `fechaRegistro` datetime(6) DEFAULT NULL,
		  `idUsuEjecutor` int(11) DEFAULT NULL,
		  `ctaUsuEjecutor` varchar(100) DEFAULT NULL,
		  `idNivelEncuesta` int(11) DEFAULT NULL,
		  `comentarioEncuesta` varchar(200) DEFAULT NULL,
		  `fechaEncuesta` datetime(6) DEFAULT NULL,
		  `grupo` char(5) DEFAULT NULL,
		  `tipoOrigen` char(3) DEFAULT NULL,
		  `tipoDestino` char(3) DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`nroTicket`)
		) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_ticket_reasignacion($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`ticket_reasignacion` (
		  `idReasigna` int(11) NOT NULL AUTO_INCREMENT,
		  `nroTicket` bigint(20) NOT NULL,
		  `idUsuAdmin` int(11) DEFAULT NULL,
		  `ctaUsuAdmin` varchar(100) DEFAULT NULL,
		  `idArea` int(11) DEFAULT NULL,
		  `idAreaDest` int(11) DEFAULT NULL,
		  `comentario` varchar(500) DEFAULT NULL,
		  `fechaRegistro` datetime(6) DEFAULT NULL,
		  `estado` int(11) DEFAULT NULL,
		  `estadoAnt` int(11) DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`idReasigna`,`nroTicket`)
		) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_ticket_programacion($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`ticket_programacion` (
		  `idProgramado` int(11) NOT NULL AUTO_INCREMENT,
		  `nroTicket` bigint(20) NOT NULL,
		  `idUsuAdmin` int(11) NOT NULL,
		  `ctaUsuAdmin` varchar(100) DEFAULT NULL,
		  `idUsuAgen` int(11) NOT NULL,
		  `ctaUsuAgen` varchar(100) DEFAULT NULL,
		  `fechaRegistro` datetime(6) DEFAULT NULL,
		  `estado` int(11) DEFAULT NULL,
		  `estadoAnt` int(11) DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`idProgramado`,`nroTicket`)
		) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_ticket_mensajes($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`ticket_mensajes` (
		  `idMsg` bigint(20) NOT NULL AUTO_INCREMENT,
		  `nroTicket` int(11) DEFAULT NULL,
		  `fechaMsg` datetime(6) DEFAULT NULL,
		  `mensaje` varchar(1000) DEFAULT NULL,
		  `idUsuCta` int(11) DEFAULT NULL,
		  `ctaUsu` varchar(100) DEFAULT NULL,
		  `rol` char(3) DEFAULT NULL,
		  `leido` tinyint(1) DEFAULT NULL,
		  `fecHorLeido` datetime(6) DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`idMsg`)
		) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_ticket_atencion($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`ticket_atencion` (
		  `idAtencion` int(11) NOT NULL AUTO_INCREMENT,
		  `nroTicket` bigint(20) NOT NULL,
		  `idUsuAtencion` int(11) DEFAULT NULL,
		  `ctaUsuAtencion` varchar(100) DEFAULT NULL,
		  `fechaRegistro` datetime(6) DEFAULT NULL,
		  `fechaAtencionAnt` datetime(6) DEFAULT NULL,
		  `tiempoDias` int(11) DEFAULT NULL,
		  `tiempoHoras` int(11) DEFAULT NULL,
		  `tiempoMin` int(11) DEFAULT NULL,
		  `tipoAtencion` int(11) DEFAULT NULL,
		  `idSoluFrecuente` int(11) DEFAULT NULL,
		  `idActivo` int(11) DEFAULT NULL,
		  `comentarioAtencion` longtext,
		  `estado` int(11) DEFAULT NULL,
		  `estadoAnt` int(11) DEFAULT NULL,
		  `idNivelEncuesta` int(11) DEFAULT NULL,
		  `idUsuEncuesta` int(11) DEFAULT NULL,
		  `mailUsuEncuesta` varchar(100) DEFAULT NULL,
		  `comentarioEncuesta` varchar(1000) DEFAULT NULL,
		  PRIMARY KEY (`idAtencion`,`nroTicket`)
		) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_ticket_anulacion($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`ticket_anulacion` (
		  `nroTicket` bigint(20) NOT NULL,
		  `idUsu` int(11) DEFAULT NULL,
		  `ctaUsu` varchar(100) DEFAULT NULL,
		  `fecharegistro` datetime(6) DEFAULT NULL,
		  `comentario` varchar(500) DEFAULT NULL,
		  `estado` int(11) DEFAULT NULL,
		  `estadoAnterior` int(11) DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`nroTicket`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_tablas($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`tablas` (
		  `tipo` char(4) NOT NULL,
		  `idTabla` bigint(20) NOT NULL,
		  `desTabla` varchar(50) DEFAULT NULL,
		  `des2Tabla` varchar(50) DEFAULT NULL,
		  `preTabla` varchar(20) DEFAULT NULL,
		  `postTabla` varchar(20) DEFAULT NULL,
		  `valNum` double DEFAULT NULL,
		  `valInt` int(11) DEFAULT NULL,
		  `valString` varchar(20) DEFAULT NULL,
		  `valString2` varchar(20) DEFAULT NULL,
		  `valString3` varchar(100) DEFAULT NULL,
		  `valBit` tinyint(1) DEFAULT NULL,
		  `activo` tinyint(1) DEFAULT NULL,
		  `interno` tinyint(1) DEFAULT NULL,
		  `externo` tinyint(1) DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  `icono` varchar(100) DEFAULT NULL,
		  PRIMARY KEY (`tipo`,`idTabla`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_subcategoria_prbfrecuente($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`subcategoria_prbfrecuente` (
		  `idSubCategoria` int(11) NOT NULL AUTO_INCREMENT,
		  `idPrbFrecuente` int(11) NOT NULL,
		  `activo` tinyint(1) DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`idSubCategoria`,`idPrbFrecuente`)
		) ENGINE=InnoDB AUTO_INCREMENT=409 DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_subcategoria($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`subcategoria` (
		  `idSubCategoria` int(11) NOT NULL AUTO_INCREMENT,
		  `desSubCategoria` varchar(100) DEFAULT NULL,
		  `idCategoria` int(11) DEFAULT NULL,
		  `activo` char(1) DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`idSubCategoria`)
		) ENGINE=InnoDB AUTO_INCREMENT=416 DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_subarea($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`subarea` (
		  `idSubArea` int(11) NOT NULL AUTO_INCREMENT,
		  `idArea` int(11) NOT NULL,
		  `descSubArea` varchar(100) DEFAULT NULL,
		  `activo` tinyint(1) DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`idSubArea`),
		  KEY `FK_Area` (`idArea`),
		  CONSTRAINT `FK_Area` FOREIGN KEY (`idArea`) REFERENCES `area` (`idArea`) ON DELETE NO ACTION ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_solufrecuente($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`solufrecuente` (
		  `idSoluFrecuente` int(11) NOT NULL AUTO_INCREMENT,
		  `descripcion` varchar(1000) DEFAULT NULL,
		  `idSubCategoria` int(11) DEFAULT NULL,
		  `activo` tinyint(1) DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`idSoluFrecuente`)
		) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_prbfrecuente($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`prbfrecuente` (
		  `idPrbFrecuente` int(11) NOT NULL AUTO_INCREMENT,
		  `desPrbFrecuente` varchar(100) DEFAULT NULL,
		  `activo` tinyint(1) DEFAULT "1",
		  `idSubCategoria` int(11) DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`idPrbFrecuente`)
		) ENGINE=InnoDB AUTO_INCREMENT=435 DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_personal_synlab($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`personal_synlab` (
		  `Documento` varchar(20) DEFAULT NULL,
		  `Empleado` varchar(100) DEFAULT NULL,
		  `Cargo` varchar(60) DEFAULT NULL,
		  `Centrocostos` varchar(10) DEFAULT NULL,
		  `Area` varchar(50) DEFAULT NULL,
		  `Gerencia` varchar(60) DEFAULT NULL,
		  `Local` varchar(30) DEFAULT NULL,
		  `Perfil` varchar(50) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_personal($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`personal` (
		  `idPersonal` bigint(20) NOT NULL,
		  `nomPersonal` varchar(200) DEFAULT NULL,
		  `activo` tinyint(1) DEFAULT NULL,
		  `FechaEvento` datetime(6) DEFAULT NULL,
		  PRIMARY KEY (`idPersonal`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_local($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`local` (
		  `idLocal` int(11) NOT NULL,
		  `nomLocal` varchar(100) DEFAULT NULL,
		  `direccion` varchar(200) DEFAULT NULL,
		  `latitud` varchar(50) DEFAULT NULL,
		  `longitud` varchar(50) DEFAULT NULL,
		  `activo` tinyint(1) DEFAULT NULL,
		  PRIMARY KEY (`idLocal`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_filestore($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`filestore` (
		  `nroTicket` bigint(20) DEFAULT NULL,
		  `nombreOriginal` varchar(500) NOT NULL,
		  `nombreFile` varchar(500) DEFAULT NULL,
		  `ext` varchar(10) DEFAULT NULL,
		  `nroTicketTmp` int(11) DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_correos($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`correos` (
		  `id` int(11) NOT NULL,
		  `nombre` varchar(45) DEFAULT NULL,
		  `smtp` varchar(45) DEFAULT NULL,
		  `port` int(11) DEFAULT NULL,
		  `encryption` varchar(45) DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  `correo` varchar(100) DEFAULT NULL,
		  `password` varchar(100) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_categoria($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`categoria` (
		  `idCategoria` int(11) NOT NULL AUTO_INCREMENT,
		  `descCategoria` varchar(100) DEFAULT NULL,
		  `idArea` int(11) DEFAULT NULL,
		  `activo` tinyint(1) DEFAULT "1",
		  `Tipo` char(5) DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`idCategoria`)
		) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_cargo($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`cargo` (
		  `idCargo` int(11) NOT NULL AUTO_INCREMENT,
		  `descCargo` varchar(100) DEFAULT NULL,
		  `idSubArea` int(11) DEFAULT NULL,
		  `activo` tinyint(1) DEFAULT NULL,
		  PRIMARY KEY (`idCargo`)
		) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_cargatickets2($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`cargatickets2` (
		  `Tipo` varchar(50) DEFAULT NULL,
		  `nroTicket` varchar(50) DEFAULT NULL,
		  `intestado` varchar(50) DEFAULT NULL,
		  `Estado` varchar(50) DEFAULT NULL,
		  `HorarioAtención` varchar(50) DEFAULT NULL,
		  `FechaRegistro` varchar(50) DEFAULT NULL,
		  `Categoria` varchar(50) DEFAULT NULL,
		  `Area` varchar(50) DEFAULT NULL,
		  `ResponsableAsignado` varchar(50) DEFAULT NULL,
		  `EquipoSistema` varchar(50) DEFAULT NULL,
		  `UsuarioSolicitante` varchar(50) DEFAULT NULL,
		  `Idususol` varchar(50) DEFAULT NULL,
		  `ctaUsusol` varchar(50) DEFAULT NULL,
		  `REF` varchar(50) DEFAULT NULL,
		  `AreaSolicitante` varchar(50) DEFAULT NULL,
		  `Empresa` varchar(50) DEFAULT NULL,
		  `Local` varchar(50) DEFAULT NULL,
		  `FechAAtencionusuario` varchar(50) DEFAULT NULL,
		  `TipoServicio` varchar(50) DEFAULT NULL,
		  `subcategoria` varchar(50) DEFAULT NULL,
		  `Prioridad` varchar(50) DEFAULT NULL,
		  `TipodeRequerimiento` varchar(255) DEFAULT NULL,
		  `DescripcionRequerimiento` varchar(500) DEFAULT NULL,
		  `ResponsableAtención` varchar(50) DEFAULT NULL,
		  `TipoSolucionbrindada` varchar(500) DEFAULT NULL,
		  `TipoAtención` varchar(50) DEFAULT NULL,
		  `FechaInicioReal` varchar(50) DEFAULT NULL,
		  `FechaTerminoReal` varchar(50) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }
    
 	protected function creaTable_cargatickets($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`cargatickets` (
		  `Tipo` varchar(50) DEFAULT NULL,
		  `nroTicket` varchar(50) DEFAULT NULL,
		  `intestado` varchar(50) DEFAULT NULL,
		  `Estado` varchar(50) DEFAULT NULL,
		  `HorarioAtención` varchar(50) DEFAULT NULL,
		  `FechaRegistro` varchar(50) DEFAULT NULL,
		  `Categoria` varchar(50) DEFAULT NULL,
		  `Area` varchar(50) DEFAULT NULL,
		  `ResponsableAsignado` varchar(50) DEFAULT NULL,
		  `EquipoSistema` varchar(50) DEFAULT NULL,
		  `UsuarioSolicitante` varchar(50) DEFAULT NULL,
		  `Idususol` varchar(50) DEFAULT NULL,
		  `ctaUsusol` varchar(50) DEFAULT NULL,
		  `REF` varchar(50) DEFAULT NULL,
		  `AreaSolicitante` varchar(50) DEFAULT NULL,
		  `Empresa` varchar(50) DEFAULT NULL,
		  `Local` varchar(50) DEFAULT NULL,
		  `FechAAtencionusuario` varchar(50) DEFAULT NULL,
		  `TipoServicio` varchar(50) DEFAULT NULL,
		  `subcategoria` varchar(50) DEFAULT NULL,
		  `Prioridad` varchar(50) DEFAULT NULL,
		  `TipodeRequerimiento` varchar(255) DEFAULT NULL,
		  `DescripcionRequerimiento` varchar(500) DEFAULT NULL,
		  `ResponsableAtención` varchar(50) DEFAULT NULL,
		  `TipoSolucionbrindada` varchar(500) DEFAULT NULL,
		  `TipoAtención` varchar(50) DEFAULT NULL,
		  `FechaInicioReal` varchar(50) DEFAULT NULL,
		  `FechaTerminoReal` varchar(50) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_auditoria($BD){
    	DB::statement('CREATE TABLE  '.$BD.'.`auditoria` (
		  `idAuditoria` bigint(20) NOT NULL AUTO_INCREMENT,
		  `tabla` varchar(50) DEFAULT NULL,
		  `evento` varchar(20) DEFAULT NULL,
		  `idInt` int(11) DEFAULT NULL,
		  `idInt2` int(11) DEFAULT NULL,
		  `idStr` varchar(50) DEFAULT NULL,
		  `idStr2` varchar(50) DEFAULT NULL,
		  `idUsuario` int(11) DEFAULT NULL,
		  `idCtaUsuario` varchar(100) DEFAULT NULL,
		  `fechaEvento` datetime(6) DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`idAuditoria`)
		) ENGINE=InnoDB AUTO_INCREMENT=9959 DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_area($BD){
    	DB::statement('CREATE TABLE  '.$BD.'.`area` (
			  `idArea` int(11) NOT NULL AUTO_INCREMENT,
			  `descArea` varchar(100) DEFAULT NULL,
			  `activo` tinyint(1) DEFAULT NULL,
			  `updated_at` timestamp NULL DEFAULT NULL,
		  	  `created_at` timestamp NULL DEFAULT NULL,
			  PRIMARY KEY (`idArea`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    protected function creaTable_activo($BD){
    	DB::statement('CREATE TABLE '.$BD.'.`activo` (
		  `idActivo` int(11) NOT NULL AUTO_INCREMENT,
		  `codigoActivo` varchar(20) DEFAULT NULL,
		  `nroSerie` varchar(30) DEFAULT NULL,
		  `tipoActivo` int(11) DEFAULT NULL,
		  `descripcion` varchar(200) DEFAULT NULL,
		  `descripcionCompleta` varchar(500) DEFAULT NULL,
		  `activo` tinyint(1) DEFAULT NULL,
		  `updated_at` timestamp NULL DEFAULT NULL,
		  `created_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`idActivo`)
		) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;');
    }

    protected function insertarRegistrosTabla($request){

    	$BD = $request->baseDatos ; 
    	$table_name = $BD.'.tablas';
 
    	DB::statement("INSERT INTO $table_name (`tipo`, `idTabla`, `desTabla`, `des2Tabla`, `preTabla`, `postTabla`, `valNum`, `valInt`, `valString`, `valString2`, `valString3`, `valBit`, `activo`, `interno`, `externo`, `updated_at`, `created_at`, `icono`) VALUES
	('ENC', 1, 'Incidencias', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2020-01-22 16:32:45', NULL, NULL),
	('ENC', 2, 'Mal', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	('ENC', 3, 'Regular', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2019-11-07 21:11:10', NULL, NULL),
	('ENC', 4, 'Bien', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	('ENC', 5, 'Muy Bien', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, '2019-11-07 21:01:05', NULL, NULL),
	('EST', 1, 'Incidencias', 'Pendiente', NULL, '2,3,4,5,98', 0, 1, '#5cb85c;', '#f9f9f9;', '3,98,99', 1, 1, 1, 1, '2020-01-22 16:32:45', NULL, NULL),
	('EST', 2, 'Asignar', 'Asignado', 'Ticket Asignado', '2,3,4,5,98,99', 10, 1, '#428bca;', '#f9f9f9;', '3,98,99', 1, 1, NULL, NULL, NULL, NULL, '<i class=\"fas fa-check\"></i>'),
	('EST', 3, 'Anular', 'Anulado', 'Ticket Anulado', '0', 0, 6, '#d9534f;', '#f9f9f9;', NULL, 0, 1, 1, 0, '2019-11-07 21:11:10', NULL, '<i class=\"far fa-trash-alt\"></i>'),
	('EST', 4, 'Cambiar de Área', 'Reasignado', 'Reasignado', '2,3,4,5,98', 0, 1, '#90EE90;', '#6D6D9D;', '3,98,99', 1, 1, NULL, NULL, NULL, NULL, '<i class=\"fas fa-exchange-alt\"></i>'),
	('EST', 5, 'Iniciar Atención', 'Iniciado', 'Atención Iniciada', '2,3,7,8,98,99', 20, 2, ' 	#5cb85c;', '#f9f9f9;', '98,99', 1, 1, 0, NULL, '2019-11-07 21:01:05', NULL, '<i class=\"far fa-play-circle\"></i>'),
	('EST', 6, 'Reaperturar', 'Ticket Reaperturado', 'Ticket Reaperturado', '2,3,5, 8,98,99', 80, 2, '#ffc107;', '#f9f9f9;', '98,99', 1, 1, NULL, NULL, '2019-11-07 21:04:09', NULL, '<i class=\"far fa-folder-open\"></i>'),
	('EST', 7, 'Pausar Atención', 'Pausado', 'Atención Pausada', '2,3,5,8,98,99', 50, 3, '#FA8072;', '#f9f9f9;', '98,99', 1, 1, 0, NULL, '2020-01-22 15:01:24', NULL, '<i class=\"far fa-pause-circle\"></i>'),
	('EST', 8, 'Terminar Atención', 'Resuelto', 'Atención Resuelta', '4,6', 90, 4, ' #17a2b8;', '#f9f9f9;', '6,10', 1, 1, 1, 1, '2020-01-22 17:33:21', NULL, '<i class=\"fas fa-fast-forward\"></i>'),
	('EST', 9, 'Cerrar Atención', 'Cerrado', 'Atención Cerrada', '0', 100, 5, '#6495ED;', '#f9f9f9;', '', 0, 1, NULL, NULL, NULL, NULL, NULL),
	('EST', 10, 'Registrar Encuesta', 'Encuesta registrada', 'Encuesta registrada', '0', 100, 5, '#6495ED;', '#6D6D9D;', NULL, 0, 1, NULL, NULL, NULL, NULL, '<i class=\"far fa-smile\"></i>'),
	('EST', 98, 'Adjuntar', 'Adjuntar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '<i class=\"fas fa-paperclip\"></i>'),
	('EST', 99, 'Consultas', 'Consultas', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '<i class=\"far fa-comments\"></i>'),
	('PRIO', 1, 'Bajo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2020-01-22 16:32:45', NULL, NULL),
	('PRIO', 2, 'Normal', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	('PRIO', 3, 'Alta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2019-11-07 21:11:10', NULL, NULL),
	('TATE', 1, 'Presencial', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2020-01-22 16:32:45', NULL, NULL),
	('TATE', 2, 'Remota', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	('TPTK', 1, 'Incidencias', 'Incidencias', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, '2020-01-22 16:32:45', NULL, NULL),
	('TPTK', 2, 'Requerimientos', NULL, NULL, NULL, NULL, NULL, NULL, NULL,NULL, NULL, 1, 1, 1, NULL, NULL, NULL),
	('TPTK', 3, 'Nueva Solicitud', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2019-11-07 21:11:10', NULL, NULL),
	('TPTK', 4, 'Quejas - Reclamo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, NULL, NULL);");

    	}

}
