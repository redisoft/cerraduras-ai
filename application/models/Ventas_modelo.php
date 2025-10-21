<?php
class Ventas_modelo extends CI_Model
{
	protected $_fecha_actual;
	protected $_table;
	protected $idLicencia;
	protected $resultado;
	protected $_user_id;
	protected $fecha;
	protected $idEstacion;

	function __construct()
	{
		parent::__construct();
		$this->config->load('datatables',TRUE);
		$this->_table 			= $this->config->item('datatables');

        $this->_user_id 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');

		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->_fecha_actual 	= mdate($datestring,now());
		$this->fecha 			= date('Y-m-d');
		$this->resultado		= "1";
		
		if($this->session->userdata('usuarioSesion'))
		{
			$this->idEstacion		= $this->session->userdata('idEstacion'); 			
		}
		else
		{
			$this->idEstacion		= get_cookie('idEstacion'.$this->session->userdata('idCookie')); 
		}
	}

	#PARA LAS  VENTAS QUE SON DIRECTAS
	public function obtenerFolio()
	{
		$sql="select coalesce(max(folio),0) as folio 
		from  cotizaciones_ ";
		
		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function realizarPagoVenta($idVenta,$folio,$pago,$total,$idCliente,$idForma)
	{
		#$idForma			= $this->input->post('idForma');
		$anticipo			= $pago<$total?'Anticipo ':'';
		
		$data = array
		(
			'idVenta'			=> $idVenta,
			'idCliente'			=> $idCliente,
			'idCuenta'			=> $this->input->post('cuentasBanco'),
			'pago'				=> $pago>=$total?$total:$pago,
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			'transferencia'		=> $this->input->post('numeroTransferencia'),
			'cheque'			=> $this->input->post('numeroCheque'),
			'formaPago'			=> '',
			'fecha'				=> $this->_fecha_actual,
			'idLicencia'		=> $this->idLicencia,
			'concepto'			=> $anticipo.'VEN-'.$folio,
			'producto'			=> $anticipo.'VEN-'.$folio,
			#'iva'				=> $this->session->userdata('iva'),
			'nombreReceptor'	=> '',
			'incluyeIva'		=> 1,
			'idForma'			=> $idForma,
			'idUsuario'			=> $this->_user_id,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_ingresos_',$data);
		$idIngreso=$this->db->insert_id();

	}
	
	public function obtenerDivisa($idDivisa)
	{
		$sql="select * from divisas
		where idDivisa='$idDivisa' ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerVentaServicio($idCotizacion) 
	{
		$sql=" select * from cotizaciones
		where idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->row_array();
	}
	
	public function obtenerVenta($idCotizacion)
	{
		#(select concat(c.nombre, ' ',c.apellidoPaterno, ' ',c.apellidoMaterno) from usuarios as c where c.idUsuario=a.idUsuario) as usuario
		$sql=" select a.*, b.empresa, b.idCliente,
		(select concat(c.vendedor) from usuarios as c where c.idUsuario=a.idUsuario) as usuario,
		(select c.nombre from configuracion_estaciones as c where c.idEstacion=a.idEstacion) as estacion
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where idCotizacion='$idCotizacion' ";

		return  $this->db->query($sql)->row();
	}
	
	public function obtenerServiciosVenta($idCotizacion) 
	{
		$sql=" select * from cotiza_productos_
		where idCotizacion='$idCotizacion'
		and servicio='1'
		and plazo>0
		order by plazo desc ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerPeriodoServicio($idProducto) 
	{
		$sql=" select a.idProducto, b.nombre, b.factor, b.valor
		from productos as a
		inner join produccion_periodos as b
		on a.idPeriodo=b.idPeriodo
		where a.idProducto='$idProducto'
		and b.nombre!='NA' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function procesarVentaServicio($idCotizacionPadre) 
	{
		$servicios	= $this->obtenerServiciosVenta($idCotizacionPadre);
		
		if($servicios!=null)
		{
			$limite		= $servicios[0]['plazo'];
			$venta		= $this->obtenerVentaServicio($idCotizacionPadre);
			$fechaBase	= $venta['fechaCompra']; //FECHA BASE PARA CALCULAR LOS PERIODOS POSTERIORES
			
			for($i=1;$i<=$limite;$i++)
			{
				foreach($servicios as $row)
				{
					if($i<=$row['plazo'])
					{
						$periodo					= $this->obtenerPeriodoServicio($row['idProduct']);
						
						if($periodo!=null)
						{
							$fechaCompra			= $this->obtenerFechaFinServicio($i*$periodo->valor,$periodo->factor,$fechaBase);
							
							//VENTA
							$subTotal				= $row['importe'];
							$descuento				= $venta['descuentoPorcentaje']>0?$subTotal*($venta['descuentoPorcentaje']/100):0;
							$suma					= $subTotal-$descuento;
							$iva					= $venta['ivaPorcentaje']>0?$suma*$venta['ivaPorcentaje']/100:0;
	
							$venta['idCotizacion']	= 0;
							$venta['pago']			= 0;
							$venta['cambio']		= 0;
							$venta['idCotizacionPadre']	= $idCotizacionPadre;
							$venta['pendiente']		= '1';
							$venta['fecha']			= $fechaCompra;
							$venta['fechaPedido']	= $fechaCompra;
							$venta['fechaEntrega']	= $fechaCompra;
							$venta['fechaCompra']	= $fechaCompra;
							
							$venta['subTotal']		= $subTotal;
							$venta['descuento']		= $descuento;
							$venta['iva']			= $iva;
							$venta['total']			= $suma+$iva;
							
							$this->db->insert('cotizaciones',$venta);
							$idCotizacion	= $this->db->insert_id();
							
							//DETALLE DE VENTA
							$row['idProducto']		= 0;
							$row['idCotizacion']	= $idCotizacion;
							$row['fechaInicio']		= $fechaCompra;
							$row['fechaVencimiento']= $fechaCompra;
							
							$this->db->insert('cotiza_productos_',$row);
						} //IF PERIODO
					} //IF PLAZO
				} //FOREACH
			} //FOR
		} //IF
	}
	
	public function registrarVenta() 
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$idForma				= $this->input->post('selectFormas');
		$folio					= $this->obtenerFolio();
		$divisa					= $this->obtenerDivisa($this->input->post('selectDivisas'));

		$subtotal				= $this->input->post('txtSubTotal');
		$iva					= $this->input->post('txtIvaTotal');
		$total					= $this->input->post('txtTotal');
		$descuento				= 0;#$this->input->post('descuento');
		$idCliente				= $this->input->post('txtIdCliente');
		$pago					= $this->input->post('txtPago');
		$idTienda				= $this->input->post('txtIdTienda');

		#--------------------------ORDEN DE VENTAS----------------------------#
		$serie					= "COT-".date('Y-m-d').'-'.$folio;
		$venta					= "VEN-".$folio;
		#---------------------------------------------------------------------#

		$comentarios			= "";
		$formaPago				= $this->configuracion->obtenerForma($idForma);
		$formaPago				= $formaPago!=null?$formaPago->nombre:'';
		
		$data=array
		(
			'ordenCompra'		=> $venta,
			'idCliente'			=> $idCliente,
			'fecha'				=> $this->_fecha_actual,
			'fechaPedido'		=> $this->input->post('txtFechaVenta'),
			
			'fechaEntrega'		=> strlen($this->input->post('selectMostrador'))==1?$this->input->post('txtFechaEntrega').' '.date('H:i:s'):$this->input->post('txtFechaVenta'),
			
			'serie'				=> $serie,
			'estatus'			=> '1',
			'idUsuario'			=> $this->_user_id,
			'fechaCompra'		=> $this->input->post('txtFechaVenta'),
			'pago'				=> $this->input->post('txtPago'),
			'cambio'			=> $this->input->post('txtCambio'), 
			'descuento'			=> $this->input->post('txtDescuentoTotal'),
			'descuentoPorcentaje'=> $this->input->post('txtDescuentoPorcentaje0'),
			'subTotal'			=> $subtotal,
			#'ivaPorcentaje'		=> $this->input->post('selectIva'),
			'iva'				=> $this->input->post('txtIvaTotal'),
			'total'				=> $total,
			'folio'				=> $folio,
			'idLicencia'		=> $this->idLicencia,
			'comentarios'		=> $comentarios,
			'idDivisa'			=> $divisa->idDivisa,
			'tipoCambio'		=> $divisa->tipoCambio,
			'condicionesPago'	=> $this->input->post('txtCondicionesPago'), 
			'formaPago'			=> $this->input->post('txtFormaPago'), 
			'facturar'			=> $this->input->post('chkFacturar')=='1'?'1':'0', 
			'metodoPago'		=> $formaPago.' '.$this->obtenerDigitosCuenta($this->input->post('cuentasBanco')), 
			'observaciones'		=> $this->input->post('txtObservacionesVenta'), 
			'diasCredito'		=> $this->input->post('txtDiasCredito'), 
			'idForma'			=> $idForma,
			'idTienda'			=> $idTienda,
			
			'idRuta'			=> $this->input->post('selectRutas'), 
			'envio'				=> $this->input->post('selectMostrador'), 
			'idEstacion'		=> $this->idEstacion, 
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('cotizaciones_',$data);
		$idCotizacion	=$this->db->insert_id();
		
		#$this->temporal->registrarDatosTemporal($idCotizacion,'registrar','ventas'); //REGISTRAR LA TABLA TEMPORAL
		#$this->temporal->registrarMovimiento($idCotizacion,'ventas'); //REGISTRAR LA TABLA TEMPORAL
		
		$this->procesarProductosVenta($idCotizacion,$idTienda);

		if($pago>0)
		{
			$this->realizarPagoVenta($idCotizacion,$folio,$pago,$total,$idCliente,$idForma); //Realizar el pago de la venta
			
			#$this->configuracion->registrarBitacora('Registrar cobro venta','Ventas','Orden: '.$venta); //Registrar bitácora
		}
		

		
		#$this->configuracion->registrarBitacora('Registrar venta','Ventas',$this->obtenerClienteEmpresa($idCliente).' Orden: '.$venta); //Registrar bitácora
		
		//PROCESAR LAS VENTAS DE SERVICIOS
		
		#$this->procesarVentaServicio($idCotizacion);

		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array(0=>'0',1=>$this->resultado);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array(0=>'1',1=>$idCotizacion);
		}
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
	
	public function obtenerDiasCredito($idCliente)
	{
		$sql="select limiteCredito
		from clientes
		where idCliente='$idCliente'";
		
		return $this->db->query($sql)->row()->limiteCredito;
	}

	public function obtenerPlazoProducto($idProducto)
	{
		$sql="select plazo
		from productos
		where idProducto='$idProducto' ";
		
		$producto=$this->db->query($sql)->row();
		
		return $producto!=null?$producto->plazo:0;
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
				$cantidad					= $this->input->post('txtCantidadProducto'.$i);
				$servicio					= $this->input->post('txtServicio'.$i);
				$tipo						= $this->input->post('txtTipoGranja'.$i);
				
				$impuestos					= $this->input->post('txtTotalImpuesto'.$i);
				$impuesto					= $impuestos/$cantidad;
				
				$data=array
				(
					'idCotizacion' 			=> $idCotizacion,
					'cantidad' 				=> $cantidad,
					'precio' 				=> $this->input->post('txtPrecioProducto'.$i)-$impuesto,
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
				
				if($this->input->post('selectMostrador')==0)
				{
					$data['enviado']		= 1;
					$data['entregado']		= 1;
					$data['produccion']		= 1;
				}

				$this->db->insert('cotiza_productos_',$data);
				
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
		
		$this->db->insert('cotiza_productos_impuestos_',$data);
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
		
		$this->db->insert('ventas_entrega_detalles_',$data);
	}
	

	public function obtenerStockProducto($idProducto,$idTienda=0)
	{
		$sql =" select ".($idTienda==0?"a.stock":" (select coalesce(sum(d.cantidad),0) from tiendas_productos as d where d.idProducto=a.idProducto and d.idTienda='$idTienda') as stock ").", a.idProducto
		from productos as a
		where a.idProducto='$idProducto' ";
		
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
			if($idTienda==0)
			{
				$data=array
				(
					'stock' =>$producto->stock-$cantidad
				);
				
				/*$this->db->where('idProducto',$producto->idProducto);
				$this->db->update('produccion_productos',$data);*/
				
				$this->db->where('idProducto',$idProducto);
				$this->db->update('productos',$data);
			}
			
			if($idTienda>0)
			{
				$data=array
				(
					'cantidad' =>$producto->stock-$cantidad
				);
				
				$this->db->where('idProducto',$producto->idProducto);
				$this->db->where('idTienda',$idTienda);
				$this->db->update('tiendas_productos',$data);
			}
			
		#----------------------------------------------------------------------------------------------------------#
		}
	}

	public function obtenerPeriodo($idPeriodo)
	{
		$sql="select * from produccion_periodos
		where idPeriodo='$idPeriodo' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerFechaFinServicio($valor,$factor,$fecha)
	{
		$sql="select date_add('".$fecha."',interval ".$valor." $factor) as fechaFin";
		
		return $this->db->query($sql)->row()->fechaFin;
	}
	
	public function obtenerProductosVenta($idCotizacion)
	{
		$sql=" select a.*, b.nombre as producto,
		c.nombre as periodo, b.codigoInterno, c.idPeriodo,
		(select d.descripcion from unidades as d where d.idUnidad=b.idUnidad) as unidad,
		
		(select coalesce(sum(d.importe),0) from cotiza_productos_impuestos_ as d where d.idProducto=a.idProducto) as impuestos
		
		from cotiza_productos_ as a
		inner join productos as b
		on a.idProduct=b.idProducto
		inner join produccion_periodos as c
		on b.idPeriodo=c.idPeriodo
		where a.idCotizacion='$idCotizacion' ";

		return  $this->db->query($sql)->result();
	}
	
	public function obtenerCotizacionVenta($idCotizacion)
	{
		$sql="select a.*, b.empresa, b.idCliente,
		c.nombre as contacto,
		c.telefono, c.email
		from cotizaciones_ as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join clientes_contactos as c
		on c.idCliente=b.idCliente
		where idCotizacion='$idCotizacion' ";

		return  $this->db->query($sql)->row();
	}
	
	public function sumarVentasDia()
	{
		$sql="select coalesce(sum(total),0) as total 
		from  cotizaciones_
		where date(fechaCompra)='$this->fecha' ";
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function revisarVentaDuplicada($venta,$productos)
	{
		$numeroProductos=0;
		
		$sql=" select count(idCotizacion) as numero
		from cotizaciones
		where total=$venta->total
		and fechaCompra='$venta->fechaCompra'
		and idCliente=$venta->idCliente
		and idEstacion=$this->idEstacion
		and idLicencia=$this->idLicencia
		and activo='1' 
		and cancelada='0' ";
		
		$numeroVentas	= $this->db->query($sql)->row()->numero;
		
		if($productos!=null)
		{
			$sql=" select count(idProducto) as numero
			from cotiza_productos
			where idCotizacion!=$venta->idCotizacion
			and fechaInicio='".$venta->fecha."' ";
			
			#and fechaInicio='".$productos[0]->fechaInicio."'
			
			$numeroProductos	= $this->db->query($sql)->row()->numero;
		}
		
		if($numeroVentas>1 and $numeroProductos==count($productos))
		{
			return false;
		}
		else
		{
			return true;
		}
		
	}
	
	
}
