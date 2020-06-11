<?php

namespace App\Http\Controllers;
use \Auth;
use Carbon\Carbon;
use \DB;
use File;
use \Miembros;
use \Comunidades;
use \Provincias;
use \Paises;
use \Iglesias;
use \Profesiones;
use \Status;
use \Usuarios;
use Uuid;
use \SubMinisterios;
use PDF;
use Illuminate\Http\Request;
use App\Traits\funcGral;


class informesController extends Controller
{
    public function reportCumpleanos()
    {

        return view('miembros.cumpleanos');
    }


    public function listarCumpleanos(Request $request)
    {

        $miembros = \App\Miembros::join('iglesias','miembros.idIglesia','=','iglesias.id')->whereMonth('fecNacimiento', $request->mes)->where(['idIglesia'=>$request->iglesia,'miembros.status'=>1])->select('miembros.id', 'miembros.idIglesia', 'miembros.nombre', 'miembros.apellido1', 'miembros.apellido2','miembros.fecNacimiento',DB::raw('DAY(fecNacimiento) dia'))->orderBy('dia', 'ASC')->get();
        $miembros->map(function($miembro){           
            $edad = Carbon::parse($miembro->fecNacimiento)->age;
            $miembro->edad = $edad;
        });     

        $data = array(
                        'miembros' => $miembros,
                        'nombreMes' => $request->nombreMes
                    );

        $pdf = PDF::loadView('miembros.pdf-listado-cumpleanos',$data);  
        $pdf->setPaper('A4', 'portrait');
        
        //echo  base_path()."\public\pdf\\ficha-".$request->idMiembro.'.pdf';
        $file_to_save = base_path()."\public\pdf\\ficha-".$request->idMiembro.'.pdf';
        //save the pdf file on the server
        file_put_contents($file_to_save, $pdf->stream('invoice'));

        return "\pdf\\ficha-".$request->idMiembro.'.pdf';
    }

    public function reportRangoEdades()
    {

        $status = \App\Status::get();
        $iglesias = \App\Iglesias::get();

        $data = array(  

                        'status'      => $status,
                        'iglesias'    => $iglesias
                     );

        return view('miembros.edades',$data);
    }

    public function listarRangoEdad(Request $request)
    {
        $edad = explode(';',$request->rangeEdad);
        $edadDesde = $edad[0];
        $edadHasta = $edad[1];
        $miembros = \App\Miembros::whereBetween(DB::raw('TIMESTAMPDIFF(YEAR,miembros.fecNacimiento,CURDATE())'),array($edadDesde,$edadHasta))->where(['idIglesia'=>$request->idIglesia,'miembros.status'=>$request->status])->select('miembros.id', 'miembros.idIglesia', 'miembros.nombre', 'miembros.apellido1', 'miembros.apellido2','miembros.fecNacimiento',DB::raw('TIMESTAMPDIFF(YEAR,miembros.fecNacimiento,CURDATE()) AS edad'))->get();
        $total = count($miembros);   

        $data = array(
                        'miembros'  => $miembros,
                        'edadDesde' => $edadDesde,
                        'edadHasta' => $edadHasta,
                        'total'     => $total,
                        'status'    => $request->nombreStatus
                    );

        $rand = rand(0,1000);
        //echo base_path()."\public\pdf\\reporte-rango-edad".$rand.".pdf";
        $pdf = PDF::loadView('miembros.pdf-listado-rango-edad',$data)->save(base_path()."\public\pdf\\reporte-rango-edad".$rand.".pdf");  
        //$pdf->setPaper('A4', 'portrait');
        //$file_to_save = base_path()."\public\pdf\\reporte-rango-edad".$rand.".pdf";


        //save the pdf file on the server
        //file_put_contents($file_to_save, $pdf->stream('reporte'));

        return "\pdf\\reporte-rango-edad".$rand.".pdf";
    }

