<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function procesarMetodoPago($metodo)
{
	$metodo		= obtenerMinusculas($metodo);

	$metodoPago	= str_replace('transferencia','',$metodo);
	if($metodoPago!=$metodo) return 'transferencia';

	$metodoPago	= str_replace('cheque','',$metodo);
	if($metodoPago!=$metodo) return 'cheque';
	
	if($metodo=='efectivo') return '1';
	if($metodo=='no identificado') return '18';
	
	
	return $metodo;
}

function procesarMoneda($moneda)
{
	$moneda		= obtenerMinusculas($moneda);
	$moneda		= explode('-',$moneda);
	
	if(isset($moneda[0]))
	{
		switch($moneda[0])
		{
			case "mxn":
			return 'MXN';
			
			case "pesos":
			return 'MXN';
			
			case "usd":
			return 'USD';
			
			case "dolares":
			return 'USD';
			
			case "eur":
			return 'EUR';
			
			case "euros":
			return 'EUR';
			
			default:
			return 'MXN';
		}
	}
	else
	{
		return 'MXN';
	}
}

function procesarXmlCfdi($xml)
{
	#$xml = simplexml_load_file('cfdi.xml'); 
	
	$xml 	= simplexml_load_file($xml); 
	$ns 	= $xml->getNamespaces(true);
	$xml->registerXPathNamespace('c', $ns['cfdi']);
	$xml->registerXPathNamespace('t', $ns['tfd']);
	
	$cfdi		= array();
	$descuento	= 0;

	$cfdi[0]	='1';
	
	foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante)
	{ 
		$cfdi[1]	= $cfdiComprobante['version'];
		$cfdi[2]	= $cfdiComprobante['fecha'];
		$cfdi[3]	= $cfdiComprobante['sello'];
		$cfdi[4]	= $cfdiComprobante['total'];
		$cfdi[5]	= $cfdiComprobante['subTotal'];
		$cfdi[6]	= $cfdiComprobante['certificado'];
		$cfdi[7]	= $cfdiComprobante['formaDePago'];
		$cfdi[8]	= $cfdiComprobante['noCertificado'];
		$cfdi[9]	= $cfdiComprobante['tipoDeComprobante'];
		  
 	 	$cfdi[10]	= $cfdiComprobante['metodoDePago'];
		
		$cfdi[11]	= $cfdiComprobante['serie'];
		$cfdi[12]	= $cfdiComprobante['folio'];
		$cfdi[13]	= strlen($cfdiComprobante['tipoCambio'])>0?$cfdiComprobante['tipoCambio']:'1.00';
		$cfdi[14]	= $cfdiComprobante['Moneda'];
		$descuento  = $cfdiComprobante['descuento'];
		
		$cfdi[46]	= $cfdiComprobante['condicionesDePago'];
		$cfdi[47]	= $cfdiComprobante['LugarExpedicion'];
	} 
	
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor)
	{ 
	   $cfdi[15]	= $Emisor['rfc'];
	   $cfdi[16]	= $Emisor['nombre'];
	} 
	
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal)
	{ 
	   $cfdi[17]	= $DomicilioFiscal['pais'];
	   $cfdi[18]	= $DomicilioFiscal['calle'];
	   $cfdi[19]	= $DomicilioFiscal['estado'];
	   $cfdi[20]	= $DomicilioFiscal['colonia'];
	   $cfdi[48]	= $DomicilioFiscal['localidad'];
	   $cfdi[21]	= $DomicilioFiscal['municipio'];
	   $cfdi[22]	= $DomicilioFiscal['noExterior'];
	   $cfdi[23]	= $DomicilioFiscal['codigoPostal'];
	} 
	
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:RegimenFiscal') as $Regimen)
	{
		 $cfdi[49]	= $Regimen['Regimen'];
	}
	
	
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor)
	{ 
	    $cfdi[24]	= $Receptor['rfc'];
		$cfdi[25]	= $Receptor['nombre'];
	} 
	
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio)
	{ 
	   $cfdi[26]	= $ReceptorDomicilio['pais'];
	   $cfdi[27]	= $ReceptorDomicilio['calle'];
	   $cfdi[28]	= $ReceptorDomicilio['estado'];
	   $cfdi[29]	= $ReceptorDomicilio['colonia'];
	   $cfdi[30]	= $ReceptorDomicilio['municipio'];
	   $cfdi[31]	= $ReceptorDomicilio['noExterior'];
	   $cfdi[32]	= $ReceptorDomicilio['localidad'];
	   $cfdi[33]	= $ReceptorDomicilio['codigoPostal'];
	} 
	
	$i=0;
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto)
	{ 
	   $data=array
	   (
	  		$Concepto['cantidad'],
	   		$Concepto['unidad'],
			$Concepto['descripcion'],
			$Concepto['valorUnitario'],
			$Concepto['importe'],
			$Concepto['noIdentificacion'],
	   );
	   
	   $cfdi[34][$i]	= $data;
	   
	   $i++;
	} 
	
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado)
	{ 
	   $cfdi[35]	= $Traslado['tasa'];
	   $cfdi[36]	= $Traslado['importe'];
	   $cfdi[37]	= $Traslado['impuesto'];
	} 
	 
	//ESTA ULTIMA PARTE ES LA QUE GENERABA EL ERROR
	foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) 
	{
	    $cfdi[38]	= $tfd['selloCFD'];
		$cfdi[39]	= $tfd['FechaTimbrado'];
		$cfdi[40]	= $tfd['UUID'];
		$cfdi[41]	= $tfd['noCertificadoSAT'];
		$cfdi[42]	= $tfd['version'];
		$cfdi[43]	= $tfd['selloSAT'];
		
		$cfdi[44]	= $descuento;
	} 
	
	$i=45;
	
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $Traslado)
	{ 
	   $cfdi[$i]	= $Traslado['importe'];   $i++;
	   $cfdi[$i]	= $Traslado['impuesto'];   $i++;
	} 
	
	return $cfdi;
}
?>
