<?php
class Venta_modelo extends CI_Model
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
		$sql="select date_sub('".date('Y-m-d H:i:s')."', interval 1 minute) as fechaActual";
		
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
		$subtotal				= $this->input->post('txtSubTotal');
		$iva					= $this->input->post('txtIvaTotal');
		$total					= $this->input->post('txtTotal');
		$descuento				= 0;#$this->input->post('descuento');
		$idCliente				= $this->input->post('txtIdCliente');
		$pago					= strlen($this->input->post('txtPago'))>0?$this->input->post('txtPago'):0;
		$idTienda				= $this->input->post('txtIdTienda');
		$idSucursal				= $this->input->post('txtIdSucursal');
		
		
		$subTotalProductos		= $this->revisarProductosVenta();
		
		
		if($total==0 or strlen($total)==0 or $idCliente==0 or strlen($idCliente)==0 or strlen($subtotal)==0 or strlen($subtotal)==0)
		{
			return array('0','Revise que los importes sean correctos');
		}
		
		if(($subTotalProductos-$subtotal)>0.5)
		{
			return array('0','Revise que los importes sean correctos');
		}
		
		if(!$this->configuracion->comprobarEstacionLicencia($this->idLicencia,$this->idEstacion))
		{
			return array('0','Error en el registro, revise la configuración de la licencia y la estación');
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
		
		$cliente				= $this->clientes->obtenerCliente($idCliente);
		
		/*if(strlen($cliente->rfc)<12 or strlen($cliente->razonSocial) <3 )
		{
			return array("0","El cliente no tiene los datos fiscales necesarios para crear la factura");
		}*/
		
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
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
			
			//'fechaCompra'		=> $this->input->post('txtFechaVenta'),
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
		
		$data['idFormaSat']		= $this->input->post('selectFormaPagoSat');
		$data['idMetodo']		= $this->input->post('selectMetodoPago');
		$data['idUso']			= $this->input->post('selectUsoCfdi');
		$data['idEmisor']		= $this->input->post('selectEmisores');
		
		$data	= procesarArreglo($data);
		$this->db->insert('cotizaciones',$data);
		$idCotizacion	=$this->db->insert_id();
		
		#$this->temporal->registrarDatosTemporal($idCotizacion,'registrar','ventas'); //REGISTRAR LA TABLA TEMPORAL
		
		#$this->temporal->registrarMovimiento($idCotizacion,'ventas'); //REGISTRAR LA TABLA TEMPORAL
		
		$this->procesarProductosVenta($idCotizacion,$idTienda);
	
		if($this->resultado=="1")
		{
			if($pago>0)
			{
				//EL PAGO NO ESTA CANCELADO!!
				$this->realizarPagoVenta($idCotizacion,$folio,$pago,$total,$idCliente,$idForma,$data['iva'],$idCuenta); //Realizar el pago de la venta
				
				$this->configuracion->registrarBitacora('Registrar cobro venta','Ventas','Orden: '.$venta); //Registrar bitácora
			}
			
			if($pago<$total)
			{
				#$this->realizarPagoProgramado($idCotizacion,$folio,$idCliente,$pago,$total,$data['iva']); //Pago programado
			}
	
			$this->configuracion->registrarBitacora('Registrar venta','Ventas',$this->clientes->obtenerClienteEmpresa($idCliente).' Orden: '.$venta); //Registrar bitácora
			
			//REGISTRAR EL CFDI DE LA VENTA
			$this->registrarCfdi($idCotizacion,$cliente,$divisa);
		
			if($idSucursal>0)
			{
				$this->tiendas->registrarTraspasoSucursal($idCotizacion);
			}
		}

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
			
			return array('1',$idCotizacion,$this->idFactura);
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
			'folio'					=> 0,
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
			'documento'				=> 'PREFACTURA',
			'tipoComprobante'		=> 'ingreso',
			'serie'					=> $configuracion->serie,
			'condicionesPago'		=> $this->input->post('txtCondicionesPago'),
			'parcial'				=> '0',
			'observaciones'			=> '',
			'divisa'				=> $divisa->nombre,
			'claveDivisa'			=> $divisa->clave,
			'tipoCambio'			=> $divisa->tipoCambio,
			'idEmisor'				=> $this->input->post('selectEmisores'),
			'metodoPago'			=> $this->input->post('metodoPagoTexto'),
			'formaPago'				=> trim($this->input->post('formaPagoTexto')),
			'usoCfdi'				=> $this->input->post('usoCfdiTexto'),
			'version'				=> '1',
			'pendiente'				=> '1',
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
		
		/*$data=array
		(
			'idCotizacion'		=> $cotizacion->idCotizacion,
			'idFactura'			=> $idFactura,
			'porcentaje'		=> 100,
		);
		
		$this->db->insert('rel_factura_cotizacion',$data);*/
		
		#GUARDAR EL DETALLE DE PRODUCTOS
		#-------------------------------------------------------------------------------------#
		
		#$productosParcial	= $_POST['productos'];
		$i					= 1;
		
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

			$pedimento		= $row->anio.'  '.$row->aduana.'  '.$row->patente.'  '.$row->digitos;

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
			);

			if(strlen($row->anio)==2 and strlen($row->aduana)==2 and strlen($row->patente)==4 and strlen($row->digitos)==7 and strlen($row->fecha)==10)
			{
				$data['fecha']		= $row->fecha;
				$data['pedimento']	= $pedimento;
			}
			
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
			
			$i++;
			
		}
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

					'precio1' 				=> $this->input->post('txtPrecio1'.$i),
				);
				
				/*if($rebanada=='si')
				{
					$rebanadas				= (1/$numeroRebanadas) * $cantidad;
					
					$data['rebanadas']		= $rebanadas;
					
					$cantidad				= $rebanadas; //DESCONTAR POR PORCION
				}*/
				
				if($this->input->post('selectMostrador')==0)
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
					#if($servicio==0 and sistemaActivo!='pinata')
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
	

	public function obtenerConfiguracion()
	{
		$sql="select * from configuracion
		where idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row();
	}

	
	public function registrarCfdi($idCotizacion=0,$cliente=null,$divisa=null)
	{
		$this->load->helper('sat');
		$this->load->helper('cfdi');
		
		$idEmisor				= $this->input->post('selectEmisores');
		$configuracion			= $this->facturacion->obtenerEmisor($idEmisor);
		$cotizacion				= $this->facturacion->obtenerCotizacion($idCotizacion);
		$comprobante			= 'ingreso';
		$data					= array();
		$productos				= $this->facturacion->obtenerProductosCotizacion($cotizacion->idCotizacion);
		$folio					= 0;
		$sello					= "";
		$certificado			= "";
		$ficheroXML				= '';

		$this->timbrarFactor($ficheroXML,$folio,$sello,'',$cotizacion,$cliente,$configuracion,$productos,$divisa);
	
		return $this->idFactura;
	}
	
	public function timbrarFactor($ficheroXML,$folio,$sello,$cadena,$cotizacion,$cliente,$configuracion,$productos,$divisa)
	{
		$data['xml']			= '';
		$data['folio']			= $folio;
		$data['cadenaTimbre']	= '';
		$data['cadenaOriginal']	= $cadena;
		$data['selloDigital']	= $sello;
		$data['UUID']			= '';
		$data['fechaTimbrado']	= '';
		$data['selloSat']		= '';
		$data['certificado']	= '';
		
		$this->agregarFactura($data,$cotizacion,$cliente,$configuracion,$productos,$divisa);
	}
}
