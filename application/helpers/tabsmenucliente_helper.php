<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


function MenuTabscliente($Visible,$IDC,$No){

$CLASS='class="'.$Visible.'"';
$HTML='<div class="Tabs"><ul>';

if ($IDC!='zz')
{
	$HTML.='<li '; if($No==1){ $HTML.=$CLASS; } $HTML.=' >'.anchor(base_url()."clientes/ficha/",'Historial','Historial').'</li>';
}
$HTML.='<li '; if($No==2){ $HTML.=$CLASS; } $HTML.=' >'.anchor(base_url()."ficha/cotizacion/".$IDC,'Nueva cotización','Nueva cotización').'</li>';
#$HTML.='<li '; if($No==3){ $HTML.=$CLASS; } $HTML.=' >'.anchor(base_url()."ficha/ventas_directas/".$IDC,'Ventas','Ventas').'</li>';
if ($IDC!='zz')
{
	$HTML.='<li '; if($No==4){ $HTML.=$CLASS; } $HTML.=' >'.anchor(base_url()."ficha/contactos/".$IDC,'Contactos','Contactos').'</li>';
	#$HTML.='<li '; if($No==5){ $HTML.=$CLASS; } $HTML.=' >'.anchor(base_url()."ficha/documentos/".$IDC,'Documentos','Documentos').'</li>';
}


$HTML.='</ul></div>';

return $HTML;

}



?>
