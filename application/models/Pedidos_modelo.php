<?php
class Pedidos_modelo extends CI_Model
{
    protected $fecha;
    protected $idUsuario;
	protected $idLicencia;
	protected $idTienda;
	protected $usuario;
	protected $idRol;
	protected $fechaCorta;

    function __construct()
	{
		parent::__construct();

		$this->fecha 			= date('Y-m-d H:i:s');
		$this->fechaCorta 		= date('Y-m-d');
		$this->idUsuario 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->idTienda 		= $this->session->userdata('idTiendaActiva');
		$this->usuario 			= $this->session->userdata('nombreUsuarioSesion');
		$this->idRol 			= $this->session->userdata('role');
   }

	#PEDIDOS
	#====================================================================================================
	public function contarPedidos($criterio,$inicio,$fin)
	{
		$sql =" select count(a.idPedido) as numero
		from productos_pedidos as a
		inner join usuarios as c
		on c.idUsuario=a.idUsuario
		where a.idPedido>0
		and date(a.fechaPedido) between '$inicio' and '$fin' ";
		
		$sql.=strlen($criterio)>0?" and (a.folio like '%$criterio%' or c.nombre like '%$criterio%' 
		or (select b.nombre from tiendas as b where b.idTienda=a.idTienda) like '%$criterio%' ) ":'';
		
		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerPedidos($numero,$limite,$criterio,$inicio,$fin,$orden='desc')
	{
		$sql =" select a.*,
		(select b.nombre from productos_lineas as b where b.idLinea=a.idLinea limit 1) as linea,
		if(a.idTienda=0,'Matriz',
		(select b.nombre from tiendas as b where b.idTienda=a.idTienda limit 1)) as tienda,
		
		
		c.nombre as usuario,
		
		(select coalesce(sum(d.cantidad),0) from productos_pedidos_producidos as d
		inner join productos_pedidos_detalles as e
		on e.idDetalle=d.idDetalle
		where e.idPedido=a.idPedido) as producido
		
		from productos_pedidos as a
		inner join usuarios as c
		on c.idUsuario=a.idUsuario
		where a.idPedido>0
		and date(a.fechaPedido) between '$inicio' and '$fin' ";
		
		$sql.=strlen($criterio)>0?" and (a.folio like '%$criterio%' or c.nombre like '%$criterio%'
		
		or (select b.nombre from tiendas as b where b.idTienda=a.idTienda) like '%$criterio%' ) ":'';
		
