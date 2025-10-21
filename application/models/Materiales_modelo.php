<?php
class Materiales_modelo extends CI_Model
{
    protected $_fecha_actual;
    protected $_table;
    protected $_user_id;
    protected $_user_name;
	protected $idLicencia;

    function __construct()
	{
		parent::__construct();
		$this->config->load('datatables',TRUE);
		$this->_table = $this->config->item('datatables');
		$this->_fecha_actual = mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_user_id = $this->session->userdata('id');
		$this->idLicencia = $this->session->userdata('idLicencia');
		$this->_user_name = $this->session->userdata('name');
    }
	
	public function obtenerMaterial($idMaterial)
	{
		$sql="select * from produccion_materiales
		where idMaterial='$idMaterial'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerMermas($idMaterial,$idProveedor)
	{
		$sql="select * from produccion_materiales_mermas
		where idMaterial='$idMaterial'
		and idProveedor='$idProveedor'
		and idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->result();	
	}
	
	public function obtenerStockMaterial($idMaterial)
	{
		$sql="select stock from produccion_materiales
		where idMaterial='$idMaterial'";
		
		return $this->db->query($sql)->row()->stock;
	}
	
	#--------------------------------------------------------------------------------------------------#
	#----------------------------------REGISTRO DE MERMAS MATERIALES-----------------------------------#
	#--------------------------------------------------------------------------------------------------#
	
	public function registrarMerma()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idMaterial		= $this->input->post('idMaterial');
		$idProveedor	= $this->input->post('idProveedor');
		$cantidad		= $this->input->post('cantidad');
		$comentarios	= $this->input->post('comentarios');
		
		#$material		= $this->obtenerMaterial($idMaterial);
		$material		= $this->obtenerMaterialProveedor($idMaterial,$idProveedor);
		
		if(($material->inventario-$material->salidas)<$cantidad)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return "2";
		}
		
		$data=array
		(
			'idMaterial' 	=> $idMaterial,
			'fechaRegistro'	=> $this->_fecha_actual,
			'fecha' 		=> $this->input->post('fecha'),
			'comentarios' 	=> $comentarios,
			'idUsuario' 	=> $this->_user_id,
			'idLicencia' 	=> $this->idLicencia,
			'idProveedor' 	=> $idProveedor,
			'cantidad' 		=> $cantidad
		);
		
		$this->db->insert('produccion_materiales_mermas',$data);
		
		$data=array
		(
			'stock'	=> $material->stock-$cantidad
		);
		
		$this->db->where('idMaterial',$idMaterial);
		$this->db->update('produccion_materiales',$data);
		
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
	
	public function actualizarUnitarioGlobal()
	{
		$sql="select idCategoria
				from categorias
				where a.idLicencia='$this->idLicencia'";
		
		$query=$this->db->query($sql);
		
		$query=$query->result();
		
		foreach($query as $row)
		{
			$this->precioUnitario($row->idCategoria);
		}
	}
	
	public function obtenerConversion($idConversion)
	{
		$sql="select * from unidades_conversiones
		where idConversion='$idConversion' ";
		
		$conversion=$this->db->query($sql)->row();
		
		return $conversion!=null?$conversion->nombre.' ('.$conversion->referencia.')':'Pendiente';
	}

	public function registrarMateriaPrima()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$unidad				= 'vacio';
		$tipoMaterial		= $this->input->post('tipo');
		$costoNuevo			= 0;
		$cantidad			= $this->input->post("ca");
		$precio				= $this->input->post("T4");
		
		$total				= $cantidad*$precio;
		
		$data=array
		(
			'clave'				=> $this->input->post('T1'),
			'nombre'			=> $this->input->post('T2'),
			'costo'				=> $this->input->post('T4'),
			'stock'				=> '0',
			'idProveedor'		=> $this->input->post('T6'),
			'fechaRegistro'		=> $this->_fecha_actual,
			'stockMinimo'		=> $this->input->post('stockMinimo'),
			'idUnidad'			=> $this->input->post('idUnidad'),	
			'idConversion'		=> $this->input->post('idConversion'),	
			'codigoInterno'		=> $this->input->post('codigoInterno'),
			'tipoMaterial'		=> $tipoMaterial,
			'total'				=> $total,
			'idLicencia'		=> $this->idLicencia,
			
			'idCuentaCatalogo'	=> $this->input->post('idCuentaCatalogo'),
			'idSubCategoria'	=> $this->input->post('idSubCategoria'),
			
			'idImpuesto'		=> $this->input->post('idImpuesto'),
			
		);
		
		
		if(sistemaActivo=='olyess')
		{
			$data['precio']				= $this->input->post('precio');
			$data['precioImpuestos']	= $this->input->post('precioImpuestos');
		}
		
