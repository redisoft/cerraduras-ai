<?php
class Inventario_model extends CI_Model
{
    protected $_fecha_actual;
    protected $_table;
    protected $_user_id;
    protected $_user_name;
	protected $idLicencia;

	function __construct()
	{
		parent::__construct();
		
		$this->config->load('datatables',TRUE);
		$this->_table 			= $this->config->item('datatables');
		$this->_fecha_actual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_user_id 		= $this->session->userdata('id');
		$this->_user_name 		= $this->session->userdata('name');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
	}

	public function actualizar_material($id)
	{
		$precioa	=$this->input->post('T5');
		$pa			=str_replace(',','',$precioa);
		
		$data=array
		(
			'nombre'		=>$this->input->post('T1'),
			'unidad'		=>$this->input->post('unidad'),
			'costo'			=>$this->input->post('T5'),
			'clave'			=>$this->input->post('T2'),
			'idProveedor'	=>$this->input->post('proveedor_0'),
		);
		
		$this->db->where('idMaterial', $id);
		$this->db->update("produccion_materiales", $data);
		
		return ($this->db->affected_rows() == 1)? TRUE : NULL;
	}

	public function getAll()
	{
		$this->db->where('block !=', 1);
		$query = $this->db->get($this->_table['productos']);
	
	  	return ($query->num_rows() > 0)? $query->result_array() : NULL;
	}

	public function delproveedor($id_prod,$id_prov)
	{
		$borra = $this->db->where('id_prod', $id_prod);
		$borra = $this->db->where('id_prov', $id_prov);
		
		$borra = $this->db->delete('proveedores_deproductos');
		
		return $borra;
	}
	
	public function getAllProveedor($id)
	{
		$SQL="SELECT * FROM ".$this->_table['productos']." WHERE id='".$id."' AND block!=1";
		$query=$this->db->query($SQL);

		return ($query->num_rows() > 0)? $query->row_array() : NULL;
	}

	public function Proveedor($id)
	{
		$sql="select p.id, q.empresa, q.nombre,q.tels,q.email, 
		q.domicilio,r.id,r.id_prod 
		from productos as p, 
		proveedores as q, 
		proveedores_deproductos as r 
		where p.id = ".$id." 
		and q.id=r.id_prov 
		and p.id=r.id_prod";
		
		$query=$this->db->query($sql);
		
		return ($query->num_rows() > 0)? $query->result_array() : NULL;
	}

