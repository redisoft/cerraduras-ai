<?php
class Cuentas_modelo extends CI_Model
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
	
	//CUENTAS PARA LOS CLIENTES
	public function obtenerCuentasCliente($idCliente)
	{
		$sql=" select a.*, b.cuenta, b.CLABE, c.nombre  as banco
		from clientes_cuentas as a
		inner join cuentas as b
		on a.idCuenta=b.id
		inner join bancos as c
		on c.id=b.banco_id
		where a.idCliente='$idCliente' ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarCuentaCliente()
	{
		$data=array
		(
			'idCuenta'			=> $this->input->post('idCuenta'),
			'idCliente'			=> $this->input->post('idCliente'),
		);
		
		$this->db->insert('clientes_cuentas', $data);
		
		return $this->db->affected_rows()==1?'1':'0';
	}
	
	public function obtenerCuentaCliente($idRelacion)
	{
		$sql=" select * from clientes_cuentas
		where idRelacion='$idRelacion' ";

		return $this->db->query($sql)->row();
	}
	
	public function borrarCuentaCliente($idRelacion)
	{
		$this->db->where('idRelacion', $idRelacion);
		$this->db->delete('clientes_cuentas');
		
		return $this->db->affected_rows()==1?'1':'0';
	}
	
	public function obtenerListaCuentasCliente($idCliente=0)
    {
		$sql="select a.nombre, a.id, b.cuenta, 
		b.CLABE, b.id as idCuenta,
		c.nombre as divisa, c.clave, c.idDivisa
		from bancos as a 
		inner join
		cuentas as b
		on a.id=b.banco_id
		inner join divisas as c
		on b.idDivisa=c.idDivisa
		where entrada='1' ";
		
		$sql.=" and (select count(d.idRelacion) from clientes_cuentas as d where d.idCuenta=b.id and d.idCliente='$idCliente') =0 ";
		
		$sql.=" order by a.nombre asc";
			  
		return $this->db->query($sql)->result();
    }
	
	public function obtenerBancosCliente($idCliente=0)
    {
		$sql=" select a.*, b.cuenta, b.CLABE, c.nombre  as banco, c.id
		from clientes_cuentas as a
		inner join cuentas as b
		on a.idCuenta=b.id
		inner join bancos as c
		on c.id=b.banco_id
		where a.idCliente='$idCliente'
		group by c.id ";

		return $this->db->query($sql)->result();
    }
	
	//CUENTAS PARA LOS PROVEEDOR
	public function obtenerCuentasProveedor($idProveedor)
	{
		$sql=" select a.*, b.cuenta, b.CLABE, c.nombre  as banco
		from proveedores_cuentas as a
		inner join cuentas as b
		on a.idCuenta=b.id
		inner join bancos as c
		on c.id=b.banco_id
		where a.idProveedor='$idProveedor' ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarCuentaProveedor()
	{
		$data=array
		(
			'idCuenta'			=> $this->input->post('idCuenta'),
			'idProveedor'		=> $this->input->post('idProveedor'),
		);
		
		$this->db->insert('proveedores_cuentas', $data);
		
		return $this->db->affected_rows()==1?'1':'0';
	}

	public function borrarCuentaProveedor($idRelacion)
	{
		$this->db->where('idRelacion', $idRelacion);
		$this->db->delete('proveedores_cuentas');
		
		return $this->db->affected_rows()==1?'1':'0';
	}
	
	public function obtenerListaCuentasProveedor($idProveedor=0)
    {
		$sql="select a.nombre, a.id, b.cuenta, 
		b.CLABE, b.id as idCuenta,
		c.nombre as divisa, c.clave, c.idDivisa
		from bancos as a 
		inner join
		cuentas as b
		on a.id=b.banco_id
		inner join divisas as c
		on b.idDivisa=c.idDivisa
		where entrada='1' ";
		
		$sql.=" and (select count(d.idRelacion) from proveedores_cuentas as d where d.idCuenta=b.id and d.idProveedor='$idProveedor') =0 ";
		
		$sql.=" order by a.nombre asc";
			  
		return $this->db->query($sql)->result();
    }
	
	public function obtenerBancosProveedores($idProveedor=0)
    {
		$sql=" select a.*, b.cuenta, b.CLABE, c.nombre  as banco, c.id
		from proveedores_cuentas as a
		inner join cuentas as b
		on a.idCuenta=b.id
		inner join bancos as c
		on c.id=b.banco_id
		where a.idProveedor='$idProveedor'
		group by c.id ";

		return $this->db->query($sql)->result();
    }
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PARA LA CONTABILIDAD ELECTRÓNICA
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function obtenerTiposCuentas()
	{
		$sql=" select a.cuenta,
		(select min(b.codigo) from fac_cuentas as b where b.cuenta=a.cuenta) as minimo,
		(select max(b.codigo) from fac_cuentas as b where b.cuenta=a.cuenta) as maximo
		from fac_cuentas as a
		group by a.cuenta
		order by a.idCuenta asc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerTiposCuenta()
	{
		$sql=" select detalle 
		from fac_cuentas
		group by detalle
		order by detalle asc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerCuenta($idCuenta)
	{
		$sql=" select * from fac_cuentas
		where idCuenta='$idCuenta' ";

		return $this->db->query($sql)->row();
	}
	
	//MANEJO DEL CATÁLOGO
	public function contarNivel1($detalle='0')
	{
		$sql=" select count(idCuenta) as numero
		from fac_cuentas ";
		
		$sql.=$detalle!='0'?" where detalle='$detalle'":'';

		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerNivel1($numero,$limite,$detalle='0')
	{
		$sql=" select * from fac_cuentas ";

		$sql.=$detalle!='0'?" where detalle='$detalle'":'';
		
		$sql.=$numero>0?" limit $limite,$numero ":'';
		
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerSaldoInicialCuenta($idSubCuenta)
	{
		$sql="select * from fac_subcuentas_saldo
		where idSubCuenta='$idSubCuenta' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarSaldoInicial()
	{
		$idSubCuenta	= $this->input->post('idSubCuenta');
		$saldo			= $this->obtenerSaldoInicialCuenta($idSubCuenta);
		
		if($saldo!=null)
		{
			$data=array
			(
				'importe'			=> $this->input->post('saldoInicial'),
				'idSubCuenta'		=> $this->input->post('idSubCuenta'),
				'idUsuario'			=> $this->idUsuario,
				'fecha'				=> $this->fecha,
			);
			
			$this->db->where('idSubCuenta',$idSubCuenta);
			$this->db->update('fac_subcuentas_saldo',$data);
		}
		
		if($saldo==null)
		{
			$data=array
			(
				'importe'			=> $this->input->post('saldoInicial'),
				'idSubCuenta'		=> $this->input->post('idSubCuenta'),
				'idUsuario'			=> $this->idUsuario,
				'fecha'				=> $this->fecha,
			);
			
			$this->db->insert('fac_subcuentas_saldo',$data);
		}
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	//OBTENER SUBCUENTAS NIVEL 2
	public function obtenerNivel2($idCuenta)
	{
		$sql=" select a.*, 
		(select b.importe from fac_subcuentas_saldo as b where b.idSubCuenta=a.idSubCuenta) as saldoInicial
		from fac_subcuentas as a ";

		$sql .= $idCuenta>0? " where a.idCuenta='$idCuenta' ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCuentaNivel2($idSubCuenta)
	{
		$sql=" select a.*,
		(select b.importe from fac_subcuentas_saldo as b where b.idSubCuenta=a.idSubCuenta) as saldoInicial
		from fac_subcuentas as a
		where a.idSubCuenta='$idSubCuenta' ";

		return $this->db->query($sql)->row();
	}
	
	
	//------------------------------------------------------------------------------------------------//
	//NIVEL 3 DE LAS CUENTAS
	//------------------------------------------------------------------------------------------------//
	public function obtenerNivel3($idSubCuenta)
	{
		$sql=" select * from fac_subcuentas3 ";

		$sql .= " where idSubCuenta='$idSubCuenta'
		and idUsuario='$this->idUsuario'
		order by nombre asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarNivel3()
	{
		$data=array
		(
			'nombre'			=> $this->input->post('nombre'),
			'idSubCuenta'		=> $this->input->post('idSubCuenta'),
			'codigo'			=> $this->input->post('codigo'),
			'idUsuario'			=> $this->idUsuario,
		);
		
		$this->db->insert('fac_subcuentas3',$data);
		
		return $this->db->affected_rows()==1?array(0=>'1',1=>$this->input->post('nombre').'('.$this->input->post('codigo').')',2=>$this->db->insert_id()):array(0=>'0');
	}
	
	public function obtenerCuentaNivel3($idSubCuenta3)
	{
		$sql=" select a.*, b.codigo, b.nombre as nivel2,
		a.codigo as codigoAgrupador
		from fac_subcuentas3 as a
		inner join fac_subcuentas as b
		on a.idSubCuenta=b.idSubCuenta
		where idSubCuenta3='$idSubCuenta3' ";

		return $this->db->query($sql)->row();
	}
	
	public function editarNivel3()
	{
		$this->db->where('idSubCuenta3',$this->input->post('idSubCuenta3'));
		$this->db->update('fac_subcuentas3',array('nombre'=>$this->input->post('nombre'),'codigo'=> $this->input->post('codigo')));
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function comprobarNivel3($idSubCuenta)
	{
		$sql="select count(idSubCuenta) as numero
		from fac_catalogos_cuentas_detalles
		where idSubCuenta='$idSubCuenta'
		and nivel='3' ";
		
		if($this->db->query($sql)->row()->numero>0)
		{
			return 1;
		}
		
		$sql=" select count(idSubCuenta3) as numero
		from fac_subcuentas4
		where idSubCuenta3='$idSubCuenta' ";
		
		if($this->db->query($sql)->row()->numero>0)
		{
			return 1;
		}
		
		return 0;
	}
	
	public function borrarNivel3($idSubCuenta3)
	{
		if($this->comprobarNivel3($idSubCuenta3)>0)
		{
			return array(0=>'0');
		}
		
		$this->db->where('idSubCuenta3',$idSubCuenta3);
		$this->db->delete('fac_subcuentas3');
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	//------------------------------------------------------------------------------------------------//
	//NIVEL 4 DE LAS CUENTAS
	//------------------------------------------------------------------------------------------------//
	public function obtenerNivel4($idSubCuenta3)
	{
		$sql=" select * from fac_subcuentas4 ";

		$sql .= " where idSubCuenta3='$idSubCuenta3' 
		and idUsuario='$this->idUsuario'
		order by nombre asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarNivel4()
	{
		$data=array
		(
			'nombre'			=> $this->input->post('nombre'),
			'idSubCuenta3'		=> $this->input->post('idSubCuenta3'),
			'codigo'			=> $this->input->post('codigo'),
			'idUsuario'			=> $this->idUsuario,
		);
		
		$this->db->insert('fac_subcuentas4',$data);
		
		return $this->db->affected_rows()==1?array(0=>'1',1=>$this->input->post('nombre').'('.$this->input->post('codigo').')',2=>$this->db->insert_id()):array(0=>'0');
	}
	
	public function obtenerCuentaNivel4($idSubCuenta4)
	{
		$sql=" select a.*, b.nombre as nivel3,
		c.codigo, c.nombre as nivel2, 
		a.codigo as codigoAgrupador
		from fac_subcuentas4 as a
		inner join fac_subcuentas3 as b
		on a.idSubCuenta3=b.idSubCuenta3
		inner join fac_subcuentas as c
		on c.idSubCuenta=b.idSubCuenta
		where idSubCuenta4='$idSubCuenta4' ";

		return $this->db->query($sql)->row();
	}
	
	public function editarNivel4()
	{
		$this->db->where('idSubCuenta4',$this->input->post('idSubCuenta4'));
		$this->db->update('fac_subcuentas4',array('nombre'=>$this->input->post('nombre'),'codigo'=> $this->input->post('codigo')));
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function comprobarNivel4($idSubCuenta)
	{
		$sql="select count(idSubCuenta) as numero
		from fac_catalogos_cuentas_detalles
		where idSubCuenta='$idSubCuenta'
		and nivel='4' ";
		
		if($this->db->query($sql)->row()->numero>0)
		{
			return 1;
		}
		
		$sql=" select count(idSubCuenta4) as numero
		from fac_subcuentas5
		where idSubCuenta4='$idSubCuenta' ";
		
		if($this->db->query($sql)->row()->numero>0)
		{
			return 1;
		}
		
		return 0;
	}
	
	public function borrarNivel4($idSubCuenta4)
	{
		if($this->comprobarNivel4($idSubCuenta4)>0)
		{
			return array(0=>'0');
		}
		
		$this->db->where('idSubCuenta4',$idSubCuenta4);
		$this->db->delete('fac_subcuentas4');
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	//------------------------------------------------------------------------------------------------//
	//NIVEL 5 DE LAS CUENTAS
	//------------------------------------------------------------------------------------------------//
	public function obtenerNivel5($idSubCuenta4)
	{
		$sql=" select * from fac_subcuentas5 ";

		$sql .= " where idSubCuenta4='$idSubCuenta4' 
		and idUsuario='$this->idUsuario'
		order by nombre asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarNivel5()
	{
		$data=array
		(
			'nombre'			=> $this->input->post('nombre'),
			'idSubCuenta4'		=> $this->input->post('idSubCuenta4'),
			'codigo'			=> $this->input->post('codigo'),
			'idUsuario'			=> $this->idUsuario,
		);
		
		$this->db->insert('fac_subcuentas5',$data);
		
		return $this->db->affected_rows()==1?array(0=>'1',1=>$this->input->post('nombre').'('.$this->input->post('codigo').')',2=>$this->db->insert_id()):array(0=>'0');
	}
	
	public function obtenerCuentaNivel5($idSubCuenta5)
	{
		$sql=" select a.*, b.nombre as nivel4,
		c.nombre as nivel3,
		d.codigo, d.nombre as nivel2,
		a.codigo as codigoAgrupador
		from fac_subcuentas5 as a
		inner join fac_subcuentas4 as b
		on a.idSubCuenta4=b.idSubCuenta4
		inner join fac_subcuentas3 as c
		on c.idSubCuenta3=b.idSubCuenta3
		inner join fac_subcuentas as d
		on d.idSubCuenta=c.idSubCuenta
		where a.idSubCuenta5='$idSubCuenta5' ";

		return $this->db->query($sql)->row();
	}
	
	public function editarNivel5()
	{
		$this->db->where('idSubCuenta5',$this->input->post('idSubCuenta5'));
		$this->db->update('fac_subcuentas5',array('nombre'=>$this->input->post('nombre'),'codigo'=> $this->input->post('codigo')));
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function comprobarNivel5($idSubCuenta)
	{
		$sql="select count(idSubCuenta) as numero
		from fac_catalogos_cuentas_detalles
		where idSubCuenta='$idSubCuenta'
		and nivel='5' ";
		
		if($this->db->query($sql)->row()->numero>0)
		{
			return 1;
		}
		
		$sql=" select count(idSubCuenta5) as numero
		from fac_subcuentas6
		where idSubCuenta5='$idSubCuenta' ";
		
		if($this->db->query($sql)->row()->numero>0)
		{
			return 1;
		}
		
		return 0;
	}
	
	public function borrarNivel5($idSubCuenta5)
	{
		if($this->comprobarNivel5($idSubCuenta5)>0)
		{
			return array(0=>'0');
		}
		
		$this->db->where('idSubCuenta5',$idSubCuenta5);
		$this->db->delete('fac_subcuentas5');
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	//------------------------------------------------------------------------------------------------//
	//NIVEL 6 DE LAS CUENTAS
	//------------------------------------------------------------------------------------------------//
	public function obtenerNivel6($idSubCuenta5)
	{
		$sql=" select * from fac_subcuentas6 ";

		$sql .= " where idSubCuenta5='$idSubCuenta5' 
		and idUsuario='$this->idUsuario'
		order by nombre asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarNivel6()
	{
		$data=array
		(
			'nombre'			=> $this->input->post('nombre'),
			'idSubCuenta5'		=> $this->input->post('idSubCuenta5'),
			'codigo'			=> $this->input->post('codigo'),
			'idUsuario'			=> $this->idUsuario,
		);
		
		$this->db->insert('fac_subcuentas6',$data);
		
		return $this->db->affected_rows()==1?array(0=>'1',1=>$this->input->post('nombre').'('.$this->input->post('codigo').')',2=>$this->db->insert_id()):array(0=>'0');
	}
	
	public function obtenerCuentaNivel6($idSubCuenta6)
	{
		$sql=" select a.*, b.nombre as nivel5,
		c.nombre as nivel4,
		d.nombre as nivel3,
		e.codigo, e.nombre as nivel2,
		a.codigo as codigoAgrupador
		from fac_subcuentas6 as a
		inner join fac_subcuentas5 as b
		on a.idSubCuenta5=b.idSubCuenta5
		inner join fac_subcuentas4 as c
		on c.idSubCuenta4=b.idSubCuenta4
		inner join fac_subcuentas3 as d
		on c.idSubCuenta3=d.idSubCuenta3
		inner join fac_subcuentas as e
		on d.idSubCuenta=e.idSubCuenta
		where a.idSubCuenta6='$idSubCuenta6' ";

		return $this->db->query($sql)->row();
	}
	
	public function editarNivel6()
	{
		$this->db->where('idSubCuenta6',$this->input->post('idSubCuenta6'));
		$this->db->update('fac_subcuentas6',array('nombre'=>$this->input->post('nombre'),'codigo'=> $this->input->post('codigo')));
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function comprobarNivel6($idSubCuenta)
	{
		$sql="select count(idSubCuenta) as numero
		from fac_catalogos_cuentas_detalles
		where idSubCuenta='$idSubCuenta'
		and nivel='6' ";
		
		if($this->db->query($sql)->row()->numero>0)
		{
			return 1;
		}

		return 0;
	}
	
	public function borrarNivel6($idSubCuenta6)
	{
		if($this->comprobarNivel6($idSubCuenta6)>0)
		{
			return array(0=>'0');
		}
		
		$this->db->where('idSubCuenta6',$idSubCuenta6);
		$this->db->delete('fac_subcuentas6');
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	//REGISTRAR LOS NIVELES CUANDO SE IMPORTE UN EXCEL
	
	public function obtenerNivelExcel($nivel,$nombre,$codigo)
	{
		$sql=" select a.idSubCuenta".($nivel==2?'':$nivel)." as idSubCuenta from fac_subcuentas".($nivel==2?'':$nivel)." as a ";
		
		switch($nivel)
		{
			case 3:
			$sql.=" inner join fac_subcuentas as b
			on a.idSubCuenta=b.idSubCuenta
			where b.codigo='$codigo'
			and a.nombre='$nombre' ";
			break;
			
			case 4:
			$sql.=" inner join fac_subcuentas3 as b
			on a.idSubCuenta3=b.idSubCuenta3
			inner join fac_subcuentas as c
			on b.idSubCuenta=b.idSubCuenta
			where c.codigo='$codigo'
			and a.nombre='$nombre' ";
			break;
			
			case 5:
			$sql.=" inner join fac_subcuentas4 as b
			on a.idSubCuenta4=b.idSubCuenta4
			inner join fac_subcuentas3 as c
			on c.idSubCuenta3=b.idSubCuenta3
			inner join fac_subcuentas as d
			on d.idSubCuenta=c.idSubCuenta
			where d.codigo='$codigo'
			and a.nombre='$nombre' ";
			break;
			
			case 6:
			$sql.="  inner join fac_subcuentas5 as b
			on a.idSubCuenta5=b.idSubCuenta5
			inner join fac_subcuentas4 as c
			on c.idSubCuenta4=b.idSubCuenta4
			inner join fac_subcuentas3 as d
			on c.idSubCuenta3=d.idSubCuenta3
			inner join fac_subcuentas as e
			on d.idSubCuenta=e.idSubCuenta
			where e.codigo='$codigo'
			and a.nombre='$nombre' ";
			break;
		}
		
		$query	= $this->db->query($sql)->row();
		
		return $query!=null?$query->idSubCuenta:0;
		
	}
	
	public function registrarNivelExcel($nivel,$nombre,$idSubCuenta)
	{
		$data=array
		(
			'nombre'				=> $nombre,
			'idSubCuenta'.$nivel	=> $idSubCuenta,
		);
		
		$this->db->insert('subcuentas'.$nivel,$data);
		
		return $this->db->affected_rows()==1?$this->db->insert_id():0;
	}
	
	//TEXTIL ARTE
	public function obtenerCatalogoMesTextil($idGasto=0)
	{
		$sql=" select a.*, b.fecha
		from fac_catalogos_cuentas_detalles as a
		inner join fac_catalogos_cuentas as b
		on a.idCatalogo=b.idCatalogo 
		where b.idCatalogo>0 ";
		
		$sql.=$idGasto>0?" and (select count(c.idRelacion) from fac_tipos_cuentas as c where c.idCuentaCatalogo=a.idCuentaCatalogo and c.idGasto='$idGasto')=0":'';
		
		$sql.=" group by a.idCuentaCatalogo, b.idCatalogo
		order by b.fechaRegistro desc,
		idCuentaCatalogo asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCuentasAsociadas($idGasto)
	{
		$sql=" select a.*, b.fecha, c.idRelacion
		from fac_catalogos_cuentas_detalles as a
		inner join fac_catalogos_cuentas as b
		on a.idCatalogo=b.idCatalogo 
		inner join fac_tipos_cuentas as c
		on c.idCuentaCatalogo=a.idCuentaCatalogo 
		where c.idGasto='$idGasto'
		group by a.idCuentaCatalogo, b.idCatalogo ";
		
		return $this->db->query($sql)->result();
	}
	
	public function asociarCuentaGasto()
	{
		$data=array
		(
			'idGasto'				=> $this->input->post('idGasto'),
			'idCuentaCatalogo'		=> $this->input->post('idCuentaCatalogo'),
		);
		
		$this->db->insert('fac_tipos_cuentas',$data);
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function borrarCuentaGasto()
	{
		$this->db->where('idRelacion',$this->input->post('idRelacion'));
		$this->db->delete('fac_tipos_cuentas');
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	//OBTENER CUENTAS CONTABLES
	public function obtenerCuentasContables($criterio='')
    {
		$sql=" select a.*, concat('Referencia: ', a.numeroCuenta,', Descripción: ', a.descripcion) as value
		from fac_catalogos_cuentas_detalles as a
		where a.idCuentaCatalogo>0
		and (a.numeroCuenta like '%$criterio%' or a.descripcion like '%$criterio%' )
		order by a.descripcion asc ";

		return $this->db->query($sql)->result_array();
    }
	
	public function obtenerCuentasContablesFiltro($criterio='',$filtro='numeroCuenta')
    {
		$sql=" select a.*, concat('Referencia: ', a.numeroCuenta,', Descripción: ', a.descripcion) as value
		from fac_catalogos_cuentas_detalles as a
		where a.idCuentaCatalogo>0
		".($filtro=='numeroCuenta'?"and a.numeroCuenta like '%$criterio%' ":"and a.descripcion like '%$criterio%' ")."
		order by a.descripcion asc
		limit 20 ";

		return $this->db->query($sql)->result_array();
    }
	
	public function sugerirCodigoAgrupador($codigo)
	{
		$codigo	= str_replace('.','-',$codigo);
		
		return $codigo.'-';
	}
}
