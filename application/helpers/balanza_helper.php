<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function xmlBalanza($balanza,$cuentas)
{
	$xml='<?xml version="1.0" encoding="UTF-8"?>
	<BCE:Balanza xmlns:BCE="http://www.sat.gob.mx/balanza" Version="'.$balanza->version.'" RFC="'.$balanza->rfc.'" TotalCtas="'.count($cuentas).'" Mes="'.substr($balanza->fecha,5,2).'" Ano="'.substr($balanza->fecha,0,4).'">';
	
	foreach($cuentas as $row)
	{
		$xml.= "\n".'    <BCE:Ctas NumCta="'.$row->numeroCuenta.'" SaldoIni="'.$row->saldoInicial.'" Debe="'.$row->debe.'" Haber="'.$row->haber.'" SaldoFin="'.$row->saldoFinal.'" />';
	}
	
	$xml.="\n".'</BCE:Balanza>';
	
	return $xml;
}
?>
