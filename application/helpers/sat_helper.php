<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

	#--------------------------------------------------------------------------------------------------#
	#	                                   XML para la Nota de Crédito							       #
	#--------------------------------------------------------------------------------------------------#
	function xmlNotaCredito($configuracion,$cliente,$productos,$sello,$certificado,$fecha,$folio,$divisa)
	{
		$fecha			=str_replace(" ","T",$fecha);
		$sello			=str_replace(" ","",$sello);
		$certificado	=str_replace(" ","",$certificado);

		#$iva			=Sprintf("% 01.2f",$iva);
		
		$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:implocal="http://www.sat.gob.mx/implocal" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
		$XMLFacturaComprobante.=' sello="'.$sello.'"';
		$XMLFacturaComprobante.=' certificado="'.$certificado.'"';
		
		$XMLFacturaComprobante.=' xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd"';
		
		$XMLFacturaComprobante.=' version="3.2" folio="'.$folio.'" serie="'.sustituir($configuracion->serie).'" fecha="'.$fecha.'" formaDePago="'.sustituir($_POST['txtFormaPago']).'" noCertificado="'.$configuracion->numeroCertificado.'" condicionesDePago="'.sustituir($_POST['txtCondiciones']).'" subTotal="'.Sprintf("% 01.2f",$_POST['txtSubTotal']).'"';
		
		if($_POST['txtDescuentoNota']>0)
		{
			$XMLFacturaComprobante.=' descuento="'.Sprintf("% 01.2f",$_POST['txtDescuentoNota']).'"';
		}
		
		if(strlen($configuracion->numeroCuenta)>0)
		{
			#$XMLFacturaComprobante.=' NumCtaPago="'.$configuracion->numeroCuenta.'"';
		}
		
		$XMLFacturaComprobante.=' TipoCambio="'.Sprintf("% 01.2f",$divisa->tipoCambio).'" Moneda="'.$divisa->clave.'" total="'.Sprintf("% 01.2f",$_POST['txtTotalNota']).'" metodoDePago="'.sustituir($_POST['txtMetodoPago']).'" tipoDeComprobante="egreso" LugarExpedicion="'.$configuracion->estado.', '.$configuracion->pais.'">';
		
		$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" nombre="'.sustituir($configuracion->nombre).'">';
		
		$XMLFacturaEmisor.=chr(13).chr(10).'    <cfdi:DomicilioFiscal';
		$XMLFacturaEmisor.=strlen($configuracion->calle)>1?' calle="'.sustituir($configuracion->calle).'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->numeroExterior)>0?' noExterior="'.$configuracion->numeroExterior.'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->colonia)>1?' colonia="'.sustituir($configuracion->colonia).'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->localidad)>1?' localidad="'.sustituir($configuracion->localidad).'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->municipio)>1?' municipio="'.$configuracion->municipio.'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->estado)>1?' estado="'.$configuracion->estado.'"':'';
		$XMLFacturaEmisor.=' pais="'.$configuracion->pais.'" codigoPostal="'.$configuracion->codigoPostal.'"/>';
		
		
		$XMLFacturaEmisor.=chr(13).chr(10).'    <cfdi:RegimenFiscal Regimen="'.sustituir($configuracion->regimenFiscal).'"/>';
		$XMLFacturaEmisor.=chr(13).chr(10).'  </cfdi:Emisor>';

		$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor rfc="'.espaciosFactura(sustituir($cliente->rfc)).'" nombre="'.sustituir($cliente->razonSocial).'" >';
		
		$XMLFacturaReceptor.=chr(13).chr(10).'    <cfdi:Domicilio';
		
		if(strlen($cliente->calle)>2)
		{
			$XMLFacturaReceptor.=' calle="'.sustituir($cliente->calle).'"';
		}
		
		if(strlen($cliente->numero)>0)
		{
			$XMLFacturaReceptor.=' noExterior="'.sustituir($cliente->numero).'"';
		}
		
		if(strlen($cliente->colonia)>2)
		{
			$XMLFacturaReceptor.=' colonia="'.sustituir($cliente->colonia).'"';
		}	
		
		if(strlen($cliente->localidad)>2)
		{
			$XMLFacturaReceptor.=' localidad="'.sustituir($cliente->localidad).'"';
		}
		
		if(strlen($cliente->municipio)>2)
		{
			$XMLFacturaReceptor.=' municipio="'.sustituir($cliente->municipio).'"';
		}
		
		if(strlen($cliente->estado)>2)
		{
			$XMLFacturaReceptor.=' estado="'.sustituir($cliente->estado).'"';
		}		
		
		$XMLFacturaReceptor.=' pais="'.sustituir($cliente->pais).'"';
		
		if(strlen($cliente->codigoPostal)==5)
		{
			$XMLFacturaReceptor.=' codigoPostal="'.sustituir($cliente->codigoPostal).'"';
		}
		
		$XMLFacturaReceptor.='/>';
		$XMLFacturaReceptor.=chr(13).chr(10).'  </cfdi:Receptor>';
		
		$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';

		foreach($productos as $row)
		{
			
			$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto cantidad="'.Sprintf("% 01.2f",$row->cantidad).'" unidad="'.$row->unidad.'"';
			$XMLFacturaConceptos.=strlen($row->codigoInterno)>0?' noIdentificacion="'.$row->codigoInterno.'"':'';
			$XMLFacturaConceptos.=' valorUnitario="'.Sprintf("% 01.2f",$row->importe/$row->cantidad).'" descripcion="'.sustituirSaltosFactura($row->nombre).'" importe="'.Sprintf("% 01.2f",$row->importe).'" />';
		}

		$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';
		
		$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="'.Sprintf("% 01.2f",$_POST['txtIva']).'">';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="'.Sprintf("% 01.2f",$_POST['txtIvaPorcentaje']).'" importe="'.Sprintf("% 01.2f",$_POST['txtIva']).'"/>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
		
		$retencion='';

		$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
		$XMLFacturaComprobante.
		$XMLFacturaEmisor.
		$XMLFacturaReceptor.
		$XMLFacturaConceptos.
		$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$retencion.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

		return $XMLFinal;
	}
	
	#--------------------------------------------------------------------------------------------------#
	#	                                    Crear el archivo XML								       #
	#--------------------------------------------------------------------------------------------------#
	function RegresaXMLFormato($configuracion,$cliente,$productos,$sello,$certificado,$fecha,$folio,$cotizacion,$retenciones,$divisa,$impuestos)
	{
		$fecha			= str_replace(" ","T",$fecha);
		$sello			= str_replace(" ","",$sello);
		$certificado	= str_replace(" ","",$certificado);

		$subTotal		= $cotizacion->subTotal;
		$total			= $cotizacion->total;
		$iva			= $cotizacion->iva;
		$descuento		= $cotizacion->descuento;
		
		if($retenciones['importe']>0)
		{
			$total	=$total-$retenciones['importe'];
		}
		
		$porcentajeParcial	=0;
		
		if($_POST['parcial']==1)
		{
			$subTotal			= $_POST['subTotal'];
			$descuento			= $_POST['descuento'];
			$iva				= $_POST['iva'];
			$total				= $_POST['total'];
			
			$porcentajeParcial	= $_POST['porcentaje'];
		}
		
		//SE AGREGARON LAS RETENCIONES
		
		$subTotal			= $_POST['subTotal'];
		$descuento			= $_POST['descuento'];
		$iva				= $_POST['iva'];
		$total				= $_POST['total'];
		
		$tasaIeps			= isset($_POST['tasaIeps'])?$_POST['tasaIeps']:0;
		$totalIeps			= isset($_POST['totalIeps'])?$_POST['totalIeps']:0;
		
		$retencionIva		= isset($_POST['retencionIva'])?$_POST['retencionIva']:0;
		
		#$totalIva			= isset($_POST['totalIeps'])?$_POST['totalIeps']:0;
		
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		
		#$iva			=Sprintf("% 01.2f",$iva);
		
		#$XMLFacturaComprobante='<cfdi:Comprobante xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:cfdi="http://www.sat.gob.mx/cfd/3"';
		$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:implocal="http://www.sat.gob.mx/implocal" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
		$XMLFacturaComprobante.=' sello="'.$sello.'"';
		$XMLFacturaComprobante.=' certificado="'.$certificado.'"';
		
		$XMLFacturaComprobante.=' xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd"';
		
		$XMLFacturaComprobante.=' version="3.2" folio="'.$folio.'"'.(strlen($configuracion->serie)>0?' serie="'.$configuracion->serie.'"':'').' fecha="'.$fecha.'" formaDePago="'.$_POST['formaPago'].'" noCertificado="'.$configuracion->numeroCertificado.'" condicionesDePago="'.$_POST['condiciones'].'" subTotal="'.Sprintf("% 01.2f",$subTotal).'"';
		
		if($descuento>0)
		{
			#$XMLFacturaComprobante.=' descuento="'.Sprintf("% 01.2f",$descuento).'"';
		}
		
		if(strlen($configuracion->numeroCuenta)>0)
		{
			#$XMLFacturaComprobante.=' NumCtaPago="'.$configuracion->numeroCuenta.'"';
		}
		
		$XMLFacturaComprobante.=' TipoCambio="'.Sprintf("% 01.2f",$divisa->tipoCambio).'" Moneda="'.$divisa->clave.'" total="'.Sprintf("% 01.2f",$total).'" metodoDePago="'.$_POST['metodoPago'].'" tipoDeComprobante="ingreso" LugarExpedicion="'.$configuracion->estado.', '.$configuracion->pais.'">';
		
		$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" nombre="'.sustituir($configuracion->nombre).'">';
		$XMLFacturaEmisor.=chr(13).chr(10).'    <cfdi:DomicilioFiscal calle="'.sustituir($configuracion->calle).'" noExterior="'.$configuracion->numeroExterior.'" colonia="'.sustituir($configuracion->colonia).'"'.(strlen($configuracion->localidad)>2?' localidad="'.sustituir($configuracion->localidad).'"':'').(strlen($configuracion->municipio)>2?' municipio="'.$configuracion->municipio.'"':'').' estado="'.$configuracion->estado.'" pais="'.$configuracion->pais.'" codigoPostal="'.$configuracion->codigoPostal.'"/>';
		$XMLFacturaEmisor.=chr(13).chr(10).'    <cfdi:RegimenFiscal Regimen="'.sustituir($configuracion->regimenFiscal).'"/>';
		$XMLFacturaEmisor.=chr(13).chr(10).'  </cfdi:Emisor>';
		
		/*$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor rfc="'.sustituir($cliente->rfc).'" nombre="'.sustituir($cliente->empresa).'" >';
		$XMLFacturaReceptor.=chr(13).chr(10).'    <cfdi:Domicilio calle="'.sustituir($cliente->calle).'" noExterior="'.sustituir($cliente->numero).'" colonia="'.sustituir($cliente->colonia).'" localidad="'.sustituir($cliente->localidad).'" municipio="'.sustituir($cliente->municipio).'" estado="'.sustituir($cliente->estado).'" pais="'.sustituir($cliente->pais).'" codigoPostal="'.sustituir($cliente->codigoPostal).'"/>';
		$XMLFacturaReceptor.=chr(13).chr(10).'  </cfdi:Receptor>';*/
		
		$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor rfc="'.espaciosFactura(sustituir($cliente->rfc)).'" nombre="'.sustituir($cliente->razonSocial).'" >';
		
		$XMLFacturaReceptor.=chr(13).chr(10).'    <cfdi:Domicilio';
		
		if(strlen($cliente->calle)>2)
		{
			$XMLFacturaReceptor.=' calle="'.sustituir($cliente->calle).'"';
		}
		
		if(strlen($cliente->numero)>0)
		{
			$XMLFacturaReceptor.=' noExterior="'.sustituir($cliente->numero).'"';
		}
		
		if(strlen($cliente->colonia)>2)
		{
			$XMLFacturaReceptor.=' colonia="'.sustituir($cliente->colonia).'"';
		}	
		
		if(strlen($cliente->localidad)>2)
		{
			$XMLFacturaReceptor.=' localidad="'.sustituir($cliente->localidad).'"';
		}
		
		if(strlen($cliente->municipio)>2)
		{
			$XMLFacturaReceptor.=' municipio="'.sustituir($cliente->municipio).'"';
		}
		
		if(strlen($cliente->estado)>2)
		{
			$XMLFacturaReceptor.=' estado="'.sustituir($cliente->estado).'"';
		}		
		
		$XMLFacturaReceptor.=' pais="'.sustituir($cliente->pais).'"';
		
		if(strlen($cliente->codigoPostal)==5)
		{
			$XMLFacturaReceptor.=' codigoPostal="'.sustituir($cliente->codigoPostal).'"';
		}
		
		$XMLFacturaReceptor.='/>';
		$XMLFacturaReceptor.=chr(13).chr(10).'  </cfdi:Receptor>';
		
		$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';

		if($_POST['parcial']==1)
		{
			$i=1;
			$productosParcial	= $_POST['productos'];
			$porcentajeParcial	= $porcentajeParcial/100;
			$cantidadParcial	= $_POST['cantidad'];
			
			$descuentos			= $_POST['descuentos'];
			
			foreach($productos as $row)
			{
				$producto			= $productosParcial[$i];
				$cantidad			= $cantidadParcial[$i];
				$importe			= $cantidad*$row->precio - $descuentos[$i];
				
				#$descuento			= $descuentos[$i]>0?$descuentos[$i]/$cantidad:0;
				$precio				= $importe/$cantidad;
				
				if($cantidad>0)
				{
					$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto cantidad="'.Sprintf("% 01.2f",$cantidad).'" unidad="'.$row->unidad.'"';
					$XMLFacturaConceptos.=strlen($row->codigoInterno)>0?' noIdentificacion="'.$row->codigoInterno.'"':'';
					$XMLFacturaConceptos.=' valorUnitario="'.Sprintf("% 01.2f",$precio).'" descripcion="'.sustituirSaltosFactura(sustituir($producto)).'" importe="'.Sprintf("% 01.2f",$importe).'" />';
				}
				
				$i++;
			}
		}
		else
		{
			$i					=1;
			$productosParcial	=$_POST['productos'];
			
			foreach($productos as $row)
			{
				#$producto	=strlen($row->nombre)>0?$row->nombre:$row->producto;
				$producto			= $productosParcial[$i];
				$descuento			= $row->descuento>0?$row->descuento/$row->cantidad:0;
				$precio				= $row->precio-$descuento;
				
				$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto cantidad="'.Sprintf("% 01.2f",$row->cantidad).'" unidad="'.$row->unidad.'"';
				$XMLFacturaConceptos.=strlen($row->codigoInterno)>0?' noIdentificacion="'.$row->codigoInterno.'"':'';
				$XMLFacturaConceptos.=' valorUnitario="'.Sprintf("% 01.2f",$precio).'" descripcion="'.sustituirSaltosFactura($producto).'" importe="'.Sprintf("% 01.2f",$row->importe).'" />';
				$i++;
			}
		}

		$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';
		
		$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos'.($retencionIva>0?' totalImpuestosRetenidos="'.$retencionIva.'" ':' ').'totalImpuestosTrasladados="'.Sprintf("% 01.2f",$iva+$totalIeps).'">';
		
		if($retencionIva>0)
		{
			$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Retenciones>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Retencion impuesto="IVA" importe="'.Sprintf("% 01.2f",$retencionIva).'"/>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Retenciones>';
		}
		
		if(count($impuestos)>1)
		{
			$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="'.Sprintf("% 01.2f",$impuestos[0]['importe']).'">';
			$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
			
			for($i=1;$i<count($impuestos);$i++)
			{
				$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="'.obtenerMayusculas($impuestos[$i]['tipo']).'" tasa="'.Sprintf("% 01.2f",$impuestos[$i]['tasa']).'" importe="'.Sprintf("% 01.2f",$impuestos[$i]['importe']).'"/>';
			}
			
			#$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IEPS" tasa="0.00" importe="0.00"/>';
			#$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="'.Sprintf("% 01.2f",$cotizacion->ivaPorcentaje).'" importe="'.Sprintf("% 01.2f",$cotizacion->iva).'"/>';
			
			$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
		}
		else
		{
			$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="0">';
			$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="0" importe="0"/>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
		}
		
		/*$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
		$XMLFacturaImpuestos.= $totalIeps>0?chr(13).chr(10).'      <cfdi:Traslado impuesto="IEPS" tasa="'.Sprintf("% 01.2f",$tasaIeps).'" importe="'.Sprintf("% 01.2f",$totalIeps).'"/>':'';
		$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="'.Sprintf("% 01.2f",$cotizacion->ivaPorcentaje).'" importe="'.Sprintf("% 01.2f",$iva).'"/>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';*/
		
		$retencion="";
		
		if($retenciones['importe']>0)
		{
			$retencion.='  <implocal:ImpuestosLocales xmlns:implocal="http://www.sat.gob.mx/implocal" version="1.0" TotaldeRetenciones="'.Sprintf("% 01.2f",$retenciones['importe']).'" TotaldeTraslados="0.00">';
			$retencion.=chr(13).chr(10).'    <implocal:RetencionesLocales ImpLocRetenido="'.$retenciones['nombre'].'" TasadeRetencion="'.Sprintf("% 01.2f",$retenciones['tasa']).'" Importe="'.Sprintf("% 01.2f",$retenciones['importe']).'"/>';
			$retencion.=chr(13).chr(10).'  </implocal:ImpuestosLocales>';
		}
		
		$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
		$XMLFacturaComprobante.
		$XMLFacturaEmisor.
		$XMLFacturaReceptor.
		$XMLFacturaConceptos.
		$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$retencion.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

		return $XMLFinal;
	}
	
	function xmFacturaVenta($configuracion,$cliente,$productos,$sello,$certificado,$fecha,$folio,$cotizacion,$retenciones,$divisa,$impuestos)
	{
		$fecha				= str_replace(" ","T",$fecha);
		$sello				= str_replace(" ","",$sello);
		$certificado		= str_replace(" ","",$certificado);

		$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:implocal="http://www.sat.gob.mx/implocal" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
		$XMLFacturaComprobante.=' sello="'.$sello.'"';
		$XMLFacturaComprobante.=' certificado="'.$certificado.'"';
		
		$XMLFacturaComprobante.=' xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd"';
		
		$XMLFacturaComprobante.=' version="3.2" folio="'.$folio.'" serie="'.sustituir($configuracion->serie).'" fecha="'.$fecha.'" formaDePago="'.sustituir($_POST['txtFormaPago']).'" noCertificado="'.$configuracion->numeroCertificado.'" condicionesDePago="'.sustituir($_POST['txtCondicionesPago']).'" subTotal="'.Sprintf("% 01.2f",$cotizacion->subTotal-$cotizacion->descuento).'"';
		
		if($cotizacion->descuento>0)
		{
			#$XMLFacturaComprobante.=' descuento="'.Sprintf("% 01.2f",$cotizacion->descuento).'"';
		}
		
		if(strlen($configuracion->numeroCuenta)>0)
		{
			#$XMLFacturaComprobante.=' NumCtaPago="'.$configuracion->numeroCuenta.'"';
		}
		
		$XMLFacturaComprobante.=' TipoCambio="'.Sprintf("% 01.2f",$divisa->tipoCambio).'" Moneda="'.sustituir($divisa->clave).'" total="'.Sprintf("% 01.2f",$cotizacion->total).'" metodoDePago="'.sustituir($_POST['selectMetodoPago']).'" tipoDeComprobante="ingreso" LugarExpedicion="'.sustituir($configuracion->estado).', '.sustituir($configuracion->pais).'">';
		
		$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" nombre="'.sustituir($configuracion->nombre).'">';
		$XMLFacturaEmisor.=chr(13).chr(10).'    <cfdi:DomicilioFiscal calle="'.sustituir($configuracion->calle).'" noExterior="'.$configuracion->numeroExterior.'" colonia="'.sustituir($configuracion->colonia).'" localidad="'.sustituir($configuracion->localidad).'" municipio="'.$configuracion->municipio.'" estado="'.sustituir($configuracion->estado).'" pais="'.sustituir($configuracion->pais).'" codigoPostal="'.sustituir($configuracion->codigoPostal).'"/>';
		$XMLFacturaEmisor.=chr(13).chr(10).'    <cfdi:RegimenFiscal Regimen="'.sustituir($configuracion->regimenFiscal).'"/>';
		$XMLFacturaEmisor.=chr(13).chr(10).'  </cfdi:Emisor>';
		
		$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor rfc="'.espaciosFactura(sustituir($cliente->rfc)).'" nombre="'.sustituir($cliente->razonSocial).'" >';
		
		$XMLFacturaReceptor.=chr(13).chr(10).'    <cfdi:Domicilio';
		
		if(strlen($cliente->calle)>2)
		{
			$XMLFacturaReceptor.=' calle="'.sustituir($cliente->calle).'"';
		}
		
		if(strlen($cliente->numero)>0)
		{
			$XMLFacturaReceptor.=' noExterior="'.sustituir($cliente->numero).'"';
		}
		
		if(strlen($cliente->colonia)>2)
		{
			$XMLFacturaReceptor.=' colonia="'.sustituir($cliente->colonia).'"';
		}	
		
		if(strlen($cliente->localidad)>2)
		{
			$XMLFacturaReceptor.=' localidad="'.sustituir($cliente->localidad).'"';
		}
		
		if(strlen($cliente->municipio)>2)
		{
			$XMLFacturaReceptor.=' municipio="'.sustituir($cliente->municipio).'"';
		}
		
		if(strlen($cliente->estado)>2)
		{
			$XMLFacturaReceptor.=' estado="'.sustituir($cliente->estado).'"';
		}		
		
		$XMLFacturaReceptor.=' pais="'.sustituir($cliente->pais).'"';
		
		if(strlen($cliente->codigoPostal)==5)
		{
			$XMLFacturaReceptor.=' codigoPostal="'.sustituir($cliente->codigoPostal).'"';
		}
		
		$XMLFacturaReceptor.='/>';
		$XMLFacturaReceptor.=chr(13).chr(10).'  </cfdi:Receptor>';
		$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';
		
		$i=0;

		foreach($productos as $row)
		{
			$producto			= strlen($row->nombre)>0?$row->nombre:$row->producto;
			$descuento			= $row->descuento>0?$row->descuento/$row->cantidad:0;
			$precio				= $row->precio-$descuento;
			
			$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto cantidad="'.Sprintf("% 01.2f",$row->cantidad).'" unidad="'.$row->unidad.'"';
			$XMLFacturaConceptos.=strlen($row->codigoInterno)>0?' noIdentificacion="'.$row->codigoInterno.'"':'';
			$XMLFacturaConceptos.=' valorUnitario="'.Sprintf("% 01.2f",$precio).'" descripcion="'.sustituir($producto).'" importe="'.Sprintf("% 01.2f",$row->importe).'" />';
		}

		$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';
		
		if(count($impuestos)>1)
		{
			$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="'.Sprintf("% 01.2f",$impuestos[0]['importe']).'">';
			$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
			
			for($i=1;$i<count($impuestos);$i++)
			{
				$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="'.obtenerMayusculas($impuestos[$i]['tipo']).'" tasa="'.Sprintf("% 01.2f",$impuestos[$i]['tasa']).'" importe="'.Sprintf("% 01.2f",$impuestos[$i]['importe']).'"/>';
			}
			
			#$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IEPS" tasa="0.00" importe="0.00"/>';
			#$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="'.Sprintf("% 01.2f",$cotizacion->ivaPorcentaje).'" importe="'.Sprintf("% 01.2f",$cotizacion->iva).'"/>';
			
			$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
		}
		else
		{
			$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="0">';
			$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="0" importe="0"/>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
		}

		$retencion="";
		
		if($retenciones['importe']>0)
		{
			$retencion.='  <implocal:ImpuestosLocales xmlns:implocal="http://www.sat.gob.mx/implocal" version="1.0" TotaldeRetenciones="'.Sprintf("% 01.2f",$retenciones['importe']).'" TotaldeTraslados="0.00">';
			$retencion.=chr(13).chr(10).'    <implocal:RetencionesLocales ImpLocRetenido="'.$retenciones['nombre'].'" TasadeRetencion="'.Sprintf("% 01.2f",$retenciones['tasa']).'" Importe="'.Sprintf("% 01.2f",$retenciones['importe']).'"/>';
			$retencion.=chr(13).chr(10).'  </implocal:ImpuestosLocales>';
		}
		
		$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
		$XMLFacturaComprobante.
		$XMLFacturaEmisor.
		$XMLFacturaReceptor.
		$XMLFacturaConceptos.
		$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$retencion.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

		return $XMLFinal;
	}

	function facturaGlobal1($configuracion,$cliente,$sello,$certificado,$fecha,$folio,$divisa,$totales,$impuestos)
	{
		$fecha			=str_replace(" ","T",$fecha);
		$sello			=str_replace(" ","",$sello);
		$certificado	=str_replace(" ","",$certificado);

		$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:implocal="http://www.sat.gob.mx/implocal" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
		$XMLFacturaComprobante.=' sello="'.$sello.'"';
		$XMLFacturaComprobante.=' certificado="'.$certificado.'"';
		
		$XMLFacturaComprobante.=' xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd"';
		
		$XMLFacturaComprobante.=' version="3.2" folio="'.$folio.'" serie="'.$configuracion->serie.'" fecha="'.$fecha.'" formaDePago="'.$_POST['txtFormaPago'].'" noCertificado="'.$configuracion->numeroCertificado.'" condicionesDePago="'.$_POST['txtCondiciones'].'" subTotal="'.Sprintf("% 01.2f",$totales->subTotal).'"';
		
		/*if($descuento>0)
		{
			$XMLFacturaComprobante.=' descuento="'.Sprintf("% 01.2f",$descuento).'"';
		}*/
		
		if(strlen($configuracion->numeroCuenta)>0)
		{
			#$XMLFacturaComprobante.=' NumCtaPago="'.$configuracion->numeroCuenta.'"';
		}
		
		$XMLFacturaComprobante.=' TipoCambio="'.Sprintf("% 01.2f",$divisa->tipoCambio).'" Moneda="'.$divisa->clave.'" total="'.Sprintf("% 01.2f",$totales->total).'" metodoDePago="'.$_POST['txtMetodoPago'].'" tipoDeComprobante="ingreso" LugarExpedicion="'.$configuracion->estado.', '.$configuracion->pais.'">';
		
		$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" nombre="'.sustituir($configuracion->nombre).'">';
		$XMLFacturaEmisor.=chr(13).chr(10).'    <cfdi:DomicilioFiscal';
		$XMLFacturaEmisor.=strlen($configuracion->calle)>1?' calle="'.sustituir($configuracion->calle).'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->numeroExterior)>0?' noExterior="'.$configuracion->numeroExterior.'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->colonia)>1?' colonia="'.sustituir($configuracion->colonia).'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->localidad)>1?' localidad="'.sustituir($configuracion->localidad).'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->municipio)>1?' municipio="'.$configuracion->municipio.'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->estado)>1?' estado="'.$configuracion->estado.'"':'';
		$XMLFacturaEmisor.=' pais="'.$configuracion->pais.'" codigoPostal="'.$configuracion->codigoPostal.'"/>';
		$XMLFacturaEmisor.=chr(13).chr(10).'    <cfdi:RegimenFiscal Regimen="'.sustituir($configuracion->regimenFiscal).'"/>';
		$XMLFacturaEmisor.=chr(13).chr(10).'  </cfdi:Emisor>';
		
		$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor rfc="'.espaciosFactura(sustituir($cliente->rfc)).'" nombre="'.sustituir($cliente->razonSocial).'" >';
		$XMLFacturaReceptor.=chr(13).chr(10).'    <cfdi:Domicilio';		
		$XMLFacturaReceptor.=strlen($cliente->calle)>2?' calle="'.sustituir($cliente->calle).'"':'';
		$XMLFacturaReceptor.=strlen($cliente->numero)>0?' noExterior="'.sustituir($cliente->numero).'"':'';
		$XMLFacturaReceptor.=strlen($cliente->colonia)>2?' colonia="'.sustituir($cliente->colonia).'"':'';
		$XMLFacturaReceptor.=strlen($cliente->localidad)>2?' localidad="'.sustituir($cliente->localidad).'"':'';
		$XMLFacturaReceptor.=strlen($cliente->municipio)>2?' municipio="'.sustituir($cliente->municipio).'"':'';
		$XMLFacturaReceptor.=strlen($cliente->estado)>2?' estado="'.sustituir($cliente->estado).'"':'';
		$XMLFacturaReceptor.=' pais="'.sustituir($cliente->pais).'"';
		$XMLFacturaReceptor.=strlen($cliente->codigoPostal)==5?' codigoPostal="'.sustituir($cliente->codigoPostal).'"':'';
		
		$XMLFacturaReceptor.='/>';
		$XMLFacturaReceptor.=chr(13).chr(10).'  </cfdi:Receptor>';
		
		
		$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';
		
		$i=0;
		#$numeroProductos	=	$_POST['txtNumeroProductos'];
		
		$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto cantidad="'.Sprintf("% 01.2f",1).'" unidad="NA" valorUnitario="'.Sprintf("% 01.2f",$totales->subTotal).'" descripcion="'.sustituir($_POST['txtConceptoGlobal']).'" importe="'.Sprintf("% 01.2f",$totales->subTotal).'" />';

		$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';
		
		if(count($impuestos)>1)
		{
			$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="'.Sprintf("% 01.2f",$impuestos[0]['importe']).'">';
			$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
			
			for($i=1;$i<count($impuestos);$i++)
			{
				$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="'.obtenerMayusculas($impuestos[$i]['tipo']).'" tasa="'.Sprintf("% 01.2f",$impuestos[$i]['tasa']).'" importe="'.Sprintf("% 01.2f",$impuestos[$i]['importe']).'"/>';
			}
			
			$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
		}
		else
		{
			$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="0">';
			$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="0" importe="0"/>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
		}
		
		/*$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="'.Sprintf("% 01.2f",$totales->iva).'">';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="'.Sprintf("% 01.2f",$totales->ivaPorcentaje).'" importe="'.Sprintf("% 01.2f",$totales->iva).'"/>';
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
	
	#--------------------------------------------------------------------------------------------------#
	#	                                    Crear el archivo XML de la nómina					       #
	#--------------------------------------------------------------------------------------------------#
	function crearXmlRecibo($configuracion,$empleado,$sello,$certificado,$fecha,$folio,$divisa,$antiguedad)
	{
		$fecha			=str_replace(" ","T",$fecha);
		$sello			=str_replace(" ","",$sello);
		$certificado	=str_replace(" ","",$certificado);

		$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:implocal="http://www.sat.gob.mx/implocal" xmlns:nomina="http://www.sat.gob.mx/nomina" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
		$XMLFacturaComprobante.=' sello="'.$sello.'"';
		$XMLFacturaComprobante.=' certificado="'.$certificado.'"';
		
		$XMLFacturaComprobante.=' xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/nomina http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina11.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd"';
		
		$XMLFacturaComprobante.=' version="3.2" folio="'.$folio.'"';
		
		if(strlen($configuracion->serie)>0)
		{
			$XMLFacturaComprobante.=' serie="'.$configuracion->serie.'"';
		}
		
		$XMLFacturaComprobante.=' fecha="'.$fecha.'" formaDePago="'.sustituir($_POST['txtFormaPago']).'" noCertificado="'.$configuracion->numeroCertificado.'"  ';
		#$XMLFacturaComprobante.=strlen($_POST['condiciones'])>0?' condicionesDePago="'.$_POST['condiciones'].'"':'';
		$XMLFacturaComprobante.=' subTotal="'.Sprintf("% 01.2f",$_POST['txtPercepciones']).'"';
		
		if($_POST['txtDeducciones']>0)
		{
			$XMLFacturaComprobante.=' descuento="'.Sprintf("% 01.2f",$_POST['txtDeducciones']-$_POST['txtTotalIsr']).'" motivoDescuento="Deducciones nómina"';
		}
		
		if(strlen($configuracion->numeroCuenta)>0)
		{
			#$XMLFacturaComprobante.=' NumCtaPago="'.$configuracion->numeroCuenta.'"';
		}
		
		$XMLFacturaComprobante.=' TipoCambio="'.Sprintf("% 01.2f",$divisa->tipoCambio).'" Moneda="'.$divisa->clave.'" total="'.Sprintf("% 01.2f",$_POST['txtTotales']).'" metodoDePago="'.$_POST['txtMetodoPago'].'" tipoDeComprobante="egreso" LugarExpedicion="'.$configuracion->estado.', '.$configuracion->pais.'">';
		
		$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" nombre="'.sustituir($configuracion->nombre).'">';
		$XMLFacturaEmisor.=chr(13).chr(10).'    <cfdi:DomicilioFiscal calle="'.sustituir($configuracion->calle).'"';

		$XMLFacturaEmisor.=strlen($configuracion->numeroExterior)>1?' noExterior="'.$configuracion->numeroExterior.'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->numeroInterior)>1?' noInterior="'.$configuracion->numeroInterior.'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->colonia)>2?' colonia="'.sustituir($configuracion->colonia).'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->localidad)>2?' localidad="'.sustituir($configuracion->localidad).'"':'';
		$XMLFacturaEmisor.=' municipio="'.$configuracion->municipio.'" estado="'.$configuracion->estado.'" pais="'.$configuracion->pais.'" codigoPostal="'.$configuracion->codigoPostal.'"/>';
		$XMLFacturaEmisor.=chr(13).chr(10).'    <cfdi:RegimenFiscal Regimen="'.sustituir($configuracion->regimenFiscal).'"/>';
		$XMLFacturaEmisor.=chr(13).chr(10).'  </cfdi:Emisor>';
		
		$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor rfc="'.espaciosFactura(sustituir($empleado->rfc)).'" nombre="'.sustituir($empleado->nombre).'" >';
		$XMLFacturaReceptor.=chr(13).chr(10).'    <cfdi:Domicilio';
		$XMLFacturaReceptor.=' pais="México"';
		$XMLFacturaReceptor.='/>';
		$XMLFacturaReceptor.=chr(13).chr(10).'  </cfdi:Receptor>';
		
		$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';
		$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto cantidad="'.Sprintf("% 01.2f",'1').'" unidad="Servicio" valorUnitario="'.Sprintf("% 01.2f",$_POST['txtPercepciones']).'" descripcion="'.$_POST['txtConcepto'].'" importe="'.Sprintf("% 01.2f",$_POST['txtPercepciones']).'" />';
		$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';
		
		$totalIsr		=$_POST['txtTotalIsr'];
		$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="0"';
		$XMLFacturaImpuestos.=$totalIsr>1?' totalImpuestosRetenidos="'.Sprintf("% 01.2f",$totalIsr).'"':'';
		$XMLFacturaImpuestos.='>';
		
		if($totalIsr>0)
		{
			$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Retenciones>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Retencion impuesto="ISR" importe="'.Sprintf("% 01.2f",$totalIsr).'"/>';
			$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Retenciones>';
		}
		
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="0.00" importe="0.00"/>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
		
		$nomina="";

		$nomina.='  <nomina:Nomina  Version="1.1" RegistroPatronal="'.$empleado->registroPatronal.'" NumEmpleado="'.$empleado->numeroEmpleado.'" CURP="'.$empleado->curp.'" TipoRegimen="'.$empleado->idRegimen.'"';
		$nomina.=' NumSeguridadSocial="'.$empleado->numeroSeguridad.'" FechaPago="'.$_POST['txtFechaPago'].'" FechaInicialPago="'.$_POST['txtFechaInicialPago'].'" FechaFinalPago="'.$_POST['txtFechaFinalPago'].'" NumDiasPagados="'.$_POST['txtDiasTrabajados'].'"';
		$nomina.=strlen($empleado->departamento)>1?' Departamento="'.sustituir($empleado->departamento).'"':'';
		$nomina.=strlen($empleado->clabe)>1?' CLABE="'.$empleado->clabe.'"':'';
		$nomina.=strlen($empleado->claveBanco)>1?' Banco="'.$empleado->claveBanco.'"':'';
		$nomina.=' FechaInicioRelLaboral="'.$empleado->fechaInicio.'" Antiguedad="'.$antiguedad.'"';
		$nomina.=strlen($empleado->puesto)>1?' Puesto="'.sustituir($empleado->puesto).'"':'';
		$nomina.=strlen($empleado->tipoContrato)>1?' TipoContrato="'.$empleado->tipoContrato.'"':'';
		$nomina.=strlen($empleado->tipoJornada)>1?' TipoJornada="'.$empleado->tipoJornada.'"':'';
		$nomina.=strlen($empleado->periodicidadPago)>1?' PeriodicidadPago="'.$empleado->periodicidadPago.'"':'';
		$nomina.=$empleado->salarioBase>0?' SalarioBaseCotApor="'.$empleado->salarioBase.'"':'';
		$nomina.=$empleado->idRiesgo>0?' RiesgoPuesto="'.$empleado->idRiesgo.'"':'';
		$nomina.=$empleado->salarioDiario>0?' SalarioDiarioIntegrado="'.$empleado->salarioDiario.'"':'';
		$nomina.='>';
		
		//PARA LAS PERCEPCIONES
		$numeroPercepciones	=$_POST['txtNumeroPercepciones'];
		$nomina.=chr(13).chr(10).'    <nomina:Percepciones TotalGravado="'.$_POST['txtTotalGravadoPercepciones'].'" TotalExento="'.$_POST['txtTotalExentoPercepciones'].'">';
		
		for($i=0;$i<$numeroPercepciones;$i++)
		{
			if(isset($_POST['txtTipoPercepcion'.$i]))
			{
				if(strlen($_POST['txtTipoPercepcion'.$i])>0)
				{
					$nomina.=chr(13).chr(10).'      <nomina:Percepcion TipoPercepcion="'.$_POST['txtTipoPercepcion'.$i].'" Clave="'.sustituir($_POST['txtClavePercepcion'.$i]).'" Concepto="'.sustituir($_POST['txtConceptoPercepcion'.$i]).'" ImporteGravado="'.$_POST['txtImporteGravadoPercepcion'.$i].'" ImporteExento="'.$_POST['txtImporteExentoPercepcion'.$i].'"/>';
				}
			}
		}
		
		$nomina.=chr(13).chr(10).'    </nomina:Percepciones>';
		
		//PARA LAS DEDUCCIONES
		$numeroDeducciones	=$_POST['txtNumeroDeducciones'];
		$nomina.=chr(13).chr(10).'    <nomina:Deducciones TotalGravado="'.$_POST['txtTotalGravadoDeducciones'].'" TotalExento="'.$_POST['txtTotalExentoDeducciones'].'">';
		
		for($i=0;$i<$numeroDeducciones;$i++)
		{
			if(isset($_POST['txtTipoDeduccion'.$i]))
			{
				if(strlen($_POST['txtTipoDeduccion'.$i])>0)
				{
					$nomina.=chr(13).chr(10).'      <nomina:Deduccion TipoDeduccion="'.$_POST['txtTipoDeduccion'.$i].'" Clave="'.($_POST['txtClaveDeduccion'.$i]).'" Concepto="'.sustituir($_POST['txtConceptoDeduccion'.$i]).'" ImporteGravado="'.$_POST['txtImporteGravadoDeduccion'.$i].'" ImporteExento="'.$_POST['txtImporteExentoDeduccion'.$i].'"/>';
				}
			}
		}
		
		$nomina.=chr(13).chr(10).'    </nomina:Deducciones>';
		$nomina.=chr(13).chr(10).'  </nomina:Nomina>';
		
		$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
		$XMLFacturaComprobante.
		$XMLFacturaEmisor.
		$XMLFacturaReceptor.
		$XMLFacturaConceptos.
		$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$nomina.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

		return $XMLFinal;
	}
	
	#--------------------------------------------------------------------------------------------------#
	#	                                   XML para los ingresos  								       #
	#--------------------------------------------------------------------------------------------------#
	function xmlIngreso($configuracion,$cliente,$sello,$certificado,$fecha,$folio,$divisa)
	{
		$fecha			=str_replace(" ","T",$fecha);
		$sello			=str_replace(" ","",$sello);
		$certificado	=str_replace(" ","",$certificado);

		#$iva			=Sprintf("% 01.2f",$iva);
		
		#$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:implocal="http://www.sat.gob.mx/implocal" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
		$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd"';
		$XMLFacturaComprobante.=' Sello="'.$sello.'"';
		$XMLFacturaComprobante.=' Certificado="'.$certificado.'"';
		
		#$XMLFacturaComprobante.=' xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd"';
		
		$XMLFacturaComprobante.=' Version="3.3" Folio="'.$folio.'"'.(strlen($configuracion->serie)>0?' Serie="'.sustituir($configuracion->serie).'"':'').' Fecha="'.$fecha.'" FormaPago="'.sustituir($_POST['txtFormaPago']).'" NoCertificado="'.$configuracion->numeroCertificado.'"'.(strlen($_POST['txtCondiciones'])>0?' CondicionesDePago="'.sustituir($_POST['txtCondiciones']).'"':'').' SubTotal="'.Sprintf("% 01.2f",$_POST['txtSubTotal']).'"';

		$XMLFacturaComprobante.=' Moneda="'.$divisa->clave.'" Total="'.Sprintf("% 01.2f",$_POST['txtTotal']).'" MetodoPago="'.sustituir($_POST['txtMetodoPago']).'" TipoDeComprobante="I" LugarExpedicion="'.$configuracion->codigoPostal.'">';
		
		$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor Rfc="'.obtenerMayusculas(espaciosFactura(sustituir($configuracion->rfc))).'" Nombre="'.sustituir($configuracion->nombre).'" RegimenFiscal="'.sustituir($configuracion->claveRegimen).'"/>';
		$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor Rfc="'.obtenerMayusculas(espaciosFactura(sustituir($cliente->rfc))).'" Nombre="'.sustituir($cliente->razonSocial).'" UsoCFDI="'.$_POST['selectUsoCfdi'].'"/>';
		
		$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';
		$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto ClaveProdServ="'.$_POST['txtClaveProducto'].'" Cantidad="1" ClaveUnidad="'.$_POST['txtClaveUnidad'].'" Unidad="'.$_POST['txtUnidadDescripcion'].'" ValorUnitario="'.Sprintf("% 01.2f",$_POST['txtSubTotal']).'" Descripcion="'.sustituir($_POST['txtConcepto']).'" Importe="'.Sprintf("% 01.2f",$_POST['txtSubTotal']).'">';
		$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Impuestos>';
		$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslados>';
		$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslado Base="'.Sprintf("% 01.2f",$_POST['txtSubTotal']).'" Impuesto="002" TipoFactor="Tasa" TasaOCuota="'.Sprintf("% 01.6f",$_POST['txtIvaPorcentaje']/100).'" Importe="'.Sprintf("% 01.2f",$_POST['txtIva']).'"/>';
		$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Traslados>';
		$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Impuestos>';
		$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Concepto>';
		$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';
		
		$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos TotalImpuestosTrasladados="'.Sprintf("% 01.2f",$_POST['txtIva']).'">';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Impuesto="002" TipoFactor="Tasa" TasaOCuota="'.Sprintf("% 01.6f",$_POST['txtIvaPorcentaje']/100).'" Importe="'.Sprintf("% 01.2f",$_POST['txtIva']).'"/>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';

		/*$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="'.Sprintf("% 01.2f",$_POST['txtIva']).'">';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="'.Sprintf("% 01.2f",$_POST['txtIvaPorcentaje']).'" importe="'.Sprintf("% 01.2f",$_POST['txtIva']).'"/>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';*/
		
		$retencion='';

		$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
		$XMLFacturaComprobante.
		$XMLFacturaEmisor.
		$XMLFacturaReceptor.
		$XMLFacturaConceptos.
		$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$retencion.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

		return $XMLFinal;
	}
	
	#--------------------------------------------------------------------------------------------------#
	#	                                    Crear el archivo XML								       #
	#--------------------------------------------------------------------------------------------------#
	function facturaManual1($configuracion,$cliente,$sello,$certificado,$fecha,$folio,$divisa)
	{
		$fecha			=str_replace(" ","T",$fecha);
		$sello			=str_replace(" ","",$sello);
		$certificado	=str_replace(" ","",$certificado);

		$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:implocal="http://www.sat.gob.mx/implocal" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
		$XMLFacturaComprobante.=' sello="'.$sello.'"';
		$XMLFacturaComprobante.=' certificado="'.$certificado.'"';
		
		$XMLFacturaComprobante.=' xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd"';
		
		$XMLFacturaComprobante.=' version="3.2" folio="'.$folio.'" serie="'.$configuracion->serie.'" fecha="'.$fecha.'" formaDePago="'.$_POST['txtFormaPago'].'" noCertificado="'.$configuracion->numeroCertificado.'" condicionesDePago="'.$_POST['txtCondiciones'].'" subTotal="'.Sprintf("% 01.2f",$_POST['txtSubTotal']).'"';
		
		/*if($descuento>0)
		{
			$XMLFacturaComprobante.=' descuento="'.Sprintf("% 01.2f",$descuento).'"';
		}*/
		
		if(strlen($configuracion->numeroCuenta)>0)
		{
			#$XMLFacturaComprobante.=' NumCtaPago="'.$configuracion->numeroCuenta.'"';
		}
		
		$XMLFacturaComprobante.=' TipoCambio="'.Sprintf("% 01.2f",$divisa->tipoCambio).'" Moneda="'.$divisa->clave.'" total="'.Sprintf("% 01.2f",$_POST['txtTotal']).'" metodoDePago="'.$_POST['txtMetodoPago'].'" tipoDeComprobante="ingreso" LugarExpedicion="'.$configuracion->estado.', '.$configuracion->pais.'">';
		
		$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor rfc="'.espaciosFactura(sustituir($configuracion->rfc)).'" nombre="'.sustituir($configuracion->nombre).'">';
		$XMLFacturaEmisor.=chr(13).chr(10).'    <cfdi:DomicilioFiscal';
		$XMLFacturaEmisor.=strlen($configuracion->calle)>1?' calle="'.sustituir($configuracion->calle).'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->numeroExterior)>0?' noExterior="'.$configuracion->numeroExterior.'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->colonia)>1?' colonia="'.sustituir($configuracion->colonia).'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->localidad)>1?' localidad="'.sustituir($configuracion->localidad).'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->municipio)>1?' municipio="'.$configuracion->municipio.'"':'';
		$XMLFacturaEmisor.=strlen($configuracion->estado)>1?' estado="'.$configuracion->estado.'"':'';
		$XMLFacturaEmisor.=' pais="'.$configuracion->pais.'" codigoPostal="'.$configuracion->codigoPostal.'"/>';
		$XMLFacturaEmisor.=chr(13).chr(10).'    <cfdi:RegimenFiscal Regimen="'.sustituir($configuracion->regimenFiscal).'"/>';
		$XMLFacturaEmisor.=chr(13).chr(10).'  </cfdi:Emisor>';
		
		$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor rfc="'.espaciosFactura(sustituir($cliente->rfc)).'" nombre="'.sustituir($cliente->razonSocial).'" >';
		$XMLFacturaReceptor.=chr(13).chr(10).'    <cfdi:Domicilio';		
		$XMLFacturaReceptor.=strlen($cliente->calle)>2?' calle="'.sustituir($cliente->calle).'"':'';
		$XMLFacturaReceptor.=strlen($cliente->numero)>0?' noExterior="'.sustituir($cliente->numero).'"':'';
		$XMLFacturaReceptor.=strlen($cliente->colonia)>2?' colonia="'.sustituir($cliente->colonia).'"':'';
		$XMLFacturaReceptor.=strlen($cliente->localidad)>2?' localidad="'.sustituir($cliente->localidad).'"':'';
		$XMLFacturaReceptor.=strlen($cliente->municipio)>2?' municipio="'.sustituir($cliente->municipio).'"':'';
		$XMLFacturaReceptor.=strlen($cliente->estado)>2?' estado="'.sustituir($cliente->estado).'"':'';
		$XMLFacturaReceptor.=' pais="'.sustituir($cliente->pais).'"';
		$XMLFacturaReceptor.=strlen($cliente->codigoPostal)==5?' codigoPostal="'.sustituir($cliente->codigoPostal).'"':'';
		
		$XMLFacturaReceptor.='/>';
		$XMLFacturaReceptor.=chr(13).chr(10).'  </cfdi:Receptor>';
		
		
		$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';
		
		$i=0;
		$numeroProductos	=	$_POST['txtNumeroProductos'];
		
		for($i=1;$i<=$numeroProductos;$i++)
		{
			if(isset($_POST['txtIdConcepto'.$i]))
			{
				$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto cantidad="'.Sprintf("% 01.2f",$_POST['txtCantidadFactura'.$i]).'" unidad="'.sustituir($_POST['txtUnidadFactura'.$i]).'" valorUnitario="'.Sprintf("% 01.2f",$_POST['txtPrecioFactura'.$i]).'" descripcion="'.sustituir($_POST['txtConceptoFactura'.$i]).'" importe="'.Sprintf("% 01.2f",$_POST['txtImporteFactura'.$i]).'" />';
			}
		}

		$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';
		
		$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="'.Sprintf("% 01.2f",$_POST['txtIva']).'">';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="'.Sprintf("% 01.2f",$_POST['selectIva']).'" importe="'.Sprintf("% 01.2f",$_POST['txtIva']).'"/>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';
		
		$retencion="";

		$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
		$XMLFacturaComprobante.
		$XMLFacturaEmisor.
		$XMLFacturaReceptor.
		$XMLFacturaConceptos.
		$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$retencion.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

		return $XMLFinal;
	}
	
	#--------------------------------------------------------------------------------------------------#
	#	                                   XML para los ingresos  								       #
	#--------------------------------------------------------------------------------------------------#
	function xmlIngresoGlobal($configuracion,$cliente,$sello,$certificado,$fecha,$folio,$divisa)
	{
		$fecha			=str_replace(" ","T",$fecha);
		$sello			=str_replace(" ","",$sello);
		$certificado	=str_replace(" ","",$certificado);

		#$iva			=Sprintf("% 01.2f",$iva);
		
		#$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:implocal="http://www.sat.gob.mx/implocal" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
		$XMLFacturaComprobante='<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd"';
		$XMLFacturaComprobante.=' Sello="'.$sello.'"';
		$XMLFacturaComprobante.=' Certificado="'.$certificado.'"';
		
		#$XMLFacturaComprobante.=' xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/implocal http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd"';
		
		$XMLFacturaComprobante.=' Version="3.3" Folio="'.$folio.'"'.(strlen($configuracion->serie)>0?' Serie="'.sustituir($configuracion->serie).'"':'').' Fecha="'.$fecha.'" FormaPago="'.sustituir($_POST['txtFormaPago']).'" NoCertificado="'.$configuracion->numeroCertificado.'"'.(strlen($_POST['txtCondiciones'])>0?' CondicionesDePago="'.sustituir($_POST['txtCondiciones']).'"':'').' SubTotal="'.Sprintf("% 01.2f",$_POST['txtSubTotal']).'"';

		$XMLFacturaComprobante.=' Moneda="'.$divisa->clave.'" Total="'.Sprintf("% 01.2f",$_POST['txtTotal']).'" MetodoPago="'.sustituir($_POST['txtMetodoPago']).'" TipoDeComprobante="I" LugarExpedicion="'.$configuracion->codigoPostal.'">';
		
		$XMLFacturaEmisor=chr(13).chr(10).'  <cfdi:Emisor Rfc="'.obtenerMayusculas(espaciosFactura(sustituir($configuracion->rfc))).'" Nombre="'.sustituir($configuracion->nombre).'" RegimenFiscal="'.sustituir($configuracion->claveRegimen).'"/>';
		$XMLFacturaReceptor=chr(13).chr(10).'  <cfdi:Receptor Rfc="'.obtenerMayusculas(espaciosFactura(sustituir($cliente->rfc))).'" Nombre="'.sustituir($cliente->razonSocial).'" UsoCFDI="'.$_POST['selectUsoCfdi'].'"/>';
		
		$XMLFacturaConceptos=chr(13).chr(10).'  <cfdi:Conceptos>';
		$XMLFacturaConceptos.=chr(13).chr(10).'    <cfdi:Concepto ClaveProdServ="'.$_POST['txtClaveProducto'].'" Cantidad="1" ClaveUnidad="'.$_POST['txtClaveUnidad'].'" Unidad="'.$_POST['txtUnidadDescripcion'].'" ValorUnitario="'.Sprintf("% 01.2f",$_POST['txtSubTotal']).'" Descripcion="'.sustituir($_POST['txtConcepto']).'" Importe="'.Sprintf("% 01.2f",$_POST['txtSubTotal']).'">';
		$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Impuestos>';
		$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslados>';
		$XMLFacturaConceptos.=chr(13).chr(10).'<cfdi:Traslado Base="'.Sprintf("% 01.2f",$_POST['txtSubTotal']).'" Impuesto="002" TipoFactor="Tasa" TasaOCuota="'.Sprintf("% 01.6f",$_POST['txtIvaPorcentaje']/100).'" Importe="'.Sprintf("% 01.2f",$_POST['txtIva']).'"/>';
		$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Traslados>';
		$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Impuestos>';
		$XMLFacturaConceptos.=chr(13).chr(10).'</cfdi:Concepto>';
		$XMLFacturaConceptos.=chr(13).chr(10).'  </cfdi:Conceptos>';
		
		$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos TotalImpuestosTrasladados="'.Sprintf("% 01.2f",$_POST['txtIva']).'">';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado Impuesto="002" TipoFactor="Tasa" TasaOCuota="'.Sprintf("% 01.6f",$_POST['txtIvaPorcentaje']/100).'" Importe="'.Sprintf("% 01.2f",$_POST['txtIva']).'"/>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';

		/*$XMLFacturaImpuestos=chr(13).chr(10).'  <cfdi:Impuestos totalImpuestosTrasladados="'.Sprintf("% 01.2f",$_POST['txtIva']).'">';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    <cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'      <cfdi:Traslado impuesto="IVA" tasa="'.Sprintf("% 01.2f",$_POST['txtIvaPorcentaje']).'" importe="'.Sprintf("% 01.2f",$_POST['txtIva']).'"/>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'    </cfdi:Traslados>';
		$XMLFacturaImpuestos.=chr(13).chr(10).'  </cfdi:Impuestos>';*/
		
		$retencion='';

		$XMLFinal='<?xml version="1.0" encoding="utf-8"?>'.chr(13).chr(10).
		$XMLFacturaComprobante.
		$XMLFacturaEmisor.
		$XMLFacturaReceptor.
		$XMLFacturaConceptos.
		$XMLFacturaImpuestos.chr(13).chr(10).'  <cfdi:Complemento>'.chr(13).chr(10).$retencion.'  </cfdi:Complemento>'.chr(13).chr(10).'</cfdi:Comprobante>';

		return $XMLFinal;
	}
	
	function QuitarEspaciosXML($Texto,$Tipo)
	{
		$Textox="";
		
		switch($Tipo)
		{
			case "A": $Textox=eregi_replace("[\n|\r|\n\r]"," ", $Texto); break;
			case "B": $Textox=preg_replace("/[\n|\r|\n\r]/i"," ", $Texto); break;
		}
		
		return $Textox;
	}

	function rmdirr($dirname)
	{
		// Simple delete for a file
		if (is_file($dirname)) 
		{
			return unlink($dirname); 
		}
		// Loop through the folder
		$dir = dir($dirname);
		while (false !== $entry = $dir->read()) 
		{
			// Skip pointers
			if ($entry == '.' || $entry == '..') 
			{ 
				continue; 
			}
			// Deep delete directories
			if (is_dir("$dirname/$entry")) 
			{
				rmdirr("$dirname/$entry"); }
			else 
			{
				unlink("$dirname/$entry");
			}
		}// Clean up
		$dir->close();
		
		return rmdir($dirname);
	}
?>
