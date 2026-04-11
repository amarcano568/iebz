<?php

namespace App\Http\Controllers;

use Usuarios;

use \Auth;
use Carbon\Carbon;
use \DB;
use File;
use \Miembros;
use \Status;
use Illuminate\Http\Request;
use App\Traits\funcGral;

class mantenimientoController extends Controller
{
    use funcGral;


    public function loadUsuarios()
    {
        $cargos = \App\Cargos::all();
        $data = array(
            'cargos' => $cargos
        );
        return view('mantenimiento.usuarios', $data);
    }

    /**
     *      Lista Usuarios del Sistema.
     */
    public function cargaUsuarios()
    {

        $usuarios = \App\Usuarios::get();

        $dataSet = array(
            "sEcho"                 =>  0,
            "iTotalRecords"         =>  1,
            "iTotalDisplayRecords"  =>  1,
            "aaData"                =>  array()
        );

        foreach ($usuarios as $usuario) {
            if ($usuario->status == 1) {
                $status = '<span class="badge badge-pill badge-success"><i class="fas fa-check"></i> Activo</span>';
                $candado  = '<i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Bloquear Usuario (<strong>' . $usuario->name . '</strong>)."  class="text-success fas fa-lock-open"></i>';
            } else {
                $status = '<span class="badge badge-pill badge-danger"><i class="fas fa-ban"></i> Inactivo</span>';
                $candado  = '<i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Desbloquear Usuario (<strong>' . $usuario->name . '</strong>)."  class="text-danger fas fa-lock"></i>';
            }


            $dataSet['aaData'][] = array(
                $usuario->id,
                $usuario->name,
                $usuario->lastName,
                $usuario->email,
                $usuario->rol,
                $status,
                '<div class="icono-action">
                                                <a href="" data-accion="editarUsuario" idUsuario="' . $usuario->id . '" >
                                                    <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Editar Usuario (<strong>' . $usuario->name . '</strong>)." class="icono-action text-primary far fa-edit">
                                                    </i>
                                                </a>
                                                <a href="" data-accion="bloquearUsuario" idUsuario="' . $usuario->id . '" status="' . $usuario->status . '">
                                                    ' . $candado . '
                                                </a>
                                                <a data-accion="editarRole" urlRole="users/' . $usuario->id . '/edit" href="users/' . $usuario->id . '/edit">          
                                                    <i data-trigger="hover" data-html="true" data-toggle="popover" data-placement="top" data-content="Editar Rol (<strong>' . $usuario->name . '</strong>)." class="icono-action text-info fab fa-r-project">
                                                    </i>
                                                </a>
                                            </div>'
            );
        }

        $salidaDeDataSet = json_encode($dataSet, JSON_HEX_QUOT);

        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
    }


