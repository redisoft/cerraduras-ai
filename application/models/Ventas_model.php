<?php
class Ventas_model extends CI_Model
{
	protected $_fecha_actual;
	protected $_table;
	protected $idLicencia;
	protected $_user_name;
	protected $idUsuario;
	protected $fechaCorta;
	protected $idTienda;
	protected $hora;
	protected $idEstacion;

	function __construct()
	{
		parent::__construct();
		
		$this->config->load('datatables',TRUE);
		$this->_table 			= $this->config->item('datatables');
        $this->idUsuario 		= $this->session->userdata('id');
		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->_fecha_actual 	= mdate($datestring,now());
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->_user_name 		= $this->session->userdata('nombreUsuarioSesion');
		$this->fechaCorta 		= date('Y-m-d');
		$this->hora 			= date('H:i:s');
		$this->idTienda 		= 0;
		
		if($this->session->userdata('usuarioSesion'))
		{
			$this->idEstacion		= $this->session->userdata('idEstacion'); 			
		}
		else
		{
			$this->idEstacion		= get_cookie('idEstacion'.$this->session->userdata('idCookie')); 
		}
	}

	public function obtenerEntregas($idProducto)
	{
		if(sistemaActivo=='olyess')
		{
			$sql=" select a.*, b.rebanadas,
			c.rebanadas as rebanadasPastel
			from ventas_entrega_detalles as a
			inner join cotiza_productos as b
			on a.idProducto=b.idProducto
			inner join productos as c
			on c.idProducto=b.idProduct
			where a.idProducto='$idProducto' ";
		}
		else
		{
			$sql="select * from ventas_entrega_detalles where idProducto='$idProducto'";
		}
		

		return $this->db->query($sql)->result();
	}
	
	public function obtenerProductoEntrega($idEntrega)
	{
		$sql=" select a.cantidad,a.idEntrega, b.cantidad as cantidadTotal,
		
		c.nombre as producto, c.idProducto, b.idProducto as idProductoCotizacion
		from ventas_entrega_detalles as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		inner join productos as c
		on c.idProducto=b.idProduct
		where a.idEntrega='$idEntrega' ";

		return $this->db->query($sql)->row();
	}
	
	
	public function obtenerTotalProducto($idProducto)
	{
		if(sistemaActivo=='olyess')
		{
			$sql=" select cantidad, rebanadas 
			from cotiza_productos
			where idProducto='$idProducto'";
			
			return $this->db->query($sql)->row();
		}
		else
		{
			$sql=" select cantidad
			from cotiza_productos
			where idProducto='$idProducto'";
			
			return $this->db->query($sql)->row()->cantidad;
		}
	}
	
	
	public function obtenerStockProducto($idProducto)
	{
		$sql="select stock, servicio, nombre
		from productos
		where idProducto='$idProducto'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerProductoEntregado($idProducto)
	{
		$sql="select coalesce(sum(cantidad),0) as cantidad
		from ventas_entrega_detalles
		where idProducto='$idProducto'";
		
		return $this->db->query($sql)->row()->cantidad;
	}
	
	public function entregarProductos()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$cantidad		= $this->input->post('cantidad');
		$entrego		= $this->input->post('entrego');
		$idProducto		= $this->input->post('idProducto');
		$idProductoCaja	= $this->input->post('idProductoCaja');
		$total			= $this->obtenerTotalProducto($idProducto);
		
		$producto		= $this->inventarioProductos->obtenerProductoStock($idProductoCaja);
		
		if(sistemaActivo=='olyess')
		{
			$detalle	= $this->obtenerTotalProducto($idProducto);
			
			if($detalle->rebanadas>0)
			{
				$total			= $detalle->rebanadas;
				
				$rebanadas		= 1/$producto->rebanadas;
				
				$cantidad		= $cantidad*$rebanadas;
				
			}
			else
			{
				$total			= $detalle->cantidad;
			}
		}
		else
		{
			$total			= $this->obtenerTotalProducto($idProducto);
		}
		
		#---------------------------------------------------------------------------#

		$cantidadEntregada	= $this->obtenerProductoEntregado($idProducto);
		$totalEntregado		= round($cantidadEntregada+$cantidad,decimales);
		$total				= round($total,decimales);
		
		#echo '<br /> Entregada: '.$cantidadEntregada;
		#echo '<br /> Cantidad: '.$cantidad;
		#echo '<br /> Total: '.$total.'<br />';
		#echo '<br /> Total: '.$totalEntregado.'<br />';

		if($totalEntregado>$total)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0','Error al enviar los productos, esta superando la cantidad total');
		}
		
		if($totalEntregado==$total)
		{
			$data=array
			(
				"entregado"	=>1,
				"enviado"	=>1,
			);
			
			$this->db->where('idProducto',$idProducto);
			$this->db->update('cotiza_productos',$data);
		}
		
		#---------------------------------------------------------------------------#
		$data=array
		(
			"fecha"			=> $this->input->post('fecha'),
			"cantidad"		=> $cantidad,
			"entrego"		=> $entrego,
			"idProducto"	=> $idProducto
		);
		
		$this->db->insert("ventas_entrega_detalles",$data);
		
		#echo $cantidadEntregada;
		
		
		
