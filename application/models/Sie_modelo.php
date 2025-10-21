<?php
class Sie_modelo extends CI_Model
{
	protected $_fecha_actual;
	protected $_table;
	protected $idLicencia;
	protected $resultado;
	protected $_user_id;
	protected $fecha;
	protected $idFactura;

	function __construct()
	{
		parent::__construct();

        $this->_user_id 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');

		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->_fecha_actual 	= mdate($datestring,now());
		$this->fecha 			= date('Y-m-d');
		$this->resultado		= "1";
		$this->idFactura		= 0;
	}
	
	public function obtenerIngresosDisponibles($fecha)
	{
		$sql ="	select coalesce(sum(a.pago),0) as total		
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		inner join bancos as d
		on d.idBanco=b.idBanco
		where a.idIngreso>0
		and a.idForma!=4
		and a.idLicencia='$this->idLicencia'
		and b.noDisponible='0'
		and b.idCuenta!=1
		and date(a.fecha)<='$fecha'
		
		and b.sie='1' ";

		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerEgresosDisponibles($fecha)
	{
		$sql ="	select coalesce(sum(c.pago),0)  as total
		from catalogos_egresos as c 
		inner join cuentas as d
		on d.idCuenta=c.idCuenta
		where c.idEgreso>0
		and c.devolucion='0'
		and date(c.fecha) <= '$fecha'
		and c.idLicencia='$this->idLicencia'
		and c.idForma!=4
		and c.idCuenta!=1
		and d.noDisponible='0'
		
		and d.sie='1'  ";

		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerIngresosEfectivo()
	{
		$sql ="	select efectivo
		from configuracion
		where idLicencia='$this->idLicencia'";

		return $this->db->query($sql)->row()->efectivo;
	}
	
	/*public function obtenerIngresosEfectivo($fecha)
	{
		$sql ="	select coalesce(sum(a.pago),0) as total, 
		b.cuenta, d.nombre as banco, d.idBanco
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		inner join bancos as d
		on d.idBanco=b.idBanco
		where a.idIngreso>0
		and a.idForma!=4
		and a.idLicencia='$this->idLicencia'
		and b.idCuenta=1
		and date(a.fecha)<='$fecha'";

		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerEgresosEfectivo($fecha)
	{
		$sql ="	select coalesce(sum(c.pago),0)  as total
		from catalogos_egresos as c 
		inner join cuentas as d
		on d.idCuenta=c.idCuenta
		where c.idEgreso>0
		and c.devolucion='0'
		and date(c.fecha) <= '$fecha'
		and c.idLicencia='$this->idLicencia'
		and c.idForma!=4
		and d.idCuenta=1  ";

		return $this->db->query($sql)->row()->total;
	}*/
	
	public function obtenerIngresosNoDisponiblesCuentas($fecha)
	{
		$sql ="	select coalesce(sum(a.pago),0) as total, b.idBanco,
		d.nombre as banco
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		inner join bancos as d
		on d.idBanco=b.idBanco
		where a.idIngreso>0
		and a.idForma!=4
		and a.idLicencia='$this->idLicencia'
		and b.noDisponible='1'
		and b.idCuenta!=1
		and date(a.fecha)<='$fecha'
		
		and b.sie='1'
		
		group by b.idBanco ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerEgresosNoDisponiblesCuenta($fecha,$idBanco)
	{
		$sql ="	select coalesce(sum(a.pago),0) as total, b.idCuenta,
		d.nombre as banco
		from catalogos_egresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		inner join bancos as d
		on d.idBanco=b.idBanco
		where a.idEgreso>0
		and a.idForma!=4
		and a.idLicencia='$this->idLicencia'
		and b.noDisponible='1'
		and b.idBanco='$idBanco'
		
		and b.sie='1'
		
		
		and date(a.fecha)<='$fecha' ";

		return $this->db->query($sql)->row()->total;
	}
	
	//SALDO AL DÍA
	
	public function obtenerIngresosDia($fecha)
	{
		$sql ="	select coalesce(sum(a.pago),0) as total		
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		inner join bancos as d
		on d.idBanco=b.idBanco
		where a.idIngreso>0
		and a.idForma!=4
		and a.idLicencia='$this->idLicencia'
		and date(a.fecha)<='$fecha'
		and b.noDisponible='0' 
		
		and b.sie='1' ";

		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerEgresosDia($fecha)
	{
		$sql ="	select coalesce(sum(c.pago),0)  as total
		from catalogos_egresos as c 
		inner join cuentas as d
		on d.idCuenta=c.idCuenta
		where c.idEgreso>0
		and c.devolucion='0'
		and date(c.fecha) <= '$fecha'
		and c.idLicencia='$this->idLicencia'
		and c.idForma!=4 
		and d.noDisponible='0'
		
		and d.sie='1'  ";

		return $this->db->query($sql)->row()->total;
	}
	
	//EGRESOS
	
	public function obtenerEgresosConceptos($inicio,$fin)
	{
		$sql ="	select coalesce(sum(a.pago),0) as importe, b.nombre as concepto
		from catalogos_egresos as a
		inner join catalogos_productos as b
		on a.idProducto=b.idProducto
		where a.idEgreso>0
		and a.devolucion='0'
		and date(a.fecha) between '$inicio' and '$fin'
		and a.idLicencia='$this->idLicencia'
		group by b.idProducto ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerEgresosNivel($inicio,$fin)
	{
		$sql ="	select coalesce(sum(a.pago),0) as importe, b.nombre as concepto
		from catalogos_egresos as a
		inner join catalogos_niveles1 as b
		on a.idNivel1=b.idNivel1
		where a.idEgreso>0
		and a.devolucion='0'
		and date(a.fecha) between '$inicio' and '$fin'
		and a.idLicencia='$this->idLicencia'
		group by b.idNivel1 ";
		
		return $this->db->query($sql)->result();
	}
	
	
	public function contarDetallesEgresosConceptos($inicio,$fin)
	{
		$sql ="	select a.idEgreso
		from catalogos_egresos as a
		inner join catalogos_niveles1 as b
		on a.idNivel1=b.idNivel1
		where a.idEgreso>0
		and a.devolucion='0'
		and date(a.fecha) between '$inicio' and '$fin'
		and a.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerDetallesEgresosConceptos($numero=0,$limite=0,$inicio,$fin)
	{
		$sql ="	select a.fecha, a.pago, b.nombre as concepto
		from catalogos_egresos as a
		inner join catalogos_niveles1 as b
		on a.idNivel1=b.idNivel1
		where a.idEgreso>0
		and a.devolucion='0'
		and date(a.fecha) between '$inicio' and '$fin'
		and a.idLicencia='$this->idLicencia'
		order by a.fecha desc ";
		
		$sql.= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFinanciera()
	{
		$sql ="	select *
		from sie_financiera ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerSaldoCuentas()
	{
		$sql ="	select coalesce(sum(saldoManual),0) as saldoManual
		from cuentas where activo='1'
		and sie='1' ";

		return $this->db->query($sql)->row()->saldoManual;
	}
	
	public function editarEfectivo()
	{
		$data=array
		(
			'efectivo'	=> trim($this->input->post('efectivo')),
		);
		
		$this->db->where('idFinanciera',1);
		$this->db->update('sie_financiera',$data);

		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	/*public function editarCuentas()
	{
		$data=array
		(
			'cuentas'	=> trim($this->input->post('cuentas')),
		);
		
		$this->db->where('idFinanciera',1);
		$this->db->update('sie_financiera',$data);

		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}*/
	
	public function editarCuentas()
	{
		$this->db->trans_start();
		
		$cuentas				= $this->configuracion->obtenerCuentasSie();
		
		foreach($cuentas as $row)
		{
			$data=array
			(
				'saldoManual'	=> trim($this->input->post('txtCuentas'.$row->idCuenta)),
			);
			
			$this->db->where('idCuenta',$row->idCuenta);
			$this->db->update('cuentas',$data);
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
	
	public function editarNoDisponible()
	{
		$data=array
		(
			'payu'		=> trim($this->input->post('payu')),
			'paypal'	=> trim($this->input->post('paypal')),
		);
		
		$this->db->where('idFinanciera',1);
		$this->db->update('sie_financiera',$data);

		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
}
