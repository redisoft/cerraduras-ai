<?php
class Modeloclientes extends CI_Model
{
	protected $_fecha_actual;
	protected $_table;
	protected $idLicencia;
	protected $resultado;
	protected $_user_id;
	protected $idRol;
	protected $fecha;
	protected $hora;
	protected $idEstacion;

	function __construct()
	{
		parent::__construct();
		$this->config->load('datatables',TRUE);
		$this->_table 			= $this->config->item('datatables');

        $this->_user_id 		= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->idRol 			= $this->session->userdata('role');

		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->_fecha_actual 	= mdate($datestring,now());
		$this->resultado		= "1";
		$this->fecha 			= date('Y-m-d');
		$this->hora 			= date('H:i:s');
		
		if($this->session->userdata('usuarioSesion'))
		{
			$this->idEstacion		= $this->session->userdata('idEstacion'); 			
		}
		else
		{
			$this->idEstacion		= get_cookie('idEstacion'.$this->session->userdata('idCookie')); 
		}
	}
	
	public function detalleProductosRemision($idCotizacion)
	{
		$sql="select a.cantidad, a.produccion, 
		a.idProducto as id, b.idProducto as idProducto,
		b.nombre as descripcion, c.serie, c.ordenCompra,
		a.precio, a.importe, b.unidad, a.servicio,
		a.descuento, a.descuentoPorcentaje
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto
		inner join cotizaciones as c
		on a.idCotizacion=c.idCotizacion
		where c.idCotizacion='$idCotizacion'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerZonas()
	{    
		$sql="select * from zonas 
		where idLicencia='$this->idLicencia'";
		
		$query=$this->db->query($sql);
		
		return ($query->num_rows()> 0)? $query->result_array() : NULL;
	}
	
	public function checarCliente($idCliente)
	{
		$sql="select idCliente
		from facturas
		where idCliente='$idCliente'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select idCliente
		from cotizaciones
		where idCliente='$idCliente'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select idCliente
		from seguimiento
		where idCliente='$idCliente'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
	}
	
	public function obtenerClienteEmpresa($idCliente)
	{
		$sql="select empresa
		from clientes
		where idCliente='$idCliente' ";
		
		$cliente=$this->db->query($sql)->row();
		
		return $cliente!=null?$cliente->empresa:'';
	}
	
	public function borrarCliente($idCliente)
	{
		/*if($this->checarCliente($idCliente)>0)
		{
			return "0";			
		}*/

		$this->db->where('idCliente',$idCliente);
		$this->db->update('clientes',array('activo'=>'0'));
		#$this->db->delete('clientes');
		
		$this->configuracion->registrarBitacora('Borrar cliente','Clientes',$this->obtenerClienteEmpresa($idCliente)); //Registrar bitácora
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}

	public function agregarProveedor()
	{
		$data=array
		(
			'empresa'		=>$this->input->post('empresa'),
			'email'			=>$this->input->post('email'),
			'telefono'		=>$this->input->post('telefono'),
			'idUsuario' 	=>$this->_user_id,
			'fecha'			=>$this->_fecha_actual,
			'domicilio'		=>$this->input->post('direccion'),
			'rfc'			=>$this->input->post('rfc'),
			'estado'		=>'México',
			'pais'			=>$this->input->post('pais'),
			'website'		=>$this->input->post('pagina'),
			'idLicencia'	=>$this->idLicencia
		);
		
		$this->db->insert('proveedores', $data);
		$idProveedor = $this->db->insert_id();
		
		$data=array
		(
			'idProveedor' 	=>$idProveedor,
			'nombre' 		=> $this->input->post('empresa'),
			'telefono' 		=> $this->input->post('telefono'),
			'email' 		=> $this->input->post('email'),
			//'fechaRegistro'	=> $this->_fecha_actual,
		);

		$this->db->insert('contactos_proveedores', $data); 
	}
	
	public function obtenerFuentesContacto()
	{
		$sql="select * from clientes_fuentes
		order by nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPadreCliente($idCliente=0)
	{
		$sql=" select a.* 
		from clientes_padres as a
		inner join clientes_padres_relacion as b
		on a.idPadre=b.idPadre
		where b.idCliente ='$idCliente' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarFuenteContacto()
	{
		$data=array
		(
			'nombre'	=>$this->input->post('nombre')
		);
		
		$this->db->insert('clientes_fuentes',$data);
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function comprobarRegistroCliente($empresa,$rfc)
	{
		$sql="select idCliente
		from clientes
		where empresa='$empresa'
		and rfc='$rfc'
		and activo='1' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function comprobarRegistroClienteIexe($telefono,$email)
	{
		$sql="select idCliente
		from clientes
		where telefono='$telefono'
		and email='$email' ";
		
		#and activo='1'
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function obtenerNumeroCliente()
	{
		$sql=" select coalesce(max(alias),0) as numeroCliente
		from clientes ";

		return $this->db->query($sql)->row()->numeroCliente+1;
	}

	public function registrarCliente()
	{
		if(!$this->comprobarRegistroCliente(reemplazarApostrofe($this->input->post('empresa')),reemplazarApostrofe($this->input->post('rfc'))))
		{
			return array('0',registroDuplicado);
			exit;
		}
		
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idCuentaCatalogo		= $this->input->post('txtIdCuentaCatalogo');
		$saldoInicial			= $this->input->post('txtSaldoInicial');
		
		$data=array
		(
			'empresa'			=> $this->input->post('empresa'),
			'precio'			=> $this->input->post('txtPrecioCliente'),	 
			'email'				=> $this->input->post('email'),
			'email2'			=> $this->input->post('email2'),
			'email3'			=> $this->input->post('email3'),
			'email4'			=> $this->input->post('email4'),
			'email5'			=> $this->input->post('email5'),

			'telefono'			=> $this->input->post('telefono'),
			'lada'				=> $this->input->post('txtLada'),
			'idUsuario' 		=> $this->_user_id,
			'fechaRegistro'		=> $this->_fecha_actual,
			'calle'				=> $this->input->post('direccion'),
			'numero'			=> $this->input->post('numero'),
			'localidad'			=> $this->input->post('localidad'),
			'rfc'				=> $this->input->post('rfc'),
			'codigoPostal'		=> trim($this->input->post('codigoPostal')),
			'colonia'			=> trim($this->input->post('colonia')),
			'municipio'			=> trim($this->input->post('txtMunicipio')),
			'estado'			=> trim($this->input->post('estado')),
			'fax'				=> $this->input->post('fax'),
			'ladaFax'			=> $this->input->post('txtLadaFax'),
			'pais'				=> trim($this->input->post('txtPais')),
			'faxEnvio'			=> '0',
			'web'				=> $this->input->post('pagina'),
			'web2'				=> $this->input->post('pagina2'),
			'web3'				=> $this->input->post('pagina3'),
			'idLicencia'		=> $this->idLicencia,
			'idZona'			=> $this->input->post('selectZonas'),
			'prospecto'			=> $this->input->post('selectRegistro'),
			'nombreVendedor'	=> $this->input->post('nombreVendedor'),
			'limiteCredito'		=> $this->input->post('limiteCredito'),
			'plazos'			=> $this->input->post('plazos'),
			'grupo'				=> $this->input->post('txtGrupo'),
			'alias'				=> $this->input->post('txtAlias'),
			'competencia'		=> $this->input->post('chkCompetencia')=='1'?'1':'0',
			'serviciosProductos'=> $this->input->post('txtServiciosProductos'),
			'idFuente'			=> $this->input->post('selectFuente'),
			'latitud'			=> $this->input->post('txtLatitud'),
			'longitud'			=> $this->input->post('txtLongitud'),
			'comentarios'		=> $this->input->post('txtComentariosCliente'),
			'ladaMovil'			=> $this->input->post('txtLadaMovilCliente'),
			'movil'				=> $this->input->post('txtMovilCliente'),
			'razonSocial'		=> $this->input->post('txtRazonSocial'),
			'idMetodo'			=> $this->input->post('selectMetodoPagoCliente'),
			'formaPago'			=> $this->input->post('txtFormaPagoCliente'),
			
			'idCuentaCatalogo'	=> $idCuentaCatalogo,
			'saldoInicial'		=> $saldoInicial,

			'direccionEnvio'	=> $this->input->post('txtCalleEnvio'),
			'numeroEnvio'		=> $this->input->post('txtNumeroEnvio'),
			'coloniaEnvio'		=> $this->input->post('txtColoniaEnvio'),
			'localidadEnvio'	=> $this->input->post('txtLocalidadEnvio'),
			'municipioEnvio'	=> $this->input->post('txtMunicipioEnvio'),
			'estadoEnvio'		=> $this->input->post('txtEstadoEnvio'),
			'paisEnvio'			=> $this->input->post('txtPaisEnvio'),
			'codigoPostalEnvio'	=> $this->input->post('txtCodigoPostalEnvio'),
			
			#'idSucursal'		=> $this->input->post('selectSucursal'),
		);
		
		
		$data	= procesarArreglo($data);
		
		$this->db->insert('clientes', $data);
		$idCliente = $this->db->insert_id();
		$this->session->set_userdata('idClienteBusqueda',$idCliente);
		$this->configuracion->registrarBitacora('Registrar cliente','Clientes',$data['empresa']); //Registrar bitácora
		
		$data=array
		(
			'idCliente' 	=> $idCliente,
			'nombre' 		=> strlen($this->input->post('txtNombreContacto'))>1?$this->input->post('txtNombreContacto'):$this->input->post('empresa'),
			'email' 		=> strlen($this->input->post('txtEmailContacto'))>1?$this->input->post('txtEmailContacto'):$this->input->post('email'),
			'telefono' 		=> strlen($this->input->post('txtTelefonoContacto'))>1?$this->input->post('txtTelefonoContacto'):$this->input->post('telefono'),
			'direccion'		=> $this->input->post('txtDepartamento'),
			'extension' 	=> $this->input->post('txtExtension'),
			
			'puesto'		=> $this->input->post('txtPuesto'),
			'lada'			=> $this->input->post('txtLadaTelefonoContacto'),
			'ladaMovil1'	=> $this->input->post('txtLadaMovil'),
			'movil1'		=> $this->input->post('txtMovil'),
			'ladaMovil2'	=> $this->input->post('txtLadaMovil2'),
			'movil2'		=> $this->input->post('txtMovil2'),
			'ladaNextel'	=> $this->input->post('txtLadaNextel'),
			'nextel'		=> $this->input->post('txtNextel'),
			
			'fechaRegistro' => date('Y-m-d-h:i:s'),
			'idUsuario' 	=> $this->_user_id,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('clientes_contactos', $data); 
		$idContacto	= $this->db->insert_id();
		
		$this->registrarCuentaCliente($idCliente); //Registrar cuenta cliente

		if($idCuentaCatalogo>0 and $saldoInicial>0)
		{
			$saldo	= $this->sumarSaldosClientesCuentas($idCuentaCatalogo);
			
			$this->db->where('idCuentaCatalogo', $idCuentaCatalogo); 
			$this->db->update('fac_catalogos_cuentas_detalles', array('saldo'=>$saldo)); 
		}
		
		$this->registrarDireccionFiscal($idCliente); //REGISTRAR LA DIRECCIÓN FISCAL
		
		//COMENTADO 27 ABRIL 2021
		/*if($this->input->post('selectSucursal')>0 and $this->comprobarClienteSucursal())
		{
			$this->registrarClienteSucursal($idCliente);
		}*/

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
			
			return array('1',$idCliente);
		}
	}
	
	public function registrarClienteSucursal($idCliente)
	{
		$data=array
		(
			'idCliente'				=> $idCliente,
			'idLicenciaTraspaso'	=> $this->idLicencia,
			'idSucursal'			=> $this->input->post('selectSucursal'),
		);
		
		$this->db->insert('clientes_sucursales', $data);
		
	}
	
	public function registrarDireccionFiscal($idCliente)
	{
		$data=array
		(
			'rfc'				=> $this->input->post('rfc'),
			'razonSocial'		=> $this->input->post('txtRazonSocial'),
			'calle'				=> $this->input->post('direccion'),
			'numero'			=> $this->input->post('numero'),
			'colonia'			=> trim($this->input->post('colonia')),
			'localidad'			=> $this->input->post('localidad'),			
			'municipio'			=> trim($this->input->post('txtMunicipio')),
			'estado'			=> trim($this->input->post('estado')),
			'pais'				=> trim($this->input->post('txtPais')),
			'codigoPostal'		=> trim($this->input->post('codigoPostal')),
			'telefono'			=> $this->input->post('telefono'),
			'email'				=> $this->input->post('email'),
			'idRegimen'			=> $this->input->post('selectRegimenFiscal'),
			'tipo'				=> 2,
			'idCliente'			=> $idCliente,
		);
		
		$this->db->insert('clientes_direcciones', $data);
		
	}
	
	public function editarPadreCliente($idPadre)
	{
		$data=array
		(
			'nombrePadre'			=> $this->input->post('txtNombrePadre'),
			'apellidoPaternoPadre'	=> $this->input->post('txtApellidoPaternoPadre'),
			'apellidoMaternoPadre'	=> $this->input->post('txtApellidoPaternoMadre'),
			'fechaNacimientoPadre'	=> strlen( $this->input->post('txtFechaNacimientoPadre'))>0? $this->input->post('txtFechaNacimientoPadre'):null,
			'telefonoPadre'			=> $this->input->post('txtTelefonoPadre'),
			'celularPadre'			=> $this->input->post('txtCelularPadre'),
			'emailPadre'			=> $this->input->post('txtEmailPadre'),
			'ocupacionPadre'		=> $this->input->post('txtOcupacionPadre'),
			'nombreMadre'			=> $this->input->post('txtNombreMadre'),
			'apellidoPaternoMadre'	=> $this->input->post('txtApellidoPaternoMadre'),
			'apellidoMaternoMadre'	=> $this->input->post('txtApellidoMaternoMadre'),
			'fechaNacimientoMadre'	=> strlen( $this->input->post('txtFechaNacimientoMadre'))>0? $this->input->post('txtFechaNacimientoMadre'):null,
			'telefonoMadre'			=> $this->input->post('txtTelefonoMadre'),
			'celularMadre'			=> $this->input->post('txtCelularMadre'),
			'emailMadre'			=> $this->input->post('txtEmailMadre'),
			'ocupacionMadre'		=> $this->input->post('txtOcupacionMadre'),
		);
		
		$this->db->where('idPadre', $idPadre);
		$this->db->update('clientes_padres', $data);
	}
	
	public function registrarDireccionesEntrega($idCliente)
	{
		for($i=1;$i<=5;$i++)
		{			
			$data=array
			(
				'idCliente' 	=> $idCliente,
				'calle'			=> $this->input->post('txtCalleEntrega'.$i),
				'numero'		=> $this->input->post('txtNumeroEntrega'.$i),
				'colonia'		=> $this->input->post('txtColoniaEntrega'.$i),
				'codigoPostal'	=> $this->input->post('txtCodigoPostalEntrega'.$i),
				'ciudad'		=> $this->input->post('txtLocalidadEntrega'.$i),
				'estado'		=> $this->input->post('txtEstadoEntrega'.$i),
				'referencia'	=> $this->input->post('txtReferenciaEntrega'.$i),
			);
			
			$this->db->insert('clientes_direcciones', $data);
		}
	}
	
	public function sumarSaldosClientesCuentas($idCuentaCatalogo)
	{
		$sql=" select coalesce(sum(saldoInicial),0) as saldoInicial
		from clientes
		where idCuentaCatalogo='$idCuentaCatalogo' ";
		
		return $this->db->query($sql)->row()->saldoInicial;
	}
	
	public function registrarCuentaCliente($idCliente)
	{
		$idBanco	= $this->input->post('txtIdBanco');
		
		if($idBanco>0)
		{			
			$data=array
			(
				'idBanco' 		=> $idBanco,
				'idCliente' 	=> $idCliente,
				'cuenta'		=> $this->input->post('txtCuenta'),
				'clabe'			=> $this->input->post('txtClabe'),
				'idEmisor' 		=> $this->input->post('selectEmisores'),
				'entrada'		=> 0,
				'modificado' 	=> date('Y-m-d-H:i:s'),
			);
			
			$this->db->insert('cuentas', $data);
		}
	}
	
	/*public function registrarCuentaCliente($idCliente)
	{
		$banco	=$this->input->post('banco');
		
		if(strlen($banco)>0)
		{
			$data=array
			(
				'idCliente' 	=>$idCliente,
				'nombre'		=>$this->input->post('banco'),
				'modificado' 	=>date('Y-m-d-H:i:s'),
			);
			
			$this->db->insert('bancos', $data);
			$idBanco = $this->db->insert_id();
			
			$data=array
			(
				'idBanco' 		=>$idBanco,
				'cuenta'		=>$this->input->post('cuenta'),
				'clabe'			=>$this->input->post('clabe'),
				'entrada'		=>0,
				'modificado' 	=>date('Y-m-d-H:i:s'),
			);
			
			$this->db->insert('cuentas', $data);
		}
	}*/
	
	public function editarCliente()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idCuentaCatalogo		= $this->input->post('txtIdCuentaCatalogo');
		$saldoInicial			= $this->input->post('txtSaldoInicial');
		$idCliente				= $this->input->post('txtClienteId');
			
		$data=array
		(
			'empresa'			=> $this->input->post('empresa'),
			'precio'			=> $this->input->post('txtPrecioClienteEditar'),	
			'email'				=> $this->input->post('email'),
			'email2'			=> $this->input->post('email2'),
			'email3'			=> $this->input->post('email3'),
			'email4'			=> $this->input->post('email4'),
			'email5'			=> $this->input->post('email5'),
			'telefono'			=> $this->input->post('telefono'),
			'lada'				=> $this->input->post('txtLada'),
			'ladaMovil'			=> $this->input->post('txtLadaMovilCliente'),
			'movil'				=> $this->input->post('txtMovilCliente'),
			'fax'				=> $this->input->post('fax'),
			'ladaFax'			=> $this->input->post('txtLadaFax'),
			
			'razonSocial'		=> $this->input->post('txtRazonSocial'),
			'rfc'				=> $this->input->post('rfc'),
			'idMetodo'			=> $this->input->post('selectMetodoPagoCliente'),
			'formaPago'			=> $this->input->post('txtFormaPagoCliente'),
			'municipio'			=> $this->input->post('txtMunicipio'),
			'numero'			=> $this->input->post('numero'),
			'calle'				=> $this->input->post('direccion'),
			'codigoPostal'		=> $this->input->post('codigoPostal'),
			'localidad'			=> $this->input->post('localidad'),
			'colonia'			=> $this->input->post('colonia'),
			'estado'			=> $this->input->post('estado'),
			'pais'				=> $this->input->post('txtPais'),
			
			
			#'telefono'			=>'0',
			'faxEnvio'			=>'0',
			
			'web'				=> $this->input->post('pagina'),
			'web2'				=> $this->input->post('pagina2'),
			'web3'				=> $this->input->post('pagina3'),
			
			'idZona'			=> $this->input->post('selectZonas'),
			'nombreVendedor'	=> $this->input->post('nombreVendedor'),
			'limiteCredito'		=> $this->input->post('txtLimiteCreditoCliente'),
			'plazos'			=> $this->input->post('plazos'),
			
			'prospecto'			=> $this->input->post('selectTipoProspecto'),
			'grupo'				=> $this->input->post('txtGrupo'),
			'alias'				=> trim($this->input->post('txtAlias')),
			'competencia'		=> $this->input->post('chkCompetencia')=='1'?'1':'0',
			'serviciosProductos'=> $this->input->post('txtServiciosProductos'),
			'idFuente'			=> $this->input->post('selectFuente'),
			'idUsuario'			=> $this->input->post('selectResponsableCliente'),
			'latitud'			=> $this->input->post('txtLatitud'),
			'longitud'			=> $this->input->post('txtLongitud'),
			'comentarios'		=> $this->input->post('txtComentariosCliente'),
			
			
			
			'idUsuarioEdicion' 	=> $this->_user_id,
			'fechaEdicion'		=> $this->_fecha_actual,
			
			'idCuentaCatalogo'	=> $idCuentaCatalogo,
			'saldoInicial'		=> $saldoInicial,
			
			
			/*'direccionEnvio'	=> $this->input->post('direccionEnvio'),
			'codigoPostalEnvio'	=> $this->input->post('codigoPostalEnvio'),
			'localidadEnvio'	=> $this->input->post('ciudadEnvio'),
			'estadoEnvio'		=> $this->input->post('estadoEnvio'),
			'paisEnvio'			=> 'México',*/
			
			
			'direccionEnvio'	=> $this->input->post('txtCalleEnvio'),
			'numeroEnvio'		=> $this->input->post('txtNumeroEnvio'),
			'coloniaEnvio'		=> $this->input->post('txtColoniaEnvio'),
			'localidadEnvio'	=> $this->input->post('txtLocalidadEnvio'),
			'municipioEnvio'	=> $this->input->post('txtMunicipioEnvio'),
			'estadoEnvio'		=> $this->input->post('txtEstadoEnvio'),
			'paisEnvio'			=> $this->input->post('txtPaisEnvio'),
			'codigoPostalEnvio'	=> $this->input->post('txtCodigoPostalEnvio'),
		);
		
		/*if($this->input->post('selectSucursal')>0)
		{
			if($this->input->post('txtIdLicenciaTraspaso')==$this->idLicencia or $this->input->post('txtIdLicenciaTraspaso')==0)
			{
				$data['idSucursal']				= $this->input->post('selectSucursal');
				$data['idLicenciaTraspaso']		= $this->idLicencia;
			}
			
		}
		else
		{
			$data['idSucursal']				= 0;
			$data['idLicenciaTraspaso']		= 0;
		}*/
		
		
		$data	= procesarArreglo($data);
		$this->db->where('idCliente',$idCliente);
		$this->db->update($this->_table['clientes'], $data);
		
		$this->configuracion->registrarBitacora('Editar cliente','Clientes',$data['empresa']); //Registrar bitácora
		
		if($idCuentaCatalogo>0 and $saldoInicial>0)
		{
			$saldo	= $this->sumarSaldosClientesCuentas($idCuentaCatalogo);
			
			$this->db->where('idCuentaCatalogo', $idCuentaCatalogo); 
			$this->db->update('fac_catalogos_cuentas_detalles', array('saldo'=>$saldo)); 
		}
		
		//COMENTADO 27 ABRIL 2021
		/*$this->db->where('idCliente', $idCliente); 
		$this->db->where('idLicenciaTraspaso', $this->idLicencia); 
		$this->db->delete('clientes_sucursales'); 
		
		if($this->input->post('selectSucursal')>0)
		{
			$this->registrarClienteSucursal($idCliente);
		}*/

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
	
	//EL REGISTRO SERA NUEVO
	public function registrarVentaPrograma($cliente,$idPrograma)
	{
		$programa	= $this->configuracion->obtenerProgramasEditar($idPrograma);
		
		$data=array
		(
			'fecha'				=> $this->_fecha_actual,
			'idPrograma' 		=> $programa->idPrograma,
			#'venta' 			=> $venta,
			
			'idCliente' 		=> $cliente->idCliente,
			'idPromotor' 		=> $cliente->idPromotor,
			'importe' 			=> $programa->importe,
			'comision' 			=> $programa->comision,
		);
		
		$this->db->insert('clientes_programas_ventas', $data);
	}
	
	public function editarDireccionesEntrega($idCliente)
	{
		for($i=1;$i<=5;$i++)
		{			
			$data=array
			(
				'calle'			=> $this->input->post('txtCalleEntrega'.$i),
				'numero'		=> $this->input->post('txtNumeroEntrega'.$i),
				'colonia'		=> $this->input->post('txtColoniaEntrega'.$i),
				'codigoPostal'	=> $this->input->post('txtCodigoPostalEntrega'.$i),
				'ciudad'		=> $this->input->post('txtLocalidadEntrega'.$i),
				'estado'		=> $this->input->post('txtEstadoEntrega'.$i),
				'referencia'	=> $this->input->post('txtReferenciaEntrega'.$i),
			);
			
			$this->db->where('idDireccion', $this->input->post('txtIdDireccion'.$i));
			$this->db->update('clientes_direcciones', $data);
		}
		
		return array('1');
	}
	
	public function registrarAcademicoCliente($idCliente)
	{
		$data=array
		(
			'idCliente' 				=> $idCliente,
			'idPrograma'				=> 0,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('clientes_academicos', $data); 
		
		//RELACION DEPERIODOS
		$data=array
		(
			'idCliente' 			=> $idCliente,
			'idPeriodo'				=> 0,
			'fecha'					=> $this->_fecha_actual
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('clientes_periodos_relacion', $data); 
	}
	
	public function obtenerAcademicoCliente($idCliente)
	{
		/*$sql=" select a.*, 
		(select b.nombre from clientes_programas as b where b.idPrograma=a.idPrograma) as programa,
		(select b.diaPago from clientes_programas as b where b.idPrograma=a.idPrograma) as diaPago
		from clientes_academicos as a
		where idCliente='$idCliente' ";*/
		
		
		$sql=" select a.*, c.idRelacion, c.idPeriodo,
		(select b.nombre from clientes_programas as b where b.idPrograma=a.idPrograma) as programa,
		(select b.diaPago from clientes_programas as b where b.idPrograma=a.idPrograma) as diaPago
		from clientes_academicos as a
		inner join clientes_periodos_relacion as c
		on c.idCliente=a.idCliente
		where a.idCliente='$idCliente'
		order by c.fecha desc
		limit 1 ";
		
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerPagosCliente($idCliente)
	{
		$sql=" select a.fecha, a.pago, a.producto,
		b.nombre as forma, d.nombre as banco
		from catalogos_ingresos as a
		inner join catalogos_formas as b
		on a.idForma=b.idForma
		inner join cuentas as c
		on c.idCuenta=a.idCuenta
		inner join bancos as d
		on d.idBanco=c.idBanco
		where a.idCliente='$idCliente'
		and a.idForma!=4 ";
		
		#$sql.=sistemaActivo=='olyess'?" and a.acrilico='0' ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTotalPagosCliente($idCliente)
	{
		$sql=" select coalesce(sum(a.pago),0) as pagos
		from catalogos_ingresos as a
		where a.idCliente='$idCliente'
		and a.idForma!=4  ";	
		
		#$sql.=sistemaActivo=='olyess'?" and a.acrilico='0' ":'';
		
		return $this->db->query($sql)->row()->pagos;
	}
	
	public function obtenerTotalPagosClienteOtros($idCliente)
	{
		$sql=" select coalesce(sum(a.pago),0) as pagos
		from catalogos_ingresos as a
		inner join catalogos_productos as b
		on a.idProducto=b.idProducto
		where a.idCliente='$idCliente'
		and a.idForma!=4
		and b.sistema='0'  ";	
		
		return $this->db->query($sql)->row()->pagos;
	}
	
	public function obtenerDireccionesEntrega($idCliente)
	{
		$sql=" select * from clientes_direcciones
		where idCliente='$idCliente' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDireccionEntrega($idDireccion)
	{
		$sql=" select * from clientes_direcciones
		where idDireccion='$idDireccion' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarDireccionesNuevas($idCliente)
	{
		for($i=1;$i<=5;$i++)
		{			
			$data=array
			(
				'idCliente' 	=> $idCliente,
			);
			
			$this->db->insert('clientes_direcciones', $data);
		}
	}
	
	public function editarDatosFiscales()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza

		$data=array
		(
			'razonSocial'		=> $this->input->post('txtRazonSocial'),
			'rfc'				=> $this->input->post('txtRfc'),
			'calle'				=> $this->input->post('txtCalle'),
			'numero'			=> $this->input->post('txtNumero'),
			'colonia'			=> $this->input->post('txtColonia'),
			'localidad'			=> $this->input->post('txtLocalidad'),
			'municipio'			=> $this->input->post('txtMunicipio'),
			'estado'			=> $this->input->post('txtEstado'),
			'pais'				=> $this->input->post('txtPais'),
			'codigoPostal'		=> $this->input->post('txtCodigoPostal'),
		);

		$data	= procesarArreglo($data);
		$this->db->where('idCliente',$this->input->post('txtIdClienteFiscales'));
		$this->db->update($this->_table['clientes'], $data);
		
		#$this->configuracion->registrarBitacora('Editar cliente','Clientes',$data['empresa']); //Registrar bitácora

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array("0");
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array("1");
		}
	}

	public function cliente_usuario($idCliente)
	{
		$sql="select a.empresa, a.idCliente, a.telefono, a.calle,
		a.rfc, a.estado, a.localidad, b.nombre, 
		a.email, a.precio, a.limiteCredito
		from clientes as a
		inner join usuarios as b
		on (a.idUsuario=b.idUsuario)
		where a.idCliente='$idCliente'
		and a.idLicencia='$this->idLicencia'";
		
		$query=$this->db->query($sql);
		
		return ($query->num_rows()> 0)? $query->row() : NULL;
	}
	
	public function obtenerContactoCliente($idCliente)
	{
		$sql=" select * from clientes_contactos
		where idCliente='$idCliente'
		and activo='1'
		limit 1 ";
		
		return $this->db->query($sql)->row();
	}

	public function getNameCliente($idCliente)
	{
		$sql=" select a.*,b.* from  
		clientes_contactos as a, 
		clientes as b 
		where a.idCliente= '$idCliente' 
		and b.idCliente = '$idCliente' 
		and a.idCliente = b.idCliente
		order by a.fechaRegistro 
		desc limit 0,1 ";
			  
		return $this->db->query($sql)->result_array();
	}
	
	#-------------------------------------------------------------------------------------------------------#
	
	public function contarClientes($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$todos,$idZona,$idPrograma=0,$diaPago=0,$idCampana=0,$matricula=0)
	{
		$sql="select a.idCliente
		from clientes as a
		inner join zonas as b
		on a.idZona=b.idZona ";
		
		if($idPrograma!=0 or $diaPago!=0)
		{
			$sql.=" 
			inner join clientes_academicos as e
			on a.idCliente=e.idCliente ";
		}

		if($diaPago!=0)
		{
			$sql.=" 
			inner join clientes_programas as f
			on f.idPrograma=e.idPrograma ";
		}
		
		$sql.=" where a.activo='1'  ";
		
		$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';
		
		#$sql.=sistemaActivo=='IEXE'?strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%') ":'':strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" 
			and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%'
			or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' 
			or a.telefono like '$criterio%' 
			or a.email like '$criterio%' 
			or a.movil like '$criterio%'
			or (select count(g.idAcademico) from clientes_academicos as g where g.idCliente=a.idCliente and g.matricula like '%$criterio%') > 0 ) ":'';
			
			$sql.=$idPrograma!=0?" and  e.idPrograma='$idPrograma' ":'';
			$sql.=$diaPago!=0?" and  f.diaPago='$diaPago' ":'';
			$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
			
			$sql.=$matricula==1?" and  (select count(j.idAcademico) from clientes_academicos as j where j.idCliente=a.idCliente and length(j.matricula)>2) >0 ":'';
			$sql.=$matricula==2?" and  (select count(j.idAcademico) from clientes_academicos as j where j.idCliente=a.idCliente and length(j.matricula)<=2) >0 ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or a.telefono like '$criterio%' or a.email like '$criterio%' or a.movil like '$criterio%') ":'';
		}
		
		$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
		$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
		
		
		
		#$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		
		/*if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			$sql.=" and  a.idPromotor='$this->_user_id' ";
		}*/
		
		
		#$sql.=$this->_user_id!=1?" and a.idUsuario='$this->_user_id' ":'';

		if($idStatus>0 or $idServicio>0 or $fecha!='fecha' or $idResponsable>0 or $fechaMes!='mes' )
		{
			$anio	=substr($fechaMes,0,4);
			$mes	=substr($fechaMes,5,2);
			
			$sql="select b.fecha, 
			c.nombre as servicio, b.idStatus
			from clientes as a
			inner join seguimiento as b
			on a.idCliente=b.idCliente
			inner join seguimiento_servicios as c
			on b.idServicio=c.idServicio
			inner join usuarios as d
			on b.idResponsable=d.idUsuario ";
			
			if($idPrograma!=0 or $diaPago!=0)
			{
				$sql.=" 
				inner join clientes_academicos as f
				on a.idCliente=f.idCliente ";
			}
	
			if($diaPago!=0)
			{
				$sql.=" 
				inner join clientes_programas as g
				on g.idPrograma=f.idPrograma ";
			}
			
			$sql.=" where a.activo='1' ";
			
			#$sql.=$this->_user_id!=1?" and a.idUsuario='$this->_user_id' ":'';
			$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';
			$sql.=$idStatus>0?" and b.idStatus='$idStatus'":'';
			$sql.=$idServicio>0?" and b.idServicio='$idServicio'":'';
			$sql.=$fecha!='fecha'?" and date(b.fecha)='$fecha'":'';
			$sql.=$idResponsable>0?" and b.idResponsable='$idResponsable'":'';
			$sql.=$fechaMes!='mes'?" and month(b.fecha)='$mes' and year(b.fecha)='$anio'":'';
			$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
			
			$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
			
			#$sql.=$idEstatus>0?" and b.idEstatus='$idEstatus'":'';
			
			
			
			#$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			
			/*if($this->idRol==1)
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
			else
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}*/
			
			
			if(sistemaActivo=='IEXE')
			{
				$sql.=strlen($criterio)>0?" 
				and (a.empresa like '$criterio%' 
				or a.razonSocial like '$criterio%' 
				or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' 
				or a.telefono like '$criterio%' 
				or a.email like '$criterio%' 
				or a.movil like '$criterio%' 
				or (select count(g.idAcademico) from clientes_academicos as g where g.idCliente=a.idCliente and g.matricula like '%$criterio%') > 0) ":'';
				
				$sql.=$idPrograma!=0?" and  f.idPrograma='$idPrograma' ":'';
				$sql.=$diaPago!=0?" and  g.diaPago='$diaPago' ":'';
				$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
				
				
				$sql.=$matricula==1?" and  (select count(j.idAcademico) from clientes_academicos as j where j.idCliente=a.idCliente and length(j.matricula)>2) >0 ":'';
				$sql.=$matricula==2?" and  (select count(j.idAcademico) from clientes_academicos as j where j.idCliente=a.idCliente and length(j.matricula)<=2) >0 ":'';
				
				
			}
			else
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or a.telefono like '$criterio%' or a.email like '$criterio%' or a.movil like '$criterio%')":'';
			}
			
			$sql.=" group by b.idCliente ";
		}

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerClientesUsuario($numero,$limite,$criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$todos,$idZona,$idPrograma=0,$diaPago=0,$idCampana=0,$matricula=0,$orden='asc')
	{
		#$orden=" order by a.empresa asc ";

		$sql="select a.*, b.descripcion
		as variable
		
		".(sistemaActivo=='IEXE'?", (select concat(d.nombre, ' ', d.apellidoPaterno, ' ', d.apellidoMaterno) from usuarios as d where d.idUsuario=a.idPromotor ) as promotor":'')."
		
		".(sistemaActivo=='IEXE'?", (select f.nombre from clientes_campanas as f where f.idCampana=a.idCampana ) as campana,
		(select g.matricula from clientes_academicos as g where g.idCliente=a.idCliente limit 1) as matricula ":'')."
		
		
		from clientes as a 
		inner join zonas as b
		on a.idZona=b.idZona ";
		
		if($idPrograma!=0 or $diaPago!=0)
		{
			$sql.=" 
			inner join clientes_academicos as e
			on a.idCliente=e.idCliente ";
		}

		if($diaPago!=0)
		{
			$sql.=" 
			inner join clientes_programas as f
			on f.idPrograma=e.idPrograma ";
		}
		
		
		$sql.=" where a.activo='1' ";
		
		#$sql.=$this->_user_id!=1?" and a.idUsuario='$this->_user_id' ":'';
		$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';
		#$sql.=$idCliente>0?" and  a.idCliente='$idCliente' ":'';
		
		#$sql.=sistemaActivo=='IEXE'?strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%') ":'':strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" 
			and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' 
			or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' 
			or a.telefono like '$criterio%' 
			or a.email like '$criterio%' 
			or a.movil like '$criterio%' 
			or (select count(g.idAcademico) from clientes_academicos as g where g.idCliente=a.idCliente and g.matricula like '%$criterio%') > 0 ) ":'';
			
			$sql.=$idPrograma!=0?" and  e.idPrograma='$idPrograma' ":'';
			$sql.=$diaPago!=0?" and  f.diaPago='$diaPago' ":'';
			$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
			
			$sql.=$matricula==1?" and  (select count(j.idAcademico) from clientes_academicos as j where j.idCliente=a.idCliente and length(j.matricula)>2) >0 ":'';
			$sql.=$matricula==2?" and  (select count(j.idAcademico) from clientes_academicos as j where j.idCliente=a.idCliente and length(j.matricula)<=2) >0 ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or a.telefono like '$criterio%' or a.email like '$criterio%' or a.movil like '$criterio%' or a.alias like '$criterio%') ":'';
		}
		
		
		$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
		$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
		
		
		/*if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			$sql.=" and  a.idPromotor='$this->_user_id' ";
		}*/
		
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=" order by a.ultimaConexion ".$orden.", a.empresa asc ";
		}
		else
		{
			$sql.=" order by a.empresa asc ";
		}
		
		$sql .= " limit $limite,$numero ";
		
		#echo $sql;
			
		if($idStatus>0 or $idServicio>0 or $fecha!='fecha' or $idResponsable>0 or $fechaMes!='mes' )
		{
			$anio	=substr($fechaMes,0,4);
			$mes	=substr($fechaMes,5,2);
			
			$sql="select a.*, b.fecha, 
			c.nombre as servicio, b.idStatus,
			concat(d.nombre, ' ', d.apellidoPaterno, ' ', d.apellidoMaterno) as responsable,
			e.descripcion as variable
			
			".(sistemaActivo=='IEXE'?",(select concat(f.nombre, ' ', f.apellidoPaterno, ' ', f.apellidoMaterno) from usuarios as f where f.idUsuario=a.idPromotor ) as promotor":"")."
			".(sistemaActivo=='IEXE'?", (select h.nombre from clientes_campanas as h where h.idCampana=a.idCampana ) as campana,
			(select g.matricula from clientes_academicos as g where g.idCliente=a.idCliente) as matricula  ":'')."
			
			
			from clientes as a
			inner join seguimiento as b
			on a.idCliente=b.idCliente
			inner join seguimiento_servicios as c
			on b.idServicio=c.idServicio
			inner join usuarios as d
			on b.idResponsable=d.idUsuario
			inner join zonas as e
			on a.idZona=e.idZona ";
			
			if(sistemaActivo=='IEXE')
			{
				if($idPrograma!=0 or $diaPago!=0)
				{
					$sql.=" 
					inner join clientes_academicos as f
					on a.idCliente=f.idCliente ";
				}
		
				if($diaPago!=0)
				{
					$sql.=" 
					inner join clientes_programas as g
					on g.idPrograma=f.idPrograma ";
				}
			}
			
			$sql.=" where a.activo='1' ";
			
			#$sql.=$this->_user_id!=1?" and a.idUsuario='$this->_user_id' ":'';
			$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';
			$sql.=$idStatus>0?" and b.idStatus='$idStatus'":'';
			$sql.=$idServicio>0?" and b.idServicio='$idServicio'":'';
			$sql.=$fecha!='fecha'?" and date(b.fecha)='$fecha'":'';
			$sql.=$idResponsable>0?" and b.idResponsable='$idResponsable'":'';
			$sql.=$fechaMes!='mes'?" and month(b.fecha)='$mes' and year(b.fecha)='$anio'":'';
			$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
			
			#$sql.=$idEstatus>0?" and b.idEstatus='$idEstatus'":'';
			$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
			
			if(sistemaActivo=='IEXE')
			{
				$sql.=$idPrograma!=0?" and  f.idPrograma='$idPrograma' ":'';
				$sql.=$diaPago!=0?" and  g.diaPago='$diaPago' ":'';
				$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
				
				$sql.=$matricula==1?" and  (select count(j.idAcademico) from clientes_academicos as j where j.idCliente=a.idCliente and length(j.matricula)>2) >0 ":'';
				$sql.=$matricula==2?" and  (select count(j.idAcademico) from clientes_academicos as j where j.idCliente=a.idCliente and length(j.matricula)<=2) >0 ":'';
			}
			
			#$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			
			/*if($this->idRol==1)
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
			else
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}*/
			
			if(sistemaActivo=='IEXE')
			{
				$sql.=strlen($criterio)>0?" 
				and (a.empresa like '$criterio%' 
				or a.razonSocial like '$criterio%' 
				or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%'
				or (select count(g.idAcademico) from clientes_academicos as g where g.idCliente=a.idCliente and g.matricula like '%$criterio%') > 0 ) ":'';
			}
			else
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or a.alias like '$criterio%')":'';
			}
			
			$sql.=" group by b.idCliente ";
			#$sql.=$criterio;
			#$sql.=" order by a.empresa asc";
			
			if(sistemaActivo=='IEXE')
			{
				$sql.=" order by a.ultimaConexion ".$orden.", a.empresa asc ";
			}
			else
			{
				$sql.=" order by a.empresa asc ";
			}
			
			$sql .= " limit $limite,$numero ";
		}
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function sumarColegiaturas($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$todos,$idZona,$idPrograma=0,$diaPago=0,$idCampana=0)
	{
		$sql="select coalesce(sum(b.colegiatura),0) as colegiatura 
		from clientes as a 
		inner join clientes_academicos as b
		on a.idCliente=b.idCliente ";
		
		if($diaPago>0)
		{
			$sql.=" 
			inner join clientes_programas as c
			on c.idPrograma=b.idPrograma ";
		}
		
		$sql.=" where a.activo='1' ";

		$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';

		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%') ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		}
		
		
		$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
		$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
		$sql.=$idPrograma!=0?" and  b.idPrograma ='$idPrograma' ":'';
		$sql.=$diaPago!=0?" and  c.diaPago ='$diaPago' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';

			
		if($idStatus>0 or $idServicio>0 or $fecha!='fecha' or $idResponsable>0 or $fechaMes!='mes' )
		{
			$anio	=substr($fechaMes,0,4);
			$mes	=substr($fechaMes,5,2);
			
			$sql="select coalesce(sum(e.colegiatura),0) as colegiatura 
			from clientes as a
			inner join seguimiento as b
			on a.idCliente=b.idCliente
			inner join seguimiento_servicios as c
			on b.idServicio=c.idServicio
			inner join usuarios as d
			on b.idResponsable=d.idUsuario
			
			inner join clientes_academicos as e
			on a.idCliente=e.idCliente ";
			
			
			if($diaPago>0)
			{
				$sql.=" 
				inner join clientes_programas as f
				on f.idPrograma=e.idPrograma ";
			}
			
			
			$sql.=" where a.activo='1' ";
			
			#$sql.=$this->_user_id!=1?" and a.idUsuario='$this->_user_id' ":'';
			$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';
			$sql.=$idStatus>0?" and b.idStatus='$idStatus'":'';
			$sql.=$idServicio>0?" and b.idServicio='$idServicio'":'';
			$sql.=$fecha!='fecha'?" and date(b.fecha)='$fecha'":'';
			$sql.=$idResponsable>0?" and b.idResponsable='$idResponsable'":'';
			$sql.=$fechaMes!='mes'?" and month(b.fecha)='$mes' and year(b.fecha)='$anio'":'';
			$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
			
			$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
			
			$sql.=$idPrograma!=0?" and  f.idPrograma ='$idPrograma' ":'';
			$sql.=$diaPago!=0?" and  f.diaPago ='$diaPago' ":'';
			$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';

			if(sistemaActivo=='IEXE')
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%') ":'';
			}
			else
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' )":'';
			}
		}

		return $this->db->query($sql)->row()->colegiatura;
	}
	
	public function obtenerDiasPago($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$todos,$idZona,$idPrograma=0,$idCampana)
	{
		$sql="select distinct c.diaPago
		from clientes as a 
		inner join clientes_academicos as b
		on a.idCliente=b.idCliente
		inner join clientes_programas as c
		on c.idPrograma=b.idPrograma 
		where a.activo='1' ";

		$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';

		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%') ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		}
		
		$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
		$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
		$sql.=$idPrograma!=0?" and  b.idPrograma ='$idPrograma' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';

			
		if($idStatus>0 or $idServicio>0 or $fecha!='fecha' or $idResponsable>0 or $fechaMes!='mes' )
		{
			$anio	=substr($fechaMes,0,4);
			$mes	=substr($fechaMes,5,2);
			
			$sql="select distinct f.diaPago
			from clientes as a
			inner join seguimiento as b
			on a.idCliente=b.idCliente
			inner join seguimiento_servicios as c
			on b.idServicio=c.idServicio
			inner join usuarios as d
			on b.idResponsable=d.idUsuario
			
			inner join clientes_academicos as e
			on a.idCliente=e.idCliente
			inner join clientes_programas as f
			on f.idPrograma=e.idPrograma 
			where a.activo='1' ";
			
			#$sql.=$this->_user_id!=1?" and a.idUsuario='$this->_user_id' ":'';
			$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';
			$sql.=$idStatus>0?" and b.idStatus='$idStatus'":'';
			$sql.=$idServicio>0?" and b.idServicio='$idServicio'":'';
			$sql.=$fecha!='fecha'?" and date(b.fecha)='$fecha'":'';
			$sql.=$idResponsable>0?" and b.idResponsable='$idResponsable'":'';
			$sql.=$fechaMes!='mes'?" and month(b.fecha)='$mes' and year(b.fecha)='$anio'":'';
			$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
			
			$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
			
			$sql.=$idPrograma!=0?" and  f.idPrograma ='$idPrograma' ":'';
			$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';

			if(sistemaActivo=='IEXE')
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%') ":'';
			}
			else
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' )":'';
			}
		}

