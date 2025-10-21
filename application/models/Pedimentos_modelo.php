<?php
class Pedimentos_modelo extends CI_Model
{
	protected $fecha;
	protected $idUsuario;

	function __construct()
	{
		parent::__construct();
		
        $this->idUsuario 		= $this->session->userdata('id');
		$this->fecha 			= date('Y-m-d H:i:s');
		$datestring   			= "%Y-%m-%d %H:%i:%s";
	}
	
	public function contarRegistros($criterio='')
	{    
		$sql=" select count(a.idPedimento) as numero
		from productos_pedimentos as a
		where a.activo='1'  ";
		 
        $sql.=strlen($criterio)>0?" and a.ejes like '$criterio%' ":'';
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerRegistros($numero=0,$limite=0,$criterio='')
	{    
		$sql=" select a.*, concat(a.anio,'  ',a.aduana,'  ',a.patente,'  ',a.digitos) as pedimento
		from productos_pedimentos as a
		where a.activo='1' ";
		 
        $sql.=strlen($criterio)>0?" and concat(a.anio,'  ',a.aduana,'  ',a.patente,'  ',a.digitos) like '$criterio%' ":'';
		
		$sql.= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
    
    public function obtenerRegistro($idPedimento)
	{    
		$sql=" select a.*, concat(a.anio,'  ',a.aduana,'  ',a.patente,'  ',a.digitos) as pedimento
		from productos_pedimentos as a
		where a.idPedimento=$idPedimento";

        return $this->db->query($sql)->row();
	}
    
    public function registrarFormulario()
	{
		$data=array
		(
			'fecha'		        => $this->input->post('txtFechaPedimento'),
            'anio'				=> $this->input->post('txtAnio'),
            'aduana'            => $this->input->post('txtAduana'),
			'patente'           => $this->input->post('txtPatente'),
			'digitos'           => $this->input->post('txtDigitos'),
			'idUsuarioRegistro' => $this->idUsuario,
			'fechaRegistro'	    => $this->fecha,
		);
		
	    $this->db->insert('productos_pedimentos',$data);

		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
    
    public function editarFormulario()
	{
		$data=array
		(
            'fecha'		        => $this->input->post('txtFechaPedimento'),
            'anio'				=> $this->input->post('txtAnio'),
            'aduana'            => $this->input->post('txtAduana'),
			'patente'           => $this->input->post('txtPatente'),
			'digitos'           => $this->input->post('txtDigitos'),
			'idUsuarioEdicion'	=> $this->idUsuario,
			'fechaEdicion'	    => $this->fecha,
		);
		
	    $this->db->where('idPedimento',$this->input->post('txtIdPedimento'));
        $this->db->update('productos_pedimentos',$data);

		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
    
    public function borrarRegistro($idPedimento)
	{
		$this->db->where('idPedimento',$idPedimento);
		$this->db->update('productos_pedimentos',array('activo'=>'0'));
        
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
    
}
