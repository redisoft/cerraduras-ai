<?php
class Compras_modelo extends CI_Model
{
    protected $_fecha_actual;
    protected $_table;
    protected $_user_id;
    protected $_user_name;
	protected $idLicencia;
	protected $resultado;
	protected $idRol;

    function __construct()
	{
		parent::__construct();
		$this->config->load('datatables',TRUE);
	
		$this->_table 			= $this->config->item('datatables');
		$this->_fecha_actual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_user_id 		= $this->session->userdata('id');
		$this->_user_name 		= $this->session->userdata('nombreUsuarioSesion');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->resultado 		= "1";
		$this->idRol 			= $this->session->userdata('role');
    }

	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//COMPRAS DE MATERIA PRIMA
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

	public function contarCompras($inicio,$fin,$criterio)
	{
		$sql ="select a.idCompras, a.idProveedor,
		a.total, a.fechaCompra, b.empresa
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor)
		where a.reventa='0' 
		and a.inventario='0'
		and a.idLicencia='$this->idLicencia'
		and date(a.fechaCompra) between '$inicio' and '$fin' 
		and (b.empresa like '%$criterio%' or a.nombre like '%$criterio%')";

		/*$sql.=$fecha!='fecha'?" and date(a.fechaCompra)='$fecha'":'';
		$sql.=$idCompras>0?" and a.idCompras='$idCompras'":'';
		$sql.=$idProveedor>0?" and a.idProveedor='$idProveedor'":'';*/
		if($this->idRol!=6)
		{
			$sql.=$this->idRol!=1?" and a.idUsuario='$this->_user_id' ":'';
		}
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerCompras($numero,$limite,$inicio,$fin,$criterio)
	{
		$sql ="select a.idCompras, a.idProveedor, a.nombre, a.cerrada,
		a.total, a.fechaCompra, b.empresa, a.cancelada, a.fechaEntrega,
		(select c.idSeguimiento from proveedores_seguimiento as c where c.idCompra=a.idCompras order by c.fecha desc limit 1) as idSeguimiento,
		
		(select coalesce(sum(c.cantidad),0) from compras_recibido as c
		inner join compra_detalles as d
		on d.idDetalle=c.idDetalle
		where d.idCompra=a.idCompras) as recibidos,
		
		(select coalesce(sum(c.cantidad),0) from compra_detalles as c
		where a.idCompras=c.idCompra) as comprados
		
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor)
		where a.reventa='0' 
		and a.inventario='0'
		and a.servicios='0'
		and a.idLicencia='$this->idLicencia'
		and date(a.fechaCompra) between '$inicio' and '$fin' 
		and (b.empresa like '%$criterio%' or a.nombre like '%$criterio%')  ";
		
		/*$sql.=$fecha!='fecha'?" and date(a.fechaCompra)='$fecha'":'';
		$sql.=$idCompras>0?" and a.idCompras='$idCompras'":'';
		$sql.=$idProveedor>0?" and a.idProveedor='$idProveedor'":'';*/
		
		if($this->idRol!=6)
		{
			$sql.=$this->idRol!=1?" and a.idUsuario='$this->_user_id' ":'';
		}
		