    /**
     * [registrarMotivo description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function registrarUsuario(Request $request)
    {

        try {
            DB::beginTransaction();
            $conn = $this->BaseDatosEmpresa();
            $idEmpresa = Auth::user()->id_Empresa;
            if (is_null($request->idUsuario)) {

                $rules = [
                    'correo' => ['required', 'email', 'unique:users,email'],
                    // 'Username' => ['required', 'unique:users,userName' ],
                ];

                $customMessages =   [
                    'correo.unique' => '<i class="fas fa-exclamation-triangle"></i> Existe otro Usuario con ese <strong>Correo</strong>',
                    // 'Username.unique'  => '<i class="fas fa-exclamation-triangle"></i> Existe otro Usuario con ese <strong>UserName</strong>',
                ];

                $v =  $this->validate($request, $rules, $customMessages);
            }
            $save = \App\Usuarios::Guardar($request, $conn, $idEmpresa);
            DB::commit();
            if (!$save) {
                App::abort(500, 'Error');
            }

            return response()->json(array('success' => true, 'mensaje' => ''));
        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function buscarUsuario(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $usuarios = \App\Usuarios::find($request->idUsuario);

        $data = array(
            'data' => $usuarios
        );

        return response()->json(array('success' => true, 'mensaje' => '', 'data' => $usuarios));
    }

    public function bloquearUsuario(Request $request)
    {
        $conn = $this->BaseDatosEmpresa();
        $usuario = \App\Usuarios::find($request->idUsuario);

        $usuario->status = $usuario->status == 1 ? 0 : 1;
        if ($usuario->save()) {
            $success = true;
            $mensaje = 'Status cambiado exitosamente';
        } else {
            $success = false;
            $mensaje = 'El Status no pudo ser cambiado.';
        }


        return response()->json(array('success' => $success, 'mensaje' => $mensaje, 'data' => ''));
    }


    public function perfilUsuario()
    {
        $idUser = Auth::id();
        $usuario = \App\Usuarios::find($idUser);
        $cargo = \App\Cargos::find($usuario->cargo);
        $usuario->nomCargo = $cargo->descCargo;


        $data = array(
            'usuario' => $usuario,
        );

        return view('mantenimiento.perfil', $data);
    }


    public function buscarImagenUsuario()
    {

        return response()->json(
            array(
                'success' => true,
                'mensaje' => 'Query realizado..!',
                'photo' => Auth::user()->photo
            )
        );
    }

    public function subirFoto(Request $request)
    {

        $conn = $this->BaseDatosEmpresa();
        $ruta     = '/Empresas/' . $conn . '/fotos/';
        $path     = public_path() . $ruta;
        $files    = $request->file('file');
        $ext      = explode('/', $request->file('file')->getMimeType());
        $fileName = $files->getClientOriginalName();
        $files->move($path, $fileName);

        rename($path . $fileName, $path . 'foto-' . Auth::id() . '.' . $ext[1]);

        DB::beginTransaction();
        $Usuarios = \App\Usuarios::find(Auth::id());
        $Usuarios->photo = "Empresas\\" . $conn . "\\fotos\\" . 'foto-' . Auth::id() . '.' . $ext[1];
        $Usuarios->save();
        DB::commit();
        //Storage::move($path.$fileName, $path.'usuario-'.$request->idSucursal);
        return "Empresas\\" . $conn . "\\fotos\\" . 'foto-' . Auth::id() . '.' . $ext[1];
    }


    /**
     *      Mantenimiento de Profesiones.
     */

    public function Profesiones()
    {

        return view('mantenimiento.profesiones');
    }

    public function listarProfesiones()
    {

        $profesiones = \App\Profesiones::get();


        $dataSet = array(
            "sEcho"                 =>  0,
            "iTotalRecords"         =>  1,
            "iTotalDisplayRecords"  =>  1,
            "aaData"                =>  array()
        );
        $contador = 1;
        foreach ($profesiones as $profesion) {

            $id     = $profesion->id;
            $nombre = $profesion->nombre;
            $status = $profesion->status;

            if ($status == 1) {
                $ActDes = '<span class="badge badge-pill badge-success">Activo</span>';
                $etiqueta = '<a data-accion="inactivar" class="blue" href="" idProfesion="' . $id . '">
                                <i class="ace-icon fa fa-unlock bigger-130" title="Inactivar Profesión"></i>
                            </a>';
            } else {
                $ActDes = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                $etiqueta = '<a data-accion="activar" class="purple" href="" idProfesion="' . $id . '">
                                <i class="ace-icon fa fa-lock bigger-130" title="Activar Profesión"></i>
                            </a>';
            }

            $botones    = '<div class="action-buttons">
                                <td>
                                    <a idProfesion="' . $id . '" status="' . $status . '" data-accion="editarProfesion" class="green" href="">
                                        <i class="far fa-edit" title="Editar Detalle de la Profesión"></i>                                        
                                    </a>
                                    ' . $etiqueta . '
                                    
                                    <a id="' . $id . '" class="red" style="display: none;">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    </a>
                                </td>
                            </div>';


            $dataSet['aaData'][] = array(
                $id,
                $nombre,
                $ActDes,
                $botones
            );
            $contador++;
        }

        $salidaDeDataSet = json_encode($dataSet, JSON_HEX_QUOT);

        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
    }

    public function actualizarStatusProfesion(Request $request)
    {

        $profesion = \App\Profesiones::find($request->idProfesion);

        $profesion->status = $profesion->status == 1 ? 0 : 1;
        if (!$profesion->save()) {
            App::abort(500, 'Error');
        }

        return response()->json(array('success' => true, 'mensaje' => 'Status cambiado exitosamente..!'));
    }

