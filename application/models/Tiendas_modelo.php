<?php
class Tiendas_modelo extends CI_Model
{
	protected $fecha;
	protected $_table;
	protected $idLicencia;
	protected $idUsuario;
	protected $idTienda;
	protected $fechaCorta;

	function __construct()
	{
		parent::__construct();
		
		$this->config->load('datatables',TRUE);
		
        $this->idUsuario 		= $this->session->userdata('id');
		$this->fecha 			= date('Y-m-d H:i:s');
		$this->idTienda 		= $this->session->userdata('idTiendaActiva');
		$this->fechaCorta 		= date('Y-m-d');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
	}
	

	public function obtenerTiendaActiva()
	{
		$sql="select * from tiendas
		where idTienda='$this->idTienda'
		and activa='1'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerTienda($idTienda)
	{
		$sql="select * from tiendas
		where idTienda='$idTienda'
		and activa='1'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerTiendaVenta($idTienda)
	{
		$sql="select * from tiendas
		where idTienda='$idTienda' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerTiendas()
	{
		$sql="select * from tiendas
		where activa='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTiendasUsuario()
	{
		$sql="select idTienda, nombre from tiendas
		where activa='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTiendasStock($idProducto)
	{
		$sql=" select a.*,
		(select b.cantidad
		from tiendas_productos as b
		where b.idTienda=a.idTienda
		and b.idProducto='$idProducto') as cantidad
		from tiendas as a
		where a.activa='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerStockSucursales($idProducto)
	{
		$sql=" select a.stock, b.nombre
		from productos_inventarios as a
		inner join configuracion as b
		on a.idLicencia=b.idLicencia
		inner join licencias as c
		on c.idLicencia=b.idLicencia
		where c.activa='1'
		and a.idProducto='$idProducto' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function comprobarTiendaRegistro($nombre)
	{
		$sql =" select idTienda
		from  tiendas 
		where activa='1'
		and nombre='$nombre' ";

		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarTienda()
	{
		if(!$this->comprobarTiendaRegistro(trim($this->input->post('txtNombre'))))
		{
			return array('0',registroDuplicado);
		}
		
		$data=array
		(
			'nombre'			=> trim($this->input->post('txtNombre')),
			'calle'				=> trim($this->input->post('txtCalle')),
			'numero'			=> trim($this->input->post('txtNumero')),
			'colonia'			=> trim($this->input->post('txtColonia')),
			'localidad'			=> trim($this->input->post('txtLocalidad')),
			'municipio'			=> trim($this->input->post('txtMunicipio')),
			'estado'			=> trim($this->input->post('txtEstado')),
			'codigoPostal'		=> trim($this->input->post('txtCodigoPostal')),
			'telefono'			=> trim($this->input->post('txtTelefono')),
			'email'				=> trim($this->input->post('txtEmail')),
			'idUsuario'			=> $this->idUsuario,
			'fecha'				=> $this->fecha,
		);
		
		$this->db->insert('tiendas',$data);
		
		$this->configuracion->registrarBitacora('Registrar tienda','Configuración - Tiendas',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarTienda()
	{
		$data=array
		(
			'nombre'			=>$this->input->post('txtNombre'),
			'calle'				=>$this->input->post('txtCalle'),
			'numero'			=>$this->input->post('txtNumero'),
			'colonia'			=>$this->input->post('txtColonia'),
			'localidad'			=>$this->input->post('txtLocalidad'),
			'municipio'			=>$this->input->post('txtMunicipio'),
			'estado'			=>$this->input->post('txtEstado'),
			'codigoPostal'		=>$this->input->post('txtCodigoPostal'),
			'telefono'			=>$this->input->post('txtTelefono'),
			'email'				=>$this->input->post('txtEmail'),
		);
		
		$this->db->where('idTienda',$this->input->post('txtIdTienda'));
		$this->db->update('tiendas',$data);
		
		$this->configuracion->registrarBitacora('Editar tienda','Configuración - Tiendas',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function borrarTienda($idTienda) //El borrado sera solamente logico
	{
		$tienda	= $this->obtenerTienda($idTienda);
		
		$this->db->where('idTienda',$idTienda);
		$this->db->update('tiendas',array('activa'	=> '0'));
		
		if($tienda!=null)
		{
			$this->configuracion->registrarBitacora('Borrar tienda','Configuración - Tiendas',$tienda->nombre); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerProductosTienda($idTienda)
	{
		$nombre=$this->input->post('nombreProducto');
		
		$nombre=str_replace("'","",$nombre);
		
		$sql="select a.*, b.nombre, b.imagen, b.precioA
		from tiendas_productos as a
		inner join productos as b
		on a.idProducto=b.idProducto
		where a.idTienda='$idTienda' 
		and (b.nombre like '%$nombre%'
		or b.codigoBarras like '%$nombre%')
		limit 5";
		
		#echo $sql;
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerIdVenta()
	{
		$sql="select max(folio) as folio from 
		cotizaciones";
		
		return $this->db->query($sql)->row()->folio+1;
	}
	
	#La venta implica muchas operaciones :) 
	public function realizarVenta() 
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas

		$folio=$this->obtenerIdVenta();
		
		$cantidad		=$this->input->post('cantidad');
		$productos		=$this->input->post('productos');
		$preciosTotales	=$this->input->post('preciosTotales');
		$precioProducto	=$this->input->post('precioProducto');
		
		$subtotal		=$this->input->post('kitTotal');
		$iva			=$this->input->post('iva');
		$total			=$this->input->post('total');
		$descuento		=$this->input->post('descuento');
		$idCliente		=$this->input->post('idCliente');
		$idTienda		=$this->session->userdata('idTiendaActiva');
		
		//$this->session->set_userdata('idVenta',$idVenta);
		
		#--------------------------ORDEN DE VENTAS----------------------------#
		$serie="COT-".$folio;
		$venta="VEN-".$folio;
		#---------------------------------------------------------------------#
		
		$data=array
		(
			'ordenCompra'		=>$venta,
			'idCliente'			=>$idCliente,
			'fecha'				=>$this->fecha,
			'fechaPedido'		=>$this->fecha,
			'fechaPedido'		=>$this->fecha,
			'serie'				=>$serie,
			'estatus'			=>'1',
			'idUsuario'			=>$this->idUsuario,
			'fechaCompra'		=>$this->fecha,
			'pago'				=>$this->input->post('pago'),
			'cambio'			=>$this->input->post('cambio'),
			'descuento'			=>$descuento,
			'subTotal'			=>$subtotal,
			'iva'				=>$iva/100,
			'total'				=>$total,
			'folio'				=>$folio,
			'idLicencia'		=>$this->idLicencia,
			'idTienda'			=>$idTienda,
		);
		
		$this->db->insert('cotizaciones',$data);
		
		$idCotizacion=$this->db->insert_id();
		
		$indice=count($cantidad);
		
		for($i=0;$i<$indice;$i++)
		{
			$data=array
			(
				'idCotizacion' 		=> $idCotizacion,
				'cantidad' 			=>$cantidad[$i],
				'precio' 			=>$precioProducto[$i],
				'importe' 			=>$preciosTotales[$i],
				'idProduct' 		=>$productos[$i],
				'tipo' 				=>$precioProducto[$i],
				'fecha_entrega' 	=>$this->fecha,
				'facturado' 		=>'0',
				'produccion' 		=>'1',
			);
			
			$this->db->insert('cotiza_productos',$data);
			
			$idProductoCotizacion=$this->db->insert_id();
			#----------------------------------------------------------------------------------------------------------#
			$sql="select * from tiendas_productos
			where idProducto='".$productos[$i]."'
			and idTienda='$idTienda'";
			
			$query=$this->db->query($sql)->row();
			
			if($query->cantidad<$cantidad[$i])
			{
				$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
				$this->db->trans_complete();
			
				return "2";
			}
			else
			{
				$data=array
				(
					'cantidad' =>$query->cantidad-$cantidad[$i]
				);
				
				$this->db->where('idProducto',$productos[$i]);
				$this->db->where('idTienda',$idTienda);
				$this->db->update('tiendas_productos',$data);
				
			#----------------------------------------------------------------------------------------------------------#
				
				$data=array
				(
					'fecha' 		=>$this->fecha,
					'cantidad' 		=>$cantidad[$i],
					'entrego' 		=>$this->session->userdata('nombreUsuarioSesion'),
					'idProducto' 	=>$idProductoCotizacion
				);
				
				$this->db->insert('ventas_entrega_detalles',$data);
			}
			
			#----------------------------------------------------------------------------------------------------------#
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return "1";
		}
	}

	public function contarVentasTienda($idTienda)
	{
		$sql="select a.idCotizacion
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.id
		where idTienda='$idTienda'
		and estatus='1' ";

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerVentasTienda($idTienda,$numero,$limite)
	{
		$idCliente	= $this->input->post('idCliente');
		
		$sql="select a.*, b.empresa
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.id
		where  idTienda='$idTienda'
		and estatus='1' ";

		$sql .= " limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	
	
	
	public function obtenerStockTienda($idTienda,$idProducto)
	{
		if($idTienda>0)
		{
			$sql=" select coalesce(sum(cantidad),0) as cantidad
			from tiendas_productos
			where idProducto='$idProducto'
			and idTienda='$idTienda' ";
			
			return $this->db->query($sql)->row()->cantidad;
		}
		else
		{
			$sql=" select stock
			from productos
			where idProducto='$idProducto' ";
			
			$producto	= $this->db->query($sql)->row();
			
			return $producto!=null?$producto->stock:0;
		}
	}
	
	public function comprobarProductoTienda($idProducto,$idTienda)
	{
		$sql=" select idProducto,cantidad
		from tiendas_productos
		where idProducto='$idProducto'
		and idTienda='$idTienda' ";
		
		return $this->db->query($sql)->row();
	}
	
	
	
	public function obtenerTiendaNombre($idTienda)
	{
		if($idTienda==0) return 'Matriz';
		
		$sql="select nombre
		from tiendas
		where idTienda='$idTienda' ";
		
		$tienda=$this->db->query($sql)->row();
		
		return $tienda!=null?$tienda->nombre:'';
	}
	
	public function obtenerProducto($idProducto)
	{
		$sql=" select idProducto, stock from productos
		where idProducto='$idProducto' ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerProductoDetalles($idProducto)
	{
		$sql=" select a.*, 
		(select d.nombre from productos_lineas as d where d.idLinea=a.idLinea) as linea
		from productos as a
		where a.idProducto='$idProducto' ";
		
		return $this->db->query($sql)->row();
	}
   
	public function actualizarStockProducto($idProducto,$cantidad,$criterio='restar')
	{
		$producto	= $this->obtenerProducto($idProducto);
		
		if($producto!=null)
		{
			$data=array
			(
				'stock'	=> $criterio=='restar'?$producto->stock-$cantidad:$producto->stock+$cantidad
			);
			
			$this->db->where('idProducto',$idProducto);
			$this->db->update('productos',$data);
		}
	}
	
	//PARA LA ADMINISTRACIÓN DE LAS VENTAS
	public function contarVentas($idTienda,$criterio)
	{
		$sql=" select a.idCotizacion
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		and (a.ordenCompra like '%$criterio%'
		or b.empresa like '%$criterio%' ) ";
		
		$sql.=$idTienda>0?" and a.idTienda='$idTienda'":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerVentas($numero,$limite,$idTienda,$criterio)
	{
		$sql=" select a.idCotizacion, a.fecha, a.idCliente,
		a.fechaCompra, a.ordenCompra, a.subTotal, a.iva, a.ivaPorcentaje,
		a.descuento, a.descuentoPorcentaje, a.total, b.empresa as cliente,
		a.idFactura, a.cancelada
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		and (a.ordenCompra like '%$criterio%'
		or b.empresa like '%$criterio%' ) ";
		
		$sql.=$idTienda>0?" and a.idTienda='$idTienda'":'';
		
		$sql .= " order by a.fechaCompra desc 
		limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	//CORTE DE CAJA
	public function obtenerTotalDia()
	{
		$sql=" select coalesce(sum(a.total),0) as total
		from cotizaciones as a
		where a.idTienda='$this->idTienda'
		and a.cancelada='0' 
		and a.idCorte=0
		and a.estatus=1
		and date(a.fechaPedido)='$this->fechaCorta' ";
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function obtenerCortesDia()
	{
		$sql=" select a.*, concat(b.nombre, ' ', b.apellidoPaterno, ' ', b.apellidoMaterno) as usuario 
		from cotizaciones_cortes as a
		inner join usuarios as b
		on a.idUsuario=b.idUsuario
		where a.idTienda='$this->idTienda'
		and date(fecha)='$this->fechaCorta'";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarCorte()
    {
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza

		$data=array
		(
			"fecha"				=> $this->fecha,
			"idUsuario"			=> $this->idUsuario,
			"efectivo"			=> $this->input->post('txtEfectivo'),
			"idTienda"			=> $this->idTienda,
			"comentarios"		=> $this->input->post('txtComentariosCorte'),
			"total"				=> $this->obtenerTotalDia(),
		);
		
		$this->db->insert("cotizaciones_cortes",$data);
		$idCorte	= $this->db->insert_id();
		
		#---------------------------------------------------------------------------------------#

		$data=array
		(
			"idCorte"		=>$idCorte,
		);
		
		$this->db->where('date(fechaPedido)',$this->fechaCorta);
		$this->db->where('idTienda',$this->idTienda);
		$this->db->where('estatus',1);
		$this->db->where('cancelada','0');
		$this->db->where('idCorte',0);
		$this->db->update('cotizaciones',$data);
	
		#---------------------------------------------------------------------------------------#
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}   
   	}
	
	//TRASPASOS POR VENTAS
	
	public function registrarTraspasosVenta()
    {
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		for($i=0;$i<$this->input->post('txtNumeroProductosTraspaso');$i++)
		{
			if(strlen($this->input->post('txtCantidadTraspaso'.$i))>0)
			{
				$cantidad		= $this->input->post('txtCantidadTraspaso'.$i);
				$idProducto		= $this->input->post('txtIdProductoTraspaso'.$i);
				$idTienda		= $this->idTienda;
				$idTiendaOrigen	= $this->input->post('selectTiendaOrigen'.$i);
				
				$data=array
				(
					"fecha"				=> $this->fecha,
					"idUsuario"			=> $this->idUsuario,
					"cantidad"			=> $cantidad,
					"idTienda"			=> $idTienda,
					"idProducto"		=> $idProducto,
					"idTiendaOrigen"	=> $idTiendaOrigen,
					"folio"				=> $this->obtenerFolioEnvio(),
				);
				
				$this->db->insert("tiendas_recepciones",$data);
				
				#---------------------------------------------------------------------------------------#
				
				$producto	= $this->comprobarProductoTienda($idProducto,$idTienda);
				
				if($producto!=null)
				{
					$data=array
					(
						"cantidad"		=>$producto->cantidad+$cantidad,
					);
					
					$this->db->where('idProducto',$idProducto);
					$this->db->where('idTienda',$idTienda);
					$this->db->update('tiendas_productos',$data);
				}
				else
				{
					$data=array
					(
						"cantidad"		=>$cantidad,
						"idProducto"	=>$idProducto,
						"idTienda"		=>$idTienda,
					);
					
					$this->db->insert('tiendas_productos',$data);
				}
				
				#---------------------------------------------------------------------------------------#
				
				if($idTiendaOrigen==0) //EL PRODUCTO SALE DE MATRIZ
				{
					$this->actualizarStockProducto($idProducto,$cantidad);
				}
				
				if($idTiendaOrigen!=0) //EL PRODUCTO SALE DE TIENDA
				{
					$producto	= $this->comprobarProductoTienda($idProducto,$idTiendaOrigen);
					
					if($producto!=null)
					{
						$data=array
						(
							"cantidad"		=>$producto->cantidad-$cantidad,
						);
						
						$this->db->where('idProducto',$idProducto);
						$this->db->where('idTienda',$idTiendaOrigen);
						$this->db->update('tiendas_productos',$data);
						
						
						$this->actualizarStockProducto($idProducto,$cantidad,'sumar');
					}
				}
				
				#$this->configuracion->registrarBitacora('Registrar traspaso de productos','Ventas',$this->obtenerProductoNombre($idProducto).', Cantidad: '.$cantidad); //Registrar bitácora
				$this->configuracion->registrarBitacora('Registrar traspaso de productos','Catálogo de productos',$this->obtenerProductoNombre($idProducto).', Cantidad: '.$cantidad.', Tienda origen: '.$this->obtenerTiendaNombre($idTiendaOrigen).', Tienda destino: '.$this->obtenerTiendaNombre($idTienda)); //Registrar bitácora
				
			}
		}

		#---------------------------------------------------------------------------------------#
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}   
   	}
	
	public function obtenerProductoNombre($idProducto)
	{
		$sql=" select nombre
		from productos
		where idProducto='$idProducto'";
		
		$producto=$this->db->query($sql)->row();
		
		return $producto!=null?$producto->nombre:'';
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//TRASPASOS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

	public function contarProductosTraspaso($criterio)
	{
		$sql =" select a.idProducto 
		from productos as a 
		where a.activo='1'
		and servicio='0' 
		and materiaPrima='0'
		and (a.nombre like '%$criterio%' 
		or a.codigoInterno like '%$criterio%'
		or a.upc like '%$criterio%'
		or a.sku like '%$criterio%' ) ";

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerProductosTraspaso($numero,$limite,$criterio)
	{
		$sql =" select a.idProducto, a.nombre, a.imagen, 
		a.precioA, a.precioB, a.precioC, a.reventa, a.codigoBarras, a.codigoInterno,
		b.nombre as linea, c.stock, a.upc, c.idInventario
		from productos as a
		inner join productos_lineas as b
		on a.idLinea=b.idLinea 
		
		inner join productos_inventarios as c
		on c.idProducto=a.idProducto 
		
		where a.activo='1'
		and a.servicio='0' 
		and a.materiaPrima='0'
		and c.idLicencia='$this->idLicencia'
		and (a.nombre like '%$criterio%' 
		or a.codigoInterno like '%$criterio%'
		or a.upc like '%$criterio%'
		or a.sku like '%$criterio%' )
		order by  c.stock desc, a.nombre asc ";
		
		$sql .= " limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function contarTraspasos($idLicenciaOrigen,$idLicenciaDestino,$criterio,$inicio,$fin)
	{
		$sql=" select a.idTraspaso
		from productos_traspasos as a

		where a.activo='1'
		and a.folio like '$criterio%'
		and date(a.fechaTraspaso) between '$inicio' and '$fin' 
		and(a.idLicencia='$this->idLicencia' or a.idLicenciaDestino='$this->idLicencia') ";
		
		$sql.=$idLicenciaOrigen>0?" and a.idLicenciaOrigen='$idLicenciaOrigen'":'';
		$sql.=$idLicenciaDestino>0?" and a.idLicenciaDestino='$idLicenciaDestino'":'';
		#$sql.=$this->idTienda>0?" and a.idTienda='$this->idTienda'":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerTraspasos($numero,$limite,$idLicenciaOrigen,$idLicenciaDestino,$criterio,$inicio,$fin)
	{
		$sql=" select a.*,
		(select b.nombre from configuracion as b where b.idLicencia=a.idLicenciaOrigen) as origen,
		(select b.nombre from configuracion as b where b.idLicencia=a.idLicenciaDestino) as destino
		from productos_traspasos as a

		where a.activo='1'
		and a.folio like '$criterio%'
		and date(a.fechaTraspaso) between '$inicio' and '$fin'
		and(a.idLicencia='$this->idLicencia' or a.idLicenciaDestino='$this->idLicencia') ";
		
		$sql.=$idLicenciaDestino>0?" and a.idLicenciaOrigen='$idLicenciaOrigen'":'';
		$sql.=$idLicenciaDestino>0?" and a.idLicenciaDestino='$idLicenciaDestino'":'';
		
		$sql .= " order by a.folio desc, a.fechaTraspaso desc ";
		
		$sql .= $numero>0?" limit $limite,$numero ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTraspaso($idTraspaso)
	{
		$sql=" select a.*,
		(select b.nombre from configuracion as b where b.idLicencia=a.idLicenciaOrigen) as origen,
		(select b.nombre from configuracion as b where b.idLicencia=a.idLicenciaDestino) as destino
		from productos_traspasos as a

		where a.activo='1'
		and a.idTraspaso='$idTraspaso' ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerDetallesTraspaso($idTraspaso)
	{
		$sql =" select a.idDetalle, a.cantidad, b.nombre as producto, b.idProducto, b.codigoInterno, c.nombre as linea,
		(select coalesce(sum(d.cantidad),0) from productos_traspasos_recepciones_detalles as d where d.idDetalle=a.idDetalle ) as recibidos
		from productos_traspasos_detalles as a
		inner join productos as b
		on a.idProducto=b.idProducto
		inner join productos_lineas as c
		on c.idLinea=b.idLinea
		where a.idTraspaso='$idTraspaso' ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerFolioTraspaso()
	{
		$sql=" select coalesce(max(folio),0) as folio from productos_traspasos where idLicencia='$this->idLicencia'";
		
		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function registrarTraspaso()
    {
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idLicenciaOrigen		= $this->idLicencia;
		$idLicenciaDestino		= $this->input->post('selectLicenciaDestino');
		
		$data=array
		(
			"idUsuario"			=> $this->idUsuario,
			"fechaRegistro"		=> $this->fecha,
			"fechaTraspaso"		=> $this->input->post('txtFechaTraspaso'),
			"comentarios"		=> $this->input->post('txtComentarios'),
			"idLicencia"		=> $this->idLicencia,
			"idLicenciaOrigen"	=> $idLicenciaOrigen,
			"idLicenciaDestino"	=> $idLicenciaDestino,
			"folio"				=> $this->obtenerFolioTraspaso(),
		);
		
		$this->db->insert("productos_traspasos",$data);
		$idTraspaso	= $this->db->insert_id();
		
		#---------------------------------------------------------------------------------------#
		
		$numeroProductos	= $this->input->post('numeroProductos');
		
		for($i=0;$i<=$numeroProductos;$i++)
		{
			$idProducto	= $this->input->post('txtIdProducto'.$i);	
			$cantidad	= $this->input->post('txtCantidadTraspaso'.$i);	
			
			if($idProducto>0)
			{
				$data=array
				(
					"idProducto"	=> $idProducto,
					"idTraspaso"	=> $idTraspaso,
					"cantidad"		=> $cantidad,
				);
				
				$this->db->insert("productos_traspasos_detalles",$data);
				
				$this->inventarioProductos->actualizarStockProducto($idProducto,$cantidad,'restar');
			}
		}

		$this->configuracion->registrarBitacora('Registrar traspaso de productos','Catálogo de productos','Sucursal origen: '.$this->configuracion->obtenerNombreSucursal($idLicenciaOrigen).', Sucursal destino: '.$this->configuracion->obtenerNombreSucursal($idLicenciaDestino)); //Registrar bitácora
		
		#---------------------------------------------------------------------------------------#
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}   
   	}
	
	public function obtenerFolioRecepcion()
	{
		$sql=" select coalesce(max(folio),0) as folio from productos_traspasos_recepciones where idLicencia='$this->idLicencia'";
		
		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function registrarRecepcion()
    {
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idTraspaso				= $this->input->post('txtIdTraspaso');
		$traspaso				= $this->obtenerTraspaso($idTraspaso);
		
		$data=array
		(
			"idUsuario"			=> $this->idUsuario,
			"fechaRegistro"		=> $this->fecha,
			"fechaRecepcion"	=> $this->input->post('txtFechaRecepcion'),
			"comentarios"		=> $this->input->post('txtComentarios'),
			"idLicencia"		=> $this->idLicencia,
			"idTraspaso"		=> $idTraspaso,
			"folio"				=> $this->obtenerFolioRecepcion(),
		);
		
		$this->db->insert("productos_traspasos_recepciones",$data);
		$idRecepcion			= $this->db->insert_id();
		
		#---------------------------------------------------------------------------------------#
		
		$numeroProductos	= $this->input->post('txtNumeroProductos');
		
		for($i=0;$i<=$numeroProductos;$i++)
		{
			$idProducto	= $this->input->post('txtIdProducto'.$i);	
			$idDetalle	= $this->input->post('txtIdDetalle'.$i);	
			$cantidad	= $this->input->post('txtCantidadRecibir'.$i);	
			
			if($idProducto>0 and $cantidad>0)
			{
				$data=array
				(
					"idRecepcion"	=> $idRecepcion,
					"idDetalle"		=> $idDetalle,
					"cantidad"		=> $cantidad,
				);
				
				$this->db->insert("productos_traspasos_recepciones_detalles",$data);
				
				$this->inventarioProductos->actualizarStockProductoSucursal($idProducto,$cantidad,'sumar',$traspaso->idLicenciaDestino);
			}
		}

		$this->configuracion->registrarBitacora('Registrar recepción de productos','Catálogo de productos','Folio: '.$traspaso->folio.', Sucursal origen: '.$this->configuracion->obtenerNombreSucursal($traspaso->idLicenciaOrigen).', Sucursal destino: '.$this->configuracion->obtenerNombreSucursal($traspaso->idLicenciaDestino)); //Registrar bitácora
		
		#---------------------------------------------------------------------------------------#
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}   
   	}
	
	public function borrarTraspaso($idTraspaso)
    {
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$traspaso				= $this->obtenerTraspaso($idTraspaso);
		$detalles				= $this->obtenerDetallesTraspaso($idTraspaso);

		$this->db->where("idTraspaso",$idTraspaso);
		$this->db->update("productos_traspasos",array('activo'=>'0'));

		foreach($detalles as $row)
		{
			//DEVOLVER EL INVENTARIO DESCONTADO
			$this->inventarioProductos->actualizarStockProducto($row->idProducto,$row->cantidad,'sumar');
			
			if($row->recibidos>0)
			{
				$this->inventarioProductos->actualizarStockProductoSucursal($row->idProducto,$row->recibidos,'restar',$traspaso->idLicenciaDestino);
			}
		}

		$this->configuracion->registrarBitacora('Borrar traspaso de productos','Catálogo de productos','Folio: '.$traspaso->folio.', Sucursal origen: '.$this->configuracion->obtenerNombreSucursal($traspaso->idLicenciaOrigen).', Sucursal destino: '.$this->configuracion->obtenerNombreSucursal($traspaso->idLicenciaDestino)); //Registrar bitácora
		
		#---------------------------------------------------------------------------------------#
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}   
   	}
	
	//REGISTRAR TRASPASOS A CLIENTES DE OTRAS SUCURSALES
	
	public function obtenerCotizacion($idCotizacion)
	{
		$sql=" select idCotizacion, folio, idLicencia, idSucursal
		from cotizaciones 
		where idCotizacion='$idCotizacion'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerProductosCotizacion($idCotizacion)
	{
		$sql=" select idProduct, cantidad
		from cotiza_productos 
		where idCotizacion='$idCotizacion'";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarTraspasoSucursal($idCotizacion=0)
    {
		$cotizacion	= $this->obtenerCotizacion($idCotizacion);
		$productos	= $this->obtenerProductosCotizacion($idCotizacion);
		
		$data=array
		(
			"idUsuario"			=> $this->idUsuario,
			"fechaRegistro"		=> $this->fecha,
			"fechaTraspaso"		=> $this->fecha,
			"comentarios"		=> 'Traspaso por venta '.$cotizacion->folio,
			"idLicencia"		=> $this->idLicencia,
			"idLicenciaOrigen"	=> $cotizacion->idLicencia,
			"idLicenciaDestino"	=> $cotizacion->idSucursal,
			"folio"				=> $this->obtenerFolioTraspaso(),
			"idCotizacion"		=> $idCotizacion,
		);
		
		$this->db->insert("productos_traspasos",$data);
		$idTraspaso	= $this->db->insert_id();
		
		#---------------------------------------------------------------------------------------#
		
		foreach($productos as $row)
		{
			$data=array
			(
				"idProducto"	=> $row->idProduct,
				"idTraspaso"	=> $idTraspaso,
				"cantidad"		=> $row->cantidad,
			);

			$this->db->insert("productos_traspasos_detalles",$data);

			#$this->inventarioProductos->actualizarStockProducto($idProducto,$cantidad,'restar');
		}

		$this->configuracion->registrarBitacora('Registrar traspaso de productos','Punto de venta','Sucursal origen: '.$this->configuracion->obtenerNombreSucursal($cotizacion->idLicencia).', Sucursal destino: '.$this->configuracion->obtenerNombreSucursal($cotizacion->idSucursal)); //Registrar bitácora
   	}
	
	public function obtenerTraspasoCotizacion($idCotizacion)
	{
		$sql=" select idTraspaso
		from productos_traspasos 
		where idCotizacion='$idCotizacion'";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarTraspasoVenta($idCotizacion)
    {
		$cotizacionTraspaso	= $this->obtenerTraspasoCotizacion($idCotizacion);
		
		if($cotizacionTraspaso!=null)
		{
			$idTraspaso				= $cotizacionTraspaso->idTraspaso;
			
			$traspaso				= $this->obtenerTraspaso($idTraspaso);
			
			if($traspaso!=null)
			{
				$detalles				= $this->obtenerDetallesTraspaso($idTraspaso);

				$this->db->where("idTraspaso",$idTraspaso);
				$this->db->update("productos_traspasos",array('activo'=>'0'));

				foreach($detalles as $row)
				{
					//DEVOLVER EL INVENTARIO DESCONTADO
					$this->inventarioProductos->actualizarStockProducto($row->idProducto,$row->cantidad,'sumar');

					if($row->recibidos>0)
					{
						$this->inventarioProductos->actualizarStockProductoSucursal($row->idProducto,$row->recibidos,'restar',$traspaso->idLicenciaDestino);
					}
				}

				$this->configuracion->registrarBitacora('Borrar traspaso de productos','Catálogo de productos','Folio: '.$traspaso->folio.', Sucursal origen: '.$this->configuracion->obtenerNombreSucursal($traspaso->idLicenciaOrigen).', Sucursal destino: '.$this->configuracion->obtenerNombreSucursal($traspaso->idLicenciaDestino)); //Registrar bitácora
			}
		}
   	}
}
