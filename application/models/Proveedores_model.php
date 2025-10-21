<?php
class Proveedores_model extends CI_Model
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
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->_user_name 		= $this->session->userdata('name');
   }
	
	public function comprobarProveedor($empresa)
	{
		$sql="select idProveedor
		from proveedores
		where empresa='$empresa'";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarProveedor()
	{
		if(!$this->comprobarProveedor($this->input->post('empresa')))
		{
			return array('0',registroDuplicado);
			exit;	
		}
		
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idCuentaCatalogo		= $this->input->post('idCuentaCatalogo');
		$saldoInicial			= $this->input->post('saldoInicial');
		$empresa				= $this->input->post('empresa');
		
		$data=array
		(
			'empresa'		=> $this->input->post('empresa'),
			'email'			=> $this->input->post('email'),
			'idUsuario'	 	=> $this->_user_id,
			'fecha'			=> $this->_fecha_actual,
			'activo'		=> 1,
			'domicilio'		=> $this->input->post('domicilio'),
			'rfc'			=> $this->input->post('rfc'),
			'estado'		=> $this->input->post('estado'),
			'pais'			=> $this->input->post('pais'),
			'website'		=> $this->input->post('pagina'),
			'idLicencia'	=> $this->idLicencia,
			'alias'			=> $this->input->post('alias'),
			'localidad'		=> $this->input->post('localidad'),
			'numero'		=> $this->input->post('numero'),
			'colonia'		=> $this->input->post('colonia'),
			'municipio'		=> $this->input->post('municipio'),
			'codigoPostal'	=> $this->input->post('codigoPostal'),
			'vende'			=> $this->input->post('vende'),
			'latitud'		=> $this->input->post('latitud'),
			'longitud'		=> $this->input->post('longitud'),
			'diasCredito'	=> $this->input->post('diasCredito'),
			
			'fax'			=> $this->input->post('fax'),
			'telefono'		=> $this->input->post('telefono'),
			'ladaFax'		=> $this->input->post('ladaFax'),
			'lada'			=> $this->input->post('lada'),
			#'idCuentaCatalogo'	=> $this->input->post('idCuentaCatalogo'),
			
			'idCuentaCatalogo'	=> $idCuentaCatalogo,
			'saldoInicial'		=> $saldoInicial,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('proveedores', $data);
		
		$idProveedor 		= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar proveedor','Proveedores',$data['empresa']); //Registrar bitácora
		
		$data=array
		(
			'idProveedor' 	=>$idProveedor,
			'nombre' 		=>strlen($this->input->post('nombreContacto'))>0?$this->input->post('nombreContacto'):$this->input->post('empresa'),
			'telefono' 		=>strlen($this->input->post('telefonoContacto'))>0?$this->input->post('telefonoContacto'):$this->input->post('telefono'),
			'email' 		=>strlen($this->input->post('emailContacto'))>0?$this->input->post('emailContacto'): $this->input->post('email'),
			'departamento'	=>$this->input->post('departamento'),
			'extension'		=>$this->input->post('extension'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert($this->_table['contactos_proveedores'], $data); 
		
		$banco	=$this->input->post('banco');
		
		if(strlen($banco)>0)
		{
			$data=array
			(
				'banco' 		=>$banco,
				'sucursal' 		=>$this->input->post('sucursal'),
				'cuenta' 		=>$this->input->post('cuenta'),
				'clabe' 		=>$this->input->post('clabe'),
				'fecha' 		=>$this->_fecha_actual,
				'idProveedor' 	=>$idProveedor,
			);
			
			$data	= procesarArreglo($data);
			$this->db->insert('proveedores_cuentas', $data); 
		}
		
		if($idCuentaCatalogo>0 and $saldoInicial>0)
		{
			$saldo	= $this->sumarSaldosProveedoresCuentas($idCuentaCatalogo);
			
			$this->db->where('idCuentaCatalogo', $idCuentaCatalogo); 
			$this->db->update('fac_catalogos_cuentas_detalles', array('saldo'=>$saldo)); 
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
			
			return array('1',registroCorrecto,$idProveedor,$empresa);
		}
	}
	
	public function sumarSaldosProveedoresCuentas($idCuentaCatalogo)
	{
		$sql=" select coalesce(sum(saldoInicial),0) as saldoInicial
		from proveedores
		where idCuentaCatalogo='$idCuentaCatalogo' ";
		
		return $this->db->query($sql)->row()->saldoInicial;
	}
	
	public function registrarCuenta()
	{
		$data=array
		(
			'banco' 		=> $this->input->post('banco'),
			'sucursal' 		=> $this->input->post('sucursal'),
			'cuenta' 		=> $this->input->post('cuenta'),
			'clabe' 		=> $this->input->post('clabe'),
			'idProveedor' 	=> $this->input->post('idProveedor')
		);
		
		$this->db->insert('proveedores_cuentas', $data); 
		
		return $this->db->affected_rows()==1?"1":"0";
	}
	
	public function editarCuenta()
	{
		$data=array
		(
			'banco' 		=> $this->input->post('banco'),
			'cuenta' 		=> $this->input->post('cuenta'),
			'sucursal' 		=> $this->input->post('sucursal'),
			'clabe' 		=> $this->input->post('clabe'),
		);
		
		$this->db->where('idCuenta', $this->input->post('idCuenta')); 
		$this->db->update('proveedores_cuentas', $data); 
		
		return $this->db->affected_rows()==1?"1":"0";
	}
	
	public function borrarCuenta($idCuenta)
	{
		$this->db->where('idCuenta', $idCuenta); 
		$this->db->delete('proveedores_cuentas', $data); 
		
		return $this->db->affected_rows()==1?"1":"0";
	}
	
	public function editarProveedor()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idCuentaCatalogo		= $this->input->post('idCuentaCatalogo');
		$saldoInicial			= $this->input->post('saldoInicial');
		
		$data=array
		(
			'empresa'			=> $this->input->post('empresa'),
			'email'				=> $this->input->post('email'),
			'domicilio'			=> $this->input->post('domicilio'),
			'rfc'				=> $this->input->post('rfc'),
			'estado'			=> $this->input->post('estado'),
			'pais'				=> $this->input->post('pais'),
			'website'			=> $this->input->post('pagina'), 
			'idUsuarioEdicion' 	=> $this->_user_id,
			'fechaEdicion'		=> $this->_fecha_actual,
			'alias'				=> $this->input->post('alias'),
			'localidad'			=> $this->input->post('localidad'),
			'numero'			=> $this->input->post('numero'),
			'colonia'			=> $this->input->post('colonia'),
			'municipio'			=> $this->input->post('municipio'),
			'codigoPostal'		=> $this->input->post('codigoPostal'),
			'vende'				=> $this->input->post('vende'),
			'latitud'			=> $this->input->post('latitud'),
			'longitud'			=> $this->input->post('longitud'),
			'diasCredito'		=> $this->input->post('diasCredito'),
			
			'fax'				=> $this->input->post('fax'),
			'telefono'			=> $this->input->post('telefono'),
			'ladaFax'			=> $this->input->post('ladaFax'),
			'lada'				=> $this->input->post('lada'),
			'idCuentaCatalogo'	=> $this->input->post('idCuentaCatalogo'),
			
			'idCuentaCatalogo'	=> $idCuentaCatalogo,
			'saldoInicial'		=> $saldoInicial,
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idProveedor',$this->input->post('idProveedor'));
		$this->db->update('proveedores',$data);
		
		$this->configuracion->registrarBitacora('Editar proveedor','Proveedores',$data['empresa']); //Registrar bitácora
		
		if($idCuentaCatalogo>0 and $saldoInicial>0)
		{
			$saldo	= $this->sumarSaldosProveedoresCuentas($idCuentaCatalogo);
			
			$this->db->where('idCuentaCatalogo', $idCuentaCatalogo); 
			$this->db->update('fac_catalogos_cuentas_detalles', array('saldo'=>$saldo)); 
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
	
	public function obtenerContactoProveedorBorrar($idContacto)
	{
		$sql="select a.nombre as contacto, b.empresa
		from contactos_proveedores as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where a.idContacto='$idContacto' ";
		
		$contacto	=$this->db->query($sql)->row(); 
		
		return $contacto!=null?array($contacto->contacto,$contacto->empresa):array('No se ha encontrado el registro','Sin detalles');
	}

	public function borrarContacto($idContacto)
	{
		$this->db->where('idContacto',$idContacto);
		#$this->db->delete('contactos_proveedores');
		$this->db->update('contactos_proveedores',array('activo'=>'0'));
		
		$contacto	= $this->obtenerContactoProveedorBorrar($idContacto);
		$this->configuracion->registrarBitacora('Borrar contacto','Proveedores - Contactos',$contacto[0].', Proveedor: '.$contacto[1]); //Registrar bitácora
		
		return $this->db->affected_rows()==1?"1":"0";
	}
	
	public function comprobarContactoProveedor($nombre)
	{
		$sql="select idContacto 
		from contactos_proveedores
		where nombre='$nombre'
		and activo='1' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarContactoProveedor()
	{
		if(!$this->comprobarContactoProveedor(trim($this->input->post('nombre'))))
		{
			return array('0',registroDuplicado);
			exit;
		}
		
		$data=array
		(
			'idProveedor'	=> trim($this->input->post('idProveedor')),
			'nombre'		=> trim($this->input->post('nombre')),
			'telefono'		=> trim($this->input->post('telefono')),
			'email'			=> trim($this->input->post('email')),
			'departamento'	=> trim($this->input->post('departamento')),
			'extension'		=> trim($this->input->post('extension')),
		);

		$data	= procesarArreglo($data);
		$this->db->insert("contactos_proveedores", $data);
		
		$this->configuracion->registrarBitacora('Registrar contacto','Proveedores - Contactos',$data['nombre'].', Proveedor: '.$this->obtenerProveedorNombre($this->input->post('idProveedor'))); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	#OBTENER CONTACTO
	public function obtenerContacto($idContacto)
	{
		$sql="select * from contactos_proveedores
		where idContacto='$idContacto'";
		
		return $this->db->query($sql)->row();
	}
	
	#EDITAR CONTACTO
	public function editarContacto()
	{
		$data=array
		(
			'nombre'		=>$this->input->post('nombre'),
			'telefono'		=>$this->input->post('telefono'),
			'email'			=>$this->input->post('email'),
			'departamento'	=>$this->input->post('departamento'),
			'extension'		=>$this->input->post('extension'),
		);	
		
		$data	= procesarArreglo($data);
		$this->db->where('idContacto',$this->input->post('idContacto'));
		$this->db->update('contactos_proveedores', $data);
		
		$this->configuracion->registrarBitacora('Editar contacto','Proveedores - Contactos',$data['nombre'].', Proveedor: '.$this->obtenerProveedorNombre($this->input->post('idProveedor'))); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}

	public function verificarProveedor($idProveedor)
	{
		$sql="select * from rel_material_proveedor
		where idProveedor='$idProveedor'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select idProveedor from compras
		where idProveedor='$idProveedor'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select idProveedor from rel_producto_proveedor
		where idProveedor='$idProveedor'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
	}
	
	public function borrarProveedor($idProveedor)
	{
		/*if($this->verificarProveedor($idProveedor)>0)
		{
			return "0";
		}*/
		
		$this->db->where('idProveedor',$idProveedor);
		#$this->db->delete('proveedores');
		$this->db->update('proveedores',array('activo'=>'0'));
		
		$this->configuracion->registrarBitacora('Borrar proveedor','Proveedores',$this->obtenerProveedorNombre($idProveedor)); //Registrar bitácora
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}
	
	public function obtenerProveedorNombre($idProveedor)
	{
		$sql="select empresa from proveedores 
		where idProveedor='$idProveedor' ";
		
		$proveedor	= $this->db->query($sql)->row();
		
		return $proveedor!=null?$proveedor->empresa:'';
	}
	
	public function obtenerProveedorCuentaCatalogo($idProveedor)
	{
		$sql="select idCuentaCatalogo from proveedores 
		where idProveedor='$idProveedor' ";
		
		$proveedor	= $this->db->query($sql)->row();
		
		return $proveedor!=null?$proveedor->idCuentaCatalogo:0;
	}
	
	public function obtenerProveedores()
	{
		$sql="select * from proveedores 
		where activo='1'
		and idLicencia='$this->idLicencia'
		order by empresa asc";
		
		return $this->db->query($sql)->result();
	}
	
	
	public function obtenerProveedor($idProveedor)
	{
		$sql="select * from proveedores 
		where idProveedor='".$idProveedor."' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerProveedorRegistro($idProveedor)
	{
		$sql="select idProveedor, email, empresa
		from proveedores 
		where idProveedor='".$idProveedor."'
		and idLicencia='$this->idLicencia'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerContactos($idProveedor)
	{
		$sql="select * from contactos_proveedores 
		where idProveedor='".$idProveedor."'
		and activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCuentas($idProveedor)
	{
		$sql="select * from proveedores_cuentas
		where idProveedor='".$idProveedor."'  ";
		
		return $this->db->query($sql)->result();
	}
	
	public function contarProveedores($idProveedor=0)
	{
		#$idProveedor=$this->session->userdata('idProveedorBusqueda');
		
		$sql="select a.*, b.*
		from proveedores as a 
		inner join usuarios as b
		on a.idUsuario=b.idUsuario
		where a.activo='1' ";
		
		$sql.=$idProveedor>0?" and  a.idProveedor='$idProveedor'":'';
		
		return $this->db->query($sql)->num_rows();
	}

	public function seleccionarProveedores($numero,$limite,$idProveedor=0)
	{
		$criterio=" order by a.empresa asc ";
		
		if($this->session->userdata('criterioProveedores')=="z")
		{
			$criterio=" order by a.empresa desc ";
		}
		
		$sql="select a.*, b.nombre
		from proveedores as a 
		inner join usuarios as b
		on a.idUsuario=b.idUsuario
		where a.activo='1' ";
		
		$sql.=$idProveedor>0?" and  a.idProveedor='$idProveedor'":'';
		
	    $sql.=$criterio;
		$sql .= " limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerDatosProveedor($idProveedor)
	{
		$sql="select a.*,
		(select concat(b.descripcion,'(',b.numeroCuenta,')') from fac_catalogos_cuentas_detalles as b where b.idCuentaCatalogo=a.idCuentaCatalogo) cuenta 
		from proveedores as a
		where a.idProveedor='$idProveedor'
		and a.idLicencia='$this->idLicencia'";
		
		return $this->db->query($sql)->row();
	}
	
	public function contarCompras($idProveedor)
	{
		$fecha		=$this->session->userdata('fechaCompras');
		
		$query ="select a.idCompras, a.idProveedor,
		a.total, a.fechaCompra, b.empresa
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor)
		where a.idLicencia='$this->idLicencia '
		and b.idProveedor='$idProveedor' 
		and reventa='0' ";
					
					
		if($fecha!="")
		{
			$query.=" and date(a.fechaCompra)='$fecha'";
		}
		
		return $this->db->query($query)->num_rows();
	}
	
	public function obtenerCompras($Num,$Limite,$idProveedor)
	{
		$fecha		=$this->session->userdata('fechaCompras');
		
		$sql ="select a.idCompras, a.idProveedor, a.nombre,
		a.total, a.fechaCompra, b.empresa
		from compras as a
		inner join proveedores as b 
		on(a.idProveedor=b.idProveedor)
		where a.idLicencia='$this->idLicencia'
		and b.idProveedor='$idProveedor' 
		and reventa='0' ";
					
		if($fecha!="")
		{
			$sql.=" and date(a.fechaCompra)='$fecha' ";
		}
		
		$sql.=" order by a.idCompras desc ";
		
		$sql .= " limit $Limite,$Num ";
		
		$query = $this->db->query($sql);
		
		#echo $sql;
		
		return ($query->num_rows() > 0)? $query->result_array() : NULL;
	}
	

	//SUMAR TODO LO QUE SE HA PAGADO
	public function sumarPagadoProveedorCompras($idProveedor)
	{
		$sql=" select coalesce(sum(a.pago),0) as pago
		from catalogos_egresos as a
		inner join compras as b
		on a.idCompra=b.idCompras
		where b.idProveedor='$idProveedor'
		and a.idForma!='4' ";
		
		return $this->db->query($sql)->row()->pago;
	}
	
	public function sumarPagadoProveedor($idProveedor)
	{
		$sql=" select coalesce(sum(pago),0) as pago
		from catalogos_egresos
		where idProveedor='$idProveedor'
		and idForma!='4' 
		and idCompra=0  ";
		
		return $this->db->query($sql)->row()->pago;
	}
	
	//SUMAR TODO LO QUE SE DEBE
	
	public function sumarComprasProveedor($idProveedor)
	{
		$sql=" select coalesce(sum(total),0) as total
		from compras 
		where idProveedor='$idProveedor'
		and cancelada='0' ";
		
		return $this->db->query($sql)->row()->total; //Total que se ha comprado
	}
	
	public function obtenerDiasCredito($idProveedor)
	{
		$sql="select diasCredito
		from proveedores
		where idProveedor='$idProveedor'";
		
		return $this->db->query($sql)->row()->diasCredito;
	}
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	//CRM PARA PROVEEDORES
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	public function contarSeguimientoProveedor($idProveedor,$inicio,$fin)
	{
		$sql=" select a.idSeguimiento
		from proveedores_seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		where a.idProveedor='$idProveedor'
		and a.idLicencia='$this->idLicencia'
		and tipo=0 ";	
		
		$sql.=" and a.fecha between '$inicio' and '$fin' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerSeguimientoProveedor($numero,$limite,$idProveedor,$inicio,$fin)
	{
		$sql=" select a.*, b.nombre as servicio,
		concat(c.nombre,' ', c.apellidoPaterno,' ',c.apellidoMaterno) as responsable,
		d.nombre as status, d.idStatusIgual, d.color,
		(select g.nombre from compras as g where g.idCompras=a.idCompra) as compra
		from proveedores_seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		inner join seguimiento_status as d
		on a.idStatus=d.idStatus
		where a.idProveedor='$idProveedor'
		and a.idLicencia='$this->idLicencia' ";	
		
		$sql.=" and a.fecha between '$inicio' and '$fin' ";
		
		$sql .= " order by fecha desc
		limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarSeguimiento()
	{
		$fecha			= trim($this->input->post('fecha'));
		$idStatus		= $this->input->post('idStatus');
		$comentarios	= $this->input->post('comentarios');
		$idResponsable	= $this->input->post('idResponsable');
		$idStatusIgual	= $this->input->post('idStatusIgual');
		$folio			= $this->crm->obtenerFolioSeguimientoProveedor();
		
		$data=array
		(
			'comentarios'		=> $comentarios,
			'bitacora' 			=> $this->input->post('bitacora'),
			'email' 			=> $this->input->post('email'),
			'fecha'				=> $fecha,
			'idProveedor' 		=> $this->input->post('idProveedor'),
			'idStatus' 			=> $idStatus,
			'idServicio' 		=> $this->input->post('idServicio'),
			'idResponsable' 	=> $idResponsable,
			'fechaCierre' 		=> trim($this->input->post('fechaCierre')),
			'lugar'				=> $this->input->post('lugar'),
			'tipo' 				=> 0,
			'idLicencia'		=> $this->idLicencia,
			'idTiempo'			=> $this->input->post('idTiempo'),
			'idContacto'		=> $this->input->post('idContacto'),
			'idCompra'			=> $this->input->post('idCompra'),
			'folio' 			=> $folio,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('proveedores_seguimiento', $data);
		
		$this->configuracion->registrarBitacora('Registrar seguimiento','Proveedores - Seguimiento',$this->obtenerProveedorNombre($this->input->post('idProveedor')).', Folio: '.obtenerFolioSeguimiento($folio).', '.$comentarios); //Registrar bitácora
		
		if($idStatusIgual==4)
		{
			$this->enviarCorreoLlamada($idResponsable,$data['comentarios'],$fecha);
		}
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function enviarCorreoLlamada($idUsuario,$comentarios,$fecha)
	{
		if(!empty($_POST))
		{
			$usuario			=$this->obtenerUsuario($idUsuario);
			$proveedor			=$this->obtenerProveedor($this->input->post('idProveedor'));
			$emisor				=$this->obtenerUsuario($this->_user_id);
			
			$remitente		=$emisor->correo;
			$destinatario	=$usuario->correo;
			#$destinatario	='programador03.redisoft@gmail.com';
			$asunto			='Llamada: '.$proveedor->empresa;
			
			$mensaje		=' <strong>Responsable: </strong>'.$usuario->nombre.'<br />';
			$mensaje		.='<strong>Fecha: </strong>'.obtenerFechaMesCortoHora($fecha).'<br />';
			$mensaje		.='<strong>Comentarios: </strong> '.$comentarios.'<br />';
			$mensaje		.='<strong>Proveedor: </strong> '.$proveedor->empresa;
			
			$this->load->library('email');
			$this->email->from($remitente,$emisor->nombre);
			$this->email->to($destinatario);
			$this->email->subject($asunto);
			$this->email->message($mensaje);
			
			if (!$this->email->send())
			{
				#print("0");
			}
			else
			{
				#print("1");
			}
				
		}
		else
		{
			#print("2");
		}
	} 
	
	public function obtenerSeguimiento($idSeguimiento)
	{
		$sql=" select a.*, b.empresa,
		d.nombre as status, d.color, d.idStatusIgual,
		concat(e.nombre,' ',e.apellidoPaterno,' ',e.apellidoMaterno) as responsable,
		f.nombre as contacto, f.telefono,
		(select g.nombre from seguimiento_tiempos as g where g.idTiempo=a.idTiempo) as tiempo,
		(select g.nombre from compras as g where g.idCompras=a.idCompra) as compra,
		(select g.nombre from seguimiento_servicios as g where g.idServicio=a.idServicio) as servicio
		from proveedores_seguimiento as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
	
		inner join seguimiento_status as d
		on a.idStatus=d.idStatus
		inner join usuarios as e
		on a.idResponsable=e.idUsuario
		inner join contactos_proveedores as f
		on f.idProveedor=b.idProveedor
		where a.idSeguimiento='$idSeguimiento' ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerUltimoSeguimiento($idProveedor)
	{
		$sql=" select a.*, b.nombre as servicio,
		d.nombre as status, d.color,
		concat(c.nombre,' ',c.apellidoPaterno,' ', c.apellidoMaterno) as responsable
		from proveedores_seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		inner join seguimiento_status as d
		on d.idStatus=a.idStatus
		where a.idProveedor='$idProveedor'
		and a.idLicencia='$this->idLicencia'
		order by a.fecha desc
		limit 1 ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerDetalleSeguimiento($idSeguimiento)
	{
		$sql=" select idProveedor, folio
		from proveedores_seguimiento
		where idSeguimiento='$idSeguimiento' ";	
		
		$seguimiento	= $this->db->query($sql)->row();
		
		return $seguimiento!=null?array($seguimiento->idProveedor,$seguimiento->folio):array('0','');
	}
	
	public function editarSeguimientoCrm()
	{
		$seguimiento		= $this->obtenerDetalleSeguimiento($this->input->post('idSeguimiento'));
		
		$data=array
		(
			'comentarios'		=> $this->input->post('comentarios'),
			'comentariosExtra'	=> $this->input->post('observaciones'),
			'fecha'				=> trim($this->input->post('fecha')),
			'idStatus' 			=> $this->input->post('idStatus'),
			'idServicio' 		=> $this->input->post('idServicio'),
			'idResponsable' 	=> $this->input->post('idResponsable'),
			'lugar'				=> $this->input->post('lugar'),
			'fechaCierre' 		=> trim($this->input->post('fechaCierre')),
			'bitacora' 			=> $this->input->post('bitacora'),
			'email' 			=> $this->input->post('email'),
			'idTiempo'			=> $this->input->post('idTiempo'),
			'idContacto'		=> $this->input->post('idContacto'),
			'idCompra'			=> $this->input->post('idCompra'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idSeguimiento', $this->input->post('idSeguimiento'));
		$this->db->update('proveedores_seguimiento', $data);
		
		$this->configuracion->registrarBitacora('Editar seguimiento','Proveedores - Seguimiento',$this->obtenerProveedorNombre($seguimiento[0]).', Folio: '.obtenerFolioSeguimiento($seguimiento[1]).', '.$this->input->post('comentarios')); //Registrar bitácora
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}

	public function obtenerUsuario($idUsuario)
	{
		$sql="select idUsuario, concat(nombre,' ',apellidoPaterno,' ',apellidoMaterno) as nombre, correo
		from usuarios 
		where idUsuario='$idUsuario' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerSeguimientoBorrado($idSeguimiento)
	{
		$sql=" select a.comentarios, b.empresa, a.folio
		from proveedores_seguimiento as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where a.idSeguimiento='$idSeguimiento' ";
		
		$seguimiento	= $this->db->query($sql)->row();
		
		return $seguimiento!=null?array($seguimiento->empresa,$seguimiento->comentarios,$seguimiento->folio):array('No se encontro el registro','Sin detalles','');
	}
	
	public function borrarSeguimiento($idSeguimiento)
	{
		$seguimiento	= $this->obtenerSeguimientoBorrado($idSeguimiento);
		
		$this->db->where('idSeguimiento',$idSeguimiento);
		$this->db->delete('proveedores_seguimiento');

		$this->configuracion->registrarBitacora('Borrar seguimiento','Proveedores - Seguimiento',$seguimiento[0].', Folio: '.obtenerFolioSeguimiento($seguimiento[2]).', '.$seguimiento[1]); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	//ARCHIVOS PARA SEGUIMIENTO
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	public function obtenerArchivosSeguimiento($idSeguimiento)
	{
		$sql="select * from proveedores_seguimiento_archivos
		where idSeguimiento='$idSeguimiento'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerArchivoSeguimiento($idArchivo)
	{
		$sql="select * from proveedores_seguimiento_archivos
		where idArchivo='$idArchivo'";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarArchivoSeguimiento($idArchivo)
	{
		$archivo	= $this->obtenerArchivoSeguimiento($idArchivo);
		
		$this->db->where('idArchivo',$idArchivo);
		$this->db->delete('proveedores_seguimiento_archivos');
		
		if($this->db->affected_rows()>=1)
		{
			$this->configuracion->registrarBitacora('Borrar archivo','Proveedores - Seguimiento',$archivo->nombre); //Registrar bitácora
			
			if(file_exists(carpetaSeguimientoProveedores.$archivo->idArchivo.'_'.$archivo->nombre))
			{
				unlink(carpetaSeguimientoProveedores.$archivo->idArchivo.'_'.$archivo->nombre);
			}
			
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	public function subirArchivosSeguimiento($idSeguimiento,$nombre,$tamano)
	{
		$data=array
		(
			'idSeguimiento'	=>$idSeguimiento,
			'nombre'		=>$nombre,
			'tamano'		=>$tamano,
			'fecha'			=>$this->_fecha_actual,
			'idUsuario'		=>$this->_user_id,
		);
		
		$this->db->insert('proveedores_seguimiento_archivos',$data);
		$idArchivo=$this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar archivo','Proveedores - Seguimiento',$nombre); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?$idArchivo:0;
	}
	
	//ADMINISTRACIÓN LOS SEGUIMIENTOS
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	public function contarSeguimientos($criterio,$inicio,$fin,$idStatus,$idServicio)
	{
		$sql=" select a.idSeguimiento
		from proveedores_seguimiento as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		inner join contactos_proveedores as c
		on a.idContacto=c.idContacto
		inner join usuarios as d
		on a.idResponsable=d.idUsuario
		where date(a.fecha) between '$inicio' and '$fin'
		and a.idLicencia='$this->idLicencia'
		and (a.comentarios like '%$criterio%'
		or b.empresa like '%$criterio%'
		or d.nombre like '%$criterio%' ) ";
		
		$sql.=$idStatus>0?" and a.idStatus='$idStatus' ":'';
		$sql.=$idServicio>0?" and a.idServicio='$idServicio' ":'';

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerSeguimientos($numero,$limite,$criterio,$inicio,$fin,$idStatus,$idServicio)
	{
		$sql=" select a.comentarios, a.fecha, a.idSeguimiento, a.folio,
		b.empresa, c.nombre as contacto, a.fechaCierre, a.lugar, 
		c.telefono, concat(d.nombre,' ',d.apellidoPaterno,' ',d.apellidoMaterno) as responsable,
		e.nombre as status, e.idStatusIgual, e.color,
		(select f.nombre from seguimiento_servicios as f where f.idServicio=a.idServicio) as servicio,
		(select f.nombre from compras as f where f.idCompras=a.idCompra) as compra,
		c.email
		from proveedores_seguimiento as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		inner join contactos_proveedores as c
		on b.idProveedor=c.idProveedor
		inner join usuarios as d
		on a.idResponsable=d.idUsuario
		
		inner join seguimiento_status as e
		on a.idStatus=e.idStatus
		
		where date(a.fecha) between '$inicio' and '$fin'
		and a.idLicencia='$this->idLicencia'
		and (a.comentarios like '%$criterio%'
		or b.empresa like '%$criterio%'
		or d.nombre like '%$criterio%' )
		and a.idContacto=c.idContacto ";
		
		$sql.=$idStatus>0?" and a.idStatus='$idStatus' ":'';
		$sql.=$idServicio>0?" and a.idServicio='$idServicio' ":'';
			
		$sql.=" order by a.fecha desc ";
		$sql.=" limit $limite, $numero";
		
		return $this->db->query($sql)->result();
	}
	
	//NOTIFICACIÓN DE SEGUIMIENTO DE  PROVEEDORES
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	public function obtenerListaResponsables($fecha)
	{
		$sql=" select a.idResponsable,
		b.correo
		from proveedores_seguimiento as a
		inner join usuarios as b
		on a.idResponsable=b.idUsuario
		where a.idStatus!=3
		and a.idLicencia='$this->idLicencia'
		and a.idTiempo=0
		and (date(fecha)='$fecha' 
		or date(fechaCierre)='$fecha') 
		group by idResponsable ";
		
		#echo $sql;
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerSeguimientoResponsables($fecha,$idResponsable)
	{
		$sql=" select a.comentarios, a.fecha, a.lugar,
		b.empresa, c.nombre as servicio, a.fechaCierre,
		d.nombre as status, a.idStatus
		from proveedores_seguimiento as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		inner join seguimiento_servicios as c
		on a.idServicio=c.idServicio
		inner join seguimiento_status as d
		on d.idStatus=a.idStatus
		where a.idResponsable='$idResponsable'
		and a.idLicencia='$this->idLicencia'
		and a.idStatus!=3
		and a.idTiempo=0
		and (date(a.fecha)='$fecha' 
		or date(a.fechaCierre)='$fecha') ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerSeguimientosTiempo()
	{
		$sql=" select a.*, b.empresa, c.nombre as status,
		d.nombre as servicio, e.tiempo, f.correo
		from proveedores_seguimiento as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		inner join seguimiento_status as c
		on a.idStatus=c.idStatus
		inner join seguimiento_servicios as d
		on a.idServicio=d.idServicio
		inner join seguimiento_tiempos as e
		on a.idTiempo=e.idTiempo
		inner join usuarios as f
		on f.idUsuario=a.idResponsable
		where a.idStatus!=3
		and a.idLicencia='$this->idLicencia'
		and a.idTiempo>0
		and a.fechaCierre>=curdate()
		and substr(concat(curdate(),' ',curtime()),1,16) = (select substr(date_sub(a.fechaCierre, interval e.tiempo minute),1,16)) ";
		
		return $this->db->query($sql)->result();
	}
	
	
	#FICHEROS
	public function obtenerFicheros($idProveedor)
	{
		$sql="select * from proveedores_ficheros
		where idProveedor='$idProveedor'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFichero($idFichero)
	{
		$sql="select * from proveedores_ficheros
		where idFichero='$idFichero'";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarFichero($idFichero)
	{
		$fichero	=$this->obtenerFichero($idFichero);
		
		$this->db->where('idFichero',$idFichero);
		$this->db->delete('proveedores_ficheros');
		
		if($this->db->affected_rows()>=1)
		{
			$this->configuracion->registrarBitacora('Borrar archivo','Proveedores',$fichero->nombre); //Registrar bitácora
			
			if(file_exists(carpetaProveedores.$fichero->idFichero.'_'.$fichero->nombre))
			{
				unlink(carpetaProveedores.$fichero->idFichero.'_'.$fichero->nombre);
			}
			
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	public function subirFicheros($idProveedor,$nombre,$tamano)
	{
		$data=array
		(
			'idProveedor'	=> $idProveedor,
			'nombre'		=> $nombre,
			'tamano'		=> $tamano,
			'fecha'			=> $this->_fecha_actual,
		);
		
		#$data	= procesarArreglo($data);
		$this->db->insert('proveedores_ficheros',$data);
		$idFichero=$this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Subir fichero','Proveedores',$nombre); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?$idFichero:0;
	}
	
	
	//ASIGNAR PROVEEDOR A PRODUCTOS
	
	public function obtenerProductosProveedor($idProveedor)
	{
		$sql=" select a.idProducto, precioA, precioB, precioC
		from rel_producto_proveedor as a
		inner join productos as b
		on a.idProducto=b.idProducto
		where a.idProveedor='$idProveedor'
		and b.activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPorcentajesProveedor($idProveedor)
	{
		$sql=" select a.*
		from productos_porcentaje as a
		where a.idProveedor='$idProveedor'
		order by a.fecha desc limit 5 ";
		
		return $this->db->query($sql)->result();
	}
	
	public function asignarPorcentajes()
	{
		$this->db->trans_start();
		
		$porcentaje1			= $this->input->post('txtPorcentaje1');
		$porcentaje2			= $this->input->post('txtPorcentaje2');
		$porcentaje3			= $this->input->post('txtPorcentaje3');
		$idProveedor			= $this->input->post('txtIdProveedorAsignar');
		
		$productos 				= $this->obtenerProductosProveedor($idProveedor);
		
		if(count($productos)>0)
		{
			foreach($productos as $row)
			{
				$data=array();

				if($porcentaje1>0)
				{
					$data['precioA']	= $row->precioA+($row->precioA*($porcentaje1/100));
				}

				if($porcentaje2>0)
				{
					$data['precioB']	= $row->precioB+($row->precioB*($porcentaje2/100));
				}

				if($porcentaje3>0)
				{
					$data['precioC']	= $row->precioC+($row->precioC*($porcentaje3/100));
				}

				$this->db->where('idProducto', $row->idProducto);
				$this->db->update('productos', $data);
			}

			$data=array
			(
				'idProveedor' 	=> $idProveedor,
				'porcentaje1' 	=> $porcentaje1,
				'porcentaje2' 	=> $porcentaje2,
				'porcentaje3' 	=> $porcentaje3,
				'idUsuario' 	=> $this->_user_id,
				'fecha' 		=> $this->_fecha_actual,
			);

			$this->db->insert('productos_porcentaje', $data);
		}
		
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
			
			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return array('1',registroCorrecto);
		}
	}
	
	//MARCAS PROVEEDORES
	public function obtenerMarcasProveedor($idProveedor)
	{
		$sql=" select a.idMarca, a.nombre, b.idRelacion
		from productos_marcas  as a
		inner join productos_proveedores_marcas b
		on a.idMarca=b.idMarca
		where a.activo='1'
		and b.idProveedor=$idProveedor ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarMarcaProveedor()
	{
		$data=array
		(
			'idProveedor'	=> $this->input->post('txtIdProveedor'),
			'idMarca'		=> $this->input->post('txtIdMarca'),
		);
		
		$this->db->insert('productos_proveedores_marcas',$data);

		#$this->configuracion->registrarBitacora('Relacionar marca','Proveedores'); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function borrarMarcaProveedor()
	{
		$this->db->where('idRelacion',$this->input->post('idRelacion'));
		$this->db->delete('productos_proveedores_marcas');

		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function borrarInventarioProveedor()
	{
		$this->db->trans_start(); 
		
		$idProveedor	= $this->input->post('idProveedor');
		
		$sql=" select a.idProducto, a.idInventario, a.idLicencia, a.stock
		from productos_inventarios as a
		inner join rel_producto_proveedor as b
		on b.idProducto=a.idProducto
		where a.stock>0
		and a.idLicencia='$this->idLicencia'
		and b.idProveedor='$idProveedor' ";
		
		$productos	= $this->db->query($sql)->result();
		
		if($productos!=null)
		{
			foreach($productos as $row)
			{
				$this->db->where('idInventario',$row->idInventario);
				$this->db->update('productos_inventarios',array('stock'=>0));
				
				$this->db->insert('productos_inventarios_movimientos',array('fecha'=>$this->_fecha_actual,'cantidad'=>$row->stock,'idProducto'=>$row->idProducto,'idLicencia'=>$this->idLicencia,'movimiento'=>'Salida','inventarioAnterior'=>$row->stock,'inventarioActual'=>0));
			}
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
			
			return array('1','Registro');
		}   
	}
	
}
?>