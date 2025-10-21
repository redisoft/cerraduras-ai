<?php
class Temporal_modelo extends CI_Model
{
	protected $fecha;
	protected $idLicencia;
	protected $idUsuario;
	protected $CI;
	protected $tiendaLocal;

	function __construct()
	{
		parent::__construct();
		
        $this->idUsuario 		= $this->session->userdata('id');
		$this->fecha 			= date('Y-m-d H:i:s');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->tiendaLocal		= $this->session->userdata('tiendaLocal');
		$this->CI				= & get_instance();
	}
	
	public function registrarDatosTemporal($id,$operacion='registrar',$modulo='ventas')
	{
		$data=array
		(
			'fecha'				=> $this->fecha,
			'id'				=> $id,
			'operacion'			=> $operacion,
			'modulo'			=> $modulo,
		);
		
		$this->db->insert('temporal_datos',$data);
	}
	
	public function registrarMovimiento($id,$modulo='ventas')
	{
		if($this->tiendaLocal=='1')
		{
			$data=array
			(
				'fecha'				=> $this->fecha,
				'id'				=> $id,
				'modulo'			=> $modulo,
				'idUsuario'			=> $this->idUsuario,
			);

			$this->db->insert('configuracion_movimientos',$data);
		}
	}

	public function obtenerSucursalActiva()
	{
		$this->load->database('default',TRUE);
		
		$sql="select * from configuracion_sucursales
		where activa='1'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerMovientosModulo($modulo='')
	{
		$this->load->database('default',TRUE);
		
		$sql="select * from configuracion_movimientos
		where modulo='$modulo' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCotizaciones($idCotizacion)
	{
		$this->load->database('default',TRUE);
		
		$sql="select * from cotizaciones
		where idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->row_array();
	}
	
	public function obtenerProductosVenta($idCotizacion)
	{
		$this->load->database('default',TRUE);
		
		$sql="select * from cotiza_productos
		where idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerProductosEntregados($idProducto=0)
	{
		$this->load->database('default',TRUE);
		
		$sql=" select a.* 
		from ventas_entrega_detalles as a
		where a.idProducto='$idProducto' ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerImpuestos($idProducto=0)
	{
		$this->load->database('default',TRUE);
		
		$sql=" select a.* 
		from cotiza_productos_impuestos as a
		where a.idProducto='$idProducto' ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerCobros($idVenta=0)
	{
		$this->load->database('default',TRUE);
		
		$sql="select * from catalogos_ingresos
		where idVenta='$idVenta' ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerFactura($idCotizacion)
	{
		$this->load->database('default',TRUE);
		
		$sql="select * from facturas
		where idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->row_array();
	}
	
	public function obtenerDetallesFactura($idFactura=0)
	{
		$this->load->database('default',TRUE);
		
		$sql="select * from facturas_detalles
		where idFactura='$idFactura' ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerImpuestoFactura($idDetalle)
	{
		$this->load->database('default',TRUE);
		
		$sql="select * from facturas_detalles_impuestos
		where idDetalle='$idDetalle' ";
		
		return $this->db->query($sql)->row_array();
	}
	
	public function actualizacionLocalNube()
	{
		$this->load->helper('base');
		
		$sucursal		= $this->obtenerSucursalActiva();
		
		if($sucursal!=null)
		{
			$dsn			= obtenerConexion($sucursal);
			
			$remota			= $this->load->database($dsn,TRUE);
			
			$remota->trans_start();
		
			$registros		= $this->obtenerMovientosModulo('ventas');

			foreach($registros as $row)
			{
				$venta		= $this->obtenerCotizaciones($row->id);
				
				if($venta!=null)
				{
					$productos	= $this->obtenerProductosVenta($venta['idCotizacion']);
					$cobros		= $this->obtenerCobros($venta['idCotizacion']);
					$factura	= $this->obtenerFactura($venta['idCotizacion']);
					
					$remota		= $this->load->database($dsn,TRUE);

					unset($venta['idCotizacion']); 

					$remota->insert('cotizaciones',$venta);
					$idCotizacion	= $remota->insert_id();
					
					if($cobros!=null)
					{
						foreach($cobros as $cob)
						{
							$remota		= $this->load->database($dsn,TRUE);

							unset($cob['idIngreso']); 
							$cob['idVenta']	= $idCotizacion;
							
							$remota->insert('catalogos_ingresos',$cob);
						}
					}
					
					if($venta['prefactura']=='1')
					{

						if($factura!=null)
						{
							$detalles	= $this->obtenerDetallesFactura($factura['idFactura']);
							
							$remota		= $this->load->database($dsn,TRUE);
							unset($factura['idFactura']); 
							$factura['idCotizacion']	= $idCotizacion;
							$remota->insert('facturas',$factura);
							$idFactura	= $remota->insert_id();
							
							if($detalles!=null)
							{
								foreach($detalles as $det)
								{
									$impuesto	= $this->obtenerImpuestoFactura($det['idDetalle']);
									
									$remota				= $this->load->database($dsn,TRUE);
									unset($det['idDetalle']); 
									$det['idFactura']	= $idFactura;
									$remota->insert('facturas_detalles',$det);
									$idDetalle	= $remota->insert_id();
									
									if($impuesto!=null)
									{
										unset($impuesto['idImpuesto']); 
										$impuesto['idDetalle']	= $idDetalle;
										
										$remota->insert('facturas_detalles_impuestos',$impuesto);
									}
								}
							}
						}
					}
					

					if($productos!=null)
					{
						foreach($productos as $pro)
						{
							$entregas	= $this->obtenerProductosEntregados($pro['idProducto']);
							$impuestos	= $this->obtenerImpuestos($pro['idProducto']);

							unset($pro['idProducto']); 

							$pro['idCotizacion']	= $idCotizacion;

							$remota			= $this->load->database($dsn,TRUE);

							$remota->insert('cotiza_productos',$pro);
							$idProducto	= $remota->insert_id();

							if($entregas!=null)
							{
								foreach($entregas as $ent)
								{
									unset($ent['idEntrega']); 
									
									$ent['idProducto']	= $idProducto;

									$remota			= $this->load->database($dsn,TRUE);
									$remota->insert('ventas_entrega_detalles',$ent);
									
									
									#$this->load->database('default',TRUE);
									$this->actualizarStockProductoSucursal($pro['idProduct'],$ent['cantidad'],'restar',$sucursal->idLicencia,$remota);
								}
							}
							
							if($impuestos!=null)
							{
								foreach($impuestos as $imp)
								{
									unset($imp['idDetalle']); 
									
									$imp['idProducto']	= $idProducto;

									$remota			= $this->load->database($dsn,TRUE);
									$remota->insert('cotiza_productos_impuestos',$imp);
								}
							}

						}
					}
				}
				
				$this->load->database('default',TRUE);
				
				$this->db->where('idMovimiento',$row->idMovimiento);
				$this->db->delete('configuracion_movimientos');
			}
			
			$remota			= $this->load->database($dsn,TRUE);
			
			if ($remota->trans_status() === FALSE)
			{
				$remota->trans_rollback();
				$remota->trans_complete();

				return array('0','Error al procesar la base de datos');
			}
			else
			{
				$remota->trans_commit();
				$remota->trans_complete();

				return array('1','Las ventas se han cargado de forma correcta hacia el servidor');
			}
		}
	}
	
	public function obtenerProductoStockLicencia($idProducto,$idLicencia,$remota)
	{
		$sql=" select a.nombre, a.servicio, b.idInventario, a.idProducto, b.stock 
		from productos as a
		inner join productos_inventarios as b
		on a.idProducto=b.idProducto
		where a.idProducto='$idProducto'
		and b.idLicencia='$idLicencia' ";
		
		return $remota->query($sql)->row();
	}
	
	public function actualizarStockProductoSucursal($idProducto,$cantidad,$criterio='restar',$idLicencia,$remota)
	{
		$producto	= $this->obtenerProductoStockLicencia($idProducto,$idLicencia,$remota);
		
		if($producto!=null)
		{
			$data=array
			(
				'stock'	=> $criterio=='restar'?$producto->stock-$cantidad:$producto->stock+$cantidad
			);
			
			$remota->where('idInventario',$producto->idInventario);
			$remota->update('productos_inventarios',$data);
		}
	}
	
	//DESCARGAR LA INFORMACIÓN DE LOS PRODUCTOS
	
	public function actualizacionNubeLocal($fichero)
	{
		$this->load->database('default',TRUE);
			
		ob_start();
		readgzfile($fichero);
		$data	= ob_get_clean();
		#ob_end_clean();

		$this->db->trans_start();
		$this->db->query('set foreign_key_checks=0;');
		
		$this->vaciarTablasLocales();

		//estas dos lineas me permitieron individualizar las consultas
		$data 		= nl2br($data);
		$data_arr 	= explode('<br />', $data); 

		foreach($data_arr as $query)
		{
			//Solo ejecutar los puros inserts
			$pos = stripos($query, 'INSERT');
						
			if($pos!==false && $pos<5) 
			{
			   $this->db->query($query);
			}
		}
	
		
		$this->db->query('set foreign_key_checks=1;');

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array('0','Error al procesar la base de datos');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array('1','El inventario se ha descargado de forma correcta desde el servidor');
		}
	}
	
	
	//SINCRONIZAR AL SERVIDOR
	public function vaciarTablasLocales()
	{
		$this->db->truncate('productos');
		$this->db->truncate('productos_inventarios');
		$this->db->truncate('productos_marcas');
		$this->db->truncate('productos_lineas');
		$this->db->truncate('proveedores');
		$this->db->truncate('rel_producto_proveedor');
		$this->db->truncate('productos_departamentos');
	}
	
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//SINCRONIZAR PRODUCTOS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function sincronizarProductosTemporales()
	{
		$sql=" select * from temporal_datos
		where modulo='productos' ";
		
		$this->CI->load->database('default', TRUE);
		$productos	= $this->db->query($sql)->result();
		
		foreach($productos as $row)
		{
			$this->CI->load->database('default', TRUE);

			if($row->operacion=='inventario')
			{
				//OBTENER PRODUCTO INVENTARIO
				$sql		= "select idProducto, stock from productos where idProducto='$row->id' ";
				$producto	= $this->db->query($sql)->row();
				
				if($producto!=null)
				{
					$remota 	= $this->CI->load->database('remota', TRUE);
					
					$remota->where('idProducto',$producto->idProducto);
					$remota->update('productos',array('stock'=>$producto->stock));
				}
			}
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//SINCRONIZAR PRODUCTOS DE LA NUBE
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function sincronizarProductosNubeTemporales()
	{
		$sql=" select * from temporal_datos
		where modulo='productos' ";
		
		$remota 	= $this->CI->load->database('remota', TRUE);
		$productos	= $remota->query($sql)->result();
		
		foreach($productos as $row)
		{
			$remota 	= $this->CI->load->database('remota', TRUE);

			if($row->operacion=='inventario')
			{
				//OBTENER PRODUCTO INVENTARIO
				$sql		= "select idProducto, stock, precioA, precioB from productos where idProducto='$row->id' ";
				$producto	= $remota->query($sql)->row();
				
				if($producto!=null)
				{
					$this->CI->load->database('default', TRUE);
					
					$this->db->where('idProducto',$producto->idProducto);
					$this->db->update('productos',array('stock'=>$producto->stock));
				}
			}
			
			if($row->operacion=='registrar')
			{
				$sql		= "select * from productos where idProducto='$row->id' ";
				$producto	= $remota->query($sql)->row_array();
				
				if($producto!=null)
				{
					$this->CI->load->database('default', TRUE);
					
					$this->db->insert('productos',$producto);
				}
			}
			
		}
	}

}
	