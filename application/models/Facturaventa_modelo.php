<?php
class Facturaventa_modelo extends CI_Model
{
	protected $_fecha_actual;
	protected $_table;
	protected $idLicencia;
	protected $resultado;
	protected $_user_id;
	protected $fecha;
	protected $idFactura;
	protected $idEstacion;

	function __construct()
	{
		parent::__construct();

        $this->_user_id 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');

		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->_fecha_actual 	= mdate($datestring,now());
		$this->fecha 			= date('Y-m-d');
		$this->resultado		= "1";
		$this->idFactura		= 0;

		if($this->session->userdata('usuarioSesion'))
		{
			$this->idEstacion		= $this->session->userdata('idEstacion'); 			
		}
		else
		{
			$this->idEstacion		= get_cookie('idEstacion'.$this->session->userdata('idCookie')); 
		}
		
		
		$this->cambiarFechaActual();
	}
	
	public function cambiarFechaActual()
	{
		$sql="select date_sub('".date('Y-m-d H:i:s')."', interval 2 minute) as fechaActual";
		
		$this->_fecha_actual=$this->db->query($sql)->row()->fechaActual;
	}
	
	public function revisarProductosVenta()
	{
		$numeroProductos		= $this->input->post('txtNumeroProductos');
		$subTotal				= 0;
		
		for($i=0;$i<=$numeroProductos;$i++)
		{
			#$descuento	= explode('|',$descuentos[$i]);
			$idProducto			= $this->input->post('txtIdProducto'.$i);
			
			if($idProducto>0)
			{
				
				$cantidad			= $this->input->post('txtCantidadProducto'.$i);
				$descuentos			= $this->input->post('txtDescuentoProducto'.$i);				
				$impuestos			= $this->input->post('txtTotalImpuesto'.$i);
				$total				= $this->input->post('txtTotalProducto'.$i);
				$precio				= $total/$cantidad;
				$impuesto			= $impuestos/$cantidad;
				$descuento			= $descuentos/$cantidad;
				
				$importe			=  $this->input->post('txtTotalProducto'.$i)-$impuestos;
				
				$subTotal+=$importe;
			
			}
		}
		
		return $subTotal;
	}
	
