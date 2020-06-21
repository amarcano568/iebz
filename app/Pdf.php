<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Codedge\Fpdf\Fpdf\Fpdf;

class Pdf extends Fpdf
{
    public function Header()
    {
        $this->Image('img//logo.png',10,6,30);
        $this->SetFont('Arial','B',12);
        $this->Cell(80);
        $this->Cell(30,10,'INFORME DE MIEMBROS',0,0,'C');
        $this->Cell(50);
        $this->Cell(10,10,date('d/m/Y'),0,1,'L');
        $this->Ln(5);
        $this->Line( 5,  25,  200,  25);
       // $this->Ln(1);
        $this->SetFont('Arial','B',10);
        $this->Cell(5);
        $this->Cell(45,10,'NOMBRE Y APELLIDOS',0,0,'C');
        $this->Cell(12.5);
        $this->Cell(20,10,'GENERO',0,0,'C');
        $this->Cell(22,10,'F. NAC.',0,0,'C');
        $this->Cell(17,10,'T. MOVIL',0,0,'C');
        $this->Cell(17,10,'T. FIJO',0,0,'C');
        $this->Line( 5,  35,  200,  35);
        $this->Ln(10);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,\utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }
}
