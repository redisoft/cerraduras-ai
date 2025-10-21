<?php
class Motivos_modelo extends CI_Model
{
    protected $fecha;
    protected $idUsuario;
 
    function __construct()
	{
		parent::__construct();

		$this->fecha 		= date('Y-m-d H:i:s');
		$this->idUsuario 	= $this->session->userdata('id');
    }

	public function contarMotivos()
	{
		$sql="select count(idMotivo) as numero
		from cotizaciones_motivos
		where activo='1' ";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerMotivos($numero,$limite)
	{
		$sql=" select * from cotizaciones_motivos
		where activo='1'
		order by nombre asc ";

		$sql .= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerMotivo($idMotivo)
	{
		$sql=" select * from  cotizaciones_motivos
		where idMotivo='$idMotivo' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarMotivo($nombre)
	{
		$sql=" select nombre
		from cotizaciones_motivos
		where nombre='$nombre'
		and activo='1' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function comprobarMotivoCotizacion($idMotivo)
	{
		$sql=" select idMotivo
		from cotizaciones
		where idMotivo='$idMotivo' ";
		
		return $this->db->query($sql)->num_rows();
	}

	public function registrarMotivo()
	{
		$nombre	= trim($this->input->post('nombre'));
		
		if($this->comprobarMotivo($nombre)>0) return array('0',registroDuplicado);
		
		$data=array
		(
			'nombre'		=> $nombre,
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('cotizaciones_motivos',$data);
		
		$this->configuracion->registrarBitacora('Registrar motivo cotización no asignada','Cotizaciones',$nombre); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarMotivo()
	{
		$nombre	= trim($this->input->post('nombre'));
		
		#if($this->comprobarMotivo($nombre)>0) return "0";
		
		$data=array
		(
			'nombre'		=> $nombre,
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idMotivo',$this->input->post('idMotivo'));
	    $this->db->update('cotizaciones_motivos',$data);
		
		$this->configuracion->registrarBitacora('Editar motivo cotización no asignada','Cotizaciones',$nombre); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?'1':'0';
	}
	
	public function obtenerMotivoNombre($idMotivo)
	{
		$sql=" select nombre 
		from  cotizaciones_motivos
		where idMotivo='$idMotivo' ";
		
		$motivo	= $this->db->query($sql)->row();
		
		return $motivo!=null?$motivo->nombre:'';
	}
	
	public function borrarMotivo($idMotivo)
	{
		#if($this->comprobarMotivoCotizacion($idMotivo)>0) return "0";
		
	    $this->db->where('idMotivo',$idMotivo);
		$this->db->update('cotizaciones_motivos',array('activo'=>'0'));
		
		$this->configuracion->registrarBitacora('Borrar motivo cotización no asignada','Cotizaciones',$this->obtenerMotivoNombre($idMotivo)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?'1':'0';
	}
}

?>
