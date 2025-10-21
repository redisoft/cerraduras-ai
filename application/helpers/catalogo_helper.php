<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function xmlCatalogo($configuracion,$cuentas,$fecha)
{
	$xml='<?xml version="1.0" encoding="UTF-8"?>
	<catalogocuentas:Catalogo xmlns:catalogocuentas="www.sat.gob.mx/esquemas/ContabilidadE/1_1/CatalogoCuentas" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="www.sat.gob.mx/esquemas/ContabilidadE/1_1/CatalogoCuentas http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/CatalogoCuentas/CatalogoCuentas_1_1.xsd" Version="'.version.'" RFC="'.$configuracion->rfc.'" Mes="'.substr($fecha,5,2).'" Anio="'.substr($fecha,0,4).'" >';
	
	foreach($cuentas as $row)
	{
		if($row->idCuentaPadre==0)
		{
			$xml.= "\n".'    <catalogocuentas:Ctas CodAgrup="'.$row->codigoAgrupador.'" NumCta="'.$row->numeroCuenta.'" Desc="'.$row->descripcion.'"'.(strlen($row->subCuentaPadre)>0?' SubCtaDe="'.$row->subCuentaPadre.'"':'').' Nivel="'.$row->nivel.'" Natur="'.$row->naturaleza.'" />';
			
			if($row->cuentasHijo>0)
			{
				$xml.=xmlCatalogoSubCuentas($cuentas,$row->idCuentaCatalogo);
			}
		}
	}
	
	$xml.="\n".'</catalogocuentas:Catalogo>';
	
	return $xml;
}

function xmlCatalogoSubCuentas($cuentas,$idCuentaCatalogo)
{
	$xml='';
	foreach($cuentas as $row)
	{
		if($row->idCuentaPadre==$idCuentaCatalogo)
		{
			$xml.= "\n".'    <catalogocuentas:Ctas CodAgrup="'.$row->codigoAgrupador.'" NumCta="'.$row->numeroCuenta.'" Desc="'.$row->descripcion.'"'.(strlen($row->subCuentaPadre)>0?' SubCtaDe="'.$row->subCuentaPadre.'"':'').' Nivel="'.$row->nivel.'" Natur="'.$row->naturaleza.'" />';
			
			if($row->cuentasHijo>0)
			{
				$xml.=xmlCatalogoSubCuentas($cuentas,$row->idCuentaCatalogo);
			}
		}
	}
	
	return $xml;
}
?>
