<?php
class Creditos_modelo extends CI_Model
{
    protected $fecha;
    protected $idUsuario;
	protected $idLicencia;
	protected $idTienda;
	protected $usuario;
	protected $idRol;
	protected $fechaCorta;

    function __construct()
	{
		parent::__construct();

		$this->fecha 			= date('Y-m-d H:i:s');
		$this->fechaCorta 		= date('Y-m-d');
		$this->idUsuario 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->idTienda 		= $this->session->userdata('idTiendaActiva');
		$this->usuario 			= $this->session->userdata('nombreUsuarioSesion');
		$this->idRol 			= $this->session->userdata('role');
   }

	#INGRESOS
	#====================================================================================================
	public function contarCreditos()
	{
		$sql =" select count(a.idCredito) as numero
		from sie_creditos as a
		where a.idCredito>0 ";
		
		#and date(a.fecha) between '$inicio' and '$fin' 

		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerCreditos($numero=0,$limite=0)
	{
		$sql =" select a.*,
		(select b.nombre from sie_creditos_frecuencias as b where a.idFrecuencia=b.idFrecuencia limit 1) as frecuencia
		from sie_creditos as a
		where a.idCredito>0  ";

		$sql .= " order by fechaPago desc ";
		
		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function obtenerTotalAdeudos()
	{
		$sql =" select coalesce(sum(a.adeudoActual),0) as total
		from sie_creditos as a
		where a.idCredito>0  ";

		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerCredito($idCredito)
	{
		$sql =" select a.*
		from sie_creditos as a
		where a.idCredito='$idCredito'  ";

		return $this->db->query($sql)->row();
	}
	
	public function registrarCredito()
	{
		$data=array
		(
			
			'fuente'			=> $this->input->post('txtFuente'),
			'monto'				=> $this->input->post('txtMonto'),
			'interesAnual'		=> $this->input->post('txtInteresAnual'),
			'adeudoActual'		=> $this->input->post('txtAdeudoActual'),
			'idFrecuencia'		=> $this->input->post('selectFrecuencias'),
			'fechaPago'			=> $this->input->post('txtFechaPago'),
			'pago'				=> $this->input->post('txtPago'),
			'fechaRegistro'		=> $this->fecha,
			'idUsuario'			=> $this->idUsuario,
		);
		
		$this->db->insert('sie_creditos', $data);
		
		return $this->db->affected_rows()==1?array('1'):array('0');
	}

	public function editarCredito()
	{
		$data=array
		(
			'fuente'			=> $this->input->post('txtFuente'),
			'monto'				=> $this->input->post('txtMonto'),
			'interesAnual'		=> $this->input->post('txtInteresAnual'),
			'adeudoActual'		=> $this->input->post('txtAdeudoActual'),
			'idFrecuencia'		=> $this->input->post('selectFrecuencias'),
			'fechaPago'			=> $this->input->post('txtFechaPago'),
			'pago'				=> $this->input->post('txtPago'),
		);
		
		$this->db->where('idCredito', $this->input->post('txtIdCredito'));
		$this->db->update('sie_creditos', $data);
		
		return $this->db->affected_rows()==1?array('1'):array('0');
	}
	
	public function borrarCredito($idCredito)
	{
		$this->db->where('idCredito', $idCredito);
		$this->db->delete('sie_creditos');
		
		return $this->db->affected_rows()==1?array('1'):array('0');
	}
	
	public function sumarCreditosDia($fecha)
	{
		$sql =" select coalesce(sum(pago),0) as importe
		from sie_creditos
		where fechaPago='$fecha'  ";

		return $this->db->query($sql)->row()->importe;
	}
	
	public function obtenerCreditosDia($fecha)
	{
		$sql =" select pago, fuente
		from sie_creditos
		where fechaPago='$fecha'  ";

		return $this->db->query($sql)->result();
	}
}
?>
