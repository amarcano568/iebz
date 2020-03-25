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
        $file_to_save = base_path()."\public\pdf\\ficha-".$request->idMiembro.'.pdf';
        //save the pdf file on the server
        file_put_contents($file_to_save, $pdf->stream('invoice'));

        return "pdf/ficha-".$request->idMiembro.'.pdf';
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
        $pdf = PDF::loadView('miembros.pdf-listado-rango-edad',$data)->save(base_path()."\public\pdf\\reporte-rango-edad".$rand.".pdf");  
        //$pdf->setPaper('A4', 'portrait');
        //$file_to_save = base_path()."\public\pdf\\reporte-rango-edad".$rand.".pdf";


        //save the pdf file on the server
        //file_put_contents($file_to_save, $pdf->stream('reporte'));

        return "pdf/reporte-rango-edad".$rand.'.pdf';
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
 
        $miembros = \App\Miembros::join('profesiones','miembros.profesion','=','profesiones.id')->where(['idIglesia'=>$request->idIglesia,'miembros.status'=>$request->status])->select('miembros.id', 'miembros.idIglesia', 'miembros.nombre', 'miembros.apellido1', 'miembros.apellido2','miembros.fecNacimiento','miembros.tipoDocumento', 'miembros.nroDocumento', 'miembros.telefonoFijo', 'miembros.telefonoMovil', 'miembros.email',DB::raw('CASE WHEN miembros.sexo = "M" THEN "Masculino" WHEN miembros.sexo = "F" THEN "Femenino" ELSE "" END AS sexo'),'profesiones.nombre AS profesion')->get();
   
        $data = array(
                        'miembros' => $miembros
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
