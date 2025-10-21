<?php
class Produccion_modelo extends CI_Model
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
		
		$this->_table 			= $this->config->item('datatables');
		$this->_fecha_actual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_user_id		 	= $this->session->userdata('id');
		$this->_user_name 		= $this->session->userdata('name');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		
		$this->load->model('materiales_modelo','materiales');
   }

	#Agregar material al producto y actualizar todos los precios
	#------------------------------------------------------------------
	
	public function obtenerDetalleMaterial($idMaterial)
	{
		$sql=" select a.nombre, a.codigoInterno
		from produccion_materiales as a
		where a.idMaterial='$idMaterial' ";
		
		$material	=$this->db->query($sql)->row();
		
		return $material!=null?array($material->nombre,$material->codigoInterno):array('Sin detalles de materia prima','');
	}
	
	public function agregarMaterialProducto() 
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idMaterial		= $this->input->post('idMaterial');
		$idProducto		= $this->input->post('idProducto');
		$cantidad		= $this->input->post('cantidad');
		
		#----------------------------------------------------------------------------------------#
	
		$ban=0;

		$sql="select * from rel_producto_material
		where idProducto='$idProducto'
		and idMaterial='$idMaterial'";
			
		$query		= $this->db->query($sql);
			
		if($query->num_rows()==0)
		{
			$data=array
			(
			   'idProducto'		=> $idProducto,
			   'idMaterial'		=> $idMaterial,
			   'cantidad'		=> $cantidad,
			   'idUnidad'		=> $this->input->post('idUnidad'),
			   'idConversion'	=> $this->input->post('idConversion')
			);
		
			$this->db->insert('rel_producto_material', $data);
			$ban	= 1;
			
			$producto	= $this->obtenerProductoDetalle($idProducto);
			$material	= $this->obtenerDetalleMaterial($idMaterial);
			
			$this->configuracion->registrarBitacora('Agregar materia prima a producto','Explosión de materiales','Producto: '.$producto[0].', Materia prima: '.$material[0].', Cantidad: '.$cantidad); //Registrar bitácora
		}
		
		if($ban==1)
		{
			#$this->precioUnitario($idCategoria); 
			
			#$costo=$this->materiales->costoEstandar();
			
			$this->materiales->actualizarProductoProduccion($idProducto);
			#$this->materiales->actualizarCajasProductos($idProducto);
			
			#foreach($quer as $row)
			#{
			#	$this->materiales->actualizarProductoProduccion($idProducto);
			#}
			
			#----------------------------------------------------------------------#
			
			#$this->actualizarProductoMateriaPrima($idProducto);
			
		}
			
		if($ban==0)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
				
			return array('0','Ya ha agregado el material al producto'); #Ya existe el material en el producto
		}
						
		#----------------------------------------------------------------------------------------#

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
	
	public function actualizarProductoMateriaPrima($idProducto)
	{
		$producto=$this->obtenerProducto($idProducto);
			
		if($producto->materiaPrima==1)
		{
			$data=array
			(
			   'costo'		=>$producto->costo,
			);
			
			$this->db->where('produccion',$idProducto);
			$this->db->update('produccion_materiales', $data);
			
			#$this->actualizacionGlobalCostoAdministrativo();
		}
	}
	
	public function obtenerCantidadDetalleMaterial($idMaterial,$idProducto)
	{
		$sql="select cantidad
		from rel_producto_material
		where idMaterial='$idMaterial'
		and idProducto='$idProducto' ";
		
		$detalle	=$this->db->query($sql)->row();
		
		return $detalle!=null?$detalle->cantidad:0;
	}
	
	public function borrarProductoMaterial($idMaterial,$idProducto)
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$producto	= $this->obtenerProductoDetalle($idProducto);
		$material	= $this->obtenerDetalleMaterial($idMaterial);
		$cantidad	= $this->obtenerCantidadDetalleMaterial($idMaterial,$idProducto);

		#--------------------------------------------------------------------------------------#
		$this->db->where('idMaterial', $idMaterial);
		$this->db->where('idProducto', $idProducto);
		$this->db->delete('rel_producto_material');
		#--------------------------------------------------------------------------------------#
		
		$this->configuracion->registrarBitacora('Borrar materia prima de producto','Explosión de materiales','Producto: '.$producto[0].', Materia prima: '.$material[0].', Cantidad: '.$cantidad); //Registrar bitácora
		
		$this->materiales->actualizarProductoProduccion($idProducto);
		#$this->materiales->actualizarCajasProductos($idProducto);
		
		#ACTUALIZAR PRECIO SI ES QUE ES MATERIA PRIMA
		#$this->actualizarProductoMateriaPrima($idProducto);
		
		
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
	
	public function crearProductoCaja($idProductoProduccion)
	{
		#$materiaPrima=$this->input->post('materiaPrima')==false?0:1;
		$materiaPrima=$this->input->post('selectUnidades')>0?1:0;
		
		$data=array
		(
			'nombre'		=>$this->input->post('txtNombreProducto'),
			'fecha'			=>$this->_fecha_actual,
			'idUsuario'		=>$this->_user_id,
			'idLicencia'	=>$this->idLicencia,
			'codigoBarras'	=>$this->input->post('txtCodigoBarras'),
			'codigoInterno'	=>$this->input->post('txtCodigoInterno'),
			'materiaPrima'	=>$materiaPrima,
			'unidad'		=>$this->input->post('txtUnidadProducto'),
			'idLinea'		=>$this->input->post('selectLineas'),
			'precioA'		=>$this->input->post('utilidadA'),
			'precioB'		=>$this->input->post('utilidadB'),
			'precioC'		=>$this->input->post('utilidadC'),
			'precioD'		=>$this->input->post('utilidadD'),
			'precioE'		=>$this->input->post('utilidadE'),
			
			'upc'				=>$this->input->post('txtUpc'),
		    'sku'				=>$this->input->post('txtSku'),
			
		);
		
		$imagen =$_FILES['userfile']['name'];
		
		if(strlen($imagen)==0)
		{
			$data['imagen']='default.png';
		}
		else
		{
			$data['imagen']=$_FILES['userfile']['name'];
		}
			
		
		$this->db->insert('productos', $data);
		$idProducto=$this->db->insert_id();
		
		#-------------------------------------------------------------------------------#
		
		if(strlen($imagen)>0)
		{
			$directorio  = "img/productos/";
			$archivo = $directorio . basename($idProducto."_".$imagen);
			
			move_uploaded_file($_FILES['userfile']['tmp_name'], $archivo);
		}
		#-------------------------------------------------------------------------------#
		
		$data=array
		(
			'idProducto'			=>$idProducto,
			'idProductoProduccion'	=>$idProductoProduccion,
			'fecha'					=>$this->_fecha_actual,
			'cantidad'				=>1,
		);
		
		
		$this->db->insert('rel_producto_produccion', $data);
		
		#-------------------------------------------------------------------------------------------#
		/*$materiaPrima=$this->input->post('materiaPrima');
		$materiaPrima=0;
		
		if($materiaPrima=="1")
		{
			$data=array
			(
				'nombre'		=>$this->input->post('nombre'),
				'costo'			=>0,
				'stock'			=>0,
				'fechaRegistro'	=>$this->_fecha_actual,
				'idLicencia'	=>$this->idLicencia,
				'codigoInterno'	=>$this->input->post('codigoInterno'),
				'produccion'	=>$idProducto ,
				'idUnidad'		=>$this->input->post('idUnidad'),
			);
			
			$this->db->insert('produccion_materiales', $data);
			$idMaterial=$this->db->insert_id();
			
			$proveedor=$this->obtenerProveedorEmpresa();
			
			$data=array
			(
				'idProveedor'		=>$proveedor->id,
				'idMaterial'		=>$idMaterial,
				'costo'				=>0,
			);
			
			$this->db->insert('rel_material_proveedor', $data);
		}*/
	}
	
	public function registrarProduccion()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$impuestos=explode('|',$this->input->post('selectImpuestos'));
		
		$data=array
		(
			'nombre'			=> $this->input->post('txtNombreProducto'),
			'fecha'				=> $this->_fecha_actual,
			'idUsuario'			=> $this->_user_id,
			'idLicencia'		=> $this->idLicencia,
			'codigoBarras'		=> $this->input->post('txtCodigoBarras'),
			'codigoInterno'		=> $this->input->post('txtCodigoInterno'),
			'materiaPrima'		=> '0',
			'reventa'			=> '0',
			'servicio'			=> '0',
			#'unidad'			=> $this->input->post('txtUnidadProducto'),
			'stock'				=> $this->input->post('txtInventarioInicial'),
			'idLinea'			=> $this->input->post('selectLineas'),
			'idSublinea'		=> $this->input->post('selectSubLineas'),
			'precioA'			=> $this->input->post('txtPrecioA'),
			'precioB'			=> $this->input->post('utilidadB'),
			'precioC'			=> $this->input->post('utilidadC'),
			'precioD'			=> $this->input->post('utilidadD'),
			'precioE'			=> $this->input->post('utilidadE'),
			'upc'				=> $this->input->post('txtUpc'),
		    'sku'				=> $this->input->post('txtSku'),
			'idDepartamento'		=> $this->input->post('selectDepartamentos'),
			'idMarca'			=> $this->input->post('selectMarcas'),
			'descripcion'		=> $this->input->post('txtDescripcion'),
			'idUnidad'			=> $this->input->post('txtIdUnidad'),
			'idClave'			=> $this->input->post('txtIdClave'),
			'precioImpuestos'	=> $this->input->post('txtPrecioImpuestos'),
			'idImpuesto'		=> $impuestos[0],
		);
		
		$data	= procesarArreglo($data);
		
		$imagen =$_FILES['userfile']['name'];
		
		if(strlen($imagen)==0)
		{
			$data['imagen']	= '';
		}
		else
		{
			$data['imagen']	= $_FILES['userfile']['name'];
		}

		$this->db->insert('productos', $data);
		$idProducto	= $this->db->insert_id();
		
		//ASIGNAR EL INVENTARIO
		$this->productos->registrarStockLicencias($idProducto,$this->input->post('txtInventarioInicial'));
		
		
		$this->configuracion->registrarBitacora('Registrar producto','Explosión de materiales',$this->input->post('txtNombreProducto')); //Registrar bitácora
		
		if(strlen($imagen)>0)
		{
			move_uploaded_file($_FILES['userfile']['tmp_name'], carpetaProductos . basename($idProducto."_".$imagen));
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
	
		
	public function obtenerProveedorEmpresa()
	{
		$sql="select * from proveedores
		where proveedorEmpresa='1' ";
		
		return $this->db->query($sql)->row();
	}


	public function actualizacionGlobalCostoAdministrativo()
	{
		$sql="select * from produccion_productos
		where activo='1' 
		and servicio='0' 
		and reventa='0' 
		and materiaPrima='0' ";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$this->materiales->actualizarProductoProduccion($row->idProducto);
			$this->materiales->actualizarCajasProductos($row->idProducto);
		}
	}
	
	#------------------------------------------------------------------------------#
	#-           Obtener los datos del material para poder editarlos			  -#
	#------------------------------------------------------------------------------#
	public function materialEditar($idProducto,$idMaterial)
	{
		$sql="	select a.stock, a.costo, a.nombre, b.cantidad
				from produccion_materiales as a
					inner join rel_producto_material as b
					on (a.idMaterial=b.idMaterial)
				where b.idProducto='".$idProducto."' 
				and b.idMaterial='".$idMaterial."'
				and a.idLicencia='$this->idLicencia'";
				
		$query=$this->db->query($sql);
		
		return ($row=$query->row());
	}
	
	#------------------------------------------------------------------------------#
	#-           Editar el material y actualizar el costo de produccion			  -#
	#------------------------------------------------------------------------------#
	
	public function editarMaterialProduccion()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se actualiza en mas de 2 tablas
		
		$idMaterial	= $this->input->post('idMaterial');
		$idProducto	= $this->input->post('idProducto');
		
		$data=array
		(
			'cantidad'	=> $this->input->post('cantidad')
		);	
		
		$this->db->where('idProducto',$idProducto);
		$this->db->where('idMaterial',$idMaterial);
		$this->db->update('rel_producto_material',$data);
		
		$producto	= $this->obtenerProductoDetalle($idProducto);
		$material	= $this->obtenerDetalleMaterial($idMaterial);

		$this->configuracion->registrarBitacora('Editar materia prima de producto','Explosión de materiales','Producto: '.$producto[0].', Materia prima: '.$material[0].', Cantidad: '.$this->input->post('cantidad')); //Registrar bitácora
			
		$this->materiales->actualizarProductoProduccion($idProducto);
		#$this->materiales->actualizarCajasProductos($idProducto);
		
		#ACTUALIZAR GLOBAL	
		#$this->actualizarProductoMateriaPrima($idProducto);
		
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
	
	#------------------------------------------------------------------------------#
	#-           Obtener los datos del producto para poder editarlos			  -#
	#------------------------------------------------------------------------------#
	
	public function obtenerProducto($idProducto)
	{
		$sql="	select a.*,
		(select concat(b.clave,', ', b.nombre) from fac_catalogos_unidades as b where b.idUnidad=a.idUnidad) as unidad,
		(select concat(b.clave,', ', b.nombre) from fac_catalogos_claves_productos as b where b.idClave=a.idClave) as claveProducto
		from productos as a
		inner join productos_inventarios as c
		on c.idProducto=a.idProducto
		
		where a.idProducto='$idProducto'
		and c.idLicencia='$this->idLicencia' ";
				
		return $this->db->query($sql)->row();
	}
	
	public function editacionSecundaria($idProducto) #Se edita una segunda tabla :D Por falta de tiempo se hace de esta forma
	{
		$sql="select idProducto
		from rel_producto_produccion
		where idProductoProduccion='$idProducto'";
		
		$idProducto=$this->db->query($sql)->row()->idProducto;	
		
		$data=array
		(
			'nombre'					=>$this->input->post('txtNombreEditar'),
			'codigoInterno'				=>$this->input->post('txtCodigoInternoEditar'),
			'fechaActualizacion'		=>$this->_fecha_actual,
			'idUsuarioActualizacion'	=>$this->_user_id,
			'unidad'					=>$this->input->post('txtUnidadProducto1'),
			'codigoBarras'				=>$this->input->post('txtCodigoBarrasEditar'),
			'idLinea'					=>$this->input->post('selectLineas'),
			'precioA'					=>$this->input->post('utilidadAEditar'),
			'precioB'					=>$this->input->post('utilidadBEditar'),
			'precioC'					=>$this->input->post('utilidadCEditar'),
			'precioD'					=>$this->input->post('utilidadDEditar'),
			'precioE'					=>$this->input->post('utilidadEEditar'),
			
			'upc'				=>$this->input->post('txtUpc'),
		   'sku'				=>$this->input->post('txtSku'),
		);	

		
		#-------------------------------------------------------------------------------#
		$imagen =$_FILES['userfile1']['name'];
		
		if(strlen($imagen)>0)
		{
			$data['imagen']=$_FILES['userfile1']['name'];
			
			$directorio  = "img/productos/";
			$archivo = $directorio . basename($idProducto."_".$imagen);
			
			move_uploaded_file($_FILES['userfile1']['tmp_name'], $archivo);
		}

		$this->db->where('idProducto',$idProducto);
		$this->db->update('productos',$data);
	}
	
	public function editarMateriaProducto($idProducto)
	{
		$data=array
		(
			'nombre'		=> $this->input->post('txtNombreEditar'),
			'codigoInterno'	=> $this->input->post('txtCodigoInternoEditar'),
			'unidad'		=> $this->input->post('txtUnidadProducto1'),
		);	
		
		$this->db->where('idProducto',$idProducto);
		$this->db->update('produccion_productos',$data);
		
		#-------------------------------------------------------------------------------#
		
		$data=array
		(
			'nombre'			=>$this->input->post('txtNombreEditar'),
			'codigoInterno'		=>$this->input->post('txtCodigoInternoEditar'),
		);	
		
		$this->db->where('produccion',$idProducto);
		$this->db->update('produccion_materiales',$data);
		
		#-------------------------------------------------------------------------------#
		
		$this->editacionSecundaria($idProducto); #Se edita una segunda tabla :D
	}
	
	public function editarProducto()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se actualiza en mas de 2 tablas
		
		$idProducto			= $this->input->post('txtIdProductoEditar');
		$impuestos			= explode('|',$this->input->post('selectImpuestos'));

		$data=array
		(
			'nombre'					=> $this->input->post('txtNombreEditar'),
			'codigoInterno'				=> $this->input->post('txtCodigoInternoEditar'),
			'fechaActualizacion'		=> $this->_fecha_actual,
			'idUsuarioActualizacion'	=> $this->_user_id,
			#'unidad'					=> $this->input->post('txtUnidadProducto1'),
			'codigoBarras'				=> $this->input->post('txtCodigoBarrasEditar'),
			'idLinea'					=> $this->input->post('selectLineas'),
			'idSublinea'				=> $this->input->post('selectSubLineas'),
			'precioA'					=> $this->input->post('txtPrecioA'),
			'precioB'					=> $this->input->post('utilidadBEditar'),
			'precioC'					=> $this->input->post('utilidadCEditar'),
			'precioD'					=> $this->input->post('utilidadDEditar'),
			'precioE'					=> $this->input->post('utilidadEEditar'),
			'upc'						=> $this->input->post('txtUpc'),
		   	'sku'						=> $this->input->post('txtSku'),
			'impuesto'					=> $this->input->post('selectImpuestos'),
			
			'idDepartamento'		=> $this->input->post('selectDepartamentos'),
			'idMarca'			=> $this->input->post('selectMarcas'),
			'descripcion'		=> $this->input->post('txtDescripcion'),
			
			#'idUnidad'			=> $this->input->post('selectUnidades'),
			'idUnidad'			=> $this->input->post('txtIdUnidad'),
			'idClave'			=> $this->input->post('txtIdClave'),
			'idImpuesto'		=> $impuestos[0],
			'precioImpuestos'	=> $this->input->post('txtPrecioImpuestos'),
			#'idImpuesto'		=> $this->input->post('selectImpuestos'),
		);	
		
		$data	= procesarArreglo($data);

		#-------------------------------------------------------------------------------#
		$imagen 			= $_FILES['userfile1']['name'];
		
		if(strlen($imagen)>0)
		{
			$data['imagen']	= $_FILES['userfile1']['name'];

			move_uploaded_file($_FILES['userfile1']['tmp_name'], carpetaProductos . basename($idProducto."_".$imagen));
		}

		$this->db->where('idProducto',$idProducto);
		$this->db->update('productos',$data);
		
		//ASIGNAR EL INVENTARIO
		$this->productos->registrarStockLicencias($idProducto,0);
		
		$this->configuracion->registrarBitacora('Editar producto','Explosión de materiales',$this->input->post('txtNombreEditar')); //Registrar bitácora
		
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
	
	
	public function SaveProducto($id)
	{
		$data=array
		(
			'nombre'=>$this->input->post('T1'),
			'piezas'=>$this->input->post('T6'),
		);
		
		$this->db->where('idProducto', $id);
		$this->db->update('produccion_productos', $data);
		
		return ($this->db->affected_rows() == 1)? TRUE : NULL;
	}//SaveProveedor

	public function buscarProductoProduccion($id)
	{
		$SQL="SELECT * FROM produccion_productos 
		WHERE idProducto='".$id."'
		and idLicencia='$this->idLicencia' 
		and servicio='0' 
		and reventa='0' 
		and  activo='1'";
		$query=$this->db->query($SQL);
		
		return ($query->num_rows() > 0)? $query->row_array() : NULL;
	}

	public function borrarProducto($idProducto)
	{
		$borra = $this->db->where('idProducto', $idProducto);
		$borra = $this->db->delete('produccion_productos');
		
		return ($this->db->affected_rows() == 1)? TRUE : NULL;
	}
	
	public function rel_producto($idProducto)
	{
		$borra = $this->db->where('idProducto', $idProducto);
		$borra = $this->db->delete('rel_producto_material');
		
		return ($this->db->affected_rows() == 1)? TRUE : NULL;
	}

	public function borrarEstandar($idMaterial)
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza

		$this->db->where('idMaterial',$idMaterial);
		$this->db->delete('produccion_materiales');
		
		#$this->materiales->actualizarTotalesCajas();
		
		$this->materiales->actualizarUnitarioGlobal(); #Actualizar el costo global de todas las velas
			
		$this->materiales->actualizarGlobalCajas();

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

	public function productos()
	{
		$sql="select * from produccion_productos
		where idLicencia='$this->idLicencia' 
		and servicio='0' 
		and reventa='0' 
		and activo='1'";
		$query = $this->db->query($sql);
		 
		return ($query->num_rows() > 0)? $query->result_array() : NULL;
	}

	public function productoTerminado()
	{
		$sql="select * from productos
		block='0'
		and idLicencia='$this->idLicencia'";
		$query = $this->db->query($sql);
		 
		return ($query->num_rows() > 0)? $query->result() : NULL;
	}
	
	public function productosSimilares()
	{
		$sql="select a.nombre, a.idProducto
		from productos AS a
		inner join rel_producto_material as b
		on(a.idProducto=b.idProducto)
		group by a.nombre";
				
		return $this->db->query($sql)->result();
	}
	
	public function registrarSimilares()
	{
		$idProducto		= $this->input->post('idProducto');
		$productos		= $this->input->post('productos');
		$codigoInterno	= $this->input->post('codigoInterno');
				
		$costo			= 0;
		$numero			= count($productos);
		
		$this->db->trans_start(); #Iniciar una transaccion
		
		#Obtener el costo del producto base
		$producto	= $this->obtenerProducto($idProducto);
		$detalles	= "";
		
		if($producto!=null)
		{
			$sql=" select * from rel_producto_material
			where idProducto='".$idProducto."'";
			
			$query	=$this->db->query($sql)->result();
	
			for($i=0;$i<$numero;$i++)
			{
				if(strlen($productos[$i])>2)
				{
					$random					= rand(100000000,999999999);
					$codigo					= str_replace("5","9",$random);
					
					$detalles.="\n".$productos[$i];
					
					$data = array
					(
						 'nombre'			=> $productos[$i],
						 'codigoInterno'	=> $codigoInterno[$i],
						 'stock'			=> 0,
						 'fecha'			=> $this->_fecha_actual,
						 'idLicencia'		=> $this->idLicencia,
						 'codigoBarras'		=> $codigo,
						 'imagen'			=> 'default.png',
						 'idUsuario'		=> $this->_user_id,
						 'precioA'			=> $producto->precioA,
						 'precioB'			=> $producto->precioB,
						 'precioC'			=> $producto->precioC,
						 'precioD'			=> $producto->precioD,
						 'precioE'			=> $producto->precioE,
						# 'promocion'		=> $producto->promocion,
						# 'unidad'			=> $producto->unidad,
						
						
						'precioImpuestos'	=> $producto->precioImpuestos,
						'idUnidad'			=> $producto->idUnidad,
						'idClave'			=> $producto->idClave,
						'idImpuesto'		=> $producto->idImpuesto,
						
						'idDepartamento'	=> $producto->idImpuesto,
						'idMarca'			=> $producto->idMarca,
						'descripcion'		=> $producto->descripcion,
						
						'idLinea'			=> $producto->idLinea,
						'idSublinea'		=> $producto->idSubLinea,
						'idUsuario'			=> $this->_user_id,
						
					);
					
					$this->db->insert('productos',$data);
					$idProducto	= $this->db->insert_id();
					
					
					//ASIGNAR EL INVENTARIO
					$this->productos->registrarStockLicencias($idProducto,$this->input->post('txtInventarioInicial'));
	
					foreach($query as $row)
					{
						$data = array
						(
							 'idProducto'		=>$idProducto,
							 'idMaterial'		=>$row->idMaterial,
							 'cantidad'			=>$row->cantidad,
							 'idUnidad'			=>$row->idUnidad,
							 'idConversion'		=>$row->idConversion
						);
						
						$this->db->insert('rel_producto_material',$data);
					}
					
					$this->materiales->actualizarProductoProduccion($idProducto);
				}
			}
			
			$this->configuracion->registrarBitacora('Registrar producto similar','Explosión de materiales','Producto base: '.$producto->nombre."\n".$detalles); //Registrar bitácora
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

	public function contarProduccion($idProducto=0)
	{
		#$idProducto=$this->session->userdata('idProductoProduccion');
		
		$sql="select a.nombre 
		from productos as a
		where a.activo='1' 
		and reventa='0' 
		and servicio='0' ";
		
		$sql.=$idProducto>0?" and a.idProducto='$idProducto'":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerProduccion($numero,$limite,$idProducto=0)
	{
		$sql=" select a.nombre, a.costo, a.idProducto, 
		c.stock, a.precioA, a.precioB, a.imagen,
		a.precioC,  a.materiaPrima, a.precioD, a.precioE,
		b.nombre as linea
		from productos as a
		inner join productos_lineas as b
		on a.idLinea=b.idLinea 
		
		inner join productos_inventarios as c
		on c.idProducto=a.idProducto 
		
		where a.activo='1' 
		and reventa='0' 
		and servicio='0'
		and c.idLicencia='$this->idLicencia' ";
		
		$sql.=$idProducto>0?" and a.idProducto='$idProducto'":'';
		
		$sql .= "
		order by a.nombre asc 
		limit $limite,$numero ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function contarProduccionPrecios()
	{
		$idProducto=$this->session->userdata('idProductoProduccion');
		
		$sql="select a.nombre 
		from produccion_productos as a
		where a.activo='1' 
		and a.idLicencia='$this->idLicencia' 
		and reventa='0' 
		and servicio='0' 
		and materiaPrima='0' ";
		
		if($idProducto!='')
		{
			$sql.=" and a.idProducto='$idProducto'";
		}
		
		$query = $this->db->query($sql);
		
		return ($query->num_rows() > 0)? $query->num_rows(): NULL;
	}
	
	public function obtenerProduccionPrecios($numero,$limite)
	{
		$idProducto=$this->session->userdata('idProductoProduccion');
		
		$sql="select a.nombre, a.costo, a.idProducto, 
		a.precioUnitario, a.stock, a.piezas, a.utilidadA,
		a.utilidadB, a.utilidadC, a.precioA, a.precioB, 
		a.precioC, a.costoAdministrativo, a.materiaPrima, 
		a.utilidadD, a.utilidadE, a.precioD, a.precioE
		from produccion_productos as a
		where a.activo='1' 
		and reventa='0' 
		and servicio='0' 
		and materiaPrima='0' ";
		
		if($idProducto!='')
		{
			$sql.=" and a.idProducto='$idProducto'";
		}
		
		$sql .= " and a.idLicencia='$this->idLicencia' 
		order by a.nombre asc 
		limit $limite,$numero ";
		
		$query = $this->db->query($sql);
		
		return ($query->num_rows() > 0)? $query->result_array(): NULL;
	}

	#------- OBTENER UNA FAMILIA -------#
	public function obtenerFamilia($idFamilia)
	{
		$query ="select * from categorias
		where idCategoria='$idFamilia'
		and idLicencia='$this->idLicencia'";

		$query = $this->db->query($query);
		
		return ($query->num_rows() > 0)? $query->row(): NULL;
	}
	
	public function definirGastoGlobal()
	{
		$fecha=$this->session->userdata('gastoAnio').'-'.$this->session->userdata('gastoMes').'-01';
		
		$data=array
		(
			'fechaCosto'=>$fecha
		);
		
		$this->db->where('idLicencia',$this->idLicencia);
		$this->db->update('configuracion',$data);
	}
	
	public function editarGastoGlobal()
	{
		$this->db->trans_start(); #Iniciar una transaccion
		
		$this->definirGastoGlobal();
		$this->actualizacionGlobalCostoAdministrativo();
		
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
	
	
	
	public function borrarCategoria($idCategoria)
	{
		$this->db->trans_start(); #Iniciar una transaccion
		
		$data=array
		(
			'activo'=>0
		);
		
		$this->db->where('idCategoria',$idCategoria);
		$this->db->update('categorias',$data);
		
		$this->db->where('idCategoria',$idCategoria);
		$this->db->update('produccion_productos',$data);
		
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

		$sql="select * from produccion_orden_produccion
		where idProducto='$idProducto'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
	}
	
	public function obtenerProductoDetalle($idProducto)
	{
		$sql="select nombre, codigoInterno
		from productos
		where idProducto='$idProducto' ";
		
		$producto	= $this->db->query($sql)->row();
		
		return $producto!=null?array($producto->nombre,$producto->codigoInterno):array('Sin detalles','');
	}
	
	public function borrarProductoCategoria($idProducto)
	{
		$this->db->where('idProducto',$idProducto);
		$this->db->update('productos',array('activo'=>'0'));
		
		$producto	= $this->obtenerProductoDetalle($idProducto);
		
		$this->configuracion->registrarBitacora('Borrar producto','Explosión de materiales',$producto[0]); //Registrar bitácora

		return $this->db->affected_rows()>=1?"1":"0";
	}

	public function precioUnitario($idCategoria)
	{
		$sql="select a.idCategoria, a.utilidad, a.piezas,
			b.costo, b.idProducto, a.modificado
			from categorias as a
			inner join produccion_productos as b
			on a.idCategoria=b.idCategoria
			where a.idCategoria='$idCategoria' 
			and a.idLicencia='$this->idLicencia'
			limit 1";
		
		$query=$this->db->query($sql);
		
		$query=$query->row();
		
		if($query!=null)
		{
			if($query->modificado=='0')
			{
				$costo=0;
				
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
				$precioUnitario=$precioUnitario/$piezas;
				
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
	
	public function obtenerCategoriasPrecios()
	{
		$sql="select a.*, b.costo, (b.precioUnitario*a.piezas) as costoCaja, 
		a.utilidadB 
			from categorias as a
			inner join produccion_productos as b
			on a.idCategoria=b.idCategoria
			where a.activo='1'
			and a.idLicencia='$this->idLicencia'
			group by a.idCategoria";
			//order by a.nombre asc";
		
		$query=$this->db->query($sql);
		
		return $query->result();
	}
	
	public function contarCategoriasPrecios()
	{
		$sql="select * from categorias
		where activo='1'
		and idLicencia='$this->idLicencia'";
		
		$query=$this->db->query($sql);
		
		return ($query->num_rows());
		
	}
	
	public function categoriasPrecios()
	{
		$sql="select * from categorias
		where activo='1'
		and idLicencia='$this->idLicencia'";
		
		$query=$this->db->query($sql);
		
		return ($query->result());
	}
	
	public function obtenerGastos($mes,$anio)
	{
		$sql="select a.*, b.nombre as cuentaContable, b.clave1 as clave
		from gastos as a
		inner join cuentas_contables as b
		on a.idCuentaContable=b.idCuenta
		where month(a.fecha)='$mes'
		and year(a.fecha)='$anio'
		and a.idLicencia='$this->idLicencia'";
		
		$query=$this->db->query($sql);
		
		return ($query->result());
	}
	
	public function borrarGasto($idGasto,$mes,$anio)
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$this->db->where('idGasto',$idGasto);
		$this->db->delete('gastos');
		
		$fecha=$anio.'-'.$mes.'-01';
		
		$sql="select fechaCosto
		from configuracion
		where idLicencia='$this->idLicencia'";
		
		$fechaCosto=$this->db->query($sql)->row()->fechaCosto;
		
		if($fecha==$fechaCosto)
		{
			$this->actualizacionGlobalCostoAdministrativo();
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
	
	public function agregarGasto()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$formaPago=$this->input->post('formaPago');
		$cheque=$this->input->post('numeroCheque');
		$transferencia=$this->input->post('numeroTransferencia');
		
		switch($formaPago)
		{
			case "1":
			$formaPago="Efectivo";
			$cheque="";
			$transferencia="";
			break;
			
			case "2":
			$formaPago="Cheque";
			$transferencia="";
			break;
			
			case "3":
			$formaPago="Transferencia";
			$cheque="";
			break;
			
			case "4":
			$formaPago="Terminal bancaria";
			$cheque="";
			$transferencia="";
			break;
		}
		
		$data=array
		(
			'nombre'			=>$this->input->post('nombre'),
			'costo' 			=>$this->input->post('costo'),
			'fecha'				=>$this->_fecha_actual,
			'formaPago'			=>$formaPago,
			'transferencia'		=>$transferencia,
			'cheque'			=>$cheque,
			'idCuenta'			=>$this->input->post('cuentasBanco'),
			'idCuentaContable'	=>$this->input->post('idCuentaContable'),
			'idLicencia'		=>$this->idLicencia,
		);
		
		
		$this->db->insert('gastos', $data);
		
		$fecha=date('Y-m').'-01';
		
		$sql="select fechaCosto
		from configuracion
		where idLicencia='$this->idLicencia'";
		
		$fechaCosto=$this->db->query($sql)->row()->fechaCosto;
		
		if($fecha==$fechaCosto)
		{
			$this->actualizacionGlobalCostoAdministrativo();
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
	
	public function obtenerGastoUnitario()
	{
		$mes	=$this->session->userdata('gastoMes');
		$anio	=$this->session->userdata('gastoAnio');
		
		$sql="select sum(costo) as costo
		from gastos
		where idLicencia='$this->idLicencia'
		and month(fecha)='$mes'
		and year(fecha)='$anio'";
		
		$costo		=$this->db->query($sql)->row()->costo;
		$costo		=$costo!=null?$costo:0;
		$costo		+=$this->sumarEgresos($mes,$anio); //SUMAR EL COSTO DE LOS EGRESOS
		$piezas		= $this->obtenerTotalUnidades();
		
		$gastoAdmin	=$costo;
		
		if($piezas>0)
		{
			$gastoAdmin=$costo/$piezas;
		}

		return $gastoAdmin;
	}

	public function contarProduccionTerminado()
	{
		$idProducto=$this->session->userdata('idProductoTerminadoBusqueda');
		
		$sql="select a.nombre 
		from produccion_productos as a
		where a.activo='1' 
		and a.idLicencia='$this->idLicencia' 
		and a.servicio='0'
		and a.reventa='0' 
		and a.materiaPrima='0' ";
		
		if($idProducto!='')
		{
			$sql.=" and a.idProducto='$idProducto'";
		}
		
		$query = $this->db->query($sql);
		
		return ($query->num_rows() > 0)? $query->num_rows(): NULL;
	}
	
	public function obtenerProduccionTerminado($numero,$limite)
	{
		$idProducto=$this->session->userdata('idProductoTerminadoBusqueda');
		
		$sql="select a.nombre, a.costo, a.idProducto, 
		a.precioUnitario, a.stock, a.piezas, a.utilidadA,
		a.utilidadB, a.utilidadC, a.precioA, a.precioB, 
		a.precioC, a.costoAdministrativo,
		a.utilidadD, a.utilidadE, a.precioD, a.precioE
		from produccion_productos as a
		where a.activo='1' 
		and a.materiaPrima='0' 
		and a.servicio='0'
		and a.reventa='0'";
		
		if($idProducto!='')
		{
			$sql.=" and a.idProducto='$idProducto'";
		}
		
		$sql .= " and a.idLicencia='$this->idLicencia' 
		order by a.nombre asc 
		limit $limite,$numero ";
		
		#echo $sql;
		
		$query = $this->db->query($sql);
		
		return ($query->num_rows() > 0)? $query->result(): NULL;
	}
}
?>
