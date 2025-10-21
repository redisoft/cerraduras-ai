<?php
class Reportes_model extends CI_Model
{
	protected $_fecha_actual;
	protected $_table;
	protected $idLicencia;
	protected $idUsuario;

	function __construct()
	{
		parent::__construct();
		$this->config->load('datatables',TRUE);
		$this->_table			= $this->config->item('datatables');

        $this->idUsuario		= $this->session->userdata('id');
		$this->idLicencia		= $this->session->userdata('idLicencia');
		
		if($this->session->userdata('usuarioSesion'))
		{
			$this->idEstacion		= $this->session->userdata('idEstacion'); 			
		}
		else
		{
			$this->idEstacion		= get_cookie('idEstacion'.$this->session->userdata('idCookie')); 
		}

		$datestring				= "%Y-%m-%d %H:%i:%s";

		$this->_fecha_actual	= mdate($datestring,now());
	}

	public function obtenerOrdenVenta($idVenta)
	{
		$sql="select ordenCompra
		from cotizaciones
		where idCotizacion='$idVenta'";
		
		$orden=$this->db->query($sql)->row();
		
		return $orden!=null?$orden->ordenCompra:'';
	}

	public function sumarIngresos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$cliente,$idIngreso,$criterio=0)
	{
		$ingresos=0;
		
		$sql ="	select coalesce(sum(a.pago),0) as ingresos
		from catalogos_ingresos as a
		where a.idForma!='4' 
		and a.idLicencia='$this->idLicencia'
		and a.idVenta=0 ";

		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';
		$sql.=$idIngreso!=0?" and a.idIngreso='$idIngreso' ":'';
		
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';
		
		$sql.=strlen($cliente)>0?" and  (select count(f.idCliente) from clientes as f where f.idCliente=a.idCliente and (f.empresa like '$cliente%' or concat(f.nombre,' ',f.paterno,' ',f.materno) like '$cliente%') > 0   ":'';

		$ingresos+= $this->db->query($sql)->row()->ingresos;
		
		$sql ="	select coalesce(sum(a.pago),0) as ingresos
		from catalogos_ingresos as a
		inner join cotizaciones as b
		 on a.idVenta=b.idCotizacion
		where a.idForma!='4' 
		and a.idLicencia='$this->idLicencia' 
		and b.folioConta>0";

		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';
		$sql.=$idIngreso!=0?" and a.idIngreso='$idIngreso' ":'';
		
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';
		
		$sql.=strlen($cliente)>0?" and  (select count(f.idCliente) from clientes as f where f.idCliente=a.idCliente and (f.empresa like '$cliente%' or concat(f.nombre,' ',f.paterno,' ',f.materno) like '$cliente%') > 0   ":'';
		
		$ingresos+= $this->db->query($sql)->row()->ingresos;
		
		return $ingresos;		
	}
	
	public function contarIngresos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$cliente,$idIngreso,$criterio=0)
	{
		$sql ="	(select a.idIngreso
		from catalogos_ingresos as a
		where idIngreso>0
		and idForma!='4'
		and a.idVenta=0
		and a.idLicencia='$this->idLicencia' ";
		 
		 #and idTraspaso=0 
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';
		#$sql.=$idCliente!=0?" and a.idCliente='$idCliente' ":'';
		$sql.=$idIngreso!=0?" and a.idIngreso='$idIngreso' ":'';
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';
		
		$sql.=strlen($cliente)>0?" and  (select count(f.idCliente) from clientes as f where f.idCliente=a.idCliente and (f.empresa like '$cliente%' or concat(f.nombre,' ',f.paterno,' ',f.materno) like '$cliente%') > 0   ":'';
		$sql.=" ) ";
		
		
		$sql .=" union	(select a.idIngreso
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where a.idIngreso>0
		and a.idForma!='4'
		and a.idLicencia='$this->idLicencia'
		and b.folioConta >0  ";
		 
		 #and idTraspaso=0 
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';
		#$sql.=$idCliente!=0?" and a.idCliente='$idCliente' ":'';
		$sql.=$idIngreso!=0?" and a.idIngreso='$idIngreso' ":'';
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';
		
		$sql.=strlen($cliente)>0?" and  (select count(f.idCliente) from clientes as f where f.idCliente=a.idCliente and (f.empresa like '$cliente%' or concat(f.nombre,' ',f.paterno,' ',f.materno) like '$cliente%') > 0   ":'';
		$sql.=" ) ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerIngresos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$cliente='',$numero,$limite,$idIngreso,$criterio=0)
	{
		#(select concat(d.serie,d.folio) from facturas as d where d.idFactura=a.idFactura) as cfdi
		$sql ="	(select a.*, b.idCotizacion, 
		a.fecha as fechaPago, c.empresa as cliente,
		(select d.idFactura
		from facturas as d 
		inner join facturas_ingresos as e
		on d.idFactura=e.idFactura
		where a.idIngreso=e.idIngreso
		order by d.idFactura desc limit 1) as idFactura,
		(select d.nombre from productos as d where d.idProducto=a.idProductoCatalogo) as productoCatalogo,
		(select concat(d.cuenta,'|',e.nombre) from cuentas as d
		inner join bancos as e
		on d.idBanco=e.idBanco 
		where d.idCuenta=a.idCuenta limit 1) as banco,
		(select d.nombre from catalogos_formas as d where d.idForma=a.idForma) as forma
		
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		inner join clientes as c
		on c.idCliente=b.idCliente
		where a.idIngreso>0
		and a.idForma!='4'
		
		and a.idVenta>0
		and a.idLicencia='$this->idLicencia'
		and b.folioConta >0   ";
		
		#and a.idTraspaso=0
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';

		$sql.=$idIngreso!=0?" and a.idIngreso='$idIngreso' ":'';
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';
		
		$sql.=strlen($cliente)>0?" and c.empresa like '$cliente%'  ":'';

		$sql.=" ) union (";
		
		#(select concat(d.serie,d.folio) from facturas as d where d.idFactura=a.idFactura) as cfdi,
		
		$sql .="	select a.*, '' as idCotizacion, 
		a.fecha as fechaPago, 
		(select b.empresa from clientes as b where b.idCliente=a.idCliente) as cliente,

		(select d.idFactura
		from facturas as d 
		inner join facturas_ingresos as e
		on d.idFactura=e.idFactura
		where a.idIngreso=e.idIngreso
		order by d.idFactura desc limit 1) as idFactura,
		
		(select d.nombre from productos as d where d.idProducto=a.idProductoCatalogo) as productoCatalogo,
		
		(select concat(d.cuenta,'|',e.nombre) from cuentas as d
		inner join bancos as e
		on d.idBanco=e.idBanco 
		where d.idCuenta=a.idCuenta limit 1) as banco,
		
		(select d.nombre from catalogos_formas as d where d.idForma=a.idForma) as forma
		
		
		".(sistemaActivo=='IEXE'?",(select f.matricula from clientes_academicos as f where a.idCliente=f.idCliente limit 1) as matricula":'')."
		
		
		from catalogos_ingresos as a
		where idIngreso>0
		and a.idForma!='4'
		
		and a.idVenta=0 
		and a.idLicencia='$this->idLicencia' ";
		
		#and a.idTraspaso=0
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';

		$sql.=$idIngreso!=0?" and a.idIngreso='$idIngreso' ":'';
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';
		
		$sql.=strlen($cliente)>0?" and  (select count(f.idCliente) from clientes as f where f.idCliente=a.idCliente and (f.empresa like '$cliente%' or concat(f.nombre,' ',f.paterno,' ',f.materno) like '$cliente%') > 0   ":'';
		
		
		$sql.=" ) ";
		
		$sql.=" order by fechaPago desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarGastos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idProveedor,$criterio=0)
	{
		$sql ="	select coalesce(sum(a.pago),0) as gastos
		from catalogos_egresos as a
		where a.idForma!='4'
		and a.idLicencia='$this->idLicencia' ";
		#and idTraspaso=0
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';
		$sql.=$idProveedor!=0?" and a.idProveedor='$idProveedor' ":'';
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';

		return $this->db->query($sql)->row()->gastos;
	}
	
	public function contarGastos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idProveedor,$criterio=0)
	{
		$sql ="	select a.idEgreso
		from catalogos_egresos as a
		where idEgreso>0
		and a.idForma!='4'
		and a.idLicencia='$this->idLicencia'  ";
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';
		$sql.=$idProveedor!=0?" and a.idProveedor='$idProveedor' ":'';
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerGastos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idProveedor,$numero,$limite,$criterio=0)
	{
		$sql ="	select a.*,
		(select b.nombre from catalogos_formas as b where b.idForma=a.idForma) as forma,
		
		(select c.nombre from productos as c where c.idProducto=a.idProductoCatalogo) as productoCatalogo,
		
		(select concat(b.cuenta,'|',c.nombre) from cuentas as b
		inner join bancos as c
		on c.idBanco=b.idBanco 
		where b.idCuenta=a.idCuenta limit 1) as banco
		from catalogos_egresos as a
		where idEgreso>0
		and a.idForma!='4'
		and a.idLicencia='$this->idLicencia'  ";
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=$idCuenta!=0?" and a.idCuenta='$idCuenta' ":'';
		$sql.=$idDepartamento!=0?" and a.idDepartamento='$idDepartamento' ":'';
		$sql.=$idProducto!=0?" and a.idProducto='$idProducto' ":'';
		$sql.=$idGasto!=0?" and a.idGasto='$idGasto' ":'';
		$sql.=$idProveedor!=0?" and a.idProveedor='$idProveedor' ":'';
		$sql.=$criterio==1?"  and a.iva>0 ":'';
		$sql.=$criterio==2?"  and a.iva=0 ":'';
		
		$sql.=" order by a.fecha desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}

	public function contarVentas($inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion=0,$idForma=0)
	{
		$sql ="	select a.serie
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		where a.ordenCompra is not null 
		and a.cancelada='0'
		and a.activo='1'
		and a.idLicencia='$this->idLicencia' ";
		
		#and a.folioConta>0 
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		$sql.=$idForma>0?" and a.idForma='$idForma' ":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerVentas($numero,$limite,$inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion=0,$idForma=0)
	{
		$orden=" order by a.fecha desc ";
		
		if($this->session->userdata('criterioVentas')=="a")
		{
			$orden=" order by a.fecha asc ";
		}
		
		$sql ="	select a.fechaCompra, a.total, a.subTotal, a.cancelada,
		a.iva,  a.idCotizacion, a.descuento, a.descuentoAdicional,
		a.descuentoPorcentaje, a.ivaPorcentaje,
		c.empresa, a.fechaVencimiento, a.idFactura,
		concat('',a.ordenCompra) as ordenCompra , a.idUsuario,
		d.descripcion as identificador, c.idZona, a.idTienda,
		concat(e.nombre, ' ', e.apellidoPaterno, ' ', e.apellidoMaterno) as usuario, '' as tienda,
		
		(select f.idSeguimiento from seguimiento as f where f.idVenta=a.idCotizacion order by f.fecha desc limit 1) as idSeguimiento,
		(select coalesce(sum(f.pago),0) from catalogos_ingresos  as f where f.idVenta=a.idCotizacion and f.idForma!=4) as pagado,
		
		(select f.nombre from configuracion_estaciones as f where f.idEstacion=a.idEstacion) as estacion,
		(select f.nombre from catalogos_formas as f where f.idForma=a.idForma) as formaPago
		
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		inner join usuarios as e
		on a.idUsuario=e.idUsuario 
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1'
		and a.idLicencia='$this->idLicencia' ";
		
		#and a.folioConta>0
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		$sql.=$idForma>0?" and a.idForma='$idForma' ":'';

		#$sql.=$criterio;
		$sql.=" order by a.fechaCompra desc ";
		$sql .=$numero>0? " limit $limite,$numero ":'';
		
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function sumarVentas($inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion=0,$idForma=0)
	{
		$sql ="	select a.total, a.idCotizacion,  
		a.idFactura, a.idUsuario
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1' 
		and a.idLicencia='$this->idLicencia'   ";
		
		#and a.folioConta>0
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		$sql.=$idForma>0?" and a.idForma='$idForma' ":'';
		
		$total=0;
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$cancelada	=0;
			
			if($row->idFactura!=0)
			{
				$cancelada	=$this->obtenerFacturaCancelada($row->idFactura);
			}
			
			if($cancelada==0)
			{
				$total		+=$row->total;
			}
		}
		
		return $total;
	}
	
	public function obtenerProductosVentas($idCotizacion,$entregas=0)
	{
		$sql="select a.cantidad,a.precio, a.importe,
		b.nombre,b.codigoInterno, a.idProducto, a.idProduct,
		a.descuento, a.descuentoPorcentaje,
		(select d.descripcion from unidades as d where d.idUnidad=b.idUnidad) as unidad, c.stock
		from cotiza_productos as a
		inner join productos as b
		on b.idProducto=a.idProduct
		inner join productos_inventarios as c
		on b.idProducto=c.idProducto
		where a.idCotizacion='$idCotizacion'
		and c.idLicencia='$this->idLicencia' ";

		#$sql.=$entregas==1?" and not exists(select c.idEntrega from ventas_entrega_detalles as c where c.idProducto=a.idProducto) ":"";
		#$sql.=$entregas==1?" and not exists(select c.idEntrega from ventas_entrega_detalles as c where c.idProducto=a.idProducto) ":"";
		
		return $this->db->query($sql)->result();
	}

	public function obtenerProductosVentasEntregas($idCotizacion,$entregas=0)
	{
		$sql="select a.cantidad,a.precio, a.importe,
		b.nombre,b.codigoInterno, a.idProducto, a.idProduct,
		a.descuento, a.descuentoPorcentaje,
		(select d.descripcion from unidades as d where d.idUnidad=b.idUnidad) as unidad, c.stock
		from cotiza_productos as a
		inner join productos as b
		on b.idProducto=a.idProduct
		inner join productos_inventarios as c
		on b.idProducto=c.idProducto
		where a.idCotizacion='$idCotizacion'
		and c.idLicencia='$this->idLicencia' ";

		#$sql.=$entregas==1?" and not exists(select c.idEntrega from ventas_entrega_detalles as c where c.idProducto=a.idProducto) ":"";
		$sql.=$entregas==1?" and not exists(select c.idRelacion from cotizaciones_tickets_entregas as c where c.idProducto=a.idProducto) ":"";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerProductosImpuestosVentas($idCotizacion)
	{
		$sql=" select a.nombre, a.tasa
		from cotiza_productos_impuestos as a
		inner join cotiza_productos as b
		on b.idProducto=a.idProducto
		where b.idCotizacion='$idCotizacion'
		and a.tasa>0
		group by a.nombre ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerVendedor($idUsuario)
	{
		$sql=" select concat(nombre, ' ', apellidoPaterno, ' ', apellidoMaterno) as usuario
		from usuarios
		where idUsuario='idUsuario'";
		
		$usuario	=$this->db->query($sql)->row();
		
		return $usuario!=null?$usuario->usuario:'';
	}
	
	//PARA EL REPORTE DE COMPRAS
	public function obtenerPagadoCompra($idCompra)
	{
		$sql="select coalesce(sum(pago),0) as pago
		from catalogos_egresos
		where idCompra='$idCompra'
		and idForma!='4' ";
		
		return $this->db->query($sql)->row()->pago;
	}
	
	public function contarCompras($inicio,$fin,$idProveedor)
	{
		$sql ="select a.idCompras
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor) 
		where a.idLicencia='$this->idLicencia' ";

		$sql.=" and date(a.fechaCompra) between '$inicio' and '$fin' ";
		
		if($idProveedor!=0)
		{
			$sql.=" and a.idProveedor='$idProveedor' ";
		}
		
		$query = $this->db->query($sql);
		
		return ($query->num_rows());
	}
	
	public function obtenerCompras($numero,$limite,$inicio,$fin,$idProveedor)
	{
		$sql ="select a.idCompras, a.idProveedor,  a.nombre,
		a.total, a.fechaCompra, b.empresa, a.subTotal,
		a.descuento, a.iva,
		(select c.idSeguimiento from proveedores_seguimiento as c where c.idCompra=a.idCompras order by c.fecha desc limit 1) as idSeguimiento
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor)
		where a.idLicencia='$this->idLicencia' ";
					
		$sql.=" and date(a.fechaCompra) between '$inicio' and '$fin' ";
		
		if($idProveedor!=0)
		{
			$sql.=" and a.idProveedor='$idProveedor' ";
		}
		
		$sql.=" order by fechaCompra desc ";
		
		if($numero>0)
		{
			$sql .= " limit $limite,$numero ";
		}

		return $this->db->query($sql)->result();
	}
	
	public function sumarCompras($inicio,$fin,$idProveedor)
	{
		$sql =" select coalesce(sum(total),0) as total
		from compras
		where idCompras>0
		and idLicencia='$this->idLicencia' ";
					
		$sql.=" and date(fechaCompra) between '$inicio' and '$fin' ";

		$sql.=$idProveedor!=0?" and idProveedor='$idProveedor' ":'';
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerVentasCobranzita($inicio,$fin,$idCliente,$idZona)
	{
		$sql ="	select a.ordenCompra, a.fechaCompra, a.total,
		c.empresa, c.telefono, a.fechaVencimiento, a.diasCredito,
		a.idCotizacion as idVenta, a.idFactura,
		d.descripcion as identificador, c.email
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona
		where a.estatus='1'
		and a.activo='1'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idCliente!=0?" and c.idCliente='$idCliente '":'';
		$sql.=$idZona!=0?" and d.idZona='$idZona '":'';
		$sql.=$inicio!="fecha"?" and date(a.fechaCompra) between '$inicio' and '$fin'":'';
		$sql.=" order by a.fechaCompra desc ";

		return $this->db->query($sql)->result();
	}
	
	public function contarCobranza($inicio,$fin,$idCliente)
	{
		$sql ="	select a.idCotizacion
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona
		where a.estatus=1
		and a.cancelada='0'
		and a.activo='1'
		and a.folioConta >0
		and a.idLicencia='$this->idLicencia'
		and a.total > (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4') ";
		
		$sql.=" and a.pendiente='0' ";

		$sql.=$idCliente>0?" and c.idCliente='$idCliente '":'';
		$sql.=$inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin'":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerVentasCobranza($numero,$limite,$inicio,$fin,$idCliente)
	{
		$sql ="	select a.ordenCompra, a.fechaCompra as fechaCompra, a.total,
		c.empresa, a.fechaVencimiento, a.diasCredito, c.telefono,
		a.idCotizacion as idVenta, a.idFactura,
		d.descripcion as identificador, c.email, 
		(a.total-(select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4'))  as saldo,
	 	(select date_add( (select f.fecha from facturas as f where f.idCotizacion=a.idCotizacion order by f.fecha desc limit 1) , interval a.diasCredito day))  as fechaVencimiento
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona
		where a.estatus=1
		and a.cancelada='0'
		and a.activo='1'
		and a.folioConta >0
		and a.idLicencia='$this->idLicencia'
		and a.total > (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4') ";
		
		$sql.=" and a.pendiente='0' ";

		$sql.=$idCliente>0?" and c.idCliente='$idCliente '":'';
		$sql.=$inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin'":'';
		$sql .=" order by fechaVencimiento desc";
		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function sumarVentasCobranza($inicio,$fin,$idCliente)
	{
		$sql ="	select  coalesce(sum((a.total-(select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4'))),0) as total
		from cotizaciones as a
		where a.estatus='1'
		and a.activo='1'
		and a.folioConta >0
		and a.idLicencia='$this->idLicencia' 
		and a.total > (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4')";
		
		$sql.=" and a.cancelada='0'
		and a.pendiente='0' ";
		
		$sql.=$idCliente!=0?" and a.idCliente='$idCliente '":'';
		$sql.=$inicio!="fecha"?" and date(a.fechaCompra) between '$inicio' and '$fin'":'';
		#echo $sql;
		return $this->db->query($sql)->row()->total;
	}
	
	/*public function sumarVentasCobranza($inicio,$fin,$idCliente)
	{
		$sql ="	select  a.total,
		a.idCotizacion as idVenta, a.idFactura
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona
		where a.estatus='1' ";
		
		$sql.=$idCliente!=0?" and c.idCliente='$idCliente '":'';
		$sql.=$inicio!="fecha"?" and date(a.fechaCompra) between '$inicio' and '$fin'":'';
		$sql.=" order by a.fechaCompra desc ";
		
		$ventas	=$this->db->query($sql)->result();
		
		$total=0;
		foreach($ventas as $row)
		{
			$cancelada	=0;
			
			if($row->idFactura!=0)
			{
				$cancelada=$this->reportes->obtenerFacturaCancelada($row->idFactura);
			}
		
			if($cancelada==0)
			{
				$pagado		=$this->sumarPagado($row->idVenta);
				$saldo		=$row->total-$pagado;
				
				if($saldo>0)
				{
					$total	+=$row->total-$pagado;
				}
			}
		}
		
		return $total;
	}*/
	
	public function obtenerFacturaCancelada($idFactura)
	{
		$sql="select cancelada
		from facturas
		where idFactura='$idFactura'";
		
		return $this->db->query($sql)->row()->cancelada;
	}
	
	public function obtenerFechaFactura($idFactura,$diasCredito)
	{
		$sql="select fecha 
		from facturas
		where idFactura='$idFactura'";
		
		$factura=$this->db->query($sql)->row();
		
		$sql="SELECT date_add('".substr($factura->fecha,0,10)."',interval ".$diasCredito." day) as fechaFin";
		
		return $this->db->query($sql)->row()->fechaFin;
	}
	
	public function obtenerDiasRestantes($fecha)
	{
		$sql="SELECT DATEDIFF('".$fecha."','".date('Y-m-d')."') as diasRestantes";
		
		return $this->db->query($sql)->row()->diasRestantes;
	}
	
	
	
	public function sumarPagado($idVenta)
	{
		$sql="select coalesce(sum(pago),0) as pago
		from catalogos_ingresos
		where idVenta='$idVenta' 
		and idForma!='4'";
			
		return $this->db->query($sql)->row()->pago;
	}
	
	public function contarVentasProducto()
	{
		$idProducto=$this->session->userdata('idProductoVenta');
		
		$sql="select sum(a.importe), b.nombre
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto 
		where b.idLicencia='$this->idLicencia' ";
		
		if($idProducto!="")
		{
			$sql.=" and b.idProducto='$idProducto '";
		}

		$sql .= " group by a.idProduct ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerVentasProducto($num,$limite)
	{
		$idProducto=$this->session->userdata('idProductoVenta');
		
		$sql="select sum(a.importe) as importe, b.nombre
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto 
		where b.idLicencia='$this->idLicencia' ";
		
		if($idProducto!="")
		{
			$sql.=" and b.idProducto='$idProducto '";
		}
		
		$sql .= " group by a.idProduct  
		limit $limite,$num ";
		
		return $this->db->query($sql)->result();
	}
	
	#PARA ADMINISTRAR LOS PAGOS DEL PERSONAL
	public function contarNomina()
	{
		$idPersonal=$this->input->post('idPersonal');
		
		$sql="select idPersonal
		from recursos_personal ";
		
		$sql.=$idPersonal!=0?" where idPersonal='$idPersonal'":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerNomina($numero,$limite,$idPersonal)
	{
		#$idPersonal=$this->input->post('idPersonal');
		
		$sql="select a.*, b.nombre as departamento,
		c.nombre as puesto
		from recursos_personal as a
		inner join catalogos_departamentos as b
		on a.idDepartamento=b.idDepartamento
		inner join recursos_puestos as c
		on c.idPuesto=a.idPuesto ";
		
		$sql.=$idPersonal!=0?" and a.idPersonal='$idPersonal'":'';
		
		$sql .= " order by nombre asc ";
		
		$sql.=$numero>0?" limit $limite,$numero":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarNomina($idPersonal,$dias)
	{
		$sql="select coalesce(sum(salario),0) as salario
		from recursos_personal 
		where idPersonal>0 ";
		
		$sql.=$idPersonal!=0?" and idPersonal='$idPersonal'":'';

		return $this->db->query($sql)->row()->salario*$dias;
	}
	
	public function obtenerDias($inicio,$fin)
	{
		$sql="select datediff('$fin','$inicio') as dias";
		
		return $this->db->query($sql)->row()->dias;
	}
	
	//REPORTE DE FACTURACIÓN
	public function obtenerFactura($idFactura)
	{
		$sql ="	select a.fecha, a.total, a.subTotal, 
		a.folio, a.serie, a.documento, a.empresa,
		a.cancelada, a.idFactura, a.cancelada,
		c.rfc, c.nombre as emisor
		from facturas as a
		inner join configuracion_emisores as c
		on a.idEmisor=c.idEmisor ";

		$sql.=$idFactura!=0?" and a.idFactura='$idFactura' ":'';
		#echo $sql;
		return $this->db->query($sql)->row();
	}
	
	public function contarFacturas($mes,$anio,$cliente='',$factura='',$idEmisor,$tipo,$canceladas=-1,$idEstacion=0)
	{
		$sql ="	(select a.idFactura
		from facturas as a
		inner join configuracion_emisores as c
		on a.idEmisor=c.idEmisor
		and a.pago='0'
		and a.pendiente='0'
		and a.idLicencia='$this->idLicencia' ";
					
		$sql.=strlen($cliente)>0?" and a.empresa like '$cliente%' ":'';
		$sql.=strlen($factura)>0?" and concat(a.serie,a.folio)='$factura' ":'';
		
		$sql.=$mes!='mes'?" and month(a.fecha)='$mes' and year(a.fecha)='$anio' ":'';
		
		$sql.=$idEmisor!=0?" and a.idEmisor='$idEmisor' ":'';
		$sql.=$tipo==1?" and a.documento='FACTURA' ":'';
		$sql.=$tipo==2?" and a.documento='Recibo de Nómina' ":'';
		$sql.=$tipo==3?" and a.documento='Nota de crédito' ":'';
		$sql.=$tipo==4?" and a.documento='PREFACTURA' ":'';
		$sql.=$tipo==5?" and a.documento='TRASLADO' ":'';
		
		$sql.=$canceladas!=-1?" and a.cancelada='$canceladas' ":'';

		$sql.=$idEstacion>0?" and (select count(e.idCotizacion) from cotizaciones as e where e.idCotizacion=a.idCotizacion and e.idEstacion='$idEstacion' ) > 0 ":'';
		
		$sql.=" ) union  ";
		
		
		$sql .="	(select a.idFactura
		from facturas as a
		inner join configuracion_emisores as c
		on a.idEmisor=c.idEmisor
		
		inner join cotizaciones as d
		on d.idCotizacion=a.idCotizacion
		
		and a.pago='0'
		and a.idLicencia='$this->idLicencia'
		and d.cancelada='0'
		and d.activo='1' ";
					
		$sql.=strlen($cliente)>0?" and a.empresa like '$cliente%' ":'';
		$sql.=strlen($factura)>0?" and concat(a.serie,a.folio)='$factura' ":'';
		
		$sql.=$mes!='mes'?" and month(a.fecha)='$mes' and year(a.fecha)='$anio' ":'';
		
		
		$sql.=$idEmisor!=0?" and a.idEmisor='$idEmisor' ":'';
		$sql.=$tipo==1?" and a.documento='FACTURA' ":'';
		$sql.=$tipo==2?" and a.documento='Recibo de Nómina' ":'';
		$sql.=$tipo==3?" and a.documento='Nota de crédito' ":'';
		$sql.=$tipo==4?" and a.documento='PREFACTURA' ":'';
		$sql.=$tipo==5?" and a.documento='TRASLADO' ":'';
		
		$sql.=$canceladas!=-1?" and a.cancelada='$canceladas' ":'';
		
		$sql.=" and a.pendiente='1' ";
		#$sql.=$pendientes==0?" and a.pendiente='0' ":" and a.pendiente='1' ";
		
		$sql.=$idEstacion>0?" and (select count(e.idCotizacion) from cotizaciones as e where e.idCotizacion=a.idCotizacion and e.idEstacion='$idEstacion' ) > 0 ":'';
		
		$sql.=" )   ";
		

		return $this->db->query($sql)->num_rows();
	}
	public function obtenerFacturas($mes,$anio,$cliente,$numero,$limite,$factura,$idEmisor,$tipo,$canceladas=-1,$pendientes=0,$idEstacion=0)
	{
		$sql ="	(select a.fecha as fecha, a.total, a.subTotal,  a.iva,
		a.folio, a.serie, a.documento, a.empresa, a.idCotizacion, a.pendiente,
		a.cancelada, a.idFactura, a.cancelada,
		c.rfc, c.nombre as emisor, a.metodoPago,
		(select f.nombre from configuracion_estaciones as f 
		inner join cotizaciones as g
		on g.idEstacion=f.idEstacion
		where g.idCotizacion=a.idCotizacion limit 1) as estacion
		from facturas as a
		inner join configuracion_emisores as c
		on a.idEmisor=c.idEmisor
		and a.pago='0'
		and a.pendiente='0'
		and a.idLicencia='$this->idLicencia' ";
					
		$sql.=strlen($cliente)>0?" and a.empresa like '$cliente%' ":'';
		$sql.=strlen($factura)>0?" and concat(a.serie,a.folio)='$factura' ":'';
		
		$sql.=$mes!='mes'?" and month(a.fecha)='$mes' and year(a.fecha)='$anio' ":'';
		
		
		$sql.=$idEmisor!=0?" and a.idEmisor='$idEmisor' ":'';
		$sql.=$tipo==1?" and a.documento='FACTURA' ":'';
		$sql.=$tipo==2?" and a.documento='Recibo de Nómina' ":'';
		$sql.=$tipo==3?" and a.documento='Nota de crédito' ":'';
		$sql.=$tipo==4?" and a.documento='PREFACTURA' ":'';
		$sql.=$tipo==5?" and a.documento='TRASLADO' ":'';
		
		$sql.=$canceladas!=-1?" and a.cancelada='$canceladas' ":'';

		$sql.=$idEstacion>0?" and (select count(e.idCotizacion) from cotizaciones as e where e.idCotizacion=a.idCotizacion and e.idEstacion='$idEstacion' ) > 0 ":'';
		
		$sql.=" ) union  ";
		
		
		$sql .="	(select a.fecha as fecha, a.total, a.subTotal,  a.iva,
		a.folio, a.serie, a.documento, a.empresa, a.idCotizacion, a.pendiente,
		a.cancelada, a.idFactura, a.cancelada,
		c.rfc, c.nombre as emisor, a.metodoPago,
		(select f.nombre from configuracion_estaciones as f 
		inner join cotizaciones as g
		on g.idEstacion=f.idEstacion
		where g.idCotizacion=a.idCotizacion limit 1) as estacion
		from facturas as a
		inner join configuracion_emisores as c
		on a.idEmisor=c.idEmisor
		
		inner join cotizaciones as d
		on d.idCotizacion=a.idCotizacion
		
		and a.pago='0'
		and a.idLicencia='$this->idLicencia'
		and d.cancelada='0'
		and d.activo='1' ";
					
		$sql.=strlen($cliente)>0?" and a.empresa like '$cliente%' ":'';
		$sql.=strlen($factura)>0?" and concat(a.serie,a.folio)='$factura' ":'';
		
		$sql.=$mes!='mes'?" and month(a.fecha)='$mes' and year(a.fecha)='$anio' ":'';

		
		$sql.=$idEmisor!=0?" and a.idEmisor='$idEmisor' ":'';
		$sql.=$tipo==1?" and a.documento='FACTURA' ":'';
		$sql.=$tipo==2?" and a.documento='Recibo de Nómina' ":'';
		$sql.=$tipo==3?" and a.documento='Nota de crédito' ":'';
		$sql.=$tipo==4?" and a.documento='PREFACTURA' ":'';
		$sql.=$tipo==5?" and a.documento='TRASLADO' ":'';
		
		$sql.=$canceladas!=-1?" and a.cancelada='$canceladas' ":'';
		
		$sql.=$pendientes==0?" and a.pendiente='0' ":"";
		
		$sql.=$idEstacion>0?" and (select count(e.idCotizacion) from cotizaciones as e where e.idCotizacion=a.idCotizacion and e.idEstacion='$idEstacion' ) > 0 ":'';
		
		$sql.=" )   ";
		
		$sql.=" order by fecha desc ";
		
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		#echo $sql;
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarFacturas($mes,$anio,$cliente='',$factura='',$idEmisor,$tipo,$canceladas=-1,$idEstacion=0)
	{
		$total=0;
		
		$sql ="	select coalesce(sum(a.total),0) as total
		from facturas as a
		where a.idFactura>0
		and a.idLicencia='$this->idLicencia'
		and a.pendiente='0'";
					
		$sql.=strlen($cliente)>0?" and a.empresa like '$cliente%' ":'';
		$sql.=strlen($factura)>0?" and concat(a.serie,a.folio)='$factura' ":'';
		
		$sql.=$mes!='mes'?" and month(a.fecha)='$mes' and year(a.fecha)='$anio' ":'';
		
		$sql.=$idEmisor!=0?" and a.idEmisor='$idEmisor' ":'';
		$sql.=$tipo==1?" and a.documento='FACTURA' ":'';
		$sql.=$tipo==2?" and a.documento='Recibo de Nómina' ":'';
		$sql.=$tipo==3?" and a.documento='Nota de crédito' ":'';
		$sql.=$tipo==4?" and a.documento='PREFACTURA' ":'';
		$sql.=$tipo==5?" and a.documento='TRASLADO' ":'';
		
		$sql.=$canceladas!=-1?" and a.cancelada='$canceladas' ":'';
		
		$sql.=$idEstacion>0?" and (select count(e.idCotizacion) from cotizaciones as e where e.idCotizacion=a.idCotizacion and e.idEstacion='$idEstacion' ) > 0 ":'';
		
		$total	= $this->db->query($sql)->row()->total;
		
		
		$sql ="	select coalesce(sum(a.total),0) as total
		from facturas as a
		inner join cotizaciones as b
		on b.idCotizacion=a.idCotizacion
		where a.idFactura>0
		and a.idLicencia='$this->idLicencia'
		and a.pendiente='1'
		and b.cancelada='0'
		and b.activo='1' ";
					
		$sql.=strlen($cliente)>0?" and a.empresa like '$cliente%' ":'';
		$sql.=strlen($factura)>0?" and concat(a.serie,a.folio)='$factura' ":'';
		
		$sql.=$mes!='mes'?" and month(a.fecha)='$mes' and year(a.fecha)='$anio' ":'';
		
		$sql.=$idEmisor!=0?" and a.idEmisor='$idEmisor' ":'';
		$sql.=$tipo==1?" and a.documento='FACTURA' ":'';
		$sql.=$tipo==2?" and a.documento='Recibo de Nómina' ":'';
		$sql.=$tipo==3?" and a.documento='Nota de crédito' ":'';
		$sql.=$tipo==4?" and a.documento='PREFACTURA' ":'';
		$sql.=$tipo==5?" and a.documento='TRASLADO' ":'';
		
		$sql.=$canceladas!=-1?" and a.cancelada='$canceladas' ":'';
		
		$sql.=$idEstacion>0?" and (select count(e.idCotizacion) from cotizaciones as e where e.idCotizacion=a.idCotizacion and e.idEstacion='$idEstacion' ) > 0 ":'';
		
		$total+= $this->db->query($sql)->row()->total;
		
		return $total;
	}
	
	//PARA LOS DETALLES EN LOS REPORTES Y VENTAS
	public function sumarFacturasParciales($idCotizacion)
	{
		$sql="select coalesce(sum(total),0) as total
		from facturas
		where cancelada='0'
		and idCotizacion='$idCotizacion'
		and pendiente='0' ";
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerCotizacionFactura($idCotizacion)
	{
		$sql="select total
		from cotizaciones
		where idCotizacion='$idCotizacion' ";
		
		$cotizacion=$this->db->query($sql)->row();
		
		return $cotizacion!=null?$cotizacion->total:0;
	}
	
	public function obtenerFoliosParciales($idCotizacion)
	{
		$sql="select concat(a.serie,a.folio) as folio, a.pendiente
		from facturas as a
		inner join rel_factura_cotizacion as b
		on a.idFactura=b.idFactura
		where b.idCotizacion='$idCotizacion'
		and cancelada='0'
		and a.documento!='TRASLADO' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFoliosPendientes($idCotizacion)
	{
		$sql=" select concat(a.serie,a.folio) as folio, a.pendiente
		from facturas as a
		where a.idCotizacion='$idCotizacion'
		and pendiente='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerProductoFactura($idFactura)
	{
		$sql=" select nombre
		from facturas_detalles
		where idFactura='$idFactura' ";
		
		return $this->db->query($sql)->row();
	}
	
	#AUXILIAR DE PROVEEDORES
	#-----------------------------------------------------------------------------------------------------#
	public function obtenerAuxiliarProveedores($inicio,$fin,$idProveedor)
	{
		$sql="select a.*, b.precio, c.idCompras,
		c.nombre as orden, a.fecha,
		c.idCompras,
		sum(a.cantidad*b.precio) as monto
		from compras_recibido as a
		inner join compra_detalles as b
		on a.idDetalle=b.idDetalle
		inner join compras as c
		on c.idCompras=b.idCompra
		and c.idLicencia='$this->idLicencia' ";
		
		$sql.=$inicio!='fecha'?" and date(a.fecha) between '$inicio' and '$fin' ":'';
		$sql.=$idProveedor>0?" and c.idProveedor='$idProveedor' ":'';
		
		$sql.=" group by a.remision
		order by c.nombre desc, 
		a.remision desc ";

		return $this->db->query($sql)->result();
	}
	
	public function sumarAuxiliarProveedores($inicio,$fin,$idProveedor)
	{
		$sql="select 
		coalesce(sum(a.cantidad*b.precio),0) as monto
		from compras_recibido as a
		inner join compra_detalles as b
		on a.idDetalle=b.idDetalle
		inner join compras as c
		on c.idCompras=b.idCompra 
		and c.idLicencia='$this->idLicencia' ";
		
		$sql.=$inicio!='fecha'?" and date(a.fecha) between '$inicio' and '$fin' ":'';
		$sql.=$idProveedor>0?" and c.idProveedor='$idProveedor' ":'';
		
		#$sql.=" group by a.remision  ";

		return $this->db->query($sql)->row()->monto;
	}
	
	public function obtenerProveedor($idProveedor)
	{
		$sql="select * from proveedores
		where idProveedor='$idProveedor'";
		
		return $this->db->query($sql)->row();	
	}
	
	public function obtenerProveedores()
	{
		$sql="select empresa, idProveedor
		from proveedores  ";
		
		return $this->db->query($sql)->result();
	}
	
	#PRONOSTICO DE PAGOS
	#-----------------------------------------------------------------------------------------------------#
	public function obtenerComprasProveedor($inicio,$fin,$idProveedor)
	{
		$sql="select  a.empresa,
		a.idProveedor, b.total, 
		b.fechaCompra, b.nombre, 
		b.idCompras
		from proveedores as a
		inner join compras as b
		on a.idProveedor=b.idProveedor ";
		
		$sql.=$inicio!='fecha'?" and date(b.fechaCompra) between '$inicio' and '$fin' ":'';
		$sql.=$idProveedor>0?" and a.idProveedor='$idProveedor' ":'';
		
		
		$sql.=" order by b.nombre desc ";

		return $this->db->query($sql)->result();
	}
	
	public function sumarPagadoCompra($idCompra)
	{
		$sql="select coalesce(sum(pago),0) as pago
		from catalogos_egresos
		where idCompra='$idCompra'";
		
		return $this->db->query($sql)->row()->pago;
	}
	
	#REPORTE CAJA CHICA
	#-----------------------------------------------------------------------------------------------------------------
	public function contarReporteCajaChica($mes,$anio,$criterio)
	{
		$sql="select idEgreso
		from catalogos_egresos as a
		inner join catalogos_productos as b
		on a.idProducto=b.idProducto
		where a.idGasto>0 
		and a.cajaChica=1
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$criterio!='criterio'?"and (a.producto like '%$criterio%'
		or b.nombre like '%$criterio%') ":'';
		
		#$sql.=strlen($inicio)>7?" and date(a.fecha) between '$inicio' and '$fin' ":'';
		$sql.="and month(a.fecha)='$mes' 
		and year(a.fecha)='$anio' ";
		
		#echo $sql;
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerReporteCajaChica($numero,$limite,$mes,$anio,$criterio)
	{
		/*$sql="select a.*, b.cuenta, c.nombre as departamento,
		d.nombre, e.nombre as concepto, 
		f.nombre as tipoGasto
		from catalogos_egresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		inner join catalogos_departamentos as c
		on a.idDepartamento=c.idDepartamento
		inner join catalogos_nombres as d
		on a.idNombre=d.idNombre
		inner join catalogos_productos as e
		on a.idProducto=e.idProducto
		inner join catalogos_gastos as f
		on a.idGasto=f.idGasto 
		where a.cajaChica=1 ";*/
		
		$sql="select a.*
		from catalogos_egresos as a
		where a.cajaChica=1  
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$criterio!='criterio'?"and (a.producto like '%$criterio%'
		or e.nombre like '%$criterio%') ":'';
		
		$sql.="and month(a.fecha)='$mes' 
		and year(a.fecha)='$anio' ";
		
		#$sql.=strlen($inicio)>7?" and date(a.fecha) between '$inicio' and '$fin' ":'';
		
		$sql .= "order by a.fecha desc ";
		
		#echo $sql;
		$sql .= $numero>0? "limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarReporteCajaChica($mes,$anio,$criterio)
	{
		$sql="select coalesce(sum(a.pago),0) as pago
		from catalogos_egresos as a
		inner join catalogos_departamentos as c
		on a.idDepartamento=c.idDepartamento
		inner join catalogos_nombres as d
		on a.idNombre=d.idNombre
		inner join catalogos_productos as e
		on a.idProducto=e.idProducto
		inner join catalogos_gastos as f
		on a.idGasto=f.idGasto 
		where a.cajaChica=1
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$criterio!='criterio'?"and (a.producto like '%$criterio%'
		or e.nombre like '%$criterio%') ":'';
		
		$sql.="and month(a.fecha)='$mes' 
		and year(a.fecha)='$anio' ";

		return $this->db->query($sql)->row()->pago;
	}
	
	//REPORTE DE FLUJO DE CAJA CHICA
	public function obtenerIngresoCajaChica($idProducto,$mes,$anio)
	{
		$sql="select coalesce(sum(a.pago),0) as pago
		from catalogos_egresos as a
		inner join catalogos_productos as b
		on a.idProducto=b.idProducto
		where cajaChica=1
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and (month(a.fecha)<'$mes' 
		and  year(a.fecha)<='$anio') ";
		
		$sql.=$idProducto>0?" and b.idProducto='$idProducto'":'';
		
		#echo $sql.'<br />';
		return $this->db->query($sql)->row()->pago;
	}
	
	public function obtenerEgresoCajaChica($idProducto,$mes,$anio)
	{
		$sql="select coalesce(sum(a.importe),0) as pago
		from catalogos_caja as a
		inner join catalogos_egresos as b
		on a.idEgreso=b.idEgreso
		
		where a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and (month(b.fecha)<'$mes' 
		and  year(b.fecha)<='$anio') ";
		
		$sql.=$idProducto>0?" and b.idProducto='$idProducto'":'';
		
		#echo $sql;
		return $this->db->query($sql)->row()->pago;
	}
	
	//PRONOSTICO DE INGRESOS
	public function sumarPronosticoIngresos($inicio,$fin,$idCliente)
	{
		$sql ="	select coalesce(sum(a.pago),0) as ingresos
		from catalogos_ingresos as a
		where a.idForma='4'
		and a.idLicencia='$this->idLicencia' ";
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		$sql.=$idCliente!=0?" and a.idCliente='$idCliente' ":'';
		
		return $this->db->query($sql)->row()->ingresos;
	}
	
	public function obtenerPronosticoIngresos($inicio,$fin,$idCliente)
	{
		$sql ="	select a.*
		from catalogos_ingresos as a
		where a.idForma='4'
		and a.idLicencia='$this->idLicencia' ";
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		$sql.=$idCliente!=0?" and a.idCliente='$idCliente' ":'';
		$sql.=" order by a.fecha desc ";
		#$sql.= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	//PRONOSTICO DE INGRESOS
	public function sumarPronosticoGastos($inicio,$fin,$idProveedor)
	{
		$sql ="	select coalesce(sum(a.pago),0) as gastos
		from catalogos_egresos as a
		where a.idForma='4'
		and a.idLicencia='$this->idLicencia' ";
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		$sql.=$idProveedor!=0?" and a.idProveedor='$idProveedor' ":'';

		return $this->db->query($sql)->row()->gastos;
	}
	
	public function obtenerPronosticoGastos($inicio,$fin,$idProveedor)
	{
		$sql ="	select a.*
		from catalogos_egresos as a
		where a.idForma='4'
		and a.idLicencia='$this->idLicencia' ";
					
		$sql.=" and date(a.fecha) between '$inicio' and '$fin' ";
		$sql.=$idProveedor!=0?" and a.idProveedor='$idProveedor' ":'';
		$sql.=" order by a.fecha desc ";
		#$sql.= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	//REPORTE DE INVENTARIOS
	public function obtenerUltimaDiaFecha($fecha)
	{
		$sql="select day(last_day('$fecha')) as dia";
		
		return $this->db->query($sql)->row()->dia;
	}
	
	public function obtenerUltimaFecha($fecha)
	{
		$sql="select last_day('$fecha') as dia";
		
		return $this->db->query($sql)->row()->dia;
	}
	
	public function contarInventarios($criterio='',$idLinea,$idUnidad,$idTienda=0)
	{
		$sql ="	select a.idProducto
		from  productos as a
		inner join productos_lineas as b
		on a.idLinea=b.idLinea
		inner join productos_inventarios as c
		on a.idProducto=c.idProducto
		where c.idLicencia='$this->idLicencia'    ";
		
		/*$sql.=$idTienda>0?" inner join tiendas_productos as c
		on c.idProducto=a.idProducto
		and c.idTienda='$idTienda'   ":'';*/
		
		$sql.=" and a.servicio=0  ";
		
		$sql.=strlen($criterio)>0?" and (a.nombre like '%$criterio%' or a.codigoInterno like '%$criterio%') ":'';
		$sql.=$idLinea!=0?" and a.idLinea='$idLinea' ":'';
		$sql.=$idUnidad!='0'?" and a.idUnidad='$idUnidad' ":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerInventarios($numero,$limite,$criterio='',$idLinea,$idUnidad,$idTienda=0)
	{
		$sql ="	select a.nombre as producto,  c.precioA,
		a.codigoInterno, a.idProducto, a.precioB, a.precioC, a.precioD,
		
		(select d.nombre from fac_catalogos_unidades as d where d.idUnidad=a.idUnidad) as unidad,
		
		b.nombre as linea, a.idLinea, c.stock
		from  productos as a
		inner join productos_lineas as b
		on a.idLinea=b.idLinea
		
		inner join productos_inventarios as c
		on a.idProducto=c.idProducto
		where c.idLicencia='$this->idLicencia'  ";
		
		/*$sql.=$idTienda>0?" inner join tiendas_productos as c
		on c.idProducto=a.idProducto
		where c.idTienda='$idTienda'   ":'';*/
		
		$sql.=" and a.servicio=0 ";		
		
		$sql.=strlen($criterio)>0?" and (a.nombre like '%$criterio%' or a.codigoInterno like '%$criterio%') ":'';
		$sql.=$idLinea!=0?" and a.idLinea='$idLinea' ":'';
		$sql.=$idUnidad!='0'?" and a.idUnidad='$idUnidad' ":'';
		
		$sql.= " order by a.stock desc, a.nombre asc ";
		$sql .= $numero>0?"limit $limite,$numero ":'';
		
		#echo $sql;
		#exit;
		#echo $modelo;
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerInventariosUnidades()
	{
		$sql =" select a.idUnidad, a.nombre
		from fac_catalogos_unidades as a
		where (select count(b.idProducto) from productos as b where b.idUnidad=a.idUnidad and b.activo='1') >0";
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarInventarios($criterio,$idLinea,$idUnidad,$idTienda=0)
	{
		/*$sql ="	select ".($idTienda==0?" coalesce(sum(a.precioA*a.stock),0) as total ":"  coalesce(sum(a.precioA*c.cantidad),0) as total ")."
		from  productos as a
		inner join productos_lineas as b
		on a.idLinea=b.idLinea ";
		
		$sql.=$idTienda>0?" inner join tiendas_productos as c
		on c.idProducto=a.idProducto
		where c.idTienda='$idTienda'   ":'';
		
		$sql.=" and a.servicio=0  ";*/
		
		
		$sql ="	select coalesce(sum(b.precioA*b.stock),0) as total
		from  productos as a
		inner join productos_inventarios as b
		on a.idProducto=b.idProducto 
		where a.servicio=0 
		and b.idLicencia= '$this->idLicencia' ";
		
		
		$sql.=strlen($criterio)>0?" and (a.nombre like '%$criterio%' or a.codigoInterno like '%$criterio%') ":'';
		$sql.=$idLinea!=0?" and a.idLinea='$idLinea' ":'';
		$sql.=$idUnidad!='0'?" and a.idUnidad='$idUnidad' ":'';

		return $this->db->query($sql)->row()->total;
	}
	
	//PARA LOS PAGOS
	public function contarPagos($inicio,$fin,$idProveedor)
	{
		$sql ="select a.idCompras
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor)
		where a.idLicencia='$this->idLicencia'  ";

		$sql.=" and date(a.fechaCompra) between '$inicio' and '$fin' ";
		
		if($idProveedor!=0)
		{
			$sql.=" and a.idProveedor='$idProveedor' ";
		}
		
		$sql.=" and (select coalesce(sum(pago),0) from catalogos_egresos as c where a.idCompras=c.idCompra and c.idForma!='4')<a.total ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerPagos($numero,$limite,$inicio,$fin,$idProveedor)
	{
		$sql =" select a.idCompras, a.idProveedor,  a.nombre,
		a.total, a.fechaCompra, b.empresa, a.subTotal,
		a.descuento, a.iva, a.diasCredito
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor) 
		where a.idLicencia='$this->idLicencia' ";
					
		$sql.=" and date(a.fechaCompra) between '$inicio' and '$fin' ";
		
		if($idProveedor!=0)
		{
			$sql.=" and a.idProveedor='$idProveedor' ";
		}
		
		$sql.=" and (select coalesce(sum(pago),0) from catalogos_egresos as c where a.idCompras=c.idCompra and c.idForma!='4')<a.total ";
		
		$sql.=" order by fechaCompra desc ";
		
		if($numero>0)
		{
			$sql .= " limit $limite,$numero ";
		}

		return $this->db->query($sql)->result();
	}
	
	public function sumarPagos($inicio,$fin,$idProveedor)
	{
		$sql =" select a.idCompras, a.idProveedor,  a.nombre,
		a.total, a.fechaCompra, b.empresa, a.subTotal,
		a.descuento, a.iva, a.diasCredito
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor)
		where a.idLicencia='$this->idLicencia'  ";
					
		$sql.=" and date(a.fechaCompra) between '$inicio' and '$fin' ";
		
		if($idProveedor!=0)
		{
			$sql.=" and a.idProveedor='$idProveedor' ";
		}
		
		$sql.=" and (select coalesce(sum(pago),0) from catalogos_egresos as c where a.idCompras=c.idCompra and c.idForma!='4')<a.total ";
		
		$total	=0;
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$pagado		=$this->obtenerPagadoCompra($row->idCompras);
			$total		+=$row->total-$pagado;
		}
		
		return $total;
	}
	
	public function obtenerFechaFin($fecha,$diasCredito)
	{	
		$sql="SELECT date_add('".substr($fecha,0,10)."',interval ".$diasCredito." day) as fechaFin";
		
		return $this->db->query($sql)->row()->fechaFin;
	}
	
	public function obtenerFechaFinCompleta($fecha,$diasCredito)
	{	
		$sql="SELECT date_add('".$fecha."',interval ".$diasCredito." day) as fechaFin";
		
		return $this->db->query($sql)->row()->fechaFin;
	}
	
	public function obtenerDiasSemana($fecha)
	{	
		$sql="select adddate('".$fecha."', INTERVAL 1-DAYOFWEEK('".$fecha."') day) diaInicio, adddate('".$fecha."', INTERVAL 7-DAYOFWEEK('".$fecha."') day) diaFin;";
		
		return $this->db->query($sql)->row();
	}
	
	
	//REPORTE DE MOBILIARIO Y EQUIPO
	public function contarMobiliario($idInventario,$idProveedor)
	{
		$sql ="	select a.idInventario
		from  inventarios as a
		inner join rel_inventario_proveedor as b
		on a.idInventario=b.idInventario
		where a.idInventario>0
		and a.idLicencia='$this->idLicencia' ";

		$sql.=$idInventario!=0?" and a.idInventario='$idInventario' ":'';
		$sql.=$idProveedor!=0?" and b.idProveedor='$idProveedor' ":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerMobiliario($numero,$limite,$idInventario,$idProveedor)
	{
		$sql ="	select a.*, b.costo, c.empresa
		from  inventarios as a
		inner join rel_inventario_proveedor as b
		on a.idInventario=b.idInventario
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idInventario>0 
		and a.idLicencia='$this->idLicencia' ";

		$sql.=$idInventario!=0?" and a.idInventario='$idInventario' ":'';
		$sql.=$idProveedor!=0?" and b.idProveedor='$idProveedor' ":'';
		
		$sql.=" order by a.nombre asc ";
		$sql .= $numero>0?"limit $limite,$numero ":'';
		
		#echo $modelo;
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarMobiliario($idInventario,$idProveedor)
	{
		$sql ="	select coalesce(sum(a.cantidad*b.costo),0) as total
		from  inventarios as a
		inner join rel_inventario_proveedor as b
		on a.idInventario=b.idInventario
		where a.idInventario>0
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$idInventario!=0?" and a.idInventario='$idInventario' ":'';
		$sql.=$idProveedor!=0?" and b.idProveedor='$idProveedor' ":'';

		return $this->db->query($sql)->row()->total;
	}
	
	//OBTENER LOS DATOS PARA LA NÓMINA
	public function obtenerPercepciones($idFactura)
	{
		$sql="select * from facturas_percepciones
		where idFactura='$idFactura'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDeducciones($idFactura)
	{
		$sql="select * from facturas_deducciones
		where idFactura='$idFactura'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerEmpleado($idFactura)
	{
		$sql="select * from facturas_empleados
		where idFactura='$idFactura'";
		
		return $this->db->query($sql)->row();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE DEPOSITOS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarDepositos($fecha,$idCuenta,$idEmisor)
	{
		$sql =" select a.idIngreso
		from catalogos_ingresos as a
		where month(a.fecha) ='".substr($fecha,5,2)."'
		and year(a.fecha) ='".substr($fecha,0,4)."' 
		and (a.idForma ='2'
		or a.idForma='3'
		or a.idForma='5'
		or a.idForma='6' )
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":'';
		
		$sql.=$idEmisor>0?" and (select count(b.idEmisor)
		from cuentas as b
		where b.idCuenta=a.idCuenta
		and b.idEmisor='$idEmisor') > 0 ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerDepositos($numero,$limite,$fecha,$idCuenta,$idEmisor)
	{
		$sql =" select a.idIngreso, a.fecha, a.pago, a.transferencia,
		a.formaPago, a.cheque, a.factura as facturaIngreso,
		(select concat(d.serie,d.folio) from facturas as d inner join cotizaciones as c on c.idCotizacion=d.idCotizacion where a.idVenta=c.idCotizacion limit 1) as factura,
		if(a.idVenta>0,(select b.empresa from clientes as b inner join cotizaciones as c on c.idCliente=b.idCliente where a.idVenta=c.idCotizacion limit 1),(select b.empresa from clientes as b where b.idCliente=a.idCliente limit 1)) as cliente
		from catalogos_ingresos as a 

		where month(a.fecha) ='".substr($fecha,5,2)."'
		and year(a.fecha) ='".substr($fecha,0,4)."'
		and (a.idForma ='2'
		or a.idForma='3'
		or a.idForma='5'
		or a.idForma='6' ) 
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta' ":'';
		
		$sql.=$idEmisor>0?" and (select count(b.idEmisor)
		from cuentas as b
		where b.idCuenta=a.idCuenta
		and b.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.=" order by a.fecha desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function sumarDepositos($fecha,$idCuenta,$idEmisor)
	{
		$sql =" select coalesce(sum(a.pago),0) as totales
		from catalogos_ingresos as a
		where month(a.fecha) ='".substr($fecha,5,2)."'
		and year(a.fecha) ='".substr($fecha,0,4)."' 
		and (a.idForma ='2'
		or a.idForma='3'
		or a.idForma='5'
		or a.idForma='6' ) 
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":'';
		$sql.=$idEmisor>0?" and (select count(b.idEmisor)
		from cuentas as b
		where b.idCuenta=a.idCuenta
		and b.idEmisor='$idEmisor') > 0 ":'';
		
		return $this->db->query($sql)->row()->totales;
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE RETIROS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarRetiros($fecha,$idCuenta,$idEmisor=0)
	{
		$sql =" select a.idEgreso
		from catalogos_egresos as a
		where month(a.fecha) ='".substr($fecha,5,2)."'
		and year(a.fecha) ='".substr($fecha,0,4)."' 
		and (a.idForma ='2'
		or a.idForma='3'
		or a.idForma='5'
		or a.idForma='6' ) 
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":'';
		$sql.=$idEmisor>0?" and (select count(b.idEmisor)
		from cuentas as b
		where b.idCuenta=a.idCuenta
		and b.idEmisor='$idEmisor') > 0 ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerRetiros($numero,$limite,$fecha,$idCuenta,$idEmisor=0)
	{
		$sql =" select a.idEgreso, a.fecha, a.pago, a.transferencia,
		a.formaPago, a.cheque, a.factura,
		if(a.idProveedor>0,(select b.empresa from proveedores as b where b.idProveedor=a.idProveedor),
		(select b.empresa from proveedores as b 
		inner join compras as c
		on c.idProveedor=b.idProveedor
		where a.idCompra=c.idCompras)) as proveedor
		from catalogos_egresos as a
		where month(a.fecha) ='".substr($fecha,5,2)."'
		and year(a.fecha) ='".substr($fecha,0,4)."'
		and (a.idForma ='2'
		or a.idForma='3'
		or a.idForma='5'
		or a.idForma='6' ) 
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":'';
		
		$sql.=$idEmisor>0?" and (select count(b.idEmisor)
		from cuentas as b
		where b.idCuenta=a.idCuenta
		and b.idEmisor='$idEmisor') > 0 ":'';

		$sql.=" order by a.fecha desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function sumarRetiros($fecha,$idCuenta,$idEmisor=0)
	{
		$sql =" select coalesce(sum(a.pago),0) as totales
		from catalogos_egresos as a
		where month(a.fecha) ='".substr($fecha,5,2)."'
		and year(a.fecha) ='".substr($fecha,0,4)."' 
		and (a.idForma ='2'
		or a.idForma='3'
		or a.idForma='5'
		or a.idForma='6' ) 
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":'';
		$sql.=$idEmisor>0?" and (select count(b.idEmisor)
		from cuentas as b
		where b.idCuenta=a.idCuenta
		and b.idEmisor='$idEmisor') > 0 ":'';

		return $this->db->query($sql)->row()->totales;
	}
	
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE INGRESOS FACTURADOS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarIngresoFacturados($fecha,$idCuenta,$idEmisor)
	{
		$sql =" select a.idIngreso
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		inner join facturas as c
		on c.idCotizacion=b.idCotizacion
		where c.cancelada='0'
		and b.cancelada='0'
		and a.idForma!='4'
		and (month(a.fecha) ='".substr($fecha,5,2)."'
		and year(a.fecha) ='".substr($fecha,0,4)."' )
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":'';
		$sql.=$idEmisor>0?" and c.idEmisor='$idEmisor'":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerIngresosFacturados($numero,$limite,$fecha,$idCuenta,$idEmisor)
	{
		$sql =" select a.idIngreso, a.fecha, a.pago, a.transferencia,
		a.formaPago, a.cheque, b.empresa as cliente, concat(d.serie,d.folio) as factura,
		d.iva, d.fecha as fechaFactura, d.idFactura, a.factura as facturaIngreso
		from catalogos_ingresos as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join cotizaciones as c
		on a.idVenta=c.idCotizacion
		inner join facturas as d
		on c.idCotizacion=d.idCotizacion
		where c.cancelada='0'
		and d.cancelada='0'
		and a.idForma!='4'
		and (month(a.fecha) ='".substr($fecha,5,2)."'
		and year(a.fecha) ='".substr($fecha,0,4)."' ) 
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":'';
		$sql.=$idEmisor>0?" and d.idEmisor='$idEmisor'":'';
		$sql.=" group by a.idIngreso
		order by a.fecha desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function sumarIngresosFacturados($fecha,$idCuenta,$idEmisor)
	{
		$sql =" select a.idIngreso, a.fecha, a.pago, a.transferencia,
		a.formaPago, a.cheque, b.empresa as cliente, concat(d.serie,d.folio) as factura,
		d.iva, d.fecha as fechaFactura, d.idFactura, a.factura as facturaIngreso
		from catalogos_ingresos as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join cotizaciones as c
		on a.idVenta=c.idCotizacion
		inner join facturas as d
		on c.idCotizacion=d.idCotizacion
		where c.cancelada='0'
		and d.cancelada='0'
		and a.idForma!='4'
		and (month(a.fecha) ='".substr($fecha,5,2)."'
		and year(a.fecha) ='".substr($fecha,0,4)."' )
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta'":'';
		$sql.=$idEmisor>0?" and d.idEmisor='$idEmisor'":'';
		$sql.=" group by a.idIngreso  ";
		
		$total		= 0;
		$ingresos	= $this->db->query($sql)->result();
		
		foreach($ingresos as $row)
		{
			$total+=$row->pago;
		}
		
		return $total;
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE RELACIÓN DE PROVEEDORES
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarRelacionProveedores($anio,$idProveedor,$idEmisor=0)
	{
		$sql =" (select a.idEgreso
		from catalogos_egresos as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where a.idProveedor>0
		and a.idCompra=0
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.=" and year(a.fecha) ='$anio' group by a.idProveedor  )";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor=0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.= " group by c.idProveedor ) ";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor>0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.= " group by c.idProveedor ) ";

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerRelacionProveedores($numero,$limite,$anio,$idProveedor=0,$idEmisor=0)
	{
		$sql =" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		b.empresa as empresa, b.rfc,
		(select d.nombre from configuracion_emisores as d inner join cuentas as e on e.idEmisor=d.idEmisor where e.idCuenta=a.idCuenta) as emisor
		from catalogos_egresos as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where a.idProveedor>0
		and a.idCompra=0
		and year(a.fecha) ='$anio'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.=" group by a.idProveedor ) ";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		c.empresa as empresa, c.rfc,
		(select d.nombre from configuracion_emisores as d inner join cuentas as e on e.idEmisor=d.idEmisor where e.idCuenta=a.idCuenta) as emisor
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor=0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.= " group by c.idProveedor ) ";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		c.empresa as empresa, c.rfc,
		(select d.nombre from configuracion_emisores as d inner join cuentas as e on e.idEmisor=d.idEmisor where e.idCuenta=a.idCuenta) as emisor
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor>0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.= " group by c.idProveedor ) ";
		
		$sql.=" order by empresa asc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		#echo $sql;
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRelacionProveedor($idProveedor=0,$anio,$idEmisor=0)
	{
		$sql =" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		b.empresa as empresa, b.rfc, 0 as ivaCompra, 'proveedor' as tipo, 0 as ivaPorcentaje
		from catalogos_egresos as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where a.idProveedor>0
		and a.idCompra=0
		and year(a.fecha) ='$anio'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.= " and a.idProveedor='$idProveedor' )";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		c.empresa as empresa, c.rfc, b.iva as ivaCompra, 'compra' as tipo, b.ivaPorcentaje
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor=0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.=" and b.idProveedor='$idProveedor' ) ";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		c.empresa as empresa, c.rfc, b.iva as ivaCompra, 'compra' as tipo, b.ivaPorcentaje
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor>0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';	
		
		$sql.=" and b.idProveedor='$idProveedor' ) ";
		
		$relacion		= $this->db->query($sql)->result();
		#$total			= 0;
		$totalIva		= 0;
		$totalSinIva	= 0;
		$subTotalTotal	= 0;
		$ivaTotal		= 0;
		
		#echo $sql.'<br /><br />';
		foreach($relacion as $row)
		{
			if($row->tipo=='proveedor')
			{
				if($row->iva>0)
				{
					$totalIva		+= $row->pago;
					$iva			 = 1+($row->iva/100);
					$subTotal		 = $row->pago/$iva;
					$ivaTotal		+= $row->pago-$subTotal;
					$subTotalTotal	+= $subTotal;
					
					#echo $row->idEgreso.', IVA: '.$iva.'<br />';
				}
				else
				{
					$totalSinIva	+= $row->pago;
					$subTotalTotal	+= $row->pago;
				}
			}
			
			if($row->tipo=='compra')
			{
				if($row->ivaCompra>0)
				{
					$totalIva		+= $row->pago;
					$iva			 = 1+($row->ivaPorcentaje/100);
					$subTotal		 = $row->pago/$iva;
					$ivaTotal		+= $row->pago-$subTotal;
					$subTotalTotal	+= $subTotal;
					
					#echo $row->idEgreso.', IVA: '.$iva.'<br />';
				}
				else
				{
					$totalSinIva	+= $row->pago;
					$subTotalTotal	+= $row->pago;
				}
			}
		}
		
		return array($subTotalTotal,$ivaTotal,$totalIva+$totalSinIva);
	}
	
	public function sumarRelacionProveedores($anio,$idProveedor,$idEmisor=0)
	{
		$sql =" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		b.empresa as empresa, b.rfc
		from catalogos_egresos as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where a.idProveedor>0
		and a.idCompra=0
		and year(a.fecha) ='$anio'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.= " group by a.idProveedor  ) ";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		c.empresa as empresa, c.rfc
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor=0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.= " group by c.idProveedor ) ";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		c.empresa as empresa, c.rfc
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor>0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.=" group by c.idProveedor ) ";
		
		$relacion	= $this->db->query($sql)->result();
		$totales	= 0;
		
		foreach($relacion as $row)
		{
			$total		=  $this->obtenerRelacionProveedor($row->idProveedor,$anio);
			$totales	+= $total[2];
		}
		
		return $totales;
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE RELACIÓN DE PROVEEDORES
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarGastosFacturados($mes,$anio,$idEmisor=0)
	{
		$sql =" (select a.idEgreso
		from catalogos_egresos as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where a.idProveedor>0
		and a.idCompra=0
		and year(a.fecha) ='$anio'
		and month(a.fecha)='$mes'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(c.idEmisor)
		from cuentas as c
		where c.idCuenta=a.idCuenta
		and c.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.=" and a.incluyeIva='1' )";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor=0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and month(a.fecha)='$mes'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.=" and b.iva>0 ) ";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor>0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and month(a.fecha)='$mes'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.=" and b.iva>0 ) ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerGastosFacturados($numero,$limite,$mes,$anio,$idEmisor=0)
	{
		$sql =" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		b.empresa as empresa, b.rfc, a.factura,
		(select d.nombre from configuracion_emisores as d inner join cuentas as e on e.idEmisor=d.idEmisor where e.idCuenta=a.idCuenta) as emisor
		from catalogos_egresos as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where a.idProveedor>0
		and a.idCompra=0
		and year(a.fecha) ='$anio'
		and month(a.fecha)='$mes'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(c.idEmisor)
		from cuentas as c
		where c.idCuenta=a.idCuenta
		and c.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.=" and a.incluyeIva='1' )";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		c.empresa as empresa, c.rfc, a.factura,
		(select d.nombre from configuracion_emisores as d inner join cuentas as e on e.idEmisor=d.idEmisor where e.idCuenta=a.idCuenta) as emisor
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor=0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and month(a.fecha)='$mes'
		and a.idLicencia='$this->idLicencia' ";
		
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.=" and b.iva>0 ) ";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		c.empresa as empresa, c.rfc, a.factura,
		(select d.nombre from configuracion_emisores as d inner join cuentas as e on e.idEmisor=d.idEmisor where e.idCuenta=a.idCuenta) as emisor
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor>0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and month(a.fecha)='$mes'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.=" and b.iva>0 ) ";
		
		$sql.=" order by fecha desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function sumarGastosFacturados($mes,$anio,$idEmisor=0)
	{
		$sql =" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		b.empresa as empresa, b.rfc, a.factura
		from catalogos_egresos as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where a.idProveedor>0
		and a.idCompra=0
		and year(a.fecha) ='$anio'
		and month(a.fecha)='$mes'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(c.idEmisor)
		from cuentas as c
		where c.idCuenta=a.idCuenta
		and c.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.= " and a.incluyeIva='1' )";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		c.empresa as empresa, c.rfc, a.factura
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor=0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and month(a.fecha)='$mes'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.= " and b.iva>0 ) ";
		
		$sql.=" union ";
		
		$sql .=" (select a.idEgreso, a.iva, a.pago, a.idProveedor, a.idCompra, a.formaPago, a.fecha as fecha,
		c.empresa as empresa, c.rfc, a.factura
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		inner join proveedores as c
		on b.idProveedor=c.idProveedor
		where a.idProveedor>0
		and a.idCompra>0
		and year(a.fecha) ='$anio'
		and month(a.fecha)='$mes'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and (select count(d.idEmisor)
		from cuentas as d
		where d.idCuenta=a.idCuenta
		and d.idEmisor='$idEmisor') > 0 ":'';
		
		$sql.= " and b.iva>0 ) ";
		
		$gastos		= $this->db->query($sql)->result();
		$total		= 0;
		$ivaTotal	= 0;
		
		foreach($gastos as $row)
		{
			$iva			= 1+($row->iva/100);
			$subTotal		= $row->pago/$iva;
			$iva			= $row->pago-$subTotal;
			
			$total			+=$row->pago;
			$ivaTotal		+=$iva;
		}
		
		return array($total,$ivaTotal);
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE INGRESOS FACTURADOS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarRelacionClientes($anio,$idEmisor)
	{
		$sql =" select a.idIngreso
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		inner join facturas as c
		on c.idCotizacion=b.idCotizacion
		where c.cancelada='0'
		and b.cancelada='0'
		and a.idForma!='4'
		and year(a.fecha) ='".$anio."' 
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idEmisor>0?" and c.idEmisor='$idEmisor'":'';
		$sql.=" group by b.idCliente ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerRelacionClientes($numero,$limite,$anio,$idEmisor)
	{
		$sql =" select a.idIngreso, a.fecha, a.pago, a.transferencia,
		a.formaPago, a.cheque, b.empresa as cliente, concat(d.serie,d.folio) as factura,
		d.iva, d.fecha as fechaFactura, d.idFactura, a.factura as facturaIngreso, b.rfc, b.idCliente
		
		from catalogos_ingresos as a
		
		inner join cotizaciones as c
		on a.idVenta=c.idCotizacion
		
		inner join clientes as b
		on c.idCliente=b.idCliente
		
		inner join facturas as d
		on c.idCotizacion=d.idCotizacion
		where c.cancelada='0'
		and d.cancelada='0'
		and a.idForma!='4'
		and year(a.fecha) ='".$anio."'
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$idEmisor>0?" and d.idEmisor='$idEmisor'":'';
		$sql.=" group by b.idCliente
		order by a.fecha desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function obtenerRelacionCliente($anio,$idEmisor,$idCliente)
	{
		$sql =" select a.idIngreso, a.fecha, a.pago, a.transferencia,
		a.formaPago, a.cheque, b.empresa as cliente, concat(d.serie,d.folio) as factura,
		d.iva, d.fecha as fechaFactura, d.idFactura, a.factura as facturaIngreso
		from catalogos_ingresos as a
		
		inner join cotizaciones as c
		on a.idVenta=c.idCotizacion
		inner join clientes as b
		on c.idCliente=b.idCliente
		
		inner join facturas as d
		on c.idCotizacion=d.idCotizacion
		where c.cancelada='0'
		and d.cancelada='0'
		and a.idForma!='4'
		and b.idCliente='$idCliente'
		and year(a.fecha) ='".$anio."'
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$idEmisor>0?" and d.idEmisor='$idEmisor'":'';
		$sql.=" group by a.idIngreso  ";

		$relacion		= $this->db->query($sql)->result();
		$totalIva		= 0;
		$ivaTotal		= 0;
		$subTotalTotal	= 0;
		
		foreach($relacion as $row)
		{
			$totalIva		+= $row->pago;
			$iva			 = 1+$row->iva;
			$subTotal		 = $row->pago/$iva;
			$ivaTotal		+= $row->pago-$subTotal;
			$subTotalTotal	+= $subTotal;
		}
		
		return array($subTotalTotal,$ivaTotal,$totalIva);
	}
	
	public function sumarRelacionClientes($anio,$idEmisor)
	{
		$sql =" select a.idIngreso, a.fecha, a.pago, a.transferencia,
		a.formaPago, a.cheque, b.empresa as cliente, concat(d.serie,d.folio) as factura,
		d.iva, d.fecha as fechaFactura, d.idFactura, a.factura as facturaIngreso
		from catalogos_ingresos as a
		
		inner join cotizaciones as c
		on a.idVenta=c.idCotizacion
		inner join clientes as b
		on c.idCliente=b.idCliente
		
		inner join facturas as d
		on c.idCotizacion=d.idCotizacion
		where c.cancelada='0'
		and d.cancelada='0'
		and a.idForma!='4'
		and year(a.fecha) ='".$anio."'
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$idEmisor>0?" and d.idEmisor='$idEmisor'":'';
		$sql.=" group by a.idIngreso  ";
		
		$total		= 0;
		$ingresos	= $this->db->query($sql)->result();
		
		foreach($ingresos as $row)
		{
			$total+=$row->pago;
		}
		
		return $total;
	}
	
	//UTILIDAD
	
	public function obtenerGastosProveedoresMes($mes,$anio,$idEmisor=0)
	{
		$sql =" select coalesce(sum(a.pago),0) as importe
		from catalogos_egresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		where b.idEmisor='$idEmisor'
		and year(a.fecha) ='$anio'
		and month(a.fecha) ='$mes'
		and a.idLicencia='$this->idLicencia' ";
		#echo $sql;
		return $this->db->query($sql)->row()->importe;
	}
	
	public function obtenerGastosClientesMes($mes,$anio,$idEmisor=0)
	{
		$sql =" select coalesce(sum(a.pago),0) as importe
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		where b.idEmisor='$idEmisor'
		and year(a.fecha) ='$anio'
		and month(a.fecha) ='$mes'
		and a.idLicencia='$this->idLicencia' ";
		#echo $sql;
		return $this->db->query($sql)->row()->importe;
	}
	
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	//PARA LA FACTURACIÓN DEL SAT
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	
	public function procesarFacturasSat()
	{
		$this->load->helper('xml');
		
		$sql=" select idFactura, uuid, xml, rfcEmisor, rfcReceptor
		from facturas_sat
		where total=0 ";
		
		$facturas		= $this->db->query($sql)->result();
		
		foreach($facturas as $row)
		{
			$archivo 	= 'media/sat/'.$row->uuid.'.xml';
			guardarFichero($archivo,$row->xml);
			
			$xml		= procesarXmlCfdi($archivo);
			
			$data=array
			(
				'serie' 	=> (string)$xml[11],
				'folio' 	=> (string)$xml[12],
				'total' 	=> $xml[4],
			);
			
			$this->db->where('idFactura',$row->idFactura);
			$this->db->update('facturas_sat',$data);
			
			unlink($archivo);
		}
	}
	
	public function contarFacturasSat($mes,$anio,$criterio,$recibida,$emisor)
	{
		$sql ="	select idFactura
		from facturas_sat
		where idFactura>0 
		and idLicencia='$this->idLicencia' ";
					
		$sql.=strlen($criterio)>0?" and concat(serie,folio) like '%$criterio%' ":'';
		$sql.=$mes!='mes'?" and month(fecha)='$mes' and year(fecha)='$anio' ":'';
		$sql.=$recibida=='0'?" and recibida='0' ":'';
		$sql.=$recibida=='1'?" and recibida='1' ":'';
		$sql.=$emisor!='0'?" and emisor='$emisor' ":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerFacturasSat($mes,$anio,$numero,$limite,$criterio,$recibida,$emisor)
	{
		$sql ="	select fecha, total, rfcEmisor, rfcReceptor,
		folio, serie, uuid, idFactura, recibida,
		emisor, receptor
		from facturas_sat
		where idFactura>0
		and idLicencia='$this->idLicencia'  ";
					
		$sql.=strlen($criterio)>0?" and concat(serie,folio) like '%$criterio%' ":'';
		$sql.=$mes!='mes'?" and month(fecha)='$mes' and year(fecha)='$anio' ":'';
		$sql.=$recibida=='0'?" and recibida='0' ":'';
		$sql.=$recibida=='1'?" and recibida='1' ":'';
		$sql.=$emisor!='0'?" and emisor='$emisor' ":'';
		
		$sql.=" order by fecha desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerEmisoresSat()
	{
		$sql ="	select emisor, recibida
		from facturas_sat
		where idFactura>0
		and idLicencia='$this->idLicencia' 
		group by emisor ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerFacturaSat($idFactura)
	{
		$sql=" select * from facturas_sat
		where idFactura='$idFactura' ";
		
		return $this->db->query($sql)->row();
	}
	
	/*public function contarFacturasSat($mes,$anio,$criterio,$recibida)
	{
		$sql ="	select idFactura
		from facturas_sat
		where idFactura>0 ";
					
		$sql.=strlen($criterio)>0?" and concat(serie,folio) like '%$criterio%' ":'';
		$sql.=$mes!='mes'?" and month(fecha)='$mes' and year(fecha)='$anio' ":'';
		$sql.=$recibida=='0'?" and recibida='0' ":'';
		$sql.=$recibida=='1'?" and recibida='1' ":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerFacturasSat($mes,$anio,$numero,$limite,$criterio,$recibida)
	{
		$sql ="	select fecha, total,
		folio, serie, uuid, idFactura, recibida
		from facturas_sat
		where idFactura>0 ";
					
		$sql.=strlen($criterio)>0?" and concat(serie,folio) like '%$criterio%' ":'';
		$sql.=$mes!='mes'?" and month(fecha)='$mes' and year(fecha)='$anio' ":'';
		$sql.=$recibida=='0'?" and recibida='0' ":'';
		$sql.=$recibida=='1'?" and recibida='1' ":'';
		
		$sql.=" order by fecha desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}*/
	
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	//REPORTE DE MATERIA PRIMA
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	
	public function contarMateriaPrima($criterio='')
	{
		$sql ="select a.idMaterial
		from produccion_materiales as a
		inner join rel_material_proveedor as d
		on a.idMaterial=d.idMaterial
		inner join  proveedores as b 
		on(b.idProveedor=d.idProveedor)
		inner join unidades as c 
		on(a.idUnidad=c.idUnidad)
		where tipoMaterial='1' 
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=strlen($criterio)>0?" and (a.nombre like '%$criterio%' or a.codigoInterno like '%$criterio%' or b.empresa like '%$criterio%')":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerMateriaPrima($numero,$limite,$criterio='')
	{
		$sql =" select a.idMaterial, a.nombre, d.costo, 
		a.costo as costoPromedio, a.produccion,
		a.stockMinimo, a.stock, b.empresa as proveedor, 
		b.idProveedor, c.descripcion as unidad, a.codigoInterno,
		a.idConversion, b.idProveedor,
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_entradas as e where e.idMaterial=a.idMaterial and b.idProveedor=e.idProveedor and e.idLicencia='$this->idLicencia')
		-
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_mermas as e where e.idMaterial=a.idMaterial and b.idProveedor=e.idProveedor and e.fechaRegistro is not null and e.idLicencia='$this->idLicencia') as existencia
		from produccion_materiales as a
		inner join rel_material_proveedor as d
		on a.idMaterial=d.idMaterial
		inner join  proveedores as b 
		on(b.idProveedor=d.idProveedor)
		inner join unidades as c 
		on(a.idUnidad=c.idUnidad)
		where tipoMaterial='1' ";
		
		$sql.=strlen($criterio)>0?" and (a.nombre like '%$criterio%' or a.codigoInterno like '%$criterio%' or b.empresa like '%$criterio%')":'';
		$sql .= 'order by existencia desc ';		
		$sql .= $numero>0? " limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarMateriaPrima($criterio='')
	{
		$sql =" select 
		
		coalesce(sum(
		((select coalesce(sum(e.cantidad),0) from produccion_materiales_entradas as e where e.idMaterial=a.idMaterial and b.idProveedor=e.idProveedor and e.idLicencia='$this->idLicencia')
		-
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_mermas as e where e.idMaterial=a.idMaterial and b.idProveedor=e.idProveedor and e.fechaRegistro is not null  and e.idLicencia='$this->idLicencia') )
		* d.costo ),0) as costo
		
		
		from produccion_materiales as a
		inner join rel_material_proveedor as d
		on a.idMaterial=d.idMaterial
		inner join  proveedores as b 
		on(b.idProveedor=d.idProveedor)
		inner join unidades as c 
		on(a.idUnidad=c.idUnidad)
		where tipoMaterial='1' ";
		
		$sql.=strlen($criterio)>0?" and (a.nombre like '%$criterio%' or a.codigoInterno like '%$criterio%' or b.empresa like '%$criterio%')":'';

		return $this->db->query($sql)->row()->costo;
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE MOVIMIENTOS
	public function contarHistorialMovimientos($inicio,$fin,$idLicencia,$usuario,$modulo)
	{
		$sql ="	select count(idBitacora) as numero
		from configuracion_bitacora
		where idBitacora>0";

		$sql.=" and date(fecha) between '$inicio' and '$fin' ";
		$sql.=strlen($usuario)>0?" and usuario='$usuario' ":'';
		$sql.=strlen($modulo)>0?" and modulo='$modulo' ":'';

		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerHistorialMovimientos($numero,$limite,$inicio,$fin,$idLicencia,$usuario,$modulo)
	{
		$sql ="	select * from configuracion_bitacora
		where idBitacora>0
		and date(fecha) between '$inicio' and '$fin' ";
		
		$sql.=strlen($usuario)>0?" and usuario='$usuario' ":'';
		$sql.=strlen($modulo)>0?" and modulo='$modulo' ":'';
		$sql.=" order by fecha desc ";
		$sql .=$numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function obtenerUsuariosHistorialMovimientos($inicio,$fin,$idLicencia)
	{
		$sql ="	select usuario, nombre
		from configuracion_bitacora
		where idBitacora>0
		and date(fecha) between '$inicio' and '$fin'
		group by nombre ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerModulosHistorialMovimientos($inicio,$fin,$idLicencia)
	{
		$sql ="	select modulo
		from configuracion_bitacora
		where idBitacora>0
		and date(fecha) between '$inicio' and '$fin'
		group by modulo ";

		return $this->db->query($sql)->result();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE VENTA DE SERVICIOS
	public function contarVentaServicios($inicio,$fin,$criterio)
	{
		$sql ="	select count(a.idProducto) as numero
		from cotiza_productos as a
		inner join cotizaciones as b
		on a.idCotizacion=b.idCotizacion
		inner join productos as c
		on a.idProduct=c.idProducto
		inner join produccion_periodos as d
		on d.idPeriodo=c.idPeriodo
		inner join clientes as e
		on b.idCliente=e.idCliente
		where a.idProducto>0
		and date(b.fechaCompra) between '$inicio' and '$fin'
		and d.nombre!='NA'
		and a.servicio='1'
		and b.folioConta >0
		and b.idLicencia='$this->idLicencia'
		
		and b.idCotizacionPadre=0
		
		and (c.nombre like '%$criterio%'
		or e.empresa like '%$criterio%' ) 
		
		group by a.idProducto ";

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerVentaServicios($numero,$limite,$inicio,$fin,$criterio)
	{
		$sql ="	select b.idCotizacion, c.idProducto, a.precio, a.importe, 
		a.plazo, a.cantidad, b.fechaCompra, b.ivaPorcentaje, b.descuentoPorcentaje,
		a.nombre as servicio, d.nombre as periodicidad, e.empresa as cliente, b.cancelada,
		b.ordenCompra
		from cotiza_productos as a
		inner join cotizaciones as b
		on a.idCotizacion=b.idCotizacion
		inner join productos as c
		on a.idProduct=c.idProducto
		inner join produccion_periodos as d
		on d.idPeriodo=c.idPeriodo
		inner join clientes as e
		on b.idCliente=e.idCliente
		where a.idProducto>0
		and date(b.fechaCompra) between '$inicio' and '$fin'
		and d.nombre!='NA'
		and a.servicio='1'
		
		and b.folioConta >0
		
		and b.idCotizacionPadre=0
		
		and b.idLicencia='$this->idLicencia'
		
		and (c.nombre like '%$criterio%'
		or e.empresa like '%$criterio%' ) 
		
		group by a.idProducto ";
		
		$sql.=" order by b.fechaCompra desc ";
		$sql .=$numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function obtenerVentaServiciosDetalle($idCotizacion,$idProducto)
	{
		$sql ="	select b.idCotizacion, a.idProduct, a.precio, 
		a.importe, a.plazo, a.cantidad, b.fechaCompra, b.subTotal,
		b.iva, b.descuento, b.total, b.cancelada, b.ordenCompra, b.pendiente,
		(select coalesce(sum(c.pago),0) from catalogos_ingresos as c where c.idVenta=b.idCotizacion) as pagado 
		from cotiza_productos as a
		inner join cotizaciones as b
		on a.idCotizacion=b.idCotizacion
		where b.idCotizacionPadre='$idCotizacion'
		and a.idProduct='$idProducto' ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerCiclosPagados($idCotizacion,$idProducto)
	{
		$sql=" select sum(a.pago) as pago
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		inner join cotiza_productos as c
		on c.idCotizacion=b.idCotizacion
		where b.idCotizacionPadre='$idCotizacion'
		and c.idProduct='$idProducto'
		
		and b.idLicencia='$this->idLicencia'
		
		group by b.idCotizacion ";
		
		
		return $this->db->query($sql)->result();
		
	}
	
	//PEDIDOS
	
	public function contarReportePanaderos($idLinea,$inicio,$fin)
	{
		$sql =" select count(a.idPedido) as numero
		from productos_pedidos as a
		inner join usuarios as c
		on c.idUsuario=a.idUsuario
		where a.idPedido>0
		and date(a.fechaPedido) between '$inicio' and '$fin' ";
		
		$sql.=$idLinea>0?" and a.idLinea='$idLinea' ":'';
		
		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerReportePanaderos($numero,$limite,$idLinea,$inicio,$fin,$orden='desc')
	{
		$sql =" select a.*,
		if(a.idTienda=0,'Matriz',
		(select b.nombre from tiendas as b where b.idTienda=a.idTienda limit 1)) as tienda,
		
		c.nombre as usuario,
		
		(select coalesce(sum(d.cantidad),0) from productos_pedidos_producidos as d
		inner join productos_pedidos_detalles as e
		on e.idDetalle=d.idDetalle
		where e.idPedido=a.idPedido) as producido,
		(select b.nombre from productos_lineas as b where b.idLinea=a.idLinea limit 1) as linea
		
		from productos_pedidos as a
		inner join usuarios as c
		on c.idUsuario=a.idUsuario
		where a.idPedido>0
		and date(a.fechaPedido) between '$inicio' and '$fin' ";
		
		$sql.=$idLinea>0?" and a.idLinea='$idLinea' ":'';
		$sql.=" order by a.fechaPedido $orden";
		
		$sql .= $numero>0? " limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function sumarReportePanaderos($idLinea,$inicio,$fin)
	{
		$sql =" select coalesce(sum(b.manoTotal+b.primaTotal-b.cuotaTotal),0) as total
		
		from productos_pedidos as a
		inner join productos_pedidos_reporte as b
		on a.idPedido=b.idPedido
		where a.idPedido>0
		and date(a.fechaPedido) between '$inicio' and '$fin' ";
		
		$sql.=$idLinea>0?" and a.idLinea='$idLinea' ":'';

		return $this->db->query($sql)->row()->total;
	}
	
	//VENTAS POR MESES PARA GRÁFICAS
	public function obtenerVentasMeses($inicio,$fin)
	{
		$sql ="	select coalesce(sum(a.total),0) as total, a.fechaCompra
		from cotizaciones as a
		where a.idCotizacion>0
		and a.cancelada='0'
		and a.activo='1'
		and a.estatus='1'
		and date(a.fechaCompra) between '$inicio-01' and (select last_day('$fin-01'))
		and a.idLicencia='$this->idLicencia'
		group by year(a.fechaCompra),month(a.fechaCompra)  ";
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarDiasFecha($fecha,$dias)
	{
		$sql="select date_add('$fecha', interval ".$dias." day ) as fecha";
		
		return $this->db->query($sql)->row()->fecha;
	}
	
	public function restarDiasFecha($fecha,$dias)
	{
		$sql="select date_sub('$fecha', interval ".$dias." day ) as fecha";
		
		return $this->db->query($sql)->row()->fecha;
	}
	
	public function obtenerSemanaFechas($fecha)
	{
		$diaInicialSemana  = date('Y-m-d', strtotime(date('Y') . 'W' . str_pad(date('W') , 2, '0', STR_PAD_LEFT)));
		#echo $diaInicialSemana;
		$fin				= $this->sumarDiasFecha($diaInicialSemana,6);
		$inicio				= $this->restarDiasFecha($diaInicialSemana,1);
		
		return array($inicio,$fin);
	}
	
	public function obtenerVentasSemana()
	{
		$semana	= $this->obtenerSemanaFechas(date('Y-m-d'));
		
		$sql ="	select coalesce(sum(a.total),0) as total, a.fechaCompra
		from cotizaciones as a
		where a.idCotizacion>0
		and a.cancelada='0'
		and a.activo='1'
		and a.estatus='1'
		and date(a.fechaCompra) between '".$semana[0]."' and '".$semana[1]."'
		and a.idLicencia='$this->idLicencia'
		group by day(a.fechaCompra)";
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerGastosMeses($inicio,$fin)
	{
		$sql ="	select coalesce(sum(a.pago),0) as total, a.fecha
		from catalogos_egresos as a
		where a.idEgreso>0
		and a.idForma!=4
		and date(a.fecha) between '$inicio-01' and (select last_day('$fin-01'))
		group by year(a.fecha),month(a.fecha)  ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerVentasProductos()
	{
		$sql ="	select coalesce(sum(b.importe),0) + (select coalesce(sum(d.importe),0) from cotiza_productos_impuestos as d where d.idProducto=b.idProducto ) as total,
		c.nombre as producto
		from cotizaciones as a
		inner join cotiza_productos as b
		on a.idCotizacion=b.idCotizacion
		inner join productos as c
		on c.idProducto=b.idProduct
		where a.idCotizacion>0
		and a.cancelada='0'
		and a.activo='1'
		and a.estatus='1'
		and year(a.fechaCompra)=".(date('Y'))."
		and a.idLicencia='$this->idLicencia'
		group by c.idProducto 
		order by total desc
		limit 5 ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFacturasMeses($inicio,$fin)
	{
		$sql ="	select coalesce(sum(a.total),0) as total, a.fecha
		from facturas as a
		where a.idFactura>0
		and a.cancelada='0'
		and a.tipoComprobante='ingreso'
		and date(a.fecha) between '$inicio-01' and (select last_day('$fin-01'))
		and a.idLicencia='$this->idLicencia'
		group by year(a.fecha),month(a.fecha) ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerGastosVentasMeses($inicio,$fin)
	{
		$sql ="	SELECT COALESCE(SUM(a.total),0) AS total, a.fechaCompra as fecha,

		(SELECT COALESCE(SUM(b.pago),0) FROM catalogos_egresos AS b WHERE b.idForma!=4 AND YEAR(b.fecha)=YEAR(a.fechaCompra) AND MONTH(b.fecha)=MONTH(a.fechaCompra)) AS totalGastos
		
		FROM cotizaciones AS a
		WHERE a.idCotizacion>0
		AND a.cancelada='0'
		AND a.activo='1'
		AND a.estatus='1'
		AND DATE(a.fechaCompra) BETWEEN '$inicio-01' AND (SELECT LAST_DAY('$fin-01'))
		GROUP BY YEAR(a.fechaCompra),MONTH(a.fechaCompra) ";
		
		$ventas	= $this->db->query($sql)->result();
		
		
		$sql ="	SELECT COALESCE(SUM(a.pago),0) AS totalGastos, a.fecha,

		(SELECT COALESCE(SUM(b.total),0) FROM cotizaciones AS b WHERE b.idForma!=4 AND YEAR(b.fechaCompra)=YEAR(a.fecha) AND MONTH(b.fechaCompra)=MONTH(a.fecha)) AS total
		
		FROM catalogos_egresos AS a
		WHERE a.idEgreso>0
		AND a.idForma!=4
		AND DATE(a.fecha) BETWEEN '$inicio-01' AND (SELECT LAST_DAY('$fin-30'))
		GROUP BY YEAR(a.fecha),MONTH(a.fecha) ";
		
		$gastos	= $this->db->query($sql)->result();
		
		return count($ventas)>count($gastos)?$ventas:$gastos;
	}
	
	public function obtenerClientesTipos()
	{
		$sql ="	select a.descripcion,
		(select count(b.idCliente) from clientes as b where a.idZona=b.idZona and b.activo='1') as numeroClientes
		from zonas as a
		where a.activo='1' 
		and (select count(b.idCliente) from clientes as b where a.idZona=b.idZona and b.activo='1') > 0  ";
		
		return $this->db->query($sql)->result();
	}
	
	//VENTAS REPORTE
	public function contarVentasReporte($inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta=0)
	{
		$sql ="	(select a.serie
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		where a.ordenCompra is not null 
		and a.cancelada='0'
		and a.activo='1' ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		
		$sql.=$tipoVenta==2?" and a.idCotizacion=0 ":'';
		

		$sql.=" union ";
		
		$sql ="	select a.serie
		from cotizaciones_ as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		where a.ordenCompra is not null 
		and a.cancelada='0'
		and a.activo='1' ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		
		$sql.=$tipoVenta==1?" and a.idCotizacion=0 ":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerVentasReporte($numero,$limite,$inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta=0)
	{
		$sql ="	(select a.fechaCompra as fechaCompra, a.total, a.subTotal, 
		a.iva,  a.idCotizacion, a.descuento, a.descuentoAdicional, a.cancelada,
		a.descuentoPorcentaje, a.ivaPorcentaje,
		c.empresa, a.fechaVencimiento, a.idFactura,
		concat('',a.ordenCompra) as ordenCompra , a.idUsuario,
		d.descripcion as identificador, c.idZona, a.idTienda,
		concat(e.nombre, ' ', e.apellidoPaterno, ' ', e.apellidoMaterno) as usuario,
		(select f.nombre from tiendas as f where f.idTienda=a.idTienda) as tienda,
		
		(select f.idSeguimiento from seguimiento as f where f.idVenta=a.idCotizacion order by f.fecha desc limit 1) as idSeguimiento,
		(select coalesce(sum(f.pago),0) from catalogos_ingresos  as f where f.idVenta=a.idCotizacion and f.idForma!=4) as pagado,
		'f3' as tipoVenta
		
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		inner join usuarios as e
		on a.idUsuario=e.idUsuario 
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1' ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		
		$sql.=$tipoVenta==2?" and a.idCotizacion=0 ":'';
		
		$sql.=")";
		
		$sql.=" union ";
		
		$sql.="	(select a.fechaCompra as fechaCompra, a.total, a.subTotal, 
		a.iva,  a.idCotizacion, a.descuento, a.descuentoAdicional, a.cancelada,
		a.descuentoPorcentaje, a.ivaPorcentaje,
		c.empresa, a.fechaVencimiento, a.idFactura,
		concat('',a.ordenCompra) as ordenCompra , a.idUsuario,
		d.descripcion as identificador, c.idZona, a.idTienda,
		concat(e.nombre, ' ', e.apellidoPaterno, ' ', e.apellidoMaterno) as usuario,
		(select f.nombre from tiendas as f where f.idTienda=a.idTienda) as tienda,
		
		(select f.idSeguimiento from seguimiento as f where f.idVenta=a.idCotizacion order by f.fecha desc limit 1) as idSeguimiento,
		(select coalesce(sum(f.pago),0) from catalogos_ingresos_  as f where f.idVenta=a.idCotizacion and f.idForma!=4) as pagado,
		'f4' as tipoVenta
		
		from cotizaciones_ as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		inner join usuarios as e
		on a.idUsuario=e.idUsuario 
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1' ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		
		$sql.=$tipoVenta==1?" and a.idCotizacion=0 ":'';
		
		
		#$sql.=" and a.idCotizacion <= 650 ";
		#$sql.=" and a.idCotizacion != 651 ";
		
		$sql.=")";

		$sql.=" order by fechaCompra desc ";

		$sql .=$numero>0? " limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function sumarVentasReporte($inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta=0)
	{
		$sql ="	(select a.total, a.idCotizacion,  
		a.idFactura, a.idUsuario
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1'  ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		
		$sql.=$tipoVenta==2?" and a.idCotizacion=0 ":'';
		
		$sql.=")";
		
		$sql.=" union ";
		
		$sql .="	(select a.total, a.idCotizacion,  
		a.idFactura, a.idUsuario
		from cotizaciones_ as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1'  ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		
		$sql.=$tipoVenta==1?" and a.idCotizacion=0 ":'';
		
		#$sql.=" and a.idCotizacion <= 650 ";
		#$sql.=" and a.idCotizacion != 651 ";
		
		
		$sql.=")";
		
		$total=0;

		$ventas	= $this->db->query($sql)->result();
		
		foreach($ventas as $row)
		{
			$cancelada	=0;
			
			if($row->idFactura!=0)
			{
				$cancelada	=$this->obtenerFacturaCancelada($row->idFactura);
			}
			
			if($cancelada==0)
			{
				$total		+=$row->total;
			}
		}
		
		return $total;
	}
	
	public function obtenerVentasReporteLinea($inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta=0)
	{
		$sql ="	(select a.total, a.idCotizacion,  
		a.idFactura, a.idUsuario,
		
		coalesce(sum(e.importe + (select coalesce(sum(h.importe),0) from cotiza_productos_impuestos as h where h.idProducto=e.idProducto)),0)   as importe, g.nombre as departamento,
		'(F3)' as tipoVenta
		
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		
		inner join cotiza_productos as e
		on e.idCotizacion=a.idCotizacion 
		inner join productos as f
		on e.idProduct=f.idProducto 
		inner join productos_departamentos as g
		on g.idDepartamento=f.idDepartamento 
		
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1'  ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		
		$sql.=$tipoVenta==2?" and a.idCotizacion=0 ":'';
		$sql.=" group by f.idDepartamento ";
		
		$sql.=")";
		
		$sql.=" union ";
		
		
		//SIN DEPARTAMENTO
		
		$sql.="	(select a.total, a.idCotizacion,  
		a.idFactura, a.idUsuario,
		
		coalesce(sum(e.importe + (select coalesce(sum(h.importe),0) from cotiza_productos_impuestos as h where h.idProducto=e.idProducto)),0)   as importe, 'Sin Departamento' as departamento,
		'(F3)' as tipoVenta
		
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		
		inner join cotiza_productos as e
		on e.idCotizacion=a.idCotizacion 
		inner join productos as f
		on e.idProduct=f.idProducto 
		
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1'  ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		
		$sql.=$tipoVenta==2?" and a.idCotizacion=0 ":'';
		$sql.=" and f.idDepartamento=0 
		group by f.idDepartamento ";
		
		$sql.=")";
		
		$sql.=" union ";
		
		
		$sql .="	(select a.total, a.idCotizacion,  
		a.idFactura, a.idUsuario,
		coalesce(sum(e.importe + (select coalesce(sum(h.importe),0) from cotiza_productos_impuestos_ as h where h.idProducto=e.idProducto)),0)  as importe, g.nombre as departamento,
		'(F4)' as tipoVenta
		from cotizaciones_ as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		
		inner join cotiza_productos_ as e
		on e.idCotizacion=a.idCotizacion 
		inner join productos as f
		on e.idProduct=f.idProducto 
		inner join productos_departamentos as g
		on g.idDepartamento=f.idDepartamento 
		
		
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1'  ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		
		$sql.=$tipoVenta==1?" and a.idCotizacion=0 ":'';
		#$sql.=" and a.idCotizacion <= 650 ";
		#$sql.=" and a.idCotizacion != 651 ";
		
		$sql.=" group by f.idDepartamento ";
		
		$sql.=")";
		
		
		$sql.=" union ";
		
		
		$sql .="	(select a.total, a.idCotizacion,  
		a.idFactura, a.idUsuario,
		coalesce(sum(e.importe + (select coalesce(sum(h.importe),0) from cotiza_productos_impuestos_ as h where h.idProducto=e.idProducto)),0)  as importe, 'Sin Departamento' as departamento,
		'(F4)' as tipoVenta
		from cotizaciones_ as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		
		inner join cotiza_productos_ as e
		on e.idCotizacion=a.idCotizacion 
		inner join productos as f
		on e.idProduct=f.idProducto 

		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1'  ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		
		$sql.=$tipoVenta==1?" and a.idCotizacion=0 ":'';
		#$sql.=" and a.idCotizacion <= 650 ";
		#$sql.=" and a.idCotizacion != 651 ";
		
		$sql.=" and f.idDepartamento=0 
		group by f.idDepartamento ";
		
		$sql.=")";
		
		$total=0;
		
		#echo $sql;
		
		return $this->db->query($sql)->result();
		
		/*foreach($ventas as $row)
		{
			$cancelada	=0;
			
			if($row->idFactura!=0)
			{
				$cancelada	=$this->obtenerFacturaCancelada($row->idFactura);
			}
			
			if($cancelada==0)
			{
				$total		+=$row->total;
			}
		}
		
		return $total;*/
	}
	
	
	public function obtenerVentasContadora($inicio,$fin,$criterio)
	{
		$sql ="	(select a.total, a.idCotizacion,  
		a.idFactura, a.idUsuario,
		
		coalesce(sum(e.importe + (select coalesce(sum(h.importe),0) from cotiza_productos_impuestos as h where h.idProducto=e.idProducto)),0)   as importe, 
		sum(e.cantidad) as cantidad,
		g.nombre as departamento
		
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		
		inner join cotiza_productos as e
		on e.idCotizacion=a.idCotizacion 
		inner join productos as f
		on e.idProduct=f.idProducto 
		inner join productos_departamentos as g
		on g.idDepartamento=f.idDepartamento 
		
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1'
		and a.idLicencia='$this->idLicencia'
		  ";
		
		#and a.folioConta >0
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';

		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		$sql.=" group by f.idDepartamento ";
		
		$sql.=")";
		
		$sql.=" union ";
		
		
		//SIN DEPARTAMENTO
		
		$sql.="	(select a.total, a.idCotizacion,  
		a.idFactura, a.idUsuario,
		
		coalesce(sum(e.importe + (select coalesce(sum(h.importe),0) from cotiza_productos_impuestos as h where h.idProducto=e.idProducto)),0)   as importe,
		sum(e.cantidad) as cantidad,
		 'Sin Departamento' as departamento
		
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		
		inner join cotiza_productos as e
		on e.idCotizacion=a.idCotizacion 
		inner join productos as f
		on e.idProduct=f.idProducto 
		
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1'
		and a.idLicencia='$this->idLicencia'
		 ";
		
		#and a.folioConta >0 
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		
		$sql.=" and f.idDepartamento=0 
		group by f.idDepartamento ";
		
		$sql.=")";

		return $this->db->query($sql)->result();
	}
	
	//REPORTE POR DEPARTAMENTO
	public function obtenerVentasLineas($inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion)
	{
		$sql ="	(select a.total, a.idCotizacion,  
		a.idFactura, a.idUsuario,
		coalesce(sum(e.cantidad),0) as cantidad,
		coalesce(sum(e.importe + (select coalesce(sum(h.importe),0) from cotiza_productos_impuestos as h where h.idProducto=e.idProducto)),0)   as importe, g.nombre as departamento
		
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		
		inner join cotiza_productos as e
		on e.idCotizacion=a.idCotizacion 
		inner join productos as f
		on e.idProduct=f.idProducto 
		inner join productos_departamentos as g
		on g.idDepartamento=f.idDepartamento 
		
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1' 
		and a.folioConta>0 
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		
		$sql.=" group by f.idDepartamento ";
		
		$sql.=")";
		
		$sql.=" union ";
		
		
		//SIN DEPARTAMENTO
		
		$sql.="	(select a.total, a.idCotizacion,  
		a.idFactura, a.idUsuario,
		coalesce(sum(e.cantidad),0) as cantidad,
		coalesce(sum(e.importe + (select coalesce(sum(h.importe),0) from cotiza_productos_impuestos as h where h.idProducto=e.idProducto)),0)   as importe, 'Sin Departamento' as departamento
		
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona 
		
		inner join cotiza_productos as e
		on e.idCotizacion=a.idCotizacion 
		inner join productos as f
		on e.idProduct=f.idProducto 
		
		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1'
		and a.folioConta>0 
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '%$criterio%' or a.ordenCompra like '%$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= $inicio!='fecha'?" and date(a.fechaCompra) between '$inicio' and '$fin' ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		
		$sql.=" and f.idDepartamento=0 
		group by f.idDepartamento ";
		
		$sql.=")";
		
		return $this->db->query($sql)->result();
	}
	
	//EGRESOS POR TIPO PARA GRÁFICAS
	public function obtenerEgresosMeses($inicio,$fin)
	{
		$sql ="	select coalesce(sum(a.pago),0) as total, b.nombre as tipo
		from catalogos_egresos as a
		inner join catalogos_gastos as b
		on a.idGasto=b.idGasto
		where a.idEgreso>0
		and a.devolucion='0'
		and date(a.fecha) between '$inicio-01' and (select last_day('$fin-01'))
		group by b.idGasto ";
		
		return $this->db->query($sql)->result();
	}
	
	//INGRESOS POR TIPO PARA GRÁFICAS
	public function obtenerIngresosMeses($inicio,$fin)
	{
		$sql ="	select coalesce(sum(a.pago),0) as total, b.nombre as tipo
		from catalogos_ingresos as a
		inner join catalogos_gastos as b
		on a.idGasto=b.idGasto
		where a.idIngreso>0
		and date(a.fecha) between '$inicio-01' and (select last_day('$fin-01'))
		group by b.idGasto ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerEgresosMesesDepartamento($inicio,$fin)
	{
		$sql ="	select coalesce(sum(a.pago),0) as total, b.nombre as tipo
		from catalogos_egresos as a
		inner join catalogos_departamentos as b
		on a.idDepartamento=b.idDepartamento
		where a.idEgreso>0
		and a.devolucion='0'
		and date(a.fecha) between '$inicio-01' and (select last_day('$fin-01'))
		and a.idLicencia='$this->idLicencia'
		group by b.idDepartamento ";
		
		return $this->db->query($sql)->result();
	}
	
	//INGRESOS POR TIPO PARA GRÁFICAS
	public function obtenerIngresosMesesDepartamento($inicio,$fin)
	{
		$sql ="	select coalesce(sum(a.pago),0) as total, b.nombre as tipo
		from catalogos_ingresos as a
		inner join catalogos_departamentos as b
		on a.idDepartamento=b.idDepartamento
		where a.idIngreso>0
		and date(a.fecha) between '$inicio-01' and (select last_day('$fin-01'))
		and a.idLicencia='$this->idLicencia'
		group by b.idDepartamento ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerIngresosEgresosMeses($inicio,$fin)
	{
		$sql ="	(select coalesce(sum(a.pago),0) as total, a.fecha as fecha, 'ingreso' as tipo
		from catalogos_egresos as a
		where a.idEgreso>0
		and a.devolucion='0'
		and date(a.fecha) between '$inicio-01' and (select last_day('$fin-01'))
		and a.idLicencia='$this->idLicencia'
		group by month(a.fecha), year(a.fecha)) ";
		
		$sql.=" union	(select coalesce(sum(a.pago),0) as total, a.fecha as fecha, 'egreso' as tipo
		from catalogos_ingresos as a
		where a.idIngreso>0
		and date(a.fecha) between '$inicio-01' and (select last_day('$fin-01'))
		and a.idLicencia='$this->idLicencia'
		group by year(a.fecha), month(a.fecha)) ";
		
		$sql.=" order by fecha asc ";
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerIngresosEgresos()
	{
		/*$sql ="	select coalesce(sum(a.pago),0) 
		
		- (select coalesce(sum(c.pago),0) from catalogos_egresos as c 
		where c.idEgreso>0
		and c.devolucion='0'
		and date(c.fecha) <= curdate()
		and c.idLicencia='$this->idLicencia'
		and c.idForma!=4
		and c.idCuenta=a.idCuenta  ) 
		
		as total
		
		, b.cuenta, d.nombre as banco
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		
		
		inner join bancos as d
		on d.idBanco=b.idBanco
		
		where a.idIngreso>0
		and a.idForma!=4
		
		and date(a.fecha)<= curdate()
		and a.idLicencia='$this->idLicencia'
		group by b.idBanco ";*/
		
		$sql ="	select coalesce(sum(a.pago),0) as total, 
		b.cuenta, d.nombre as banco, d.idBanco
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		inner join bancos as d
		on d.idBanco=b.idBanco
		where a.idIngreso>0
		and a.idForma!=4
		and date(a.fecha)<= curdate()
		and a.idLicencia='$this->idLicencia'
		group by b.idBanco ";
		
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerEgresosBanco($idBanco)
	{
		$sql ="	select coalesce(sum(c.pago),0)  as total
		from catalogos_egresos as c 
		inner join cuentas as d
		on d.idCuenta=c.idCuenta
		where c.idEgreso>0
		and c.devolucion='0'
		and date(c.fecha) <= curdate()
		and c.idLicencia='$this->idLicencia'
		and c.idForma!=4
		and d.idBanco='$idBanco'  ";

		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerFechaFinCriterio($fecha,$valor,$criterio)
	{	
		$sql="SELECT date_add('$fecha',interval ".$valor." $criterio) as fechaFin";
		
		return $this->db->query($sql)->row()->fechaFin;
	}
	
	public function obtenerMesesCriterio($meses,$fecha)
	{	
		$data=array();
		
		for($i=0;$i<=$meses;$i++)
		{
			$data[$i]	= $this->obtenerFechaFinCriterio($fecha,$i,'month');
		}
		
		#echo 'Meses'.$meses.'<br /><br />';
		#print_r($data);
		
		return $data;
	}
	
	public function obtenerDiferenciaFechas($inicio,$fin,$criterio)
	{
		$sql ="	select timestampdiff($criterio,'$inicio','$fin') as valor  ";
		#echo $sql;
		return $this->db->query($sql)->row()->valor;
	}
	
	public function obtenerEgresosMes($fecha,$idCuenta=0)
	{
		$sql ="	select coalesce(sum(a.pago),0) as total
		from catalogos_egresos as a
		where a.idEgreso>0
		and a.devolucion='0'
		and date(a.fecha) between '$fecha-01' and (select last_day('$fecha-01'))
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta' ":'';
		#echo $sql;
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerIngresosMes($fecha,$idCuenta=0)
	{
		$sql=" select coalesce(sum(a.pago),0) as total
		from catalogos_ingresos as a
		where a.idIngreso>0
		and date(a.fecha) between '$fecha-01' and (select last_day('$fecha-01'))
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$idCuenta>0?" and a.idCuenta='$idCuenta' ":'';
		
		#echo $sql;
		return $this->db->query($sql)->row()->total;
	}
	
	//PEDIDOS
	public function contarPedidos($inicio,$fin,$criterio,$idZona,$idUsuario)
	{
		$sql ="	select a.idCotizacion
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		
		inner join cotizaciones_pedidos as c
		on c.idCotizacion=a.idCotizacion 
		
		where a.ordenCompra is not null 
		and a.cancelada='0'
		and a.activo='1' 
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (b.empresa like '%$criterio%' or a.folio like '$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= " and date(c.fechaEntrega) between '$inicio' and '$fin' ";

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerPedidos($numero,$limite,$inicio,$fin,$criterio,$idZona,$idUsuario)
	{
		$sql ="	
		
		select a.total, a.idCotizacion, a.folio,
		
		b.empresa, b.telefono, c.sabor, c.cobertura, c.relleno, c.forma, c.decoracion, c.fechaEntrega, c.hora,
		c.idEstado, c.idTipo, c.idPedido, c.idDireccion,
		
		(select coalesce(sum(d.pago),0) from catalogos_ingresos  as d where d.idVenta=a.idCotizacion and d.idForma!=4 and acrilico='0') as pagado,
		
		(select coalesce(sum(d.pago),0) from catalogos_ingresos  as d where d.idVenta=a.idCotizacion and d.idForma!=4 and acrilico='1') as acrilico,
		
		(select d.nombre from cotizaciones_pedidos_estados  as d where d.idEstado=c.idEstado) as estado,
		(select d.nombre from cotizaciones_pedidos_tipos  as d where d.idTipo=c.idTipo) as tipo,
		
		
		(select d.referencia from clientes_direcciones  as d where d.idDireccion=c.idDireccion) as referencia
		
		
		
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		
		inner join cotizaciones_pedidos as c
		on c.idCotizacion=a.idCotizacion 

		where a.estatus='1'
		and a.cancelada='0'
		and a.activo='1'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and a.pendiente='0' ";
		
		$sql.=strlen($criterio)>0?" and (b.empresa like '%$criterio%' or a.folio like '$criterio%' ) ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'":'';
		$sql.=$idZona>0?" and d.idZona='$idZona'":'';
		$sql.= " and date(c.fechaEntrega) between '$inicio' and '$fin' ";

		#$sql.=$criterio;
		$sql.=" order by c.fechaEntrega desc ";
		$sql .=$numero>0? " limit $limite,$numero ":'';

		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPedido($idCotizacion)
	{
		$sql=" select a.total, a.idCotizacion, a.folio, a.idTienda, b.idCliente, c.idPedido, c.idPersonal, c.idDireccion, c.peso,
		b.empresa, b.telefono, c.sabor, c.cobertura, c.relleno, c.forma, c.decoracion, c.fechaEntrega, c.hora, c.especial, c.descripcion,
		(select coalesce(sum(d.pago),0) from catalogos_ingresos  as d where d.idVenta=a.idCotizacion and d.idForma!=4  and acrilico='0') as pagado,
		(select coalesce(sum(d.pago),0) from catalogos_ingresos  as d where d.idVenta=a.idCotizacion and d.idForma!=4 and acrilico='1') as acrilico,
		(select d.nombre from cotizaciones_pedidos_estados  as d where d.idEstado=c.idEstado) as estado,
		(select d.nombre from cotizaciones_pedidos_tipos  as d where d.idTipo=c.idTipo) as tipo,
		(select d.nombre from usuarios  as d where d.idUsuario=a.idUsuario) as usuario
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join cotizaciones_pedidos as c
		on c.idCotizacion=a.idCotizacion 

		where a.idCotizacion='$idCotizacion' ";

		return  $this->db->query($sql)->row();
	}
	
	public function cambiarEstado($idPedido)
	{
		$data=array
		(
			'idEstado'		=>$this->input->post('idEstado'),
		);
		
		$this->db->where('idPedido',$idPedido);
		$this->db->update('cotizaciones_pedidos',$data);
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}
	
	public function editarRepartidor($idPedido)
	{
		$data=array
		(
			'idPersonal'		=>$this->input->post('idPersonal'),
		);
		
		$this->db->where('idPedido',$idPedido);
		$this->db->update('cotizaciones_pedidos',$data);
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}
	
	//LLAMADAS DE PROSPECTOS
	public function contarLlamadasProspectos($inicio,$fin,$src)
	{
		$this->load->helper('base');

		$dsn		= obtenerConexion('iexe.edu.mx','iexe2013_elastix','Elastix%&892#','iexe2013_elastix');
		
		#$dsn 		= 'mysqli://iexe2013_elastix:Elastix%&892#@iexe.edu.mx/iexe2013_elastix';
		$base		= $this->load->database($dsn,true);
			
		$sql ="	select count(src) as  numero, sum(billsec)
		from cdr 
		where date(calldate) between '$inicio' and '$fin'
		and length(src)='3'
		and abs(src)>=101 and  abs(src)<=111 ";
		
		$sql.=$src!='0'?" and src='$src'":'';
		
		$sql.=" group by  src "; #date(calldate), 

		return $base->query($sql)->num_rows();
	}
	
	public function obtenerLlamadasProspectos($numero,$limite,$inicio,$fin,$src)
	{
		/*$dsn 		= 'mysqli://iexe2013_elastix:Elastix%&892#@iexe.edu.mx/iexe2013_elastix';
		$base		= $this->load->database($dsn,true);*/
		
		$this->load->helper('base');

		$dsn		= obtenerConexion('iexe.edu.mx','iexe2013_elastix','Elastix%&892#','iexe2013_elastix');
		
		$base		= $this->load->database($dsn,true);
		
		$sql ="	select round(sum(billsec)/60,0) as minutos, count(src) as llamadas,
		calldate as fecha, src
		from cdr
		where date(calldate) between '$inicio' and '$fin'
		and abs(src)>=101 and  abs(src)<=111 ";
		
		$sql.=$src!='0'?" and src='$src'":'';
		
		$sql.=" group by   src
		order by llamadas desc "; #date(calldate),
		
		$sql .=$numero>0? " limit $limite,$numero ":'';

		$reporte= $base->query($sql)->result();
		
		$this->load->database('default',true);
		
		return $reporte;
	}
	
	
	//ATRASOS
	public function contarAtrasos($inicio,$fin,$idUsuario,$registros=0,$criterio='')
	{
		$sql="";
		#if($registros!=1)
		{
			$sql="
			(select distinct a.idCliente
			from clientes as a
			inner join usuarios as b
			on a.idPromotor=b.idUsuario
			inner join seguimiento as c
			on c.idCliente=a.idCliente ";
			
			$sql.=" inner join clientes_campanas as f
			on a.idCampana=f.idCampana ";
			
			$sql.="  where  a.prospecto='1'
			and a.activo='1'
			and a.idZona!=2
			and a.idZona!=8
			and f.atrasos='0' ";
			
			$sql.=" and (select g.preinscrito from clientes_academicos as g where g.idCliente=a.idCliente) = '0' ";
			
			$sql.=" and timestampdiff(minute,(select concat(e.fechaSeguimiento,' ',e.horaInicial) from seguimiento_detalles as e inner join seguimiento as f on f.idSeguimiento=e.idSeguimiento where f.idCliente=a.idCliente order by e.fechaSeguimiento desc, e.horaInicial desc limit 1 ) ,now()) / 60 >= 3 ";
			#$sql.=" and (select concat(e.fechaSeguimiento,' ',e.horaInicial) from seguimiento_detalles as e inner join seguimiento as f on f.idSeguimiento=e.idSeguimiento where f.idCliente=a.idCliente order by e.fechaSeguimiento desc, e.horaInicial desc limit 1 ) < '$this->_fecha_actual' ";
			
			
			$sql.=" and (select e.fechaSeguimiento from seguimiento_detalles as e inner join seguimiento as f on f.idSeguimiento=e.idSeguimiento where f.idCliente=a.idCliente order by e.fechaSeguimiento desc limit 1 ) between '$inicio' and '$fin' ";
			
			$sql.=$idUsuario!='0'?" and b.idUsuario='$idUsuario' ":'';
			
			
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
			
			//AGREGAR LA CAMPAÑA
			$sql.=" and f.fechaFinal>curdate() ";
			
			#$sql.=" and (select concat(e.fechaSeguimiento,' ',e.horaInicial) from seguimiento_detalles as e inner join seguimiento as f on f.idSeguimiento=e.idSeguimiento where f.idCliente=a.idCliente order by e.fechaSeguimiento desc, e.horaInicial desc limit 1 ) between '$inicio' and '$fin' ";
			$sql.=" group by a.idCliente) ";
		}
		
		#if($registros!=2)
		{
			$sql.= $registros==0?" union ":'';
	
			$sql.="
			(select distinct a.idCliente
			from clientes as a
			inner join usuarios as b
			on a.idPromotor=b.idUsuario ";
			
			$sql.=" inner join clientes_campanas as f
			on a.idCampana=f.idCampana
			and f.atrasos='0' ";
			
			$sql.="
			
			and b.promotor='1'
			and a.prospecto='1'
			and a.activo='1'
			and a.idZona!=2
			and a.idZona!=8
			and timestampdiff(minute,a.fechaRegistro ,now()) / 60 >= 24
			and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=0
			and date(a.fechaRegistro) between '$inicio' and '$fin'   ";
			 
			 #and a.nuevoRegistro='1' 
			
			 //EL CRITERIO SERA CON UNA BANDERA
			/*
			*/
			
			$sql.=" and (select g.preinscrito from clientes_academicos as g where g.idCliente=a.idCliente) = '0' ";
			
			$sql.=$idUsuario!='0'?" and b.idUsuario='$idUsuario' ":'';
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
			
			//AGREGAR LA CAMPAÑA
			$sql.=" and f.fechaFinal>curdate() ";
			
			#$sql.=" and a.fechaRegistro between '$inicio' and '$fin' ";
			$sql.=" group by a.idCliente ) ";
		}

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerAtrasos($numero,$limite,$inicio,$fin,$idUsuario,$registros=0,$criterio='',$inicio,$fin)
	{
		/*$sql ="	select concat(a.nombre, ' ', a.paterno,' ', a.materno) as alumno,
		concat(b.nombre, ' ', b.apellidoPaterno,' ', b.apellidoMaterno) as promotor 
		
		from clientes as a
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		where b.promotor='1'
		AND ( SELECT TIMESTAMPDIFF(MINUTE,(SELECT CONCAT(c.fechaSeguimiento,' ',c.horaInicial) FROM seguimiento_detalles AS c INNER JOIN seguimiento AS d ON d.idSeguimiento=c.idSeguimiento WHERE a.idPromotor=d.idUsuarioRegistro ORDER BY c.fechaSeguimiento DESC, c.horaInicial DESC LIMIT 1 ),NOW()) / 60 )>= 24 ";*/
		
		$sql="";
		
		
		
			$sql="
			(select distinct a.idCliente, concat(a.nombre, ' ', a.paterno,' ', a.materno) AS prospecto,
			a.lada, a.telefono, a.ladaMovil, a.movil, a.email,
			
			f.nombre as campana, 
			
			concat(b.nombre, ' ', b.apellidoPaterno,' ', b.apellidoMaterno) as promotor,
			timestampdiff(minute,(select concat(e.fechaSeguimiento,' ',e.horaInicial) from seguimiento_detalles as e inner join seguimiento as f on f.idSeguimiento=e.idSeguimiento where f.idCliente=a.idCliente order by e.fechaSeguimiento desc, e.horaInicial desc limit 1 ) ,now()) / 60 as horas,
			(select concat(e.fechaSeguimiento,' ',e.horaInicial) from seguimiento_detalles as e inner join seguimiento as f on f.idSeguimiento=e.idSeguimiento where f.idCliente=a.idCliente order by e.fechaSeguimiento desc, e.horaInicial desc limit 1 ) as fechaSeguimiento
			from clientes as a
			inner join usuarios as b
			on a.idPromotor=b.idUsuario
			inner join seguimiento as c
			on c.idCliente=a.idCliente ";
			
			$sql.=" inner join clientes_campanas as f
			on a.idCampana=f.idCampana ";
			
			
			$sql.=" and (select g.preinscrito from clientes_academicos as g where g.idCliente=a.idCliente) = '0' ";
			
			
			$sql.=" and (select e.fechaSeguimiento from seguimiento_detalles as e inner join seguimiento as f on f.idSeguimiento=e.idSeguimiento where f.idCliente=a.idCliente order by e.fechaSeguimiento desc limit 1 ) between '$inicio' and '$fin' ";
			
			
			
			$sql.=" where a.prospecto='1'
			and a.activo='1'
			and a.idZona!=2 
			and a.idZona!=8 
			and f.atrasos='0' ";
			
			$sql.=" and timestampdiff(minute,(select concat(e.fechaSeguimiento,' ',e.horaInicial) from seguimiento_detalles as e inner join seguimiento as f on f.idSeguimiento=e.idSeguimiento where f.idCliente=a.idCliente order by e.fechaSeguimiento desc, e.horaInicial desc limit 1 ) ,now()) / 60 >= 3 ";
			#$sql.=" and (select concat(e.fechaSeguimiento,' ',e.horaInicial) from seguimiento_detalles as e inner join seguimiento as f on f.idSeguimiento=e.idSeguimiento where f.idCliente=a.idCliente order by e.fechaSeguimiento desc, e.horaInicial desc limit 1 ) < '$this->_fecha_actual' ";
			
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
			
			$sql.=$idUsuario!='0'?" and b.idUsuario='$idUsuario' ":'';
			
			//AGREGAR LA CAMPAÑA
			$sql.=" and f.fechaFinal>curdate() ";
			
			#$sql.=" and (select concat(e.fechaSeguimiento,' ',e.horaInicial) from seguimiento_detalles as e inner join seguimiento as f on f.idSeguimiento=e.idSeguimiento where f.idCliente=a.idCliente order by e.fechaSeguimiento desc, e.horaInicial desc limit 1 ) between '$inicio' and '$fin' ";
	
			$sql.=" group by a.idCliente) ";
		
		
		
		
			$sql.= $registros==0?" union ":'';
			
			$sql.="
			(select distinct a.idCliente, concat(a.nombre, ' ', a.paterno,' ', a.materno) as prospecto,
			a.lada, a.telefono, a.ladaMovil, a.movil, a.email,
			f.nombre as campana, 
			concat(b.nombre, ' ', b.apellidoPaterno,' ', b.apellidoMaterno) as promotor,
			timestampdiff(minute,a.fechaRegistro ,now()) / 60 as horas,
			a.fechaRegistro as fechaSeguimiento
			from clientes as a
			inner join usuarios as b
			on a.idPromotor=b.idUsuario ";
			
			$sql.=" inner join clientes_campanas as f
			on a.idCampana=f.idCampana
			where f.atrasos='0' ";
			
			
			$sql.=" 
			and b.promotor='1'
			and a.prospecto='1'
			and a.activo='1'
			and a.idZona!=2
			and a.idZona!=8
			and timestampdiff(minute,a.fechaRegistro ,now()) / 60 >= 3
			and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=0
			and date(a.fechaRegistro) between '$inicio' and '$fin'  ";
			 
			 #and a.nuevoRegistro='1'
			 
			 //EL CRITERIO SERA CON UNA BANDERA
			 /*
			
			 */
			 
			 $sql.=" and (select g.preinscrito from clientes_academicos as g where g.idCliente=a.idCliente) = '0' ";
			
			$sql.=$idUsuario!='0'?" and b.idUsuario='$idUsuario' ":'';
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
			
			
			#$sql.=" and a.fechaRegistro between '$inicio' and '$fin' ";
			
			//AGREGAR LA CAMPAÑA
			$sql.=" and f.fechaFinal>curdate() ";
			$sql.=" group by a.idCliente ) ";
			$sql.=" order by fechaSeguimiento desc ";
		
		

		$sql .=$numero>0? " limit $limite,$numero ":'';
		
		#echo $sql;
		
		return $this->db->query($sql)->result();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE CHECADOR
	public function contarChecador($inicio,$fin,$idLicencia=0,$criterio)
	{
		$sql=" select a.idPersonal
		from recursos_personal as a
		inner join recursos_personal_chequeo as b
		on a.idPersonal=b.idPersonal
		where  a.idLicencia='$this->idLicencia'
		and date(b.fecha) between '$inicio' and '$fin'
		and (a.nombre like '%$criterio%' or a.numeroAcceso like '%$criterio%' or b.dia like '%$criterio%') ";

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerChecador($numero,$limite,$inicio,$fin,$idLicencia=0,$criterio)
	{

		$sql=" select a.nombre, a.numeroAcceso, b.*, d.horaInicial as horaInicialPersonal, d.horaFinal as horaFinalPersonal,
		(select c.nombre from catalogos_departamentos as c where a.idDepartamento=c.idDepartamento) as departamento,
		(select c.nombre from puestos as c where a.idPuesto=c.idPuesto) as puesto		
		from recursos_personal as a
		inner join recursos_personal_chequeo as b
		on a.idPersonal=b.idPersonal
		
		inner join recursos_personal_horarios as d
		on a.idPersonal=d.idPersonal
		
		where  a.idLicencia='$this->idLicencia'
		and date(b.fecha) between '$inicio' and '$fin'
		and (a.nombre like '%$criterio%' or a.numeroAcceso like '%$criterio%' or b.dia like '%$criterio%') ";
		
		$sql .=$numero>0?" limit $limite,$numero ":''; 


		return $this->db->query($sql)->result();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE ENVÍOS
	public function contarEnvios($inicio,$fin,$criterio='',$idRuta=0,$cobrados=0,$idPersonal=0,$folioTicket)
	{
		$sql ="	select a.idCotizacion
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		where a.estatus=1
		and a.cancelada='0'
		and a.activo='1'
		and a.idRuta>0
		and a.idLicencia='$this->idLicencia'
		
		and a.pendiente='0'
		and a.fechaCompra between '$inicio' and '$fin' ";
		#and a.total > (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4') 
		$sql.=strlen($criterio)>0?" and (c.empresa like '$criterio%' or a.folio like '%$criterio%' )":'';
		$sql.=$idRuta>0?" and a.idRuta='$idRuta' ":'';
		$sql.=$cobrados==1?" and a.total = (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4') ":'';
		$sql.=$cobrados==2?" and a.total != (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4') ":'';
		
		$sql.=$idPersonal>0?" and exists(select d.idPersonal
		from cotizaciones_tickets as d
		inner join cotizaciones_tickets_detalles as e
		on e.idTicket=d.idTicket
		where d.idPersonal='$idPersonal'
		and e.idCotizacion=a.idCotizacion) ":'';

		$sql.=strlen($folioTicket)>0?" and exists(select d.folio
		from cotizaciones_tickets as d
		inner join cotizaciones_tickets_detalles as e
		on e.idTicket=d.idTicket
		where d.folio='$folioTicket'
		and e.idCotizacion=a.idCotizacion) ":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerEnvios($numero,$limite,$inicio,$fin,$criterio='',$idRuta=0,$cobrados=0,$fecha=0,$idPersonal=0,$envio=0,$folioTicket,$folioIndividual='0')
	{
		$sql ="	select a.ordenCompra, a.fechaCompra as fechaCompra, a.total, a.observaciones,
		c.empresa, a.fechaVencimiento, a.diasCredito, c.telefono, a.folio, a.fechaEntrega,
		a.idCotizacion as idVenta, a.idFactura, c.email, a.total, a.cancelada,
		(a.total-(select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4'))  as saldo,
	 	(select date_add( (select f.fecha from facturas as f where f.idCotizacion=a.idCotizacion order by f.fecha desc limit 1) , interval a.diasCredito day))  as fechaVencimiento,
		(select d.nombre from  catalogos_rutas as d where d.idRuta=a.idRuta) as ruta,
		(select d.nombre from  configuracion_estaciones as d where d.idEstacion=a.idEstacion) as estacion,		
		(select concat(d.serie,d.folio) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0' and d.pendiente='0' limit 1) as factura,
        
        (select coalesce(sum(d.cantidad),0) from ventas_entrega_detalles as d
		inner join cotiza_productos as e
		on e.idProducto=d.idProducto		
		where e.idCotizacion=a.idCotizacion ) as numeroEntregados,

		if((exists(select d.idTicket from cotizaciones_tickets_detalles as d where d.idCotizacion=a.idCotizacion)),1,0) as idTicket,
		(select d.folio from cotizaciones_tickets as d inner join cotizaciones_tickets_detalles as e on e.idTicket=d.idTicket where e.idCotizacion=a.idCotizacion limit 1) as folioTicket,
		(select d.idTicket from cotizaciones_tickets as d inner join cotizaciones_tickets_detalles as e on e.idTicket=d.idTicket where e.idCotizacion=a.idCotizacion and e.idEntrega>0 limit 1) as entregados,

		(select d.nombre 
		from recursos_personal as d
		inner join cotizaciones_tickets as e
		on e.idPersonal=d.idPersonal
		inner join cotizaciones_tickets_detalles as f
		on e.idTicket=f.idTicket
		where f.idCotizacion=a.idCotizacion limit 1) as personal
		
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		where a.estatus=1
		and a.cancelada='0'
		and a.activo='1'
		and a.idRuta>0
		and a.idLicencia='$this->idLicencia'
		and a.pendiente='0' ";
		
		if($folioIndividual=='0') $sql.=$fecha==0?" and a.fechaCompra between '$inicio' and '$fin' ":" and a.fechaEntrega between '$inicio' and '$fin' ";
		#and a.total > (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4')
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '$criterio%' or a.folio like '%$criterio%' )":'';
		$sql.=$idRuta>0?" and a.idRuta='$idRuta' ":'';
		$sql.=$cobrados==1?" and a.total = (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4') ":'';
		$sql.=$cobrados==2?" and a.total != (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4') ":'';
		
		$sql.=$idPersonal>0?" and exists(select d.idPersonal
		from cotizaciones_tickets as d
		inner join cotizaciones_tickets_detalles as e
		on e.idTicket=d.idTicket
		where d.idPersonal='$idPersonal'
		and e.idCotizacion=a.idCotizacion) ":'';

		$sql.=$envio==1?" and exists(select d.idTicket
		from cotizaciones_tickets_detalles as d
		where d.idEntrega=0
		and d.idCotizacion=a.idCotizacion) ":'';

		$sql.=strlen($folioTicket)>0?" and exists(select d.folio
		from cotizaciones_tickets as d
		inner join cotizaciones_tickets_detalles as e
		on e.idTicket=d.idTicket
		where d.folio='$folioTicket'
		and e.idCotizacion=a.idCotizacion) ":'';

		$sql .=" group by a.idCotizacion 
		order by ".($envio==0?" fechaCompra desc":" folioTicket desc ")." ";
		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}

	public function sumarEnvios($inicio,$fin,$criterio='',$idRuta=0,$cobrados=0,$idPersonal=0,$folioTicket)
	{
		$sql ="	select  coalesce(sum((a.total-(select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4'))),0) as total
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		where a.estatus='1'
		and a.activo='1'
		and a.idRuta>0
		and a.cancelada='0'
		and a.idLicencia='$this->idLicencia' 
		and a.total > (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4')
		and a.pendiente='0' ";
		
		$sql.=" and a.fechaCompra between '$inicio' and '$fin' ";
		
		$sql.=strlen($criterio)>0?" and (c.empresa like '$criterio%' or a.folio like '%$criterio%' )":'';
		$sql.=$idRuta>0?" and a.idRuta='$idRuta' ":'';
		$sql.=$cobrados==1?" and a.total = (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4') ":'';
		$sql.=$cobrados==2?" and a.total != (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4') ":'';

		return $this->db->query($sql)->row()->total;
	}

	public function obtenerEnviosTicket()
	{
		$numeroRegistros	= $this->input->post('txtNumeroRegistros');

		$sql ="	select a.ordenCompra, a.fechaCompra as fechaCompra, a.total, a.observaciones,
		c.empresa, a.fechaVencimiento, a.diasCredito, c.telefono, a.folio, a.fechaEntrega,
		a.idCotizacion as idVenta, a.idFactura, c.email, a.total, a.cancelada,
		(a.total-(select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4'))  as saldo,
	 	(select date_add( (select f.fecha from facturas as f where f.idCotizacion=a.idCotizacion order by f.fecha desc limit 1) , interval a.diasCredito day))  as fechaVencimiento,
		(select d.nombre from  catalogos_rutas as d where d.idRuta=a.idRuta) as ruta,
		(select d.nombre from  configuracion_estaciones as d where d.idEstacion=a.idEstacion) as estacion,
		(select concat(d.serie,d.folio) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0' and d.pendiente='0' limit 1) as factura,
        
        (select coalesce(sum(d.cantidad),0) from ventas_entrega_detalles as d
		inner join cotiza_productos as e
		on e.idProducto=d.idProducto		
		where e.idCotizacion=a.idCotizacion ) as numeroEntregados
		
		
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		where a.estatus=1
		and a.cancelada='0'
		and a.activo='1'
		and a.idRuta>0
		and a.idLicencia='$this->idLicencia'
		and a.pendiente='0' ";

		$sql.=" and ( ";
		$c=0;
		for($i=0;$i<$numeroRegistros;$i++)
		{
			$idCotizacion	= $this->input->post('chkVenta'.$i);

			if($idCotizacion>0)
			{
				$sql.=$c==0?" a.idCotizacion='$idCotizacion' ":" or a.idCotizacion='$idCotizacion' ";

				$c++;
			}
		}
		
		$sql.=" ) ";

		$sql .=" order by fechaCompra desc";
		

		return $this->db->query($sql)->result();
	}

	public function revisarRelacionTicket($ventas,$idPersonal)
	{
		return $this->db->select("a.idDetalle")
		->from("cotizaciones_tickets_detalles a")
		->join("cotizaciones_tickets b","a.idTicket=b.idTicket")
		#->where("b.idPersonal",$idPersonal)
		->where("a.idCotizacion",$ventas[0]->idVenta)->get()->row()!=null?false:true;
	}

	public function obtenerFolio()
	{
		return $this->db->select_max("folio")
		->from("cotizaciones_tickets")
		->where("idLicencia",$this->idLicencia)
		->get()->row()->folio+1;
	}
	
	public function registrarRelacionTicket($ventas,$idPersonal,$idVehiculo)
	{
		if(!$this->revisarRelacionTicket($ventas,$idPersonal)) return array('0','Ya existe un ticket relacionado'); ;

		$this->db->query("lock table cotizaciones_tickets write, cotizaciones_tickets_detalles write");

		$data=array
		(
			'idPersonal'		=> $idPersonal,
			'idVehiculo'		=> $idVehiculo,
			'idUsuarioRegistro'	=> $this->idUsuario,
			'fechaRegistro'		=> $this->_fecha_actual,
			'folio'				=> $this->obtenerFolio(),
			'idLicencia'		=> $this->idLicencia,
			'idEstacion'		=> $this->idEstacion,
		);

		$this->db->insert('cotizaciones_tickets',$data);
		$idRegistro=$this->db->insert_id();

		foreach($ventas as $row)
		{
			$data=array
			(
				'idTicket'		=> $idRegistro,
				'idCotizacion'	=> $row->idVenta,
			);

			$this->db->insert('cotizaciones_tickets_detalles',$data);
		}

		$this->db->query("unlock tables");

		return array('1',sha1($idRegistro));
	}

	public function contarReporteEntregas($criterio,$inicio,$fin,$idPersonal,$idVehiculo)
	{
		 $this->db->select("a.idTicket")
		->from("cotizaciones_tickets")
		->where("date(fechaRegistro) between '$inicio' and '$fin' ","",false)
		->where("idLicencia",$this->idLicencia);

		if(strlen($criterio)>0) $this->db->like("folio",$criterio,"after");
		if($idPersonal>0) $this->db->where("idPersonal",$idPersonal);
		if($idVehiculo>0) $this->db->where("idVehiculo",$idVehiculo);

		return $this->db->count_all_results();
	}

	public function obtenerReporteEntregas($numero=0,$limite=0,$criterio,$inicio,$fin,$idPersonal,$idVehiculo)
	{
		 $this->db->select("a.idTicket, a.idPersonal, a.folio, a.fechaRegistro, 
		b.nombre as personal, c.modelo, c.marca, d.usuario as tienda ")
		->from("cotizaciones_tickets a")
		->join("recursos_personal b","a.idPersonal=b.idPersonal","left")
		->join("recursos_vehiculos c","a.idVehiculo=c.idVehiculo","left")
		->join("licencias d","a.idLicencia=d.idLicencia","left")
		->where("date(a.fechaRegistro) between '$inicio' and '$fin' ","",false)
		->where("a.idLicencia",$this->idLicencia);

		if(strlen($criterio)>0) $this->db->like("a.folio",$criterio,"after");
		if($idPersonal>0) $this->db->where("a.idPersonal",$idPersonal);
		if($idVehiculo>0) $this->db->where("a.idVehiculo",$idVehiculo);

		return $this->db->order_by('a.folio desc')
		->get('', $numero, $limite)->result();
	}

	public function obtenerTicket($idTicket)
	{
		return $this->db->select("a.idTicket, a.folio, a.fechaRegistro, b.nombre as personal, c.modelo, c.marca, d.usuario as tienda ")
		->from("cotizaciones_tickets a")
		->join("recursos_personal b","a.idPersonal=b.idPersonal","left")
		->join("recursos_vehiculos c","a.idVehiculo=c.idVehiculo","left")
		->join("licencias d","a.idLicencia=d.idLicencia","left")
		->where("sha1(a.idTicket)",$idTicket)->get()->row();
	}

	public function obtenerDetallesTicket($idTicket)
	{
		 return $this->db->select("a.idTicket, a.idCotizacion, b.total, b.folio, b.fechaCompra, c.nombre as estacion, d.empresa as cliente,
		(select coalesce(sum(d.pago),0) from catalogos_ingresos as d where d.idVenta=b.idCotizacion) as pagado")
		->from("cotizaciones_tickets_detalles a")
		->join("cotizaciones b","a.idCotizacion=b.idCotizacion")
		->join("configuracion_estaciones c","b.idEstacion=c.idEstacion","left")
		->join("clientes d","d.idCliente=b.idCliente")
		->where("a.idTicket",$idTicket)->get()->result();
	}

	public function contarReporteInventario($criterio,$inicio,$fin,$folio,$folioTicket)
	{
		$this->db->select("a.idProducto ") 
		->from("productos a")
		->join("cotiza_productos b","a.idProducto=b.idProduct")
		->join("cotizaciones c","b.idCotizacion=c.idCotizacion")
		->join("configuracion_estaciones e","e.idEstacion=c.idEstacion")
		->where("date(c.fechaCompra) between '$inicio' and '$fin' ","",false)
		->where("c.idLicencia",$this->idLicencia)
		->where("c.cancelada",'0')
		->where("c.activo",'1')
		->where("c.pendiente",'0')
		->where("c.idRuta>0",'',false);

		if(strlen($folio)>0) $this->db->like("concat(e.nombre,c.folio)",$folio,"after");
		if(strlen($folioTicket)>0) $this->db->where("exists(select f.folio from cotizaciones_tickets as f
		inner join cotizaciones_tickets_detalles as g
		on g.idTicket=f.idTicket
		where g.idCotizacion=c.idCotizacion
		and f.folio='$folioTicket') ","",false);

		if(strlen($criterio)>0):
			$this->db->group_start()
			->like('a.nombre', $criterio, 'after')
			->or_like('a.codigoInterno', $criterio, 'after')
			->group_end();
		endif;

		return $this->db->count_all_results();
	}

	public function obtenerReporteInventario($numero=0,$limite=0,$criterio,$inicio,$fin,$folio,$folioTicket)
	{
		 $this->db->select("a.idProducto, a.codigoInterno, a.nombre as producto, b.cantidad, c.folio, c.fechaCompra, e.nombre as estacion,
		(select coalesce(sum(d.cantidad),0) from ventas_entrega_detalles as d where d.idProducto=b.idProducto ) as entregado, f.comentarios,
		f.cantidad as cantidadEntregada, f.noEntregados,
		(select f.folio from cotizaciones_tickets as f
		inner join cotizaciones_tickets_detalles as g
		on g.idTicket=f.idTicket
		where g.idCotizacion=c.idCotizacion limit 1) as folioTicket") 
		->from("productos a")
		->join("cotiza_productos b","a.idProducto=b.idProduct")
		->join("cotizaciones c","b.idCotizacion=c.idCotizacion")
		->join("configuracion_estaciones e","e.idEstacion=c.idEstacion")

		->join("cotizaciones_tickets_entregas f","b.idProducto=f.idProducto","left")

		->where("date(c.fechaCompra) between '$inicio' and '$fin' ","",false)
		->where("c.idLicencia",$this->idLicencia)
		->where("c.cancelada",'0')
		->where("c.activo",'1')
		->where("c.pendiente",'0')
		->where("c.idRuta>0",'',false);

		if(strlen($folio)>0) $this->db->like("concat(e.nombre,c.folio)",$folio,"after");
		if(strlen($folioTicket)>0) $this->db->where("exists(select f.folio from cotizaciones_tickets as f
		inner join cotizaciones_tickets_detalles as g
		on g.idTicket=f.idTicket
		where g.idCotizacion=c.idCotizacion
		and f.folio='$folioTicket') ","",false);

		if(strlen($criterio)>0):
			$this->db->group_start()
			->like('a.nombre', $criterio, 'after')
			->or_like('a.codigoInterno', $criterio, 'after')
			->group_end();
		endif;

		return $this->db->order_by('folioTicket desc, c.folio desc,a.nombre asc')
		->get('', $numero, $limite)->result();
	}

	
	
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE CORTE DIARIO
	public function obtenerFoliosCorteFactura($fecha)
	{
		$sql ="	select coalesce(max(folio),0) as folioMayor,
		coalesce(min(folio),0) as folioMenor,
		coalesce(sum(total),0) as total
		from facturas
		where pendiente='0'
		and cancelada='0'
		and idLicencia='$this->idLicencia'
		and date(fecha) ='$fecha' ";
	
		return $this->db->query($sql)->row();
	}
	
	public function obtenerFoliosCorteRemisión($fecha)
	{
		$sql ="	select coalesce(max(a.folio),0) as folioMayor,
		coalesce(min(a.folio),0) as folioMenor, b.nombre as estacion, a.idEstacion	
		from cotizaciones as a
		inner join configuracion_estaciones as b
		on a.idEstacion=b.idEstacion
		where a.prefactura=0
		and a.idLicencia='$this->idLicencia'
		and a.estatus=1
		and a.cancelada='0'
		and a.activo='1'
		and date(a.fechaCompra) ='$fecha'
		group by a.idEstacion
		order by b.nombre asc ";
	
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTotalCorteContado($fecha,$idEstacion)
	{
		$sql ="	select coalesce(sum(total),0) as total	
		from cotizaciones as a
		where a.prefactura=0
		and a.idLicencia='$this->idLicencia'
		and a.estatus=1
		and date(a.fechaCompra) ='$fecha'
		and a.idForma!=7
		and a.cancelada='0'
		and a.activo='1'
		and a.idEstacion='$idEstacion' ";
	
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerTotalCorteCredito($fecha,$idEstacion)
	{
		$sql ="	select coalesce(sum(total),0) as total	
		from cotizaciones as a
		where a.prefactura=0
		and a.idLicencia='$this->idLicencia'
		and a.estatus=1
		and date(a.fechaCompra) ='$fecha'
		and a.idForma=7
		and a.cancelada='0'
		and a.activo='1'
		and a.idEstacion='$idEstacion'";
	
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerFoliosCortePrefactura($fecha)
	{
		$sql ="	select coalesce(max(a.folio),0) as folioMayor,
		coalesce(min(a.folio),0) as folioMenor, b.nombre as estacion, a.idEstacion	
		from cotizaciones as a
		inner join configuracion_estaciones as b
		on a.idEstacion=b.idEstacion
		where a.prefactura=1
		and a.idLicencia='$this->idLicencia'
		and a.estatus=1
		and a.cancelada='0'
		and a.activo='1'
		and date(a.fechaCompra) ='$fecha'
		group by a.idEstacion
		order by b.nombre asc ";
	
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTotalPrefacturaContado($fecha,$idEstacion)
	{
		$sql ="	select coalesce(sum(a.total),0) as total	
		from cotizaciones as a
		where a.prefactura=1
		and a.idLicencia='$this->idLicencia'
		and a.estatus=1
		and date(a.fechaCompra) ='$fecha'
		and a.idForma!=7
		and a.cancelada='0'
		and a.activo='1'
		and a.idEstacion='$idEstacion' ";
		
		#and (select count(b.idFactura) from facturas as b where b.idFactura=a.idFactura and b.cancelada='0' and b.pendiente='0') = 0
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerTotalPrefacturaCredito($fecha,$idEstacion)
	{
		$sql ="	select coalesce(sum(a.total),0) as total	
		from cotizaciones as a
		where a.prefactura=1
		and a.idLicencia='$this->idLicencia'
		and a.estatus=1
		and date(a.fechaCompra) ='$fecha'
		and a.idForma=7 
		and a.cancelada='0'
		and a.activo='1'
		and a.idEstacion='$idEstacion'
		";
		
		#and (select count(b.idFactura) from facturas as b where b.idFactura=a.idFactura and b.cancelada='0' and b.pendiente='0') = 0
	
		return $this->db->query($sql)->row()->total;
	}
	
	//PAGOS CRÉDITOS
	public function contarPagosCredito($inicio,$fin,$criterio='')
	{		
		$sql =" select a.idIngreso
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		inner join clientes as c
		on c.idCliente=b.idCliente
		where a.idIngreso>0
		and b.idForma=7
		and a.idForma!=4
		and a.idLicencia='$this->idLicencia'
		and b.cancelada='0'
		and b.activo='1'  
		and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=strlen($criterio)>0?" and c.empresa like '$criterio%'  ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerPagosCredito($numero,$limite,$inicio,$fin,$criterio='')
	{
		$sql ="	select a.*, b.idCotizacion, b.folio, b.total,
		a.fecha as fechaPago, c.empresa as cliente,
		(select d.idFactura
		from facturas as d 
		inner join facturas_ingresos as e
		on d.idFactura=e.idFactura
		where a.idIngreso=e.idIngreso
		order by d.idFactura desc limit 1) as idFactura,
		(select d.nombre from productos as d where d.idProducto=a.idProductoCatalogo) as productoCatalogo,
		(select concat(d.cuenta,'|',e.nombre) from cuentas as d
		inner join bancos as e
		on d.idBanco=e.idBanco 
		where d.idCuenta=a.idCuenta limit 1) as banco,
		(select d.nombre from catalogos_formas as d where d.idForma=a.idForma) as forma,
		(select d.nombre from configuracion_estaciones as d where d.idEstacion=b.idEstacion) as estacion
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		inner join clientes as c
		on c.idCliente=b.idCliente
		where a.idIngreso>0
		and b.idForma=7
		and a.idForma!=4
		and a.idLicencia='$this->idLicencia'
		and b.cancelada='0'
		and b.activo='1' 
		and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=strlen($criterio)>0?" and c.empresa like '$criterio%'  ":'';

		$sql.=" order by fechaPago desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarPagosCredito($inicio,$fin,$criterio='')
	{
		$sql ="	select coalesce(sum(a.pago),0) as ingresos
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		inner join clientes as c
		on c.idCliente=b.idCliente
		where a.idIngreso>0
		and b.idForma=7
		and a.idForma!=4
		and a.idLicencia='$this->idLicencia'
		and b.cancelada='0'
		and b.activo='1' 
		and date(a.fecha) between '$inicio' and '$fin' ";

		$sql.=strlen($criterio)>0?" and c.empresa like '$criterio%'  ":'';
		
		return $this->db->query($sql)->row()->ingresos;
	}
	
	//PREFACTURAS
	public function contarPrefacturas($inicio,$fin,$criterio='')
	{		
		$sql =" select a.idCotizacion
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join cotizaciones as c
		on c.idCotizacionPasada=a.idCotizacion
		where a.idLicencia='$this->idLicencia'
		and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=strlen($criterio)>0?" and (b.empresa like '$criterio%' or a.folio like '$criterio%' or c.folio like '$criterio%' )  ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerPrefacturas($numero,$limite,$inicio,$fin,$criterio='')
	{
		$sql =" select a.idCotizacion, a.folio, a.fechaCompra,
		c.folio as folioRemision, c.fechaCompra as fechaRemision,
		a.total,b.empresa
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join cotizaciones as c
		on c.idCotizacionPasada=a.idCotizacion
		where a.idLicencia='$this->idLicencia'
		and date(a.fecha) between '$inicio' and '$fin' ";
		
		$sql.=strlen($criterio)>0?" and (b.empresa like '$criterio%' or a.folio like '$criterio%' or c.folio like '$criterio%' )  ":'';

		$sql.=" order by a.fechaCompra desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRegistrosCaja($fecha)
	{
		$sql =" (select a.folio, a.tipoRegistro, a.pago as importe,
		a.fecha as fecha, '' as estacion
		from catalogos_egresos as a
		where a.idLicencia='$this->idLicencia'
		and date(a.fecha) = '$fecha'
		and a.tipoRegistro>0 ) ";
		
		$sql .=" union (select b.folio, 0 as tipoRegistro, sum(a.pago) as importe,
		a.fecha as fecha, 
		(select c.nombre from configuracion_estaciones as c where c.idEstacion=b.idEstacion limit 1) as estacion
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where a.idLicencia='$this->idLicencia'
		and date(a.fecha) = '$fecha' 
		and b.cancelada='0' 
		and b.activo='1'
		and a.idForma=1
		group by b.idCotizacion ) ";

		$sql.=" order by fecha asc ";

		
		return $this->db->query($sql)->result();
	}
	
	//REPORTE RETIROS
	public function contarReporteRetiros($inicio,$fin,$idEstacion=0)
	{		
		$sql =" select count(a.idEgreso) as numero
		from catalogos_egresos as a
		where a.idLicencia='$this->idLicencia'
		and date(a.fecha) between '$inicio' and '$fin'
		and a.tipoRegistro>0 ";
		
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion'  ":'';
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerReporteRetiros($numero,$limite,$inicio,$fin,$idEstacion=0)
	{
		$sql =" (select a.idEgreso, a.fecha as fecha, a.pago, a.tipoRegistro, sha1(a.idEgreso) as id,
		(select b.nombre from configuracion_estaciones as b where b.idEstacion=a.idEstacion) as estacion, a.producto
		from catalogos_egresos as a
		where a.idLicencia='$this->idLicencia'
		and date(a.fecha) between '$inicio' and '$fin'
		and a.tipoRegistro>0 ";
		
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion'  ":'';
		
		
		$sql.=" ) union (";
		
		
		$sql .=" select a.idIngreso as idEgreso, a.fecha as fecha, a.pago, '3' as tipoRegistro, 0 as id, '' as estacion, a.producto
		from catalogos_ingresos as a
		where a.idLicencia='$this->idLicencia'
		and date(a.fecha) between '$inicio' and '$fin'
		and a.saldoInicial='1' ) ";
		

		$sql.=" order by fecha desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
    
    public function obtenerRegistroValesRetiros($idEgreso=0)
	{
		$sql =" select a.idEgreso, a.fecha as fecha, a.pago, a.tipoRegistro, a.idUsuario, a.idUsuarioRetiro,
		(select b.nombre from configuracion_estaciones as b where b.idEstacion=a.idEstacion) as estacion, a.producto,
        (select concat(b.nombre,' ',b.apellidoPaterno, ' ', b.apellidoMaterno) from usuarios as b where b.idUsuario=a.idUsuario) as usuario,
		(select concat(b.nombre,' ',b.apellidoPaterno, ' ', b.apellidoMaterno) from usuarios as b where b.idUsuario=a.idUsuarioRetiro) as usuarioRetiro
		from catalogos_egresos as a
		where sha1(a.idEgreso)='$idEgreso' ";
        
		return $this->db->query($sql)->row();
	}
	
	//REPORTE VENTAS EFECTIVO
	public function contarReporteVentasEfectivo($inicio,$fin,$idEstacion=0)
	{		
		$sql =" select count(a.idIngreso) as numero
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where a.idLicencia='$this->idLicencia'
		and date(a.fecha) between '$inicio' and '$fin' 
		and a.idForma=1  ";
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion'  ":'';
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerReporteVentasEfectivo($numero,$limite,$inicio,$fin,$idEstacion=0)
	{
		$sql =" select a.idIngreso, a.fecha, a.pago, b.folio, b.prefactura,
		(select c.nombre from configuracion_estaciones as c where  c.idEstacion=b.idEstacion ) as estacion
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where a.idLicencia='$this->idLicencia'
		and date(a.fecha) between '$inicio' and '$fin'
		and a.idForma=1 ";
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion'  ":'';
		
		$sql.=" order by a.fecha desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarReporteVentasEfectivo($inicio,$fin,$idEstacion=0)
	{
		$sql =" select coalesce(sum(a.pago),0) as total
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where a.idLicencia='$this->idLicencia'
		and date(a.fecha) between '$inicio' and '$fin'
		and a.idForma=1 ";
		
		$sql.=$idEstacion>0?" and b.idEstacion='$idEstacion'  ":'';

		return $this->db->query($sql)->row()->total;
	}
	
	//PRECIO 1
	public function contarPrecio1($inicio,$fin,$criterio='',$idEstacion=0,$idUsuario=0)
	{		
		$sql =" select a.idCotizacion
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join cotiza_productos as c
		on c.idCotizacion=a.idCotizacion
		where a.idLicencia='$this->idLicencia'
		and date(a.fecha) between '$inicio' and '$fin'
		and c.precio1='1' ";
		
		$sql.=strlen($criterio)>0?" and (b.empresa like '$criterio%' or a.folio like '$criterio%' )  ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'  ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion'  ":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerPrecio1($numero,$limite,$inicio,$fin,$criterio='',$idEstacion=0,$idUsuario=0)
	{
		$sql =" select a.idCotizacion, a.folio, a.fechaCompra,b.empresa, a.ivaPorcentaje,
		c.cantidad, c.precio, c.importe, concat(d.nombre,' ',d.apellidoPaterno, ' ', d.apellidoMaterno) as usuario,
		e.nombre as estacion, f.nombre as producto, g.nombre as formaPago
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join cotiza_productos as c
		on c.idCotizacion=a.idCotizacion
		inner join usuarios as d
		on d.idUsuario=a.idUsuario
		inner join configuracion_estaciones as e
		on e.idEstacion=a.idEstacion
		inner join productos as f
		on f.idProducto=c.idProduct
		inner join catalogos_formas as g
		on g.idForma=a.idForma
		where a.idLicencia='$this->idLicencia'
		and date(a.fecha) between '$inicio' and '$fin'
		and c.precio1='1' ";
		
		$sql.=strlen($criterio)>0?" and (b.empresa like '$criterio%' or a.folio like '$criterio%' )  ":'';
		$sql.=$idUsuario>0?" and a.idUsuario='$idUsuario'  ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion'  ":'';

		$sql.=" order by a.fechaCompra desc ";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}

	public function cotizacionPdf($idCotizacion,$desglose=0,$opcion=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 

		$this->load->library('mpdf/mpdf');
		$this->load->library('ccantidadletras');

		$data['cotizacion'] 	= $this->ventas->obtenerRemision($idCotizacion);
		$data['contacto'] 		= $this->clientes->obtenerContacto($data['cotizacion']->idContacto);
		$data['cliente'] 		= $this->ventas->obtenerCliente($data['cotizacion']->idCliente);
		$data['productos'] 		= $this->ventas->obtenerProductos($data['cotizacion']->idCotizacion);
		$data['empresa'] 		= $this->configuracion->obtenerConfiguraciones($data['cotizacion']->idLicencia);
		$data['cuentas']		= $this->configuracion->obtenerCuentasReportes();
		$data['direccion'] 		= $this->clientes->obtenerDireccionEntrega($data['cotizacion']->idDireccion);
		$data['sucursales'] 	= $this->configuracion->obtenerLicenciasRegistro();
		$data['desglose']		= $desglose;
		$data['reporte'] 		= 'formatos/cotizacion/cotizacion';
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['cotizacion']->total);
		$this->ccantidadletras->setMoneda($data['cotizacion']->divisa);//
		$CantidadLetras=$this->ccantidadletras->PrimeraMayuscula();
		
		$data['cantidadLetra']	= $CantidadLetras;

		$html		= $this->load->view('formatos/principal',$data,true);
		#$cabecera	= $this->load->view('formatos/cotizacion/cabecera',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',10,10,33,5,2,0);
		#$this->mpdf->SetHTMLHeader($cabecera);
		#$this->mpdf->SetHTMLHeader($cabecera,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		#$this->mpdf->Output();

		if($opcion==0)
		{
			$this->mpdf->Output($data['cotizacion']->folioCotizacion.'.pdf','D');
		}

		if($opcion==1)
		{
			$this->mpdf->Output('media/cotizaciones/'.$data['cotizacion']->folioCotizacion.'.pdf','F');

			return 'media/cotizaciones/'.$data['cotizacion']->folioCotizacion.'.pdf';
		}
	}

	public function cotizacionPdfMura($idCotizacion,$desglose=0,$opcion=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 

		$this->load->library('mpdf/mpdf');
		$this->load->library('ccantidadletras');

		$data['cotizacion'] 	= $this->ventas->obtenerRemision($idCotizacion);
		$data['contacto'] 		= $this->clientes->obtenerContacto($data['cotizacion']->idContacto);
		$data['cliente'] 		= $this->ventas->obtenerCliente($data['cotizacion']->idCliente);
		$data['productos'] 		= $this->ventas->obtenerProductos($data['cotizacion']->idCotizacion);
		$data['empresa'] 		= $this->configuracion->obtenerConfiguraciones($data['cotizacion']->idLicencia);
		$data['cuentas']		= $this->configuracion->obtenerCuentasReportes();
		$data['direccion'] 		= $this->clientes->obtenerDireccionEntrega($data['cotizacion']->idDireccion);
		$data['sucursales'] 	= $this->configuracion->obtenerLicenciasRegistro();
		$data['desglose']		= $desglose;
		$data['reporte'] 		= 'formatos/mura/cotizacion';
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['cotizacion']->total);
		$this->ccantidadletras->setMoneda($data['cotizacion']->divisa);//
		$CantidadLetras=$this->ccantidadletras->PrimeraMayuscula();
		
		$data['cantidadLetra']	= $CantidadLetras;

		$html		= $this->load->view('formatos/principal',$data,true);
		#$cabecera	= $this->load->view('formatos/cotizacion/cabecera',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',5,5,5,5,2,0);
		#$this->mpdf->SetHTMLHeader($cabecera);
		#$this->mpdf->SetHTMLHeader($cabecera,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(); return;

		if($opcion==0)
		{
			$this->mpdf->Output($data['cotizacion']->folioCotizacion.'.pdf','D');
		}

		if($opcion==1)
		{
			$this->mpdf->Output('media/cotizaciones/'.$data['cotizacion']->folioCotizacion.'.pdf','F');

			return 'media/cotizaciones/'.$data['cotizacion']->folioCotizacion.'.pdf';
		}
	}


	public function revisarRelacionCotizacion($idCotizacion)
	{
		return $this->db->select("idDetalle")
		->from("cotizaciones_tickets_detalles")
		->where("idCotizacion",$idCotizacion)->get()->row()!=null?false:true;
	}

	public function registrarRegistroEnvios()
	{
		$numero	= $this->input->post("txtNumeroEnvios");

		for($i=0;$i<$numero;$i++)
		{
			$idCotizacion	= $this->input->post("txtIdCotizacion".$i);

			if($idCotizacion>0)
			{
				if(!$this->revisarRelacionCotizacion($idCotizacion)) return array("0","Error en el registro, revise que no hayan sido asignado los registros");
			}
		}

		$this->db->query("lock table cotizaciones_tickets write, cotizaciones_tickets_detalles write");

		$data=array
		(
			'idPersonal'		=> $this->input->post("selectPersonalEnvio"),
			'idVehiculo'		=> $this->input->post("selectVehiculoEnvio"),
			'idUsuarioRegistro'	=> $this->idUsuario,
			'fechaRegistro'		=> $this->_fecha_actual,
			'folio'				=> $this->obtenerFolio(),
			'idLicencia'		=> $this->idLicencia,
			'idEstacion'		=> $this->idEstacion,
		);

		$this->db->insert('cotizaciones_tickets',$data);
		$idRegistro=$this->db->insert_id();

		for($i=0;$i<$numero;$i++)
		{
			$idCotizacion	= $this->input->post("txtIdCotizacion".$i);

			if($idCotizacion>0)
			{
				$data=array
				(
					'idTicket'		=> $idRegistro,
					'idCotizacion'	=> $idCotizacion,
				);

				$this->db->insert('cotizaciones_tickets_detalles',$data);
			}
			
		}

		$this->db->query("unlock tables");

		return array('1',registroCorrecto);
	}
}
