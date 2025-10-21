<?php
class Estaciones_modelo extends CI_Model
{
	protected $fecha;
	protected $idLicencia;
	protected $resultado;
	protected $idUsuario;

	function __construct()
	{
		parent::__construct();

        $this->idUsuario 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->fecha 			= mdate($datestring,now());
		$this->resultado		="1";
	}

	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//MATERIAS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarRegistros($criterio='')
	{    
		$sql=" select a.idEstacion
		from configuracion_estaciones as a
		where a.activo='1'
		and a.idLicencia='$this->idLicencia' ";
		 
		$sql.=strlen($criterio)>0?" and a.nombre like '$criterio%' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerRegistros($numero=0,$limite=0,$criterio='')
	{    
		$sql=" select a.idEstacion, a.nombre
		from configuracion_estaciones as a
		where a.activo='1'
		and a.idLicencia='$this->idLicencia' ";
		 
		 $sql.=strlen($criterio)>0?" and a.nombre like '$criterio%' ":'';
		
		$sql.= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRegistrosLicencia($idLicencia)
	{    
		$sql=" select * from configuracion_estaciones 
		where idLicencia=$idLicencia
		and activo='1'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRegistro($idEstacion)
	{    
		$sql=" select * from configuracion_estaciones 
		where idEstacion=$idEstacion ";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarRegistro($nombre)
	{    
		$this->db->select('idEstacion');
		$this->db->from('configuracion_estaciones');
		$this->db->where('nombre',$nombre);
		$this->db->where('idLicencia',$this->idLicencia);
		$this->db->where('activo','1');
		
		$registro	= $this->db->get()->row();
		
		return $registro!=null?true:false;
	}
	
	public function registrarFormulario()
	{
		if($this->comprobarRegistro($this->input->post('txtNombre')))
		{
			return array('0','El registros esta duplicado');
		}
		
		$data=array
		(
			'nombre'		=> $this->input->post('txtNombre'),
			'idLicencia'	=> $this->idLicencia,
		);
		
	    $this->db->insert('configuracion_estaciones',$data);
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarFormulario()
	{
		$data=array
		(
			'nombre'		=> $this->input->post('txtNombre'),
		);
		
		$this->db->where('idEstacion',$this->input->post('txtIdRegistro'));
	    $this->db->update('configuracion_estaciones',$data);
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}

	public function borrarRegistro($idEstacion)
	{
	    $this->db->where('idEstacion',$idEstacion);
		$this->db->update('configuracion_estaciones',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?array('1',borradoCorrecto):array('0',errorBorrado); 
	}
	
	
	
}
