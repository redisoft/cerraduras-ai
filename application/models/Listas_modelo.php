<?php
class Listas_modelo extends CI_Model
{
    protected $fecha;
 	protected $fechaCorta;
    protected $idUsuario;
	protected $idLicencia;
	protected $idTienda;

    function __construct()
	{
		parent::__construct();
		
		$this->config->load('datatables',TRUE);
		
		$this->fecha 			= date('Y-m-d H:i:s');
		$this->fechaCorta 		= date('Y-m-d');
		$this->idUsuario 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->idTienda 		= $this->session->userdata('idTiendaActiva');
   }
   
  
  	public function contarListas($criterio,$inicio,$fin)
	{
		$sql =" select a.idLista 
		from productos_listas as a  
		where a.activo='1'
		and a.idLicencia='$this->idLicencia' 
		and a.fechaInicial between '$inicio' and '$fin' ";
		
		$sql.= strlen($criterio)>0?" and a.nombre like '$criterio%' ":' ';
		
		return $this->db->query($sql)->num_rows();
	}

	public function obtenerListas($numero,$limite,$criterio,$inicio,$fin)
	{
		$sql =" select a.* 
		from productos_listas as a  
		where a.activo='1'
		and a.idLicencia='$this->idLicencia' 
		and a.fechaInicial between '$inicio' and '$fin' ";
		
		$sql.= strlen($criterio)>0?" and a.nombre like '$criterio%' ":' ';

		$sql.=" order by a.fechaInicial desc, a.nombre asc ";
		$sql .= " limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerLista($idLista)
	{
		$sql =" select a.* 
		from productos_listas as a  
		where a.activo='1'
		and a.idLista='$idLista'  ";

		return $this->db->query($sql)->row();
	}
	
	public function comprobarLista($nombre,$inicio,$fin)
	{
		$sql="select idLista 
		from productos_listas
		where nombre='$nombre'
		and fechaInicial='$inicio'
		and fechaFinal='$fin'
		and activo='1'
		and idLicencia='$this->idLicencia'  ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarLista()
	{
		if(!$this->comprobarLista($this->input->post('txtNombreLista'),$this->input->post('txtFechaInicialRegistro'),$this->input->post('txtFechaFinalRegistro')))
		{
			return array('0',registroDuplicado);
		}
		
		$this->db->trans_start(); 

		#--------------------------------------------------------------------------------------------#
		$data=array
		(
		   	'nombre'			=> $this->input->post('txtNombreLista'),
		   	'fechaRegistro'		=> $this->fecha,
		   	'idLicencia'		=> $this->idLicencia,
		   	'idUsuario'			=> $this->idUsuario,
		   	'fechaInicial'		=> $this->input->post('txtFechaInicialRegistro'),
		   	'fechaFinal'		=> $this->input->post('txtFechaFinalRegistro'),
			'vigencia'			=> $this->input->post('chkVigencia')=='1'?'1':'0',
		);

		$data	= procesarArreglo($data);
		$this->db->insert('productos_listas', $data);
		$idLista	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar lista','Catálogo de productos - Listas de precios',$this->input->post('txtNombreLista')); //Registrar bitácora

		$this->registrarDetallesLista($idLista);
		
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
			
			return array('1','El registro ha sido exitoso');
		}   
	}
	
	public function registrarDetallesLista($idLista)
	{
		$numeroProductos		= $this->input->post('txtNumeroProductosLista');
		$descuentoPorcentaje	= $this->input->post('txtPorcentajeDescuento');
		
		for($i=0;$i<$numeroProductos;$i++)
		{
			$idProducto	= $this->input->post('txtIdProducto'.$i);
			
			if($idProducto>0)
			{
				$precio						= $this->input->post('txtPrecioProducto'.$i);
				$descuento					= ($precio*$descuentoPorcentaje)/100;

				$descuento					= round($descuento,2);
				$precioNuevo				=  $precio-$descuento;

				if($precioNuevo>0)
				{
					$data=array
					(
						'idLista'				=> $idLista,
						'idProducto'			=> $this->input->post('txtIdProducto'.$i),
						'precioPasado'			=> $this->input->post('txtPrecioProducto'.$i),
						#'precioNuevo'			=> $this->input->post('txtPrecioNuevo'.$i),
						'precioNuevo'			=> $precioNuevo,
					);

					$this->db->insert('productos_listas_detalles', $data);

					$this->db->where('idProducto',$idProducto);
					$this->db->where('idLicencia',$this->idLicencia);
					$this->db->update('productos_inventarios',['precioA'=>$precioNuevo]);
				}
			}
		}
	}
	
	public function editarLista()
	{
		/*if(!$this->comprobarLista($this->input->post('txtNombreLista'),$this->input->post('txtFechaInicialRegistro'),$this->input->post('txtFechaFinalRegistro')))
		{
			return array('0',registroDuplicado);
		}*/
		
		$this->db->trans_start(); 
		
		$idLista	= $this->input->post('txtIdLista');
		
		#--------------------------------------------------------------------------------------------#
		$data=array
		(
		   	'nombre'			=> $this->input->post('txtNombreLista'),
		   	'fechaRegistro'		=> $this->fecha,
		   	'idLicencia'		=> $this->idLicencia,
		   	'idUsuario'			=> $this->idUsuario,
		   	'fechaInicial'		=> $this->input->post('txtFechaInicialRegistro'),
		   	'fechaFinal'		=> $this->input->post('txtFechaFinalRegistro'),
			'vigencia'			=> $this->input->post('chkVigencia')=='1'?'1':'0',
		);

		$data	= procesarArreglo($data);
		$this->db->where('idLista', $idLista);
		$this->db->update('productos_listas', $data);
		
		$this->configuracion->registrarBitacora('Editar lista','Catálogo de productos - Listas de precios',$this->input->post('txtNombreLista')); //Registrar bitácora
		
		$this->db->where('idLista', $idLista);
		$this->db->delete('productos_listas_detalles');
		
		$this->registrarDetallesLista($idLista);
		
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
			
			return array('1','El registro ha sido exitoso');
		}   
	}
	
