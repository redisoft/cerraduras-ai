<?php
class Tablero_modelo extends CI_Model
{
	protected $_fecha_actual;
	protected $_table;
	protected $idLicencia;
	protected $idUsuario;

	function __construct()
	{
		parent::__construct();
		
		$this->_fecha_actual 	= date('Y-m-d H:i:s');
		$this->idUsuario		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
	}
	
	public function obtenerCotizaciones($fecha,$h1,$h2,$permiso)
	{
		$sql=" select idCotizacion, fecha, serie
		from cotizaciones
		where estatus='0'
		and cancelada='0'
		and date(fecha)='".$fecha."'
		and time(fecha) between '".$h1.":00' and '".($h1).":59' 
		and idLicencia='$this->idLicencia'
		and folioConta >0 ";
		
		$sql.=$permiso==0?" and idUsuario='$this->idUsuario' ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerVentas($fecha,$h1,$h2,$permiso=0)
	{
		$sql="select a.ordenCompra,
		a.idCotizacion, a.facturar,
		b.alias
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where a.estatus='1'
		and a.cancelada='0'
		and a.idLicencia='$this->idLicencia' 
		and date(fechaCompra)='".$fecha."'
		and time(fechaCompra) between '".$h1.":00' and '".($h1).":59'
		and a.folioConta >0  ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=$permiso==0?" and a.idUsuario='$this->idUsuario' ":'';

		return $this->db->query($sql)->result();
	}
	
	public function obtenerCompras($fecha,$h1,$h2,$permiso)
	{
		$sql="select idCompras, nombre, total
		from compras
		where date(fechaCompra)='".$fecha."' 
		and cancelada='0'
		and idLicencia='$this->idLicencia' 
		and time(fechaCompra) between '".$h1.":00' and '".($h1).":59' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPagadoCompra($idCompra)
	{
		$sql="select coalesce(sum(pago),0) as total
		from catalogos_egresos
		where idCompra='$idCompra'
		and idForma!='4' ";
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerCobros($fecha,$h1,$h2,$permiso)
	{
		$sql="select a.ordenCompra, 
		a.idCotizacion
		from cotizaciones as a
		inner join catalogos_ingresos as b
		on a.idCotizacion=b.idVenta
		where date(b.fecha)='".$fecha."' 
		and time(b.fecha) between '".$h1.":00' and '".($h1).":59' 
		and b.idForma!='4'
		and a.idLicencia='$this->idLicencia' 
		group by a.ordenCompra, b.idVenta";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPagos($fecha,$h1,$h2,$permiso)
	{
		$sql="select a.nombre, a.idCompras
		from compras as a
		inner join catalogos_egresos as b
		on a.idCompras=b.idCompra
		where date(b.fecha)='".$fecha."'
		and time(b.fecha) between '".$h1.":00' and '".($h1).":59' 
		and idForma!='4'
		and a.idLicencia='$this->idLicencia' 
		group by a.nombre, a.idCompras";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFacturas($fecha,$h1,$h2,$permiso)
	{
		$sql="select idFactura, folio, serie
		from facturas
		where date(fecha)='".$fecha."'
		and idLicencia='$this->idLicencia' 
		and time(fecha) between '".$h1.":00' and '".($h2).":59' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerSeguimiento($fecha,$h1,$h2,$permiso)
	{
		$sql=" select a.idSeguimiento, b.empresa,
		a.idStatus, d.nombre as status, d.idStatusIgual, d.color,
		concat(c.nombre,' ',c.apellidoPaterno,' ',c.apellidoMaterno) as responsable
		from seguimiento as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join usuarios as c
		on c.idUsuario=a.idResponsable
		inner join seguimiento_status as d
		on d.idStatus=a.idStatus
		where date(a.fecha)='".$fecha."'
		and a.idLicencia='$this->idLicencia' 
		and time(a.fecha) between '".$h1.":00' and '".($h1).":59'  ";
		
		$sql.=$permiso==0?" and a.idResponsable='$this->idUsuario' ":'';
		#echo 'Permiso'.$permiso.'<br />'.$sql; exit;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerSeguimientoProveedor($fecha,$h1,$h2,$permiso)
	{
		$sql=" select a.idSeguimiento, b.empresa,
		a.idStatus, d.nombre as status, d.color, d.idStatusIgual,
		concat(c.nombre,' ',c.apellidoPaterno,' ',c.apellidoMaterno) as responsable
		from proveedores_seguimiento as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		inner join usuarios as c
		on c.idUsuario=a.idResponsable
		inner join seguimiento_status as d
		on d.idStatus=a.idStatus
		where date(a.fecha)='".$fecha."'
		and a.idLicencia='$this->idLicencia' 
		and time(a.fecha) between '".$h1.":00' and '".($h1).":59'  ";
		
		#$sql.=$permiso->todos==0?" and a.idResponsable='$this->idUsuario' ":'';
		
		return $this->db->query($sql)->result();
	}
	
	//DETALLES DE CFDI
	
	public function obtenerConceptosCfdi($idFactura)
	{
		$sql="select * from facturas_detalles
		where idFactura='$idFactura' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerEmpleado($idFactura)
	{
		$sql="select * from facturas_empleados
		where idFactura='$idFactura' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerPercepciones($idFactura)
	{
		$sql="select * from facturas_percepciones
		where idFactura='$idFactura' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDeducciones($idFactura)
	{
		$sql="select * from facturas_deducciones
		where idFactura='$idFactura' ";
		
		return $this->db->query($sql)->result();
	}
}
