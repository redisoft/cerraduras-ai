<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
	
function facturaGlobal($configuracion,$cliente,$sello,$certificado,$fecha,$folio,$productos)
{
	$subTotal	= 0;
	$total		= 0;
	$iva		= 0;
	$ivas		= 0;
	$ieps		= 0;
	$descuentos	= 0;
	
	foreach($productos as $row)
	{
		$importe	= $row->cantidad*$row->precio;
		$importe	= round($importe,decimales);

		$descuento	= $importe*($row->descuentoPorcentaje/100);
		$descuento	= round($descuento,decimales);
		
		$diferencia	= $importe-$descuento;
		$diferencia	= round($diferencia,decimales);
		
		$impuesto	= $diferencia*($row->tasa/100);
		$impuesto	= round($impuesto,decimales);
		
		$subTotal	+=$importe;
		$ivas		+=$impuesto;
		$descuentos	+=$descuento;
		
		#$Impuesto	= obtenerImpuestoPinata($row->impuesto);
		
		#if($Impuesto[0]=='003')
		if($row->claveImpuesto=='003')
		{
			$ieps	+=$impuesto;
		}
		
		#if($Impuesto[0]=='002')
		if($row->claveImpuesto=='002')
		{
			$iva	+=$impuesto;
		}
		
	}
	
	$total			= $subTotal-$descuentos+$ivas;
	$total			= round($total,decimales);
	
	
	$fecha			= str_replace(" ","T",$fecha);
	$sello			= str_replace(" ","",$sello);
	$certificado	= str_replace(" ","",$certificado);

	$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd"';
	$XMLFacturaComprobante.=' Sello="'.$sello.'"';
	$XMLFacturaComprobante.=' Certificado="'.$certificado.'"';
	
	#$XMLFacturaComprobante.=' xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd"';
	
	$XMLFacturaComprobante.=' Version="3.3" Folio="'.$folio.'" Serie="'.$configuracion->serie.'" Fecha="'.$fecha.'" FormaPago="'.$_POST['txtFormaPago'].'" NoCertificado="'.$configuracion->numeroCertificado.'" CondicionesDePago="'.$_POST['txtCondiciones'].'" SubTotal="'.Sprintf("% 01.2f",$subTotal).'"';
	
	if($descuentos)
	{
		$XMLFacturaComprobante.=' Descuento="'.Sprintf("% 01.2f",$descuentos).'"';
	}
	
	if(strlen($configuracion->numeroCuenta)>0)
	{
		#$XMLFacturaComprobante.=' NumCtaPago="'.$configuracion->numeroCuenta.'"';
	}
	
	$XMLFacturaComprobante.=' Moneda="MXN" Total="'.Sprintf("% 01.2f",$total).'" MetodoPago="'.$_POST['txtMetodoPago'].'" TipoDeComprobante="I" LugarExpedicion="'.sustituir($configuracion->codigoPostal).'">';
	
	$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor Rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" Nombre="'.sustituir($configuracion->nombre).'" RegimenFiscal="'.sustituir($configuracion->claveRegimen).'"/>';
	$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor Rfc="'.espaciosFactura(sustituir($cliente->rfc)).'" Nombre="'.sustituir($cliente->razonSocial).'" UsoCFDI="'.$_POST['selectUsoCfdi'].'"/>';

	$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';
	
	$i		= 0;
	$iva16	= 0;
	$iva0	= 0; 

	foreach($productos as $row)
	{
		$producto	= strlen($row->producto)>0?$row->producto:$row->nombre;
		
		$importe	= $row->cantidad*$row->precio;
		$importe	= round($importe,decimales);

		$descuento	= $importe*($row->descuentoPorcentaje/100);
		$descuento	= round($descuento,decimales);
		
		$diferencia	=$importe-$descuento;
		$diferencia	= round($diferencia,decimales);
		
		$impuesto	= $diferencia*($row->tasa/100);
		$impuesto	= round($impuesto,decimales);
		
		
		$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto ClaveProdServ="'.$row->claveProducto.'"'.(strlen(sustituir($row->codigoInterno))>0?' NoIdentificacion="'.sustituir($row->codigoInterno).'"':'').' Cantidad="'.Sprintf("% 01.2f",$row->cantidad).'" ClaveUnidad="'.$row->claveUnidad.'" Unidad="'.$row->unidad.'" ValorUnitario="'.Sprintf("% 01.4f",$row->precio).'" Descripcion="'.sustituir($producto).'" Importe="'.Sprintf("% 01.2f",$importe).'"'.($row->descuento>0?' Descuento="'.Sprintf("% 01.2f",$descuento).'"':'').'>';
		$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Impuestos>';
		$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslados>';
		
		if($row->tasa>0)
		{
			#$Impuesto	= obtenerImpuestoPinata($row->impuesto);
			
			$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslado Base="'.Sprintf("% 01.2f",$importe-$descuento).'" Impuesto="'.$row->claveImpuesto.'" TipoFactor="Tasa" TasaOCuota="'.Sprintf("% 01.6f",$row->tasa/100).'" Importe="'.Sprintf("% 01.2f",$impuesto).'"/>';
		}
		else
		{
			#$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslado Base="'.Sprintf("% 01.2f",$importe-$descuento).'" Impuesto="002" TipoFactor="Exento"/>';
			
			if($row->exento=='0')
			{
				$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslado Base="'.Sprintf("% 01.2f",$importe-$descuento).'" Impuesto="'.$row->claveImpuesto.'" TipoFactor="Tasa" TasaOCuota="'.Sprintf("% 01.6f",0).'" Importe="'.Sprintf("% 01.2f",0).'"/>';
			}
			else
			{
				$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslado Base="'.Sprintf("% 01.2f",$importe-$descuento).'" Impuesto="'.$row->claveImpuesto.'" TipoFactor="Exento"/>';
			}
		}
		
		
		$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Traslados>';
		$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Impuestos>';
		$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Concepto>';
		
		#if($row->ivaTotal>0) $iva16	= 1;
		#if($row->ivaTotal==0) $iva0	= 1;
	}

	$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';
	
	//IMPUESTOS
	if($ivas>0)
	{
		$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos TotalImpuestosTrasladados="'.Sprintf("% 01.2f",$ivas).'">';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
	
		if($iva>0)
		{
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.160000" Importe="'.Sprintf("% 01.2f",$iva).'"/>';
		}
		
		if($ieps>0)
		{
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Impuesto="003" TipoFactor="Tasa" TasaOCuota="0.080000" Importe="'.Sprintf("% 01.2f",$ieps).'"/>';
		}
		
		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
	}
	

	$retencion	= "";
	$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
	$XMLFacturaComprobante.
	$XMLFacturaEmisor.
	$XMLFacturaReceptor.
	$XMLFacturaConceptos.
	$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$retencion.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

	return $XMLFinal;
}

