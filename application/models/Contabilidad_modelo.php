<?php
class Contabilidad_modelo extends CI_Model 
{
	protected $fecha;
	protected $idUsuario;
	protected $rfc;
	protected $saldoInicial;
	protected $debe;
	protected $haber;
	protected $idLicencia;
	
    //declaramos el constructor de la clase
    function __construct() 
	{
		parent::__construct();
		
		$this->fecha		= date("Y-m-d H:i:s");
		#$this->idUsuario	= $this->session->userdata('idUsuarioContabilidad');
		$this->idUsuario	= '1';
		$this->rfc			= $this->session->userdata('rfcUsuario');
		$this->idLicencia	= $this->session->userdata('idLicencia');
		
		$this->saldoInicial	= 0;
		$this->debe			= 0;
		$this->haber		= 0;
    }
	
	//CUENTAS Y SUBCUENTAS
	public function obtenerCuentas($cuenta='todos')
	{
		$sql="select * from fac_cuentas
		where idCuenta>0 ";
		$sql.=$cuenta!='todos'?" and cuenta='$cuenta' ":'';
		$sql.=" order by idCuenta asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerSubCuenta($idSubCuenta)
	{
		$sql=" select a.*, b.cuenta 
		from fac_subcuentas as a
		inner join fac_cuentas as b
		on a.idCuenta=b.idCuenta
		where idSubCuenta='$idSubCuenta' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerSubCuentas($idCuenta)
	{
		$sql=" select * from fac_subcuentas
		where idCuenta='$idCuenta' ";
		
		return $this->db->query($sql)->result();
	}
	
	//MANEJO DEL CATÁLOGO
	
	public function contarCatalogo($inicio,$fin)
	{
		$sql=" select count(idCatalogo) as numero
		from fac_catalogos_cuentas
		where idUsuario='$this->idUsuario' ";
		
		$sql.=" and fecha between '$inicio-01' and '$fin-01' ";
		#$sql.=$fecha!='fecha'?" and fecha='$fecha-01'":' ';
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerCatalogo($numero,$limite,$inicio,$fin)
	{
		$sql=" select a.*, 
		(select count(idCuentaCatalogo) from fac_catalogos_cuentas_detalles as b where a.idCatalogo=b.idCatalogo) as numeroCuentas
		from fac_catalogos_cuentas as a
		where a.idUsuario='$this->idUsuario' ";
		
		$sql.=" and fecha between '$inicio-01' and '$fin-01' ";
		#$sql.=$fecha!='fecha'?" and a.fecha='$fecha-01'":' ';
		
		$sql .= " order by a.fecha desc ";
		$sql .=$numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function comprobarCatalogo($fecha)
	{
		$sql="select * from fac_catalogos_cuentas
		where fecha='$fecha' ";
		
		#echo $sql;
		return $this->db->query($sql)->num_rows();
	}
	
	public function registrarCatalogo($copiar='0')
	{
		if($this->comprobarCatalogo($this->input->post('txtFechaCatalogo').'-01')>0)
		{
			return array(0=>'0');
		}
		
		$catalogo	= null;
		
		if($copiar=='1')
		{
			$catalogo	= $this->obtenerUltimoCatalogo();
		}
		
		$data=array
		(
			'rfc'			=>$this->input->post('txtRfc'),
			'fecha'			=>$this->input->post('txtFechaCatalogo').'-01',
			'fechaRegistro'	=>$this->fecha,
			'idUsuario'		=>$this->idUsuario,
			'version'		=>version,
		);
		
		$this->db->insert('fac_catalogos_cuentas',$data);
		$idCatalogo	= $this->db->insert_id();
		
		if($copiar=='1')
		{		
			$this->copiarCatalogoMensual($idCatalogo,$catalogo);
		}
		
		return $idCatalogo>0?array(0=>'1'):array(0=>'0');
	}
	
	public function obtenerUltimoCatalogo()
	{
		$sql=" select a.* 
		from fac_catalogos_cuentas_detalles as a
		inner join fac_catalogos_cuentas as b
		on a.idCatalogo=b.idCatalogo
		where  b.fecha=(select c.fecha from fac_catalogos_cuentas as c order by c.fecha desc limit 1 ) ";

		return $this->db->query($sql)->result();
	}
	
	public function copiarCatalogoMensual($idCatalogo,$catalogo)
	{
		foreach($catalogo as $row)
		{
			$data=array
			(
				'codigoAgrupador'	=> $row->codigoAgrupador,
				'numeroCuenta'		=> $row->numeroCuenta,
				'descripcion'		=> $row->descripcion,
				'nivel'				=> $row->nivel,
				'naturaleza'		=> $row->naturaleza,
				'idCatalogo'		=> $idCatalogo,
				'idSubCuenta'		=> $row->idSubCuenta,
				'idCuenta'			=> $row->idCuenta,
				'subCuenta'			=> $row->subCuenta,
			);
			
			$this->db->insert('fac_catalogos_cuentas_detalles',$data);
		}
	}
	
	public function obtenerCatalogoEditar($idCatalogo)
	{
		$sql=" select a.*,
		(select count(b.idCuentaCatalogo) from fac_catalogos_cuentas_detalles as b where a.idCatalogo=b.idCatalogo) as numeroCuentas 
		from fac_catalogos_cuentas as a
		where a.idCatalogo='$idCatalogo' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function editarCatalogo()
	{
		$data=array
		(
			'fecha'			=>$this->input->post('txtFechaCatalogo').'-01',
		);
		
		$this->db->where('idCatalogo',$this->input->post('txtIdCatalogo'));
		$this->db->update('fac_catalogos_cuentas',$data);
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function borrarCatalogo($idCatalogo)
	{
		$this->db->trans_start();
		
		$sql="select idCuentaCatalogo
		from fac_catalogos_cuentas_detalles
		where idCatalogo='$idCatalogo' ";
		
		//DE MOMENTO ESTA COMENTADO ESTO PARA TEXTIL ARTE
		/*$catalogo=$this->db->query($sql)->result();
		
		foreach($catalogo as $row)		
		{
			$this->db->where('idCuentaCatalogo',$row->idCuentaCatalogo);
			$this->db->delete('balanza_detalles');
			
			$this->borrarTransaccionesCuenta($row->idCuentaCatalogo);
		}*/
		
		#BORRAR LOS DETALLES DEL CATÁLOGO
		$this->db->where('idCatalogo',$idCatalogo);
		$this->db->delete('fac_catalogos_cuentas_detalles');
		
		#BORRAR EL CATÁLOGO
		$this->db->where('idCatalogo',$idCatalogo);
		$this->db->where('idUsuario',$this->idUsuario);
		$this->db->delete('fac_catalogos_cuentas');
		
		#
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	//CUENTAS DEL CATÁLOGO
	
	public function obtenerCuentaNivel($nivel,$idCuenta)
	{
		switch($nivel)
		{
			case 1:
			$sql=" select idCuenta, nombre, codigo, saldoInicial
			from fac_cuentas
			where idCuenta='$idCuenta' ";
			break;
			
			case 2:
			$sql=" select a.idSubCuenta as idCuenta, a.nombre, a.codigo, 
			(select b.importe from fac_subcuentas_saldo as b
			where b.idSubCuenta=a.idSubCuenta
			and b.idUsuario='$this->idUsuario') as saldoInicial
			from fac_subcuentas as a
			where a.idSubCuenta='$idCuenta' ";
			break;
			
			default:
			$sql=" select idSubCuenta".$nivel." as idCuenta, nombre, codigo, '0' as saldoInicial
			from fac_subcuentas".$nivel."
			where idSubCuenta".$nivel."='$idCuenta'
			and idUsuario='$this->idUsuario' ";
			break;
		}
		
		#echo $sql;
		
		return $this->db->query($sql)->row();
	}
	
	public function contarCuentasCatalogo($criterio,$tipo='todos',$inicio,$fin)
	{
		$sql=" select count(a.idCuentaCatalogo) as numero
		from fac_catalogos_cuentas_detalles  as a
		where a.idCuentaCatalogo>0
		and a.idCuentaPadre=0
		and a.activo='1'  ";
		
		$sql.=strlen($criterio)>0?"and (a.numeroCuenta like '%$criterio%'
		or a.descripcion like '%$criterio%' 
		or a.codigoAgrupador like '%$criterio%' 
		or a.subCuenta like '%$criterio%'  )":'';
		
		$sql.=$tipo!='todos'?" and if(a.nivel=1,(select b.cuenta from fac_cuentas as b where b.idCuenta=a.idCuenta limit 1),
		(select b.cuenta from fac_cuentas as b 
		inner join fac_subcuentas as c 
		on b.idCuenta=c.idCuenta
		where c.idSubCuenta=a.idCuenta limit 1)) ='$tipo' ":'';
		
		$sql.=" and a.fecha between '$inicio' and (select last_day('$fin')) ";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerCuentasCatalogo($numero,$limite,$criterio,$tipo='todos',$inicio,$fin)
	{
		$sql=" select a.*,
		if (a.idSubCuenta>0,(select b.nombre from fac_subcuentas as b where b.idSubCuenta=a.idCuenta limit 1),(select b.nombre from fac_cuentas as b where b.idCuenta=a.idCuenta limit 1)) as cuenta,
		
		(select count(b.idCuentaCatalogo) from fac_catalogos_cuentas_detalles as b where b.idCuentaPadre=a.idCuentaCatalogo) as cuentasHijo,
		
		(select coalesce(sum(b.debe),0) from fac_polizas_transacciones as b inner join fac_polizas_conceptos as c on c.idConcepto=b.idConcepto where b.idCuentaCatalogo=a.idCuentaCatalogo and c.cancelada='0') as debe,
		(select coalesce(sum(b.haber),0) from fac_polizas_transacciones as b inner join fac_polizas_conceptos as c on c.idConcepto=b.idConcepto where b.idCuentaCatalogo=a.idCuentaCatalogo and c.cancelada='0') as haber
		
		
		from fac_catalogos_cuentas_detalles as a
		where  a.idCuentaCatalogo>0 
		and a.idCuentaPadre=0
		and a.activo='1'  ";
		
		$sql.=strlen($criterio)>0?" and (a.numeroCuenta like '%$criterio%'
		or a.descripcion like '%$criterio%' 
		or a.codigoAgrupador like '%$criterio%' 
		or a.subCuenta like '%$criterio%'  )":'';

		$sql.=$tipo!='todos'?" and if(a.nivel=1,(select b.cuenta from fac_cuentas as b where b.idCuenta=a.idCuenta limit 1),
		(select b.cuenta from fac_cuentas as b 
		inner join fac_subcuentas as c 
		on b.idCuenta=c.idCuenta
		where c.idSubCuenta=a.idCuenta limit 1)) ='$tipo' ":'';
		
		$sql.=" and a.fecha between '$inicio' and (select last_day('$fin')) ";
		
		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function obtenerCuentasCatalogoDetalleVista($idCuentaCatalogo,$numero)
	{
		$data['cuentas']	   	 	= $this->obtenerCuentasCatalogoDetalle($idCuentaCatalogo);	
		$data['idCuentaCatalogo']	= $idCuentaCatalogo;
		$data['numero']				= $numero;

		$this->load->view('contabilidad/catalogo/obtenerCuentasCatalogoDetalle',$data);
	}
	
	public function obtenerCuentasCatalogoAsociar($tipo='todos',$idCuenta,$idSubCuenta)
	{
		$sql=" select a.*,
		if (a.idSubCuenta>0,(select b.nombre from fac_subcuentas as b where b.idSubCuenta=a.idCuenta limit 1),(select b.nombre from fac_cuentas as b where b.idCuenta=a.idCuenta limit 1)) as cuenta,
		
		(select count(b.idCuentaCatalogo) from fac_catalogos_cuentas_detalles as b where b.idCuentaPadre=a.idCuentaCatalogo) as cuentasHijo
		
		from fac_catalogos_cuentas_detalles as a
		where  a.idCuentaCatalogo>0 
		
		and a.activo='1'  ";

		$sql.=$tipo!='todos'?" and if(a.nivel=1,(select b.cuenta from fac_cuentas as b where b.idCuenta=a.idCuenta limit 1),
		(select b.cuenta from fac_cuentas as b 
		inner join fac_subcuentas as c 
		on b.idCuenta=c.idCuenta
		where c.idSubCuenta=a.idCuenta limit 1)) ='$tipo' ":'';
		
		if($idCuenta>0 and $idSubCuenta==0)
		{
			#$sql.=" and a.idCuenta='$idCuenta' ";
			$sql.=" AND IF(a.nivel=1,(SELECT b.idCuenta FROM fac_cuentas AS b 
			WHERE b.idCuenta=a.idCuenta LIMIT 1), (SELECT b.idCuenta FROM fac_cuentas AS b INNER JOIN fac_subcuentas AS c ON b.idCuenta=c.idCuenta 
			WHERE c.idSubCuenta=a.idCuenta LIMIT 1)) ='$idCuenta'";
		}
		
		if($idSubCuenta>0)
		{
			$sql.=" and a.idCuenta='$idSubCuenta' ";
		}
		
		#echo $sql;

		return $this->db->query($sql)->result();
	}
	
	public function obtenerCuentasCatalogoDetalle($idCuentaPadre)
	{
		$sql=" select a.*,
		if (a.idSubCuenta>0,(select b.nombre from fac_subcuentas as b where b.idSubCuenta=a.idCuenta limit 1),(select b.nombre from fac_cuentas as b where b.idCuenta=a.idCuenta limit 1)) as cuenta,
		(select count(b.idCuentaCatalogo) from fac_catalogos_cuentas_detalles as b where b.idCuentaPadre=a.idCuentaCatalogo) as cuentasHijo,
		
		(select coalesce(sum(b.debe),0) from fac_polizas_transacciones as b inner join fac_polizas_conceptos as c on c.idConcepto=b.idConcepto where b.idCuentaCatalogo=a.idCuentaCatalogo and c.cancelada='0') as debe,
		(select coalesce(sum(b.haber),0) from fac_polizas_transacciones as b inner join fac_polizas_conceptos as c on c.idConcepto=b.idConcepto where b.idCuentaCatalogo=a.idCuentaCatalogo and c.cancelada='0') as haber
		
		from fac_catalogos_cuentas_detalles as a
		where  a.idCuentaCatalogo>0 
		and a.idCuentaPadre='$idCuentaPadre'
		and a.activo='1'  ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerCuentasCatalogoExportar($inicio,$fin)
	{
		$sql=" select a.*,
		if (a.idSubCuenta>0,(select b.nombre from fac_subcuentas as b where b.idSubCuenta=a.idCuenta limit 1),(select b.nombre from fac_cuentas as b where b.idCuenta=a.idCuenta limit 1)) as cuenta,
		(select count(b.idCuentaCatalogo) from fac_catalogos_cuentas_detalles as b where b.idCuentaPadre=a.idCuentaCatalogo) as cuentasHijo,
		if(a.idCuentaPadre>0,(select b.numeroCuenta from fac_catalogos_cuentas_detalles as b where b.idCuentaCatalogo=a.idCuentaPadre limit 1),( if(a.idCuentaPadre=0 and a.nivel=2,a.numeroCuenta,'') )) as subCuentaPadre
		from fac_catalogos_cuentas_detalles as a
		where  a.idCuentaCatalogo>0 
		and a.activo='1'   ";

		$sql.=" and a.fecha between '$inicio' and (select last_day('$fin')) ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarCuenta()
	{
		$data=array
		(
			'codigoAgrupador'	=> $this->input->post('txtCodigoAgrupador'),
			'numeroCuenta'		=> $this->input->post('txtNumeroCuenta'),
			'descripcion'		=> $this->input->post('txtDescripcion'),
			'nivel'				=> $this->input->post('txtNivel'),
			'naturaleza'		=> $this->input->post('selectNaturaleza'),
			'idCatalogo'		=> 0,
			'idCuenta'			=> $this->input->post('txtIdCuenta'),
			'idSubCuenta'		=> $this->input->post('txtIdSubCuenta'),
			'subCuenta'			=> $this->input->post('txtSubCuenta'),
			'fecha'				=> $this->input->post('txtFechaCuenta'),
			'idCuentaPadre'		=> $this->input->post('txtIdCuentaCatalogo'),
			'saldo'				=> $this->input->post('txtSaldoCuenta'),
			'idUsuario'			=> $this->idUsuario,
		);
		
		$this->db->insert('fac_catalogos_cuentas_detalles',$data);
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function obtenerCuenta($idCuentaCatalogo)
	{
		$sql=" select a.*,
		if (a.nivel>1,(select b.nombre from fac_subcuentas as b where b.idSubCuenta=a.idCuenta limit 1),(select b.nombre from fac_cuentas as b where b.idCuenta=a.idCuenta limit 1)) as cuenta
		from fac_catalogos_cuentas_detalles as a
		where a.idCuentaCatalogo='$idCuentaCatalogo' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function editarCuenta()
	{
		$data=array
		(
			'numeroCuenta'		=> $this->input->post('txtNumeroCuenta'),
			'descripcion'		=> $this->input->post('txtDescripcion'),
			'naturaleza'		=> $this->input->post('selectNaturaleza'),
			'subCuenta'			=> $this->input->post('txtSubCuenta'),
			'saldo'				=> $this->input->post('txtSaldoCuenta'),
			'fecha'				=> $this->input->post('txtFechaCuenta'),
		);
		
		$this->db->where('idCuentaCatalogo',$this->input->post('txtIdDetalle'));
		$this->db->update('fac_catalogos_cuentas_detalles',$data);
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function borrarCuenta($idCuentaCatalogo)
	{
		$this->db->trans_start();
		
		/*$this->db->where('idCuentaCatalogo',$idCuentaCatalogo);
		$this->db->delete('fac_catalogos_cuentas_detalles');*/
		
		$this->db->where('idCuentaCatalogo',$idCuentaCatalogo);
		$this->db->update('fac_catalogos_cuentas_detalles',array('activo'=>'0'));
		
		//ESTOS CAMBIOS LOS PONDREMOS CUANDO SE PONGAN TODOS LOS MODULOS
		/*$this->db->where('idCuentaCatalogo',$idCuentaCatalogo);
		$this->db->delete('fac_balanza_detalles');
		
		$this->borrarTransaccionesCuenta($idCuentaCatalogo);*/
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	public function borrarTransaccionesCuenta($idCuentaCatalogo)
	{
		$sql="select idTransaccion
		from fac_polizas_transacciones
		where idCuentaCatalogo='$idCuentaCatalogo' ";
		
		$transacciones=$this->db->query($sql)->result();
		
		foreach($transacciones as $row)
		{
			$this->db->where('idTransaccion',$row->idTransaccion);
			$this->db->delete('fac_polizas_conceptos_cheques');
			
			$this->db->where('idTransaccion',$row->idTransaccion);
			$this->db->delete('fac_polizas_conceptos_comprobantes');
			
			$this->db->where('idTransaccion',$row->idTransaccion);
			$this->db->delete('fac_polizas_conceptos_transferencias');
			
			
			#BORRAR LA TRANSACCIÓN
			$this->db->where('idTransaccion',$row->idTransaccion);
			$this->db->delete('fac_polizas_transacciones');
		}
	}
	
	//----------------------------------------------------------------------------------------------------------------//
	//PARA ADMINISTRAR LA BALANZA DE COMPROBACIÓN
	//----------------------------------------------------------------------------------------------------------------//
	
	public function contarBalanza($inicio,$fin)
	{
		$sql=" select count(idBalanza) as numero
		from fac_balanza
		where idUsuario='$this->idUsuario' ";
		
		$sql.=" and fecha between '$inicio-01' and '$fin-01' ";
		#$sql.=$fecha!='fecha'?" and fecha='$fecha-01'":' ';
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerBalanza($numero,$limite,$inicio,$fin)
	{
		$sql=" select a.*, 
		(select count(idDetalle) from fac_balanza_detalles as b where a.idBalanza=b.idBalanza) as numeroCuentas
		from fac_balanza as a
		where a.idUsuario='$this->idUsuario' ";
		
		$sql.=" and fecha between '$inicio-01' and '$fin-01' ";
		#$sql.=$fecha!='fecha'?" and a.fecha='$fecha-01'":' ';
		
		$sql .= " order by a.fecha desc 
		limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCatalogoMes($fecha)
	{
		$sql=" select a.*
		from fac_catalogos_cuentas_detalles as a
		inner join fac_catalogos_cuentas as b
		on a.idCatalogo=b.idCatalogo 
		where b.fecha='$fecha'
		and b.idUsuario='$this->idUsuario'
		group by a.idCuentaCatalogo, b.idCatalogo
		order by b.fechaRegistro desc,
		idCuentaCatalogo asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function comprobarBalanza($fecha)
	{
		$sql="select * from fac_balanza
		where fecha='$fecha'
		and idUsuario='$this->idUsuario' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function registrarBalanza()
	{
		if($this->comprobarBalanza($this->input->post('txtFechaBalanza').'-01')>0)
		{
			return array(0=>'0');
		}
			
		$this->db->trans_start();
		
		$data=array
		(
			'rfc'			=>$this->input->post('txtRfc'),
			'fecha'			=>$this->input->post('txtFechaBalanza').'-01',
			'fechaRegistro'	=>$this->fecha,
			'idUsuario'		=>$this->idUsuario,
			'version'		=>version,
		);
		
		$this->db->insert('balanza',$data);
		$idBalanza	=$this->db->insert_id();
		
		/*$catalogo	= $this->obtenerCatalogoMes($this->input->post('txtFechaBalanza').'-01');
		$idCatalogo = 0;
		$i			= 1;
		
		foreach($catalogo as $row)
		{
			$idCatalogo	= $i==1?$row->idCatalogo:$idCatalogo;
			
			if($idCatalogo!=$row->idCatalogo)
			{
				break;
			}
			
			$data=array
			(
				'numeroCuenta'	=> $row->numeroCuenta,
				'idBalanza'		=> $idBalanza,
			);
			
			$this->db->insert('balanza_detalles',$data);
			
			$i++;
		}*/
			
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	public function obtenerBalanzaEditar($idBalanza)
	{
		$sql=" select a.* ,
		(select count(b.idCuentaCatalogo) 
		from fac_balanza_detalles as b 
		inner join fac_catalogos_cuentas_detalles as c
		on c.idCuentaCatalogo=b.idCuentaCatalogo
		where a.idBalanza=b.idBalanza and c.naturaleza='D' ) as cuentasDeudoras,
		(select count(b.idCuentaCatalogo) 
		from fac_balanza_detalles as b 
		inner join fac_catalogos_cuentas_detalles as c
		on c.idCuentaCatalogo=b.idCuentaCatalogo
		where a.idBalanza=b.idBalanza and c.naturaleza='A' ) as cuentasAcreedoras
		from fac_balanza as a
		where a.idBalanza='$idBalanza' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerSaldoDeudor($idBalanza)
	{
		$sql=" select coalesce(sum(a.saldoFinal),0) as deudor
		from fac_balanza_detalles as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		where a.idBalanza='$idBalanza'
		and b.naturaleza='D'
		and a.iva='1' ";
		
		return $this->db->query($sql)->row()->deudor;
	}
	
	public function obtenerSaldoAcreedor($idBalanza)
	{
		$sql=" select coalesce(sum(a.saldoFinal),0) as acreedor
		from fac_balanza_detalles as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		where a.idBalanza='$idBalanza'
		and b.naturaleza='A'
		and a.iva='1' ";
		
		return $this->db->query($sql)->row()->acreedor;
	}
	
	public function editarBalanza()
	{
		$data=array
		(
			'fecha'			=>$this->input->post('txtFechaBalanza').'-01',
		);
		
		$this->db->where('idBalanza',$this->input->post('txtIdBalanza'));
		$this->db->update('balanza',$data);
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function borrarBalanza($idBalanza)
	{
		$this->db->trans_start();
		
		#BORRAR LOS DETALLES DEL CATÁLOGO
		$this->db->where('idBalanza',$idBalanza);
		$this->db->delete('balanza_detalles');
		
		#BORRAR EL CATÁLOGO
		$this->db->where('idBalanza',$idBalanza);
		$this->db->where('idUsuario',$this->idUsuario);
		$this->db->delete('balanza');
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	public function obtenerCuentasBalanza($idBalanza)
	{
		$sql=" select a.*, b.numeroCuenta, b.naturaleza,b.descripcion
		from fac_balanza_detalles as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		where a.idBalanza='$idBalanza'
		order by a.idDetalle asc ";
		
		return $this->db->query($sql)->result();
	}

	public function guardarBalanzaComprobacion()
	{
		$this->db->trans_start();
		
		for($i=1;$i<=$this->input->post('txtNumeroCuentas');$i++)
		{
			$idDetalle	= $this->input->post('txtIdDetalle'.$i);
			
			if(strlen($idDetalle)>0)
			{
				$data=array
				(
					'idCuentaCatalogo'	=> $this->input->post('selectCuentas'.$i),
					'saldoInicial'		=> $this->input->post('txtSaldoInicial'.$i),
					'debe'				=> $this->input->post('txtDebe'.$i),
					'haber'				=> $this->input->post('txtHaber'.$i),
					'saldoFinal'		=> $this->input->post('txtSaldoFinal'.$i),
					'iva'				=> $this->input->post('chkIva'.$i)==1?'1':'0',
					'idBalanza'			=> $this->input->post('txtIdBalanza'),
				);
				
				if($idDetalle>0)
				{
					$this->db->where('idDetalle',$idDetalle);
					$this->db->update('balanza_detalles',$data);
				}
				
				if($idDetalle==0)
				{
					$this->db->insert('balanza_detalles',$data);
				}
			}
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	public function borrarCuentaBalanza($idDetalle)
	{
		$this->db->where('idDetalle',$idDetalle);
		$this->db->delete('balanza_detalles');
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	//----------------------------------------------------------------------------------------------------------------//
	//PARA ADMINISTRAR LAS PÓLIZAS
	//----------------------------------------------------------------------------------------------------------------//
	
	public function contarPolizas($inicio,$fin,$tipo=0)
	{
		$sql=" select count(idConcepto) as numero
		from fac_polizas_conceptos
		where idUsuario='$this->idUsuario'
		and idLicencia='$this->idLicencia'
		and activo='1'  ";
		
		$sql.=" and fecha between '$inicio' and '$fin' ";
		$sql.=$tipo!=0?" and tipo='$tipo' ":'';

		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerPolizas($numero,$limite,$inicio,$fin,$tipo=0)
	{
		$sql=" select a.*,
		(select coalesce(sum(b.debe),0) from fac_polizas_transacciones as b where b.idConcepto=a.idConcepto) as debe,
		(select coalesce(sum(b.haber),0) from fac_polizas_transacciones as b where b.idConcepto=a.idConcepto) as haber
		from fac_polizas_conceptos as a
		where a.idUsuario='$this->idUsuario'
		and a.idLicencia='$this->idLicencia'
		and a.activo='1' ";
		
		$sql.=" and a.fecha between '$inicio' and '$fin' ";
		$sql.=$tipo!=0?" and tipo='$tipo' ":'';

		$sql .= " order by a.fecha asc  ";
		$sql.=$numero>0?" limit $limite,$numero":'';
		
		return $this->db->query($sql)->result();
	}

	public function comprobarPoliza($fecha)
	{
		$sql=" select * from fac_polizas
		where fecha='$fecha'
		and idUsuario='$this->idUsuario' ";

		return $this->db->query($sql)->num_rows();
	}
	
	public function registrarPoliza()
	{
		/*if($this->comprobarPoliza($this->input->post('txtFechaPoliza').'-01')>0)
		{
			return array(0=>'0');	
		}*/
		
		$data=array
		(
			#'rfc'				=> $this->input->post('txtRfc'),
			'fecha'				=> $this->input->post('txtFechaPoliza'),
			'tipo'				=> $this->input->post('selectPolizas'),
			'numero'			=> $this->obtenerNumeroPoliza($this->input->post('selectPolizas')),
			#'tipoSolicitud'		=> $this->input->post('selectTipoSolicitud'),
			#'numeroTramite'		=> $this->input->post('txtNumeroTramite'),
			#'numeroOrden'		=> $this->input->post('txtNumeroOrden'),
			'fechaRegistro'		=> $this->fecha,
			'idUsuario'			=> $this->idUsuario,
			#'version'			=> version,
		);
		
		$this->db->insert('fac_polizas_conceptos',$data);
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function obtenerPoliza($idPoliza)
	{
		$sql=" select * from fac_polizas
		where idPoliza='$idPoliza' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function editarPoliza()
	{
		$data=array
		(
			'fecha'				=> $this->input->post('txtFechaPoliza').'-01',
			'tipoSolicitud'		=> $this->input->post('selectTipoSolicitud'),
			'numeroTramite'		=> $this->input->post('txtNumeroTramite'),
			'numeroOrden'		=> $this->input->post('txtNumeroOrden'),
		);
		
		$this->db->where('idPoliza',$this->input->post('txtIdPoliza'));
		$this->db->update('polizas',$data);
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}

	public function borrarTransaccionesPoliza($idConcepto)
	{
		$sql=" select idTransaccion, idGrupo
		from fac_polizas_transacciones
		where idConcepto='$idConcepto' ";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$this->db->where('idTransaccion',$row->idTransaccion);
			$this->db->delete('polizas_conceptos_cheques');
			
			$this->db->where('idTransaccion',$row->idTransaccion);
			$this->db->delete('polizas_conceptos_comprobantes');
			
			$this->db->where('idTransaccion',$row->idTransaccion);
			$this->db->delete('polizas_conceptos_transferencias');
			
			$this->db->where('idTransaccion',$row->idTransaccion);
			$this->db->delete('polizas_transacciones');
			
			$this->db->where('idTransaccion',$row->idTransaccion);
			$this->db->delete('polizas_transacciones_conceptos');
			
			$this->db->where('idTransaccion',$row->idTransaccion);
			$this->db->delete('polizas_conceptos_metodos');
			
			$this->db->where('idGrupo',$row->idGrupo);
			$this->db->delete('polizas_transacciones_grupos');
		}
	}
	
	public function borrarConceptosPoliza($idPoliza)
	{
		$sql=" select idConcepto
		from fac_polizas_conceptos
		where idPoliza='$idPoliza' ";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$this->borrarTransaccionesPoliza($row->idConcepto);
			
			$this->db->where('idConcepto',$row->idConcepto);
			$this->db->delete('polizas_conceptos');
		}
	}
	
	public function borrarPoliza($idPoliza)
	{
		$this->db->trans_start();
		
		#BORRAR LOS DETALLES DE LA PÓLIZA
		$this->borrarConceptosPoliza($idPoliza);
		
		#BORRAR LA PÓLIZA
		$this->db->where('idPoliza',$idPoliza);
		$this->db->delete('polizas');
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	//CONCEPTOS DE LAS PÓLIZAS
	public function contarConceptosPoliza($idPoliza,$tipo)
	{
		$sql=" select count(idConcepto) as numero
		from fac_polizas_conceptos
		where idPoliza='$idPoliza' ";
		
		$sql.=$tipo>0?" and tipo='$tipo' ":'';
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerConceptosPoliza($numero,$limite,$idPoliza,$tipo)
	{
		$sql=" select a.*
		from fac_polizas_conceptos as a
		where a.idPoliza='$idPoliza' ";
		
		$sql.=$tipo>0?" and tipo='$tipo' ":'';
		
		$sql .= " order by a.fecha desc ";
		
		$sql .= $numero>0? " limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerNumeroPoliza($tipo)
	{
		$sql=" select coalesce(max(numero),0) as numero
		from fac_polizas_conceptos
		where tipo='$tipo' ";
		
		return $this->db->query($sql)->row()->numero+1;
	}
	
	public function registrarConceptosTransaccion($idTransaccion,$t)
	{
		$numero	= $this->input->post('txtNumeroProductos'.$t);
		
		for($i=0;$i<$numero;$i++)
		{
			$data=array
			(
				'cantidad'			=> $this->input->post('txtCantidad'.$t.'_'.$i),
				'unidad'			=> $this->input->post('txtUnidad'.$t.'_'.$i),
				'codigo'			=> $this->input->post('txtCodigo'.$t.'_'.$i),
				'descripcion'		=> $this->input->post('txtDescripcion'.$t.'_'.$i),
				'precioUnitario'	=> $this->input->post('txtPrecioUnitario'.$t.'_'.$i),
				'importe'			=> $this->input->post('txtImporte'.$t.'_'.$i),
				'idTransaccion'		=> $idTransaccion,
			);
			
			$this->db->insert('polizas_transacciones_conceptos',$data);
		}
	}
	
	public function registrarGrupoTransaccion($grupo)
	{
		$data=array
		(
			'totalDebe'			=> $this->input->post('txtDebeGrupo'.$grupo),
			'totalHaber'		=> $this->input->post('txtHaberGrupo'.$grupo),
		);
		
		$this->db->insert('polizas_transacciones_grupos',$data);
		
		return $this->db->insert_id();
	}
	
	public function procesarTransaccionesConcepto($idConcepto)
	{
		//REGISTRAR LA TRANSACCIÓNES PARA LA PÓLIZA
		
		$numeroTransacciones	= $this->input->post('txtNumeroTransacciones');
		$grupo					= 10000; //Un número diferente para evitar duplicar
		$idGrupo				= 0;
		
		for($t=0;$t<$numeroTransacciones;$t++)
		{
			$idCuentaCatalogo	= $this->input->post('selectCuentasTransaccion'.$t);
			$concepto			= $this->input->post('txtConceptoTransaccion'.$t);
			$debe				= $this->input->post('txtDebe'.$t);
			$haber				= $this->input->post('txtHaber'.$t);
			$moneda				= $this->input->post('txtMoneda'.$t);
			$tipoCambio			= $this->input->post('txtTipoCambio'.$t);
			
			if($idCuentaCatalogo>0 and strlen($concepto)>1 and strlen($debe)>0 and strlen($haber)>0 and strlen($moneda)>2)
			{
				
				if($this->input->post('txtGrupo'.$t)!=$grupo)
				{
					$grupo		= $this->input->post('txtGrupo'.$t);
					$idGrupo	= $this->registrarGrupoTransaccion($grupo);
				}
				
				$data=array
				(
					'idCuentaCatalogo'	=> $idCuentaCatalogo,
					'concepto'			=> $concepto,
					'debe'				=> $debe,
					'haber'				=> $haber,
					'moneda'			=> $moneda,
					'tipoCambio'		=> strlen($tipoCambio)>0?$tipoCambio:'0',
					'idConcepto'		=> $idConcepto,
					
					'idGrupo'			=> $idGrupo, //AGREGAR EL GRUPO
				);
				
				$this->db->insert('polizas_transacciones',$data);
				$idTransaccion	= $this->db->insert_id();
				
				if($idTransaccion>0)
				{
					//REGISTRAR LOS CONCEPTOS (CFDI)
					
					$this->registrarConceptosTransaccion($idTransaccion,$t);
					
					//REGISTRAR EL CHEQUE
					$numero				= $this->input->post('txtNumeroCheque'.$t);
					$cuentaOrigen		= $this->input->post('txtCuentaOrigenCheque'.$t);
					$monto				= $this->input->post('txtMonto'.$t);
					$beneficiario		= $this->input->post('txtBeneficiario'.$t);
					$rfc				= $this->input->post('txtRfc'.$t);
					$idBanco			= $this->input->post('selectBancos'.$t);
				
				
					if($idBanco>0 and strlen($numero)>2 and strlen($cuentaOrigen)>0 and strlen($monto)>0 and strlen($beneficiario)>2 and strlen($rfc)>11)
					{
						$banco					= $this->obtenerBanco($idBanco);
						
						$data=array
						(
							'numero'			=> $numero,
							'cuentaOrigen'		=> $cuentaOrigen,
							'monto'				=> $monto,
							'beneficiario'		=> $beneficiario,
							#'moneda'			=> $moneda,
							'rfc'				=> $rfc,
							'idBanco'			=> $idBanco,
							'idTransaccion'		=> $idTransaccion,
							'banco'				=> $banco!=null?$banco->clave:'',
							'nombreBanco'		=> $banco!=null?$banco->nombre:'',
							'fecha'				=> $this->input->post('txtFechaCheque'.$t),
						);
						
						$this->db->insert('polizas_conceptos_cheques',$data);
					}
					
					//REGISTRAR LA TRANSFERENCIA
					$cuentaOrigen		= $this->input->post('txtCuentaOrigen'.$t);
					$idBancoOrigen		= $this->input->post('selectBancosOrigen'.$t);
					$monto				= $this->input->post('txtMontoTransferencia'.$t);
					$cuentaDestino		= $this->input->post('txtCuentaDestino'.$t);
					$idBancoDestino		= $this->input->post('selectBancosDestino'.$t);
					$beneficiario		= $this->input->post('txtBeneficiarioTransferencia'.$t);
					$rfc				= $this->input->post('txtRfcTransferencia'.$t);
					
					if(strlen($cuentaOrigen)>2 and strlen($monto)>0 and strlen($cuentaDestino)>2 and strlen($beneficiario)>2 and strlen($rfc)>11)
					{
						$bancoOrigen		= $this->obtenerBanco($idBancoOrigen);
						$bancoDestino		= $this->obtenerBanco($idBancoDestino);
						
						$data=array
						(
							'cuentaOrigen'		=> $cuentaOrigen,
							'idBancoOrigen'		=> $idBancoOrigen,
							'monto'				=> $monto,
							'cuentaDestino'		=> $cuentaDestino,
							'idBancoDestino'	=> $idBancoDestino,
							'beneficiario'		=> $beneficiario,
							'rfc'				=> $rfc,
							'idTransaccion'		=> $idTransaccion,
							'fecha'				=> $this->input->post('txtFecha'.$t),
							'bancoOrigen'		=> $bancoOrigen!=null?$bancoOrigen->clave:'',
							'nombreBancoOrigen'	=> $bancoOrigen!=null?$bancoOrigen->nombre:'',
							'bancoDestino'		=> $bancoDestino!=null?$bancoDestino->clave:'',
							'nombreBancoDestino'=> $bancoDestino!=null?$bancoDestino->nombre:'',
							
						);
						
						$this->db->insert('polizas_conceptos_transferencias',$data);
					}
					
					//REGISTRAR EL COMPROBANTE
					$uuid		= $this->input->post('txtUuid'.$t);
					$monto		= $this->input->post('txtMontoComprobante'.$t);
					$rfc		= $this->input->post('txtRfcComprobante'.$t);
					
					if(strlen($uuid)>35 and strlen($monto)>0 and strlen($rfc)>11)
					{
						$data=array
						(
							'uuid'				=> $uuid,
							'monto'				=> $monto,
							'rfc'				=> $rfc,
							'idTransaccion'		=> $idTransaccion,
						);
						
						$this->db->insert('polizas_conceptos_comprobantes',$data);
					}
					
					
					//REGISTRAR OTRO MÉTODO DE PAGO
					$fecha			= $this->input->post('txtFechaMetodo'.$t);
					$beneficiario	= $this->input->post('txtBeneficiarioMetodo'.$t);
					$rfc			= $this->input->post('txtRfcMetodo'.$t);
					$monto			= $this->input->post('txtMontoMetodo'.$t);
					$moneda			= $this->input->post('selectMonedaMetodo'.$t);
					$tipoCambio		= $this->input->post('txtTipoCambioMetodo'.$t);
					$idMetodo		= $this->input->post('selectMetodos'.$t);
					
					if(strlen($fecha)>9 and strlen($beneficiario)>2 and strlen($rfc)>11  and $idMetodo>0 and $monto>0)
					{
						$data=array
						(
							'fecha'			=> $fecha,
							'beneficiario'	=> $beneficiario,
							'rfc'			=> $rfc,
							'monto'			=> $monto,
							'moneda'		=> $moneda,
							'tipoCambio'	=> $tipoCambio,
							'idMetodo'		=> $idMetodo,
							'idTransaccion'	=> $idTransaccion,
						);
						
						$this->db->insert('polizas_conceptos_metodos',$data);
					}
				}
			}
		}
	}
	
	public function registrarConcepto()
	{
		$this->db->trans_start();
		
		$tipo	= $this->input->post('selectTipo');
		
		$data=array
		(
			'tipo'			=> $tipo,
			'numero'		=> $this->input->post('txtNumero'),
			'fecha'			=> $this->input->post('txtFechaConcepto'),
			'concepto'		=> $this->input->post('txtConcepto'),
			'idPoliza'		=> $this->input->post('idPoliza'),
			'idUsuario'		=> $this->idUsuario,
			'fechaRegistro'	=> $this->fecha,
		);
		
		if($tipo=='1')
		{
			$data['pagada']	= $this->input->post('selectTipoIngreso');
		}
		
		if($tipo=='2')
		{
			$data['pagada']	= $this->input->post('selectTipoEgreso');
		}
		
		
		$this->db->insert('polizas_conceptos',$data);
		$idConcepto	= $this->db->insert_id();
		
		$this->procesarTransaccionesConcepto($idConcepto);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	public function editarConcepto()
	{
		$this->db->trans_start();
		
		$tipo		= $this->input->post('selectTipo');
		$idConcepto	= $this->input->post('txtIdConcepto');
		
		$data=array
		(
			'tipo'			=> $this->input->post('selectTipo'),
			'numero'		=> $this->input->post('txtNumero'),
			'fecha'			=> $this->input->post('txtFechaConcepto'),
			'concepto'		=> $this->input->post('txtConcepto'),
		);
		
		if($tipo=='1')
		{
			$data['pagada']	= $this->input->post('selectTipoIngreso');
		}
		
		if($tipo=='2')
		{
			$data['pagada']	= $this->input->post('selectTipoEgreso');
		}
		
		
		$this->db->where('idConcepto',$idConcepto);
		$this->db->update('polizas_conceptos',$data);
		
		$this->borrarTransaccionesPoliza($idConcepto); //BORRAR ANTES LOS CONCEPTOS QUE ESTEN REGISTRADOS
		
		$this->procesarTransaccionesConcepto($idConcepto);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	/*public function editarConcepto()
	{
		$data=array
		(
			'tipo'			=> $this->input->post('selectTipo'),
			'numero'		=> $this->input->post('txtNumero'),
			'fecha'			=> $this->input->post('txtFechaConcepto'),
			'concepto'		=> $this->input->post('txtConcepto'),
		);
		
		$this->db->where('idConcepto',$this->input->post('txtIdConcepto'));
		$this->db->update('polizas_conceptos',$data);
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}*/
	
	public function obtenerConcepto($idConcepto)
	{
		$sql=" select a.*
		from fac_polizas_conceptos as a
		where idConcepto='$idConcepto' ";

		return $this->db->query($sql)->row();
	}

	public function borrarConcepto($idConcepto)
	{
		$this->db->trans_start();
		
		#BORRAR LOS DETALLES DE LA PÓLIZA
		$this->borrarTransaccionesPoliza($idConcepto);
		
		#BORRAR LA PÓLIZA
		$this->db->where('idConcepto',$idConcepto);
		$this->db->delete('polizas_conceptos');
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	//ADMINISTRAR LAS TRANSACCIONES
	public function obtenerTransaccion($idTransaccion)
	{
		$sql=" select a.*, b.numeroCuenta
		from fac_polizas_transacciones as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		where idTransaccion='$idTransaccion' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerTransacciones($idConcepto)
	{
		/*
		(select count(c.idCheque) from fac_polizas_conceptos_cheques as c where c.idTransaccion=a.idTransaccion) as numeroCheques,
		(select count(c.idTransferencia) from fac_polizas_conceptos_transferencias as c where c.idTransaccion=a.idTransaccion) as numeroTransferencias*/
		$sql=" select a.*, b.numeroCuenta, b.descripcion, b.nivel, b.naturaleza, b.idCuenta, b.subCuenta, b.codigoAgrupador,
		(select count(c.idConcepto) from fac_polizas_transacciones_conceptos as c where c.idTransaccion=a.idTransaccion) as numeroConceptos
		from fac_polizas_transacciones as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		where idConcepto='$idConcepto' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTransaccionesGrupo($idGrupo)
	{
		$sql=" select a.*, b.numeroCuenta, b.descripcion, b.nivel, b.naturaleza, b.idCuenta, b.subCuenta, b.codigoAgrupador,
		(select count(c.idConcepto) from fac_polizas_transacciones_conceptos as c where c.idTransaccion=a.idTransaccion) as numeroConceptos
		from fac_polizas_transacciones as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		where a.idGrupo='$idGrupo' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerConceptosTransaccion($idTransaccion)
	{
		$sql=" select * from fac_polizas_transacciones_conceptos
		where idTransaccion='$idTransaccion' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerGruposTransaccion($idConcepto)
	{
		$sql=" select a.* 
		from fac_polizas_transacciones_grupos as a
		inner join fac_polizas_transacciones as b
		on a.idGrupo=b.idGrupo
		where b.idConcepto='$idConcepto'
		group by a.idGrupo ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarTransacciones()
	{
		$this->db->trans_start();
		
		for($i=1;$i<=$this->input->post('txtNumeroCuentas');$i++)
		{
			$idTransaccion	= $this->input->post('txtIdTransaccion'.$i);
			
			if(strlen($idTransaccion)>0)
			{
				$data=array
				(
					#'numeroCuenta'	=> $this->input->post('txtCuenta'.$i),
					'idCuentaCatalogo'	=> $this->input->post('selectCuentasTransaccion'.$i),
					'concepto'			=> $this->input->post('txtConcepto'.$i),
					'debe'				=> $this->input->post('txtDebe'.$i),
					'haber'				=> $this->input->post('txtHaber'.$i),
					'moneda'			=> $this->input->post('txtMoneda'.$i),
					'tipoCambio'		=> $this->input->post('txtTipoCambio'.$i),
					'idConcepto'		=> $this->input->post('txtIdConcepto'),
				);
				
				if($idTransaccion>0)
				{
					$this->db->where('idTransaccion',$idTransaccion);
					$this->db->update('polizas_transacciones',$data);
				}
				
				if($idTransaccion==0)
				{
					$this->db->insert('polizas_transacciones',$data);
				}
			}
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	public function borrarTransaccion($idTransaccion)
	{
		$this->db->trans_start();
		
		#BORRAR LOS DETALLES DE LA TRANSACCIÓN
		$this->db->where('idTransaccion',$idTransaccion);
		$this->db->delete('polizas_conceptos_cheques');
		
		$this->db->where('idTransaccion',$idTransaccion);
		$this->db->delete('polizas_conceptos_comprobantes');
		
		$this->db->where('idTransaccion',$idTransaccion);
		$this->db->delete('polizas_conceptos_transferencias');
		
		
		#BORRAR LA TRANSACCIÓN
		$this->db->where('idTransaccion',$idTransaccion);
		$this->db->delete('polizas_transacciones');
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	//ADMINISTRAR LOS CHEQUES
	public function obtenerMetodos()
	{
		$sql=" select * from fac_catalogos_metodos ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerMonedas()
	{
		$sql=" select * from fac_catalogos_monedas ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerBancos()
	{
		$sql=" select * from bancos ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerBanco($idBanco)
	{
		$sql=" select * from fac_bancos
		where idBanco='$idBanco' ";
		
		return $this->db->query($sql)->row();
	}
	
	/*public function obtenerCheques($idTransaccion)
	{
		$sql=" select a.*, b.numeroCuenta as cuentaOrigen
		from fac_polizas_conceptos_cheques as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		where a.idTransaccion='$idTransaccion' ";
		
		return $this->db->query($sql)->result();
	}*/
	
	public function obtenerChequesConcepto($idConcepto)
	{
		$sql=" select a.* 
		from fac_polizas_conceptos_cheques as a
		inner join fac_polizas_transacciones as b
		on a.idTransaccion=b.idTransaccion
		where b.idConcepto='$idConcepto' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCheques($idTransaccion)
	{
		$sql=" select * from fac_polizas_conceptos_cheques
		where idTransaccion='$idTransaccion' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarCheques()
	{
		$this->db->trans_start();
		
		for($i=1;$i<=$this->input->post('txtNumeroCheques');$i++)
		{
			$idCheque	= $this->input->post('txtIdCheque'.$i);
			
			if(strlen($idCheque)>0)
			{
				$idBanco	= $this->input->post('selectBancos'.$i);
				$banco		= $this->obtenerBanco($idBanco);
				
				$data=array
				(
					'numero'			=> $this->input->post('txtNumeroCheque'.$i),
					'banco'				=> $banco!=null?$banco->clave:'',
					'cuentaOrigen'		=> $this->input->post('txtCuentaOrigen'.$i),
					#'idCuentaCatalogo'	=> $this->input->post('selectCuentasCheque'.$i),
					'fecha'				=> $this->input->post('txtFecha'.$i),
					'monto'				=> $this->input->post('txtMonto'.$i),
					'beneficiario'		=> $this->input->post('txtBeneficiario'.$i),
					'rfc'				=> $this->input->post('txtRfc'.$i),
					'idTransaccion'		=> $this->input->post('txtIdTransaccion'),
					
					'nombreBanco'		=> $banco!=null?$banco->nombre:'',
					'idBanco'			=> $idBanco,
				);
				
				if($idCheque>0)
				{
					$this->db->where('idCheque',$idCheque);
					$this->db->update('polizas_conceptos_cheques',$data);
				}
				
				if($idCheque==0)
				{
					$this->db->insert('polizas_conceptos_cheques',$data);
				}
			}
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	public function borrarCheque($idCheque)
	{
		#BORRAR EL CHEQUE
		$this->db->where('idCheque',$idCheque);
		$this->db->delete('polizas_conceptos_cheques');
	
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	//ADMINISTRAR LAS TRANSFERENCIAS
	public function obtenerTransferenciasConcepto($idConcepto)
	{
		$sql=" select a.* 
		from fac_polizas_conceptos_transferencias as a
		inner join fac_polizas_transacciones as b
		on a.idTransaccion=b.idTransaccion
		where b.idConcepto='$idConcepto' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTransferencias($idTransaccion)
	{
		$sql=" select * from fac_polizas_conceptos_transferencias
		where idTransaccion='$idTransaccion' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarTransferencias()
	{
		$this->db->trans_start();
		
		for($i=1;$i<=$this->input->post('txtNumeroTransferencias');$i++)
		{
			$idTransferencia	= $this->input->post('txtIdTransferencia'.$i);
			
			if(strlen($idTransferencia)>0)
			{
				$idBancoOrigen		= $this->input->post('selectBancosOrigen'.$i);
				$bancoOrigen		= $this->obtenerBanco($idBancoOrigen);
				$idBancoDestino		= $this->input->post('selectBancosDestino'.$i);
				$bancoDestino		= $this->obtenerBanco($idBancoDestino);
				
				$data=array
				(
					'cuentaOrigen'			=> $this->input->post('txtCuentaOrigen'.$i),
					'bancoOrigen'			=> $bancoOrigen!=null?$bancoOrigen->clave:'',
					'monto'					=> $this->input->post('txtMonto'.$i),
					'cuentaDestino'			=> $this->input->post('txtCuentaDestino'.$i),
					'bancoDestino'			=> $bancoDestino!=null?$bancoDestino->clave:'',
					'fecha'					=> $this->input->post('txtFecha'.$i),
					'beneficiario'			=> $this->input->post('txtBeneficiario'.$i),
					'rfc'					=> $this->input->post('txtRfc'.$i),
					'idTransaccion'			=> $this->input->post('txtIdTransaccion'),
					'nombreBancoOrigen'		=> $bancoOrigen!=null?$bancoOrigen->nombre:'',
					'idBancoOrigen'			=> $idBancoOrigen,
					'nombreBancoDestino'	=> $bancoDestino!=null?$bancoDestino->nombre:'',
					'idBancoDestino'		=> $idBancoDestino,
				);
				
				if($idTransferencia>0)
				{
					$this->db->where('idTransferencia',$idTransferencia);
					$this->db->update('polizas_conceptos_transferencias',$data);
				}
				
				if($idTransferencia==0)
				{
					$this->db->insert('polizas_conceptos_transferencias',$data);
				}
			}
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	public function borrarTransferencia($idTransferencia)
	{
		#BORRAR EL CHEQUE
		$this->db->where('idTransferencia',$idTransferencia);
		$this->db->delete('polizas_conceptos_transferencias');
	
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	//ADMINISTRAR LAS MÉTODOS DE PAGO
	public function obtenerMetodosConcepto($idConcepto)
	{
		$sql=" select a.* 
		from fac_polizas_conceptos_metodos as a
		inner join fac_polizas_transacciones as b
		on a.idTransaccion=b.idTransaccion
		where b.idConcepto='$idConcepto' ";
		
		return $this->db->query($sql)->result();
	}
	
	//ADMINISTRAR LOS COMPROBANTES
	public function obtenerComprobanteTransaccion($idTransaccion)
	{
		$sql=" select * from fac_polizas_conceptos_comprobantes
		where idTransaccion='$idTransaccion' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerComprobantes($idTransaccion)
	{
		$sql=" select * from fac_polizas_conceptos_comprobantes
		where idTransaccion='$idTransaccion' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarComprobantes()
	{
		$this->db->trans_start();
		
		for($i=1;$i<=$this->input->post('txtNumeroComprobantes');$i++)
		{
			$idComprobante	= $this->input->post('txtIdComprobante'.$i);
			
			if(strlen($idComprobante)>0)
			{
				$data=array
				(
					'uuid'				=> $this->input->post('txtUuid'.$i),
					'monto'				=> $this->input->post('txtMonto'.$i),
					'rfc'				=> $this->input->post('txtRfc'.$i),
					'idTransaccion'		=> $this->input->post('txtIdTransaccion'),
				);
				
				if($idComprobante>0)
				{
					$this->db->where('idComprobante',$idComprobante);
					$this->db->update('polizas_conceptos_comprobantes',$data);
				}
				
				if($idComprobante==0)
				{
					$this->db->insert('polizas_conceptos_comprobantes',$data);
				}
			}
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	public function borrarComprobante($idComprobante)
	{
		#BORRAR EL COMPROBANTE
		$this->db->where('idComprobante',$idComprobante);
		$this->db->delete('polizas_conceptos_comprobantes');
	
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	//DATOS PARA GENERAR EL XML DE LA PÓLIZA
	
	public function obtenerConceptosXml($idPoliza)
	{
		$sql=" select a.*
		from fac_polizas_conceptos as a
		where a.idPoliza='$idPoliza' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTransaccionesXml($idPoliza)
	{
		$sql=" select a.*, c.numeroCuenta, 
		c.descripcion as descripcionCuenta
		from fac_polizas_transacciones as a
		inner join fac_polizas_conceptos as b
		on a.idConcepto=b.idConcepto
		inner join fac_catalogos_cuentas_detalles as c
		on a.idCuentaCatalogo=c.idCuentaCatalogo
		where b.idPoliza='$idPoliza' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerChequesXml($idPoliza)
	{
		$sql=" select a.* 
		from fac_polizas_conceptos_cheques as a
		inner join fac_polizas_transacciones as b
		on a.idTransaccion=b.idTransaccion
		inner join fac_polizas_conceptos as c
		on c.idConcepto=b.idConcepto
		where c.idPoliza='$idPoliza' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTransferenciasXml($idPoliza)
	{
		$sql=" select a.* 
		from fac_polizas_conceptos_transferencias as a
		inner join fac_polizas_transacciones as b
		on a.idTransaccion=b.idTransaccion
		inner join fac_polizas_conceptos as c
		on c.idConcepto=b.idConcepto
		where c.idPoliza='$idPoliza' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerComprobantesXml($idPoliza)
	{
		$sql=" select a.* 
		from fac_polizas_conceptos_comprobantes as a
		inner join fac_polizas_transacciones as b
		on a.idTransaccion=b.idTransaccion
		inner join fac_polizas_conceptos as c
		on c.idConcepto=b.idConcepto
		where c.idPoliza='$idPoliza' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerMetodosXml($idPoliza)
	{
		$sql=" select a.*,
		(select d.clave
		from fac_catalogos_metodos as d
		where d.idMetodo=a.idMetodo) as metodoPago
		from fac_polizas_conceptos_metodos as a
		inner join fac_polizas_transacciones as b
		on a.idTransaccion=b.idTransaccion
		inner join fac_polizas_conceptos as c
		on c.idConcepto=b.idConcepto
		where c.idPoliza='$idPoliza' ";
		
		return $this->db->query($sql)->result();
	}
	
	//REGISTRAR CATALOGOS DE CUENTAS A PARTIR DE UN EXCEL
	
	public function registrarCatalogoExcel($mes,$anio,$rfc)
	{
		$data=array
		(
			'rfc'			=>$rfc,
			'fecha'			=>$anio.'-'.$mes.'-01',
			'fechaRegistro'	=>$this->fecha,
			'idUsuario'		=>$this->idUsuario,
			'version'		=>version,
		);
		
		$this->db->insert('catalogos_cuentas',$data);
		
		return $this->db->affected_rows()==1?$this->db->insert_id():0;
	}
	
	public function registrarCuentaCatalogoExcel($data)
	{
		$this->db->insert('catalogos_cuentas_detalles',$data);
		
		return $this->db->affected_rows()==1?array(0=>$this->db->insert_id()):array(0=>'0');
	}
	
	public function obtenerSubCuentaExcel($codigo)
	{
		$sql="select idSubCuenta
		from fac_subcuentas
		where codigo='$codigo'";
		
		$subCuenta	= $this->db->query($sql)->row();
		
		return $subCuenta!=null?$subCuenta->idSubCuenta:0;
	}
	
	public function obtenerCuentaSubCuentaExcel($codigo)
	{
		$sql="select idCuenta
		from fac_subcuentas
		where codigo='$codigo'";
		
		$subCuenta	= $this->db->query($sql)->row();
		
		return $subCuenta!=null?$subCuenta->idCuenta:0;
	}
	
	public function obtenerCuentaExcel($codigo)
	{
		$sql="select idCuenta
		from fac_cuentas
		where codigo='$codigo'";
		
		$cuenta	= $this->db->query($sql)->row();
		
		return $cuenta!=null?$cuenta->idCuenta:0;
	}
	
	//REGISTRAR LA BALANZA DE COMPROBACIÓN A PARTIR DE UN EXCEL
	
	public function obtenerIdCuenta($numeroCuenta)
	{
		$sql=" select idCuentaCatalogo
		from fac_catalogos_cuentas_detalles
		where numeroCuenta='$numeroCuenta'";
		
		$cuenta	= $this->db->query($sql)->row();
		
		return $cuenta!=null?$cuenta->idCuentaCatalogo:0;
	}
	
	public function registrarBalanzaExcel($mes,$anio,$rfc)
	{
		$data=array
		(
			'rfc'			=>$rfc,
			'fecha'			=>$anio.'-'.$mes.'-01',
			'fechaRegistro'	=>$this->fecha,
			'idUsuario'		=>$this->idUsuario,
			'version'		=>version,
		);
		
		$this->db->insert('balanza',$data);
		
		return $this->db->affected_rows()==1?$this->db->insert_id():0;
	}
	
	public function registrarCuentaBalanzaExcel($data)
	{
		$this->db->insert('balanza_detalles',$data);
		
		return $this->db->affected_rows()==1?array(0=>$this->db->insert_id()):array(0=>'0');
	}
	
	//REGISTRAR LAS PÓLIZAS A PARTIR DE UN EXCEL
	public function obtenerBancoClave($clave)
	{
		$sql=" select * from fac_bancos
		where clave='$clave'";

		return $this->db->query($sql)->row();
	}
	
	public function registrarPolizaExcel($mes,$anio,$rfc)
	{
		$data=array
		(
			'rfc'			=>$rfc,
			'fecha'			=>$anio.'-'.$mes.'-01',
			'fechaRegistro'	=>$this->fecha,
			'idUsuario'		=>$this->idUsuario,
			'version'		=>version,
		);
		
		$this->db->insert('polizas',$data);
		
		return $this->db->affected_rows()==1?$this->db->insert_id():0;
	}
	
	public function registrarConceptoPolizaExcel($data)
	{
		$this->db->insert('polizas_conceptos',$data);
		
		return $this->db->affected_rows()==1?$this->db->insert_id():0;
	}
	
	public function registrarTransaccionPolizaExcel($data)
	{
		$this->db->insert('polizas_transacciones',$data);
		
		return $this->db->affected_rows()==1?$this->db->insert_id():0;
	}
	
	public function registrarChequePolizaExcel($data)
	{
		$this->db->insert('polizas_conceptos_cheques',$data);
		
		return $this->db->affected_rows()==1?$this->db->insert_id():0;
	}
	
	public function registrarTransferenciaPolizaExcel($data)
	{
		$this->db->insert('polizas_conceptos_transferencias',$data);
		
		return $this->db->affected_rows()==1?$this->db->insert_id():0;
	}
	
	public function registrarComprobantePolizaExcel($data)
	{
		$this->db->insert('polizas_conceptos_comprobantes',$data);
		
		return $this->db->affected_rows()==1?$this->db->insert_id():0;
	}
	
	
	//OBTENER LAS BALANZAS AUTOMATICAMENTE APARTIR DE LAS PÓLIZAS
	
	public function obtenerPolizasBalanza($fecha)
	{
		$sql=" select a.*, b.codigoAgrupador,
		b.numeroCuenta, b.descripcion, b.nivel, c.concepto,
		d.fecha, b.idCuenta,
		sum(debe) as debe,
		sum(haber) as haber
		from fac_polizas_transacciones as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		inner join fac_polizas_conceptos as c
		on a.idConcepto=c.idConcepto
		inner join fac_polizas as d
		on d.idPoliza=c.idPoliza
		where d.fecha='$fecha'
		group by a.idCuentaCatalogo ";
		
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	/*public function obtenerMovimientosSaldoBalanzaCuenta($fecha,$idCuentaCatalogo)
	{
		$sql=" select coalesce(sum(a.debe),0) as debe, coalesce(sum(a.haber),0) as haber
		from fac_polizas_transacciones as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		inner join fac_polizas_conceptos as c
		on a.idConcepto=c.idConcepto
		inner join fac_polizas as d
		on d.idPoliza=c.idPoliza
		where d.fecha<'$fecha'
		and a.idCuentaCatalogo='$idCuentaCatalogo' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerMovimientosBalanzaCuenta($fecha,$idCuentaCatalogo)
	{
		$sql=" select coalesce(sum(a.debe),0) as debe, coalesce(sum(a.haber),0) as haber
		from fac_polizas_transacciones as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		inner join fac_polizas_conceptos as c
		on a.idConcepto=c.idConcepto
		inner join fac_polizas as d
		on d.idPoliza=c.idPoliza
		where d.fecha='$fecha'
		and a.idCuentaCatalogo='$idCuentaCatalogo' ";
		
		return $this->db->query($sql)->row();
	}*/
	
	public function obtenerMovimientosSaldoBalanzaCuenta($fecha,$idCuenta)
	{
		$sql=" select coalesce(sum(a.debe),0) as debe, coalesce(sum(a.haber),0) as haber
		from fac_polizas_transacciones as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		inner join fac_polizas_conceptos as c
		on a.idConcepto=c.idConcepto
		inner join fac_polizas as d
		on d.idPoliza=c.idPoliza
		where d.fecha<'$fecha'
		and d.idUsuario='$this->idUsuario'
		and b.idCuenta='$idCuenta' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerMovimientosBalanzaCuenta($fecha,$idCuenta)
	{
		$sql=" select coalesce(sum(a.debe),0) as debe, coalesce(sum(a.haber),0) as haber
		from fac_polizas_transacciones as a
		inner join fac_catalogos_cuentas_detalles as b
		on a.idCuentaCatalogo=b.idCuentaCatalogo
		inner join fac_polizas_conceptos as c
		on a.idConcepto=c.idConcepto
		inner join fac_polizas as d
		on d.idPoliza=c.idPoliza
		where d.fecha='$fecha'
		and d.idUsuario='$this->idUsuario'
		and b.idCuenta='$idCuenta' ";
		
		return $this->db->query($sql)->row();
	}
	
	///>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PARTIDAS
	///>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function borrarPolizaConcepto($idConcepto)
	{
		$this->db->where('idConcepto',$idConcepto);
		$this->db->update('fac_polizas_conceptos',array('activo'=>'0'));
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function cancelarPolizaConcepto($idConcepto)
	{
		$this->db->where('idConcepto',$idConcepto);
		$this->db->update('fac_polizas_conceptos',array('cancelada'=>'1'));
		
		return $this->db->affected_rows()==1?array(0=>'1'):array(0=>'0');
	}
	
	public function guardarConcepto()
	{
		$this->db->trans_start();
		
		$idConcepto				= $this->input->post('txtIdConcepto');
		
		$data=array
		(
			'fecha'				=> $this->input->post('txtFechaConcepto'),
			'concepto'			=> $this->input->post('txtConceptoPoliza'),
		);
		
		$this->db->where('idConcepto',$idConcepto);
		$this->db->update('fac_polizas_conceptos',$data);
		
		//BORRAR PARTIDAS O TRANSACCIONES DE PÓLIZA
		$this->db->where('idConcepto',$idConcepto);
		$this->db->delete('fac_polizas_transacciones');
		
		$this->registrarPartidas($idConcepto);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return array(0=>'0');
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array(0=>'1');
		}
	}
	
	//REGISTRAR PARTIDAS O TRANSACCIONES DE PÓLIZAS
	public function registrarPartidas($idConcepto)
	{
		$numeroPartidas	= $this->input->post('txtNumeroPartidas');
		
		for($i=0;$i<$numeroPartidas;$i++)
		{
			$idCuentaCatalogo	= $this->input->post('txtIdCuentaCatalogo'.$i);
			
			if($idCuentaCatalogo>0)
			{
				$data=array
				(
					'idConcepto'		=> $idConcepto,
					'idCuentaCatalogo'	=> $idCuentaCatalogo,
					'debe'				=> $this->input->post('txtCargo'.$i),
					'haber'				=> $this->input->post('txtAbono'.$i),
					'concepto'			=> $this->input->post('txtConcepto'.$i),
				);
				
				$this->db->insert('fac_polizas_transacciones',$data);
			}
		}
	}
	
	//FICHEROS DE CONCEPTOS O TRANSACCIONES
	public function obtenerComprobantesConcepto($idConcepto)
	{
		$sql="select * from fac_polizas_conceptos_comprobantes
		where idConcepto='$idConcepto'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerComprobanteConcepto($idComprobante)
	{
		$sql="select * from fac_polizas_conceptos_comprobantes
		where idComprobante='$idComprobante'";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarComprobanteConcepto($idComprobante)
	{
		$comprobante	= $this->obtenerComprobanteConcepto($idComprobante);
		
		$this->db->where('idComprobante',$idComprobante);
		$this->db->delete('fac_polizas_conceptos_comprobantes');
		
		if($this->db->affected_rows()>=1)
		{
			#$this->configuracion->registrarBitacora('Borrar fichero','Clientes',$comprobante->nombre); //Registrar bitácora
			
			if(file_exists(carpetaXml.$idComprobante.'_'.$comprobante->nombre))
			{
				unlink(carpetaXml.$idComprobante.'_'.$comprobante->nombre);
			}
			
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	public function subirComprobanteConcepto($idConcepto,$nombre,$tamano)
	{
		$data=array
		(
			'idConcepto'	=> $idConcepto,
			'nombre'		=> $nombre,
			'tamano'		=> $tamano,
			'fecha'			=> $this->fecha,
		);
		
		#$data	= procesarArreglo($data);
		$this->db->insert('fac_polizas_conceptos_comprobantes',$data);
		$idComprobante	= $this->db->insert_id();
		
		#$this->configuracion->registrarBitacora('Subir fichero','Clientes',$nombre); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?$idComprobante:0;
	}
	
	//PÓLIZAS DE COMPRAS
	public function registrarPolizaCompraVenta($fecha,$concepto='',$idCuentaCatalogo=0,$importe=0,$criterio='venta',$id=0)
	{
		$data=array
		(
			'fecha'				=> $fecha,
			'concepto'			=> $concepto,
			'tipo'				=> '3',
			'numero'			=> $this->obtenerNumeroPoliza(3),
			'fechaRegistro'		=> $this->fecha,
			'idUsuario'			=> $this->idUsuario,
			'idCompras'			=> $criterio=='compra'?$id:0,
			'idCotizacion'		=> $criterio=='venta'?$id:0,
		);
		
		$this->db->insert('fac_polizas_conceptos',$data);
		$idConcepto	= $this->db->insert_id();
		
		//REGISTRAR PARTIDA - TRANSACCIÓN
		
		$data=array
		(
			'idConcepto'		=> $idConcepto,
			'idCuentaCatalogo'	=> $idCuentaCatalogo,
			'debe'				=> $criterio=='compra'?$importe:0,
			'haber'				=> $criterio=='venta'?$importe:0,
			'concepto'			=> $concepto,
		);
		
		$this->db->insert('fac_polizas_transacciones',$data);
	}
	
	//PÓLIZAS DE INGRESOS
	public function registrarPolizaIngreso($fecha,$concepto='',$idCuentaCatalogo=0,$importe=0,$id=0)
	{
		$data=array
		(
			'fecha'				=> $fecha,
			'concepto'			=> $concepto,
			'tipo'				=> '1',
			'numero'			=> $this->obtenerNumeroPoliza(1),
			'fechaRegistro'		=> $this->fecha,
			'idUsuario'			=> $this->idUsuario,
			'idCompras'			=> 0,
			'idCotizacion'		=> 0,
			'idIngreso'			=> $id,
		);
		
		$this->db->insert('fac_polizas_conceptos',$data);
		$idConcepto	= $this->db->insert_id();
		
		//REGISTRAR PARTIDA - TRANSACCIÓN
		
		$data=array
		(
			'idConcepto'		=> $idConcepto,
			'idCuentaCatalogo'	=> 13, //DEFAULT
			'debe'				=> 0,
			'haber'				=> $importe,
			'concepto'			=> $concepto,
		);
		
		$this->db->insert('fac_polizas_transacciones',$data);
		
		$data=array
		(
			'idConcepto'		=> $idConcepto,
			'idCuentaCatalogo'	=> 4, //DEFAULT
			'debe'				=> $importe,
			'haber'				=> 0,
			'concepto'			=> $concepto,
			#'idIngreso'			=> $id,
		);
		
		$this->db->insert('fac_polizas_transacciones',$data);
	}
	
	//PÓLIZAS DE EGRESOS
	public function registrarPolizaEgreso($fecha,$concepto='',$idCuentaCatalogo=0,$importe=0,$id=0)
	{
		$data=array
		(
			'fecha'				=> $fecha,
			'concepto'			=> $concepto,
			'tipo'				=> '2',
			'numero'			=> $this->obtenerNumeroPoliza(1),
			'fechaRegistro'		=> $this->fecha,
			'idUsuario'			=> $this->idUsuario,
			'idCompras'			=> 0,
			'idCotizacion'		=> 0,
			'idEgreso'			=> $id,
		);
		
		$this->db->insert('fac_polizas_conceptos',$data);
		$idConcepto	= $this->db->insert_id();
		
		//REGISTRAR PARTIDA - TRANSACCIÓN
		
		$data=array
		(
			'idConcepto'		=> $idConcepto,
			'idCuentaCatalogo'	=> 251, //DEFAULT
			'debe'				=> $importe,
			'haber'				=> 0,
			'concepto'			=> $concepto,
		);
		
		$this->db->insert('fac_polizas_transacciones',$data);
		
		$data=array
		(
			'idConcepto'		=> $idConcepto,
			'idCuentaCatalogo'	=> 4, //DEFAULT
			'debe'				=> 0,
			'haber'				=> $importe,
			'concepto'			=> $concepto,
			#'idIngreso'			=> $id,
		);
		
		$this->db->insert('fac_polizas_transacciones',$data);
	}
	
	//REPORTE PARA BALANZA DE COMPROBACIÓN
	public function obtenerCuentasCatalogoBalanza($criterio,$tipo='todos',$inicio,$fin)
	{
		$sql=" select a.*,
		if (a.idSubCuenta>0,(select b.nombre from fac_subcuentas as b where b.idSubCuenta=a.idCuenta limit 1),(select b.nombre from fac_cuentas as b where b.idCuenta=a.idCuenta limit 1)) as cuenta,
		
		(select count(b.idCuentaCatalogo) from fac_catalogos_cuentas_detalles as b where b.idCuentaPadre=a.idCuentaCatalogo) as cuentasHijo,
		
		(select coalesce(sum(b.debe),0) from fac_polizas_transacciones as b inner join fac_polizas_conceptos as c on c.idConcepto=b.idConcepto where b.idCuentaCatalogo=a.idCuentaCatalogo and c.cancelada='0') as debe,
		(select coalesce(sum(b.haber),0) from fac_polizas_transacciones as b inner join fac_polizas_conceptos as c on c.idConcepto=b.idConcepto where b.idCuentaCatalogo=a.idCuentaCatalogo and c.cancelada='0') as haber

		from fac_catalogos_cuentas_detalles as a
		where  a.idCuentaCatalogo>0 
		and a.idCuentaPadre=0
		and a.activo='1'  ";
		
		$sql.=strlen($criterio)>0?" and (a.numeroCuenta like '%$criterio%'
		or a.descripcion like '%$criterio%' 
		or a.codigoAgrupador like '%$criterio%' 
		or a.subCuenta like '%$criterio%'  )":'';
		#echo $sql;
		
		/*$sql.=$tipo!='todos'?" and if(a.nivel=1,(select b.cuenta from fac_cuentas as b where b.idCuenta=a.idCuenta limit 1),
		(select b.cuenta from fac_cuentas as b 
		inner join fac_subcuentas as c 
		on b.idCuenta=c.idCuenta
		where c.idSubCuenta=a.idCuenta limit 1)) ='$tipo' ":'';*/
		
		$sql.=" and a.fecha between '$inicio' and '$fin' ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerCuentasBalanzaVista($idCuentaCatalogo,$numero,$filtro)
	{
		$data['cuentas']	   	 	= $this->obtenerCuentasCatalogoDetalle($idCuentaCatalogo);	
		$data['idCuentaCatalogo']	= $idCuentaCatalogo;
		$data['numero']				= $numero;
		$data['filtro']				= $filtro;

		$this->load->view('contabilidad/balanza/obtenerCuentasBalanzaVista',$data);
	}
	
	/*public function obtenerCuentasBalanzaVistaExcel($idCuentaCatalogo,$numero,$i,$excel)
	{
		$data['cuentas']	   	 	= $this->obtenerCuentasCatalogoDetalle($idCuentaCatalogo);	
		$data['idCuentaCatalogo']	= $idCuentaCatalogo;
		$data['numero']				= $numero;
		$data['i']					= $i;
		$data['excel']				= $excel;

		return $this->load->view('contabilidad/balanza/reportes/excelBalanzaDetalles',$data);
	}*/
	
	public function obtenerCuentasBalanzaVistaExcel($idCuentaCatalogo,$numero,$i,$excel,$filtro)
	{
		$cuentas	   	 	= $this->obtenerCuentasCatalogoDetalle($idCuentaCatalogo);	
		/*$data['idCuentaCatalogo']	= $idCuentaCatalogo;
		$data['numero']				= $numero;
		$data['i']					= $i;
		$data['excel']				= $excel;*/

		
		$numero		= $numero+1;

		foreach($cuentas as $row)
		{
			$saldo	= $row->saldo;
			$debe	= $row->debe;
			$haber	= $row->haber;
		
			if($row->cuentasHijo>0)
			{
				$saldos	= $this->contabilidad->obtenerSaldoCuentas($row->idCuentaCatalogo,$row->cuentasHijo,0);
				$saldo	= $saldos[0];
				$debe	= $saldos[1];
				$haber	= $saldos[2];
			}
			
			$mostrar		= false;
			if($filtro==0 and ($saldo>0 or $debe>0 or $haber>0))
			{
				$mostrar=true;
			}
			
			if($filtro==1 and $saldo==0 and $debe==0 and $haber==0)
			{
				$mostrar=true;
			}
			
			if($mostrar)
			{
				$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setIndent($numero);
			
				$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(($row->numeroCuenta), PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->descripcion, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValue('C'.$i, $saldo);
				$excel->getActiveSheet()->setCellValue('D'.$i, $debe);
				$excel->getActiveSheet()->setCellValue('E'.$i, $haber);
				$excel->getActiveSheet()->setCellValue('F'.$i, $saldo+$debe-$haber);
			
				$excel->getActiveSheet()->getStyle('C'.$i.':F'.$i)->getNumberFormat()->setFormatCode('$0.00');
				
				$i++;
			}
			
			
			if($row->cuentasHijo>0)
			{
				$i=$this->obtenerCuentasBalanzaVistaExcel($row->idCuentaCatalogo,$numero,$i,$excel,$filtro);
			}
		}
		
		return $i;
	}
	
	public function obtenerSaldoCuentaPadre($idCuentaPadre)
	{
		$sql="select coalesce(sum(saldo),0)  as saldo
		from fac_catalogos_cuentas_detalles
		where idCuentaPadre='$idCuentaPadre' ";
		
		return $this->db->query($sql)->row()->saldo;
	}
	
	public function obtenerSaldoCuentas($idCuentaPadre,$hijos,$saldoInicial=1)
	{
		if($saldoInicial==0) 
		{
			$this->saldoInicial	= 0;	
			$this->debe			= 0;	
			$this->haber		= 0;	
		}
		
		$sql=" select a.saldo, a.idCuentaCatalogo,
		(select count(b.idCuentaCatalogo) from fac_catalogos_cuentas_detalles as b where b.idCuentaPadre=a.idCuentaCatalogo) as cuentasHijo,
		
		(select coalesce(sum(b.debe),0) from fac_polizas_transacciones as b inner join fac_polizas_conceptos as c on c.idConcepto=b.idConcepto where b.idCuentaCatalogo=a.idCuentaCatalogo and c.cancelada='0') as debe,
		(select coalesce(sum(b.haber),0) from fac_polizas_transacciones as b inner join fac_polizas_conceptos as c on c.idConcepto=b.idConcepto where b.idCuentaCatalogo=a.idCuentaCatalogo and c.cancelada='0') as haber
		
		from fac_catalogos_cuentas_detalles as a
		where a.idCuentaPadre='$idCuentaPadre' ";

		$saldos	= $this->db->query($sql)->result();
		
		foreach($saldos as $row)
		{
			if($row->cuentasHijo>0)
			{
				$this->obtenerSaldoCuentas($row->idCuentaCatalogo,$row->cuentasHijo);
			}
			else
			{
				$this->saldoInicial	+=$row->saldo;
				$this->debe			+=$row->debe;
				$this->haber		+=$row->haber;
			}
		}
		
		return array($this->saldoInicial,$this->debe,$this->haber);
	}
}
?>