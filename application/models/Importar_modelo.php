<?php
class Importar_modelo extends CI_Model
{
	protected $fecha;
	protected $idLicencia;
	protected $resultado;
	protected $idUsuario;
	protected $fechaCorta;

	function __construct()
	{
		parent::__construct();

        $this->idUsuario 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->fecha 			= date('Y-m-d H:i:s');
		$this->fechaCorta 		= date('Y-m-d');
		$this->resultado		="1";
	}
	
	//CLIENTES
	public function comprobarCliente($cliente)
	{
		$sql="select idCliente
		from clientes
		where empresa='".$cliente['empresa']."'
		and rfc='".$cliente['rfc']."' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function registrarCliente($cliente,$contacto,$academicos=null)
	{
		if($this->comprobarCliente($cliente)>0) return "0";
		
		//REGISTRAR CLIENTE
		$this->db->insert('clientes', $cliente);
		$idCliente = $this->db->insert_id();
		
		//CONTACTO DEL CLIENTE
		$contacto['idCliente']	= $idCliente;
		$this->db->insert('clientes_contactos', $contacto); 
		
		if($academicos!=null)
		{
			//ACADEMICOS
			$academicos['idCliente']	= $idCliente;
			$this->db->insert('clientes_academicos', $academicos); 
		}
	}
	
	public function registrarProspecto($cliente,$contacto,$academicos=null)
	{
		#if($this->comprobarCliente($cliente)>0) return "0";
		
		$cliente['nuevoRegistro']		= '1';
		$cliente['idCampanaOriginal']	= $cliente['idCampana'];
		
		//REGISTRAR CLIENTE
		$cliente=procesarArreglo($cliente);
		$this->db->insert('clientes', $cliente);
		$idCliente = $this->db->insert_id();
		
		//CONTACTO DEL CLIENTE
		$contacto['idCliente']	= $idCliente;
		$this->db->insert('clientes_contactos', $contacto); 
		
		$idContacto = $this->db->insert_id();
		
		if($academicos!=null)
		{
			//ACADEMICOS
			$academicos['idCliente']	= $idCliente;
			$this->db->insert('clientes_academicos', $academicos); 
		}
		
		return array($idCliente,$idContacto);
	}
	
	public function comprobarSeguimientoProspecto($idCliente)
	{
		$sql="select count(idSeguimiento) as numero
		from seguimiento 
		where idCliente='$idCliente'";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function registrarSeguimientoInicial($seguimiento,$detalle)
	{
		if($this->comprobarSeguimientoProspecto($seguimiento['idCliente'])>0) return false;
		
			//REGISTRAR CLIENTE
		$this->db->insert('seguimiento', $seguimiento);
		$idSeguimiento = $this->db->insert_id();
		
		//CONTACTO DEL CLIENTE
		$detalle['idSeguimiento']	= $idSeguimiento;
		$this->db->insert('seguimiento_detalles', $detalle); 
	}
	
	public function registrarSeguimientoInicialCrm($seguimiento)
	{
		$this->db->insert('seguimiento', $seguimiento);
		
		return $this->db->insert_id();
	}
	
	public function registrarDetalleSeguimiento($detalle)
	{
		$this->db->insert('seguimiento_detalles', $detalle); 
	}
	
	public function comprobarSeguimientoProspectoRegistro($idCliente)
	{
		$sql=" select idSeguimiento
		from seguimiento 
		where idCliente='$idCliente'
		and tipo='1'
		order by idSeguimiento desc limit 1 ";
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarSeguimientoInicialRegistro($seguimiento,$detalle)
	{
		$registro	= $this->comprobarSeguimientoProspectoRegistro($seguimiento['idCliente']);
		
		if($registro==null)
		{
			//REGISTRAR CLIENTE
			$this->db->insert('seguimiento', $seguimiento);
			$idSeguimiento = $this->db->insert_id();
		}
		else
		{
			$idSeguimiento	= $registro->idSeguimiento;
		}
		
		
		//CONTACTO DEL CLIENTE
		$detalle['idSeguimiento']	= $idSeguimiento;
		$this->db->insert('seguimiento_detalles', $detalle); 
	}
	
	public function exportarClientes($registro='clientes')
	{
		$sql=" select a.*
		from clientes as a
		where a.activo='1' ";
		
		$sql.=$registro=='prospectos'?" and prospecto='1' ":'';
		
		$sql.=" order by empresa asc ";
		
		return $this->db->query($sql)->result();
	}
	
	//PROVEEDORES
	public function comprobarProveedor($proveedor)
	{
		$sql="select idProveedor
		from proveedores
		where empresa='".$proveedor['empresa']."'
		and rfc='".$proveedor['rfc']."' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function registrarProveedor($proveedor,$contacto)
	{
		if($this->comprobarProveedor($proveedor)>0) return "0";
		
		//REGISTRAR PROVEEDOR
		$this->db->insert('proveedores', $proveedor);
		$idProveedor = $this->db->insert_id();
		
		//CONTACTO DEL PROVEEDOR
		$contacto['idProveedor']	= $idProveedor;
		$this->db->insert('contactos_proveedores', $contacto); 
	}
	
	public function exportarProveedores()
	{
		$sql=" select a.*
		from proveedores as a
		where a.activo='1'
		order by empresa asc ";
		
		return $this->db->query($sql)->result();
	}
	
	//MATERIALES

	public function obtenerUnidad($nombre)
	{
		$sql="select idUnidad
		from unidades
		where descripcion='$nombre' ";
		
		$unidad=$this->db->query($sql)->row();
		
		if($unidad!=null)
		{
			return $unidad->idUnidad;
		}
		else
		{
			$this->db->insert('unidades',array('descripcion'=>$nombre));
			
			return $this->db->insert_id();
		}
	}
	
	public function obtenerProveedor($nombre)
	{
		if(strlen($nombre)==0) return 1;
		
		$sql="select idProveedor
		from proveedores	
		where empresa='$nombre' ";	
		
		$proveedor	= $this->db->query($sql)->row();
		
		if($proveedor!=null)
		{
			return $proveedor->idProveedor;
		}
		else
		{
			$data=array
			(
				'empresa'		=> $nombre,
				'email'			=> '',
				'telefono'		=> '',
				'idUsuario'	 	=> $this->idUsuario,
				'fecha'			=> $this->fecha,
				'activo'		=> 1,
				'domicilio'		=> '',
				'rfc'			=> '',
				'estado'		=> '',
				'pais'			=> 'México',
				'website'		=> '',
				'idLicencia'	=> 1,
				
				'alias'			=> $nombre,
				'fax'			=> '',
				'localidad'		=> '',
				
				'numero'		=> '',
				'colonia'		=> '',
				'municipio'		=> '',
				'codigoPostal'	=> '',
				'vende'			=> '',
				
				'latitud'		=> '',
				'longitud'		=> '',
			);
			
			$this->db->insert('proveedores',$data);
			
			return $this->db->insert_id();
		}
	}
	
	public function comprobarMaterial($material)
	{
		$sql="select idMaterial
		from produccion_materiales
		where nombre='".$material['nombre']."' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function registrarMaterial($material,$proveedor)
	{
		if($this->comprobarMaterial($material)>0) return "0";
		
		//REGISTRAR MATERIAL
		$this->db->insert('produccion_materiales', $material);
		$idMaterial = $this->db->insert_id();
		
		//PROVEEDOR
		$proveedor['idMaterial']	= $idMaterial;
		$this->db->insert('rel_material_proveedor', $proveedor); 
	}
	
	public function exportarMateriales()
	{
		$sql=" select a.idMaterial, a.nombre, d.costo, 
		a.costo as costoPromedio, a.produccion,
		a.stockMinimo, a.stock, b.empresa as nombreProveedor, 
		b.idProveedor, c.descripcion, a.codigoInterno,
		a.idConversion,
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_entradas as e where e.idMaterial=a.idMaterial and b.idProveedor=e.idProveedor and e.idLicencia='$this->idLicencia') as inventario,
		(select coalesce(sum(e.cantidad),0) from produccion_materiales_mermas as e where e.idMaterial=a.idMaterial and b.idProveedor=e.idProveedor and e.fechaRegistro is not null and e.idLicencia='$this->idLicencia') as salidas
		from produccion_materiales as a
		inner join rel_material_proveedor as d
		on a.idMaterial=d.idMaterial
		inner join  proveedores as b 
		on(b.idProveedor=d.idProveedor)
		inner join unidades as c 
		on(a.idUnidad=c.idUnidad)
		where tipoMaterial='1'
		order by a.nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	//PRODUCTOS
	
	public function obtenerLinea($nombre)
	{
		$sql="select idLinea
		from productos_lineas
		where nombre='$nombre' ";
		
		$linea	= $this->db->query($sql)->row();
		
		if($linea!=null)
		{
			return $linea->idLinea;
		}
		else
		{
			$this->db->insert('productos_lineas',array('nombre'=>$nombre));
			
			return $this->db->insert_id();
		}
	}
	
	public function obtenerSubLinea($nombre,$idLinea)
	{
		$sql="select idSubLinea
		from productos_sublineas
		where nombre='$nombre' 
		and idLinea='$idLinea' ";
		
		$linea	= $this->db->query($sql)->row();
		
		if($linea!=null)
		{
			return $linea->idSubLinea;
		}
		else
		{
			$this->db->insert('productos_sublineas',array('nombre'=>$nombre,'idLinea'=>$idLinea));
			
			return $this->db->insert_id();
		}
	}
	
	public function obtenerClaveUnidad($clave)
	{
		$sql=" select idUnidad
		from fac_catalogos_unidades
		where clave='$clave' ";
		
		$unidad	= $this->db->query($sql)->row();
		
		return $unidad!=null?$unidad->idUnidad:1070;
	}
	
	public function obtenerClaveUnidadImportar($clave)
	{
		$sql=" select idUnidad
		from fac_catalogos_unidades
		where clave='$clave'
		or nombre='$clave' ";
		
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
	
	public function comprobarProducto($producto)
	{
		/*$sql="select idProducto
		from productos
		where nombre='".$producto['nombre']."' ";*/
		
		$this->db->select('idProducto');
		$this->db->from('productos');
		$this->db->where('nombre',$producto['nombre']);
		$this->db->where('codigoBarras',$producto['codigoBarras']);
		$this->db->where('codigoInterno',$producto['codigoInterno']);
		
		return $this->db->get()->num_rows();
	}
	
	public function comprobarProductoCodigo($producto)
	{
		$this->db->select('idProducto');
		$this->db->from('productos');
		$this->db->where('codigoInterno',$producto['codigoInterno']);
		
		return $this->db->get()->row();
	}

	public function obtenerConsecutivoProducto()
	{
		$this->db->select_max('folio');
		$this->db->from('productos');
		$this->db->where('activo','1');
		
		$folio	= $this->db->get()->row()->folio+1;

		switch(strlen($folio))
		{
			case 1: return array('0000'.$folio,$folio); break;
			case 2: return array('000'.$folio,$folio); break;
			case 3: return array('00'.$folio,$folio); break;
			case 4: return array('0'.$folio,$folio); break;
			case 5: return array($folio,$folio); break;

		}
	}
	
	public function registrarProducto($producto,$produccion,$proveedor)
	{
		if(sistemaActivo=='cerraduras')
		{
			$inventario	= $this->comprobarProductoCodigo($producto);
			
			if($inventario!=null)
			{
				if(strlen($producto['codigoInterno'])>0)
				{
					$this->db->where('idProducto',$inventario->idProducto);
					$this->db->update('productos',array('precioA'=>$producto['precioA']));
					
					$this->db->where('idProducto',$inventario->idProducto);
					$this->db->where('idLicencia',$this->idLicencia);
					$this->db->update('productos_inventarios',array('stock'=>$producto['stock']));
				}
				
				
				return "0";
			}
		}
		else
		{
			if($this->comprobarProducto($producto)>0) return "0";
		}
		
		
		//REGISTRAR PRODUCTO
		$this->db->insert('productos', $producto);
		$idProducto = $this->db->insert_id();
		
		/*$this->db->insert('produccion_productos', $produccion);
		$idProductoProduccion = $this->db->insert_id();
		
		$this->db->insert('rel_producto_produccion', array('idProducto'=>$idProducto,'idProductoProduccion'=>$idProductoProduccion,'fecha'=>$this->fecha,'cantidad'=>1));*/

		
		//PROVEEDOR
		$proveedor['idProducto']	= $idProducto;
		$this->db->insert('rel_producto_proveedor', $proveedor); 
		
		//ASOCIAR PRODUCTOS CON LAS SUCURSALES
		$this->inventario->registrarStockLicencias($idProducto,$producto['stock'],0,$producto['precioA'],$producto['precioB'],$producto['precioC'],$producto['precioD'],$producto['precioE']);
	}
	
	public function exportarProductos($criterio='',$orden='asc',$minimo='0',$codigoInterno='')
	{
		$sql =" select a.idProducto, a.nombre, a.imagen,  a.descripcion,
		g.precioA, g.precioB, g.precioC, g.precioD, g.precioE, a.reventa, 
		a.stock, a.codigoBarras, a.codigoInterno, a.unidad, g.stock, a.precioImpuestos,
		b.nombre as linea, a.sku, a.upc, d.precio as costo, e.empresa as proveedor,
		
		(select f.nombre from productos_departamentos as f where f.idDepartamento=a.idDepartamento) as departamento,
		(select f.nombre from productos_marcas as f where f.idMarca=a.idMarca) as marca,
		(select f.nombre from productos_sublineas as f where f.idSubLinea=a.idSubLinea) as sublinea,
		
		(select concat(f.clave,', ',f.nombre) from fac_catalogos_unidades as f where f.idUnidad=a.idUnidad) as unidad,
		(select concat(f.clave,', ',f.nombre) from fac_catalogos_claves_productos as f where f.idClave=a.idClave) as claveProducto
		
		
		from productos as a
		inner join productos_lineas as b
		on a.idLinea=b.idLinea 
		inner join rel_producto_proveedor as d
		on d.idProducto=a.idProducto 
		inner join proveedores as e
		on e.idProveedor=d.idProveedor 
		
		inner join productos_inventarios as g
		on g.idProducto=a.idProducto 
		
		
		where a.activo='1'
		and g.idLicencia='$this->idLicencia' 
		and a.reventa='1'
		and servicio='0' 
		and materiaPrima='0' ";
		
		$sql.= strlen($criterio)>0?" and (a.nombre like '%$criterio%' or a.codigoInterno like '%$criterio%' or a.codigoBarras like '%$criterio%' ) ":' ';
		$sql.= strlen($codigoInterno)>0?" and (a.codigoInterno like '$codigoInterno%') ":' ';
		
		if(sistemaActivo=='cerraduras')
		{
			#$sql.=$minimo=='1'?" and a.stockMinimo >= g.stock ":'';
		}
		
		$sql.=" group by a.idProducto
		order by a.nombre $orden ";

		return $this->db->query($sql)->result();
	}
	
	public function comprobarProduccion($producto)
	{
		$sql="select idProducto
		from productos
		where nombre='".$producto['nombre']."'
		and codigoInterno='".$producto['codigoInterno']."' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function registrarProduccion($producto,$produccion)
	{
		if($this->comprobarProducto($producto)>0) return "0";
		
		//REGISTRAR PRODUCCIÓN
		$this->db->insert('productos', $producto);
		$idProducto = $this->db->insert_id();
		
		
		//ASOCIAR PRODUCTOS CON LAS SUCURSALES
		$this->inventario->registrarStockLicencias($idProducto,$producto['stock']);
		
		/*$this->db->insert('produccion_productos', $produccion);
		$idProductoProduccion = $this->db->insert_id();
		
		$this->db->insert('rel_producto_produccion', array('idProducto'=>$idProducto,'idProductoProduccion'=>$idProductoProduccion,'fecha'=>$this->fecha,'cantidad'=>1));*/
	}
	
	public function exportarProduccion()
	{
		$sql =" select a.idProducto, a.nombre,
		a.precioA, a.precioB, a.precioC, precioD, precioE, 
		c.stock, a.codigoInterno,
		b.nombre as linea, a.sku, a.upc, a.costo, a.codigoBarras,
		
		(select concat(f.clave,', ',f.nombre) from fac_catalogos_unidades as f where f.idUnidad=a.idUnidad) as unidad,
		(select concat(f.clave,', ',f.nombre) from fac_catalogos_claves_productos as f where f.idClave=a.idClave) as claveProducto
		
		
		from productos as a
		inner join productos_lineas as b
		on a.idLinea=b.idLinea 
		
		inner join productos_inventarios as c
		on a.idProducto=c.idProducto 
		
		
		where a.activo='1'
		and a.reventa='0'
		and c.idLicencia='$this->idLicencia' 
		and servicio='0' 
		and materiaPrima='0' ";

		return $this->db->query($sql)->result();
	}
	
	//PROSPECTOS
	
	public function obtenerIdCampana($nombre)
	{
		$sql=" select idCampana
		from clientes_campanas
		where nombre='$nombre'
		and activa='1' ";
		
		$catalogo=$this->db->query($sql)->row();
		
		if($catalogo!=null)
		{
			return $catalogo->idCampana;
		}
		else
		{
			$data=array
			(
				'nombre'		=> $nombre,
				'fechaInicial'	=> $this->fecha,
				'fechaFinal'	=> $this->fecha,
				'fechaRegistro'	=> $this->fecha,
				'idUsuario'		=> $this->idUsuario
			);
			
			$this->db->insert('clientes_campanas',$data);
			
			return $this->db->insert_id();
		}
	}
	
	public function obtenerIdFuente($nombre)
	{
		$sql="select idFuente
		from clientes_fuentes
		where nombre='$nombre' ";
		
		$catalogo=$this->db->query($sql)->row();
		
		if($catalogo!=null)
		{
			return $catalogo->idFuente;
		}
		else
		{
			$this->db->insert('clientes_fuentes',array('nombre'=>$nombre));
			
			return $this->db->insert_id();
		}
	}
	
	public function obtenerIdPrograma($nombre,$idCampana=0)
	{
		if(strlen($nombre)==0) return 0;
		
		$sql="select idPrograma
		from clientes_programas
		where nombre='$nombre' ";
		
		$catalogo=$this->db->query($sql)->row();
		
		if($catalogo!=null)
		{
			return $catalogo->idPrograma;
		}
		else
		{
			$this->db->insert('clientes_programas',array('nombre'=>$nombre));
			
			$idPrograma	= $this->db->insert_id();
			
			$this->db->insert('clientes_campanas_programas',array('idPrograma'=>$idPrograma,'idCampana'=>$idCampana));
			
			return $idPrograma;
		}
	}
	
	public function obtenerIdZona($descripcion)
	{
		$sql="select idZona
		from zonas
		where descripcion='$descripcion' ";
		
		$catalogo=$this->db->query($sql)->row();
		
		if($catalogo!=null)
		{
			return $catalogo->idZona;
		}
		else
		{
			$this->db->insert('zonas',array('descripcion'=>$descripcion));
			
			return $this->db->insert_id();
		}
	}
	
	public function obtenerIdPromotor($nombre)
	{
		$sql=" select idUsuario
		from usuarios
		where concat(nombre,' ',apellidoPaterno,' ',apellidoMaterno)='$nombre'
		and activo='1'
		and idRol=18 ";
		
		$catalogo=$this->db->query($sql)->row();
		
		if($catalogo!=null)
		{
			return $catalogo->idUsuario;
		}
		else
		{
			$data=array
			(
				'nombre'			=> $nombre,
				'fechaCreacion'		=> $this->fecha,
				'idRol'				=> '18',
				'idUsuarioCreacion'	=> $this->idUsuario,
				'usuario'			=> rand(100000,999999),
				'promotor'			=> '1',
			);
			
			$this->db->insert('usuarios',$data);
			
			return $this->db->insert_id();
		}
	}
	
	public function comprobarUsuarioPromotor($email,$movil)
	{
		if(strlen($email)==0 and strlen($movil)==0) return null;
		
		$sql=" select a.idCliente, a.idPromotor,
		(select b.idContacto from clientes_contactos as b where b.idCliente=a.idCliente limit 1) as idContacto
		from clientes as a
		where a.idCliente>0 ";
		
		$sql.=strlen($email)>1?" and a.email='$email' ":'';
		$sql.=strlen($movil)>1?" and a.movil='$movil' ":'';
		
		$catalogo=$this->db->query($sql)->row();
		
		if($catalogo!=null)
		{
			return $catalogo;
		}
		else
		{
			return null;
		}
	}
	
	//REVISAR  QUE ESTE EN CUALQUIER ESTATUS MENOS ALUMNO O EXALUMNO
	public function comprobarUsuarioEmailImportar($email)
	{
		$email	= trim($email);
		
		if(strlen($email)==0) return null;
		
		$sql=" select a.idCliente, a.idPromotor, a.prospecto, a.idCampana,
		(select b.idContacto from clientes_contactos as b where b.idCliente=a.idCliente limit 1) as idContacto
		from clientes as a
		where a.idCliente>0
		and a.email='$email'
		and a.prospecto='1' ";

		return  $this->db->query($sql)->row();
	}
	
	public function comprobarUsuarioMovilImportar($movil)
	{
		$movil	= trim($movil);
		
		if(strlen($movil)==0) return null;
		
		$sql=" select a.idCliente, a.idPromotor, a.prospecto,  a.idCampana,
		(select b.idContacto from clientes_contactos as b where b.idCliente=a.idCliente limit 1) as idContacto
		from clientes as a
		where a.idCliente>0
		and a.activo='1'
		and a.movil='$movil'
		and a.prospecto='1' ";

		return  $this->db->query($sql)->row();
	}
	
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function comprobarUsuarioEmail($email)
	{
		$email	= trim($email);
		
		if(strlen($email)==0) return null;
		
		$sql=" select a.idCliente, a.idPromotor, a.prospecto, a.idCampana,
		(select b.idContacto from clientes_contactos as b where b.idCliente=a.idCliente limit 1) as idContacto
		from clientes as a
		where a.idCliente>0
		and a.activo='1'
		and a.email='$email' ";

		return  $this->db->query($sql)->row();
	}
	
	public function comprobarUsuarioMovil($movil)
	{
		$movil	= trim($movil);
		
		if(strlen($movil)==0) return null;
		
		$sql=" select a.idCliente, a.idPromotor, a.prospecto,  a.idCampana,
		(select b.idContacto from clientes_contactos as b where b.idCliente=a.idCliente limit 1) as idContacto
		from clientes as a
		where a.idCliente>0
		and a.activo='1'
		and a.movil='$movil' ";

		return  $this->db->query($sql)->row();
	}
	
	public function comprobarUsuarioEmailProspecto($email)
	{
		$email	= trim($email);
		
		if(strlen($email)==0) return null;
		
		$sql=" select a.idCliente, a.idPromotor, a.prospecto, a.idCampana, a.idZona,
		(select b.idContacto from clientes_contactos as b where b.idCliente=a.idCliente limit 1) as idContacto
		from clientes as a
		where a.idCliente>0
		and a.email='$email'
		and prospecto='1'
		and (a.activo='0' or a.idZona=2) ";

		return  $this->db->query($sql)->row();
	}
	
	public function comprobarUsuarioMovilProspecto($movil)
	{
		$movil	= trim($movil);
		
		if(strlen($movil)==0) return null;
		
		$sql=" select a.idCliente, a.idPromotor, a.prospecto,  a.idCampana, a.idZona,
		(select b.idContacto from clientes_contactos as b where b.idCliente=a.idCliente limit 1) as idContacto
		from clientes as a
		where a.idCliente>0
		and a.movil='$movil'
		and prospecto='1'
		and (a.activo='0' or a.idZona=2) ";

		return  $this->db->query($sql)->row();
	}
	
	/*public function comprobarUsuarioNombre($nombre,$paterno='',$materno='')
	{
		if(strlen($nombre)==0) return null;
		
		$sql=" select a.idCliente, a.idPromotor,
		(select b.idContacto from clientes_contactos as b where b.idCliente=a.idCliente limit 1) as idContacto
		from clientes as a
		where a.idCliente>0
		and a.nombre='$nombre' ";
		
		$sql.=strlen($paterno)>0?" and a.paterno='$paterno' ":'';
		$sql.=strlen($materno)>0?" and a.materno='$materno' ":'';

		return  $this->db->query($sql)->row();
	}*/
	
	public function comprobarUsuarioNombre($nombre,$paterno='',$materno='')
	{
		if(strlen($nombre)==0) return null;
		
		/*
		
		$sql=" select a.idCliente, a.idPromotor,
		(select b.idContacto from clientes_contactos as b where b.idCliente=a.idCliente limit 1) as idContacto
		from clientes as a
		where a.idCliente>0
		and a.nombre='$nombre' ";
		
		$sql.=strlen($paterno)>0?" and a.paterno='$paterno' ":'';
		$sql.=strlen($materno)>0?" and a.materno='$materno' ":'';*/
		
		
		$this->db->select('a.idCliente, a.idPromotor, b.idContacto');
		$this->db->from("clientes as a");
		$this->db->join("clientes_contactos as b",'a.idCliente=b.idCliente');
		
		$this->db->where('a.nombre', $nombre);
		
		if(strlen($paterno)>0)
		{
			$this->db->where('a.paterno', $paterno);
		}
		
		if(strlen($materno)>0)
		{
			$this->db->where('a.materno', $materno);
		}

		return  $this->db->get()->row();
	}
	
	public function truncarClientesRepetidos()
	{
		$this->db->truncate('clientes_repetidos');
	}
	
	public function registrarClientesRepetidos($clientes)
	{
		$this->db->insert('clientes_repetidos',$clientes);
	}
	
	public function comprobarRepetidos()
	{
		$sql=" select count(idCliente) as numero
		from clientes_repetidos
		where fecha='$this->fechaCorta' ";
		
		$repetidos	= $this->db->query($sql)->row()->numero;
		
		return array($repetidos);
	}
	
	public function obtenerRepetidos()
	{
		$sql=" select a.*,
		concat(b.nombre,' ',b.apellidoPaterno,' ',b.apellidoMaterno) as promotor,
		c.prospecto, d.descripcion as zona
		from clientes_repetidos as a
		inner join usuarios as b
		on a.idUsuario=b.idUsuario
		
		inner join clientes as c
		on a.idCliente=c.idCliente
		
		inner join zonas as d
		on d.idZona=c.idZona
		where fecha='$this->fechaCorta' ";
		
		return $this->db->query($sql)->result();
	}
	
	//PROSPECTOS DE FACEBOOK
	public function obtenerPromotores()
	{
		$sql=" select idUsuario, concat(nombre,' ',apellidoPaterno,' ',apellidoMaterno) as nombre
		from usuarios
		where idUsuario>0 
		and idLicencia='$this->idLicencia'
		and promotor='1' 
		and activo='1'
		order by idUsuario asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerUltimoFacebook()
	{
		$sql=" select idPromotor
		from clientes
		where facebook='1'
		and nuevo='0'
		order by idCliente desc
		limit 1 ";
		
		$cliente= $this->db->query($sql)->row();
		
		return $cliente!=null?$cliente->idPromotor:0;
	}
	
	public function obtenerProspectosFacebook()
	{
		$sql=" select idCliente, idPromotor
		from clientes
		where nuevo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerUltimoSeguimientoCliente($idCliente)
	{
		$sql=" select idSeguimiento
		from seguimiento
		where idCliente='$idCliente'
		order by idSeguimiento desc
		limit 1 ";
		
		return $this->db->query($sql)->row();
	}
	
	public function procesarProspectosPromotores()
	{
		$prospectos	= $this->obtenerProspectosFacebook();
		$promotores	= $this->obtenerPromotores();
		$idPromotor	= $this->obtenerUltimoFacebook();
		$p			= 0; //Indice para promotor
		
		if($idPromotor>0)
		{
			for($i=0;$i<count($promotores);$i++)
			{
				if($promotores[$i]->idUsuario==$idPromotor)
				{
					$p=$i+1;
					break;
				}
			}
		}
		
		#echo '<br />idPromotor: '.$idPromotor;
		#echo '<br />Promotores: '.count($promotores);
		#echo '<br />P: '.$p;
		
		if($p==count($promotores)) $p=0;
		
		#echo '<br />Promotores: '.count($promotores);
		#echo '<br />P: '.$p;
		
		foreach($prospectos as $row)
		{
			$ultimo=$this->obtenerUltimoSeguimientoCliente($row->idCliente);
			
			$this->db->where('idCliente',$row->idCliente);
			$this->db->update('clientes',array('idPromotor'=>$promotores[$p]->idUsuario,'nuevo'=>'0'));
			
			if($ultimo!=null)
			{
				$this->db->where('idSeguimiento',$ultimo->idSeguimiento);
				$this->db->update('seguimiento',array('idUsuarioRegistro'=>$promotores[$p]->idUsuario,'idResponsable'=>$promotores[$p]->idUsuario));
			}
			
			$p++;
			if($p==count($promotores)) $p=0;
		}
	}
	
	//IMPORTAR EL PERSONAL PARA CHEQUEO
	public function obtenerPersonalNombre($nombre)
	{
		$sql=" select a.idPersonal, b.horaInicial, b.horaFinal
		from recursos_personal as a
		inner join recursos_personal_horarios as b
		on a.idPersonal=b.idPersonal
		where a.nombre='$nombre' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarRegistroChequeo($idPersonal,$fecha)
	{
		$sql=" select idChequeo, horaEntrada, horaSalida
		from recursos_personal_chequeo 
		where idPersonal='$idPersonal'
		and fecha='$fecha' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerDiferenciaChequeo($fechaActual,$fechaPersonal)
	{
		$sql		= "select timestampdiff(second, '".$fechaActual."', '".$fechaPersonal."') as diferencia";
		
		#echo '<br />'.$sql.'<br />';

		$segundos		= $this->db->query($sql)->row()->diferencia;
		$operacion		= $segundos/60;		
		$minutos		= intval($operacion);
		
		$decimales		= explode('.',$operacion);
		$decimal		= isset($decimales[1])?$decimales[1]:0;
		$segundosTotal	= 0;
		
		if($decimal!=0)
		{
			$segundosTotal	= ($segundos<0?$segundos*-1:$segundos)-(($minutos<0?$minutos*-1:$minutos)*60);
			$segundosTotal	= $segundosTotal<10?$segundosTotal/10:$segundosTotal;
		}

		$total		 = ($operacion<0?'-':''). ($minutos<0?$minutos*-1:$minutos).'.'.(str_replace('.','',$segundosTotal));
		
		#echo '<br />'.$total.'<br />';
		
		return $total;
	}
	
	public function registrarChequeo($data)
	{
		//REGISTRAR CHEQUEO
		$this->db->insert('recursos_personal_chequeo', $data);
	}
	
	public function editarChequeo($data,$idChequeo)
	{
		//EDITAR CHEQUEO
		$this->db->where('idChequeo', $idChequeo);
		$this->db->update('recursos_personal_chequeo', $data);
	}
	
	//IMPORTAR COMPARAR
	
	public function truncarClientesComparar()
	{
		$this->db->truncate('clientes_comparaciones');
	}
	
	public function registrarClientesComparar($clientes)
	{
		$this->db->insert('clientes_comparaciones',$clientes);
	}
	
	public function obtenerComparados()
	{
		$sql=" select a.*,
		concat(c.nombre,' ',c.apellidoPaterno,' ',c.apellidoMaterno) as promotor,
		concat(b.nombre,' ',b.paterno,' ',b.materno) as alumno, d.descripcion as zona,
		b.prospecto, b.email, b.movil, b.telefono,
		
		(select e.nombre from clientes_programas as e inner join clientes_academicos as f on f.idPrograma=e.idPrograma where f.idCliente=a.idCliente limit 1) as programa,
		(select e.nombre from clientes_campanas as e where b.idCampana=e.idCampana limit 1) as campana
		
		from clientes_comparaciones as a
		
		inner join clientes as b
		on a.idCliente=b.idCliente
		
		inner join usuarios as c
		on c.idUsuario=b.idPromotor
		
		inner join zonas as d
		on d.idZona=b.idZona
		where a.idCliente>0 ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCorreos()
	{
		$sql=" select * from clientes_comparaciones
		where idCliente=0 ";
		
		return $this->db->query($sql)->result();
	}
	
	
	//BORRAR SEGUIMIENTOS CLIENTES
	
	public function obtenerSeguimientosBorrar($idCliente)
	{
		$sql=" select a.idSeguimiento 
		FROM seguimiento AS a 
		WHERE a.idCliente='$idCliente' ";

		return $this->db->query($sql)->result();
	}
	
	public function procesarSeguimientosBorrar($idCliente)
	{
		$seguimiento	= $this->obtenerSeguimientosBorrar($idCliente);
		
		foreach($seguimiento as $row)
		{	
			$this->db->where('idSeguimiento',$row->idSeguimiento);
			$this->db->delete('seguimiento_detalles');
			
			$this->db->where('idSeguimiento',$row->idSeguimiento);
			$this->db->delete('seguimiento');
		}
	}
	
	public function obtenerNiveles($nivel1,$nivel2,$nivel3)
	{
		$idNivel1	= 0;
		$idNivel2	= 0;
		$idNivel3	= 0;
		
		if(strlen($nivel1)>0)
		{
			$sql="select idNivel1 from catalogos_niveles1 where activo='1' and nombre='$nivel1'";
			
			$registro	= $this->db->query($sql)->row();
			
			if($registro!=null)
			{
				$idNivel1=$registro->idNivel1;
			}
			else
			{
				$this->db->insert('catalogos_niveles1',array('nombre'=>$nivel1));
				$idNivel1	= $this->db->insert_id();
			}
			
			if($idNivel1>0 and strlen($nivel2)>0)
			{
				$sql="select idNivel2 from catalogos_niveles2 where activo='1' and nombre='$nivel2' and idNivel1='$idNivel1' ";
				
				$registro	= $this->db->query($sql)->row();
			
				if($registro!=null)
				{
					$idNivel2=$registro->idNivel2;
				}
				else
				{
					$this->db->insert('catalogos_niveles2',array('nombre'=>$nivel2,'idNivel1'=>$idNivel1));
					$idNivel2	= $this->db->insert_id();
				}
				
				
				if($idNivel2>0 and strlen($nivel3)>0)
				{
					$sql="select idNivel3 from catalogos_niveles3 where activo='1' and nombre='$nivel3' and idNivel2='$idNivel2' ";
					
					$registro	= $this->db->query($sql)->row();
				
					if($registro!=null)
					{
						$idNivel3=$registro->idNivel3;
					}
					else
					{
						$this->db->insert('catalogos_niveles3',array('nombre'=>$nivel3,'idNivel2'=>$idNivel2));
						$idNivel3	= $this->db->insert_id();
					}
				}
			}
		}
		
		
		return array($idNivel1,$idNivel2,$idNivel3);
	}
	
	public function editarProspecto($cliente,$academicos=null,$reasignacion=null,$idCliente=0)
	{
		$this->db->where('idCliente', $idCliente);
		$this->db->update('clientes', $cliente);
		
		$this->db->where('idCliente', $idCliente);
		$this->db->update('clientes_academicos', $academicos);

		if($reasignacion!=null)
		{
			$this->db->insert('clientes_reasignaciones', $reasignacion); 
		}
	}
}
