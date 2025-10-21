<?php
class Informacion_modelo extends CI_Model
{
    protected $_fecha_actual;
    protected $_table;
    protected $_user_id;
    protected $_user_name;
	protected $idLicencia;
	protected $resultado;
	protected $idRol;

    function __construct()
	{
		parent::__construct();
		$this->config->load('datatables',TRUE);
	
		$this->_table 			= $this->config->item('datatables');
		$this->_fecha_actual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_user_id 		= $this->session->userdata('id');
		$this->_user_name 		= $this->session->userdata('nombreUsuarioSesion');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->resultado 		= "1";
		$this->idRol 			= $this->session->userdata('role');
    }

	public function contarCompras($idMaterial)
	{
		$sql=" select count(a.idRecibido) as numero
		from compras_recibido as a
		inner join compra_detalles as b
		on a.idDetalle=b.idDetalle
		inner join compras as c
		on c.idCompras=b.idCompra
		inner join proveedores as d
		on d.idProveedor=c.idProveedor
		where b.idMaterial='$idMaterial'
		and c.reventa='1'
		and c.cancelada='0'
		and c.activo='1'
		and c.idLicencia='$this->idLicencia'";

		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerCompras($numero=0,$limite=0,$idMaterial)
	{
		$sql=" select a.cantidad, a.fecha, b.precio, c.nombre,
		d.empresa as proveedor
		from compras_recibido as a
		inner join compra_detalles as b
		on a.idDetalle=b.idDetalle
		inner join compras as c
		on c.idCompras=b.idCompra
		inner join proveedores as d
		on d.idProveedor=c.idProveedor
		where b.idMaterial='$idMaterial'
		and c.reventa='1'
		and c.cancelada='0'
		and c.activo='1'
		and c.idLicencia='$this->idLicencia'
		order by a.fecha desc";

		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}

	public function sumarCompras($idMaterial)
	{
		$sql=" select coalesce(sum(a.cantidad),0) as cantidad
		from compras_recibido as a
		inner join compra_detalles as b
		on a.idDetalle=b.idDetalle
		inner join compras as c
		on c.idCompras=b.idCompra
		inner join proveedores as d
		on d.idProveedor=c.idProveedor
		where b.idMaterial='$idMaterial'
		and c.reventa='1'
		and c.cancelada='0'
		and c.activo='1'
		and c.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row()->cantidad;
	}

	//ENTRADAS TRASPASOS
	public function contarEntradasTraspasos($idProducto)
	{		
		$sql=" select count(a.idRecepcion) as numero
		from productos_traspasos_recepciones as a
		inner join productos_traspasos_recepciones_detalles as b
		on a.idRecepcion=b.idRecepcion
		inner join productos_traspasos_detalles as c
		on a.idRecepcion=b.idRecepcion
		inner join productos_traspasos as d
		on d.idTraspaso=c.idTraspaso
		where c.idDetalle=b.idDetalle
		and  c.idProducto='$idProducto'
		and a.idLicencia='$this->idLicencia'
		and d.activo='1' ";

		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerEntradasTraspasos($numero=0,$limite=0,$idProducto)
	{		
		$sql=" select a.folio, a.fechaRecepcion as fecha,
		a.comentarios, b.cantidad, c.idProducto,
		(select e.nombre from configuracion as e
		where e.idLicencia=d.idLicenciaOrigen limit 1) as sucursal		
		from productos_traspasos_recepciones as a
		inner join productos_traspasos_recepciones_detalles as b
		on a.idRecepcion=b.idRecepcion
		inner join productos_traspasos_detalles as c
		on a.idRecepcion=b.idRecepcion
		inner join productos_traspasos as d
		on d.idTraspaso=c.idTraspaso
		where c.idDetalle=b.idDetalle
		and  c.idProducto='$idProducto'
		and a.idLicencia='$this->idLicencia'
		and d.activo='1'
		order by a.fechaRecepcion desc ";

		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}

	public function sumarEntradasTraspasos($idProducto)
	{		
		$sql=" select coalesce(sum(b.cantidad),0) as cantidad	
		from productos_traspasos_recepciones as a
		inner join productos_traspasos_recepciones_detalles as b
		on a.idRecepcion=b.idRecepcion
		inner join productos_traspasos_detalles as c
		on a.idRecepcion=b.idRecepcion
		inner join productos_traspasos as d
		on d.idTraspaso=c.idTraspaso
		where c.idDetalle=b.idDetalle
		and  c.idProducto='$idProducto'
		and a.idLicencia='$this->idLicencia'
		and d.activo='1' ";

		return $this->db->query($sql)->row()->cantidad;
	}

	//VENTAS
	public function contarVentas($idProducto)
	{
		$sql=" select count(a.idEntrega) as numero
		from ventas_entrega_detalles as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		inner join cotizaciones as c
		on c.idCotizacion=b.idCotizacion
		inner join clientes as d
		on d.idCliente=c.idCliente
		where b.idProduct='$idProducto'
		and c.cancelada='0'
		and c.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerVentas($numero=0,$limite=0,$idProducto)
	{
		$sql=" select a.cantidad, a.fecha, b.precio, 
		c.ordenCompra, d.empresa
		from ventas_entrega_detalles as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		inner join cotizaciones as c
		on c.idCotizacion=b.idCotizacion
		inner join clientes as d
		on d.idCliente=c.idCliente
		where b.idProduct='$idProducto'
		and c.cancelada='0'
		and c.idLicencia='$this->idLicencia'
		order by fecha desc";

		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}

	public function sumarVentas($idProducto)
	{
		$sql=" select coalesce(sum(a.cantidad),0) as cantidad
		from ventas_entrega_detalles as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		inner join cotizaciones as c
		on c.idCotizacion=b.idCotizacion
		inner join clientes as d
		on d.idCliente=c.idCliente
		where b.idProduct='$idProducto'
		and c.cancelada='0'
		and c.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row()->cantidad;
	}


	//ENVIOS DE PRODUCTOS
	public function contarEnvios($idProducto)
	{
		$sql=" select count(a.idDetalle) as numero
		from productos_traspasos_detalles as a
		inner join productos_traspasos as b
		on a.idTraspaso=b.idTraspaso
		where b.idLicenciaOrigen='$this->idLicencia'
		and  a.idProducto='$idProducto'
		and b.activo='1' ";

		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerEnvios($numero=0,$limite=0,$idProducto)
	{
		$sql=" select a.cantidad, b.folio,
		b.fechaTraspaso as fecha, b.idCotizacion,
		(select c.nombre from configuracion as c where c.idLicencia=b.idLicenciaDestino limit 1) as sucursal
		from productos_traspasos_detalles as a
		inner join productos_traspasos as b
		on a.idTraspaso=b.idTraspaso
		where b.idLicenciaOrigen='$this->idLicencia'
		and  a.idProducto='$idProducto'
		and b.activo='1' 
		order by fecha desc ";

		$sql .= $numero>0?" limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}

	public function sumarEnvios($idProducto)
	{
		$sql=" select coalesce(sum(a.cantidad),0) as cantidad
		from productos_traspasos_detalles as a
		inner join productos_traspasos as b
		on a.idTraspaso=b.idTraspaso
		where b.idLicenciaOrigen='$this->idLicencia'
		and  a.idProducto='$idProducto'
		and b.activo='1' ";

		return $this->db->query($sql)->row()->cantidad;
	}

	//MOVIMIENTOS

	public function contarMovimientos($idProducto)
	{
		$sql=" select count(idMovimiento)  as numero
		from productos_inventarios_movimientos
		where idProducto='$idProducto'
		and idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerMovimientos($numero=0,$limite=0,$idProducto)
	{
		$sql=" select * 
		from productos_inventarios_movimientos
		where idProducto='$idProducto'
		and idLicencia='$this->idLicencia'
		order by fecha desc ";

		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}

	public function sumarMovimientos($idProducto)
	{
		$sql=" select coalesce(sum(cantidad),0)  as cantidad
		from productos_inventarios_movimientos
		where idProducto='$idProducto'
		and idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row()->cantidad;
	}

	//DIARIO

	public function contarDiario($idProducto)
	{
		$sql=" select count(a.idDetalle) as numero
		from productos_inventarios_registro_detalles as a
		inner join productos_inventarios_registro as b
		on a.idRegistro=b.idRegistro
		where a.idProducto='$idProducto'
		and a.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerDiario($numero=0,$limite=0,$idProducto)
	{
		$sql=" select a.stock, b.fecha
		from productos_inventarios_registro_detalles as a
		inner join productos_inventarios_registro as b
		on a.idRegistro=b.idRegistro
		where a.idProducto='$idProducto'
		and a.idLicencia='$this->idLicencia'
		order by b.fecha desc ";

		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}

	public function sumarDiario($idProducto)
	{
		$sql=" select coalesce(sum(a.stock),0) as stock
		from productos_inventarios_registro_detalles as a
		inner join productos_inventarios_registro as b
		on a.idRegistro=b.idRegistro
		where a.idProducto='$idProducto'
		and a.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row()->stock;
	}

	//NO ENTREGADOS

	public function contarEntradasEntrega($idProducto)
	{
		$sql=" select count(a.idRelacion) as numero
		from cotizaciones_tickets_entregas as a
		inner join cotizaciones_entregas as b
		on a.idEntrega=b.idEntrega

		inner join cotiza_productos as c
		on a.idProducto=c.idProducto

		inner join cotizaciones as d
		on c.idCotizacion=d.idCotizacion

		where c.idProduct='$idProducto'
		and d.idLicencia='$this->idLicencia'
		and a.noEntregados>0 ";
		
		return $this->db->query($sql)->row()->numero;
	}

	public function obtenerEntradasEntregas($numero=0,$limite=0,$idProducto)
	{
		$sql=" select a.noEntregados, a.comentarios, b.fecha,
		d.folio, (select e.nombre from configuracion_estaciones as e where e.idEstacion=d.idEstacion) as estacion
		
		from cotizaciones_tickets_entregas as a
		inner join cotizaciones_entregas as b
		on a.idEntrega=b.idEntrega

		inner join cotiza_productos as c
		on a.idProducto=c.idProducto

		inner join cotizaciones as d
		on c.idCotizacion=d.idCotizacion

		where c.idProduct='$idProducto'
		and d.idLicencia='$this->idLicencia'
		and a.noEntregados>0
		order by b.fecha desc ";

		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}

	public function sumarEntradasEntrega($idProducto)
	{
		$sql=" select coalesce(sum(a.noEntregados),0) as cantidad
		from cotizaciones_tickets_entregas as a
		inner join cotizaciones_entregas as b
		on a.idEntrega=b.idEntrega

		inner join cotiza_productos as c
		on a.idProducto=c.idProducto

		inner join cotizaciones as d
		on c.idCotizacion=d.idCotizacion

		where c.idProduct='$idProducto'
		and d.idLicencia='$this->idLicencia'
		and a.noEntregados>0 ";
		
		return $this->db->query($sql)->row()->cantidad;
	}
}
?>
