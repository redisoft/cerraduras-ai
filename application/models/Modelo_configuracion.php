<?php
class Modelo_configuracion extends CI_Model
{
    protected $_fecha_actual;
    protected $_table;
    protected $_user_id;
    protected $_user_name;
	protected $idLicencia;
	protected $idRol;
	protected $fechaCorta;
	protected $usuarioActivo;
	protected $idEstacion;

    function __construct()
	{
		parent::__construct();
		$this->config->load('datatables',TRUE);
		
		$this->_table 			= $this->config->item('datatables');
		$this->_fecha_actual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_user_id 		= $this->session->userdata('id');
		$this->_user_name 		= $this->session->userdata('nombreUsuarioSesion');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->idRol 			= $this->session->userdata('rol');
		$this->usuarioActivo 	= $this->session->userdata('usuarioActivo');
		$this->fechaCorta 		= date('Y-m-d');
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
	
	public function comprobarSesion()
	{
		if(!$this->session->userdata('id') or $this->session->userdata('rol') or sistemaActivo!='demo')
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function editarEfectivo()
	{
		$data=array
		(
			'efectivo'	=> trim($this->input->post('efectivo')),
		);
		
		$this->db->where('idLicencia',$this->idLicencia);
		$this->db->update('configuracion',$data);

		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#--------------------------------------------------CONVERSION----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function registrarConversion()
	{
		$data=array
		(
			'idUnidad'		=> $this->input->post('idUnidad'),
			'nombre'		=> trim($this->input->post('nombre')),
			'valor'			=> trim($this->input->post('valor')),
			'referencia'	=> trim($this->input->post('referencia')),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('unidades_conversiones',$data);
		
		$this->registrarBitacora('Registrar conversión','Configuración - Unidades - Conversiones',$data['nombre'].', '.$this->obtenerDetalleUnidad($this->input->post('idUnidad'))); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerConversiones($idUnidad)
	{
		$sql="select * from unidades_conversiones
		where idUnidad='$idUnidad'
		and activo='1' ";	
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerNombreEmpresa()
	{
		$sql=" select nombre 
		from configuracion
		where idLicencia='$this->idLicencia'";	
		
		$empresa	= $this->db->query($sql)->row();
		
		
		$sql=" select nombre 
		from configuracion_estaciones
		where idEstacion='$this->idEstacion'";	
		
		$estacion	= $this->db->query($sql)->row();
		
		return $empresa!=null?$empresa->nombre.($estacion!=null?' | Estación '.$estacion->nombre:''):'';
	}
	
	public function obtenerConversion($idConversion)
	{
		$sql="select * from unidades_conversiones
		where idConversion='$idConversion'";	
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerDetallesConversion($idConversion)
	{
		$sql="select a.nombre, b.descripcion
		from unidades_conversiones as a
		inner join unidades as b
		on a.idUnidad=b.idUnidad
		where a.idConversion='$idConversion'";	
		
		$conversion	= $this->db->query($sql)->row();
		
		return $conversion!=null?array($conversion->nombre,$conversion->descripcion):array('Sin detalles de conversión','');
	}
	
	public function editarConversion()
	{
		$data=array
		(
			'nombre'		=>$this->input->post('nombre'),
			'valor'			=>$this->input->post('valor'),
			'referencia'	=>$this->input->post('referencia'),
		);
		
		$this->db->where('idConversion',$this->input->post('idConversion'));
		$this->db->update('unidades_conversiones',$data);
		
		$conversion	= $this->obtenerDetallesConversion($this->input->post('idConversion'));
		
		$this->registrarBitacora('Editar conversión','Configuración - Unidades - Conversiones',$data['nombre'].', '.$conversion[1]); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarConversion($idConversion)
	{
		$sql="select idConversion
		from produccion_materiales
		where idConversion=$idConversion";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select idConversion
		from rel_producto_material
		where idConversion=$idConversion";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
	}
	
	public function borrarConversion($idConversion)
	{
		if($this->comprobarConversion($idConversion)>0)
		{
			return "0";
		}
		
		$conversion	= $this->obtenerDetallesConversion($idConversion);
		$this->registrarBitacora('Borrar conversión','Configuración - Unidades - Conversiones',$conversion[0].', '.$conversion[1]); //Registrar bitácora
		
	    $this->db->where('idConversion',$idConversion);
		$this->db->update('unidades_conversiones',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerUnidad($idUnidad)
	{
		$sql="select * from unidades
		where idUnidad='$idUnidad' ";	
		
		return $this->db->query($sql)->row();
	}
 
	
	public function activarEstilo($color)
	{
		$data['color']	= $color;
		
		$this->db->where('idLicencia',$this->idLicencia);
		$this->db->update('configuracion',$data);
		
		$this->registrarBitacora('Cambiar estilo','Configuración - Estilo',$color); //Registrar bitácora
		
		return "1";
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#-----------------------------------------------CUENTAS CONTABLES------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	public function editarCuentaContable()
	{
		$data=array
		(
			'nombre'			=>$this->input->post('nombre'),
			'nivel1'			=>$this->input->post('nivel1'),
			'clave1'			=>$this->input->post('clave1'),
			'nivel2'			=>$this->input->post('nivel2'),
			'clave2'			=>$this->input->post('clave2'),
			'nivel3'			=>$this->input->post('nivel3'),
			'clave3'			=>$this->input->post('clave3'),
			'nivel4'			=>$this->input->post('nivel4'),
			'clave4'			=>$this->input->post('clave4'),
		);
		
		$this->db->where('idCuenta',$this->input->post('idCuenta'));
		$this->db->update('cuentas_contables',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
		
	}
	
	public function obtenerCuentaContable($idCuenta)
	{
		$sql="select * from cuentas_contables
		where idLicencia='$this->idLicencia'
		and idCuenta='$idCuenta'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerCuentasContables()
	{
		$sql="select * from cuentas_contables
		where idLicencia='$this->idLicencia'
		order by nombre asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function agregarCuentaContable()
	{
		$data=array
		(
			'nombre'			=>$this->input->post('nombre'),
			'nivel1'			=>$this->input->post('nivel1'),
			'clave1'			=>$this->input->post('clave1'),
			'nivel2'			=>$this->input->post('nivel2'),
			'clave2'			=>$this->input->post('clave2'),
			'nivel3'			=>$this->input->post('nivel3'),
			'clave3'			=>$this->input->post('clave3'),
			'nivel4'			=>$this->input->post('nivel4'),
			'clave4'			=>$this->input->post('clave4'),
			'idLicencia'		=>$this->idLicencia,
		);
		
		$this->db->insert('cuentas_contables',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
		
	}
	
	public function comprobarCuentaContable($idCuenta)
	{
		$sql="select nombre
		from gastos
		where idCuentaContable='$idCuenta'";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function borrarCuentaContable($idCuenta)
	{
		if($this->comprobarCuentaContable($idCuenta)>0)
		{
			return "0";	
		}
		
		$this->db->where('idCuenta',$idCuenta);
		$this->db->delete('cuentas_contables');
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------PROCESOS-----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function obtenerProcesos($idOrden=0)
	{
		$sql="select a.* 
		from produccion_orden_procesos as a
		where a.activo='1' ";
		
		if($idOrden!=0)
		{
			$sql.=" and (select count(b.idRelacion) from rel_orden_proceso as b where b.idOrden='$idOrden' and b.idProceso=a.idProceso) = 0 ";
		}
		
		$sql.=" order by a.idProceso asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerProceso($idProceso)
	{
		$sql="select * from produccion_orden_procesos
		where idProceso ='$idProceso'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarProcesoRegistro($nombre)
	{
		$sql ="select idProceso
		from  produccion_orden_procesos 
		where activo='1'
		and nombre='$nombre'";

		return $this->db->query($sql)->num_rows()>0?false:true;
	}

	
	public function agregarProceso()
	{
		if(!$this->comprobarProcesoRegistro($this->input->post('nombre')))
		{
			return array('0',registroDuplicado);
		}

		$data=array
		(
			'nombre'		=>$this->input->post('nombre'),
			'idLicencia'	=>$this->idLicencia,
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('produccion_orden_procesos',$data);
		
		$this->registrarBitacora('Registrar proceso','Configuración - Procesos',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function editarProceso()
	{
		$data=array
		(
			'nombre'		=>$this->input->post('nombre'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idProceso',$this->input->post('idProceso'));
		$this->db->update('produccion_orden_procesos',$data);
		
		$this->registrarBitacora('Editar proceso','Configuración - Procesos',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarProceso($idProceso)
	{
		$sql="select * from rel_orden_proceso
		where idProceso='$idProceso'";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerDetalleProceso($idProceso)
	{
		$sql="select nombre
		from produccion_orden_procesos
		where idProceso='$idProceso' ";
		
		$proceso	= $this->db->query($sql)->row();
		
		return $proceso!=null?$proceso->nombre:'';
	}
	
	public function borrarProceso($idProceso)
	{
		if($this->comprobarProceso($idProceso)>0)
		{
			return "0";	
		}
		
		$this->db->where('idProceso',$idProceso);
		$this->db->update('produccion_orden_procesos',array('activo'=>'0'));
		
		$this->registrarBitacora('Borrar proceso','Configuración - Procesos',$this->obtenerDetalleProceso($idProceso)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------TIENDAS------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#

	public function obtenerTienda($idTitular)
	{
		$sql="select * from tiendas
		where idTitular='$idTitular'
		and activa='1'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerRolTienda()
	{
		$sql="select * from roles
		where nombre='Tienda'
		and idLicencia='$this->idLicencia'
		and activo='0'";	
		
		return $this->db->query($sql)->row();
	}
	
	/*public function obtenerTiendas()
	{    
		$sql="select a.*, b.apellidoPaterno, 
		b.apellidoMaterno, b.nombre
		from tiendas as a
		inner join usuarios as b
		on a.idTitular=b.idUsuario
		where b.idLicencia='$this->idLicencia'
		and a.activa='1'";
		
		$query=$this->db->query($sql);
		
		return ($query->num_rows()> 0)? $query->result() : NULL;
	}*/
	
	public function obtenerTiendas()
	{    
		$sql="select * from tiendas
		where activa='1'";
		
		return $this->db->query($sql)->result();
	}
	
	public function agregarTienda()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$rolTienda=$this->obtenerRolTienda();
		
		$data=array
		(
			'name'			=>$this->input->post('nombre'),
			'paterno'		=>$this->input->post('materno'),
			'materno'		=>$this->input->post('paterno'),
			'createDate'	=>$this->_fecha_actual,
			'create_by'		=>$this->_user_id,
			'correo'		=>$this->input->post('email'),
			'username'		=>$this->input->post('usuario'),
			'password'		=>sha1($this->input->post('password')),
			'idLicencia'	=>$this->idLicencia,
			'role'			=>$rolTienda->idRol,
			'tienda'		=>'1',
		);
		
		$this->db->insert('usuarios',$data);
		
		$idUsuario=$this->db->insert_id();
		
		$data=array
		(
			'nombre'=>$this->input->post('nombreTienda'),
			'direccion'=>$this->input->post('direccion'),
			'numero'=>$this->input->post('numero'),
			'colonia'=>$this->input->post('colonia'),
			'codigoPostal'=>$this->input->post('codigoPostal'),
			'ciudad'=>$this->input->post('ciudad'),
			'telefono'=>$this->input->post('telefono'),
			'idTitular'=>$idUsuario,
			'idUsuario'=>$this->_user_id,
			'idLicencia'=>$this->idLicencia,
		);
		
		$this->db->insert('tiendas',$data);
		
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
	#----------------------------------------------------------------------------------------------------------#
	
	public function getAllVariables()
	{
		$sql="select * from configuracion
		where idLicencia='$this->idLicencia'";
		
		$query = $this->db->query($sql);
		
		return ($query->num_rows() > 0)? $query->result_array() : NULL;
	}
	
	public function obtenerConfiguraciones($idLicencia=1)
	{
		$sql="select * from configuracion
		where idLicencia='$idLicencia'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerConfiguracionActual()
	{
		$sql="select * from configuracion
		where idLicencia='$this->idLicencia'";
		
		return $this->db->query($sql)->row();
	}
	
	
	public function obtenerLicenciasTraspaso()
	{
		$sql=" select idLicencia, id, nombre
		from configuracion
		where idLicencia!='$this->idLicencia' ";
		
		return $this->db->query($sql)->result();
	}

	public function obtenerLicenciasRegistro()
	{
		$sql=" select idLicencia, id, nombre, rfc, codigoPostal, estado, municipio, 
		colonia, direccion, numero, numeroExterior, localidad, telefono
		from configuracion
		where idLicencia!='$this->idLicencia' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerNombreSucursal($idLicencia)
	{
		$sql=" select nombre
		from configuracion
		where idLicencia='$idLicencia' ";
		
		$licencia	= $this->db->query($sql)->row();
		
		return $licencia!=null?$licencia->nombre:'Sin resultados';
	}
	
	public function obtenerEstilo()
	{
		$sql="select color, logotipo, usuarioTiendas, passwordTiendas,
		id, nombre, idCookie
		from configuracion
		where idLicencia='1' ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerConfiguracionLicencia($idLicencia)
	{
		$sql="select color, logotipo, usuarioTiendas, passwordTiendas,
		id, nombre, idLicencia, numeroVentas, importeDinero
		from configuracion
		where idLicencia='$idLicencia' ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerConfiguracionCookie()
	{
		$sql="select color, idCookie
		from configuracion ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerConfiguracion()
	{
		$sql="select * from configuracion
		where idLicencia='$this->idLicencia'";
		
		$query = $this->db->query($sql);
		
		return ($query->num_rows() > 0)? $query->row_array() : NULL;
	}

	public function guardar($logo)
	{
		$id			= $this->input->post('id');
		$Factor		= $this->input->post('T6');
		
		$data=array
		(
			'admin'				=> $this->input->post('T1'),
			'dolar'				=> $this->input->post('T2'),
			'correo'			=> $this->input->post('T4'),
			'factor'			=> $Factor,
			#'iva'				=> $Factor,
			#'iva2'				=> $this->input->post('txtIva2'),
			#'iva3'				=> $this->input->post('txtIva3'),
			'nombre'			=> $this->input->post('T42'),
			'rfc'				=> $this->input->post('T43'),
			'direccion'			=> $this->input->post('T44'),
			'numero'			=> $this->input->post('numero'),
			'telefono'			=> $this->input->post('T45'),
			'contacto'			=> $this->input->post('T46'),
			'codigoPostal'		=> $this->input->post('T47'),
			'pais'				=> $this->input->post('pais'),
			'estado'			=> $this->input->post('T49'),
			'colonia'			=> $this->input->post('txtColonia'),
			'municipio'			=> $this->input->post('txtMunicipio'),
			'localidad'			=> $this->input->post('txtLocalidad'),
			//'folio_inicio'		=>$this->input->post('folio_inicio'),
			//'folio_final'		=>$this->input->post('folio_final'),
			'identificador'		=> $this->input->post('identificador'),
			'notificaciones'	=> $this->input->post('chkNotificaciones')=='1'?'1':'0',
			
			'variable1'			=> $this->input->post('txtVariableA'),
			'variable2'			=> $this->input->post('txtVariableB'),
			'variable3'			=> $this->input->post('txtVariableC'),
			'variable4'			=> $this->input->post('txtVariableD'),
			
			'ordenProductos'	=> $this->input->post('selectOrdenProductos'),
			
			'impresoraLocal'	=> $this->input->post('chkAgente')=='1'?'1':'0',
		);
		
		if($this->idLicencia==1)
		{
			$data['usuarioTiendas']	= $this->input->post('txtUsuarioTiendas');
			
			$passwordTiendas	= $this->input->post('txtPasswordTiendas');
		
			if(strlen($passwordTiendas)>3)
			{
				$data['passwordTiendas']	= sha1($passwordTiendas);
			}
		}
		
		$this->session->set_userdata('identificador',$this->input->post('identificador'));  
		$this->session->set_userdata('iva',$Factor);  
		$this->session->set_userdata('notificacionesActivas',$this->input->post('chkNotificaciones'));  
		$this->session->set_userdata('ordenProductos',$this->input->post('selectOrdenProductos'));  
		$this->session->set_userdata('impresoraLocal',($this->input->post('chkAgente')=='1'?'1':'0'));  
		
		if($logo!='nada')
		{
			$data['logotipo']=$logo;
		}
		
		$codigoBorrado=$this->input->post('txtCodigoBorrado');
		
		if(strlen($codigoBorrado)>3)
		{
			$data['codigoBorrado']=sha1($codigoBorrado);

			$this->session->set_userdata('codigoBorrado',sha1($codigoBorrado)); 
		}
		
		$codigoEditar=$this->input->post('txtCodigoEditar');
		
		if(strlen($codigoEditar)>3)
		{
			$data['codigoEditar']=sha1($codigoEditar);

			$this->session->set_userdata('codigoEditar',sha1($codigoEditar));
		}
		
		$codigoImportar	= $this->input->post('txtCodigoImportar');
		
		if(strlen($codigoImportar)>3)
		{
			$data['codigoImportar']	= sha1($codigoImportar);

			$this->session->set_userdata('codigoImportar',sha1($codigoImportar));
		}
		
		$data	= procesarArreglo($data);
		$this->db->where('id', $id);
		$this->db->update('configuracion', $data);
		
		$this->registrarBitacora('Guardar configuración','Configuración - Sistema',$this->input->post('T42')); //Registrar bitácora
		
		return(($this->db->affected_rows() == 1) ? $this->db->insert_id():NULL) ;
	}
	
	
	//ROLES Y PERMISOS 
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function registrarRol()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$data=array
		(
			'nombre'		=>$this->input->post('txtNombre'),
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('roles',$data);
		$idRol	=$this->db->insert_id();
		
		$this->registrarBitacora('Registrar rol','Configuración - Roles',$data['nombre']); //Registrar bitácora
		
		$this->registrarBotonesRol($idRol);

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
	
	public function registrarBotonesRol($idRol)
	{
		$indice		=$this->input->post('txtIndice');

		for($i=1;$i<$indice;$i++)
		{
			$activo		=$this->input->post('chkBoton'.$i);
			
			$data=array
			(
				'idRol'		=>$idRol,
				'idBoton' 	=>$this->input->post('txtBoton'.$i),
				'activo' 	=>$activo>=1?1:0,
			);
			
	    	$this->db->insert('rel_rol_boton',$data);
		}
	}
	
	public function editarRol()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idRol	=$this->input->post('txtIdRol');
		
		#----------------------------------------------------------------#
		$data=array
		(
			'nombre'	=> $this->input->post('txtNombre')
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idRol',$idRol);
	    $this->db->update('roles',$data);
		
		$this->registrarBitacora('Editar rol','Configuración - Roles',$data['nombre']); //Registrar bitácora
		
		#----------------------------------------------------------------#
		
		$this->db->where('idRol',$idRol);
	    $this->db->delete('rel_rol_boton');
		#----------------------------------------------------------------#
		
		$this->registrarBotonesRol($idRol);

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
	
	public function comprobarRol($idRol)
	{
		$sql="select count(idRol) as numero
		from usuarios
		where idRol='$idRol'
		and activo='1' ";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerRolDetalle($idRol)
	{
		$sql="select nombre
		from roles
		where idRol='$idRol'";
		
		$rol	= $this->db->query($sql)->row();
		
		return $rol!=null?$rol->nombre:'';
	}
	
	public function borrarRol($idRol)
	{
		if($this->comprobarRol($idRol)>0)
		{
			return "0";	
		}
		
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$this->registrarBitacora('Borrar rol','Configuración - Roles',$this->obtenerRolDetalle($idRol)); //Registrar bitácora
		
		
		/*$this->db->where('idRol',$idRol);
		$this->db->delete('rel_rol_boton');
		
		$this->db->where('idRol',$idRol);
		$this->db->delete('rel_rol_permiso');
		
		$this->db->where('idRol',$idRol);
		$this->db->delete('roles');*/
		$this->db->where('idRol',$idRol);
		$this->db->update('roles',array('activo'=>'0'));

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
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function agregarRol()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$data=array
		(
			'nombre'	=>$this->input->post('nombre'),
			'idLicencia'=>$this->idLicencia
		);
		
		
	    $this->db->insert('roles',$data);
		$idRol		=$this->db->insert_id();
		
		$leer		=$this->input->post('lectura');
		$escribir	=$this->input->post('escritura');
		$todos		=$this->input->post('todos');
		
		$permisos	=$this->obtenerPermisos();
		
		$i=1;
		
		foreach($permisos as $row)
		{
			$data=array
			(
				'idRol'		=>$idRol,
				'idPermiso' =>$row->idPermiso,
				'leer' 		=>$leer[$i],
				'escribir' 	=>$escribir[$i],
				'todos' 	=>$todos[$i]
			);
			
	    	$this->db->insert('rel_rol_permiso',$data);
			$i++;
		}
		
		/*for($i=1;$i<=19;$i++)
		{
			$data=array
			(
				'idRol'		=>$idRol,
				'idPermiso' =>$i,
				'leer' 		=>$leer[$i],
				'escribir' 	=>$escribir[$i] 
			);
			
			
	    	$this->db->insert('rel_rol_permiso',$data);
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
	
	public function editarRolasd()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$idRol	=$this->input->post('idRol');
		
		#----------------------------------------------------------------#
		$data=array
		(
			'nombre'=>$this->input->post('nombreRol')
		);
		
		$this->db->where('idRol',$idRol);
	    $this->db->update('roles',$data);
		
		#----------------------------------------------------------------#
		$sql="delete from rel_rol_permiso
			where idRol='$idRol'";
		
		$this->db->query($sql);
		#----------------------------------------------------------------#
			
		$leer		=$this->input->post('lectura');
		$escribir	=$this->input->post('escritura');
		$todos		=$this->input->post('todos');
		
		$permisos	=$this->obtenerPermisos();
		
		$i=1;
		
		foreach($permisos as $row)
		{
			$data=array
			(
				'idRol'		=>$idRol,
				'idPermiso' =>$row->idPermiso,
				'leer' 		=>$leer[$i],
				'escribir' 	=>$escribir[$i],
				'todos' 	=>$todos[$i]
			);
			
	    	$this->db->insert('rel_rol_permiso',$data);
			$i++;
		}
		
		/*for($i=1;$i<=19;$i++)
		{
			$data=array
			(
				'idRol'		=>$idRol,
				'idPermiso' =>$i,
				'leer' 		=>$leer[$i],
				'escribir' 	=>$escribir[$i] 
			);
			
			
	    	$this->db->insert('rel_rol_permiso',$data);
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

	public function colorAgregar()
	{
		$descripcion=$this->input->post('desc');
		
		$data=array
		(
			'descripcion'=>$descripcion
		);
		
		
	    $this->db->insert('colores',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function colorBorrar($idColor)
	{
	    $this->db->where('idColor',$idColor);
		$this->db->delete('colores');
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarUnidadRegistro($descripcion)
	{
		$sql ="select idUnidad
		from  unidades 
		where activo='1'
		and descripcion='$descripcion'";

		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarUnidad()
	{
		$descripcion	= trim($this->input->post('descripcion'));
		
		if(!$this->comprobarUnidadRegistro($descripcion))
		{
			return array('0',registroDuplicado);
		}
		
		$data=array
		(
			'descripcion'	=>$descripcion,
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('unidades',$data);
		
		$this->registrarBitacora('Registrar unidad','Configuración - Unidades',$data['descripcion']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarUnidad()
	{
		$data=array
		(
			'descripcion'	=>$this->input->post('nombre'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idUnidad',$this->input->post('idUnidad'));
	    $this->db->update('unidades',$data);
		
		$this->registrarBitacora('Editar unidad','Configuración - Unidades',$data['descripcion']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function combrarUnidad($idUnidad)
	{
		$sql="select idUnidad
		from produccion_materiales
		where idUnidad=$idUnidad";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select idUnidad
		from unidades_conversiones
		where idUnidad=$idUnidad";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
	}
	
	public function obtenerDetalleUnidad($idUnidad)
	{
		$sql=" select descripcion
		from unidades
		where idUnidad='$idUnidad' ";
		
		$unidad	= $this->db->query($sql)->row();
		
		return $unidad!=null?$unidad->descripcion:0;
	}
	
	public function unidadBorrar($idUnidad)
	{
		if($this->combrarUnidad($idUnidad)>0)
		{
			return "0";
		}
		
		$this->registrarBitacora('Borrar unidad','Configuración - Unidades',$this->obtenerDetalleUnidad($idUnidad)); //Registrar bitácora
		
	    $this->db->where('idUnidad',$idUnidad);
		$this->db->update('unidades',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarZonaNombre($descripcion)
	{
		$sql="select count(idZona) as numero
		from zonas
		where descripcion='$descripcion'
		and activo='1' ";
		
		return $this->db->query($sql)->row()->numero>0?false:true;
	}
	
	public function registrarZona()
	{
		if(!$this->comprobarZonaNombre($this->input->post('descripcion')))
		{
			return array('0',registroDuplicado);
		}
		
		$data=array
		(
			'descripcion'	=> $this->input->post('descripcion'),
			'idLicencia'	=> $this->idLicencia
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('zonas',$data);
		
		$this->registrarBitacora('Registrar tipo cliente','Configuración - Tipo cliente',$data['descripcion']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarZona()
	{
		$data=array
		(
			'descripcion'	=> $this->input->post('descripcion'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idZona',$this->input->post('idZona'));
	    $this->db->update('zonas',$data);
		
		$this->registrarBitacora('Editar tipo cliente','Configuración - Tipo cliente',$data['descripcion']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarZona($idZona)
	{
		$sql="select count(idZona) as numero
		from clientes
		where idZona='$idZona'";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerDetalleZona($idZona)
	{
		$sql="select descripcion
		from zonas
		where idZona='$idZona' ";
		
		$zona	= $this->db->query($sql)->row();
		
		return $zona!=null?$zona->descripcion:'';
	}
	
	public function borrarZona($idZona)
	{
		if($this->comprobarZona($idZona)>0)
		{
			return "0";	
		}
		
	    $this->db->where('idZona',$idZona);
		#$this->db->delete('zonas');
		$this->db->update('zonas',array('activo'=>'0'));
		
		$this->registrarBitacora('Borrar tipo cliente','Configuración - Tipo cliente',$this->obtenerDetalleZona($idZona)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function comprobarUsuario($idUsuario)
	{
		$sql="select idUsuario
		from cotizaciones
		where idUsuario='$idUsuario'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select idUsuario
		from clientes
		where idUsuario='$idUsuario'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		return 0;
	}
	
	public function obtenerUsuarioDetalle($idUsuario)
	{
		$sql="select usuario, concat(nombre,'',apellidoPaterno,'',apellidoMaterno) as nombre, nombre as nombreUsuario
		from usuarios
		where idUsuario='$idUsuario' ";
		
		$usuario=$this->db->query($sql)->row();
		
		return $usuario!=null?array($usuario->usuario,$usuario->nombre):array('Sin detalles','');
	}
	
	public function borrarUsuario($idUsuario)
	{
		/*if($this->comprobarUsuario($idUsuario)>0)
		{
			return "0";
		}*/
		
		$this->db->where('idUsuario', $idUsuario);
		#$this->db->delete('usuarios');
		$this->db->update('usuarios',array('activo'=>'0'));
		
		$usuario	= $this->obtenerUsuarioDetalle($idUsuario);
		$this->registrarBitacora('Desactivar usuario','Configuración - Usuarios',$usuario[0].', '.$usuario[0]); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function reactivarUsuario($idUsuario)
	{
		/*if($this->comprobarUsuario($idUsuario)>0)
		{
			return "0";
		}*/
		
		$this->db->where('idUsuario', $idUsuario);
		#$this->db->delete('usuarios');
		$this->db->update('usuarios',array('activo'=>'1'));
		
		$usuario	= $this->obtenerUsuarioDetalle($idUsuario);
		$this->registrarBitacora('Reactivar usuario','Configuración - Usuarios',$usuario[0].', '.$usuario[0]); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}

	public function comprobarUsuarioRegistrado($usuario)
	{
		$sql=" select usuario
		from usuarios
		where usuario='$usuario'";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function comprobarSucursalesUsuario($idUsuario,$idLicencia)
	{
		$sql="select * from usuarios_licencias
		where idUsuario='$idUsuario'
		and idLicencia='$idLicencia' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarUsuario()
	{
		if($this->comprobarUsuarioRegistrado($this->input->post('txtUsuario'))>0) return array('0',registroDuplicado);;
		#if(!comprobarCorreoGmail($this->input->post('correo'))>0) return '2';
		
		$this->db->trans_start();
		
		$data = array
		(
			"nombre" 			=> $this->input->post('txtNombre'),
			"apellidoPaterno"	=> $this->input->post('txtPaterno'),
			"apellidoMaterno"	=> $this->input->post('txtMaterno'),
			"usuario" 			=> $this->input->post('txtUsuario'),
			"password" 			=> sha1($this->input->post('txtPassword')),
			"idRol" 			=> $this->input->post('selectRol'),
			"fechaCreacion" 	=> $this->_fecha_actual,
			"idUsuarioCreacion" => $this->_user_id,
			"correo" 			=> $this->input->post('txtCorreo'),
			#"idTienda" 			=> $this->input->post('idTienda'),
			'idLicencia'		=> $this->idLicencia,
			"firma" 			=> $this->input->post('txtFirma'),
			"vendedor" 			=> $this->input->post('txtVendedor'),
			"ipad" 				=> $this->input->post('chkIpd')=='1'?'1':'0',
		);
		
		if(strlen($this->input->post('txtClaveDescuento'))>5)
		{
			$data['claveDescuento']	= sha1($this->input->post('txtClaveDescuento'));
		}
		
		if(strlen($this->input->post('txtClaveCancelacion'))>5)
		{
			$data['claveCancelacion']	= sha1($this->input->post('txtClaveCancelacion'));
		}
		
		$data	= procesarArreglo($data);
		$this->db->insert('usuarios',$data);
		$idUsuario=$this->db->insert_id();
		
		$this->registrarBitacora('Registrar usuario','Configuración - Usuarios',$data['usuario'].', '.$data['nombre'].' '.$data['apellidoPaterno'].' '.$data['apellidoMaterno']); //Registrar bitácora
		
		$this->registrarSucursalesUsuario($idUsuario);

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
	
	public function registrarSucursalesUsuario($idUsuario)
	{
		$this->db->where('idUsuario',$idUsuario);
		$this->db->delete('usuarios_licencias');
		
		$licencias	= $this->obtenerLicenciasActivas();
		
		foreach($licencias as $row)
		{
			if($this->input->post('chkLicencia'.$row->idLicencia)=='1')
			{
				$data=array
				(
					'idLicencia'	=> $row->idLicencia,
					'idUsuario'		=> $idUsuario,
				);
				
				$this->db->insert('usuarios_licencias',$data);
			}
		}
	}
	
	public function editarUsuario()
	{
		$this->db->trans_start();
		
		$idUsuario		= $this->input->post('txtIdUsuario');
		
		$data = array
		(
			"nombre" 			=> $this->input->post('txtNombre'),
			"apellidoPaterno"	=> $this->input->post('txtPaterno'),
			"apellidoMaterno"	=> $this->input->post('txtMaterno'),
			"usuario" 			=> $this->input->post('txtUsuario'),
			"idRol" 			=> $this->input->post('selectRol'),
			"fechaEdicion" 		=> $this->_fecha_actual,
			"idUsuarioEdicion" 	=> $this->_user_id,
			"correo" 			=> $this->input->post('txtCorreo'),
			"firma" 			=> $this->input->post('txtFirma'),
			"vendedor" 			=> $this->input->post('txtVendedor'),
			"ipad" 				=> $this->input->post('chkIpd')=='1'?'1':'0',
		);
		
		if(strlen($this->input->post('txtClaveDescuento'))>5)
		{
			$data['claveDescuento']	= sha1($this->input->post('txtClaveDescuento'));
		}
		
		if(strlen($this->input->post('txtPassword'))>5)
		{
			$data['password']=sha1($this->input->post('txtPassword'));
		}
		
		if(strlen($this->input->post('txtClaveCancelacion'))>5)
		{
			$data['claveCancelacion']	= sha1($this->input->post('txtClaveCancelacion'));

			if($idUsuario==$this->_user_id)
			{
				$this->session->set_userdata('claveCancelacion',sha1($this->input->post('txtClaveCancelacion')));
			}
			
		}
	
		
		$data	= procesarArreglo($data);
		$this->db->where('idUsuario',$idUsuario);
		$this->db->update('usuarios',$data);
		
		$this->registrarBitacora('Editar usuario','Configuración - Usuarios',$data['usuario'].', '.$data['nombre'].' '.$data['apellidoPaterno'].' '.$data['apellidoMaterno']); //Registrar bitácora
		
		
		$this->registrarSucursalesUsuario($idUsuario);
		
		#return $this->db->affected_rows()>=1?"1":"0"; 
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
			
			return "1";
		}
	}

	public function accesoUsuario()
	{
		return "1";
		
		/*$data=array
		(
			'fechaAcceso'	=> $this->_fecha_actual
		);
		
		$this->db->where('idUsuario',$this->_user_id);
		$this->db->update('usuarios',$data);
		
		if($this->db->affected_rows()==1)
		{
			return "1";
		}
		else
		{
			return "0";
		}*/
	}
	
	public function obtenerUsuariosConectados()
	{
		$sql=" select concat(nombre,' ', apellidoPaterno, ' ', apellidoMaterno) as usuario
		from usuarios
		where activo=1
		and idLicencia='$this->idLicencia'
		and idUsuario!='$this->_user_id'
		AND fechaAcceso between  (select date_sub('$this->_fecha_actual', interval 15 minute)) and '$this->_fecha_actual' ";
		
		return $this->db->query($sql)->result();
	}

	public function getusuarios()
	{    
		$SQL="SELECT * FROM  ".$this->_table['usuarios']." 
		where role != 0  
		and idLicencia='$this->idLicencia'";
		
		$query=$this->db->query($SQL);
		return ($query->num_rows()> 0)? $query->result_array() : NULL;
	}
	
	#------------------------------------------------------------------------------------------#
	#--------------------------------ADMINISTRACION DE ROLES-----------------------------------#
	#------------------------------------------------------------------------------------------#
	
	public function contarUsuarios($criterio,$idRol=0)
	{    
		$sql=" select count(a.idUsuario) as numero
		from usuarios as a
		where a.idLicencia>0
		and ( concat(a.nombre,' ',a.apellidoPaterno,' ', a.apellidoMaterno) like '%$criterio%'  or a.usuario like '$criterio%') ";
		
		$sql.=$idRol>0?" and a.idRol='$idRol' ":'';
		#and a.idLicencia='$this->idLicencia'
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerUsuarios($numero,$limite,$criterio,$idRol=0)
	{    
		$sql="select a.*, b.nombre as rol
		from usuarios as a
		inner join roles as b
		on b.idRol=a.idRol
		where a.idLicencia>0
		and ( concat(a.nombre,' ',a.apellidoPaterno,' ', a.apellidoMaterno) like '%$criterio%'  or a.usuario like '$criterio%')  ";
		
		#and a.idLicencia='$this->idLicencia'
		
		$sql.=$idRol>0?" and a.idRol='$idRol' ":'';
		
		$sql.=" order by a.activo desc, a.idUsuario asc ";
		$sql.=" limit $limite, $numero";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerListaUsuarios($checador=0)
	{
		$sql="select idUsuario, concat(nombre,' ',apellidoPaterno,' ',apellidoMaterno) as nombre, correo, firma
		from usuarios 
		where idLicencia='$this->idLicencia'";
		
		$sql.=$checador==1?" and idRol!=3 ":'';

		$sql.=" order by nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerUsuario($idUsuario)
	{    
		$sql="select a.*, b.nombre as rol
		from usuarios as a
		inner join roles as b
		on b.idRol=a.idRol
		where a.idUsuario='$idUsuario' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerUsuarioDescuento($idUsuario)
	{    
		$sql="select claveDescuento
		from usuarios
		where idUsuario='$idUsuario' ";
		
		$usuario	= $this->db->query($sql)->row();
		
		return $usuario!=null?$usuario->claveDescuento:'';
	}
	
	public function obtenerRoles()
	{    
		$sql="select a.* from roles as a
		where a.activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRol($idRol)
	{    
		$sql="select a.*
		from roles as a
		where idRol='$idRol' ";
		
		$query=$this->db->query($sql);
		
		return ($query->num_rows()> 0)? $query->row() : NULL;
	}
	
	public function obtenerPermisosId($idPermiso,$idRol)
	{
		$sql="select * from rel_rol_permiso
		where idRol='".$idRol."' 
		and idPermiso='".$idPermiso."'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerPermisoActivo($idRol)
	{
		$sql="select a.*, b.url 
		from rel_rol_permiso as a
		inner join permisos as b
		on a.idPermiso=b.idPermiso
		where a.idRol='".$idRol."'
		and (a.leer=1
		or a.escribir=1 )
		order by a.idPermiso asc
		limit 1 ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerPermisosRol($idRol)
	{    
		$sql="select a.*, b.*
		from permisos as a
		inner join rel_rol_permiso as b
		on (a.idPermiso=b.idPermiso)
		where b.idRol='$idRol' 
		order by a.idPermiso desc ";
		
		$query=$this->db->query($sql);
		
		#echo $sql;
		
		return ($query->num_rows()> 0)? $query->result() : NULL;
	}
	
	public function obtenerPermisosRolAcceso($idRol)
	{    
		$sql=" select a.*, b.nombre,
		c.activo
		from permisos as a
		inner join permisos_botones as b
		on a.idPermiso=b.idPermiso
		
		inner join rel_rol_boton as c
		on c.idBoton=b.idBoton
		
		where c.idRol='$idRol' 
		and a.activo='1'
		and (b.nombre='Ver'
		or b.nombre='Cajero')
		order by a.orden asc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerRolPermisos($idRol)
	{
		$sql=" select a.*, b.descripcion as permiso,
		c.activo
		from permisos_botones as a
		inner join permisos as b
		on a.idPermiso=b.idPermiso
		inner join rel_rol_boton as c
		where c.idBoton=a.idBoton
		and c.idRol='$idRol'
		and (a.nombre='Ver'
		or a.nombre='Cajero' ) ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerRolPermisosPasado($idRol)
	{
		$sql=" select a.*, b.*
		from permisos as a
		inner join rel_rol_permiso as b
		on (a.idPermiso=b.idPermiso)
		where b.idRol='$idRol' ";
		
		$permisos=array();
		
		$i=1;

		foreach($this->db->query($sql)->result() as $row)
		{
			$permisos['leer'][$row->idPermiso]		=$row->leer;
			$permisos['escribir'][$row->idPermiso]	=$row->escribir;
		}
		
		return $permisos;
	}
	
	public function obtenerPermisos()
	{    
		$sql="select * from permisos
		order by idPermiso asc";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPermisosOrdenado()
	{    
		$sql="select * from permisos
		order by orden asc";
		
		return $this->db->query($sql)->result();
	}
	
	//PERMISOS POR BOTONES
	public function obtenerPermisosBoton($idPermiso,$idRol)
	{
		$sql=" select a.idBoton, a.activo
		from rel_rol_boton as a
		inner join permisos_botones as b
		on a.idBoton=b.idBoton
		where a.idRol='$idRol' 
		and b.idPermiso='$idPermiso' ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerRolBoton($idBoton,$idRol)
	{
		$sql=" select activo
		from rel_rol_boton 
		where idRol='$idRol' 
		and idBoton='$idBoton' ";

		return $this->db->query($sql)->row()->activo;
	}
	
	public function obtenerPermisosBotones($idPermiso)
	{
		$sql="select * from permisos_botones
		where idPermiso='$idPermiso' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPermisosBotonesRol($idRol)
	{
		$sql=" select a.idBoton, a.activo,
		b.nombre
		from rel_rol_boton as a
		inner join permisos_botones as b
		on a.idBoton=b.idBoton
		where a.idRol='$idRol' ";

		return $this->db->query($sql)->result();
	}

	public function obtenerColores()
	{    
		$sql="select * from colores ";
		$query=$this->db->query($sql);
		
		return ($query->num_rows()> 0)? $query->result_array() : NULL;
	}
	
	public function obtenerUnidades()
	{    
		$sql="select * from unidades 
		order by descripcion asc";
		
		$query=$this->db->query($sql);
		
		return ($query->num_rows()> 0)? $query->result_array() : NULL;
	}
	
	
	public function seleccionarUnidades()
	{    
		$sql="select * from unidades 
		where activo='1'
		order by idUnidad asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerZonas()
	{    
		$sql=" select a.*,
		(select count(b.idCliente) from clientes as b where b.idZona=a.idZona and b.activo='1') as numeroClientes
		from zonas as a
		where a.activo='1' ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerZona($idZona)
	{    
		$sql="select * from zonas 
		where idZona='$idZona'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerCajas()
	{    
		$sql="select * from cajas ";
		$query=$this->db->query($sql);
		
		return ($query->num_rows()> 0)? $query->result_array() : NULL;
	}

	public function getusuarios_por($id)
	{    
		$SQL="SELECT * FROM  ".$this->_table['usuarios']." 
		where role != 0 and 
		id =$id  
		and idLicencia='$this->idLicencia'";
		
		$query=$this->db->query($SQL);
		
		return ($query->num_rows()> 0)? $query->row_array() : NULL;
	
	}
	
	public function borrauser($id)
	{
		$borra = $this->db->where('id', $id);
		$borra = $this->db->delete('usuarios');
		
		return $borra;
	}

	public function editauser($id)
	{
		$nombre=trim($this->input->post('T1'));
		$paterno=trim($this->input->post('paterno'));
		$materno=trim($this->input->post('materno'));
		
		$data = array
		(
			"name" 				=>$nombre,
			"paterno"			=>$paterno,
			"materno"			=>$materno,
			"password"		 	=> sha1($this->input->post('T3')),
			"role"			 	=> $this->input->post('T6'),
			"modify_fech"		=> $this->_fecha_actual,
			"correo" 			=> $this->input->post('T5'),
			"modify_by" 		=> $this->session->userdata('id')
		);
		
		
		$str=$this->db->where('id',$id);
		$str = $this->db->update($this->_table['usuarios'], $data);
		
		return $str;
	}

	public function guardarlinea()
	{
		$data = array
		(
			"linea" =>$this->input->post('linea'),
			"fecha"=>date('Y-m-d'),
		);
		
		
		$Regresa=$this->db->insert('lineas',$data);
		
		return ($Regresa);//$this->db->affected_rows() == 1)? $this->db->insert_id() : NULL ;
	}
	
	public function verlineas()
	{
		$SQL="SELECT * FROM  lineas ";
		$query=$this->db->query($SQL);
		return ($query->num_rows()> 0)? $query->result_array() : NULL;
	}

    public function borralinea($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('lineas');

        return ($this->db->affected_rows() == 1)? TRUE : FALSE;
    }

	public function porlinea($id)
	{
		$SQL="SELECT * FROM  lineas where id =".$id;
		$query=$this->db->query($SQL);
		return ($query->num_rows()> 0)? $query->result_array() : NULL;
 	}

  	public function updatelinea($id)
    {
        $data = array
		(
			'linea' => $this->input->post('linea'),
			'fecha' => $this->_fecha_actual
		);

        $this->db->where('id', $id);
        $this->db->update('lineas', $data);

        return ($this->db->affected_rows() == 1)? TRUE : FALSE;
    }

	public function obtenerLicencias($idProducto=0)
	{    
		$sql=" select a.idLicencia, b.nombre
		from licencias  as a
		inner join configuracion as b
		on a.idLicencia=b.idLicencia
		where a.idLicencia>0 ";
		
		$sql.= $idProducto>0?" and (select count(c.idInventario) from productos_inventarios as c where c.idLicencia=a.idLicencia and c.idProducto='$idProducto') = 0 ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerLicenciasActivas()
	{    
		$sql=" select a.idLicencia, a.usuario as nombre, 
		b.numeroVentas, b.nombre as sucursal, importeDinero
		from licencias  as a
		inner join configuracion as b
		on a.idLicencia=b.idLicencia
		where a.activa='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function crearDirectorioFacturacion($idLicencia)
	{
		$ruta='media/fel/'.$idLicencia.'_facturacion';
		
		if(!file_exists($ruta))
		{
			$crear=mkdir($ruta,0777);
			
			if($crear)
			{
				chmod($ruta,0777);
			}
		}
	}
	
	public function agregarLicencia()
	{
		$this->db->trans_start(); #Se Inicia una transaccion porque se inserta y actualiza
		
		$data=array
		(
			'usuario'		=>$this->input->post('empresa'),
			'fechaInicio'	=>$this->input->post('fechaInicio'),
			'fechaFin'		=>$this->input->post('fechaFin'),
		);
		
		
		$this->db->insert('licencias', $data);
		
		$idLicencia=$this->db->insert_id();
		
		#------------------------------------------------------------------------------------------------#
		$this->crearDirectorioFacturacion($idLicencia);
		#------------------------------------------------------------------------------------------------#
		
		$data=array
		(
			'admin'				=>'',
			'dolar'				=>'0',
			'correo'			=>'',
			'factor'			=>'',
			'nombre'			=>$this->input->post('empresa'),
			'RFC'				=>'',
			'direccion'			=>'',
			'numero'			=>'',
			'telefono'			=>'',
			'contacto'			=>'',
			'codigoPostal'		=>'',
			'pais'				=>'México',
			'estado'			=>'',
			'folioInicio'		=>'0',
			'folioFinal'		=>'0',
			'idLicencia'		=>$idLicencia,
			'fechaCosto'		=>date('Y-m-d')
		);
		
		
		$this->db->insert('configuracion', $data);
		
		#---------------------------------------------------------------#
		$data=array
		(
			'nombre'			=>'Administrador',
			'idLicencia'		=>$idLicencia,
			'activo'			=>'0'
		);
		
		
		$this->db->insert('roles', $data);
		
		$idRolAdmin=$this->db->insert_id();
		
		for($i=1;$i<=19;$i++)
		{
			$data=array
			(
				'idRol'				=>$idRolAdmin,
				'idPermiso'			=>$i,
				'leer'				=>1,
				'escribir'			=>1
			);
			
			
			$this->db->insert('rel_rol_permiso', $data);
		}
		
		#---------------------------------------------------------------#
		$data=array
		(
			'nombre'			=>'Tienda',
			'idLicencia'		=>$idLicencia,
			'activo'			=>'0'
		);
		
		
		$this->db->insert('roles', $data);
		
		$idRol=$this->db->insert_id();
		
		for($i=1;$i<=19;$i++)
		{
			if($i==18)
			{
				$data=array
				(
					'idRol'				=>$idRol,
					'idPermiso'			=>$i,
					'leer'				=>1,
					'escribir'			=>1
				);
			}
			else
			{
				$data=array
				(
					'idRol'				=>$idRol,
					'idPermiso'			=>$i,
					'leer'				=>0,
					'escribir'			=>0
				);
			}
			
			
			$this->db->insert('rel_rol_permiso', $data);
		}
		
		#---------------------------------------------------------------#
		
		$data=array
		(
			'name'			=>$this->input->post('empresa'),
			'paterno'		=>$this->input->post('empresa'),
			'materno'		=>$this->input->post('empresa'),
			'createDate'	=>$this->_fecha_actual,
			'block'			=>'0',
			'username'		=>$this->input->post('empresa'),
			'password'		=>sha1($this->input->post('empresa')),
			'role'			=>$idRolAdmin,
			'idLicencia'	=>$idLicencia,
			'superAdmin'	=>'1'
		);
		
	
		$this->db->insert('usuarios', $data);
		
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
	
	public function obtenerFel()
	{
		$sql="select * from configuracion
		where idLicencia='$this->idLicencia'";
		
		return $this->db->query($sql)->row();
	}
	
	public function actualizarFEL()
	{
		#if(strlen($this->input->post('passwordFEL'))>7)
		#{
			
		#Facturación
		$this->session->set_userdata('serie',$this->input->post('txtSerie')); 
		$this->session->set_userdata('folioInicio',$this->input->post('txtFolioInicial')); 
		$this->session->set_userdata('folioFinal',$this->input->post('txtFolioFinal'));  
		
		$data=array
		(
			'passwordFEL'			=>$this->input->post('passwordFEL'),
			'usuarioFEL'			=>$this->input->post('usuarioFEL'),
			'folioInicio'			=>$this->input->post('txtFolioInicial'),
			'folioFinal'			=>$this->input->post('txtFolioFinal'),
			'passwordLlave'			=>$this->input->post('passwordLlave'),
			'serie'					=>$this->input->post('txtSerie'),
			'numeroCertificado'		=>$this->input->post('numeroCertificado'),
			'numeroCuenta'			=>$this->input->post('txtNumeroCuenta'),
			'regimenFiscal'			=>$this->input->post('txtRegimenFiscal'),
		);
		
		if(strlen($_FILES['fileCertificado']['name'])>3)
		{
			$uploaddir  = "media/fel/".$this->idLicencia.'_facturacion/';
			$uploadfile = $uploaddir . basename($_FILES['fileCertificado']['name']);
			
			move_uploaded_file($_FILES['fileCertificado']['tmp_name'], $uploadfile);
			
			$data['certificado']=$_FILES['fileCertificado']['name'];
		}
		
		if(strlen($_FILES['fileLlave']['name'])>3)
		{
			$uploaddir  = "media/fel/".$this->idLicencia.'_facturacion/';
			$uploadfile = $uploaddir . basename($_FILES['fileLlave']['name']);
			
			move_uploaded_file($_FILES['fileLlave']['tmp_name'], $uploadfile);
			
			$data['llave']=$_FILES['fileLlave']['name'];
		}
		
		#}
		/*else
		{
			$data=array
			(
				'usuarioFEL'	=>$this->input->post('usuarioFEL'),
			);
		}*/
		
		$this->db->where('idLicencia',$this->idLicencia);
		$this->db->update('configuracion',$data);
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function obtenerImpuestos($iva=0)
	{
		$sql="select a.* 
		from configuracion_impuestos as a
		order by a.idImpuesto asc
		limit 1";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCatalogoImpuestos()
	{
		$sql=" select a.idImpuesto, a.tasa, a.tipo, 
		a.exento, b.nombre, b.clave
		from configuracion_impuestos as a
		inner join fac_impuestos as b
		on a.idCatalogoImpuesto=b.idCatalogoImpuesto
		where a.idLicencia>0
		order by a.idImpuesto asc
		limit 2";
		
		#where a.idLicencia='$this->idLicencia'
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerImpuestoTasa($tasa=0)
	{
		$sql=" select * from configuracion_impuestos
		where tasa='$tasa' ";
		
		$impuesto	= $this->db->query($sql)->row();
		
		return $impuesto!=null?$impuesto->idImpuesto:1;
	}
	
	public function actualizarImpuestos()
	{
		$impuestos=$this->obtenerImpuestos();
		
		foreach($impuestos as $row)
		{
			$data=array
			(
				'tasa'				=> $this->input->post('txtImpuesto'.$row->idImpuesto),
			);
				
			$this->db->where('idImpuesto',$row->idImpuesto);
			$this->db->update('configuracion_impuestos',$data);
			
			#$this->registrarBitacora('Actualizar vigilancia','Configuración - Vigilancia',$this->input->post('url')); //Registrar bitácora
		}
		
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function autoCompletadoClientes()
	{
		$empresa = $_POST['descripcion'];	
		
		if(strlen($empresa) >0) 
		{
			$sql = "select a.idCliente, a.empresa,
			b.nombre as contacto
			from clientes  as a
			inner join clientes_contactos as b
			on 	a.idCliente=b.idCliente
			where (a.empresa like '%$empresa%' or b.nombre like '%$empresa%' ) 
			and a.activo='1'  
			and a.idLicencia='$this->idLicencia'
			group by a.idCliente
			order by b.fechaRegistro asc
			limit 20";
			
			return $this->db->query($sql)->result();
		}
	}
	
	public function autoCompletadoProductos()
	{
		$descripcion = $_POST['descripcion'];	
		
		if(strlen($descripcion) >0) 
		{
			$sql = "select idProducto, nombre 
			from productos 
			where nombre like '%$descripcion%' 
			and activo='1' 
			and idLicencia='$this->idLicencia' 
			and servicio='0' 
			and materiaPrima='0'
			limit 20";
			
			return $this->db->query($sql)->result();
		}
	}
	
	public function autoCompletadoProductosAlmacen()
	{
		$descripcion = $_POST['descripcion'];	
		
		if(strlen($descripcion) >0) 
		{
			$sql = "select idProducto, nombre 
			from productos 
			where nombre like '%$descripcion%' 
			and activo='1' 
			and idLicencia='$this->idLicencia' 
			and servicio='0' 
			and reventa='0'
			limit 20";
			
			return $this->db->query($sql)->result();
		}
	}
	
	public function autoCompletadoServicios()
	{
		$descripcion = $_POST['descripcion'];	
		
		if(strlen($descripcion) >0) 
		{
			$sql = "select idProducto, nombre 
			from productos 
			where nombre like '%$descripcion%' 
			and activo='1' 
			and idLicencia='$this->idLicencia' 
			and servicio='1' 
			limit 20";
			
			return $this->db->query($sql)->result();
		}
	}
	
	public function autoCompletadoProveedores()
	{
		$empresa = $_POST['descripcion'];	
		
		if(strlen($empresa) >0) 
		{
			$sql = "select idProveedor, empresa 
			from proveedores 
			where empresa like '%$empresa%'  
			and activo='1'
			and idLicencia='$this->idLicencia'
			limit 20";

			return $this->db->query($sql)->result();
		}
	}
	
	public function autoCompletadoMateriales()
	{
		$nombre = $_POST['descripcion'];	
		
		if(strlen($nombre) >0) 
		{
			$sql = "select a.idMaterial, a.nombre,
			b.descripcion as unidad, b.idUnidad 
			from produccion_materiales as a
			inner join unidades as b
			on a.idUnidad=b.idUnidad
			where a.nombre like '%$nombre%'  
			and a.idLicencia='$this->idLicencia'
			and a.tipoMaterial='1'
			limit 20";
			
			return $this->db->query($sql)->result();
		}
	}
	
	
	
	public function autoCompletadoProduccion()
	{
		$nombre = $_POST['descripcion'];	
		
		if(strlen($nombre) >0) 
		{
			$sql = "select idProducto, nombre 
			from produccion_productos 
			where nombre like '%$nombre%'  
			and idLicencia='$this->idLicencia'
			and activo='1'
			and reventa='0' 
			and servicio='0' 
			limit 20";
			
			return $this->db->query($sql)->result();
		}
	}
	
	public function autoCompletadoCategorias()
	{
		$nombre = $_POST['descripcion'];	
		
		if(strlen($nombre) >0) 
		{
			$sql = "select idCategoria, nombre 
			from categorias 
			where nombre like '%$nombre%'  
			and idLicencia='$this->idLicencia'
			and activo='1'
			limit 20";
			
			return $this->db->query($sql)->result();
		}
	}
	
	public function autoCompletadoUsuarios1()
	{
		$nombre = $_POST['descripcion'];	
		
		if(strlen($nombre) >0) 
		{
			$sql = "select id, name, paterno 
			from usuarios 
			where name like '%$nombre%'  
			and idLicencia='$this->idLicencia'
			and block='0'
			and role!='0'
			limit 20";
			
			return $this->db->query($sql)->result();
		}
	}
	
	public function autoCompletadoIdentificador()
	{
		$nombre = $_POST['descripcion'];	
		
		if(strlen($nombre) >0) 
		{
			$sql = "select *
			from zonas 
			where descripcion like '%$nombre%'  
			and idLicencia='$this->idLicencia'
			limit 20";
			
			return $this->db->query($sql)->result();
		}
	}
	
	public function autoCompletadoCotizacion()
	{
		$serie 		= $_POST['descripcion'];	
		$idCliente 	= $_POST['idCliente'];	
		
		if(strlen($serie) >0) 
		{
			$sql = "select serie, idCotizacion
			from cotizaciones 
			where serie like '%$serie%'  
			and idLicencia='$this->idLicencia'
			and idCliente='$idCliente'
			and estatus='0'
			limit 20";
			
			return $this->db->query($sql)->result();
		}
	}
	
	public function autoCompletadoVentas()
	{
		$orden 		= $_POST['descripcion'];	
		$idCliente 	= $_POST['idCliente'];	
		
		if(strlen($orden) >0) 
		{
			$query = "select ordenCompra, idCotizacion
			from cotizaciones 
			where ordenCompra like '%$orden%'  
			and idLicencia='$this->idLicencia'
			and idCliente='$idCliente'
			and estatus='1'
			limit 20";
			
			$query = $this->db->query($query);
			
			return $query->result();
		}
	}

	public function obtenerStatusTipo($tipo)
	{
		$sql="select * from seguimiento_status
		where tipo='$tipo'";
		
		$query=$this->db->query($sql);
		
		return $query->result();
	}

	public function obtenerResponsables($permiso=0,$idUsuario=0,$promotor=0)
	{
		$sql="select idUsuario as idResponsable, concat(nombre,' ',apellidoPaterno,' ',apellidoMaterno) as nombre,
		correo
		from usuarios
		where idUsuario>0 
		and idLicencia='$this->idLicencia'
		and idRol!=3 
		and activo='1' ";
		
		$sql.=$idUsuario>0?" and idUsuario='$idUsuario' ":'';
		
		if($promotor==1)
		{
			$sql.=sistemaActivo=='IEXE'?" and promotor='1' ":'';
		}
		
		
		if($permiso==0 and $idUsuario==0)
		{
			$sql.=$this->idRol!=1?" and idUsuario='$this->_user_id' ":'';
		}
		
		$sql.=" order by nombre asc ";
		#echo $sql;
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPromotoresRegistro($todos=0)
	{
		$sql=" select idUsuario, concat(nombre,' ',apellidoPaterno,' ',apellidoMaterno) as nombre,
		correo ".(sistemaActivo=='IEXE'?", src":"")."
		from usuarios
		where idUsuario>0 
		and idLicencia='$this->idLicencia'
		
		and activo='1' ";
		
		$sql.=sistemaActivo=='IEXE'?" and promotor='1'  ":'';
		
		$sql.=$todos==0?" and idUsuario='$this->_user_id' ":'';

		$sql.=" order by nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPromotoresExtensiones($todos=0)
	{
		$sql=" select idUsuario, concat(nombre,' ',apellidoPaterno,' ',apellidoMaterno) as nombre,
		correo ".(sistemaActivo=='IEXE'?", src":"")."
		from usuarios
		where idUsuario>0 
		and idLicencia='$this->idLicencia'
		
		and activo='1' ";
		
		$sql.=sistemaActivo=='IEXE'?" and src!='' and promotor='1'  ":'';
		$sql.=$todos==0?" and idUsuario='$this->_user_id' ":'';

		$sql.=" order by nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPromotorExtension($src)
	{
		$sql=" select idUsuario, concat(nombre,' ',apellidoPaterno,' ',apellidoMaterno) as nombre
		from usuarios ";
		
		$sql.=sistemaActivo=='IEXE'?" where src='$src' ":'';

		$promotor	= $this->db->query($sql)->row();
		
		return $promotor!=null?$promotor->nombre:$src;
	}
	
	public function obtenerPeriodosProduccion()
	{
		$sql="select * from produccion_periodos";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPeriodo($idPeriodo)
	{
		$sql="select * from produccion_periodos
		where idPeriodo='$idPeriodo'";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerFechasDias($dias)
	{
		$sql="select date_add('".date('Y-m-d')."', interval ".$dias." day) as fecha";
		
		#echo $sql;
		return $this->db->query($sql)->row()->fecha;
	}
	
	public function obtenerServiciosVencidos()
	{
		$fecha1	=$this->obtenerFechasDias(1);
		$fecha3	=$this->obtenerFechasDias(3);
		$fecha8	=$this->obtenerFechasDias(8);
		
		$sql="select a.*, b.nombre,
		c.folio, d.empresa
		from cotiza_productos as a
		inner join productos as b
		on a.idProduct=b.idProducto
		inner join cotizaciones as c
		on a.idCotizacion=c.idCotizacion
		inner join clientes as d
		on c.idCliente=d.idCliente
		where a.notificar=1 
		and c.estatus=1
		and c.idLicencia='$this->idLicencia'
		and (date(a.fechaVencimiento)='$fecha3'
		or date(a.fechaVencimiento)='$fecha8'
		or date(a.fechaVencimiento)='$fecha1')";
		
		#echo $sql;
		
		return $this->db->query($sql)->result();
	}
	
	//AUTOCOMPLETADOS ACTUALIZADOS	
	public function obtenerClientes($criterio,$contactos=1,$permiso,$catalogos=0,$ingresos=0,$prospectos='0')
	{
		$criterio	= trim($criterio);
		
		$sql=" select a.idCliente, precio, concat('#',a.alias,', ',a.empresa) as value,
		(select c.idSucursal from clientes_sucursales as c where c.idCliente=a.idCliente and c.idSucursal!='$this->idLicencia' limit 1) as idSucursal ";
		
		#(select c.idSucursal from clientes_sucursales as c where c.idCliente=a.idCliente and c.idLicenciaTraspaso='$this->idLicencia' limit 1) as idSucursal 
		
		$sql.=$catalogos==1?"
		, (select c.idDepartamento from catalogos_ingresos as c where c.idCliente=a.idCliente order by c.idIngreso desc limit 1) as idDepartamento
		, (select c.idNombre from catalogos_ingresos as c where c.idCliente=a.idCliente order by c.idIngreso desc limit 1) as idNombre
		, (select c.idProducto from catalogos_ingresos as c where c.idCliente=a.idCliente order by c.idIngreso desc limit 1) as idProducto
		, (select c.idForma from catalogos_ingresos as c where c.idCliente=a.idCliente order by c.idIngreso desc limit 1) as idForma
		, (select c.idCuenta from catalogos_ingresos as c where c.idCliente=a.idCliente order by c.idIngreso desc limit 1) as idCuenta
		, (select c.idGasto from catalogos_ingresos as c where c.idCliente=a.idCliente order by c.idIngreso desc limit 1) as idGasto ":'';
		
		$sql.=" from clientes as a
		inner join zonas as b
		on a.idZona=b.idZona
		
		where (a. empresa like '%$criterio%'  or a.alias like '$criterio%'   ";
		
		
		
		$sql.= ")
		and a.activo='1' ";
        
        #or a.telefono like '%$criterio%' or a.movil like '%$criterio%'  or a.email like '%$criterio%'

		if($prospectos=='1')
		{
			$sql.=$permiso==0?" and a.idPromotor='$this->_user_id' ":'';
			
			
			$sql.=" and (select f.preinscrito from clientes_academicos as f where f.idCliente=a.idCliente) = '0' ";
			
			$sql.=" group by a.idCliente
			order by d.fechaSeguimiento desc, d.horaInicial desc, a.nombre asc, a.empresa asc
			limit 20 ";
		}
		else
		{
			$sql.=$permiso==0?" and a.idUsuario='$this->_user_id' ":'';
			
			$sql.=" group by a.idCliente
			order by a.nombre asc, a.empresa asc
			limit 20 ";
		}
		
		#echo $sql;
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerClientesActivos($criterio,$permiso)
	{
		$criterio	= trim($criterio);
		
		if(sistemaActivo=='IEXE')
		{
			$sql=" select a.idCliente, 
			concat(b.descripcion, ': ', a.nombre, ' ', a.paterno, ' ', a.materno,' (Activo), Cliente: ', a.empresa) as value ";
		}
		else
		{
			$sql=" select a.idCliente, precio,
			a.empresa as value ";
		}

		$sql.= "
		
		from clientes as a 
		inner join zonas as b
		on a.idZona=b.idZona
		where a.activo='1' 
		 ";
		
		$sql.=" and a.idZona!=2 and a.idZona!=8 ";
		 
		 #and a.idZona=1

		$sql.=$permiso==0?" and a.idUsuario='$this->_user_id' ":'';
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=" and (concat(a.nombre,' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%'  or a.email like '%$criterio%') ";
		}
		else
		{
			$sql.=" and (a. empresa like '%$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%'  or a.email like '%$criterio%' ) ";
		}
			
		$sql.=" group by a.idCliente
		order by a.nombre asc, a.empresa asc
		limit 20 ";
		
		#echo $sql;
		return $this->db->query($sql)->result_array();
	}
	

	public function obtenerProspectosNuevos($criterio,$permiso)
	{
		$criterio	= trim($criterio);
		
		$sql=" select a.idCliente, 
		concat(a.nombre, ' ', a.paterno, ' ', a.materno ) as value ,
		
		(select e.idSeguimiento from seguimiento as e where a.idCliente=e.idCliente and tipo='1' limit 1) as idSeguimiento
		
		from clientes as a
		inner join zonas as b
		on a.idZona=b.idZona
		
		inner join clientes_campanas as c
		on a.idCampana=c.idCampana
		
		where (a. empresa like '%$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%'  or a.email like '%$criterio%'  
		or concat(a.nombre, ' ', a.paterno, ' ', a.materno) like '%$criterio%' or a.telefono like '%$criterio%' or a.movil like '%$criterio%'  or a.email like '%$criterio%' )
		and a.prospecto='1' 
		and a.idZona!='2'
		and a.activo='1'
		and a.nuevoRegistro
		and c.fechaFinal > curdate() ";
		
		#$sql.=" and (select count(g.idDetalle) from seguimiento_detalles as g inner join seguimiento as h on g.idSeguimiento=h.idSeguimiento where h.idCliente=a.idCliente )=0";
		
		#$sql.=$permiso==0?" and a.idPromotor='$this->_user_id' ":'';
		#$sql.=$this->idRol!=1?" and a.idPromotor='$this->_user_id' ":'';
		
		if($permiso==0)
		{
			$sql.=" and a.idPromotor='$this->_user_id' ";
		}
		
		
		
		$sql.=" order by a.nombre asc, 
		a.empresa asc
		limit 20  ";

		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerProveedores($criterio,$idProducto=0,$idMaterial=0,$idInventario=0,$idServicio=0,$catalogos=0)
	{
		$sql="select a.idProveedor,  a.diasCredito,
		concat(empresa,', Alias: ',a.alias) as value "; 
		
		$sql.=$catalogos==1?"
		, (select c.idDepartamento from catalogos_egresos as c where c.idProveedor=a.idProveedor order by c.idEgreso desc limit 1) as idDepartamento
		, (select c.idNombre from catalogos_egresos as c where c.idProveedor=a.idProveedor order by c.idEgreso desc limit 1) as idNombre
		, (select c.idProducto from catalogos_egresos as c where c.idProveedor=a.idProveedor order by c.idEgreso desc limit 1) as idProducto
		, (select c.idForma from catalogos_egresos as c where c.idProveedor=a.idProveedor order by c.idEgreso desc limit 1) as idForma
		, (select c.idCuenta from catalogos_egresos as c where c.idProveedor=a.idProveedor order by c.idEgreso desc limit 1) as idCuenta
		, (select c.idGasto from catalogos_egresos as c where c.idProveedor=a.idProveedor order by c.idEgreso desc limit 1) as idGasto ":'';
		
		$sql.=" from proveedores as a
		where (a.empresa like '%$criterio%' or a.alias like '%$criterio%' )
		and a.activo='1' ";
		
		$sql.=$idProducto>0?" and  (select count(b.idProveedor) from rel_producto_proveedor as b where b.idProveedor=a.idProveedor and b.idProducto='$idProducto' ) = 0 ":'';
		$sql.=$idMaterial>0?" and  (select count(b.idProveedor) from rel_material_proveedor as b where b.idProveedor=a.idProveedor and b.idMaterial='$idMaterial' ) = 0 ":'';
		$sql.=$idInventario>0?" and  (select count(b.idProveedor) from rel_inventario_proveedor as b where b.idProveedor=a.idProveedor and b.idInventario='$idInventario' ) = 0 ":'';
		$sql.=$idServicio>0?" and  (select count(b.idProveedor) from servicios_proveedores as b where b.idProveedor=a.idProveedor and b.idServicio='$idServicio' ) = 0 ":'';
		
		
		
		$sql .=" limit 20 ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerOrdenesCompra($criterio,$tipo,$idProveedor=0)
	{
		$sql=" select a.idCompras, a.idProveedor,
		a.total, a.iva, a.subTotal, a.ivaPorcentaje,
		b.empresa as proveedor,
		concat(a.nombre, ', ',b.empresa) as value,
		
		(select coalesce(sum(c.pago),0) from catalogos_egresos as c where c.idCompra=a.idCompras and c.idForma!=4) as pagado,
		(select c.nombre from productos as c inner join compra_detalles as d
		on d.idMaterial=c.idProducto 
		where d.idCompra=a.idCompras limit 1) as producto
		
		from compras as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		where a.nombre like '%$criterio%'
		and a.idLicencia='$this->idLicencia'
		and (select coalesce(sum(c.pago),0) from catalogos_egresos as c where c.idCompra=a.idCompras and c.idForma!=4) < a.total ";
		
		$sql.=$idProveedor>0?" and a.idProveedor='$idProveedor' ":'';
		
		if($tipo==0) $sql.=" and a.reventa=0 and inventario=0 and a.servicios='0' ";
		if($tipo==1) $sql.=" and a.reventa=1";
		if($tipo==2) $sql.=" and a.inventario=1";
		if($tipo==3) $sql.=" and a.servicios='1'";
		
		
		$sql.=" limit 20 ";
		
		return $this->db->query($sql)->result_array();
	}
	
	
	public function obtenerMateriales($criterio,$precios=1)
	{
		$sql = "select a.idMaterial, a.idUnidad,  a.codigoInterno,";
		
		if(sistemaActivo=='olyess')
		{
			$sql.=" concat(a.codigoInterno, ', ', a.nombre, ', ', b.descripcion, ', $', a.precio) as value, ";
		}
		else
		{
			if($precios==1)
			{
				$sql.=" concat(a.codigoInterno, ', ', a.nombre, ', ', b.descripcion, ', $', c.costo) as value, ";	
			}
			else
			{
				$sql.=" concat(a.codigoInterno, ', ', a.nombre, ', ', b.descripcion) as value, ";
			}
			
		}
		
		
		$sql.=" a.nombre, c.costo, d.empresa, d.idProveedor, b.descripcion as unidad, a.precio, a.precioImpuestos,
		b.descripcion as unidad
		from produccion_materiales as a
		inner join unidades as b
		on a.idUnidad=b.idUnidad
		inner join rel_material_proveedor as c
		on a.idMaterial=c.idMaterial
		inner join proveedores as d
		on c.idProveedor=d.idProveedor
		where (a.nombre like '%$criterio%' or a.codigoInterno like '%$criterio%')
		and a.tipoMaterial='1'
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerMaterialesProduccion($criterio)
	{
		$sql = " select a.idMaterial, a.idUnidad, 
		concat(a.nombre, ', Unidad: ', b.descripcion, ', Costo: $', a.costo) as value,
		a.nombre
		from produccion_materiales as a
		inner join unidades as b
		on a.idUnidad=b.idUnidad
		where a.nombre like '%$criterio%'  
		and a.tipoMaterial='1'
		limit 20 ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerProductosInventario($criterio,$idLinea=0)
	{
		#concat('Código interno: ', codigoInterno, ', Código barras: ' ,codigoBarras, ', Nombre: ', nombre) as value
		$sql = "select idProducto, nombre, codigoInterno, codigoBarras,
		concat('Código: ', codigoInterno, ', ', 'Producto: ', nombre) as value
		from productos 
		where (nombre like '%$criterio%' 
		or codigoInterno like '%$criterio%'
		or codigoBarras like '%$criterio%' )
		and activo='1' 
		and servicio='0' 
		and materiaPrima='0' ";
		
		$sql.=$idLinea>0?" and idLinea='$idLinea' ":'';
		/*$sql.=$idLinea==2?" and idLinea='$idLinea' ":'';
		
		if($idLinea!=3 and $idLinea!=2)
		{
			$sql.=" and idLinea='$idLinea' ";
		}*/
		
		
		$sql.=" limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerProductosActualizar($criterio,$campo='')
	{
		$sql = " select a.idProducto, a.nombre, a.codigoInterno, a.codigoBarras,
		
		a.idUnidad, a.idClave, d.precioA, d.precioB, d.precioC, round(a.cantidadMayoreo,2) as cantidadMayoreo,
		a.stockMinimo, concat(b.clave, ' ', b.nombre) as unidad, concat(c.clave, ' ', c.nombre) as claveProducto,
		
		concat('Código: ', a.codigoInterno, ', ', 'Producto: ', a.nombre) as value, 
		
		round(d.stock,2) as stock, e.precio as costo, a.stockMaximo
		
		from productos  as a
		
		inner join fac_catalogos_unidades as b
		on a.idUnidad=b.idUnidad
		
		inner join fac_catalogos_claves_productos as c
		on a.idClave=c.idClave
		
		inner join productos_inventarios as d
		on d.idProducto=a.idProducto
		
		inner join rel_producto_proveedor as e
		on e.idProducto=a.idProducto
		
		
		where a.".$campo." like '$criterio%' 
		and a.activo='1' 
		and a.servicio='0' 
		and a.materiaPrima='0'
		and d.idLicencia='$this->idLicencia'
		limit 20 ";

		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerProductosCampos($criterio,$campo='')
	{
		$sql = " select a.idProducto, a.nombre, a.codigoInterno, a.codigoBarras,
		
		a.idUnidad, a.idClave, d.precioA, d.precioB, d.precioC, round(a.cantidadMayoreo,2) as cantidadMayoreo,
		a.stockMinimo, concat(b.clave, ' ', b.nombre) as unidad, concat(c.clave, ' ', c.nombre) as claveProducto,
		
		concat('Código: ', a.codigoInterno, ', ', 'Producto: ', a.nombre) as value, 
		
		round(d.stock,2) as stock, e.precio as costo, a.stockMaximo
		
		from productos  as a
		
		inner join fac_catalogos_unidades as b
		on a.idUnidad=b.idUnidad
		
		inner join fac_catalogos_claves_productos as c
		on a.idClave=c.idClave
		
		inner join productos_inventarios as d
		on d.idProducto=a.idProducto
		
		inner join rel_producto_proveedor as e
		on e.idProducto=a.idProducto
		
		
		where a.".$campo." = '$criterio' 
		and a.activo='1' 
		and a.servicio='0' 
		and d.idLicencia='$this->idLicencia'  ";
		

		return $this->db->query($sql)->row_array();
	}
	
	
	public function obtenerProductosInventarioRepetido($criterio,$columna='')
	{
		$sql = "select idProducto, 
		".$columna." as value
		from productos 
		where (nombre like '%$criterio%' 
		or codigoInterno like '%$criterio%'
		or codigoBarras like '%$criterio%' )
		and activo='1' 
		and servicio='0' 
		and materiaPrima='0'
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerServiciosAutocompletado($criterio)
	{
		$sql = "select idProducto, 
		concat('Código interno: ', codigoInterno, ', Nombre: ', nombre) as value
		from productos 
		where (nombre like '%$criterio%' 
		or codigoInterno like '%$criterio%' )
		and activo='1' 
		and servicio='1' 
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerRolesRepetidos($criterio)
	{
		$sql = "select idRol, 
		nombre as value
		from roles 
		where nombre like '%$criterio%' 
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerBancosRepetidos($criterio)
	{
		$sql = "select idBanco, 
		nombre as value
		from bancos 
		where nombre like '%$criterio%' 
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerUnidadesRepetidas($criterio)
	{
		$sql = "select idUnidad, 
		descripcion as value
		from unidades 
		where descripcion like '%$criterio%' 
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerTipoCliente($criterio)
	{
		$sql = "select idZona, 
		descripcion as value
		from zonas 
		where descripcion like '%$criterio%' 
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerProcesosRepetidos($criterio)
	{
		$sql = "select idProceso, 
		nombre as value
		from produccion_orden_procesos 
		where nombre like '%$criterio%' 
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerDepartamentosRepetidos($criterio)
	{
		$sql = "select idDepartamento, 
		nombre as value
		from catalogos_departamentos 
		where nombre like '%$criterio%' 
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerProductosRepetidos($criterio)
	{
		$sql = "select idProducto, 
		nombre as value
		from catalogos_productos 
		where nombre like '%$criterio%' 
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerGastosRepetidos($criterio)
	{
		$sql = "select idGasto, 
		nombre as value
		from catalogos_gastos 
		where nombre like '%$criterio%' 
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerNombresRepetidos($criterio)
	{
		$sql = "select idNombre, 
		nombre as value
		from catalogos_nombres 
		where nombre like '%$criterio%' 
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerLineasRepetidas($criterio)
	{
		$sql = "select idLinea, 
		nombre as value
		from productos_lineas 
		where nombre like '%$criterio%' 
		and activo='1'
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerOrdenesProduccion($criterio)
	{
		$sql = "select a.idOrden, concat('Orden: ',a.orden,' Producto: ', b.nombre) as value 
		from produccion_orden_produccion as a
		inner join produccion_productos as b
		on a.idProducto=b.idProducto 
		where  a.idOrden>0
		and (b.nombre like '%$criterio%' 
		or a.orden like '%$criterio%')
		order by a.fechaRegistro desc 
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerProductosServicios($criterio,$descripcion=1)
	{
		$sql = "select idProducto, 
		".($descripcion==1?"concat('Código interno: ', codigoInterno, ', Código barras: ' ,codigoBarras, ', Nombre: ', nombre)":"nombre")." as value,
		nombre, precioA, precioB, precioC, precioD, precioE, servicio,idPeriodo
		from productos 
		where (nombre like '%$criterio%' 
		or codigoInterno like '%$criterio%'
		or codigoBarras like '%$criterio%' )
		and activo='1' 
		and materiaPrima='0'
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerProductosPedido($criterio)
	{
		$sql = "select nombre, (precioA/1.16) as precioA, (precioB/1.16) as precioB,
		(precioC/1.16) as precioC,  (precioD/1.16) as precioD, (precioE/1.16) as precioE,
		codigoInterno, idProducto, 
		nombre as value
		from productos 
		where activo='1'
		and(nombre like '%$criterio%' or
		codigoInterno like '%$criterio%')
		limit 10";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerInventarioProduccion($criterio)
	{
		$sql = "select idProducto, nombre as value
		from productos 
		where nombre like '%$criterio%'  
		and activo='1'
		and reventa='0' 
		and servicio='0' 
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerInventarioMobiliario($criterio)
	{
		$sql = "select idInventario, nombre as value
		from inventarios 
		where nombre like '%$criterio%'  
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerCotizaciones($criterio)
	{
		$sql = "select idCotizacion, 
		concat('Folio: ',folio,', Serie: ',serie) as value
		from cotizaciones 
		where estatus='0'
		and (serie like '%$criterio%'
		or folio like '$criterio' )
		and idCliente=".$this->session->userdata('idClienteFicha')."
		and idLicencia='$this->idLicencia' 
		limit 15";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerListaCotizaciones($criterio,$idCliente=0,$permiso=0)
	{
		$sql = " select idCotizacion, idCliente,
		concat('Folio: ',folio,', Serie: ',serie) as value
		from cotizaciones 
		where estatus='0' 
		and idLicencia='$this->idLicencia' ";
		
		$sql.=$idCliente>0?" and idCliente='$idCliente' ":'';
		$sql.=$permiso==0?" and idUsuario='$this->_user_id' ":'';
		
		$sql.=" and (serie like '%$criterio%'
		or folio like '$criterio' )
		limit 15 ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerVentas($criterio)
	{
		$sql = "select idCotizacion, 
		concat('Folio: ',folio,', Orden: ',ordenCompra) as value
		from cotizaciones 
		where estatus='1'
		and activo='1' 
		and (ordenCompra like '%$criterio%'
		or folio like '$criterio' )
		and idCliente=".$this->session->userdata('idClienteFicha')."
		and idLicencia='$this->idLicencia' 
		limit 15";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerListaVentas($criterio,$idCliente=0,$permiso=0)
	{
		$sql = "select a.idCotizacion, a.idCliente,
		 b.empresa, (concat(b.nombre,' ',b.paterno,' ',b.materno)) as alumno,
		a.iva, a.subTotal, a.total,
		
		(select coalesce(sum(c.pago),0) from catalogos_ingresos as c where c.idVenta=a.idCotizacion  and c.idForma!=4) as pagado,
		
		(select c.nombre from cotiza_productos as c where c.idCotizacion=a.idCotizacion limit 1) as producto,
		
		(select c.tasa from cotiza_productos_impuestos as c 
		inner join cotiza_productos as d
		on d.idProducto=c.idProducto
		where d.idCotizacion=a.idCotizacion limit 1) as tasa,
		
		concat('Folio: ',a.folio,', Orden: ',a.ordenCompra) as value
		from cotizaciones  as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where a.estatus='1' 
		and a.activo='1' 
		and a.idLicencia='$this->idLicencia'  ";
		
		$sql.=$idCliente>0?" and a.idCliente='$idCliente' ":'';
		$sql.=$permiso==0?" and idUsuario='$this->_user_id' ":'';
		
		$sql.= " and (a.ordenCompra like '%$criterio%'
		or a.folio like '$criterio' )
		
		
		and (select coalesce(sum(c.pago),0) from catalogos_ingresos as c where c.idVenta=a.idCotizacion and c.idForma!=4) < a.total
		limit 15";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerFacturas($criterio)
	{
		$sql = "select idFactura, idCliente,
		concat('Serie: ',serie,', Folio: ',folio) as value
		from facturas 
		where idFactura>0
		and idLicencia='$this->idLicencia'
		and (serie like '%$criterio%'
		or folio like '$criterio' )
		limit 15";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerFacturasIngresos($criterio)
	{
		$sql = "select idIngreso, factura as value
		from catalogos_ingresos 
		where idIngreso>0
		and factura like '%$criterio%'
		and idLicencia='$this->idLicencia'
		limit 15";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerZonasBusqueda($criterio)
	{
		$sql = "select idZona, descripcion as value
		from zonas 
		where descripcion like '%$criterio%'  
		and activo='1'
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerPersonal($criterio)
	{
		$sql = "select idPersonal, nombre as value
		from recursos_personal 
		where nombre like '%$criterio%'  
		and estatus='1'
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------DIVISAS------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function obtenerDivisas()
	{    
		$sql="select * from divisas
		where activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDivisa($idDivisa)
	{    
		$sql="select * from divisas 
		where idDivisa='$idDivisa'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarDivisaRegistro($nombre,$clave)
	{
		$sql ="select idDivisa
		from  divisas 
		where activo='1'
		and nombre='$nombre'
		and clave='$clave'  ";

		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarDivisa()
	{
		if(!$this->comprobarDivisaRegistro(trim($this->input->post('nombre')),trim($this->input->post('clave'))))
		{
			return array('0',registroDuplicado);
		}
		
		$data=array
		(
			'nombre'		=> trim($this->input->post('nombre')),
			'clave'			=> trim($this->input->post('clave')),
			'tipoCambio'	=> $this->input->post('tipoCambio'),
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('divisas',$data);
		
		$this->registrarBitacora('Registrar divisa','Configuración - Divisas',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarDivisa()
	{
		$data=array
		(
			'nombre'		=>$this->input->post('nombre'),
			'clave'			=>$this->input->post('clave'),
			'tipoCambio'	=>$this->input->post('tipoCambio'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idDivisa',$this->input->post('idDivisa'));
	    $this->db->update('divisas',$data);
		
		$this->registrarBitacora('Editar divisa','Configuración - Divisas',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarDivisa($idDivisa)
	{
		$sql="select idDivisa
		from cotizaciones
		where idDivisa='$idDivisa'";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function obtenerDetalleDivisa($idDivisa)
	{
		$sql="select nombre
		from divisas
		where idDivisa='$idDivisa' ";
		
		$divisa	= $this->db->query($sql)->row();
		
		return $divisa!=null?$divisa->nombre:'';
	}
	
	public function borrarDivisa($idDivisa)
	{
		if($this->comprobarDivisa($idDivisa)>0)
		{
			return 0;
		}
		
		$this->db->where('idDivisa',$idDivisa);
		$this->db->update('divisas',array('activo'=>'0'));
		
		$this->registrarBitacora('Borrar divisa','Configuración - Divisas',$this->obtenerDetalleDivisa($idDivisa)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerIvas()
	{
		$sql="select iva, iva2, iva3, variable1, variable2, variable3 ,variable4
		from configuracion ";
		
		return $this->db->query($sql)->row();
	}
	
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------CATALOGOS----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	// PARA DEPARTAMENTOS
	public function obtenerDepartamentos()
	{    
		$sql="select * from catalogos_departamentos
		where ingresos=1 ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDepartamento($idDepartamento)
	{    
		$sql="select * from catalogos_departamentos 
		where idDepartamento='$idDepartamento'";
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarDepartamento()
	{
		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
			'tipo'			=> $this->input->post('tipo'),
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('catalogos_departamentos',$data);
		
		$this->registrarBitacora('Registrar departamento','Configuración - Catálogos contables',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function editarDepartamento()
	{
		$data=array
		(
			'nombre'		=>$this->input->post('nombre'),
			'tipo'			=> $this->input->post('tipo'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idDepartamento',$this->input->post('idDepartamento'));
	    $this->db->update('catalogos_departamentos',$data);
		
		$this->registrarBitacora('Editar departamento','Configuración - Catálogos contables',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarDepartamento($idDepartamento)
	{
		$sql="select idDepartamento
		from catalogos_ingresos
		where idDepartamento='$idDepartamento'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select idDepartamento
		from catalogos_egresos
		where idDepartamento='$idDepartamento'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		return 0;
	}
	
	public function borrarDepartamento($idDepartamento)
	{
		if($this->comprobarDepartamento($idDepartamento)>0)
		{
			return "0";
		}
		
		$departamento	= $this->obtenerDepartamento($idDepartamento);
		
		$this->db->where('idDepartamento',$idDepartamento);
		$this->db->delete('catalogos_departamentos');
		
		if($departamento!=null)
		{
			$this->registrarBitacora('Borrar departamento','Configuración - Catálogos contables',$departamento->nombre); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	
	// PARA PRODUCTOS
	public function obtenerProductos()
	{    
		$sql="select * from catalogos_productos
		where ingresos=1 ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerProducto($idProducto)
	{    
		$sql="select * from catalogos_productos 
		where idProducto='$idProducto'";
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarProducto()
	{
		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
			'tipo'			=> $this->input->post('tipo'),
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('catalogos_productos',$data);
		
		$this->registrarBitacora('Registrar producto','Configuración - Catálogos contables',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function editarProducto()
	{
		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
			'tipo'			=> $this->input->post('tipo'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idProducto',$this->input->post('idProducto'));
	    $this->db->update('catalogos_productos',$data);
		
		$this->registrarBitacora('Editar producto','Configuración - Catálogos contables',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarProducto($idProducto)
	{
		$sql="select idProducto
		from catalogos_ingresos
		where idProducto='$idProducto'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select idProducto
		from catalogos_egresos
		where idProducto='$idProducto'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		return 0;
	}
	
	public function borrarProducto($idProducto)
	{
		if($this->comprobarProducto($idProducto)>0)
		{
			return "0";
		}
		
		$producto	= $this->obtenerProducto($idProducto);
		
		$this->db->where('idProducto',$idProducto);
		$this->db->delete('catalogos_productos');
		
		if($producto!=null)
		{
			$this->registrarBitacora('Borrar producto','Configuración - Catálogos contables',$producto->nombre); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	// PARA TIPOS DE GASTO
	public function obtenerGastos()
	{    
		$sql="select * from catalogos_gastos
		where ingresos=1 ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerGasto($idGasto)
	{    
		$sql="select * from catalogos_gastos 
		where idGasto='$idGasto'";
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarGasto()
	{
		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
			'tipo'			=> $this->input->post('tipo'),
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('catalogos_gastos',$data);
		
		$this->registrarBitacora('Registrar tipo de gasto','Configuración - Catálogos contables',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function editarGasto()
	{
		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
			'tipo'			=> $this->input->post('tipo'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idGasto',$this->input->post('idGasto'));
	    $this->db->update('catalogos_gastos',$data);
		
		$this->registrarBitacora('Editar tipo de gasto','Configuración - Catálogos contables',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarGasto($idGasto)
	{
		$sql="select idGasto
		from catalogos_ingresos
		where idGasto='$idGasto'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select idGasto
		from catalogos_egresos
		where idGasto='$idGasto'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		return 0;
	}
	
	public function borrarGasto($idGasto)
	{
		if($this->comprobarGasto($idGasto)>0)
		{
			return "0";
		}
		
		$gasto	= $this->obtenerGasto($idGasto);
		
		$this->db->where('idGasto',$idGasto);
		$this->db->delete('catalogos_gastos');
		
		if($gasto!=null)
		{
			$this->registrarBitacora('Borrar tipo de gasto','Configuración - Catálogos contables',$gasto->nombre); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	// PARA NOMBRES
	public function obtenerNombres()
	{    
		$sql="select * from catalogos_nombres 
		where ingresos=1";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerNombre($idNombre)
	{    
		$sql="select * from catalogos_nombres 
		where idNombre='$idNombre'";
		
		$query=$this->db->query($sql);
		
		return  $query->row();
	}
	
	public function registrarNombre()
	{
		$data=array
		(
			'nombre'		=>$this->input->post('nombre'),
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('catalogos_nombres',$data);
		
		$this->registrarBitacora('Registrar nombre','Configuración - Catálogos contables',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function editarNombre()
	{
		$data=array
		(
			'nombre'		=>$this->input->post('nombre'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idNombre',$this->input->post('idNombre'));
	    $this->db->update('catalogos_nombres',$data);
		
		$this->registrarBitacora('Editar nombre','Configuración - Catálogos contables',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarNombre($idNombre)
	{
		$sql="select idNombre
		from catalogos_ingresos
		where idNombre='$idNombre'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		$sql="select idNombre
		from catalogos_egresos
		where idNombre='$idNombre'";
		
		if($this->db->query($sql)->num_rows()>0)
		{
			return 1;
		}
		
		return 0;
	}
	
	public function borrarNombre($idNombre)
	{
		if($this->comprobarNombre($idNombre)>0)
		{
			return "0";
		}
		
		$nombre	= $this->obtenerNombre($idNombre);
		
		$this->db->where('idNombre',$idNombre);
		$this->db->delete('catalogos_nombres');
		
		if($nombre!=null)
		{
			$this->registrarBitacora('Borrar nombre','Configuración - Catálogos contables',$nombre->nombre); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function quitarNotificacionCobro()
	{
		$data=array
		(
			'notificacion'	=>0
		);
		
		$this->db->where('idIngreso',$this->input->post('idIngreso'));
		$this->db->update('catalogos_ingresos',$data);
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function quitarNotificacionPago()
	{
		$data=array
		(
			'notificacion'	=>0
		);
		
		$this->db->where('idEgreso',$this->input->post('idEgreso'));
		$this->db->update('catalogos_egresos',$data);
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function obtenerCobrosProgramados()
	{
		$sql="select idIngreso, fecha, producto, 
		concepto, pago, idCliente
		from  catalogos_ingresos
		where idForma='4'
		and notificacion=1 
		and date(fecha)='".date('Y-m-d')."' ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerPagosProgramados()
	{
		$sql="select idEgreso, fecha, producto, 
		concepto, pago, idProveedor
		from  catalogos_egresos
		where idForma='4'
		and notificacion=1
		and date(fecha)='".date('Y-m-d')."' ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerCliente($idCliente)
	{
		$sql="select empresa
		from clientes
		where idCliente='$idCliente' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerProveedor($idProveedor)
	{
		$sql="select empresa
		from proveedores
		where idProveedor='$idProveedor' ";
		
		return $this->db->query($sql)->row();
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#-------------------------------------------------SERVICIOS------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function obtenerServicios($cliente='')
	{    
		$sql=" select * from seguimiento_servicios
		where idServicio>0 
		and activo='1' ";
		$sql.=strlen($cliente)>0?" and cliente='$cliente' ":'';
		$sql."= order by sistema desc,
		nombre asc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerServicio($idServicio)
	{    
		$sql="select * from seguimiento_servicios 
		where idServicio='$idServicio'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarServicioRegistro($nombre,$cliente)
	{
		$sql ="select idServicio
		from  seguimiento_servicios 
		where activo='1'
		and nombre='$nombre'
		and cliente='$cliente' ";

		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarServicio()
	{
		if(!$this->comprobarServicioRegistro(trim($this->input->post('nombre')),trim($this->input->post('cliente'))))
		{
			return array('0',registroDuplicado);
		}

		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
			'cliente'		=> $this->input->post('cliente'),
		);
		
		$data	= procesarArreglo($data);
	    $this->db->insert('seguimiento_servicios',$data);
		
		$this->registrarBitacora('Registrar servicio','Configuración - Servicios',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);  
	}
	
	public function editarServicio()
	{
		$data=array
		(
			'nombre'		=>$this->input->post('nombre'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idServicio',$this->input->post('idServicio'));
	    $this->db->update('seguimiento_servicios',$data);
		
		$this->registrarBitacora('Editar servicio','Configuración - Servicios',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarServicio($idServicio)
	{
		$sql="select idServicio
		from seguimiento
		where idServicio='$idServicio'";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function borrarServicio($idServicio)
	{
		/*if($this->comprobarServicio($idServicio)>0)
		{
			return 0;
		}*/
		
		$servicio=$this->obtenerServicio($idServicio);
		
		$this->db->where('idServicio',$idServicio);
		$this->db->update('seguimiento_servicios',array('activo'=>'0'));
		
		if($servicio!=null)
		{
			$this->registrarBitacora('Borrar servicio','Configuración - Servicios',$servicio->nombre); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerUltimaDiaFecha($fecha)
	{
		$sql="select day(last_day('$fecha')) as dia";
		
		return $this->db->query($sql)->row()->dia;
	}
	
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	#EMISORES
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function obtenerErrores($idEmisor)
	{
		$sql="select count(idError) as numero 
		from facturas_errores  
		where idEmisor='$idEmisor' ";
		
		return 0;
		
		#return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerEmisores()
	{
		$sql=" select a.*,
		(select b.usuario from licencias as  b where a.idLicencia=b.idLicencia) as licencia
		
		from configuracion_emisores as a 
		where a.activo='1' ";
		
		$sql.=$this->idLicencia!=1?" and a.idLicencia='$this->idLicencia' ":'';
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFoliosTotales()
	{
		$sql=" select sum(a.folioFinal) as comprados,
		(select count(idFactura) from facturas as b where b.pendiente='0') as consumidos
		from configuracion_emisores as a 
		where a.activo='1' ";
		
		return $this->db->query($sql)->row();
	}
	
	public function obtenerEmisor($idEmisor)
	{
		$sql=" select a.*,
		b.nombre as regimen, b.clave as claveRegimen
		from configuracion_emisores as a
		inner join fac_catalogos_regimen_fiscal as b
		on a.idRegimen=b.idRegimen
		where a.idEmisor='$idEmisor' ";
		
		return $this->db->query($sql)->row();
	}
	
	
	public function registrarEmisor()
	{
		#Facturación
		$rfc	=$this->input->post('txtRfc');
		
		$data=array
		(
			'nombre'				=> $this->input->post('txtEmpresa'),
			'rfc'					=> $rfc,
			'calle'					=> $this->input->post('txtCalle'),
			'numeroExterior'		=> $this->input->post('txtNumeroExterior'),
			'numeroInterior'		=> $this->input->post('txtNumeroInterior'),
			'colonia'				=> $this->input->post('txtColonia'),
			'localidad'				=> $this->input->post('txtLocalidad'),
			'municipio'				=> $this->input->post('txtMunicipio'),
			'estado'				=> $this->input->post('txtEstado'),
			'pais'					=> $this->input->post('txtPais'),
			'codigoPostal'			=> $this->input->post('txtCodigoPostal'),
			'folioInicial'			=> $this->input->post('txtFolioInicial'),
			'folioFinal'			=> $this->input->post('txtFolioFinal'),
			'passwordLlave'			=> $this->input->post('passwordLlave'),
			'serie'					=> $this->input->post('txtSerie'),
			'numeroCertificado'		=> $this->input->post('txtNumeroCertificado'),
			'numeroCuenta'			=> $this->input->post('txtNumeroCuenta'),
			'sucursales'			=> $this->input->post('txtSucursales'),
			'notas'					=> $this->input->post('txtNotaMargen'),
			'fechaInicio'			=> $this->input->post('txtFechaInicio'),
			'fechaCaducidad'		=> $this->input->post('txtFechaCaducidad'),
			'idRegimen'				=> $this->input->post('selectRegimenFiscal'),
			'idLicencia'			=> $this->idLicencia,
			'idPac'					=> 2
		);
		
		$data	= procesarArreglo($data);
		
		$directorio="media/fel/".$rfc.'/';
		crearDirectorio($directorio);
		
		if(strlen($_FILES['fileCertificado']['name'])>3)
		{
			#$uploaddir  = "media/documentos/arterama/";
			$uploadfile = $directorio . basename($_FILES['fileCertificado']['name']);
			
			move_uploaded_file($_FILES['fileCertificado']['tmp_name'], $uploadfile);
			
			$data['certificado']=$_FILES['fileCertificado']['name'];
		}
		
		if(strlen($_FILES['fileLlave']['name'])>3)
		{
			#$uploaddir  = "media/fel/".$this->idLicencia.'_facturacion/';
			$uploadfile = $directorio . basename($_FILES['fileLlave']['name']);
			
			move_uploaded_file($_FILES['fileLlave']['tmp_name'], $uploadfile);
			
			$data['llave']=$_FILES['fileLlave']['name'];
		}
		
		if(strlen($_FILES['fileImagen']['name'])>1)
		{
			#$uploaddir  = "media/fel/".$this->idLicencia.'_facturacion/';
			$uploadfile = $directorio . basename($_FILES['fileImagen']['name']);
			
			move_uploaded_file($_FILES['fileImagen']['tmp_name'], $uploadfile);
			
			$data['logotipo']=$_FILES['fileImagen']['name'];
		}
		
		$this->db->insert('configuracion_emisores',$data);
		
		$this->registrarBitacora('Registrar emisor','Configuración - Emisores',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	
	public function editarEmisor()
	{
		#Facturación
		$rfc	=$this->input->post('txtRfc');
		
		$data=array
		(
			'nombre'				=>$this->input->post('txtEmpresa'),
			'rfc'					=>$rfc,
			'calle'					=>$this->input->post('txtCalle'),
			'numeroExterior'		=>$this->input->post('txtNumeroExterior'),
			'numeroInterior'		=>$this->input->post('txtNumeroInterior'),
			'colonia'				=>$this->input->post('txtColonia'),
			'localidad'				=>$this->input->post('txtLocalidad'),
			'municipio'				=>$this->input->post('txtMunicipio'),
			'estado'				=>$this->input->post('txtEstado'),
			'pais'					=>$this->input->post('txtPais'),
			'codigoPostal'			=>$this->input->post('txtCodigoPostal'),
			
			#'folioInicial'			=>$this->input->post('txtFolioInicial'),
			#'folioFinal'			=>$this->input->post('txtFolioFinal'),
			'passwordLlave'			=>$this->input->post('passwordLlave'),
			#'serie'					=>$this->input->post('txtSerie'),
			'numeroCertificado'		=>$this->input->post('txtNumeroCertificado'),
			'numeroCuenta'			=>$this->input->post('txtNumeroCuenta'),
			#'regimenFiscal'			=>$this->input->post('txtRegimenFiscal'),
			
			
			'sucursales'			=>$this->input->post('txtSucursales'),
			'notas'					=>$this->input->post('txtNotaMargen'),
			'fechaInicio'			=>$this->input->post('txtFechaInicio'),
			'fechaCaducidad'		=>$this->input->post('txtFechaCaducidad'),
			
			'idRegimen'				=>$this->input->post('selectRegimenFiscal'),
		);
		
		$data	= procesarArreglo($data);
		
		$directorio="media/fel/".$rfc.'/';
		
		if(!file_exists($directorio))
		{
			crearDirectorio($directorio);
		}
		
		if(strlen($_FILES['fileCertificado']['name'])>3)
		{
			#$uploaddir  = "media/documentos/arterama/";
			$uploadfile = $directorio . basename($_FILES['fileCertificado']['name']);
			
			move_uploaded_file($_FILES['fileCertificado']['tmp_name'], $uploadfile);
			
			$data['certificado']=$_FILES['fileCertificado']['name'];
		}
		
		if(strlen($_FILES['fileLlave']['name'])>3)
		{
			#$uploaddir  = "media/fel/".$this->idLicencia.'_facturacion/';
			$uploadfile = $directorio . basename($_FILES['fileLlave']['name']);
			
			move_uploaded_file($_FILES['fileLlave']['tmp_name'], $uploadfile);
			
			$data['llave']=$_FILES['fileLlave']['name'];
		}
		
		if(strlen($_FILES['fileImagen']['name'])>1)
		{
			#$uploaddir  = "media/fel/".$this->idLicencia.'_facturacion/';
			$uploadfile = $directorio . basename($_FILES['fileImagen']['name']);
			
			move_uploaded_file($_FILES['fileImagen']['tmp_name'], $uploadfile);
			
			$data['logotipo']=$_FILES['fileImagen']['name'];
		}
		
		$this->db->where('idEmisor',$this->input->post('txtIdEmisor'));
		$this->db->update('configuracion_emisores',$data);
		
		$this->registrarBitacora('Editar emisor','Configuración - Emisores',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0";
	}
	
	public function comprobarEmisor($idEmisor)
	{
		$sql=" select count(idEmisor) as numero
		from facturas
		where idEmisor='$idEmisor' ";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerDetallesEmisor($idEmisor)
	{
		$sql="select nombre
		from configuracion_emisores
		where idEmisor='$idEmisor' ";
		
		$emisor	= $this->db->query($sql)->row();
		
		return $emisor!=null?$emisor->nombre:'';
	}
	
	public function borrarEmisor($idEmisor)
	{
		if($this->comprobarEmisor($idEmisor)>0)
		{
			return "0";
		}
		
		$emisor	=$this->obtenerEmisor($idEmisor);
	    
		if(file_exists('media/fel/'.$emisor->rfc.'/'.$emisor->certificado))
		{
			unlink('media/fel/'.$emisor->rfc.'/'.$emisor->certificado);
		}
		
		if(file_exists('media/fel/'.$emisor->rfc.'/'.$emisor->llave))
		{
			unlink('media/fel/'.$emisor->rfc.'/'.$emisor->llave);
		}
		
		$this->db->where('idEmisor',$idEmisor);
		#$this->db->delete('configuracion_emisores');
		$this->db->update('configuracion_emisores',array('activo'=>'0'));
		
		$this->registrarBitacora('Borrar emisor','Configuración - Emisores',$this->obtenerDetallesEmisor($idEmisor)); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerCompras()
	{
		$sql="select a.idCompras, a.nombre, a.fechaCompra, 
		a.total, b.empresa, b.idProveedor
		from  compras as a
		inner join proveedores as b
		on a.idProveedor=b.idProveedor
		and a.notificacion=1 ";

		return $this->db->query($sql)->result();
	}
	
	public function configurarNotificaciones()
	{
		$this->db->where('id','1');
		$this->db->update('configuracion',array('notificaciones'=>$this->input->post('criterio')));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerConfiguracionNotificacion()
	{
		$sql="select notificaciones
		from configuracion ";
		
		return $this->db->query($sql)->row()->notificaciones;
	}
	
	public function obtenerVentasCobranza()
	{
		$sql ="	select a.ordenCompra, a.fechaCompra as fechaCompra, a.total,
		c.empresa, a.fechaVencimiento, a.diasCredito,
		a.idCotizacion as idVenta, a.idFactura,
		d.descripcion as identificador, c.email, 
		(a.total-(SELECT COALESCE(SUM(f.pago),0) FROM catalogos_ingresos AS f WHERE f.idVenta=a.idCotizacion and f.idForma!='4'))  AS saldo
		from cotizaciones as a
		inner join clientes as c
		on a.idCliente=c.idCliente
		inner join zonas as d
		on c.idZona=d.idZona
		where a.estatus=1
		and a.cancelada='0'
		and a.total > (select coalesce(sum(f.pago),0) from catalogos_ingresos as f where f.idVenta=a.idCotizacion and f.idForma!='4') ";

		#$sql.=$idCliente>0?" and c.idCliente='$idCliente '":'';
		#$sql.=$inicio!='fecha'?" and a.fechaCompra between '$inicio' and '$fin'":'';
		$sql .=" order by fechaCompra desc";

		return $this->db->query($sql)->result();
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
	
	//----------------------------------------------------------------------------------------------------------#
	//PARA LAS FORMAS DE PAGO
	//----------------------------------------------------------------------------------------------------------#
	
	public function seleccionarFormas()
	{
		$sql="select * from catalogos_formas
		where activo='1'
		order by orden asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFormas()
	{
		$sql="select a.*,
		if(a.idForma=0,'',(select concat(b.cuenta,',',c.nombre) from cuentas as b inner join bancos as c on c.idBanco=b.idBanco where a.idCuenta=b.idCuenta limit 1)) as cuenta
		from catalogos_formas  as a
		where a.activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerForma($idForma)
	{
		$sql="select * from catalogos_formas
		where idForma='$idForma'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarFormaRegistro($nombre)
	{
		$sql ="select idForma
		from  catalogos_formas 
		where activo='1'
		and nombre='$nombre' ";

		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarForma()
	{
		if(!$this->comprobarFormaRegistro(trim($this->input->post('nombre'))))
		{
			return array('0',registroDuplicado);
		}
		
		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
			'fecha'			=> $this->input->post('fecha'),
			'porcentaje'	=> $this->input->post('porcentaje'),
			'idCuenta'		=> $this->input->post('idCuenta'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('catalogos_formas',$data);
		
		$this->registrarBitacora('Registrar forma de pago','Configuración - Formas de pago',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarForma()
	{
		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
			'fecha'			=> $this->input->post('fecha'),
			'porcentaje'	=> $this->input->post('porcentaje'),
			'idCuenta'		=> $this->input->post('idCuenta'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idForma',$this->input->post('idForma'));
		$this->db->update('catalogos_formas',$data);
		
		$this->registrarBitacora('Editar forma de pago','Configuración - Formas de pago',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function comprobarForma($idForma)
	{
		$sql="select idForma
		from catalogos_ingresos
		where idForma='$idForma'";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function borrarForma($idForma)
	{
		/*if($this->comprobarForma($idForma)>0)
		{
			return 0;
		}*/
		
		$forma=$this->obtenerForma($idForma);
		
		$this->db->where('idForma',$idForma);
		$this->db->update('catalogos_formas',array('activo'=>'0'));
		
		if($forma!=null)
		{
			$this->registrarBitacora('Borrar forma de pago','Configuración - Formas de pago',$forma->nombre); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerCuentasFactura()
	{
		$sql="select a.*, b.nombre as banco,
		b.logotipo
		from cuentas as a
		inner join bancos as b
		on a.idBanco=b.idBanco
		where a.factura=1 ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerMensaje()
	{
		$sql=" select mensaje
		from configuracion ";
		
		return $this->db->query($sql)->row()->mensaje;
	}

	public function obtenerTiempos()
	{
		$sql=" select * from seguimiento_tiempos ";
		
		return $this->db->query($sql)->result();
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------CONTABILIDAD ELECTRÓNICA-------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	//CODIGO AGRUPADOR
	public function obtenerCodigoAgrupador($criterio)
	{
		$sql=" (select idCuenta, nombre as nombre, cuenta, '1' as nivel, codigo, codigo as codigoAgrupador,
		concat('Nivel 1: ', nombre, '(',codigo,', ',cuenta,')') as value
		from fac_cuentas
		where nombre like '%$criterio%'
		or codigo like '$criterio' ) ";
		
		$sql.=" union ";
		
		$sql.=" (select a.idSubCuenta as idCuenta, a.nombre as nombre, b.cuenta, '2' as nivel, a.codigo, a.codigo as codigoAgrupador,
		concat('Nivel 2: ', a.nombre, '(',a.codigo,', ',b.cuenta,')') as value
		from fac_subcuentas as a
		inner join fac_cuentas as b
		on a.idCuenta=b.idCuenta
		where a.nombre like '%$criterio%'
		or a.codigo like '%$criterio%'
		or b.codigo like '%$criterio%' ) ";
		
		$sql.=" union ";
		
		$sql.=" (select a.idSubCuenta3 as idCuenta, a.nombre as nombre, c.cuenta, '3' as nivel, a.codigo, b.codigo as codigoAgrupador,
		concat('Nivel 3: ', a.nombre, '(',a.codigo,', ',c.cuenta,')') as value
		from fac_subcuentas3 as a
		inner join fac_subcuentas as b
		on a.idSubCuenta=b.idSubCuenta
		inner join fac_cuentas as c
		on c.idCuenta=b.idCuenta
		where a.nombre like '%$criterio%'
		or b.codigo like '%$criterio%') ";
		
		$sql.=" union ";
		
		$sql.=" (select a.idSubCuenta4 as idCuenta, a.nombre as nombre, d.cuenta, '4' as nivel, a.codigo, c.codigo as codigoAgrupador,
		concat('Nivel 4: ', a.nombre, '(',a.codigo,', ',d.cuenta,')') as value
		from fac_subcuentas4 as a
		inner join  fac_subcuentas3 as b
		on a.idSubCuenta3=b.idSubCuenta3
		inner join fac_subcuentas as c
		on c.idSubCuenta=b.idSubCuenta
		inner join fac_cuentas as d
		on c.idCuenta=d.idCuenta
		where a.nombre like '%$criterio%'
		or c.codigo like '%$criterio%') ";
		
		$sql.=" union ";
		
		$sql.=" (select a.idSubCuenta5 as idCuenta, a.nombre as nombre, e.cuenta, '5' as nivel, a.codigo, d.codigo as codigoAgrupador,
		concat('Nivel 5: ', a.nombre, '(',a.codigo,', ',e.cuenta,')') as value
		from fac_subcuentas5 as a
		inner join  fac_subcuentas4 as b
		on a.idSubCuenta4=b.idSubCuenta4
		inner join  fac_subcuentas3 as c
		on c.idSubCuenta3=b.idSubCuenta3
		inner join fac_subcuentas as d
		on c.idSubCuenta=d.idSubCuenta
		inner join fac_cuentas as e
		on e.idCuenta=d.idCuenta
		where a.nombre like '%$criterio%'
		or d.codigo like '%$criterio%') ";
		
		$sql.=" union ";
		
		$sql.=" (select a.idSubCuenta6 as idCuenta, a.nombre as nombre, f.cuenta, '6' as nivel, a.codigo, e.codigo as codigoAgrupador,
		concat('Nivel 6: ', a.nombre, '(',a.codigo,', ',f.cuenta,')') as value
		from fac_subcuentas6 as a
		inner join fac_subcuentas5 as b
		on a.idSubCuenta5=b.idSubCuenta5
		inner join  fac_subcuentas4 as c
		on c.idSubCuenta4=b.idSubCuenta4
		inner join  fac_subcuentas3 as d
		on c.idSubCuenta3=d.idSubCuenta3
		inner join fac_subcuentas as e
		on e.idSubCuenta=d.idSubCuenta
		inner join fac_cuentas as f
		on e.idCuenta=f.idCuenta
		where a.nombre like '%$criterio%'
		or e.codigo like '%$criterio%') ";
		
		$sql.=" order by nombre asc
		limit 20";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function comprobarHorario($idUsuario)
	{
		$dia	= obtenerDiaActual(date('Y-m-d'));
		
		$sql=" select ".$dia." from usuarios_horarios
		where idUsuario='$idUsuario'
		and ".$dia."='1'
		and time('".date('H:i:s')."') between horaInicial and horaFinal ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	//YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY
	//COMPROBAR FOLIOS CONSUMIDOS
	//YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY
	
	public function obtenerFoliosConsumidos($idEmisor=0)
	{
		$sql=" select count(idFactura) as numero
		from facturas
		where idEmisor='$idEmisor'
		and pendiente='0' ";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerFoliosErrores($idEmisor=0)
	{
		$sql=" select count(idError) as numero
		from facturas_errores
		where idEmisor='$idEmisor' ";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	//YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY
	//CUENTAS REPORTES
	//YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYs
	public function obtenerCuentasReportes()
	{
		$sql="select a.*, b.nombre as banco,
		b.logotipo
		from cuentas as a
		inner join bancos as b
		on a.idBanco=b.idBanco
		where a.reportes='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCuentasSie()
	{
		$sql=" select a.*, b.nombre as banco,
		b.logotipo
		from cuentas as a
		inner join bancos as b
		on a.idBanco=b.idBanco
		where a.sie='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	//YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY
	//CUOTA DE MYSQL
	//YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYs
	
	public function obtenerCuotaAdquirida()
	{
		$sql="select cuota
		from configuracion
		where id='1' ";
		
		$cuota	= $this->db->query($sql)->row();	
		
		return $cuota!=null?$cuota->cuota:0;
	}
	
	public function obtenerCuotaBase()
	{
		$sql=" select 
		coalesce(sum( data_length + index_length ) / 1024 / 1024,0) as cuota
		from information_schema.tables
		where table_schema='".$this->db->database."'
		group by table_schema ";
		
		return $this->db->query($sql)->row()->cuota;
	}
	
	public function comprobarCuota()
	{
		/*$cuota			= $this->obtenerCuotaAdquirida();
		$cuotaBase		= $this->obtenerCuotaBase();
		$archivos		= calcularTamanoDisco(carpetaMedia);
		$imagenes		= calcularTamanoDisco(carpetaProductos);

		if(($cuotaBase+$archivos+$imagenes)<$cuota) 
			return true;
		else
			return false;*/
		
		return true;
		
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#-------------------------------------------------  STATUS ------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function obtenerStatus($cliente='')
	{    
		$sql=" select a.*,(select b.nombre from seguimiento_status as b where b.idStatus=a.idStatusIgual) as igual
		from seguimiento_status as a
		where a.idStatus>0 
		and activo='1' ";
		
		$sql.=strlen($cliente)>0?" and a.cliente='$cliente' or a.sistema='1' ":'';
		$sql."= order by a.sistema desc,
		a.nombre asc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerStatusEditar($idStatus)
	{    
		$sql="select * from seguimiento_status 
		where idStatus='$idStatus'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarStatus($nombre,$cliente)
	{
		$sql="select idStatus
		from seguimiento_status
		where nombre='$nombre'
		and cliente='$cliente'
		and activo='1' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarStatus()
	{
		if(!$this->comprobarStatus($this->input->post('nombre'),$this->input->post('cliente')))
		{
			return array('0',registroDuplicado); 
		}
		
		$data=array
		(
			'nombre'			=> $this->input->post('nombre'),
			'cliente'			=> $this->input->post('cliente'),
			'color'				=> $this->input->post('color'),
			'idStatusIgual'		=> $this->input->post('idStatusIgual'),
		);

	    $this->db->insert('seguimiento_status',$data);
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarStatus()
	{
		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
			'color'			=> $this->input->post('color'),
		);
		
		$this->db->where('idStatus',$this->input->post('idStatus'));
	    $this->db->update('seguimiento_status',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function borrarStatus($idStatus)
	{
		/*if($this->comprobarServicio($idServicio)>0)
		{
			return 0;
		}*/
		
		$this->db->where('idStatus',$idStatus);
		$this->db->update('seguimiento_status',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	
	#----------------------------------------------------------------------------------------------------------#
	#-------------------------------------------------  ESTATUS ------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function obtenerEstatus($tipo='0')
	{    
		$sql=" select a.*
		from seguimiento_estatus as a
		where a.idEstatus>0 
		and activo='1' ";
		
		if(sistemaActivo=='IEXE')
		{
			$sql.=" and a.tipo='$tipo' ";
		}
		
		
		#$sql.=strlen($cliente)>0?" and a.cliente='$cliente' or a.sistema='1' ":'';
		$sql."= order by a.sistema desc,
		a.nombre asc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerEstatusEditar($idEstatus)
	{    
		$sql="select * from seguimiento_estatus 
		where idEstatus='$idEstatus'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarEstatus($nombre,$cliente)
	{
		$sql="select idEstatus
		from seguimiento_estatus
		where nombre='$nombre'
		and cliente='$cliente'
		and activo='1' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarEstatus()
	{
		if(!$this->comprobarEstatus($this->input->post('nombre'),$this->input->post('cliente')))
		{
			return array('0',registroDuplicado); 
		}
		
		$data=array
		(
			'nombre'			=> $this->input->post('nombre'),
			'cliente'			=> $this->input->post('cliente'),
			'color'				=> $this->input->post('color'),
			'tipo'				=> $this->input->post('tipo'),
		);

	    $this->db->insert('seguimiento_estatus',$data);
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarEstatus()
	{
		$data=array
		(
			'nombre'		=> $this->input->post('nombre'),
			'color'			=> $this->input->post('color'),
		);
		
		$this->db->where('idEstatus',$this->input->post('idEstatus'));
	    $this->db->update('seguimiento_estatus',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function borrarEstatus($idEstatus)
	{
		/*if($this->comprobarServicio($idServicio)>0)
		{
			return 0;
		}*/
		
		$this->db->where('idEstatus',$idEstatus);
		$this->db->update('seguimiento_estatus',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	//BITACORA DE ACCIONES
	
	public function registrarBitacora($accion='',$modulo='',$descripcion='')
	{
		$data=array
		(
			'idUsuario'			=> $this->_user_id,
			'fecha'				=> $this->_fecha_actual,
			'usuario'			=> $this->usuarioActivo,
			'nombre'			=> $this->_user_name,
			'accion'			=> $accion,
			'modulo'			=> $modulo,
			'descripcion'		=> $descripcion,
			#'idLicencia'		=> $this->idLicencia,
		);

	    $this->db->insert('configuracion_bitacora',$data);
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function registrarHistorialEnvios($correo='',$tipo='',$idUsuario=0,$id)
	{
		$data=array
		(
			'correo'			=> $correo,
			'tipo'				=> $tipo,
			'idUsuario'			=> $idUsuario,
			'id'				=> $id,
			'fecha'				=> $this->_fecha_actual,
		);

	    $this->db->insert('envios_historial',$data);
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerHistorialEnvios($id,$tipo)
	{    
		$sql=" select a.*, concat(b.nombre,' ',b.apellidoPaterno,' ',b.apellidoMaterno) as usuario
		from envios_historial as a
		inner join usuarios as b
		on a.idUsuario=b.idUsuario
		where id='$id'
		and tipo='$tipo'
		order by a.fecha desc ";
		
		return $this->db->query($sql)->result();
	}

	#------------------------------------------------------------------------------------------#
	
	public function comprobarLineaNombre($nombre)
	{
		$sql="select idLinea
		from productos_lineas
		where nombre='$nombre'
		and activo='1' ";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function agregarLinea()
	{
		if($this->comprobarLineaNombre($this->input->post('nombre'))>0) return array('0',registroDuplicado);;
		
		$data = array
		(
			"nombre" =>$this->input->post('nombre'),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('productos_lineas',$data);
		
		$this->registrarBitacora('Registrar línea','Configuración - Líneas',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro);
	}
	
	public function obtenerLineas($pedidos=0)
	{    
		$sql="select * from productos_lineas 
		where activo='1' ";
		
		$sql.=$pedidos==1?' and (idLinea=2 or idLinea=3)':'';
		$sql.=" order by nombre asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerLinea($idLinea)
	{    
		$sql="select * from productos_lineas 
		where idLinea='$idLinea'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarLineaRegistro($nombre)
	{
		$sql ="select idLinea
		from  productos_lineas 
		where activo='1'
		and nombre='$nombre' ";

		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarLinea()
	{
		if(!$this->comprobarLineaRegistro(trim($this->input->post('txtNombre'))))
		{
			return array('0',registroDuplicado);
		}
		
		$data=array
		(
			'nombre'		=> trim($this->input->post('txtNombre')),
		);
		
		$data	= procesarArreglo($data);
		
		$imagen 	= $_FILES['txtImagen']['name'];
		
		if(strlen($imagen)>2)
		{
			$data['imagen']	= $imagen;

			move_uploaded_file($_FILES['txtImagen']['tmp_name'], carpetaProductos.basename($imagen));
		}

	    $this->db->insert('productos_lineas',$data);
		
		$this->registrarBitacora('Registrar línea','Configuración - Líneas',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarLinea()
	{
		$data=array
		(
			'nombre'		=>$this->input->post('txtNombre'),
		);
		
		$data	= procesarArreglo($data);
		
		$imagen 	= $_FILES['txtImagen']['name'];
		
		if(strlen($imagen)>2)
		{
			$data['imagen']	= $imagen;

			move_uploaded_file($_FILES['txtImagen']['tmp_name'], carpetaProductos.basename($imagen));
		}
		
		$this->db->where('idLinea',$this->input->post('txtIdLinea'));
	    $this->db->update('productos_lineas',$data);
		
		$this->registrarBitacora('Editar línea','Configuración - Líneas',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function comprobarLinea($idLinea)
	{
		$sql="select idLinea
		from productos
		where idLinea='$idLinea'";
		
		return $this->db->query($sql)->num_rows();
	}
	
	public function borrarLinea($idLinea)
	{
		/*if($this->comprobarLinea($idLinea)>0)
		{
			return 0;
		}*/
		
		$linea=$this->obtenerLinea($idLinea);
		
		$this->db->where('idLinea',$idLinea);
		$this->db->update('productos_lineas',array('activo'=>'0'));
		
		if($linea!=null)
		{
			$this->registrarBitacora('Borrar línea','Configuración - Líneas',$linea->nombre); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	//SUBLINEAS
	
	public function comprobarSubLineaRegistro($nombre)
	{
		$sql ="select idLinea
		from  productos_sublineas 
		where activo='1'
		and nombre='$nombre' ";

		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function obtenerSubLinea($idSubLinea)
	{
		$sql ="select *
		from  productos_sublineas 
		where activo='1'
		and idSubLinea='$idSubLinea' ";

		return $this->db->query($sql)->row();
	}
	
	public function obtenerSubLineas($idLinea)
	{
		$sql ="select *
		from  productos_sublineas 
		where activo='1'
		and idLinea='$idLinea' ";

		return $this->db->query($sql)->result();
	}
	
	public function registrarSubLinea()
	{
		if(!$this->comprobarSubLineaRegistro(trim($this->input->post('txtSubLinea'))))
		{
			return array('0',registroDuplicado);
		}
		
		$data=array
		(
			'nombre'		=> trim($this->input->post('txtSubLinea')),
			'idLinea'		=> trim($this->input->post('idLinea')),
		);
		
		$data	= procesarArreglo($data);

	    $this->db->insert('productos_sublineas',$data);
		
		$this->registrarBitacora('Registrar sublinea','Configuración - Líneas',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarSubLinea()
	{
		$data=array
		(
			'nombre'		=>$this->input->post('txtSubLinea'),
		);
		
		$data	= procesarArreglo($data);

		$this->db->where('idSubLinea',$this->input->post('txtIdSubLinea'));
	    $this->db->update('productos_sublineas',$data);
		
		$this->registrarBitacora('Editar sublinea','Configuración - Líneas',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}

	public function borrarSubLinea($idSubLinea)
	{
		$linea=$this->obtenerSubLinea($idSubLinea);
		
		$this->db->where('idSubLinea',$idSubLinea);
		$this->db->update('productos_sublineas',array('activo'=>'0'));
		
		if($linea!=null)
		{
			$this->registrarBitacora('Borrar sublinea','Configuración - Líneas',$linea->nombre); //Registrar bitácora
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	//PÓLIZAS
	public function obtenerConfiguracionPolizas()
	{
		$sql="select polizaIngresos, polizaEgresos, polizaDiario
		from configuracion
		where idLicencia='$this->idLicencia'";
		
		return $this->db->query($sql)->row();
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#--------------------------------------------------CATEGORÍAS----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function obtenerSubCategoriasCategorias()
	{
		$sql=" select a.*, b.nombre as categoria
		from produccion_materiales_categorias as b
		inner join produccion_materiales_subcategorias as a
		on a.idCategoria=b.idCategoria
		and b.activo='1'
		and a.activo='1' ";	
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCategorias()
	{
		$sql=" select a.*, (select count(b.idSubCategoria) from produccion_materiales_subcategorias as b where b.idCategoria=a.idCategoria and b.activo='1') as numeroSubCategorias
		from produccion_materiales_categorias as a
		where a.activo='1'
		order by a.nombre asc ";	
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerCategoria($idCategoria)
	{
		$sql=" select * from produccion_materiales_categorias
		where idCategoria='$idCategoria'
		and activo='1' ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function registrarCategoria()
	{
		$data=array
		(
			'nombre'		=> trim($this->input->post('txtCategoria')),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('produccion_materiales_categorias',$data);
		
		$this->registrarBitacora('Registrar categoría','Configuración - Categorías ',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarCategoria()
	{
		$data=array
		(
			'nombre'		=> trim($this->input->post('txtCategoria')),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idCategoria',$this->input->post('txtIdCategoria'));
		$this->db->update('produccion_materiales_categorias',$data);
		
		$this->registrarBitacora('Editar categoría','Configuración - Categorías ',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerDetallesCategoria($idCategoria)
	{
		$sql="select nombre
		from produccion_materiales_categorias
		where idCategoria='$idCategoria'";	
		
		$categoria	= $this->db->query($sql)->row();
		
		return $categoria!=null?$categoria->nombre:'';
	}
	
	public function borrarCategoria($idCategoria)
	{
		$this->registrarBitacora('Borrar categoría','Configuración - Categorías',$this->obtenerDetallesCategoria($idCategoria)); //Registrar bitácora
		
	    $this->db->where('idCategoria',$idCategoria);
		$this->db->update('produccion_materiales_categorias',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	//SUBCATEGORÍAS
	public function obtenerSubCategorias($idCategoria)
	{
		$sql=" select * from produccion_materiales_subcategorias
		where idCategoria='$idCategoria'
		and activo='1' ";	
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarSubCategoria()
	{
		$data=array
		(
			'nombre'		=> trim($this->input->post('txtSubCategoria')),
			'idCategoria'	=> trim($this->input->post('idCategoria')),
		);
		
		$data	= procesarArreglo($data);
		$this->db->insert('produccion_materiales_subcategorias',$data);
		
		$this->registrarBitacora('Registrar subcategoría','Configuración - Categorías - Subcategorías',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerSubCategoria($idSubCategoria)
	{
		$sql=" select * from produccion_materiales_subcategorias
		where idSubCategoria='$idSubCategoria'
		and activo='1' ";	
		
		return $this->db->query($sql)->row();
	}
	
	public function editarSubCategoria()
	{
		$data=array
		(
			'nombre'		=> trim($this->input->post('txtSubCategoria')),
		);
		
		$data	= procesarArreglo($data);
		$this->db->where('idSubCategoria',$this->input->post('txtIdSubCategoria'));
		$this->db->update('produccion_materiales_subcategorias',$data);
		
		$this->registrarBitacora('Editar subcategoría','Configuración - Subcategorías ',$data['nombre']); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function obtenerDetallesSubCategoria($idSubCategoria)
	{
		$sql=" select nombre
		from produccion_materiales_subcategorias
		where idSubCategoria='$idSubCategoria' ";	
		
		$subCategoria	= $this->db->query($sql)->row();
		
		return $subCategoria!=null?$subCategoria->nombre:'';
	}
	
	public function borrarSubCategoria($idSubCategoria)
	{
		$this->registrarBitacora('Borrar subcategoría','Configuración - Subcategorías',$this->obtenerDetallesSubCategoria($idSubCategoria)); //Registrar bitácora
		
	    $this->db->where('idSubCategoria',$idSubCategoria);
		$this->db->update('produccion_materiales_subcategorias',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}	
	
	public function obtenerLimiteVentas()
	{
		$sql="select limiteVentas from configuracion
		where idLicencia='$this->idLicencia'";	
		
		return $this->db->query($sql)->row()->limiteVentas;
	}
	
	public function actualizarLimiteVentas()
	{
		$data=array
		(
			'limiteVentas'		=>$this->input->post('limiteVentas'),
		);
		
		$this->db->where('idLicencia',$this->idLicencia);
		$this->db->update('configuracion',$data);

		#$this->registrarBitacora('Editar conversión','Configuración - Unidades - Conversiones',$data['nombre'].', '.$conversion[1]); //Registrar bitácora
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function autoCompletadoUsuarios($criterio)
	{
		$sql=" select idUsuario, usuario,
		usuario as value
		from usuarios
		where idUsuario>0
		and activo='1'
		and idRol!=3
		and idLicencia='$this->idLicencia'
		and (usuario like '$criterio%'
		or nombre like '$criterio%') 
		order by nombre asc
		limit 25 ";
		
		return $this->db->query($sql)->result_array();
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PARA CFDI 3.3
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function autoCompletadoUnidades($criterio)
	{
		$sql=" select idUnidad, clave, nombre,
		concat(clave, ', ', nombre) as value
		from fac_catalogos_unidades
		where idUnidad>0
		and (clave like '$criterio%'
		or nombre like '$criterio%') 
		order by nombre asc
		limit 25 ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function autoCompletadoProductoServicios($criterio)
	{
		$sql=" select idClave, clave, nombre,
		concat(clave, ', ', nombre) as value
		from fac_catalogos_claves_productos
		where idClave>0
		and (clave like '$criterio%'
		or nombre like '$criterio%') 
		order by nombre asc
		limit 25 ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function autoCompletadoMonedas($criterio)
	{
		$sql=" select idMoneda, codigo, nombre,
		concat(codigo, ', ', nombre) as value
		from fac_catalogos_monedas
		where idMoneda>0
		and (codigo like '$criterio%'
		or nombre like '$criterio%') 
		order by nombre asc
		limit 25 ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function autoCompletadoUuid($criterio)
	{
		$sql=" select idFactura, UUID, concat(serie,folio) as folio, total, empresa,
		concat('Cliente: ',empresa, ', Serie y folio:', serie,folio,', UUID: ',UUID) as value
		from facturas
		where idFactura>0
		and pendiente='0'
		and (concat(serie,folio) like '$criterio%'
		or empresa like '$criterio%'
		or UUID like '$criterio%') 
		order by folio desc
		limit 25 ";
		
		return $this->db->query($sql)->result_array();
	}
	
	public function obtenerImpuestosTasas()
	{    
		$sql=" select a.*, b.nombre as impuesto
		from fac_impuestos_tasas as a
		inner join fac_impuestos as b
		on a.idImpuesto=b.idImpuesto
		where a.idImpuestoTasa<3
		order by idImpuestoTasa ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerUsosCfdi()
	{    
		$sql=" select * from fac_catalogos_usocfdi
		order by idUso asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTipoRelaciones()
	{    
		$sql=" select * from fac_catalogos_relaciones
		order by idRelacion asc ";
		
		return $this->db->query($sql)->result();
	}
	
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
	
	public function obtenerProveedorNombre($empresa)
	{
		$sql=" select idProveedor
		from proveedores
		where empresa='$empresa' ";
		
		$proveedor	= $this->db->query($sql)->row();
		
		return $proveedor!=null?$proveedor->idProveedor:104;
	}
	
	public function obtenerMetodosPago()
	{
		$sql=" select * from fac_catalogos_metodos ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFormasPago()
	{
		$sql=" select * from fac_catalogos_formas ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerTipoNomina()
	{
		$sql=" select * from fac_catalogos_nomina ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerRegimenFiscal()
	{
		$sql=" select * from fac_catalogos_regimen_fiscal ";
		
		return $this->db->query($sql)->result();
	}
	
	
	#----------------------------------------------------------------------------------------------------------#
	#-------------------------------------------------PROGRAMAS------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function contarProgramas($criterio)
	{    
		$sql=" select count(a.idPrograma) as numero
		from clientes_programas as a
		where a.idPrograma>0 
		and activo='1' ";
		
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';

		$sql."= order by a.nombre asc ";

		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerProgramas($numero=0,$limite=0,$criterio='',$matricula=0)
	{    
		$sql=" select a.*,
		(select b.nombre from clientes_programas_periodos as b where b.idPeriodo=a.idPeriodo) as periodo,
		(select b.nombre from clientes_programas_grados as b where b.idGrado=a.idGrado) as grado
		from clientes_programas as a
		where a.idPrograma>0 
		and a.activo='1' ";
		
		$sql.=$matricula==1?" and (select count(c.idMatricula) from  sie_matriculas as c where c.idPrograma=a.idPrograma) <4 ":'';
		
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';

		$sql.=" order by a.nombre asc ";
		$sql .= $numero>0? " limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function obtenerProgramasCampanas($idCampana)
	{    
		$sql=" select a.*,
		(select count(b.idRelacion) from clientes_campanas_programas as b where b.idPrograma=a.idPrograma and b.idCampana='$idCampana') as activa
		from clientes_programas as a
		where a.idPrograma>0 
		and a.activo='1' ";

		$sql.=" order by a.nombre asc ";

		return $this->db->query($sql)->result();
	}
	
	public function obtenerProgramasEditar($idPrograma)
	{    
		$sql="select a.*,
		(select b.nombre from clientes_programas_periodos as b where b.idPeriodo=a.idPeriodo) as periodo
		
		from clientes_programas  as a
		where a.idPrograma='$idPrograma'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarProgramas($nombre)
	{
		$sql="select idPrograma
		from clientes_programas
		where nombre='$nombre'
		and activo='1' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function obtenerProgramasGrado($idGrado)
	{    
		$sql="select a.*
		from clientes_programas  as a
		where a.idGrado='$idGrado'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPeriodosProgramas()
	{    
		$sql="select * from clientes_programas_periodos ";
		
		return $this->db->query($sql)->result();
	}
	
	public function registrarProgramas()
	{
		if(!$this->comprobarProgramas($this->input->post('nombre')))
		{
			return array('0',registroDuplicado); 
		}

		$data=array
		(
			'nombre'					=> $this->input->post('nombre'),
			'cantidadInscripcion'		=> $this->input->post('cantidadInscripcion'),
			'cantidadColegiatura'		=> $this->input->post('cantidadColegiatura'),
			'cantidadReinscripcion'		=> $this->input->post('cantidadReinscripcion'),
			'diaPago'					=> $this->input->post('diaPago'),
			'idPeriodo'					=> $this->input->post('idPeriodo'),
			'idGrado'					=> $this->input->post('idGrado'),
			'editado'					=> '1',
		);

	    $this->db->insert('clientes_programas',$data);

		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarProgramas()
	{
		$data=array
		(
			'nombre'					=> $this->input->post('nombre'),
			'cantidadInscripcion'		=> $this->input->post('cantidadInscripcion'),
			'cantidadColegiatura'		=> $this->input->post('cantidadColegiatura'),
			'cantidadReinscripcion'		=> $this->input->post('cantidadReinscripcion'),
			
			
			'diaPago'					=> $this->input->post('diaPago'),
			'idPeriodo'					=> $this->input->post('idPeriodo'),
			'idGrado'					=> $this->input->post('idGrado'),
			'editado'					=> '1',
		);
		
		$this->db->where('idPrograma',$this->input->post('idPrograma'));
	    $this->db->update('clientes_programas',$data);
		
		//EDITAR EL PROGRAMA PARA LOS ALUMNOS A LOS QUE ESTE ASOCIADO
		if($this->input->post('editarAlumnos')=='1')
		{
			$this->editarProgramaAlumnos($data,$this->input->post('idPrograma')); 
		}
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function borrarProgramas($idPrograma)
	{
		$this->db->where('idPrograma',$idPrograma);
		$this->db->update('clientes_programas',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function editarProgramaAlumnos($programa,$idPrograma)
	{
		$data=array
		(
			'cantidadInscripcion'		=> $programa['cantidadInscripcion'],
			'cantidadColegiatura'		=> $programa['cantidadColegiatura'],
			'cantidadReinscripcion'		=> $programa['cantidadReinscripcion'],
		);
		
		$this->db->where('idPrograma',$idPrograma);
	    $this->db->update('clientes_academicos',$data);
	}
	
	public function editarProgramasComisiones()
	{
		$data=array
		(
			'importe'		=> $this->input->post('importe'),
			'comision'		=> $this->input->post('comision'),
		);
		
		$this->db->where('idPrograma',$this->input->post('idPrograma'));
	    $this->db->update('clientes_programas',$data);

		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerProgramasCampana($idCampana)
	{    
		$sql=" select a.idPrograma, a.nombre, a.idGrado
		from clientes_programas as a
		inner join clientes_campanas_programas as b
		on a.idPrograma=b.idPrograma 
		where b.idCampana='$idCampana'
		and a.activo='1' ";
		
		return $this->db->query($sql)->result();
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#-------------------------------------------------CAMPAÑAS-------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function contarCampanas($criterio)
	{    
		$sql=" select count(a.idCampana) as numero
		from clientes_campanas as a
		where a.idCampana>0 
		and activa='1' ";
		
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';

		$sql."= order by a.nombre asc ";

		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerCampanas($numero=0,$limite=0,$criterio='')
	{    
		$sql=" select a.*
		from clientes_campanas as a
		where a.idCampana>0 
		and activa='1' ";
		
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';

		$sql.=" order by a.nombre asc ";
		$sql .= $numero>0? " limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function obtenerCampanasEditar($idCampana)
	{    
		$sql="select * from clientes_campanas 
		where idCampana='$idCampana'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarCampanas($nombre)
	{
		$sql="select idCampana
		from clientes_campanas
		where nombre='$nombre'
		and activa='1' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarCampanas()
	{
		if(!$this->comprobarCampanas($this->input->post('txtCampana')))
		{
			return array('0',registroDuplicado); 
		}
		
		$this->db->trans_start();
		
		$data=array
		(
			'nombre'				=> $this->input->post('txtCampana'),
			'fechaInicial'			=> $this->input->post('txtFechaInicialCampana'),
			'fechaFinal'			=> $this->input->post('txtFechaFinalCampana'),
			'atrasos'				=> $this->input->post('chkAtrasos')=='1'?'1':'0',
			'idUsuario'				=> $this->_user_id,
			'fechaRegistro'			=> $this->_fecha_actual,
		);

	    $this->db->insert('clientes_campanas',$data);
		$idCampana	=$this->db->insert_id();
		
		$this->asociarProgramasCampana($idCampana);
		
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
	
	public function asociarProgramasCampana($idCampana)
	{
		$numero		= $this->input->post('txtNumeroProgramas');
		
		for($i=1;$i<=$numero;$i++)
		{
			$idPrograma		= $this->input->post('chkPrograma'.$i);
			
			if($idPrograma>0)
			{
				$data=array
				(
					'idCampana'			=> $idCampana,
					'idPrograma'		=> $idPrograma,
				);
		
				$this->db->insert('clientes_campanas_programas',$data);
			}
		}
	}
	
	public function editarCampanas()
	{
		$this->db->trans_start();
		
		$idCampana	= $this->input->post('txtIdCampana');
		
		$data=array
		(
			'nombre'				=> $this->input->post('txtCampana'),
			'fechaInicial'			=> $this->input->post('txtFechaInicialCampana'),
			'fechaFinal'			=> $this->input->post('txtFechaFinalCampana'),
			'atrasos'				=> $this->input->post('chkAtrasos')=='1'?'1':'0',
		);
		
		$this->db->where('idCampana',$idCampana);
	    $this->db->update('clientes_campanas',$data);
		
		$this->db->where('idCampana',$idCampana);
	    $this->db->delete('clientes_campanas_programas');

		$this->asociarProgramasCampana($idCampana);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->db->trans_complete();
			
			return "0";
		}
		else
		{
			$this->db->trans_commit(); 
			$this->db->trans_complete();
			
			return "1";
		}
	}

	public function borrarCampanas($idCampana)
	{
		$this->db->where('idCampana',$idCampana);
		$this->db->update('clientes_campanas',array('activa'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#-------------------------------------------------PROMOTORES------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function contarPromotores($criterio,$idUsuario=0,$idCampana=0)
	{    
		$sql=" select count(a.idUsuario) as numero
		from clientes_campanas_metas as a
		inner join usuarios as c
		on c.idUsuario=a.idUsuario
		where a.idUsuario>0 
		and a.activo='1' ";
		
		$sql.=strlen($criterio)>0?" and concat(c.nombre,' ',c.apellidoPaterno,' ',c.apellidoMaterno) like '%$criterio%' ":'';
		$sql.=$idUsuario>0?" and c.idUsuario='$idUsuario' ":'';
		$sql.=$idCampana>0?" and a.idCampana='$idCampana' ":'';

		$sql."= order by a.nombre asc ";

		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerPromotores($numero=0,$limite=0,$criterio='',$idUsuario=0,$idCampana=0)
	{    
		$sql=" select a.*,
		(select b.nombre from clientes_campanas as b where b.idCampana=a.idCampana) as campana,
		concat(c.nombre,' ',c.apellidoPaterno,' ',c.apellidoMaterno) as promotor,

		(select count(b.idCliente) from clientes as b where b.idPromotor=a.idUsuario and b.idCampana=a.idCampana) as totalProspectos,		
		
		(select count(b.idCliente) from clientes as b where b.idPromotor=a.idUsuario and b.idCampana=a.idCampana and b.idZona!=2 and  b.idZona!=8) as numeroProspectos,
		
		(select count(b.idCliente) from clientes as b where b.idPromotor=a.idUsuario and b.idCampana=a.idCampana and (b.idZona=2 or b.idZona=8)) as bajas,
		
		(select count(b.idCliente) from clientes as b where b.idPromotor=a.idUsuario and b.idCampana=a.idCampana and b.prospecto='0') as numeroInscritos,
		(SELECT COUNT(DISTINCT(b.idCliente)) FROM seguimiento AS b INNER JOIN clientes AS c ON c.idCliente=b.idCliente WHERE c.idPromotor=a.idUsuario AND c.idCampana=a.idCampana LIMIT 1) AS atendidos

		
		
		from clientes_campanas_metas as a
		inner join usuarios as c
		on c.idUsuario=a.idUsuario
		where a.idUsuario>0 
		and a.activo='1' ";
		
		$sql.=strlen($criterio)>0?" and concat(c.nombre,' ',c.apellidoPaterno,' ',c.apellidoMaterno) like '%$criterio%' ":'';
		$sql.=$idUsuario>0?" and c.idUsuario='$idUsuario' ":'';
		$sql.=$idCampana>0?" and a.idCampana='$idCampana' ":'';

		$sql."= order by a.nombre asc ";
		$sql .= $numero>0? " limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function obtenerPromotoresEditar($idMeta)
	{    
		$sql="select * from clientes_campanas_metas 
		where idMeta='$idMeta'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarPromotores($idUsuario,$idCampana)
	{
		$sql=" select idUsuario
		from clientes_campanas_metas
		where idUsuario='$idUsuario'
		and idCampana='$idCampana'
		and activo='1' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function obtenerPromotoresAsignados($idPromotor,$idCampana)
	{
		$sql=" select count(idCliente) as numero
		from clientes
		where idPromotor='$idPromotor'
		and idCampana='$idCampana'
		and activo='1'
		and idZona!=2 ";
		
		return $this->db->query($sql)->row()->numero;
	}
	
	public function registrarPromotores()
	{
		if(!$this->comprobarPromotores($this->input->post('idUsuario'),$this->input->post('idCampana')))
		{
			return array('0','Ya se ha asignado la campaña'); 
		}
		
		$data=array
		(
			'idUsuario'				=> $this->input->post('idUsuario'),
			'idCampana'				=> $this->input->post('idCampana'),
			'meta'					=> $this->input->post('meta'),
			'fechaRegistro'			=> $this->_fecha_actual,
		);

	    $this->db->insert('clientes_campanas_metas',$data);
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarPromotores()
	{
		$data=array
		(
			'meta'					=> $this->input->post('meta'),
		);
		
		$this->db->where('idMeta',$this->input->post('idMeta'));
	    $this->db->update('clientes_campanas_metas',$data);

		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function borrarPromotores($idMeta)
	{
		$this->db->where('idMeta',$idMeta);
		$this->db->update('clientes_campanas_metas',array('activo'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#------------------------------------------------- CAUSAS -------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function contarCausas($criterio,$tipo='0')
	{    
		$sql=" select count(a.idCausa) as numero
		from clientes_bajas_causas as a
		where a.idCausa>0 
		and activa='1'
		and tipo='$tipo' ";
		
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';

		$sql."= order by a.nombre asc ";

		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerCausas($numero=0,$limite=0,$criterio='',$idCausa=0,$tipo='0')
	{    
		$sql=" select a.*
		from clientes_bajas_causas as a
		where a.idCausa>0 
		and activa='1'
		and tipo='$tipo' ";
		
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';
		
		$sql.=$idCausa>0?" and a.idCausa='$idCausa' ":'';

		$sql."= order by a.nombre asc ";
		$sql .= $numero>0? " limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function obtenerCausasEditar($idCausa)
	{    
		$sql="select * from clientes_bajas_causas 
		where idCausa='$idCausa'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarCausas($nombre)
	{
		$sql="select idCausa
		from clientes_bajas_causas
		where nombre='$nombre'
		and activa='1' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarCausas()
	{
		if(!$this->comprobarCausas($this->input->post('nombre')))
		{
			return array('0',registroDuplicado); 
		}
		
		$data=array
		(
			'nombre'				=> $this->input->post('nombre'),
			'tipo'					=> $this->input->post('tipo'),
			'fechaRegistro'			=> $this->_fecha_actual,
		);

	    $this->db->insert('clientes_bajas_causas',$data);
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}
	
	public function editarCausas()
	{
		$data=array
		(
			'nombre'				=> $this->input->post('nombre'),
		);
		
		$this->db->where('idCausa',$this->input->post('idCausa'));
	    $this->db->update('clientes_bajas_causas',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}

	public function borrarCausas($idCausa)
	{
		$this->db->where('idCausa',$idCausa);
		$this->db->update('clientes_bajas_causas',array('activa'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerNocuali($numero=0,$limite=0,$criterio='',$idCausa=0,$tipo='0')
	{    
		$sql=" select a.*
		from clientes_nocuali_causas as a
		where a.idCausa>0 
		and activo='1'";
		
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';
		
		$sql.=$idCausa>0?" and a.idCausa='$idCausa' ":'';

		$sql."= order by a.nombre asc ";
		$sql .= $numero>0? " limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#-------------------------------------------------COMISIONES-----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function contarComisiones($criterio,$idPromotor=0,$idCampana=0,$idPrograma=0,$permiso=0)
	{    
		$sql=" select count(a.idVenta) as numero
		from clientes_programas_ventas as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		where a.idCliente>0 
		and b.activo='1' ";
		
		$sql.=strlen($criterio)>0?" and (b.empresa like '$criterio%' or b.razonSocial like '$criterio%' or concat(b.nombre,' ', b.paterno, ' ', b.materno) like '%$criterio%' or b.email like '$criterio%' or b.telefono like '%$criterio%' or b.movil like '%$criterio%') ":'';
		$sql.=$idPromotor>0?" and a.idPromotor='$idPromotor' ":'';
		$sql.=$idCampana>0?" and b.idCampana='$idCampana' ":'';
		$sql.=$idPrograma>0?" and a.idPrograma='$idPrograma' ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($permiso==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}
		

		$sql."= order by a.nombre asc ";

		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerComisiones($numero=0,$limite=0,$criterio='',$idPromotor=0,$idCampana=0,$idPrograma=0,$permiso=0)
	{    
		$sql=" select a.*, d.matricula,
		b.empresa, concat(b.nombre, ' ', b.paterno,' ',b.materno) as alumno, b.ultimaConexion,
		(select c.nombre from clientes_campanas as c where b.idCampana=c.idCampana) as campana,
		(select c.nombre from clientes_programas as c where a.idPrograma=c.idPrograma) as programa,
		(select concat(c.nombre, ' ', c.apellidoPaterno,' ',c.apellidoMaterno) from usuarios as c where a.idPromotor=c.idUsuario) as promotor,
		
		(select count(c.idIngreso) from catalogos_ingresos as c where b.idCliente=c.idCliente and c.idForma!=4) as numeroPagos
		
		
		from clientes_programas_ventas as a
		inner join clientes as b
		on a.idCliente=b.idCliente
		
		inner join clientes_academicos as d
		on b.idCliente=d.idCliente
		
		where a.idCliente>0 
		and b.activo='1' ";
		
		$sql.=strlen($criterio)>0?" and (b.empresa like '$criterio%' or b.razonSocial like '$criterio%' or concat(b.nombre,' ', b.paterno, ' ', b.materno) like '%$criterio%' or b.email like '$criterio%' or b.telefono like '%$criterio%' or b.movil like '%$criterio%') ":'';
		$sql.=$idPromotor>0?" and a.idPromotor='$idPromotor' ":'';
		$sql.=$idCampana>0?" and b.idCampana='$idCampana' ":'';
		$sql.=$idPrograma>0?" and a.idPrograma='$idPrograma' ":'';
		
		if($this->idRol==1)
		{
			$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
		}
		else
		{
			if($permiso==0)
			{
				$sql.=" and  a.idPromotor='$this->_user_id' ";
			}
			else
			{
				$sql.=$idPromotor!=0?" and  a.idPromotor='$idPromotor' ":'';
			}
		}

		$sql."= order by a.fecha desc, promotor asc ";
		$sql .= $numero>0? " limit $limite,$numero ":'';
		
		#echo $sql;

		return $this->db->query($sql)->result();
	}
	

	#----------------------------------------------------------------------------------------------------------#
	#-------------------------------------------------PERIODOS------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	public function contarPeriodos($criterio)
	{    
		$sql=" select count(a.idPeriodo) as numero
		from clientes_periodos as a
		where a.idPeriodo>0 
		and activa='1' ";
		
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';

		$sql."= order by a.nombre asc ";

		return $this->db->query($sql)->row()->numero;
	}
	
	public function obtenerPeriodos($numero=0,$limite=0,$criterio='')
	{    
		$sql=" select a.*
		from clientes_periodos as a
		where a.idPeriodo>0 
		and activa='1' ";
		
		$sql.=strlen($criterio)>0?" and a.nombre like '%$criterio%' ":'';

		$sql.=" order by a.nombre asc ";
		$sql .= $numero>0? " limit $limite,$numero ":'';

		return $this->db->query($sql)->result();
	}
	
	public function obtenerPeriodosEditar($idPeriodo)
	{    
		$sql="select * from clientes_periodos 
		where idPeriodo='$idPeriodo'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarPeriodos($nombre)
	{
		$sql="select idPeriodo
		from clientes_periodos
		where nombre='$nombre'
		and activa='1' ";
		
		return $this->db->query($sql)->num_rows()>0?false:true;
	}
	
	public function registrarPeriodos()
	{
		if(!$this->comprobarPeriodos($this->input->post('txtPeriodo')))
		{
			return array('0',registroDuplicado); 
		}

		$data=array
		(
			'nombre'				=> $this->input->post('txtPeriodo'),
			'fechaInicial'			=> $this->input->post('txtFechaInicialPeriodo'),
			'fechaFinal'			=> $this->input->post('txtFechaFinalPeriodo'),
			'idUsuario'				=> $this->_user_id,
			'fechaRegistro'			=> $this->_fecha_actual,
		);

	    $this->db->insert('clientes_periodos',$data);
		$idPeriodo	=$this->db->insert_id();
		
		return $this->db->affected_rows()>=1?array('1',registroCorrecto):array('0',errorRegistro); 
	}

	public function editarPeriodos()
	{
		$idPeriodo	= $this->input->post('txtIdPeriodo');
		
		$data=array
		(
			'nombre'				=> $this->input->post('txtPeriodo'),
			'fechaInicial'			=> $this->input->post('txtFechaInicialPeriodo'),
			'fechaFinal'			=> $this->input->post('txtFechaFinalPeriodo'),
		);
		
		$this->db->where('idPeriodo',$idPeriodo);
	    $this->db->update('clientes_periodos',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 

	}

	public function borrarPeriodos($idPeriodo)
	{
		$this->db->where('idPeriodo',$idPeriodo);
		$this->db->update('clientes_periodos',array('activa'=>'0'));
		
		return $this->db->affected_rows()>=1?"1":"0"; 
	}
	
	public function obtenerUsuariosVentas()
	{
		$sql=" select idUsuario, usuario, vendedor	
		from usuarios as a
		where a.idUsuario>0
		and a.activo='1'
		and a.idRol!=3
		and length(a.vendedor)>0 ";
		
		$sql.=" and (select count(b.idRelacion) from usuarios_licencias as b where b.idUsuario=a.idUsuario and b.idLicencia='$this->idLicencia') > 0 ";
		$sql.=" order by a.nombre asc "; 
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerMetodos()
	{
		$sql="select * from seguimiento_metodos where activo='1'";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerEmbudos($numero=0)
	{
		$sql="select a.*,
		(select count(b.idDetalleEmbudo) from seguimiento_embudos_detalles as b where b.idEmbudo=a.idEmbudo) as detalles 
		from seguimiento_embudos as a
		where a.activo='1'";
	   
	   $sql.=$numero>0?" and a.idEmbudo>1 ":"";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerDetallesEmbudo($idEmbudo)
	{
		$sql="select * from seguimiento_embudos_detalles
		where idEmbudo='$idEmbudo'";
		
		return $this->db->query($sql)->result();
	}
	
	//CATÁLOGO DE ÁREAS Y CONCEPTOS
	
	public function obteneraAreas()
	{
		$sql="select * from seguimiento_areas";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerConceptosArea($idArea)
	{
		$sql="select * from seguimiento_areas_conceptos 
		where idArea='$idArea'";
		
		return $this->db->query($sql)->result();
	}
	
	public function revisarCodigoUsuario()
	{
		$idUsuario			= $this->input->post('idUsuario');
		$claveCancelacion	= $this->input->post('claveCancelacion');
		
		$sql="select idUsuario
		from usuarios
		where idUsuario='$idUsuario'
		and claveCancelacion='$claveCancelacion'";
		
		return $this->db->query($sql)->row()!=null?array('1','El usuario esta registrado'):array('0','Sin registro de usuario');
	}
	
	public function revisarAccesoFacturacion()
	{
		$sql="select accesoFacturacion 
		from configuracion 
		where idLicencia='$this->idLicencia'";
		
		return array($this->db->query($sql)->row()->accesoFacturacion);
	}
	
	public function actualizarAccesoFacturacion($accesoFacturacion)
	{
		$data=array
		(
			'accesoFacturacion'				=> $accesoFacturacion,
		);
		
		$this->db->where('idLicencia',$this->idLicencia);
	    $this->db->update('configuracion',$data);
		
		return $this->db->affected_rows()>=1?"1":"0"; 

	}
	
	public function obtenerPac($idPac=0)
	{
		$sql="select * 
		from configuracion_pacs 
		where idPac='$idPac'";
		
		return $this->db->query($sql)->row();
	}
	
	public function comprobarEstacionLicencia($idLicencia=0,$idEstacion=0)
	{
		$sql=" select idEstacion 
		from configuracion_estaciones 
		where idEstacion='$idEstacion'
		and idLicencia='$idLicencia' ";
		
		$registro	= $this->db->query($sql)->row();
		
		return $registro!=null?true:false;
	}
	
	public function obtenerMotivosCancelacion()
	{    
		$sql=" select * from fac_catalogos_cancelaciones ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerPeriodicidad()
	{    
		$sql=" select * from fac_catalogos_global_periodicidad
		order by idPeriodicidad asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerMeses()
	{    
		$sql=" select * from fac_catalogos_meses
		order by idMes asc ";
		
		return $this->db->query($sql)->result();
	}
	
	public function obtenerFoliosComprados()
	{
		$sql="select cantidad 
		from configuracion_folios
		where idFolios=1";
		
		$cantidad	= $this->db->query($sql)->row();
		
		return $cantidad!=null?$cantidad->cantidad:0;
	}
	
	public function obtenerFoliosConsumidosTotal()
	{
		$sql=" select count(idFactura) as numero
		from facturas
		where pendiente='0' ";
		
		return $this->db->query($sql)->row()->numero;
	}
}
?>