		if($producto->servicio==0)
		{
			if($producto->stock<$cantidad)
			{
				$this->db->trans_rollback(); 
				$this->db->trans_complete();
				
				return array('0','No existen suficientes productos para ser entregados, revise por favor el inventario');
			}
			else
			{
				/*$data=array
				(
					'stock'	=>$producto->stock-$cantidad
				);
				
				$this->db->where('idProducto',$idProductoCaja);
				$this->db->update('productos',$data);*/
				
				$this->inventarioProductos->actualizarStockProducto($idProductoCaja,$cantidad,'restar');
				
				$this->configuracion->registrarBitacora('Entregar producto','Ventas',$producto->nombre.', Orden: '.$this->obtenerVentaProducto($idProducto).', Cantidad: '.number_format($cantidad,decimales)); //Registrar bitácora
			}
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}
	}
	
	public function obtenerVentaProducto($idProducto)
	{
		$sql=" select concat(c.nombre, a.folio) as ordenCompra
		from cotizaciones as a
		inner join cotiza_productos as b
		on a.idCotizacion=b.idCotizacion
		inner join configuracion_estaciones as c
		on a.idEstacion=c.idEstacion
		where b.idProducto='$idProducto' ";
		
		$venta	= $this->db->query($sql)->row();
		
		return $venta!=null?$venta->ordenCompra:'';
	}
	
	public function obtenerVentaOrden($idCotizacion)
	{
		$sql="select ordenCompra
		from cotizaciones 
		where idCotizacion='$idCotizacion' ";
		
		$venta	= $this->db->query($sql)->row();
		
		return $venta!=null?$venta->ordenCompra:'';
	}
	
	public function obtenerCotizacionSerie($idCotizacion)
	{
		$sql="select serie
		from cotizaciones 
		where idCotizacion='$idCotizacion' ";
		
		$venta	= $this->db->query($sql)->row();
		
		return $venta!=null?$venta->serie:'';
	}

	public function obtenerVentas($anio)
	{
		$ventas= array();
		
		for($i=1;$i<=12;$i++)
		{
			$sql="select sum(a.total) as ventasMes
			from cotizaciones as a
			where month(a.fechaCompra)='".$i."'
			and year(a.fechaCompra)='".$anio."'
			and estatus=1 
			and idLicencia='$this->idLicencia'";
			
			$query=$this->db->query($sql);
			
			$query=$query->row();
			
			$ventas[$i]=0;
			
			if($query->ventasMes!=null)
			{
				$ventas[$i]=$query->ventasMes;
			}
		}
		
		return $ventas;
	}
	
	
	public function obtenerProductosFacturados($idCotizacion)
	{
		$i=0;
		
		$sql="select a.cantidad, a.precio,
		a.importe, b.nombre as descripcion
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto
		where a.idCotizacion='$idCotizacion'";
		
		$query=$this->db->query($sql);
		#$productos=$productos->result();
		
		return $query->result();
	}


	public function obtenerRemision($idCotizacion)
	{
		$sql=" select a.*, b.nombre as divisa, b.clave,
		c.clave as usoCfdi, d.clave as formaPagoSat, e.clave as metodoPago,
		concat(f.nombre, ' ', f.apellidoPaterno, ' ',f.apellidoMaterno) as usuario
		from cotizaciones as a
		inner join divisas as b
		on a.idDivisa=b.idDivisa

		left join fac_catalogos_usocfdi as c
		on a.idUso=c.idUso

		left join fac_catalogos_formas as d
		on a.idFormaSat=d.idForma

		left join fac_catalogos_metodos as e
		on a.idMetodo=e.idMetodo

		left join usuarios as f
		on a.idUsuario=f.idUsuario

		where a.idCotizacion='$idCotizacion' ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerCliente($idCliente)
	{
		$sql="select * from clientes
		where idCliente='$idCliente';";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerTotales($idDetalle)
	{
		$sql="select * from cotiza_detalles_venta
		where iddv='$idDetalle'";
		
		$query=$this->db->query($sql);
		
		return($query->row());
	}
	
	public function obtenerProductos($idCotizacion)
	{
		$sql="  select a.precio, a.cantidad, a.importe, b.idLinea,
		b.nombre as descripcion, a.servicio, c.nombre as periodo,
		a.nombre as producto, b.codigoInterno, a.descuento, a.descuentoPorcentaje,
		
		(select d.nombre from fac_catalogos_unidades as d where b.idUnidad=d.idUnidad) as unidad
		
		from cotiza_productos as a
		inner join productos as b
		ON a.idProduct=b.idProducto
		inner join produccion_periodos as c
		on b.idPeriodo=c.idPeriodo
		where a.idCotizacion='$idCotizacion'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerServicioDomicilio($idCotizacion)
	{
		$sql="  select coalesce(sum(a.importe+b.importe),0) as importe
		from cotiza_productos as a
		inner join cotiza_productos_impuestos as b
		on a.idProducto=b.idProducto
		where a.idCotizacion='$idCotizacion'
		and domicilio='1' ";
		
		return $this->db->query($sql)->row()->importe;
	}
	
	#ENVIAR TODOS LOS PRODUCTOS
	#----------------------------------------------------------------------------------------------------#
	
	public function obtenerProductosEnvio($idCotizacion)
	{
		if(sistemaActivo=='olyess')
		{
			$sql=" select ((if(a.rebanadas>0,a.rebanadas,a.cantidad)) - (select coalesce(sum(c.cantidad),0) from  ventas_entrega_detalles as c where c.idProducto=a.idProducto)) as cantidad, a.idProducto, a.idProduct, a.servicio,
			b.idTienda, b.idCotizacion, a.nombre as producto, b.ordenCompra
			
			from cotiza_productos as a
			inner join cotizaciones as b
			on a.idCotizacion=b.idCotizacion
			where a.idCotizacion='$idCotizacion'
			and (select coalesce(sum(c.cantidad),0) from  ventas_entrega_detalles as c where c.idProducto=a.idProducto) < if(a.rebanadas>0,a.rebanadas,a.cantidad) ";
		}
		else
		{
			$sql=" select (a.cantidad - (select coalesce(sum(c.cantidad),0) from  ventas_entrega_detalles as c where c.idProducto=a.idProducto)) as cantidad, a.idProducto, a.idProduct, a.servicio,
			b.idTienda, b.idCotizacion, a.nombre as producto, b.ordenCompra
			
			from cotiza_productos as a
			inner join cotizaciones as b
			on a.idCotizacion=b.idCotizacion
			where a.idCotizacion='$idCotizacion'
			and (select coalesce(sum(c.cantidad),0) from  ventas_entrega_detalles as c where c.idProducto=a.idProducto) < a.cantidad ";
		}
		
		return $this->db->query($sql)->result();
	}
	
	public function enviarTodosProductos($idCotizacion)
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$productos	= $this->obtenerProductosEnvio($idCotizacion);
		
		foreach($productos as $row)
		{
			$data=array
			(
				"fecha"			=> $this->_fecha_actual,
				"cantidad"		=> $row->cantidad, 
				"entrego"		=> $this->_user_name,
				"idProducto"	=> $row->idProducto
			);
			
			$this->db->insert("ventas_entrega_detalles",$data);
			
			$producto	= $this->inventarioProductos->obtenerProductoStock($row->idProduct);
			
			$data=array
			(
				"enviado"	=>1,
			);
			
			$this->db->where('idProducto',$row->idProducto);
			$this->db->update('cotiza_productos',$data);
			
			if($row->servicio==0)
			{
				if($row->cantidad>$producto->stock)
				{
					$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
					$this->db->trans_complete();
				
					return array('0','No existen suficientes productos para ser entregados, revise por favor el inventario');
				}
				
				
				$this->inventarioProductos->actualizarStockProducto($row->idProduct,$row->cantidad,'restar');
				/*if($row->idTienda==0)
				{
					$data=array
					(
						'stock'	=>$producto->stock-$row->cantidad
					);
					
					$this->db->where('idProducto',$row->idProduct);
					$this->db->update('productos',$data);
				}
				else
				{
					$data=array
					(
						'cantidad'	=> $producto->stock-$row->cantidad
					);
					
					$this->db->where('idTienda',$row->idTienda);
					$this->db->where('idProducto',$row->idProduct);
					$this->db->update('tiendas_productos',$data);
				}*/
				
				$this->configuracion->registrarBitacora('Entregar producto','Ventas',$row->producto.', Orden: '.$row->ordenCompra.', Cantidad: '.number_format($row->cantidad,decimales)); //Registrar bitácora
			}
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}
	}
	/*public function obtenerProductosEnvio($idCotizacion)
	{
		$sql="select cantidad, idProducto, idProduct
		from cotiza_productos
		where idCotizacion='$idCotizacion'
		and enviado='0' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function enviarTodosProductos($idCotizacion)
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		foreach($this->obtenerProductosEnvio($idCotizacion) as $query)
		{
			$data=array
			(
				"fecha"			=>$this->_fecha_actual,
				"cantidad"		=>$query->cantidad, 
				"entrego"		=>$this->_user_name,
				"idProducto"	=>$query->idProducto
			);
			
			$this->db->insert("ventas_entrega_detalles",$data);
			
			$sql="select a.*, b.piezas, b.nombre, b.stock 
			from rel_producto_produccion as a
			inner join produccion_productos as b
			on idProductoProduccion=b.idProducto
			where a.idProducto='".$query->idProduct."'
			and b.idLicencia='$this->idLicencia'";
			
			$data=array
			(
				"enviado"	=>1,
			);
			
			$this->db->where('idProducto',$query->idProducto);
			$this->db->update('cotiza_productos',$data);
			
			foreach($this->db->query($sql)->result() as $row)
			{
				$piezasEntregadas=$row->cantidad*$query->cantidad;
				
				if($piezasEntregadas>$row->stock)
				{
					$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
					$this->db->trans_complete();
				
					return "2";
				}
				
				$data=array
				(
					'stock'	=>$row->stock-$piezasEntregadas
				);
				
				$this->db->where('idProducto',$row->idProductoProduccion);
				$this->db->update('produccion_productos',$data);
			}
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return "1";
		}
	}*/
	
	public function contarCotizaciones($criterio,$inicio,$fin,$permiso=0,$idEstacion=0,$permisoEstacion=0)
	{
		$sql=" select a.idCotizacion
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where (a.serie like '%$criterio%'
		or b.empresa like '%$criterio%')
		and a.estatus=0 
		and a.asignada='1'
		and a.idLicencia='$this->idLicencia'
		
		and date(a.fecha) between '$inicio' and '$fin' ";
		
		#and a.idEstacion='$this->idEstacion' 
		
		$sql.=$permiso==0?" and a.idUsuario='$this->idUsuario' ":'';
		
		if($permisoEstacion=='1')
		{
			$sql.=$idEstacion>0?" and a.idEstacion=$idEstacion":'';
		}
		else
		{
			$sql.=" and a.idEstacion='$this->idEstacion' ";
		}
		
		#$sql.=" where idCliente='$idCliente' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerCotizaciones($numero,$limite,$criterio,$inicio,$fin,$orden='desc',$permiso=0,$idEstacion=0,$permisoEstacion=0)
	{
		$sql=" select a.idCotizacion, a.fecha, a.serie, a.diasCredito,
		a.subTotal, a.descuento, a.iva, a.total, a.folio, a.descuentoPorcentaje,
		a.observaciones, b.empresa, a.cancelada, a.ivaPorcentaje,
		(select c.nombre from cotiza_productos as c where c.idCotizacion=a.idCotizacion limit 1) as producto,
		
		(select c.idSeguimiento from seguimiento as c where c.idCotizacion=a.idCotizacion order by c.fecha desc limit 1) as idSeguimiento,
		(select c.nombre from configuracion_estaciones as c where c.idEstacion=a.idEstacion limit 1) as estacion
		
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		
		where (a.serie like '%$criterio%'
		or b.empresa like '%$criterio%' )
		and a.estatus=0
		and a.asignada='1' 
		and date(a.fecha) between '$inicio' and '$fin'
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$permiso==0?" and a.idUsuario='$this->idUsuario' ":'';
		
		if($permisoEstacion=='1')
		{
			$sql.=$idEstacion>0?" and a.idEstacion=$idEstacion":'';
		}
		else
		{
			$sql.=" and a.idEstacion='$this->idEstacion' ";
		}
		
		#
		
		$sql.= " order by a.fecha " .$orden;
			
		$sql.=" limit $limite, $numero";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCotizacionDetalles($idCotizacion) 
	{
		$sql="select idCliente, total, idEstacion, envio
		from cotizaciones
		where idCotizacion='$idCotizacion' ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function convertirOrdenVenta() 
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idCotizacion		= $this->input->post('idCotizacion');
		$prefactura			= $this->input->post('prefactura');
		$cotizacion			= $this->obtenerCotizacionDetalles($idCotizacion);
		$folio				= $this->clientes->obtenerFolio($prefactura,$cotizacion->idEstacion);
		
		$this->load->model("inventarioproductos_modelo","inventarioProductos");
		#$folio				= $this->clientes->obtenerFolio(0);

		if($cotizacion!=null)
		{
			$data=array
			(
				'ordenCompra'		=> 'VEN-'.$folio,
				'estatus'			=> 1,
				'folio'				=> $folio,
				'activo'			=> '1',
				'fechaCompra'		=> $this->_fecha_actual,
				'prefactura'		=> $prefactura,
			);
			
			$this->db->where('idCotizacion',$idCotizacion);
			$this->db->update('cotizaciones',$data);
			
			
			$idCuentaCatalogo	= $this->clientes->obtenerClienteCuentaCatalogo($cotizacion->idCliente);
			//REGISTAR LA PÓLIZA
			if($idCuentaCatalogo>0)
			{
				$this->contabilidad->registrarPolizaCompraVenta($this->_fecha_actual,$data['ordenCompra'],$idCuentaCatalogo,$cotizacion->total,'venta'); 
			}
			
			$this->configuracion->registrarBitacora('Convertir cotización a venta','Cotizaciones','Cotización: '.$this->obtenerCotizacionSerie($idCotizacion).', Venta: '.$this->input->post('orden'),$idCotizacion); //Registrar bitácora
			
			if($cotizacion->envio=='0')
			{
				$productos	= $this->obtenerProductosEnvio($idCotizacion);
		
				foreach($productos as $row)
				{
					$data=array
					(
						"fecha"			=> $this->_fecha_actual,
						"cantidad"		=> $row->cantidad, 
						"entrego"		=> $this->_user_name,
						"idProducto"	=> $row->idProducto
					);

					$this->db->insert("ventas_entrega_detalles",$data);

				
					$producto	= $this->inventarioProductos->obtenerProductoStock($row->idProduct);

					$data=array
					(
						"enviado"	=>1,
					);

					$this->db->where('idProducto',$row->idProducto);
					$this->db->update('cotiza_productos',$data);

					if($row->servicio==0)
					{
						if($row->cantidad>$producto->stock)
						{
							$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
							$this->db->trans_complete();

							return array('0','No existen suficientes productos para ser entregados, revise por favor el inventario');
						}


						$this->inventarioProductos->actualizarStockProducto($row->idProduct,$row->cantidad,'restar');

						$this->configuracion->registrarBitacora('Entregar producto','Ventas',$row->producto.', Orden: '.$row->ordenCompra.', Cantidad: '.number_format($row->cantidad,decimales)); //Registrar bitácora
					}
				}
			}			
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}
	}

	public function obtenerMargenCotizacion($idCotizacion)
	{
		$sql=" select margen
		from cotizaciones
		where idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->row()->margen;
	}
	
	
	public function establecerMargen($idCotizacion,$margen)
	{
		$data=array
		(
			'margen'		=>$margen,
		);
		
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->update('cotizaciones',$data);
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}
	
	public function obtenerInformacionVentas($idProducto,$inicio='fecha',$fin='fecha',$idTienda=0)
	{
		$sql=" (select a.cantidad, a.fecha as fecha, b.precio, 
		c.ordenCompra, d.empresa
		from ventas_entrega_detalles as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		inner join cotizaciones as c
		on c.idCotizacion=b.idCotizacion
		inner join clientes as d
		on d.idCliente=c.idCliente
		where b.idProduct='$idProducto'
		and c.cancelada='0'
		and c.idLicencia='$this->idLicencia' ";
		
		#$sql.= $idTienda>0?" and c.idTienda='$idTienda' ":'';
		
		$sql.=$inicio!='fecha'?" and a.fecha between '$inicio' and '$fin' ":'';
		
		$sql.=" ) order by fecha desc  ";
		
		/*$sql.=$idLicencia>0?" and c.idLicencia='$idLicencia'":'';
		
		$sql.=" ) union (";
		
		$sql.=" select a.cantidad, a.fecha as fecha, b.precio, 
		c.ordenCompra, d.empresa
		from ventas_entrega_detalles as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		inner join cotizaciones as c
		on c.idCotizacion=b.idCotizacion
		inner join clientes as d
		on d.idCliente=c.idCliente
		inner join facturas as e
		on e.idFactura=c.idFactura
		where b.idProduct='$idProducto' 
		and e.cancelada=0 ";
		
		$sql.=$inicio!='fecha'?" and a.fecha between '$inicio' and '$fin' ":'';
		$sql.=$idLicencia>0?" and c.idLicencia='$idLicencia'":'';
		
		$sql.=" ) order by fecha desc  ";*/
		
		return $this->db->query($sql)->result();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//COTIZACIONES ASIGNADAS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarCotizacionesAsignadas($criterio,$idMotivo=0,$permiso=0)
	{
		$sql=" select a.idCotizacion
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where (a.serie like '%$criterio%'
		or b.empresa like '%$criterio%')
		and a.estatus=0
		and a.asignada='0'
		and a.idLicencia='$this->idLicencia'
		and a.idEstacion='$this->idEstacion' ";
		
		$sql.=$idMotivo>0?" and a.idMotivo = '$idMotivo' ":'';
		$sql.=$permiso==0?" and a.idUsuario='$this->idUsuario' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerCotizacionesAsignadas($numero,$limite,$criterio,$idMotivo=0,$permiso=0)
	{
		$sql=" select a.idCotizacion, a.fecha, a.serie, a.diasCredito,
		a.subTotal, a.descuento, a.iva, a.total, a.folio, 
		a.observaciones, b.empresa, c.nombre as motivos,
		(select c.nombre from cotiza_productos as c where c.idCotizacion=a.idCotizacion limit 1) as producto
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join cotizaciones_motivos as c
		on a.idMotivo=c.idMotivo
		where (a.serie like '%$criterio%'
		or b.empresa like '%$criterio%' )
		and a.estatus=0
		and a.asignada='0'
		and a.idLicencia='$this->idLicencia'
		and a.idEstacion='$this->idEstacion' ";
		
		$sql.=$idMotivo>0?" and a.idMotivo = '$idMotivo' ":'';
		$sql.=$permiso==0?" and a.idUsuario='$this->idUsuario' ":'';
		$sql.=" order by a.fecha desc ";
		$sql.=" limit $limite, $numero";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarCotizacionAsignada()
	{
		$data=array
		(
			'asignada'			=> '0',
			'fechaAsignacion'	=> $this->input->post('txtFechaAsignacion'),
			'idMotivo'			=> $this->input->post('selectMotivos'),
		);
		
		$this->db->where('idCotizacion',$this->input->post('txtIdCotizacion'));
		$this->db->update('cotizaciones',$data);
		
		$this->configuracion->registrarBitacora('Desasignar cotización','Cotizaciones',$this->obtenerCotizacionSerie($this->input->post('txtIdCotizacion')).', Motivo: '.$this->motivos->obtenerMotivoNombre($this->input->post('selectMotivos'))); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	//PROCESADAS
	
	public function obtenerCotizacion($idCotizacion)
	{
		$sql=" select a.*, 
		b.empresa, b.email,
		(select coalesce(sum(d.pago),0) from catalogos_ingresos  as d where d.idVenta=a.idCotizacion and d.idForma!=4 ) as pagado,
		(select d.nombre from usuarios  as d where d.idUsuario=a.idUsuario) as usuario
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where a.idCotizacion='$idCotizacion' ";

		return $this->db->query($sql)->row();
	}
	
	public function contarProcesadas($criterio,$permiso=0)
	{
		$sql=" select a.idCotizacion
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where (a.serie like '%$criterio%'
		or b.empresa like '%$criterio%')
		and a.estatus=1
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and a.pendiente='0' ";
		$sql.=$permiso==0?" and a.idUsuario='$this->idUsuario' ":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerProcesadas($numero,$limite,$criterio,$permiso=0)
	{
		$sql=" select a.idCotizacion, a.fecha, a.serie, a.diasCredito,
		a.subTotal, a.descuento, a.iva, a.total, a.folio,
		a.observaciones, b.empresa, a.ordenCompra,
		(select c.nombre from cotiza_productos as c where c.idCotizacion=a.idCotizacion limit 1) as producto
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		
		where (a.serie like '%$criterio%'
		or b.empresa like '%$criterio%' )
		and a.estatus=1
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and a.pendiente='0' ";
		$sql.=$permiso==0?" and a.idUsuario='$this->idUsuario' ":'';
			
		$sql.=" order by a.fecha desc
		limit $limite, $numero";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerProductosVenta($idCotizacion)
	{
		if(sistemaActivo=='olyess')
		{
			$sql=" select a.cantidad, a.precio, a.importe, a.fechaInicio,
			a.idProducto, b.nombre as descripcion, a.nombre as producto,
			a.idProduct, b.codigoInterno, a.rebanadas, b.rebanadas as rebanadasPastel,
			(select coalesce(sum(c.cantidad),0) from ventas_entrega_detalles as c where c.idProducto=a.idProducto) as entregados
			from cotiza_productos as a
			inner join productos as b
			on(b.idProducto=a.idProduct)
			where a.idCotizacion='$idCotizacion' ";
		}
		else
		{
			$sql=" select a.cantidad, a.precio, a.importe, a.fechaInicio,
			a.idProducto, b.nombre as descripcion, a.nombre as producto,
			a.idProduct, b.codigoInterno,
			(select coalesce(sum(c.cantidad),0) from ventas_entrega_detalles as c where c.idProducto=a.idProducto) as entregados
			from cotiza_productos as a
			inner join productos as b
			on(b.idProducto=a.idProduct)
			where a.idCotizacion='$idCotizacion'";
		}
		
		return $this->db->query($sql)->result();
	}
	
	//CANCELAR COTIZACIÓN
	public function cancelarCotizacion($idCotizacion)
	{
		$data=array
		(
			'cancelada'		=>'1',
		);
		
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->update('cotizaciones',$data);
		
		return $this->db->affected_rows() >= 1? "1" : "0";
	}
	
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<*  VENTAS POR PRODUCTO  **<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	public function contarVentasProducto($inicio,$fin,$idCliente=0,$idCotizacion=0,$idProducto=0,$permiso=0)
	{
		$sql="select a.idProducto
		from cotiza_productos as a
		inner join cotizaciones as b
		on a.idCotizacion=b.idCotizacion
		where b.estatus='1'
		and a.servicio=0
		and b.activo='1' 
		and b.idLicencia='$this->idLicencia' ";
		
		$sql.=$idProducto>0?" and a.idProduct='$idProducto' ":'';
		$sql.=$idCliente>0?" and b.idCliente='$idCliente' ":'';
		$sql.=$idCotizacion>0?" and a.idCotizacion='$idCotizacion' ":'';
		$sql.="and date(b.fechaCompra) between '$inicio' and  '$fin' ";
		$sql.=$permiso==0?" and b.idUsuario='$this->idUsuario' ":'';
		
		return $this->db->query($sql)->num_rows;
	}

	public function obtenerVentasProducto($numero,$limite,$inicio,$fin,$idCliente=0,$idCotizacion=0,$idProducto=0,$orden='desc',$permiso=0)
	{
		$sql=" select a.idProduct as idProducto, a.cantidad, a.descuento,
		a.descuentoPorcentaje, a.precio, a.importe, b.fechaCompra,
		b.total, b.ordenCompra, b.idCotizacion, b.cancelada, b.idTienda,
		c.idCliente, c.empresa as cliente, d.nombre as producto, b.folio,
		
		(select e.nombre from tiendas as e where e.idTienda=b.idTienda) as tienda
		
		from cotiza_productos as a
		inner join cotizaciones as b
		on a.idCotizacion=b.idCotizacion
		inner join clientes as c
		on c.idCliente=b.idCliente
		inner join productos as d
		on a.idProduct=d.idProducto
		where b.estatus='1'
		and a.servicio=0 
		and b.activo='1'
		and b.idLicencia='$this->idLicencia' ";
		
		$sql.=$idProducto>0?" and a.idProduct='$idProducto' ":'';
		$sql.=$idCliente>0?" and b.idCliente='$idCliente' ":'';
		$sql.=$idCotizacion>0?" and a.idCotizacion='$idCotizacion' ":'';
		$sql.=$permiso==0?" and b.idUsuario='$this->idUsuario' ":'';
		$sql.="and date(b.fechaCompra) between '$inicio' and  '$fin'
		order by b.fechaCompra $orden ";
		
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<*  VENTAS POR SERVICIOS **<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	public function contarVentasServicio($inicio,$fin,$idCliente=0,$idCotizacion=0,$idProducto=0,$permiso=0)
	{
		$sql="select a.idProducto
		from cotiza_productos as a
		inner join cotizaciones as b
		on a.idCotizacion=b.idCotizacion
		where b.estatus='1'
		and a.servicio=1 
		and b.activo='1' 
		and b.idLicencia='$this->idLicencia'";
		
		$sql.=" and b.pendiente='0' ";
		
		$sql.=$idProducto>0?" and a.idProduct='$idProducto' ":'';
		$sql.=$idCliente>0?" and b.idCliente='$idCliente' ":'';
		$sql.=$idCotizacion>0?" and a.idCotizacion='$idCotizacion' ":'';
		$sql.="and date(b.fechaCompra) between '$inicio' and  '$fin' ";
		$sql.=$permiso==0?" and b.idUsuario='$this->idUsuario' ":'';
		
		return $this->db->query($sql)->num_rows;
	}

	public function obtenerVentasServicio($numero,$limite,$inicio,$fin,$idCliente=0,$idCotizacion=0,$idProducto=0,$orden='desc',$permiso=0)
	{
		$sql=" select a.idProduct as idProducto, a.cantidad, a.descuento,
		a.descuentoPorcentaje, a.precio, a.importe, b.fechaCompra,
		b.total, b.ordenCompra, b.idCotizacion, b.cancelada, b.idTienda,
		c.idCliente, c.empresa as cliente, d.nombre as producto,
		(select e.nombre from tiendas as e where e.idTienda=b.idTienda) as tienda
		from cotiza_productos as a
		inner join cotizaciones as b
		on a.idCotizacion=b.idCotizacion
		inner join clientes as c
		on c.idCliente=b.idCliente
		inner join productos as d
		on a.idProduct=d.idProducto
		where b.estatus='1'
		and a.servicio=1
		and b.activo='1'
		and b.idLicencia='$this->idLicencia'  ";
		
		$sql.=" and b.pendiente='0' ";
		
		$sql.=$idProducto>0?" and a.idProduct='$idProducto' ":'';
		$sql.=$idCliente>0?" and b.idCliente='$idCliente' ":'';
		$sql.=$idCotizacion>0?" and a.idCotizacion='$idCotizacion' ":'';
		$sql.=$permiso==0?" and b.idUsuario='$this->idUsuario' ":'';
		
		$sql.="and date(b.fechaCompra) between '$inicio' and  '$fin'
		order by b.fechaCompra $orden ";
		
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<* DEVOLUCIONES PRODUCTOS *<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	
	public function obtenerFolioDevolucion()
	{
		$sql=" select coalesce(max(folio),0) as folio 
		from cotizaciones_devoluciones ";
		
		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function obtenerSerieDevolucion()
	{
		$folio	= $this->obtenerFolioDevolucion();	
		
		$serie	= 'DEV-'.date('Y-m-d').'-'.$folio;
		
		return array($serie,$folio);
	}
	
	public function obtenerDevoluciones($idCotizacion)
	{
		$sql=" select a.*, b.folio, b.serie, b.fecha, 
		c.nombre as producto, c.precio, d.nombre as motivo, e.nombre as tipo
		from cotizaciones_devoluciones_detalles as a
		inner join cotizaciones_devoluciones as b
		on a.idDevolucion=b.idDevolucion
		inner join cotiza_productos as c
		on c.idProducto=a.idProducto
		inner join cotizaciones_devoluciones_motivos as d
		on b.idMotivo=d.idMotivo
		inner join cotizaciones_devoluciones_tipos as e
		on b.idTipo=e.idTipo
		where b.idCotizacion='$idCotizacion' 
		order by b.fecha desc, idDevolucion desc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerProductosDevoluciones($idCotizacion)
	{
		$sql=" select b.idProducto, c.idProducto as idProductoCatalogo, coalesce(sum(a.cantidad),0) as cantidad,
		b.precio, b.nombre as producto, c.unidad, b.descuento, b.descuentoPorcentaje, b.cantidad as cantidaTotal,
		(select coalesce(sum(d.cantidad),0) from cotizaciones_devoluciones_detalles as d where d.idProducto=b.idProducto) as devueltos
		from ventas_entrega_detalles as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		inner join productos as c
		on c.idProducto=b.idProduct
		where b.idCotizacion='$idCotizacion' 
		group by a.idProducto
		order by b.nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarDevolucion()
	{
		$this->db->trans_start();

		$serie				= $this->obtenerSerieDevolucion();
		$idCotizacion		= $this->input->post('txtIdCotizacion');
		
		$data=array
		(
			"fecha"			=> $this->input->post('txtFechaDevolucion'),
			"fechaRegistro"	=> $this->_fecha_actual,
			"folio"			=> $serie[1], 
			"serie"			=> $serie[0], 
			"idUsuario"		=> $this->idUsuario,
			"idCotizacion"	=> $idCotizacion,
			"idTipo"		=> $this->input->post('selectTipoDevolucion'),
			"idMotivo"		=> $this->input->post('selectMotivos'),
			"importe"		=> $this->input->post('txtImporteTotal')
		);
		
		$this->db->insert("cotizaciones_devoluciones",$data);
		$idDevolucion	= $this->db->insert_id();
		
		$tipo	= $this->catalogos->obtenerTipoDevolucionNombre($this->input->post('selectTipoDevolucion'));
		$orden	= $this->obtenerVentaOrden($idCotizacion);
		
		$this->configuracion->registrarBitacora('Registrar devolución','Ventas',$tipo.', Orden: '.$orden); //Registrar bitácora
		
		for($i=1;$i<=$this->input->post('txtNumeroProductos');$i++)
		{
			if(strlen($this->input->post('txtCantidadDevolver'.$i))>0)
			{
				$data=array
				(
					'idDevolucion'	=> $idDevolucion,
					'idProducto'	=> $this->input->post('txtIdProducto'.$i),
					'cantidad'		=> $this->input->post('txtCantidadDevolver'.$i),
					'importe'		=> $this->input->post('txtImporteProducto'.$i),
					'descuento'		=> $this->input->post('txtDescuentoProducto'.$i),
				);
				
				$this->db->insert('cotizaciones_devoluciones_detalles',$data);
			}
		}
		
		$cfdi=array('1');
		
		if($this->input->post('selectTipoDevolucion')=='3')
		{
			$this->devolverProductosCatalogo($idDevolucion); //DEVOLVER EL PRODUCTO A EL CATÁLOGO
			
			$cfdi	= $this->nota->registrarNota($idDevolucion,$serie[0],$tipo,$orden);
		}
		
		if($this->input->post('selectTipoDevolucion')=='2')
		{
			$this->devolverProductosCatalogo($idDevolucion); //DEVOLVER EL PRODUCTO A EL CATÁLOGO
			
			$nota	= $this->registrarEgreso($idDevolucion,$serie[0],$tipo,$orden);
		}
		
		//ACTUALIZAR EL TOTAL DE LA VENTA CUANDO LAS DEVOLUCIONES NO SEAN DE DINERO
		if($this->input->post('selectTipoDevolucion')!='1')
		{
			$cotizacion		= $this->obtenerCotizacion($idCotizacion);
			$productos		= $this->facturacion->obtenerProductosCotizacion($idCotizacion);
			$subTotal		= 0;
			
			foreach($productos as $row)
			{
				$cantidad			= $row->cantidad-$row->devueltos;
				
				if($cantidad>0)
				{
					$importe	= $row->precio*$cantidad;
					$importe 	= round($importe,2);
					
					$subTotal	+=$importe;
				}
			}
			
			$iva	= $subTotal * ($cotizacion->ivaPorcentaje/100);
			$iva	= round($iva,2);
			$total	= $subTotal+$iva;
			
			$this->db->where('idCotizacion',$idCotizacion);
			$this->db->update('cotizaciones',array('subTotal'=>$subTotal,'iva'=>$iva,'total'=>$total));
		}
		
		if ($this->db->trans_status() === FALSE or $cfdi[0]=='0')
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array("0",$cfdi[1]);
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array("1",'La devolución se ha registrado correctamente');
		}
	}
	
	public function obtenerProductosNotaDevolucion($idDevolucion)
	{
		$sql=" select a.cantidad, b.idProducto, 
		b.idProduct as idProductoCatalogo
		from cotizaciones_devoluciones_detalles as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		where a.idDevolucion='$idDevolucion'
		and b.servicio=0 ";
		
		return $this->db->query($sql)->result();
	}
	
	public function devolverProductosCatalogo($idDevolucion)
	{
		$productos	= $this->obtenerProductosNotaDevolucion($idDevolucion);	
		
		foreach($productos as $row)
		{
			#$producto	= $this->obtenerStockProducto($row->idProductoCatalogo);
			$producto	= $this->inventarioProductos->obtenerProductoStock($row->idProductoCatalogo);
			
			if($producto!=null)
			{
				$this->inventarioProductos->actualizarStockProducto($row->idProductoCatalogo,$row->cantidad,'sumar');
				
				/*$this->db->where('idProducto',$row->idProductoCatalogo);
				$this->db->update('productos',array('stock'=>$producto->stock+$row->cantidad));*/
			}
		}
	}
	
	public function registrarEgreso($idDevolucion,$devolucion,$tipo,$orden)
	{
		$idForma				= $this->input->post('selectFormas');

		$data=array
		(
			'pago'				=> $this->input->post('txtImporteDinero'),
			'fecha'				=> $this->input->post('txtFechaEgreso'),
			'formaPago'			=> '',
			'idCuenta'			=> $this->input->post('cuentasBanco'),
			'transferencia'		=> $this->input->post('txtNumeroTransferencia'),
			'cheque'			=> $this->input->post('txtNumeroCheque'),
			'idDepartamento'	=> $this->input->post('selectDepartamento'),
			'idNombre'			=> $idForma==2?$this->input->post('selectNombres'):'',
			'producto'			=> $this->input->post('txtDescripcionProducto'),
			'idProducto'		=> $this->input->post('txtConcepto'),
			'idGasto'			=> $this->input->post('selectTipoGasto'),
			'iva'				=> $this->input->post('txtIvaPorcentajeDinero')*100,
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			'incluyeIva'		=> $this->input->post('txtIvaPorcentajeDinero')>0?'1':'0',
			'cajaChica'			=> '0',
			'comentarios'		=> $this->input->post('txtComentarios'),
			'idProveedor'		=> $this->input->post('txtIdProveedor'),
			'esRemision'		=> $this->input->post('selectFacturaRemision'),
			'remision'			=> $this->input->post('txtRemision'),
			'cantidad'			=> $this->input->post('txtCantidad'),
			'subTotal'			=> $this->input->post('txtSubTotalDinero'),
			'idForma'			=> $idForma,
			'devolucion'		=> '1',
            'idUsuario'			=> $this->idUsuario,
		);

		$this->db->insert('catalogos_egresos',$data);
		$idEgreso	= $this->db->insert_id();
		
		$this->db->where('idDevolucion',$idDevolucion);
		$this->db->update('cotizaciones_devoluciones',array('idEgreso'=>$idEgreso));
		
		$this->configuracion->registrarBitacora('Registrar gasto por devolución','Ventas',$tipo.', Orden: '.$orden.', Devolución: '.$devolucion); //Registrar bitácora
		
		/*//SUBIR EL ARCHIVO
		
		$archivo 		= $_FILES['archivoEgreso']['name'];
		
		if(strlen($archivo)>0)
		{
			$idComprobante	= $this->administracion->subirFicherosEgreso($idEgreso,$archivo,$_FILES['archivoEgreso']['size']);
			
			move_uploaded_file($_FILES['archivoEgreso']['tmp_name'], carpetaEgresos.basename($idComprobante."_".$archivo));
		}*/
	}
	
	public function obtenerVentasNoPagadasServicio($idCotizacionPadre,$idCotizacion,$idProduct) 
	{
		$sql=" select a.idCotizacion, a.ordenCompra
		from cotizaciones as a
		inner join cotiza_productos as c
		on c.idCotizacion=a.idCotizacion
		where a.idCotizacionPadre='$idCotizacionPadre'
		and a.idCotizacion!='$idCotizacion'
		and c.idProduct='$idProduct'
		and (select coalesce(sum(b.pago),0) from catalogos_ingresos as b where b.idVenta=a.idCotizacion)=0 ";
		
		return $this->db->query($sql)->result(); 
	}
	
	public function cancelarVentaServicios($idCotizacionPadre,$idCotizacion,$idProduct) 
	{
		$this->db->trans_start();
		
		$ventas	= $this->obtenerVentasNoPagadasServicio($idCotizacionPadre,$idCotizacion,$idProduct);
		
		$data=array
		(
			'cancelada'			=> '1',
			'fechaModificacion'	=> $this->_fecha_actual,
		);
		
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->update('cotizaciones',$data);
		
		$this->db->where('idVenta',$idCotizacion);
		$this->db->delete('catalogos_ingresos');
		
		$this->configuracion->registrarBitacora('Cancelar ventas de servicios','Reportes - Ventas de servicios','Venta: '.$this->obtenerVentaOrden($idCotizacionPadre)); //Registrar bitácora
		
		foreach($ventas as $row)
		{
			$data=array
			(
				'cancelada'			=> '1',
				'fechaModificacion'	=> $this->_fecha_actual,
			);
			
			$this->db->where('idCotizacion',$row->idCotizacion);
			$this->db->update('cotizaciones',$data);
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array("0",$cfdi[1]);
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array("1",'Ventas canceladas corractamente');
		}
	}
	
	public function comprobarVentaPendiente($idCotizacion) 
	{
		$sql="select idCotizacion
		from cotizaciones
		where idCotizacion='$idCotizacion'
		and pendiente='1' ";
		
		return $this->db->query($sql)->row()!=null?true:false;
	}
	
	public function registrarVentaSecuenciaServicios($idCotizacion) 
	{
		if(!$this->comprobarVentaPendiente($idCotizacion)) return;
		
		$folio					= $this->clientes->obtenerFolio();
		$serie					= "COT-".date('Y-m-d').'-'.$folio;
		$venta					= "VEN-".$folio;
		
		$data=array
		(
			'pendiente'		=> '0',
			'ordenCompra'	=> $venta,
			'serie'			=> $serie,
			'folio'			=> $folio,
		);
		
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->update('cotizaciones',$data);
	}
	
	//ACTIVAR VENTAS DE SERVICIOS	
	public function activarVentasServicios() 
	{
		$sql="select idCotizacion
		from cotizaciones
		where idCotizacionPadre>0
		and pendiente=1
		and activo='1'
		and cancelada='0' 
		and date(fechaCompra)='$this->fechaCorta' ";
		
		$ventas	= $this->db->query($sql)->result();
		
		foreach($ventas as $row)
		{
			$this->registrarVentaSecuenciaServicios($row->idCotizacion);
		}
	}
	
	//EDITAR VENTA DE SERVICIOS
	
	public function obtenerServicioCotizacion($idCotizacion) 
	{
		$sql=" select * from cotiza_productos
		where idCotizacion='$idCotizacion'";
		
		return $this->db->query($sql)->row();
	}
	
	public function editarVentaServicios() 
	{
		$this->db->trans_start();
		
		$idCotizacion	= $this->input->post('txtIdCotizacion');
		$idProducto		= $this->input->post('txtIdProducto');
		
		$data=array
		(
			'subTotal'			=> $this->input->post('txtSubTotalVenta'),
			'descuento'			=> $this->input->post('txtDescuentoVenta'),
			'iva'				=> $this->input->post('txtIva'),
			'total'				=> $this->input->post('txtTotalVenta'),
		);
		
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->update('cotizaciones',$data);
		
		$data=array
		(
			'precio'			=> $this->input->post('txtPrecioProducto'),
			'importe'			=> $this->input->post('txtImporte'),
			'descuento'			=> $this->input->post('txtDescuento'),
		);
		
		$this->db->where('idProducto',$idProducto);
		$this->db->update('cotiza_productos',$data);
		
		$this->configuracion->registrarBitacora('Editar ventas de servicios','Reportes - Ventas de servicios','Venta: '.$this->obtenerVentaOrden($idCotizacion).', Servicio: '.$this->input->post('txtNombreProducto').', Precio: $'.$this->input->post('txtPrecioProducto')); //Registrar bitácora

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array("0",$cfdi[1]);
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array("1",'El servicio se ha editado correctamente');
		}
	}
	
	
	//FONDO DE CAJA
	public function registrarFondoCaja()
	{		
		$data=array
		(
			'idVenta'			=> 0,
			'pago'				=> $this->input->post('txtFondoCaja'),
			'subTotal'			=> $this->input->post('txtFondoCaja'),
			'ivaTotal'			=> 0,
			'iva'				=> 0,
			'incluyeIva'		=> '1',
			
			'fecha'				=> $this->_fecha_actual,
			'formaPago'			=> '',
			'idCuenta'			=> 1,
			'transferencia'		=> '',
			'cheque'			=> '',
			'idDepartamento'	=> 0,
			'idNombre'			=> 0,
			'producto'			=> '',
			'idGasto'			=> 0,
			'idProducto'		=> 0,
			'nombreReceptor'	=> '',
			
			'comentarios'		=> '',
			'idCliente'			=> 0,
			'factura'			=> '',
			'remision'			=> '',
			
			'idProductoCatalogo'=> 0,
			'cantidad'			=> 1,
			'idForma'			=> 1,
			'fondoCaja'			=> '1',
			'idLicencia'		=> $this->idLicencia,
			'idUsuario'			=> $this->idUsuario,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_ingresos',$data);
		$idIngreso	=$this->db->insert_id();

		return $this->db->affected_rows()>=1?array('1',registroCorrecto,$idIngreso):array('0',errorRegistro); 
	}
	
	public function obtenerTotalEfectivo()
	{
		$sql="select coalesce(sum(pago),0) as pago
		from catalogos_ingresos 
		where date(fecha)='$this->fechaCorta'
		and idForma='1'
		and fondoCaja='0' ";
		
		return $this->db->query($sql)->row()->pago;
	}
	
	public function obtenerTotalFondo()
	{
		$sql=" select coalesce(sum(pago),0) as pago
		from catalogos_ingresos 
		where date(fecha)='$this->fechaCorta'
		and fondoCaja='1' ";
		
		return $this->db->query($sql)->row()->pago;
	}
	
	public function obtenerTotalRetiros()
	{
		$sql=" select coalesce(sum(pago),0) as pago
		from catalogos_egresos 
		where date(fecha)='$this->fechaCorta'
		and idForma='1'
		and retiroEfectivo='1' ";
		
		return $this->db->query($sql)->row()->pago;
	}
	
	public function obtenerTotalesFormas()
	{
		$sql=" select coalesce(sum(a.pago),0) as pago, b.nombre as forma
		from catalogos_ingresos  as a
		inner join catalogos_formas as b
		on a.idForma=b.idForma
		where date(a.fecha)='$this->fechaCorta'
		and a.fondoCaja='0'
		and a.idForma!=4
		group by a.idForma ";
		
		return $this->db->query($sql)->result();
	}
	
	//RETIRO DE EFECTIVO
	public function registrarRetiroEfectivo()
	{		
		$data=array
		(
			'pago'				=> $this->input->post('txtRetiroEfectivo'),
			'subTotal'			=> $this->input->post('txtRetiroEfectivo'),
			'ivaTotal'			=> 0,
			'iva'				=> 0,
			'incluyeIva'		=> '1',
			
			'fecha'				=> $this->_fecha_actual,
			'formaPago'			=> '',
			'idCuenta'			=> 1,
			'transferencia'		=> '',
			'cheque'			=> '',
			'idDepartamento'	=> 0,
			'idNombre'			=> 0,
			'producto'			=> '',
			'idProducto'		=> 0,
			'idGasto'			=> 0,
			'nombreReceptor'	=> '',
			#'incluyeIva'		=> $this->input->post('chkIva')=='1'?'1':'0',
			'cajaChica'			=> '0',
			'comentarios'		=> $this->input->post('txtMotivoRetiro'),
			'idProveedor'		=> '',
			'esRemision'		=> '0',
			'remision'			=> '',
			'cantidad'			=> 1,
			'idForma'			=> 1,
			'retiroEfectivo'	=> '1',
            'idUsuario'			=> $this->idUsuario,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_egresos',$data);
		$idEgreso	=$this->db->insert_id();

		return $this->db->affected_rows()>=1?array('1',registroCorrecto,$idEgreso):array('0',errorRegistro); 
	}
	
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//ARQUEO DE CAJA
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function obtenerDenominaciones()
	{
		$sql=" select a.*		
		from catalogos_denominaciones as a
		order by valor desc ";

		return  $this->db->query($sql)->result();
	}
	
	public function obtenerDenominacionesArqueo($idArqueo)
	{
		$sql=" select a.*, b.cantidad, b.idArqueo, b.idRelacion
		from catalogos_denominaciones as a
		inner join arqueos_denominaciones as b
		on a.idDenominacion=b.idDenominacion
		where b.idArqueo='$idArqueo'
		order by a.valor desc ";

		return  $this->db->query($sql)->result();
	}
	
	public function obtenerArqueo($idArqueo)
	{
		$sql=" select * from arqueos_caja
		where idArqueo='$idArqueo' ";

		return  $this->db->query($sql)->row();
	}
	
	public function comprobarArqueo()
	{
		$sql=" select idArqueo
		from arqueos_caja
		where fecha='$this->fechaCorta'
		and idTienda='$this->idTienda' ";

		$arqueo	= $this->db->query($sql)->row();
		
		if($arqueo==null)
		{
			$idArqueo= $this->registrarArqueoInicial();
			
			$this->registrarDenominacionesInicial($idArqueo);
			
			return $idArqueo;
		}
		else
		{
			return $arqueo->idArqueo;
		}
	}
	
	public function registrarArqueoInicial()
	{
		$data = array
		(
			'idUsuario'			=> $this->idUsuario,
			'fecha'				=> $this->fechaCorta,
			'hora'				=> $this->hora,
			'idTienda'			=> $this->idTienda,
		);
		
		$this->db->insert('arqueos_caja',$data);
		
		return $this->db->insert_id();
	}
	
	public function registrarDenominacionesInicial($idArqueo)
	{
		$denominaciones	= $this->obtenerDenominaciones($idArqueo);
		
		foreach($denominaciones as $row)
		{
			$data = array
			(
				'idArqueo'			=> $idArqueo,
				'idDenominacion'	=> $row->idDenominacion,
			);
			
			$this->db->insert('arqueos_denominaciones',$data);
		}
	}
	
	public function sumarDenominaciones($idArqueo)
	{
		$sql=" select coalesce(sum(a.cantidad*b.valor),0) as pago
		from arqueos_denominaciones as a
		inner join catalogos_denominaciones as b
		on a.idDenominacion=b.idDenominacion
		where a.idArqueo='$idArqueo'";

		return $this->db->query($sql)->row()->pago;
	}
	
	public function registrarDenominacion()
	{
	
		$data = array
		(
			'cantidad'		=> $this->input->post('cantidad'),
		);
		
		$this->db->where('idRelacion',$this->input->post('idRelacion'));
		$this->db->update('arqueos_denominaciones',$data);
		
		return $this->db->affected_rows()>0?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function registrarArqueo()
	{
		$data = array
		(
			'fondoInicial'			=> $this->input->post('fondoInicial'),
			'efectivo'				=> $this->input->post('efectivo'),
			'retiros'				=> $this->input->post('retiros'),
			'totalEfectivo'			=> $this->input->post('totalEfectivo'),
			'efectivoReportado'		=> $this->input->post('efectivoReportado'),
			'idUsuario'				=> $this->idUsuario,
			'fecha'					=> $this->fechaCorta,
			'hora'					=> $this->hora,
			#'editado'				=> '1',
		);
		
		$this->db->where('idArqueo',$this->input->post('idArqueo'));
		$this->db->update('arqueos_caja',$data);
		
		return $this->db->affected_rows()>0?'1':'0';
	}
	
	//DEVOLUCIÓN DE ACRÍLICO
	public function registrarAcrilico()
	{
		$this->db->trans_start(); 

		$data = array
		(
			'idCompra'			=> 0,
			'idCuenta'			=> 1,
			'transferencia'		=> '',
			'cheque'			=> '',
			'formaPago'			=> '',
			'fecha'				=> $this->input->post('txtFechaEgreso'),
			'idLicencia'		=> $this->idLicencia,
			'idDepartamento'	=> 0,
			'idNombre'			=> 0,
			'producto'			=> $this->input->post('txtDescripcionProducto'),
			'concepto'			=> $this->input->post('txtDescripcionProducto'),
			'idProducto'		=> 0,
			'idGasto'			=> 0,
			'nombreReceptor'	=> '',
			'factura'			=> '',
			'comentarios'		=> '',
			'idProveedor'		=> 0,
			'idForma'			=> 1,
			'esRemision'		=> '0',
			
			'ivaTotal'			=> 0,
			'iva'				=> 0,
			'incluyeIva'		=> '0',
			'pago'				=> $this->input->post('txtAcrilico'),
			'subTotal'			=> $this->input->post('txtAcrilico'),
			'idCotizacion'		=> $this->input->post('txtIdCotizacion'),
            'idUsuario'			=> $this->idUsuario,
			
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_egresos',$data);
		$idEgreso=$this->db->insert_id();
		
		#$this->contabilidad->registrarPolizaEgreso($data['fecha'],$data['producto'],0,$data['pago'],$idEgreso); //REGISTRAR LA PÓLIZA DE INGRESO
		
		#$this->configuracion->registrarBitacora('Registrar pago de compras','Compras - Pagos',$this->input->post('txtDescripcionProducto').', Importe: $'.number_format($this->input->post('montoPagar'),2)); //Registrar bitácora

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();

			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}
	}
	
	//CONVERTIR PREFACTURA  A REMISIÓN
	public function obtenerCotizacionRegistro($idCotizacion)
	{
		$sql="select * from cotizaciones 
		where idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->row_array();
	}
	
	public function obtenerDetallesCotizacionRegistro($idCotizacion)
	{
		$sql="select * from cotiza_productos 
		where idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerEntregasRegistro($idProducto)
	{
		$sql="select * from ventas_entrega_detalles 
		where idProducto='$idProducto' ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerImpuestosRegistro($idProducto)
	{
		$sql="select * from cotiza_productos_impuestos 
		where idProducto='$idProducto' ";
		
		return $this->db->query($sql)->row_array();
	}
	
	public function obtenerIngresosRegistro($idVenta)
	{
		$sql="select * from catalogos_ingresos 
		where idVenta='$idVenta' ";
		
		return $this->db->query($sql)->result_array();
	}
	
	
	public function convertirPreFactura()
	{
		$this->db->trans_start(); 

		$idCotizacion	= $this->input->post('idCotizacion');
		
		$cotizacion		= $this->obtenerCotizacionRegistro($idCotizacion);
		$detalles		= $this->obtenerDetallesCotizacionRegistro($idCotizacion);
		
		$folio							= $this->clientes->obtenerFolio(0,$cotizacion['idEstacion']);
		$serie							= "COT-".date('Y-m-d').'-'.$folio;
		$venta							= "VEN-".$folio;
		$folioConta						= $this->clientes->obtenerFolioConta();
		
		$cotizacion['idCotizacion']		= 0;
		$cotizacion['prefactura']		= 0;
		$cotizacion['folio']			= $folio;
		$cotizacion['serie']			= $serie;
		$cotizacion['ordenCompra']		= $venta;
		$cotizacion['fecha']			= $this->_fecha_actual;
		$cotizacion['fechaPedido']		= $this->_fecha_actual;
		$cotizacion['fechaEntrega']		= $this->_fecha_actual;
		$cotizacion['fechaCompra']		= $this->_fecha_actual;
		$cotizacion['folioConta']		= $folioConta;
		$cotizacion['idCotizacionPasada']	= $idCotizacion;
		
		
		$this->db->insert('cotizaciones',$cotizacion);
		$idCotizacionNueva	= $this->db->insert_id();
		
		foreach($detalles as $row)
		{
			$entregas	= $this->obtenerEntregasRegistro($row['idProducto']);
			$impuesto	= $this->obtenerImpuestosRegistro($row['idProducto']);
			$ingresos	= $this->obtenerIngresosRegistro($idCotizacion);
			
			$row['idProducto']		= 0;
			$row['idCotizacion']	= $idCotizacionNueva;
			
			$this->db->insert('cotiza_productos',$row);
			$idProducto	= $this->db->insert_id();
			
			//REGISTRAR EL IMPUESTO
			$impuesto['idDetalle']		= 0;
			$impuesto['idProducto']		= $idProducto;
			$this->db->insert('cotiza_productos_impuestos',$impuesto);
		
			//REGISTRAR LAS ENTREGAS
			foreach($entregas as $ent)
			{
				$ent['idEntrega']		= 0;
				$ent['idProducto']		= $idProducto;
				$ent['fecha']			= $this->_fecha_actual;
				
				$this->db->insert('ventas_entrega_detalles',$ent);		
			}
			
			//REGISTRAR LAS ENTREGAS
			foreach($ingresos as $ing)
			{
				$ing['idIngreso']		= 0;
				$ing['idVenta']			= $idCotizacionNueva;
				$ing['fecha']			= $this->_fecha_actual;
				$ing['concepto']		= 'VEN-'.$folio;
				$ing['producto']		= 'VEN-'.$folio;
				$ing['idLicencia']		= $this->idLicencia;
				$ing['idProducto']		= $this->idUsuario;
				
				$this->db->insert('catalogos_ingresos',$ing);		
			}
			
			
			//CANCELAR LA VENTA Y EL INGRESO
			$this->db->where('idVenta',$idCotizacion);
			$this->db->delete('catalogos_ingresos');

			$this->db->where('idCotizacion',$idCotizacion);
			$this->db->update('cotizaciones',array('cancelada' => '1'));
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();

			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}
	}
	
	public function editarEntrega()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$cantidadEntregada		= $this->input->post('txtCantidadEntregada'); //ES LA CANTIDAD QUE SE VA A SUMAR AL INVENTARIO
		$cantidad				= $this->input->post('txtCantidadEditarEntregar');
		$idProducto				= $this->input->post('txtIdProductoEntrega');
		$idProductoCotizacion	= $this->input->post('txtIdProductoCotizacion');
		$idEntrega				= $this->input->post('txtIdEntrega');
		
		
		$this->inventarioProductos->actualizarStockProducto($idProducto,$cantidadEntregada,'sumar');

		$producto				= $this->inventarioProductos->obtenerProductoStock($idProducto);
		$cantidadEntregada		= $this->obtenerProductoEntregado($idProductoCotizacion);
	
		
		#---------------------------------------------------------------------------#
		$data=array
		(
			"cantidad"		=> $cantidad,
		);
		
		$this->db->where("idEntrega",$idEntrega);
		$this->db->update("ventas_entrega_detalles",$data);
		
		if($producto->stock<$cantidad)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();

			return array('0','No existen suficientes productos para ser entregados, revise por favor el inventario');
		}
		else
		{
			$this->inventarioProductos->actualizarStockProducto($idProducto,$cantidad,'restar');

			#$this->configuracion->registrarBitacora('Entregar producto','Ventas',$producto->nombre.', Orden: '.$this->obtenerVentaProducto($idProducto).', Cantidad: '.number_format($cantidad,decimales)); //Registrar bitácora
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}
	}
	
	//PAGO DE LA VENTA POR LA CAJA
	public function obtenerVentaFolio()
	{
		error_reporting(0);
		/*$folio		= $this->input->post('folio');
		$prefactura	= $this->input->post('prefactura');
		$folio		= explode('-',$folio);
		$folios		= trim($folio[0]).trim($folio[1]);

		#$folio	= str_replace("-","",$folio);
		#$folio	= str_replace(" ","",$folio);
		
		
		$sql=" select a.idCotizacion, a.idCliente, a.folio as folioVenta,
		a.total, concat(a.folio, '-', b.nombre) as folio, c.nombre as forma, c.idForma,
		
		(select coalesce(sum(c.pago),0) from catalogos_ingresos as c where c.idVenta=a.idCotizacion and c.idForma!=4) as pagado
		
		from cotizaciones as a
		inner join configuracion_estaciones as b
		on a.idEstacion=b.idEstacion
		
		inner join catalogos_formas as c
		on a.idForma=c.idForma
		
		where a.idLicencia='$this->idLicencia'
		and concat(a.folio,b.nombre) = '$folios'
		and a.activo='1' 
		and a.cancelada='0' ";

		$sql.=" and a.prefactura='$prefactura'";
		
		#and date(a.fechaCompra)=curdate() 
		#echo $sql;
		$cotizacion	= $this->db->query($sql)->row();*/
		
		
		
		$idCotizacion		= trim($this->input->post('folio'));
		
		$sql=" select a.idCotizacion, a.idCliente, a.folio as folioVenta,
		a.total, concat(a.folio, '-', b.nombre) as folio, c.nombre as forma, c.idForma,
		
		(select coalesce(sum(c.pago),0) from catalogos_ingresos as c where c.idVenta=a.idCotizacion and c.idForma!=4) as pagado
		
		from cotizaciones as a
		inner join configuracion_estaciones as b
		on a.idEstacion=b.idEstacion
		
		inner join catalogos_formas as c
		on a.idForma=c.idForma
		
		where a.idLicencia='$this->idLicencia'
		and a.idCotizacion = '$idCotizacion'
		and a.activo='1' 
		and a.cancelada='0' ";

		#$sql.=" and a.prefactura='$prefactura'";
		
		#and date(a.fechaCompra)=curdate() 
		#echo $sql;
		$cotizacion	= $this->db->query($sql)->row();
		
		return $cotizacion!=null?$cotizacion:array('idCotizacion'=>0);
	}
	
	public function registrarPagoCaja()
	{
		$numeroPagos			= $this->input->post('txtNumeroPagos');
		$numeroFormas			= $this->input->post('txtNumeroFormas');
		
		if($numeroPagos==1)
		{
			$importe				= $this->input->post('txtSaldoCaja');
			$pago					= $this->input->post('txtImporteCaja0');

			if($pago<$importe)
			{
				$importe=$pago;
			}

			$subTotal				= $importe/1.16;
		
			$data = array
			(
				'idVenta'			=> $this->input->post('txtIdCotizacion'),
				'idCliente'			=> $this->input->post('txtIdCliente'),
				'idCuenta'			=> 1,
				'pago'				=> $importe,
				'nombreReceptor'	=> '',
				'transferencia'		=> '',
				'cheque'			=> '',
				'formaPago'			=> '',
				'fecha'				=> $this->_fecha_actual,
				'idLicencia'		=> $this->idLicencia,
				'concepto'			=> $this->input->post('txtConcepto'),
				'producto'			=> $this->input->post('txtConcepto'),
			
				'nombreReceptor'	=> '',
				'incluyeIva'		=> 1,
				'idForma'			=> $this->input->post('selectFormasPago0'),
				'iva'				=> 16,
				'subTotal'			=> $subTotal,
				'ivaTotal'			=> $importe-$subTotal,
				'idUsuario'			=> $this->idUsuario,
			);
		
			$data	= procesarArreglo($data);
			$this->db->insert('catalogos_ingresos',$data);

			return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
		}
		
		if($numeroPagos>1)
		{
			$this->db->trans_start();

			for($i=0;$i<$numeroFormas;$i++)
			{
				$importe				= $this->input->post('txtImporteCaja'.$i);
				$subTotal				= $importe/1.16;
		
				$data = array
				(
					'idVenta'			=> $this->input->post('txtIdCotizacion'),
					'idCliente'			=> $this->input->post('txtIdCliente'),
					'idCuenta'			=> 1,
					'pago'				=> $importe,
					'nombreReceptor'	=> '',
					'transferencia'		=> '',
					'cheque'			=> '',
					'formaPago'			=> '',
					'fecha'				=> $this->_fecha_actual,
					'idLicencia'		=> $this->idLicencia,
					'concepto'			=> $this->input->post('txtConcepto'),
					'producto'			=> $this->input->post('txtConcepto'),
			
					'nombreReceptor'	=> '',
					'incluyeIva'		=> 1,
					'idForma'			=> $this->input->post('selectFormasPago'.$i),
					'iva'				=> 16,
					'subTotal'			=> $subTotal,
					'ivaTotal'			=> $importe-$subTotal,
					'idUsuario'			=> $this->idUsuario,
				);
		
				$data	= procesarArreglo($data);
				$this->db->insert('catalogos_ingresos',$data);
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$this->db->trans_complete();
			
				return array('0',errorRegistro);
			}
			else
			{
				$this->db->trans_commit();
				$this->db->trans_complete();
			
				return array('1',registroCorrecto);
			}
		}
		
	}
	
	public function obtenerFolioTipoRegistro($tipoRegistro)
	{
		$sql="select coalesce(max(folio),0) as folio
		from catalogos_egresos 
		where tipoRegistro='$tipoRegistro'";
		
		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function registrarValesRetiros()
	{
		$importe				= $this->input->post('txtImporteValeRetiro');
		$subTotal				= $importe/1.16;
		
		$data = array
		(
			'pago'				=> $importe,
			'subTotal'			=> $subTotal,
			'ivaTotal'			=> $importe-$subTotal,
			'iva'				=> 16,
			'incluyeIva'		=> '1',
			'fecha'				=> $this->_fecha_actual,
			'formaPago'			=> '',
			'idCuenta'			=> 1,
			'transferencia'		=> '',
			'cheque'			=> '',
			'idDepartamento'	=> 0,
			'idNombre'			=> 0,
			'producto'			=> $this->input->post('txtValeRetiro'),
			'idProducto'		=> 0,
			'idGasto'			=> 0,
			'nombreReceptor'	=> '',
			'cajaChica'			=> '0',
			'comentarios'		=> '',
			'idProveedor'		=> 0,
			'esRemision'		=> '0',
			'remision'			=> '',
			
			'idVariable1'		=> 0,
			'idVariable2'		=> 0,
			'idVariable3'		=> 0,
			'idVariable4'		=> 0,
			
			'idPersonal'		=> 0,
			
			'idCompra'			=> 0,
			'cantidad'			=> 1,
			'idForma'			=> 1,
			'idProductoCatalogo'=> 0,
			'idLicencia'		=> $this->idLicencia,
			'tipoRegistro'		=> $this->input->post('txtTipoRegistro'),
			'folio'				=> $this->obtenerFolioTipoRegistro($this->input->post('txtTipoRegistro')),
			'idEstacion'		=> $this->idEstacion,
            'idUsuario'			=> $this->idUsuario,

			'idUsuarioRetiro'	=> strlen($this->input->post('selectUsuarios'))>0?$this->input->post('selectUsuarios'):0,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_egresos',$data);
		$idRegistro=$this->db->insert_id();
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto,sha1($idRegistro)):array('0',errorRegistro); 
	}
	
	
	public function editarVenta()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idCotizacion			= $this->input->post('txtIdCotizacion'); //ES LA CANTIDAD QUE SE VA A SUMAR AL INVENTARIO
		$numeroProductos		= $this->input->post('txtNumeroProductos');
		$ivaPorcetaje			= $this->input->post('txtIvaPorcentaje');
		$ban					= false;
		
		for($i=0;$i<$numeroProductos;$i++)
		{
			$cantidad				= $this->input->post('txtCantidad'.$i);
			$cantidadTotal			= $this->input->post('txtCantidadTotal'.$i);
			$idRelacion				= $this->input->post('txtIdRelacion'.$i);
			$idProducto				= $this->input->post('txtIdProducto'.$i);
			$importe				= $this->input->post('txtImporte'.$i);
			
			if($cantidad!=$cantidadTotal)
			{
				$ban	= true;
				
				$diferencia	= $cantidadTotal-$cantidad;
				
				$this->db->where("idProducto",$idRelacion);
				$this->db->delete("ventas_entrega_detalles");
				
				if($diferencia>0)
				{
					$this->inventarioProductos->actualizarStockProducto($idProducto,$diferencia,'sumar');
					
					$this->db->insert("ventas_entrega_detalles",array('idProducto'=>$idRelacion,'cantidad'=>$cantidad,'fecha'=>$this->_fecha_actual,'entrego'=>$this->_user_name));
				}
				
				//BORRAR LA PARTIDA CUANDO LA CANTIDAD SEA 0
				if($cantidad==0)
				{
					$this->db->where("idProducto",$idRelacion);
					$this->db->delete("cotiza_productos");
					
					$this->db->where("idProducto",$idRelacion);
					$this->db->delete("cotiza_productos_impuestos");

					$sql=" delete a, b
					from facturas_detalles a
					inner join facturas_detalles_impuestos b on a.idDetalle = b.idDetalle
					where a.idProducto='$idRelacion'";

					$this->db->query($sql);

					/*$this->db->where("idProducto",$idRelacion);
					$this->db->delete("facturas_detalles");*/
				}
				else
				{
					$this->db->where("idProducto",$idRelacion);
					$this->db->update("cotiza_productos",array('cantidad'=>$cantidad,'importe'=>$importe));
					
					$impuesto		= $importe*($ivaPorcetaje/100);
					
					$this->db->where("idProducto",$idRelacion);
					$this->db->update("cotiza_productos_impuestos",array('importe'=>$impuesto));


					$this->db->where("idProducto",$idRelacion);
					$this->db->update("facturas_detalles",array('cantidad'=>$cantidad,'importe'=>$importe));

					$sql=" update facturas_detalles_impuestos a, facturas_detalles b
					set a.importe = ".$impuesto."
					where a.idDetalle = b.idDetalle
					and b.idProducto='$idRelacion'";

					$this->db->query($sql);
				}
			}
		}
		
		if($ban)
		{
			$this->db->where("idVenta",$idCotizacion);
			$this->db->delete("catalogos_ingresos");

			$this->db->where("idCotizacion",$idCotizacion);
			$this->db->update("cotizaciones",array('subTotal'=> $this->input->post('txtSubTotal'),'iva'=>$this->input->post('txtIvaTotal'),'total'=>$this->input->post('txtTotal')));

			//EDITAR LA FACTURA
			$this->db->where("idCotizacion",$idCotizacion);
			$this->db->update("facturas",array('subTotal'=> $this->input->post('txtSubTotal'),'iva'=>$this->input->post('txtIvaTotal'),'total'=>$this->input->post('txtTotal')));
		}
				
		if ($this->db->trans_status() === FALSE or !$ban)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0','El registro no tuvo cambios');
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}
	}
	
	public function obtenerValesRetiros($tipo,$idEstacion=0,$fecha='')
	{
		$sql="select coalesce(sum(pago),0) as total
		from catalogos_egresos
		where tipoRegistro='$tipo'
		and date(fecha)='$fecha'
		and idLicencia='$this->idLicencia'";
		
		$sql.=$idEstacion>0?" and idEstacion='$idEstacion' ":'';
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerEfectivo($idEstacion,$fecha='')
	{
		$sql=" select coalesce(sum(a.pago),0) as total
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where a.idForma='1'
		and a.idLicencia='$this->idLicencia'
		and date(a.fecha)='$fecha' ";
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion' ":'';
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerEfectivoFecha($idEstacion,$fecha='',$idUsuario=0)
	{
		$sql=" select coalesce(sum(a.pago),0) as total
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where a.idForma='1'
		and a.idLicencia='$this->idLicencia'
		and date(a.fecha)='$fecha'
		and date(b.fechaCompra)='$fecha' ";
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion' ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario' ":'';
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerEfectivoFechaDiferente($idEstacion,$fecha='',$idUsuario=0)
	{
		$sql=" select coalesce(sum(a.pago),0) as total
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where a.idForma='1'
		and a.idLicencia='$this->idLicencia'
		and date(a.fecha)='$fecha'
		and date(b.fechaCompra)!='$fecha' ";
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion' ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario' ":'';
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerTotalesFormasEstacion($idEstacion,$fecha='')
	{
		$sql=" select coalesce(sum(a.pago),0) as total, c.nombre as forma
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		inner join catalogos_formas as c
		on a.idForma=c.idForma
		where  date(a.fecha)='$fecha'
		and a.idLicencia='$this->idLicencia'
		and a.idForma<4 ";
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion' ":'';
		
		$sql.=" group by a.idForma ";
		
		#echo $sql;
		
		return $this->db->query($sql)->result();
	}
	
	
	public function obtenerTotalesFormasEstacionFecha($idEstacion,$fecha='',$idUsuario=0)
	{
		$sql=" select coalesce(sum(a.pago),0) as total, c.nombre as forma
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		inner join catalogos_formas as c
		on a.idForma=c.idForma
		where  date(a.fecha)='$fecha'
		and date(b.fechaCompra)='$fecha'
		and a.idLicencia='$this->idLicencia'
		and a.idForma<4 ";
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion' ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario' ":'';
		
		$sql.=" group by a.idForma ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerTotalesFormasEstacionFechaDiferente($idEstacion,$fecha='',$idUsuario=0)
	{
		$sql=" select coalesce(sum(a.pago),0) as total, c.nombre as forma, c.idForma
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		inner join catalogos_formas as c
		on a.idForma=c.idForma
		where  date(a.fecha)='$fecha'
		and date(b.fechaCompra)!='$fecha'
		and a.idLicencia='$this->idLicencia'
		and a.idForma<4 ";
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion' ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario' ":'';
		
		$sql.=" group by a.idForma ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerVentasFormasEstacionFecha($idEstacion,$fecha='',$idForma=0)
	{
		$sql=" select a.pago as total, b.folio, b.fechaCompra,
		(select c.empresa from clientes as c where c.idCliente=b.idCliente) as cliente,
		(select c.nombre from configuracion_estaciones as c where c.idEstacion=b.idEstacion) as estacion
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where  date(a.fecha)='$fecha'
		and date(b.fechaCompra)!='$fecha'
		and a.idLicencia='$this->idLicencia' ";
		
		if($idForma==5)
		{
			$sql.=" and (a.idForma=5 or a.idForma=6)  ";
		}
		else
		{
			$sql.=" and a.idForma=$idForma ";
		}
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion' ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTotalesFormasPendiente($idEstacion,$fecha='')
	{
		$sql=" select sum(a.total - (select coalesce(sum(b.pago),0) from catalogos_ingresos as b where b.idVenta=a.idCotizacion) ) as total
		from cotizaciones as a
		where  date(a.fechaCompra)='$fecha'
		and a.idLicencia='$this->idLicencia'
		and a.idForma!=7
		and estatus='1'
		and a.cancelada='0'
		and a.activo='1' ";
		
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		
		#echo $sql;
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerFormasPendiente($idEstacion,$fecha='')
	{
		$sql=" select a.total - (select coalesce(sum(b.pago),0) from catalogos_ingresos as b where b.idVenta=a.idCotizacion)  as total,
		a.folio, a.fechaCompra,
		(select c.empresa from clientes as c where c.idCliente=a.idCliente) as cliente,
		(select c.nombre from configuracion_estaciones as c where c.idEstacion=a.idEstacion) as estacion
		from cotizaciones as a
		where  date(a.fechaCompra)='$fecha'
		and a.idLicencia='$this->idLicencia'
		and a.idForma!=7
		and estatus='1'
		and a.cancelada='0'
		and a.activo='1'
		and (a.total - (select coalesce(sum(b.pago),0) from catalogos_ingresos as b where b.idVenta=a.idCotizacion))>0 ";
		
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerEnviosCobrados($idEstacion,$fecha='')
	{
		$sql ="	select sum(a.total) as total
		from cotizaciones as a
		where a.estatus=1
		and a.cancelada='0'
		and a.activo='1'
		and a.idRuta>0
		and a.idLicencia='$this->idLicencia'
		
		and a.pendiente='0'
		and date(a.fechaCompra) = '$fecha' 
		and (a.total - (select coalesce(sum(b.pago),0) from catalogos_ingresos as b where b.idVenta=a.idCotizacion) ) = 0 ";

		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerEnviosPendientes($idEstacion,$fecha='')
	{
		$sql ="	select sum(a.total - (select coalesce(sum(b.pago),0) from catalogos_ingresos as b where b.idVenta=a.idCotizacion) ) as total
		from cotizaciones as a
		where a.estatus=1
		and a.cancelada='0'
		and a.activo='1'
		and a.idRuta>0
		and a.idLicencia='$this->idLicencia'
		and a.pendiente='0'
		and date(a.fechaCompra) = '$fecha' 
		and (a.total - (select coalesce(sum(b.pago),0) from catalogos_ingresos as b where b.idVenta=a.idCotizacion) )>0 ";

		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerSaldoInicial($fecha='')
	{
		$sql="select coalesce(sum(a.pago),0) as total
		from catalogos_ingresos as a
		where a.idForma='1'
		and a.idLicencia='$this->idLicencia'
		and a.saldoInicial='1'
		
		and date(a.fecha)='$fecha'";
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerTarjetas($idEstacion=0,$fecha='')
	{
		$sql="select coalesce(sum(a.pago),0) as total
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where date(a.fecha)='$fecha'
		and a.idLicencia='$this->idLicencia'
		and (a.idForma=5 or a.idForma=6) ";
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion' ":'';
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerTarjetasFecha($idEstacion=0,$fecha='',$idUsuario=0)
	{
		$sql="select coalesce(sum(a.pago),0) as total
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where date(a.fecha)='$fecha'
		and date(b.fechaCompra)='$fecha'
		and a.idLicencia='$this->idLicencia'
		and (a.idForma=5 or a.idForma=6) ";
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion' ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario' ":'';
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerTarjetasFechaDiferente($idEstacion=0,$fecha='',$idUsuario=0)
	{
		$sql="select coalesce(sum(a.pago),0) as total
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where date(a.fecha)='$fecha'
		and date(b.fechaCompra)!='$fecha'
		and a.idLicencia='$this->idLicencia'
		and (a.idForma=5 or a.idForma=6) ";
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion' ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario' ":'';
		
		return $this->db->query($sql)->row()->total;
	}
	
	//REGISTRAR SALDO INICIAL
	
	public function registrarSaldoInicial()
	{
		$data = array
		(
			'idVenta'			=> 0,
			'idCliente'			=> 0,
			'idCuenta'			=> 1,
			
			'pago'				=> $this->input->post('txtImporte'),
			'nombreReceptor'	=> '',
			'transferencia'		=> '',
			'cheque'			=> '',
			'formaPago'			=> '',
			'fecha'				=> $this->_fecha_actual,
			'idLicencia'		=> $this->idLicencia,
			'concepto'			=> $this->input->post('txtConceptoSaldo'),
			'producto'			=> $this->input->post('txtConceptoSaldo'),
			
			'nombreReceptor'	=> '',
			'incluyeIva'		=> 1,
			'idForma'			=> $this->input->post('txtIdForma'),
			'iva'				=> 0,
			'subTotal'			=> $this->input->post('txtImporte'),
			'ivaTotal'			=> 0,
			'saldoInicial'		=> '1',
			'idUsuario'			=> $this->idUsuario,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_ingresos',$data);
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerUsuariosCajero()
	{
		$sql=" select distinct(a.idUsuario), concat(a.nombre,' ',a.apellidoPaterno,' ',a.apellidoMaterno) as usuario 
		from usuarios as a
		inner join catalogos_ingresos as b
		on a.idUsuario=b.idUsuario
		where b.idLicencia='$this->idLicencia'";
		
		return $this->db->query($sql)->result();
	}

	public function revisarEntrega($idCotizacion)
	{
		return $this->db->select("idEntrega")
		->from("cotizaciones_tickets_detalles")
		->where("idCotizacion",$idCotizacion)
		->where("idEntrega!=0","",false)
		->get()->row()!=null?false:true;
	}

	public function registrarEntregas()
	{
		$this->db->trans_start();

		$data=array
		(
			"fecha"				=> $this->_fecha_actual,
			"idPersonal"		=> $this->input->post('txtIdPersonal'),
			"idUsuarioRegistro"	=> $this->idUsuario,
		);
		
		$this->db->insert("cotizaciones_entregas",$data);
		$idRegistro	= $this->db->insert_id();

		for($i=0;$i<$this->input->post('txtNumeroCotizaciones');$i++)
		{
			$idCotizacion	= $this->input->post('txtIdCotizacion'.$i);

			if($this->revisarEntrega($idCotizacion))
			{
				$this->db->where("idCotizacion",$idCotizacion);
				$this->db->update("cotizaciones_tickets_detalles",array('idEntrega'=>$idRegistro));
			}
			else
			{
				$this->db->trans_rollback();
				$this->db->trans_complete();
			
				return array('0',"Ya se ha registrado una entrega del producto");
			}
		}

		for($i=0;$i<$this->input->post('txtNumeroProductos');$i++)
		{
			$disponible		= $this->input->post('txtEntrega'.$i);
			$cantidad		= $this->input->post('txtCantidad'.$i);

			$total			= $disponible-$cantidad;

			$idProducto		= $this->input->post('txtIdProducto'.$i);
			$idDetalle		= $this->input->post('txtIdProductoDetalle'.$i);

			if($cantidad>0)
			{
				$producto		= $this->inventarioProductos->obtenerProductoStock($idProducto);

				/*if($producto->stock<$total)
				{
					$this->db->trans_rollback(); 
					$this->db->trans_complete();
				
					return array('0','No existen suficientes productos para ser entregados, revise por favor el inventario');
				}

				$data=array
				(
					"fecha"			=> $this->_fecha_actual,
					"cantidad"		=> $total,
					"entrego"		=> $this->input->post('txtChofer'),
					"idProducto"	=> $idDetalle
				);
		
				$this->db->insert("ventas_entrega_detalles",$data);*/

				$this->inventarioProductos->actualizarStockProducto($idProducto,$cantidad,'sumar');

				#$this->configuracion->registrarBitacora('Entregar producto','Envíos',$producto->nombre.', Nota: '.$this->obtenerVentaProducto($idProducto).', Cantidad: '.number_format($total,decimales)); //Registrar bitácora
			}

			//REGISTRAR EL DETALLE DE LA ENTREGA Y COMENTARIOS 
			$data=array
			(
				"idEntrega"			=> $idRegistro,
				"comentarios"		=> $this->input->post('txtComentarios'.$i),
				"cantidad"			=> $total,
				"noEntregados"		=> $cantidad,
				"idProducto"		=> $idDetalle
			);
		
			$this->db->insert("cotizaciones_tickets_entregas",$data);
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}
	}

	public function obtenerTicketFolio($folio)
	{
		return $this->db->select("idTicket, idLicencia")
		->from("cotizaciones_tickets")
		->where("folio",$folio)
		->where("idLicencia",$this->idLicencia)
		->get()->row();
	}

	public function obtenerDetallesTicket($idTicket)
	{
		return $this->db->select("idDetalle, idEntrega")
		->from("cotizaciones_tickets_detalles")
		->where("idTicket",$idTicket)
		->get()->result();
	}

	public function borrarFolioEntregas()
	{
		$this->db->trans_start();

		$folio	= $this->input->post('folio');

		$ticket	= $this->obtenerTicketFolio($folio);

		if($ticket!=null)
		{
			$detalles	= $this->obtenerDetallesTicket($ticket->idTicket);

			foreach($detalles as $row)
			{
				if($row->idEntrega>0)
				{
					$this->db->trans_rollback();
					$this->db->trans_complete();
			
					return array('0',"Error al borrar el registro, ya se ha registrado una entrega del producto");
				}

				$this->db->where('idDetalle',$row->idDetalle);
				$this->db->delete('cotizaciones_tickets_detalles');
			}

			$this->db->where('idTicket',$ticket->idTicket);
			$this->db->delete('cotizaciones_tickets');
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}
	}

	public function obtenerVentaEnvio()
	{
		$idCotizacion		= trim($this->input->post('idCotizacion'));
		
		$sql=" select a.idCotizacion, a.idCliente, a.folio as folioVenta,
		a.total, concat(b.nombre,a.folio) as folio, c.nombre as forma, c.idForma,
		d.empresa as cliente, e.nombre as ruta
		
		from cotizaciones as a
		inner join configuracion_estaciones as b
		on a.idEstacion=b.idEstacion
		
		inner join catalogos_formas as c
		on a.idForma=c.idForma

		inner join clientes as d
		on a.idCliente=d.idCliente

		inner join catalogos_rutas as e
		on a.idRuta=e.idRuta
		
		where a.idLicencia='$this->idLicencia'
		and a.idCotizacion = '$idCotizacion'
		and a.activo='1' 
		and a.cancelada='0'
		and a.pendiente='0' 
		and not exists(select e.idTicket from cotizaciones_tickets as e 
		inner join cotizaciones_tickets_detalles as f
		on f.idTicket=e.idTicket
		where f.idCotizacion=a.idCotizacion and e.idLicencia='$this->idLicencia')  ";

		$cotizacion	= $this->db->query($sql)->row();
		
		return $cotizacion!=null?$cotizacion:array('idCotizacion'=>0);
	}
}
