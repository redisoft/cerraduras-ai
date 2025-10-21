<?php
require_once(APPPATH.'libraries/tcpdf/config/tcpdf_config.php');
require_once(APPPATH.'libraries/tcpdf/tcpdf.php');

class Ctcpdf extends TCPDF{
    
  function TCPDF(){
	  parent::TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	}



	public function Header() {

                $this->SetXY(1,1);
                $this->SetDrawColor(132,132,132);
                $this->SetLineWidth(.13);
                $this->Line(10,8,203,8);
                $this->SetFont('helvetica',"",7);
                //Número de pgina
                $TextoA=utf8_decode('Fecha impreso: '.date("Y-m-d"));
                $Pagina=utf8_decode('Pág.'.$this->PageNo().'/{nb}');
                $this->Ln(1);
                $this->Cell(176,8,$TextoA,0,0,'R');
                $this->Cell(13,8,$Pagina,0,0,'R');
                $this->Ln(3);

	}

	// Page footer
	public function Footer() {

                $this->SetY(-15);
                $this->SetDrawColor(132,132,132);
                $this->SetLineWidth(.13);
                $this->Line(10,285,203,285);
                $this->SetFont('helvetica',"",7);
                //Número de pgina
                $TextoA=utf8_decode('9 Sur Esq. 49 Pte. No. 4901                                      (222) 750.11.06                              www.biodiagnosticos.com');
                $TextoB=utf8_decode('Altos 5 Plaza Comercial 49.9                                          (222) 237.00.58                             ventas@biodiagnosticos.com     ');
                $Pagina=utf8_decode('Pág.'.$this->PageNo().'/{nb}');
                $this->Ln(1);
           /*     
				$this->Cell(0,7,$TextoA,0,0,'C');
                $this->Cell(0,7,$Pagina,0,0,'C');
                $this->Ln(3);
                $this->Cell(0,7,$TextoB,0,0,'C');
*/
$pie='<table width="95%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td scope="col">&nbsp;</td>
    <td scope="col">&nbsp;</td>
    <td scope="col">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" scope="col">9 Sur Esq. 49 Pte. No. 4901 </td>
    <td align="center" scope="col"><div align="center">(222) 750.11.06</div></td>
    <td align="right" scope="col">www.biodiagnosticos.com</td>
  </tr>
  <tr>
    <td align="left">Altos 5 Plaza Comercial 49.9 </td>
    <td align="center"><div align="center">(222) 237.00.58 </div></td>
    <td align="right">ventas@biodiagnosticos.com</td>
  </tr>
  <tr>
    <td align="left">Col. Prados Agua Azul, Puebla, Pue. </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
';
    //   $this->writeHTML($pie, true, false, false, false, '');
	}

}//Fin de la clase



?>