function facturaGlobalVentas($configuracion,$cliente,$sello,$certificado,$fecha,$folio,$ventas)
{
	$subTotal	= 0;
	$total		= 0;
	$iva		= 0;
	$ivas		= 0;
	$ieps		= 0;
	$descuentos	= 0;
	
	foreach($ventas as $row)
	{
		$precio		= round($row->subTotal,decimales);
		$descuento	= round($row->descuento,decimales);
		$importe	= $precio;

		$diferencia	= $importe-$descuento;
		$diferencia	= round($diferencia,decimales);
		
		$impuesto	= $diferencia*($row->ivaPorcentaje/100);
		$impuesto	= round($impuesto,decimales);
		
		$subTotal	+=$importe;
		$ivas		+=$impuesto;
		$descuentos	+=$descuento;
		
	}
	
	$total			= $subTotal-$descuentos+$ivas;
	$total			= round($total,decimales);
	
	
	$fecha			= str_replace(" ","T",$fecha);
	$sello			= str_replace(" ","",$sello);
	$certificado	= str_replace(" ","",$certificado);

	$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd"';
	$XMLFacturaComprobante.=' Sello="'.$sello.'"';
	$XMLFacturaComprobante.=' Certificado="'.$certificado.'"';
	
	#$XMLFacturaComprobante.=' xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd"';
	
	$XMLFacturaComprobante.=' Version="3.3" Folio="'.$folio.'" Serie="'.$configuracion->serie.'" Fecha="'.$fecha.'" FormaPago="'.$_POST['selectFormaPago'].'" NoCertificado="'.$configuracion->numeroCertificado.'" CondicionesDePago="'.$_POST['txtCondiciones'].'" SubTotal="'.Sprintf("% 01.2f",$subTotal).'"';
	
	if($descuentos)
	{
		$XMLFacturaComprobante.=' Descuento="'.Sprintf("% 01.2f",$descuentos).'"';
	}
	
	if(strlen($configuracion->numeroCuenta)>0)
	{
		#$XMLFacturaComprobante.=' NumCtaPago="'.$configuracion->numeroCuenta.'"';
	}
	
	$XMLFacturaComprobante.=' Moneda="MXN" Total="'.Sprintf("% 01.2f",$total).'" MetodoPago="'.$_POST['selectMetodoPago'].'" TipoDeComprobante="I" LugarExpedicion="'.sustituir($configuracion->codigoPostal).'">';
	
	$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor Rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" Nombre="'.sustituir($configuracion->nombre).'" RegimenFiscal="'.sustituir($configuracion->claveRegimen).'"/>';
	$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor Rfc="'.espaciosFactura(sustituir($cliente->rfc)).'" Nombre="'.sustituir($cliente->empresa).'" UsoCFDI="'.$_POST['selectUsoCfdi'].'"/>';

	$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';
	
	$i		= 0;
	$iva16	= 0;
	$iva0	= 0; 

	foreach($ventas as $row)
	{
		$precio		= round($row->subTotal,decimales);
		$descuento	= round($row->descuento,decimales);
		$importe	= $precio;

		$diferencia	= $importe-$descuento;
		$diferencia	= round($diferencia,decimales);
		
		$impuesto	= $diferencia*($row->ivaPorcentaje/100);
		$impuesto	= round($impuesto,decimales);
		
		
		$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto ClaveProdServ="'.$_POST['txtClaveProducto'].'" Cantidad="'.Sprintf("% 01.2f",1).'" ClaveUnidad="'.$_POST['txtClaveUnidad'].'" Unidad="'.substr($_POST['txtUnidad'],0,20).'" ValorUnitario="'.Sprintf("% 01.4f",$precio).'" Descripcion="'.sustituir($row->folio).'" Importe="'.Sprintf("% 01.2f",$importe).'"'.($descuento>0?' Descuento="'.Sprintf("% 01.2f",$descuento).'"':'').'>';
		$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Impuestos>';
		$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslados>';
		
		$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslado Base="'.Sprintf("% 01.2f",$diferencia).'" Impuesto="002" TipoFactor="Tasa" TasaOCuota="'.Sprintf("% 01.6f",$row->ivaPorcentaje/100).'" Importe="'.Sprintf("% 01.2f",$impuesto).'"/>';
		
		
		$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Traslados>';
		$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Impuestos>';
		$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Concepto>';
		
		#if($row->ivaTotal>0) $iva16	= 1;
		#if($row->ivaTotal==0) $iva0	= 1;
	}

	$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';
	
	$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos TotalImpuestosTrasladados="'.Sprintf("% 01.2f",$ivas).'">';
	$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';

	$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.160000" Importe="'.Sprintf("% 01.2f",$ivas).'"/>';
	
	$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
	$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
	

	$retencion	= "";
	$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
	$XMLFacturaComprobante.
	$XMLFacturaEmisor.
	$XMLFacturaReceptor.
	$XMLFacturaConceptos.
	$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$retencion.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

	return $XMLFinal;
}

?>