	public function contarProductosLista($criterio)
	{
		$sql =" select a.idProducto 
		from productos as a 
		inner join productos_inventarios as b
		on b.idProducto=a.idProducto 
		where a.activo='1'
		and b.idLicencia='$this->idLicencia' 
		and materiaPrima='0'
		and (a.nombre like '%$criterio%' 
		or a.codigoInterno like '%$criterio%'
		or a.upc like '%$criterio%'
		or a.sku like '%$criterio%' ) ";

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerProductosLista($numero,$limite,$criterio)
	{
		$sql =" select a.idProducto, a.nombre, a.imagen,  a.precioImpuestos,
		c.precioA, c.precioB, c.precioC, a.reventa, a.codigoBarras, a.codigoInterno,
		b.nombre as linea, a.upc
		from productos as a
		inner join productos_lineas as b
		on a.idLinea=b.idLinea 
		inner join productos_inventarios as c
		on c.idProducto=a.idProducto 
		where a.activo='1'
		and c.idLicencia='$this->idLicencia' 
		and a.materiaPrima='0'
		and (a.nombre like '%$criterio%' 
		or a.codigoInterno like '%$criterio%'
		or a.upc like '%$criterio%'
		or a.sku like '%$criterio%' )
		order by  a.nombre asc ";
		
		$sql .= " limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function borrarLista($idLista=0)
	{
		$this->db->trans_start(); 

		$this->db->where('idLista', $idLista);
		$this->db->delete('productos_listas_detalles');
		
		$this->db->where('idLista', $idLista);
		$this->db->delete('productos_listas');
	
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
			
			return array('1',$idLista);
		}   
	}
	
	public function autorizarLista($idLista=0)
	{
		$this->db->trans_start(); 

		$this->db->where('idLista', $idLista);
		$this->db->update('productos_listas', array('autorizada'=>'1'));
		
		$lista	= $this->obtenerLista($idLista);
		
		if($lista->fechaInicial==$this->fechaCorta)
		{
			$this->aplicarPreciosLista($idLista);
		}
	
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
			
			return array('1',$idLista);
		}   
	}

	
	public function obtenerDetallesLista($idLista)
	{
		$sql =" select a.idDetalle, a.idProducto, a.precioPasado, a.precioNuevo,
		b.nombre as producto, b.codigoInterno, c.nombre as linea,
		d.tasa
		from productos_listas_detalles as a  
		inner join productos as b
		on a.idProducto=b.idProducto
		inner join productos_lineas as c
		on c.idLinea=b.idLinea
		
		inner join configuracion_impuestos as d
		on d.idImpuesto=b.idImpuesto
		
		where b.activo='1'
		and a.idLista = '$idLista' ";

		$sql.=" order by b.nombre asc, a.precioNuevo desc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerListasVigentes()
	{
		$sql =" select idLista
		from productos_listas 
		where autorizada='1'
		and fechaInicial='$this->fechaCorta' ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerListasPasadas()
	{
		$sql =" select idLista
		from productos_listas 
		where autorizada='1'
		and fechaFinal='$this->fechaCorta'
		and vigencia='1' ";

		return $this->db->query($sql)->result();
	}
	
	public function administrarListasVigentes()
	{
		$this->db->trans_start(); 
		
		$listas	= $this->obtenerListasVigentes();
		
		foreach($listas as $row)
		{
			$this->aplicarPreciosLista($row->idLista);
			
			$this->db->where('idLista', $row->idLista);
			$this->db->update('productos_listas', array('aplicada'=>'1'));
		}
		
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
	
	public function administrarListasPasadas()
	{
		$this->db->trans_start(); 
		
		$listas	= $this->obtenerListasPasadas();
		
		foreach($listas as $row)
		{
			$this->aplicarPreciosListaPasadas($row->idLista);
			
			$this->db->where('idLista', $row->idLista);
			$this->db->update('productos_listas', array('regresada'=>'1'));
		}
		
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
	
	public function aplicarPreciosLista($idLista)
	{
		$productos	= $this->obtenerDetallesLista($idLista);
		
		foreach($productos as $row)
		{
			$precioA	= $row->precioNuevo/(1+($row->tasa/100));
			$precioA	= round($precioA,decimales);
			
			$this->db->where('idProducto', $row->idProducto);
			$this->db->update('productos', array('precioImpuestos'=>$row->precioNuevo,'precioA'=>$precioA));
		}
	}
	
	//REGRESAR AL PRECIO ORIGINAL CUANDO TERMINE LA VIGENCIA
	public function aplicarPreciosListaPasadas($idLista)
	{
		$productos	= $this->obtenerDetallesLista($idLista);
		
		foreach($productos as $row)
		{
			$precioA	= $row->precioPasado/(1+($row->tasa/100));
			$precioA	= round($precioA,decimales);
			
			$this->db->where('idProducto', $row->idProducto);
			#$this->db->update('productos', array('precioA'=>$row->precioPasado));
			$this->db->update('productos', array('precioImpuestos'=>$row->precioPasado,'precioA'=>$precioA));
		}
	}
}
?>