    public function registrarProfesion(Request $request)
    {
        //return $request;
        try {
            DB::beginTransaction();

            $save = \App\Profesiones::Guardar($request);
            DB::commit();
            if (!$save) {
                App::abort(500, 'Error');
            }

            return response()->json(array('success' => true, 'mensaje' => 'Profesión guardada exitosamente..!'));
        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function registrarProfesionMiembros(Request $request)
    {
        //return $request; 
        $profesion = new \App\Profesiones;
        $profesion->nombre = $request->nombreProfesion;
        $profesion->status = 1;
        $profesion->save();
        $id = $profesion->id;

        return response()->json(array('success' => true, 'mensaje' => 'Profesión guardada exitosamente..!', 'idNuevaProfesion' => $id));
    }

    public function EditarProfesion(Request $request)
    {
        //return $request;
        try {
            DB::beginTransaction();

            $profesion = \App\Profesiones::find($request->idProfesion);

            return response()->json(array('success' => true, 'mensaje' => 'Cargo guardado exitosamente..!', 'data' => $profesion));
        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    /**
     *      Mantenimiento de Paises.
     */

    public function paises()
    {

        return view('mantenimiento.paises');
    }

    public function listarPaises()
    {

        $paises = \App\Paises::get();


        $dataSet = array(
            "sEcho"                 =>  0,
            "iTotalRecords"         =>  1,
            "iTotalDisplayRecords"  =>  1,
            "aaData"                =>  array()
        );
        $contador = 1;
        foreach ($paises as $pais) {

            $id     = $pais->id;
            $nombre = $pais->nombre;
            $status = $pais->status;

            if ($status == 1) {
                $ActDes = '<span class="badge badge-pill badge-success">Activo</span>';
                $etiqueta = '<a data-accion="inactivar" class="blue" href="" idPais="' . $id . '">
                                <i class="ace-icon fa fa-unlock bigger-130" title="Inactivar Pais"></i>
                            </a>';
            } else {
                $ActDes = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                $etiqueta = '<a data-accion="activar" class="purple" href="" idPais="' . $id . '">
                                <i class="ace-icon fa fa-lock bigger-130" title="Activar Pais"></i>
                            </a>';
            }

            $botones    = '<div class="action-buttons">
                                <td>
                                    <a idPais="' . $id . '" status="' . $status . '" data-accion="editarPais" class="green" href="">
                                        <i class="far fa-edit" title="Editar Detalle del Pais"></i>
                                    </a>
                                    ' . $etiqueta . '
                                    
                                    <a id="' . $id . '" class="red" style="display: none;">
                                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    </a>
                                </td>
                            </div>';


            $dataSet['aaData'][] = array(
                $id,
                $nombre,
                $ActDes,
                $botones
            );
            $contador++;
        }

        $salidaDeDataSet = json_encode($dataSet, JSON_HEX_QUOT);

        /* SE DEVUELVE LA SALIDA */
        echo $salidaDeDataSet;
    }


    public function actualizarStatusPais(Request $request)
    {

        $pais = \App\Paises::find($request->idPais);

        $pais->status = $pais->status == 1 ? 0 : 1;
        if (!$pais->save()) {
            App::abort(500, 'Error');
        }

        return response()->json(array('success' => true, 'mensaje' => 'Status cambiado exitosamente..!'));
    }

    public function EditarPais(Request $request)
    {
        //return $request;
        try {
            DB::beginTransaction();

            $pais = \App\Paises::find($request->idPais);

            return response()->json(array('success' => true, 'mensaje' => 'Cargo guardado exitosamente..!', 'data' => $pais));
        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function registrarPais(Request $request)
    {

        try {
            DB::beginTransaction();

            $save = \App\Paises::Guardar($request);
            DB::commit();
            if (!$save) {
                App::abort(500, 'Error');
            }

            return response()->json(array('success' => true, 'mensaje' => 'País guardado exitosamente..!'));
        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function loadInfo()
    {
        // Total general de miembros
        $total = \App\Miembros::count();

        // Obtener conteos por status
        $statusCounts = \App\Miembros::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Obtener conteos por género
        $generos = \App\Miembros::selectRaw('sexo, COUNT(*) as total')
            ->groupBy('sexo')
            ->get();

        // ========================
        // Construcción del HTML
        // ========================
        $salida = '';

        // Tile Principal - Total Miembros
        $salida .= '<div class="col-lg-2 col-md-2 col-sm-4 tile_stats_count" style="width: 25em!important">
                  <span class="count_top"><i class="fas fa-users"></i> Miembros</span>
                  <div class="count" id="total">' . $total . '</div>
                </div>';

        // Tiles de Status (con la modificación solicitada)
        foreach ($statusCounts as $statusId => $item) {

            $rowStatus = \App\Status::find($statusId);
            if (!$rowStatus) continue;

            $valorMostrar = $item->total;
            $nombreStatus = $rowStatus->nombre;
            $icono       = $rowStatus->fontAwesome;
            $color       = $rowStatus->color;

            // === MODIFICACIÓN ESPECIAL ===
            // Si es "Miembro Alta", sumamos también los "Miembro Pasivo"
            if (strtolower($nombreStatus) === 'miembro alta' || $nombreStatus === 'Alta') {

                $pasivo = $statusCounts->get( /* aquí va el ID del estado Pasivo */
                    // Si no sabes el ID, puedes buscar por nombre:
                    $statusCounts->first(function ($s, $id) {
                        $st = \App\Status::find($id);
                        return $st && (strtolower($st->nombre) === 'miembro pasivo' || $st->nombre === 'Pasivo');
                    })?->total ?? 0
                );

                $valorMostrar = $item->total + $pasivo;
                $nombreStatus = 'Miembro Alta';   // puedes cambiar el texto si quieres
            }

            $salida .= '<div class="col-lg-2 col-md-2 col-sm-4 tile_stats_count">
                      <span class="count_top">' . $icono . ' ' . $nombreStatus . '</span>
                      <div class="count" style="color:' . $color . '">' . $valorMostrar . '</div>
                    </div>';
        }

        // Tiles de Género
        foreach ($generos as $genero) {
            if ($genero->sexo == 'M') {
                $icono = '<i class="fas fa-male"></i>';
                $nombre = 'Masculino';
                $color = '#A6D2AE';
            } elseif ($genero->sexo == 'F') {
                $icono = '<i class="fas fa-female"></i>';
                $nombre = 'Femenino';
                $color = '#FECFD7';
            } else {
                $icono = '<i class="fas fa-question"></i>';
                $nombre = 'Sin asignar Género';
                $color = '#CFCFC3';
            }

            $salida .= '<div class="col-lg-2 col-md-2 col-sm-4 tile_stats_count">
                      <span class="count_top">' . $icono . ' ' . $nombre . '</span>
                      <div class="count" style="color: ' . $color . '">' . $genero->total . '</div>
                    </div>';
        }

        // ========================
        // Datos para los gráficos (mantengo lógica original)
        // ========================
        $values = [];
        $labels = [];
        $colores = [];

        foreach ($statusCounts as $statusId => $item) {
            $rowStatus = \App\Status::find($statusId);
            if (!$rowStatus) continue;

            $values[] = $item->total;                    // gráfico usa valor real (sin suma)
            $labels[] = $rowStatus->nombre;
            $colores[] = $rowStatus->color;
        }

        $valuesGenero = $generos->pluck('total')->toArray();
        $labelsGenero = $generos->map(function ($g) {
            return match ($g->sexo) {
                'M' => 'Masculino',
                'F' => 'Femenino',
                default => 'Sin asignar Género'
            };
        })->toArray();

        $coloresGenero = $generos->map(function ($g) {
            return match ($g->sexo) {
                'M' => '#A6D2AE',
                'F' => '#FECFD7',
                default => '#CFCFC3'
            };
        })->toArray();

        $data = [
            'graficoMiembros' => [
                'values'  => $values,
                'labels'  => $labels,
                'colores' => $colores
            ],
            'graficoGenero' => [
                'values'  => $valuesGenero,
                'labels'  => $labelsGenero,
                'colores' => $coloresGenero
            ],
            'tableroInfo' => $salida
        ];

        return response()->json([
            'success' => true,
            'mensaje' => 'Información generada exitosamente.',
            'data'    => $data
        ]);
    }
}