    public function informeMiembros()
    {

        $status = \App\Status::get();
        $iglesias = \App\Iglesias::get();

        $data = array(  

                        'status'      => $status,
                        'iglesias'    => $iglesias
                     );

        return view('miembros.informe-miembros',$data);
    }

    public function listarMiembros(Request $request)
    {
 
        $orden = $request->ordenado == 1 ? 'nombre' : 'apellido1';
        $miembros = \App\Miembros::join('profesiones','miembros.profesion','=','profesiones.id')->where(['idIglesia'=>$request->idIglesia,'miembros.status'=>$request->status])->select('miembros.id', 'miembros.idIglesia', 'miembros.nombre', 'miembros.apellido1', 'miembros.apellido2','miembros.fecNacimiento','miembros.tipoDocumento', 'miembros.nroDocumento', 'miembros.telefonoFijo', 'miembros.telefonoMovil', 'miembros.email',DB::raw('CASE WHEN miembros.sexo = "M" THEN "Masculino" WHEN miembros.sexo = "F" THEN "Femenino" ELSE "" END AS sexo'),'profesiones.nombre AS profesion')->orderBy($orden, 'ASC')->get();
        $miembros->map(function($miembro){           
            $nombreOrdenadoNombre =  trim($miembro->nombre).' '.trim($miembro->apellido1).' '.trim($miembro->apellido2);
            $nombreOrdenadoApellido =   trim($miembro->apellido1) .' '.  trim($miembro->apellido2).', '.  trim($miembro->nombre);
            $miembro->Nombre = str_pad(trim(substr($nombreOrdenadoNombre,0,30)),  30, " ");
            $miembro->Apellido = str_pad(trim(substr($nombreOrdenadoApellido,0,30)),  30, " ");
        });

        $data = array(
                        'miembros' => $miembros,
                        'orden' => $request->ordenado
                    );     

        $rand = rand(0,1000);
        $pdf = PDF::loadView('miembros.pdf-listado-miembros',$data)->save(base_path()."\public\pdf\\informe-miembros".$rand.".pdf");  
        return "pdf/informe-miembros".$rand.'.pdf';
    }


    public function listarInformeMinisterios(Request $request)
    {

        if ($request->idMinisterio == '*'){
            $ministerios = \App\Ministerios::get();
        }else{
            $ministerios = \App\Ministerios::where('id',$request->idMinisterio)->get();
        }
        

        $salida = '<table style="100%" class="table table-striped table-bordered>">
                    <tbody>';
        foreach ($ministerios as $ministerio) {
            $miembros = $ministerio->miembros;
            $status = $ministerio->status == 1 ? 'ACTIVO' : 'INACTIVO';
            $lista = substr(str_replace(';', ",", $miembros), 0, -1);
            $listaMiembros = \App\Miembros::select('id','nombre','apellido1','apellido2','telefonoFijo','telefonoMovil','email')->whereIn('id',explode(',', $lista))->get();

            $salida .= '<tr bgcolor="#AAB7B8">
                            <td colspan="5" style="text-align: center;"> MINISTERIO DE '.strtoupper($ministerio->nombre).'</td>
                        </tr>';
            
            foreach ($listaMiembros as $miembro) {
                $salida .= '<tr>
                                <td colspan="2" >'.$miembro->nombre.' '.$miembro->apellido1.' '.$miembro->apellido2.'</td>
                                <td>'.$miembro->telefonoFijo.'</td>
                                <td>'.$miembro->telefonoMovil.'</td>
                                <td>'.$miembro->email.'</td>
                            </tr>';
            }

            $salida .= $this->subMinisterios($ministerio->id);
            
        }

        $salida .= '</tbody></table>';

        $data = array(
                        'salida' => $salida,
                        'nombreMinisterio' => strtoupper($request->nombreMinisterio)
                    );

        $rand = rand(0,1000);
        $pdf = PDF::loadView('miembros.pdf-listado-ministerios',$data)->save(base_path()."\public\pdf\\informe-ministerios".$rand.".pdf");  
        return "pdf/informe-ministerios".$rand.'.pdf';

    }