	public function Proveedores()
	{
		$sql="select * from proveedores  
		where idLicencia='$this->idLicencia' 
		and activo='1' 
		order by empresa asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerProveedores()
	{
		$sql="select * from proveedores  
		where idLicencia='$this->idLicencia' 
		order by empresa asc";
		
		return $this->db->query($sql)->result();
	}

	
	public function getSerie($Name,$Idc)
	{
		$query=$this->db->query("select * from usuarios where username = '$Name'");
		
		foreach($query->result() as $su)
		{
			$name=$su->name;
			$paterno=$su->paterno;
			$materno=$su->materno;
		}
		
		$iniciales=ucwords(substr($name,0,1)).ucwords(substr($paterno,0,1)).ucwords(substr($materno,0,1));
		$Serie="";
		
		if($this->getMaxSerie()!=NULL)
		{
			foreach ($this->getMaxSerie() as $S)
			{
				if ($S["serie"]!=NULL)
				{												
					//$Cad=explode("-",$S["serie"]);								
					//$Serie=$iniciales.date("dmY").'-'.$this->numeracion($Cad[1]+1);
				}//IF
				else
				{
					$Serie=$iniciales.date("dmY").'-001';          
				}
			}
		}
	
		return $Serie;
	}//Fin de Generar Serie

	public function getMaxSerie()
	{
		$this->db->select_max('serie');
		$query = $this->db->get($this->_table['cotizaciones']);
		
		return ($query->num_rows()==1) ? $query->result_array() : NULL;
	}

	public function numeracion($Suma)
	{
		$Rest="";
		
		switch (strlen($Suma))
		{
			case "1": $Rest="00".$Suma;
			break;
			case "2": $Rest="0".$Suma;
			break;
			case "3": $Rest=$Suma;
			break;
			case "4": $Rest="00".$Suma;
			break;
		}
		
		return($Rest);
	}

	public function coutproducto()
	{
		$cad=$this->input->post('TB');
		$sql="select *from productos";
		
		$sql.=" where descripcion like '%$cad%'";
		
		$query=$this->db->query($sql);

		return($query->num_rows>0) ? $query->num_rows : 0;
	}

	public function get_AllPag($Num,$Limite)
	{
		$cad=$this->input->post('TB');

		$sql="select *from productos";
		$sql.=" where descripcion like '%$cad%'";
		$sql .= " limit $Limite,$Num ";
		
		$query=$this->db->query($sql);

		return ($query->num_rows() > 0)? $query->result_array() : NULL;
	}

	public function obtenerProductosCotizacion($idCotizacion)
	{
		$sql="select a.*, b.*
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto
		where a.idCotizacion='$idCotizacion'";

		$query=$this->db->query($sql);
		
		return $query->result();
	}

	public function getDatosProductosCotizaCout($idCotizacion)
	{
		$sql="select a.*, b.*
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto
		where a.idCotizacion='$idCotizacion'";
	
		$query=$this->db->query($sql);
		
		return $var = ($query->num_rows()> 0) ?  $query->num_rows() : NULL;
	}

	public function obtenerFolio()
	{
		$sql="select max(folio) as folio
		from cotizaciones";
		
		$folio=$this->db->query($sql)->row()->folio;
		
		return $folio!=null?$folio+1:1;
	}
	#---------------------NUEVA COTIZACION---------------------#
	
	public function agregarCotizacion()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
			
		$fecha		=$this->input->post('txtFechaCotizacion');
		$sql		="select date_add('".$fecha."',interval ".$this->input->post('txtDias')." day) as fechaFin";
		$fechaFin	=$this->db->query($sql)->row()->fechaFin;

		$data = array
		(
			"serie" 			=> $this->input->post('id_serie'),
			"idUsuario" 		=> $this->_user_id,
			"fecha" 			=> $this->input->post('txtFechaCotizacion'),
			"fechaPedido" 		=> $this->input->post('txtFechaEntrega'),
			"fechaEntrega" 		=> $this->input->post('txtFechaEntrega'),
			"idCliente" 		=> $this->input->post('id_cli'),
			"estatus" 			=> 0,
			"diasCredito"		=> $this->input->post('txtDias'),
			"fechaVencimiento"	=> $fechaFin,
			"descuento" 		=> $this->input->post('TDesc'),
			"subtotal" 			=> $this->input->post('TSubTotal'),
			"iva" 				=> $this->input->post('TIVA'),
			"total" 			=> $this->input->post('TTotal'),//Venta
			"comentarios" 		=> $this->input->post('txtComentarios'),
			"idLicencia" 		=> $this->idLicencia,
			"folio" 			=> $this->obtenerFolio() 
		);
		
		$this->db->insert($this->_table['cotizaciones'],$data);
		
		$idCotizacion	=$this->db->insert_id();
		
		$this->session->set_userdata('idCotizacionRemision',$idCotizacion);
		
		for($i=0; $i<=$this->input->post('contador');$i++)
		{
			$Idp=trim($this->input->post('id_p_'.$i));
			
			if(!empty($Idp))
			{
				if($this->input->post('txtServicio'.$i)==1)
				{
					$factor	=$this->input->post('txtFactor'.$i);
					$valor	=$this->input->post('txtValor'.$i)*$this->input->post('id_canti_'.$i); #VALOR ES EL NUMERO DE DIAS, AÑOS, MESES
					
					$sql="select date_add('".$this->input->post('txtFechaInicio'.$i)."',interval ".$valor." $factor) as fechaFin";
					$fechaFin=$this->db->query($sql)->row()->fechaFin;
					
					$Data['fechaInicio']		=$this->input->post('txtFechaInicio'.$i);
					$Data['fechaVencimiento']	=$fechaFin;
					$Data['servicio']			=1;
				}
		
				$Data['cantidad']		=$this->input->post('id_canti_'.$i);
				$Data['precio']			=$this->input->post('id_precioNormal_'.$i);
				$Data['importe']		=$this->input->post('id_precio_t_'.$i);
				$Data['idCotizacion']	=$idCotizacion;
				$Data['tipo']			=$this->input->post('id_tpo_'.$i);
				$Data['idProduct']		=$Idp;
				$Data["idLicencia"]		=$this->idLicencia;
				
				
				$this->db->insert($this->_table['cotiza_productos'],$Data);
			}
		}
		
		$data=array
		(
			'prospecto'	=> 0
		);
		
		$this->db->where('idCliente',$this->input->post('id_cli'));
		$this->db->update('clientes',$data);
		
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
	
	public function obtenerProducto($idProducto)
	{
		$sql="select * from productos
		where idProducto='$idProducto'";
		
		return $this->db->query($sql)->row();
	}
	
	#---------------------EDITAR COTIZACION---------------------#
	
	public function editarCotizacion()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idCotizacion	=$this->input->post('idCotizacion');
		
		#-----------------------------------------------------------------#
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->delete('cotiza_productos');
		#-----------------------------------------------------------------#

		$fecha			=$this->input->post('txtFechaCotizacion');
		
		$sql="select date_add('".$fecha."',interval ".$this->input->post('txtDias')." day) as fechaFin";
		
		$fechaFin	=$this->db->query($sql)->row()->fechaFin;

		$data = array
		(
			"fecha" 			=> $this->input->post('txtFechaCotizacion'),
			"fechaPedido" 		=> $this->input->post('txtFechaCotizacion'),
			"fechaEntrega" 		=> $this->input->post('txtFechaEntrega'),
			"idCliente" 		=> $this->input->post('id_cli'),
			"estatus" 			=> 0,
			"diasCredito"		=> $this->input->post('txtDias'),
			"fechaVencimiento"	=> $fechaFin,
			"descuento" 		=> $this->input->post('TDesc'),
			"subtotal" 			=> $this->input->post('TSubTotal'),
			"iva" 				=> $this->input->post('TIVA'),
			"total" 			=> $this->input->post('TTotal'),
			"comentarios" 		=> $this->input->post('txtComentarios'),
		);
		

		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->update($this->_table['cotizaciones'],$data);
		
		$this->session->set_userdata('idCotizacionRemision',$idCotizacion);
		
		for($i=0; $i<=$this->input->post('contador');$i++)
		{
			$Idp=trim($this->input->post('id_p_'.$i));
			
			if(!empty($Idp))
			{
				if($this->input->post('txtServicio'.$i)==1)
				{
					$factor	=$this->input->post('txtFactor'.$i);
					$valor	=$this->input->post('txtValor'.$i)*$this->input->post('id_canti_'.$i); #VALOR ES EL NUMERO DE DIAS, AÑOS, MESES
					
					$sql="select date_add('".$this->input->post('txtFechaInicio'.$i)."',interval ".$valor." $factor) as fechaFin";
					$fechaFin					=$this->db->query($sql)->row()->fechaFin;
					
					$Data['fechaInicio']		=$this->input->post('txtFechaInicio'.$i);
					$Data['fechaVencimiento']	=$fechaFin;
					$Data['servicio']			=1;
				}
				
				$Data['cantidad']		=$this->input->post('id_canti_'.$i);
				$Data['precio']			=$this->input->post('id_tpo_'.$i); #Problemas con la cotización
				$Data['importe']		=$this->input->post('id_precio_t_'.$i);
				$Data['idCotizacion']	=$idCotizacion;
				$Data['tipo']			=$this->input->post('id_tpo_'.$i);
				$Data['idProduct']		=$Idp;
				$Data["idLicencia"]		=$this->idLicencia;
				
				
				$this->db->insert($this->_table['cotiza_productos'],$Data);
			}
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

	public function AddNoOrdenTrabajo($No,$Idct)
	{
		$data=array
		(
			"ordenCompra"	=>$No,
			"fechaCompra"	=>date("Y-m-d H:m:s"),
			"estatus" 		=>1
		);
		
		#$datos = $this->input->xss_clean($Data);
		$this->db->update($this->_table['cotizaciones'], $data, array('idCotizacion' => $Idct));
		
		$Bandera= (($this->db->affected_rows() == 1)? TRUE : NULL);
		
		return $Bandera;		  
	}


	public function obtenerId()
	{
		$sql="select coalesce(max(folio),0) as idCotizacion
		from cotizaciones ";

		return $this->db->query($sql)->row()->idCotizacion+1;
	}
	
	public function obtenerProductos()#Todos los productos para ordenes de produccion
	{
		$sql="select * from productos
		where idLicencia='$this->idLicencia'
		limit 20";
		
		return $this->db->query($sql)->result();
	}
}
?>
