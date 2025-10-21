<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function facturaManual($configuracion,$cliente,$sello,$certificado,$fecha,$folio,$divisa)
{
	$fecha			= str_replace(" ","T",$fecha);
	$sello			= str_replace(" ","",$sello);
	$certificado	= str_replace(" ","",$certificado);
	$ivaPorcentaje	= $_POST['txtIvaPorcentaje'];

	$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd"';
	$XMLFacturaComprobante.=' Sello="'.$sello.'"';
	$XMLFacturaComprobante.=' Certificado="'.$certificado.'"';

	$XMLFacturaComprobante.=' Version="3.3" Folio="'.$folio.'" Serie="'.$configuracion->serie.'" Fecha="'.$fecha.'" FormaPago="'.$_POST['txtFormaPago'].'" NoCertificado="'.$configuracion->numeroCertificado.'" CondicionesDePago="'.$_POST['txtCondiciones'].'" SubTotal="'.Sprintf("% 01.2f",$_POST['txtSubTotal']).'"';
	
	/*if($cotizacion->descuento>0)
	{
		$XMLFacturaComprobante.=' Descuento="'.Sprintf("% 01.2f",$cotizacion->descuento).'"';
	}*/
	
	if(strlen($configuracion->numeroCuenta)>0)
	{
		#$XMLFacturaComprobante.=' NumCtaPago="'.$configuracion->numeroCuenta.'"';
	}
	
	#TipoCambio="'.Sprintf("% 01.2f",$divisa->tipoCambio).'"
	$XMLFacturaComprobante.=' Moneda="MXN" Total="'.Sprintf("% 01.2f",$_POST['txtTotal']).'" MetodoPago="'.$_POST['txtMetodoPago'].'" TipoDeComprobante="I" LugarExpedicion="'.sustituir($configuracion->codigoPostal).'">';
	
	$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor Rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" Nombre="'.sustituir($configuracion->nombre).'" RegimenFiscal="'.sustituir($configuracion->claveRegimen).'"/>';
	$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor Rfc="'.espaciosFactura(sustituir($cliente->rfc)).'" Nombre="'.sustituir($cliente->razonSocial).'" UsoCFDI="'.$_POST['selectUsoCfdi'].'"/>';

	$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';
	
	$i		= 0;
	$iva16	= 0;
	$iva0	= 0;
	
	$i=0;
	$numeroProductos	=	$_POST['txtNumeroProductos'];

	for($i=1;$i<=$numeroProductos;$i++)
	{
		if(isset($_POST['txtIdConcepto'.$i]))
		{
			$iva	= round($_POST['txtImporteFactura'.$i]*($ivaPorcentaje),2);
			
			$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto ClaveProdServ="'.$_POST['txtClaveProductoFactura'.$i].'" Cantidad="'.Sprintf("% 01.2f",$_POST['txtCantidadFactura'.$i]).'" ClaveUnidad="'.sustituir($_POST['txtClaveUnidad'.$i]).'" Unidad="'.sustituir($_POST['txtUnidadFactura'.$i]).'" ValorUnitario="'.Sprintf("% 01.2f",$_POST['txtPrecioFactura'.$i]).'" Descripcion="'.sustituir($_POST['txtConceptoFactura'.$i]).'" Importe="'.Sprintf("% 01.2f",$_POST['txtImporteFactura'.$i]).'">';
			$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Impuestos>';
			$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslados>';
			$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslado Base="'.Sprintf("% 01.2f",$_POST['txtImporteFactura'.$i]).'" Impuesto="002" TipoFactor="Tasa" TasaOCuota="'.($iva>0?'0.160000':'0.000000').'" Importe="'.Sprintf("% 01.2f",$_POST['txtIvaProducto'.$i]).'"/>';
			$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Traslados>';
			$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Impuestos>';
			$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Concepto>';
		}
	}

	$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';
	
	//IMPUESTOS
	$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos TotalImpuestosTrasladados="'.Sprintf("% 01.2f",$_POST['txtIva']).'">';
	$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
	$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Impuesto="002" TipoFactor="Tasa" TasaOCuota="'.($ivaPorcentaje>0?'0.160000':'0.000000').'" Importe="'.Sprintf("% 01.2f",$_POST['txtIva']).'"/>';
	$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
	$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
	
	
	/*$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="'.Sprintf("% 01.2f",$iva).'">';
	$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
	#$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IEPS" tasa="0.00" importe="0.00"/>';
	$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="'.Sprintf("% 01.2f",$cotizacion->iva*100).'" importe="'.Sprintf("% 01.2f",$iva).'"/>';
	$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
	$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';*/
	
	$retencion="";
	
	
	$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
	$XMLFacturaComprobante.
	$XMLFacturaEmisor.
	$XMLFacturaReceptor.
	$XMLFacturaConceptos.
	$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$retencion.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

	return $XMLFinal;
}