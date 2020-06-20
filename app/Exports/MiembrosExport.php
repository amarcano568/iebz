<?php

namespace App\Exports;

use App\Miembros;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;




class MiembrosExport implements FromCollection,WithHeadings,WithDrawings, WithCustomStartCell,ShouldAutoSize, WithEvents
{
    use Exportable;
    use RegistersEventListeners;
    private $myArray;
    private $myHeadings;
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
        ->select('miembros.id',  'miembros.nombre', 'miembros.apellido1', 'miembros.apellido2', 'miembros.tipoDocumento','miembros.nroDocumento', 'miembros.direccion', 'miembros.codigoPostal', 'miembros.comunidad', 'miembros.provincia', 'miembros.telefonoFijo', 'miembros.telefonoMovil', 'miembros.email', 'miembros.fecNacimiento', 'miembros.lugarNacimiento','paises.nombre AS pais',  'miembros.profesion', 'miembros.fecBautismo', 'miembros.iglesiaBautismo', 'miembros.fecCartaTraslado', 'miembros.iglesiaProcedencia', 'miembros.otrosDatos', 'miembros.foto',  'miembros.poblacion','comunidades.nombre AS comunidad','provincias.nombre AS provincia','profesiones.nombre AS profesion','status.nombre AS statusNombre')
        ->get();
    }

    

    public static function afterSheet(AfterSheet $event)
    {
        
        $styleArray = [
            'font' => [
                'bold' => false,
                'color' => ['argb' => 'ffffff'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],

            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endColor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
        ];
        $sheet = $event->sheet->getDelegate();
        
       // $to = $event->sheet->getDelegate()->getHighestColumn();
        $event->sheet->getDelegate()->getStyle('A6:Y6')->applyFromArray($styleArray);
        $event->sheet->setCellValue('D3', 'INFORME DE MIEMBROS');
        //$sheet->setFontFamily('Comic Sans MS');
        $sheet->getStyle('D3')->getFont()->setSize(16);
        $sheet->getStyle('A6:Y6')->getFont()->setSize(14);
        $sheet->getStyle('A6:Y6')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('0091ff');
            
            
    }

    public function __construct( $myHeadings){
        $this->myHeadings = $myHeadings;
    }

    public function headings(): array{
        return $this->myHeadings;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('IEBZ LOGO');
        $drawing->setPath(public_path('/img/logo.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    
}
