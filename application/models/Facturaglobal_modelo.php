<?php
class Facturaglobal_modelo extends CI_Model 
{
    protected $_fecha_actual;
    protected $_table;
    protected $_iduser;
	protected $idLicencia;
	protected $resultado;

    function __construct() 
	{
        parent::__construct();

        $datestring 			= "%Y-%m-%d %H:%i:%s";
        $this->_fecha_actual 	= mdate($datestring, now());
        $this->_iduser 			= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->resultado		= "1";
		
		$this->cambiarFechaActual();
    }
	
	public function cambiarFechaActual()
	{
		$sql="select date_sub('".date('Y-m-d H:i:s')."', interval 10 minute) as fechaActual";
		
		$this->_fecha_actual=$this->db->query($sql)->row()->fechaActual;
	}
	
	public function obtenerOrdenesVentaFolios($inicio,$fin)
	{
		$sql=" select a.ordenCompra, a.idCotizacion
		 from cotizaciones as a
		 where a.folio between '$inicio' and '$fin'
		 and a.idFactura=0
		 and a.cancelada='0'
		 and a.activo='1'
		 and a.estatus='1'
		 and a.idCliente='1'	
		 and a.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTotalesVentaFolios($inicio,$fin)
	{
		$sql=" select coalesce(sum(a.total),0) as total,
		coalesce(sum(a.subTotal),0) as subTotal,
		coalesce(sum(a.iva),0) as iva,
		ivaPorcentaje
		from cotizaciones as a
		where a.folio between '$inicio' and '$fin'
		and a.idFactura=0
		and a.cancelada='0'
		and a.activo='1'
		and a.estatus='1'
		and a.idCliente='1'	
		and a.idLicencia='$this->idLicencia'  ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerOrdenesVentaFactura($inicio,$fin)
	{
		$sql=" select a.ordenCompra, a.idCotizacion
		 from cotizaciones as a
		 where date(a.fechaCompra) between '$inicio' and '$fin'
		 and a.idFactura=0
		 and a.cancelada='0'
		 and a.activo='1'
		 and a.estatus='1'
		 and a.idLicencia='$this->idLicencia'
		 and a.idCliente='1' ";
		
		# and (select count(c.idFactura) from facturas as c where c.idCotizacion=a.idCotizacion and c.pendiente='1') =0 
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTotalesVentaFactura($inicio,$fin,$tipo='')
	{
		$sql=" select coalesce(sum(a.total),0) as total,
		coalesce(sum(a.subTotal),0) as subTotal,
		coalesce(sum(a.iva),0) as iva,
		coalesce(sum(a.descuento),0) as descuento,
		ivaPorcentaje
		from cotizaciones as a
		where date(a.fechaCompra) between '$inicio' and '$fin'
		
		and a.cancelada='0'
		and a.activo='1'
		and a.estatus='1'
		and a.idLicencia='$this->idLicencia' ";
		
		#and a.idFactura=0
		
		#and a.idCliente='1' 
		
		$sql.=$tipo==0?" 
		and a.prefactura='0'
		and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') = 0 
		and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') = 0 ":'';
		
		$sql.=$tipo==1?" and a.prefactura='1' 
		and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') = 0 
		and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') > 0 ":'';
		
		#and (select count(c.idFactura) from facturas as c where c.idCotizacion=a.idCotizacion and c.pendiente='1') =0
		#echo $sql;
		return $this->db->query($sql)->row();
	}
	
	public function obtenerFoliosVentaFactura($inicio,$fin,$tipo='')
	{
		$sql=" select a.idCotizacion, a.fechaCompra, a.folio, a.total,
		a.subTotal,
		a.iva, a.descuento, a.ivaPorcentaje,
		b.nombre as estacion,
		
		
		(select coalesce(sum(c.importe),0) from cotiza_productos as c inner join cotiza_productos_impuestos as d on d.idProducto=c.idProducto where d.tasa>0 and c.idCotizacion=a.idCotizacion) as subTotalIva,
		(select coalesce(sum(c.importe),0) from cotiza_productos as c inner join cotiza_productos_impuestos as d on d.idProducto=c.idProducto where d.tasa=0 and c.idCotizacion=a.idCotizacion) as subTotalSinIva
		
		
		from cotizaciones as a
		inner join configuracion_estaciones as b
		on a.idEstacion=b.idEstacion
		where date(a.fechaCompra) between '$inicio' and '$fin'
		
		and a.cancelada='0'
		and a.activo='1'
		and a.estatus='1'
		and a.idLicencia='$this->idLicencia' ";
		#and a.idFactura=0
		#and a.idCliente='1' 
		
		$sql.=$tipo==0?" 
		and a.prefactura='0'
		and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') = 0 
		and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') = 0 ":'';
		
		$sql.=$tipo==1?" and a.prefactura='1'
		and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') = 0 
		and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') > 0 ":'';
		
		#and (select count(c.idFactura) from facturas as c where c.idCotizacion=a.idCotizacion and c.pendiente='1') =0
		
		$sql.=" order by b.nombre asc, a.folio asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFoliosVentaFacturaCanceladas($inicio,$fin,$tipo='')
	{
		$sql=" select a.idCotizacion, a.fechaCompra, a.folio, a.total,
		a.subTotal,
		a.iva, a.descuento, a.ivaPorcentaje,
		b.nombre as estacion
		from cotizaciones as a
		
		inner join configuracion_estaciones as b
		on a.idEstacion=b.idEstacion
		
		where date(a.fechaCompra) between '$inicio' and '$fin'
		and a.idFactura=0
		and a.cancelada='1'
		and a.activo='1'
		and a.estatus='1'
		and a.idLicencia='$this->idLicencia' ";
		
		#and a.idCliente='1' 
		
		$sql.=$tipo==0?" 
		and a.prefactura='0'
		and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') = 0 
		and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') = 0 ":'';
		
		$sql.=$tipo==1?"
		and a.prefactura='1'
		and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') = 0 
		and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') > 0 ":'';
		
		#and (select count(c.idFactura) from facturas as c where c.idCotizacion=a.idCotizacion and c.pendiente='1') =0
		
		$sql.=" order by b.nombre asc, a.folio asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerImpuestosProductosGlobal($inicio,$fin,$tipo='Fechas')
	{
		$sql=" select sum(a.importe) as importe, a.tasa, a.tipo, a.nombre, a.idImpuesto
		from cotiza_productos_impuestos as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		inner join cotizaciones as c
		on c.idCotizacion=b.idCotizacion
		where a.importe>0
		and c.idFactura=0
		and c.cancelada='0'
		and c.activo='1'
		and c.estatus='1'
		and c.idCliente='1'
		and c.idLicencia='$this->idLicencia'  ";
		
		$sql.=$tipo=='Fechas'?" and date(c.fechaCompra) between '$inicio' and '$fin' ":" and c.folio between '$inicio' and '$fin' ";
		
		$sql.=" group by a.tipo, a.tasa";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerProductosCotizacionesPeriodo($inicio,$fin)
	{
		$sql=" select a.idProducto, a.cantidad, a.precio, a.importe,
		a.descuento, a.descuentoPorcentaje, b.nombre, c.clave as claveUnidad, c.nombre as unidad,
		d.clave as claveProducto, d.nombre as claveDescripcion, b.codigoInterno,
		
		e.tasa, e.importe as importeImpuesto, e.tipo, e.nombre as impuesto,
		
		g.exento, h.clave as claveImpuesto, a.nombre as producto
		
		
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto

		inner join fac_catalogos_unidades as c
		on c.idUnidad=b.idUnidad
		
		inner join fac_catalogos_claves_productos as d
		on d.idClave=b.idClave
		
		inner join cotiza_productos_impuestos as e
		on e.idProducto=a.idProducto

		inner join cotizaciones as f
		on f.idCotizacion=a.idCotizacion
		
		
		inner join configuracion_impuestos as g
		on e.idImpuesto=g.idImpuesto
		
		inner join fac_impuestos as h
		on h.idCatalogoImpuesto=g.idCatalogoImpuesto

		where f.estatus=1 
		and date(f.fechaCompra) between '$inicio' and '$fin'
		and f.idFactura=0 
		and f.cancelada='0'
		and f.idLicencia='$this->idLicencia'
		and f.idCliente='1'
		
	 	  ";
		
		#and (select count(i.idFactura) from facturas as i where i.idCotizacion=f.idCotizacion and i.pendiente='1') =0
		
		return $this->db->query($sql)->result();
	}
	
	public function procesarImpuestosGlobal($inicio,$fin,$tipo='Fechas')
	{
		$data			= array();
		$i				= 1;
		$importeTotal	= 0;
		
		$impuestos		= $this->obtenerImpuestosProductosGlobal($inicio,$fin,$tipo);
		
		foreach($impuestos as $row)
		{
			$data[$i]['tasa']		= $row->tasa;
			$data[$i]['tipo']		= $row->tipo;
			$data[$i]['nombre']		= $row->nombre;
			$data[$i]['importe']	= $row->importe;
			$data[$i]['idImpuesto']	= $row->idImpuesto;
			
			$importeTotal			+=$row->importe;
			
			$i++;
		}

		$data[0]['tasa']		= 0;
		$data[0]['tipo']		= '';
		$data[0]['nombre']		= '';
		$data[0]['importe']		= $importeTotal;
		
		return $data;
	}
	
	/*public function procesarImpuestosGlobal($inicio,$fin,$tipo='Fechas')
	{
		$ordenes		= $tipo=='Fechas'?$this->obtenerOrdenesVentaFactura($inicio,$fin):$this->obtenerOrdenesVentaFolios($inicio,$fin);
		
		$data			= array();
		$i				= 1;
		$importeTotal	= 0;
		
		foreach($ordenes as $ord)
		{
			$impuestos		= $this->facturacion->obtenerImpuestosProductosCotizacion($ord->idCotizacion);
			
			foreach($impuestos as $row)
			{
				$data[$i]['tasa']		= $row->tasa;
				$data[$i]['tipo']		= $row->tipo;
				$data[$i]['nombre']		= $row->nombre;
				$data[$i]['importe']	= $row->importe;
				$data[$i]['idImpuesto']	= $row->idImpuesto;
				
				$importeTotal			+=$row->importe;
				
				$i++;
			}
		}

		$data[0]['tasa']		= 0;
		$data[0]['tipo']		= '';
		$data[0]['nombre']		= '';
		$data[0]['importe']		= $importeTotal;
		
		return $data;
	}*/

	#------------------------------------------------------------------------------------------------------#
	public function registrarFacturaGlobal()
	{
		if(!$this->facturacion->comprobarFoliosDisponibles())
		{
			return array('0','Los folios se han terminado, por favor consulte con el administrador','','','');
		}
		
		$this->db->trans_start();
		
		$this->load->helper('sat');
		$this->load->helper('global');

		$idCliente				= $this->input->post('txtIdClienteGlobal');
		$idEmisor				= $this->input->post('selectEmisoresGlobal');
		$idDireccion			= $this->input->post('selectDirecciones');
		$fechaFactura			= $this->input->post('txtFechaFactura');
		
		$diferencia				= $this->reportes->obtenerDiferenciaFechas($fechaFactura,date('Y-m-d H:i'),'minute');
		
		if($diferencia>4319)
		{
			return array('0',"La fecha es mayor a las 72 horas permitidas",'','','');
		}
		
		if($diferencia<0)
		{
			return array('0',"La fecha esta en el futuro",'','','');
		}
		
		$this->_fecha_actual	= $fechaFactura.':00';
		
		$configuracion			= $this->facturacion->obtenerEmisor($idEmisor);
		
		#$cliente				= $this->facturacion->obtenerCliente($idCliente);
		$cliente				= $this->clientes->obtenerDireccionesEditar($idDireccion);
		
	
		$divisa					= $this->facturacion->obtenerDivisa(1);
		$tipo					= $this->input->post('selectTipoRango');
		$inicio					= $this->input->post('txtInicio');
		$fin					= $this->input->post('txtFin');
		$tipo					= $this->input->post('selectTipoDocumento');
		
		$totales				= $this->obtenerTotalesVentaFactura($inicio,$fin,$tipo);
		$ventas					= $this->obtenerFoliosVentaFactura($inicio,$fin,$tipo);

		if(strlen($cliente->rfc)<12 or strlen($cliente->razonSocial) <3 or strlen($cliente->codigoPostal) !=5)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();

			return array('0',"El cliente no tiene los datos fiscales necesarios para crear la factura");
		}
		
		$folio					= $this->facturacion->obtenerFolio($idEmisor);
		
		if($folio<1)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',"Error de servidor SAT");
		}
		

		$carpetaUsuario		= carpetaCfdi.$configuracion->rfc.'/';
		$carpetaFolio		= $carpetaUsuario.'folio'.$configuracion->serie.$folio.'/';
		$cfd				= $carpetaFolio.'cfd'.$folio.'.xml';

		crearDirectorio($carpetaFolio);
		
		$sello					= "";
		$certificado			= "";

		$ficheroXML				= facturaGlobal($configuracion,$cliente,$sello,$certificado,$this->_fecha_actual,$folio,$ventas);
		
		guardarArchivoXML($cfd,$ficheroXML);
		
		exec("xsltproc ".carpetaCfdi.'cadenaoriginal4.xslt'." ".$cfd." > ".$carpetaFolio.'cadena.txt');
		
		exec("openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt');
		exec("openssl dgst -sha256 -sign ".$carpetaFolio."certificado.txt ".$carpetaFolio."cadena.txt | openssl enc -base64 -A > ".$carpetaFolio.'sello.txt');
		exec("openssl enc -base64 -in ".$carpetaUsuario.$configuracion->certificado." -out ".$carpetaFolio.'certificadoImprimir.txt');
		
		$certificado	= leerFichero($carpetaFolio.'certificadoImprimir.txt',"READ","");
		$certificado 	= QuitarEspaciosXML($certificado,"B");
		$sello			= leerFichero($carpetaFolio.'sello.txt',"READ","");
		$sello 			= QuitarEspaciosXML($sello,"B");
		$cadena			= leerFichero($carpetaFolio.'cadena.txt',"READ","");

		$ficheroXML		= facturaGlobal($configuracion,$cliente,$sello,$certificado,$this->_fecha_actual,$folio,$ventas);
		
		if(guardarArchivoXML($cfd,$ficheroXML))
		{
			if($configuracion->pac=='4gFactor')
			{
				$this->timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$ventas);
			}
			
			if($configuracion->pac=='finkok')
			{
				$this->timbrarFinkok($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$ventas);
			}
		}
		
		#$this->configuracion->registrarBitacora('Registrar factura manual','Reporte ventas público',$configuracion->serie.$folio.', '.$cliente->empresa); //Registrar bitácora
		
		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',$this->resultado);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			$this->facturacion->borrarArchivosTemporales($carpetaFolio);
			
			$this->configuracion->actualizarAccesoFacturacion('0');
			
			return array('1','');
		}
	}
	
	public function timbrarFinkok($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$ventas)
	{
		$this->load->library('finkok');
		$this->load->helper('xmlcfdi');

		$timbrado 		= new Finkok();
		$respuesta 		= $timbrado->obtenerTimbre($configuracion->usuarioPac,$configuracion->passwordPac,$configuracion->url,$ficheroXML);

		if(!$respuesta['estatus'])
		{
			$this->resultado	= $respuesta['mensaje'];
			
			if($respuesta['codigoError']!='0')
			{
				$this->facturacion->registrarError($respuesta['codigoError'],$respuesta['mensaje'],$configuracion->idEmisor);	
			}

			return 0;
		}
		
		if($respuesta['estatus'])
		{
			$timbre		=$carpetaFolio.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			$fichero	=fopen($timbre,"w");	
			fwrite($fichero,$respuesta['xml']);
			fclose($fichero);
			
			$data['xml']			= $respuesta['xml'];
			$data['folio']			= $folio;
			$data['cadenaOriginal']	= $cadena;
			$data['selloDigital']	= $sello;
			$data['UUID']			= $respuesta['valores'][40];
			$data['fechaTimbrado']	= $respuesta['valores'][39];
			$data['selloSat']		= $respuesta['valores'][43];
			$data['certificado']	= $respuesta['valores'][41];
			
			$data['cadenaTimbre']	= '||1.1|'.$data['UUID'].'|'.$data['fechaTimbrado'].'|'.$respuesta['valores'][38].'|'.$data['selloDigital'].'|'.$data['certificado'].'||';
			
			$this->session->set_userdata('notificacion',"El cfdi se ha creado correctamente");
			
			$this->agregarFactura($data,$configuracion,$cliente,$ventas);
			
		}
	}
	
	public function timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cliente,$configuracion,$ventas)
	{
		$this->load->library('factor');
		
		$timbrado 		= new Factor();
		$respuesta 		= $timbrado->obtenerTimbre($configuracion->usuarioPac, $configuracion->passwordPac, $ficheroXML,$configuracion->url);

		if(!$respuesta['estatus'])
		{
			if(strlen($respuesta['codigoError'])>0)
			{
				$this->facturacion->registrarError($respuesta['codigoError'],$respuesta['comentarios'],$configuracion->idEmisor);	
			}
			
			$this->resultado	= $respuesta['mensaje'];
			
			return 0;
		}
		
		if($respuesta['estatus'])
		{
			$timbre		=$carpetaFolio.'cfdi'.$folio.'Timbre.xml'; #Es el archivo XML Timbrado
			$fichero	=fopen($timbre,"w");	
			fwrite($fichero,$respuesta['xml']);
			fclose($fichero);
			
			$data['xml']			=$respuesta['xml'];
			$data['folio']			=$folio;
			$data['cadenaTimbre']	=$respuesta['cadenaTimbre'];
			$data['cadenaOriginal']	=$cadena;
			$data['selloDigital']	=$sello;
			$data['UUID']			=$respuesta['uuid'];
			$data['fechaTimbrado']	=$respuesta['fechaTimbrado'];
			$data['selloSat']		=$respuesta['selloSat'];
			$data['certificado']	=$respuesta['certificado'];

			$this->agregarFactura($data,$configuracion,$cliente,$ventas);
		}
	}

	public function agregarFactura($timbre,$configuracion,$cliente,$ventas)
	{
		$subTotal	= 0;
		$total		= 0;
		$iva		= 0;
		$ivas		= 0;
		$ieps		= 0;
		$descuentos	= 0;
		
		foreach($ventas as $row)
		{
			$importe	= $row->subTotalIva;
			$importe	= round($importe,decimales);

			$importeSinIva	= $row->subTotalSinIva;
			$importeSinIva	= round($importeSinIva,decimales);
			
			$impuesto	= $importe*($row->ivaPorcentaje/100);
			$impuesto	= round($impuesto,decimales);

			$subTotal	+=$importe+$importeSinIva;
			$ivas		+=$impuesto;
		}
		
		$total			= $subTotal+$ivas;
		$total			= round($total,decimales);
		
		$data=array
		(
			'rfc'					=> $cliente->rfc,
			'empresa'				=> $cliente->razonSocial,
			'calle'					=> $cliente->calle,
			'numeroExterior'		=> $cliente->numero,
			'colonia'				=> $cliente->codigoPostal,
			'localidad'				=> $cliente->localidad,
			'municipio'				=> $cliente->municipio,
			'estado'				=> $cliente->estado,
			'pais'					=> $cliente->pais,
			'codigoPostal'			=> $cliente->codigoPostal,
			'telefono'				=> $cliente->telefono,
			'email'					=> $cliente->email,
			'colonia'				=> $cliente->colonia,
			'idCliente'				=> $cliente->idCliente,
			'idDireccion'			=> $cliente->idDireccion,
			'subTotal'				=> $subTotal,
			'iva'					=> $ivas,
			'descuento'				=> 0,
			'descuentoPorcentaje'	=> 0,
			'total'					=> $total,
			'folio'					=> $timbre['folio'],
			'fecha'					=> $this->_fecha_actual,
			'xml'					=> $timbre['xml'],
			'cadenaOriginal'		=> $timbre['cadenaOriginal'],
			'selloSat'				=> $timbre['selloSat'],
			'selloDigital'			=> $timbre['selloDigital'],
			'UUID'					=> $timbre['UUID'],
			'certificadoSat'		=> $timbre['certificado'],
			'cadenaTimbre'			=> $timbre['cadenaTimbre'],	
			'fechaTimbrado'			=> $timbre['fechaTimbrado'],
			'idLicencia'			=> $this->idLicencia,
			'idCotizacion'			=> 0,
			'documento'				=> 'FACTURA',
			'tipoComprobante'		=> "ingreso",
			'serie'					=> $configuracion->serie,
			'condicionesPago'		=> $this->input->post('txtCondiciones'),
			'parcial'				=> 0,
			'divisa'				=> 'Pesos',
			'claveDivisa'			=> 'MXN',
			'tipoCambio'			=> 1,
			'global'				=> '1',
			'idUsuario'				=> $this->_iduser,
			'idEmisor'				=> $configuracion->idEmisor,
			'metodoPago'			=> $this->input->post('metodoPagoTexto'),
			'formaPago'				=> $this->input->post('formaPagoTexto').' '.$this->input->post('txtCuentaPago'),
			'usoCfdi'				=> 'S01, Sin efectos fiscales',
			'inicio'				=> $this->input->post('txtInicio'),
			'fin'					=> $this->input->post('txtFin'),
			'prefacturas'			=> $this->input->post('selectTipoDocumento'),
			'idPac'					=> $configuracion->idPac,
			
			'versionCfdi'			=> '4.0',
			'regimenFiscalCliente'	=> $cliente->claveRegimen.', '.$cliente->regimenFiscal,
			'certificadoEmisor'		=> $configuracion->numeroCertificado,
		);
		
		$this->db->insert('facturas',$data);
		$idFactura = $this->db->insert_id();
		
		#-------------------------------------------------------------------------------------#
		$data=array();
		$data['encriptacion']	=sha1("'".$idFactura.$timbre['fechaTimbrado']."'"); 
		
		$this->db->where('idFactura',$idFactura); 
		$this->db->update('facturas',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS
		#-------------------------------------------------------------------------------------#
		foreach($ventas as $row)
		{
			$producto		= 'VENTA '.obtenerFechaFormato($row->fechaCompra).' '.$row->folio.'('.$row->estacion.')';
		
			$importe		= $row->subTotalIva;
			$importe		= round($importe,decimales);
			
			$importeSinIva	= $row->subTotalSinIva;
			$importeSinIva	= round($importeSinIva,decimales);

			$impuesto		= $importe*($row->ivaPorcentaje/100);
			$impuesto		= round($impuesto,decimales);
			
			$data=array
			(
				'idFactura'				=> $idFactura,
				'idProducto'			=> 0,
				'nombre'				=> $producto,
				'precio'				=> $importe+$importeSinIva,
				'importe'				=> $importe+$importeSinIva,
				'cantidad'				=> 1,
				'descuento'				=> 0,
				'descuentoPorcentaje'	=> 0,
				
				'unidad'				=> 'ACT',
				'claveUnidad'			=> 'ACT',
				'claveProducto'			=> '01010101',
				'claveDescripcion'		=> '',
				'codigoInterno'			=> '',
				
				'claveObjetoImpuesto'	=> '02',
				'objetoImpuesto'		=> 'Si objeto de impuesto',
			);
			
			$this->db->insert('facturas_detalles',$data);
			$idDetalle	= $this->db->insert_id();
			
			$registrar=true;
			
			if($importe>0)
			{
				$data=array
				(
					'idDetalle'				=> $idDetalle,
					'tasa'					=> $row->ivaPorcentaje,
					'importe'				=> $impuesto,
					'impuesto'				=> '002',
					'nombreImpuesto'		=> 'IVA',
					'base'					=>  $importe,
				);

				$this->db->insert('facturas_detalles_impuestos',$data);
				
				$registrar=false;
			}
			
			if($registrar and $importeSinIva>0)
			{
				$data=array
				(
					'idDetalle'				=> $idDetalle,
					'tasa'					=> $row->ivaPorcentaje,
					'importe'				=> 0,
					'impuesto'				=> '002',
					'nombreImpuesto'		=> 'IVA',
					'base'					=>  $importeSinIva,
				);

				$this->db->insert('facturas_detalles_impuestos',$data);
			}
			
		
		}
		
		#-------------------------------------------------------------------------------------#
		
		$tipo					= $this->input->post('selectTipoRango');

		
		$inicio					= $tipo=='Fechas'?$this->input->post('txtInicio'):$this->input->post('txtFolioInicial');
		$fin					= $tipo=='Fechas'?$this->input->post('txtFin'):$this->input->post('txtFolioFinal');
		#$ordenes				= $tipo=='Fechas'?$this->obtenerOrdenesVentaFactura($inicio,$fin):$this->obtenerOrdenesVentaFolios($inicio,$fin);
		
		foreach($ventas as $row)
		{
			$this->db->where('idCotizacion',$row->idCotizacion);
			$this->db->update('cotizaciones',array('idFactura'=>$idFactura));
			
			//AGREGAR LA FACTURA CON LA COTIZACIÓN
			$this->db->insert('rel_factura_cotizacion',array('idFactura'=>$idFactura,'idCotizacion'=>$row->idCotizacion));
			
			
			//BORRAR LAS PREFACTURAS
			$this->db->where('idCotizacion',$row->idCotizacion);
			$this->db->where('pendiente','1');
			$this->db->delete('facturas');
		}
		
		//REGISTRAR LA FACTURA GLOBAL
		$this->registrarGlobal($idFactura);

	}
	
	public function registrarGlobal($idFactura)
	{
		$data=array
		(
			'idFactura'		=> $idFactura,
			'anio'			=> $this->input->post('selectAnio'),
			'mes'			=> $this->input->post('mes'),
			'periodicidad'	=> $this->input->post('periodicidad'),
		);

		$this->db->insert('facturas_global',$data);
	}
	
	/*public function obtenerOrdenesVentaFactura($inicio,$fin)
	{
		$sql=" select a.ordenCompra, a.idCotizacion
		 from cotizaciones as a
		 where date(a.fechaCompra) between '$inicio' and '$fin'
		 and a.idFactura=0
		 and a.cancelada='0'
		 and a.activo='1' ";
		
		return $this->db->query($sql)->result();
	}*/
	
}
?>
