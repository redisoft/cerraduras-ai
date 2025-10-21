<?php
class Servicios_modelo extends CI_Model
{
    protected $fecha;
    protected $idUsuario;
	protected $idLicencia;
	protected $idTienda;
	protected $usuario;
	protected $idRol;

    function __construct()
	{
		parent::__construct();

		$this->fecha 			= date('Y-m-d H:i:s');
		$this->idUsuario 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->idTienda 		= $this->session->userdata('idTiendaActiva');
		$this->usuario 			= $this->session->userdata('nombreUsuarioSesion');
		$this->idRol 			= $this->session->userdata('role');
   }

	#SERVICIOS
	#====================================================================================================
	public function contarServicios($criterio)
	{
		$sql =" select count(idServicio) as numero
		from servicios
		where activo='1' ";
		
		$sql.=strlen($criterio)>0?" and nombre like '%$criterio%' ":'';
		
		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerServicios($numero,$limite,$criterio)
	{
		$sql =" select a.idServicio, a.nombre, c.descripcion as unidad, a.codigoInterno, a.fecha,
		(select b.costo from servicios_proveedores as b where b.idServicio=a.idServicio limit 1) as costo
		from servicios as a
		inner join unidades as c
		on c.idUnidad=a.idUnidad
		where a.activo='1' ";
		
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';
		
		$sql .= " order by a.nombre asc
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerServicio($idServicio)
	{
		$sql =" select * from servicios
		where idServicio='$idServicio' ";

		return $this->db->query($sql)->row();
	}
	
	public function comprobarServicioRegistro($nombre,$codigo,$idUnidad)
	{
		$sql=" select count(idServicio) as numero
		from servicios
		where nombre='$nombre'
		and codigoInterno='$codigo'
		and idUnidad='$idUnidad'
		and activo='1' ";
		
		return $this->db->query($sql)->row()->numero>0?false:true;
	}
	
	public function registrarServicio()#Se registra servicio
	{
		$nombre				= trim($this->input->post('txtNombreServicio'));
	   	$codigoInterno		= trim($this->input->post('txtCodigo'));
	   	$idUnidad			= trim($this->input->post('selectUnidad'));
		
		if(!$this->comprobarServicioRegistro($nombre,$codigoInterno,$idUnidad))
		{  
			return array('0',registroDuplicado);
			exit;
		}
		 
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		$data=array
		(
		   'nombre'				=> $nombre,
		   'codigoInterno'		=> $codigoInterno,
		   'idUnidad'			=> $idUnidad,
		   'fecha'				=> $this->fecha,
		   'idLicencia'			=> $this->idLicencia,
		   'idUsuario'			=> $this->idUsuario,
		);
		
		$data	= procesarArreglo($data);
		
		$this->db->insert('servicios', $data);
		$idServicio=$this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar servicio','Compras - Servicios',$nombre); //Registrar bitácora
		
		$data=array
		(
		   'idProveedor'		=> $this->input->post('txtIdProveedorServicio'),
		   'idServicio'			=> $idServicio,
		   'costo'				=> $this->input->post('txtCostoServicio'),
		);

		$this->db->insert('servicios_proveedores', $data);

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
	
	public function editarServicio()
	{
		$data=array
		(
		   'nombre'				=> trim($this->input->post('txtNombreServicio')),
		   'codigoInterno'		=> trim($this->input->post('txtCodigo')),
		   'idUnidad'			=> trim($this->input->post('selectUnidad')),
		   'fechaEdicion'		=> $this->fecha,
		   'idUsuarioEdicion'	=> $this->idUsuario,
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idServicio',$this->input->post('txtIdServicio'));
		$this->db->update('servicios',$data);
		
		$this->configuracion->registrarBitacora('Registrar servicio','Compras - Servicios',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 

	}

	public function borrarServicio($idServicio) //EL BORRADO SERA LÓGICO
	{
		$this->db->where('idServicio', $idServicio);
		$this->db->update('servicios',array('activo'=>'0'));

		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	
	#AGREGAR PROVEEDOR A SERVICIO
	#================================================================================================

	public function obtenerServiciosProveedores($idServicio)
	{
		$sql =" select a.nombre, b.*, c.empresa
		from  servicios as a
		inner join servicios_proveedores as b
		on a.idServicio=b.idServicio
		inner join proveedores as c
		on c.idProveedor=b.idProveedor
		where a.idServicio='$idServicio'  ";

		return $this->db->query($sql)->result();
	}

	public function comprobarProveedorServicio($idProveedor,$idServicio)
	{
		$sql=" select idRelacion from servicios_proveedores
		where idProveedor='$idProveedor'
		and idServicio='$idServicio'";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function asociarProveedorServicio()
	{
		if(!$this->comprobarProveedorServicio($this->input->post('idProveedor'),$this->input->post('idServicio'))) 
		{
			return array('0',registroDuplicado);
			exit;
		}
		
		$data=array
		(
			'idServicio'			=> $this->input->post('idServicio'),
			'idProveedor'			=> $this->input->post('idProveedor'),
			'costo'					=> $this->input->post('costo'),
		);
		
		$this->db->insert('servicios_proveedores', $data);
		
		$servicio	= $this->obtenerDetalleServicio($this->input->post('idServicio'),$this->input->post('idProveedor'));
		
		$this->configuracion->registrarBitacora('Asociar proveedor con servicio','Compras - Servicios',$servicio[0].', '.$servicio[1].', Costo: $'.$this->input->post('costo')); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerDetalleServicio($idServicio,$idProveedor)
	{
		$sql=" select a.nombre, c.empresa, b.costo
		from servicios as a
		inner join servicios_proveedores as b
		on a.idServicio=b.idServicio
		inner join proveedores as c
		on c.idProveedor=b.idProveedor
		where a.idServicio='$idServicio'
		and b.idProveedor='$idProveedor' ";
		
		$servicio	= $this->db->query($sql)->row();
		
		return $servicio!=null?array($servicio->nombre,$servicio->empresa,$servicio->costo):array('Sin detalles de servicio','',0);
	}
	
	public function editarCostoProveedorServicio()
	{
		$data=array
		(
			'costo'				=> $this->input->post('costo'),
		);
		
		$this->db->where('idServicio', $this->input->post('idServicio'));
		$this->db->where('idProveedor', $this->input->post('idProveedor'));
		$this->db->update('servicios_proveedores', $data);
		
		$servicio	= $this->obtenerDetalleServicio($this->input->post('idServicio'),$this->input->post('idProveedor'));
		
		$this->configuracion->registrarBitacora('Editar costo servivio','Compras - Servicios',$servicio[0].', '.$servicio[1].', Costo: $'.number_format($this->input->post('costo'),2)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function borrarProveedorServicio() //EL BORRADO SERA FISICO, LAS RELACIONES PUEDEN CONSTRUIRSE SIN PROBLEMAS
	{
		$servicio	= $this->obtenerDetalleServicio($this->input->post('idServicio'),$this->input->post('idProveedor'));
		
		$this->db->where('idServicio', $this->input->post('idServicio'));
		$this->db->where('idProveedor', $this->input->post('idProveedor'));
		$this->db->delete('servicios_proveedores');
		
		$this->configuracion->registrarBitacora('Borrar proveedor servicio','Compras - Servicios',$servicio[0].', '.$servicio[1].', Costo: $'.number_format($servicio[2],2)); //Registrar bitácora

		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//COMPRAS DE SERVICIOS
	public function contarCompras($fecha,$idCompras,$idProveedor)
	{
		$sql ="select a.idCompras, a.idProveedor,
		a.total, a.fechaCompra, b.empresa
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor)
		where a.reventa='0' 
		and a.inventario='0'
		and a.servicios='1' 
		and a.idLicencia='$this->idLicencia' ";

		$sql.=$fecha!='fecha'?" and date(a.fechaEntrega)='$fecha'":'';
		$sql.=$idCompras>0?" and a.idCompras='$idCompras'":'';
		$sql.=$idProveedor>0?" and a.idProveedor='$idProveedor'":'';
		$sql.=$this->idRol!=1?" and a.idUsuario='$this->idUsuario' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerCompras($numero,$limite,$fecha,$idCompras,$idProveedor)
	{
		$sql =" select a.idCompras, a.idProveedor, a.nombre,
		a.total, a.fechaCompra, b.empresa, a.cancelada, a.fechaEntrega,
		(select c.idSeguimiento from proveedores_seguimiento as c where c.idCompra=a.idCompras order by c.fecha desc limit 1) as idSeguimiento,
		(select coalesce(sum(c.cantidad),0) from compra_detalles as c where c.idCompra=a.idCompras) as comprados,
		(select coalesce(sum(c.cantidad),0) 
		from compras_recibido as c 
		inner join compra_detalles as d
		on d.idDetalle=c.idDetalle
		where d.idCompra=a.idCompras) as recibidos
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor)
		where a.reventa='0' 
		and a.inventario='0'
		and a.servicios='1'
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$fecha!='fecha'?" and date(a.fechaEntrega)='$fecha'":'';
		$sql.=$idCompras>0?" and a.idCompras='$idCompras'":'';
		$sql.=$idProveedor>0?" and a.idProveedor='$idProveedor'":'';
		$sql.=$this->idRol!=1?" and a.idUsuario='$this->idUsuario' ":'';
		
		$sql.=" order by a.idCompras desc ";
		$sql .= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function contarServiciosCompra($criterio,$idProveedor)
	{
		$sql =" select count(a.idServicio) as numero
		from servicios as a
		inner join servicios_proveedores as b
		on a.idServicio=b.idServicio
		inner join proveedores as c
		on c.idProveedor=b.idProveedor
		where a.activo='1' ";
		
		$sql.=strlen($criterio)>0?" and (a.nombre like '%$criterio%' or c.empresa like '%$criterio%' ) ":'';
		$sql.=$idProveedor>0?" and b.idProveedor='$idProveedor' ":'';
		
		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerServiciosCompra($numero,$limite,$criterio,$idProveedor)
	{
		$sql =" select a.idServicio, a.nombre, d.descripcion as unidad, a.codigoInterno, a.fecha,
		b.costo, b.idProveedor, c.empresa
		from servicios as a
		inner join servicios_proveedores as b
		on a.idServicio=b.idServicio
		inner join proveedores as c
		on c.idProveedor=b.idProveedor
		
		inner join unidades as d
		on a.idUnidad=d.idUnidad
		
		where a.activo='1' ";
		
		$sql.=strlen($criterio)>0?" and (a.nombre like '%$criterio%' or c.empresa like '%$criterio%' ) ":'';
		$sql.=$idProveedor>0?" and b.idProveedor='$idProveedor' ":'';
		
		$sql .= " order by a.nombre asc
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarCompra()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$cantidad				= $this->input->post('cantidad');
		$productos				= $this->input->post('productos');
		$preciosTotales			= $this->input->post('preciosTotales');
		$precioProducto			= $this->input->post('precioProducto');
		$indice					= count($cantidad);
		$idProveedor			= $this->input->post('idProveedor');
		$diasCredito			= $this->input->post('diasCredito');
		$totalCompra			= $this->input->post('kitTotal');
		$fechas					= $this->input->post('fechas');
		$descuentos				= $this->input->post('descuentos');
		$descuentosPorcentajes	= $this->input->post('descuentosPorcentajes');
		
		$data=array
		(
			'fechaCompra' 			=> $this->input->post('fecha'),
			'fechaEntrega' 			=> $this->input->post('fechaEntrega'),
			'total'					=> $this->input->post('total'),
			'subTotal'				=> $this->input->post('kitTotal'),
			'descuento'				=> $this->input->post('descuento'),
			'descuentoPorcentaje'	=> $this->input->post('descuentoPorcentaje'),
			'iva'					=> $this->input->post('iva'),
			'ivaPorcentaje'			=> $this->input->post('ivaPorcentaje'),
			'nombre'				=> $this->input->post('nombreKit'),
			'idProveedor'			=> $idProveedor,
			'idLicencia'			=> $this->idLicencia,
			'diasCredito'			=> $diasCredito,
			'folio'					=> $this->compras->obtenerFolioCompras(),
			'terminos'				=> $this->input->post('terminos'),
			'servicios'				=> '1',
			'idUsuario'				=> $this->idUsuario,
		);
		
		$data		= procesarArreglo($data);
		$this->db->insert('compras',$data);
		$idCompra	= $this->db->insert_id();
		
		//REGISTAR LA PÓLIZA
		$idCuentaCatalogo	= $this->proveedores->obtenerProveedorCuentaCatalogo($idProveedor);
		
		if($idCuentaCatalogo>0)
		{
			$this->contabilidad->registrarPolizaCompraVenta($data['fechaCompra'],$data['nombre'],$idCuentaCatalogo,$data['total'],'compra',$idCompra); 
		}
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		
		$this->configuracion->registrarBitacora('Registrar compra de servicios','Compras - Servicios',$this->input->post('nombreKit').', '.$this->proveedores->obtenerProveedorNombre($idProveedor)); //Registrar bitácora
		$this->compras->registrarPagoProgramado($this->input->post('total'),$diasCredito,$idProveedor,$this->input->post('nombreKit'),$idCompra); //Registrar el pago programado
		
		for($i=0;$i<$indice;$i++)
		{
			$data=array
			(
				'idCompra' 				=> $idCompra,
				'idMaterial' 			=> $productos[$i],
				'cantidad' 				=> $cantidad[$i],
				'total' 				=> $preciosTotales[$i],
				'precio' 				=> $precioProducto[$i],
				'fechaEntrega'			=> strlen($fechas[$i])>2?$fechas[$i]:null,
				'descuento'				=> $descuentos[$i],
				'descuentoPorcentaje'	=> $descuentosPorcentajes[$i]
			);
			
			$this->db->insert('compra_detalles',$data);
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
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//RECIBIR TODOS LOS PRODUCTOS DE MATERIA PRIMA
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function recibirTodosServicios()
	{
		$this->db->trans_start();
		
		$idCompra		= $this->input->post('idCompra');
		$factura		= $this->input->post('factura');
		$fecha			= $this->input->post('fecha');
		$remision		= $this->input->post('remision');
		
		$compra			= $this->compras->obtenerCompra($idCompra);
		$servicios		= $this->compras->obtenerServiciosComprados($idCompra);
		
		foreach($servicios as $row)
		{
			$servicio		= $this->compras->obtenerServicioCompra($row->idDetalle);
			$totalRecibido	= $this->compras->totalRecibido($row->idDetalle);
			
			if(($row->cantidad+$totalRecibido)>$servicio->cantidad)
			{
				$this->db->trans_rollback();
				$this->db->trans_complete();
				
				return array('0','Error al recibir los servicios, ya habia una recepción parcial');
			}
			
			
			$this->db->where('idDetalle',$row->idDetalle);
			$this->db->update('compra_detalles',array('recibido'=>1));
			
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$data=array
			(
				'fecha' 	=> $fecha,
				'cantidad' 	=> $row->cantidad,
				'idDetalle' => $row->idDetalle,
				'idUsuario' => $this->idUsuario,
				'recibio' 	=> $this->usuario,
				'remision' 	=> $remision,
				'factura' 	=> $factura,
			);
			
			$data	= procesarArreglo($data);
			$this->db->insert('compras_recibido',$data);
			$idRecibido	= $this->db->insert_id();
		}
		
		$this->configuracion->registrarBitacora('Recibir todos los servicios','Compras - Servicios','Compra: '.$compra->nombre); //Registrar bitácora
		
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
	
	public function confirmarRecibirServicio()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$idDetalle		= $this->input->post('txtIdDetalle');
		$cantidad		= $this->input->post('txtCantidadRecibir');
		$fecha			= $this->input->post('txtFechaRecibido');
		$servicio		= $this->compras->obtenerServicioCompra($idDetalle);
		$totalRecibido	= $this->compras->totalRecibido($idDetalle);
		$compra			= $this->compras->obtenerCompraDetalle($servicio!=null?$servicio->idCompra:0);
		#$totalUnidades	= $material->cantidad;
		
		if(($cantidad+$totalRecibido)>$servicio->cantidad)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0','Error al recibir el servicio, esta tratando de ingresar mas de lo comprado');
		}
		
		if(($cantidad+$totalRecibido)==$servicio->cantidad)
		{
			$data=array
			(
				'recibido' =>1,
			);
			
			$this->db->where('idDetalle',$idDetalle);
			$this->db->update('compra_detalles',$data);
		}
				
		$data=array
		(
			'fecha' 	=> $fecha,
			'cantidad' 	=> $cantidad,
			'idDetalle' => $idDetalle,
			'idUsuario' => $this->idTienda,
			'recibio' 	=> $this->usuario,
			'remision' 	=> $this->input->post('txtRemision'),
			'factura' 	=> $this->input->post('selectFactura'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('compras_recibido',$data);
		$idRecibido	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Recibir servicios','Compras - Servicios',$servicio->nombre.', Compra: '.$compra[0].', Cantidad: '.number_format($cantidad,2)); //Registrar bitácora

		 //SUBIR EL ARCHIVO SI ES QUE EXISTE
		$archivo 		= $_FILES['txtArchivoComprobante']['name'];
		if(strlen($archivo)>0)
		{
			$idComprobante	= $this->compras->subirFicherosCompra($servicio->idCompra,$archivo,$_FILES['txtArchivoComprobante']['size'],$idRecibido);
			
			move_uploaded_file($_FILES['txtArchivoComprobante']['tmp_name'], carpetaCompras.basename($idComprobante."_".$archivo));
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
	
	public function obtenerDetalleServicioRecibido($idRecibido)
	{
		$sql="select a.cantidad, b.total, c.nombre, d.nombre as compra
		from compras_recibido as a
		inner join compra_detalles as b
		on a.idDetalle=b.idDetalle
		inner join servicios as c
		on c.idServicio=b.idMaterial 
		inner join compras as d
		on d.idCompras=b.idCompra 
		where a.idRecibido='$idRecibido' ";
		
		$recibido	=$this->db->query($sql)->row();
		
		return $recibido!=null?array($recibido->compra,$recibido->nombre,$recibido->cantidad):array('Sin detalles de compra','',0);
	}
	
	public function borrarServicioRecibido()
	{
		$recibido	= $this->obtenerDetalleServicioRecibido($this->input->post('idRecibido'));
		
		$this->db->where('idRecibido',$this->input->post('idRecibido'));
		$this->db->delete('compras_recibido');
		
		$this->configuracion->registrarBitacora('Borrar servicio recibido','Compras - Servicios',$recibido[1].', Compra: '.$recibido[0].', Cantidad: '.number_format($recibido[2],2)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
}
?>
