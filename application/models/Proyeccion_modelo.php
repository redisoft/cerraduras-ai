<?php
class Proyeccion_modelo extends CI_Model
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
	public function contarIngresos($inicio,$fin,$criterio,$idEscenario=0)
	{
		$sql =" select count(a.idIngreso) as numero
		from sie_proyeccion_ingresos as a
		where a.idIngreso>0 ";
		
		$sql.=" and date(a.fecha) between '$inicio' and '$fin'  ";
		$sql.=strlen($criterio)>0?" and a.concepto like '$criterio%'  ":'';
		$sql.=$idEscenario>0?" and a.idEscenario = '$idEscenario'  ":'';

		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerIngresos($numero,$limite,$inicio,$fin,$criterio,$idEscenario=0)
	{
		$sql =" select a.*,
		(select b.nombre from sie_proyeccion_ingresos_escenarios as b where b.idEscenario=a.idEscenario) as escenario
		from sie_proyeccion_ingresos as a
		where a.idIngreso>0  ";
		
		$sql.=" and date(a.fecha) between '$inicio' and '$fin'  ";
		$sql.=strlen($criterio)>0?" and a.concepto like '$criterio%'  ":'';
		$sql.=$idEscenario>0?" and a.idEscenario = '$idEscenario'  ":'';


		$sql .= " order by fecha desc
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function sumarIngresos($inicio,$fin,$criterio,$idEscenario=0)
	{
		$sql =" select coalesce(sum(a.importe),0) as importe
		from sie_proyeccion_ingresos as a
		where a.idIngreso>0
		and a.cobrado='0'  ";
		
		$sql.=" and date(a.fecha) between '$inicio' and '$fin'  ";
		$sql.=strlen($criterio)>0?" and a.concepto like '$criterio%'  ":'';
		$sql.=$idEscenario>0?" and a.idEscenario = '$idEscenario'  ":'';

		return $this->db->query($sql)->row()->importe;
	}
	
	public function obtenerIngreso($idIngreso)
	{
		$sql =" select a.*
		from sie_proyeccion_ingresos as a
		where a.idIngreso='$idIngreso'  ";

		return $this->db->query($sql)->row();
	}
	
	public function sumarIngresosFecha($fecha,$idEscenario=0)
	{
		$sql =" select coalesce(sum(importe),0) as importe
		from sie_proyeccion_ingresos
		where fecha<='$fecha'
		and cobrado='0' ";
		
		$sql.=$idEscenario>0?" and idEscenario='$idEscenario' ":'';

		return $this->db->query($sql)->row()->importe;
	}
	
	public function obtenerIngresosFecha($fecha)
	{
		$sql =" select importe, concepto
		from sie_proyeccion_ingresos
		where fecha<='$fecha'
		and cobrado='0'   ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerIngresosFechaGrupo($fecha,$idEscenario=0)
	{
		$sql =" select sum(importe) as importe, concepto
		from sie_proyeccion_ingresos
		where fecha<='$fecha'
		and cobrado='0'   ";
		
		$sql.=$idEscenario>0?" and idEscenario='$idEscenario' ":'';
		
		$sql.=" group by concepto
		order by importe desc ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarIngreso()
	{
		$data=array
		(
			'fecha'				=> $this->input->post('txtFecha'),
			'concepto'			=> $this->input->post('txtConcepto'),
			'importe'			=> $this->input->post('txtImporte'),
			'idEscenario'		=> $this->input->post('selectEscenarios'),
			'fechaRegistro'		=> $this->fecha,
			'idUsuario'			=> $this->idUsuario,
		);
		
		$this->db->insert('sie_proyeccion_ingresos', $data);
		
		return $this->db->affected_rows()==1?array('1'):array('0');
	}

	public function editarIngreso()
	{
		$data=array
		(
			'fecha'				=> $this->input->post('txtFecha'),
			'concepto'			=> $this->input->post('txtConcepto'),
			'importe'			=> $this->input->post('txtImporte'),
			'idEscenario'		=> $this->input->post('selectEscenarios'),
		);
		
		$this->db->where('idIngreso', $this->input->post('txtIdIngreso'));
		$this->db->update('sie_proyeccion_ingresos', $data);
		
		return $this->db->affected_rows()==1?array('1'):array('0');
	}
	
	public function borrarIngreso($idIngreso)
	{
		$this->db->where('idIngreso', $idIngreso);
		$this->db->delete('sie_proyeccion_ingresos');
		
		return $this->db->affected_rows()==1?array('1'):array('0');
	}
	
	public function definirIngresoCobrado()
	{
		$this->db->trans_start();
		
		$efectivo			= round($this->input->post('txtEfectivo'),decimales);
		$cuentas			= round($this->input->post('txtCuentas'),decimales);
		$paypal				= round($this->input->post('txtPaypal'),decimales);

		$data=array
		(
			#'efectivo'		=> $efectivo,
			#'cuentas'		=> $cuentas,
			#'paypal'		=> $paypal,
			'cobrado'		=> '1',
			
			'importe'		=> $this->input->post('txtTotalCobradoIngreso'),
		);
		
		$this->db->where('idIngreso', $this->input->post('txtIdIngreso'));
		$this->db->update('sie_proyeccion_ingresos',$data);
		
		//ACTUALIZAR LA INFORMACIÓN FINANCIERA DE LA TABLA
		
		$cuentas				= $this->configuracion->obtenerCuentasSie();
		
		foreach($cuentas as $row)
		{
			$data=array
			(
				'saldoManual'	=> $row->saldoManual+trim($this->input->post('txtCuentas'.$row->idCuenta)),
			);
			
			$this->db->where('idCuenta',$row->idCuenta);
			$this->db->update('cuentas',$data);
		}
		
		
		$financiera			= $this->sie->obtenerFinanciera();
		
		$data=array
		(
			'efectivo'		=> $financiera->efectivo+$efectivo,
			/*'cuentas'		=> $financiera->cuentas+$cuentas,
			'paypal'		=> $financiera->paypal+$paypal,*/
		);
		
		$this->db->where('idFinanciera', 1);
		$this->db->update('sie_financiera',$data);
		
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
			
			return array('1','');
		}
	}
	
	#EGRESOS
	#====================================================================================================
	public function contarEgresos($inicio,$fin,$criterio,$tipoFecha,$pagado='0',$idEscenario=0)
	{
		$sql =" select count(a.idEgreso) as numero
		from sie_proyeccion_egresos as a
		where a.idEgreso>0
		and a.pagado='$pagado' ";
		
		$sql.=$tipoFecha==0?" and date(a.fecha) between '$inicio' and '$fin'  ":" and date(a.fechaPago) between '$inicio' and '$fin'  ";
		$sql.=strlen($criterio)>0?" and a.concepto like '$criterio%'  ":'';
		$sql.=$idEscenario>0?" and a.idEscenario = '$idEscenario'  ":'';


		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerEgresos($numero,$limite,$inicio,$fin,$criterio,$tipoFecha,$pagado='0',$idEscenario=0)
	{
		$sql =" select distinct(a.idEgreso), a.*,
		(select b.nombre from sie_proyeccion_egresos_escenarios as b where b.idEscenario=a.idEscenario) as escenario
		from sie_proyeccion_egresos as a
		where a.idEgreso>0 
		and a.pagado='$pagado' ";
		
		$sql.=$tipoFecha==0?" and date(a.fecha) between '$inicio' and '$fin'  ":" and date(a.fechaPago) between '$inicio' and '$fin'  ";
		$sql.=strlen($criterio)>0?" and a.concepto like '$criterio%'  ":'';
		$sql.=$idEscenario>0?" and a.idEscenario = '$idEscenario'  ":'';

		$sql .= " order by a.fecha desc, a.fechaRegistro desc
		limit $limite,$numero ";
		
		#echo $sql;

		return $this->db->query($sql)->result();
	}
	
	public function sumarEgresos($inicio,$fin,$criterio,$tipoFecha,$pagado='0',$idEscenario=0)
	{
		$sql =" select coalesce(sum(a.importe),0) as importe
		from sie_proyeccion_egresos as a
		where a.idEgreso>0 
		and a.pagado='$pagado' ";
		
		$sql.=$tipoFecha==0?" and date(a.fecha) between '$inicio' and '$fin'  ":" and date(a.fechaPago) between '$inicio' and '$fin'  ";
		$sql.=strlen($criterio)>0?" and a.concepto like '$criterio%'  ":'';
		$sql.=$idEscenario>0?" and a.idEscenario = '$idEscenario'  ":'';

		return $this->db->query($sql)->row()->importe;
	}
	
	public function obtenerEgreso($idEgreso)
	{
		$sql =" select a.*
		from sie_proyeccion_egresos as a
		where a.idEgreso='$idEgreso'  ";

		return $this->db->query($sql)->row();
	}
	
	public function sumarEgresosFecha($fecha,$idEscenario=0)
	{
		$sql =" select coalesce(sum(importe),0) as importe
		from sie_proyeccion_egresos
		where fechaPago<='$fecha' 
		and pagado='0' ";
		
		$sql.=$idEscenario>0?" and idEscenario='$idEscenario' ":'';

		return $this->db->query($sql)->row()->importe;
	}
	
	public function obtenerEgresosFecha($fecha)
	{
		$sql =" select importe, concepto
		from sie_proyeccion_egresos
		where fechaPago<='$fecha'
		and pagado='0'  ";
		
		#echo  $sql;

		return $this->db->query($sql)->result();
	}
	
	public function obtenerEgresosFechaGrupo($fecha,$idEscenario=0)
	{
		$sql =" select sum(importe) as importe, concepto
		from sie_proyeccion_egresos
		where fechaPago<='$fecha'
		and pagado='0' ";
		
		$sql.=$idEscenario>0?" and idEscenario='$idEscenario' ":'';
		
		$sql.=" group by concepto
		order by importe desc ";

		return $this->db->query($sql)->result();
	}

	public function registrarEgreso()
	{
		$data=array
		(
			'fecha'				=> $this->input->post('txtFecha'),
			'fechaPago'			=> $this->input->post('txtFechaPago'),
			'concepto'			=> $this->input->post('txtConcepto'),
			'importe'			=> $this->input->post('txtImporte'),
			'idEscenario'		=> $this->input->post('selectEscenarios'),
			'fechaRegistro'		=> $this->fecha,
			'idUsuario'			=> $this->idUsuario,
		);
		
		$this->db->insert('sie_proyeccion_egresos', $data);
		
		return $this->db->affected_rows()==1?array('1'):array('0');
	}

	public function editarEgreso()
	{
		$data=array
		(
			'fecha'				=> $this->input->post('txtFecha'),
			'fechaPago'			=> $this->input->post('txtFechaPago'),
			'concepto'			=> $this->input->post('txtConcepto'),
			'importe'			=> $this->input->post('txtImporte'),
			'idEscenario'		=> $this->input->post('selectEscenarios'),
		);
		
		$this->db->where('idEgreso', $this->input->post('txtIdEgreso'));
		$this->db->update('sie_proyeccion_egresos', $data);
		
		return $this->db->affected_rows()==1?array('1'):array('0');
	}
	
	public function borrarEgreso($idEgreso)
	{
		$this->db->where('idEgreso', $idEgreso);
		$this->db->delete('sie_proyeccion_egresos');
		
		return $this->db->affected_rows()==1?array('1'):array('0');
	}
	
	
	public function definirEgresoPagado($idEgreso)
	{
		$this->db->trans_start();
		
		$efectivo			= round($this->input->post('txtEfectivo'),decimales);
		$cuentas			= round($this->input->post('txtCuentas'),decimales);
		$paypal				= round($this->input->post('txtPaypal'),decimales);

		$data=array
		(
			#'efectivo'		=> $efectivo,
			##'cuentas'		=> $cuentas,
			'paypal'		=> $paypal,
			'pagado'		=> '1',
			
			'importe'		=> $this->input->post('txtTotalPagadoEgreso'),
		);
		
		$this->db->where('idEgreso', $this->input->post('txtIdEgreso'));
		$this->db->update('sie_proyeccion_egresos',$data);
		
		//ACTUALIZAR LA INFORMACIÓN FINANCIERA DE LA TABLA
		
		$cuentas				= $this->configuracion->obtenerCuentasSie();
		
		foreach($cuentas as $row)
		{
			$data=array
			(
				'saldoManual'	=> $row->saldoManual-trim($this->input->post('txtCuentas'.$row->idCuenta)),
			);
			
			$this->db->where('idCuenta',$row->idCuenta);
			$this->db->update('cuentas',$data);
		}
		
		
		$financiera			= $this->sie->obtenerFinanciera();
		
		$data=array
		(
			'efectivo'		=> $financiera->efectivo-$efectivo,
			/*'cuentas'		=> $financiera->cuentas-$cuentas,
			'paypal'		=> $financiera->paypal-$paypal,*/
		);
		
		$this->db->where('idFinanciera', 1);
		$this->db->update('sie_financiera',$data);
		
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
			
			return array('1','');
		}
	}
	//05-AGOSTO-2019
	/*public function definirEgresoPagado($idEgreso)
	{
		$this->db->trans_start();
		
		$efectivo			= round($this->input->post('txtEfectivo'),decimales);
		$cuentas			= round($this->input->post('txtCuentas'),decimales);
		$paypal				= round($this->input->post('txtPaypal'),decimales);

		$data=array
		(
			'efectivo'		=> $efectivo,
			'cuentas'		=> $cuentas,
			'paypal'		=> $paypal,
			'pagado'		=> '1',
			
			'importe'		=> $efectivo+$cuentas+$paypal,
		);
		
		$this->db->where('idEgreso', $this->input->post('txtIdEgreso'));
		$this->db->update('sie_proyeccion_egresos',$data);
		
		//ACTUALIZAR LA INFORMACIÓN FINANCIERA DE LA TABLA
		$financiera			= $this->sie->obtenerFinanciera();
		
		$data=array
		(
			'efectivo'		=> $financiera->efectivo-$efectivo,
			'cuentas'		=> $financiera->cuentas-$cuentas,
			'paypal'		=> $financiera->paypal-$paypal,
		);
		
		$this->db->where('idFinanciera', 1);
		$this->db->update('sie_financiera',$data);
		
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
			
			return array('1','');
		}
	}*/
	
	public function editarFechaPago($idEgreso)
	{
		$this->db->where('idEgreso', $idEgreso);
		$this->db->update('sie_proyeccion_egresos',array('fechaPago'=>$this->input->post('fechaPago')));
		
		return $this->db->affected_rows()==1?array('1'):array('0');
	}
	
	public function obtenerEgresosMes($mes,$anio)
	{
		$sql =" (select importe, concepto, fechaPago as fechaPago
		from sie_proyeccion_egresos
		where month(fechaPago)='$mes'
		and  year(fechaPago)='$anio'
		group by day(fechaPago) )
		
		union 
		
		 (select pago as importe, fuente as concepto, fechaPago as fechaPago
		from sie_creditos
		where month(fechaPago)='$mes'
		and  year(fechaPago)='$anio'
		group by day(fechaPago)) 
		 
		 order by fechaPago asc";

		return $this->db->query($sql)->result();
	}
	
	public function sumarEgresosDia($fecha)
	{
		$sql =" select coalesce(sum(importe),0) as importe
		from sie_proyeccion_egresos
		where fechaPago='$fecha' 
		and pagado='0' ";

		return $this->db->query($sql)->row()->importe;
	}
	
	public function obtenerEgresosDia($fecha)
	{
		$sql =" select importe, concepto, pagado
		from sie_proyeccion_egresos
		where fechaPago='$fecha'  ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerEscenariosEgresos()
	{
		$sql =" select * from sie_proyeccion_egresos_escenarios   ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerEscenariosIngresos()
	{
		$sql =" select * from sie_proyeccion_ingresos_escenarios   ";

		return $this->db->query($sql)->result();
	}
}
?>
