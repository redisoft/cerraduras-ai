<?php
class InventarioProductos_modelo extends CI_Model
{
    protected $_fecha_actual;
    protected $_table;
    protected $_user_id;
    protected $_user_name;
	protected $idLicencia;
	protected $idTienda;
	protected $ordenProductos;

    function __construct()
	{
		parent::__construct();
		
		$this->config->load('datatables',TRUE);
		
		$this->_table 			= $this->config->item('datatables');
		$this->_fecha_actual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_user_id 		= $this->session->userdata('id');
		$this->_user_name 		= $this->session->userdata('name');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->idTienda 		= $this->session->userdata('idTiendaActiva');
		$this->ordenProductos 	= $this->session->userdata('ordenProductos');
		
		#$this->load->model('materiales_modelo','materiales');
   }
   
   //=================TIENDAS==================//
   public function enviarProductosTienda()
   {
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$cantidad		=$this->input->post('cantidad');
		$idProducto		=$this->input->post('idProducto');
		$idTienda		=$this->input->post('idTienda');
		
		$data=array
		(
			"fecha"			=>$this->_fecha_actual,
			"cantidad"		=>$cantidad,
			"idTienda"		=>$idTienda,
			"idProducto"	=>$idProducto
		);
		
		$this->db->insert("tiendas_recepciones",$data);
		
		#---------------------------------------------------------------------------------------#
		
		$sql="select * from tiendas_productos
		where idProducto='$idProducto'
		and idTienda='$idTienda'";
		
		$query=$this->db->query($sql);
		
		if($query->num_rows()>0)
		{
			$data=array
			(
				"cantidad"		=>$query->row()->cantidad+$cantidad,
			);
			
			$this->db->where('idProducto',$idProducto);
			$this->db->where('idTienda',$idTienda);
			$this->db->update('tiendas_productos',$data);
		}
		else
		{
			$data=array
			(
				"cantidad"		=>$cantidad,
				"idProducto"	=>$idProducto,
				"idTienda"		=>$idTienda,
			);
			
			$this->db->insert('tiendas_productos',$data);
		}
		
		#---------------------------------------------------------------------------------------#
		
		$sql="select a.*, b.piezas, b.nombre, b.stock 
		from rel_producto_produccion as a
		inner join produccion_productos as b
		on idProductoProduccion=b.idProducto
		where a.idProducto='".$idProducto."'
		and b.idLicencia='$this->idLicencia'";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$piezasEnviadas=$row->cantidad*$cantidad;
			
			if($piezasEnviadas>$row->stock)
			{
				$this->db->trans_rollback(); #No existen suficientes productos para enviarlos a la tienda
				$this->db->trans_complete();
			
				return "2";
			}
			
			$data=array
			(
				'stock'	=>$row->stock-$piezasEnviadas
			);
			
			$this->db->where('idProducto',$row->idProductoProduccion);
			$this->db->update('produccion_productos',$data);
		}
		