		$sql .= " order by fechaPedido $orden
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerFolioPedido()
	{
		$sql =" select coalesce(max(folio),0) as folio from productos_pedidos  ";

		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function obtenerPedido($idPedido)
	{
		$sql =" select a.*,
		if(a.idTienda=0,'Matriz',
		(select b.nombre from tiendas as b where b.idTienda=a.idTienda limit 1)) as tienda,
		
		if(a.idLinea!=4,0,
		(select coalesce(sum(b.peso),0) from productos_pedidos_detalles as b where b.idPedido=a.idPedido limit 1)) as totalPeso,
		
		(select b.nombre from usuarios as b where b.idUsuario=a.idUsuario limit 1) as usuario,
		(select b.nombre from productos_lineas as b where b.idLinea=a.idLinea limit 1) as linea
		from productos_pedidos as a
		where a.idPedido='$idPedido' ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerProductosPedido($idPedido)
	{
		$sql =" select a.*, 
		b.nombre as producto, b.codigoInterno, b.precioA, b.impuesto,
		(select coalesce(sum(c.cantidad),0) from productos_pedidos_producidos as c where c.idDetalle=a.idDetalle) as producido,
		(select coalesce(sum(c.merma),0) from productos_pedidos_producidos as c where c.idDetalle=a.idDetalle) as mermas
		from productos_pedidos_detalles as a
		inner join productos as b
		on a.idProducto=b.idProducto
		where a.idPedido='$idPedido' ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarProductosPedido($idPedido)#Se registra materiales requisición
	{
		$numeroProductos	= $this->input->post('txtNumeroProductos');
		
		for($i=0;$i<$numeroProductos;$i++)
		{
			if($this->input->post('txtIdProducto'.$i)>0)
			{
				$data=array
				(
				   'idPedido'			=> $idPedido,
				   'idProducto'			=> $this->input->post('txtIdProducto'.$i),
				   'cantidad'			=> $this->input->post('txtCantidadPedido'.$i),
				   'peso'				=> $this->input->post('txtPesoPedido'.$i)>0?$this->input->post('txtPesoPedido'.$i):0,
				);
				
				$this->db->insert('productos_pedidos_detalles', $data);
			}
		}
	}
	
	public function registrarPedido()
	{
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		$data=array
		(
		   'fechaRegistro'		=> $this->fecha,
		   'fechaPedido'		=> $this->input->post('txtFechaPedido'),
		   'idUsuario'			=> $this->idUsuario,
		   'idTienda'			=> $this->input->post('selectTiendas'),
		   'comentarios'		=> $this->input->post('txtComentarios'),
		   'folio'				=> $this->obtenerFolioPedido(),
		   'idLinea'			=> $this->input->post('selectLineas'),
		);
		
		$data	= procesarArreglo($data);
		
		$this->db->insert('productos_pedidos', $data);
		$idPedido	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar pedido','Pedidos',''); //Registrar bitácora

		$this->registrarProductosPedido($idPedido);

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
	
	public function editarPedido()
	{
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		$idPedido				= $this->input->post('txtIdPedido');
		
		$data=array
		(
		   	'fechaPedido'		=> $this->input->post('txtFechaPedido'),
		   	'comentarios'		=> $this->input->post('txtComentarios'),
			'idTienda'			=> $this->input->post('selectTiendas'),
		);
		
		$data	= procesarArreglo($data);
		
		$this->db->where('idPedido', $idPedido);
		$this->db->update('productos_pedidos', $data);
		
		$this->db->where('idPedido', $idPedido);
		$this->db->delete('productos_pedidos_detalles');
		
		$this->configuracion->registrarBitacora('Editar salida','Pedidos',''); //Registrar bitácora

		$this->registrarProductosPedido($idPedido);

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
	
	public function borrarPedido($idPedido)
	{
		$this->db->trans_start(); 
		
		$this->db->where('idPedido', $idPedido);
		$this->db->delete('productos_pedidos');
		
		$this->db->where('idPedido', $idPedido);
		$this->db->delete('productos_pedidos_detalles');

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
	
	public function cancelarPedido($idPedido)
	{
		$this->db->trans_start(); 
		
		$this->db->where('idPedido', $idPedido);
		$this->db->update('productos_pedidos',array('cancelado'=>'1'));
		
		/*$this->db->where('idPedido', $idPedido);
		$this->db->delete('productos_pedidos_detalles');*/

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

	
	//REGISTRAR PRODUCIDO
	public function obtenerFolioProducido()
	{
		$sql =" select coalesce(max(folio),0) as folio from productos_pedidos  ";

		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function registrarProducido()
	{
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		for($i=0;$i<$this->input->post('txtNumeroProductos');$i++)
		{
			if($this->input->post('txtCantidadProducir'.$i)>0)
			{
				$data=array
				(
				   'fecha'				=> $this->fecha,
				   'idUsuario'			=> $this->idUsuario,
				   'cantidad'			=> $this->input->post('txtCantidadProducir'.$i),
				   'idDetalle'			=> $this->input->post('txtIdDetalle'.$i),
				   'peso'				=> $this->input->post('txtPesoProducir'.$i)>0?$this->input->post('txtPesoProducir'.$i):0,
				   'merma'				=> $this->input->post('txtMerma'.$i),
				  # 'folio'				=> $this->obtenerFolioProducido(),
				);
				
				$data	= procesarArreglo($data);
				
				$this->db->insert('productos_pedidos_producidos', $data);
			}
			
			
			#$this->configuracion->registrarBitacora('Registrar pedido','Pedidos',''); //Registrar bitácora
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
	
	public function obtenerProductoPedido($idDetalle)
	{
		$sql =" select a.*, 
		b.nombre as producto, b.codigoInterno, d.folio,
		(select coalesce(sum(c.cantidad),0) from productos_pedidos_producidos as c where c.idDetalle=a.idDetalle) as producido
		from productos_pedidos_detalles as a
		inner join productos as b
		on a.idProducto=b.idProducto
		inner join productos_pedidos as d
		on d.idPedido=a.idPedido
		where a.idDetalle='$idDetalle' ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerProducidosProducto($idDetalle)
	{
		$sql =" select * from productos_pedidos_producidos 	
		where idDetalle='$idDetalle' ";

		return $this->db->query($sql)->result();
	}
	
	public function editarProducidoProducto()
	{
		$data=array
		(
			'cantidad'			=> $this->input->post('cantidad'),
		);
		
		$this->db->where('idProducido', $this->input->post('idProducido'));
		$this->db->update('productos_pedidos_producidos', $data);
		
		return $this->db->affected_rows()==1?'1':'0';
	}
	
	public function borrarProducidoProducto($idProducido)
	{
		$this->db->where('idProducido', $idProducido);
		$this->db->delete('productos_pedidos_producidos');
		
		return $this->db->affected_rows()==1?'1':'0';
	}
	
	//REPORTE
	
	public function obtenerTotalesPedido($idPedido)
	{
		$sql =" select coalesce( sum(b.precioA * (select coalesce(sum(c.cantidad),0) from productos_pedidos_producidos as c where c.idDetalle=a.idDetalle)),0 )  as total
		from productos_pedidos_detalles as a
		inner join productos as b
		on a.idProducto=b.idProducto
		where a.idPedido='$idPedido' ";

		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerImpuestosPedido($idPedido)
	{
		$sql =" select coalesce( sum((b.precioA * b.impuesto/100) * (select coalesce(sum(c.cantidad),0) from productos_pedidos_producidos as c where c.idDetalle=a.idDetalle)),0 )  as total
		from productos_pedidos_detalles as a
		inner join productos as b
		on a.idProducto=b.idProducto
		where a.idPedido='$idPedido' ";

		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerReportePedido($idPedido)
	{
		$sql =" select * from productos_pedidos_reporte
		where idPedido='$idPedido' ";

		return $this->db->query($sql)->row();
	}
	
	public function registrarReporte()
	{
		$idPedido	= $this->input->post('txtIdPedidoReporte');
		$idReporte	= $this->input->post('txtIdReporte');
		
		$data=array
		(
			'idPedido'			=> $idPedido,
			'manoObra'			=> $this->input->post('txtManoObra'),
			'cuotaSindical'		=> $this->input->post('txtCuotaSindical'),
			'primaDominical'	=> $this->input->post('txtPrimaDominical'),
			
			'manoTotal'			=> $this->input->post('txtManoTotal'),
			'cuotaTotal'		=> $this->input->post('txtCuotaTotal'),
			'primaTotal'		=> $this->input->post('txtPrimaTotal'),
			
			'maestro'			=> $this->input->post('txtMaestro'),
			'oficial'			=> $this->input->post('txtOficial'),
		);
		
		if($this->input->post('txtTipoRerporte')=="pasteles")
		{
			$data=array
			(
				'idPedido'			=> $idPedido,
				'pagoKg'			=> $this->input->post('txtPagoKg'),
				'costoKg'			=> $this->input->post('txtCostoKg'),
				'maestro'			=> $this->input->post('txtPagoMaestro'),
			);
		}
		
		if($idReporte>0)
		{
			$this->db->where('idReporte', $idReporte);
			$this->db->update('productos_pedidos_reporte', $data);
		}
		else
		{
			$this->db->insert('productos_pedidos_reporte', $data);
		}
		
		
		return $this->db->affected_rows()==1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	//CONTEOS
	
	public function contarConteos($criterio,$inicio,$fin)
	{
		$sql =" select count(a.idConteo) as numero
		from productos_pedidos_conteo as a
		inner join usuarios as c
		on c.idUsuario=a.idUsuario
		where a.idConteo>0
		and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=strlen($criterio)>0?" and (a.folio like '%$criterio%' or c.nombre like '%$criterio%' 
		or (select b.nombre from tiendas as b where b.idTienda=a.idTienda) like '%$criterio%' ) ":'';
		
		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerConteos($numero,$limite,$criterio,$inicio,$fin,$orden='desc')
	{
		$sql =" select a.*,
		if(a.idTienda=0,'Matriz',
		(select b.nombre from tiendas as b where b.idTienda=a.idTienda limit 1)) as tienda,
		c.nombre as usuario
		from productos_pedidos_conteo as a
		inner join usuarios as c
		on c.idUsuario=a.idUsuario
		where a.idConteo>0
		and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=strlen($criterio)>0?" and (a.folio like '%$criterio%' or c.nombre like '%$criterio%'
		
		or (select b.nombre from tiendas as b where b.idTienda=a.idTienda) like '%$criterio%' ) ":'';
		
		$sql .= " order by fecha $orden
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerConteo($idConteo)
	{
		$sql =" select a.*,
		if(a.idTienda=0,'Matriz',
		(select b.nombre from tiendas as b where b.idTienda=a.idTienda limit 1)) as tienda,
		c.nombre as usuario
		from productos_pedidos_conteo as a
		inner join usuarios as c
		on c.idUsuario=a.idUsuario
		where a.idConteo='$idConteo' ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerProductosConteo($idConteo)
	{
		$sql =" select a.idProducto, a.cantidad, b.nombre, b.codigoInterno
		from productos_pedidos_conteo_detalles as a
		inner join productos as b
		on a.idProducto=b.idProducto
	  	where  b.activo='1'
		and a.idConteo='$idConteo'
		order by a.idDetalle desc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerFolioConteo()
	{
		$sql =" select coalesce(max(folio),0) as folio from productos_pedidos_conteo  ";

		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function obtenerProductosPedidosConteo()
	{
		$sql =" select a.idProducto, b.nombre, b.codigoInterno
		from productos_pedidos_detalles as a
		inner join productos as b
		on a.idProducto=b.idProducto
		
		inner join productos_pedidos as c
		on a.idPedido=c.idPedido
		
		where c.cancelado='0'
		and b.activo='1'
		and date(c.fechaPedido) = (select date_sub('$this->fechaCorta', interval 1 day))
		group by a.idProducto ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarProductosConteo($idConteo)#Se registra materiales requisición
	{
		$numeroProductos	= $this->input->post('txtNumeroProductos');
		
		for($i=0;$i<$numeroProductos;$i++)
		{
			if($this->input->post('txtIdProducto'.$i)>0)
			{
				if($this->input->post('txtCantidadConteo'.$i)>0)
				{
					$data=array
					(
					   'idConteo'			=> $idConteo,
					   'idProducto'			=> $this->input->post('txtIdProducto'.$i),
					   'cantidad'			=> $this->input->post('txtCantidadConteo'.$i),
					);
					
					$this->db->insert('productos_pedidos_conteo_detalles', $data);
				}
			}
		}
	}
	
	public function registrarConteo()
	{
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		$folio	= $this->obtenerFolioConteo();
		
		$data=array
		(
		   'fecha'				=> $this->fecha,
		   'idUsuario'			=> $this->idUsuario,
		   'idTienda'			=> $this->input->post('selectTiendas'),
		   'comentarios'		=> $this->input->post('txtComentarios'),
		   'folio'				=> $folio,
		   #'consecutivo'		=> conteo,
		);
		
		$data	= procesarArreglo($data);
		
		$this->db->insert('productos_pedidos_conteo', $data);
		$idConteo	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar conteo','Conteo de pan',''); //Registrar bitácora

		$this->registrarProductosConteo($idConteo);

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
	
	public function editarConteo()
	{
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		$idConteo	= $this->input->post('txtIdConteo');
		
		$data=array
		(
		   'comentarios'		=> $this->input->post('txtComentarios'),
		);
		
		$data	= procesarArreglo($data);
		
		$this->db->where('idConteo', $idConteo);
		$this->db->update('productos_pedidos_conteo', $data);
		
		
		$this->db->where('idConteo', $idConteo);
		$this->db->delete('productos_pedidos_conteo_detalles');
		#$this->configuracion->registrarBitacora('Editar conteo','Conteo de pan',''); //Registrar bitácora

		$this->registrarProductosConteo($idConteo);

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
	
	public function borrarConteo($idConteo)
	{
		$this->db->trans_start(); 
		
		$this->db->where('idConteo', $idConteo);
		$this->db->delete('productos_pedidos_conteo');
		
		$this->db->where('idConteo', $idConteo);
		$this->db->delete('productos_pedidos_conteo_detalles');

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
?>