		$data	= procesarArreglo($data);
		$this->db->insert('produccion_materiales', $data);
		$idMaterial	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar materia prima','Materia prima',$this->input->post('T2')); //Registrar bitácora
		
		$data=array
		(
			'idMaterial'	=>$idMaterial,
			'idProveedor'  	=>$this->input->post('T6'),
			'costo'			=>$this->input->post('T4')
		);
		
		$this->db->insert('rel_material_proveedor', $data);
		
		if($tipoMaterial=="0")
		{
			#$this->actualizarUnitarioGlobal(); #Actualizar el costo global de todas las velas
			
			#$this->actualizarGlobalCajas();
		}
		
		//INVENTARIO INICIAL
		$inventarioInicial	= $this->input->post('inventarioInicial');
		if($inventarioInicial>0)
		{
			$data=array
			(
				'idMaterial'		=> $idMaterial,
				'idProveedor'  		=> $this->input->post('T6'),
				'cantidad'			=> $inventarioInicial,
				'fecha'				=> $this->_fecha_actual,
				'fechaEntrada'		=> $this->_fecha_actual,
				'idUsuario'			=> $this->_user_id,
				'Comentarios'		=> 'Inventario inicial',
				'idLicencia'		=> $this->idLicencia,
			);
			
			$this->db->insert('produccion_materiales_entradas', $data);
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
	
	public function actualizarTotalesCajas() #---Para actualizar totales las cajas
	{
		$estandar		=$this->costoEstandar();
		$costoEstandar	=$estandar;
		$iva			=$this->session->userdata('iva')/100;
		
		$sql="  select a.idProductoProduccion, b.id, 
				 sum(a.cantidad*c.costo) as total, 
				 sum(a.cantidad) as cantidad
				from rel_producto_produccion as a
				inner join productos as b
				on(a.idProducto=b.id)
				inner join produccion_productos as c
				on (a.idProductoProduccion=c.idProducto)
				where a.idLicencia='$this->idLicencia'
				and b.idLicencia='$this->idLicencia'
				and b.servicio='0' 
				and b.reventa='0' 
				and b.activo='1'
				group by b.id";
				
		$query=$this->db->query($sql);
		
		$query=$query->result();
	
		foreach($query as $row)
		{
			$actualizarTotal=0;
			$totalCosto		=0;
			$totalCantidad	=0;
			$totalGanancia	=0;
			$ivaActualizar	=0;
			
			#-----------------Porcentaje de ganancia por producto--------------#
			$consulta="select porcentajeGanancia
			from productos where id='".$row->id."'
			and idLicencia='$this->idLicencia'";
			
			$porcentaje		=$this->db->query($consulta);
			$porcentaje		=$porcentaje->row();
			$ganancia		=$porcentaje->porcentajeGanancia/100;
			#-------------------------------------------------------------------#
			
			$totalCosto			=$row->total;
			$totalCantidad		=$row->cantidad;
			$actualizarTotal	=$totalCosto+($totalCantidad*$costoEstandar);
			$totalGanancia		=($actualizarTotal*$ganancia)+$actualizarTotal;
			$ivaActualizar		=$totalGanancia*$iva;
			
			$data= array
			(
				'precio_costo' 	=> $actualizarTotal,
				'precio_a'		=> $totalGanancia,
				'iva'			=>$ivaActualizar
			);
			
			$this->db->where('id',$row->id);
			$this->db->update('productos',$data);
		}
	}
	
	public function confirmarEditar()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se actualiza en mas de 2 tablas
		
		$idMaterial = $this->input->post('idMaterial');
		
		$data = array
		(
			'nombre'			=> $this->input->post('nombre'),
			#'costo'			=>$this->input->post('costo'),
			'codigoInterno'		=> $this->input->post('codigoInterno'),
			'stockMinimo'		=> $this->input->post('stockMinimo'),
			'idUnidad'			=> $this->input->post('idUnidad'),
			'idConversion'		=> $this->input->post('idConversion'),
			'idCuentaCatalogo'	=> $this->input->post('idCuentaCatalogo'),
			'idSubCategoria'	=> $this->input->post('idSubCategoria'),
			
			'idImpuesto'		=> $this->input->post('idImpuesto'),
		);
		
		if(sistemaActivo=='olyess')
		{
			$data['precio']				= $this->input->post('precio');
			$data['precioImpuestos']	= $this->input->post('precioImpuestos');
		}
		
		$data	= procesarArreglo($data);
		$this->db->where('idMaterial',$idMaterial);
		$this->db->update('produccion_materiales',$data);
		
		$this->configuracion->registrarBitacora('Editar materia prima','Materia prima',$this->input->post('nombre')); //Registrar bitácora
		
		#--------------------------------------------------------------------------------------------# Codigo Extra para cambios
		
		$data = array
		(
			'costo'			=>$this->input->post('costo'),
			'idProveedor'	=>$this->input->post('idProveedor'),
		);
		
		$this->db->where('idMaterial',$idMaterial);
		$this->db->where('idProveedor',$this->input->post('idProveedorPasado'));
		$this->db->update('rel_material_proveedor',$data);
		
		
		$this->actualizarProductosMateriales($idMaterial);
		
		
		#$this->actualizarProductosCategoria($idMaterial); #Precios unitarios
		
		#$query=$this->obtenerDetalleProduccion($idMaterial);
		
		#--------------------------------------------------------------------------------------------#				
		
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
	
	public function actualizarProductosMateriales($idMaterial) #SI SE CAMBIO EL PRECIO DE UN MATERIAL ENTONCES TAMBIEN CAMBIA EL COSTO GLOBAL
	{
		$sql="select idProducto 
		from rel_producto_material
		where idMaterial='$idMaterial'";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$this->actualizarProductoProduccion($row->idProducto); #Precios unitarios
			#$this->actualizarCajasProductos($row->idProducto);
		}
	}
	
