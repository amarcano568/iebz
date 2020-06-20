<?php

namespace App\Exports;

use App\Miembros;
use Maatwebsite\Excel\Concerns\FromCollection;

class MiembrosExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Miembros::join('iglesias','miembros.idIglesia','=','iglesias.id')
        ->join('comunidades','miembros.comunidad','=','comunidades.id')
        ->join('provincias','miembros.provincia','=','provincias.id')
        ->join('paises','miembros.paisNacimiento','=','paises.id')
        ->leftjoin('profesiones','miembros.profesion','=','profesiones.id')
        ->join('status','miembros.status','=','status.id')
        ->select('miembros.id', 'miembros.idIglesia', 'miembros.nombre', 'miembros.apellido1', 'miembros.apellido2', 'miembros.tipoDocumento','miembros.nroDocumento', 'miembros.direccion', 'miembros.codigoPostal', 'miembros.comunidad', 'miembros.provincia', 'miembros.telefonoFijo', 'miembros.telefonoMovil', 'miembros.email', 'miembros.fecNacimiento', 'miembros.lugarNacimiento', 'miembros.paisNacimiento', 'miembros.profesion', 'miembros.fecBautismo', 'miembros.iglesiaBautismo', 'miembros.fecCartaTraslado', 'miembros.iglesiaProcedencia', 'miembros.otrosDatos', 'miembros.foto', 'miembros.relacionFamilia', 'miembros.status', 'miembros.poblacion','iglesias.nombreCorto','comunidades.nombre AS comunidad','provincias.nombre AS provincia','paises.nombre AS pais','profesiones.nombre AS profesion','status.nombre AS statusNombre')
        ->get();
    }
}