	public function registrarVenta() 
	{
		if(!$this->facturacion->comprobarFoliosDisponibles())
		{
			return array('0','Los folios se han terminado, por favor consulte con el administrador','','','');
		}
		
		$subtotal				= $this->input->post('txtSubTotal');
		$iva					= $this->input->post('txtIvaTotal');
		$total					= $this->input->post('txtTotal');
		$descuento				= 0;#$this->input->post('descuento');
		$idCliente				= $this->input->post('txtIdCliente');
		$idDireccion			= $this->input->post('selectDireccionesCfdi');
		
		$pago					= strlen($this->input->post('txtPago'))>0?$this->input->post('txtPago'):0;
		$idTienda				= $this->input->post('txtIdTienda');
		$idSucursal				= $this->input->post('txtIdSucursal');
		
		$subTotalProductos		= $this->revisarProductosVenta();
		
		if($total==0 or strlen($total)==0 or $idCliente==0 or strlen($idCliente)==0 or strlen($subtotal)==0 or strlen($subtotal)==0)
		{
			return array('0','Revise que los importes sean correctos','','');
		}
		
		if(($subTotalProductos-$subtotal)>0.5)
		{
			return array('0','Revise que los importes sean correctos','','');
		}
		
		if(!$this->configuracion->comprobarEstacionLicencia($this->idLicencia,$this->idEstacion))
		{
			return array('0','Error en el registro, revise la configuración de la licencia y la estación','','');
		}
		
		$formas					= $this->input->post('selectFormas');
		$formas					= explode('|',$formas);
		$idForma				= isset($formas[0])?$formas[0]:1;
		$intereses				= isset($formas[1])?$formas[1]:0;
		$idCuenta				= isset($formas[2])?$formas[2]:1;
		
		$folio					= $this->clientes->obtenerFolio(1);
		$folioConta				= $this->clientes->obtenerFolioConta();
		#$divisa					= $this->obtenerDivisa($this->input->post('selectDivisas'));
		$divisa					= $this->clientes->obtenerDivisa(1);

		

		#--------------------------ORDEN DE VENTAS----------------------------#
		$serie					= "COT-".date('Y-m-d').'-'.$folio;
		$venta					= "VEN-".$folio;
		#---------------------------------------------------------------------#

		$comentarios			= "";
		$formaPago				= $this->configuracion->obtenerForma($idForma);
		$formaPago				= $formaPago!=null?$formaPago->nombre:'';
		
		#$cliente				= $this->clientes->obtenerCliente($idCliente);
		$cliente				= $this->clientes->obtenerDireccionesEditar($idDireccion);
		
		if(strlen($cliente->rfc)<12 or strlen($cliente->razonSocial) <3 or strlen($cliente->codigoPostal) !=5)
		#if(strlen($cliente->rfc)<12 or strlen($cliente->empresa) <3 )
		{
			return array("0","El cliente no tiene los datos fiscales necesarios para crear la factura",'','','');
		}
		
		$this->db->trans_start();
		
		$data=array
		(
			'ordenCompra'		=> $venta,
			'idCliente'			=> $idCliente,
			'fecha'				=> $this->_fecha_actual,
			'fechaPedido'		=> $this->_fecha_actual,
			'fechaEntrega'		=> $this->_fecha_actual,
			/*'fechaPedido'		=> $this->input->post('txtFechaVenta'),
			'fechaEntrega'		=> $this->input->post('txtFechaVenta'),*/
			'serie'				=> $serie,
			'estatus'			=> '1',
			#'idUsuario'			=> $this->_user_id,
			
			'idUsuario'			=> $this->input->post('txtIdUsuarioVendedor'),
			
			#'fechaCompra'		=> $this->input->post('txtFechaVenta'),
			'fechaCompra'		=> $this->_fecha_actual,
			'pago'				=> $this->input->post('txtPago'),
			'cambio'			=> $this->input->post('txtCambio'), 
			'descuento'			=> $this->input->post('txtDescuentoTotal'),
			'descuentoPorcentaje'=> $this->input->post('txtDescuentoPorcentaje0'),
			'subTotal'			=> $subtotal,
			'ivaPorcentaje'		=> 16,
			'iva'				=> $this->input->post('txtIvaTotal'),
			'total'				=> $total,
			'folio'				=> $folio,
			'folioConta'		=> $folioConta,
			'idLicencia'		=> $this->idLicencia,
			'comentarios'		=> $comentarios,
			'idDivisa'			=> $divisa->idDivisa,
			'tipoCambio'		=> $divisa->tipoCambio,
			'condicionesPago'	=> $this->input->post('txtCondicionesPago'), 
			
			'facturar'			=> $this->input->post('chkFacturar')=='1'?'1':'0', 
			
			'metodoPago'		=> $formaPago.' '.$this->obtenerDigitosCuenta($this->input->post('cuentasBanco')), 
			'formaPago'			=> $this->input->post('txtFormaPago'), 
			
			'observaciones'		=> $this->input->post('txtObservacionesEnvio'), 
			'diasCredito'		=> $this->input->post('txtDiasCredito'), 
			'idForma'			=> $idForma,
			'idTienda'			=> $idTienda,
			'intereses'			=> $intereses,
			'idEstacion'		=> $this->idEstacion, 
			
			'idRuta'			=> $this->input->post('selectRutas'), 
			'idDireccion'		=> $this->input->post('selectDirecciones'), 
			'idSucursal'		=> $idSucursal, 
			'prefactura'		=> 1, 
			'envio'				=> $this->input->post('selectMostrador'),
			'tipoEnvio'			=> $this->input->post('tipoEnvio'), 
		);
		
		/*$data['idFormaSat']		= $this->input->post('selectFormaPagoSat');
		$data['idMetodo']		= $this->input->post('selectMetodoPago');
		$data['idUso']			= $this->input->post('selectUsoCfdi');
		$data['idEmisor']		= $this->input->post('selectEmisores');*/
		
		$data	= procesarArreglo($data);
		$this->db->insert('cotizaciones',$data);
		$idCotizacion	=$this->db->insert_id();
		
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		$this->temporal->registrarDatosTemporal($idCotizacion,'registrar','ventas'); //REGISTRAR LA TABLA TEMPORAL
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
		$this->procesarProductosVenta($idCotizacion,$idTienda);
	
		if($this->resultado=="1")
		{
			if($pago>0)
			{
				//EL PAGO NO ESTA CANCELADO
				$this->realizarPagoVenta($idCotizacion,$folio,$pago,$total,$idCliente,$idForma,$data['iva'],$idCuenta); //Realizar el pago de la venta
				
				$this->configuracion->registrarBitacora('Registrar cobro venta','Ventas','Orden: '.$venta); //Registrar bitácora
			}
			
			/*if($pago<$total)
			{
				$this->realizarPagoProgramado($idCotizacion,$folio,$idCliente,$pago,$total,$data['iva']); //Pago programado
			}*/
	
			$this->configuracion->registrarBitacora('Registrar venta','Ventas',$this->clientes->obtenerClienteEmpresa($idCliente).' Orden: '.$venta); //Registrar bitácora
			
			//REGISTRAR EL CFDI DE LA VENTA
			$this->registrarCfdi($idCotizacion,$cliente,$divisa);

			if($idSucursal>0 and $this->resultado=='1')
			{
				$this->tiendas->registrarTraspasoSucursal($idCotizacion);
			}		
		}

		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',$this->resultado,'','','');
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',$idCotizacion,$this->idFactura,$cliente->email,strlen($cliente->email)>0?'1':'0');
		}
	}
	
	public function registrarCfdi($idCotizacion,$cliente,$divisa)
	{	
		$this->load->helper('sat');
		$this->load->helper('cfdi');
		
		$idEmisor				= $this->input->post('selectEmisoresVenta');
		$configuracion			= $this->facturacion->obtenerEmisor($idEmisor);
		$cotizacion				= $this->facturacion->obtenerCotizacion($idCotizacion);

		
		$retenciones['importe']	= $this->input->post('retencion');
		$retenciones['tasa']	= $this->input->post('tasa');
		$retenciones['nombre']	= $this->input->post('nombre');
		
		$data	=array();
		
		#$impuestos				= $this->procesarImpuestos($idCotizacion);
		
		$productos	= $this->facturacion->obtenerProductosCotizacion($cotizacion->idCotizacion);

		if(strlen($cliente->rfc)<12 or strlen($cliente->razonSocial) <3 )
		#if(strlen($cliente->rfc)<12 or strlen($cliente->empresa) <3 )
		{
			#$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			#$this->db->trans_complete();
			
			$this->resultado='El cliente no tiene los datos fiscales necesarios para crear la factura';
			
			return false;
		}
		
		$folio	= $this->facturacion->obtenerFolio($idEmisor);
		
		if($folio<1)
		{
			#$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			#$this->db->trans_complete();
			
			#return array('0',"Sin folios suficientes para crear el comprobante",'','','');
			$this->resultado='Sin folios suficientes para crear el comprobante';
			
			return false;
		}
		
		$carpetaUsuario		= carpetaCfdi.$configuracion->rfc.'/';
		$carpetaFolio		= $carpetaUsuario.'folio'.$configuracion->serie.$folio.'/';
		$cfd				= $carpetaFolio.'cfd'.$folio.'.xml';
		
		crearDirectorio($carpetaFolio);
		
		$sello				="";
		$certificado		="";

		$ficheroXML		= xmlCfdVenta($configuracion,$cliente,$productos,$sello,$certificado,$this->_fecha_actual,$folio,$cotizacion,$retenciones,$divisa,1);
		
		guardarArchivoXML($cfd,$ficheroXML);
		
		exec("xsltproc ".carpetaCfdi.'cadenaoriginal4.xslt'." ".$cfd." > ".$carpetaFolio.'cadena.txt'); #Comentado mejor quitarlo jaja
		exec("openssl pkcs8 -inform DER -in ".$carpetaUsuario.$configuracion->llave." -passin pass:".$configuracion->passwordLlave." -out ".$carpetaFolio.'certificado.txt');
		exec("openssl dgst -sha256 -sign ".$carpetaFolio."certificado.txt ".$carpetaFolio."cadena.txt | openssl enc -base64 -A > ".$carpetaFolio.'sello.txt');
		exec("openssl enc -base64 -in ".$carpetaUsuario.$configuracion->certificado." -out ".$carpetaFolio.'certificadoImprimir.txt');
		
		$certificado	= leerFichero($carpetaFolio.'certificadoImprimir.txt',"READ","");
		$certificado 	= QuitarEspaciosXML($certificado,"B");
		$sello			= leerFichero($carpetaFolio.'sello.txt',"READ","");
		$sello 			= QuitarEspaciosXML($sello,"B");
		$cadena			= leerFichero($carpetaFolio.'cadena.txt',"READ","");

		$ficheroXML		= xmlCfdVenta($configuracion,$cliente,$productos,$sello,$certificado,$this->_fecha_actual,$folio,$cotizacion,$retenciones,$divisa,1); 
		
		if(guardarArchivoXML($cfd,$ficheroXML))
		{
			if($configuracion->pac=='4gFactor')
			{
				$this->timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cotizacion,$cliente,$configuracion,$productos,$divisa);
			}
			
			if($configuracion->pac=='finkok')
			{
				$this->timbrarFinkok($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cotizacion,$cliente,$configuracion,$productos,$divisa);
			}
		}
	}
	
	public function timbrarFinkok($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cotizacion,$cliente,$configuracion,$productos,$divisa)
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

			return false;
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
			
			$this->agregarFactura($data,$cotizacion,$cliente,$configuracion,$productos,$divisa);
			
			$this->facturacion->borrarArchivosTemporales($carpetaFolio);
		}
	}
	
	public function timbrarFactor($ficheroXML,$folio,$carpetaFolio,$sello,$cadena,$cotizacion,$cliente,$configuracion,$productos,$divisa)
	{
		$this->load->library('factor');
		
		$timbrado 		= new Factor();
		$respuesta 		= $timbrado->obtenerTimbre($configuracion->usuarioPac, $configuracion->passwordPac, $ficheroXML,$configuracion->url);

		if(!$respuesta['estatus'])
		{
			if(strlen($respuesta['codigoError'])>0)
			{
				$this->registrarError($respuesta['codigoError'],$respuesta['comentarios'],$configuracion->idEmisor);	
			}
			
			$this->resultado	=$respuesta['mensaje'];
			#$this->resultado	= 'Error de servidor SAT';
			return false;
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
			
			$this->session->set_userdata('notificacion',"El cfdi se ha creado correctamente");
			$this->agregarFactura($data,$cotizacion,$cliente,$configuracion,$productos,$divisa);
			
			$this->facturacion->borrarArchivosTemporales($carpetaFolio);
			
		}
	}

	public function agregarFactura($timbre,$cotizacion,$cliente,$configuracion,$productos,$divisa)
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
		}
		
		$total			= $subTotal-$descuentos+$ivas;
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
			
			'subTotal'				=> $subTotal,
			'iva'					=> $ivas,
			'descuento'				=> $descuentos,
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
			'idCotizacion'			=> $cotizacion->idCotizacion,
			'idCliente'				=> $cotizacion->idCliente,
			'documento'				=> 'FACTURA',
			'tipoComprobante'		=> 'ingreso',
			'serie'					=> $configuracion->serie,
			
			'parcial'				=> '0',
			'observaciones'			=> '',
			'divisa'				=> $divisa->nombre,
			'claveDivisa'			=> $divisa->clave,
			'tipoCambio'			=> $divisa->tipoCambio,
			
			'condicionesPago'		=> $this->input->post('condiciones'),
			'idEmisor'				=> $this->input->post('selectEmisoresVenta'),
			'metodoPago'			=> $this->input->post('metodoPagoTexto'),
			'formaPago'				=> trim($this->input->post('formaPagoTexto')),
			'usoCfdi'				=> $this->input->post('usoCfdiTexto'),
			
			'idDireccion'			=> $cliente->idDireccion,
			
			'version'				=> '1',
			
			'idPac'					=> $configuracion->idPac,
			
			'versionCfdi'			=> '4.0',
			'regimenFiscalCliente'	=> $cliente->claveRegimen.', '.$cliente->regimenFiscal,
			'certificadoEmisor'		=> $configuracion->numeroCertificado,
		);
		
		$this->db->insert('facturas',$data);
		$idFactura 			= $this->db->insert_id();
		$this->idFactura	= $idFactura;
		
		#-------------------------------------------------------------------------------------#
		$data=array();
		$data['encriptacion']		= sha1("'".$idFactura.$timbre['fechaTimbrado']."'"); 
		
		$this->db->where('idFactura',$idFactura); 
		$this->db->update('facturas',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS Y ASOCIAR LA FACTURA PARCIAL EN CASO DE SER NECESARIO
		#-------------------------------------------------------------------------------------#
		
		$data=array
		(
			'idCotizacion'		=> $cotizacion->idCotizacion,
			'idFactura'			=> $idFactura,
			'porcentaje'		=> 100,
		);
		
		$this->db->insert('rel_factura_cotizacion',$data);
		
		#GUARDAR EL DETALLE DE PRODUCTOS
		#-------------------------------------------------------------------------------------#
		
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

			$data=array
			(
				'idFactura'				=> $idFactura,
				'idProducto'			=> $row->idProducto,
				'nombre'				=> $row->nombre,
				'precio'				=> $row->precio,
				'importe'				=> $importe,
				'cantidad'				=> $row->cantidad,
				'descuento'				=> $descuento,
				'descuentoPorcentaje'	=> $row->descuentoPorcentaje,
				'unidad'				=> $row->unidad,
				'claveUnidad'			=> $row->claveUnidad,
				'claveProducto'			=> $row->claveProducto,
				'claveDescripcion'		=> $row->claveDescripcion,
				'codigoInterno'			=> $row->codigoInterno,
				
				'claveObjetoImpuesto'	=> '02',
				'objetoImpuesto'		=> 'Si objeto de impuesto',
			);
			
			$this->db->insert('facturas_detalles',$data);
			$idDetalle	= $this->db->insert_id();

			$data=array
			(
				'idDetalle'				=> $idDetalle,
				#'idImpuesto'			=> 0,
				'tasa'					=> $row->tasa,
				'importe'				=> $impuesto,
				'impuesto'				=> $row->claveImpuesto,
				'nombreImpuesto'		=> $row->nombreImpuesto,
				'base'					=>  $importe-$descuento,
				'exento'				=> $row->exento,
			);
			
			$this->db->insert('facturas_detalles_impuestos',$data);
			
		}
	
		
		$data=array
		(
			'idFactura'	=>$idFactura
		);
		
		$this->db->where('idCotizacion',$cotizacion->idCotizacion); 
		$this->db->update('cotizaciones',$data);
	}
	
	public function procesarProductosVenta($idCotizacion,$idTienda)
	{
		$numeroProductos		= $this->input->post('txtNumeroProductos');
		
		for($i=0;$i<=$numeroProductos;$i++)
		{
			#$descuento	= explode('|',$descuentos[$i]);
			$idProducto			= $this->input->post('txtIdProducto'.$i);
			
			if($idProducto>0)
			{
				//REVISAR SI HAY REBANADAS
				$numeroRebanadas	= $this->input->post('txtNumeroRebanadas'.$i);
				$rebanada			= $this->input->post('txtRebanadas'.$i);
				
				$cantidad			= $this->input->post('txtCantidadProducto'.$i);
				$servicio			= $this->input->post('txtServicio'.$i);
				$tipo				= $this->input->post('txtTipoGranja'.$i);
				$descuentos			= $this->input->post('txtDescuentoProducto'.$i);				
				$impuestos			= $this->input->post('txtTotalImpuesto'.$i);
				$total				= $this->input->post('txtTotalProducto'.$i);
				$precio				= $total/$cantidad;
				$impuesto			= $impuestos/$cantidad;
				$descuento			= $descuentos/$cantidad;

				//REVISAR LOS PRECIOS 
				$producto			= $this->obtenerStockProducto($idProducto);
				
				if(!validarPrecioProducto($precio,$producto))
				{
					$this->resultado="Error en el registro, el precio del producto es difente al registrado ";
					return;
				}
				
				$data=array
				(
					'idCotizacion' 			=> $idCotizacion,
					'cantidad' 				=> $cantidad,
					#'precio' 				=> $this->input->post('txtPrecioProducto'.$i)-$impuesto,
					'precio' 				=> $precio-$impuesto+$descuento,
					'importe' 				=> $this->input->post('txtTotalProducto'.$i)-$impuestos,
					'idProduct' 			=> $idProducto,
					'tipo' 					=> $this->input->post('txtPrecioProducto'.$i),
					'nombre' 				=> $this->input->post('txtNombreProducto'.$i),
					'servicio' 				=> $servicio,
					'fechaInicio' 			=> $this->_fecha_actual,
					'fechaVencimiento' 		=> $this->_fecha_actual,
					'notificar' 			=> 0,
					'facturado' 			=> '0',
					
					'descuento' 			=> $this->input->post('txtDescuentoProducto'.$i),
					'descuentoPorcentaje'	=> $this->input->post('txtDescuentoPorcentaje'.$i),
					'plazo'					=> $this->obtenerPlazoProducto($idProducto),
				);
				
				/*if($rebanada=='si')
				{
					$rebanadas				= (1/$numeroRebanadas) * $cantidad;
					
					$data['rebanadas']		= $rebanadas;
					
					$cantidad				= $rebanadas; //DESCONTAR POR PORCION
				}*/
				
				#if($this->input->post('selectMostrador')==0)
				{
					$data['enviado']		= 1;
					$data['entregado']		= 1;
					$data['produccion']		= 1;
				}
				
				/*if(sistemaActivo=='olyess')
				{
					$data['domicilio']		=  $this->input->post('txtDomicilioProducto'.$i);
				}*/

				$this->db->insert('cotiza_productos',$data);
				
				$idProductoCotizacion	= $this->db->insert_id();
				
				$this->registrarImpuestosProducto($idProductoCotizacion,$i);
				#----------------------------------------------------------------------------------------------------------#
				
				#if($this->input->post('selectMostrador')==0)
				{
					if($servicio==0)
					{
						$this->actualizarStockProducto($idProducto,$idProductoCotizacion,$cantidad,$idTienda);	
					}
					
					$this->entregarProductos($idProductoCotizacion,$cantidad);
				}
				
				#----------------------------------------------------------------------------------------------------------#
			}
		}
		
		return $this->resultado; //REVISAR RESUELTADO
	}
	
	public function obtenerPlazoProducto($idProducto)
	{
		$sql="select plazo
		from productos
		where idProducto='$idProducto' ";
		
		$producto=$this->db->query($sql)->row();
		
		return $producto!=null?$producto->plazo:0;
	}
	
	public function entregarProductos($idProductoCotizacion,$cantidad)
	{
		$data=array
		(
			'fecha' 		=>$this->_fecha_actual,
			'cantidad' 		=>$cantidad,
			'entrego' 		=>$this->session->userdata('nombreUsuarioSesion'),
			'idProducto' 	=>$idProductoCotizacion
		);
		
		$this->db->insert('ventas_entrega_detalles',$data);
	}
	
	public function obtenerStockProducto($idProducto,$idTienda=0)
	{
		$sql =" select a.idInventario, a.stock, a.precioA, a.precioB, a.precioC
		from productos_inventarios as a 
		where a.idProducto='$idProducto'
		and a.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function actualizarStockProducto($idProducto,$idProductoCotizacion,$cantidad,$idTienda)
	{
		$producto	= $this->obtenerStockProducto($idProducto,$idTienda);
		
		if($producto->stock<$cantidad)
		{
			$this->resultado	= "No existen suficientes productos para realizar la venta";
			
			return 0;
		}
		else
		{
			$data=array
			(
				'stock' =>$producto->stock-$cantidad
			);

			$this->db->where('idInventario',$producto->idInventario);
			$this->db->update('productos_inventarios',$data);
			
		#----------------------------------------------------------------------------------------------------------#
		}
	}
	
	public function registrarImpuestosProducto($idProducto,$i)
	{
		$data=array
		(
			'idProducto' 		=> $idProducto,
			'idImpuesto' 		=> $this->input->post('txtIdImpuesto'.$i),
			'tasa' 				=> $this->input->post('txtTasaImpuesto'.$i),
			'importe' 			=> $this->input->post('txtTotalImpuesto'.$i),
			'tipo' 				=> $this->input->post('txtTipoImpuesto'.$i),
			'nombre' 			=> $this->input->post('txtImpuesto'.$i),
		);
		
		$this->db->insert('cotiza_productos_impuestos',$data);
	}
	
	public function realizarPagoProgramado($idVenta,$folio,$idCliente,$pago,$total,$iva)
	{
		$idCuenta	=$this->input->post('cuentasBanco');
		#$idCuenta	=$idCuenta==0?1:$idCuenta;
		
		$data = array
		(
			'idVenta'			=> $idVenta,
			#'idCuenta'			=> $idCuenta,
			'idCuenta'			=> 1,
			'idCliente'			=> $idCliente,
			'pago'				=> $total-$pago,
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			'transferencia'		=> '',
			'cheque'			=> '',
			'formaPago'			=> '',
			'fecha'				=> $this->obtenerFechaFinServicio($this->input->post('txtDiasCredito'),'day',$this->input->post('txtFechaVenta')),
			'idLicencia'		=> $this->idLicencia,
			'concepto'			=> 'VEN-'.$folio,
			'producto'			=> 'VEN-'.$folio,
			#'idGasto'			=> 1,
			#'idProducto'		=> 1,
			'iva'				=> $iva>0?16:0,
			'nombreReceptor'	=> '',
			'incluyeIva'		=> 1,
			#'idDepartamento'	=> 1,
			#'idNombre'			=> 1,
			'notificacion'		=> '1',
			'idForma'			=> '4',
			'idUsuario'			=> $this->_user_id,
		);
		
		$this->db->insert('catalogos_ingresos',$data);
	}
	
	public function obtenerFechaFinServicio($valor,$factor,$fecha)
	{
		$sql="select date_add('".$fecha."',interval ".$valor." $factor) as fechaFin";
		
		return $this->db->query($sql)->row()->fechaFin;
	}
	
	public function realizarPagoVenta($idVenta,$folio,$pago,$total,$idCliente,$idForma,$iva=0,$idCuenta=1)
	{
		#$idForma			= $this->input->post('idForma');
		$anticipo			= $pago<$total?'Anticipo ':'';
		$importe			= $pago>=$total?$total:$pago;
		
		$subTotal			= $iva>0?$importe/1.16:$importe;
		
		$data = array
		(
			'idVenta'			=> $idVenta,
			'idCliente'			=> $idCliente,
			#'idCuenta'			=> $this->input->post('cuentasBanco'),
			'idCuenta'			=> $idCuenta,
			
			'pago'				=> $pago>=$total?$total:$pago,
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			'transferencia'		=> $this->input->post('numeroTransferencia'),
			'cheque'			=> $this->input->post('numeroCheque'),
			'formaPago'			=> '',
			'fecha'				=> $this->_fecha_actual,
			'idLicencia'		=> $this->idLicencia,
			'concepto'			=> $anticipo.'VEN-'.$folio,
			'producto'			=> $anticipo.'VEN-'.$folio,
			
			'nombreReceptor'	=> '',
			'incluyeIva'		=> 1,
			'idForma'			=> $idForma,
			'iva'				=> $iva>0?16:0,
			'subTotal'			=> $subTotal,
			'ivaTotal'			=> $importe-$subTotal,
			'idUsuario'			=> $this->_user_id,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_ingresos',$data);
		$idIngreso=$this->db->insert_id();
		
		$this->contabilidad->registrarPolizaIngreso($data['fecha'],$data['producto'],0,$data['pago'],$idIngreso); //REGISTRAR LA PÓLIZA DE INGRESO
	}
	
	public function obtenerDigitosCuenta($idCuenta)
	{
		$idCuenta	=$idCuenta==0?1:$idCuenta;
		
		if($idCuenta>0)
		{
			$sql="select cuenta
			from cuentas
			where idCuenta='$idCuenta' ";
			
			$cuenta		= $this->db->query($sql)->row()->cuenta;
			
			if($cuenta=='Efectivo')return '';
			
			$longitud	=strlen($cuenta);
			return substr($cuenta,$longitud-4,4);
		}
		else
		{
			return '';
		}
	}
}
