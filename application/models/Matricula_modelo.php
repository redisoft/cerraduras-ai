<?php
class Matricula_modelo extends CI_Model
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
	//NIVELES 1
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarRegistros($licenciatura='1')
	{    
		$sql=" select a.idMatricula
		from sie_matriculas as a
		where a.licenciatura='$licenciatura' ";

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerRegistros($numero=0,$limite=0,$licenciatura='1')
	{    
		$sql=" select a.*, b.nombre as programa
		from sie_matriculas as a
		inner join clientes_programas as b
		on a.idPrograma=b.idPrograma
		where a.licenciatura='$licenciatura' ";
		
		$sql.= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRegistro($idMatricula)
	{    
		$sql=" select * from sie_matriculas 
		where idMatricula=$idMatricula ";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarInformacion($licenciatura,$cuatrimestre,$idPrograma)
	{    
		$sql=" select idMatricula 
		from sie_matriculas 
		where licenciatura='$licenciatura'
		and cuatrimestre='$cuatrimestre'
		and idPrograma='$idPrograma' ";

		return $this->db->query($sql)->num_rows();
	}
	
	public function registrarInformacion()
	{
		if($this->comprobarInformacion($this->input->post('licenciatura'),$this->input->post('selectCuatrimestreSie'),$this->input->post('selectProgramasSie'))>0) return "0";
		
		$data=array
		(
			'licenciatura'	=> $this->input->post('licenciatura'),
			'cuatrimestre'	=> $this->input->post('selectCuatrimestreSie'),
			'idPrograma'	=> $this->input->post('selectProgramasSie'),
			'ingresos'		=> $this->input->post('txtIngresosSie'),
			'actual'		=> $this->input->post('txtActualSie'),
			'meta'			=> $this->input->post('txtMetaSie'),
			
		);
		
	    $this->db->insert('sie_matriculas',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function borrarRegistro($idMatricula)
	{
	    $this->db->where('idMatricula',$idMatricula);
		$this->db->delete('sie_matriculas');
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function editarMatricula($idMatricula)
	{
	    $this->db->where('idMatricula',$idMatricula);
		$this->db->update('sie_matriculas',array($this->input->post('campo')=>$this->input->post('valor')));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerMatricula($licenciatura='1')
	{    
		$sql=" select  a.cuatrimestre, sum(a.ingresos) as ingresos, sum(a.actual) as actual,
		avg(a.meta) as meta
		from sie_matriculas as a
		where a.licenciatura='$licenciatura'
		group by a.cuatrimestre ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerDetallesMatricula($cuatrimestre='',$licenciatura='')
	{    
		$sql=" select a.*, b.nombre as programa
		from sie_matriculas as a
		inner join clientes_programas as b
		on a.idPrograma=b.idPrograma ";
		 
		 #where a.licenciatura='$licenciatura'
		
		$sql.=strlen($licenciatura)>0?" and a.licenciatura='$licenciatura' ":'';
		$sql.=strlen($cuatrimestre)>0?" and a.cuatrimestre='$cuatrimestre' ":'';
		
		$sql.=" order by b.nombre asc, a.ingresos desc";

		return $this->db->query($sql)->result();
	}
	
}
