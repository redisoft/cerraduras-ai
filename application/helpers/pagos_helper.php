<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
	
function crearXmlPago($configuracion,$factura,$sello,$certificado,$fecha,$folio,$divisa,$cliente)
{
	$fecha			= str_replace(" ","T",$fecha);
	$sello			= str_replace(" ","",$sello);
	$certificado	= str_replace(" ","",$certificado);
	$fechaPago		= str_replace(" ","T",$_POST['txtFechaPago'].':00');
	
	$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:pago20="http://www.sat.gob.mx/Pagos20" xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd http://www.sat.gob.mx/Pagos20 http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos20.xsd"';
	$XMLFacturaComprobante.=' Version="4.0"'.(strlen($configuracion->serie)>0?' Serie="'.$configuracion->serie.'"':'').' Folio="'.$folio.'" Fecha="'.$fecha.'" Exportacion="01" NoCertificado="'.$configuracion->numeroCertificado.'"';
	#$XMLFacturaComprobante.=strlen($_POST['txtCondicionesPago'])>0?' CondicionesDePago="'.$_POST['txtCondicionesPago'].'"':'';
	$XMLFacturaComprobante.=' SubTotal="0"';
	
	
	
	$XMLFacturaComprobante.=' Moneda="XXX" Total="0" TipoDeComprobante="P"';
	#$XMLFacturaComprobante.=$_POST['selectDocumento']!='T'?' MetodoPago="'.trim($_POST['selectMetodoPago']).'"':'';
	$XMLFacturaComprobante.=' LugarExpedicion="'.$configuracion->codigoPostal.'"';
	
	
	$XMLFacturaComprobante.=' Sello="'.$sello.'"';
	$XMLFacturaComprobante.=' Certificado="'.$certificado.'">';

	$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor Rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" Nombre="'.sustituir($configuracion->nombre).'" RegimenFiscal="'.sustituir($configuracion->claveRegimen).'"/>';
	$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor Rfc="'.espaciosFactura(sustituir($factura->rfc)).'" Nombre="'.sustituir($factura->empresa).'" UsoCFDI="CP01" RegimenFiscalReceptor="'.sustituir($cliente->claveRegimen).'" DomicilioFiscalReceptor="'.sustituir($cliente->codigoPostal).'"/>';

	$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';

	$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto ClaveProdServ="84111506" Cantidad="1" ClaveUnidad="ACT" Descripcion="Pago" ValorUnitario="0" Importe="0" ObjetoImp="01"/>';

	$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';
	
	$subTotal		= $_POST['txtImportePagar']-$_POST['txtImporteIva16'];
	
	$XMLFacturaPagos='<pago20:Pagos Version="2.0">';
	$XMLFacturaPagos.=chr(13).chr(10).'<pago20:Totales MontoTotalPagos="'.Sprintf("% 01.2f",$_POST['txtImportePagar']).'"'.($_POST['txtImporteIva16']>0?' TotalTrasladosBaseIVA16="'.Sprintf("% 01.2f",$subTotal).'" TotalTrasladosImpuestoIVA16="'.Sprintf("% 01.2f",$_POST['txtImporteIva16']).'"':'').'/>';
	$XMLFacturaPagos.=chr(13).chr(10).'<pago20:Pago FechaPago="'.$fechaPago.'" MonedaP="'.$divisa->clave.'" FormaDePagoP="'.$_POST['selectFormaPago'].'" TipoCambioP="1" Monto="'.Sprintf("% 01.2f",$_POST['txtImportePagar']).'"';
	
	$XMLFacturaPagos.=strlen($_POST['txtNumeroOperacion'])>0?' NumOperacion="'.$_POST['txtNumeroOperacion'].'"':'';
	
	$XMLFacturaPagos.=strlen($_POST['txtRfcOrdenante'])>0?' RfcEmisorCtaOrd="'.$_POST['txtRfcOrdenante'].'"':'';
	$XMLFacturaPagos.=strlen($_POST['txtCuentaOrdenante'])>0?' CtaOrdenante="'.$_POST['txtCuentaOrdenante'].'"':'';
	
	$XMLFacturaPagos.=strlen($_POST['txtRfcBeneficiario'])>0?' RfcEmisorCtaBen="'.$_POST['txtRfcBeneficiario'].'"':'';
	$XMLFacturaPagos.=strlen($_POST['txtCuentaBeneficiario'])>0?' CtaBeneficiario="'.$_POST['txtCuentaBeneficiario'].'"':'';
	
	
	$XMLFacturaPagos.='>';
	
	$insoluto	= $_POST['txtSaldoFactura']+-$_POST['txtImportePagar'];
	
	$XMLFacturaPagos.=chr(13).chr(10).'<pago20:DoctoRelacionado EquivalenciaDR="1" IdDocumento="'.$factura->UUID.'"'.(strlen($factura->serie)>0?' Serie="'.$factura->serie.'"':'').' Folio="'.$factura->folio.'"';
	$XMLFacturaPagos.=' MonedaDR="'.$factura->claveDivisa.'"' ;

	$XMLFacturaPagos.=' NumParcialidad="'.$_POST['txtNumeroParcialidad'].'"';
	
	$XMLFacturaPagos.=' ImpSaldoAnt="'.Sprintf("% 01.2f",$_POST['txtSaldoFactura']).'" ImpSaldoInsoluto="'.Sprintf("% 01.2f",($_POST['txtSaldoFactura']+-$_POST['txtImportePagar'])).'" ImpPagado="'.Sprintf("% 01.2f",$_POST['txtImportePagar']).'" ObjetoImpDR="'.($_POST['txtImporteIva16']>0?'02':'01').'">';
	
	if($_POST['txtImporteIva16']>0)
	{
		$XMLFacturaPagos.=chr(13).chr(10).'<pago20:ImpuestosDR>';

		$XMLFacturaPagos.=chr(13).chr(10).'   <pago20:TrasladosDR>';
		$XMLFacturaPagos.=chr(13).chr(10).'      <pago20:TrasladoDR BaseDR="'.Sprintf("% 01.2f",$subTotal).'" ImpuestoDR="002" TipoFactorDR="Tasa" TasaOCuotaDR="0.160000" ImporteDR="'.Sprintf("% 01.2f",$_POST['txtImporteIva16']).'"/>';
		$XMLFacturaPagos.=chr(13).chr(10).'   </pago20:TrasladosDR>';

		$XMLFacturaPagos.=chr(13).chr(10).'</pago20:ImpuestosDR>';

		$XMLFacturaPagos.=chr(13).chr(10).'</pago20:DoctoRelacionado>';

		$XMLFacturaPagos.=chr(13).chr(10).'<pago20:ImpuestosP>';

		$XMLFacturaPagos.=chr(13).chr(10).'   <pago20:TrasladosP>';
		$XMLFacturaPagos.=chr(13).chr(10).'      <pago20:TrasladoP BaseP="'.Sprintf("% 01.2f",$subTotal).'" ImpuestoP="002" TipoFactorP="Tasa" TasaOCuotaP="0.160000" ImporteP="'.Sprintf("% 01.2f",$_POST['txtImporteIva16']).'"/>';
		$XMLFacturaPagos.=chr(13).chr(10).'   </pago20:TrasladosP>';

		$XMLFacturaPagos.=chr(13).chr(10).'</pago20:ImpuestosP>';
	}
	else
	{
		$XMLFacturaPagos.=chr(13).chr(10).'</pago20:DoctoRelacionado>';
	}
	
	
	$XMLFacturaPagos.=chr(13).chr(10).'</pago20:Pago>';
	$XMLFacturaPagos.=chr(13).chr(10).'</pago20:Pagos>';
	
	
	$XMLFacturaImpuestos='';


	$retencion="";
	$donataria="";

	$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
	$XMLFacturaComprobante.
	$XMLFacturaEmisor.
	$XMLFacturaReceptor.
	$XMLFacturaConceptos.
	$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$XMLFacturaPagos.$donataria.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

	return $XMLFinal;
}

?>
