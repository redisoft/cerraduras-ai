<?php
class Ordenes_modelo extends CI_Model
{
    protected $_fecha_actual;
    protected $_table;
    protected $_user_id;
    protected $_user_name;
	protected $idLicencia;
	protected $resultado;

    function __construct()
	{
		parent::__construct();
		$this->config->load('datatables',TRUE);
		
		$this->_table 			= $this->config->item('datatables');
		$this->_fecha_actual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_user_id 		= $this->session->userdata('id');
		$this->_user_name 		= $this->session->userdata('nombreUsuarioSesion');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->resultado		= "1";
   }
   
   #-------------------------------------------------------------------------------------------------------------------#
   #--------------------------------------------------PROCESOS DE PRODUCC.---------------------------------------------#
   #-------------------------------------------------------------------------------------------------------------------#
	public function obtenerDetallesProceso($idRelacion)
	{
		$sql="select * from produccion_orden_detalle 
			where idRelacion='".$idRelacion."'";
			
		$query=$this->db->query($sql);
		
		return ($query->num_rows() > 0)? $query->result() : NULL;
	}
	
	public function obtenerTotalProceso($idRelacion)
	{
		$sql="select sum(cantidad) as total
		from produccion_orden_detalle 
		where idRelacion='".$idRelacion."'";
		
		$query = $this->db->query($sql)->row();
		
		#echo $sql;
		return $query->total;
	}
	
	public function obtenerTotalProcesoSalida($idRelacion)
	{
		$sql="select sum(cantidad) as total
		from produccion_orden_detalle_salida 
		where idRelacion='".$idRelacion."'";
		
		$query = $this->db->query($sql)->row();

		return $query->total;
	}
	
	public function obtenerTotalOrden($idOr)
	{
		$sql="select sum(a.cantidad) as estatus
			  from produccion_orden_detalle as a
				inner join produccion_orden_produccion as b
				on (a.idOrden=b.idOrden)
			  where a.idOrden='".$idOr."'
			  and b.idLicencia='$this->idLicencia'";
		
		$query = $this->db->query($sql)->row();

		return $query->estatus;
	}
	