	public function actualizarProductosCategoria($idMaterial)
	{
		$sql="select a.idProducto, b.idCategoria
		from rel_producto_material as a
		inner join produccion_productos AS b
		on (a.idProducto=b.idProducto)
		where a.idMaterial='$idMaterial'
		and b.idLicencia='$this->idLicencia'
		group by b.idCategoria";
				
		$query=$this->db->query($sql);
		
		$query=$query->result();
		
		foreach($query as $row)
		{
			$this->precioUnitario($row->idCategoria);
		}
	}
	
	public function obtenerDetalleProduccion($idMaterial) #Obtiene la relacion de productos de produccion
	{
		$sql="  select a.costo, a.nombre, b.cantidad, a.idProducto
		from produccion_productos as a
		inner join rel_producto_material as b
		on (a.idProducto=b.idProducto)
		where b.idMaterial='".$idMaterial."'
		and a.idLicencia='$this->idLicencia'";
				
		$query=$this->db->query($sql);
		
		$query=$query->result();
		
		foreach($query as $row)
		{
			$this->actualizarProductoProduccion($row->idProducto);
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
		$costoTotal	= 0;
		
		$sql=" select a.*, b.costo 
		from rel_producto_material as a
		inner join produccion_materiales as b
		on a.idMaterial=b.idMaterial
		where idProducto='$idProducto'";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			#$costoTotal+=$actualizar->costoTotal;
			if($row->idConversion>0)
			{
				$sql="select * from unidades_conversiones
				where idConversion='$row->idConversion'";
				
				$conversiones		=$this->db->query($sql)->row();
				$precioUnitario		=$row->costo/$conversiones->valor;
				$costoTotal			+=$precioUnitario*$row->cantidad;
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
		
		#$this->actualizarCantidadesProducto($idProducto);
		
		#$this->actualizarCajasProductos($idProducto); #Actualizar el costo con una funcion
	}
	
	public function actualizarCantidadesProducto($idProducto) #ACTUALIZAR EL COSTO ADMINISTRATIVO
	{
		$costoAdministrativo	=0;//$this->costoEstandar();
		$producto				=$this->obtenerDatosProducto($idProducto);
		
		$costo					=$costoAdministrativo*$producto->piezas; #Es el costo administrativo del producto
		$costoTotal				=$costo+$producto->costo; #Es el costo del producto + el costo administrativo
		
		$precioA				=$costoTotal+($costoTotal*($producto->utilidadA/100));
		$precioB				=$costoTotal+($costoTotal*($producto->utilidadB/100));
		$precioC				=$costoTotal+($costoTotal*($producto->utilidadC/100));
		$precioD				=$costoTotal+($costoTotal*($producto->utilidadD/100));
		$precioE				=$costoTotal+($costoTotal*($producto->utilidadE/100));
		
		$data = array
		(
			'costoAdministrativo' 	=> $costo,
			'precioA' 				=> $precioA,
			'precioB' 				=> $precioB,
			'precioC' 				=> $precioC,
			'precioD' 				=> $precioD,
			'precioE' 				=> $precioE,
		);
		
		$this->db->where('idProducto',$idProducto);
		$this->db->update('produccion_productos',$data);
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
		and b.activo='1' ";
		
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
	
	
	public function actualizarGlobalCajas()
	{
		$sql="select id 
			 from productos ";
			 
		$query=$this->db->query($sql);
		
		$query=$query->result();
		
		foreach($query as $row)
		{
			$this->actualizarDetalleCajasProductos($row->id,0);
		}
	}
	
	public function comprobarMaterial($idMaterial,$idProveedor)
	{
		$sql="select idMaterial from rel_material_proveedor 
		where idMaterial='".$idMaterial."'";
			  
		$relaciones	=$this->db->query($sql)->num_rows();
		
		if($relaciones==1)
		{
			$sql="select * from rel_producto_material 
				  where idMaterial='".$idMaterial."'";
				  
			$query=$this->db->query($sql);
			
			if ($query->num_rows() >0)
			{
				return 1;
			}
		}
		
		$sql="select a.idMaterial 
		from compra_detalles  as a
		inner join compras as b
		on a.idCompra=b.idCompras
		where a.idMaterial='".$idMaterial."'
		and b.idProveedor='$idProveedor'";
			  
		$query=$this->db->query($sql);
		
		if ($query->num_rows() >0)
		{
			return 1;
		}
		
		$sql="select * from produccion_materiales_mermas 
			  where idMaterial='".$idMaterial."'";
			  
		$query=$this->db->query($sql);
		
		if ($query->num_rows() >0)
		{
			return 1;
		}

		/*
		
		if ($query->num_rows() >1)
		{
			return 1;
		}*/
		
		return 0;
	}
	
	public function comprobarComprasMaterial($idMaterial,$idProveedor)
	{
		$sql="select a.idMaterial 
		from compra_detalles  as a
		inner join compras as b
		on a.idCompra=b.idCompras
		where a.idMaterial='$idMaterial'
		and b.idProveedor='$idProveedor' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function comprobarNumeroRelaciones($idMaterial)
	{
		$sql="select idMaterial from rel_material_proveedor 
		where idMaterial='".$idMaterial."'";
			  
		return $this->db->query($sql)->num_rows();
	}
	
	public function borrarProductoMaterial($idMaterial,$idProveedor)
	{
		if($this->comprobarMaterial($idMaterial,$idProveedor)>0)
		{
			return "0";
		}
		
		$this->db->trans_start();
		
		$material	= $this->obtenerDetalleMaterial($idMaterial,$idProveedor);
		
		if($this->comprobarNumeroRelaciones($idMaterial)==1)
		{
			$this->db->where('idMaterial', $idMaterial);
			$this->db->delete('produccion_materiales');
		}
		
		$this->db->where('idMaterial', $idMaterial);
		$this->db->where('idProveedor', $idProveedor);
		$this->db->delete('rel_material_proveedor');
		
		$this->configuracion->registrarBitacora('Borrar materia prima','Materia prima',$material[0].', '.$material[1]); //Registrar bitácora
		
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
	
	public function obtenerDetalleMaterial($idMaterial,$idProveedor)
	{
		$sql=" select a.nombre, c.empresa
		from produccion_materiales as a
		inner join rel_material_proveedor as b
		on a.idMaterial=b.idMaterial
		inner join proveedores as c
		on c.idProveedor=b.idProveedor
		where a.idMaterial='$idMaterial'
		and b.idProveedor='$idProveedor' ";
		
		$material	=$this->db->query($sql)->row();
		
		return $material!=null?array($material->nombre,$material->empresa):array('Sin detalles de materia prima','');
	}

	public function materiales()
	{
		$sql="select * from produccion_materiales
		where idLicencia='$this->idLicencia'";
		$query = $this->db->query($sql);
		 
		return ($query->num_rows() > 0)? $query->result_array() : NULL;
	}

	public function buscar_material($id)
	{
		$sql="SELECT a.idMaterial, a.nombre, a.unidad, 
		a.costo, a.codigoInterno, 
		a.stock, a.clave, a.stockMinimo,
		a.idUnidad, a.idConversion 
		FROM produccion_materiales as a
		WHERE idMaterial='".$id."'
		and a.idLicencia='$this->idLicencia'";
		
		$query=$this->db->query($sql);
		#echo $sql;
		
		return ($query->num_rows() > 0)? $query->row() : NULL;
	}
	
	public function obtenerMaterialProveedor($idMaterial,$idProveedor)
	{
		$sql=" select a.idMaterial, a.nombre, a.unidad, 
		a.costo, a.codigoInterno, a.impuesto, a.idImpuesto, a.precio, a.precioImpuestos,
		a.stock, a.clave, a.stockMinimo,
		a.idUnidad, a.idConversion, b.costo as costoMaterial,
		c.empresa, c.idProveedor, a.idSubCategoria,
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_entradas as e where e.idMaterial=a.idMaterial and b.idProveedor=e.idProveedor and e.idLicencia='$this->idLicencia') as inventario,
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_mermas as e where e.idMaterial=a.idMaterial and b.idProveedor=e.idProveedor and e.fechaRegistro is not null and e.idLicencia='$this->idLicencia') as salidas,
		(select concat(b.descripcion,'(',b.numeroCuenta,')') from fac_catalogos_cuentas_detalles as b where b.idCuentaCatalogo=a.idCuentaCatalogo) cuenta, a.idCuentaCatalogo
		from produccion_materiales as a
		inner join rel_material_proveedor as b	
		on a.idMaterial=b.idMaterial
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idMaterial='$idMaterial'
		and b.idProveedor='$idProveedor' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function contarMateriales($criterio='')
	{
		$sql ="select a.idMaterial
		from produccion_materiales as a
		inner join rel_material_proveedor as d
		on a.idMaterial=d.idMaterial
		inner join  proveedores as b 
		on(b.idProveedor=d.idProveedor)
		inner join unidades as c 
		on(a.idUnidad=c.idUnidad)
		where tipoMaterial='1'  ";
		
		$sql.=strlen($criterio)>0?" and (a.nombre like '%$criterio%' or a.codigoInterno like '%$criterio%' )":'';
		
		$sql.=" group by a.idMaterial ";

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerMateriales($numero,$limite,$criterio='',$orden='asc')
	{
		$sql =" select a.idMaterial, a.nombre, d.costo, 
		a.costo as costoPromedio, a.produccion,
		a.stockMinimo, a.stock, b.empresa as nombreProveedor, 
		b.idProveedor, c.descripcion, a.codigoInterno,
		a.idConversion,
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_entradas as e where e.idMaterial=a.idMaterial and b.idProveedor=e.idProveedor and e.idLicencia='$this->idLicencia') as inventario,
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_mermas as e where e.idMaterial=a.idMaterial and b.idProveedor=e.idProveedor and e.idLicencia='$this->idLicencia' and e.fechaRegistro is not null) as salidas,
		
		(select coalesce(sum(e.costo),0) from rel_material_proveedor as e where e.idMaterial=a.idMaterial) / (select count(e.idMaterial) from rel_material_proveedor as e where e.idMaterial=a.idMaterial ) as costoPromedio
		
		from produccion_materiales as a
		inner join rel_material_proveedor as d
		on a.idMaterial=d.idMaterial
		inner join  proveedores as b 
		on(b.idProveedor=d.idProveedor)
		inner join unidades as c 
		on(a.idUnidad=c.idUnidad)
		where tipoMaterial='1' ";
		
		$sql.=strlen($criterio)>0?" and (a.nombre like '%$criterio%' or a.codigoInterno like '%$criterio%' )":'';
		
		$sql.=" group by a.idMaterial ";
		
		$sql .= 'order by a.nombre '.$orden;		
		$sql .= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerEstandar()
	{
		$sql ="select a.idMaterial, a.nombre, a.costo, a.total, 
		a.stockMinimo, a.stock, b.nombre as nombreProveedor, b.id, c.descripcion
		from produccion_materiales as a
		inner join  proveedores as b on(b.id=a.idProveedor)
		inner join unidades as c on(a.idUnidad=c.idUnidad)
		where tipoMaterial='0' 
		and a.idLicencia='$this->idLicencia' ";
		
		$query = $this->db->query($sql);
		
		return ($query->num_rows() > 0)? $query->result() : NULL;
	} 
	
	/*public function costoEstandar()
	{
		$sql="select sum(total) as total
			  from produccion_materiales
			  where tipoMaterial='0'
			  and idLicencia='$this->idLicencia'";
		
		$query=$this->db->query($sql);
		
		return($query->row());
	}*/
	
	public function obtenerTotalUnidades()
	{
		$sql="select sum(piezas) as piezas
		from produccion_productos
		where idLicencia='$this->idLicencia'
		and activo='1' 
		and servicio='0' 
		and reventa='0' 
		and materiaPrima='0' ";
		
		$piezas=$this->db->query($sql)->row()->piezas;

		return $piezas!=null?$piezas:0;
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
	
	public function costoEstandar()
	{
		$sql="select fechaCosto
		from configuracion
		where idLicencia='$this->idLicencia'";
		
		$query	=$this->db->query($sql)->row();

		$mes	=substr($query->fechaCosto,5,2);
		$anio	=substr($query->fechaCosto,0,4);
		
		$sql="select sum(costo) as costo
		from gastos
		where idLicencia='$this->idLicencia'
		and month(fecha)='$mes'
		and year(fecha)='$anio'";
		
		$costo	=$this->db->query($sql)->row()->costo;
		$costo	=$costo!=null?$costo:0;
		$costo	+=$this->sumarEgresos($mes,$anio); //SUMAR EL COSTO DE LOS EGRESOS
		
		$piezas=$this->obtenerTotalUnidades();
		$gastoAdmin=$costo;
		
		if($piezas>0)
		{
			$gastoAdmin=$costo/$piezas;
		}
		
		return $gastoAdmin;
	}
	
	public function precioUnitario($idCategoria)
	{
		$sql="SELECT a.idCategoria, a.utilidad, a.piezas,
			b.costo, b.idProducto, a.modificado
			FROM categorias AS a
			inner JOIN produccion_productos as b
			ON a.idCategoria=b.idCategoria
			where a.idCategoria='$idCategoria' 
			and a.idLicencia='$this->idLicencia'
			and b.servicio='0' 
			and b.reventa='0' 
			and b.activo='1'
			limit 1";
		
		$query=$this->db->query($sql);
		
		$query=$query->row();
		
		if($query!=null)
		{
			if($query->modificado=='0')
			{
				$costo=$this->costoEstandar();
				
				#$costoAdministrativo=$costo->total; #Costo administrativo Lo cambie el 03 Enero a las 12:52 Checarlo
				$costoAdministrativo=$costo; #Costo administrativo
				$costoStandar=$query->costo;		#Costo standart
				$utilidad=$query->utilidad;
				$piezas=$query->piezas;
				
				#print($costoAdministrativo);
				#print($costoStandar);
				
				#return;
				
				$costoAntes=$costoStandar+$costoAdministrativo;
				$utilidad=$costoStandar*($utilidad/100);
				$iva=($this->session->userdata('iva')/100)+1;
				
				//	$iva=$this->session->userdata('iva');
				
				$precioUnitario=($utilidad+$costoAntes);#*$iva;
				
				if($piezas!=0)
				{
					$precioUnitario=$precioUnitario/$piezas;
				}
				else
				{
					$precioUnitario=0;
				}
				
				//print($iva);
				#return;
				
				$data=array
				(
					'precioUnitario'	=>$precioUnitario
				);
				
				$this->db->where('idCategoria',$idCategoria);
				$this->db->update('produccion_productos',$data);
			}
		}
	}
	
	public function checarProveedorMaterial($idProveedor,$idMaterial)
	{
		$sql="select * from rel_material_proveedor
			  where idProveedor='$idProveedor'
			  and idMaterial='$idMaterial'";
		
		$query=$this->db->query($sql);
		
		return $query->num_rows();
	}
	
	public function agregarProveedorMaterial()
	{
		$data = array
		(
			'idProveedor'	=> $this->input->post('idProveedor'),
			'idMaterial'	=> $this->input->post('idMaterial'),
			'costo'			=> $this->input->post('costo')
		);
		
		$this->db->insert('rel_material_proveedor',$data);
		
		$material	= $this->obtenerDetalleMaterial($this->input->post('idMaterial'),$this->input->post('idProveedor'));
		
		$this->configuracion->registrarBitacora('Asociar proveedor con materia prima','Materia prima',$material[0].', '.$material[1]); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerIdUnidad($nombre)
	{
		$sql="select * from unidades
		where descripcion='$nombre'";
		
		return $this->db->query($sql)->row()->idUnidad;
	}
	
	public function exportarProductos()
	{
		$this->db->trans_start(); #EXPORTAR MATERIA PRIMA PARA COLORIPY
		
		$sql="select * from exportar ";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$data=array
			(
				'nombre'			=>$row->nombre,
				'costo'				=>0,
				'fechaRegistro'		=>$this->_fecha_actual,
				'idUnidad'			=>$this->obtenerIdUnidad($row->unidad),
				'idLicencia'		=>$this->idLicencia,
				'stockMinimo'		=>1,
				'codigoInterno'		=>$row->codigoInterno,
				'stock'				=>$row->stock,
			);
			
			$this->db->insert('produccion_materiales',$data);
			$idMaterial=$this->db->insert_id();
			
			$data=array
			(
				'idProveedor'			=>17,
				'idMaterial'			=>$idMaterial,
				'costo'					=>0,
			);
			
			$this->db->insert('rel_material_proveedor',$data);
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
	
	#ACTUALIZAR INVENTARIO DE MATERIA PRIMA
	#==========================================================================================================#
	
	public function obtenerIdSA($nombre)
	{
		$sql="select * from unidades
		where descripcion='$nombre'";
		
		return $this->db->query($sql)->row()->idUnidad;
	}
	
	public function prima()
	{
		$this->db->trans_start(); #EXPORTAR MATERIA PRIMA PARA COLORIPY
		
		$sql="select * from exportar ";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$data=array
			(
				'stock'				=>$row->stock,
			);
			
			$this->db->where('codigoInterno',$row->codigoInterno);
			$this->db->update('produccion_materiales',$data);
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
	
	public function obtenerIdProduccion($codigoInterno)
	{
		$sql="select idProducto 
		from produccion_productos
		where codigoInterno='$codigoInterno'";
		
		$idProducto=$this->db->query($sql)->row()->idProducto;
		
		$sql="select b.idProducto
		from rel_producto_produccion as a
		inner join productos as b
		on a.idProducto=b.idProducto
		where idProductoProduccion='$idProducto'";
		
		return $this->db->query($sql)->row()->idProducto;	
	}
	
	public function produccion()
	{
		$this->db->trans_start(); #EXPORTAR MATERIA PRIMA PARA COLORIPY
		
		$sql="select * from exportar ";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			if($row->stock>0)
			{
				$idProducto=$this->obtenerIdProduccion($row->codigoInterno);
				
				$data=array
				(
					'stock'				=>$row->stock,
				);
				
				$this->db->where('idProducto',$idProducto);
				$this->db->update('produccion_productos',$data);
				
				#=====================================================================================#
				$data=array
				(
					'autorizo'			=>'Coloripy',
					'idProducto'		=>$idProducto,
					'cantidad'			=>$row->stock,
					'fechaRegistro'		=>$this->_fecha_actual,
					'fechaTerminacion'	=>$this->_fecha_actual,
					'idLicencia'		=>$this->idLicencia,
				);
				
				$this->db->insert('produccion_orden_produccion',$data);
				$idOrden=$this->db->insert_id();
				
				#=====================================================================================#
				$data=array
				(
					'idOrden'		=>$idOrden,
				);
				
				$this->db->insert('rel_orden_proceso',$data);
				$idRelacion=$this->db->insert_id();
				
				#=====================================================================================#
				$data=array
				(
					'idOrden'			=>$idOrden,
					'cantidad'			=>$row->stock,
					'superviso'			=>'Coloripy',
					'fechaProduccion'	=>$this->_fecha_actual,
				);
				
				$this->db->insert('produccion_orden_detalle',$data);
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
	}
	
	public function obtenerProveedoresMaterial($idMaterial)
	{
		$sql=" select a.costo, a.idProveedor,
		b.empresa
		from rel_material_proveedor as a
		inner join  proveedores as b
		on a.idProveedor=b.idProveedor
		where a.idMaterial='$idMaterial' ";
		
		return $this->db->query($sql)->result();
	}
}
?>
