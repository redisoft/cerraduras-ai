<?php
class Control_modelo extends CI_Model
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
	public function contarSalidasControl($criterio,$inicio,$fin)
	{
		$sql =" select count(a.idSalida) as numero
		from produccion_materiales_control_salidas as a
		inner join usuarios as c
		on c.idUsuario=a.idUsuario
		where a.idSalida>0
		and date(a.fechaSalida) between '$inicio' and '$fin' ";
		
		$sql.=strlen($criterio)>0?" and (a.folio like '%$criterio%' or c.nombre like '%$criterio%'
		
		or (select b.nombre from tiendas as b where b.idTienda=a.idTienda) like '%$criterio%' ) ":'';
		
		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerSalidasControl($numero,$limite,$criterio,$inicio,$fin)
	{
		$sql =" select a.*,

		if(a.idTienda=0,'Matriz',
		(select b.nombre from tiendas as b where b.idTienda=a.idTienda limit 1)) as tienda,
		
		c.nombre as usuario
		from produccion_materiales_control_salidas as a
		inner join usuarios as c
		on c.idUsuario=a.idUsuario
		where a.idSalida>0
		and date(a.fechaSalida) between '$inicio' and '$fin' ";
		
		$sql.=strlen($criterio)>0?" and (a.folio like '%$criterio%' or c.nombre like '%$criterio%'
		or (select b.nombre from tiendas as b where b.idTienda=a.idTienda) like '%$criterio%' ) ":'';
		
		$sql .= " order by folio desc
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerFolioSalida()
	{
		$sql =" select coalesce(max(folio),0) as folio from produccion_materiales_control_salidas  ";

		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function obtenerSalidaControl($idSalida)
	{
		$sql =" select a.*,
		if(a.idTienda=0,'Matriz',
		(select b.nombre from tiendas as b where b.idTienda=a.idTienda limit 1)) as tienda,
		(select b.nombre from usuarios as b where b.idUsuario=a.idUsuario limit 1) as usuario
		from produccion_materiales_control_salidas as a
		where a.idSalida='$idSalida' ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerMaterialesSalidaControl($idSalida)
	{
		$sql =" select a.*, b.nombre as material, b.codigoInterno,
		c.descripcion as unidad
		from produccion_materiales_control_salidas_detalles as a
		inner join produccion_materiales as b
		on a.idMaterial=b.idMaterial
		inner join unidades as c
		on c.idUnidad=b.idUnidad
		where a.idSalida='$idSalida' ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarMaterialesSalida($idSalida)#Se registra materiales requisición
	{
		$numeroMateriales	= $this->input->post('txtNumeroMateriales');
		
		for($i=0;$i<$numeroMateriales;$i++)
		{
			if($this->input->post('txtIdMaterial'.$i)>0)
			{
				$data=array
				(
				   'idSalida'			=> $idSalida,
				   'idMaterial'			=> $this->input->post('txtIdMaterial'.$i),
				   'cantidad'			=> $this->input->post('txtCantidadControl'.$i),
				);
				
				$this->db->insert('produccion_materiales_control_salidas_detalles', $data);
			}
		}
	}
	
	public function registrarSalidaControl()
	{
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		$data=array
		(
		   'fechaRegistro'		=> $this->fecha,
		   'fechaDevolucion'	=> $this->input->post('txtFechaDevolucion'),
		   'fechaSalida'		=> $this->input->post('txtFechaSalida'),
		   'idUsuario'			=> $this->idUsuario,
		   'comentarios'		=> $this->input->post('txtComentarios'),
		   'folio'				=> $this->obtenerFolioSalida(),
		   'idTienda'			=> $this->input->post('selectTiendas'),
		);
		
		$data	= procesarArreglo($data);
		
		$this->db->insert('produccion_materiales_control_salidas', $data);
		$idSalida	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar salida','Control materia prima - Salidas',''); //Registrar bitácora

		$this->registrarMaterialesSalida($idSalida);

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
	
	public function editarSalidaControl()
	{
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		$idSalida				= $this->input->post('txtIdSalida');
		
		$data=array
		(
		  	'fechaDevolucion'	=> $this->input->post('txtFechaDevolucion'),
		   	'fechaSalida'		=> $this->input->post('txtFechaSalida'),
		   	'comentarios'		=> $this->input->post('txtComentarios'),
			'idTienda'			=> $this->input->post('selectTiendas'),
		);
		
		$data	= procesarArreglo($data);
		
		$this->db->where('idSalida', $idSalida);
		$this->db->update('produccion_materiales_control_salidas', $data);
		
		$this->db->where('idSalida', $idSalida);
		$this->db->delete('produccion_materiales_control_salidas_detalles');
		
		$this->configuracion->registrarBitacora('Editar salida','Control materia prima - Salidas',''); //Registrar bitácora

		$this->registrarMaterialesSalida($idSalida);

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
	
	public function borrarSalidaControl($idSalida)
	{
		$this->db->trans_start(); 
		
		$this->db->where('idSalida', $idSalida);
		$this->db->delete('produccion_materiales_control_salidas_detalles');
		
		$this->db->where('idSalida', $idSalida);
		$this->db->delete('produccion_materiales_control_salidas');

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
	
	//DEVOLUCIONES 
	public function registrarDevueltosControl()
	{
		$this->db->trans_start(); 
		
		#--------------------------------------------------------------------------------------------#
		$idSalida				= $this->input->post('txtIdSalida');
		$materiales				= $this->obtenerMaterialesSalidaControl($idSalida);
		
		foreach($materiales as $row)
		{
			$data=array
			(
				'devueltos'		=> $this->input->post('txtCantidadDevuelto'.$row->idDetalle),
			);
			
			$this->db->where('idDetalle', $row->idDetalle);
			$this->db->update('produccion_materiales_control_salidas_detalles',$data);
		}

		$this->db->where('idSalida', $idSalida);
		$this->db->update('produccion_materiales_control_salidas', array('devueltos'=>'1'));

		#$this->configuracion->registrarBitacora('Editar salida','Control materia prima - Salidas',''); //Registrar bitácora

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