	public function obtenerOrden($idOrden)
	{
		$sql=" select a.*, b.nombre as producto, b.stock,
		(select coalesce(sum(c.cantidad),0) from produccion_orden_detalle as c where c.idOrden=a.idOrden and idRelacion=0) as producidos
		from produccion_orden_produccion as a
		inner join productos as b
		on a.idProducto=b.idProducto
		where idOrden='$idOrden' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function procesosProducido() #Ordenes de producción por procesos, complicado pero funciona, analizar el codigo :)
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idOrden			=$this->input->post('idOrden');
		$idRelacion			=$this->input->post('idRelacion');
		$idRelacionPasada	=$this->input->post('idRelacionPasada'); #La relacion pasada es para saber la el totalBase
		$prioridad			=$this->input->post('prioridad'); #La prioridad se refleja en el numero de procesos
		$cantidad			=$this->input->post('cantidad');
		
		$totalBase=0;
		$totalProceso=0;
		$totalProcesoSalida=0;
		#Primer proceso de producción
		#-------------------------------------------------------------------------------#
		if($prioridad==1)
		{
			$orden			= $this->obtenerOrden($idOrden);
			$totalBase		= $orden->cantidad;
			$totalProceso	= $this->obtenerTotalProceso($idRelacion);

			if($totalProceso==null)
			{
				$totalProceso=0;
			}
		}
		
		#Existe mas de 1 proceso de produccion
		#-------------------------------------------------------------------------------#
		if($prioridad>1)
		{
			$totalBase			=$this->obtenerTotalProceso($idRelacionPasada);
			$totalProceso		=$this->obtenerTotalProcesoSalida($idRelacionPasada);
			
			if($totalBase==null)
			{
				$totalBase=0;
				
				$this->db->trans_rollback(); #El proceso no cuenta aun con unidades 
				$this->db->trans_complete();
				
				return array('0','El proceso anterior no cuenta con unidades para continuar con el proceso seleccionado');
			}
			
			if($totalProceso==null)
			{
				$totalProceso=0;	
			}
		}
		
		#Transaccion fallida debido a superación de cantidades
		#-------------------------------------------------------------------------------#
		if(($totalProceso+$cantidad)>$totalBase)
		{
			#echo $totalProceso.' ';
			
			$this->db->trans_rollback(); #La cantidad del proceso supera la cantidad base
			$this->db->trans_complete();
			
			return array('0','Esta superando la cantidad del proceso');
		}
		#-------------------------------------------------------------------------------#
		$data=array
		(
			'idOrden'			=>$idOrden,
			'idRelacion'		=>$idRelacion,
			'cantidad'			=>$cantidad,
			'fechaProduccion'	=>$this->input->post('fecha').' '.date('H:i:s'),
			'superviso'			=>$this->input->post('superviso')
		);
		
		$this->db->insert('produccion_orden_detalle',$data);
		$idDetalleOrden=$this->db->insert_id();
		#-------------------------------------------------------------------------------#
		
		if($idRelacionPasada!=0)
		{
			$data=array
			(
				'idRelacion'		=> $idRelacionPasada,
				'cantidad'			=> $cantidad,
				'fecha'				=> $this->input->post('fecha').' '.date('H:i:s'), 
				'idDetalleOrden'	=> $idDetalleOrden,
			);
		
			$this->db->insert('produccion_orden_detalle_salida',$data);
		}
		
		$relacion	= $this->obtenerRelacionProcesoOrden($idRelacion);
		
		if($relacion!=null)
		{
			$orden	= $this->obtenerDetalleOrden($relacion->idOrden);
			
			$this->configuracion->registrarBitacora('Registrar cantidad proceso','Ordenes de producción',$orden[0].', Producto: '.$orden[1].', Cantidad: '.$cantidad.', Proceso: '.$this->configuracion->obtenerDetalleProceso($relacion->idProceso)); //Registrar bitácora
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
	
	#-------------------------------------------------------------------------------------------------------------------#
	
	public function obtenerDetallesProducido($idOrden)
	{
		$sql="select * from produccion_orden_detalle 
			where idOrden='".$idOrden."'
			and idRelacion='0'";
			
		$query=$this->db->query($sql);
		
		return ($query->num_rows() > 0)? $query->result() : NULL;
	
	}
	
	public function obtenerTotalProducido($idOrden)
	{
		$sql="select sum(a.cantidad) as total
			  from produccion_orden_detalle as a
			  where a.idOrden='".$idOrden."'
			  and idRelacion='0'";
		
		$total = $this->db->query($sql)->row()->total;

		return $total!=null?$total:0;
	}
	
	public function producidoOrden()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idOrden			=$this->input->post('idOrden');
		$idRelacion			=$this->input->post('idRelacion');
		$idProducto			=$this->input->post('idProducto');
		$cantidad			=$this->input->post('cantidad');
		$orden				=$this->obtenerOrden($idOrden);
		$cantidadProducida	=$this->obtenerTotalProducido($idOrden);
		
		/*if($cantidadProducida==null)
		{
			$cantidadProducida=0;	
		}*/
		
		$totalBase			=$orden->cantidad;
		
		if($idRelacion!=0)
		{
			$totalBase			=$this->obtenerTotalProceso($idRelacion);
			//$totalProceso		=$this->obtenerTotalProcesoSalida($idRelacionPasada);
			
			if($totalBase==null)
			{
				$totalBase=0;
				
				$this->db->trans_rollback(); #El proceso no cuenta aun con unidades 
				$this->db->trans_complete();
			
				return array('0','El proceso anterior no cuenta con unidades para registrar el producto terminado');
			}
		}
		
		#Transaccion fallida debido a superación de cantidades
		#-------------------------------------------------------------------------------#
		if(($cantidadProducida+$cantidad)>$totalBase)
		{
			#echo $totalProceso.' ';
			
			$this->db->trans_rollback(); #La cantidad del proceso supera la cantidad base
			$this->db->trans_complete();
			
			return array('0','No puede producir mas producto del registrado, esta superando la cantidad del proceso');
		}
		#-------------------------------------------------------------------------------#
		
		$data=array
		(
			'idOrden'			=> $idOrden,
			'idRelacion'		=> 0,
			'cantidad'			=> $cantidad,
			'fechaProduccion'	=> $this->input->post('fecha').' '.date('H:i:s'),
			'superviso'			=> $this->input->post('superviso'),
			'fechaCaducidad'	=> $this->input->post('fechaCaducidad'),
		);
		
		$this->db->insert('produccion_orden_detalle',$data);
		$idDetalleOrden=$this->db->insert_id();
		
		#Si existe un proceso anterior entonces se registraran las cantidades de salida
		#-------------------------------------------------------------------------------#
		
		if($idRelacion!=0)
		{
			$data=array
			(
				'idRelacion'		=> $idRelacion,
				'cantidad'			=> $cantidad,
				'fecha'				=> $this->input->post('fecha').' '.date('H:i:s'), 
				'idDetalleOrden'	=> $idDetalleOrden, 
			);
		
			$this->db->insert('produccion_orden_detalle_salida',$data);
		}
		#-------------------------------------------------------------------------------#
		
		/*$producto	= $this->obtenerProducto($idProducto);
		
		if($producto!=null)
		{
			$this->db->where('idProducto',$idProducto);
			$this->db->update('productos',array('stock'	=> $producto->stock+$cantidad));
		}*/
		
		//ACTUALIZAR EL STOCK DEL PRODUCTO
		$producto	= $this->inventario->obtenerProductoStock($idProducto);
		$this->inventario->actualizarStockProducto($idProducto,$cantidad,'sumar');
		
		$orden		= $this->obtenerDetalleOrden($idOrden);
		
		if($orden!=null)
		{
			$this->configuracion->registrarBitacora('Registrar producto terminado','Ordenes de producción',$orden[0].', Producto: '.$orden[1].', Cantidad: '.$cantidad); //Registrar bitácora
		}
		
		/*$materiaPrima	=$this->input->post('materiaPrima');
		
		if($materiaPrima==0)
		{
			$sql="select a.*, b.piezas, b.nombre, b.stock 
			from rel_producto_produccion as a
			inner join produccion_productos as b
			on idProductoProduccion=b.idProducto
			where a.idProducto='".$idProducto."'
			and b.idLicencia='$this->idLicencia'";
	
			
			foreach($this->db->query($sql)->result() as $row)
			{
				$piezasProducidas=$row->cantidad*$cantidad;
				
				$data=array
				(
					'stock'	=>$row->stock+$piezasProducidas
				);
				
				$this->db->where('idProducto',$row->idProductoProduccion);
				$this->db->update('produccion_productos',$data);
			}
		}*/
		
		/*if($materiaPrima==1)
		{
			$this->actualizarStockMateria($idProducto,$cantidad);
		}*/
		
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
	
	public function actualizarStockMateria($idProducto,$cantidad)
	{
		$sql="select stock
		from produccion_materiales
		where produccion='$idProducto'";
		
		$stock=$this->db->query($sql)->row()->stock;
		
		$data=array
		(
			'stock'	=>$stock+$cantidad
		);
		
		$this->db->where('produccion',$idProducto);
		$this->db->update('produccion_materiales',$data);
	}
	
	public function obtenerProducto($idProducto) #Obtener el producto para saber si es materiaPrima
	{
		$sql="select a.*
		from productos as a
		where a.idProducto='".$idProducto."' ";
		
		return $this->db->query($sql)->row();
	}
	#-------------------------------------------------------------------------------------------------------------------#
	
	public function registrarSalidaOrden($idOrden,$idMaterial,$cantidad)
	{
		$data=array
		(
			'idMaterial' 	=> $idMaterial,
			'fechaRegistro'	=> $this->_fecha_actual,
			'fecha' 		=> $this->_fecha_actual,
			'comentarios' 	=> 'Salida por orden de producción',
			'idUsuario' 	=> $this->_user_id,
			'idLicencia' 	=> 1,
			'idProveedor' 	=> $this->input->post('idProveedor'),
			'cantidad' 		=> $cantidad
		);
		
		$this->db->insert('produccion_materiales_mermas',$data);
	}
	
	public function obtenerMaterialesProducto($idProducto)
	{
		$sql=" select a.idMaterial, a.nombre, 
		a.unidad, a.stock, a.costo, 
		b.cantidad, b.idUnidad, b.idConversion,
		(select c.valor from unidades_conversiones as c where c.idConversion=b.idConversion) as valor,
		(select c.nombre from unidades_conversiones as c where c.idConversion=b.idConversion) as conversion,
		(select c.descripcion from unidades as c where c.idUnidad=b.idUnidad) as unidad,
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_entradas as e where e.idMaterial=a.idMaterial and d.idProveedor=e.idProveedor) as inventario,
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_mermas as e where e.idMaterial=a.idMaterial and d.idProveedor=e.idProveedor and e.fechaRegistro is not null) as salidas
		from produccion_materiales as a
		inner join rel_producto_material as b
		on a.idMaterial=b.idMaterial
		
		inner join rel_material_proveedor as d
		on a.idMaterial=d.idMaterial
		
		where b.idProducto='".$idProducto."' ";
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function procesarMaterialesProduccion($idProducto,$idOrden)
	{
		$materiales	= $this->obtenerMaterialesProducto($idProducto);
		
		foreach($materiales as $row)
		{
			$cantidad	= $row->cantidad;
			$inventario	= $row->inventario-$row->salidas;
			
			if($row->idConversion>0 and $row->valor>0)
			{
				$cantidad	= (1/$row->valor)*$row->cantidad;
			}
			
			if($cantidad>$inventario)
			{
				$this->resultado	= "2";
								
				return "2";
			}
			else
			{
				$data = array
				(
					'stock'=>$row->stock-$cantidad
				);
				
				$this->db->where('idMaterial',$row->idMaterial);
				$this->db->update('produccion_materiales',$data);
				
				$this->registrarSalidaOrden($idOrden,$row->idMaterial,$cantidad);
			}
		}
	}
	
	//19-NOVIEMBRE-2015 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	/*public function registrarOrden()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$cantidad			= $this->input->post('cantidad');
		$idProducto			= $this->input->post('idProducto'); #Es el id del producto
		$idProductoCotizado	= $this->input->post('idProductoCotizado'); #Es el id de la caja de productos
		$idCotizacion		= $this->input->post('idCotizacion');
		

		$data=array
		(
		   'idProducto'		=> $idProducto, #Es el id de la caja de productos
		   'cantidad'		=> $this->input->post('cantidad'),
		   'folio'			=> $this->obtenerFolio(),
		   'orden'			=> $this->obtenerOrdenFolio(),
		   'fechaRegistro'	=> $this->_fecha_actual,
		   'autorizo'		=> $this->_user_name,
		   'idLicencia'		=> $this->idLicencia,
		   'idCotizacion'	=> $idCotizacion
		);

		#-----------------------------------------------------------------------------------#

		$this->db->insert('produccion_orden_produccion', $data);
		$idOrden	= $this->db->insert_id();
		
		#-------------------------------------SALIDA DE MATERIALES--------------------------------------#
		#$this->procesarMaterialesProduccion($idProducto,$idOrden);
		
		#-------------------------------------PROCESOS--------------------------------------#
		$procesos=$this->input->post('procesos');
		
		for($i=0;$i<count($procesos);$i++)
		{
			$data=array
			(
				'idOrden'	=>$idOrden,
				'idProceso'	=>$procesos[$i],
			);
			
			$this->db->insert('rel_orden_proceso', $data);
		}
		#-----------------------------------------------------------------------------------#
		
		$data=array
		(
			'produccion'=>'1'
		);
		
		$this->db->where('idProducto',$this->input->post('idProductoCotizado'));
		$this->db->update('cotiza_productos',$data);
		
		
		
		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); 
			$this->db->trans_complete();
			