		return $this->db->query($sql)->result();
	}
	
	
	public function contarProspectos($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$todos,$idZona,$idEstatus=0,$idPromotor=0,$fechaFin,$numeroSeguimientos=-1,$idCampana,$idPrograma=0,$idFuente=0,$tipoFecha,$inicial,$final)
	{
		$sql="select a.idCliente
		from clientes as a
		inner join zonas as b
		on a.idZona=b.idZona
		where a.activo='1' ";
		  
		  #and a.idZona!=2
		
		#$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';

		
		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		}
		
		$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
		$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		
		$sql.=$tipoFecha==0?" and date(a.fechaRegistro) between '$inicial' and '$final' ":" and date(a.fechaCaptacion) between '$inicial' and '$final' ";
			
		/*if($numeroSeguimientos=='-2')
		{
			$sql.=" and a.nuevoRegistro='1'";
		}
		else
		{
			$sql.=$numeroSeguimientos!=-1?" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=$numeroSeguimientos ":'';
		}*/
		
		if($numeroSeguimientos=='-2')
		{
			$sql.=" and a.nuevoRegistro='1'";
		}
		else
		{
			if($numeroSeguimientos!=-1 and $numeroSeguimientos<5)
			{
				$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=$numeroSeguimientos ";
			}
			
			if($numeroSeguimientos==5)
			{
				$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )>=$numeroSeguimientos ";
			}
			
		}
			
		#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';

		if($idStatus>0 or $idServicio>0 or $fecha!='fecha' or $idResponsable>0 or $fechaMes!='mes' or $idEstatus>0)
		{
			$anio	=substr($fechaMes,0,4);
			$mes	=substr($fechaMes,5,2);
			
			$sql="select b.fecha, 
			c.nombre as servicio, b.idStatus
			from clientes as a
			inner join seguimiento as b
			on a.idCliente=b.idCliente
			inner join seguimiento_servicios as c
			on b.idServicio=c.idServicio
			inner join usuarios as d
			on b.idResponsable=d.idUsuario
			where a.activo='1'
			  ";
			  
			  #and a.idZona!=2
		
			#$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';
			$sql.=$idStatus>0?" and b.idStatus='$idStatus'":'';
			$sql.=$idServicio>0?" and b.idServicio='$idServicio'":'';
			
			$sql.=$idResponsable>0?" and b.idResponsable='$idResponsable'":'';
			$sql.=$fechaMes!='mes'?" and month(b.fecha)='$mes' and year(b.fecha)='$anio'":'';
			$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
			$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
			
			$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
			
			$sql.=$idEstatus>0?" and b.idEstatus='$idEstatus'":'';
			$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
			$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
			
			$sql.=$tipoFecha==0?" and date(a.fechaRegistro) between '$inicial' and '$final' ":" and date(a.fechaCaptacion) between '$inicial' and '$final' ";
			
			/*if($numeroSeguimientos=='-2')
			{
				$sql.=" and a.nuevoRegistro='1'";
			}
			else
			{
				$sql.=$numeroSeguimientos!=-1?" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=$numeroSeguimientos ":'';
			}*/
			
			if($numeroSeguimientos=='-2')
			{
				$sql.=" and a.nuevoRegistro='1'";
			}
			else
			{
				if($numeroSeguimientos!=-1 and $numeroSeguimientos<5)
				{
					$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=$numeroSeguimientos ";
				}
				
				if($numeroSeguimientos==5)
				{
					$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )>=$numeroSeguimientos ";
				}
				
			}
			
			
			if($this->idRol!=1)
			{
				$sql.=$fecha!='fecha'?" and date(b.fecha)='$fecha'":'';
			}
			else
			{
				$sql.=$fecha!='fecha'?" and date(b.fecha) between '$fecha' and '$fechaFin'  ":'';
			}

			#$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';

			if(sistemaActivo=='IEXE')
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
			}
			else
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' )":'';
			}
			
			$sql.=" group by b.idCliente ";
		}

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerProspectosUsuario($numero,$limite,$criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$todos,$idZona,$orden='asc',$idEstatus=0,$idPromotor=0,$fechaFin,$numeroSeguimientos=-1,$idCampana=0,$idPrograma=0,$idFuente=0,$tipoFecha,$inicial,$final)
	{
		#$orden=" order by a.empresa asc ";

		$sql=" select a.*, b.descripcion
		as variable,
		
		(select concat(d.nombre, ' ', d.apellidoPaterno, ' ', d.apellidoMaterno) from usuarios as d where d.idUsuario=a.idPromotor ) as promotor,
		(select d.correo from usuarios as d where d.idUsuario=a.idPromotor ) as emailPromotor,
		
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		
		(select d.nombre from clientes_programas as d inner join clientes_academicos as e on d.idPrograma=e.idPrograma where a.idCliente=e.idCliente) as programa,
		
		(select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente) as numeroSeguimientos,
		
		(select d.nombre from clientes_fuentes as d where d.idFuente=a.idFuente) as fuente
		
		from clientes as a 
		inner join zonas as b
		on a.idZona=b.idZona
		where a.activo='1' ";
		
		#and a.idZona!=2

		#$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';

		#$sql.=sistemaActivo=='IEXE'?strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%') ":'':strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		}
		
		
		$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
		$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		
		$sql.=$tipoFecha==0?" and date(a.fechaRegistro) between '$inicial' and '$final' ":" and date(a.fechaCaptacion) between '$inicial' and '$final' ";
		
		if($numeroSeguimientos=='-2')
		{
			$sql.=" and a.nuevoRegistro='1'";
		}
		else
		{
			if($numeroSeguimientos!=-1 and $numeroSeguimientos<5)
			{
				$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=$numeroSeguimientos ";
			}
			
			if($numeroSeguimientos==5)
			{
				$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )>=$numeroSeguimientos ";
			}
			
		}
		
		
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		/*if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}*/
		//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		
		
		$sql.=" order by concat(a.nombre,' ',a.paterno,' ',a.materno) asc ";
		$sql .= $numero>0? " limit $limite,$numero ":'';
		
		#echo $sql;
			
		if($idStatus>0 or $idServicio>0 or $fecha!='fecha' or $idResponsable>0 or $fechaMes!='mes' or $idEstatus>0)
		{
			$anio	=substr($fechaMes,0,4);
			$mes	=substr($fechaMes,5,2);
			
			$sql="select a.*, b.fecha, 
			c.nombre as servicio, b.idStatus,
			concat(d.nombre, ' ', d.apellidoPaterno, ' ', d.apellidoMaterno) as responsable,
			e.descripcion as variable,
			
			(select concat(f.nombre, ' ', f.apellidoPaterno, ' ', f.apellidoMaterno) from usuarios as f where f.idUsuario=a.idPromotor ) as promotor,
			
			(select f.correo from usuarios as f where f.idUsuario=a.idPromotor ) as emailPromotor,
			
			(select f.nombre from clientes_campanas as f where f.idCampana=a.idCampana) as campana,
			
			(select e.nombre from clientes_programas as e inner join clientes_academicos as f on e.idPrograma=f.idPrograma where a.idCliente=f.idCliente) as programa,
			
			(select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente ) as numeroSeguimientos,
			(select d.nombre from clientes_fuentes as d where d.idFuente=a.idFuente) as fuente
			
			
			from clientes as a
			inner join seguimiento as b
			on a.idCliente=b.idCliente
			inner join seguimiento_servicios as c
			on b.idServicio=c.idServicio
			inner join usuarios as d
			on b.idResponsable=d.idUsuario
			inner join zonas as e
			on a.idZona=e.idZona
			where a.activo='1'
			 ";
			 #and a.idZona!=2
			
			#$sql.=$this->_user_id!=1?" and a.idUsuario='$this->_user_id' ":'';
			#$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';
			$sql.=$idStatus>0?" and b.idStatus='$idStatus'":'';
			$sql.=$idServicio>0?" and b.idServicio='$idServicio'":'';
			#$sql.=$fecha!='fecha'?" and date(b.fecha)='$fecha'":'';
			$sql.=$idResponsable>0?" and b.idResponsable='$idResponsable'":'';
			$sql.=$fechaMes!='mes'?" and month(b.fecha)='$mes' and year(b.fecha)='$anio'":'';
			$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
			$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
			
			$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
			$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
			
			$sql.=$tipoFecha==0?" and date(a.fechaRegistro) between '$inicial' and '$final' ":" and date(a.fechaCaptacion) between '$inicial' and '$final' ";
			
			
			$sql.=$idEstatus>0?" and b.idEstatus='$idEstatus'":'';
			$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';

			/*if($numeroSeguimientos=='-2')
			{
				$sql.=" and a.nuevoRegistro='1'";
			}
			else
			{
				$sql.=$numeroSeguimientos!=-1?" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=$numeroSeguimientos ":'';
			}*/
			
			if($numeroSeguimientos=='-2')
			{
				$sql.=" and a.nuevoRegistro='1'";
			}
			else
			{
				if($numeroSeguimientos!=-1 and $numeroSeguimientos<5)
				{
					$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=$numeroSeguimientos ";
				}
				
				if($numeroSeguimientos==5)
				{
					$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )>=$numeroSeguimientos ";
				}
				
			}
			
			if($this->idRol!=1)
			{
				$sql.=$fecha!='fecha'?" and date(b.fecha)='$fecha'":'';
			}
			else
			{
				$sql.=$fecha!='fecha'?" and date(b.fecha) between '$fecha' and '$fechaFin'  ":'';
			}
			
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			
			/*
			if($this->idRol==1)
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
			else
			{
				#$sql.=" and  a.idPromotor='$this->_user_id' ";
				
				if($todos==0)
				{
					$sql.=" and  a.idPromotor='$this->_user_id' ";
				}
				else
				{
					$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
				}
			}*/
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			if(sistemaActivo=='IEXE')
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
			}
			else
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' )":'';
			}
			
			$sql.=" group by b.idCliente ";
			#$sql.=$criterio;
			$sql.=" order by a.empresa ".$orden;
			$sql .= $numero>0? " limit $limite,$numero ":'';
		}
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	
	public function sumarSeguimientosProspectosUsuario($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$todos,$idZona,$orden='asc',$idEstatus=0,$idPromotor=0,$fechaFin,$numeroSeguimientos=-1,$idCampana=0,$idPrograma=0,$idFuente=0,$tipoFecha,$inicial,$final)
	{
		$sql=" select a.idCliente,
		coalesce(sum((select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente)),0) as numeroSeguimientos
		from clientes as a 
		inner join zonas as b
		on a.idZona=b.idZona
		where a.activo='1' ";

		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';

		$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
		$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		
		$sql.=$tipoFecha==0?" and date(a.fechaRegistro) between '$inicial' and '$final' ":" and date(a.fechaCaptacion) between '$inicial' and '$final' ";
		
		if($numeroSeguimientos=='-2')
		{
			$sql.=" and a.nuevoRegistro='1'";
		}
		else
		{
			if($numeroSeguimientos!=-1 and $numeroSeguimientos<5)
			{
				$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=$numeroSeguimientos ";
			}
			
			if($numeroSeguimientos==5)
			{
				$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )>=$numeroSeguimientos ";
			}
			
		}

		$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';

		if($idStatus>0 or $idServicio>0 or $fecha!='fecha' or $idResponsable>0 or $fechaMes!='mes' or $idEstatus>0)
		{
			$anio	=substr($fechaMes,0,4);
			$mes	=substr($fechaMes,5,2);
			
			$sql="select a.idCliente,
			coalesce(sum((select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente)),0) as numeroSeguimientos	
			from clientes as a
			inner join seguimiento as b
			on a.idCliente=b.idCliente
			inner join seguimiento_servicios as c
			on b.idServicio=c.idServicio
			inner join usuarios as d
			on b.idResponsable=d.idUsuario
			inner join zonas as e
			on a.idZona=e.idZona
			where a.activo='1'  ";

			$sql.=$idStatus>0?" and b.idStatus='$idStatus'":'';
			$sql.=$idServicio>0?" and b.idServicio='$idServicio'":'';
			$sql.=$idResponsable>0?" and b.idResponsable='$idResponsable'":'';
			$sql.=$fechaMes!='mes'?" and month(b.fecha)='$mes' and year(b.fecha)='$anio'":'';
			$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
			$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
			
			$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
			$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
			
			$sql.=$tipoFecha==0?" and date(a.fechaRegistro) between '$inicial' and '$final' ":" and date(a.fechaCaptacion) between '$inicial' and '$final' ";

			$sql.=$idEstatus>0?" and b.idEstatus='$idEstatus'":'';
			$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';

			if($numeroSeguimientos=='-2')
			{
				$sql.=" and a.nuevoRegistro='1'";
			}
			else
			{
				if($numeroSeguimientos!=-1 and $numeroSeguimientos<5)
				{
					$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=$numeroSeguimientos ";
				}
				
				if($numeroSeguimientos==5)
				{
					$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )>=$numeroSeguimientos ";
				}
				
			}
			
			if($this->idRol!=1)
			{
				$sql.=$fecha!='fecha'?" and date(b.fecha)='$fecha'":'';
			}
			else
			{
				$sql.=$fecha!='fecha'?" and date(b.fecha) between '$fecha' and '$fechaFin'  ":'';
			}
			
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';

			if(sistemaActivo=='IEXE')
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.email like '$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%') ":'';
			}
			else
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' )":'';
			}
			
		}

		return $this->db->query($sql)->row()->numeroSeguimientos;
	}
	
	public function obtenerProspectosUsuarioSeguimiento($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$todos,$idZona,$orden='asc',$idEstatus=0,$idPromotor=0,$fechaFin,$idCampana,$idPrograma=0,$idFuente=0)
	{
		#$orden=" order by a.empresa asc ";

		$sql=" select a.idCliente,
		(select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente ) as numeroSeguimientos		
		from clientes as a 
		inner join zonas as b
		on a.idZona=b.idZona
		where a.activo='1'
		and a.idZona!=2 ";

		#$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';

		#$sql.=sistemaActivo=='IEXE'?strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%') ":'':strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%') ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		}
		
		
		$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
		$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}

		$sql.="
		group by numeroSeguimientos
		order by numeroSeguimientos asc";
		
		#echo $sql;
			
		if($idStatus>0 or $idServicio>0 or $fecha!='fecha' or $idResponsable>0 or $fechaMes!='mes' or $idEstatus>0)
		{
			$anio	=substr($fechaMes,0,4);
			$mes	=substr($fechaMes,5,2);
			
			$sql="select a.*, b.fecha, 
			c.nombre as servicio, b.idStatus,
			concat(d.nombre, ' ', d.apellidoPaterno, ' ', d.apellidoMaterno) as responsable,
			e.descripcion as variable,
			
			(select concat(f.nombre, ' ', f.apellidoPaterno, ' ', f.apellidoMaterno) from usuarios as f where f.idUsuario=a.idPromotor ) as promotor,
			(select f.nombre from clientes_campanas as f where f.idCampana=a.idCampana) as campana,
			
			(select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente ) as numeroSeguimientos
			
			
			from clientes as a
			inner join seguimiento as b
			on a.idCliente=b.idCliente
			inner join seguimiento_servicios as c
			on b.idServicio=c.idServicio
			inner join usuarios as d
			on b.idResponsable=d.idUsuario
			inner join zonas as e
			on a.idZona=e.idZona
			where a.activo='1'
			and a.idZona!=2 ";
			
			#$sql.=$this->_user_id!=1?" and a.idUsuario='$this->_user_id' ":'';
			#$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';
			$sql.=$idStatus>0?" and b.idStatus='$idStatus'":'';
			$sql.=$idServicio>0?" and b.idServicio='$idServicio'":'';
			#$sql.=$fecha!='fecha'?" and date(b.fecha)='$fecha'":'';
			$sql.=$idResponsable>0?" and b.idResponsable='$idResponsable'":'';
			$sql.=$fechaMes!='mes'?" and month(b.fecha)='$mes' and year(b.fecha)='$anio'":'';
			$sql.=$idZona!=0?" and  a.idZona='$idZona' ":'';
			$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
			
			$sql.=$idEstatus>0?" and b.idEstatus='$idEstatus'":'';
			$sql.=$idTipo!=4?" and  a.prospecto='$idTipo' ":'';
			$sql.=$idPrograma!=0?" and (select f.idPrograma from clientes_academicos as f where f.idCliente=a.idCliente limit 1) = '$idPrograma' ":'';
			$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
			
			if($this->idRol!=1)
			{
				$sql.=$fecha!='fecha'?" and date(b.fecha)='$fecha'":'';
			}
			else
			{
				$sql.=$fecha!='fecha'?" and date(b.fecha) between '$fecha' and '$fechaFin'  ":'';
			}
			
			#$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			
			if($this->idRol==1)
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
			else
			{
				#$sql.=" and  a.idPromotor='$this->_user_id' ";
				
				if($todos==0)
				{
					$sql.=" and  a.idPromotor='$this->_user_id' ";
				}
				else
				{
					$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
				}
			}
			
			if(sistemaActivo=='IEXE')
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%') ":'';
			}
			else
			{
				$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' )":'';
			}
			
			$sql.=" group by numeroSeguimientos ";
			#$sql.=$criterio;
			#$sql.=" order by a.empresa ".$orden;
		}

		return $this->db->query($sql)->result();
	}
	
	
	public function agruparClientesRegistro()
	{
		$sql=" select coalesce(count(idCliente),0) as total, prospecto
		from clientes
		where activo='1'
		group by prospecto ";

		return $this->db->query($sql)->result();
	}
	
	public function contarProspectos1()
	{
		$idCliente=$this->session->userdata('idProspectoBusqueda');
		
		$sql="select a.*
		from clientes as a
		where a.activo='1' 
		and a.prospecto='1' ";
		
		if($idCliente!='')
		{
			$sql.=" and  a.id='$idCliente'";
		}
		
		$query=$this->db->query($sql);
		return($query->num_rows()>0) ? $query->num_rows() : 0;
	}

	public function obtenerProspectos($Num,$Limite)
	{
		$idCliente=$this->session->userdata('idProspectoBusqueda');
		$criterio=" order by a.empresa asc ";
		
		if($this->session->userdata('criterioProspectos')=="z")
		{
			$criterio=" order by a.empresa desc ";
		}
		
		$sql="select a.*
		from clientes as a 
		where a.idLicencia='$this->idLicencia'
		and a.activo='1' 
		and a.prospecto='1' ";
		
		if($idCliente!='')
		{
			$sql.=" and  a.id='$idCliente'";
		}
		
	    $sql.=$criterio;

		$sql .= " limit $Limite,$Num ";
		
		
		$query=$this->db->query($sql);
		
		return ($query->num_rows() > 0)? $query->result_array() : NULL;
	}
	
	public function contarBajas($criterio,$todos,$idCampana=0,$idPrograma=0,$idPromotor=0,$inicio,$fin,$idCausa=-1,$idFuente=0,$idDetalle=0)
	{
		$sql="select a.idCliente
		from clientes as a
		inner join zonas as b
		on a.idZona=b.idZona
		where a.activo='1'
		and a.idZona=2 
		and bajaAnterior='1'
		and a.prospecto='1' ";
		 
		 #and date(a.fechaBaja) between '$inicio' and '$fin'

		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.telefono like '$criterio%'  or a.movil like '$criterio%'  or a.email like '$criterio%') ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		}
		
		$sql.=$idCausa!=-1?" and  a.idCausa='$idCausa' ":'';
		$sql.=$idDetalle!=0?" and  a.idDetalle='$idDetalle' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		
		$sql.=$idPrograma!=0?" and  (select c.idPrograma from clientes_academicos as c where c.idCliente=a.idCliente) = '$idPrograma' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';

		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			#$sql.=" and  a.idPromotor='$this->_user_id' ";
			
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		
		
		return $this->db->query($sql)->num_rows();
	}

	public function obtenerBajas($numero,$limite,$criterio,$todos,$idCampana=0,$idPrograma=0,$idPromotor=0,$inicio,$fin,$idCausa=-1,$idFuente=0,$idDetalle=0)
	{
		#$orden=" order by a.empresa asc ";

		$sql=" select distinct a.idCliente, a.nombre, a.paterno, a.materno, a.telefono, a.movil, a.email, 
		b.descripcion as variable, a.fechaBaja, a.idDetalle, a.texto,
		
		(select d.nombre from clientes_bajas_causas_detalles as d where d.idDetalle=a.idDetalle) as detalle,
		
		(select concat(d.nombre, ' ', d.apellidoPaterno, ' ', d.apellidoMaterno) from usuarios as d where d.idUsuario=a.idPromotor ) as promotor,
		
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select d.nombre from clientes_bajas_causas as d where d.idCausa=a.idCausa) as causa,
		(select d.nombre from clientes_fuentes as d where d.idFuente=a.idFuente) as fuente,
		(select d.nombre from clientes_programas as d inner join clientes_academicos as c on c.idPrograma=d.idPrograma where c.idCliente=a.idCliente) as programa
		
		from clientes as a 
		inner join zonas as b
		on a.idZona=b.idZona
		where a.activo='1'
		and a.idZona=2
		and a.prospecto='1'
		and bajaAnterior='1' ";
		  
		 #and date(a.fechaBaja) between '$inicio' and '$fin'

		#$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';

		#$sql.=sistemaActivo=='IEXE'?strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%') ":'':strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%'  or a.telefono like '$criterio%'  or a.movil like '$criterio%'  or a.email like '$criterio%') ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		}
		
		
		$sql.=$idCausa!=-1?" and  a.idCausa='$idCausa' ":'';
		$sql.=$idDetalle!=0?" and  a.idDetalle='$idDetalle' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and  (select c.idPrograma from clientes_academicos as c where c.idCliente=a.idCliente) = '$idPrograma' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		$sql.=" order by a.empresa asc";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function contarNoDisponible($criterio,$todos,$idCampana=0,$idPrograma=0,$idPromotor=0,$inicio,$fin,$idDetalle=0,$idFuente=0)
	{
		$sql="select a.idCliente
		from clientes as a
		inner join zonas as b
		on a.idZona=b.idZona
		where a.activo='1'
		and a.idZona=2 
		and bajaAnterior='0'
		and a.prospecto='1' ";
		 
		 #and date(a.fechaBaja) between '$inicio' and '$fin'

		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.telefono like '$criterio%'  or a.movil like '$criterio%'  or a.email like '$criterio%') ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		}

		$sql.=$idDetalle!=0?" and  a.idDetalle='$idDetalle' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		
		$sql.=$idPrograma!=0?" and  (select c.idPrograma from clientes_academicos as c where c.idCliente=a.idCliente) = '$idPrograma' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';

		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			#$sql.=" and  a.idPromotor='$this->_user_id' ";
			
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerNoDisponible($numero,$limite,$criterio,$todos,$idCampana=0,$idPrograma=0,$idPromotor=0,$inicio,$fin,$idDetalle=0,$idFuente=0)
	{
		$sql=" select distinct a.idCliente, a.nombre, a.paterno, a.materno, a.telefono, a.movil, a.email, 
		b.descripcion as variable, a.fechaBaja, a.idDetalle, a.texto,
		
		(select d.nombre from clientes_bajas_causas_detalles as d where d.idDetalle=a.idDetalle) as detalle,
		
		(select concat(d.nombre, ' ', d.apellidoPaterno, ' ', d.apellidoMaterno) from usuarios as d where d.idUsuario=a.idPromotor ) as promotor,
		
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select d.nombre from clientes_bajas_causas as d where d.idCausa=a.idCausa) as causa,
		(select d.nombre from clientes_fuentes as d where d.idFuente=a.idFuente) as fuente,
		(select d.nombre from clientes_programas as d inner join clientes_academicos as c on c.idPrograma=d.idPrograma where c.idCliente=a.idCliente) as programa
		
		from clientes as a 
		inner join zonas as b
		on a.idZona=b.idZona
		where a.activo='1'
		and a.idZona=2
		and a.prospecto='1'
		and bajaAnterior='0' ";

		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%'  or a.telefono like '$criterio%'  or a.movil like '$criterio%'  or a.email like '$criterio%') ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		}
		
		$sql.=$idDetalle!=0?" and  a.idDetalle='$idDetalle' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and  (select c.idPrograma from clientes_academicos as c where c.idCliente=a.idCliente) = '$idPrograma' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		
		$sql.=" order by a.empresa asc";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function contarNocuali($criterio,$todos,$idCampana=0,$idPrograma=0,$idPromotor=0,$inicio,$fin,$idCausa=0,$idFuente=0,$idDetalle=0)
	{
		$sql="select a.idCliente
		from clientes as a
		inner join clientes_nocuali as b
		on a.idCliente=b.idCliente
		where a.activo='1'
		and a.idZona=8 
		and a.prospecto='1' ";

		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.telefono like '$criterio%'  or a.movil like '$criterio%'  or a.email like '$criterio%') ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		}
		
		$sql.=$idCausa!=0?" and  b.idCausa='$idCausa' ":'';
		$sql.=$idDetalle!=0?" and  b.idDetalle='$idDetalle' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		
		$sql.=$idPrograma!=0?" and  (select c.idPrograma from clientes_academicos as c where c.idCliente=a.idCliente) = '$idPrograma' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';

		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerNocuali($numero,$limite,$criterio,$todos,$idCampana=0,$idPrograma=0,$idPromotor=0,$inicio,$fin,$idCausa=0,$idFuente=0,$idDetalle=0)
	{
		$sql=" select distinct a.idCliente, a.nombre, a.paterno, a.materno, a.telefono, 
		a.movil, a.email,  b.fecha as fechaBaja, c.nombre as causa, b.texto, b.idDetalle,
		
		(select d.nombre from clientes_nocuali_causas_detalles as d where d.idDetalle=b.idDetalle) as detalle,
		
		(select concat(d.nombre, ' ', d.apellidoPaterno, ' ', d.apellidoMaterno) from usuarios as d where d.idUsuario=a.idPromotor ) as promotor,
		
		(select d.nombre from clientes_campanas as d where d.idCampana=a.idCampana) as campana,
		(select d.nombre from clientes_fuentes as d where d.idFuente=a.idFuente) as fuente,
		(select d.nombre from clientes_programas as d inner join clientes_academicos as c on c.idPrograma=d.idPrograma where c.idCliente=a.idCliente) as programa
		
		from clientes as a 
		inner join clientes_nocuali as b
		on a.idCliente=b.idCliente
		
		inner join clientes_nocuali_causas as c
		on c.idCausa=b.idCausa
		
		where a.activo='1'
		and a.idZona=8
		and a.prospecto='1' ";

		if(sistemaActivo=='IEXE')
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%'  or a.telefono like '$criterio%'  or a.movil like '$criterio%'  or a.email like '$criterio%') ":'';
		}
		else
		{
			$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%') ":'';
		}

		$sql.=$idCausa!=0?" and  b.idCausa='$idCausa' ":'';
		$sql.=$idDetalle!=0?" and  b.idDetalle='$idDetalle' ":'';
		$sql.=$idCampana!=0?" and  a.idCampana='$idCampana' ":'';
		$sql.=$idPrograma!=0?" and  (select d.idPrograma from clientes_academicos as d where d.idCliente=a.idCliente) = '$idPrograma' ":'';
		$sql.=$idFuente!=0?" and  a.idFuente='$idFuente' ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($todos==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		
		$sql.=" order by b.fecha desc";
		$sql .= $numero>0?" limit $limite,$numero ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}

	public function registrarContactoCliente()
	{
		$data=array
		(
			'idCliente'		=> $this->input->post('idCliente'),
			'nombre'		=> $this->input->post('nombre'),
			'telefono'		=> $this->input->post('telefono'),
			'email'			=> $this->input->post('email'),
			'direccion'		=> $this->input->post('direccion'),
			'extension'		=> $this->input->post('extension'),
			'puesto'		=> $this->input->post('puesto'),
			'fechaRegistro'	=> $this->_fecha_actual,
			
			'puesto'		=> $this->input->post('puesto'),
			'lada'			=> $this->input->post('lada'),
			'ladaMovil1'	=> $this->input->post('ladaMovil1'),
			'movil1'		=> $this->input->post('movil1'),
			'ladaMovil2'	=> $this->input->post('ladaMovil2'),
			'movil2'		=> $this->input->post('movil2'),
			'ladaNextel'	=> $this->input->post('ladaNextel'),
			'nextel'		=> $this->input->post('nextel'),
			'idUsuario' 	=> $this->_user_id,
		);	
		
		$data	= procesarArreglo($data);
		$this->db->insert('clientes_contactos', $data);
		
		$this->configuracion->registrarBitacora('Registrar contacto','Clientes - Contactos',$data['nombre'].', Cliente: '.$this->obtenerClienteEmpresa($this->input->post('idCliente'))); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}

	public function obtenerContactos($idCliente,$permiso=0)
	{
		$sql="select * from clientes_contactos
		where idCliente='$idCliente'
		and activo='1' ";	
		
		$sql.=$permiso==0?" and idUsuario='$this->_user_id' ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerContacto($idContacto)
	{
		$sql="select * from clientes_contactos
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
			'direccion'		=>$this->input->post('direccion'),
			'extension'		=>$this->input->post('extension'),
			'puesto'		=> $this->input->post('puesto'),
			
			'puesto'		=> $this->input->post('puesto'),
			'lada'			=> $this->input->post('lada'),
			'ladaMovil1'	=> $this->input->post('ladaMovil1'),
			'movil1'		=> $this->input->post('movil1'),
			'ladaMovil2'	=> $this->input->post('ladaMovil2'),
			'movil2'		=> $this->input->post('movil2'),
			'ladaNextel'	=> $this->input->post('ladaNextel'),
			'nextel'		=> $this->input->post('nextel'),
		);	
		
		$this->db->where('idContacto',$this->input->post('idContacto'));
		$this->db->update('clientes_contactos', $data);
		
		/*if(sistemaActivo=='IEXE')
		{
			$this->db->where('idCliente',$this->input->post('idCliente'));
			$this->db->update('clientes', array('email'=>$this->input->post('email')));
		}*/
		
		$this->configuracion->registrarBitacora('Editar contacto','Clientes - Contactos',$data['nombre'].', Cliente: '.$this->obtenerClienteEmpresa($this->input->post('idCliente'))); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function editarContactoCliente()
	{
		$data=array
		(
			'telefono'		=> $this->input->post('telefono'),
			'email'			=> $this->input->post('email'),
			'lada'			=> $this->input->post('lada'),
			'ladaMovil'		=> $this->input->post('ladaMovil'),
			'movil'			=> $this->input->post('movil'),
		);	
		
		$this->db->where('idCliente',$this->input->post('idCliente'));
		$this->db->update('clientes', $data);

		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	#BORRAR CONTACTO
	
	public function obtenerContactoClienteBorrar($idContacto)
	{
		$sql="select a.nombre as contacto, b.empresa
		from clientes_contactos as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where a.idContacto='$idContacto' ";
		
		$contacto	=$this->db->query($sql)->row(); 
		
		return $contacto!=null?array($contacto->contacto,$contacto->empresa):array('No se ha encontrado el registro','Sin detalles');
	}
	
	public function borrarContacto($idContacto)
	{
		$this->db->where('idContacto',$idContacto);
		$this->db->update('clientes_contactos',array('activo'=>'0'));
		
		$contacto=$this->obtenerContactoClienteBorrar($idContacto);
		$this->configuracion->registrarBitacora('Borrar contacto','Clientes - Contactos',$contacto[0].', Cliente: '.$contacto[1]); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function getContactosINCliente($ListIDs)
	{
		$SQL="SELECT * FROM ".$this->_table['clientes_contactos']." WHERE  (id IN(".$ListIDs.")) ORDER BY fechadd";
		
		$query=$this->db->query($SQL);
		
		return ($query->num_rows() > 0)? $query->result_array() : NULL;
	}

	function getByContactosCliente($Idc)
	{
		$SQL="SELECT * FROM ".$this->_table['clientes_contactos']." WHERE id_cliente='".$Idc."'  ORDER BY fechadd DESC LIMIT 0,1";
		
		$query=$this->db->query($SQL);
		
		return ($query->num_rows() == 1)? $query->row_array() : NULL;
	}//Devuelve el ultimo contacto Registrado

//*******************************************************************************
//*********************** Historial de Cotizaciones *****************************
//*******************************************************************************


	/*public function coutCotizacion()
	{
		if($this->input->post('Search')=="Search"){
		$Cad=trim($this->input->post('TB'));
		}else{
		$Cad="";
		}
		
		$Datos=array(
		'idc'=>trim($this->input->post('idc'))
		);
		
		$Estatus=array(
		"estatus"=>0
		);
		
		//$datos = $this->input->xss_clean($datos);
		$this->db->where($Datos);
		$this->db->where($Estatus);
		
		$this->db->like('serie',$Cad);
		$query = $this->db->get($this->_table['cotizaciones']);
		
		return($query->num_rows>0) ? $query->num_rows : 0;
	
	}//coutproducto*/
	
	public function contarClienteCotizaciones($idCotizacion,$idCliente=0)
	{
		$sql="select idCotizacion
		from cotizaciones
		where idCliente='$idCliente'
		and idLicencia='$this->idLicencia'
		and estatus='0'";
		
		$sql.= $idCotizacion!=0 ?" and idCotizacion='$idCotizacion'":'';
		
		return $this->db->query($sql)->num_rows();
	}

	public function obtenerClienteCotizaciones($numero,$limite,$idCotizacion,$idCliente=0)
	{
		$sql="select a.*,
		(select b.idSeguimiento from seguimiento as b where b.idCotizacion=a.idCotizacion order by b.fecha desc limit 1) as idSeguimiento
		from cotizaciones as a
		where a.idCliente='$idCliente'
		and a.estatus='0'
		and idLicencia='$this->idLicencia' ";
		
		$sql.= $idCotizacion!=0 ?" and a.idCotizacion='$idCotizacion'":'';
		
		$sql .= " order by a.fecha desc
		limit $limite,$numero ";
		
		return $this->db->query($sql)->result();
	}

	function obtenerTotalRemisiones()
	{
		$idCliente	= $this->session->userdata('idClienteFicha');
		
		$sql="select coalesce(sum(a.total),0) as total
		from cotizaciones as a
		where a.idCliente='$idCliente'
		and a.idLicencia='$this->idLicencia' ";
			
		return $this->db->query($sql)->row()->total;
	}

	public function getDatosCotizaSerie($Serie)
	{
		$SQL="SELECT * FROM ".$this->_table['cotizaciones']." 
		WHERE serie='".$Serie."' 
		AND estatus=0";
		
		$consulta=$this->db->query($SQL);
		
		return $var = ($consulta->num_rows() == 1)? $consulta->row_array() : 0;
	}

	public function getByCotizacionAndDetallesVenta($idCotizacion)
	{
		$sql="select a.*, b.*,
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.id
		where idCotizacion='$idCotizacion'";
	
		$consulta=$this->db->query($sql);
		return ( $consulta->num_rows() > 0)?  $consulta->row_array() : NULL;
	}
	
	public function obtenerCotizacionVentaIva($idCotizacion)
	{
		$sql="select ivaPorcentaje
		from cotizaciones
		where idCotizacion='$idCotizacion' ";

		$iva	= $this->db->query($sql)->row();
		
		return $iva!=null?$iva->ivaPorcentaje:0;
	}
	
	public function obtenerCotizacionVenta($idCotizacion)
	{
		$sql="select a.*, b.empresa, b.idCliente,
		c.nombre as contacto,
		c.telefono, c.email
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join clientes_contactos as c
		on c.idCliente=b.idCliente
		where idCotizacion='$idCotizacion' ";

		return  $this->db->query($sql)->row();
	}
	
	public function obtenerCotizacionVentaDetalle($idCotizacion)
	{
		$sql="select a.*, b.empresa, b.idCliente
		from cotizaciones as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where idCotizacion='$idCotizacion' ";

		return  $this->db->query($sql)->row();
	}
	
	public function obtenerProductosVenta($idCotizacion)
	{
		$sql=" select a.*, b.nombre as producto,
		c.nombre as periodo, b.codigoInterno, c.idPeriodo,
		(select d.descripcion from unidades as d where d.idUnidad=b.idUnidad) as unidad,
		(select coalesce(sum(d.importe),0) from cotiza_productos_impuestos as d where d.idProducto=a.idProducto) as impuestos,

		b.idPedimento, concat(i.anio, '  ',i.aduana, '  ',i.patente, '  ',i.digitos) as pedimento, i.fecha
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto
		inner join produccion_periodos as c
		on b.idPeriodo=c.idPeriodo

		left join productos_pedimentos as i
		on b.idPedimento=i.idPedimento

		where a.idCotizacion='$idCotizacion' ";

		return  $this->db->query($sql)->result();
	}
	
	public function obtenerImpuestosCotizacion($idProducto)
	{
		$sql="select * from cotiza_productos_impuestos
		where idProducto='$idProducto' ";

		return  $this->db->query($sql)->result();
	}
	
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	//PARA LA FACTURACIÓN DEL SAT
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	public function obtenerFacturaCotizaciones($criterio,$inicio,$fin,$idCliente=0,$idCotizacion=0,$idFactura=0,$idEstacion=0,$traspasos=0,$saldo=0)
	{
		$sql=" select b.idFactura, concat(b.serie, b.folio) as factura
		from cotizaciones as a
		inner join facturas as b
		on a.idCotizacion=b.idCotizacion
		where a.estatus='1'
		and a.pendiente='1' ";
		
		$sql.=$idFactura>0?" and b.idFactura='$idFactura' ":'';
		$sql.=$idCliente>0?" and a.idCliente='$idCliente' ":'';
		$sql.=$idCotizacion>0?" and a.idCotizacion='$idCotizacion' ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		$sql.=strlen($criterio)>0?" and a.ordenCompra like '%$criterio%' ":"";
		$sql.="and a.fechaCompra between '$inicio' and  '$fin' ";
		
		$sql.=$traspasos==1?" and a.idSucursal>0 ":'';
		$sql.=$traspasos==2?" and a.idSucursal=0 ":'';
		
		$sql.=$saldo==1?" and a.total!=(select coalesce(sum(h.pago),0) from catalogos_ingresos as h where h.idVenta=a.idCotizacion and h.idForma!=4) and a.cancelada='0'  ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function contarCotizacionesClientes($criterio,$inicio,$fin,$idCliente=0,$idCotizacion=0,$idFactura=0,$permiso=0,$idEstacion=0,$traspasos=0,$saldo=0)
	{
		$sql="select a.idCotizacion
		from cotizaciones as a 
		inner join clientes as c
		on c.idCliente=a.idCliente
		where estatus='1'
		and a.activo='1'
		and a.idLicencia=$this->idLicencia ";
		
		$sql.=" and a.pendiente='0' ";
		
		#$sql.=$idFactura>0?" and (select count(c.idFactura) from facturas as c where c.idCotizacion=a.idCotizacion and c.idFactura='$idFactura' limit 1) > 0 ":'';
		/*$sql.=$idFactura==1?" and a.idFactura=0 and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') = 0 
		and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') = 0 ":'';*/
		$sql.=$idFactura==1?" and a.prefactura='0' ":'';
		
		$sql.=$idFactura==2?" and a.idFactura>0 and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0' and d.documento!='TRASLADO') > 0 ":'';
		#$sql.=$idFactura==3?" and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') > 0 ":'';
		$sql.=$idFactura==3?" and a.prefactura='1' ":'';
		
		$sql.=$idFactura==4?" and a.cancelada='1' ":'';
		
		$sql.=$idCliente>0?" and a.idCliente='$idCliente' ":'';
		$sql.=$idCotizacion>0?" and idCotizacion='$idCotizacion' ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		
		$sql.=$traspasos==1?" and a.idSucursal>0 ":'';
		$sql.=$traspasos==2?" and a.idSucursal=0 ":'';
		
		$sql.=$saldo==1?" and a.total!=(select coalesce(sum(h.pago),0) from catalogos_ingresos as h where h.idVenta=a.idCotizacion and h.idForma!=4) and a.cancelada='0'  ":'';
		
		$sql.=strlen($criterio)>0?" and (a.ordenCompra like '$criterio%' or a.folio like '$criterio%'  
		or c.empresa like '$criterio%' or c.alias like '$criterio%' 
		
		or (select count(c.idFactura) from facturas as c where c.idCotizacion=a.idCotizacion and concat(c.serie,c.folio) like '%$criterio%'  limit 1) > 0
		
		  )":"";
		
		
		$sql.="and fechaCompra between '$inicio' and  '$fin' ";
		$sql.=$permiso==0?" and a.idUsuario='$this->_user_id' ":'';
		
		return $this->db->query($sql)->num_rows();
	}

	public function obtenerVentasClientes($numero,$limite,$criterio,$inicio,$fin,$idCliente=0,$idCotizacion=0,$idFactura=0,$orden='desc',$permiso=0,$idEstacion=0,$traspasos=0,$saldo=0)
	{
		#$idCliente		=$this->session->userdata('idClienteFicha');

		$sql=" select a.folio, a.idCotizacion, a.idCliente, a.envio, a.fechaCompra, a.cancelada, a.total, a.fecha, a.ordenCompra, a.prefactura, a.idTienda, a.idForma, a.idSucursal, a.idFactura,
		(select b.nombre from configuracion_estaciones as b where b.idEstacion=a.idEstacion) as estacion,
		c.empresa as cliente,
		(select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.cancelada='0' and d.pendiente='0' and d.documento!='TRASLADO' ) as numeroFacturas,
		(select coalesce(sum(d.cantidad),0) from ventas_entrega_detalles as d
		inner join cotiza_productos as e
		on e.idProducto=d.idProducto		
		where e.idCotizacion=a.idCotizacion ) as numeroEntregados, 
		0 as idSeguimiento,
		
		(select b.nombre from catalogos_formas as b where b.idForma=a.idForma  limit 1) as formaPagoVenta,
		(select b.nombre from catalogos_formas as b inner join catalogos_ingresos as c on b.idForma=c.idForma where c.idVenta=a.idCotizacion  limit 1) as formaPagoIngreso,
		0 as devoluciones
		
		 ";
		
		#(select coalesce(sum(b.importe),0) from cotizaciones_devoluciones as b where b.idCotizacion=a.idCotizacion) as devoluciones
	
		$sql.=" from cotizaciones as a 
		inner join clientes as c
		on c.idCliente=a.idCliente
		where a.estatus='1'
		and a.activo='1'
		and a.idLicencia=$this->idLicencia ";
		
		$sql.=" and a.pendiente='0' ";
		
		#$sql.=$idFactura>0?" and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.idFactura='$idFactura' limit 1) > 0 ":'';
		/*$sql.=$idFactura==1?" and a.idFactura=0 and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') = 0 
		and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') = 0 ":'';*/
		$sql.=$idFactura==1?" and a.prefactura='0' ":'';
		
		$sql.=$idFactura==2?" and a.idFactura>0 and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0' and d.documento!='TRASLADO') > 0 ":'';
		#$sql.=$idFactura==3?" and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') > 0 ":'';
		$sql.=$idFactura==3?" and a.prefactura='1' ":'';
		$sql.=$idFactura==4?" and a.cancelada='1' ":'';
		
		$sql.=$traspasos==1?" and a.idSucursal>0 ":'';
		$sql.=$traspasos==2?" and a.idSucursal=0 ":'';
		
		$sql.=$saldo==1?" and a.total!=(select coalesce(sum(h.pago),0) from catalogos_ingresos as h where h.idVenta=a.idCotizacion and h.idForma!=4) and a.cancelada='0' ":'';
		
		$sql.=$idCliente>0?" and a.idCliente='$idCliente' ":'';
		$sql.=$idCotizacion>0?" and a.idCotizacion='$idCotizacion' ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		$sql.=strlen($criterio)>0?" and (a.ordenCompra like '$criterio%' or a.folio like '$criterio%'  or c.empresa like '$criterio%' or c.alias like '$criterio%' 
		
		or (select count(c.idFactura) from facturas as c where c.idCotizacion=a.idCotizacion and concat(c.serie,c.folio) like '%$criterio%' limit 1) > 0 )":"";
		$sql.=$permiso==0?" and a.idUsuario='$this->_user_id' ":'';
		$sql.=" and a.fechaCompra between '$inicio' and  '$fin'
		order by abs(a.folio) $orden ";
		
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		#echo '<br /><br />'.$sql;
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerVentasRegistroClientes($numero,$limite,$criterio,$inicio,$fin,$idCliente=0,$idCotizacion=0,$idFactura=0,$orden='desc',$permiso=0,$idEstacion=0,$traspasos=0,$saldo=0)
	{
		#$idCliente		=$this->session->userdata('idClienteFicha');

		$sql=" select a.idCliente,
		c.empresa as cliente
		
		from cotizaciones as a
		inner join clientes as c
		on c.idCliente=a.idCliente
		where a.estatus='1'
		and a.activo='1'
		and a.idLicencia=$this->idLicencia ";
		
		$sql.=" and a.pendiente='0' ";
		
		#$sql.=$idFactura>0?" and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.idFactura='$idFactura' limit 1) > 0 ":'';
		/*$sql.=$idFactura==1?" and a.idFactura=0 and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') = 0 
		and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') = 0 ":'';*/
		$sql.=$idFactura==1?" and a.prefactura='0' ":'';
		
		$sql.=$idFactura==2?" and a.idFactura>0 and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') > 0 ":'';
		#$sql.=$idFactura==3?" and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') > 0 ":'';
		$sql.=$idFactura==3?" and a.prefactura='1' ":'';
		$sql.=$idFactura==4?" and a.cancelada='1' ":'';
		
		$sql.=$traspasos==1?" and a.idSucursal>0 ":'';
		$sql.=$traspasos==2?" and a.idSucursal=0 ":'';
		
		$sql.=$saldo==1?" and a.total!=(select coalesce(sum(h.pago),0) from catalogos_ingresos as h where h.idVenta=a.idCotizacion and h.idForma!=4) and a.cancelada='0' ":'';
		
		#$sql.=$idCliente>0?" and a.idCliente='$idCliente' ":'';
		$sql.=$idCotizacion>0?" and a.idCotizacion='$idCotizacion' ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		
		$sql.=$permiso==0?" and a.idUsuario='$this->_user_id' ":'';
		$sql.="and a.fechaCompra between '$inicio' and  '$fin' ";
		
		$sql.=" group by a.idCliente
		order by c.empresa asc ";
		
		#$sql .= $numero>0?" limit $limite,$numero ":'';
		
		#echo '<br /><br />'.$sql;
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerVentasClientesReporte($numero,$limite,$criterio,$inicio,$fin,$idCliente=0,$idCotizacion=0,$idFactura=0,$orden='desc',$permiso=0,$idEstacion=0,$traspasos=0,$saldo=0)
	{
		#$idCliente		=$this->session->userdata('idClienteFicha');

		$sql=" select a.*, 
		(select b.nombre from tiendas as b where b.idTienda=a.idTienda) as tienda,
		(select b.nombre from configuracion_estaciones as b where b.idEstacion=a.idEstacion) as estacion,
		c.empresa as cliente,
		(select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.cancelada='1') as numeroFacturas,
		(select coalesce(sum(d.cantidad),0) from ventas_entrega_detalles as d
		inner join cotiza_productos as e
		on e.idProducto=d.idProducto		
		where e.idCotizacion=a.idCotizacion ) as numeroEntregados,
		
		(select b.idSeguimiento from seguimiento as b where b.idVenta=a.idCotizacion order by b.fecha desc limit 1) as idSeguimiento,
		
		(select b.nombre from catalogos_formas as b where b.idForma=a.idForma  limit 1) as formaPagoVenta,
		(select b.nombre from catalogos_formas as b inner join catalogos_ingresos as c on b.idForma=c.idForma where c.idVenta=a.idCotizacion  limit 1) as formaPagoIngreso,
		
		
		(select coalesce(sum(b.importe),0) from cotizaciones_devoluciones as b where b.idCotizacion=a.idCotizacion) as devoluciones ";
		 
	
		$sql.=" from cotizaciones as a
		inner join clientes as c
		on c.idCliente=a.idCliente
		where a.estatus='1'
		and a.activo='1'
		and a.idLicencia=$this->idLicencia ";
		
		$sql.=" and a.pendiente='0'
		and a.prefactura='0' ";
		
		#$sql.=$idFactura>0?" and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.idFactura='$idFactura' limit 1) > 0 ":'';
		/*$sql.=$idFactura==1?" and a.idFactura=0 and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') = 0 
		and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') = 0 ":'';
		
		$sql.=$idFactura==2?" and a.idFactura>0 and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') > 0 ":'';
		$sql.=$idFactura==3?" and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') > 0 ":'';*/
		
		$sql.=$idCliente>0?" and a.idCliente='$idCliente' ":'';
		$sql.=$idCotizacion>0?" and a.idCotizacion='$idCotizacion' ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		
		$sql.=$traspasos==1?" and a.idSucursal>0 ":'';
		$sql.=$traspasos==2?" and a.idSucursal=0 ":'';
		
		$sql.=$saldo==1?" and a.total!=(select coalesce(sum(h.pago),0) from catalogos_ingresos as h where h.idVenta=a.idCotizacion and h.idForma!=4)  ":'';
		
		
		$sql.=strlen($criterio)>0?" and (a.ordenCompra like '$criterio%' or a.folio like '$criterio%'  or c.empresa like '$criterio%'
		
		or (select count(c.idFactura) from facturas as c where c.idCotizacion=a.idCotizacion and concat(c.serie,c.folio) like '%$criterio%' limit 1) > 0 )":"";
		$sql.=$permiso==0?" and a.idUsuario='$this->_user_id' ":'';
		$sql.="and date(a.fechaCompra) between '$inicio' and  '$fin'
		order by estacion asc, a.folio $orden ";
		
		$sql .= $numero>0?" limit $limite,$numero ":'';
		
		#echo '<br /><br />'.$sql;
		
		return $this->db->query($sql)->result();
	}
	
	public function sumarVentasClientes($criterio,$inicio,$fin,$idCliente=0,$idCotizacion=0,$idFactura=0,$permiso=0,$idEstacion=0,$traspasos=0,$saldo=0)
	{
		#$idCliente		=$this->session->userdata('idClienteFicha');

		$sql=" select coalesce(sum(a.total),0) as ventas,
		
		sum((select coalesce(sum(c.pago),0)
		from catalogos_ingresos as c
		where c.idVenta=a.idCotizacion
		and c.idForma!=4 )) as pagado,
		
		sum((select coalesce(sum(c.total),0)
		from facturas as c
		where c.idCotizacion=a.idCotizacion
		and c.cancelada='0'
		and c.pendiente='0')) as parciales ";

		$sql.=" from cotizaciones as a
		inner join clientes as b
		on b.idCliente=a.idCliente
		where a.estatus='1'
		and a.activo='1'
		and a.cancelada='0'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=" and a.pendiente='0' ";
		
		#$sql.=$idFactura>0?" and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.idFactura='$idFactura' limit 1) > 0 ":'';
		/*$sql.=$idFactura==1?" and a.idFactura=0 and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') = 0 
		and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') = 0 ":'';*/
		$sql.=$idFactura==1?" and a.prefactura='0' ":'';
		
		$sql.=$idFactura==2?" and a.idFactura>0 and (select count(d.idFactura) from facturas as d where d.idFactura=a.idFactura and d.cancelada='0') > 0 ":'';
		#$sql.=$idFactura==3?" and (select count(d.idFactura) from facturas as d where d.idCotizacion=a.idCotizacion and d.pendiente='1') > 0 ":'';
		$sql.=$idFactura==3?" and a.prefactura='1' ":'';
		$sql.=$idFactura==4?" and a.cancelada='1' ":'';
		
		$sql.=$traspasos==1?" and a.idSucursal>0 ":'';
		$sql.=$traspasos==2?" and a.idSucursal=0 ":'';
		
		$sql.=$saldo==1?" and a.total!=(select coalesce(sum(h.pago),0) from catalogos_ingresos as h where h.idVenta=a.idCotizacion and h.idForma!=4) and a.cancelada='0' ":'';
		
		$sql.=$idCliente>0?" and a.idCliente='$idCliente' ":'';
		$sql.=$idCotizacion>0?" and a.idCotizacion='$idCotizacion' ":'';
		$sql.=$idEstacion>0?" and a.idEstacion='$idEstacion' ":'';
		$sql.=strlen($criterio)>0?" and (a.ordenCompra like '$criterio%' or a.folio like '$criterio%'  or b.empresa like '$criterio%' or b.alias like '$criterio%' 
		or (select count(c.idFactura) from facturas as c where c.idCotizacion=a.idCotizacion and concat(c.serie,c.folio) like '%$criterio%' limit 1) > 0 )":"";
		$sql.=$permiso==0?" and a.idUsuario='$this->_user_id' ":'';
		$sql.=" and date(a.fechaCompra) between '$inicio' and  '$fin' ";

		#echo $sql;
		return $this->db->query($sql)->row();
	}
	
	public function comprobarCotizacionFactura($idCotizacion)
	{
		$sql=" select count(idFactura) as numero
		from facturas
		where idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->row()->numero;
	}

	public function getDatosCotizaSerieOrden($Serie)
	{
		$SQL="SELECT * FROM ".$this->_table['cotizaciones']." 
		WHERE serie='".$Serie."' AND estatus=1";
		
		$consulta=$this->db->query($SQL);
		
		return $var = ($consulta->num_rows() == 1)? $consulta->row_array() : 0;
	}

	public function realizarPago()
	{
		$this->db->trans_start(); 
		
		$pago					= $this->input->post('montoPagar');
		$iva					= $this->input->post('txtIvaPorcentaje');
		$iva					= $iva>0?$iva/100:0;
		$subTotal				= $pago/(1+$iva);
		
		$data = array
		(
			'idVenta'			=> $this->input->post('txtIdVenta'),
			'idCuenta'			=> $this->input->post('cuentasBanco'),
			'transferencia'		=> $this->input->post('numeroTransferencia'),
			'cheque'			=> $this->input->post('numeroCheque'),
			'formaPago'			=> '',
			'fecha'				=> $this->input->post('txtFechaIngreso'),
			'idLicencia'		=> $this->idLicencia,
			'concepto'			=> $this->input->post('txtDescripcionProducto'),
			'producto'			=> $this->input->post('txtDescripcionProducto'),
			'idGasto'			=> $this->input->post('selectTipoGasto'),
			'idProducto'		=> $this->input->post('txtConcepto'),
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			'idDepartamento'	=> $this->input->post('selectDepartamento'),
			'idNombre'			=> $this->input->post('selectFormas')==2?$this->input->post('selectNombres'):0,
			'factura'			=> $this->input->post('txtFactura'),
			'comentarios'		=> $this->input->post('txtComentarios'),
			'idCliente'			=> $this->input->post('txtIdClienteCobro'),
			'idForma'			=> $this->input->post('selectFormas'),
			'remision'			=> $this->input->post('selectFacturaRemision'),
			'ivaTotal'			=> $pago-$subTotal,
			'iva'				=> $iva*100,
			'incluyeIva'		=> $iva>0?'1':'0',
			'pago'				=> $this->input->post('montoPagar'),
			'subTotal'			=> $subTotal,
			'idUsuario'			=> $this->_user_id,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_ingresos',$data);
		$idIngreso=$this->db->insert_id();
		
		$this->contabilidad->registrarPolizaIngreso($data['fecha'],$data['producto'],0,$data['pago'],$idIngreso); //REGISTRAR LA PÓLIZA DE INGRESO
		
		$this->procesarPagoProgramado($this->input->post('txtIdVenta'),$this->input->post('montoPagar'));
		
		$this->configuracion->registrarBitacora('Registrar cobro','Ventas',$this->input->post('txtDescripcionProducto').', Importe: $'.number_format($this->input->post('montoPagar'),decimales)); //Registrar bitácora
		
		/*$this->db->where('idCotizacion',$$this->input->post('idVenta'));
		$this->db->update('cotizaciones',array('pendiente'=>'0'));*/
		
		$this->ventas->registrarVentaSecuenciaServicios($this->input->post('txtIdVenta'));
		
		$archivo 		= $_FILES['txtComprobante']['name'];
		
		if(strlen($archivo)>0)
		{
			$idComprobante	= $this->administracion->subirFicheros($idIngreso,$archivo,$_FILES['txtComprobante']['size'],'0');
			
			move_uploaded_file($_FILES['txtComprobante']['tmp_name'], carpetaIngresos.basename($idComprobante."_".$archivo));
		}
		
		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
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
	
	/*public function realizarPago() //20 OCTUBRE 2016
	{
		$this->db->trans_start(); 
		
		$pago					= $this->input->post('montoPagar');
		$iva					= $this->input->post('iva');
		$iva					= $iva>0?$iva/100:0;
		$subTotal				= $pago/(1+$iva);
		
		$data = array
		(
			'idVenta'			=> $this->input->post('idVenta'),
			'idCuenta'			=> $this->input->post('cuentasBanco'),
			'transferencia'		=> $this->input->post('numeroTransferencia'),
			'cheque'			=> $this->input->post('numeroCheque'),
			'formaPago'			=> '',
			'fecha'				=> $this->input->post('fecha'),
			'idLicencia'		=> $this->idLicencia,
			'concepto'			=> $this->input->post('concepto'),
			'producto'			=> $this->input->post('concepto'),
			'idGasto'			=> $this->input->post('idGasto'),
			'idProducto'		=> $this->input->post('idProducto'),
			'nombreReceptor'	=> $this->input->post('nombreReceptor'),
			'idDepartamento'	=> $this->input->post('idDepartamento'),
			'idNombre'			=> $this->input->post('idNombre'),
			'factura'			=> $this->input->post('factura'),
			'comentarios'		=> $this->input->post('comentarios'),
			'idCliente'			=> $this->input->post('idCliente'),
			'idForma'			=> $this->input->post('idForma'),
			'remision'			=> $this->input->post('remision'),
			'ivaTotal'			=> $pago-$subTotal,
			'iva'				=> $iva*100,
			'incluyeIva'		=> $iva>0?'1':'0',
			'pago'				=> $this->input->post('montoPagar'),
			'subTotal'			=> $subTotal,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_ingresos',$data);
		
		$this->procesarPagoProgramado($this->input->post('idVenta'),$this->input->post('montoPagar'));
		
		$this->configuracion->registrarBitacora('Registrar cobro','Ventas',$this->input->post('concepto').', Importe: $'.number_format($this->input->post('montoPagar'),decimales)); //Registrar bitácora
		
		//$this->db->where('idCotizacion',$$this->input->post('idVenta'));
		//$this->db->update('cotizaciones',array('pendiente'=>'0'));
		
		$this->ventas->registrarVentaSecuenciaServicios($this->input->post('idVenta'));
		
		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
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
	}*/
	
	public function procesarPagoProgramado($idVenta,$monto)
	{
		$sql="select pago, idIngreso
		from catalogos_ingresos
		where idVenta='$idVenta'
		and idForma='4' ";
		 
		$programado	=$this->db->query($sql)->row();
		
		if($programado!=null)
		{
			if($monto==$programado->pago)
			{
				$this->db->where('idIngreso',$programado->idIngreso);
				$this->db->delete('catalogos_ingresos');
			}
			
			if($monto<$programado->pago)
			{
				$this->db->where('idIngreso',$programado->idIngreso);
				$this->db->update('catalogos_ingresos',array('pago'	=>$programado->pago-$monto));
			}
		}
	}
	
	public function obtenerCotizacion($idCotizacion)
	{
		$sql="select * from cotizaciones
		where idCotizacion='$idCotizacion'";
		
		return $this->db->query($sql)->row();
	}
	
	public function descuentoAdicional()
	{
		$idCotizacion=$this->input->post('idCotizacion');
		$cotizacion=$this->obtenerCotizacion($idCotizacion);
		
		$descuentoAdicional=$this->input->post('descuentoAdicional');
		
		$descuento=$cotizacion->descuento;
		$subTotal=$cotizacion->subTotal-(($descuento/100)*$cotizacion->subTotal);
		
		$descuento=$subTotal*($descuentoAdicional/100);
		$subTotal=$subTotal-$descuento;

		$iva=$cotizacion->iva*$subTotal;
		$total=$subTotal+$iva;
		
		$descuento=(($descuento*100)/$cotizacion->subTotal)+$cotizacion->descuento;
		/*$descuento=$cotizacion->descuento+$descuentoAdicional;
		$subTotal=$cotizacion->subTotal-(($descuento/100)*$cotizacion->subTotal);
		$iva=$cotizacion->iva*$subTotal;
		$total=$subTotal+$iva;*/
		
		$data=array
		(
			//'subTotal'				=>$subTotal,
			'descuento'				=>$descuento,
			'iva'					=>$cotizacion->iva,
			'total'					=>$total,
			'descuentoAdicional'	=>$descuentoAdicional,
			'descuentoOriginal'		=>$cotizacion->descuento,
		);
		
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->update('cotizaciones',$data);
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function obtenerIngresoDetalle($idIngreso)
	{
		$sql="select producto, pago
		from catalogos_ingresos
		where idIngreso='$idIngreso' ";
		
		$ingreso	= $this->db->query($sql)->row();
		
		return $ingreso!=null?array($ingreso->producto,$ingreso->pago):array('','0');
	}
	
	public function borrarCobro($idIngreso)
	{
		$ingreso	= $this->obtenerIngresoDetalle($idIngreso);
		
		$this->db->where('idIngreso',$idIngreso);
		$this->db->delete('catalogos_ingresos');
		
		$this->configuracion->registrarBitacora('Borrar cobro','Ventas',$ingreso[0].', Importe: $'.number_format($ingreso[1],decimales)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}

	public function obtenerPagos($idVenta)
	{
		$sql="  select a.*, b.cuenta,
		c.nombre as formaPago
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		inner join catalogos_formas as c
		on a.idForma=c.idForma
		where a.idVenta='$idVenta'
		and a.idForma!='4' ";
		
		$sql.=" union 
		select a.*, '' as cuenta,
		b.nombre as formaPago
		from catalogos_ingresos as a
		inner join catalogos_formas as b
		on a.idForma=b.idForma
		where a.idVenta='$idVenta'
		and a.idCuenta='0'
		and a.idForma!='4' ";
			  
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPagado($idVenta)
	{
		$sql="select sum(pago) as pago 
		from catalogos_ingresos
		where idVenta='".$idVenta."'
		and idForma!='4'";
		
		$sql.=sistemaActivo=='olyess'?" and acrilico='0' ":'';
			  
		return $this->db->query($sql)->row();
	}
	
	/*public function obtenerUltimoPago($idVenta)
	{
		$sql=" select idDepartamento, idNombre, idProducto, idGasto
		from catalogos_ingresos
		where idVenta='$idVenta'
		order by fecha desc
		limit 1 ";
			  
		return $this->db->query($sql)->row();
	}*/
	
	public function obtenerUltimoPago($idCliente)
	{
		$sql=" select a.idDepartamento, a.idNombre, a.idProducto, a.idGasto,
		a.idCuenta, b.idBanco, a.idForma
		from catalogos_ingresos as a
		inner join cuentas as b
		on a.idCuenta=b.idCuenta
		where a.idCliente=$idCliente
		order by a.idIngreso desc
		limit 1 ";
		
		#echo $sql;
		return $this->db->query($sql)->row();
	}
	
	public function sumarPagado($idVenta)
	{
		$sql="select coalesce(sum(pago),0) as pago 
		from catalogos_ingresos
		where idVenta='".$idVenta."'
		and idForma!=4 ";
		
		$sql.=sistemaActivo=='olyess'?" and acrilico='0' ":'';
			  
		return $this->db->query($sql)->row()->pago;
	}

	public function obtenerClientes()
	{
		$sql="select idCliente, empresa 
		from clientes
		where activo='1'
		and idLicencia='$this->idLicencia'
		order by empresa asc";	
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCliente($idCliente)
	{
		$sql="select a.*,
		
		(select concat(b.descripcion,'(',b.numeroCuenta,')') from fac_catalogos_cuentas_detalles as b where b.idCuentaCatalogo=a.idCuentaCatalogo) cuenta ,
		
		
		(select b.nombre from clientes_fuentes as b where b.idFuente=a.idFuente) fuente,
		
		(select b.idContacto from clientes_contactos as b where b.idCliente=a.idCliente order by b.idContacto desc limit 1) idContacto 
		
		
		".(sistemaActivo=='IEXE'?", (select b.nombre from clientes_campanas as b where b.idCampana=a.idCampana) as campana":'')."
		".(sistemaActivo=='IEXE'?", (select b.idPrograma from clientes_academicos as b where b.idCliente=a.idCliente limit 1) as idPrograma":'')."
		".(sistemaActivo=='IEXE'?", (select b.preinscrito from clientes_academicos as b where b.idCliente=a.idCliente limit 1) as preinscrito":'')."
		".(sistemaActivo=='IEXE'?", (select count(b.idPrograma) from clientes_programas_ventas as b inner join clientes_academicos as c on c.idPrograma=b.idPrograma where b.idCliente=a.idCliente limit 1) as numeroVentas":'')."
		".(sistemaActivo=='IEXE'?", (select  concat(b.nombre,' ', b.apellidoPaterno,' ',b.apellidoMaterno)  from usuarios as b where b.idUsuario=a.idPromotor) as promotor":'')."
		from clientes as a
		where a.idCliente='$idCliente'";	
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerProgramaCliente($idCliente)
	{
		$sql=" select a.idPrograma, a.nombre
		from clientes_programas as a
		inner join clientes_academicos as b
		on a.idPrograma=b.idPrograma
		where b.idCliente='$idCliente'";	
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerCuentasCliente($idCliente)
	{
		$sql=" select a.cuenta, a.clabe, 
		b.nombre as banco, a.idCuenta,
		(select c.nombre from configuracion_emisores as c where c.idEmisor=a.idEmisor) as emisor
		from cuentas as a
		inner join bancos as b
		on a.idBanco=b.idBanco
		where a.idCliente='$idCliente' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerClienteSoap($idCliente)
	{
		$sql="select * from clientes
			where idCliente='$idCliente'";	
		
		$query=$this->db->query($sql);
		$query=$query->row();
		#echo $sql;
		
		if($query!=null)
		{
			$data[0]=$query->empresa;
			$data[1]=$query->localidad;
		}
		else
		{
			$data=null;
		}
		
		return($data); 
	}
	
	public function obtenerClientesZona($idZona)
	{
		$sql="select id, empresa 
		from clientes
		where block='0'
		and idZona='$idZona'
		and idLicencia='$this->idLicencia'
		order by empresa asc";	
		
		if($idZona=='0')
		{
			$sql="select id, empresa 
			from clientes
			where block='0'
			and idLicencia='$this->idLicencia'
			order by empresa asc";
		}
		
		$query=$this->db->query($sql);
		
		return($query->result_array()); 
	}
	
	public function obtenerDatosCliente($idCliente)
	{
		$sql=" select a.*,
		(select concat(b.descripcion,'(',b.numeroCuenta,')') from fac_catalogos_cuentas_detalles as b where b.idCuentaCatalogo=a.idCuentaCatalogo) cuenta 
		from clientes as a
		where a.idCliente='$idCliente' ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerPrecioCliente($idCliente)
	{
		$sql="select precio
		from clientes
		where idCliente='$idCliente' ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function remisiones($remision)
	{
		$remision=str_replace('-',' ', $remision);
		$query=$this->db->query($remision);
	}
	
	public function contarSeguimientoCliente($idCliente,$inicio,$fin,$permiso=1,$tipo='0')
	{
		$sql=" select a.idSeguimiento
		from seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		where a.idCliente='$idCliente'
		and tipo='$tipo'
		and a.idLicencia='$this->idLicencia' ";	
		
		$sql.=" and a.fecha between '$inicio' and '$fin' ";
		$sql.=$permiso==0?" and a.idResponsable='$this->_user_id' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerSeguimientoCliente($numero,$limite,$idCliente,$inicio,$fin,$permiso=1,$tipo='0')
	{
		$sql=" select a.*, b.nombre as servicio,
		concat(c.nombre,' ', c.apellidoPaterno,' ',c.apellidoMaterno) as responsable,
		d.nombre as status, d.color, d.idStatusIgual,
		
		(select concat('Folio: ',g.folio, ', Serie: ', g.serie) from cotizaciones as g where g.idCotizacion=a.idCotizacion) as cotizacion,
		(select concat('Folio: ',g.folio, ', Orden: ', g.ordenCompra) from cotizaciones as g where g.idCotizacion=a.idVenta) as venta,
		
		(select g.nombre from seguimiento_estatus as g where g.idEstatus=a.idEstatus) as estatus,
		(select g.color from seguimiento_estatus as g where g.idEstatus=a.idEstatus) as estatusColor
		
		".(sistemaActivo=='IEXE'?", (select  concat(g.nombre,' ', g.apellidoPaterno,' ',g.apellidoMaterno)  from usuarios as g where g.idUsuario=a.idUsuarioRegistro) as usuarioRegistro":'')."
		
		from seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		
		inner join seguimiento_status as d
		on a.idStatus=d.idStatus
		
		where a.idCliente='$idCliente'
		and a.tipo='$tipo'
		and a.idLicencia='$this->idLicencia' ";	
		
		$sql.=" and a.fecha between '$inicio' and '$fin' ";
		$sql.=$permiso==0?" and a.idResponsable='$this->_user_id' ":'';
		
		$sql .= " order by fecha desc
		limit $limite,$numero ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerUltimoSeguimiento($idCliente,$permiso=0)
	{
		$sql="select a.*, b.nombre as servicio,
		d.nombre as status, d.color,
		concat(c.nombre,' ',c.apellidoPaterno,' ', c.apellidoMaterno) as responsable,
		
		(select g.nombre from seguimiento_estatus as g where g.idEstatus=a.idEstatus) as estatus,
		(select g.color from seguimiento_estatus as g where g.idEstatus=a.idEstatus) as estatusColor
		
		
		from seguimiento as a
		inner join seguimiento_servicios as b
		on a.idServicio=b.idServicio
		inner join usuarios as c
		on a.idResponsable=c.idUsuario
		inner join seguimiento_status as d
		on d.idStatus=a.idStatus
		where a.idCliente='$idCliente'
		and a.idLicencia='$this->idLicencia' ";
		
		$sql.=$permiso==0?" and a.idResponsable='$this->_user_id' ":'';
		
		$sql.=" order by fecha desc
		limit 1 ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerServicio($idServicio)
	{
		$sql="select * from seguimiento_servicios
		where idServicio='$idServicio'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerCampanaCliente($idCliente)
	{
		$sql=" select idCampana from clientes
		where idCliente='$idCliente' ";
		
		$campana	= $this->db->query($sql)->row();
		
		return $campana!=null?$campana->idCampana:0;
	}
	
	public function obtenerCampanaClienteSeguimiento($idSeguimiento)
	{
		$sql=" select a.idCampana 
		from clientes as a
		inner join seguimiento as b
		on a.idCliente=b.idCliente
		where b.idSeguimiento='$idSeguimiento' ";
		
		$campana	= $this->db->query($sql)->row();
		
		return $campana!=null?$campana->idCampana:0;
	}
	
	public function obtenerSeguimiento($idSeguimiento)
	{
		$sql=" select a.*, b.empresa,
		d.nombre as status, d.idStatusIgual,
		concat(e.nombre,' ',e.apellidoPaterno,' ',e.apellidoMaterno) as responsable,
		f.nombre as contacto, f.telefono,
		(select g.nombre from seguimiento_tiempos as g where g.idTiempo=a.idTiempo) as tiempo,
		(select concat('Folio: ',g.folio, ', Serie: ', g.serie) from cotizaciones as g where g.idCotizacion=a.idCotizacion) as cotizacion,
		(select concat('Folio: ',g.folio, ', Orden: ', g.ordenCompra) from cotizaciones as g where g.idCotizacion=a.idVenta) as venta,
		(select g.nombre from seguimiento_servicios as g where g.idServicio=a.idServicio) as servicio,
		
		(select g.nombre from seguimiento_estatus as g where g.idEstatus=a.idEstatus) as estatus,
		(select g.color from seguimiento_estatus as g where g.idEstatus=a.idEstatus) as estatusColor
		
		".(sistemaActivo=='IEXE'?", (select  concat(g.nombre,' ', g.apellidoPaterno,' ',g.apellidoMaterno)  from usuarios as g where g.idUsuario=a.idUsuarioRegistro) as usuarioRegistro,
		
		(select g.nombre from clientes_programas as g inner join clientes_academicos as h on g.idPrograma=h.idPrograma where h.idCliente=b.idCliente) as programa,
		
		(select g.idArea from seguimiento_areas_conceptos as g where g.idConcepto=a.idConcepto limit 1) as idArea
		
		 ":'')."
		
		from seguimiento as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join seguimiento_status as d
		on a.idStatus=d.idStatus
		inner join usuarios as e
		on a.idResponsable=e.idUsuario
		inner join clientes_contactos as f
		on f.idContacto=a.idContacto
		where a.idSeguimiento='$idSeguimiento' ";	
		
		#echo $sql;
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerUltimoSeguimientoStatus($idStatus,$idCliente,$permiso=0)
	{
		$sql=" select a.*, 
		d.nombre as status, d.idStatusIgual, d.color,
		concat(e.nombre,' ',e.apellidoPaterno,' ',e.apellidoMaterno) as responsable,
		(select g.nombre from seguimiento_tiempos as g where g.idTiempo=a.idTiempo) as tiempo,
		(select g.nombre from seguimiento_servicios as g where g.idServicio=a.idServicio) as servicio,
		(select concat('Folio: ',g.folio, ', Serie: ', g.serie) from cotizaciones as g where g.idCotizacion=a.idCotizacion) as cotizacion,
		(select concat('Folio: ',g.folio, ', Orden: ', g.ordenCompra) from cotizaciones as g where g.idCotizacion=a.idVenta) as venta,
		
		(select g.nombre from seguimiento_estatus as g where g.idEstatus=a.idEstatus) as estatus,
		(select g.color from seguimiento_estatus as g where g.idEstatus=a.idEstatus) as estatusColor
		
		from seguimiento as a
		inner join seguimiento_status as d
		on a.idStatus=d.idStatus
		inner join usuarios as e
		on a.idResponsable=e.idUsuario
		where a.idStatus='$idStatus'
		and a.idCliente='$idCliente'
		and a.idLicencia='$this->idLicencia' ";	
		
		$sql.=$permiso==0?" and a.idResponsable='$this->_user_id' ":'';
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerUltimoSeguimientoEstatus($idEstatus,$idCliente,$permiso=0)
	{
		$sql=" select a.*, 
		
		d.nombre as status, d.idStatusIgual, d.color,
		
		concat(e.nombre,' ',e.apellidoPaterno,' ',e.apellidoMaterno) as responsable,
		(select g.nombre from seguimiento_tiempos as g where g.idTiempo=a.idTiempo) as tiempo,
		(select g.nombre from seguimiento_servicios as g where g.idServicio=a.idServicio) as servicio,
		(select concat('Folio: ',g.folio, ', Serie: ', g.serie) from cotizaciones as g where g.idCotizacion=a.idCotizacion) as cotizacion,
		(select concat('Folio: ',g.folio, ', Orden: ', g.ordenCompra) from cotizaciones as g where g.idCotizacion=a.idVenta) as venta,
		
		h.nombre as estatus,
		h.color as estatusColor,
		
		i.fechaSeguimiento, i.horaInicial, i.horaFinal
		
		from seguimiento as a
		inner join seguimiento_status as d
		on a.idStatus=d.idStatus
		inner join usuarios as e
		on a.idResponsable=e.idUsuario
		
		inner join seguimiento_estatus as h
		on a.idEstatus=h.idEstatus
		
		
		inner join seguimiento_detalles as i
		on a.idSeguimiento=i.idSeguimiento
		
		
		where a.idCliente='$idCliente'
		and h.idEstatus='$idEstatus'
		and a.idLicencia='$this->idLicencia'
		order by i.fechaSeguimiento desc, i.horaInicial desc ";	
		
		$sql.=$permiso==0?" and a.idResponsable='$this->_user_id' ":'';
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerUltimoSeguimientoEstatusCliente($idCliente)
	{
		$sql=" select a.*, 
		
		d.nombre as status, d.idStatusIgual, d.color,
		
		concat(e.nombre,' ',e.apellidoPaterno,' ',e.apellidoMaterno) as responsable,
		(select g.nombre from seguimiento_tiempos as g where g.idTiempo=a.idTiempo) as tiempo,
		(select g.nombre from seguimiento_servicios as g where g.idServicio=a.idServicio) as servicio,
		(select concat('Folio: ',g.folio, ', Serie: ', g.serie) from cotizaciones as g where g.idCotizacion=a.idCotizacion) as cotizacion,
		(select concat('Folio: ',g.folio, ', Orden: ', g.ordenCompra) from cotizaciones as g where g.idCotizacion=a.idVenta) as venta,
		
		h.nombre as estatus,
		h.color as estatusColor,
		
		i.fechaSeguimiento, i.horaInicial, i.horaFinal
		
		from seguimiento as a
		inner join seguimiento_status as d
		on a.idStatus=d.idStatus
		inner join usuarios as e
		on a.idResponsable=e.idUsuario
		
		inner join seguimiento_estatus as h
		on a.idEstatus=h.idEstatus
		
		
		inner join seguimiento_detalles as i
		on a.idSeguimiento=i.idSeguimiento

		where a.idCliente='$idCliente'
		order by i.fechaSeguimiento desc, i.horaInicial desc ";	
	
		#echo $sql.'<br /><br />';
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerSeguimientito($idSeguimiento)
	{
		$sql="select a.*, b.empresa,
		d.nombre as status,
		e.nombre as responsable
		from seguimiento as a
		inner join clientes as b
		on a.idCliente=b.idCliente
	
		inner join seguimiento_status as d
		on a.idStatus=d.idStatus
		inner join seguimiento_responsables as e
		on a.idResponsable=e.idResponsable
		where a.idSeguimiento='$idSeguimiento'
		and a.idLicencia='$this->idLicencia'";	
		
		return $this->db->query($sql)->row();
	}
	
	public function sumarHoras($fecha,$horas)
	{
		$sql="select date_add('$fecha', interval ".$horas." hour) as fechaFinal ";
		
		return $this->db->query($sql)->row()->fechaFinal;
	}
	
	//REGISTRAR EL EVENTO EN EL CALENDARIO DE GOOGLE
	
	public function obtenerIdCalendario($idSeguimiento)
	{
		$sql=" select idCalendario, folio, idCliente, comentarios
		from seguimiento
		where idSeguimiento='$idSeguimiento' ";
		
		$seguimiento	= $this->db->query($sql)->row();
		
		#return $seguimiento!=null?$seguimiento->idCalendario:'';
		return $seguimiento!=null?array($seguimiento->idCalendario,$seguimiento->idCliente,$seguimiento->comentarios,$seguimiento->folio):array('','0','','');
		
	}
	
	public function borrarSeguimientoErp($idSeguimiento)
	{
		$calendario		= $this->obtenerIdCalendario($idSeguimiento);
		$idCalendario	= $calendario[0];
		
		if($idCalendario!='' and $this->session->userdata('conexionGmail')=='1')
		{
			$this->borrarEventoGoogle($idCalendario);
		}
		
		$this->db->where('idSeguimiento',$idSeguimiento);
		$this->db->delete('seguimiento');
		
		$this->db->where('idSeguimiento',$idSeguimiento);
		$this->db->delete('seguimiento_detalles');
		
		$this->configuracion->registrarBitacora('Borrar seguimiento','Clientes - Seguimiento',$this->obtenerClienteEmpresa($calendario[1]).', Folio: '.obtenerFolioSeguimiento($calendario[3]).', '.$calendario[2]); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function borrarEventoGoogle($eventId)
	{
		$cal 							= new Gcal();
		
		#$configObj['calendarId']		= 'primary';
		$configObj['calendarId']		= 'programador03.redisoft@gmail.com';
		$configObj['eventId']			= $eventId;
		$configObj['redirectURI']		= base_url().'principal/tableroControl';
		$evento							= $cal->eventDelete($configObj);
	}
	
	public function obtenerStatusCrm($idStatus)
	{
		$sql=" select nombre from seguimiento_status
		where idStatus='$idStatus' ";
		
		return $this->db->query($sql)->row()->nombre;
	}
	
	public function obtenerClienteCrm($idCliente)
	{
		$sql=" select empresa from clientes
		where idCliente='$idCliente' ";
		
		return $this->db->query($sql)->row()->empresa;
	}
	
	public function obtenerServicioCrm($idServicio)
	{
		$sql=" select nombre from seguimiento_servicios
		where idServicio='$idServicio' ";
		
		$servicio=$this->db->query($sql)->row();
		
		return $servicio!=null?$servicio->nombre:'';
	}
	
	public function obtenerFolioCrm($idSeguimiento)
	{
		$sql=" select folio 
		from seguimiento
		where idSeguimiento='$idSeguimiento' ";
		
		$seguimiento	= $this->db->query($sql)->row();
		
		return $seguimiento!=null?$seguimiento->folio:'';
	}
	
	public function editarSeguimientoCrm()
	{
		$idSeguimiento	= $this->input->post('idSeguimiento');
		#$folio			= $this->obtenerFolioCrm($idSeguimiento);
		#$seguimiento	= $this->obtenerIdCalendario($idSeguimiento);
		
		$data=array
		(
			'comentarios'		=> $this->input->post('comentarios'),
			'comentariosExtra'	=> $this->input->post('observaciones'),
			'fecha'				=> trim($this->input->post('fecha')),
			#'idCliente' 		=> $this->input->post('idCliente'),
			'idStatus' 			=> $this->input->post('idStatus'),
			'idServicio' 		=> $this->input->post('idServicio'),
			'idResponsable' 	=> $this->input->post('idResponsable'),
			#'monto' 			=> $this->input->post('monto'),
			'lugar'				=> $this->input->post('lugar'),
			'fechaCierre' 		=> trim($this->input->post('fechaCierre')),
			'bitacora' 			=> $this->input->post('bitacora'),
			'email' 			=> $this->input->post('email'),
			'idTiempo'			=> $this->input->post('idTiempo'),
			'idContacto'		=> $this->input->post('idContacto'),
			'idCotizacion'		=> $this->input->post('idCotizacion'),
			'idVenta'			=> $this->input->post('idVenta'),
			
			'idEstatus'			=> $this->input->post('idEstatus'),
			#'idUsuarioRegistro'	=> $this->input->post('idUsuarioRegistro'),
			
			#'horaCierreFin'		=> $this->input->post('horaCierreFin'),
			#'alerta'			=> $this->input->post('alerta'),
		);
		
		if(sistemaActivo=='IEXE')
		{
			$data['idUsuarioRegistro']	= $this->input->post('idUsuarioRegistro');
			$data['horaCierreFin']		= $this->input->post('horaCierreFin');
			$data['alerta']				= $this->input->post('alerta');
			
			if($this->input->post('idConcepto')>0)
			{
				$data['idConcepto']			= $this->input->post('idConcepto')>0?$this->input->post('idConcepto'):0;
			}
			
		}
		
		
		$data	= procesarArreglo($data);
		$this->db->where('idSeguimiento', $idSeguimiento);
		$this->db->update('seguimiento', $data);
		
		$calendario		= $this->obtenerIdCalendario($idSeguimiento);
		$idCalendario	= $calendario[0];
		
		if($idCalendario!='' and $this->session->userdata('conexionGmail')=='1')
		{
			$this->editarEventoGoogle(trim($this->input->post('fecha')),$idSeguimiento,$idCalendario);
		}
		
		$this->configuracion->registrarBitacora('Editar seguimiento','Clientes - Seguimiento',$this->obtenerClienteEmpresa($calendario[1]).', Folio: '.obtenerFolioSeguimiento($calendario[3]).', '.$this->input->post('comentarios')); //Registrar bitácora
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}
	
	public function editarSeguimientoDiario()
	{
		$idSeguimiento	= $this->input->post('idSeguimiento');

		$data=array
		(
			'comentarios'		=> $this->input->post('comentarios'),
			'fechaCierre' 		=> trim($this->input->post('fechaCierre')),
			'idEstatus'			=> $this->input->post('idEstatus'),
			'horaCierreFin'		=> $this->input->post('horaCierreFin'),
			'alerta'			=> $this->input->post('alerta'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idSeguimiento', $idSeguimiento);
		$this->db->update('seguimiento', $data);

		#$this->configuracion->registrarBitacora('Editar seguimiento','Clientes - Seguimiento',$this->obtenerClienteEmpresa($calendario[1]).', Folio: '.obtenerFolioSeguimiento($calendario[3]).', '.$this->input->post('comentarios')); //Registrar bitácora
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}
	
	public function editarDatosGenerales()
	{
		$idCliente				= $this->input->post('idCliente');

		$data=array
		(
			'nombre'			=> $this->input->post('nombre'),
			'paterno' 			=> $this->input->post('paterno'),
			'materno'			=> $this->input->post('materno'),
			'fechaNacimiento'	=> $this->input->post('fechaNacimiento'),
			'idCampana'			=> $this->input->post('idCampana'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idCliente', $idCliente);
		$this->db->update('clientes', $data);

		#$this->configuracion->registrarBitacora('Editar seguimiento','Clientes - Seguimiento',$this->obtenerClienteEmpresa($calendario[1]).', Folio: '.obtenerFolioSeguimiento($calendario[3]).', '.$this->input->post('comentarios')); //Registrar bitácora
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}
	
	public function editarEventoGoogle($fecha,$idSeguimiento,$eventId)
	{
		$servicio				= '';
		
		if($this->input->post('idStatus')!=3)
		{
			$servicio	= "\n\nServicio: ".$this->obtenerServicioCrm($this->input->post('idServicio'));
		}
		
		$cal = new Gcal();
		
		#$configObj['calendarId']		= 'primary';
		$configObj['calendarId']		= 'programador03.redisoft@gmail.com';
		$configObj['eventId']			= $eventId;
		
		$configObj['start']				= strtotime($this->sumarHoras($fecha,6));
		$configObj['end']				= strtotime($this->sumarHoras($fecha,6));
		
		$configObj['description']		= $this->input->post('idStatus')!=3?$this->input->post('comentarios').$servicio:$this->input->post('bitacora');
		$configObj['location']			= $this->input->post('lugar');
		$configObj['summary']			= $this->obtenerStatusCrm($this->input->post('idStatus')).': '.$this->obtenerClienteCrm($this->input->post('idCliente'));
		$configObj['recurrence']		= '';
		$configObj['allday']			= false;
		$configObj['redirectURI']		= base_url().'principal/tableroControl';
		$configObj['timezone_offset']	= "Z";
		$configObj['colorId']			= obtenerColorStatus($this->input->post('idStatus'));
		
		$evento							= $cal->eventUpdate($configObj);
	}


	public function registrarEventoGoogle($fecha,$idSeguimiento)
	{
		$servicio				= '';
		
		if($this->input->post('idStatus')!=3)
		{
			$servicio	= "\n\nServicio: ".$this->obtenerServicioCrm($this->input->post('idServicio'));
		}
		
		$cal = new Gcal();
		
		$configObj['calendarId']		= 'primary';
		#$configObj['calendarId']		= 'licfloresdejesus@gmail.com';
		#$configObj['calendarId']		= 'programador03.redisoft@gmail.com';
		
		$configObj['start']				= strtotime($this->sumarHoras($fecha,6));
		$configObj['end']				= strtotime($this->sumarHoras($fecha,6));
		
		$configObj['description']		= $this->input->post('idStatus')!=3?$this->input->post('comentarios').$servicio:$this->input->post('bitacora');
		$configObj['location']			= $this->input->post('lugar');
		$configObj['summary']			= $this->obtenerStatusCrm($this->input->post('idStatus')).': '.$this->obtenerClienteCrm($this->input->post('idCliente'));
		$configObj['recurrence']		= '';
		$configObj['allday']			= false;
		$configObj['redirectURI']		= base_url().'principal/tableroControl';
		$configObj['timezone_offset']	= "Z";
		
		$configObj['colorId']			= obtenerColorStatus($this->input->post('idStatus'));
		
		$evento							= $cal->eventInsert($configObj);
		
		if(isset($evento->id))
		{
			$this->db->where('idSeguimiento',$idSeguimiento);
			$this->db->update('seguimiento',array('idCalendario'=>$evento->id));
		}
	}

	public function agregarSeguimiento()
	{
		$fecha					= trim($this->input->post('fecha'));
		$idStatus				= $this->input->post('idStatus');
		$comentarios			= $this->input->post('comentarios');
		$idResponsable			= $this->input->post('idResponsable');
		$idStatusIgual			= $this->input->post('idStatusIgual');
		
		$folio					= $this->crm->obtenerFolioSeguimientoCliente($this->input->post('tipo'));

		$data=array
		(
			'comentarios'		=> $comentarios,
			'bitacora' 			=> $this->input->post('bitacora'),
			'email' 			=> $this->input->post('email'),
			'fecha'				=> $fecha,
			'idCliente' 		=> $this->input->post('idCliente'),
			'idStatus' 			=> $idStatus,
			'idServicio' 		=> $this->input->post('idServicio'),
			'idResponsable' 	=> $idResponsable,
			'fechaCierre' 		=> trim($this->input->post('fechaCierre')),
			'lugar'				=> $this->input->post('lugar'),
			'tipo' 				=> 0,
			'idLicencia'		=> $this->idLicencia,
			'idTiempo'			=> $this->input->post('idTiempo'),
			'idContacto'		=> $this->input->post('idContacto'),
			'idCotizacion'		=> $this->input->post('idCotizacion'),
			'idVenta'			=> $this->input->post('idVenta'),
			
			'folio' 			=> $folio,
			
			
			'idEstatus'			=> $this->input->post('idEstatus'),
			'idUsuarioRegistro'	=> $this->input->post('idUsuarioRegistro'),
			
			'tipo'				=> $this->input->post('tipo'),
			'horaCierreFin'		=> $this->input->post('horaCierreFin'),
			'alerta'			=> $this->input->post('alerta'),
		);
		
		$data	= procesarArreglo($data);

		$this->db->insert('seguimiento', $data);
		$idSeguimiento	= $this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar seguimiento','Clientes - Seguimiento',$this->obtenerClienteEmpresa($this->input->post('idCliente')).', Folio: '.obtenerFolioSeguimiento($folio).', '.$comentarios); //Registrar bitácora
		
		if($idStatusIgual==4)
		{
			#$this->enviarCorreoLlamada($idResponsable,$comentarios,$fecha);
		}
		
		if($this->session->userdata('oauth_access_token') and $this->session->userdata('conexionGmail')=='1') //SI EL TOKEN ESTA ACTIVADO
		{
			$this->registrarEventoGoogle($fecha,$idSeguimiento);
		}
		
		if($this->input->post('tipo')=='1')
		{
			$data=array
			(
				'fechaRegistro'		=> $this->_fecha_actual,
				'idSeguimiento' 	=> $idSeguimiento,
				'fecha' 			=> $this->fecha,
				'hora' 				=> $this->hora,
				'observaciones' 	=> $comentarios,
				'fechaSeguimiento' 	=> $this->input->post('fechaCierre'),
				'horaInicial' 		=> $this->input->post('horaInicial'),
				'horaFinal' 		=> $this->input->post('horaCierreFin'),
				'alerta' 			=> $this->input->post('alerta'),
				'cero' 				=> '1',
			);
			
			$this->db->insert('seguimiento_detalles', $data);
			
			
			if($idStatus==6)
			{
				$this->subirArchivosSeguimientoProspecto($idSeguimiento);
			}
		}

		return $this->db->affected_rows() >=1?array('1',$idSeguimiento):array('0',errorRegistro);
	}
	
	public function subirArchivosSeguimientoProspecto($idSeguimiento)
	{
		$archivos	= $this->input->post('archivos');
		$id			= $this->input->post('id');
		
		for($i=0;$i<count($archivos);$i++)
		{
			#$archivo		= explode("-_",$archivos[$i]);
			
			$data=array
			(
				'idSeguimiento'	=> $idSeguimiento,
				'nombre'		=> $archivos[$i],
				'tamano'		=> filesize(carpetaSeguimientoClientes.$id.'-_'.$archivos[$i]),
				'fecha'			=> $this->_fecha_actual,
				'idUsuario'		=> $this->_user_id,
			);
			
			$this->db->insert('seguimiento_archivos',$data);
			$idArchivo	= $this->db->insert_id();

			rename(carpetaSeguimientoClientes.$id.'-_'.$archivos[$i], carpetaSeguimientoClientes.$idArchivo.'_'.$archivos[$i]); 

			#$this->db->where('idArchivo',$idArchivo);
			#$this->db->update('seguimiento_archivos',array('nombre'=>$idArchivo.'_'.$archivos[$i]));
		}
	}
	
	public function obtenerUsuario($idUsuario)
	{
		$sql="select idUsuario, concat(nombre,' ',apellidoPaterno,' ',apellidoMaterno) as nombre, correo
		from usuarios 
		where idUsuario='$idUsuario' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function enviarCorreoLlamadaIexe($idUsuario,$comentarios,$fecha,$folio='')
	{
		if(!empty($_POST))
		{
			$usuario			= $this->obtenerUsuario($idUsuario);
			$cliente			= $this->obtenerCliente($this->input->post('idCliente'));
			$emisor				= $this->obtenerUsuario($this->_user_id);
			$email				= $this->input->post('email');
			
			$remitente		=$emisor->correo;
			$destinatario	=$usuario->correo;
			#$destinatario	='programador03.redisoft@gmail.com';
			$asunto			='Llamada: '.$cliente->empresa;
			
			$mensaje		='<strong>Folio: </strong>'.obtenerFolioSeguimiento($folio).'<br />';
			$mensaje		.='<strong>Fecha: </strong>'.obtenerFechaMesCortoHora($fecha).'<br />';
			$mensaje		.='<strong>Responsable: </strong>'.$usuario->nombre.'<br />';
			$mensaje		.='<strong>Alumno/Cliente: </strong> '.$cliente->nombre.' '.$cliente->paterno.' '.$cliente->materno;
			$mensaje		.='<strong>Comentarios: </strong> '.$comentarios.'<br />';
			
			
			$this->load->library('email');
			#$this->email->from($remitente,$emisor->nombre);
			$this->email->from($remitente.', '.$email);
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
	
	public function enviarCorreoLlamada($idUsuario,$comentarios,$fecha)
	{
		if(!empty($_POST))
		{
			$usuario			=$this->obtenerUsuario($idUsuario);
			$cliente			=$this->obtenerCliente($this->input->post('idCliente'));
			$emisor				=$this->obtenerUsuario($this->_user_id);
			
			$remitente		=$emisor->correo;
			$destinatario	=$usuario->correo;
			#$destinatario	='programador03.redisoft@gmail.com';
			$asunto			='Llamada: '.$cliente->empresa;
			
			$mensaje		=' <strong>Responsable: </strong>'.$usuario->nombre.'<br />';
			$mensaje		.='<strong>Fecha: </strong>'.obtenerFechaMesCortoHora($fecha).'<br />';
			$mensaje		.='<strong>Comentarios: </strong> '.$comentarios.'<br />';
			$mensaje		.='<strong>Cliente: </strong> '.$cliente->empresa;
			
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
	
	
	
	public function confirmarSeguimiento()
	{
		$data=array
		(
			'activo'	=>0,
			'comentariosExtra'	=>$this->input->post('observaciones'),
		);
		
		$this->db->where('idSeguimiento',$this->input->post('idSeguimiento'));
		$this->db->update('seguimiento', $data);
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}//
	
	public function borrarCotizacion($idCotizacion)
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se elimina en de 2 tablas
		
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->delete('cotiza_productos');
		
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->delete('cotizaciones');
		
		$this->configuracion->registrarBitacora('Borrar cotización','Cotizaciones',$this->ventas->obtenerCotizacionSerie($idCotizacion)); //Registrar bitácora
		
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
	
	public function regresarProductosVenta($idCotizacion,$idTienda)
	{
		/*$sql=" select coalesce(sum(a.cantidad),0) as cantidad, a.idEntrega, b.idProduct,
		".($idTienda==0?" c.stock ":"(select coalesce(sum(e.cantidad),0) from tiendas_productos as e where e.idProducto=c.idProducto and e.idTienda='$idTienda') as stock")." 
		from ventas_entrega_detalles as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		inner join productos as c 
		on c.idProducto=b.idProduct
		where b.idCotizacion='$idCotizacion'
		and b.servicio=0 
		group by b.idProduct ";*/
		
		$sql=" select coalesce(sum(a.cantidad),0) as cantidad, 
		a.idEntrega, b.idProduct,
		(select coalesce(sum(c.cantidad),0) 
		from cotizaciones_devoluciones_detalles as c
		inner join cotizaciones_devoluciones as d
		on d.idDevolucion=c.idDevolucion
		where c.idProducto=b.idProducto
		and d.idCotizacion='$idCotizacion'
		and d.idTipo!=1 ) as devoluciones
		from ventas_entrega_detalles as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		where b.idCotizacion='$idCotizacion'
		and b.servicio=0
		group by b.idProducto  ";
		
		$entregados		= $this->db->query($sql)->result();
		
		foreach($entregados as $row)
		{
			$producto	= $this->inventarioProductos->obtenerProductoStock($row->idProduct);
			
			if($producto!=null)
			{
				/*$data=array
				(
					'stock' =>$producto->stock+($row->cantidad-$row->devoluciones)
				);
				
				$this->db->where('idProducto',$row->idProduct);
				$this->db->update('productos',$data);*/
				
				$this->inventarioProductos->actualizarStockProducto($row->idProduct,$row->cantidad,'sumar');
			}
			
			/*if($idTienda==0)
			{
				$data=array
				(
					'stock' =>$row->stock+$row->cantidad
				);
				
				$this->db->where('idProducto',$row->idProduct);
				$this->db->update('productos',$data);
			}
			
			if($idTienda>0)
			{
				$data=array
				(
					'cantidad' =>$row->stock+$row->cantidad
				);
				
				$this->db->where('idProducto',$row->idProduct);
				$this->db->where('idTienda',$idTienda);
				$this->db->update('tiendas_productos',$data);
			}*/
			
			/*$this->db->where('idEntrega',$row->idEntrega);
			$this->db->delete('ventas_entrega_detalles');*/
		}
	}
	
	public function borrarTablaEntrega($idCotizacion)
	{
		$sql="select a.idEntrega
		from ventas_entrega_detalles as a
		inner join cotiza_productos as b
		on a.idProducto=b.idProducto
		where b.idCotizacion='$idCotizacion' ";
		
		$entregas = $this->db->query($sql)->result();;
		
		foreach($entregas as $row)
		{
			$this->db->where('idEntrega',$row->idEntrega);
			$this->db->delete('ventas_entrega_detalles');
		}
	}
	
	public function obtenerTiendaCotizacion($idCotizacion=0)
	{
		$sql="select idTienda
		from cotizaciones
		where idCotizacion='$idCotizacion'";
		
		return $this->db->query($sql)->row()->idTienda;
	}
	
	public function obtenerVentaSucursal($idCotizacion=0)
	{
		$sql="select idSucursal, prefactura
		from cotizaciones
		where idCotizacion='$idCotizacion'";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarVenta($idCotizacion)
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se elimina en de 2 tablas
		
		$venta	= $this->obtenerVentaSucursal($idCotizacion);
		
		if($venta->idSucursal==0)
		{
			$this->regresarProductosVenta($idCotizacion,0);
		}
		
		if($venta->idSucursal>0)
		{
			$this->tiendas->borrarTraspasoVenta($idCotizacion);
		}
		
		
		$this->borrarTablaEntrega($idCotizacion);
		
		/*$this->db->where('idCotizacion',$idCotizacion);
		$this->db->delete('cotiza_productos');*/
		
		$this->db->where('idVenta',$idCotizacion);
		$this->db->delete('catalogos_ingresos');
		
		/*$this->db->where('idCotizacion',$idCotizacion);
		$this->db->delete('cotizaciones');*/
		
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->update('cotizaciones',array('activo' => '0'));
		
		$this->configuracion->registrarBitacora('Borrar venta','Ventas',$this->ventas->obtenerVentaOrden($idCotizacion)); //Registrar bitácora
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0','Error al borrar la venta');
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1','La venta se ha borrado correctamente');
		}
	}
	
	public function cancelarVenta($idCotizacion)
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se elimina en de 2 tablas
		
		$venta	= $this->obtenerVentaSucursal($idCotizacion);
		
		if($venta->idSucursal==0)
		{
			$this->regresarProductosVenta($idCotizacion,0);
		}
		
		if($venta->idSucursal>0)
		{
			$this->tiendas->borrarTraspasoVenta($idCotizacion);
		}
		
		$this->borrarTablaEntrega($idCotizacion);
		
		$this->db->where('idVenta',$idCotizacion);
		$this->db->delete('catalogos_ingresos');
		
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->update('cotizaciones',array('cancelada' => '1'));
		
		$this->configuracion->registrarBitacora('Cancelar venta','Ventas',$this->ventas->obtenerVentaOrden($idCotizacion)); //Registrar bitácora
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array('0','Error al cancelar la venta');
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1','La venta se ha cancelado correctamente');
		}
	}
	
	public function obtenerIdVenta()
	{
		$sql="select max(folio) as folio from 
		cotizaciones";
		
		return $this->db->query($sql)->row()->folio+1;
	}
	
	#La venta implica muchas operaciones :) 
	public function realizarVenta($productos,$idCliente) 
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas

		$folio=$this->obtenerIdVenta();
		
		/*$cantidad		=$this->input->post('cantidad');
		$productos		=$this->input->post('productos');
		$preciosTotales	=$this->input->post('preciosTotales');
		$precioProducto	=$this->input->post('precioProducto');*/
		
		$iva =$this->session->userdata('iva')/100;
		
		$subtotal		=0;
		$total			=0;
		
		//$this->session->set_userdata('idVenta',$idVenta);
		
		#--------------------------ORDEN DE VENTAS----------------------------#
		$serie="COT-".$folio;
		$venta="VEN-".$folio;
		#---------------------------------------------------------------------#
		
		$data=array
		(
			'ordenCompra'		=>$venta,
			'idCliente'			=>$idCliente,
			'fecha'				=>$this->_fecha_actual,
			'fechaPedido'		=>$this->_fecha_actual,
			'fechaEntrega'		=>$this->_fecha_actual,
			'serie'				=>$serie,
			'estatus'			=>'1',
			'idUsuario'			=>$this->_user_id,
			'fechaCompra'		=>$this->_fecha_actual,
			'pago'				=>0,
			'cambio'			=>0,
			'descuento'			=>0,
			'subTotal'			=>$subtotal,
			'iva'				=>$this->session->userdata('iva')/100,
			'total'				=>$total,
			'folio'				=>$folio,
			'idLicencia'		=>$this->idLicencia,
		);
		
		$this->db->insert('cotizaciones',$data);
		
		$idCotizacion=$this->db->insert_id();
		$indice=count($productos);
		
		for($i=0;$i<$indice;$i++)
		{
			$sql="select precioA
			from productos
			where idProducto='".$productos[$i]["idProducto"]."'";
			
			//echo $sql;
			
			$precio=$this->db->query($sql)->row()->precioA;
			$importe=$precio*$productos[$i]["cantidad"];
			$subtotal+=$importe;
			
			$data=array
			(
				'idCotizacion' 		=>$idCotizacion,
				'cantidad' 			=>$productos[$i]["cantidad"],
				'precio' 			=>$precio,
				'importe' 			=>$importe,
				'idProduct' 		=>$productos[$i]["idProducto"],
				'tipo' 				=>$precio,
				'fecha_entrega' 	=>$this->_fecha_actual,
				'facturado' 		=>'0',
				'produccion' 		=>'0',
			);
			
			#var_dump($data);
			
			$this->db->insert('cotiza_productos',$data);
			
			#-----------------------------------------------------------------------------------------------------#
			$total=($subtotal*$iva)+$subtotal;
			
			$data=array
			(
				'subTotal' =>$subtotal,
				'total' 	=>$total,
			);
			
			$this->db->where('idCotizacion',$idCotizacion);
			$this->db->update('cotizaciones',$data);
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
	
	#SEGUIMIENTO ERP
	
	public function obtenerSeguimientoErp($idCliente)
	{
		$sql="select a.*, b.nombre as responsable
		from seguimiento as a
		inner join seguimiento_responsables as b
		on a.idResponsable=b.idResponsable
		where a.idCliente='$idCliente'
		and tipo=1  
		order by fecha desc";	

		return $this->db->query($sql)->result(); 
	}
	
	public function obtenerErpSeguimiento($idSeguimiento)
	{
		$sql="select * from seguimiento
		where idSeguimiento='$idSeguimiento'";	

		return $this->db->query($sql)->row(); 
	}
	
	public function registrarSeguimientoErp()
	{
		$data=array
		(
			'comentarios'		=>$this->input->post('comentarios'),
			'fecha'				=>$this->input->post('fecha'),
			'idCliente' 		=> $this->input->post('idCliente'),
			'cliente' 			=> $this->input->post('cliente'),
			'idStatus' 			=> $this->input->post('idStatus'),
			'idResponsable' 	=> $this->input->post('idResponsable'),
			'comentariosExtra' 	=> $this->input->post('observaciones'),
			'tipo' 				=> $this->input->post('tipo'),
			'idLicencia'		=>$this->idLicencia
		);

		$this->db->insert('seguimiento', $data);
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}
	
	public function editarSeguimientoErp()
	{
		$data=array
		(
			'comentarios'		=>$this->input->post('comentarios'),
			'fecha'				=>$this->input->post('fecha'),
			'cliente' 			=> $this->input->post('cliente'),
			'idStatus' 			=> $this->input->post('idStatus'),
			'idResponsable' 	=> $this->input->post('idResponsable'),
			'comentariosExtra' 	=> $this->input->post('observaciones'),
			'idEstatus'			=> $this->input->post('idEstatus'),
		);
		
		$this->db->where('idSeguimiento',$this->input->post('idSeguimiento'));
		$this->db->update('seguimiento', $data);
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}

	#SEGUIMIENTO PW
	public function obtenerSeguimientoPw($idCliente)
	{
		$sql="select a.*, b.nombre as responsable
		from seguimiento as a 
		inner join seguimiento_responsables as b
		on a.idResponsable=b.idResponsable
   		where a.idCliente='$idCliente' 
		and tipo=2 
		order by fecha desc";

		return $this->db->query($sql)->result(); 
	}
	
	public function quitarNotificacion()
	{
		$data=array
		(
			'notificar'	=>0
		);
		
		$this->db->where('idProducto',$this->input->post('idProducto'));
		$this->db->update('cotiza_productos',$data);
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	#NOTAS
	public function obtenerNotas($idCliente)
	{
		$sql="select a.*, b.nombre as responsable
		from clientes_notas as a
		inner join seguimiento_responsables as b
		on a.idResponsable=b.idResponsable
		where a.idCliente='$idCliente'
		order by fecha desc";	

		return $this->db->query($sql)->result(); 
	}
	
	public function obtenerNota($idNota)
	{
		$sql="select * from clientes_notas
		where idNota='$idNota'";	

		return $this->db->query($sql)->row(); 
	}
	
	public function registrarNota()
	{
		$data=array
		(
			'idCliente'			=>$this->input->post('idCliente'),
			'idResponsable'		=>$this->input->post('idResponsable'),
			'fecha'				=>$this->input->post('fecha'),
			'comentarios'		=>$this->input->post('comentarios'),
		);
		
		$this->db->insert('clientes_notas',$data);
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function editarNota()
	{
		$data=array
		(
			'idResponsable'		=>$this->input->post('idResponsable'),
			'fecha'				=>$this->input->post('fecha'),
			'comentarios'		=>$this->input->post('comentarios'),
		);
		
		$this->db->where('idNota',$this->input->post('idNota'));
		$this->db->update('clientes_notas',$data);
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function borrarNota($idNota)
	{
		$this->db->where('idNota',$idNota);
		$this->db->delete('clientes_notas');
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	#HOSTINGS
	public function obtenerHostings($idCliente)
	{
		$sql="select * from clientes_hostings
		where idCliente='$idCliente'";	

		return $this->db->query($sql)->result(); 
	}
	
	public function obtenerHosting($idHosting)
	{
		$sql="select * from clientes_hostings
		where idHosting='$idHosting'";	

		return $this->db->query($sql)->row(); 
	}
	
	public function obtenerFechaHosting($fecha,$periodo)
	{
		if($periodo==0)
		{
			$valor		=1;
			$factor		='year';
		}
		
		if($periodo==1)
		{
			$valor		=6;
			$factor		='month';
		}
		
		$sql="select date_add('".$fecha."',interval ".$valor." $factor) as fechaFin";
		
		return $this->db->query($sql)->row()->fechaFin;
	}
	
	public function registrarHosting()
	{
		$data=array
		(
			'idCliente'			=>$this->input->post('idCliente'),
			'tipo'				=>$this->input->post('tipo'),
			'fechaInicio'		=>$this->input->post('fechaInicio'),
			'fechaFin'			=>$this->obtenerFechaHosting($this->input->post('fechaInicio'),$this->input->post('periodicidad')),
			'comentarios'		=>$this->input->post('comentarios'),
			'periodicidad'		=>$this->input->post('periodicidad'),
			'status'			=>$this->input->post('status'),
			'nombre'			=>$this->input->post('nombre'),
			'cpanel'			=>$this->input->post('cpanel'),
		);
		
		$this->db->insert('clientes_hostings',$data);
		
		$this->enviarCorreoHosting();
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function enviarCorreoHosting()
	{
		if(!empty($_POST))
		{
			$idCliente		=$this->input->post('idCliente');
			$cliente		=$this->obtenerCliente($idCliente);
			$tipo			=$this->input->post('tipo')==0?'Hosting':'Dominio';
			$periodicidad	=$this->input->post('periodicidad')==0?'Anual':'Semestral';
			$status			=$this->input->post('status')==0?'Activo':'Inactivo';
			
			if($this->input->post('status')==2)
			{
				$status="Suspendido";
			}
			
			$destinatario	='juan.franco@redisoft.mx';
			$asunto			=$cliente->empresa.', Facturación de '.$tipo;
			
			$mensaje		=' <strong>Registro de: </strong>'.$tipo.'<br />';
			$mensaje		.='<strong>Fecha inicio: </strong>'.$this->input->post('fechaInicio').'<br />';
			$mensaje		.='<strong>Periodicidad: </strong> '.$periodicidad.'<br />';
			$mensaje		.='<strong>Status: </strong> '.$status;
			
			$this->load->library('email');
			$this->email->from($destinatario,'Redisoft');
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
	}//function
	
	public function editarHosting()
	{
		$data=array
		(
			'tipo'				=>$this->input->post('tipo'),
			'fechaInicio'		=>$this->input->post('fechaInicio'),
			'fechaFin'			=>$this->obtenerFechaHosting($this->input->post('fechaInicio'),$this->input->post('periodicidad')),
			'comentarios'		=>$this->input->post('comentarios'),
			'periodicidad'		=>$this->input->post('periodicidad'),
			'status'			=>$this->input->post('status'),
			'nombre'			=>$this->input->post('nombre'),
			'cpanel'			=>$this->input->post('cpanel'),
		);
		
		$this->db->where('idHosting',$this->input->post('idHosting'));
		$this->db->update('clientes_hostings',$data);
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function borrarHosting($idHosting)
	{
		$this->db->where('idHosting',$idHosting);
		$this->db->delete('clientes_hostings');
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	#FICHEROS
	public function obtenerFicheros($idCliente)
	{
		$sql="select * from clientes_ficheros
		where idCliente='$idCliente'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFichero($idFichero)
	{
		$sql="select * from clientes_ficheros
		where idFichero='$idFichero'";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarFichero($idFichero)
	{
		$fichero	=$this->obtenerFichero($idFichero);
		
		$this->db->where('idFichero',$idFichero);
		$this->db->delete('clientes_ficheros');
		
		if($this->db->affected_rows()>=1)
		{
			$this->configuracion->registrarBitacora('Borrar fichero','Clientes',$fichero->nombre); //Registrar bitácora
			
			if(file_exists(carpetaClientes.$fichero->idFichero.'_'.$fichero->nombre))
			{
				unlink(carpetaClientes.$fichero->idFichero.'_'.$fichero->nombre);
			}
			
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	public function subirFicheros($idCliente,$nombre,$tamano)
	{
		$data=array
		(
			'idCliente'	=> $idCliente,
			'nombre'	=> $nombre,
			'tamano'	=> $tamano,
			'fecha'		=> $this->_fecha_actual,
		);
		
		#$data	= procesarArreglo($data);
		$this->db->insert('clientes_ficheros',$data);
		$idFichero=$this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Subir fichero','Clientes',$nombre); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?$idFichero:0;
	}
	
	#OBTENER PROYECTOS
	public function obtenerProyectos($idCliente)
	{
		$sql=" select a.*, concat(b.nombre,' ',b.apellidoPaterno, ' ',b.apellidoMaterno) as responsable
		from seguimiento as a 
		inner join usuarios as b
		on a.idResponsable=b.idUsuario
   		where a.idCliente='$idCliente' 
		and tipo=3
		order by fecha desc";

		return $this->db->query($sql)->result(); 
	}
	
	public function registrarProyecto()
	{
		$data=array
		(
			'comentarios'		=>$this->input->post('comentarios'),
			'fecha'				=>$this->input->post('fecha'),
			'idCliente' 		=> $this->input->post('idCliente'),
			'cliente' 			=> $this->input->post('cliente'),
			'idStatus' 			=> $this->input->post('idStatus'),
			'idResponsable' 	=> $this->input->post('idResponsable'),
			'proyecto' 			=> $this->input->post('proyecto'),
			'meta' 				=> $this->input->post('meta'),
			'avance' 			=> $this->input->post('avance'),
			'tiempo' 			=> $this->input->post('tiempo'),
			'tipo' 				=> $this->input->post('tipo'),
			'idLicencia'		=>$this->idLicencia
		);
		
		$this->db->insert('seguimiento', $data);
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}
	
	public function editarProyecto()
	{
		$data=array
		(
			'comentarios'		=>$this->input->post('comentarios'),
			'fecha'				=>$this->input->post('fecha'),
			'idStatus' 			=> $this->input->post('idStatus'),
			'idResponsable' 	=> $this->input->post('idResponsable'),
			'proyecto' 			=> $this->input->post('proyecto'),
			'meta' 				=> $this->input->post('meta'),
			'avance' 			=> $this->input->post('avance'),
			'tiempo' 			=> $this->input->post('tiempo'),
		);
		
		$this->db->where('idSeguimiento',$this->input->post('idSeguimiento'));
		$this->db->update('seguimiento', $data);
		
		return ($this->db->affected_rows() >= 1)? "1" : "0";
	}
	
	
	#PARA LAS  VENTAS QUE SON DIRECTAS
	public function obtenerFolio($prefactura=0,$idEstacion=0)
	{
		$sql=" select coalesce(max(abs(folio)),0) as folio 
		from  cotizaciones
		where activo='1'
		and estatus=1
		
		and folio not like('%-%')
		
		
		and idLicencia='$this->idLicencia'
		and prefactura=".$prefactura;
		
		$sql.= $idEstacion>0?" and idEstacion='$idEstacion'":" and idEstacion='$this->idEstacion'" ;
		
		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function obtenerFolioCotizacion()
	{
		$sql=" select coalesce(max(folioCotizacion),0) as folio 
		from  cotizaciones
		where activo='1'
		and estatus=0
		and idEstacion='$this->idEstacion'
		and idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row()->folio+1;
	}
	
	public function realizarPagoVenta($idVenta,$folio,$pago,$total,$idCliente,$idForma,$iva=0,$idCuenta=1)
	{
		#$idForma			= $this->input->post('idForma');
		$anticipo			= $pago<$total?'Anticipo ':'';
		$importe			= $pago>=$total?$total:$pago;
		
		$subTotal			= $iva>0?$importe/1.16:$importe;
		
		$data = array
		(
			'idVenta'			=> $idVenta,
			'idCliente'			=> $idCliente,
			#'idCuenta'			=> $this->input->post('cuentasBanco'),
			'idCuenta'			=> $idCuenta,
			
			'pago'				=> $pago>=$total?$total:$pago,
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			'transferencia'		=> $this->input->post('numeroTransferencia'),
			'cheque'			=> $this->input->post('numeroCheque'),
			'formaPago'			=> '',
			'fecha'				=> $this->_fecha_actual,
			'idLicencia'		=> $this->idLicencia,
			'concepto'			=> $anticipo.'VEN-'.$folio,
			'producto'			=> $anticipo.'VEN-'.$folio,
			
			'nombreReceptor'	=> '',
			'incluyeIva'		=> 1,
			'idForma'			=> $idForma,
			'iva'				=> $iva>0?16:0,
			'subTotal'			=> $subTotal,
			'ivaTotal'			=> $importe-$subTotal,
			'idUsuario'			=> $this->_user_id,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_ingresos',$data);
		$idIngreso=$this->db->insert_id();
		
		$this->contabilidad->registrarPolizaIngreso($data['fecha'],$data['producto'],0,$data['pago'],$idIngreso); //REGISTRAR LA PÓLIZA DE INGRESO
	}
	
	public function realizarPagoAcrilico($idVenta,$folio,$pago,$total,$idCliente,$idForma,$iva=0)
	{
		#$idForma			= $this->input->post('idForma');
		#$anticipo			= $pago<$total?'Anticipo ':'';
		$importe			= $pago>=$total?$total:$pago;
		
		$subTotal			= $iva>0?$pago/1.16:$pago;
		
		$data = array
		(
			'idVenta'			=> $idVenta,
			'idCliente'			=> $idCliente,
			'idCuenta'			=> 1,
			'pago'				=> $pago,
			'nombreReceptor'	=> '',
			'transferencia'		=> '',
			'cheque'			=> '',
			'formaPago'			=> '',
			'fecha'				=> $this->_fecha_actual,
			'idLicencia'		=> $this->idLicencia,
			'concepto'			=> 'Acrílico '.'VEN-'.$folio,
			'producto'			=> 'Acrílico '.'VEN-'.$folio,
			
			'nombreReceptor'	=> '',
			'incluyeIva'		=> 1,
			'idForma'			=> 1,
			'iva'				=> $iva>0?16:0,
			'subTotal'			=> $subTotal,
			'ivaTotal'			=> $pago-$subTotal,
			'acrilico'			=> '1',
			'idUsuario'			=> $this->_user_id,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_ingresos',$data);
		$idIngreso=$this->db->insert_id();
		
		#$this->contabilidad->registrarPolizaIngreso($data['fecha'],$data['producto'],0,$data['pago'],$idIngreso); //REGISTRAR LA PÓLIZA DE INGRESO
	}
	
	public function obtenerDivisa($idDivisa)
	{
		$sql="select * from divisas
		where idDivisa='$idDivisa' ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerVentaServicio($idCotizacion) 
	{
		$sql=" select * from cotizaciones
		where idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->row_array();
	}
	
	public function obtenerServiciosVenta($idCotizacion) 
	{
		$sql=" select * from cotiza_productos
		where idCotizacion='$idCotizacion'
		and servicio='1'
		and plazo>0
		order by plazo desc ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerPeriodoServicio($idProducto) 
	{
		$sql=" select a.idProducto, b.nombre, b.factor, b.valor
		from productos as a
		inner join produccion_periodos as b
		on a.idPeriodo=b.idPeriodo
		where a.idProducto='$idProducto'
		and b.nombre!='NA' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function procesarVentaServicio($idCotizacionPadre) 
	{
		$servicios	= $this->obtenerServiciosVenta($idCotizacionPadre);
		
		if($servicios!=null)
		{
			$limite		= $servicios[0]['plazo'];
			$venta		= $this->obtenerVentaServicio($idCotizacionPadre);
			$fechaBase	= $venta['fechaCompra']; //FECHA BASE PARA CALCULAR LOS PERIODOS POSTERIORES
			
			for($i=1;$i<=$limite;$i++)
			{
				foreach($servicios as $row)
				{
					if($i<=$row['plazo'])
					{
						$periodo					= $this->obtenerPeriodoServicio($row['idProduct']);
						
						if($periodo!=null)
						{
							$fechaCompra			= $this->obtenerFechaFinServicio($i*$periodo->valor,$periodo->factor,$fechaBase);
							
							//VENTA
							$subTotal				= $row['importe'];
							$descuento				= $venta['descuentoPorcentaje']>0?$subTotal*($venta['descuentoPorcentaje']/100):0;
							$suma					= $subTotal-$descuento;
							$iva					= $venta['ivaPorcentaje']>0?$suma*$venta['ivaPorcentaje']/100:0;
	
							$venta['idCotizacion']	= 0;
							$venta['pago']			= 0;
							$venta['cambio']		= 0;
							$venta['idCotizacionPadre']	= $idCotizacionPadre;
							$venta['pendiente']		= '1';
							$venta['fecha']			= $fechaCompra;
							$venta['fechaPedido']	= $fechaCompra;
							$venta['fechaEntrega']	= $fechaCompra;
							$venta['fechaCompra']	= $fechaCompra;
							
							$venta['subTotal']		= $subTotal;
							$venta['descuento']		= $descuento;
							$venta['iva']			= $iva;
							$venta['total']			= $suma+$iva;
							
							$this->db->insert('cotizaciones',$venta);
							$idCotizacion	= $this->db->insert_id();
							
							//DETALLE DE VENTA
							$row['idProducto']		= 0;
							$row['idCotizacion']	= $idCotizacion;
							$row['fechaInicio']		= $fechaCompra;
							$row['fechaVencimiento']= $fechaCompra;
							
							$this->db->insert('cotiza_productos',$row);
						} //IF PERIODO
					} //IF PLAZO
				} //FOREACH
			} //FOR
		} //IF
	}
	
	public function obtenerFolioConta()
	{
		$sql=" select coalesce(max(folioConta),0)  as folioConta
		from cotizaciones
		where idLicencia='$this->idLicencia' ";
		
		#and idEstacion='$this->idEstacion'
		
		return $this->db->query($sql)->row()->folioConta+1;
	}
	
	public function revisarProductosVenta()
	{
		$numeroProductos		= $this->input->post('txtNumeroProductos');
		$subTotal				= 0;
		
		for($i=0;$i<=$numeroProductos;$i++)
		{
			#$descuento	= explode('|',$descuentos[$i]);
			$idProducto			= $this->input->post('txtIdProducto'.$i);
			
			if($idProducto>0)
			{
				
				$cantidad			= $this->input->post('txtCantidadProducto'.$i);
				$descuentos			= $this->input->post('txtDescuentoProducto'.$i);				
				$impuestos			= $this->input->post('txtTotalImpuesto'.$i);
				$total				= $this->input->post('txtTotalProducto'.$i);
				$precio				= $total/$cantidad;
				$impuesto			= $impuestos/$cantidad;
				$descuento			= $descuentos/$cantidad;
				
				$importe			=  $this->input->post('txtTotalProducto'.$i)-$impuestos;
				
				$subTotal+=$importe;
			
			}
		}
		
		return $subTotal;
	}
	
	public function registrarVenta() 
	{
		$subtotal				= $this->input->post('txtSubTotal');
		$iva					= $this->input->post('txtIvaTotal');
		$total					= $this->input->post('txtTotal');
		$descuento				= 0;#$this->input->post('descuento');
		$idCliente				= $this->input->post('txtIdCliente');
		$pago					= strlen($this->input->post('txtPago'))>0?$this->input->post('txtPago'):0;
		$idTienda				= $this->input->post('txtIdTienda');
		$idSucursal				= $this->input->post('txtIdSucursal'); 
		
		$subTotalProductos		= $this->revisarProductosVenta();
		
		if($total==0 or strlen($total)==0 or $idCliente==0 or strlen($idCliente)==0 or strlen($subtotal)==0 or strlen($subtotal)==0)
		{
			return array('0','Revise que los importes sean correctos');
		}
		
		if(($subTotalProductos-$subtotal)>0.5)
		{
			return array('0','Revise que los importes sean correctos');
		}
		
		if(!$this->configuracion->comprobarEstacionLicencia($this->idLicencia,$this->idEstacion))
		{
			return array('0','Error en el registro, revise la configuración de la licencia y la estación');
		}
		
		#return array('0',$subTotalProductos.' - '.$subtotal);
		
		$formas					= $this->input->post('selectFormas');
		
		$cotizacion				= $this->input->post('cotizacion');
		
		$formas					= explode('|',$formas);
		$idForma				= isset($formas[0])?$formas[0]:1;
		$intereses				= isset($formas[1])?$formas[1]:0;
		$idCuenta				= isset($formas[2])?$formas[2]:1;
		$folio					= 0;
		$folioCotizacion		= 0;
		
		if($cotizacion=='0')
		{
			$folio					= $this->obtenerFolio(0);
		}
		else
		{
			$folioCotizacion		= $this->obtenerFolioCotizacion();
		}
		
		$folioConta				= $this->obtenerFolioConta();
		
		$divisa					= $this->obtenerDivisa($this->input->post('selectDivisas'));

		

		#--------------------------ORDEN DE VENTAS----------------------------#
		$serie					= "COT-".date('Y-m-d').'-'.$folioCotizacion;
		$venta					= "VEN-".$folio;
		#---------------------------------------------------------------------#

		$comentarios			= "";
		$formaPago				= $this->configuracion->obtenerForma($idForma);
		$formaPago				= $formaPago!=null?$formaPago->nombre:'';
		
		
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		
		$data=array
		(
			'ordenCompra'		=> $cotizacion=='1'?'':$venta,
			'idCliente'			=> $idCliente,
			'fecha'				=> $this->_fecha_actual,
			#'fechaPedido'		=> $this->input->post('txtFechaVenta'),
			'fechaPedido'		=> $this->_fecha_actual,
			
			'fechaEntrega'		=> strlen($this->input->post('selectMostrador'))==1?$this->input->post('txtFechaEntrega').' '.date('H:i:s'):$this->input->post('txtFechaVenta'),
			
			'serie'				=> $serie,
			'estatus'			=> $cotizacion=='1'?'0':'1',
			#'idUsuario'			=> $this->_user_id,
			
			'idUsuario'			=> $this->input->post('txtIdUsuarioVendedor'),
			
			#'fechaCompra'		=> $this->input->post('txtFechaVenta'),
			'fechaCompra'				=> $this->_fecha_actual,
			'pago'				=> $cotizacion=='1'?0:$this->input->post('txtPago'),
			'cambio'			=> $cotizacion=='1'?0:$this->input->post('txtCambio'), 
			'descuento'			=> $this->input->post('txtDescuentoTotal'),
			'descuentoPorcentaje'=> $this->input->post('txtDescuentoPorcentaje0'),
			'subTotal'			=> $subtotal,
			
			'iva'				=> $this->input->post('txtIvaTotal'),
			'total'				=> $total,
			
			'ivaPorcentaje'		=> 16.00,
			
			'idLicencia'		=> $this->idLicencia,
			'comentarios'		=> $comentarios,
			'idDivisa'			=> $divisa->idDivisa,
			'tipoCambio'		=> $divisa->tipoCambio,
			'condicionesPago'	=> $this->input->post('txtCondicionesPago'), 
			'formaPago'			=> $this->input->post('txtFormaPago'), 
			#'facturar'			=> $this->input->post('chkFacturar')=='1'?'1':'0', 
			'metodoPago'		=> $formaPago.' '.$this->obtenerDigitosCuenta($this->input->post('cuentasBanco')), 
			'observaciones'		=> $this->input->post('txtObservacionesEnvio'), 
			'diasCredito'		=> $this->input->post('txtDiasCredito'), 
			'idForma'			=> $idForma,
			'idTienda'			=> $idTienda,
			
			'intereses'			=> $intereses,
			
			'idEstacion'		=> $this->idEstacion, 
			
			'folio'				=> $folio,
			'folioConta'		=> $cotizacion=='1'?0:$folioConta,
			'folioCotizacion'	=> $folioCotizacion,
			
			'envio'				=> $this->input->post('selectMostrador'), 
			'idRuta'			=> $this->input->post('selectRutas'), 
			'idDireccion'		=> $this->input->post('selectDirecciones'), 
			
			'idSucursal'		=> $idSucursal, 

			'tipoEnvio'			=> $this->input->post('tipoEnvio'), 
		);
		
		
		$data['idFormaSat']		= $this->input->post('selectFormaPagoSat');
		$data['idMetodo']		= $this->input->post('selectMetodoPago');
		$data['idUso']			= $this->input->post('selectUsoCfdi');
		$data['idEmisor']		= $this->input->post('selectEmisores');
		
		$data	= procesarArreglo($data);
		$this->db->insert('cotizaciones',$data);
		$idCotizacion	=$this->db->insert_id();
		
		#$this->temporal->registrarDatosTemporal($idCotizacion,'registrar','ventas'); //REGISTRAR LA TABLA TEMPORAL
		
	#	$this->temporal->registrarMovimiento($idCotizacion,'ventas'); //REGISTRAR LA TABLA TEMPORAL
		
		$this->procesarProductosVenta($idCotizacion,$idTienda,$cotizacion);
		
		if($this->resultado=="1")
		{
			if($cotizacion=='0')
			{
				if($pago>0)
				{
					//EL PAGO NO ESTA CANCELADO 25-AGOSTO-2021
					$this->realizarPagoVenta($idCotizacion,$folio,$pago,$total,$idCliente,$idForma,$data['iva'],$idCuenta); //Realizar el pago de la venta

					$this->configuracion->registrarBitacora('Registrar cobro venta','Ventas','Orden: '.$venta); //Registrar bitácora
				}

				if($pago<$total)
				{
					#$this->realizarPagoProgramado($idCotizacion,$folio,$idCliente,$pago,$total,$data['iva']); //Pago programado
				}
			}

			//PROCESAR LAS VENTAS DE SERVICIOS
		
			#$this->procesarVentaServicio($idCotizacion);
		
			if($cotizacion=='0')
			{
				if($this->revisarVentasEfectivo($total) and $idCliente==1)
				{
					$this->db->where('idCotizacion',$idCotizacion);
					$this->db->update('cotizaciones',array('folioConta'=>0));
				}
				else
				{
					$this->configuracion->registrarBitacora('Registrar venta','Ventas',$this->obtenerClienteEmpresa($idCliente).' Orden: '.$venta); //Registrar bitácora
				}
			}
		
			if($idSucursal>0 and $cotizacion=='0')
			{
				$this->tiendas->registrarTraspasoSucursal($idCotizacion);
			}
		}
		
		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();
			
			return array(0=>'0',1=>$this->resultado);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array(0=>'1',1=>$idCotizacion);
		}
	}
	
	public function revisarVentasEfectivo($total=0) 
	{
		$configuracion	= $this->configuracion->obtenerConfiguracionLicencia($this->idLicencia);
		$ventas			= $this->sumarVentasEfectivo();

		if($configuracion->numeroVentas==0) return false;
		if($configuracion->importeDinero<$ventas) return false;
		
		if($total==0) return false;
		
		
		//CONTEMPLAR LAS DEVOLUCIONES

		$idCotizacion	= $this->obtenerVentaEfectivo();
		$cotizaciones	= $this->obtenerVentasEfectivo($idCotizacion);
		
		return $cotizaciones>=$configuracion->numeroVentas?true:false;
	}
	
	public function obtenerVentaEfectivo() 
	{
		$sql=" select a.idCotizacion 
		from cotizaciones as a 
		where a.idLicencia='$this->idLicencia'
		and a.estatus='1'
		and a.activo='1'
		and a.cancelada='0'
		and a.folioConta=0
		and a.idCliente='1'
		and date(a.fechaCompra)='$this->fecha' ";
		
		$sql.=" and (select count(b.idIngreso) from catalogos_ingresos as b where b.idVenta=a.idCotizacion and b.idForma!=1) = 0 ";
		
		$sql.=" order by a.idCotizacion desc
		limit 1 ";
		
		$cotizacion	= $this->db->query($sql)->row();
		
		return $cotizacion!=null?$cotizacion->idCotizacion:1;
	}
	
	public function obtenerVentasEfectivo($idCotizacion) 
	{
		$sql=" select count(a.idCotizacion) as numero
		from cotizaciones as a 
		where a.idLicencia='$this->idLicencia'
		and a.activo='1'
		and a.cancelada='0'
		and a.folioConta>0
		and a.idCliente='1'
		and  a.idCotizacion > $idCotizacion
		and date(a.fechaCompra)='$this->fecha' ";
		
		$sql.=" and (select count(b.idIngreso) from catalogos_ingresos as b where b.idVenta=a.idCotizacion and b.idForma!=1) = 0 ";

		return $this->db->query($sql)->row()->numero;
	}
	
	public function sumarVentasEfectivo() 
	{
		$sql=" select coalesce(sum(a.total),0) as total
 		from cotizaciones as a 
		where a.idLicencia='$this->idLicencia'
		and a.estatus='1'
		and a.activo='1'
		and a.cancelada='0'
		and a.folioConta=0
		and a.idCliente='1'
		and date(a.fechaCompra)='$this->fecha' ";
		
		return $this->db->query($sql)->row()->total;
	}
	
	public function registrarPedidoVenta($idCotizacion) 
	{
		$data=array
		(
			'idCotizacion'		=> $idCotizacion,
			'sabor'				=> $this->input->post('txtSabor'),
			'cobertura'			=> $this->input->post('txtCobertura'),
			'relleno'			=> $this->input->post('txtRelleno'),
			'forma'				=> $this->input->post('txtForma'),
			'decoracion'		=> $this->input->post('txtDecoracion'),
			'fechaEntrega'		=> $this->input->post('txtFechaEntrega'),
			'hora'				=> $this->input->post('selectHoras').':'.$this->input->post('selectMinutos'),
			'idDireccion'		=> $this->input->post('selectDirecciones'),
			'especial'			=> $this->input->post('chkEspecial')=='1'?'1':'0',
			'descripcion'		=> $this->input->post('chkEspecial')=='1'?$this->input->post('txtEspecial'):'',
			'peso'				=> $this->input->post('txtPesoKg'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('cotizaciones_pedidos',$data);
	}
	
	
	public function obtenerDigitosCuenta($idCuenta)
	{
		$idCuenta	=$idCuenta==0?1:$idCuenta;
		
		if($idCuenta>0)
		{
			$sql="select cuenta
			from cuentas
			where idCuenta='$idCuenta' ";
			
			$cuenta		= $this->db->query($sql)->row()->cuenta;
			
			if($cuenta=='Efectivo')return '';
			
			$longitud	=strlen($cuenta);
			return substr($cuenta,$longitud-4,4);
		}
		else
		{
			return '';
		}
	}
	
	public function obtenerDiasCredito($idCliente)
	{
		$sql="select limiteCredito
		from clientes
		where idCliente='$idCliente'";
		
		return $this->db->query($sql)->row()->limiteCredito;
	}
	
	public function realizarPagoProgramado($idVenta,$folio,$idCliente,$pago,$total,$iva)
	{
		$idCuenta	=$this->input->post('cuentasBanco');
		#$idCuenta	=$idCuenta==0?1:$idCuenta;
		
		$data = array
		(
			'idVenta'			=> $idVenta,
			#'idCuenta'			=> $idCuenta,
			'idCuenta'			=> 1,
			'idCliente'			=> $idCliente,
			'pago'				=> $total-$pago,
			'nombreReceptor'	=> $this->input->post('txtNombreReceptor'),
			'transferencia'		=> '',
			'cheque'			=> '',
			'formaPago'			=> '',
			'fecha'				=> $this->obtenerFechaFinServicio($this->input->post('txtDiasCredito'),'day',$this->input->post('txtFechaVenta')),
			'idLicencia'		=> $this->idLicencia,
			'concepto'			=> 'VEN-'.$folio,
			'producto'			=> 'VEN-'.$folio,
			#'idGasto'			=> 1,
			#'idProducto'		=> 1,
			'iva'				=> $iva>0?16:0,
			'nombreReceptor'	=> '',
			'incluyeIva'		=> 1,
			#'idDepartamento'	=> 1,
			#'idNombre'			=> 1,
			'notificacion'		=> '1',
			'idForma'			=> '4',
		);
		
		$this->db->insert('catalogos_ingresos',$data);
	}
	
	public function obtenerPlazoProducto($idProducto)
	{
		$sql="select plazo
		from productos
		where idProducto='$idProducto' ";
		
		$producto=$this->db->query($sql)->row();
		
		return $producto!=null?$producto->plazo:0;
	}
	
	public function procesarProductosVenta($idCotizacion,$idTienda,$cotizacion='0')
	{
		$numeroProductos		= $this->input->post('txtNumeroProductos');
		
		for($i=0;$i<=$numeroProductos;$i++)
		{
			#$descuento	= explode('|',$descuentos[$i]);
			$idProducto			= $this->input->post('txtIdProducto'.$i);
			
			if($idProducto>0)
			{
				//REVISAR SI HAY REBANADAS
				$numeroRebanadas	= $this->input->post('txtNumeroRebanadas'.$i);
				$rebanada			= $this->input->post('txtRebanadas'.$i);
				
				$cantidad			= $this->input->post('txtCantidadProducto'.$i);
				$servicio			= $this->input->post('txtServicio'.$i);
				$tipo				= $this->input->post('txtTipoGranja'.$i);
				$descuentos			= $this->input->post('txtDescuentoProducto'.$i);				
				$impuestos			= $this->input->post('txtTotalImpuesto'.$i);
				$total				= $this->input->post('txtTotalProducto'.$i);
				$precio				= $total/$cantidad;
				$impuesto			= $impuestos/$cantidad;
				$descuento			= $descuentos/$cantidad;

				//REVISAR LOS PRECIOS 
				$producto			= $this->obtenerStockProducto($idProducto);
				
				if(!validarPrecioProducto($precio,$producto))
				{
					$this->resultado	= "Error en el registro, el precio del producto es difente al registrado ";
					return;
				}
				
				$data=array
				(
					'idCotizacion' 			=> $idCotizacion,
					'cantidad' 				=> $cantidad,
					#'precio' 				=> $this->input->post('txtPrecioProducto'.$i)-$impuesto,
					'precio' 				=> $precio-$impuesto+$descuento,
					'importe' 				=> $this->input->post('txtTotalProducto'.$i)-$impuestos,
					'idProduct' 			=> $idProducto,
					'tipo' 					=> $this->input->post('txtPrecioProducto'.$i),
					'nombre' 				=> $this->input->post('txtNombreProducto'.$i),
					'servicio' 				=> $servicio,
					'fechaInicio' 			=> $this->_fecha_actual,
					'fechaVencimiento' 		=> $this->_fecha_actual,
					'notificar' 			=> 0,
					'facturado' 			=> '0',
					
					'descuento' 			=> $this->input->post('txtDescuentoProducto'.$i),
					'descuentoPorcentaje'	=> $this->input->post('txtDescuentoPorcentaje'.$i),
					'plazo'					=> $this->obtenerPlazoProducto($idProducto),

					'precio1' 				=> $this->input->post('txtPrecio1'.$i),
				);
				
				if($rebanada=='si')
				{
					$rebanadas				= (1/$numeroRebanadas) * $cantidad;
					
					$data['rebanadas']		= $rebanadas;
					
					$cantidad				= $rebanadas; //DESCONTAR POR PORCION
				}
				
				if($this->input->post('selectMostrador')==0)
				{
					$data['enviado']		= 1;
					$data['entregado']		= 1;
					$data['produccion']		= 1;
				}
				
				if(sistemaActivo=='olyess')
				{
					$data['domicilio']		=  $this->input->post('txtDomicilioProducto'.$i);
				}

				$this->db->insert('cotiza_productos',$data);
				
				$idProductoCotizacion	= $this->db->insert_id();
				
				$this->registrarImpuestosProducto($idProductoCotizacion,$i);
				#----------------------------------------------------------------------------------------------------------#
				
				if($cotizacion=='0')
				{
					//if($this->input->post('selectMostrador')==0)
					{
						if($servicio==0)
						{
							$this->actualizarStockProducto($idProducto,$idProductoCotizacion,$cantidad,$idTienda);	
						}

						$this->entregarProductos($idProductoCotizacion,$cantidad);
					}
				}
				
				#----------------------------------------------------------------------------------------------------------#
			}
		}
		
		return $this->resultado; //REVISAR RESULTADO
	}
	
	public function registrarMaterialesProducto($idProducto)
	{
		$numeroMateriales	= $this->input->post('txtNumeroMateriales');
		
		#echo $numeroMateriales;
		for($i=0;$i<$numeroMateriales;$i++)
		{
			if($this->input->post('txtIdMaterial'.$i)>0)
			{
				$data=array
				(
					'idProducto' 		=> $idProducto,
					'idMaterial' 		=> $this->input->post('txtIdMaterial'.$i),
					'cantidad' 			=> $this->input->post('txtCantidadMaterial'.$i),
					'importe' 			=> $this->input->post('txtImporteMaterial'.$i),
					'precio' 			=> $this->input->post('txtPrecioMaterial'.$i),
					'costo' 			=> $this->input->post('txtCostoMaterial'.$i),
				);
				
				$this->db->insert('cotiza_productos_materiales',$data);
			}
		}
	}
	
	public function registrarImpuestosProducto($idProducto,$i)
	{
		$data=array
		(
			'idProducto' 		=> $idProducto,
			'idImpuesto' 		=> $this->input->post('txtIdImpuesto'.$i),
			'tasa' 				=> $this->input->post('txtTasaImpuesto'.$i),
			'importe' 			=> $this->input->post('txtTotalImpuesto'.$i),
			'tipo' 				=> $this->input->post('txtTipoImpuesto'.$i),
			'nombre' 			=> $this->input->post('txtImpuesto'.$i),
		);
		
		$this->db->insert('cotiza_productos_impuestos',$data);
	}
	
	public function procesarProductosVenta1($idCotizacion,$cantidad,$productos,$preciosTotales,$precioProducto,$idTienda)
	{
		$servicios		= $this->input->post('servicios');
		$nombres		= $this->input->post('nombres');
		$descuentos		= $this->input->post('descuentos');
		
		for($i=0;$i<count($cantidad);$i++)
		{
			$descuento	= explode('|',$descuentos[$i]);
			
			$data=array
			(
				'idCotizacion' 			=> $idCotizacion,
				'cantidad' 				=> $cantidad[$i],
				'precio' 				=> $precioProducto[$i],
				'importe' 				=> $preciosTotales[$i],
				'idProduct' 			=> $productos[$i],
				'tipo' 					=> $precioProducto[$i],
				'nombre' 				=> $nombres[$i],
				'servicio' 				=> $servicios[$i],
				'fechaInicio' 			=> $this->_fecha_actual,
				'fechaVencimiento' 		=> $this->_fecha_actual,
				'notificar' 			=> 0,
				'facturado' 			=> '0',
				
				'descuento' 			=> $descuento[1],
				'descuentoPorcentaje'	=> $descuento[0],
				'plazo'					=> $this->obtenerPlazoProducto($productos[$i]),
			);
			
			if($this->input->post('mostrador')==0)
			{
				$data['enviado']		= 1;
				$data['entregado']		= 1;
				$data['produccion']		= 1;
			}
			
			/*if($servicios[$i]!=0)
			{
				$data['servicio']		=1;
				
				if($servicios[$i]!=8)
				{
					$periodo	=$this->obtenerPeriodo($servicios[$i]);
					$fechaFin	=$this->obtenerFechaFinServicio($periodo->valor*$cantidad[$i],$periodo->factor,$fechas[$i]);
					
					$data['fechaInicio']		=$fechas[$i];
					$data['fechaVencimiento']	=$fechaFin;
					$data['notificar']			=1;
				}
			}*/
			
			$this->db->insert('cotiza_productos',$data);
			
			$idProductoCotizacion	= $this->db->insert_id();
			#----------------------------------------------------------------------------------------------------------#
			
			if($this->input->post('mostrador')==0)
			{
				if($servicios[$i]==0)
				{
					$this->actualizarStockProducto($productos[$i],$idProductoCotizacion,$cantidad[$i],$idTienda);	
				}
				
				$this->entregarProductos($idProductoCotizacion,$cantidad[$i]);
			}
			#----------------------------------------------------------------------------------------------------------#
		}
	}
	
	public function entregarProductos($idProductoCotizacion,$cantidad)
	{
		$data=array
		(
			'fecha' 		=>$this->_fecha_actual,
			'cantidad' 		=>$cantidad,
			'entrego' 		=>$this->session->userdata('nombreUsuarioSesion'),
			'idProducto' 	=>$idProductoCotizacion
		);
		
		$this->db->insert('ventas_entrega_detalles',$data);
	}
	
	public function registrarDetallesPaquete($idCotizacion,$cantidad,$idProducto)
	{
		$data=array
		(
			'idCotizacion' 		=>$idCotizacion,
			'cantidad' 			=>$cantidad,
			'idProduct' 		=>$idProducto,
		);
	
		$this->db->insert('cotiza_productos_paquete',$data);
	}
	
	public function obtenerStockProducto($idProducto,$idTienda=0)
	{
		$sql =" select a.idInventario, a.stock, a.precioA, a.precioB, a.precioC
		from productos_inventarios as a 
		where a.idProducto='$idProducto'
		and a.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function actualizarStockProducto($idProducto,$idProductoCotizacion,$cantidad,$idTienda)
	{
		$producto	= $this->obtenerStockProducto($idProducto,$idTienda);
		
		if($producto->stock<$cantidad)
		{
			$this->resultado	= "No existen suficientes productos para realizar la venta";
			
			return 0;
		}
		else
		{
			$data=array
			(
				'stock' =>$producto->stock-$cantidad
			);

			$this->db->where('idInventario',$producto->idInventario);
			$this->db->update('productos_inventarios',$data);
			
		#----------------------------------------------------------------------------------------------------------#
		}
	}

	/*public function actualizarStockProducto($idProducto,$idProductoCotizacion,$cantidad,$idTienda)
	{
		$producto	= $this->obtenerStockProducto($idProducto,$idTienda);
		
		if($producto->stock<$cantidad)
		{
			$this->resultado	= "No existen suficientes productos para realizar la venta";
			
			return 0;
		}
		else
		{
			if($idTienda==0)
			{
				$data=array
				(
					'stock' =>$producto->stock-$cantidad
				);
				
				$this->db->where('idProducto',$idProducto);
				$this->db->update('productos',$data);
			}
			
			if($idTienda>0)
			{
				$data=array
				(
					'cantidad' =>$producto->stock-$cantidad
				);
				
				$this->db->where('idProducto',$producto->idProducto);
				$this->db->where('idTienda',$idTienda);
				$this->db->update('tiendas_productos',$data);
			}
			
		#----------------------------------------------------------------------------------------------------------#
		}
	}*/
	
	public function verificarProductoPaquete($idProducto)
	{
		$sql="select idProducto, paquete
		from productos
		where idProducto='$idProducto'";
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarCotizacion() 
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$folio			=$this->obtenerFolio();
		$divisa			=$this->obtenerDivisa($this->input->post('selectDivisas'));

		$total			=$this->input->post('txtTotal');
		$descuento		=0;#$this->input->post('descuento');
		$idCliente		=$this->input->post('txtIdCliente');

		#--------------------------ORDEN DE VENTAS----------------------------#
		#$serie="COT-".date('Y-m-d').'-'.$folio;
		#---------------------------------------------------------------------#
		
		$data=array
		(
			#'ordenCompra'		=> $venta,
			'idCliente'			=> $idCliente,
			'fecha'				=> $this->input->post('txtFechaCotizacion'),
			'fechaPedido'		=> $this->input->post('txtFechaCotizacion'),
			'fechaEntrega'		=> $this->input->post('txtFechaEntrega'),
			'serie'				=> $this->input->post('txtSerie'),
			'diasCredito'		=> $this->input->post('txtDiasCredito'),
			'idContacto'		=> $this->input->post('selectContactosClienteCotizacion'),
			'estatus'			=> '0',
			#'idUsuario'			=> $this->_user_id,
			'descuento'				=> $this->input->post('txtDescuentoTotal'),
			#'descuentoPorcentaje'	=> $this->input->post('txtDescuentoPorcentaje0'),
			'descuentoPorcentaje'	=> 0,
			'subTotal'				=> $this->input->post('txtSubTotal'),
			'iva'					=> $this->input->post('txtIvaTotal'),
			#'ivaPorcentaje'		=> $this->input->post('ivaPorcentaje'),
			'total'				=> $total,
			'folio'				=> $folio,
			'idLicencia'		=> $this->idLicencia,
			'comentarios'		=> $this->input->post('txtComentarios'),
			'idDivisa'			=> $divisa->idDivisa,
			'tipoCambio'		=> $divisa->tipoCambio,
			
			'asunto'			=> $this->input->post('txtAsunto'),
			'presentacion'		=> $this->input->post('txtPresentacion'),
			'condiciones'		=> $this->input->post('txtCondicionesPago'),
			'terminos'			=> $this->input->post('txtTerminos'),
			'firma'				=> $this->input->post('txtFirma'),
			'agradecimientos'	=> $this->input->post('txtAgradecimientos'),
			'idUsuario'			=> $this->input->post('selectUsuariosEnviar'),
			
			'idEstacion'		=> $this->idEstacion, 
		);
		
		$this->db->insert('cotizaciones',$data);
		$idCotizacion	=$this->db->insert_id();
		
		$this->procesarProductosCotizacion($idCotizacion);
		
		$this->configuracion->registrarBitacora('Registrar cotización','Cotizaciones',$this->input->post('txtSerie')); //Registrar bitácora

		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
		{
			$this->db->trans_rollback(); #Revertir si hubo algun fallo en las actualizaciones
			$this->db->trans_complete();

			return array('0',errorRegistro);
		}
		else
		{
			$this->db->trans_commit(); #Guargar los cambios si todo ha sido correcto
			$this->db->trans_complete();
			
			return array('1',registroCorrecto,$idCotizacion);
		}
	}
	
	public function procesarProductosCotizacion($idCotizacion)
	{
		/*$servicios			= $this->input->post('servicios');
		$fechas					= $this->input->post('fechas');
		$nombres				= $this->input->post('nombres');
		$descuentos				= $this->input->post('descuentos');*/
		
		$numeroProductos		= $this->input->post('txtNumeroProductos');
		
		for($i=1;$i<=$numeroProductos;$i++)
		{
			#$descuento	= explode('|',$descuentos[$i]);
			$idProducto			= $this->input->post('txtIdProducto'.$i);
			
			if($idProducto>0)
			{
				$descuentos			= $this->input->post('txtDescuentoProducto'.$i);	
				$cantidad			= $this->input->post('txtCantidadProducto'.$i);			
				$impuestos			= $this->input->post('txtTotalImpuesto'.$i);
				$total				= $this->input->post('txtTotalProducto'.$i);
				$precio				= $total/$cantidad;
				$impuesto			= $impuestos/$cantidad;
				$descuento			= $descuentos/$cantidad;
				
				
				#$cantidad			= $this->input->post('txtCantidadProducto'.$i);
				#$impuestos			= $this->input->post('txtTotalImpuesto'.$i);
				#$impuesto			= $impuestos/$cantidad;
				
				$data=array
				(
					'idCotizacion' 			=> $idCotizacion,
					#'cantidad' 				=> $this->input->post('txtCantidadProducto'.$i),
					'cantidad' 				=> $cantidad,
					
					
					#'precio' 				=> $this->input->post('txtPrecioProducto'.$i)-$impuesto,
					'precio' 				=> $precio-$impuesto+$descuento,
					
					
					#'importe' 				=> $this->input->post('txtTotalProducto'.$i)-$impuestos,
					'importe' 				=> $this->input->post('txtTotalProducto'.$i)-$impuestos,
					
					
					'idProduct' 			=> $idProducto,
					'nombre' 				=> $this->input->post('txtNombreProducto'.$i),
					'tipo' 					=> $this->input->post('txtPrecioProducto'.$i),
					
					'descuento' 			=> $this->input->post('txtDescuentoProducto'.$i),
					'descuentoPorcentaje'	=> $this->input->post('txtDescuentoPorcentaje'.$i),
					
					'fechaInicio' 			=> $this->_fecha_actual,
					'fechaVencimiento' 		=> $this->_fecha_actual,
					'notificar' 			=> 0,
					'facturado' 			=> 0,
					'enviado' 				=> 0,
					'produccion' 			=> 0,
					'servicio' 				=> $this->input->post('txtServicio'.$i),
					'plazo'					=> $this->obtenerPlazoProducto($idProducto),
				);
				
				/*if($servicios[$i]!=0)
				{
					$data['servicio']			=1;
					
					if($servicios[$i]!=8)
					{
						$periodo					= $this->obtenerPeriodo($servicios[$i]);
						$fechaFin					= $this->obtenerFechaFinServicio($periodo->valor*$cantidad[$i],$periodo->factor,$fechas[$i]);
						
						$data['fechaInicio']		= $fechas[$i];
						$data['fechaVencimiento']	= $fechaFin;
						$data['notificar']			= 1;
					}
				}*/
				
				$this->db->insert('cotiza_productos',$data);
				$idProductoCotizacion	= $this->db->insert_id();
				#----------------------------------------------------------------------------------------------------------#
				
				$this->registrarImpuestosProducto($idProductoCotizacion,$i);
			}
		}
	}
	
	public function obtenerPeriodo($idPeriodo)
	{
		$sql="select * from produccion_periodos
		where idPeriodo='$idPeriodo' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerFechaFinServicio($valor,$factor,$fecha)
	{
		$sql="select date_add('".$fecha."',interval ".$valor." $factor) as fechaFin";
		
		return $this->db->query($sql)->row()->fechaFin;
	}
	
	public function obtenerIdProductosCotizacion($idCotizacion)
	{
		$sql=" select idProducto from cotiza_productos
		where idCotizacion='$idCotizacion' ";
		
		return $this->db->query($sql)->result();
	}

	public function editarCotizacion() 
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta en mas de 2 tablas
		
		$divisa					= $this->obtenerDivisa($this->input->post('selectDivisas'));

		$total					= $this->input->post('txtTotal');
		$descuento				= 0;
		$idCotizacion			= $this->input->post('txtIdCotizacion');

		$data=array
		(
			'fecha'				=> $this->input->post('txtFechaCotizacion'),
			'fechaEntrega'		=> $this->input->post('txtFechaEntrega'),
			'diasCredito'		=> $this->input->post('txtDiasCredito'),
			'estatus'			=> '0',
			#'idUsuario'			=> $this->_user_id,
			'descuento'				=> $this->input->post('txtDescuentoTotal'),
			'descuentoPorcentaje'	=> $this->input->post('txtDescuentoPorcentaje0'),
			'subTotal'			=> $this->input->post('txtSubTotal'),
			'iva'				=> $this->input->post('txtIvaTotal'),
			#'ivaPorcentaje'		=> $this->input->post('ivaPorcentaje'),
			
			'total'				=> $total,
			'comentarios'		=> $this->input->post('txtComentarios'),
			'idDivisa'			=> $divisa->idDivisa,
			'tipoCambio'		=> $divisa->tipoCambio,
			'idContacto'		=> $this->input->post('selectContactosClienteCotizacion'),
			
			'asunto'			=> $this->input->post('txtAsunto'),
			'presentacion'		=> $this->input->post('txtPresentacion'),
			'condiciones'		=> $this->input->post('txtCondicionesPago'),
			'terminos'			=> $this->input->post('txtTerminos'),
			'firma'				=> $this->input->post('txtFirma'),
			'agradecimientos'	=> $this->input->post('txtAgradecimientos'),
			'idUsuario'			=> $this->input->post('selectUsuariosEnviar'),
		);
		
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->update('cotizaciones',$data);
		
		$productos=$this->obtenerIdProductosCotizacion($idCotizacion);
		
		foreach($productos as $row)
		{
			$this->db->where('idProducto',$row->idProducto);
			$this->db->delete('cotiza_productos_impuestos');
		}
		
		$this->db->where('idCotizacion',$idCotizacion);
		$this->db->delete('cotiza_productos');
		
		$this->procesarProductosCotizacion($idCotizacion);
		
		$this->configuracion->registrarBitacora('Editar cotización','Cotizaciones',$this->ventas->obtenerCotizacionSerie($idCotizacion)); //Registrar bitácora

		if ($this->db->trans_status() === FALSE or $this->resultado!="1")
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
	
	public function obtenerFechaAnterior($fecha,$valor,$criterio)
	{
		$sql=" select date_add('$fecha',interval ".$valor." $criterio) as fecha";
		
		return $this->db->query($sql)->row()->fecha;
	}
	
	public function obtenerListaResponsables($fecha)
	{
		$sql=" select a.idResponsable,
		b.correo
		from seguimiento as a
		inner join usuarios as b
		on a.idResponsable=b.idUsuario
		where a.idStatus!=3
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
		from seguimiento as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join seguimiento_servicios as c
		on a.idServicio=c.idServicio
		inner join seguimiento_status as d
		on d.idStatus=a.idStatus
		where a.idResponsable='$idResponsable'
		and a.idStatus!=3
		and a.idTiempo=0
		and (date(fecha)='$fecha' 
		or date(fechaCierre)='$fecha') ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerSeguimientosTiempo()
	{
		$sql=" select a.*, b.empresa, c.nombre as status,
		d.nombre as servicio, e.tiempo, f.correo
		from seguimiento as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join seguimiento_status as c
		on a.idStatus=c.idStatus
		inner join seguimiento_servicios as d
		on a.idServicio=d.idServicio
		inner join seguimiento_tiempos as e
		on a.idTiempo=e.idTiempo
		inner join usuarios as f
		on f.idUsuario=a.idResponsable
		where a.idStatus!=3
		and a.idTiempo>0
		and a.fechaCierre>=curdate()
		and substr(concat(curdate(),' ',curtime()),1,16) = (select substr(date_sub(a.fechaCierre, interval e.tiempo minute),1,16)) ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPronosticoIngresos($fecha)
	{
		$sql="select * from catalogos_ingresos
		where date(fecha)='$fecha'
		and formaPago='Programado' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPronosticoGastos($fecha)
	{
		$sql="select * from catalogos_egresos
		where date(fecha)='$fecha'
		and formaPago='Programado' ";
		
		return $this->db->query($sql)->result();
	}
	
	//SUMAR TODO LO QUE SE HA PAGADO
	public function sumarCobradoClientesVentas($idCliente)
	{
		$sql=" select coalesce(sum(a.pago),0) as pago
		from catalogos_ingresos as a
		inner join cotizaciones as b
		on a.idVenta=b.idCotizacion
		where b.idCliente='$idCliente'
		and a.idForma<>'4' ";
		
		$sql.=" and a.idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row()->pago;
	}
	
	public function sumarCobradoClientes($idCliente)
	{
		$sql=" select coalesce(sum(pago),0) as pago
		from catalogos_ingresos
		where idCliente='$idCliente'
		and idForma<>'4' 
		and idVenta=0  ";
		
		return $this->db->query($sql)->row()->pago;
	}
	
	//SUMAR TODO LO QUE SE DEBE
	
	public function sumarVentasCliente($idCliente)
	{
		$sql=" select coalesce(sum(total),0) as total
		from cotizaciones 
		where idCliente='$idCliente'
		and estatus=1
		and activo='1'
		and cancelada='0' ";
		
		$sql.=" and pendiente='0' ";
		
		$sql.=" and idLicencia='$this->idLicencia' ";
		
		return $this->db->query($sql)->row()->total; //Total que se ha comprado
	}
	
	//ADMINISTRACIÓN DE LLAMADAS
	public function contarLlamadas($criterio,$inicio,$fin,$idStatus,$idServicio,$permiso=0,$idUsuarioRegistro=0,$idResponsable=0,$idEstatus=0)
	{
		$sql=" select a.idSeguimiento
		
		from seguimiento as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		
		inner join clientes_contactos as c
		on b.idCliente=c.idCliente
		
		inner join usuarios as d
		on a.idResponsable=d.idUsuario
		where  date(a.fecha) between '$inicio' and '$fin'
		
		and a.idLicencia='$this->idLicencia'
		
		and b.prospecto!='1'
		
		
		and (a.comentarios like '%$criterio%'
		or b.empresa like '%$criterio%'
		or d.nombre like '%$criterio%' ) ";
		
		$sql.=$idStatus>0?" and a.idStatus='$idStatus' ":'';
		$sql.=$idServicio>0?" and a.idServicio='$idServicio' ":'';
		$sql.=$permiso==0?" and a.idResponsable='$this->_user_id' ":'';
		#$sql.=$idUsuarioRegistro>0?" and a.idUsuarioRegistro='$idUsuarioRegistro' ":'';
		
		$sql.=$idResponsable>0?" and a.idResponsable='$idResponsable' ":'';
		$sql.=$idEstatus>0?" and a.idEstatus='$idEstatus' ":'';
		
		if($this->idRol!=1)
		{
			$sql.=" and a.idUsuarioRegistro='$this->_user_id' ";
		}
		else
		{
			$sql.=$idUsuarioRegistro>0?" and a.idUsuarioRegistro='$idUsuarioRegistro' ":'';
		}

		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerLlamadas($numero,$limite,$criterio,$inicio,$fin,$idStatus,$idServicio,$permiso=0,$idUsuarioRegistro=0,$idResponsable=0,$idEstatus=0)
	{
		$sql=" select a.comentarios, a.fecha, a.idSeguimiento, a.folio,
		b.empresa, c.nombre as contacto, a.fechaCierre, a.lugar, 
		c.telefono, concat(d.nombre,' ',d.apellidoPaterno,' ',d.apellidoMaterno) as responsable,
		e.nombre  as status, e.idStatusIgual, e.color, a.bitacora,
		(select f.nombre from seguimiento_servicios as f where f.idServicio=a.idServicio) as servicio, c.email,
		
		".(sistemaActivo=='IEXE'?" (select f.nombre from seguimiento_estatus as f where f.idEstatus=a.idEstatus) as estatus, 
		(select f.color from seguimiento_estatus as f where f.idEstatus=a.idEstatus) as estatusColor, ":"")."
		
		(select concat('Folio: ',g.folio, ', Serie: ', g.serie) from cotizaciones as g where g.idCotizacion=a.idCotizacion) as cotizacion,
		(select concat('Folio: ',g.folio, ', Orden: ', g.ordenCompra) from cotizaciones as g where g.idCotizacion=a.idVenta) as venta
		".(sistemaActivo=='IEXE'?", (select concat(f.nombre, ' ', f.apellidoPaterno, ' ', f.apellidoMaterno) from usuarios as f where f.idUsuario=a.idUsuarioRegistro) as usuarioRegistro,
		
		(select g.nombre from clientes_programas as g inner join clientes_academicos as h on g.idPrograma=h.idPrograma where h.idCliente=b.idCliente) as programa ":'')."

		from seguimiento as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		inner join clientes_contactos as c
		on b.idCliente=c.idCliente
		inner join usuarios as d
		on a.idResponsable=d.idUsuario
		
		inner join seguimiento_status as e
		on e.idStatus=a.idStatus
		
		where date(a.fecha) between '$inicio' and '$fin'
		and a.idLicencia='$this->idLicencia'
		
		and b.prospecto!='1'
		
		and (a.comentarios like '%$criterio%'
		or b.empresa like '%$criterio%'
		or d.nombre like '%$criterio%' )
		
		and a.idContacto=c.idContacto 
		and b.prospecto!=1 ";
		
		$sql.=$idStatus>0?" and a.idStatus='$idStatus' ":'';
		$sql.=$idServicio>0?" and a.idServicio='$idServicio' ":'';
		$sql.=$permiso==0?" and a.idResponsable='$this->_user_id' ":'';
		$sql.=$idResponsable>0?" and a.idResponsable='$idResponsable' ":'';
		$sql.=$idEstatus>0?" and a.idEstatus='$idEstatus' ":'';
		
		if($this->idRol!=1)
		{
			$sql.=" and a.idUsuarioRegistro='$this->_user_id' ";
		}
		else
		{
			$sql.=$idUsuarioRegistro>0?" and a.idUsuarioRegistro='$idUsuarioRegistro' ":'';
		}
		
			
		$sql.=" order by a.fecha desc ";
		$sql.=" limit $limite, $numero";
		
		return $this->db->query($sql)->result();
	}
	
	
	//ARCHIVOS PARA SEGUIMIENTO
	
	public function obtenerArchivosSeguimiento($idSeguimiento)
	{
		$sql="select * from seguimiento_archivos
		where idSeguimiento='$idSeguimiento'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerArchivoSeguimiento($idArchivo)
	{
		$sql="select * from seguimiento_archivos
		where idArchivo='$idArchivo'";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarArchivoSeguimiento($idArchivo)
	{
		$archivo	= $this->obtenerArchivoSeguimiento($idArchivo);
		
		$this->db->where('idArchivo',$idArchivo);
		$this->db->delete('seguimiento_archivos');
		
		if($this->db->affected_rows()>=1)
		{
			$this->configuracion->registrarBitacora('Borrar archivo','Clientes - Seguimiento',$archivo->nombre); //Registrar bitácora
			
			if(file_exists(carpetaSeguimientoClientes.$archivo->idArchivo.'_'.$archivo->nombre))
			{
				unlink(carpetaSeguimientoClientes.$archivo->idArchivo.'_'.$archivo->nombre);
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
			'idSeguimiento'	=> $idSeguimiento,
			'nombre'		=> $nombre,
			'tamano'		=> $tamano,
			'fecha'			=> $this->_fecha_actual,
			'idUsuario'		=> $this->_user_id,
		);
		
		$this->db->insert('seguimiento_archivos',$data);
		$idArchivo=$this->db->insert_id();
		
		$this->configuracion->registrarBitacora('Registrar archivo','Clientes - Seguimiento',$nombre); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?$idArchivo:0;
	}
	
	public function obtenerClienteCuentaCatalogo($idCliente)
	{
		$sql="select idCuentaCatalogo from clientes 
		where idCliente='$idCliente' ";
		
		$cliente	= $this->db->query($sql)->row();
		
		return $cliente!=null?$cliente->idCuentaCatalogo:0;
	}

	
	//DOCUMENTOS DE LOS CLIENTES
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function obtenerDocumentosCliente($idCliente)
	{
		$sql="select * from clientes_documentos
		where idCliente='$idCliente'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDocumentoCliente($idDocumento)
	{
		$sql="select * from clientes_documentos
		where idDocumento='$idDocumento'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerDocumentosTipoCliente($idTipo=0, $idCliente=0)
	{
		$sql="select * from clientes_documentos
		where idTipo='$idTipo'
		and idCliente='$idCliente' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function comprobarDocumentosCliente($idCliente)
	{
		$sql=" select a.nombre,
		(select count(b.idDocumento) from clientes_documentos as b where b.idTipo=a.idTipo and b.idCliente='$idCliente') as numero
		from clientes_documentos_tipos as a
		where a.activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarDocumentosCliente($idCliente)
	{
		$idClienteDocumentos	= $this->input->post('txtIdClienteDocumentos');
		$documentos				= $this->obtenerDocumentosTemporal($idClienteDocumentos);
		
		foreach($documentos as $row)
		{
			$data =array
			(
				'idTipo'			=> $row->idTipo,
				'nombre'			=> $row->nombre,
				'tamano'			=> $row->tamano,
				'fecha'				=> $this->_fecha_actual,
				'id'				=> $row->id,
				'idCliente'			=> $idCliente,
			);
			
			$this->db->insert('clientes_documentos',$data);
		}
		
		$this->borrarDocumentosTemporal($idClienteDocumentos);
	}
	
	public function borrarDocumentosTemporal($idCliente)
	{
		$this->db->where('idCliente',$idCliente);
		$this->db->delete('clientes_documentos_temporal');
	}
	
	public function registrarDocumentoTemporal($idTipo,$nombre,$tamano,$id,$idCliente,$temporal)
	{
		$data =array
		(
			'idTipo'			=> $idTipo,
			'nombre'			=> $nombre,
			'tamano'			=> $tamano,
			'fecha'				=> $this->_fecha_actual,
			'id'				=> $id,
			'idCliente'			=> $idCliente,
		);
		
		if($temporal==1)
		{
			$this->db->insert('clientes_documentos_temporal',$data);
		}
		else
		{
			$this->db->insert('clientes_documentos',$data);
		}

		$idDocumento=$this->db->insert_id();

		return $this->db->affected_rows()>=1?$idDocumento:0;
	}
	
	public function obtenerDocumentosTemporal($idCliente)
	{
		$sql="select * from clientes_documentos_temporal
		where idCliente='$idCliente'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDocumentoTemporal($idDocumento)
	{
		$sql="select * from clientes_documentos_temporal
		where idDocumento='$idDocumento'";
		
		return $this->db->query($sql)->row();
	}
	
	public function borrarDocumentoTemporal($idDocumento,$temporal=1)
	{
		$documento	= $temporal==1?$this->obtenerDocumentoTemporal($idDocumento):$this->obtenerDocumentoCliente($idDocumento);
		
		$this->db->where('idDocumento',$idDocumento);
		
		if($temporal==1)
		{
			$this->db->delete('clientes_documentos_temporal');
		}
		else
		{
			$this->db->delete('clientes_documentos');
		}
		
		if($this->db->affected_rows()>=1)
		{
			#$this->configuracion->registrarBitacora('Borrar documento','Administración - Recursos humanos',$documento->nombre); //Registrar bitácora
			
			if(file_exists(carpetaClientesDocumentos.$documento->id.'_'.$documento->nombre))
			{
				unlink(carpetaClientesDocumentos.$documento->id.'_'.$documento->nombre);
			}
			
			return array("1");
		}
		else
		{
			return array("0");
		}
	}
	
	public function verificarDocumentosTemporal()
	{
		$sql=" select count(idDocumento) as numero from clientes_documentos_temporal
		where date(fecha)<'$this->_fecha_actual' ";
		
		return $this->db->query($sql)->row()->numero;
	}
		
		
	//BORRAR DOCUMENTOS TEMPORAL	
	public function obtenerDocumentosTemporalFecha()
	{
		$sql="select * from clientes_documentos_temporal
		where date(fecha)<'$this->_fecha_actual'";
		
		return $this->db->query($sql)->result();
	}
	
	public function borrarDocumentosTemporales()
	{
		$documentos	= $this->obtenerDocumentosTemporalFecha();
		
		foreach($documentos as $row)
		{
			$this->db->where('idDocumento',$row->idDocumento);
			$this->db->delete('clientes_documentos_temporal');
			
			#$this->configuracion->registrarBitacora('Borrar documento','Administración - Recursos humanos',$documento->nombre); //Registrar bitácora
			
			if(file_exists(carpetaClientesDocumentos.$row->id.'_'.$row->nombre))
			{
				unlink(carpetaClientesDocumentos.$row->id.'_'.$row->nombre);
			}
		}
	}
	
	//PREINSCRITOS
	public function contarPreinscritos($criterio='',$idPromotor=0,$idPrograma=0,$idCampana=0,$todos=0,$inicio='',$fin='',$idFuente=0,$matricula=0,$mes='',$idPeriodo=0)
	{
		$sql=" select a.idCliente
		from clientes as a
		inner join zonas as b
		on a.idZona=b.idZona 
		inner join clientes_academicos as e
		on a.idCliente=e.idCliente
		
		inner join seguimiento as f
		on f.idCliente=a.idCliente
		
		inner join seguimiento_detalles as g
		on g.idSeguimiento=f.idSeguimiento
		
		where a.activo='1'
		and e.confirmado='1' 
		and date(e.fechaPreinscrito) between '$inicio' and '$fin' ";
		 #and  a.prospecto='1'
		
		#$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';
		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.telefono like '$criterio%' or a.email like '$criterio%' or a.movil like '$criterio%') ":'';		
		$sql.=$idPrograma!=0?" and  e.idPrograma='$idPrograma' ":'';
		$sql.=$idCampana!=0?" and a.idCampana='$idCampana' ":'';
		$sql.=$idPromotor!=0?" and a.idPromotor='$idPromotor' ":'';
		$sql.=$idFuente!=0?" and a.idFuente='$idFuente' ":'';
		
		$sql.=$matricula==1?" and length(e.matricula)>0 ":'';
		$sql.=$matricula==2?" and length(e.matricula)=0 ":'';
		
		$sql.=$mes!=''?" and e.mes='$mes' ":'';
		$sql.=$idPeriodo!=0?" and (select count(j.idPeriodo) from clientes_periodos as j inner join clientes_periodos_relacion as k on k.idPeriodo=j.idPeriodo where k.idCliente=a.idCliente and j.idPeriodo='$idPeriodo' ) >0 ":'';
		
		$sql.=" group by a.idCliente ";

		return $this->db->query($sql)->num_rows();
	}

	public function obtenerPreinscritos($numero,$limite,$criterio='',$idPromotor=0,$idPrograma=0,$idCampana=0,$todos=0,$inicio='',$fin='',$idFuente=0,$matricula=0,$mes='',$idPeriodo=0)
	{
		$sql=" select a.idCliente, a.empresa, e.matricula, a.telefono, a.movil, a.idFuente,
		concat(a.nombre,' ',a.paterno,' ',a.materno) as prospecto, e.matricula, e.mes,
		a.email,  concat(h.nombre,' ',h.apellidoPaterno,' ',h.apellidoMaterno) as promotor,
		g.fechaSeguimiento, g.horaInicial, g.horaFinal, i.color, i.nombre as status,
		(select j.nombre from clientes_programas as j where j.idPrograma=e.idPrograma) as programa,
		(select j.nombre from clientes_campanas as j where j.idCampana=a.idCampana) as campana,
		
		(select count(j.idFichero) from clientes_ficheros as j where j.idCliente=a.idCliente) as numeroArchivos,
		
		(select j.nombre from clientes_periodos as j inner join clientes_periodos_relacion as k on k.idPeriodo=j.idPeriodo where k.idCliente=a.idCliente order by idRelacion desc limit 1) as periodo
		
		from clientes as a
		inner join zonas as b
		on a.idZona=b.idZona 
		inner join clientes_academicos as e
		on a.idCliente=e.idCliente
		
		inner join seguimiento as f
		on f.idCliente=a.idCliente
		
		inner join seguimiento_detalles as g
		on g.idSeguimiento=f.idSeguimiento
		
		inner join usuarios as h
		on h.idUsuario=a.idPromotor
		
		inner join seguimiento_estatus as i
		on i.idEstatus=f.idEstatus
		
		where a.activo='1'
		and e.confirmado='1'
		and date(e.fechaPreinscrito) between '$inicio' and '$fin' ";
		 
		 #and  a.prospecto='1'
		
		#$sql.=$todos==0?" and a.idUsuario='$this->_user_id' ":'';
		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.razonSocial like '$criterio%' or concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.telefono like '$criterio%' or a.email like '$criterio%' or a.movil like '$criterio%') ":'';		
		$sql.=$idPrograma!=0?" and  e.idPrograma='$idPrograma' ":'';
		$sql.=$idCampana!=0?" and a.idCampana='$idCampana' ":'';
		$sql.=$idPromotor!=0?" and a.idPromotor='$idPromotor' ":'';
		$sql.=$idFuente!=0?" and a.idFuente='$idFuente' ":'';
		
		$sql.=$matricula==1?" and length(e.matricula)>0 ":'';
		$sql.=$matricula==2?" and length(e.matricula)=0 ":'';
		
		$sql.=$mes!=''?" and e.mes='$mes' ":'';
		$sql.=$idPeriodo!=0?" and (select count(j.idPeriodo) from clientes_periodos as j inner join clientes_periodos_relacion as k on k.idPeriodo=j.idPeriodo where k.idCliente=a.idCliente and j.idPeriodo='$idPeriodo' ) >0 ":'';
		
		$sql.=" group by a.idCliente
		order by prospecto asc ";

		$sql.= $numero>0? " limit $limite,$numero ":'';
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//CLIENTES
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function contarClientesBusqueda($criterio='')
	{    
		$sql=" select a.idCliente
		from clientes as a
		where a.activo='1' ";
		 
		$sql.=strlen($criterio)>0?" and a.empresa like '$criterio%' ":'';
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerClientesBusqueda($numero=0,$limite=0,$criterio='')
	{    
		$sql=" select a.idCliente, a.empresa, a.limiteCredito, a.calle, 
		a.numero, a.colonia, a.localidad, a.municipio, a.email, a.telefono,
		
		(select c.idSucursal from clientes_sucursales as c where c.idCliente=a.idCliente and c.idSucursal!='$this->idLicencia' limit 1) as idSucursal
		
		from clientes as a
		where a.activo='1' ";
		
		#(select c.idSucursal from clientes_sucursales as c where c.idCliente=a.idCliente and c.idLicenciaTraspaso='$this->idLicencia' limit 1) as idSucursal
		 
		$sql.=strlen($criterio)>0?" and (a.empresa like '$criterio%' or a.alias like '$criterio%' ) ":'';
		
		$sql.= $numero>0?" limit $limite,$numero ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function editarClienteVenta()
	{
		$data =array
		(
			'email'				=> $this->input->post('email'),
			'telefono'			=> $this->input->post('telefono'),
		);
		
		$this->db->where('idCliente',$this->input->post('idCliente'));
		$this->db->update('clientes',$data);
		
		return $this->db->affected_rows()>=1?array('1','El registro se ha editado correctamente'):array('0','El registro no tuvo cambios');
	}
	
	public function registrarClienteVenta()
	{
		if(!$this->comprobarRegistroCliente(reemplazarApostrofe($this->input->post('txtEmpresa')),reemplazarApostrofe($this->input->post('txtRfc'))))
		{
			return array('0',registroDuplicado);
			exit;
		}
		
		$this->db->trans_start(); 
		
		$idCuentaCatalogo		= $this->input->post('txtIdCuentaCatalogo');
		$saldoInicial			= $this->input->post('txtSaldoInicial');
		
		$data=array
		(
			'empresa'			=> $this->input->post('txtEmpresa'),
			'precio'			=> 1,	 
			'email'				=> $this->input->post('txtEmail'),
			'telefono'			=> $this->input->post('txtTelefono'),
			'calle'				=> $this->input->post('txtCalle'),
			'numero'			=> $this->input->post('txtNumero'),
			'numero'			=> $this->input->post('txtNumeroInterior'),
			'localidad'			=> $this->input->post('txtLocalidad'),
			'rfc'				=> $this->input->post('txtRfc'),
			'codigoPostal'		=> trim($this->input->post('txtCodigoPostal')),
			'colonia'			=> trim($this->input->post('txtColonia')),
			'municipio'			=> trim($this->input->post('txtMunicipio')),
			'estado'			=> trim($this->input->post('txtEstado')),
			'pais'				=> trim($this->input->post('txtPais')),
			'razonSocial'		=> $this->input->post('txtRazonSocial'),
			#'idSucursal'		=> $this->input->post('selectSucursal'),
			'idUsuario' 		=> $this->_user_id,
			'fechaRegistro'		=> $this->_fecha_actual,
			'idLicencia'		=> $this->idLicencia,
			'idZona'			=> '1',
			'prospecto'			=> '0',
			
			'alias'				=> $this->input->post('txtAlias'),

		);
		
		if($this->input->post('selectSucursal')>0)
		{
			$data['idSucursal']				= $this->input->post('selectSucursal');
			$data['idLicenciaTraspaso']		= $this->idLicencia;
		}
		
		
		$data	= procesarArreglo($data);
		
		$this->db->insert('clientes', $data);
		$idCliente = $this->db->insert_id();

		$this->configuracion->registrarBitacora('Registrar cliente','Clientes',$data['empresa']); 
		
		$contacto=array
		(
			'idCliente' 	=> $idCliente,
			'nombre' 		=> $this->input->post('txtEmpresa'),
			'email' 		=> $this->input->post('txtEmail'),
			'telefono' 		=> $this->input->post('txtTelefono'),
			'fechaRegistro' => $this->_fecha_actual,
			'idUsuario' 	=> $this->_user_id,
		);
		
		$contacto	= procesarArreglo($contacto);
		$this->db->insert('clientes_contactos', $contacto); 
		$idContacto	= $this->db->insert_id();
		
		
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
			
			return array('1',registroCorrecto,$idCliente,$data['empresa']);
		}
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#-----------------------------------------------  DIRECCIONES----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function obtenerDirecciones($idCliente=0,$tipo='')
	{    
		$sql=" select a.*,
		(select b.idDireccion from facturas as b where b.idDireccion=a.idDireccion limit 1) as relaciones
		from clientes_direcciones as a
		where a.idDireccion>0 
		and activo='1'
		and a.idCliente='$idCliente' ";

		if($tipo!=3)
		{
			$sql.=strlen($tipo)>0?" and a.tipo='$tipo' ":'';
		}
		
		if($tipo==3)
		{
			$sql.=" and (a.tipo='0' or a.tipo='2') ";
		}
		
		
		
		$sql."= order by a.razonSocial asc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerDireccionesFiscales($idCliente=0)
	{    
		$sql=" select a.*
		from clientes_direcciones as a
		where a.idDireccion>0 
		and activo='1'
		and a.idCliente='$idCliente' ";

		$sql.=" and (a.tipo='1' or a.tipo='2') ";

		$sql."= order by a.razonSocial asc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerDireccionesEditar($idDireccion)
	{    
		$sql=" select a.*, b.clave as claveRegimen, b.nombre as regimenFiscal 
		from clientes_direcciones  as a
		inner join fac_catalogos_regimen_fiscal as b
		on a.idRegimen=b.idRegimen
		where idDireccion='$idDireccion'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarDirecciones($rfc,$empresa)
	{
		$sql=" select idDireccion
		from clientes_direcciones
		where rfc='$rfc'
		and razonSocial='$empresa'
		and activo='1' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarDirecciones()
	{
		/*if(!$this->comprobarDirecciones($this->input->post('txtRfc'),$this->input->post('txtEmpresa')))
		{
			return array('0',registroDuplicado); 
		}*/
		
		$data=array
		(
			'rfc'					=> $this->input->post('txtRfc'),
			'razonSocial'			=> $this->input->post('txtEmpresa'),
			'calle'					=> $this->input->post('txtCalle'),
			'numero'				=> $this->input->post('txtNumero'),
			'colonia'				=> $this->input->post('txtColonia'),
			'localidad'				=> $this->input->post('txtLocalidad'),
			'municipio'				=> $this->input->post('txtMunicipio'),
			'estado'				=> $this->input->post('txtEstado'),
			'codigoPostal'			=> $this->input->post('txtCodigoPostal'),
			'telefono'				=> $this->input->post('txtTelefono'),
			
			'email'					=> $this->input->post('txtEmail'),
			'pais'					=> $this->input->post('txtPais'),
			
			'tipo'					=> $this->input->post('selectTipoDireccion'),
			'idCliente'				=> $this->input->post('idCliente'),
			'idRegimen'				=> $this->input->post('selectRegimenFiscal'),
		);

	    $this->db->insert('clientes_direcciones',$data);
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarDirecciones()
	{
		$data=array
		(
			'rfc'					=> $this->input->post('txtRfc'),
			'razonSocial'			=> $this->input->post('txtEmpresa'),
			'calle'					=> $this->input->post('txtCalle'),
			'numero'				=> $this->input->post('txtNumero'),
			'colonia'				=> $this->input->post('txtColonia'),
			'localidad'				=> $this->input->post('txtLocalidad'),
			'municipio'				=> $this->input->post('txtMunicipio'),
			'estado'				=> $this->input->post('txtEstado'),
			'codigoPostal'			=> $this->input->post('txtCodigoPostal'),
			'telefono'				=> $this->input->post('txtTelefono'),
			'email'					=> $this->input->post('txtEmail'),
			'pais'					=> $this->input->post('txtPais'),
			'tipo'					=> $this->input->post('selectTipoDireccion'),
			'idRegimen'				=> $this->input->post('selectRegimenFiscal'),
		);
		
		$this->db->where('idDireccion',$this->input->post('txtIdDireccion'));
	    $this->db->update('clientes_direcciones',$data);
		
		return $this->db->affected_rows()>=1?array("1",'El registro ha sido exitoso'):array("0",'Sin cambios'); 
	}

	public function borrarDirecciones($idDireccion)
	{
		$this->db->where('idDireccion',$idDireccion);
		$this->db->update('clientes_direcciones',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerDireccionCotizacion($idCotizacion)
	{    
		$sql="select a.* 
		from clientes_direcciones  as a
		inner join cotizaciones as b
		on a.idDireccion=b.idDireccion
		where b.idCotizacion='$idCotizacion'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerDireccionCompra($idDireccion)
	{    
		$sql="select a.*, b.razonSocial 
		from clientes_direcciones  as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where a.idDireccion='$idDireccion'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarClienteSucursal()
	{
		$sql=" select count(a.idCliente) as numero
		from clientes_sucursales as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where b.activo='1'
		and a.idLicenciaTraspaso='$this->idLicencia'
		and a.idSucursal>0 ";
		
		#echo $sql;
		
		return $this->db->query($sql)->row()->numero>0?false:true;
	}
	
	public function obtenerClienteSucursal($idCliente)
	{
		$sql=" select a.*
		from clientes_sucursales as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where b.activo='1'
		and a.idLicenciaTraspaso='$this->idLicencia'
		and a.idCliente='$idCliente' ";

		return $this->db->query($sql)->row();
	}
	
	public function comprobarClienteSucursalRegistro($idCliente)
	{
		$sql=" select count(a.idCliente) as numero
		from clientes_sucursales as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where b.activo='1'
		and a.idCliente='$idCliente' ";

		return $this->db->query($sql)->row()->numero>0?false:true;
	}
	
	public function obtenerSucursalesCliente($idCliente)
	{
		$sql=" select a.*, b.usuario as sucursal
		from clientes_sucursales as a
		inner join licencias as b
		on a.idSucursal=b.idLicencia
		where a.idCliente='$idCliente'
		and a.idLicenciaTraspaso='$this->idLicencia' ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarSucursalesCliente()
	{
		$this->db->trans_start(); 
		
		$idCliente	= $this->input->post('txtIdClienteSucursal');
		$numero		= $this->input->post('txtNumeroSucursales');
		
		$this->db->where('idCliente', $idCliente);
		$this->db->where('idLicenciaTraspaso', $this->idLicencia);
		$this->db->delete('clientes_sucursales');
		
		for($i=0;$i<=$numero;$i++)
		{
			$idSucursal	= $this->input->post('txtIdLicencia'.$i);
			
			if($idSucursal>0)
			{
				$data=array
				(
					'idCliente' 			=> $idCliente,
					'idLicenciaTraspaso' 	=> $this->idLicencia,
					'idSucursal' 			=> $idSucursal,
				);

				$this->db->insert('clientes_sucursales', $data); 
			}
			
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
}
