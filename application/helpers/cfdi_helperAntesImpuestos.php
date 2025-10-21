<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

#--------------------------------------------------------------------------------------------------#
#	                                    Crear el archivo XML								       #
#--------------------------------------------------------------------------------------------------#
function xmlCfd($configuracion,$cliente,$productos,$sello,$certificado,$fecha,$folio,$cotizacion,$retenciones,$divisa,$venta=0)
{
	$fecha			= str_replace(" ","T",$fecha);
	$sello			= str_replace(" ","",$sello);
	$certificado	= str_replace(" ","",$certificado);

	$subTotal	= 0;
	$total		= 0;
	$iva		= 0;
	$ivas		= 0;
	$ieps		= 0;
	$descuentos	= 0;
	/*$iva0		= false;
	$iva16		= false;*/


	foreach($productos as $row)
	{
		$cantidad		= $row->cantidad-$row->devueltos;
		
		if($cantidad>0)
		{
			$importe	= $cantidad*$row->precio;
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

			if($row->claveImpuesto=='003')
			{
				$ieps	+=$impuesto;
			}

			if($row->claveImpuesto=='002')
			{
				$iva	+=$impuesto;
			}
			
			/*if($row->tasa>0)
			{
				$iva16	= true;
			}
			
			if($row->tasa==0)
			{
				$iva0	= true;
			}*/
		}

	}

	$total			= $subTotal-$descuentos+$ivas;
	$total			= round($total,decimales);



	$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd"';
	$XMLFacturaComprobante.=' Sello="'.$sello.'"';
	$XMLFacturaComprobante.=' Certificado="'.$certificado.'"';

	$XMLFacturaComprobante.=' Version="4.0" Folio="'.$folio.'"'.(strlen($configuracion->serie)>0?' Serie="'.$configuracion->serie.'"':'').' Fecha="'.$fecha.'" Exportacion="01" FormaPago="'.$_POST['txtFormaPago'].'" NoCertificado="'.$configuracion->numeroCertificado.'"'.(strlen($_POST['txtCondiciones'])>0?' CondicionesDePago="'.$_POST['txtCondiciones'].'"':'').' SubTotal="'.Sprintf("% 01.2f",$subTotal).'"';

	if($descuentos>0)
	{
		$XMLFacturaComprobante.=' Descuento="'.Sprintf("% 01.2f",$descuentos).'"';
	}

	#TipoCambio="'.Sprintf("% 01.2f",$divisa->tipoCambio).'"

	$XMLFacturaComprobante.=' Moneda="'.$divisa->clave.'" Total="'.Sprintf("% 01.2f",$total).'" MetodoPago="'.$_POST['txtMetodoPago'].'" TipoDeComprobante="I" LugarExpedicion="'.$configuracion->codigoPostal.'">';

	$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor Rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" Nombre="'.sustituir($configuracion->nombre).'" RegimenFiscal="'.sustituir($configuracion->claveRegimen).'"/>';
	$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor Rfc="'.espaciosFactura(sustituir($cliente->rfc)).'" Nombre="'.sustituir($cliente->razonSocial).'" UsoCFDI="'.$_POST['selectUsoCfdi'].'" RegimenFiscalReceptor="'.sustituir($cliente->claveRegimen).'" DomicilioFiscalReceptor="'.sustituir($cliente->codigoPostal).'"/>';
	$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';

	$i					=1;

	if($venta==0)
	{
		#$productosParcial	=$_POST['productos'];
	}

	$iva0=false;

	foreach($productos as $row)
	{
		$cantidad		= $row->cantidad-$row->devueltos;
		
		if($cantidad>0)
		{
			if($venta==0)
			{
				#$producto	= $productosParcial[$i];
			}
			else
			{
				#$producto	= $row->nombre;
			}

			$producto	= $_POST['txtDescripcionProductoFactura'.$i];

			$importe	= $cantidad*$row->precio;
			$importe	= round($importe,decimales);

			$descuento	= $importe*($row->descuentoPorcentaje/100);
			$descuento	= round($descuento,decimales);

			$diferencia	= $importe-$descuento;
			$diferencia	= round($diferencia,decimales);

			$impuesto	= $diferencia*($row->tasa/100);
			$impuesto	= round($impuesto,decimales);


			$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto ClaveProdServ="'.$row->claveProducto.'"'.(strlen(sustituir($row->codigoInterno))>0?' NoIdentificacion="'.sustituir($row->codigoInterno).'"':'').' Cantidad="'.Sprintf("% 01.2f",$cantidad).'" ClaveUnidad="'.$row->claveUnidad.'" Unidad="'.sustituir(substr($row->unidad,0,20)).'" ValorUnitario="'.Sprintf("% 01.4f",$row->precio).'" Descripcion="'.sustituir($producto).'" Importe="'.Sprintf("% 01.2f",$importe).'"'.($row->descuento>0?' Descuento="'.Sprintf("% 01.2f",$descuento).'"':'').' ObjetoImp="02">';
			$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Impuestos>';
			$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslados>';

			if($row->tasa>0)
			{
				#$Impuesto	= obtenerImpuestoPinata($row->impuesto);

				$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslado Base="'.Sprintf("% 01.2f",$importe-$descuento).'" Impuesto="'.$row->claveImpuesto.'" TipoFactor="Tasa" TasaOCuota="'.Sprintf("% 01.6f",$row->tasa/100).'" Importe="'.Sprintf("% 01.2f",$impuesto).'"/>';
			}
			else
			{
				if($row->exento=='0')
				{
					$iva0=true;

					$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslado Base="'.Sprintf("% 01.2f",$importe-$descuento).'" Impuesto="'.$row->claveImpuesto.'" TipoFactor="Tasa" TasaOCuota="'.Sprintf("% 01.6f",0).'" Importe="'.Sprintf("% 01.2f",0).'"/>';
				}
				else
				{
					$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslado Base="'.Sprintf("% 01.2f",$importe-$descuento).'" Impuesto="'.$row->claveImpuesto.'" TipoFactor="Exento"/>';
				}

			}

			$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Traslados>';
			$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Impuestos>';

			//INCLUIR LA ADUANA SI LA TIENE
			
			$pedimento1	= trim($_POST['txtPedimento1'.$i]);
			$pedimento2	= trim($_POST['txtPedimento2'.$i]);
			$pedimento3	= trim($_POST['txtPedimento3'.$i]);
			$pedimento4	= trim($_POST['txtPedimento4'.$i]);
			$fecha		= trim($_POST['txtFecha'.$i]);
			
			$pedimento	= $pedimento1.'  '.$pedimento2.'  '.$pedimento3.'  '.$pedimento4;
			
			if(strlen($pedimento1)==2 and strlen($pedimento2)==2 and strlen($pedimento3)==4 and strlen($pedimento4)==7 and strlen($fecha)==10)
			{
				$XMLFacturaConceptos.=' <cfdi:InformacionAduanera NumeroPedimento="'.$pedimento.'"/>';
			}

			$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Concepto>';
			
			$i++;
		}
	}

	$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';

	//IMPUESTOS
	$XMLFacturaImpuestos='';
	#if($ivas>0)
	{
		$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos TotalImpuestosTrasladados="'.Sprintf("% 01.2f",$ivas).'">';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';

		if($iva0)
		{
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.000000" Importe="0.00"/>';
		}

		if($iva>0)
		{
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Base="'.Sprintf("% 01.2f",$subTotal).'" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.160000" Importe="'.Sprintf("% 01.2f",$iva).'"/>';
		}

		if($ieps>0)
		{
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Impuesto="003" TipoFactor="Tasa" TasaOCuota="0.080000" Importe="'.Sprintf("% 01.2f",$ieps).'"/>';
		}

		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
	}

	/*if($ivas==0)
	{
		$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos TotalImpuestosTrasladados="0">';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';

		$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.000000" Importe="0"/>';

		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
	}*/


	$retencion="";

	$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
	$XMLFacturaComprobante.
	$XMLFacturaEmisor.
	$XMLFacturaReceptor.
	$XMLFacturaConceptos.
	$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$retencion.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

	return $XMLFinal;
}