		$sql.=" order by a.fechaCompra desc ";
		$sql .= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}

	public function obtenerFechaFinServicio($valor,$factor,$fecha)
	{
		$sql="select date_add('".$fecha."',interval ".$valor." $factor) as fechaFin";
		
		return $this->db->query($sql)->row()->fechaFin;
	}

	public function registrarCompraMateria()
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
			'folio'					=> $this->obtenerFolioCompras(),
			'terminos'				=> $this->input->post('terminos'),
			'idUsuario'				=> $this->_user_id,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('compras',$data);
		$idCompra	= $this->db->insert_id();
		
		$idCuentaCatalogo	= $this->proveedores->obtenerProveedorCuentaCatalogo($idProveedor);
		//REGISTAR LA PÓLIZA
		if($idCuentaCatalogo>0)
		{
			$this->contabilidad->registrarPolizaCompraVenta($data['fechaCompra'],$data['nombre'],$idCuentaCatalogo,$data['total'],'compra',$idCompra); 
		}
		
		$this->configuracion->registrarBitacora('Registrar compra de materia prima','Compras - Materia prima',$this->input->post('nombreKit').', '.$this->proveedores->obtenerProveedorNombre($idProveedor),$idCompra); //Registrar bitácora
		
		$this->registrarPagoProgramado($this->input->post('total'),$diasCredito,$idProveedor,$this->input->post('nombreKit'),$idCompra); //Registrar el pago programado
		
		for($i=0;$i<$indice;$i++)
		{
			$data=array
			(
				'idCompra' 				=> $idCompra,
				'idMaterial' 			=> $productos[$i],
				'cantidad' 				=> $cantidad[$i],
				'total' 				=> $preciosTotales[$i],
				'precio' 				=> $precioProducto[$i],
				'fechaEntrega'			=> $fechas[$i],
				'descuento'				=> $descuentos[$i],
				'descuentoPorcentaje'	=> $descuentosPorcentajes[$i],
				
				'cantidadOriginal' 		=> $cantidad[$i],
			);
			
			$this->db->insert('compra_detalles',$data);
			
			$this->actualizarCostoPromedio($productos[$i]); #Actualizar el costo promedio, la cantidad de operaciones
															#puede ser axageradamente grande

			$this->actualizarProductosMateriales($productos[$i]); #Precios unitarios
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

	public function productosRecibidos($idDetalle)
	{
		$sql="select a.*, b.nombre as sucursal
		from compras_recibido a
		left join configuracion b
		on a.idLicencia=b.idLicencia
		where idDetalle='$idDetalle'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerProductoCompra($idDetalle)
	{
		$sql="select a.cantidad, a.precio, 
		a.idCompra, b.nombre, a.idCompra, a.idMaterial, 
		a.descuento, a.descuentoPorcentaje, a.fechaEntrega
		from compra_detalles as a
		inner join produccion_materiales as b
		on a.idMaterial=b.idMaterial
		where a.idDetalle='$idDetalle'";
		
		return $this->db->query($sql)->row();
	}
	
	public function totalRecibido($idDetalle)
	{
		$sql="select sum(cantidad) as cantidad
		from compras_recibido
		where idDetalle='$idDetalle'";
		
		$cantidad=$this->db->query($sql)->row()->cantidad;
		
		return $cantidad!=null?$cantidad:0;
	}
	
	public function confirmarRecibirCompra()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$idDetalle		= $this->input->post('txtIdDetalle');
		$cantidad		= $this->input->post('txtCantidadRecibir');
		$fecha			= $this->input->post('txtFechaRecibido');
		$material		= $this->obtenerProductoCompra($idDetalle);
		$idMaterial		= $material->idMaterial;
		$totalRecibido	= $this->totalRecibido($idDetalle);
		$compra			= $this->obtenerCompraDetalle($material!=null?$material->idCompra:0);
		
		
		#$totalUnidades	= $material->cantidad;
		
		if(($cantidad+$totalRecibido)>$material->cantidad)
		{
			return array('0','Error al recibir el producto, esta tratando de ingresar mas de lo comprado');
		}
		
		if(($cantidad+$totalRecibido)==$material->cantidad)
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
			'idUsuario' => $this->_user_id,
			'recibio' 	=> $this->_user_name,
			'remision' 	=> $this->input->post('txtRemision'),
			'factura' 	=> $this->input->post('selectFactura'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('compras_recibido',$data);
		$idRecibido	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Recibir materia prima','Compras - Materia prima',$material->nombre.', Compra: '.$compra[0].', Cantidad: '.number_format($cantidad,2)); //Registrar bitácora
		#--------------------Actualizar existencias en productos------------------------#
		
		 $sql="select a.stock
		 from produccion_materiales as a
		 where a.idMaterial='$idMaterial'";
		 
		 $query=$this->db->query($sql)->row();

		 $data=array
		 (
		 	'stock' => $query->stock+$cantidad
		 );
		 
		 $this->db->where('idMaterial',$idMaterial);
		 $this->db->update('produccion_materiales',$data);
		 
		 
		//REGISTRAR LA ENTRADA DE MATERIA PRIMA POR PROVEEDOR
		$data=array
		(
			'fecha' 		=> $this->_fecha_actual,
			'fechaEntrada' 	=> $fecha,
			'cantidad' 		=> $cantidad,
			'idDetalle' 	=> $idDetalle,
			'idRecibido' 	=> $idRecibido,
			'idUsuario' 	=> $this->_user_id,
			'idProveedor' 	=> $this->input->post('txtIdProveedorCompra'),
			'idMaterial' 	=> $idMaterial,
			'comentarios' 	=> $this->input->post('txtRemision'),
			'idLicencia' 	=> $this->idLicencia,
		);
		
		$this->db->insert('produccion_materiales_entradas',$data); 
		
		//SUBIR EL ARCHIVO SI ES QUE EXISTE
		$archivo 		= $_FILES['txtArchivoComprobante']['name'];
		if(strlen($archivo)>0)
		{
			$idComprobante	= $this->subirFicherosCompra($material->idCompra,$archivo,$_FILES['txtArchivoComprobante']['size'],$idRecibido);
			
			move_uploaded_file($_FILES['txtArchivoComprobante']['tmp_name'], carpetaCompras.basename($idComprobante."_".$archivo));
		}
		
		$recibidos		= $this->obtenerCompra($material->idCompra);
		
		if($recibidos->totalRecibido==$recibidos->totalComprado)
		{
			//CERRAR LA COMPRITA
			$this->db->where('idCompras',$material->idCompra);
			$this->db->update('compras',array('cerrada'=>'1'));
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
			
			return array('1','El producto se ha recibido correctamente');
		}
	}

	public function contarMaterialesInventario()
	{
		$busquedaNombre	=$this->input->post('buscame');
		$idProveedor	=$this->input->post('idProveedor');
		
		$sql="select a.nombre
		from produccion_materiales as a
		inner join rel_material_proveedor as b
		on a.idMaterial=b.idMaterial
		inner join proveedores as c
		on c.idProveedor=b.idProveedor
		where a.nombre like'%$busquedaNombre%' 
		and a.idLicencia='$this->idLicencia' 
		and c.activo='1' 
		and a.produccion='0' ";
		
		if($idProveedor!=0)
		{
			$sql.=" and b.idProveedor='$idProveedor'";
		}
		
		$query=$this->db->query($sql);
		
		return($query->num_rows()>0) ? $query->num_rows() : 0;
	}
	
	public function obtenerMaterialesInventario($numero,$limite)
	{
		$busquedaNombre	= $this->input->post('buscame');
		$idProveedor	= $this->input->post('idProveedor');
		
		$sql="select a.nombre, b.costo, a.stock, a.idMaterial, 
		b.idProveedor, c.empresa, d.descripcion as unidad,
		
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_entradas as e where e.idMaterial=a.idMaterial and b.idProveedor=e.idProveedor and e.idLicencia='$this->idLicencia') as inventario,
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_mermas as e where e.idMaterial=a.idMaterial and b.idProveedor=e.idProveedor and e.fechaRegistro is not null and e.idLicencia='$this->idLicencia') as salidas
		
		from produccion_materiales as a
		inner join rel_material_proveedor as b
		on a.idMaterial=b.idMaterial
		inner join proveedores as c
		on c.idProveedor=b.idProveedor
		
		inner join unidades as d
		on a.idUnidad=d.idUnidad
		
		where a.nombre like'%$busquedaNombre%' 
		and c.activo='1' 
		and a.produccion='0' ";

		$sql.=$idProveedor!=0?" and b.idProveedor='$idProveedor'":'';
		$sql .= " order by a.nombre asc ";
		$sql .= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function precioMaterial()
	{
		$data=array
		(
			'costo'	=>$this->input->post('costo')
		);
		
		$this->db->where('idMaterial',$this->input->post('idMaterial'));
		$this->db->where('idProveedor',$this->input->post('idProveedor'));
		$this->db->update('rel_material_proveedor',$data);
		
		$material	= $this->materiales->obtenerDetalleMaterial($this->input->post('idMaterial'),$this->input->post('idProveedor'));
		
		$this->configuracion->registrarBitacora('Editar costo materia prima','Materia prima',$material[0].', '.$material[1].', Costo: '.number_format($this->input->post('costo'),2)); //Registrar bitácora
		
		return "1";
	}
	
	public function recibirTodosMateriales()
	{
		$this->db->trans_start();
		
		$idCompra		= $this->input->post('idCompra');
		$factura		= $this->input->post('factura');
		$fecha			= $this->input->post('fecha');
		$remision		= $this->input->post('remision');
		
		$compra			= $this->obtenerCompra($idCompra);
		$materiales		= $this->obtenerProductosComprados($idCompra);
		
		foreach($materiales as $row)
		{
			$material		= $this->obtenerProductoCompra($row->idDetalle);
			$idMaterial		= $material->idMaterial;
			$totalRecibido	= $this->totalRecibido($row->idDetalle);
	
			if(($row->cantidad+$totalRecibido)>$material->cantidad)
			{
				$this->db->trans_rollback();
				$this->db->trans_complete();
				
				return array('0','Error al recibir los productos, ya habia una recepción parcial');
			}
			
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$data=array
			(
				'recibido' =>1,
			);
			
			$this->db->where('idDetalle',$row->idDetalle);
			$this->db->update('compra_detalles',$data);
			
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$data=array
			(
				'fecha' 	=> $fecha,
				'cantidad' 	=> $row->cantidad,
				'idDetalle' => $row->idDetalle,
				'idUsuario' => $this->_user_id,
				'recibio' 	=> $this->_user_name,
				'remision' 	=> $remision,
				'factura' 	=> $factura,
			);
			
			$data	= procesarArreglo($data);
			$this->db->insert('compras_recibido',$data);
			$idRecibido	= $this->db->insert_id();
			#--------------------Actualizar existencias en productos------------------------#
			
			 $sql="select a.stock
			 from produccion_materiales as a
			 where a.idMaterial='$idMaterial'";
			 
			 $query	= $this->db->query($sql)->row();
	
			 $data=array
			 (
				'stock' => $query->stock+$row->cantidad
			 );
			 
			 $this->db->where('idMaterial',$idMaterial);
			 $this->db->update('produccion_materiales',$data);
			 
			//REGISTRAR LA ENTRADA DE MATERIA PRIMA POR PROVEEDOR
			$data=array
			(
				'fecha' 		=> $this->_fecha_actual,
				'fechaEntrada' 	=> $fecha,
				'cantidad' 		=> $row->cantidad,
				'idDetalle' 	=> $row->idDetalle,
				'idUsuario' 	=> $this->_user_id,
				'idProveedor' 	=> $compra->idProveedor,
				'idMaterial' 	=> $idMaterial,
				'comentarios' 	=> $remision,
				'idRecibido' 	=> $idRecibido,
				'idLicencia'	=> $this->idLicencia,
			);
			
			$data	= procesarArreglo($data);
			$this->db->insert('produccion_materiales_entradas',$data); 
		}
		
		$this->configuracion->registrarBitacora('Recibir toda la materia prima','Compras - Materia prima','Compra: '.$compra->nombre); //Registrar bitácora
		
		$this->db->where('idCompras',$idCompra);
		$this->db->update('compras',array('cerrada'=>'1'));
		
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
	
	public function borrarMaterialRecibido()
	{
		$this->db->trans_start();
		
		$idRecibido	= $this->input->post('idRecibido');
		$recibido	= $this->obtenerDetalleMaterialRecibido($idRecibido);
		
		$this->borrarArchivosRecibido($idRecibido);
		
		$this->db->where('idRecibido',$idRecibido);
		$this->db->delete('produccion_materiales_entradas');
		
		$this->db->where('idRecibido',$idRecibido);
		$this->db->delete('compras_recibido');
		
		$this->configuracion->registrarBitacora('Borrar material recibido','Compras - Materia prima',$recibido[1].', Compra: '.$recibido[0].', Cantidad: '.number_format($recibido[2],2)); //Registrar bitácora

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
	
	public function obtenerDetalleMaterialRecibido($idRecibido)
	{
		$sql="select a.cantidad, b.total, c.nombre, d.nombre as compra
		from compras_recibido as a
		inner join compra_detalles as b
		on a.idDetalle=b.idDetalle
		inner join produccion_materiales as c
		on c.idMaterial=b.idMaterial 
		inner join compras as d
		on d.idCompras=b.idCompra 
		where a.idRecibido='$idRecibido' ";
		
		$recibido	=$this->db->query($sql)->row();
		
		return $recibido!=null?array($recibido->compra,$recibido->nombre,$recibido->cantidad):array('Sin detalles de compra','',0);
	}
	
	
	public function actualizarProductosMateriales($idMaterial) #SI SE CAMBIO EL PRECIO DE UN MATERIAL ENTONCES TAMBIEN CAMBIA EL COSTO GLOBAL
	{
		$sql="select idProducto 
		from rel_producto_material
		where idMaterial='$idMaterial'";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$this->actualizarProductoProduccion($row->idProducto); #Precios unitarios
		}
	}
	
	public function obtenerDatosProducto($idProducto)
	{
		$sql="select * from produccion_productos
		where idProducto='$idProducto' 
		and servicio='0' 
		and reventa='0' 
		and activo='1' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function actualizarProductoProduccion($idProducto)
	{
		$costoTotal=0;
		$sql="select a.*, b.costo 
		from rel_producto_material as a
		inner join produccion_materiales as b
		on a.idMaterial=b.idMaterial
		where idProducto='$idProducto'";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			if($row->idConversion>0)
			{
				$sql="select * from unidades_conversiones
				where idConversion='$row->idConversion'";
				
				$conversiones	 =$this->db->query($sql)->row();
				$precioUnitario	 =$row->costo/$conversiones->valor;
				$costoTotal		+=$precioUnitario*$row->cantidad;
			}
			else
			{
				$costoTotal+=$row->costo*$row->cantidad;
			}
		}
		
		$data = array
		(
			'costo' => $costoTotal
		);
		
		$this->db->where('idProducto',$idProducto);
		$this->db->update('productos',$data);
	}

	public function actualizarCostoPromedio($idMaterial)
	{
		$sql="select b.idProveedor
		 from compra_detalles as a
		 inner join compras as b
		 on a.idCompra=b.idCompras 
		 where a.idMaterial='$idMaterial'
		 group by b.idProveedor";
		 
		$query			=$this->db->query($sql);
		$costo			=0;
		$nProveedores	=count($query->result()); #Es el numero de proveedores
		
		if($nProveedores>0)
		{
			$nProveedores=0;
			
			foreach($query->result() as $row)
			{
				$sql="select a.precio, b.idCompras
				from compra_detalles as a
				inner join compras as b
				on a.idCompra=b.idCompras
				where b.idProveedor='$row->idProveedor'
				and a.idMaterial='$idMaterial'
				order by b.fechaCompra desc
				limit 1";
				
				$queri=$this->db->query($sql);
				
				$queri=$queri->row();
				
				if($queri!=null)
				{
					$costo+=$queri->precio;
					$nProveedores+=1;
				}
			} 
			
			$costoPromedio=$costo/$nProveedores;
			
			$data=array
			(
				'costo'	=>$costoPromedio
			);
			
			$this->db->where('idMaterial',$idMaterial);
			$this->db->update('produccion_materiales',$data);
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
		and b.activo='1' ";
					
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
	#-----------------------------------------------------------------------------------------------------------------------------#
	
	
	public function obtenerProductosComprados($idCompras)
	{
		$sql="	select a.cantidad, b.nombre, a.idCompra, a.idMaterial,
		a.total, a.precio, a.recibido, a.idDetalle,  a.fechaEntrega,
		a.totalRecibido, a.descuento, a.descuentoPorcentaje, a.cantidadOriginal
		from compra_detalles as a
		inner join produccion_materiales as b
		on a.idMaterial=b.idMaterial
		where a.idCompra='$idCompras' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerProductosPDF($idCompras)
	{
		$sql="	select a.cantidad, b.nombre, a.idCompra, 
		a.total, a.precio, a.recibido, a.idDetalle,
		b.codigoInterno, a.descuento, a.descuentoPorcentaje
		from compra_detalles as a
		inner join produccion_materiales as b
		on a.idMaterial=b.idMaterial
		where a.idCompra='$idCompras' ";
				
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCompra($idCompras)
	{
		$sql="	select a.*, b.empresa, b.domicilio,
		b.telefono, b.pais, b.estado, b.email,
		(select coalesce(sum(c.cantidad),0) from compras_recibido as c inner join compra_detalles as d on d.idDetalle=c.idDetalle where d.idCompra=a.idCompras) as totalRecibido,
		(select coalesce(sum(c.cantidad),0) from compra_detalles as c where c.idCompra=a.idCompras) as totalComprado
		from compras as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where idCompras='$idCompras' ";
				
		return $this->db->query($sql)->row();
	}

	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//COMPRAS DE PRODUCTOS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarComprasProductos($fecha,$idCompras,$idProveedor)
	{
		$sql ="select a.idCompras, a.idProveedor,
		a.total, a.fechaCompra, b.empresa
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor)
		where a.idLicencia='$this->idLicencia'
		and a.reventa='1' ";

		$sql.=$fecha!='fecha'?" and date(a.fechaEntrega)='$fecha'":'';
		$sql.=$idCompras>0?" and a.idCompras='$idCompras'":'';
		$sql.=$idProveedor>0?" and a.idProveedor='$idProveedor'":'';
		$sql.=$this->idRol!=1?" and a.idUsuario='$this->_user_id' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerComprasProductos($numero,$limite,$fecha,$idCompras,$idProveedor)
	{
		$sql ="select a.idCompras, a.idProveedor, a.nombre,
		a.total, a.fechaCompra, a.fechaEntrega, b.empresa, a.cancelada,
		(select c.idSeguimiento from proveedores_seguimiento as c where c.idCompra=a.idCompras order by c.fecha desc limit 1) as idSeguimiento
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor)
		where a.idLicencia='$this->idLicencia' 
		and a.reventa='1' ";
					
		$sql.=$fecha!='fecha'?" and date(a.fechaEntrega)='$fecha'":'';
		$sql.=$idCompras>0?" and a.idCompras='$idCompras'":'';
		$sql.=$idProveedor>0?" and a.idProveedor='$idProveedor'":'';
		$sql.=$this->idRol!=1?" and a.idUsuario='$this->_user_id' ":'';
		
		$sql.= " order by a.idCompras desc ";
		$sql.= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function registrarCompraProducto()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$cantidad				= $this->input->post('cantidad');
		$productos				= $this->input->post('productos');
		$preciosTotales			= $this->input->post('preciosTotales');
		$precioProducto			= $this->input->post('precioProducto');
		$fechas					= $this->input->post('fechas');
		$descuentos				= $this->input->post('descuentos');
		$descuentosPorcentajes	= $this->input->post('descuentosPorcentajes');
		
		$indice					= count($cantidad);
		$idProveedor			= $this->input->post('idProveedor');
		$totalCompra			= $this->input->post('kitTotal');
		$diasCredito			= $this->input->post('diasCredito');

		$data=array
		(
			'fechaCompra' 			=> $this->input->post('fecha'),
			'fechaEntrega' 			=> $this->input->post('fechaEntrega'),
			#'total'			=>$totalCompra,
			'total'					=> $this->input->post('total'),
			'subTotal'				=> $this->input->post('kitTotal'),
			'descuento'				=> $this->input->post('descuento'),
			'descuentoPorcentaje'	=> $this->input->post('descuentoPorcentaje'),
			'iva'					=> $this->input->post('iva'),
			'ivaPorcentaje'			=> $this->input->post('ivaPorcentaje'),
			'nombre'				=> $this->input->post('nombreKit'),
			'idProveedor'			=> $idProveedor,
			'idLicencia'			=> $this->idLicencia,
			'reventa'				=> 1,
			'diasCredito'			=> $diasCredito,
			'folio'					=> $this->obtenerFolioCompras(),
			'terminos'				=> $this->input->post('terminos'),
			'idUsuario'				=> $this->_user_id,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('compras',$data);
		$idCompra=$this->db->insert_id();
		
		//REGISTAR LA PÓLIZA
		$idCuentaCatalogo	= $this->proveedores->obtenerProveedorCuentaCatalogo($idProveedor);
		
		if($idCuentaCatalogo>0)
		{
			$this->contabilidad->registrarPolizaCompraVenta($data['fechaCompra'],$data['nombre'],$idCuentaCatalogo,$data['total'],'compra',$idCompra); 
		}
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		
		$this->configuracion->registrarBitacora('Registrar compra de productos','Compras - Productos',$this->input->post('nombreKit').', '.$this->proveedores->obtenerProveedorNombre($idProveedor)); //Registrar bitácora
		
		$this->registrarPagoProgramado($this->input->post('total'),$diasCredito,$idProveedor,$this->input->post('nombreKit'),$idCompra); //Registrar el pago programado
		
		for($i=0;$i<$indice;$i++)
		{
			$data=array
			(
				'idCompra' 				=> $idCompra,
				'idMaterial' 			=> $productos[$i],
				'cantidad' 				=> $cantidad[$i],
				'total' 				=> $preciosTotales[$i],
				'precio' 				=> $precioProducto[$i],
				'fechaEntrega'			=> $fechas[$i],
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
	
	public function obtenerdDetalleProductoCompra($idDetalle)
	{
		$sql="select a.cantidad, a.precio, 
		a.idCompra, b.nombre, a.idCompra, a.idMaterial, a.idCompra, a.fechaEntrega
		from compra_detalles as a
		inner join productos as b
		on a.idMaterial=b.idProducto
		where a.idDetalle='$idDetalle'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerPDFProductos($idCompras)
	{
		$sql="	select a.cantidad, b.nombre, a.idCompra, 
		a.total, a.precio, a.recibido, a.idDetalle,
		b.codigoInterno, a.descuento, a.descuentoPorcentaje,
		
		(select d.descripcion from unidades as d where d.idUnidad=b.idUnidad) as unidad
		
		from compra_detalles as a
		inner join productos as b
		on a.idMaterial=b.idProducto
		where a.idCompra='$idCompras' ";
				
		return $this->db->query($sql)->result();
	}
	
    public function obtenerProductoRelacionado($idProducto)
	{
		$sql=" select a.idProducto, a.stock 
		from productos as a
		where a.idProducto='$idProducto' ";
		
		return $this->db->query($sql)->row();
	}

	public function confirmarRecibirCompraProductos()
	{
		$idDetalle		= $this->input->post('txtIdDetalle');
		$cantidad		= $this->input->post('txtCantidadRecibir');
		$fecha			= $this->input->post('txtFechaRecibido');
		$idLicencia		= $this->input->post('selectLicencias');
		
		$producto		= $this->obtenerdDetalleProductoCompra($idDetalle);
		$totalRecibido	= $this->totalRecibido($idDetalle);
		
		$compra			= $this->obtenerCompraDetalle($producto!=null?$producto->idCompra:0);
		
		if(($cantidad+$totalRecibido)>$producto->cantidad)
		{
			return array('0','Error al recibir el producto, esta tratando de ingresar mas de lo comprado');
		}
		
		$this->db->trans_start(); #La transaccion es debido a la actualización de la tabla compra_detalles, compras_recibido y produccion_productos
		
		if(($cantidad+$totalRecibido)==$producto->cantidad)
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
			'fecha' 		=> $fecha,
			'cantidad' 		=> $cantidad,
			'idDetalle'		=> $idDetalle,
			'idUsuario'		=> $this->_user_id,
			'recibio' 		=> $this->_user_name,
			'remision' 		=> $this->input->post('txtRemision'),
			'factura' 		=> $this->input->post('selectFactura'),
			'idLicencia' 	=> $idLicencia,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('compras_recibido',$data);
		$idRecibido=$this->db->insert_id();
		
		
		$this->configuracion->registrarBitacora('Recibir producto','Compras - Productos',$producto->nombre.', Compra: '.$compra[0].', Cantidad: '.number_format($cantidad,2)); //Registrar bitácora
		#--------------------Actualizar existencias en productos------------------------#
		
		//PRODUCTOS ES LA VARIABLE RELACIONADA A PRODUCCION_PRODUCTOS

		if($idLicencia==0)
		{
			$productos	= $this->inventarios->obtenerProductoStock($producto->idMaterial);
		}
		else
		{
			$productos	= $this->inventarios->obtenerProductoStockLicencia($producto->idMaterial,$idLicencia);
		}
		
		 
		if($productos!=null)
		{
			if($idLicencia==0)
			{
				$this->inventarios->actualizarStockProducto($producto->idMaterial,$cantidad,'sumar');
			}
			else
			{
				$this->inventarios->actualizarStockProductoSucursal($producto->idMaterial,$cantidad,'sumar',$idLicencia);
			}
		}
		else
		{
			if($idLicencia==0)
			{
				$this->db->insert('productos_inventarios',array('idProducto'=>$productos->idProducto,'idLicencia'=>$this->idLicencia,'stock'=>$cantidad));
			}
			else
			{
				$this->db->insert('productos_inventarios',array('idProducto'=>$productos->idProducto,'idLicencia'=>$idLicencia,'stock'=>$cantidad));
			}
		}
		 
		 
		 //SUBIR EL ARCHIVO SI ES QUE EXISTE
		$archivo 		= $_FILES['txtArchivoComprobante']['name'];
		if(strlen($archivo)>0)
		{
			$idComprobante	= $this->subirFicherosCompra($producto->idCompra,$archivo,$_FILES['txtArchivoComprobante']['size'],$idRecibido);
			
			move_uploaded_file($_FILES['txtArchivoComprobante']['tmp_name'], carpetaCompras.basename($idComprobante."_".$archivo));
		}
		 
	 	if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0','Error al recibir el producto');
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1','El producto se ha recibido correctamente');
		}
	}
	
	public function obtenerCompradosProductos($idCompras)
	{
		$sql="	select a.cantidad, b.nombre, a.idCompra, a.idMaterial,
		a.total, a.precio, a.recibido, a.idDetalle, 
		a.totalRecibido, a.fechaEntrega, a.descuento, a.descuentoPorcentaje
		from compra_detalles as a
		inner join productos as b
		on a.idMaterial=b.idProducto
		where a.idCompra='$idCompras' ";
				
		return $this->db->query($sql)->result();
	}
	
	public function contarProductosReventa()
	{
		$busqueda			= $this->input->post('buscame');
		$idProveedor		= $this->input->post('idProveedor');
		
		$sql="select a.idProducto, b.precio as costo
		from productos as a
		inner join rel_producto_proveedor as b
		on a.idProducto=b.idProducto
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		
		inner join productos_inventarios as f
		on f.idProducto=a.idProducto
		
		where a.reventa='1'
		and a.activo='1'  
		and f.idLicencia='$this->idLicencia'  ";
		
		$sql.=strlen($busqueda)>0?" 
		and (a.nombre like'$busqueda%' or a.codigoInterno like'$busqueda%' ) ":'';
		
		if($idProveedor!=0)
		{
			$sql.=" and c.idProveedor='$idProveedor'";
		}
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerProductosReventa($numero,$limite)
	{
		$busqueda			= $this->input->post('buscame');
		$idProveedor		= $this->input->post('idProveedor');
		
		$sql="select a.nombre, b.precio as costo, f.stock, 
		a.idProducto as idMaterial, a.codigoInterno,
		e.idProveedor, e.empresa,
		(select c.nombre from fac_catalogos_unidades as c where c.idUnidad=a.idUnidad) as unidad
		".(sistemaActivo=='cerraduras'?', a.stockMaximo':'')."
		from productos as a
		inner join rel_producto_proveedor as b
		on a.idProducto=b.idProducto
		inner join proveedores as e
		on e.idProveedor=b.idProveedor
		
		inner join productos_inventarios as f
		on f.idProducto=a.idProducto
		
		
		where  f.idLicencia='$this->idLicencia' 
		and a.reventa='1'
		and a.activo='1' ";
		
		$sql.=strlen($busqueda)>0?" 
		and (a.nombre like'$busqueda%' or a.codigoInterno like'$busqueda%' ) ":'';
		
		if($idProveedor!=0)
		{
			$sql.=" and e.idProveedor='$idProveedor'";
		}
		
		$sql .= " order by a.nombre asc ";
		$sql .= " limit $limite,$numero ";
		
		#echo $sql;
		
		return $this->db->query($sql)->result();
	}
	
	public function recibirTodosProductos()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$idCompra		= $this->input->post('idCompra');
		$factura		= $this->input->post('factura');
		$fecha			= $this->input->post('fecha');
		$remision		= $this->input->post('remision');
		$idLicencia		= $this->input->post('idLicencia');

		$productos		= $this->obtenerCompradosProductos($idCompra);
		$compra			= $this->obtenerCompraDetalle($idCompra);
		
		foreach($productos as $row)
		{
			$totalRecibido	= $this->totalRecibido($row->idDetalle);
			$producto		= $this->obtenerdDetalleProductoCompra($row->idDetalle);
			
			if(($row->cantidad+$totalRecibido)>$producto->cantidad)
			{
				$this->db->trans_rollback();
				$this->db->trans_complete();
			
				return array('0','Error al recibir los productos, ya habia una recepción parcial');
			}

			$data=array
			(
				'recibido' =>1,
			);
			
			$this->db->where('idDetalle',$row->idDetalle);
			$this->db->update('compra_detalles',$data);
					
			$data=array
			(
				'fecha' 		=> $fecha,
				'cantidad' 		=> $row->cantidad,
				'idDetalle' 	=> $row->idDetalle,
				'idUsuario' 	=> $this->_user_id,
				'recibio' 		=> $this->_user_name,
				'remision' 		=> $remision,
				'factura' 		=> $factura,
				'idLicencia' 	=> $idLicencia,
			);
			
			$data	= procesarArreglo($data);
			$this->db->insert('compras_recibido',$data);
			#--------------------Actualizar existencias en productos------------------------#
			
			//PRODUCTOS ES LA VARIABLE RELACIONADA A PRODUCCION_PRODUCTOS

			if($idLicencia==0)
			{
				$producto	= $this->inventarios->obtenerProductoStock($row->idMaterial);
			}
			else
			{
				$producto	= $this->inventarios->obtenerProductoStockLicencia($row->idMaterial,$idLicencia);
			}
			
			 
			if($producto!=null)
			{
				if($idLicencia==0)
				{
					$this->inventarios->actualizarStockProducto($row->idMaterial,$row->cantidad,'sumar');
				}
				else
				{
					$this->inventarios->actualizarStockProductoSucursal($row->idMaterial,$row->cantidad,'sumar',$idLicencia);
				}
			}
			else
			{
				if($idLicencia==0)
				{
					$this->db->insert('productos_inventarios',array('idProducto'=>$producto->idProducto,'idLicencia'=>$this->idLicencia,'stock'=>$row->cantidad));
				}
				else
				{
					$this->db->insert('productos_inventarios',array('idProducto'=>$producto->idProducto,'idLicencia'=>$idLicencia,'stock'=>$row->cantidad));
				}
			}
			 
			
			 /*$productoDetalle	= $this->obtenerProductoRelacionado($row->idMaterial);
			 
			 $data=array
			 (
				'stock' => $productoDetalle->stock+$row->cantidad
			 );
			 
			 $this->db->where('idProducto',$productoDetalle->idProducto);
			 $this->db->update('productos',$data);*/
		}
		
		$this->configuracion->registrarBitacora('Recibir todos los productos','Compras - Productos','Compra: '.$compra[0]); //Registrar bitácora

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
	
	public function obtenerProductoRecibidoDetalle($idRecibido)
	{
		$sql=" select a.idProducto, a.nombre, a.stock, c.cantidad, c.idRecibido, c.idLicencia, d.nombre as compra
		from productos as a
		inner join compra_detalles as b
		on a.idProducto=b.idMaterial
		inner join compras_recibido as c
		on c.idDetalle=b.idDetalle
		inner join compras as d
		on b.idCompra=d.idCompras
		where c.idRecibido='$idRecibido' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarProductoRecibido()
	{
		$this->db->trans_start();
		
		$idRecibido	= $this->input->post('idRecibido');
		
		$producto	= $this->obtenerProductoRecibidoDetalle($idRecibido);
		
		if($producto!=null)
		{
			#$this->db->where('idProducto',$producto->idProducto);
			#$this->db->update('productos',array('stock'=>$producto->stock-$producto->cantidad));

			if($producto->idLicencia==0)
			{
				$registro	= $this->inventarios->obtenerProductoStock($producto->idProducto);
			}
			else
			{
				$registro	= $this->inventarios->obtenerProductoStockLicencia($producto->idProducto,$producto->idLicencia);
			}
			
			 
			if($registro!=null)
			{
				if($producto->idLicencia==0)
				{
					$this->inventarios->actualizarStockProducto($producto->idProducto,$producto->cantidad,'restar');
				}
				else
				{
					$this->inventarios->actualizarStockProductoSucursal($producto->idProducto,$producto->cantidad,'restar',$producto->idLicencia);
				}
			}
		
			$this->borrarArchivosRecibido($idRecibido);

			$this->db->where('idRecibido',$idRecibido);
			$this->db->delete('compras_recibido');
			
			$this->configuracion->registrarBitacora('Borrar producto recibido','Compras - Productos',$producto->nombre.', Compra: '.$producto->compra.', Cantidad: '.number_format($producto->cantidad,2)); //Registrar bitácora
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
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//COMPRAS DE MOBILIARIO/EQUIPO
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarInventarios()
	{
		$criterio		=$this->input->post('criterio');
		$idProveedor	=$this->input->post('idProveedor');
		
		$sql ="select a.idInventario
		from  inventarios as a
		inner join rel_inventario_proveedor as b
		on a.idInventario=b.idInventario
		inner join proveedores as c
		on c.idProveedor=b.idProveedor
		and a.idLicencia='$this->idLicencia' ";

		$sql.=$idProveedor>0?" and b.idProveedor='$idProveedor'":'';

	    return $this->db->query($sql)->num_rows();
	}

	public function obtenerInventarios($numero,$limite)
	{
		$criterio		=$this->input->post('criterio');
		$idProveedor	=$this->input->post('idProveedor');
		
		$sql ="select a.*, b.costo, c.empresa,
		b.idProveedor
		from  inventarios as a
		inner join rel_inventario_proveedor as b
		on a.idInventario=b.idInventario
		inner join proveedores as c
		on c.idProveedor=b.idProveedor 
		where a.nombre like '%$criterio%'
		and a.activo='1'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idProveedor>0?" and b.idProveedor='$idProveedor'":'';

		$sql .= " order by a.nombre asc
		limit $limite,$numero ";
		
		#echo $sql;
		
		return $this->db->query($sql)->result();
	}
	
	public function contarComprasInventarios($fecha,$idCompras,$idProveedor)
	{
		$sql ="select a.idCompras, a.idProveedor,
		a.total, a.fechaCompra, b.empresa
		from compras as a
		inner join proveedores as b 
		on a.idProveedor=b.idProveedor
		where a.inventario='1'
		and a.idLicencia='$this->idLicencia' ";
					
		$sql.=$fecha!='fecha'?" and date(a.fechaCompra)='$fecha'":'';
		$sql.=$idCompras>0?" and a.idCompras='$idCompras'":'';
		$sql.=$idProveedor>0?" and a.idProveedor='$idProveedor'":'';
		$sql.=$this->idRol!=1?" and a.idUsuario='$this->_user_id' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerComprasInventarios($numero,$limite,$fecha,$idCompras,$idProveedor)
	{
		$sql ="select a.idCompras, a.idProveedor, a.nombre,
		a.total, a.fechaCompra, b.empresa, a.cancelada, a.fechaEntrega,
		(select c.idSeguimiento from proveedores_seguimiento as c where c.idCompra=a.idCompras order by c.fecha desc limit 1) as idSeguimiento
		from compras as a
		inner join proveedores as b 
		on a.idProveedor=b.idProveedor
		where a.inventario='1'
		and a.idLicencia='$this->idLicencia' ";
					
		$sql.=$fecha!='fecha'?" and date(a.fechaEntrega)='$fecha'":'';
		$sql.=$idCompras>0?" and a.idCompras='$idCompras'":'';
		$sql.=$idProveedor>0?" and a.idProveedor='$idProveedor'":'';
		$sql.=$this->idRol!=1?" and a.idUsuario='$this->_user_id' ":'';
		
		$sql.= " order by a.idCompras desc ";
		$sql.= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarCompraInventario()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$cantidad					= $this->input->post('cantidad');
		$productos					= $this->input->post('productos');
		$preciosTotales				= $this->input->post('preciosTotales');
		$precioProducto				= $this->input->post('precioProducto');
		$indice						= count($cantidad);
		$idProveedor				= $this->input->post('idProveedor');
		$totalCompra				= $this->input->post('kitTotal');
		$diasCredito				= $this->input->post('diasCredito');
		$fechas						= $this->input->post('fechas');
		$descuentos					= $this->input->post('descuentos');
		$descuentosPorcentajes		= $this->input->post('descuentosPorcentajes');

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
			'inventario'			=> 1,
			'diasCredito'			=> $diasCredito,
			'folio'					=> $this->obtenerFolioCompras(),
			'terminos'				=> $this->input->post('terminos'),
			'idUsuario'				=> $this->_user_id,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('compras',$data);
		$idCompra=$this->db->insert_id();
		
		//REGISTAR LA PÓLIZA
		$idCuentaCatalogo	= $this->proveedores->obtenerProveedorCuentaCatalogo($idProveedor);
		
		if($idCuentaCatalogo>0)
		{
			$this->contabilidad->registrarPolizaCompraVenta($data['fechaCompra'],$data['nombre'],$idCuentaCatalogo,$data['total'],'compra',$idCompra); 
		}
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		
		$this->configuracion->registrarBitacora('Registrar compra de mobiliario/equipo','Compras - Mobiliario/equipo',$this->input->post('nombreKit').', '.$this->proveedores->obtenerProveedorNombre($idProveedor)); //Registrar bitácora
		
		$this->registrarPagoProgramado($this->input->post('total'),$diasCredito,$idProveedor,$this->input->post('nombreKit'),$idCompra); //Registrar el pago programado
		
		for($i=0;$i<$indice;$i++)
		{
			$data=array
			(
				'idCompra' 				=> $idCompra,
				'idMaterial' 			=> $productos[$i],
				'cantidad' 				=> $cantidad[$i],
				'total' 				=> $preciosTotales[$i],
				'precio' 				=> $precioProducto[$i],
				'fechaEntrega'			=> $fechas[$i],
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
	
	public function obtenerInventariosComprados($idCompras)
	{
		$sql="	select a.cantidad, b.nombre, a.idCompra, a.idMaterial,
		a.total, a.precio, a.recibido, a.idDetalle, a.fechaEntrega,
		a.totalRecibido, a.descuento, a.descuentoPorcentaje
		from compra_detalles as a
		inner join inventarios as b
		on a.idMaterial=b.idInventario
		where a.idCompra='$idCompras' ";
		
		return $this->db->query($sql)->result();
 	}
	
	public function obtenerPDFInventarios($idCompras)
	{
		$sql="	select a.cantidad, b.nombre, a.idCompra, 
		a.total, a.precio, a.recibido, a.idDetalle,
		a.descuento, a.descuentoPorcentaje, b.codigoInterno, b.unidad
		from compra_detalles as a
		inner join inventarios as b
		on a.idMaterial=b.idInventario
		where a.idCompra='$idCompras' ";
				
		return $this->db->query($sql)->result();
	}
	
	public function obtenerInventarioCompra($idDetalle)
	{
		$sql="select a.cantidad, a.precio, 
		a.idCompra, b.nombre, a.idCompra, b.idInventario
		from compra_detalles as a
		inner join inventarios as b
		on a.idMaterial=b.idInventario
		where a.idDetalle='$idDetalle'";
		
		return $this->db->query($sql)->row();
	}
	
	public function confirmarRecibirInventario()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$idDetalle		= $this->input->post('txtIdDetalle');
		$cantidad		= $this->input->post('txtCantidadRecibir');
		$fecha			= $this->input->post('txtFechaRecibido');
		
		$inventario		= $this->obtenerInventarioCompra($idDetalle);
		$idInventario	= $inventario->idInventario;
		$compra			= $this->obtenerCompraDetalle($inventario!=null?$inventario->idCompra:0);
		
		$totalRecibido	= $this->totalRecibido($idDetalle);
		#$totalUnidades	=$material->cantidad;
		
		if(($cantidad+$totalRecibido)>$inventario->cantidad)
		{
			return array('0','Error al recibir el producto, esta tratando de ingresar mas de lo comprado');
		}
		
		if(($cantidad+$totalRecibido)==$inventario->cantidad)
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
			'fecha' 	=>$fecha,
			'cantidad' 	=>$cantidad,
			'idDetalle' =>$idDetalle,
			'idUsuario' =>$this->_user_id,
			'recibio' 	=>$this->_user_name,
			'remision' 	=>$this->input->post('txtRemision'),
			'factura' 	=>$this->input->post('selectFactura'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('compras_recibido',$data);
		$idRecibido	= $this->db->insert_id();
		#--------------------Actualizar existencias en productos------------------------#
		
		$sql="select cantidad
		 from inventarios
		 where idInventario='$idInventario'";
		 
		 $query=$this->db->query($sql)->row();

		 $data=array
		 (
		 	'cantidad' => $query->cantidad+$cantidad
		 );
		 
		 $this->db->where('idInventario',$idInventario);
		 $this->db->update('inventarios',$data);
		 
		 //SUBIR EL ARCHIVO SI ES QUE EXISTE
		$archivo 		= $_FILES['txtArchivoComprobante']['name'];
		if(strlen($archivo)>0)
		{
			$idComprobante	= $this->subirFicherosCompra($inventario->idCompra,$archivo,$_FILES['txtArchivoComprobante']['size'],$idRecibido);
			
			move_uploaded_file($_FILES['txtArchivoComprobante']['tmp_name'], carpetaCompras.basename($idComprobante."_".$archivo));
		}
		
		$this->configuracion->registrarBitacora('Recibir mobiliario/equipo','Compras - Mobiliario/equipo',$inventario->nombre.', Compra: '.$compra[0].', Cantidad: '.number_format($cantidad,2)); //Registrar bitácora
		
	 	if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0','Error al recibir el producto');
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1','El producto se ha recibido correctamente');
		}
	}
	
	public function obtenerInventariosComprasdos($idCompras)
	{
		$sql="	select a.cantidad, b.nombre, a.idCompra, a.idMaterial,
		a.total, a.precio, a.recibido, a.idDetalle, 
		a.totalRecibido
		from compra_detalles as a
		inner join inventarios as b
		on a.idMaterial=b.idInventario
		where a.idCompra='$idCompras' ";
		
		return $this->db->query($sql)->result();
	}

	public function recibirTodosInventarios()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$idCompra			= $this->input->post('idCompra');
		$factura			= $this->input->post('factura');
		$fecha				= $this->input->post('fecha');
		$remision			= $this->input->post('remision');
		
		$compra				= $this->obtenerCompra($idCompra);
		$productos			= $this->obtenerInventariosComprados($idCompra);
		
		foreach($productos as $row)
		{
			$inventario		= $this->obtenerInventarioCompra($row->idDetalle);
			$idInventario	= $inventario->idInventario;
			
			$totalRecibido	= $this->totalRecibido($row->idDetalle);

			if(($row->cantidad+$totalRecibido)>$inventario->cantidad)
			{
				return array('0','Error al recibir los productos, ya habia una recepción parcial');
			}
			
			//-------------------------------------------------------------------------//
			$data=array
			(
				'recibido' =>1,
			);
			
			$this->db->where('idDetalle',$row->idDetalle);
			$this->db->update('compra_detalles',$data);
			
			//-------------------------------------------------------------------------//
			$data=array
			(
				'fecha' 	=> $fecha,
				'cantidad' 	=> $row->cantidad,
				'idDetalle' => $row->idDetalle,
				'idUsuario' => $this->_user_id,
				'recibio' 	=> $this->_user_name,
				'remision' 	=> $remision,
				'factura' 	=> $factura,
			);
			
			$this->db->insert('compras_recibido',$data);
			#--------------------Actualizar existencias en productos------------------------#
			
			$sql="select cantidad
			from inventarios
			where idInventario='$idInventario'";
			
			 $query	= $this->db->query($sql)->row();
	
			 $data=array
			 (
				'cantidad' => $query->cantidad+$row->cantidad
			 );
			 
			 $this->db->where('idInventario',$idInventario);
			 $this->db->update('inventarios',$data);
		}
		
		$this->configuracion->registrarBitacora('Recibir todo el mobiliario/equipo','Compras - Mobiliario/equipo','Compra: '.$compra->nombre); //Registrar bitácora
		 
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
	
	public function obtenerInventarioRecibidoDetalle($idRecibido)
	{
		$sql=" select a.idInventario, a.nombre, a.cantidad as stock, 
		c.cantidad, c.idRecibido, d.nombre as  compra
		from inventarios as a
		inner join compra_detalles as b
		on a.idInventario=b.idMaterial
		inner join compras_recibido as c
		on c.idDetalle=b.idDetalle
		
		inner join compras as d
		on b.idCompra=d.idCompras
		
		where c.idRecibido='$idRecibido' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarInventarioRecibido()
	{
		$this->db->trans_start();
		
		$idRecibido	= $this->input->post('idRecibido');
		
		$producto	= $this->obtenerInventarioRecibidoDetalle($idRecibido);
		
		if($producto!=null)
		{
			$this->db->where('idInventario',$producto->idInventario);
			$this->db->update('inventarios',array('cantidad'=>$producto->stock-$producto->cantidad));
		
			$this->borrarArchivosRecibido($idRecibido);

			$this->db->where('idRecibido',$idRecibido);
			$this->db->delete('compras_recibido');
			
			$this->configuracion->registrarBitacora('Borrar mobiliario/equipo recibido','Compras - Mobiliario/equipo',$producto->nombre.', Compra: '.$producto->compra.', Cantidad: '.number_format($producto->cantidad,2)); //Registrar bitácora
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
	
	public function obtenerReventaComprados($idCompras)
	{
		$sql="	select a.cantidad, b.nombre, a.idCompra, a.idMaterial,
		a.total, a.precio, a.recibido, a.idDetalle, 
		a.totalRecibido, a.descuento, a.descuentoPorcentaje
		from compra_detalles as a
		inner join productos as b
		on a.idMaterial=b.idProducto
		where a.idCompra='$idCompras' ";
		
		return $this->db->query($sql)->result();
	}
	
	//PARA EL PRONOSTICO DE PAGOS
	public function obtenerProveedoresCompras($idProveedor,$fechaInicio,$fechaFin)
	{
		$sql="select  a.empresa,
		a.idProveedor
		from proveedores as a
		inner join compras as b
		on a.idProveedor=b.idProveedor  ";
		
		$sql.=$idProveedor!=0?" and b.idProveedor='$idProveedor'":'';
		
		$sql.=$fechaInicio!='fecha'?" and date(b.fechaCompra) between '$fechaInicio' and '$fechaFin'":'';
		
		$sql.=" group by a.idProveedor
		order by a.empresa asc  ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDiferenciaFecha($fechaPago)
	{
		$sql="select datediff('".date('Y-m-d')."','".$fechaPago."') as diferencia";
		
		return $this->db->query($sql)->row()->diferencia;
	}

	public function obtenerConsecutivoCompras()
	{
		$folio	=$this->obtenerFolioCompras();
		
		switch(strlen($folio))
		{
			case 1: return '000'.$folio; break;
			case 2: return '00'.$folio; break;
			case 3: return '0'.$folio; break;
			default: return $folio; break;
		}
	}
	
	public function obtenerFolioCompras()
	{
		$sql=" select coalesce(max(folio),0) as folio
		from compras
		where idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row()->folio+1;
	}

	public function borrarArchivosRecibido($idRecibido)
	{
		$sql="select * from compras_comprobantes
		where idRecibido='$idRecibido'";
		
		$comprobantes	= $this->db->query($sql)->result();
		
		foreach($comprobantes as $row)
		{
			if(file_exists(carpetaCompras.$row->idComprobante.'_'.$row->nombre) and strlen($row->nombre)>0)
			{
				unlink(carpetaCompras.$row->idComprobante.'_'.$row->nombre);
			}
		}
	}

	public function obtenerInformacionCompras($idMaterial,$idTienda=0)
	{
		$sql=" select a.cantidad, a.fecha, b.precio, c.nombre,
		d.empresa as proveedor
		from compras_recibido as a
		inner join compra_detalles as b
		on a.idDetalle=b.idDetalle
		inner join compras as c
		on c.idCompras=b.idCompra
		inner join proveedores as d
		on d.idProveedor=c.idProveedor
		where b.idMaterial='$idMaterial'
		and c.reventa='1'
		and c.cancelada='0'
		and c.activo='1'
		and c.idLicencia='$this->idLicencia' ";
		
		#$sql.=$idTienda>0?" and c.idCompras=0  ":'';
		
		$sql.=" order by a.fecha desc ";
		
		return $this->db->query($sql)->result();
	}

	
	
	public function obtenerInformacionComprasMaterial($idMaterial,$idProveedor=0)
	{
		$sql=" select a.cantidad, a.fecha, b.precio, c.nombre,
		d.empresa as proveedor
		from compras_recibido as a
		inner join compra_detalles as b
		on a.idDetalle=b.idDetalle
		inner join compras as c
		on c.idCompras=b.idCompra
		inner join proveedores as d
		on d.idProveedor=c.idProveedor
		where b.idMaterial='$idMaterial'
		and c.reventa='0'
		and c.cancelada='0'
		and c.activo='1'
		and c.idProveedor='$idProveedor' ";

		$sql.=" order by a.fecha desc ";
		
		return $this->db->query($sql)->result();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//COMPRAS DE SERVICIOS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function obtenerServiciosComprados($idCompras)
	{
		$sql="	select a.cantidad, b.nombre, a.idCompra, a.idMaterial,
		a.total, a.precio, a.recibido, a.idDetalle,  b.codigoInterno, c. descripcion as unidad,
		a.totalRecibido, a.descuento, a.descuentoPorcentaje, a.fechaEntrega
		from compra_detalles as a
		inner join servicios as b
		on a.idMaterial=b.idServicio
		
		inner join unidades as c
		on b.idUnidad=c.idUnidad
		
		where a.idCompra='$idCompras' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerServicioCompra($idDetalle)
	{
		$sql="select a.cantidad, a.precio, 
		a.idCompra, b.nombre, a.idCompra, a.idMaterial, 
		a.descuento, a.descuentoPorcentaje, a.fechaEntrega
		from compra_detalles as a
		inner join servicios as b
		on a.idMaterial=b.idServicio
		where a.idDetalle='$idDetalle'";
		
		return $this->db->query($sql)->row();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//COMPRAS GLOBALES
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarComprasGlobal($inicio,$fin,$criterio,$idProveedor,$materiales,$productos,$inventarios,$servicios)
	{
		$sql ="select a.idCompras, a.idProveedor,
		a.total, a.fechaCompra, b.empresa
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor)
		where a.idCompras>0 
		and a.idLicencia='$this->idLicencia' ";

		$sql.=" and date(a.fechaEntrega) between '$inicio' and '$fin' ";
		$sql.=$idProveedor>0?" and a.idProveedor='$idProveedor'":'';
		$sql.=strlen($criterio)>0?" and (b.empresa like '%$criterio%' or a.nombre like '%$criterio%')":'';
		$sql.=$this->idRol!=1?" and a.idUsuario='$this->_user_id' ":'';
		
		if($materiales==0 or $productos==0 or $inventarios==0 or $servicios==0)
		{
			$sql.=" and ( a.idCompras>0 ";
			
			$sql.=$productos==0?" and a.reventa='0' ":'';
			$sql.=$inventarios==0?" and a.inventario='0' ":'';
			$sql.=$servicios==0?" and a.servicios='0' ":'';
			$sql.=$materiales==0?"  and (a.servicios!='0' or a.inventario!='0' or a.reventa!='0' ) ":'';
			
			$sql.=" ) ";
		}
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerComprasGlobal($numero,$limite,$inicio,$fin,$criterio,$idProveedor,$materiales,$productos,$inventarios,$servicios)
	{
		$sql =" select a.idCompras, a.idProveedor, a.nombre,
		a.total, a.fechaCompra, b.empresa, a.cancelada, a.fechaEntrega,
		(select c.idSeguimiento from proveedores_seguimiento as c where c.idCompra=a.idCompras order by c.fecha desc limit 1) as idSeguimiento,
		
		a.reventa, a.inventario, a.servicios
		
		from compras as a
		inner join proveedores as b 
		on a.idProveedor=b.idProveedor
		where a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and date(a.fechaEntrega) between '$inicio' and '$fin' ";
		$sql.=$idProveedor>0?" and a.idProveedor='$idProveedor'":'';
		$sql.=strlen($criterio)>0?" and (b.empresa like '%$criterio%' or a.nombre like '%$criterio%')":'';
		$sql.=$this->idRol!=1?" and a.idUsuario='$this->_user_id' ":'';
		
		if($materiales==0 or $productos==0 or $inventarios==0 or $servicios==0)
		{
			$sql.=" and ( a.idCompras>0 ";
			
			$sql.=$productos==0?" and a.reventa='0' ":'';
			$sql.=$inventarios==0?" and a.inventario='0' ":'';
			$sql.=$servicios==0?" and a.servicios='0' ":'';
			$sql.=$materiales==0?"  and (a.servicios!='0' or a.inventario!='0' or a.reventa!='0' ) ":'';
			
			$sql.=" ) ";
		}
		

		$sql.=" order by a.fechaCompra desc ";
		$sql .= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//BORRAR COMPRAS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

	public function borrarMateriaComprada($idCompra,$borrar=0)
	{
		$sql=" select a.idMaterial, b.stock, a.idDetalle
		from compra_detalles as a
		inner join produccion_materiales as b
		on a.idMaterial=b.idMaterial
		inner join compras as c
		on c.idCompras=a.idCompra
		where a.idCompra='$idCompra' ";
		
		$recibidos	= $this->db->query($sql)->result();
		
		foreach($recibidos as $row)	
		{
			$recibido	= $this->totalRecibido($row->idDetalle);
			
			if($recibido>0)
			{
				if($recibido<=$row->stock)
				{
					$data['stock']	= $row->stock-$recibido;
					
					$this->db->where('idMaterial',$row->idMaterial);
					$this->db->update('produccion_materiales',$data);
					
					if($borrar==1)
					{
						$this->db->where('idDetalle',$row->idDetalle);
						#$this->db->update('compras_recibido',$data);
						$this->db->delete('compras_recibido');
					}
					
				}
				else
				{
					$this->resultado="2";
				}
				
				$this->db->where('idDetalle',$row->idDetalle);
				$this->db->delete('produccion_materiales_entradas');
			}
			
		}
	}
	
	public function borrarProductosComprados($idCompra,$borrar=0)
	{
		$sql=" select b.idProducto, d.stock, a.idDetalle,
		e.cantidad, e.idLicencia
		from compra_detalles as a
		inner join productos as b
		on a.idMaterial=b.idProducto		
		inner join compras as c		
		on c.idCompras=a.idCompra
		inner join productos_inventarios as d
		on d.idProducto=b.idProducto	
		inner join compras_recibido as e
		on a.idDetalle=e.idDetalle		
		where a.idCompra='$idCompra'
		and c.reventa=1
		and d.idLicencia='$this->idLicencia' ";

		$registros	= $this->db->query($sql)->result();

		foreach($registros as $row)	
		{
			$recibido	= $this->totalRecibido($row->idDetalle);
			$producto	= $row->idLicencia==0?$this->inventarios->obtenerProductoStock($row->idProducto):$this->inventarios->obtenerProductoStockLicencia($row->idProducto,$row->idLicencia);
			
			if($producto!=null)
			{
				if($row->cantidad>$producto->stock)
				{
					$this->resultado="2";
				}

				if($row->idLicencia==0)
				{
					$this->inventarios->actualizarStockProducto($row->idProducto,$row->cantidad,'restar');
				}
				else
				{
					$this->inventarios->actualizarStockProductoSucursal($row->idProducto,$row->cantidad,'restar',$row->idLicencia);
				}
			}
		}

		if($borrar==1)
		{
			$sql="delete a
			from compras_recibido a
			inner join compra_detalles b on a.idDetalle = b.idDetalle
			where b.idCompra='$idCompra'";

			$this->db->query($sql);
		}
		
		/*foreach($this->db->query($sql)->result() as $row)	
		{
			$recibido	=$this->totalRecibido($row->idDetalle);
			
			if($recibido>0)
			{
				if($recibido<=$row->stock)
				{
					$this->inventarios->actualizarStockProducto($row->idProducto,$recibido,'restar');
					
					if($borrar==1)
					{
						$this->db->where('idDetalle',$row->idDetalle);
						$this->db->delete('compras_recibido');
					}
				}
				else
				{
					$this->resultado="2";
				}
			}
		}*/
	}
	
	public function borrarInventariosComprados($idCompra,$borrar=0)
	{
		$sql=" select b.idInventario, b.cantidad, a.idDetalle
		from compra_detalles as a
		inner join inventarios as b
		on a.idMaterial=b.idInventario
		inner join compras as c
		on c.idCompras=a.idCompra
		where a.idCompra='$idCompra'
		and c.inventario=1 ";
		
		foreach($this->db->query($sql)->result() as $row)	
		{
			$recibido	=$this->totalRecibido($row->idDetalle);
			
			if($recibido>0)
			{
				if($recibido<=$row->cantidad)
				{
					$data['cantidad']	=$row->cantidad-$recibido;
					$this->db->where('idInventario',$row->idInventario);
					$this->db->update('inventarios',$data);
					
					if($borrar==1)
					{
						$this->db->where('idDetalle',$row->idDetalle);
						#$this->db->update('compras_recibido',$data);
						$this->db->delete('compras_recibido');
					}
				}
				else
				{
					$this->resultado="2";
				}
			}
		}
	}
	
	public function borrarServiciosComprados($idCompra)
	{
		$sql=" select a.idDetalle
		from compra_detalles as a
		where a.idCompra='$idCompra' ";
		
		foreach($this->db->query($sql)->result() as $row)	
		{
			$this->db->where('idDetalle',$row->idDetalle);
			$this->db->update('compras_recibido',$data);
		}
	}
	
	public function comprasBorrar($idCompras,$seccion)
	{
		$this->db->trans_start(); #Se Inicia una transaccion para el borrado de varias tablas
		
		$compra	= $this->obtenerCompraDetalle($idCompras);
		
		switch($seccion)
		{
			case "compras":
				$this->borrarMateriaComprada($idCompras,1);
			break;
			
			case "productos":
				$this->borrarProductosComprados($idCompras,1);
			break;
			
			case "inventarios":
				$this->borrarInventariosComprados($idCompras,1);
			break;
			
			case "inventarios":
				$this->borrarServiciosComprados($idCompras);
			break;
		}
		
		$this->db->where('idCompra', $idCompras);
		$this->db->delete('compra_detalles');

		$this->db->where('idCompra', $idCompras);
		$this->db->delete('catalogos_egresos');
		
		$this->db->where('idCompra', $idCompras);
		$this->db->delete('proveedores_seguimiento');
		
		$this->db->where('idCompras', $idCompras);
		$this->db->delete('compras');

		$this->configuracion->registrarBitacora('Borrar compra','Compras',$compra[0].', '.$compra[1]); //Registrar bitácora
		
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
			
			return $this->resultado;
		}
	}
	
	public function cancelarCompra($idCompras,$seccion)
	{
		$this->db->trans_start(); #Se Inicia una transaccion para el borrado de varias tablas
		
		switch($seccion)
		{
			case "compras":
				$this->borrarMateriaComprada($idCompras);
			break;
			
			case "productos":
				$this->borrarProductosComprados($idCompras);
			break;
			
			case "inventarios":
				$this->borrarInventariosComprados($idCompras);
			break;
		}
		
		/*$this->db->where('idCompra', $idCompras);
		$this->db->delete('compra_detalles');*/

		$this->db->where('idCompra', $idCompras);
		$this->db->delete('catalogos_egresos');
		
		$this->db->where('idCompras', $idCompras);
		$this->db->update('compras',array('cancelada'=>'1'));
		
		$compra	= $this->obtenerCompraDetalle($idCompras);
		
		$this->configuracion->registrarBitacora('Cancelar compra','Compras',$compra[0].', '.$compra[1]); //Registrar bitácora
		
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
			
			return $this->resultado;
		}
	}
	
	public function obtenerCompraDetalle($idCompras)
	{
		$sql=" select total, nombre, empresa
		from compras as a 
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where a.idCompras='$idCompras' ";
		
		$compra	= $this->db->query($sql)->row();
		
		return $compra!=null?array($compra->nombre,$compra->empresa,$compra->total):array('Sin detalles','','');
	}
	
	public function borrarProductoMaterial($idMaterial,$idProducto)
	{
		$borra = $this->db->where('idMaterial', $idMaterial);
		$borra = $this->db->where('idProducto', $idProducto);
	
		$borra = $this->db->delete('rel_producto_material');
	
		  return ($this->db->affected_rows() == 1)? TRUE : NULL;
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PAGOS DE COMPRAS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function realizarPago()
	{
		$this->db->trans_start(); 
		
		$idCompra		=$this->input->post('idCompras');
		$compra			= $this->obtenerCompra($idCompra);
		
		$pago			= $this->input->post('montoPagar');
		$iva			= $compra->ivaPorcentaje;
		$iva			= $iva>0?$iva/100:0;
		$subTotal		= $pago/(1+$iva);

		$data = array
		(
			'idCompra'			=> $this->input->post('idCompras'),
			'idCuenta'			=> $this->input->post('cuentasBanco'),
			'transferencia'		=> $this->input->post('numeroTransferencia'),
			'cheque'			=> $this->input->post('numeroCheque'),
			'formaPago'			=> '',
			'fecha'				=> $this->input->post('txtFechaEgreso'),
			'idLicencia'		=> $this->idLicencia,
			'idDepartamento'	=> $this->input->post('selectDepartamento'),
			'idNombre'			=> $this->input->post('selectFormas')==2?$this->input->post('selectNombres'):0,
			'producto'			=> $this->input->post('txtDescripcionProducto'),
			'concepto'			=> $this->input->post('txtDescripcionProducto'),
			'idProducto'		=> $this->input->post('selectProductos'),
			'idGasto'			=> $this->input->post('selectTipoGasto'),
			#'iva'				=> $this->input->post('txtIva'),
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			#'incluyeIva'		=> $this->input->post('chkIva')=='1'?1:0,
			'factura'			=> $this->input->post('txtFactura'),
			'comentarios'		=> $this->input->post('txtComentarios'),
			'idProveedor'		=> $this->input->post('txtIdProveedorCompra'),
			'idForma'			=> $this->input->post('selectFormas'),
			'esRemision'		=> $this->input->post('selectFactura'),
			
			'ivaTotal'			=> $pago-$subTotal,
			'iva'				=> $compra->ivaPorcentaje,
			'incluyeIva'		=> $iva>0?'1':'0',
			'pago'				=> $pago,
			'subTotal'			=> $subTotal,
            'idUsuario'			=> $this->_user_id,
			
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_egresos',$data);
		$idEgreso=$this->db->insert_id();
		
		$this->contabilidad->registrarPolizaEgreso($data['fecha'],$data['producto'],0,$data['pago'],$idEgreso); //REGISTRAR LA PÓLIZA DE INGRESO
		
		$this->configuracion->registrarBitacora('Registrar pago de compras','Compras - Pagos',$this->input->post('txtDescripcionProducto').', Importe: $'.number_format($this->input->post('montoPagar'),2)); //Registrar bitácora
		
		//SUBIR EL ARCHIVO
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		$archivo 		= $_FILES['txtArchivoPagoCompra']['name'];
		
		if(strlen($archivo)>0)
		{
			$idComprobante	= $this->administracion->subirFicherosEgreso($idEgreso,$archivo,$_FILES['txtArchivoPagoCompra']['size']);
			
			move_uploaded_file($_FILES['txtArchivoPagoCompra']['tmp_name'], carpetaEgresos.basename($idComprobante."_".$archivo));
		}
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		
		$this->procesarPagoProgramado($this->input->post('idCompras'),$this->input->post('montoPagar'));
		
		//CAMBIAR LA NOTIFICACION
		$pagado	= $this->obtenerTotalPagado($idCompra);
		
		
		if($compra->total==$pagado)
		{
			$this->db->where('idCompras',$idCompra);
			$this->db->update('compras',array('notificacion'=>0));
		}
		
		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
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
	
	public function obtenerTotalPagado($idCompra)
	{
		$sql="select coalesce(sum(pago),0) as pago 
		from catalogos_egresos 
		where idCompra='$idCompra'";
		
		return $this->db->query($sql)->row()->pago;
	}
	
	public function procesarPagoProgramado($idCompra,$monto)
	{
		$sql="select pago, idEgreso
		from catalogos_egresos
		where idCompra='$idCompra'
		and idForma='4' ";
		 
		$programado	=$this->db->query($sql)->row();
		
		if($programado!=null)
		{
			if($monto==$programado->pago)
			{
				$this->db->where('idEgreso',$programado->idEgreso);
				$this->db->delete('catalogos_egresos');
			}
			
			if($monto<$programado->pago)
			{
				$this->db->where('idEgreso',$programado->idEgreso);
				$this->db->update('catalogos_egresos',array('pago'	=>$programado->pago-$monto));
			}
		}
	}
	
	public function comprasPagos($idCompra)
	{
		$sql=" select a.total, a.nombre,	 
		a.fechaCompra,a.idProveedor, a.idCompras,
		b.empresa, b.rfc, b.telefono,
		(select concat(c.nombre, '|', c.telefono, '|', c.email) from contactos_proveedores as c where c.idProveedor=b.idProveedor limit 1 ) as contacto,
		a.subTotal, a.descuento, a.iva, a.ivaPorcentaje,
		a.reventa, a.inventario, a.servicios
		from compras as a
		inner join proveedores as b 
		on a.idProveedor=b.idProveedor
		where a.idCompras='".$idCompra."' ";
			  
		return $this->db->query($sql)->row();
	}
	
	public function obtenerUltimoPagoCompras($idProveedor)
	{
		$sql=" select a.*, b.idBanco
		from catalogos_egresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		where a.idCompra>0
		and a.idProveedor='$idProveedor'
		order by a.idEgreso desc
		limit 1 ";
		
		return $this->db->query($sql)->row();
	}
	

	public function obtenerTotal($idCompra)
	{
		$sql="select sum(pago) as pago
		from catalogos_egresos
		where idCompra='$idCompra'
		and idForma!='4'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerPagado($idCompra)
	{
		$sql="select coalesce(sum(pago),0) as pago
		from catalogos_egresos
		where idCompra='$idCompra'
		and idForma!='4'";
		
		return $this->db->query($sql)->row()->pago;
	}
	
	public function obtenerPagos($idCompra)
	{
		$sql=" select a.*, b.cuenta,
		c.nombre as formaPago
		from catalogos_egresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		
		inner join catalogos_formas as c
		on a.idForma=c.idForma
		
		where a.idCompra='$idCompra' 
		and a.idForma!='4' ";
		
		$sql.=" union 
		select a.*, '' as cuenta,
		b.nombre as formaPago
		from catalogos_egresos as a
		
		inner join catalogos_formas as b
		on b.idForma=a.idForma
		
		where a.idCompra='$idCompra'
		and a.idCuenta='0'
		and a.idForma!='4' ";
		
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	public function obtenerDetalleCompraRecibido($idCompra) //PARA OBTENER LA REMISION O LA FACTURA
	{
		$sql=" select a.remision, a.factura
		from compras_recibido as a
		inner join compra_detalles as b
		on a.idDetalle=b.idDetalle
		where b.idCompra='$idCompra'
		order by fecha desc limit 1 ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarPago($idEgreso)
	{
		$pago	= $this->administracion->obtenerConceptoEgreso($idEgreso);
		
		$this->db->where('idEgreso',$idEgreso);
		$this->db->delete('catalogos_egresos');
		
		$this->configuracion->registrarBitacora('Borrar pago de compras','Compras - Pagos',$pago[0].', Importe: $'.number_format($pago[1],2)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function cerrarCompra($idCompras)
	{
		$this->db->where('idCompras',$idCompras);
		$this->db->update('compras',array('cerrada'=>'1'));

		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function registrarPagoProgramado($pago,$dias,$idProveedor,$nombre,$idCompra)
	{
		$data=array
		(
			'pago'				=>$pago,
			'fecha'				=>$this->obtenerFechaFinServicio($dias,'day',$this->input->post('fecha')),
			'formaPago'			=>'',
			'idCuenta'			=>0,
			'transferencia'		=>'',
			'cheque'			=>'',
			#'idDepartamento'	=>$this->input->post('idDepartamento'),
			#'idNombre'			=>$this->input->post('idNombre'),
			'producto'			=>$nombre,
			#'idProducto'		=>$this->input->post('idProducto'),
			#'idGasto'			=>$this->input->post('idGasto'),
			'iva'				=>$this->session->userdata('iva'),
			'nombreReceptor'	=>'',
			'incluyeIva'		=>1,
			'cajaChica'			=>0,
			'comentarios'		=>'',
			'idProveedor'		=>$idProveedor,
			'factura'			=>'',
			'idCompra'			=>$idCompra,
			'cantidad'			=>1,
			'notificacion'		=>1,
			'idForma'			=>4,
            'idUsuario'			=> $this->_user_id,
		);

		$this->db->insert('catalogos_egresos',$data);
	}
	
	public function sumarEgresos($mes,$anio)
	{
		$sql="select sum(pago) as pago
		from catalogos_egresos
		where idCompra='0'
		and month(fecha)='$mes'
		and year(fecha)='$anio' ";
		
		$costo=$this->db->query($sql)->row()->pago;
		
		return $costo!=null?$costo:0;
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PARA LOS COMPROBANTES DE COMPRAS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function borrarComprobanteCompra($idComprobante)
	{
		$comprobante	=$this->obtenerComprobanteCompra($idComprobante);
		
		$this->db->where('idComprobante',$idComprobante);
		$this->db->delete('compras_comprobantes');
		
		if($this->db->affected_rows()>=1)
		{
			$this->configuracion->registrarBitacora('Borrar comprobante','Compras - Comprobantes',$comprobante->nombre); //Registrar bitácora
			
			if(file_exists('media/ficheros/comprobantesCompras/'.$comprobante->idComprobante.'_'.$comprobante->nombre))
			{
				unlink('media/ficheros/comprobantesCompras/'.$comprobante->idComprobante.'_'.$comprobante->nombre);
			}
			
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	public function obtenerComprobantesCompras($idCompra,$idRecibido)
	{
		$sql=" select *  from compras_comprobantes
		where idCompra='$idCompra'";
		
		$sql.=$idRecibido>0?" and idRecibido='$idRecibido' ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerComprobanteCompra($idComprobante)
	{
		$sql="select *  from compras_comprobantes
		where idComprobante='$idComprobante'";
		
		return $this->db->query($sql)->row();
	}
	
	public function subirFicherosCompra($idCompra,$comprobante,$tamano,$idRecibido=0)
	{
		$data=array
		(
			'nombre'		=> $comprobante,
			'tamano'		=> $tamano,
			'idCompra'		=> $idCompra,
			'fecha'			=> $this->_fecha_actual,
			'idRecibido'	=> $idRecibido,
		);
		
		$this->db->insert('compras_comprobantes',$data);
		$idComprobante	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar comprobante','Compras - Comprobantes',$comprobante); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?$idComprobante:0;
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//ANDEN DE COMPRAS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function obtenerProductosAndenCompra($criterio,$idCompras)
	{
		$sql="	select a.cantidad, b.nombre, a.idCompra, a.idMaterial,
		a.total, a.precio, a.recibido, a.idDetalle,  a.fechaEntrega,
		a.totalRecibido, a.descuento, a.descuentoPorcentaje, b.codigoInterno,
		concat('Código: ', b.codigoInterno, ', ',b.nombre) as value
		from compra_detalles as a
		inner join produccion_materiales as b
		on a.idMaterial=b.idMaterial
		where a.idCompra='$idCompras'
		and (b.codigoInterno like '%$criterio%' or b.nombre like '%$criterio%' ) ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function recibirMaterialesAnden()
	{
		$this->db->trans_start();
		
		$idCompra		= $this->input->post('txtIdComprita');
		$factura		= $this->input->post('selectFactura');
		$fecha			= $this->input->post('txtFechaRecibido');
		$remision		= $this->input->post('txtRemision');
		$anden			= $this->obtenerFolioAnden();
		$importe		= $this->input->post('txtImporteAnden');
		
		$actualizar		= false;
		
		$compra			= $this->obtenerCompra($idCompra);
		$materiales		= $this->obtenerProductosComprados($idCompra);
		
		foreach($materiales as $row)
		{
			$material		= $this->obtenerProductoCompra($row->idDetalle);
			$idMaterial		= $material->idMaterial;
			
			$cantidad		= $this->input->post('txtCantidadRecibir'.$row->idDetalle);
			
			if($cantidad>0)
			{
				if($cantidad>$row->cantidadOriginal)
				{
					//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
					$data=array
					(
						'recibido' =>1,
					);
					
					$this->db->where('idDetalle',$row->idDetalle);
					$this->db->update('compra_detalles',$data);
				}
				
				//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
				$data=array
				(
					'fecha' 	=> $fecha,
					'cantidad' 	=> $cantidad,
					'idDetalle' => $row->idDetalle,
					'idUsuario' => $this->_user_id,
					'recibio' 	=> $this->_user_name,
					'remision' 	=> $remision,
					'factura' 	=> $factura,
					'importe' 	=> $importe,
					'anden' 	=> $anden,
				);
				
				$data	= procesarArreglo($data);
				$this->db->insert('compras_recibido',$data);
				$idRecibido	= $this->db->insert_id();
				#--------------------Actualizar existencias en productos------------------------#
				
				 $sql="select a.stock
				 from produccion_materiales as a
				 where a.idMaterial='$idMaterial'";
				 
				 $query	= $this->db->query($sql)->row();
		
				 $data=array
				 (
					'stock' => $query->stock+$cantidad
				 );
				 
				 $this->db->where('idMaterial',$idMaterial);
				 $this->db->update('produccion_materiales',$data);
				 
				//REGISTRAR LA ENTRADA DE MATERIA PRIMA POR PROVEEDOR
				$data=array
				(
					'fecha' 		=> $this->_fecha_actual,
					'fechaEntrada' 	=> $fecha,
					'cantidad' 		=> $cantidad,
					'idDetalle' 	=> $row->idDetalle,
					'idUsuario' 	=> $this->_user_id,
					'idProveedor' 	=> $compra->idProveedor,
					'idMaterial' 	=> $idMaterial,
					'comentarios' 	=> $remision,
					'idRecibido' 	=> $idRecibido,
					'idLicencia'	=> $this->idLicencia,
				);
				
				$data	= procesarArreglo($data);
				$this->db->insert('produccion_materiales_entradas',$data); 
				
				if($cantidad>$row->cantidadOriginal)
				{
					$actualizar				= true;
					$subTotal				= $cantidad*$row->precio;
					$descuentoPorcentaje	= $row->descuentoPorcentaje>0?$row->descuentoPorcentaje/100:0;
					$descuento				= $subTotal*$descuentoPorcentaje;
					
					$data=array
					(
						'cantidad' 		=> $cantidad,
						'total' 		=> $subTotal-$descuento,
						'descuento' 	=> $descuento,
					);
					
					$this->db->where('idDetalle',$row->idDetalle);
				 	$this->db->update('compra_detalles',$data);
				}
			}
		}
		
		if($actualizar)
		{
			$subTotal				= $this->obtenerImportesProductos($idCompra);
			
			$descuentoPorcentaje	= $compra->descuentoPorcentaje>0?$compra->descuentoPorcentaje/100:0;
			$descuento				= $subTotal*$descuentoPorcentaje;
			$suma					= $subTotal-$descuento;
			$ivaPorcentaje			= $compra->ivaPorcentaje>0?$compra->ivaPorcentaje/100:0;
			$iva					= $suma*$ivaPorcentaje;
			
			$data=array
			(
				'total' 			=> $suma+$iva,
				'subTotal' 			=> $subTotal,
				'descuento' 		=> $descuento,
				'iva' 				=> $iva,
			);
			
			$this->db->where('idCompras',$idCompra);
			$this->db->update('compras',$data);
		}
		
		#$this->configuracion->registrarBitacora('Recibir toda la materia prima','Compras - Materia prima','Compra: '.$compra->nombre); //Registrar bitácora
		
		/*$this->db->where('idCompras',$idCompra);
		$this->db->update('compras',array('cerrada'=>'1'));*/
		
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
	
	public function obtenerImportesProductos($idCompra)
	{
		$sql="select coalesce(sum(total),0) as total
		from compra_detalles
		where idCompra='$idCompra' ";
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerFolioAnden()
	{
		$sql=" select coalesce(max(anden),0) as folio
		from compras_recibido ";
		
		return $this->db->query($sql)->row()->folio+1;
	}
}
?>
