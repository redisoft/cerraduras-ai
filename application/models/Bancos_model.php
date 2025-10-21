<?php

class Bancos_model extends CI_Model
{
    protected $_fecha_actual;
    protected $_table;
    protected $_user_id;
	protected $idLicencia;

    function __construct()
	{
		parent::__construct();
		$this->config->load('datatables',TRUE);
		
		$this->_table 			= $this->config->item('datatables');
		$this->_fecha_actual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_user_id 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
    }

    public function getAll()
    {
		$sql="select * from bancos
		where idLicencia='$this->idLicencia' 
		order by nombre asc ";
		
		$query=$this->db->query($sql);
		
        return ($query->num_rows() > 0)? $query->result_array() : NULL;
    }
	
	public function comprobarBancoNombre($nombre)
	{
		$sql ="select idBanco
		from  bancos 
		where nombre='$nombre' ";

		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
    public function registrarBanco()
    {
		if(!$this->comprobarBancoNombre($this->input->post('txtNombre')))
		{
			return array('0',registroDuplicado);
		}
		
        $data = array
		(
			'nombre' 		=> $this->input->post('txtNombre'),
            'modificado'	=> $this->_fecha_actual,
			'idLicencia' 	=> $this->idLicencia,
			'idCliente' 	=> $this->input->post('txtIdCliente'),
		);

       /* $imagen 			= $_FILES['txtLogotipo']['name'];
		
		if(strlen($imagen)>2)
		{
			$data['logotipo'] = $imagen;
		}*/
		
		$data	= procesarArreglo($data);
        $this->db->insert('bancos', $data);
		$idBanco	=$this->db->insert_id();

		#move_uploaded_file($_FILES['txtLogotipo']['tmp_name'], carpetaBancos . basename($idBanco."_".$imagen));
		
		$this->configuracion->registrarBitacora('Registrar banco','Configuración - Bancos',$data['nombre']); //Registrar bitácora
	
        return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
    }

    public function editarBanco()
    {
        $data = array
		(
			'nombre' 		=> $this->input->post('nombre'),
            'modificado' 	=> $this->_fecha_actual
		);

       /* $imagen 			= $_FILES['txtLogotipo']['name'];
		
		if(strlen($imagen)>2)
		{
			$data['logotipo'] = $imagen;
		}*/
		
		$data	= procesarArreglo($data);
		$this->db->where('idBanco', $this->input->post('idBanco'));
        $this->db->update('bancos', $data);
		
		$this->configuracion->registrarBitacora('Editar banco','Configuración - Bancos',$data['nombre']); //Registrar bitácora

		#move_uploaded_file($_FILES['txtLogotipo']['tmp_name'], carpetaBancos . basename($this->input->post('txtIdBanco')."_".$imagen));

         return $this->db->affected_rows()>=1?"1":"0";
    }
	
	public function comprobarBanco($idBanco)
	{
		$sql="select * from cuentas
		where idBanco='$idBanco'";

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerDetalleBanco($idBanco)
	{
		$sql="select nombre
		from bancos
		where idBanco='$idBanco' ";
		
		$banco	= $this->db->query($sql)->row();
		
		return $banco!=null?$banco->nombre:'';
	}
	
    public function borrarBanco($idBanco)
    {
		if($this->comprobarBanco($idBanco)>0)
		{
			return "0";	
		}
		
		$this->configuracion->registrarBitacora('Borrar banco','Configuración - Bancos',$this->obtenerDetalleBanco($idBanco)); //Registrar bitácora
		
        $this->db->where('idBanco', $idBanco);
        $this->db->delete('bancos');

       return $this->db->affected_rows()>=1?"1":"0";
    }

    public function getById($banco_id)
    {
        $this->db->where('id', $banco_id);
        $query = $this->db->get($this->_table['bancos']);

        return ($query->num_rows() > 0)? $query->row_array() : NULL;
    }
	
	/*public function obtenerCuentas()
    {
		$sql="select a.nombre, a.id, b.cuenta, 
		b.CLABE, b.id as idCuenta 
		from bancos AS a 
		inner join
		cuentas AS b
		on (a.id=b.banco_id)
		where entrada='1'
		and a.idLicencia='$this->idLicencia'
		order by a.nombre asc";
			  
		$query=$this->db->query($sql);

        return ($query->num_rows() > 0)? $query->result() : NULL;
    }*/
	
	public function obtenerCuenta($idCuenta)
    {
		$sql="select a.*, b.nombre as banco,
		b.idCliente,
		(select concat(c.descripcion,'(',c.numeroCuenta,')') from fac_catalogos_cuentas_detalles as c where c.idCuentaCatalogo=a.idCuentaCatalogo) cuentaContable
		from cuentas as a 
		inner join bancos as b
		on a.idBanco=b.idBanco
		where idCuenta='$idCuenta' ";

		return $this->db->query($sql)->row();
    }
	
	public function obtenerCuentaProveedor($idCuenta)
    {
		$sql="select * from proveedores_cuentas
		where idCuenta='$idCuenta' ";

		return $this->db->query($sql)->row();
    }
	
	public function obtenerCuentas($idCuenta=0)
    {
		$sql="select a.nombre, a.idBanco, 
		b.cuenta, b.clabe, b.idCuenta, b.tarjetaCredito
		".(sistemaActivo=='IEXE'?', b.dashboard':'')."
		from bancos as a 
		inner join cuentas as b
		on a.idBanco=b.idBanco ";
		#where entrada='1' 
		$sql.=$idCuenta!=0?" and b.idCuenta<>$idCuenta":"";
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=" and a.idBanco>1 ";
		}
		
		#$sql.=" order by a.nombre asc";
		$sql.=" order by b.idCuenta asc";
			  
		return $this->db->query($sql)->result();
    }
	
	public function obtenerCuentasAdministracion($idCuenta=0)
    {
		$sql="select a.nombre, a.idBanco, b.saldoInicial, b.tarjetaCredito,
		b.cuenta, b.clabe, b.idCuenta, b.reportes, 
		(select c.empresa from clientes as c where b.idCliente=c.idCliente) as cliente,
		(select c.nombre from configuracion_emisores as c where b.idEmisor=c.idEmisor) as emisor
		
		".(sistemaActivo=='IEXE'?', b.dashboard':'')."
		
		from bancos as a 
		inner join cuentas as b
		on a.idBanco=b.idBanco ";
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=" and b.idCuenta>0 ";
		}
		
		$sql.=$idCuenta!=0?" and b.idCuenta<>$idCuenta":"";
		
		$sql.=" order by a.nombre asc";
			  
		return $this->db->query($sql)->result();
    }
	
