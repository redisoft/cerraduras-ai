<?php
class soporte_modelo extends CI_Model
{
	protected $idUsuario;
    protected $fechaCorta;
	protected $fecha;
	protected $hora;
	protected $usuario;
	
	function __construct()
	{
		parent::__construct();
		
		$this->fecha 			= date('Y-m-d H:i:s');
		$this->fechaCorta		= date('Y-m-d');
		$this->hora 			= date('H:i:s');
		$this->idUsuario 		= $this->session->userdata('id');
		$this->usuario	 		= $this->session->userdata('nombreUsuarioSesion');
		
	}

	public function obtenerProductos()
	{
		$sql=" select idProducto, stock, servicio
		from productos
		
		order by idProducto asc ";
		
		#where idProducto>=17357
		
		/*
		where servicio='0'
		order by idProducto asc*/
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerLinea($nombre)
	{
		$sql=" select idLinea
		from productos_lineas
		where nombre='$nombre' ";
		
		return $this->db->query($sql)->row()->idLinea;
	}
	
	public function procesarProductos()
	{
		$productos	= $this->obtenerProductos();
		
		foreach($productos as $row)
		{
			$data=array
			(
				'idLinea'				=> $this->obtenerLinea($row->familia),
			);
			
			$this->db->where('idProducto',$row->idProducto);
			$this->db->update('productos',$data);
		}
	}
	
	
	
	public function obtenerClientes($contactos=1)
	{
		$sql=" select a.idCliente, a.empresa, a.razonSocial, a.nombre, a.paterno, a.materno, a.email, a.telefono 
		from clientes as a
		where a.idCliente>1 ";
		
		$sql.=$contactos==1?" and (select count(b.idContacto) from clientes_contactos as b where b.idCliente=a.idCliente) = 0 ":"";
		
		$sql.=" order by a.idCliente asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function procesarClientes()
	{
		$clientes	= $this->obtenerClientes();
		
		foreach($clientes as $row)
		{
			$cliente	= $row->empresa;
			
			if(strlen($row->nombre)>0)
			{
				$cliente	= $row->nombre.' '.$row->paterno.' '.$row->materno;
			}
			
			$data=array
			(
				'idCliente'				=> $row->idCliente,
				'nombre'				=> $cliente,
				'telefono'				=> $row->telefono,
				'email'					=> $row->email,
				'direccion'				=> '',
				'fechaRegistro'			=> $this->fecha,
			);
			
			$this->db->insert('clientes_contactos',$data);
		}
		
		//REGISTRAR LOS DATOS ACADEMICOS
		$clientes	= $this->obtenerClientes(0);
		
		foreach($clientes as $row)
		{
			$data=array
			(
				'idCliente'				=> $row->idCliente,
			);
			
			$this->db->insert('clientes_academicos',$data);
		}
		
	}
	
	//CREADOR DE PERMISOS
	public function obtenerRoles()
	{
		$sql=" select * from roles ";
		
		return $this->db->query($sql)->result();
	}
	
	public function creadorPermisos($idPermiso)
	{
		$roles	= $this->obtenerRoles();
		
		foreach($roles as $row)
		{
			$data=array
			(
				'leer' 		=> $row->idRol==1?'1':'0',
				'escribir' 	=> $row->idRol==1?'1':'0',
				'idPermiso' => $idPermiso,
				'idRol' 	=> $row->idRol
			);
			
			$this->db->insert('rel_rol_permiso',$data);
		}
	}
	
	//PROCESAR LOS MATERIALES
	public function obtenerMateriales()
	{
		$sql=" select * from produccion_materiales
		order by idMaterial asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerUnidad($descripcion)
	{
		$sql=" select idUnidad
		from unidades
		where descripcion='$descripcion' ";
		
		return $this->db->query($sql)->row()->idUnidad;
	}
	
	public function obtenerProveedor($proveedor)
	{
		$sql=" select idProveedor
		from proveedores
		where proveedor='$proveedor' ";
		
		$proveedor	= $this->db->query($sql)->row();
		
		return $proveedor!=null?$proveedor->idProveedor:1;
	}
	
	public function procesarMateriaPrima()
	{
		$materiales	= $this->obtenerMateriales();
		
		foreach($materiales as $row)
		{
			if($row->proveedor=='S/P' or $row->proveedor=='' or $row->proveedor==' ')
			{
				$idProveedor=1;
			}
			else
			{
				$idProveedor	= $this->obtenerProveedor($row->proveedor);	
			}
			
			$this->db->where('idMaterial',$row->idMaterial);
			$this->db->update('produccion_materiales',array('idUnidad'=>$this->obtenerUnidad($row->unidad)));
			
			$data=array
			(
				'idMaterial'			=> $row->idMaterial,
				'idProveedor'			=> $idProveedor,
				'costo'					=> $row->costo,
			);
			
			$this->db->insert('rel_material_proveedor',$data);
			
		}
	}
	
	
	//PROVEEDORES
	
	public function obtenerProveedores()
	{
		$sql=" select idProveedor, empresa, telefono, email, contacto
		from proveedores
		where idProveedor>1 ";
		
		return $this->db->query($sql)->result();
	}
	
	public function procesarProveedores()
	{
		$proveedores	= $this->obtenerProveedores();
		
		foreach($proveedores as $row)
		{
			$data=array
			(
				'idProveedor'			=> $row->idProveedor,
				'nombre'				=> strlen($row->contacto)>1?$row->contacto:$row->empresa,
				'telefono'				=> $row->telefono,
				'email'					=> $row->email,
			);
			
			$this->db->insert('contactos_proveedores',$data);
		}
	}
	
	
	
	
	
	public function registrarStockLicencias()
	{
		#$licencias	= $this->configuracion->obtenerLicencias();
		$productos	= $this->obtenerProductos();
		
		foreach($productos as $row)
		{
			$this->inventario->registrarStockLicencias($row->idProducto,$row->stock);
		}
		
		/*foreach($licencias as $row)
		{
			$data=array
			(
				'idProducto'			=> $idProducto,
				'idLicencia'			=> $row->idLicencia,
				'stock'					=> $row->idLicencia==$this->idLicencia?$stock:0,
			);
	
			$this->db->insert('productos_inventarios', $data);
		}*/
	}
	
	public function obtenerProductosCordobita()
	{
		$sql=" select a.idProducto, a.stock, a.servicio, a.precioA, a.costo
		from productos as a
		where a.idProducto>=20515
		 ";
		#
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarStockLicencia()
	{
		$productos	= $this->obtenerProductosCordobita();
		
		foreach($productos as $row)
		{
			$data=array
			(
				'idProducto'			=> $row->idProducto,
				'idLicencia'			=> 8,
				'stock'					=> $row->stock,
				'precioA'				=> $row->precioA,
			);
	
			$this->db->insert('productos_inventarios', $data);
			
			//RELACIÓN CON EL PROVEEDOR
			$data=array
			(
				'idProducto'			=> $row->idProducto,
				'idProveedor'			=> 1,
				'precio'				=> $row->costo,
			);
	
			$this->db->insert('rel_producto_proveedor', $data);
			
			/*if(strlen($row->idClave)>0)
			{
				$this->db->where('idProducto',$row->idProducto);
				$this->db->update('productos',array('idClave'=>$row->idClave));
			}
			
			$this->db->where('idProducto',$row->idProducto);
			$this->db->update('productos',array('descripcion'=>''));*/
			
		}
	}
	
	
	//PROCESAR DATOS DE LA PIÑATA
	//PROVEEDORES
	
	public function obtenerClaveUnidad($clave)
	{
		$sql=" select idUnidad
		from fac_catalogos_unidades
		where clave='$clave' ";
		
		$unidad	= $this->db->query($sql)->row();
		
		return $unidad!=null?$unidad->idUnidad:1070;
	}
	
	public function obtenerClaveProducto($clave)
	{
		$sql=" select idClave
		from fac_catalogos_claves_productos
		where clave='$clave' ";
		
		$producto	= $this->db->query($sql)->row();
		
		return $producto!=null?$producto->idClave:1;
	}
	
	public function obtenerProductosPinata()
	{
		$sql=" select * from soporte ";
		
		return $this->db->query($sql)->result();
	}
	
	public function procesarProductosPinata()
	{
		$productos	= $this->obtenerProductosPinata();
		
		foreach($productos as $row)
		{
			$data=array
			(
				'idClave'					=> $this->obtenerClaveProducto($row->clave),
			);
			
			$this->db->where('codigoBarras',$row->codigoBarras);
			$this->db->update('productos',$data);
		}
	}
	
	
	//PROCESAR LOS DATOS ACADEMICOS DE LOS ALUMNOS
	
	public function obtenerPrograma($nombre)
	{
		if(strlen($nombre)<2)
		{
			return 0;
		}
		
		$sql=" select idPrograma
		from clientes_programas
		where nombre='$nombre' ";
		
		$programa=$this->db->query($sql)->row();

		if($programa!=null)
		{
			return $programa->idPrograma;
		}
		else
		{
			$data=array
			(
				'nombre'				=> $nombre,
			);
			
			$this->db->insert('clientes_programas',$data);
			return $this->db->insert_id();
		}
		
	}
	
	
	public function obtenerClientesAlumnos()
	{
		$sql=" select *
		from clientes_alumnos
		order by nombre asc, apellido asc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerClienteAlumno($nombre,$apellido)
	{
		$sql=" select a.idCliente
		from clientes as a
		inner join clientes_academicos as b
		on a.idCliente=b.idCliente
		where a.nombre='$nombre'
		and concat(a.paterno,' ',a.materno)='$apellido'
		and length(b.matricula)=0 ";

		return $this->db->query($sql)->result();
	}
	
	public function procesarClientesAlumnos()
	{
		$clientes	= $this->obtenerClientesAlumnos();
		
		foreach($clientes as $row)
		{
			$cliente	= $this->obtenerClienteAlumno($row->nombre,$row->apellido);
			
			foreach($cliente as $cli)
			{
				$data=array
				(
					'matricula'				=> $row->matricula,
				);
				
				$this->db->where('idCliente',$cli->idCliente);
				$this->db->update('clientes_academicos',$data);
			}
		}
	}
	
	//CREADOR DE BOTONES DE  PERMISOS
	public function creadorPermisosBotones($idBoton)
	{
		$roles	= $this->obtenerRoles();
		
		foreach($roles as $row)
		{
			$data=array
			(
				'idBoton' 	=> $idBoton,
				'idRol' 	=> $row->idRol,
				'activo' 	=> $row->idRol==1?'1':'0'
			);
			
			$this->db->insert('rel_rol_boton',$data);
		}
	}
	
	public function creadorPermisosBotonesRango($boton1,$boton2)
	{
		$roles	= $this->obtenerRoles();
		
		for($i=$boton1;$i<=$boton2;$i++)
		{
			foreach($roles as $row)
			{
				$data=array
				(
					'idBoton' 	=> $i,
					'idRol' 	=> $row->idRol,
					'activo' 	=> $row->idRol==1?'1':'0'
				);
				
				$this->db->insert('rel_rol_boton',$data);
			}
		}
	}
	
	public function obtenerClienteMatricula($matricula)
	{
		$sql=" select idCliente
		from clientes_academicos
		where matricula='$matricula' ";

		$alumno	= $this->db->query($sql)->row();
		
		return $alumno!=null?$alumno->idCliente:0;
	}
	
	public function obtenerClientesMatricula()
	{
		$sql=" select *
		from clientes_alumnos ";

		return $this->db->query($sql)->result();
	}
	
	public function procesarClientesZona()
	{
		$clientes	= $this->obtenerClientesMatricula();
		
		foreach($clientes as $row)
		{
			$cliente	= $this->obtenerClienteMatricula($row->matricula);
			
			$this->db->where('idCliente',$cliente);
			$this->db->update('clientes',array('idZona'=>1));
		}
	}
	
	
	////PROCESAR LOS SEGUIMIENTOS REPETIDOS
	public function obtenerSeguimientosBorrar()
	{
		$sql=" select a.idSeguimiento 
		FROM seguimiento AS a 
		WHERE a.idCliente>1
		AND (SELECT COUNT(b.idCliente) FROM clientes AS b WHERE b.idCliente=a.idCliente) =0 ";

		return $this->db->query($sql)->result();
	}
	
	public function procesarSeguimientosBorrar()
	{
		$seguimiento	= $this->obtenerSeguimientosBorrar();
		
		foreach($seguimiento as $row)
		{	
			$this->db->where('idSeguimiento',$row->idSeguimiento);
			$this->db->delete('seguimiento_detalles');
			
			$this->db->where('idSeguimiento',$row->idSeguimiento);
			$this->db->delete('seguimiento');
		}
	}
	
	//QUITAR PROGRAMAS DUPLICADOS
	public function obtenerProgramasNombre($nombre,$idPrograma)
	{
		$sql=" select idPrograma 
		from clientes_programas 
		where activo='1'
		and nombre='$nombre'
		and idPrograma!='$idPrograma' ";

		return $this->db->query($sql)->result();
	}
	
	public function procesarProgramasAlumnos()
	{
		$sql=" select * from clientes_programas where activo='1' group by nombre";
		
		$programas	=$this->db->query($sql)->result();
		
		foreach($programas as $row)
		{
			$detalles	= $this->obtenerProgramasNombre($row->nombre,$row->idPrograma);
			
			foreach($detalles as $det)
			{
				$this->db->where('idPrograma',$det->idPrograma);
				$this->db->update('clientes_academicos',array('idPrograma'=>$row->idPrograma));
				
				$this->db->where('idPrograma',$det->idPrograma);
				$this->db->update('clientes_programas',array('activo'=>'0'));
			}
		}
	}
	
	public function procesarAlumnosInscritos1()
	{
		$sql=" select a.idCliente,
		(select b.fecha from catalogos_ingresos as b where b.idCliente=a.idCliente order by fecha desc  limit 1) as fecha
		from clientes as a
		where a.prospecto='0'
		and a.activo='1' ";
		
		$clientes	=$this->db->query($sql)->result();
		
		foreach($clientes as $row)
		{
			$this->db->where('idPrograma',$det->idPrograma);
			$this->db->update('clientes_academicos',array('idPrograma'=>$row->idPrograma));
		}
	}
	
	public function obtenerAlumnosInscritos($cliente)
	{
		$sql=" select a.idCliente,
		a.idCampana,
		(select count(b.idIngreso) from catalogos_ingresos as b where b.idCliente=a.idCliente) as numeroPagos,
		(select count(b.idDetalle) from seguimiento_detalles as b inner join seguimiento as c on c.idSeguimiento=b.idSeguimiento where c.idCliente=a.idCliente) as numeroSeguimientos,
		
		(select b.idPrograma from clientes_academicos  as b where b.idCliente=a.idCliente limit 1) as idPrograma
		
		from clientes as a
		where a.activo='1'
		
		and (trim(a.empresa)='$cliente->nombre'
		or trim(a.nombre)='$cliente->nombre'
		or concat(trim(a.nombre),' ',trim(a.paterno),' ',trim(a.materno))='$cliente->nombre'  ";	
		
		$sql.=strlen($cliente->email)>2?" or a.email='$cliente->email' ":'';
		
		#and a.prospecto='0'
		
		$sql.=" )
		order by  numeroSeguimientos desc,
		numeroPagos desc,
		a.email desc, 
		a.idCampana desc ";	
		
		return $this->db->query($sql)->result();
	}
	
	public function procesarAlumnosInscritos()
	{
		$sql=" select * from clientes_inscritos ";
		
		$inscritos	=$this->db->query($sql)->result();
		
		foreach($inscritos as $ins)
		{
			$clientes	= $this->obtenerAlumnosInscritos($ins);
			
			if(count($clientes)>1)
			{
				$idCliente	= $clientes[0]->idCliente;
				$idPrograma	= $clientes[0]->idPrograma;
				$idCampana	= $clientes[0]->idCampana;
				
				$i=0;
				
				foreach($clientes as $row)
				{
					if($i>0)
					{
						$data	= array('idCliente'=>$idCliente);
						
						$this->db->where('idCliente',$row->idCliente);
						$this->db->update('seguimiento',$data);
						
						$this->db->where('idCliente',$row->idCliente);
						$this->db->update('catalogos_ingresos',$data);
						
						$this->db->where('idCliente',$row->idCliente);
						$this->db->update('clientes',array('activo'=>'0'));
						
						$this->db->where('idCliente',$idCliente);
						$this->db->update('clientes',array('prospecto'=>'0'));
						
						if($idPrograma<1)
						{
							$this->db->where('idCliente',$idCliente);
							$this->db->update('clientes_academicos',array('idPrograma'=>$row->idPrograma));
						}
						
						if($idCampana<1)
						{
							$this->db->where('idCliente',$idCliente);
							$this->db->update('clientes',array('idCampana'=>$row->idCampana));
						}
					}
					
					$i++;
				}
			}
		}
	}
	
	public function procesarCorreos()
	{
		$sql=" select * from clientes_correos ";
		
		$clientes	=$this->db->query($sql)->result();
		
		foreach($clientes as $row)
		{
			$this->db->where('email',$row->email);
			$this->db->update('clientes',array('idCampana'=>6));
		}
	}
	
	
	public function procesarSeguimientosAtrasos()
	{
		$sql.="
		select distinct a.idCliente, a.idPromotor, concat(a.nombre, ' ', a.paterno,' ', a.materno) as prospecto,
		a.lada, a.telefono, a.ladaMovil, a.movil, a.email,
		
		f.nombre as campana, 
		
		(select g.idContacto from clientes_contactos as g where g.idCliente=a.idCliente limit 1 ) as idContacto,
		
		concat(b.nombre, ' ', b.apellidoPaterno,' ', b.apellidoMaterno) as promotor,
		timestampdiff(minute,a.fechaRegistro ,now()) / 60 as horas,
		a.fechaRegistro as fechaSeguimiento
		from clientes as a
		inner join usuarios as b
		on a.idPromotor=b.idUsuario
		inner join clientes_campanas as f
		on a.idCampana=f.idCampana 
		
		
		where b.promotor='1'
		and a.prospecto='1'
		and a.activo='1'
		and a.idZona!=2
		and timestampdiff(minute,a.fechaRegistro ,now()) / 60 >= 24
		
		and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=0  ";

		//AGREGAR LA CAMPAÑA
		$sql.=" and f.fechaFinal>curdate() ";
		
		$sql.=" group by a.idCliente 
		order by fechaSeguimiento desc ";
		
		$prospectos	=$this->db->query($sql)->result();
		
		foreach($prospectos as $row)
		{
			
			$seguimiento['fecha']				= $this->fecha;
			$seguimiento['comentarios']			= 'S/C';
			$seguimiento['fechaCierre']			= $this->fecha;
			$seguimiento['horaCierreFin']		= '13:00';
			$seguimiento['idStatus']			= 4;
			$seguimiento['idEstatus']			= 4;
			$seguimiento['tipo']				= 1;
			$seguimiento['idServicio']			= 1;
			$seguimiento['folio']				= $this->crm->obtenerFolioSeguimientoCliente(1);
			
			$seguimiento['idResponsable']		= $row->idPromotor;
			$seguimiento['idUsuarioRegistro']	= $row->idPromotor;
			$seguimiento['idCliente']			= $row->idCliente;
			$seguimiento['idContacto']			= $row->idContacto;

			$detalle['fecha']					= $this->fecha;
			$detalle['hora']					= '13:00';
			$detalle['fechaRegistro']			= $this->fecha;
			$detalle['observaciones']			= 'S/C';
			$detalle['fechaSeguimiento']		= $this->fecha;
			$detalle['horaInicial']				= '13:00';;
			$detalle['horaFinal']				= '13:00';;
			$detalle['alerta']					= '1';
			
			$this->importar->registrarSeguimientoInicial($seguimiento,$detalle);
		}
	}	
	
	public function procesarNuevos()
	{
		$sql=" SELECT DISTINCT a.idCliente
		FROM clientes AS a
		INNER JOIN usuarios AS b
		ON a.idPromotor=b.idUsuario
		INNER JOIN clientes_campanas AS f
		ON a.idCampana=f.idCampana
		WHERE b.promotor='1'
		AND a.prospecto='1'
		AND a.activo='1'
		AND a.idZona!=2
		AND (SELECT COUNT(g.idDetalle) FROM seguimiento_detalles AS g INNER JOIN seguimiento AS h ON g.idSeguimiento=h.idSeguimiento WHERE h.idCliente=a.idCliente )=0
		AND f.fechaFinal>CURDATE() 
		GROUP BY a.idCliente ";
		
		$prospectos	=$this->db->query($sql)->result();
		
		foreach($prospectos as $row)
		{
			$this->db->where('idCliente',$row->idCliente);
			$this->db->update('clientes',array('nuevoRegistro'=>'1'));
		}
	}
	
	public function obtenerDetalleSeguimiento($idSeguimiento)
	{
		$sql=" select * from seguimiento_detalles_
		where idSeguimiento='$idSeguimiento' ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function procesarSeguimientosClientes()
	{
		$sql=" select * from seguimiento_ ";
		
		$seguimiento	= $this->db->query($sql)->result_array();
		
		foreach($seguimiento as $row)
		{
			$detalles	= $this->obtenerDetalleSeguimiento($row['idSeguimiento']);
			
			$row['idSeguimiento']	= 0;
			
			$this->db->insert('seguimiento',$row);
			$idSeguimiento	= $this->db->insert_id();
			
			foreach($detalles as $seg)
			{
				$seg['idSeguimiento']	= $idSeguimiento;
				$seg['idDetalle']		= 0;
				
				$this->db->insert('seguimiento_detalles',$seg);
			}
		}
	}
	
	public function obtenerSeguimientoCliente($idCliente)
	{
		$sql=" select idSeguimiento from seguimiento
		where idCliente='$idCliente' ";
		
		return $this->db->query($sql)->result();
	}
	
	
	public function comprobarProductoCodigo($codigoInterno)
	{
		$this->db->select('idProducto');
		$this->db->from('productos');
		$this->db->where('codigoInterno',$codigoInterno);
		
		return $this->db->get()->row();
	}
	
	public function importarProductosCerraduras()
	{
		$sql=" select * from productos_importar";
		
		$productos		= $this->db->query($sql)->result();
		
		
		foreach($productos as $row)
		{
			$inventario	= $this->comprobarProductoCodigo($row->clave);
			
			$data=array
			(
				'codigoBarras' 		=> $row->codigo,
				'nombre' 			=> $row->nombre,
				'precioC' 			=> $row->precioA,
				'precioA' 			=> $row->precioB,
				'precioB' 			=> $row->precioC,
				'cantidadMayoreo' 	=> $row->mayoreo,
				'idClave' 			=> $this->importar->obtenerClaveProducto($row->claveProducto),
				'idUnidad' 			=> $this->importar->obtenerClaveUnidadImportar($row->unidad)
			);
			
			if($inventario!=null)
			{
				if(strlen($row->clave)>0)
				{
					$this->db->where('idProducto',$inventario->idProducto);
					$this->db->update('productos',$data);
					
					$this->db->where('idProducto',$inventario->idProducto);
					$this->db->update('rel_producto_proveedor',array('precio'=>$row->costo));
				}
			}
			else
			{
				$data['codigoInterno']		= $row->clave;
				$data['fecha']				= $this->fecha;
				$data['idUsuario']			= $this->idUsuario;
				$data['reventa']			= 1;
				$data['descripcion']		= '';
				
				$this->db->insert('productos',$data);
				$idProducto	= $this->db->insert_id();
				
				$this->inventario->registrarStockLicencias($idProducto,0);
				
				
				$proveedor=array
				(
					'idProducto' 		=> $idProducto,
					'idProveedor' 		=> 1,
					'precio' 			=> $row->costo,
				);
				
				$this->db->insert('rel_producto_proveedor',$proveedor);
			}
		}
	}
	
	
	
	public function procesarDireccionesFiscales()
	{
		$sql=" select * from clientes ";
		
		$clientes	=$this->db->query($sql)->result();
		
		foreach($clientes as $row)
		{
			$data=array
		(
			'rfc'				=> $row->rfc,
			'razonSocial'		=> $row->razonSocial,
			'calle'				=> $row->calle,
			'numero'			=> $row->numero,
			'colonia'			=> $row->colonia,
			'localidad'			=> $row->localidad,		
			'municipio'			=> $row->municipio,
			'estado'			=> $row->estado,
			'pais'				=> $row->pais,
			'codigoPostal'		=> $row->codigoPostal,
			'telefono'			=> $row->telefono,
			'email'				=> $row->email,
			'tipo'				=> 2,
			'idCliente'			=> $row->idCliente,
		);
		
		$this->db->insert('clientes_direcciones', $data);
		}
	}
	
	
	public function procesarCotizacionesEstaciones()
	{
		$sql=" select idEstacion
		from configuracion_estaciones
		where activo='1' ";
		
		$estaciones	=$this->db->query($sql)->result();
		
		foreach($estaciones as $est)
		{
			$i=1;
		
			$sql=" select a.idCotizacion
			from cotizaciones as a 
			where a.activo='1' 
			and a.idFactura=0
			and a.estatus=1
			and a.idEstacion='$est->idEstacion'
			and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') = 0 
			and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') = 0
			order by idCotizacion asc ";

			$registros	=$this->db->query($sql)->result();

			foreach($registros as $row)
			{
				$this->db->where('idCotizacion',$row->idCotizacion);
				$this->db->update('cotizaciones',array('folio'=>$i,'ordenCompra'=>'VEN-'.$i));
				
				$i++;
			}
			
			$i=1;
		
			$sql=" select a.idCotizacion
			from cotizaciones as a 
			where a.activo='1' 
			and a.estatus=1
			and a.idEstacion='$est->idEstacion'
			
			and(a.idFactura>0 or (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') > 0)
			order by idCotizacion asc ";

			$registros	=$this->db->query($sql)->result();

			foreach($registros as $row)
			{
				$this->db->where('idCotizacion',$row->idCotizacion);
				$this->db->update('cotizaciones',array('folio'=>$i,'ordenCompra'=>'VEN-'.$i,'prefactura'=>'1'));
				
				$i++;
			}
		}
	}
	
	//OBTENER LAS COMPRAS POR SUCURSAL Y POR PRODUCTO
	public function obtenerComprasProducto($idMaterial,$idLicencia)
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
		and c.idLicencia='$idLicencia' ";
		
		return $this->db->query($sql)->row()->cantidad;
	}
	
	public function obtenerVentasProducto($idProducto,$idLicencia)
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
		and c.idLicencia='$idLicencia' ";
	
		return $this->db->query($sql)->row()->cantidad;
	}
	
	//ENVIOS Y RECEPCIONES DE PRODUCTOS
	public function obtenerEnviosProductoInventario($idProducto,$idLicencia)
	{
		$sql=" select coalesce(sum(a.cantidad),0) as cantidad
		from productos_traspasos_detalles as a
		inner join productos_traspasos as b
		on a.idTraspaso=b.idTraspaso
		where b.idLicenciaOrigen='$idLicencia'
		and  a.idProducto='$idProducto'
		and b.activo='1' ";

		return $this->db->query($sql)->row()->cantidad;
	}
	
	public function obtenerRecepcionesProductoInventario($idProducto,$idLicencia)
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
		and a.idLicencia='$idLicencia'
		and d.activo='1' ";
		

		return $this->db->query($sql)->row()->cantidad;
	}
	
	public function procesarStockSucursal()
	{
		$idLicencia=5;
		
		$sql="select idProducto
		from productos where activo='1' ";
		
		$productos=$this->db->query($sql)->result();
		
		foreach($productos as $row)
		{
			$compras		= $this->obtenerComprasProducto($row->idProducto,$idLicencia);
			$ventas			= $this->obtenerVentasProducto($row->idProducto,$idLicencia);
			$envios			= $this->obtenerEnviosProductoInventario($row->idProducto,$idLicencia);
			$recepciones	= $this->obtenerRecepcionesProductoInventario($row->idProducto,$idLicencia);
			
			$stock			= $compras+$recepciones-$envios-$ventas;
			
			$this->db->where('idProducto',$row->idProducto);
			$this->db->where('idLicencia',$idLicencia);
			$this->db->update('productos_inventarios',array('stock'=>$stock));
		}

	}
	
	public function registrarInventarioDiario()
	{
		$this->db->trans_start(); 
		
		$data=array
		(
			'fecha'			=> $this->fecha
		);

		$this->db->insert('productos_inventarios_registro', $data);
		$idRegistro=$this->db->insert_id();

		$sql=" select a.idProducto, b.stock, b.idLicencia
		from productos as a
		inner join productos_inventarios as b
		on a.idProducto=b.idProducto
		where a.activo='1' ";
		
		$productos=$this->db->query($sql)->result();
		
		foreach($productos as $row)
		{
			$data=array
			(
				'idProducto'			=> $row->idProducto,
				'idLicencia'			=> $row->idLicencia,
				'stock'					=> $row->stock,
				'idRegistro'			=> $idRegistro,
			);
	
			$this->db->insert('productos_inventarios_registro_detalles', $data);
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
		}
	}
	
	
	public function registrarPreciosProductos()
	{
		$this->db->trans_start(); 
		

		$sql=" select a.idProducto, a.precioA, a.precioB, a.precioC, a.precioD, a.precioE
		from productos as a
		where a.activo='1' ";
		
		$productos	= $this->db->query($sql)->result();
		
		foreach($productos as $row)
		{
			for($i=1;$i<=7;$i++)
			{
				$data=array
				(
					'precioA'			=> $row->precioA,
					'precioB'			=> $row->precioB,
					'precioC'			=> $row->precioC,
					'precioD'			=> $row->precioD,
					'precioE'			=> $row->precioE,
				);

				$this->db->where('idProducto', $row->idProducto);
				$this->db->where('idLicencia', $i);
				$this->db->update('productos_inventarios', $data);
			}
			
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
		}
	}
	
	public function borrarProductosCordobita()
	{
		$this->db->trans_start(); 

		$sql=" select a.*, b.idInventario
		from productos as a
		inner join productos_inventarios as b
		on a.idProducto=b.idProducto
		where b.idLicencia=8
		and (select count(c.idProducto) from productos_inventarios as c where c.idProducto=a.idProducto and c.idLicencia=1)=0
		and a.activo='1' ";
		
		$productos	= $this->db->query($sql)->result();
		
		foreach($productos as $row)
		{
			$this->db->where('idProducto', $row->idProducto);
			$this->db->update('productos', array('activo'=>'0'));
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
		}
	}
	
	//ENVIAR TODOS LOS PRODUCTOS A COTIZACIÓN
	
	public function obtenerProductosEnvio()
	{
		$sql=" select (a.cantidad - (select coalesce(sum(c.cantidad),0) from  ventas_entrega_detalles as c where c.idProducto=a.idProducto)) as cantidad, a.idProducto, a.idProduct, a.servicio,
		b.idTienda, b.idCotizacion, a.nombre as producto, b.ordenCompra, b.idLicencia

		from cotiza_productos as a
		inner join cotizaciones as b
		on a.idCotizacion=b.idCotizacion
		where b.cancelada='0'
		and b.estatus='1'
		and b.activo='1'
		and (select coalesce(sum(c.cantidad),0) from  ventas_entrega_detalles as c where c.idProducto=a.idProducto) < a.cantidad ";
		
		return $this->db->query($sql)->result();
	}
	
	public function entregarProductosPendientes()
	{
		$this->load->model("inventarioproductos_modelo","inventarioProductos");
		
		$productos	= $this->obtenerProductosEnvio();
		
		foreach($productos as $row)
		{
			$data=array
			(
				"fecha"			=> $this->fecha,
				"cantidad"		=> $row->cantidad, 
				"entrego"		=> $this->usuario,
				"idProducto"	=> $row->idProducto
			);

			$this->db->insert("ventas_entrega_detalles",$data);


			#$producto	= $this->inventarioProductos->obtenerProductoStockLicencia($row->idProduct,$row->idLicencia);

			$data=array
			(
				"enviado"	=>1,
			);

			$this->db->where('idProducto',$row->idProducto);
			$this->db->update('cotiza_productos',$data);

			if($row->servicio==0)
			{
				$this->inventarioProductos->actualizarStockProductoSucursal($row->idProduct,$row->cantidad,'restar',$row->idLicencia);

				#$this->configuracion->registrarBitacora('Entregar producto','Ventas',$row->producto.', Orden: '.$row->ordenCompra.', Cantidad: '.number_format($row->cantidad,decimales)); //Registrar bitácora
			}
		}
	}
	
	public function copiarProductosSucursal()
	{
		$sql=" select distinct(a.idProducto), a.precioA, a.precioB, a.precioC, a.precioD, a.precioE, a.stock
		from productos_inventarios as a
		inner join productos as b
		on a.idProducto=b.idProducto
		where a.idLicencia=2
		and b.activo='1' ";
		
		#and (select count(c.idProducto) from productos_inventarios as c where c.idProducto=a.idProducto and c.idLicencia=8) =0
		
		$productos	= $this->db->query($sql)->result();
		
		foreach($productos as $row)
		{
			$data=array
			(
				"idProducto"	=> $row->idProducto,
				"precioA"		=> $row->precioA, 
				"precioB"		=> $row->precioB,
				"precioC"		=> $row->precioC,
				"precioD"		=> $row->precioD,
				"precioE"		=> $row->precioE,
				"idLicencia"	=> 12,
				#"stock"			=> $row->stock,
			);

			$this->db->insert("productos_inventarios",$data);

		}
	}

	public function borrarDuplicados()
	{
		$sql=" select a.* from facturas as a where a.folio=0 and documento='PREFACTURA' 
		and date(fecha)>='2022-12-12' and exists(select b.idFactura from facturas as b where b.folio>0 and a.idCotizacion=b.idCotizacion and documento='FACTURA') ";
		
		
		$registros	= $this->db->query($sql)->result();
		$i=0;
		foreach($registros as $row)
		{
			$this->db->where("idFactura",$row->idFactura);
			$this->db->delete("facturas");

			$this->db->where("idFactura",$row->idFactura);
			$this->db->delete("facturas_detalles");

			$i++;

		}

		echo 'Registros borrados: '.$i;
	}

	public function revertirInventario()
	{
		$sql=" select idProducto, idLicencia, cantidad
		from productos_inventarios_movimientos
		where fecha='2023-05-01 00:57:00'
		and movimiento='Salida' ";
		
		$registros	= $this->db->query($sql)->result();

		foreach($registros as $row)
		{
			$this->db->where('idProducto',$row->idProducto);
			$this->db->where('idLicencia',$row->idLicencia);
			$this->db->update('productos_inventarios',array('stock'=>$row->cantidad));

			$this->db->where("idMovimiento",$row->idMovimiento);
			$this->db->delete("productos_inventarios_movimientos");
		}
	}
}
