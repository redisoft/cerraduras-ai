<?php
class Redisoft_modelo extends CI_Model
{
	protected $_fecha_actual;
	protected $_table;
	protected $idLicencia;
	protected $_user_name;

	function __construct()
	{
		parent::__construct();
		
		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->_fecha_actual 	= mdate($datestring,now());
	}

	public function obtenerUsuarios()
	{
		$sql=" select idUsuario, concat(nombre, apellidoPaterno, apellidoMaterno) as nombre,
		fechaCreacion, fechaAcceso, correo, password, usuario
		from usuarios
		order by fechaAcceso desc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerEmisores()
	{
		$sql=" select a.idEmisor, a.nombre,
		a.rfc, a.serie, a.folioInicial, a.folioFinal,
		(select coalesce(count(b.idFactura),0) from facturas as b where b.idEmisor=a.idEmisor) as foliosUsados
		from configuracion_emisores as a
		order by a.nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerMensaje()
	{
		$sql=" select id, mensaje
		from configuracion ";
		
		return $this->db->query($sql)->row();
	}
	
	public function editarMensaje()
	{
		$datos=array
		(
			'mensaje'		=>$this->input->post('mensaje'),
		);	
		
		$this->db->where('id',$this->input->post('id'));
		$this->db->update('configuracion', $datos);
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
}
