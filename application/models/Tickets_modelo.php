<?php
class Tickets_modelo extends CI_Model
{
	protected $fecha;
	protected $fechaCorta;
	protected $idUsuario;

	function __construct()
	{
		parent::__construct();
		
		$this->fecha			= date('Y-m-d H:i:s');
		$this->fechaCorta		= date('Y-m-d');
		$this->idUsuario 		= $this->session->userdata('id');
	}

	#LOGIN DE USUARIOS
	public function obtenerCotizacion($idCotizacion)
	{
		
		$sql=" select a.idCotizacion, a.total, a.idForma, a.idDireccion, a.fechaCompra, a.folio, a.idLicencia, a.idCliente, a.prefactura,
		b.empresa, b.idCliente,
		(select concat(c.vendedor) from usuarios as c where c.idUsuario=a.idUsuario) as usuario,
		(select c.nombre from configuracion_estaciones as c where c.idEstacion=a.idEstacion) as estacion
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where idCotizacion='$idCotizacion' ";
		
		$registro	=  $this->db->query($sql)->row_array();
		
		if($registro!=null)
		{
			$registro['respuesta'] = true;
		}
		
		return $registro;
	}
	
	public function obtenerProductosVenta($idCotizacion)
	{
		$sql=" select a.idProducto, a.nombre, a.cantidad, a.precio, a.importe, a.descuento,
		b.nombre as producto,
		c.nombre as periodo, b.codigoInterno, c.idPeriodo,
		(select d.descripcion from unidades as d where d.idUnidad=b.idUnidad) as unidad,
		(select coalesce(sum(d.importe),0) from cotiza_productos_impuestos as d where d.idProducto=a.idProducto) as impuestos
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto
		inner join produccion_periodos as c
		on b.idPeriodo=c.idPeriodo
		where a.idCotizacion='$idCotizacion' ";

		$registros	=  $this->db->query($sql)->result_array();
		$i=0;
		if($registros!=null)
		{
			foreach($registros as $row)
			{
				$impuesto				= $row['impuestos']/$row['cantidad'];
				
				$row['cantidad'] 		= number_format($row['cantidad'],2);
				$row['precio'] 			= number_format($row['precio']+$impuesto,2);
				$row['importe'] 		= number_format($row['importe']+$row['impuestos'],2);
					
				$registros[$i]			= $row;
				
				$i++;
			}
		}

		#return array('respuesta'=>($registros!=null?true:false),'productos'=>$registros);
		return $registros;
	}
	
	public function obtenerConfiguraciones($idLicencia=0)
	{
		$sql="select * from configuracion
		where idLicencia='$idLicencia'";
		
		return $this->db->query($sql)->row_array();
	}
	
	public function obtenerCliente($idCliente)
	{
		$sql="select a.*
		from clientes as a
		where a.idCliente='$idCliente'";	
		
		return $this->db->query($sql)->row_array();
	}
	
	public function obtenerDireccion($idDireccion)
	{    
		$sql="select * from clientes_direcciones 
		where idDireccion='$idDireccion'";
		
		return $this->db->query($sql)->row_array();
	}
	
	#OBTENER EVENTO POR ID
	public function obtenerEvento($idEvento=0)
	{
		$sql="select idEvento, nombre, fecha, hora, imagen, descripcion
		from escolar_eventos
		where activo='1'
		and idEvento='$idEvento'";

		$registro	=  $this->db->query($sql)->row_array();
		
		if($registro!=null)
		{
			$registro['respuesta'] 	= true;
			
			if(strlen($registro['imagen'])>0 and file_exists(carpetaEventos.$registro['idEvento'].'_'.$registro['imagen']))
			{
				$registro['imagen'] 	= base_url().carpetaEventos.$registro['idEvento'].'_'.$registro['imagen'];
			}
		}
		
		return $registro;
	}
	
	#OBTENER AVISO POR ID
	public function obtenerAvisos($idCliente=0)
	{
		$sql="select distinct(a.idAviso), a.nombre, a.inicio, a.fin, a.descripcion
		from escolar_avisos as a
		inner join escolar_avisos_destinatarios as b
		on a.idAviso=b.idAviso
		
		inner join escolar_destinatarios_detalles as c
		on c.idDestinatario=c.idDestinatario
		
		inner join escolar_grupos_alumnos as d
		on d.idGrupo=c.idGrupo

		where a.activo='1'
		and d.idCliente='$idCliente'";

		$registros	=  $this->db->query($sql)->result_array();

		return array('respuesta'=>$registros!=null?true:false,'avisos'=>$registros);
	}
	
	//OBTENER REGISTROS DE PADRES
	public function obtenerUsuarioPadre($usuario='',$password='',$tipoUsuario='0')
	{
		$sql=" select  b.idPadre, usuario, avisoPrivacidad
		from clientes as a
		inner join clientes_padres_relacion as b
		on a.idCliente=b.idCliente
		inner join clientes_padres as c
		on b.idPadre=c.idPadre
		where a.activo='1'
		and a.usuario='$usuario'
		and a.password='$password'
		limit 1 ";

		return $this->db->query($sql)->row_array();
	}
	
	/*public function obtenerNumeroHijosPadre($idPadre=0)
	{
		$sql=" select count(idRelacion) as numero
		from clientes_padres_relacion 
		where idPadre='$idPadre' ";

		return $this->db->query($sql)->row()->numero;
	}*/
	
	public function obtenerAlumnosPadre($idPadre=0)
	{
		$sql="select a.idCliente, a.nombre,a.paterno,a.materno,a.fotografia,c.idGrado,d.descripcion,d.nombre as grado
		from clientes as a
		inner join clientes_padres_relacion as b on a.idCliente=b.idCliente
		left join clientes_academicos as c on a.idCliente=c.idCliente
		left join escolar_grados as d on c.idGrado=d.idGrado 
		where a.activo='1'
		and b.idPadre='$idPadre' ";

		$registros	= $this->db->query($sql)->result_array();
		
		$i=0;
		if($registros!=null)
		{
			foreach($registros as $row)
			{
				if(strlen($row['fotografia'])>0 and file_exists(carpetaClientes.$row['idCliente'].'_'.$row['fotografia']))
				{
					$row['fotografia'] 		= base_url().carpetaClientes.$row['idCliente'].'_'.$row['fotografia'];
					
					$registros[$i]=$row;
				}
				
				$i++;
			}
		}
		
		return $registros;
	}
}