function xmlCfdVenta($configuracion,$cliente,$productos,$sello,$certificado,$fecha,$folio,$cotizacion,$retenciones,$divisa,$venta=0)
{
	$fecha			= str_replace(" ","T",$fecha);
	$sello			= str_replace(" ","",$sello);
	$certificado	= str_replace(" ","",$certificado);

	$subTotal	= 0;
	$total		= 0;
	$iva		= 0;
	$ivas		= 0;
	$ieps		= 0;
	$descuentos	= 0;
	/*$iva0		= false;
	$iva16		= false;*/


	foreach($productos as $row)
	{
		$cantidad		= $row->cantidad-$row->devueltos;
		
		if($cantidad>0)
		{
			$importe	= $cantidad*$row->precio;
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

			if($row->claveImpuesto=='003')
			{
				$ieps	+=$impuesto;
			}

			if($row->claveImpuesto=='002')
			{
				$iva	+=$impuesto;
			}
			
			/*if($row->tasa>0)
			{
				$iva16	= true;
			}
			
			if($row->tasa==0)
			{
				$iva0	= true;
			}*/
		}

	}

	$total			= $subTotal-$descuentos+$ivas;
	$total			= round($total,decimales);



	$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd"';
	$XMLFacturaComprobante.=' Sello="'.$sello.'"';
	$XMLFacturaComprobante.=' Certificado="'.$certificado.'"';

	$XMLFacturaComprobante.=' Version="4.0" Folio="'.$folio.'"'.(strlen($configuracion->serie)>0?' Serie="'.$configuracion->serie.'"':'').' Fecha="'.$fecha.'" Exportacion="01" FormaPago="'.$_POST['formaPago'].'" NoCertificado="'.$configuracion->numeroCertificado.'"'.(strlen($_POST['condiciones'])>0?' CondicionesDePago="'.$_POST['condiciones'].'"':'').' SubTotal="'.Sprintf("% 01.2f",$subTotal).'"';

	if($descuentos>0)
	{
		$XMLFacturaComprobante.=' Descuento="'.Sprintf("% 01.2f",$descuentos).'"';
	}

	#TipoCambio="'.Sprintf("% 01.2f",$divisa->tipoCambio).'"

	$XMLFacturaComprobante.=' Moneda="'.$divisa->clave.'" Total="'.Sprintf("% 01.2f",$total).'" MetodoPago="'.$_POST['metodoPago'].'" TipoDeComprobante="I" LugarExpedicion="'.$configuracion->codigoPostal.'">';

	$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor Rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" Nombre="'.sustituir($configuracion->nombre).'" RegimenFiscal="'.sustituir($configuracion->claveRegimen).'"/>';
	$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor Rfc="'.espaciosFactura(sustituir($cliente->rfc)).'" Nombre="'.sustituir($cliente->razonSocial).'" UsoCFDI="'.$_POST['usoCfdi'].'" RegimenFiscalReceptor="'.sustituir($cliente->claveRegimen).'" DomicilioFiscalReceptor="'.sustituir($cliente->codigoPostal).'"/>';
	$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';

	$i					=1;

	if($venta==0)
	{
		$productosParcial	=$_POST['productos'];
	}

	$iva0=false;

	foreach($productos as $row)
	{
		$cantidad		= $row->cantidad-$row->devueltos;
		
		if($cantidad>0)
		{
			if($venta==0)
			{
				$producto	= $productosParcial[$i];
			}
			else
			{
				$producto	= $row->nombre;
			}

			$importe	= $cantidad*$row->precio;
			$importe	= round($importe,decimales);

			$descuento	= $importe*($row->descuentoPorcentaje/100);
			$descuento	= round($descuento,decimales);

			$diferencia	= $importe-$descuento;
			$diferencia	= round($diferencia,decimales);

			$impuesto	= $diferencia*($row->tasa/100);
			$impuesto	= round($impuesto,decimales);


			$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto ClaveProdServ="'.$row->claveProducto.'"'.(strlen(sustituir($row->codigoInterno))>0?' NoIdentificacion="'.sustituir($row->codigoInterno).'"':'').' Cantidad="'.Sprintf("% 01.2f",$cantidad).'" ClaveUnidad="'.$row->claveUnidad.'" Unidad="'.sustituir(substr($row->unidad,0,20)).'" ValorUnitario="'.Sprintf("% 01.4f",$row->precio).'" Descripcion="'.sustituir($producto).'" Importe="'.Sprintf("% 01.2f",$importe).'"'.($row->descuento>0?' Descuento="'.Sprintf("% 01.2f",$descuento).'"':'').' ObjetoImp="02">';
			$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Impuestos>';
			$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslados>';

			if($row->tasa>0)
			{
				#$Impuesto	= obtenerImpuestoPinata($row->impuesto);

				$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslado Base="'.Sprintf("% 01.2f",$importe-$descuento).'" Impuesto="'.$row->claveImpuesto.'" TipoFactor="Tasa" TasaOCuota="'.Sprintf("% 01.6f",$row->tasa/100).'" Importe="'.Sprintf("% 01.2f",$impuesto).'"/>';
			}
			else
			{
				if($row->exento=='0')
				{
					$iva0=true;

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
			
			$i++;
		}
	}

	$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';

	//IMPUESTOS
	$XMLFacturaImpuestos='';
	#if($ivas>0)
	{
		$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos TotalImpuestosTrasladados="'.Sprintf("% 01.2f",$ivas).'">';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';

		if($iva0)
		{
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.000000" Importe="0.00"/>';
		}

		if($iva>0)
		{
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Base="'.Sprintf("% 01.2f",$subTotal).'" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.160000" Importe="'.Sprintf("% 01.2f",$iva).'"/>';
		}

		if($ieps>0)
		{
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Impuesto="003" TipoFactor="Tasa" TasaOCuota="0.080000" Importe="'.Sprintf("% 01.2f",$ieps).'"/>';
		}

		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
	}

	/*if($ivas==0)
	{
		$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos TotalImpuestosTrasladados="0">';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';

		$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.000000" Importe="0"/>';

		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
	}*/


	$retencion="";

	$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
	$XMLFacturaComprobante.
	$XMLFacturaEmisor.
	$XMLFacturaReceptor.
	$XMLFacturaConceptos.
	$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$retencion.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

	return $XMLFinal;
}

function xmlTraslado($configuracion,$cliente,$productos,$sello,$certificado,$fecha,$folio,$cotizacion)
{
	$fecha			= str_replace(" ","T",$fecha);
	$sello			= str_replace(" ","",$sello);
	$certificado	= str_replace(" ","",$certificado);

	$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd"';
	$XMLFacturaComprobante.=' Sello="'.$sello.'"';
	$XMLFacturaComprobante.=' Certificado="'.$certificado.'"';

	$XMLFacturaComprobante.=' Version="4.0" Folio="'.$folio.'"'.(strlen($configuracion->serie)>0?' Serie="'.$configuracion->serie.'"':'').' Fecha="'.$fecha.'" Exportacion="01" NoCertificado="'.$configuracion->numeroCertificado.'" SubTotal="0"';

	$XMLFacturaComprobante.=' Moneda="XXX" Total="0" TipoDeComprobante="T" LugarExpedicion="'.$configuracion->codigoPostal.'">';

	$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor Rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" Nombre="'.sustituir($configuracion->nombre).'" RegimenFiscal="'.sustituir($configuracion->claveRegimen).'"/>';
	$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor Rfc="'.espaciosFactura(sustituir($cliente->rfc)).'" Nombre="'.sustituir($cliente->razonSocial).'" UsoCFDI="'.$_POST['selectUsoCfdi'].'" RegimenFiscalReceptor="'.sustituir($cliente->claveRegimen).'" DomicilioFiscalReceptor="'.sustituir($cliente->codigoPostal).'"/>';
	$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';

	$i					=1;

	foreach($productos as $row)
	{
		$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto ClaveProdServ="'.$row->claveProducto.'"'.(strlen(sustituir($row->codigoInterno))>0?' NoIdentificacion="'.sustituir($row->codigoInterno).'"':'').' Cantidad="'.Sprintf("% 01.2f",$row->cantidad).'" ClaveUnidad="'.$row->claveUnidad.'" Unidad="'.sustituir(substr($row->unidad,0,20)).'" ValorUnitario="'.Sprintf("% 01.4f",$row->precio).'" Descripcion="'.sustituir($row->nombre).'" Importe="'.Sprintf("% 01.2f",$row->importe).'" ObjetoImp="01"';

		//INCLUIR LA ADUANA SI LA TIENE
			
		$pedimento1	= trim($_POST['txtPedimento1'.$i]);
		$pedimento2	= trim($_POST['txtPedimento2'.$i]);
		$pedimento3	= trim($_POST['txtPedimento3'.$i]);
		$pedimento4	= trim($_POST['txtPedimento4'.$i]);
		$fecha		= trim($_POST['txtFecha'.$i]);
			
		$pedimento	= $pedimento1.'  '.$pedimento2.'  '.$pedimento3.'  '.$pedimento4;
			
		if(strlen($pedimento1)==2 and strlen($pedimento2)==2 and strlen($pedimento3)==4 and strlen($pedimento4)==7 and strlen($fecha)==10)
		{
			$XMLFacturaConceptos.='>';
			$XMLFacturaConceptos.=chr(13).chr(10).' <cfdi:InformacionAduanera NumeroPedimento="'.$pedimento.'"/>';
			$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Concepto>';
		}
		else
		{
			$XMLFacturaConceptos.='/>';
		}

		$i++;
	}

	$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';

	//IMPUESTOS
	$XMLFacturaImpuestos='';

	
	$retencion="";

	$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
	$XMLFacturaComprobante.
	$XMLFacturaEmisor.
	$XMLFacturaReceptor.
	$XMLFacturaConceptos.
	$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$retencion.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

	return $XMLFinal;
}

?>