		#---------------------------------------------------------------------------------------#
		
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
   }
   
   public function obtenerProductosEnvio()
   {
	   $nombre=$this->input->post('nombre');
	   $nombre=str_replace("'","",$nombre);
	   
	   $sql="select * from productos
	   where idLicencia='$this->idLicencia'
	   and activo='1'
	   and nombre like '%$nombre%' 
	   and servicio='0' 
	   and materiaPrima='0' ";
	   
	   return $this->db->query($sql)->result();
   }
   
   	public function comprobarProductoRegistro($nombre,$codigoBarras,$codigoInterno)
	{
		$sql="select idProducto 
		from productos
		where nombre='$nombre'
		and codigoBarras='$codigoBarras'
		and codigoInterno='$codigoInterno'";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}

	public function registrarProducto($imagen='')
	{
		if(!$this->comprobarProductoRegistro($this->input->post('txtNombre'),$this->input->post('txtCodigoBarras'),$this->input->post('txtCodigoInterno')))
		{
			return array('0',registroDuplicado);
		}
		
		$this->db->trans_start(); 
		
		$impuestos				= explode('|',$this->input->post('selectImpuestos'));
		$idProveedor			= $this->input->post('selectProveedores');
		$idMarca				= $this->input->post('selectMarcas');
		$solo					= $this->input->post('chkSolo')=='1'?'1':'0';
		
		#--------------------------------------------------------------------------------------------#
		$data=array
		(
		   	'nombre'			=> $this->input->post('txtNombre'),
		   	'fecha'				=> $this->_fecha_actual,
		   	'idLicencia'		=> $this->idLicencia,
		   	'codigoBarras'		=> $this->input->post('txtCodigoBarras'),
		   	'idUsuario'			=> $this->_user_id,
		   	'codigoInterno'		=> $this->input->post('txtCodigoInterno'),
		   	#'unidad'			=> $this->input->post('txtUnidad'),
		   	'reventa'			=> 1,
		   	'precioA'			=> $this->input->post('txtPrecioA'),
			'precioB'			=> $this->input->post('txtPrecioB'),
			'precioC'			=> $this->input->post('txtPrecioC'),
			'precioD'			=> $this->input->post('txtPrecioD'),
			'precioE'			=> $this->input->post('txtPrecioE'),
			'unidad'			=> $this->input->post('txtUnidad'),
		   	'idProveedor'		=> $this->input->post('selectProveedores'),
		   	'idLinea'			=> $this->input->post('selectLineas'),
			'idSublinea'		=> $this->input->post('selectSubLineas'),
		   	'upc'				=> $this->input->post('txtUpc'),
		   	'sku'				=> $this->input->post('txtSku'),
		   	'stock'				=> $this->input->post('txtInventarioInicial'),
			'idCuentaCatalogo'	=> $this->input->post('txtIdCuentaCatalogo'),
			
			#'impuesto'			=> $this->input->post('selectImpuestos'),
			
			'idDepartamento'	=> $this->input->post('selectDepartamentos'),
			'idMarca'			=> $idMarca,
			'descripcion'		=> $this->input->post('txtDescripcion'),
			
			'idUnidad'			=> $this->input->post('txtIdUnidad'),
			'idClave'			=> $this->input->post('txtIdClave'),
			
			'idImpuesto'		=> $impuestos[0],
			'precioImpuestos'	=> $this->input->post('txtPrecioImpuestos'),
			'solo'				=> $solo,
			'idPedimento'		=> $this->input->post('txtIdPedimentoRegistro'),
		);
		
		if(strlen($imagen)>0)
		{
			$data['imagen']		= $imagen;
		}
		
		if(sistemaActivo=='olyess')
		{
			$data['rebanadas']			= $this->input->post('txtNumeroRebanadas');
			$data['precioRebanada']		= $this->input->post('txtPrecioRebanada');
		}
		
		if(sistemaActivo=='cerraduras')
		{
			$data['cantidadMayoreo']			= $this->input->post('txtApartirB');
			$data['stockMinimo']				= $this->input->post('txtStockMinimo');
			$data['stockMaximo']				= $this->input->post('txtStockMaximo');
		}
		
		$data	= procesarArreglo($data);
		$this->db->insert('productos', $data);
		$idProducto	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar producto','Catálogo de productos',$this->input->post('txtNombre')); //Registrar bitácora

		#--------------------------------------------------------------------------------------------#
		$data=array
		(
			'idProducto'			=> $idProducto,
			'idProveedor'			=> $idProveedor,
			'precio'				=> $this->input->post('txtCosto'),
		);

		$this->db->insert('rel_producto_proveedor', $data);
		
		#--------------------------------------------------------------------------------------------#
		
		//REGISTRAR EL STOCK INICIAL

		if($solo=='0')
		{
			$this->registrarStockLicencias($idProducto,$this->input->post('txtInventarioInicial'),1);
		}
		else
		{
			$data=array
			(
				'idProducto'			=> $idProducto,
				'idLicencia'			=> $this->idLicencia,
				'stock'					=> $this->input->post('txtInventarioInicial'),
				'precioA'				=> $this->input->post('txtPrecioA'),
				'precioB'				=> $this->input->post('txtPrecioB'),
				'precioC'				=> $this->input->post('txtPrecioC'),
				'precioD'				=> $this->input->post('txtPrecioD'),
				'precioE'				=> $this->input->post('txtPrecioE'),
			);
			
			$this->db->insert('productos_inventarios', $data);
		}
		
		
		if($idMarca>0 and $idProveedor>0)
		{
			$this->registrarMarcaProveedor($idMarca,$idProveedor);
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
			
			return array('1',$idProducto);
		}   
	}
	
	public function revisarMarcaProveedor($idMarca,$idProveedor)
	{
		$sql="select idRelacion
		from productos_proveedores_marcas
		where idMarca=$idMarca
		and idProveedor=$idProveedor";
		
		$registro=$this->db->query($sql)->row();
		
		return $registro!=null?false:true;
	}
	
	public function registrarMarcaProveedor($idMarca,$idProveedor)
	{
		if(!$this->revisarMarcaProveedor($idMarca,$idProveedor)) return false;
		
		$data=array
		(
			'idMarca'				=> $idMarca,
			'idProveedor'			=> $idProveedor,
		);

		$this->db->insert('productos_proveedores_marcas', $data);
		
	}
	
	public function registrarStockLicencias($idProducto,$stock,$precios=0,$precioA=0,$precioB=0,$precioC=0,$precioD=0,$precioE=0)
	{
		$licencias	= $this->configuracion->obtenerLicencias($idProducto);
		
		foreach($licencias as $row)
		{
			$data=array
			(
				'idProducto'			=> $idProducto,
				'idLicencia'			=> $row->idLicencia,
				'stock'					=> $row->idLicencia==$this->idLicencia?$stock:0,
				'precioA'				=> $precioA,
				'precioB'				=> $precioB,
				'precioC'				=> $precioC,
				'precioD'				=> $precioD,
				'precioE'				=> $precioE,
			);
			
			if($precios==1)
			{
				$data['precioA']			= $this->input->post('txtPrecioA');
				$data['precioB']			= $this->input->post('txtPrecioB');
				$data['precioC']			= $this->input->post('txtPrecioC');
				$data['precioD']			= $this->input->post('txtPrecioD');
				$data['precioE']			= $this->input->post('txtPrecioE');
			}
	
			$this->db->insert('productos_inventarios', $data);
		}
	}
	
	public function obtenerProveedoresAsociados($idProducto)
	{
		$sql="select a.*, b.empresa
		from rel_producto_proveedor as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where a.idProducto='$idProducto'";
		
		return $this->db->query($sql)->result();
	}
	
	public function editarCostoProveedor()
	{
		$data=array
		(
			'precio'				=> $this->input->post('precio'),
		);
		
		$this->db->where('idProducto', $this->input->post('idProducto'));
		$this->db->where('idProveedor', $this->input->post('idProveedor'));
		$this->db->update('rel_producto_proveedor', $data);
		
		$producto	= $this->obtenerDetalleProducto($this->input->post('idProducto'),$this->input->post('idProveedor'));
		
		$this->configuracion->registrarBitacora('Editar costo producto','Catálogo de productos',$producto[0].', '.$producto[1].', Costo: $'.number_format($this->input->post('precio'),2)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function borrarProveedorProducto()
	{
		$producto	= $this->obtenerDetalleProducto($this->input->post('idProducto'),$this->input->post('idProveedor'));
		
		$this->db->where('idProducto', $this->input->post('idProducto'));
		$this->db->where('idProveedor', $this->input->post('idProveedor'));
		$this->db->delete('rel_producto_proveedor');

		$this->configuracion->registrarBitacora('Borrar proveedor producto','Catálogo de productos',$producto[0].', '.$producto[1].', Costo: $'.number_format($producto[2],2)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function asociarProveedorProducto()
	{
		$data=array
		(
			'idProducto'			=>$this->input->post('idProducto'),
			'idProveedor'			=>$this->input->post('idProveedor'),
			'precio'				=>$this->input->post('precio'),
		);

		$this->db->insert('rel_producto_proveedor', $data);
		
		$producto	= $this->obtenerDetalleProducto($this->input->post('idProducto'),$this->input->post('idProveedor'));
		
		$this->configuracion->registrarBitacora('Asociar proveedor con producto','Catálogo de productos',$producto[0].', '.$producto[1].', Costo: $'.$this->input->post('precio')); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function obtenerDetalleProducto($idProducto,$idProveedor)
	{
		$sql=" select a.nombre, c.empresa, b.precio
		from productos as a
		inner join rel_producto_proveedor as b
		on a.idProducto=b.idProducto
		inner join proveedores as c
		on c.idProveedor=b.idProveedor
		where a.idProducto='$idProducto'
		and b.idProveedor='$idProveedor' ";
		
		$producto	= $this->db->query($sql)->row();
		
		return $producto!=null?array($producto->nombre,$producto->empresa,$producto->precio):array('Sin detalles de producto','',0);
	}
	
	public function obtenerDetallesProducto($idProducto)
	{
		$sql=" select a.idProducto,  a.nombre as descripcion, a.idPeriodo, a.impuesto, a.idImpuesto, a.idSubLinea, a.idClave,
		a.imagen, a.codigoBarras, a.codigoInterno, c.precioA, a.costo, a.idUnidad, a.precioImpuestos, c.stock, a.idPedimento, concat(d.anio, '  ',d.aduana, '  ',d.patente, '  ',d.digitos) as pedimento,
		c.precioB, c.precioC, c.precioD, c.precioE, c.stock, a.fecha, a.descripcion as descripcionProducto,
		a.idLinea, a.upc, a.sku, a.plazo, a.idCuentaCatalogo, a.idDepartamento, a.idMarca, 
		(select concat(b.descripcion,'(',b.numeroCuenta,')') from fac_catalogos_cuentas_detalles as b where b.idCuentaCatalogo=a.idCuentaCatalogo) cuenta ,
		(select concat(b.clave,', ', b.nombre) from fac_catalogos_unidades as b where b.idUnidad=a.idUnidad) as unidad,
		(select concat(b.clave,', ', b.nombre) from fac_catalogos_claves_productos as b where b.idClave=a.idClave) as claveProducto
		".(sistemaActivo=='olyess'?',a.rebanadas, a.precioRebanada':'')."
		
		".(sistemaActivo=='cerraduras'?',a.cantidadMayoreo, a.stockMinimo, a.stockMaximo, 
		(select e.precio from rel_producto_proveedor as e where e.idProducto=a.idProducto limit 1) as costo ':'')."
		
		from productos as a
		
		inner join productos_inventarios as c
		on c.idProducto=a.idProducto

		left join productos_pedimentos as d
		on d.idPedimento=a.idPedimento
		
		where a.idProducto='$idProducto' 
		and c.idLicencia='$this->idLicencia'";
		
		return $this->db->query($sql)->row();
	}
	
	public function actualizarCantidadesCaja($id)
	{
		$sql="select porcentajeGanancia
		from productos where id='$id'
		and idLicencia='$this->idLicencia'";
		
		$query=$this->db->query($sql);
		
		$query=$query->row();
		
		#$this->materiales->actualizarDetalleCajasProductos($id,$query->porcentajeGanancia);
	}
	
	public function obtenerDetalleCaja($idProductoCaja,$idProducto)
	{
		$sql="select cantidad
		from rel_producto_produccion 
		where idProducto='$idProductoCaja'
		and idProductoProduccion='$idProducto'";
		
		$query=$this->db->query($sql);
		
		return $query->row();
		
	}
	
	public function editarProductoDetalleConfirmar()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		$idProducto=$this->input->post('id');
		$idProductoProduccion=$this->input->post('idProductoProduccion');
		
		$data=array
		(
		   'cantidad'	=>$this->input->post('cantidad'),
		 );	
		 
		 $this->db->where('idProducto',$idProducto);
		 $this->db->where('idProductoProduccion',$idProductoProduccion);
		 
		 $this->db->update('rel_producto_produccion',$data);
		 
		 $this->actualizarProductoCaja($idProducto);
		 
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
	}

	public function editarProducto($imagen)
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$impuestos				= explode('|',$this->input->post('selectImpuestos'));
		$idProducto				= $this->input->post('txtIdProducto');
		
		$stock					= $this->input->post('txtInventarioInicial');
		$stockActual			= $this->input->post('txtInventarioActual');
		
		$data=array
		(
			'nombre'			=> $this->input->post('txtNombre'),
			'codigoBarras'		=> $this->input->post('txtCodigoBarras'),
			'codigoInterno'		=> $this->input->post('txtCodigoInterno'),
			'precioA'			=> $this->input->post('txtPrecioA'),
			'precioB'			=> $this->input->post('txtPrecioB'),
			'precioC'			=> $this->input->post('txtPrecioC'),
			'precioD'			=> $this->input->post('txtPrecioD'),
			'precioE'			=> $this->input->post('txtPrecioE'),
			#'unidad'			=> $this->input->post('txtUnidad'),
			'idLinea'			=> $this->input->post('selectLineas'),
			'idSublinea'		=> $this->input->post('selectSubLineas'),
			'stock'				=> $stock,
			'upc'				=> $this->input->post('txtUpc'),
		    'sku'				=> $this->input->post('txtSku'),
			'idCuentaCatalogo'	=> $this->input->post('txtIdCuentaCatalogo'),
			'impuesto'			=> $this->input->post('selectImpuestos'),
			
			'idDepartamento'	=> $this->input->post('selectDepartamentos'),
			'idMarca'			=> $this->input->post('selectMarcas'),
			'descripcion'		=> $this->input->post('txtDescripcion'),
			
			'idUnidad'			=> $this->input->post('selectUnidades'),
			
			#'idImpuesto'		=> $this->input->post('selectImpuestos'),
			'idImpuesto'		=> $impuestos[0],
			'precioImpuestos'	=> $this->input->post('txtPrecioImpuestos'),
			
			'idUnidad'			=> $this->input->post('txtIdUnidad'),
			'idClave'			=> $this->input->post('txtIdClave'),
			
			'fechaActualizacion'	=> $this->_fecha_actual,
			'idUsuarioActualizacion'=> $this->_user_id,

			'idPedimento'			=> $this->input->post('txtIdPedimentoRegistro'),
		);	
		 
		if(strlen($imagen)>2)
		{
		 	$data['imagen']	= $imagen;
		}
		
		if(sistemaActivo=='olyess')
		{
			$data['rebanadas']					= $this->input->post('txtNumeroRebanadas');
			$data['precioRebanada']				= $this->input->post('txtPrecioRebanada');
		}
		
		if(sistemaActivo=='cerraduras')
		{
			$data['cantidadMayoreo']			= $this->input->post('txtApartirB');
			$data['stockMinimo']				= $this->input->post('txtStockMinimo');
			$data['stockMaximo']				= $this->input->post('txtStockMaximo');
		}
		 
		$data	= procesarArreglo($data);
		$this->db->where('idProducto',$this->input->post('txtIdProducto'));
		$this->db->update('productos',$data);
		
		
		//ACTUALIZAR PRECIOS POR SUCURSAL
		$data=array
		(
			'precioA'				=> $this->input->post('txtPrecioA'),
			'precioB'				=> $this->input->post('txtPrecioB'),
			'precioC'				=> $this->input->post('txtPrecioC'),
			'precioD'				=> $this->input->post('txtPrecioD'),
			'precioE'				=> $this->input->post('txtPrecioE'),
			'fechaActualizacion'	=> $this->_fecha_actual,
		);	
		
		$this->db->where('idProducto',$this->input->post('txtIdProducto'));
		$this->db->where('idLicencia',$this->idLicencia);
		$this->db->update('productos_inventarios',$data);
		
		 
		$this->configuracion->registrarBitacora('Editar producto','Catálogo de productos',$this->input->post('txtNombre')); //Registrar bitácora
		
		//QUITAR LA EDICIÓN DEL INVENTARIO
		if(sistemaActivo!='pinata')
		{
			$this->db->where('idProducto',$this->input->post('txtIdProducto'));
			$this->db->where('idLicencia',$this->idLicencia);
			$this->db->update('productos_inventarios',array('stock'=>$stock));
		
			$this->registrarStockLicencias($idProducto,0);
		}
		
		if(sistemaActivo=='cerraduras')
		{
			$this->db->where('idProducto',$this->input->post('txtIdProducto'));
			$this->db->update('rel_producto_proveedor',array('precio'=>$this->input->post('txtCostoProducto')));
		
			$this->registrarStockLicencias($idProducto,0);
		}
		
		if($stock!=$stockActual)
		{
			$cantidad	= $stock-$stockActual;
			$cantidad	= $cantidad<0?$cantidad*-1:$cantidad;
			
			$this->db->insert('productos_inventarios_movimientos',array('fecha'=>$this->_fecha_actual,'cantidad'=>$cantidad,'idProducto'=>$idProducto,'idLicencia'=>$this->idLicencia,'movimiento'=>($stockActual>$stock?'Salida':'Entrada'),'inventarioAnterior'=>$stockActual,'inventarioActual'=>$stock));
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
	}
	
	public function editarProductoActualizar()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idProducto				= $this->input->post('txtIdProducto');
		$producto				= $this->obtenerProducto($idProducto);
		
		$stock					= $this->input->post('txtInventarioInicial');
		$stockActual			= $this->input->post('txtInventarioActual');

		
		$data=array
		(
			'nombre'				=> $this->input->post('txtNombre'),
			'codigoInterno'			=> $this->input->post('txtCodigoInterno'),
			'precioA'				=> $this->input->post('txtPrecioA'),
			'precioB'				=> $this->input->post('txtPrecioB'),
			'precioC'				=> $this->input->post('txtPrecioC'),
			'stock'					=> $stock,
			'idUnidad'				=> $this->input->post('txtIdUnidad'),
			'idClave'				=> $this->input->post('txtIdClave'),
			'cantidadMayoreo'		=> $this->input->post('txtApartirB'),
			'stockMinimo'			=> $this->input->post('txtStockMinimo'),
			'stockMaximo'			=> $this->input->post('txtStockMaximo'),
			'fechaActualizacion'	=> $this->_fecha_actual,
			'idUsuarioActualizacion'=> $this->_user_id,
		);	

		$data	= procesarArreglo($data);
		$this->db->where('idProducto',$idProducto);
		$this->db->update('productos',$data);
		 
		$this->configuracion->registrarBitacora('Editar producto','Catálogo de productos',$this->input->post('txtNombre')); //Registrar bitácora
		
		
		//ACTUALIZAR PRECIOS POR SUCURSAL
		$data=array
		(
			'precioA'				=> $this->input->post('txtPrecioA'),
			'precioB'				=> $this->input->post('txtPrecioB'),
			'precioC'				=> $this->input->post('txtPrecioC'),
			'fechaActualizacion'	=> $this->_fecha_actual,
			'stock'					=> $stock,
		);	
	
		$this->db->where('idProducto',$idProducto);
		$this->db->where('idLicencia',$this->idLicencia);
		$this->db->update('productos_inventarios',$data);
		
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

		$this->db->where('idProducto',$idProducto);
		$this->db->update('rel_producto_proveedor',array('precio'=>$this->input->post('txtCostoProducto')));

		if($producto->solo=='0')
		{
			$this->registrarStockLicencias($idProducto,0);
		}
		
		if($stock!=$stockActual)
		{
			$cantidad	= $stock-$stockActual;
			$cantidad	= $cantidad<0?$cantidad*-1:$cantidad;
			
			$this->db->insert('productos_inventarios_movimientos',array('fecha'=>$this->_fecha_actual,'cantidad'=>$cantidad,'idProducto'=>$idProducto,'idLicencia'=>$this->idLicencia,'movimiento'=>($stockActual>$stock?'Salida':'Entrada'),'inventarioAnterior'=>$stockActual,'inventarioActual'=>$stock));
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array("0",'Error en el registro');
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array("1",'El registro ha sido exitoso');
		}
	}
	
	public function editarProductoActualizarSucursales()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idProducto				= $this->input->post('txtIdProducto');
		
		$stock					= $this->input->post('txtInventarioInicial');
		$stockActual			= $this->input->post('txtInventarioActual');
		$numeroSucursales		= $this->input->post('txtNumeroSucursales');
		
		$data=array
		(
			'nombre'				=> $this->input->post('txtNombre'),
			'codigoInterno'			=> $this->input->post('txtCodigoInterno'),
			'precioA'				=> $this->input->post('txtPrecioA'),
			'precioB'				=> $this->input->post('txtPrecioB'),
			'precioC'				=> $this->input->post('txtPrecioC'),
			'stock'					=> $stock,
			'idUnidad'				=> $this->input->post('txtIdUnidad'),
			'idClave'				=> $this->input->post('txtIdClave'),
			'cantidadMayoreo'		=> $this->input->post('txtApartirB'),
			'stockMinimo'			=> $this->input->post('txtStockMinimo'),
			'stockMaximo'			=> $this->input->post('txtStockMaximo'),
			'fechaActualizacion'	=> $this->_fecha_actual,
			'idUsuarioActualizacion'=> $this->_user_id,
		);	

		$data	= procesarArreglo($data);
		$this->db->where('idProducto',$idProducto);
		$this->db->update('productos',$data);
		 
		$this->configuracion->registrarBitacora('Editar producto','Catálogo de productos',$this->input->post('txtNombre')); //Registrar bitácora
		
		//EL STOCK SOLO EN LA ACTUAL
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		$data=array
		(
			'stock'					=> $stock,
		);	
	
		$this->db->where('idProducto',$idProducto);
		$this->db->where('idLicencia',$this->idLicencia);
		$this->db->update('productos_inventarios',$data);
		
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		//ACTUALIZAR PRECIOS POR SUCURSAL
		for($i=0;$i<$numeroSucursales;$i++)
		{
			$idLicencia				= $this->input->post('chkSucursal'.$i);
			
			if($idLicencia>0)
			{
				$data=array
				(
					'precioA'				=> $this->input->post('txtPrecioA'),
					'precioB'				=> $this->input->post('txtPrecioB'),
					'precioC'				=> $this->input->post('txtPrecioC'),
					'fechaActualizacion'	=> $this->_fecha_actual,
				);	

				$this->db->where('idProducto',$idProducto);
				$this->db->where('idLicencia',$idLicencia);
				$this->db->update('productos_inventarios',$data);
			}
		}
		
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

		$this->db->where('idProducto',$idProducto);
		$this->db->update('rel_producto_proveedor',array('precio'=>$this->input->post('txtCostoProducto')));

		$this->registrarStockLicencias($idProducto,0);
		
		if($stock!=$stockActual)
		{
			$cantidad	= $stock-$stockActual;
			$cantidad	= $cantidad<0?$cantidad*-1:$cantidad;
			
			$this->db->insert('productos_inventarios_movimientos',array('fecha'=>$this->_fecha_actual,'cantidad'=>$cantidad,'idProducto'=>$idProducto,'idLicencia'=>$this->idLicencia,'movimiento'=>($stockActual>$stock?'Salida':'Entrada'),'inventarioAnterior'=>$stockActual,'inventarioActual'=>$stock));
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array("0",'Error en el registro');
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array("1",'El registro ha sido exitoso');
		}
	}
	
	public function borrarProductoCaja($idProducto,$id)
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$this->db->where('idProducto', $id);
		$this->db->where('idProductoProduccion', $idProducto);
		$this->db->delete('rel_producto_produccion');
		
		$this->actualizarProductoCaja($id);

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
	}
	
	public function agregarProductoCaja()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza

		$idProducto	=$this->input->post('idProductoProduccion');
		$id			=$this->input->post('idProducto');
		$cantidad	=$this->input->post('cantidad');
		
		$sql="select * from rel_producto_material
		where idProducto='$idProducto'";
		
		$query=$this->db->query($sql);
		
		if($query->num_rows()==0)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return "2";
		}
		
		$sql="select * from rel_producto_produccion
		where idProducto='$id'
		and idProductoProduccion='$idProducto'";
		
		$query=$this->db->query($sql);
		
		if($query->num_rows()>0)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return "3";
		}
		
		$data=array
		(
		   'idProducto'				=>$id,
		   'idProductoProduccion'	=>$idProducto,
		   'cantidad'				=>$cantidad,
		   'fecha'					=>$this->_fecha_actual
		);
	
		
		$this->db->insert('rel_producto_produccion', $data);
		
		$this->actualizarProductoCaja($id);
		
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
	}
	
	public function actualizarProductoCaja($idProducto)
	{
		$sql="select * from rel_producto_produccion
		where idProducto='$idProducto'";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$this->actualizarCajasProductos($row->idProductoProduccion);
		}
	}
	
	public function actualizarCajasProductos($idProducto) #Actualizar totales en las cajas de productos
	{
		#---OBTENER EL ID DE LAS CAJAS DE PRODUCTOS---#
		$sql="select a.idProducto
		from rel_producto_produccion as a
		inner join productos as b
		on(a.idProducto=b.idProducto)
		where a.idProductoProduccion='$idProducto'
		and b.idLicencia='$this->idLicencia' 
		and b.servicio='0' 
		and b.reventa='0' 
		and b.activo='1'";
					
		$query=$this->db->query($sql)->result();
					
		 foreach($query as $row)
		 {
			 #---OBTENER EL DETALLE DE LAS CAJAS DE PRODUCTOS APARTIR DEL ID OBTENIDO---#
			 //$productoCaja=$productos->idProducto;
			 $this->actualizarDetalleCajasProductos($row->idProducto);
		 }
	}
	
	public function actualizarDetalleCajasProductos($idProducto)
	{
		$sql="select a.idProductoProduccion,
		((b.precioA/b.piezas)*a.cantidad) as precioA,
		((b.precioB/b.piezas)*a.cantidad) as precioB,
		((b.precioC/b.piezas)*a.cantidad) as precioC,
		((b.precioD/b.piezas)*a.cantidad) as precioD,
		((b.precioE/b.piezas)*a.cantidad) as precioE
		from rel_producto_produccion as a
		inner join produccion_productos as b
		on(a.idProductoProduccion=b.idProducto)
		where a.idProducto='$idProducto'
		and b.idLicencia='$this->idLicencia' 
		and b.servicio='0' 
		and b.reventa='0' 
		and b.activo='1'";
		
		$query=$this->db->query($sql)->result();
		
		$precioA=0;
		$precioB=0;
		$precioC=0;
		$precioD=0;
		$precioE=0;
		
		foreach($query as $row)
		{
			$precioA+=$row->precioA;
			$precioB+=$row->precioB;
			$precioC+=$row->precioC;
			$precioD+=$row->precioD;
			$precioE+=$row->precioE;
		}
		
		$data = array
		(
			'precioA' 					=>$precioA,#$costoTotalProduccion, -------->Modificado
			'precioB' 					=>$precioB,
			'precioC' 					=>$precioC,
			'precioD' 					=>$precioD,
			'precioE' 					=>$precioE,
			'fechaActualizacion' 		=>$this->_fecha_actual,
			'idUsuarioActualizacion' 	=>$this->_user_id
		);
		
		$this->db->where('idProducto',$idProducto);
		$this->db->update('productos',$data); #EJECUTAR LA ACTUALIZACION
	}
	
	public function productoAgregar()
	{
	    $this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza

		$data=array
		(
		   'idProducto'				=>$this->input->post('id'),
		   'idProductoProduccion'	=>$this->input->post('prod'),
		   'cantidad'				=>$this->input->post('canti'),
		   'fecha'					=>$this->_fecha_actual
		);
		
		$idProducto	=$this->input->post('prod');
		$id			=$this->input->post('id');
		$cantidad	=$this->input->post('canti');
		
		#--------------------------------------------------
		$sql="select stock, costo, color
		from produccion_productos 
		where idProducto='".$idProducto."'";
		
		$query	=$this->db->query($sql);
		$row	=$query->row();
		
		$stock	=$row->stock;
		$costo	=$row->costo;
		$color  =$row->color;
		
		#--------------------------------------------------
		$sql="	select cexistencia, precio_venta, precio_costo, 
					porcentajeGanancia, descripcion
				from productos 
				where id='".$id."'";
		
		$query	=$this->db->query($sql);
		$row	=$query->row();
		
		$existencia=$row->cexistencia;
		$precioCosto=$row->precio_costo; 
		$precioVenta=$row->precio_venta;
		$descripcion=$row->descripcion;
		
		#--------------------------------------------------
		
		if(($existencia*$cantidad)>$stock)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return "2";
		}
		
		$ganancia=($row->porcentajeGanancia)/100;  #GANANCIA
		$stockActual=$stock-($existencia*$cantidad);
		$costoProduccion=$cantidad*$costo;
		$precioActualizar=$precioCosto+$costoProduccion; #COSTO DE PRODUCCION
		$precioVender=$precioActualizar+($precioActualizar*$ganancia); #PRECIO DE VENTA
		$iva=$this->session->userdata('iva')/100; #IVA
		$ivaActualizar=$precioVender*$iva;
		
		$dataActualizar= array
		(
			'stock'	=>$stockActual
		);
		
		$this->db->where('idProducto',$idProducto);  #Actualizar el stock de productos
		$this->db->update('produccion_productos', $dataActualizar);
		
		$dataPrecio= array
		(
			'precio_costo'	=>$precioActualizar,
			'precio_venta'	=>$precioVender,
			'iva'			=>$ivaActualizar
		);
		
		$this->db->where('id',$id);  #Actualizar el precio de venta de productos
		$this->db->update('productos', $dataPrecio);

		
		$this->db->insert('rel_producto_produccion', $data);
		
		
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
	}

	public function comprobarProductoCaja($idProducto)
	{
		$sql="select * from cotiza_productos
		where idProduct='$idProducto'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select * from rel_producto_proveedor
		where idProducto='$idProducto'";
		
		if($this->db->query($sql)->num_rows()>1)
		{
			return 1;
		}
		
		$sql="select * from produccion_orden_produccion
		where idProducto='$idProducto'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select a.nombre, b.idMaterial
		from compras as a
		inner join compra_detalles as b
		on a.idCompras=b.idCompra
		where b.idMaterial='$idProducto' 
		and a.reventa='1'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
	}
	
	public function obtenerNombreProducto($idProducto)
	{
		$sql="select nombre, codigoInterno 
		from productos
		where idProducto='".$idProducto."'";
		 
	  	$producto	= $this->db->query($sql)->row();
	
	  	return $producto!=null?array($producto->codigoInterno,$producto->nombre):array('Sin detalles del producto','');
	}
	
	public function borrarProducto($idProducto) //EL BORRADO SERA LÓGICO
	{
		$producto	= $this->obtenerNombreProducto($idProducto);
		
		$this->db->where('idProducto', $idProducto);
		$this->db->update('productos',array('activo'=>'0'));
		
		 $this->configuracion->registrarBitacora('Borrar producto','Catálogo de productos',$producto[1]); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?'1':'0';
	}
	
	/*public function borrarProducto($idProducto)
	{
		if($this->comprobarProductoCaja($idProducto)>0)
		{
			return "0";
		}
		
		$this->db->trans_start();
		
		$this->db->where('idProducto', $idProducto);
		$this->db->delete('productos');
		
		$sql="select idProductoProduccion
		from rel_producto_produccion
		where idProducto='$idProducto'";
		
		$query	=$this->db->query($sql)->row();
		
		$this->db->where('idProducto', $query->idProductoProduccion);
		$this->db->delete('produccion_productos');
		
		$this->db->where('idProducto', $idProducto);
		$this->db->delete('rel_producto_produccion');
		
		//BORRAR LA RELACION CON PROVEEDORES
		$this->db->where('idProducto',$idProducto);
		$this->db->delete('rel_producto_proveedor');
		
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

	public function productos()
	{
		$sql="select * from produccion_productos
		where idLicencia='$this->idLicencia'";
		$query = $this->db->query($sql);
		 
		return ($query->num_rows() > 0)? $query->result_array() : NULL;
	}
	
	public function obtenerProductosGraficas()
	{
		$sql="select * from productos
		where idLicencia='$this->idLicencia' 
		and servicio='0' 
		and activo='1'";
		$query = $this->db->query($sql);
		 
		return ($query->num_rows() > 0)? $query->result() : NULL;
	}
	
	public function obtenerDetallesProduccion()#Todos los productos para ordenes de produccion
	{
		$sql="select * from productos
		where idLicencia='$this->idLicencia' 
		and servicio='0' 
		and reventa='0' 
		and activo='1' ";
		$query = $this->db->query($sql);
		 
		return ($query->num_rows() > 0)? $query->result() : NULL;
	}

	public function ObtenerProductos()
	{
		$queryString = $_POST['queryString'];	
		
		if(strlen($queryString) >0) 
		{
			$query = "select a.*
			from produccion_productos as a
			where a.nombre LIKE '%$queryString%'
			and a.idLicencia='$this->idLicencia'  
			and servicio='0' 
			and activo='1'
			limit 20 ";
			$query = $this->db->query($query);
			
			return ($query->num_rows() > 0)? $query->result_array() : NULL;
		}
	}
	
	public function obtenerRemisionProductos()
	{
		$criterio = $_POST['queryString'];	
		
		if(strlen($criterio) >0) 
		{
			$query = "select * from productos
			where nombre like '%$criterio%'  
			and idLicencia='$this->idLicencia'
			and activo='1'
			and materiaPrima='0'
			
			and activo='1'
			limit 20";
			
			#and servicio='1' 
			$query = $this->db->query($query);
			
			return ($query->num_rows() > 0)? $query->result() : NULL;
		}
	}

	public function contarProductos($criterio,$minimo='0',$codigoInterno='')
	{
		$sql =" select a.idProducto 
		from productos as a  
		
		inner join productos_inventarios as c
		on c.idProducto=a.idProducto 
		
		where a.activo='1'
		and c.idLicencia='$this->idLicencia' 
		and servicio='0' 
		and materiaPrima='0' ";
		
		$sql.= strlen($criterio)>0?" and (a.nombre like '%$criterio%' or a.codigoInterno like '%$criterio%' or a.codigoBarras like '%$criterio%' ) ":' ';
		$sql.= strlen($codigoInterno)>0?" and (a.codigoInterno like '$codigoInterno%') ":' ';
		
		if(sistemaActivo=='cerraduras')
		{
			$sql.=$minimo=='1'?" and a.stockMinimo >= c.stock ":'';
		}
		
		return $this->db->query($sql)->num_rows();
	}

	public function obtenerProductosPaginado($numero,$limite,$criterio,$orden='asc',$minimo='0',$codigoInterno='',$idProveedor=0)
	{
		
		$sql ="select a.idProducto ";
		
		if($idProveedor==0)
		{
			$sql.=", a.nombre, a.imagen, 
			c.precioA, c.precioB, c.precioC, a.reventa, 
			c.stock, a.codigoBarras, a.codigoInterno,
			b.nombre as linea, a.precioImpuestos,
			(select c.nombre from productos_departamentos as c where c.idDepartamento=a.idDepartamento) as departamento, 
			a.stockMinimo, a.stockMaximo  ";
		}
		
		$sql.=" from productos as a
		inner join productos_lineas as b
		on a.idLinea=b.idLinea 
		
		
		inner join productos_inventarios as c
		on c.idProducto=a.idProducto 
		
		where a.activo='1'
		and c.idLicencia='$this->idLicencia' 
		and servicio='0' 
		and materiaPrima='0' ";
		
		$sql.= strlen($criterio)>0?" and (a.nombre like '%$criterio%' or a.codigoInterno like '%$criterio%' or a.codigoBarras like '%$criterio%' ) ":' ';
		
		$sql.= strlen($codigoInterno)>0?" and (a.codigoInterno like '$codigoInterno%') ":' ';
		
		if(sistemaActivo=='cerraduras')
		{
			$sql.=$minimo=='1'?" and a.stockMinimo >= c.stock ":'';
		}
		
		$sql.=$idProveedor>0?" and (select count(d.idProducto) from rel_producto_proveedor as d where d.idProducto=a.idProducto and d.idProveedor='$idProveedor') = 0":'';
		
		$sql.=" order by a.nombre $orden ";
		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function obtenerProductosImpresion()
	{
		$sql="select a.nombre, a.precioA, 
		b.nombre as linea, a.codigoInterno,
		a.stock, a.unidad
		from productos as a
		inner join productos_lineas as b
		on a.idLinea=b.idLinea
		where a.idLicencia='$this->idLicencia' 
		and a.servicio='0' 
		and a.activo='1'  ";
		
		return $this->db->query($sql)->result();
	}
	
	#SERVICIOS
	#====================================================================================================
	public function contarServicios($criterio)
	{
		$sql ="select a.idProducto from productos as a 
		where a.activo='1'
		and servicio='1' ";
		
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';
		
		return $this->db->query($sql)->num_rows();
	}

	public function obtenerServiciosPaginado($numero,$limite,$criterio)
	{
		$sql ="select a.idProducto, a.nombre, a.imagen,  a.precioImpuestos,
		a.precioA, a.precioB, a.precioC, a.reventa, a.idPeriodo,
		a.stock, a.codigoBarras, a.codigoInterno,
		b.nombre as periodo, a.plazo,
		(select c.nombre from fac_catalogos_unidades as c where c.idUnidad=a.idUnidad) as unidad
		from productos as a 
		inner join produccion_periodos as b
		on a.idPeriodo=b.idPeriodo ";
		
		$sql.=" where a.activo='1'
		and servicio='1' ";
		 
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';
		
		$sql .= " order by a.nombre asc
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function editarServicio()
	{
		$this->db->trans_start(); 
		
		$idProducto				= $this->input->post('txtIdProducto');
		$impuestos				= explode('|',$this->input->post('selectImpuestos'));
			
		$data=array
		(
			'nombre'				=> $this->input->post('txtNombreProducto'),
			'codigoInterno'		=> $this->input->post('txtCodigoInterno'),
			'idPeriodo'			=> $this->input->post('selectPeriodos'),
			#'idUnidad'			=> $this->input->post('selectUnidades'),
			'precioA'			=> $this->input->post('txtPrecioA'),
			'precioB'			=> $this->input->post('txtPrecioB'),
			'precioC'			=> $this->input->post('txtPrecioC'),
			'precioD'			=> $this->input->post('txtPrecioD'),
			'precioE'			=> $this->input->post('txtPrecioE'),
			'idLinea'			=> $this->input->post('selectLineas'),
			'plazo'				=> $this->input->post('txtPlazo'),
			'impuesto'			=> $this->input->post('selectImpuestos'),
			
			'idDepartamento'		=> $this->input->post('selectDepartamentos'),
			'idMarca'			=> $this->input->post('selectMarcas'),
			'descripcion'		=> $this->input->post('txtDescripcion'),
			
			#'idImpuesto'		=> $this->input->post('selectImpuestos'),
			
			
			'idUnidad'			=> $this->input->post('txtIdUnidad'),
			'idClave'			=> $this->input->post('txtIdClave'),
			'idImpuesto'		=> $impuestos[0],
			'precioImpuestos'	=> $this->input->post('txtPrecioImpuestos'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idProducto',$idProducto);
		$this->db->update('productos',$data);
		
		$this->configuracion->registrarBitacora('Registrar servicio','Catálogo de servicios',$data['nombre']); //Registrar bitácora
		
		//STOCK DE LAS LICENCIAS
		$this->registrarStockLicencias($idProducto,0);

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
	}
	
	public function comprobarServicioRegistro($nombre,$codigo)
	{
		$sql ="select idProducto
		from  productos 
		where activo='1'
		and servicio='1'
		and nombre='$nombre'
		and codigoInterno='$codigo'  ";

		return $this->db->query($sql)->num_rows()>0?false:true;
	}

	
	public function registrarServicio()
	{
		$impuestos=explode('|',$this->input->post('selectImpuestos'));
		
		if(!$this->comprobarServicioRegistro($this->input->post('txtNombreProducto'),$this->input->post('txtCodigoInterno')))
		{		
			return array('0',registroDuplicado);
		}
		
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		$data=array
		(
		   	'nombre'			=> $this->input->post('txtNombreProducto'),
		   	'codigoInterno'		=> $this->input->post('txtCodigoInterno'),
		   	'idPeriodo'			=> $this->input->post('selectPeriodos'),
		  
		   	'precioA'			=> $this->input->post('txtPrecioA'),
		   	'precioB'			=> $this->input->post('txtPrecioB'),
		   	'precioC'			=> $this->input->post('txtPrecioC'),
		   	'precioD'			=> $this->input->post('txtPrecioD'),
		   	'precioE'			=> $this->input->post('txtPrecioE'),
		   	'idLinea'			=> $this->input->post('selectLineas'),
		   	'plazo'				=> $this->input->post('txtPlazo'),
		   	'fecha'				=> $this->_fecha_actual,
		   	'idLicencia'			=> $this->idLicencia,
		   	'codigoBarras'		=> '0',
		   	'idUsuario'			=> $this->_user_id,
		   	'reventa'			=> 0,
		   	'servicio'			=> 1,
		  
		   
		    'idDepartamento'		=> $this->input->post('selectDepartamentos'),
			'idMarca'			=> $this->input->post('selectMarcas'),
			'descripcion'		=> $this->input->post('txtDescripcion'),
			
			
			/*'impuesto'			=> $this->input->post('selectImpuestos'),
			'idImpuesto'		=> $this->input->post('selectImpuestos'),
			'idUnidad'			=> $this->input->post('selectUnidades'),*/
			
			'idUnidad'			=> $this->input->post('txtIdUnidad'),
			'idClave'			=> $this->input->post('txtIdClave'),
			'idImpuesto'		=> $impuestos[0],
			'precioImpuestos'	=> $this->input->post('txtPrecioImpuestos'),
		);

		$data	= procesarArreglo($data);
		$this->db->insert('productos', $data);
		
		$idProducto	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar servicio','Catálogo de servicios',$data['nombre']); //Registrar bitácora
		
		if(sistemaActivo!='pinata')
		{
			$this->registrarStockLicencias($idProducto,0);
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
	
	public function comprobarServicio($idProducto)
	{
		$sql=" select idProducto
		from cotiza_productos
		where idProduct='$idProducto'";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function borrarServicioProducto($idProducto)
	{
		/*if($this->comprobarServicio($idProducto)>0)
		{
			return "0";
		}
		
		$this->db->trans_start();
		
		$this->db->where('idProducto', $idProducto);
		$this->db->delete('productos');
		
		$this->db->where('idProducto', $idProducto);
		$this->db->delete('rel_producto_produccion');*/
		
		$producto	= $this->obtenerDetallesProducto($idProducto);
		
		if($producto!=null)
		{
			$this->db->where('idProducto', $idProducto);
			$this->db->update('productos',array('activo'=>'0'));
			
			
			$this->configuracion->registrarBitacora('Borrar servicio','Catálogo de servicios',$producto->descripcion); //Registrar bitácora
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
	}
	
	
	#AGREGAR PROVEEDOR A PRODUCTO
	#================================================================================================
	
	public function checarProveedorProducto($idProveedor,$idProducto)
	{
		$sql=" select * from rel_producto_proveedor
		where idProveedor='$idProveedor'
		and idProducto='$idProducto'";
		
		return $this->db->query($sql)->num_rows();
	}
	
	#INVENTARIOS
	#====================================================================================================
	public function contarInventarios($criterio)
	{
		$sql ="select a.idInventario
		from  inventarios as a
		inner join rel_inventario_proveedor as b
		on a.idInventario=b.idInventario
		inner join proveedores as c
		on c.idProveedor=b.idProveedor
		where a.activo='1'
		and a.idLicencia='$this->idLicencia' ";

		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%'":'';

	    return $this->db->query($sql)->num_rows();
	}

	public function obtenerInventarios($numero,$limite,$criterio)
	{
		$sql ="select a.*, b.costo, c.empresa
		from  inventarios as a
		inner join rel_inventario_proveedor as b
		on a.idInventario=b.idInventario
		inner join proveedores as c
		on c.idProveedor=b.idProveedor
		where a.activo='1' 
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%'":'';

		$sql .= " order by a.nombre asc
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function comprobarInventarioRegistro($nombre,$codigo)
	{
		$sql ="select idInventario
		from  inventarios 
		where activo='1'
		and nombre='$nombre'
		and codigoInterno='$codigo'
		and idLicencia='$this->idLicencia'  ";

		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarInventario()
	{
		if(!$this->comprobarInventarioRegistro($this->input->post('txtNombre'),$this->input->post('txtCodigo')))
		{
			return array('0',registroDuplicado);
			exit;
		}
		
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		$data=array
		(
		   'nombre'				=> $this->input->post('txtNombre'),
		   'cantidad'			=> $this->input->post('txtCantidad'),
		   'codigoInterno'		=> $this->input->post('txtCodigo'),
		   'unidad'				=> $this->input->post('txtUnidad'),
		   'fechaRegistro'		=> $this->_fecha_actual,
		   'idUsuario'			=> $this->_user_id,
		   'idLicencia'			=> $this->idLicencia,
		);

		$data	= procesarArreglo($data);
		$this->db->insert('inventarios', $data);
		$idInventario	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar mobiliario/equipo','Mobiliario/equipo',$this->input->post('txtNombre')); //Registrar bitácora
		#--------------------------------------------------------------------------------------------#
		$data=array
		(
			'idInventario'			=>$idInventario,
			'idProveedor'			=>$this->input->post('selectProveedor'),
			'costo'					=>$this->input->post('txtCosto'),
		);

		$this->db->insert('rel_inventario_proveedor', $data);
		#--------------------------------------------------------------------------------------------#
		
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
	
	public function editarInventario()
	{
		$data=array
		(
			'nombre'				=> $this->input->post('txtNombre'),
			'codigoInterno'			=> $this->input->post('txtCodigo'),
		    'unidad'				=> $this->input->post('txtUnidad'),
			'idUsuarioEdicion'		=> $this->_user_id,
			'fechaEdicion'			=> $this->_fecha_actual,
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idInventario', $this->input->post('txtIdInventario'));
		$this->db->update('inventarios', $data);
		
		$this->configuracion->registrarBitacora('Editar mobiliario/equipo','Mobiliario/equipo',$this->input->post('txtNombre')); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function asociarProveedorInventario()
	{
		$data=array
		(
			'idInventario'			=>$this->input->post('idInventario'),
			'idProveedor'			=>$this->input->post('idProveedor'),
			'costo'					=>$this->input->post('costo'),
		);

		$this->db->insert('rel_inventario_proveedor', $data);
		
		$inventario	= $this->obtenerDetalleInventario($this->input->post('idInventario'),$this->input->post('idProveedor'));
		
		$this->configuracion->registrarBitacora('Asociar proveedor con mobiliario/equipo','Mobiliario/equipo',$inventario[0].', '.$inventario[1]); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerDetalleInventario($idInventario,$idProveedor)
	{
		$sql=" select a.nombre, c.empresa
		from inventarios as a
		inner join rel_inventario_proveedor as b
		on a.idInventario=b.idInventario
		inner join proveedores as c
		on c.idProveedor=b.idProveedor
		where a.idInventario='$idInventario'
		and b.idProveedor='$idProveedor' ";
		
		$inventario	= $this->db->query($sql)->row();
		
		return $inventario!=null?array($inventario->nombre,$inventario->empresa):array('Sin detalles de mobiliario/equipo','');
	}
	
	public function comprobarProveedorInventario($idProveedor,$idInventario)
	{
		$sql="select * from rel_inventario_proveedor
		where idInventario='$idInventario'
		and idProveedor='$idProveedor'";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerInventarioProveedor($idInventario)
	{
		$sql ="select a.*, b.costo, c.empresa
		from  inventarios as a
		inner join rel_inventario_proveedor as b
		on a.idInventario=b.idInventario
		inner join proveedores as c
		on c.idProveedor=b.idProveedor 
		where a.idInventario='$idInventario'";

		$sql .= " order by c.empresa asc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerInventario($idInventario)
	{
		$sql="select * from inventarios
		where idInventario='$idInventario'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarInventario($idInventario)
	{
		$sql="select idInventario 
		from inventarios_uso
		where idInventario='$idInventario'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select a.nombre, b.idMaterial
		from compras as a
		inner join compra_detalles as b
		on a.idCompras=b.idCompra
		where b.idMaterial='$idInventario' 
		and a.inventario='1'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
	}
	
	public function obtenerDetallesInventario($idInventario)
	{
		$sql=" select a.nombre, a.codigoInterno
		from inventarios as a
		where a.idInventario='$idInventario' ";
		
		$inventario	= $this->db->query($sql)->row();
		
		return $inventario!=null?array($inventario->nombre,$inventario->codigoInterno):array('Sin detalles de mobiliario/equipo','');
	}
	
	public function borrarInventario($idInventario)
	{
		$this->db->trans_start();
		
		/*if($this->comprobarInventario($idInventario)>0)
		{
			return "0";
		}*/
		
		/*$this->db->where('idInventario', $idInventario);
		$this->db->delete('rel_inventario_proveedor');
		
		$this->db->where('idInventario', $idInventario);
		$this->db->delete('inventarios');*/
		
		$this->db->where('idInventario', $idInventario);
		$this->db->update('inventarios',array('activo'=>'0'));
		
		$inventario	= $this->obtenerDetallesInventario($idInventario);
		
		$this->configuracion->registrarBitacora('Borrar mobiliario/equipo','Mobiliario/equipo',$inventario[0]); //Registrar bitácora

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
	}
	
	public function editarCostoInventario()
	{
		$data=array
		(
			'costo'				=> $this->input->post('costo'),
		);
		
		$this->db->where('idInventario', $this->input->post('idInventario'));
		$this->db->where('idProveedor', $this->input->post('idProveedor'));
		$this->db->update('rel_inventario_proveedor', $data);
		
		$inventario	= $this->obtenerDetalleInventario($this->input->post('idInventario'),$this->input->post('idProveedor'));
		
		$this->configuracion->registrarBitacora('Editar costo mobiliario/equipo','Mobiliario/equipo',$inventario[0].', '.$inventario[1].', Costo: '.number_format($this->input->post('costo'),2)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	#USOS DEL INVENTARIO
	public function contarUsosInventario()
	{
		$idInventario=$this->input->post('idInventario');
		
		$sql ="select idUso
		from inventarios_uso 
		where idInventario='$idInventario'";

	    return $this->db->query($sql)->num_rows();
	}

	public function obtenerUsosInventario($numero,$limite)
	{
		$idInventario=$this->input->post('idInventario');
		
		$sql ="select * from inventarios_uso 
		where idInventario='$idInventario' ";
		
		$sql .= " order by fecha desc
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarUsoInventario()
	{
		$this->db->trans_start();
		
		$idInventario	=$this->input->post('idInventario');
		$inventario		=$this->obtenerInventario($idInventario);
		
		$data=array
		(
			'idInventario'	=>$idInventario,
			'fecha'			=>$this->_fecha_actual,
			'cantidad'		=>$this->input->post('cantidad'),
			'comentarios'	=>$this->input->post('comentarios'),
		);

		$this->db->insert('inventarios_uso',$data);
		
		#--------------------------------------------------------------#
		$data=array
		(
			'cantidad'		=>$inventario->cantidad-$this->input->post('cantidad'),
		);
		
		$this->db->where('idInventario',$idInventario);
		$this->db->update('inventarios',$data);
		
		$inventario	= $this->obtenerDetallesInventario($idInventario);
		
		$this->configuracion->registrarBitacora('Registrar uso mobiliario/equipo','Mobiliario/equipo',$inventario[0].', Cantidad: '.$this->input->post('cantidad')); //Registrar bitácora

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
	
	public function obtenerStockSucursales($idProducto)
	{
		$sql=" select coalesce(sum(stock),0) as stock
		from productos_inventarios
		where idProducto='$idProducto'";
		
		return $this->db->query($sql)->row()->stock;
	}
	
	public function contarProductosVenta()
	{
		$criterio	= $this->input->post('criterio');
		$idLinea	= $this->input->post('idLinea');
		$idSubLinea	= $this->input->post('idSubLinea');
		
		$sql =" select a.idProducto
		from productos as a
		
		
		inner join productos_inventarios as b
		on b.idProducto=a.idProducto
		
		where a.activo='1'
		and b.idLicencia='$this->idLicencia'
		and a.materiaPrima='0' 
		and (a.nombre like '%$criterio%'
		or a.codigoInterno like '%$criterio%'
		or a.codigoBarras like '%$criterio%'
		or a.sku like '%$criterio%'
		or a.upc like '%$criterio%' )";
		
		$sql.=$idLinea>0?" and a.idLinea='$idLinea' ":'';
		$sql.=$idSubLinea>0?" and a.idSubLinea='$idSubLinea' ":'';
		$sql.=" limit 30";

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerProductosVenta($numero,$limite)
	{
		$criterio	= $this->input->post('criterio');
		$idLinea	= $this->input->post('idLinea');
		$idSubLinea	= $this->input->post('idSubLinea');
		
		#".($this->idTienda==0?" if(a.idProducto=1559,1000000000,a.stock) as stock ":"(select coalesce(sum(e.cantidad),0) from tiendas_productos as e where e.idProducto=a.idProducto and e.idTienda='$this->idTienda') as stock").",
		
		$sql =" select a.idProducto, a.nombre, a.imagen, 
		a.precioA, a.precioB, a.precioC, a.reventa, 
		a.codigoBarras, a.codigoInterno, a.descripcion,
		a.precioD, a.precioE, d.idPeriodo, d.nombre as periodo,
		d.factor, d.valor, a.servicio, a.precioImpuestos,  a.idLinea,
		
		".(sistemaActivo=='olyess'?" a.domicilio, a.rebanadas, a.precioRebanada,  ":"")."
		
		(select coalesce(sum(e.cantidad),0) from cotiza_productos as e
		inner join cotizaciones as f
		on f.idCotizacion=e.idCotizacion
		where a.idProducto=e.idProduct
		and f.estatus='1'
		and f.cancelada='0') as vendidos,
		
		(select b.nombre from fac_catalogos_unidades as b where b.idUnidad=a.idUnidad) as unidad,
		g.tasa, g.nombre as impuesto, g.tipo as tipoImpuesto, g.idImpuesto,
		h.stock
		
		from productos as a
		inner join produccion_periodos as d
		on a.idPeriodo=d.idPeriodo
		
		
		inner join configuracion_impuestos as g
		on g.idImpuesto=a.idImpuesto
		
		inner join productos_inventarios as h
		on h.idProducto=a.idProducto
		
		
		where a.activo='1'
		and a.materiaPrima='0'
		and h.idLicencia='$this->idLicencia' ";
		
		
		
		$sql.=" and (a.nombre like '%$criterio%'
		or a.codigoInterno like '%$criterio%'
		or a.codigoBarras like '%$criterio%'
		or a.sku like '%$criterio%'
		or a.upc like '%$criterio%' ) ";
		
		$sql.=$idLinea>0?" and a.idLinea='$idLinea' ":'';
		$sql.=$idSubLinea>0?" and a.idSubLinea='$idSubLinea' ":'';
		
		#$sql .= " order by stock desc, vendidos desc, a.nombre asc ";
		$sql .= $this->ordenProductos=='stock'?" order by stock desc, vendidos desc, a.nombre asc ":'order by vendidos desc, stock desc, a.nombre asc';
		
		$sql.=" limit 30 ";
		#$sql.=" limit $limite,$numero";
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function contarProductosVentaCerraduras()
	{
		$codigoInterno	= trim($this->input->post('codigoInterno'));
		$nombre			= trim($this->input->post('criterio'));
		$codigoBusqueda	= $nombre;
		$idLinea		= (int) $this->input->post('idLinea');
		$idSubLinea		= (int) $this->input->post('idSubLinea');

		$this->db->select('a.idProducto');
		$this->db->from('productos as a');
		$this->db->join('productos_inventarios as b', 'b.idProducto = a.idProducto');
		$this->db->where('a.activo', '1');
		$this->db->where('b.idLicencia', $this->idLicencia);

		if($codigoInterno !== '')
		{
			$this->db->like('a.codigoInterno', $codigoInterno, 'after');
		}

		if($nombre !== '')
		{
			$this->db->group_start();
			$this->db->like('a.nombre', $nombre);
			$this->db->or_like('a.codigoBarras', $nombre);
			$this->db->or_like('a.codigoInterno', $nombre);
			$this->db->group_end();
		}

		if($idLinea > 0)
		{
			$this->db->where('a.idLinea', $idLinea);
		}

		if($idSubLinea > 0)
		{
			$this->db->where('a.idSubLinea', $idSubLinea);
		}

		$this->db->group_by('a.idProducto');

		return $this->db->get()->num_rows();
	}

	public function obtenerProductosVentaCerraduras($numero,$limite)
	{
		$codigoInterno		= trim($this->input->post('codigoInterno'));
		$nombre				= trim($this->input->post('criterio'));
		$idLinea			= (int) $this->input->post('idLinea');
		$idSubLinea			= (int) $this->input->post('idSubLinea');

		$this->db->select("
			a.idProducto,
			a.nombre,
			a.imagen,
			a.cantidadMayoreo,
			h.precioA,
			h.precioB,
			h.precioC,
			a.reventa,
			a.codigoBarras,
			a.codigoInterno,
			a.descripcion,
			h.precioD,
			h.precioE,
			a.servicio,
			a.precioImpuestos,
			a.idLinea,
			g.tasa,
			g.nombre as impuesto,
			g.tipo as tipoImpuesto,
			g.idImpuesto,
			h.stock,
			(select b.nombre from fac_catalogos_unidades as b where b.idUnidad=a.idUnidad) as unidad
		", false);

		$this->db->from('productos as a');
		$this->db->join('configuracion_impuestos as g', 'g.idImpuesto = a.idImpuesto');
		$this->db->join('productos_inventarios as h', 'h.idProducto = a.idProducto');
		$this->db->where('a.activo', '1');
		$this->db->where('h.idLicencia', $this->idLicencia);

		if($codigoInterno !== '')
		{
			$this->db->like('a.codigoInterno', $codigoInterno, 'after');
		}

		if($nombre !== '')
		{
			$this->db->group_start();
			$this->db->like('a.nombre', $nombre);
			$this->db->or_like('a.codigoBarras', $nombre);
			$this->db->or_like('a.codigoInterno', $nombre);
			$this->db->group_end();
		}

		if($idLinea > 0)
		{
			$this->db->where('a.idLinea', $idLinea);
		}

		if($idSubLinea > 0)
		{
			$this->db->where('a.idSubLinea', $idSubLinea);
		}

		$this->db->group_by('a.idProducto');
		$this->db->order_by('h.stock', 'DESC');
		$this->db->order_by('a.nombre', 'ASC');

		$numero = (int) $numero;
		$limite = (int) $limite;

		$this->db->limit($numero > 0 ? $numero : 40, $limite);

		return $this->db->get()->result();
	}

	public function obtenerProductosSync($desde = null, $limite = 100, $offset = 0)
	{
		$limite = (int) $limite;
		$offset = (int) $offset;

		$limite = $limite > 0 ? $limite : 100;
		$offset = $offset >= 0 ? $offset : 0;

		$this->db->select(
			'a.idProducto, a.nombre, a.codigoInterno, a.codigoBarras, a.descripcion, a.servicio, a.precioImpuestos, a.idLinea, '
			.'a.fechaActualizacion as productoActualizacion, '
			.'h.precioA, h.precioB, h.precioC, h.precioD, h.precioE, h.stock, h.cantidadMayoreo, '
			.'h.fechaActualizacion as inventarioActualizacion, '
			.'g.tasa, g.nombre as impuestoNombre, g.tipo as impuestoTipo, g.idImpuesto, '
			.'(select b.nombre from fac_catalogos_unidades as b where b.idUnidad=a.idUnidad) as unidad',
			false
		);

		$this->db->from('productos as a');
		$this->db->join('configuracion_impuestos as g', 'g.idImpuesto = a.idImpuesto');
		$this->db->join('productos_inventarios as h', 'h.idProducto = a.idProducto');
		$this->db->where('a.activo', '1');
		$this->db->where('h.idLicencia', $this->idLicencia);

		if(!empty($desde))
		{
			$this->db->group_start();
			$this->db->where('a.fechaActualizacion >=', $desde);
			$this->db->or_where('h.fechaActualizacion >=', $desde);
			$this->db->group_end();
		}

		$this->db->group_by('a.idProducto');
		$this->db->order_by('a.fechaActualizacion', 'DESC');
		$this->db->limit($limite, $offset);

		return $this->db->get()->result();
	}
	
	public function obtenerProductoCodigo($codigoBarras)
	{
		$sql =" select a.idProducto, a.nombre, a.imagen, 
		h.precioA, h.precioB, h.precioC, a.reventa, 
		a.codigoBarras, a.codigoInterno, h.stock,
		h.precioD, h.precioE, d.idPeriodo, d.nombre as periodo,
		d.factor, d.valor, a.servicio,  a.precioImpuestos,
		
		(select coalesce(sum(e.cantidad),0) from cotiza_productos as e
		inner join cotizaciones as f
		on f.idCotizacion=e.idCotizacion
		where a.idProducto=e.idProducto
		and f.estatus='1'
		and f.cancelada='0') as vendidos,
		
		(select b.descripcion from unidades as b where b.idUnidad=a.idUnidad) as unidad,
		
		g.tasa, g.nombre as impuesto, g.tipo as tipoImpuesto, g.idImpuesto
		
		from productos as a
		inner join produccion_periodos as d
		on a.idPeriodo=d.idPeriodo
		
		
		inner join configuracion_impuestos as g
		on g.idImpuesto=a.idImpuesto
		
		inner join productos_inventarios as h
		on h.idProducto=a.idProducto
		
		where a.activo='1'
		and a.materiaPrima='0'
		and  (a.codigoBarras = '$codigoBarras'
		or a.codigoInterno = '$codigoBarras' )
		
		
		and h.idLicencia='$this->idLicencia'
		
		limit 1 ";

		$producto	= $this->db->query($sql)->row();
		
		return $producto!=null?$producto:array('idProducto'=>0);
	}
	
	public function obtenerProductoId($idProducto)
	{
		$sql =" select a.idProducto, a.nombre, a.imagen, 
		h.precioA, h.precioB, h.precioC, a.reventa, 
		a.codigoBarras, a.codigoInterno, h.stock,
		h.precioD, h.precioE, d.idPeriodo, d.nombre as periodo,
		d.factor, d.valor, a.servicio,  a.precioImpuestos,
		
		(select coalesce(sum(e.cantidad),0) from cotiza_productos as e
		inner join cotizaciones as f
		on f.idCotizacion=e.idCotizacion
		where a.idProducto=e.idProducto
		and f.estatus='1'
		and f.cancelada='0') as vendidos,
		
		(select b.descripcion from unidades as b where b.idUnidad=a.idUnidad) as unidad,
		
		g.tasa, g.nombre as impuesto, g.tipo as tipoImpuesto, g.idImpuesto
		
		from productos as a
		inner join produccion_periodos as d
		on a.idPeriodo=d.idPeriodo
		
		
		inner join configuracion_impuestos as g
		on g.idImpuesto=a.idImpuesto
		
		inner join productos_inventarios as h
		on h.idProducto=a.idProducto
		
		where a.activo='1'
		and a.materiaPrima='0'
		and a.idProducto = '$idProducto'
		
		
		and h.idLicencia='$this->idLicencia'
		
		limit 1 ";

		$producto	= $this->db->query($sql)->row();
		
		return $producto!=null?$producto:array('idProducto'=>0);
	}
	
	public function obtenerProducto($idProducto)
	{
		$sql="select a.idProducto, a.nombre, a.descripcion, a.idImpuesto,
		a.codigoInterno, a.codigoBarras, a.solo,
		b.precioA, b.precioB, b.precioC, b.precioD, b.precioE
		from productos as a
		inner join productos_inventarios as b
		on a.idProducto=b.idProducto
		where a.idProducto='$idProducto'
		and b.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerProductoLicencia($idProducto,$idLicencia)
	{
		$sql="select a.idProducto, a.nombre, a.descripcion, a.idImpuesto,
		a.codigoInterno, a.codigoBarras,
		b.precioA, b.precioB, b.precioC, b.precioD, b.precioE
		from productos as a
		inner join productos_inventarios as b
		on a.idProducto=b.idProducto
		where a.idProducto='$idProducto'
		and b.idLicencia='$idLicencia' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerProductoInventario($idProducto,$idTienda=0)
	{
		$sql="select a.nombre, a.codigoInterno,
		a.stock, a.unidad, b.nombre as linea,
		c.stock
		from productos as a
		inner join productos_lineas as b
		on a.idLinea=b.idLinea 
		
		inner join productos_inventarios as c
		on a.idProducto=c.idProducto
		where c.idLicencia='$this->idLicencia'  ";
		
		/*$sql.=$idTienda>0?" inner join tiendas_productos as c
		on c.idProducto=a.idProducto
		where c.idTienda='$idTienda' ":'';*/
		
		$sql.=" and a.idProducto='$idProducto' ";
		
		return $this->db->query($sql)->row();
	}
	
	//ENVIOS Y RECEPCIONES DE PRODUCTOS
	public function obtenerEnviosProductoInventario($idProducto,$idTienda=0)
	{
		/*$sql=" select a.cantidad, a.folio,
		a.fecha, if(a.idTienda=0,'Matriz',(select b.nombre from tiendas as b where b.idTienda=a.idTienda)) as tienda
		from tiendas_recepciones as a
		where a.idTiendaOrigen='$idTienda'
		and  a.idProducto='$idProducto' ";*/
		
		$sql=" select a.cantidad, b.folio,
		b.fechaTraspaso as fecha, b.idCotizacion,
		(select c.nombre from configuracion as c where c.idLicencia=b.idLicenciaDestino limit 1) as sucursal
		from productos_traspasos_detalles as a
		inner join productos_traspasos as b
		on a.idTraspaso=b.idTraspaso
		where b.idLicenciaOrigen='$this->idLicencia'
		and  a.idProducto='$idProducto'
		and b.activo='1' 
		order by fecha desc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerRecepcionesProductoInventario($idProducto,$idTienda=0)
	{
		/*$sql=" select a.cantidad, a.folio,
		a.fecha, if(a.idTiendaOrigen=0,'Matriz',(select b.nombre from tiendas as b where b.idTienda=a.idTiendaOrigen)) as tienda
		from tiendas_recepciones as a
		where a.idTienda='$idTienda'
		and  a.idProducto='$idProducto' ";*/
		
		
		$sql=" select a.folio, a.fechaRecepcion as fecha,
		a.comentarios, b.cantidad, c.idProducto,
		
		(select e.nombre from configuracion as e
		where e.idLicencia=d.idLicenciaOrigen limit 1) as sucursal
		
		from productos_traspasos_recepciones as a
		
		inner join productos_traspasos_recepciones_detalles as b
		on a.idRecepcion=b.idRecepcion
		
		inner join productos_traspasos_detalles as c
		on a.idRecepcion=b.idRecepcion
		
		
		inner join productos_traspasos as d
		on d.idTraspaso=c.idTraspaso
		
		where c.idDetalle=b.idDetalle
		and  c.idProducto='$idProducto'
		and a.idLicencia='$this->idLicencia'
		and d.activo='1' ";
		

		return $this->db->query($sql)->result();
	}
	
	public function obtenerProductoStock($idProducto)
	{
		$sql=" select a.nombre, a.servicio, b.idInventario, a.idProducto, b.stock 
		from productos as a
		inner join productos_inventarios as b
		on a.idProducto=b.idProducto
		where a.idProducto='$idProducto'
		and b.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function actualizarStockProducto($idProducto,$cantidad,$criterio='restar')
	{
		$producto	= $this->obtenerProductoStock($idProducto);
		
		if($producto!=null)
		{
			$data=array
			(
				'stock'	=> $criterio=='restar'?$producto->stock-$cantidad:$producto->stock+$cantidad
			);
			
			$this->db->where('idInventario',$producto->idInventario);
			$this->db->update('productos_inventarios',$data);
		}
	}
	
	public function obtenerProductoStockLicencia($idProducto,$idLicencia)
	{
		$sql=" select a.nombre, a.servicio, b.idInventario, a.idProducto, b.stock 
		from productos as a
		inner join productos_inventarios as b
		on a.idProducto=b.idProducto
		where a.idProducto='$idProducto'
		and b.idLicencia='$idLicencia' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function actualizarStockProductoSucursal($idProducto,$cantidad,$criterio='restar',$idLicencia)
	{
		$producto	= $this->obtenerProductoStockLicencia($idProducto,$idLicencia);
		
		if($producto!=null)
		{
			$data=array
			(
				'stock'	=> $criterio=='restar'?$producto->stock-$cantidad:$producto->stock+$cantidad
			);
			
			$this->db->where('idInventario',$producto->idInventario);
			$this->db->update('productos_inventarios',$data);
		}
	}
	
	public function obtenerUltimaCompraProducto($idProducto)
	{
		$sql=" select a.precio
		from compra_detalles as a
		inner join compras as b
		on a.idCompra=b.idCompras
		where a.idMaterial='$idProducto'
		order by b.fechaCompra desc
		limit 1 ";
		
		$compra	= $this->db->query($sql)->row();
		
		return $compra!=null?$compra->precio:0;
	}
	
	public function obtenerCostoPromedioCompraProducto($idProducto)
	{
		$sql=" select avg(a.precio) as promedio
		from compra_detalles as a
		inner join compras as b
		on a.idCompra=b.idCompras
		where a.idMaterial='$idProducto'
		order by b.fechaCompra desc
		limit 5 ";
		
		$compra	= $this->db->query($sql)->row();
		
		return $compra!=null?$compra->promedio:0;
	}
	
	//ASIGNAR PROVEEDOR A PRODUCTOS
	public function asignarProveedor()
	{
		$this->db->trans_start();
		
		$criterio				= $this->input->post('criterio');
		$orden					= $this->input->post('orden');
		$minimo					= $this->input->post('minimo');
		$codigoInterno			= $this->input->post('codigoInterno');
		$idProveedor			= $this->input->post('idProveedor');
		
		$productos 				= $this->obtenerProductosPaginado(0,0,$criterio,$orden,$minimo,$codigoInterno,$idProveedor);
		
		foreach($productos as $row)
		{
			$data=array
			(
				'idProducto'			=> $row->idProducto,
				'idProveedor'			=> $idProveedor,
			);

			$this->db->insert('rel_producto_proveedor', $data);
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
	
	public function obtenerPorcentajesProducto($idProducto)
	{
		$sql=" select a.*
		from productos_porcentaje_historial as a
		where a.idProducto='$idProducto'
		and a.idLicencia='$this->idLicencia'
		order by a.fecha desc limit 5 ";
		
		return $this->db->query($sql)->result();
	}
	
	public function asignarPorcentajes()
	{
		$this->db->trans_start();
		
		$porcentaje1			= $this->input->post('txtPorcentaje1');
		$porcentaje2			= $this->input->post('txtPorcentaje2');
		$porcentaje3			= $this->input->post('txtPorcentaje3');
		$idProducto				= $this->input->post('txtIdProducto');
		
		$producto 				= $this->obtenerProducto($idProducto);
		
		$data					= array('fechaActualizacion'	=> $this->_fecha_actual);

		if($porcentaje1>0)
		{
			$data['precioA']	= $producto->precioA+($producto->precioA*($porcentaje1/100));
		}

		if($porcentaje2>0)
		{
			$data['precioB']	= $producto->precioB+($producto->precioB*($porcentaje2/100));
		}

		if($porcentaje3>0)
		{
			$data['precioC']	= $producto->precioC+($producto->precioC*($porcentaje3/100));
		}
		
		/*$this->db->where('idProducto', $idProducto);
		$this->db->where('idLicencia', $this->idLicencia);
		$this->db->update('productos_inventarios', $data);*/
		

		$this->db->where('idProducto', $idProducto);
		$this->db->where('idLicencia', $this->idLicencia);
		$this->db->update('productos_inventarios', $data);
		
		$data=array
		(
			'idProducto' 	=> $idProducto,
			'porcentaje1' 	=> $porcentaje1,
			'porcentaje2' 	=> $porcentaje2,
			'porcentaje3' 	=> $porcentaje3,
			'idUsuario' 	=> $this->_user_id,
			'fecha' 		=> $this->_fecha_actual,
			'idLicencia' 	=> $this->idLicencia,
		);

		$this->db->insert('productos_porcentaje_historial', $data);
		
		
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
	
	public function asignarPorcentajesSucursales()
	{
		$this->db->trans_start();
		
		$porcentaje1			= $this->input->post('txtPorcentaje1');
		$porcentaje2			= $this->input->post('txtPorcentaje2');
		$porcentaje3			= $this->input->post('txtPorcentaje3');
		$idProducto				= $this->input->post('txtIdProducto');
		
		$numeroSucursales		= $this->input->post('txtNumeroSucursales');
		
		for($i=0;$i<$numeroSucursales;$i++)
		{
			$idLicencia				= $this->input->post('chkSucursal'.$i);
			
			if($idLicencia>0)
			{
				$producto 				= $this->obtenerProductoLicencia($idProducto,$idLicencia);
				
				if($producto!=null)
				{
					$data					= array('fechaActualizacion'	=> $this->_fecha_actual);

					if($porcentaje1>0)
					{
						$data['precioA']	= $producto->precioA+($producto->precioA*($porcentaje1/100));
					}

					if($porcentaje2>0)
					{
						$data['precioB']	= $producto->precioB+($producto->precioB*($porcentaje2/100));
					}

					if($porcentaje3>0)
					{
						$data['precioC']	= $producto->precioC+($producto->precioC*($porcentaje3/100));
					}

					$this->db->where('idProducto', $idProducto);
					$this->db->where('idLicencia', $idLicencia);
					$this->db->update('productos_inventarios', $data);

					$data=array
					(
						'idProducto' 	=> $idProducto,
						'porcentaje1' 	=> $porcentaje1,
						'porcentaje2' 	=> $porcentaje2,
						'porcentaje3' 	=> $porcentaje3,
						'idUsuario' 	=> $this->_user_id,
						'fecha' 		=> $this->_fecha_actual,
						'idLicencia' 	=> $idLicencia,
					);

					$this->db->insert('productos_porcentaje_historial', $data);
				}
			}
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
	
	public function obtenerInformacionMovimientos($idProducto)
	{
		$sql=" select * from productos_inventarios_movimientos
		where idProducto='$idProducto'
		and idLicencia='$this->idLicencia'
		order by fecha desc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerInformacionDiario($idProducto)
	{
		$sql=" select a.stock, b.fecha
		from productos_inventarios_registro_detalles as a
		inner join productos_inventarios_registro as b
		on a.idRegistro=b.idRegistro
		where a.idProducto='$idProducto'
		and a.idLicencia='$this->idLicencia'
		order by b.fecha desc ";
		
		return $this->db->query($sql)->result();
	}
	
	
	public function borrarInventarioSucursal()
	{
		$this->db->trans_start(); 
		
		$sql=" select a.idProducto, a.idInventario, a.idLicencia, a.stock
		from productos_inventarios as a
		where a.stock>0
		and a.idLicencia='$this->idLicencia' ";
		
		$productos	= $this->db->query($sql)->result();
		
		if($productos!=null)
		{
			foreach($productos as $row)
			{
				$this->db->where('idInventario',$row->idInventario);
				$this->db->update('productos_inventarios',array('stock'=>0));
				
				$this->db->insert('productos_inventarios_movimientos',array('fecha'=>$this->_fecha_actual,'cantidad'=>$row->stock,'idProducto'=>$row->idProducto,'idLicencia'=>$this->idLicencia,'movimiento'=>'Salida','inventarioAnterior'=>$row->stock,'inventarioActual'=>0,'idUsuario'=>$this->_user_id));
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
			
			return array('1','Registro');
		}   
	}
}
?>
