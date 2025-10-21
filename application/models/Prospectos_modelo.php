<?php
class Prospectos_modelo extends CI_Model
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
	public function contarRegistros($inicio='',$fin='')
	{    
		$sql=" select a.idMeta
		from sie_prospectos_metas as a
		where date(a.fechaInicial) between '$inicio' and '$fin' ";

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerRegistros($numero=0,$limite=0,$inicio='',$fin='')
	{    
		$sql=" select a.*, b.nombre as tipo, c.nombre as grado
		from sie_prospectos_metas as a
		inner join sie_prospectos_tipos as b
		on a.idTipo=b.idTipo
		
		inner join clientes_programas_grados as c
		on c.idGrado=a.idGrado
		
		where date(a.fechaInicial) between '$inicio' and '$fin'
		order by a.fechaInicial desc ";
		
		$sql.= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRegistro($idMeta)
	{    
		$sql=" select * from sie_prospectos_metas 
		where idMeta=$idMeta ";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarInformacion($licenciatura,$cuatrimestre,$idPrograma)
	{    
		$sql=" select idMatricula 
		from sie_prospectos_metas 
		where licenciatura='$licenciatura'
		and cuatrimestre='$cuatrimestre'
		and idPrograma='$idPrograma' ";

		return $this->db->query($sql)->num_rows();
	}
	
	public function registrarInformacion()
	{
		#if($this->comprobarInformacion($this->input->post('licenciatura'),$this->input->post('selectCuatrimestreSie'),$this->input->post('selectProgramasSie'))>0) return "0";
		
		$data=array
		(
			'fechaInicial'		=> $this->input->post('txtFechaInicialSie'),
			'fechaFinal'		=> $this->input->post('txtFechaFinalSie'),
			#'semana'			=> $this->input->post('selectProgramasSie'),
			'meta'				=> $this->input->post('txtMetaSie'),
			'idTipo'			=> $this->input->post('selectTiposSie'),
			'idGrado'			=> $this->input->post('selectGradosSie'),
			'fechaRegistro'		=> $this->fecha,
			'idUsuario'			=> $this->idUsuario,
		);
		
	    $this->db->insert('sie_prospectos_metas',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function editarInformacion()
	{
		#if($this->comprobarInformacion($this->input->post('licenciatura'),$this->input->post('selectCuatrimestreSie'),$this->input->post('selectProgramasSie'))>0) return "0";
		
		$data=array
		(
			'fechaInicial'		=> $this->input->post('txtFechaInicialSie'),
			'fechaFinal'		=> $this->input->post('txtFechaFinalSie'),
			#'semana'			=> $this->input->post('selectProgramasSie'),
			'meta'				=> $this->input->post('txtMetaSie'),
			'idTipo'			=> $this->input->post('selectTiposSie'),
			'idGrado'			=> $this->input->post('selectGradosSie'),
		);
		
	    $this->db->where('idMeta',$this->input->post('txtIdMeta'));
		$this->db->update('sie_prospectos_metas',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function borrarRegistro($idMeta)
	{
	    $this->db->where('idMeta',$idMeta);
		$this->db->delete('sie_prospectos_metas');
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerProspectos($inicio='',$fin='',$idTipo=1)
	{    
		$sql=" select  coalesce(sum(a.meta),0) as meta,
		b.nombre as grado,
		
		(select count(c.idCliente) from clientes as c
		inner join clientes_academicos as d
		on d.idCliente=c.idCliente
		inner join clientes_programas as e
		on e.idPrograma=d.idPrograma
		where e.idGrado=a.idGrado
		and c.prospecto='1'
		and c.activo='1'
		".(strlen($inicio)>0?" and date(c.fechaRegistro) between '$inicio' and '$fin' ":"").") as resultado
		
		from sie_prospectos_metas as a
		inner join clientes_programas_grados as b
		on a.idGrado=b.idGrado
		where  a.idTipo='$idTipo'
		".(strlen($inicio)>0?" and a.fechaInicial='$inicio' and a.fechaFinal='$fin' ":"")."
		
		group by a.idGrado ";
		
		#echo $sql;

		return $this->db->query($sql)->result();
	}
	
	public function obtenerDetallesMatricula($cuatrimestre='',$licenciatura='')
	{    
		$sql=" select a.*, b.nombre as programa
		from sie_prospectos_metas as a
		inner join clientes_programas as b
		on a.idPrograma=b.idPrograma ";
		 
		 #where a.licenciatura='$licenciatura'
		
		$sql.=strlen($licenciatura)>0?" and a.licenciatura='$licenciatura' ":'';
		$sql.=strlen($cuatrimestre)>0?" and a.cuatrimestre='$cuatrimestre' ":'';
		
		$sql.=" order by b.nombre asc, a.ingresos desc";

		return $this->db->query($sql)->result();
	}
	
	//INSCRITOS
	public function obtenerInscritos($inicio='',$fin='',$idTipo=2)
	{    
		$sql=" select  coalesce(sum(a.meta),0) as meta,
		b.nombre as grado,
		
		(select count(c.idCliente) from clientes as c
		inner join clientes_academicos as d
		on d.idCliente=c.idCliente
		inner join clientes_programas as e
		on e.idPrograma=d.idPrograma
		where e.idGrado=a.idGrado
		and d.preinscrito='1'
		and c.activo='1'
		".(strlen($inicio)>0?" and date(d.fechaPreinscrito) between '$inicio' and '$fin' ":"").") as resultado
		
		from sie_prospectos_metas as a
		inner join clientes_programas_grados as b
		on a.idGrado=b.idGrado
		where  a.idTipo='$idTipo'
		".(strlen($inicio)>0?" and a.fechaInicial='$inicio' and a.fechaFinal='$fin' ":"")."
		
		group by a.idGrado ";

		return $this->db->query($sql)->result();
	}
	
}