    public function subMinisterios($idMinisterio)
    {
        $subMinisterios = \App\SubMinisterios::where('idMinisterio',$idMinisterio)->get();

        $salida = '';

        if ($subMinisterios->count() > 0){
            foreach ($subMinisterios as $subMinisterio) {
                $status = $subMinisterio->status == 1 ? '<br><span class="badge badge-pill badge-success">&nbsp;Activo&nbsp;</span>' : '<br><span class="badge badge-pill badge-danger">&nbsp;Inactivo&nbsp;</span>';
                $total = count(explode(';',$subMinisterio->miembros))-1;
                $salida .= '<tr><td colspan="5">
                                    <span class="text-info">
                                        <strong>
                                            '.strtoupper($subMinisterio->nombre).'
                                        </strong>
                                    </span>
                            </td></tr>'.$this->miembrosMinisterio($subMinisterio->miembros,$subMinisterio->id,2);;
            }

            return $salida;
        }

        return '';
    }

    public function miembrosMinisterio($miembros,$idMinisterio,$nivel)
    {
        $lista = substr(str_replace(';', ",", $miembros), 0, -1);
        $listaMiembros = \App\Miembros::select('id','nombre','apellido1','apellido2','telefonoFijo','telefonoMovil','email')->whereIn('id',explode(',', $lista))->get();
        $salida = '';    
        foreach ($listaMiembros as $miembro) {
            $salida .= '<tr>
                                <td colspan="2" >'.$miembro->nombre.' '.$miembro->apellido1.' '.$miembro->apellido2.'</td>
                                <td>'.$miembro->telefonoFijo.'</td>
                                <td>'.$miembro->telefonoMovil.'</td>
                                <td>'.$miembro->email.'</td>
                            </tr>';


        }

        return $salida;
    }

    public function listarInformeMinisterios2(Request $request)
    {

        if ($request->idMinisterio == '*'){
            $ministerios = \App\Ministerios::get();
        }else{
            $ministerios = \App\Ministerios::where('id',$request->idMinisterio)->get();
        }
        

        $salida = '<table >
                    <tbody>';
        foreach ($ministerios as $ministerio) {
            $miembros = $ministerio->miembros;
            $status = $ministerio->status == 1 ? 'ACTIVO' : 'INACTIVO';
            $lista = substr(str_replace(';', ",", $miembros), 0, -1);
            $listaMiembros = \App\Miembros::select('id','nombre','apellido1','apellido2','telefonoFijo','telefonoMovil','email')->whereIn('id',explode(',', $lista))->get();

            $salida .= '<tr bgcolor="#AAB7B8">
                            <td>'.$ministerio->id.'</td>
                            <td>'.strtoupper($ministerio->nombre).'</td>
                            <td>'.$status.'</td>
                        </tr>';
            
            foreach ($listaMiembros as $miembro) {
                $salida .= '<tr>
                                <td></td>
                                <td>'.$miembro->nombre.' '.$miembro->apellido1.' '.$miembro->apellido2.'</td>
                                <td>'.$miembro->telefonoFijo.'</td>
                                <td>'.$miembro->telefonoMovil.'</td>
                                <td>'.$miembro->email.'</td>
                            </tr>';
            }
            
        }

        $salida .= '</tbody></table>';

        $data = array(
                        'salida' => $salida,
                        'nombreMinisterio' => $request->nombreMinisterio
                    );

        $rand = rand(0,1000);
        $pdf = PDF::loadView('miembros.pdf-listado-ministerios',$data)->save(base_path()."\public\pdf\\informe-ministerios".$rand.".pdf");  
        return "pdf/informe-ministerios".$rand.'.pdf';

    }

}