			return "1";
		}
	}*/
	
	public function registrarOrden()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$cantidad			= $this->input->post('txtCantidadProduccion');
		$idProducto			= $this->input->post('txtIdProducto'); #Es el id del producto
		$idProductoCotizado	= $this->input->post('idProductoCotizado'); #Es el id de la caja de productos
		$idCotizacion		= $this->input->post('idCotizacion');
		$orden				= $this->obtenerOrdenFolio();
		

		$data=array
		(
		   'idProducto'		=> $idProducto, #Es el id de la caja de productos
		   'cantidad'		=> $this->input->post('txtCantidadProduccion'),
		   'folio'			=> $this->obtenerFolio(),
		   'orden'			=> $orden,
		   'fechaRegistro'	=> $this->_fecha_actual,
		   'autorizo'		=> $this->_user_name,
		   'idLicencia'		=> $this->idLicencia,
		   'idCotizacion'	=> $idCotizacion
		);

		#-----------------------------------------------------------------------------------#

		$this->db->insert('produccion_orden_produccion', $data);
		$idOrden	= $this->db->insert_id();
		
		$producto=$this->inventario->obtenerDetallesProducto($idProducto);
		$this->configuracion->registrarBitacora('Registrar orden','Ordenes de producción',$orden.', Cantidad: '.$this->input->post('txtCantidadProduccion').', Producto: '.$producto->descripcion); //Registrar bitácora
		
		#-------------------------------------SALIDA DE MATERIALES--------------------------------------#
		#$this->procesarMaterialesProduccion($idProducto,$idOrden);
		
		#-------------------------------------PROCESOS--------------------------------------#
		$procesos	= $this->input->post('txtIndiceProcesos');
		
		for($i=0;$i<=$procesos;$i++)
		{
			if($this->input->post('chkProceso'.$i)>0)
			{
				$data=array
				(
					'idOrden'	=> $idOrden,
					'idProceso'	=> $this->input->post('chkProceso'.$i),
				);
				
				$this->db->insert('rel_orden_proceso', $data);
			}
		}
		#-----------------------------------------------------------------------------------#
		
		$data=array
		(
			'produccion'=>'1'
		);
		
		$this->db->where('idProducto',$this->input->post('idProductoCotizado'));
		$this->db->update('cotiza_productos',$data);
		
		//09 ENERO 2017 !!!DE MOMENTO NO HABRA SALIDA DE MATERIALES
		#$this->procesarSalidaMateriales($idOrden,$idProducto); //PROCESAR LA SALIDA DE LOS MATERIALES POR ORDENES DE COMPRA
		
		
		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
			
			return array('0',$this->resultado);
		}
		else
		{
			$this->db->trans_commit(); 
			$this->db->trans_complete();
			
			return "1";
		}
	}
	
	public function procesarSalidaMateriales($idOrden,$idProducto)
	{
		for($i=0;$i<=$this->input->post('txtNumeroMateriales');$i++)
		{
			$cantidadRequerida	= $this->input->post('txtCantidadRequerida'.$i);
			$idMaterial			= $this->input->post('txtIdMaterial'.$i);
			
			if(strlen($cantidadRequerida)>0)
			{
				#$cantidad	= $cantidadRequerida;
				
				for($c=0;$c<=$this->input->post('txtNumeroOrdenes'.$i);$c++)
				{
					if($cantidadRequerida>0)
					{
						$idCompra	= $this->input->post('chkCompra'.$i.'_'.$c);
						
						if(strlen($idCompra)>0)
						{
							$cantidadOrden	= $this->input->post('txtCantidadOrden'.$i.'_'.$c);
							$cantidadSalida = $cantidadRequerida<=$cantidadOrden?$cantidadRequerida:$cantidadOrden;
							
							$entrada	= explode('|',$this->input->post('txtIdEntrada'.$i.'_'.$c));
							
							$this->registrarSalidaOrdenCompra($idMaterial,$cantidadSalida,'Salida por orden de producción',$idOrden,$entrada);
							
							$cantidadRequerida-= $cantidadRequerida<=$cantidadOrden?$cantidadRequerida:$cantidadOrden;
						}
					}
				}
			}
		}
	}
	
	public function registrarSalidaOrdenCompra($idMaterial,$cantidad,$comentarios,$idOrden,$entrada)
	{
		$data=array
		(
			'idMaterial' 	=> $idMaterial,
			'fecha' 		=> $this->_fecha_actual,
			'fechaRegistro' => $this->_fecha_actual,
			'comentarios' 	=> $comentarios,
			'idUsuario' 	=> $this->_user_id,
			'idLicencia' 	=> 1,
			'cantidad' 		=> $cantidad,
			'idOrden' 		=> $idOrden,
			'idEntrada' 	=> $entrada[0],
			'idProveedor' 	=> $entrada[1],
		);
		
		$this->db->insert('produccion_materiales_mermas',$data);
		
		$this->db->where('idMaterial',$idMaterial);
		$this->db->update('produccion_materiales',array('stock'=>$this->materiales->obtenerStockMaterial($idMaterial)-$cantidad) );
	}
	
	//CANCELAR LA ORDEN DE PRODUCCIÓN
	public function obtenerSalidasOrden($idOrden)
	{
		$sql="select idMaterial, cantidad
		from produccion_materiales_mermas 
		where idOrden='$idOrden' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function devolverMaterialesOrden($idOrden)
	{
		$salidas	= $this->obtenerSalidasOrden($idOrden);
		
		foreach($salidas as $row)
		{
			$this->db->where('idMaterial',$row->idMaterial);
			$this->db->update('produccion_materiales',array('stock'=>$this->materiales->obtenerStockMaterial($row->idMaterial)+$row->cantidad) );
		}
		
		$this->db->where('idOrden',$idOrden);
		$this->db->delete('produccion_materiales_mermas');
	}
	
	public function obtenerStockProducto($idOrden)
	{
		$sql="select idMaterial, cantidad
		from produccion_materiales_mermas 
		where idOrden='$idOrden' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRelacionesOrden($idOrden)
	{
		$sql="select * from rel_orden_proceso
		where idOrden='$idOrden'";
		
		return $this->db->query($sql)->result();
	}
	
	public function borrarRelacionesOrden($idOrden)
	{
		$relaciones=$this->obtenerRelacionesOrden($idOrden);
		
		foreach($relaciones as $row)
		{
			$this->db->where('idRelacion',$row->idRelacion);
			$this->db->delete('rel_orden_proceso');
		}
	}
	
	public function cancelarOrden($idOrden)
	{
		$this->db->trans_start();
		
		$this->devolverMaterialesOrden($idOrden); //DEVOLVER MATERIA PRIMA AL INVENTARIO
		
		$orden	= $this->obtenerOrden($idOrden);
		
		if($orden!=null)
		{
			/*$this->db->where('idProducto',$orden->idProducto);
			$this->db->update('productos',array('stock'=>$orden->stock-$orden->producidos));*/
			
			
			$this->inventario->actualizarStockProducto($orden->idProducto,$orden->producidos,'restar');
			
			#$this->borrarRelacionesOrden($idOrden);
			
			$this->db->where('idOrden',$idOrden);
			$this->db->update('produccion_orden_produccion',array('cancelada'=>'1'));
			
			$this->configuracion->registrarBitacora('Cancelar orden','Ordenes de producción',$orden->orden.', Producto: '.$orden->producto); //Registrar bitácora
		}
		
		/*$this->db->where('idOrden',$idOrden);
		$this->db->delete('rel_orden_proceso');*/
		
		if ($this->db->trans_status() === FALSE )
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); 
			$this->db->trans_complete();
			
			return "1";
		}
	}
	
	
	
	public function buscarProductoProduccion($id)
	{
		$sql="select * from productos 
		where idProducto='".$id."'
		and idLicencia='$this->idLicencia'";
		
		$query=$this->db->query($sql);
		
		return ($query->num_rows() > 0)? $query->row_array() : NULL;
	}

	public function delproveedor($id_prod,$id_prov)
	{
		$borra = $this->db->where('id_prod', $id_prod);
		$borra = $this->db->where('id_prov', $id_prov);
	
		$borra = $this->db->delete('proveedores_deproductos');
	
		return $borra;
	}

	public function borrarProductoMaterial($idMaterial,$idProducto)
	{
		$borra = $this->db->where('idMaterial', $idMaterial);
		$borra = $this->db->where('idProducto', $idProducto);
	
		$borra = $this->db->delete('rel_producto_material');
	
		  return ($this->db->affected_rows() == 1)? TRUE : NULL;
	}
	
	#--------------------------------------------------------------------------------------------------#
	#----------------------------------BORRANDO ORDENES DE PRODUCCION----------------------------------
	#--------------------------------------------------------------------------------------------------#
	public function borrarOrdenes($idOrden)
	{
		$this->db->where('idOrden', $idOrden);
		$this->db->delete('produccion_orden_produccion');
		
		return ($this->db->affected_rows() == 1)? TRUE : NULL;
	}
	
	public function borrarOrdenDetalle($idOrden)
	{
		$this->db->where('idOrden', $idOrden);
		$this->db->delete('produccion_orden_detalle');
		
		return ($this->db->affected_rows() == 1)? TRUE : NULL;
	}
	
	#--------------------------------------------------------------------------------------------------

	public function proveedores()
	{
		$SQL="SELECT * FROM proveedores 
		where block='0'
		and idLicencia='$this->idLicencia'";
		$query=$this->db->query($SQL);
		return ($query->num_rows() > 0)? $query->result_array() : NULL;
	}

	public function productos()
	{
		$sql="select * from productos
		where idLicencia='$this->idLicencia' 
		and servicio='0' 
		and reventa='0' 
		and activo='1'";
		
		$query = $this->db->query($sql);
		 
		return ($query->num_rows() > 0)? $query->result_array() : NULL;
	}
	
	public function numeroProduccion($idOrden=0,$fecha='fecha')
	{
		$sql ="select a.idOrden
		from produccion_orden_produccion as a
		inner join productos as b
		on (a.idProducto=b.idProducto) 
		where a.idLicencia='$this->idLicencia' ";
		
		$sql.=$fecha!="fecha"?" and date(a.fechaRegistro)='$fecha' ":'';
		$sql.=$idOrden!=0?" and a.idOrden='$idOrden' ":'';
		
		return  $this->db->query($sql)->num_rows;
	}
	
	public function obtenerProduccion($numero,$limite,$idOrden=0,$fecha='fecha')
	{
		$sql ="select a.idOrden, a.autorizo, a.cantidad,
		a.fechaRegistro, a.producido, a.idProducto, a.cancelada,
		b.nombre as descripcion, b.materiaPrima, a.folio,
		a.orden, (select coalesce(sum(c.cantidad),0) from produccion_orden_detalle as c where c.idOrden=a.idOrden and c.idRelacion=0) as producidos
		from produccion_orden_produccion as a
		inner join productos as b
		on (a.idProducto=b.idProducto) 
		where a.idLicencia='$this->idLicencia' ";
		
		$sql.=$fecha!="fecha"?" and date(a.fechaRegistro)='$fecha' ":'';
		$sql.=$idOrden!=0?" and a.idOrden='$idOrden' ":'';
		
		$sql .= " order by fechaRegistro desc
		limit $limite,$numero ";
		
		return  $this->db->query($sql)->result();
	}
	
	public function obtenerProcesosProduccion($idOrden)
	{
		$sql="select a.*, b.*
		from rel_orden_proceso as a
		inner join produccion_orden_procesos as b
		on a.idProceso=b.idProceso
		where a.idOrden='$idOrden' 
		order by a.idRelacion asc ";
		
		return  $this->db->query($sql)->result();
	}
	
	public function obtenerCantidadProceso($idRelacion)
	{
		$sql="select coalesce(sum(cantidad),0) as total
		from produccion_orden_detalle 
		where idRelacion='".$idRelacion."'";
		
		return  $this->db->query($sql)->row()->total;
	}
	
	public function obtenerCantidadProcesoSalida($idRelacion)
	{
		$sql="select coalesce(sum(cantidad),0) as total
		from produccion_orden_detalle_salida
		where idRelacion='".$idRelacion."'";
		
		return  $this->db->query($sql)->row()->total;
	}
	
	public function obtenerFolio()
	{
		$sql="select coalesce(max(folio),0) as folio from produccion_orden_produccion where idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function obtenerOrdenFolio()
	{
		$folio	= $this->obtenerFolio();
		
		switch(strlen($folio))
		{
			case 1:
				return '00000'.$folio;
			break;
			
			case 2:
				return '0000'.$folio;
			break;
			
			case 3:
				return '000'.$folio;
			break;
			
			case 4:
				return '00'.$folio;
			break;
			
			case 5:
				return '0'.$folio;
			break;
			
			default:
				return $folio;
			break;
		}
	}
	
	public function obtenerDisponiblesComprasMateriales($idMaterial)
	{
		$sql=" select a.idCompras, a.nombre, a.total,
		( (select d.cantidad from produccion_materiales_entradas as d where d.idRecibido=c.idRecibido limit 1) - 
		( select coalesce(sum(d.cantidad),0) from produccion_materiales_mermas as d 
		inner join produccion_materiales_entradas as e on e.idEntrada=d.idEntrada
		where e.idRecibido=c.idRecibido ) ) as disponible,
		(select concat(d.idEntrada,'|',d.idProveedor) from produccion_materiales_entradas as d where d.idRecibido=c.idRecibido limit 1) as idEntrada
		from compras as a
		inner join compra_detalles as b
		on a.idCompras=b.idCompra
		inner join compras_recibido as c
		on c.idDetalle=b.idDetalle
		where b.idMaterial='$idMaterial'
		and a.reventa=0
		and a.inventario=0
		and (select d.cantidad from produccion_materiales_entradas as d where d.idRecibido=c.idRecibido limit 1) > 
		( select coalesce(sum(d.cantidad),0) from produccion_materiales_mermas as d 
		inner join produccion_materiales_entradas as e on e.idEntrada=d.idEntrada
		where e.idRecibido=c.idRecibido )  ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerComprasMateriales($materiales)
	{
		foreach($materiales as $row)
		{
			$compras	= $this->obtenerDisponiblesComprasMateriales($row->idMaterial);
		}
	}
	
	//<><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>
	//PROCESOS DE PRODUCCIÓN
	//<><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>
	
	public function obtenerDetalleOrden($idOrden)
	{
		$sql=" select a.orden, a.cantidad,
		b.nombre as producto
		from produccion_orden_produccion as a
		inner join productos as b
		on a.idProducto=b.idProducto
		where a.idOrden='$idOrden' ";
		
		$orden	= $this->db->query($sql)->row();
		
		return $orden!=null?array($orden->orden,$orden->producto,$orden->cantidad):array('Sin detalles de orden','',0);
	}
	
	public function agregarProcesoOrden()
	{
		$data=array
		(
			'idOrden'		=> $this->input->post('idOrden'),
			'idProceso'		=> $this->input->post('idProceso'),
		);

	    $this->db->insert('rel_orden_proceso',$data);
		
		$orden=$this->obtenerDetalleOrden($this->input->post('idOrden'));
		
		$this->configuracion->registrarBitacora('Agregar proceso','Ordenes de producción',$orden[0].', Producto: '.$orden[1].', Proceso: '.$this->configuracion->obtenerDetalleProceso($this->input->post('idProceso'))); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function obtenerProcesosOrden($idOrden)
	{
		$sql=" select a.idRelacion, a.idOrden, a.idProceso, a.idDetalle, 
		b.nombre as proceso
		from rel_orden_proceso as a
		inner join produccion_orden_procesos as b
		on a.idProceso=b.idProceso
		where a.idOrden='$idOrden' 
		order by a.idRelacion asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function comprobarProcesoOrden($idRelacion)
	{
		$sql=" select count(idDetalle) as numero
		from produccion_orden_detalle
		where idRelacion='$idRelacion' ";
		
		if($this->db->query($sql)->row()->numero>0) return false;
		
		$sql="select count(idDetalle) as numero
		from produccion_orden_detalle_salida
		where idRelacion='$idRelacion' ";
		
		if($this->db->query($sql)->row()->numero>0) return false;
		
		return true;
	}
	
	public function obtenerRelacionProcesoOrden($idRelacion)
	{
		$sql=" select * from rel_orden_proceso
		where idRelacion='$idRelacion'";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarProcesoOrden($idRelacion)
	{
		if(!$this->comprobarProcesoOrden($idRelacion)) return "0";

		$relacion	= $this->obtenerRelacionProcesoOrden($idRelacion);
		
		if($relacion!=null)
		{
			$this->db->where('idRelacion',$idRelacion);
			$this->db->delete('rel_orden_proceso');
			
			$orden=$this->obtenerDetalleOrden($relacion->idOrden);
			
			$this->configuracion->registrarBitacora('Borrar proceso','Ordenes de producción',$orden[0].', Producto: '.$orden[1].', Proceso: '.$this->configuracion->obtenerDetalleProceso($relacion->idProceso)); //Registrar bitácora
		}
		
		return $this->db->affected_rows()==1?"1":"0"; 
	}
	
	public function obtenerDetalleProductoProducido($idDetalle)
	{
		$sql=" select a.cantidad, b.idOrden, b.idProducto, d.stock, a.fechaCaducidad,
		c.nombre as producto, a.idDetalle, a.superviso, a.fechaProduccion, b.orden
		from produccion_orden_detalle as a
		inner join produccion_orden_produccion as b
		on a.idOrden=b.idOrden
		inner join productos as c
		on b.idProducto=c.idProducto
		
		
		inner join productos_inventarios as d
		on d.idProducto=c.idProducto
		
		where a.idDetalle='$idDetalle'
		and d.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row(); 
	}
	
	public function borrarProductoTerminado($idDetalle)
	{
		$orden				= $this->obtenerDetalleProductoProducido($idDetalle);
		
		if($orden!=null)
		{
			#Transaccion fallida debido a superación de cantidades
			#-------------------------------------------------------------------------------#
			if($orden->cantidad>$orden->stock)
			{
				return "2";
			}
			#-------------------------------------------------------------------------------#
			
			$this->db->trans_start();
			
			$this->db->where('idDetalle',$idDetalle);
			$this->db->delete('produccion_orden_detalle');
			
			$this->db->where('idDetalleOrden',$idDetalle);
			$this->db->delete('produccion_orden_detalle_salida');
	
			/*$data=array
			(
				'stock'	=> $orden->stock-$orden->cantidad
			);
			
			$this->db->where('idProducto',$orden->idProducto);
			$this->db->update('productos',$data);	*/
			
			
			$this->inventario->actualizarStockProducto($orden->idProducto,$orden->cantidad,'restar');
			
		
			$this->configuracion->registrarBitacora('Borrar producto terminado','Ordenes de producción',$orden->orden.', Producto: '.$orden->producto.', Cantidad: '.round($orden->cantidad,decimales)); //Registrar bitácora
	
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
		else
		{
			return "0";
		}
	}
	
	public function obtenerProductoTerminadoDetalle($idDetalle)
	{
		$sql="select * from produccion_orden_detalle
		where idDetalle='$idDetalle' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function editarProductoTerminado()
	{
		$idDetalleProductoTerminado	= $this->input->post('idDetalleProductoTerminado');
		$terminado					= $this->obtenerProductoTerminadoDetalle($idDetalleProductoTerminado);
		$cantidad					= $this->input->post('cantidad');
		$idPersonal					= $this->input->post('idPersonal');
		$superviso					= $this->input->post('superviso');
		$idOrden					= $this->input->post('idOrden');
		#$idDetalle					= $this->input->post('idDetalle');
		$idRelacion					= $this->input->post('idRelacion');
		$idProducto					= $this->input->post('idProducto');
		$fecha						= $this->input->post('fecha');
		
		if($terminado!=null)
		{
			
			
			if($cantidad==$terminado->cantidad)
			{
				$data=array
				(
					'fechaProduccion'	=> $fecha.' '.date('H:i:s'),
					'fechaCaducidad'	=> $this->input->post('fechaCaducidad'),
					'superviso'			=> $superviso,
					#'idPersonal'		=> $idPersonal
				);
				
				$this->db->where('idDetalle',$idDetalleProductoTerminado);
				$this->db->update('produccion_orden_detalle',$data);
				
				return $this->db->affected_rows()>=1?"1":"0"; 
			}
			else
			{
				$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza

				//ACTUALIZAR PRIMERO EL STOCK Y PONER EN 0 LAS CANTIDADES DE RECIBIDO
				//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
				#$producto	= $this->obtenerProducto($idProducto);
				
				$producto	= $this->inventario->obtenerProductoStock($idProducto);
				
				#print_r($producto);
				
				if($producto!=null)
				{
					/*$this->db->where('idProducto',$idProducto);
					$this->db->update('productos',array('stock'	=> $producto->stock-$terminado->cantidad));*/
					
					$this->inventario->actualizarStockProducto($idProducto,$terminado->cantidad,'restar');
					
					$this->db->where('idDetalle',$idDetalleProductoTerminado);
					$this->db->update('produccion_orden_detalle',array('cantidad'	=> 0));
					
					$this->db->where('idDetalleOrden',$idDetalleProductoTerminado);
					$this->db->update('produccion_orden_detalle_salida',array('cantidad'	=> 0));
				}
				
				//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
				
				$orden				= $this->obtenerOrden($idOrden);
				$cantidadProducida	= $this->obtenerTotalProducido($idOrden);
				$totalBase			= $orden->cantidad;
				
				if($idRelacion!=0)
				{
					$totalBase			=$this->obtenerTotalProceso($idRelacion);

					if($totalBase==null)
					{
						$totalBase=0;
						
						$this->db->trans_rollback(); #El proceso no cuenta aun con unidades 
						$this->db->trans_complete();
					
						return "3";
					}
				}
				
				#Transaccion fallida debido a superación de cantidades
				#-------------------------------------------------------------------------------#
				if(($cantidadProducida+$cantidad)>$totalBase)
				{
					#echo $totalProceso.' ';
					
					$this->db->trans_rollback(); #La cantidad del proceso supera la cantidad base
					$this->db->trans_complete();
					
					return "2";
				}
				#-------------------------------------------------------------------------------#
				
				$data=array
				(
					'fechaProduccion'	=> $fecha.' '.date('H:i:s'),
					'fechaCaducidad'	=> $this->input->post('fechaCaducidad'),
					'superviso'			=> $superviso,
					#'idPersonal'		=> $idPersonal,
					'cantidad'			=> $cantidad,
				);
				
				$this->db->where('idDetalle',$idDetalleProductoTerminado);
				$this->db->update('produccion_orden_detalle',$data);

				#Si existe un proceso anterior entonces se registraran las cantidades de salida
				#-------------------------------------------------------------------------------#
				
				if($idRelacion!=0)
				{
					$data=array
					(
						'cantidad'			=>$cantidad,
						'fecha'				=>$fecha.' '.date('H:i:s'), 
					);
					
					$this->db->where('idDetalleOrden',$idDetalleProductoTerminado);
					$this->db->update('produccion_orden_detalle_salida',$data);
				}
				#-------------------------------------------------------------------------------#
				
				$materiaPrima	= $this->input->post('materiaPrima');
				
				/*$producto	= $this->obtenerProducto($idProducto);

				if($producto!=null)
				{
					$this->db->where('idProducto',$idProducto);
					$this->db->update('productos',array('stock'	=> $producto->stock+$cantidad));
				}*/
				
				$this->inventario->actualizarStockProducto($idProducto,$cantidad,'sumar');
				
				$orden=$this->obtenerDetalleOrden($idOrden);
		
				if($orden!=null)
				{
					$this->configuracion->registrarBitacora('Editar producto terminado','Ordenes de producción',$orden[0].', Producto: '.$orden[1].', Cantidad: '.$cantidad); //Registrar bitácora
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
		}
		else
		{
			return "0";
		}
	}
}
?>