	public function obtenerCuentasBanco($idBanco,$idCliente=0)
    {
		$sql="select a.nombre, a.idBanco, b.cuenta, 
		b.clabe, b.idCuenta, b.tarjetaCredito
		from bancos as a 
		inner join
		cuentas as b
		on a.idBanco=b.idBanco
		where a.idBanco='$idBanco' ";
		
		$sql.=$idCliente>0?" and  b.idCliente='$idCliente' ":'';
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=" and a.idBanco>1 ";
		}

		return $query=$this->db->query($sql)->result();
    } 

	public function registrarCuenta()
	{
		$idCuentaCatalogo		= $this->input->post('idCuentaCatalogo');
		$saldoInicial			= $this->input->post('saldoInicial');
		$dashboard				= $this->input->post('dashboard');
		
		$data= array
		(
			'idBanco' 			=> $this->input->post('idBanco'),
			'cuenta' 			=> $this->input->post('cuenta'),
			'clabe' 			=> $this->input->post('clabe'),
			'idEmisor' 			=> $this->input->post('idEmisor'),
			'idCliente' 		=> $this->input->post('idCliente'),
			'reportes' 			=> $this->input->post('reportes'),
			'idCuentaCatalogo' 	=> $idCuentaCatalogo,
			'saldoInicial' 		=> $saldoInicial,
			'modificado'		=> $this->_fecha_actual,
			'entrada' 			=> '1',
			
			'tarjetaCredito' 	=> $this->input->post('tarjetaCredito'),
			#'noDisponible' 		=> $this->input->post('noDisponible'),
			#'sie' 				=> $this->input->post('sie'),
			#'dashboard' 		=> $dashboard,
		);
		
		if(sistemaActivo=='IEXE')
		{
			$data['dashboard']	= $dashboard;
			
			if($dashboard=='1')
			{
				$this->db->where('idCuenta>', 0); 
				$this->db->update('cuentas', array('dashboard'=>'0')); 
			}
		
		}

		$data	= procesarArreglo($data);
		$this->db->insert('cuentas',$data);
		
		$this->configuracion->registrarBitacora('Registrar cuenta','Configuración - Cuentas',$data['cuenta'].', '.$this->obtenerDetalleBanco($this->input->post('idBanco'))); //Registrar bitácora
		
		if($idCuentaCatalogo>0 and $saldoInicial>0)
		{
			$saldo	= $this->sumarSaldosCuentas($idCuentaCatalogo);
			
			$this->db->where('idCuentaCatalogo', $idCuentaCatalogo); 
			$this->db->update('fac_catalogos_cuentas_detalles', array('saldo'=>$saldo)); 
		}
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function sumarSaldosCuentas($idCuentaCatalogo)
	{
		$sql=" select coalesce(sum(saldoInicial),0) as saldoInicial
		from cuentas
		where idCuentaCatalogo='$idCuentaCatalogo' ";
		
		return $this->db->query($sql)->row()->saldoInicial;
	}
	
	public function editarCuenta()
	{
		$idCuentaCatalogo		= $this->input->post('idCuentaCatalogo');
		$saldoInicial			= $this->input->post('saldoInicial');
		$dashboard				= $this->input->post('dashboard');
		
		$data= array
		(
			'idBanco' 			=> $this->input->post('idBanco'),
			'cuenta' 			=> $this->input->post('cuenta'),
			'clabe' 			=> $this->input->post('clabe'),
			'idEmisor' 			=> $this->input->post('idEmisor'),
			'reportes' 			=> $this->input->post('reportes'),
			'idCuentaCatalogo' 	=> $idCuentaCatalogo,
			'saldoInicial' 		=> $saldoInicial,
			'modificado'		=> $this->_fecha_actual,
			'tarjetaCredito' 	=> $this->input->post('tarjetaCredito'),
			
			#'noDisponible' 		=> $this->input->post('noDisponible'),
			#'sie' 				=> $this->input->post('sie'),
			
			#'dashboard' 		=> $dashboard,
		);
		
		if(sistemaActivo=='IEXE')
		{
			$data['dashboard']	= $dashboard;
			
			if($dashboard=='1')
			{
				$this->db->where('idCuenta>', 0); 
				$this->db->update('cuentas', array('dashboard'=>'0')); 
			}
		}
		
		$data	= procesarArreglo($data);
		$this->db->where('idCuenta',$this->input->post('idCuenta'));
		$this->db->update('cuentas',$data);
		
		$this->configuracion->registrarBitacora('Editar cuenta','Configuración - Cuentas',$data['cuenta'].', '.$this->obtenerDetalleBanco($this->input->post('idBanco'))); //Registrar bitácora
		
		if($idCuentaCatalogo>0 and $saldoInicial>0)
		{
			$saldo	= $this->sumarSaldosCuentas($idCuentaCatalogo);
			
			$this->db->where('idCuentaCatalogo', $idCuentaCatalogo); 
			$this->db->update('fac_catalogos_cuentas_detalles', array('saldo'=>$saldo)); 
		}
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function comprobarCuenta($idCuenta)
	{
		$sql="select * from catalogos_ingresos
		where idCuenta='$idCuenta'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select * from catalogos_egresos
		where idCuenta='$idCuenta'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
	}
	
	public function obtenerCuentaDetalle($idCuenta)
	{
		$sql="select a.nombre, a.idBanco, b.cuenta	
		from bancos as a 
		inner join cuentas as b
		on a.idBanco=b.idBanco
		where b.idCuenta='$idCuenta' ";
		
		$cuenta	= $this->db->query($sql)->row();
		
		return $cuenta!=null?array($cuenta->cuenta,$cuenta->nombre):array('Sin detalles de cuenta','');
	}
	
	public function borrarCuenta($idCuenta)
	{
		if($this->comprobarCuenta($idCuenta)>0)
		{
			return "0";	
		}
		
		$cuenta=$this->obtenerCuentaDetalle($idCuenta);
		
		$this->configuracion->registrarBitacora('Borrar cuenta','Configuración - Cuentas',$cuenta[0].', '.$cuenta[1]); //Registrar bitácora
		
		$this->db->where('idCuenta',$idCuenta);
		$this->db->delete('cuentas');
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function contarBancos()
    {
        $sql="select count(idBanco) as numero 
		from bancos ";
			  
		return $this->db->query($sql)->row()->numero;
    }
	
	public function obtenerListaBancos($numero,$limite)
    {
        $sql="select * from bancos  ";
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=" where idBanco>1 ";
		}
		
		$sql.=" order by idBanco asc ";
		
		$sql.=$numero>0?" limit $limite,$numero ":'';
			  
		return $this->db->query($sql)->result();
    }
	
	public function obtenerBancos($cuentas=0)
    {
        $sql=" select a.* 
		from bancos as a
		where a.activo='1' ";
		
		$sql.=$cuentas==1?" and (select count(b.idCuenta) from cuentas as b where b.idBanco=a.idBanco ) >0":'';
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=" and a.idBanco>1 ";
		}
		
		$sql.=" order by a.idBanco asc ";
			  
		return $this->db->query($sql)->result();
    }
	
	public function obtenerBancosCliente($idCliente)
    {
        $sql="select * from bancos 
		where idCliente='$idCliente'
		order by nombre asc ";
			  
		return $this->db->query($sql)->result();
    }
	
	public function obtenerClienteBanco($idCliente)
	{
		$sql="select empresa
		from clientes
		where idCliente='$idCliente'";
		
		$cliente=$this->db->query($sql)->row();
		
		return $cliente!=null?$cliente->empresa:'';
	}
	
	public function obtenerBanco($idBanco)
    {
        $sql="select * from bancos 
		where idBanco='$idBanco' ";
			  
		return $this->db->query($sql)->row();
    }
	
	public function obtenerIngresosCuenta($idCuenta)
	{
		$sql="select sum(pago) as ingreso
		from catalogos_ingresos
		where idCuenta='$idCuenta'
		and idLicencia='$this->idLicencia' ";
		
		$ingreso=$this->db->query($sql)->row()->ingreso;
		
		return $ingreso!=null?$ingreso:0;
	}
	
	public function obtenerEgresosCuenta($idCuenta)
	{
		$sql="select sum(pago) as egreso
		from catalogos_egresos
		where idCuenta='$idCuenta'
		and idLicencia='$this->idLicencia'  ";
		
		$egreso=$this->db->query($sql)->row()->egreso;
		
		return $egreso!=null?$egreso:0;
	}
	
	public function obtenerMontoEgreso($idEgreso)
	{
		$sql="select pago
		from catalogos_egresos
		where idEgreso='$idEgreso'";
		
		return $this->db->query($sql)->row()->pago;
	}
	
	public function obtenerSumaCaja($idEgreso)
	{
		$sql="select sum(importe) as caja
		from catalogos_caja
		where idEgreso='$idEgreso'";
		
		$caja=$this->db->query($sql)->row()->caja;
		
		return $caja!=null?$caja:0;
	}
	
	//OBTENER EL SALDO DE LAS CUENTAS INICIALES
	public function obtenerIngresosCuentaInicial($idCuenta,$mes,$anio)
	{
		$sql="select sum(pago) as ingreso
		from catalogos_ingresos
		where idCuenta='$idCuenta'
		and (month(fecha)<'$mes'
		and year(fecha)<='$anio')
		and idLicencia='$this->idLicencia' ";

		$ingreso=$this->db->query($sql)->row()->ingreso;

		return $ingreso!=null?$ingreso:0;
	}
	
	public function obtenerEgresosCuentaInicial($idCuenta,$mes,$anio)
	{
		$sql="select sum(pago) as egreso
		from catalogos_egresos
		where idCuenta='$idCuenta'
		and (month(fecha)<'$mes'
		and year(fecha)<='$anio')
		and idLicencia='$this->idLicencia'  ";

		$egreso=$this->db->query($sql)->row()->egreso;
		
		return $egreso!=null?$egreso:0;
	}
	
	//OBTENER EL SALDO DE LAS CUENTAS FINALES
	public function obtenerIngresosCuentaFinal($idCuenta,$mes,$anio)
	{
		$sql="select sum(pago) as ingreso
		from catalogos_ingresos
		where idCuenta='$idCuenta'
		and (month(fecha)<='$mes'
		and year(fecha)<='$anio')
		and idLicencia='$this->idLicencia'  ";
		
		$ingreso=$this->db->query($sql)->row()->ingreso;

		return $ingreso!=null?$ingreso:0;
	}
	
	public function obtenerEgresosCuentaFinal($idCuenta,$mes,$anio)
	{
		$sql="select sum(pago) as egreso
		from catalogos_egresos
		where idCuenta='$idCuenta'
		and (month(fecha)<='$mes'
		and year(fecha)<='$anio')
		and idLicencia='$this->idLicencia'   ";
		
		$egreso=$this->db->query($sql)->row()->egreso;

		return $egreso!=null?$egreso:0;
	}
}
?>
