<?php
class Requisiciones_modelo extends CI_Model
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

	#REQUISICIONES
	#====================================================================================================
	public function contarRequisiciones($criterio,$inicio,$fin)
	{
		$sql =" select count(idRequisicion) as numero
		from produccion_materiales_requisiciones
		where idRequisicion>0
		and idLicencia='$this->idLicencia'
		and date(fechaRequisicion) between '$inicio' and '$fin' ";
		
		#$sql.=strlen($criterio)>0?" and nombre like '%$criterio%' ":'';
		
		$sql.=$this->idRol!=1?" and idUsuario='$this->idUsuario' ":'';
		
		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerRequisiciones($numero,$limite,$criterio,$inicio,$fin)
	{
		$sql =" select a.*,
		(select b.autorizada from produccion_materiales_requisiciones_detalles as b where b.idRequisicion=a.idRequisicion and b.autorizada='1' limit 1) as autorizadaCompra,
		
		(select concat(b.nombre,' ',b.apellidoPaterno,' ', b.apellidoMaterno) from usuarios as b where b.idUsuario=a.idUsuario) as usuario
		
		from produccion_materiales_requisiciones as a
		where a.idRequisicion>0
		and idLicencia='$this->idLicencia'
		and date(a.fechaRequisicion) between '$inicio' and '$fin' ";
		
		#$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';
		$sql.=$this->idRol!=1?" and a.idUsuario='$this->idUsuario' ":'';
		
		$sql .= " order by fechaRequisicion desc
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerFolioRequisicion()
	{
		$sql =" select coalesce(max(folio),0) as folio from produccion_materiales_requisiciones where idLicencia='$this->idLicencia'  ";

		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function obtenerRequisicion($idRequisicion)
	{
		$sql =" select * from produccion_materiales_requisiciones
		where idRequisicion='$idRequisicion' ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerMaterialesRequisicion($idRequisicion)
	{
		$sql =" select a.*, b.nombre as material, 
		c.descripcion as unidad
		from produccion_materiales_requisiciones_detalles as a
		inner join produccion_materiales as b
		on a.idMaterial=b.idMaterial
		inner join unidades as c
		on c.idUnidad=b.idUnidad
		where a.idRequisicion='$idRequisicion' ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarMateriaPrima($nombre)
	{
		$data=array
		(
			'clave'				=> '',
			'nombre'			=> $nombre,
			'costo'				=> 0,
			'stock'				=> '0',
			'idProveedor'		=> 214,
			'fechaRegistro'		=> $this->fecha,
			'stockMinimo'		=> 1,
			'idUnidad'			=> 2,	
			'idConversion'		=> 0,	
			'codigoInterno'		=> '',
			'tipoMaterial'		=> 0,
			'total'				=> 0,
			'idLicencia'		=> $this->idLicencia,
			'idCuentaCatalogo'	=> 0,
			'idSubCategoria'	=> 0,
			'idImpuesto'		=> 1,
		);
		
		if(sistemaActivo=='olyess')
		{
			$data['precio']				= $this->input->post('precio');
			$data['precioImpuestos']	= $this->input->post('precioImpuestos');
		}
		
		$data	= procesarArreglo($data);
		$this->db->insert('produccion_materiales', $data);
		$idMaterial	= $this->db->insert_id();

		$data=array
		(
			'idMaterial'	=> $idMaterial,
			'idProveedor'  	=> 214,
			'costo'			=> 0
		);
		
		$this->db->insert('rel_material_proveedor', $data);
		
		return $idMaterial;
	}
	
	public function registrarMaterialesRequisicion($idRequisicion)#Se registra materiales requisición
	{
		$numeroMateriales	= $this->input->post('txtNumeroMateriales');
		
		for($i=0;$i<$numeroMateriales;$i++)
		{
			$idMaterial	= $this->input->post('txtIdMaterial'.$i);
			
			if($idMaterial>0)
			{
				if($idMaterial==100000000)
				{
					$idMaterial	= $this->registrarMateriaPrima( $this->input->post('txtNombreMaterial'.$i));
				}
				
				$data=array
				(
				   'idRequisicion'		=> $idRequisicion,
				   'idMaterial'			=> $idMaterial,
				   'cantidad'			=> $this->input->post('txtCantidadRequisicion'.$i),
				);
				
				$this->db->insert('produccion_materiales_requisiciones_detalles', $data);
			}
		}
	}
	
	public function registrarRequisicion()
	{
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		$data=array
		(
		   'fechaRequisicion'	=> $this->fecha,
		   'fechaArribo'		=> $this->input->post('txtFechaArribo'),
		   'idUsuario'			=> $this->idUsuario,
		   'comentarios'		=> $this->input->post('txtComentariosRequisicion'),
		   'folio'				=> $this->obtenerFolioRequisicion(),
		   'idLicencia'			=> $this->idLicencia,
		);
		
		$data	= procesarArreglo($data);
		
		$this->db->insert('produccion_materiales_requisiciones', $data);
		$idRequisicion	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar requisicion','Materia prima - Requisiciones',''); //Registrar bitácora

		$this->registrarMaterialesRequisicion($idRequisicion);

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
	
	public function editarRequisicion()
	{
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		$idRequisicion	= $this->input->post('txtIdRequisicion');
		$data=array
		(
		  #'fechaRequisicion'	=> $this->fecha,
		   'fechaArribo'		=> $this->input->post('txtFechaArribo'),
		   'comentarios'		=> $this->input->post('txtComentariosRequisicion'),
		);
		
		$data	= procesarArreglo($data);
		
		$this->db->where('idRequisicion', $idRequisicion);
		$this->db->update('produccion_materiales_requisiciones', $data);
		
		$this->db->where('idRequisicion', $idRequisicion);
		$this->db->delete('produccion_materiales_requisiciones_detalles');
		
		$this->configuracion->registrarBitacora('Editar requisicion','Materia prima - Requisiciones',''); //Registrar bitácora

		$this->registrarMaterialesRequisicion($idRequisicion);

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
	
	public function borrarRequisicion($idRequisicion)
	{
		$this->db->trans_start(); 
		
		$this->db->where('idRequisicion', $idRequisicion);
		$this->db->delete('produccion_materiales_requisiciones');
		
		$this->db->where('idRequisicion', $idRequisicion);
		$this->db->delete('produccion_materiales_requisiciones_detalles');

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
	
	
	#REQUISICIONES DE COMPRAS
	#====================================================================================================
	public function contarRequisicionesCompras($criterio,$inicio,$fin)
	{
		$sql =" select a.idRequisicion
		from produccion_materiales_requisiciones as a
		inner join produccion_materiales_requisiciones_detalles as b
		on a.idRequisicion=b.idRequisicion
		inner join produccion_materiales as c
		on c.idMaterial=b.idMaterial
		inner join rel_material_proveedor as d
		on d.idMaterial=c.idMaterial
		inner join proveedores as e
		on d.idProveedor=e.idProveedor
		where a.idRequisicion>0
		and a.idLicencia='$this->idLicencia'
		and date(a.fechaRequisicion) between '$inicio' and '$fin' 
		and (c.nombre like '%$criterio%' or e.empresa like '%$criterio%' or a.folio like '%$criterio%' )
		and b.autorizada='0'
		and a.idEstatus=0
		group by b.idDetalle ";

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerRequisicionesCompras($numero,$limite,$criterio,$inicio,$fin)
	{
		$sql =" select a.folio, a.comentarios, a.fechaRequisicion,
		b.cantidad, b.idDetalle, c.nombre as material, d.costo, c.idMaterial,
		e.empresa as proveedor, f.descripcion as unidad, d.idProveedor,
		(select concat(h.nombre,' ',h.apellidoPaterno,' ',h.apellidoMaterno) from usuarios as h where h.idUsuario=a.idUsuario) as usuario,
		
		(select coalesce(sum(g.cantidad),0) from produccion_materiales_entradas as g where g.idMaterial=c.idMaterial and d.idProveedor=g.idProveedor) as inventario,
		(select coalesce(sum(g.cantidad),0) from produccion_materiales_mermas as g where g.idMaterial=c.idMaterial and d.idProveedor=g.idProveedor and g.fechaRegistro is not null) as salidas
		
		from produccion_materiales_requisiciones as a
		inner join produccion_materiales_requisiciones_detalles as b
		on a.idRequisicion=b.idRequisicion
		inner join produccion_materiales as c
		on c.idMaterial=b.idMaterial
		inner join rel_material_proveedor as d
		on d.idMaterial=c.idMaterial
		inner join proveedores as e
		on d.idProveedor=e.idProveedor
		
		inner join unidades as f
		on f.idUnidad=c.idUnidad
		where a.idRequisicion>0
		and a.idLicencia='$this->idLicencia'
		and date(a.fechaRequisicion) between '$inicio' and '$fin' 
		and (c.nombre like '%$criterio%' or e.empresa like '%$criterio%' or a.folio like '%$criterio%')
		and b.autorizada='0'
		and a.idEstatus=0
		group by b.idDetalle ";

		$sql .= " order by a.fechaRequisicion desc
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerProveedoresComprasRequisiones()
	{
		$numeroRequisiciones	= $this->input->post('txtNumeroRequisiciones');
		$provedores				= array();
		$p=0;
		
		for($i=0;$i<$numeroRequisiciones;$i++)
		{
			if($this->input->post('chkAutorizar'.$i)>0)
			{
				$idProveedor		= $this->input->post('txtIdProveedor'.$i);
				
				if($idProveedor>0)
				{
					$provedores[$p]	= $idProveedor;
					$p++;
				}
			}
		}
		
		return array_unique($provedores);
	}
	
	public function registrarComprasRequisiones()
	{
		$this->db->trans_start(); 
		
		$proveedores			= $this->obtenerProveedoresComprasRequisiones();
		$numeroRequisiciones	= $this->input->post('txtNumeroRequisiciones');
		$ivas					= $this->configuracion->obtenerIvas();
		
		for($p=0;$p<count($proveedores);$p++)
		{
			$idProveedor	= $proveedores[$p];
			$diasCredito	= $this->proveedores->obtenerDiasCredito($idProveedor);

			//REGISTRAR LA COMPRA
			$data=array
			(
				'fechaCompra' 			=> $this->input->post('fechaCompra'),
				'fechaEntrega' 			=> $this->input->post('fechaCompra'),
				'total'					=> 0,
				'subTotal'				=> 0,
				'descuento'				=> 0,
				'descuentoPorcentaje'	=> 0,
				'iva'					=> 0,
				'ivaPorcentaje'			=> $ivas->iva,
				'nombre'				=> 'OC-'.$this->compras->obtenerConsecutivoCompras(),
				'idProveedor'			=> $idProveedor,
				'idLicencia'			=> $this->idLicencia,
				'diasCredito'			=> $diasCredito,
				'folio'					=> $this->compras->obtenerFolioCompras(),
				'terminos'				=> '',
				'idUsuario'				=> $this->idUsuario,
			);
			
			$data	= procesarArreglo($data);
			$this->db->insert('compras',$data);
			$idCompra	= $this->db->insert_id();
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$subTotal=0;
			
			for($i=0;$i<$numeroRequisiciones;$i++)
			{
				if($idProveedor==$this->input->post('txtIdProveedor'.$i) and $this->input->post('chkAutorizar'.$i)>0)
				{
					$cantidad	= $this->input->post('txtCantidadProducto'.$i);
					$costo		= $this->input->post('txtCostoProducto'.$i);
					$idMaterial	= $this->input->post('txtIdMaterial'.$i);
					$idDetalle	= $this->input->post('txtIdDetalle'.$i);
					$importe	= $cantidad*$costo;
					$subTotal	+=$importe;
					
					$data=array
					(
						'idCompra' 				=> $idCompra,
						'idMaterial' 			=> $idMaterial,
						'cantidad' 				=> $cantidad,
						'total' 				=> $importe,
						'precio' 				=> $costo,
						'fechaEntrega'			=> $this->fecha,
						'descuento'				=> 0,
						'descuentoPorcentaje'	=> 0,
						
						'idDetalleRequisicion'	=> $idDetalle
					);
					
					$this->db->insert('compra_detalles',$data);
					
					//ACTUALIZAR REL MATERIA PRIMA
					$this->db->where('idProveedor',$idProveedor);
					$this->db->where('idMaterial',$idMaterial);
					$this->db->update('rel_material_proveedor',array('costo'=>$costo));
					
					//ACTUALIZAR EL ESTATUS
					$this->db->where('idDetalle',$idDetalle);
					$this->db->update('produccion_materiales_requisiciones_detalles',array('autorizada'=>'1'));
				}
			}
			
			//ACTUALIZAR IMPORTES COMPRAS
			$iva	= $ivas->iva>0?$subTotal*($ivas->iva/100):0;
			$data=array
			(
				'subTotal' 			=> $subTotal,
				'ivaPorcentaje' 	=> $ivas->iva,
				'iva' 				=> $iva,
				'total' 			=> $subTotal+$iva,
			);
			
			$this->db->where('idCompras',$idCompra);
			$this->db->update('compras',$data);
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
	
	#REQUISICIONES
	#====================================================================================================
	public function contarRequisicionesProcesadas($criterio,$inicio,$fin)
	{
		$sql =" select count(a.idRequisicion) as numero
		from produccion_materiales_requisiciones as a
		inner join produccion_materiales_requisiciones_detalles as b
		on a.idRequisicion=b.idRequisicion
		
		inner join compra_detalles as c
		on c.idDetalleRequisicion=b.idDetalle
		
		inner join compras as d
		on c.idCompra=d.idCompras
		
		inner join proveedores as e
		on e.idProveedor=d.idProveedor
		
		where a.idRequisicion>0
		and date(a.fechaRequisicion) between '$inicio' and '$fin'
		and b.autorizada='1'
		
		and a.idLicencia='$this->idLicencia'
		and (a.folio like '%$criterio%' or e.empresa like '%$criterio%' or d.nombre like '%$criterio%')
		
		group by a.idRequisicion ";
		
		#$sql.=strlen($criterio)>0?" and nombre like '%$criterio%' ":'';
		
		return $this->db->query($sql)->num_rows();
	}

	public function obtenerRequisicionesProcesadas($numero,$limite,$criterio,$inicio,$fin)
	{
		$sql =" select a.*
		from produccion_materiales_requisiciones as a
		inner join produccion_materiales_requisiciones_detalles as b
		on a.idRequisicion=b.idRequisicion
		
		inner join compra_detalles as c
		on c.idDetalleRequisicion=b.idDetalle
		
		inner join compras as d
		on c.idCompra=d.idCompras
		
		inner join proveedores as e
		on e.idProveedor=d.idProveedor
		
		where a.idRequisicion>0
		and date(a.fechaRequisicion) between '$inicio' and '$fin'
		and b.autorizada='1'
		and a.idLicencia='$this->idLicencia'
		
		and (a.folio like '%$criterio%' or e.empresa like '%$criterio%' or d.nombre like '%$criterio%')
		
		group by a.idRequisicion  ";
		
		#$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';
		
		$sql .= " order by a.fechaRequisicion desc
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerComprasRequisicion($idRequisicion)
	{
		$sql =" select distinct idCompras, a.nombre, a.cerrada
		from compras as a
		inner join compra_detalles as b
		on a.idCompras=b.idCompra
		inner join produccion_materiales_requisiciones_detalles as c
		on c.idDetalle=b.idDetalleRequisicion
		where c.idRequisicion='$idRequisicion'
		order by cerrada desc ";

		return $this->db->query($sql)->result();
	}
}
?>
