<?php
if(!defined('BASEPATH'))exit('No direct script access allowed');

/*helper funcion ayuda a definir los margenes tipografía y creación del footer y números de pagína APPPATH*/
function prep_pdf($orientation = 'portrait'){
    $CI =& get_instance();
    $CI->cezpdf->selectFont(APPPATH.'libraries/fonts/Helvetica.afm');

    $all = $CI->cezpdf->openObject();
    $CI->cezpdf->saveState();
    $CI->cezpdf->setStrokeColor(0,0,0,1);
    $CI->cezpdf->ezSetCmMargins(1,1,1.5,1.5);

    if($orientation == 'portrait') {
        $CI->cezpdf->ezSetMargins(20,70,20,20);
        $CI->cezpdf->ezStartPageNumbers(570,28,8,'','{PAGENUM}',1);
        $CI->cezpdf->line(20,40,578,40);
        $CI->cezpdf->addText(25,32,8,'Impreso ' . date('m/d/Y h:i:s a'));
    }
    else {
        $CI->cezpdf->ezStartPageNumbers(750,28,8,'','{PAGENUM}',1);
        $CI->cezpdf->line(20,40,800,40);
        $CI->cezpdf->addText(25,32,8,'Impreso '.date('m/d/Y h:i:s a'));
    }
    $CI->cezpdf->restoreState();
    $CI->cezpdf->closeObject();
    $CI->cezpdf->addObject($all,'all');
}

//Para Cotización

function prep_pdf_Cotiza($orientation = 'portrait'){
    $CI =& get_instance();
    $CI->cezpdf->selectFont(APPPATH.'libraries/fonts/Helvetica.afm');

    $all = $CI->cezpdf->openObject();
    $CI->cezpdf->saveState();
    $CI->cezpdf->setStrokeColor(0,0,0,1);
    $CI->cezpdf->ezSetCmMargins(1,1,1.5,1.5);

    if($orientation == 'portrait') {
        $CI->cezpdf->ezSetMargins(20,70,20,20);
        $CI->cezpdf->ezStartPageNumbers(570,28,8,'','{PAGENUM}',1);
        $CI->cezpdf->setStrokeColor(1,0,0);

        $TextoA='9 Sur Esq. 49 Pte. No. 4901             (222) 750.11.06      www.biodiagnosticos.com';
        $TextoB='Fax (52)(222)7563981 y Celular (521)(222)2 382594, e-mail: hankebosco@prodigy.net.mx';

        $TextoA=utf8_decode($TextoA);
        $TextoB=utf8_decode($TextoB);

        $CI->cezpdf->line(20,40,578,40);
        $CI->cezpdf->addText(80,32,8,$TextoA);
        $CI->cezpdf->addText(130,23,8,$TextoB);
        //$CI->cezpdf->addText(25,32,8,'Impreso ' . date('m/d/Y h:i:s a'));
   
    }
    else {
        $CI->cezpdf->ezStartPageNumbers(750,28,8,'','{PAGENUM}',1);

        $CI->cezpdf->setStrokeColor(1,0,0);
        $TextoA='Hanke-Crimp-Technik de México S.A de C.V., dsadasdadsPriv.99 B Poniente #106 Col.Arboledas de Loma Bella Pueba,Pue. C.P. 72490';
        $TextoB='Fax (52)(222)7563981 y Celular (521)(222)2 382594, e-mail: hankebosco@prodigy.net.mx';

        $CI->cezpdf->line(20,40,578,40);
        $CI->cezpdf->addText(80,32,8,$TextoA);
        $CI->cezpdf->addText(130,23,8,$TextoB);
        //$CI->cezpdf->addText(25,32,8,'Impreso '.date('m/d/Y h:i:s a'));
    }
    $CI->cezpdf->restoreState();
    $CI->cezpdf->closeObject();
    $CI->cezpdf->addObject($all,'all');
} 


?>

