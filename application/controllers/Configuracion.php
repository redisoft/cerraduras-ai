<?php
class Configuracion extends CI_Controller
{
    private $_template;
    protected $_fechaActual;
    protected $_iduser;
    protected $_csstyle;
	protected $base;
	protected $cuota;
	protected $idLicencia;

    function __construct()
	{
		parent::__construct();

		if( ! $this->redux_auth->logged_in() )
		{
			redirect(base_url().'login');
		}
	
		$this->config->load('style', TRUE);
		$this->config->load('js',TRUE);
		
		$this->_fechaActual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_iduser		 	= $this->session->userdata('id');
		$this->_csstyle 		= $this->config->item('style');
		$this->_jss				= $this->config->item('js');
		$this->idLicencia		= $this->session->userdata('idLicencia');
		
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("modeloclientes","modeloclientes");
		$this->load->model("proveedores_model","modeloproveedores");
		$this->load->model("inventario_model","modeloinventario");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("tiendas_modelo","tiendas");
		$this->load->model("catalogos_modelo","catalogos");
		$this->load->model("crm_modelo","crm");
		$this->load->model("bancos_model","bancos");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
    }

 	public function index()
	{ 
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];  
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones($this->session->userdata('idLicencia'));
		$data['cuatoBase']		= $this->configuracion->obtenerCuotaBase();
		$data['archivos']		= calcularTamanoDisco(carpetaMedia);
		$data['imagenes']		= calcularTamanoDisco(carpetaProductos);
		$data["breadcumb"]		= 'Sistema';

		$this->load->view("configuracion/index",$data);
		$this->load->view("pie",$Data);
	}

	public function guardar()
	{
		if(!empty ($_POST))
		{
			$logo 				= $_FILES['userfile']['name'];
			$idConfiguracion	= $this->input->post('id');
			$uploaddir  		= "img/logos/";
			$uploadfile 		= $uploaddir . basename($idConfiguracion."_".$logo);
			
			move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
			
			if(strlen($logo)<2)
			{
				$logo			= "nada";
			}
			
			if($this->configuracion->guardar($logo) != NULL)
			{
				$this->session->set_flashdata('message', 
				array('messageType' => 'success','Message' => 'Los datos se han almacenado correctamente.'));
			}
			else
			{
				$this->session->set_flashdata('message', 
				array('messageType' => 'success','Message' => 'Los datos se han almacenado correctamente.'));
			}
			
			redirect('configuracion','refresh');
		}
		else 
		{
			redirect('configuracion','refresh');
		}
	}
	
	
	
	#----------------------------------------------------------------------------#
	#						     AGREGAR UN NUEVO COLOR							 #
	#----------------------------------------------------------------------------#
	public function agregarColor()
	{
		if (!empty($_POST))
		{
			$color=$this->configuracion->colorAgregar();
			print($color);
		}
	}
	
	#----------------------------------------------------------------------------#
	#						         BORRAR UN COLOR							 #
	#----------------------------------------------------------------------------#
	public function borrarColor($idColor)
	{
			$color=$this->configuracion->colorBorrar($idColor);
			redirect('configuracion/colores','refresh');
	}
	
	#----------------------------------------------------------------------------#
	#						    AGREGAR UNA NUEVA UNIDAD						 #
	#----------------------------------------------------------------------------#
	public function registrarUnidad()
	{
		if (!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarUnidad());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerUnidad()
	{
		$idUnidad	=$this->input->post('idUnidad');	
		$unidad		=$this->configuracion->obtenerUnidad($idUnidad);
		
		echo'
		<table class="admintable" width="100%">
			<tr>
				<td class="key">Nombre:</td>
				<td>
					<input style="width:300px" type="text" class="cajas" id="txtUnidad" value="'.$unidad->descripcion.'" />
					<input type="hidden" class="cajas" id="txtIdUnidad" value="'.$idUnidad.'" />
				</td>
			</tr>
		</table>';
	}
	
	public function editarUnidad()
	{
		if (!empty($_POST))
		{
			$unidad=$this->configuracion->editarUnidad();
			
			$unidad=="1"?
				$this->session->set_userdata('notificacion','La unidad se ha editado correctamente'):'';
				
			echo $unidad;
		}
	}
	
	#----------------------------------------------------------------------------#
	#						      BORAR UNA UNIDAD								 #
	#----------------------------------------------------------------------------#
	public function borrarUnidad($idUnidad)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$unidad	=$this->configuracion->unidadBorrar($idUnidad);
		
		$unidad=="1"?
				$this->session->set_userdata('notificacion','La unidad se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar la unidad porque esta asociada a materia prima o conversiones');
				
		redirect('configuracion/unidades','refresh');
	}
	
	
	#----------------------------------------------------------------------------#
	#						    AGREGAR UNA NUEVA CAJA							 #
	#----------------------------------------------------------------------------#
	public function agregarCaja()
	{
		if (!empty($_POST))
		{
			$caja=$this->configuracion->cajaAgregar();
			print($caja);
		}
	}
	
	#----------------------------------------------------------------------------#
	#						      BORRAR UNA CAJA								 #
	#----------------------------------------------------------------------------#
	public function borrarCaja($idCaja)
	{
		$caja=$this->configuracion->cajaBorrar($idCaja);
		redirect('configuracion/cajas','refresh');
	}
	
	#----------------------------------------------------------------------------#
	#						    ADMINISTRACION DE ROLES							 #
	#----------------------------------------------------------------------------#
	
	public function roles()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];   
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;    
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['roles']			= $this->configuracion->obtenerRoles();
		$data['permisos']		= $this->configuracion->obtenerPermisos();
		$data["breadcumb"]		= 'Roles';
		
		$this->load->view("configuracion/roles/roles",$data);
		$this->load->view("pie",$Data);
	}
	
	public function formularioRoles()
	{
		$data['permisos']	= $this->configuracion->obtenerPermisosOrdenado();
		
		$this->load->view("configuracion/roles/formularioRoles",$data);
	}
	
	public function registrarRol()
	{
		if (!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarRol());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarRol()
	{
		if (!empty($_POST))
		{
			echo $this->configuracion->editarRol();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarRol($idRol)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$roles=$this->configuracion->borrarRol($idRol);
		
		$roles=="1"?
				$this->session->set_userdata('notificacion','El rol se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar el rol, se encuentra asociado a usuarios');
				
		redirect('configuracion/roles','refresh');
	}
	
	public function obtenerRol()
	{
		if (!empty($_POST))
		{
			$idRol				= $this->input->post('idRol');
			
			$data['rol']		= $this->configuracion->obtenerRol($idRol);
			$data['permisos']	= $this->configuracion->obtenerPermisosOrdenado();
			$data['idRol']		= $idRol;
			
			$this->load->view('configuracion/roles/obtenerRol',$data);
		}
	}

	public function adduser()
	{
		$Data['title']= "Panel de Administración";
		$Data['cassadmin']=$this->_csstyle["cassadmin"];
		$Data['csmenu']=$this->_csstyle["csmenu"];
		$Data['csvalidate']=$this->_csstyle["csvalidate"];
		$Data['csui']=$this->_csstyle["csui"];
		$Data['Jry']=$this->_jss['jquery'];
		$Data['Jqui']=$this->_jss['jqueryui'];
		$Data['jvalidate']=$this->_jss['jvalidate'];
		$Data['nameusuario']=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']=$this->_fechaActual;
		$Data['permisos']=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$data['Configuraciones']=$this->configuracion->getAllVariables();
		$data['roles']=$this->configuracion->obtenerRoles();

		$this->load->view("configuracion/adduser",$data);
		$this->load->view("pie",$Data);
	}
	
	public function registrarUsuario()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarUsuario());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarUsuario()
	{
		if(!empty ($_POST))
		{
			echo $this->configuracion->editarUsuario();
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerUsuario()
	{
		$idUsuario			= $this->input->post('idUsuario');
		
		$data['roles']		= $this->configuracion->obtenerRoles();
		$data['usuario']	= $this->configuracion->obtenerUsuario($idUsuario);
		$data['licencias']	= $this->configuracion->obtenerLicenciasActivas();

		$this->load->view("configuracion/usuarios/editarUsuario",$data);
	}
	
	public function formularioUsuarios()
	{
		$data['roles']		= $this->configuracion->obtenerRoles();
		$data['tiendas']	= $this->tiendas->obtenerTiendasUsuario();
		$data['licencias']	= $this->configuracion->obtenerLicenciasActivas();

		$this->load->view("configuracion/usuarios/agregarUsuario",$data);
	}
	
	public function borrarUsuario()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->borrarUsuario($this->input->post('idUsuario')));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}	
	
	public function reactivarUsuario()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->reactivarUsuario($this->input->post('idUsuario')));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}	
	
	/*public function borrarUsuario($idUsuario)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$usuario=$this->configuracion->borrarUsuario($idUsuario);
		
		$usuario=="1"?
				$this->session->set_userdata('notificacion','El usuario se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar el usuario porque esta asociado a diversos modulos del sistema');
		
		redirect('configuracion/listauser','refresh');
	}	*/

	public function listauser()
	{
		if($this->idLicencia!=1)
		{
			redirect('configuracion','refresh');
		}
		
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];   
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;    
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		#$data['usuarios']		= $this->configuracion->obtenerUsuarios();//getAllMotivos
		$data["breadcumb"]		= 'Usuarios';
		
		$this->load->view("configuracion/usuarios/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerUsuarios($limite=0)
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		$criterio				= $this->input->post('criterio');
		$idRol					= $this->input->post('idRol');

		$Pag["base_url"]		= base_url()."configuracion/obtenerUsuarios/";
		$Pag["total_rows"]		= $this->configuracion->contarUsuarios($criterio,$idRol);//Total de Registros
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		$data['usuarios']		= $this->configuracion->obtenerUsuarios($Pag["per_page"],$limite,$criterio,$idRol);
		$data['roles']			= $this->configuracion->obtenerRoles();
		$data['limite']			= $limite+1;
		$data['idRol']			= $idRol;
		
		$this->load->view('configuracion/usuarios/obtenerUsuarios',$data);
	}

	public function colores()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];   
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		////$Data['csuidemo']=$this->_csstyle["csuidemo"];
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		//$Data['Jquical']=$this->_jss['jquerycal'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;    
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		$Data['Categoria']=$this->uri->segment(1);
		$Data['colores']=$this->configuracion->obtenerColores();//getAllMotivos
		
		$this->load->view("configuracion/colores",$Data);
		$this->load->view("pie",$Data);
	}
	
	
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------TIENDAS------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function tiendas()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			=$this->_csstyle["cassadmin"];
		$Data['csmenu']				=$this->_csstyle["csmenu"];   
		$Data['csvalidate']			=$this->_csstyle["csvalidate"];
		$Data['csui']				=$this->_csstyle["csui"];
		$Data['Jry']				=$this->_jss['jquery'];
		$Data['Jqui']				=$this->_jss['jqueryui'];
		$Data['jvalidate']			=$this->_jss['jvalidate'];
		$Data['nameusuario']		=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		=$this->_fechaActual;    
		$Data['permisos']			=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['tiendas']=$this->configuracion->obtenerTiendas();//getAllMotivos
		
		$this->load->view("configuracion/tiendas",$data);
		$this->load->view("pie",$Data);
	}
	
	public function agregarTienda()
	{
		if(!empty ($_POST))
		{
			$tienda=$this->configuracion->agregarTienda();
			echo $tienda;
		}
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------PROCESO------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function procesos()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			=$this->_csstyle["cassadmin"];
		$Data['csmenu']				=$this->_csstyle["csmenu"];   
		$Data['csvalidate']			=$this->_csstyle["csvalidate"];
		$Data['csui']				=$this->_csstyle["csui"];
		$Data['Jry']				=$this->_jss['jquery'];
		$Data['Jqui']				=$this->_jss['jqueryui'];
		$Data['jvalidate']			=$this->_jss['jvalidate'];
		$Data['nameusuario']		=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		=$this->_fechaActual;    
		$Data['permisos']			=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['procesos']=$this->configuracion->obtenerProcesos();
		$data["breadcumb"]		= 'Procesos';
		
		$this->load->view("configuracion/procesos",$data);
		$this->load->view("pie",$Data);
	}
	
	public function agregarProceso()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$proceso=$this->configuracion->agregarProceso();
			
			$proceso[0]=="1"?
				$this->session->set_userdata('notificacion','El proceso se registro correctamente'):'';
				
			echo json_encode($proceso);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerProceso()
	{
		if(!empty ($_POST))
		{
			$proceso	=$this->configuracion->obtenerProceso($this->input->post('idProceso'));

			echo '
			<table class="admintable" width="100%" >
				<tr>
					<td class="key">Nombre: </td>
					<td>
						<input type="text" class="cajas" id="txtNombreProcesoEditar" value="'.$proceso->nombre.'"  style="width:250px"/>
						<input type="hidden" id="txtIdProceso" value="'.$proceso->idProceso.'" />
					</td>
				</tr>
			</table>';
		}
	}
	
	public function editarProceso()
	{
		if(!empty ($_POST))
		{
			$proceso=$this->configuracion->editarProceso();
			
			$proceso=="1"?
				$this->session->set_userdata('notificacion','El proceso se editado correctamente'):'';
				
			echo $proceso;
		}
	}
	
	public function borrarProceso($idProceso)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$proceso=$this->configuracion->borrarProceso($idProceso);
		
		$proceso=="1"?
				$this->session->set_userdata('notificacion','El proceso se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar el proceso porque esta asociado a ordenes de producción');
		
		redirect('configuracion/procesos','refresh');
	}

	#----------------------------------------------------------------------------------------------------------#
	
	public function zonas()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			= $this->_csstyle["cassadmin"];
		$Data['csmenu']				= $this->_csstyle["csmenu"];   
		$Data['csvalidate']			= $this->_csstyle["csvalidate"];
		$Data['csui']				= $this->_csstyle["csui"];
		$Data['Jry']				= $this->_jss['jquery'];
		$Data['Jqui']				= $this->_jss['jqueryui'];
		$Data['nameusuario']		= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		= $this->_fechaActual;    
		$Data['permisos']			= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			= 'configuracion'; 
		$Data['mostrarMenu']		= true; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= 'Tipo cliente';

		$this->load->view("configuracion/zonas/zonas",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoZonas()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		$data['mostrarMenu']	= false; 
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}

		$this->load->view("configuracion/zonas/zonas",$data);
	}
	
	public function obtenerRegistrosZona()
	{
		$data['zonas']	= $this->configuracion->obtenerZonas();
		
		$this->load->view("configuracion/zonas/obtenerRegistrosZona",$data);
	}
	
	public function obtenerZonasCatalogo()
	{
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['zonas']	= $this->configuracion->obtenerZonas();
	
		$this->load->view("configuracion/zonas/obtenerZonas",$data);
	}
	
	public function formularioZonas()
	{
		$this->load->view("configuracion/zonas/formularioZonas");
	}

	public function registrarZona()
	{
		if (!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarZona());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}

	public function borrarZona()
	{
		if (!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
			
			if($data['permiso'][0]->activo=='0')
			{
				$this->load->view('accesos/index');
				return;
			}
			
			echo $this->configuracion->borrarZona($this->input->post('idZona'));
		}
		else
		{
			echo "0";
		}
	}
	
	
	public function obtenerZona()
	{
		$data['zona']	= $this->configuracion->obtenerZona($this->input->post('idZona'));
		
		$this->load->view("configuracion/zonas/obtenerZona",$data);
	}
	
	public function editarZona()
	{
		if(!empty ($_POST))
		{
			echo $this->configuracion->editarZona();
		}
		else
		{
			echo "0";
		}
	}

	public function borrauser($id)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		if($this->configuracion->borrauser($id))
		{
			$this->session->set_flashdata('message', array('messageType' => 'success','Message' => 'El usuario se ha eliminado correctamente.'));
			redirect('configuracion/listauser','refresh');
		}
		else
		{
			//       $this->session->set_flashdata('message', array('messageType' => 'success','Message' => 'Los datos se han almacenado correctamente.'));
			$this->session->set_flashdata('message', array('messageType' => 'error','Message' => 'Ocurrio un error al borrar el usuario.'));
			redirect('configuracion/listauser','refresh');
		}
	}	

	public function updateuser($id)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		if($this->configuracion->editauser($id))
		{
			$this->session->set_flashdata('message', array('messageType' => 'success','Message' => 'El usuario se ha editado correctamente.'));
			redirect('configuracion/listauser','refresh');
		}
		else
		{
			$this->session->set_flashdata('message', array('messageType' => 'error','Message' => 'Ocurrio un error al editar el usuario.'));
			redirect('configuracion/listauser','refresh');
		
		}
	}

	public function camaras()
	{
		$Data['title']			= "Panel de Administración";	
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];
		$Data['Jry']			=$this->_jss['jquery'];	  	 	
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='camaras'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['vigilancia']=$this->configuracion->obtenerFel();
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		$this->load->view('configuracion/camaras',$data);
		$this->load->view("pie",$Data);	
	}
	
	public function activarEstilo()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$color=$this->input->post('txtColor');
		
		$this->session->set_userdata('estilo',$color);   
		$estilo=$this->configuracion->activarEstilo($color);
		
		redirect('configuracion/estilo','refresh');
	}
	
	public function estilo()
	{
		$Data['title']			= "Panel de Administración";	
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['Jry']			= $this->_jss['jquery'];	  
		$Data['Jqui']			= $this->_jss['jqueryui'];	 	
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= 'Estilo';
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		$this->load->view('configuracion/estilo/estilo',$data);
		$this->load->view("pie",$Data);	
	}
	
	public function actualizarImpuestos()
	{
		if(!empty ($_POST))
		{
			$factura=$this->configuracion->actualizarImpuestos();
			redirect('configuracion/impuestos','refresh');
		}
	}
	
	public function impuestos()
	{ 
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];  
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['impuestos']		= $this->configuracion->obtenerImpuestos();
		$data["breadcumb"]		= 'Impuestos';
		
		$this->load->view("configuracion/impuestos/impuestosLista",$data);
		$this->load->view("pie",$Data);
	}

	public function actualizarFEL()
	{
		if(!empty ($_POST))
		{
			$factura=$this->configuracion->actualizarFEL();
			redirect('configuracion/facturacion','refresh');
		}
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#--------------------------------------------------CONVERSION----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function unidades()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];   
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;    
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['unidades']		= $this->configuracion->seleccionarUnidades();
		$data["breadcumb"]		= 'Unidades';
		
		$this->load->view("configuracion/unidades/unidades",$data);
		$this->load->view("pie",$Data);
	}
	
	public function registrarConversion()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarConversion());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerConversionesProduccion()
	{
		if(!empty ($_POST))
		{
			$idUnidad		=$this->input->post('idUnidad');
			$conversiones	=$this->configuracion->obtenerConversiones($idUnidad);
			
			echo'
			<select class="cajas" style="width:300px" id="selectConversiones">
				<option value="0">Seleccione</option>';
			
			foreach($conversiones as $row)
			{
				echo'<option value="'.$row->idConversion.'">'.$row->nombre.' ('.$row->referencia.'), '.$row->valor.'</option>';
			}
			
			echo '</select>';
		}
	}
	public function obtenerConversiones()
	{
		if(!empty ($_POST))
		{
			$idUnidad				= $this->input->post('idUnidad');
			$data['conversiones']	= $this->configuracion->obtenerConversiones($idUnidad);
			$data['unidad']			= $this->configuracion->obtenerUnidad($idUnidad);
			$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
			$data['idUnidad']		= $idUnidad;
			
			$this->load->view("configuracion/unidades/obtenerConversiones",$data);
		}
	}
	
	public function obtenerConversion()
	{
		$idConversion	=$this->input->post('idConversion');	
		$conversion		=$this->configuracion->obtenerConversion($idConversion);
		
		echo'
		<table class="admintable" width="100%">
			<tr>
				<td class="key">Nombre:</td>
				<td>
					<input style="width:200px" type="text" class="cajas" id="txtConversionEditar" value="'.$conversion->nombre.'" />
					<input type="hidden" class="cajas" id="txtIdConversion" value="'.$idConversion.'" />
				</td>
			</tr>
			<tr>
				<td class="key">Referencia:</td>
				<td>
					<input style="width:200px" type="text" class="cajas" id="txtReferenciaEditar" value="'.$conversion->referencia.'" />
				</td>
			</tr>
			
			<tr>
				<td class="key">Valor:</td>
				<td>
					<input style="width:200px" type="text" class="cajas" id="txtValorEditar" value="'.$conversion->valor.'" />
				</td>
			</tr>
		</table>';
	}
	
	public function editarConversion()
	{
		if (!empty($_POST))
		{
			$unidad=$this->configuracion->editarConversion();
			echo $unidad;
		}
	}
	
	public function borrarConversion()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			echo "0";
			return;
		}
		
		$idConversion	=$this->input->post('idConversion');
		$conversion		=$this->configuracion->borrarConversion($idConversion);
		
		echo $conversion;
	}
	
	public function cuentasContables()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			=$this->_csstyle["cassadmin"];
		$Data['csmenu']				=$this->_csstyle["csmenu"];
		$Data['csvalidate']			=$this->_csstyle["csvalidate"];
		$Data['csui']				=$this->_csstyle["csui"];
		$Data['nameusuario']		=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		=$this->_fechaActual;
		$Data['Jry']				=$this->_jss['jquery'];
		$Data['JFuntBuscaClientes']	=$this->_jss['JFuntBuscaClientes'];
		$Data['jvalidate']			=$this->_jss['jvalidate'];
		$Data['Jqui']				=$this->_jss['jqueryui'];
		$Data['permisos']			=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['cuentas'] = $this->configuracion->obtenerCuentasContables();
		
		$this->load->view("configuracion/cuentas",$data);
		$this->load->view("pie",$data);
	}
	
	public function agregarCuentaContable()
	{
		if(!empty ($_POST))
		{
			$cuenta=$this->configuracion->agregarCuentaContable();
			
			$cuenta=="1"?
				$this->session->set_userdata('notificacion','La cuenta se ha registrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al registrar la cuenta');
			
			echo $cuenta;
		}
	}
	
	public function editarCuentaContable()
	{
		if(!empty ($_POST))
		{
			$cuenta=$this->configuracion->editarCuentaContable();
			
			$cuenta=="1"?
				$this->session->set_userdata('notificacion','La cuenta se ha editado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al editar la cuenta');
			
			echo $cuenta;
		}
	}
	
	public function obtenerCuentaContable()
	{
		if(!empty ($_POST))
		{
			$idCuenta=$this->input->post('idCuenta');
			$cuenta=$this->configuracion->obtenerCuentaContable($idCuenta);
			
			echo '<input name="txtIdCuenta" id="txtIdCuenta" value="'.$idCuenta.'" type="hidden" />';
			echo'
			<table class="admintable" width="99%;">
				 <tr>
					<td style="width:15%" class="key">Nivel 1:</td>
					<td>
						<input value="'.$cuenta->nivel1.'" name="txtNivel11" id="txtNivel11" type="text" class="cajasSelect"  />
					</td>
					<td style="width:15%" class="key">Clave:</td>
					<td>
					   <input value="'.$cuenta->clave1.'" name="txtClave11" id="txtClave11" type="text" class="cajasSelect"  />
					</td>
				</tr>	
				
				<tr>
					<td width="10%" class="key">Nivel 2:</td>
					<td>
						<input value="'.$cuenta->nivel2.'" name="txtNivel21" id="txtNivel21" type="text" class="cajasSelect"  />
					</td>
					<td  class="key">Clave:</td>
					<td>
					   <input value="'.$cuenta->clave2.'" name="txtClave21" id="txtClave21" type="text" class="cajasSelect"  />
					</td>
				</tr>	
				
				<tr>
					<td  width="10%" class="key">Nivel 3:</td>
					<td>
						<input value="'.$cuenta->nivel3.'" name="txtNivel31" id="txtNivel31" type="text" class="cajasSelect"  />
					</td>
					<td class="key">Clave:</td>
					<td>
					   <input value="'.$cuenta->clave3.'" name="txtClave31" id="txtClave31" type="text" class="cajasSelect"  />
					</td>
				</tr>	
				
				 <tr>
					<td width="10%" class="key">Nivel 4:</td>
					<td>
						<input value="'.$cuenta->nivel4.'" name="txtNivel41" id="txtNivel41" type="text" class="cajasSelect"  />
					</td>
					<td class="key">Clave:</td>
					<td>
					   <input value="'.$cuenta->clave4.'" name="txtClave41" id="txtClave41" type="text" class="cajasSelect"  />
					</td>
				</tr>	
				 <tr>
					<td width="10%" class="key">Descripcion:</td>
					<td colspan="3">
						<input value="'.$cuenta->nombre.'" name="txtCuentaEditar" style="width:300px" id="txtCuentaEditar" type="text" class="cajasSelect"  />
					</td>
				</tr>	
			</table>';
		}
	}
	
	public function borrarCuentaContable($idCuenta)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$cuenta=$this->configuracion->borrarCuentaContable($idCuenta);
		
		$cuenta=="1"?
				$this->session->set_userdata('notificacion','La cuenta se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar la cuenta porque esta asociada a gastos administrativos');
				
		redirect('configuracion/cuentasContables','refresh');
	}
	
	
	#RESPALDO Y RESTAURACIÓN DE BASE DE DATOS
	#---------------------------------------------------------------------------------------------------------------------#
	public function respaldoBD()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$this->load->dbutil();
		
		$prefs = array
		(
			'format'      => 'gzip',             // gzip, zip, txt
			'filename'    => 'redisoftsystems'.date('Y-m-d').'.sql',    // File name - NEEDED ONLY WITH ZIP FILES
			'newline'     => "\n"               // Newline character used in backup file
        );


		$copiaSeguridad =& $this->dbutil->backup($prefs); 
		
		$base='redisoftsystems'.date('Y-m-d').".gz";
		
		$this->load->helper('download');
		force_download($base, $copiaSeguridad); 
	}
	
	public function subirFichero()
	{
		$directorio  	= "media/";
		$fichero 		= $directorio . basename($_FILES['userfile']['name']);

		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $fichero)) 
		{
			$this->restaurarBase($fichero);
			echo "success";
		} 
		else 
		{
		  	echo "error";
		}
	}

	public function vaciarTablas()
	{
		$sql="SHOW TABLE STATUS FROM redisofs_redisoft;";
		#$sql="SHOW TABLE STATUS FROM producsoft;";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$this->db->query('truncate table '.$row->Name);
		}
	}
	
	public function restaurarBase($fichero)
	{
		redirect('configuracion','refresh');
		return;
		#$fichero='media/redisoftsystems2013-01-10.gz';
		ob_start();
		readgzfile($fichero);
		$data	=ob_get_clean();
		#ob_end_clean();

		$this->db->trans_start();
		$this->db->query('set foreign_key_checks=0;');
		
		$this->vaciarTablas();

		//estas dos lineas me permitieron individualizar las consultas
		$data 		= nl2br($data);
		$data_arr 	= explode('<br />', $data); 

		foreach($data_arr as $query)
		{
			//Solo ejecutar los puros inserts
			$pos = stripos($query, 'INSERT');
						
			if($pos!==false && $pos<5) 
			{
			   $this->db->query($query);
			}
		}
		
		$this->db->query('set foreign_key_checks=1;');

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
	
	//AUTOCOMPLETADOS ACTUALIZADOS
	public function obtenerClientes($contactos=1,$catalogos=0,$ingresos=0,$prospectos='0')
	{
		$permiso		= $this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		$clientes 		= $this->configuracion->obtenerClientes($this->input->get('term'),$contactos,$permiso[4]->activo,$catalogos,$ingresos,$prospectos);
		
		if($clientes!=null)
		{
			foreach ($clientes as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerClientesActivos()
	{
		$permiso		= $this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		$clientes 		= $this->configuracion->obtenerClientesActivos($this->input->get('term'),$permiso[4]->activo);
		
		if($clientes!=null)
		{
			foreach ($clientes as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerClientesProspectos($contactos=1,$catalogos=0,$ingresos=0,$prospectos='0')
	{
		$permiso		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$clientes 		= $this->configuracion->obtenerClientes($this->input->get('term'),$contactos,$permiso[17]->activo,$catalogos,$ingresos,$prospectos);
		
		if($clientes!=null)
		{
			foreach ($clientes as $row)
			{
				$result[]= $row;
			}
		}
		else
		{
			$result[]=array('idSeguimiento'=>0,'value'=>'No se encuentra registrado');
		}
		
		echo json_encode($result);
	}
	
	public function obtenerProspectosNuevos()
	{
		$permiso		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$clientes 		= $this->configuracion->obtenerProspectosNuevos($this->input->get('term'),$permiso[17]->activo);
		
		if($clientes!=null)
		{
			foreach ($clientes as $row)
			{
				$result[]= $row;
			}
		}
		else
		{
			$result[]=array('idSeguimiento'=>0,'value'=>'No se encuentra registrado');
		}
		
		echo json_encode($result);
	}
	
	public function obtenerProveedores($idProducto=0,$idMaterial=0,$idInventario=0,$idServicio=0,$catalogos=0)
	{
		$proveedores 	= $this->configuracion->obtenerProveedores($this->input->get('term'),$idProducto,$idMaterial,$idInventario,$idServicio,$catalogos);
		
		if($proveedores!=null)
		{
			foreach ($proveedores as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerOrdenesCompra($tipo=0,$idProveedor=0)
	{
		$ordenes = $this->configuracion->obtenerOrdenesCompra($this->input->get('term'),$tipo,$idProveedor);
		
		if($ordenes!=null)
		{
			foreach ($ordenes as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerMateriales($precios=1)
	{
		$materiales = $this->configuracion->obtenerMateriales($this->input->get('term'),$precios);
		
		if($materiales!=null)
		{
			foreach ($materiales as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerMaterialesProduccion()
	{
		$materiales = $this->configuracion->obtenerMaterialesProduccion($this->input->get('term'));
		
		if($materiales!=null)
		{
			foreach ($materiales as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerProductosInventario($idLinea=0)
	{
		$productos = $this->configuracion->obtenerProductosInventario($this->input->get('term'),$idLinea);
		
		if($productos!=null)
		{
			foreach ($productos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerProductosActualizar($criterio='nombre')
	{
		$productos = $this->configuracion->obtenerProductosActualizar($this->input->get('term'),$criterio);
		
		if($productos!=null)
		{
			foreach ($productos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerProductosCampos()
	{
		$producto = $this->configuracion->obtenerProductosCampos($this->input->post('criterio'),$this->input->post('campo'));
		
		if($producto!=null)
		{
			echo json_encode($producto);
		}
		else
		{
			echo json_encode(array('idProducto'=>0));
		}
	}
	
	
	public function obtenerServicios()
	{
		$servicios = $this->configuracion->obtenerServiciosAutocompletado($this->input->get('term'));
		
		if($servicios!=null)
		{
			foreach ($servicios as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerProductosInventarioRepetido($columna='nombre')
	{
		$productos = $this->configuracion->obtenerProductosInventarioRepetido($this->input->get('term'),$columna);
		
		if($productos!=null)
		{
			foreach ($productos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerProductosServicios($descripcion=1)
	{
		$productos = $this->configuracion->obtenerProductosServicios($this->input->get('term'),$descripcion);
		
		if($productos!=null)
		{
			foreach ($productos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerProductosPedido()
	{
		$productos = $this->configuracion->obtenerProductosPedido($this->input->get('term'));
		
		if($productos!=null)
		{
			foreach ($productos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerInventarioProduccion()
	{
		$productos = $this->configuracion->obtenerInventarioProduccion($this->input->get('term'));
		
		if($productos!=null)
		{
			foreach ($productos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerInventarioMobiliario()
	{
		$inventario = $this->configuracion->obtenerInventarioMobiliario($this->input->get('term'));
		
		if($inventario!=null)
		{
			foreach ($inventario as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	#PARA COTIZACIONES
	public function obtenerCotizaciones() 
	{
		$cotizaciones = $this->configuracion->obtenerCotizaciones($this->input->get('term'));
		
		if($cotizaciones!=null)
		{
			foreach ($cotizaciones as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	#PARA COTIZACIONES
	public function obtenerListaCotizaciones($idCliente=0) 
	{
		$permiso		= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		$cotizaciones 	= $this->configuracion->obtenerListaCotizaciones($this->input->get('term'),$idCliente,$permiso[4]->activo);
		
		if($cotizaciones!=null)
		{
			foreach ($cotizaciones as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	#PARA VENTAS
	public function obtenerVentas() 
	{
		$ventas = $this->configuracion->obtenerVentas($this->input->get('term'));
		
		if($ventas!=null)
		{
			foreach ($ventas as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerListaVentas($idCliente=0) 
	{
		$permiso		= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$ventas = $this->configuracion->obtenerListaVentas($this->input->get('term'),$idCliente,$permiso[4]->activo);
		
		if($ventas!=null)
		{
			foreach ($ventas as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	#PARA FACTURAS
	public function obtenerFacturas() 
	{
		$facturas = $this->configuracion->obtenerFacturas($this->input->get('term'));
		
		if($facturas!=null)
		{
			foreach ($facturas as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerFacturasIngresos() 
	{
		$facturas = $this->configuracion->obtenerFacturasIngresos($this->input->get('term'));
		
		if($facturas!=null)
		{
			foreach ($facturas as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	#PARA ZONAS
	public function obtenerZonas() 
	{
		$zonas = $this->configuracion->obtenerZonasBusqueda($this->input->get('term'));
		
		if($zonas!=null)
		{
			foreach ($zonas as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	#PARA PERSONAL
	public function obtenerPersonal() 
	{
		$personal = $this->configuracion->obtenerPersonal($this->input->get('term'));
		
		if($personal!=null)
		{
			foreach ($personal as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerRolesRepetidos()
	{
		$roles = $this->configuracion->obtenerRolesRepetidos($this->input->get('term'));
		
		if($roles!=null)
		{
			foreach ($roles as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerBancosRepetidos()
	{
		$bancos = $this->configuracion->obtenerBancosRepetidos($this->input->get('term'));
		
		if($bancos!=null)
		{
			foreach ($bancos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerUnidadesRepetidas()
	{
		$unidades = $this->configuracion->obtenerUnidadesRepetidas($this->input->get('term'));
		
		if($unidades!=null)
		{
			foreach ($unidades as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerTipoCliente()
	{
		$tipoCliente = $this->configuracion->obtenerTipoCliente($this->input->get('term'));
		
		if($tipoCliente!=null)
		{
			foreach ($tipoCliente as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerProcesosRepetidos()
	{
		$procesos = $this->configuracion->obtenerProcesosRepetidos($this->input->get('term'));
		
		if($procesos!=null)
		{
			foreach ($procesos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerDepartamentosRepetidos()
	{
		$departamentos = $this->configuracion->obtenerDepartamentosRepetidos($this->input->get('term'));
		
		if($departamentos!=null)
		{
			foreach ($departamentos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerProductosRepetidos()
	{
		$productos = $this->configuracion->obtenerProductosRepetidos($this->input->get('term'));
		
		if($productos!=null)
		{
			foreach ($productos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerGastosRepetidos()
	{
		$gastos = $this->configuracion->obtenerGastosRepetidos($this->input->get('term'));
		
		if($gastos!=null)
		{
			foreach ($gastos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerNombresRepetidos()
	{
		$nombres = $this->configuracion->obtenerNombresRepetidos($this->input->get('term'));
		
		if($nombres!=null)
		{
			foreach ($nombres as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerLineasRepetidas()
	{
		$lineas = $this->configuracion->obtenerLineasRepetidas($this->input->get('term'));
		
		if($lineas!=null)
		{
			foreach ($lineas as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerOrdenesProduccion($tipo=0)
	{
		$ordenes = $this->configuracion->obtenerOrdenesProduccion($this->input->get('term'),$tipo);
		
		if($ordenes!=null)
		{
			foreach ($ordenes as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function obtenerLineaNueva($nombre)
	{
		$sql="select idLinea
		from productos_lineas
		where nombre='$nombre'";	
		
		$query=$this->db->query($sql)->row();
		
		if($query!=null)
		{
			return $query->idLinea;
		}
		else
		{
			$data['nombre']	=$nombre;
			$this->db->insert('productos_lineas',$data);
			
			return $this->db->insert_id();
		}
	}
	
	public function obtenerProveedorNuevo($nombre)
	{
		$sql="select idProveedor
		from proveedores	
		where empresa='$nombre'";	
		
		$query=$this->db->query($sql)->row();
		
		if($query!=null)
		{
			return $query->idProveedor;
		}
		else
		{
			$data=array
			(
				'empresa'		=>$nombre,
				'email'			=>'',
				'telefono'		=>'',
				'idUsuario'	 	=>$this->_iduser,
				'fecha'			=>$this->_fechaActual,
				'activo'		=>1,
				'domicilio'		=>'',
				'rfc'			=>'',
				'estado'		=>'',
				'pais'			=>'México',
				'website'		=>'',
				'idLicencia'	=>1,
				
				'alias'			=>$nombre,
				'fax'			=>'',
				'localidad'		=>'',
				
				'numero'		=>'',
				'colonia'		=>'',
				'municipio'		=>'',
				'codigoPostal'	=>'',
				'vende'			=>'',
				
				'latitud'		=>'',
				'longitud'		=>'',
			);
			
			$this->db->insert('proveedores',$data);
			
			return $this->db->insert_id();
		}
	}
	
	public function importarProductos()
	{
		$this->db->trans_start();
		
		$sql="select * from exportar";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$data=array
			(
				'nombre'			=>$row->nombre,
				'fechaRegistro'		=>date('Y-m-d H:i:s'),
				'precioA'			=>$row->precioA,
				'precioB'			=>$row->precioB,
				'precioC'			=>$row->precioC,
				'precioD'			=>$row->precioD,
				#'precioE'			=>$row->precio,
				'reventa'			=>1,
				'piezas'			=>1,
				'unidad'			=>$row->unidad,
				'codigoInterno'		=>$row->codigoInterno,
				#'diseno'			=>'',
				#'talla'				=>'',
				'stock'				=>$row->inventarioInicial,
				'idLinea'			=>$this->obtenerLineaNueva($row->linea),
			);
			
			$this->db->insert('produccion_productos',$data);
			$idProducto		=$this->db->insert_id();
			
			$data=array
			(
				'nombre'			=>$row->nombre,
				'fecha'				=>date('Y-m-d H:i:s'),
				'precioA'			=>$row->precioA,
				'precioB'			=>$row->precioB,
				'precioC'			=>$row->precioC,
				'precioD'			=>$row->precioD,
				#'precioE'			=>$row->precio,
				'reventa'			=>1,
				'codigoInterno'		=>$row->codigoInterno,
				'unidad'			=>$row->unidad,
				'codigoBarras'		=>'',
				'idUsuario'			=>1,
				#'diseno'			=>$row->diseno,
				#'talla'				=>$row->talla,
				'idLinea'			=>$this->obtenerLineaNueva($row->linea),
			);
			
			$this->db->insert('productos',$data);
			$idProductoCaja		=$this->db->insert_id();
			
			$data=array
			(
				'idProductoProduccion'	=>$idProducto,
				'idProducto'			=>$idProductoCaja,
				'cantidad'				=>1,
				'fecha'					=>date('Y-m-d H:i:s')
			);
			
			$this->db->insert('rel_producto_produccion',$data);
			
			$data=array
			(
				'idProducto'			=>$idProductoCaja,
				'precio'				=>1,
				'idProveedor'			=>$this->obtenerProveedorNuevo($row->proveedor)
			);
			
			$this->db->insert('rel_producto_proveedor',$data);
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
	  	}
		
		redirect('configuracion/index','refresh');
	}
	
	public function importarBaby()
	{
		$this->db->trans_start();
		
		$sql="select * from exportar";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$data=array
			(
				'nombre'			=>$row->nombre,
				'fechaRegistro'		=>date('Y-m-d H:i:s'),
				'precioA'			=>$row->precio,
				'precioB'			=>$row->precio,
				'precioC'			=>$row->precio,
				'precioD'			=>$row->precio,
				'precioE'			=>$row->precio,
				'reventa'			=>1,
				'piezas'			=>1,
				'unidad'			=>'PZA',
				'codigoInterno'		=>$row->codigoInterno,
				'diseno'			=>'Pendiente',
				'talla'				=>'Pendiente',
				'idLinea'			=>1,
			);
			
			$this->db->insert('produccion_productos',$data);
			$idProducto		=$this->db->insert_id();
			
			$data=array
			(
				'nombre'			=>$row->nombre,
				'fecha'				=>date('Y-m-d H:i:s'),
				'precioA'			=>$row->precio,
				'precioB'			=>$row->precio,
				'precioC'			=>$row->precio,
				'precioD'			=>$row->precio,
				'precioE'			=>$row->precio,
				'reventa'			=>1,
				'codigoInterno'		=>$row->codigoInterno,
				'unidad'			=>'PZA',
				'codigoBarras'		=>$row->codigoBarras,
				'idUsuario'			=>1,
				'diseno'			=>'Pendiente',
				'talla'				=>'Pendiente',
				'idLinea'			=>1,
			);
			
			$this->db->insert('productos',$data);
			$idProductoCaja		=$this->db->insert_id();
			
			$data=array
			(
				'idProductoProduccion'	=>$idProducto,
				'idProducto'			=>$idProductoCaja,
				'cantidad'				=>1,
				'fecha'					=>date('Y-m-d H:i:s')
			);
			
			$this->db->insert('rel_producto_produccion',$data);
			
			$data=array
			(
				'idProducto'			=>$idProductoCaja,
				'precio'				=>$row->costo,
				'idProveedor'			=>6
			);
			
			$this->db->insert('rel_producto_proveedor',$data);
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
	  	}
		
		redirect('configuracion/index','refresh');
	}
	
	public function exportarClientes()
	{
		$this->db->trans_start();
		
		$sql="select * from expor";
		
		foreach($this->db->query($sql)->result() as $row)
		{
			$data=array
			(
				'email'				=>$row->email,
				'fechaRegistro'		=>date('Y-m-d H:i:s'),
				'telefono'			=>$row->telefono,
				'idUsuario'			=>1,
				'rfc'				=>$row->rfc,
				'empresa'			=>$row->empresa,
				'calle'				=>$row->calle,
				'estado'			=>$row->estado,
				'municipio'			=>$row->municipio,
				'pais'				=>'MÉXICO',
				'idZona'			=>$row->idZona,
			);
			
			$this->db->insert('clientes',$data);
			$idCliente		=$this->db->insert_id();
			
			$data=array
			(
				'nombre'			=>$row->contacto,
				'fechaRegistro'		=>date('Y-m-d H:i:s'),
				'idCliente'			=>$idCliente,
				'direccion'			=>$row->departamento,
				'email'				=>$row->email,
				'telefono'			=>$row->telefono,
			);
			
			$this->db->insert('clientes_contactos',$data);
			
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback(); 
			$this->db->trans_complete();
		}
		else
		{
			$this->db->trans_commit();
			$this->db->trans_complete();
	  	}
		
		redirect('configuracion/index','refresh');
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------DIVISAS------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function divisas()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];   
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		//$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;    
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['divisas']		= $this->configuracion->obtenerDivisas();
		$data["breadcumb"]		= 'Divisas';
		
		$this->load->view("configuracion/divisas",$data);
		$this->load->view("pie",$Data);
	}
	
	public function formularioDivisas()
	{
		echo '
		<table class="admintable" width="100%;">
			<tr>
				<td class="key">Nombre:</td>
				<td>
					<input name="txtNombre" id="txtNombre" type="text" class="cajas"  />
				</td>
			</tr>	
			
			<tr>
				<td class="key">Clave:</td>
				<td>
					<input name="txtClave" id="txtClave" type="text" class="cajas"  />
				</td>
			</tr>	
			
			<tr>
				<td class="key">Tipo de cambio:</td>
				<td>
					<input name="txtTipoCambio" id="txtTipoCambio" type="text" class="cajas"  />
				</td>
			</tr>	
		</table>';
	}
	
	public function obtenerDivisa()
	{
		$idDivisa=$this->input->post('idDivisa');
		
		$divisa=$this->configuracion->obtenerDivisa($idDivisa);
		
		echo '
		<table class="admintable" width="100%;">
			<tr>
				<td class="key">Nombre:</td>
				<td>
					<input name="txtNombre" value="'.$divisa->nombre.'" id="txtNombre" type="text" class="cajas"  />
					<input value="'.$divisa->idDivisa.'" id="txtIdDivisa" type="hidden" />
				</td>
			</tr>	
			
			<tr>
				<td class="key">Clave:</td>
				<td>
					<input name="txtClave" value="'.$divisa->clave.'" id="txtClave" type="text" class="cajas"  />
				</td>
			</tr>	
			
			<tr>
				<td class="key">Tipo de cambio:</td>
				<td>
					<input name="txtTipoCambio" value="'.$divisa->tipoCambio.'" id="txtTipoCambio" type="text" class="cajas"  />
				</td>
			</tr>	
		</table>';
	}
	
	public function registrarDivisa()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$divisa=$this->configuracion->registrarDivisa();
			
			$divisa[0]=="1"?
				$this->session->set_userdata('notificacion','La divisa se ha registrado correctamente'):'';
			
			echo json_encode($divisa);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarDivisa()
	{
		if(!empty ($_POST))
		{
			$divisa=$this->configuracion->editarDivisa();
			
			$divisa=="1"?
				$this->session->set_userdata('notificacion','La divisa se ha editado correctamente'):'';
			
			echo $divisa;
		}
	}
	
	public function borrarDivisa($idDivisa)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$cuenta=$this->configuracion->borrarDivisa($idDivisa);
		
		$cuenta=="1"?
				$this->session->set_userdata('notificacion','La divisa se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar la divisa, esta asociada a cotizaciones y ventas');
				
		redirect('configuracion/divisas','refresh');
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#-------------------------------------PARA LOS CATALOGOS CONTABLES-----------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	public function catalogosContables()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];   
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		//$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;    
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='configuracion';
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['departamentos']	=$this->configuracion->obtenerDepartamentos();
		$data['productos']		=$this->configuracion->obtenerProductos();
		$data['gastos']			=$this->configuracion->obtenerGastos();
		$data['nombres']		=$this->configuracion->obtenerNombres();
		$data["breadcumb"]		= 'Catálogos contables';
		
		$this->load->view("configuracion/catalogosContables",$data);
		$this->load->view("pie",$Data);
	}
	
	public function formularioDepartamentos()
	{
		$this->load->view('configuracion/catalogosContables/formularioDepartamentos');
	}
	
	public function obtenerDepartamento()
	{
		$idDepartamento			= $this->input->post('idDepartamento');
		$data['departamento']	= $this->configuracion->obtenerDepartamento($idDepartamento);
		$data['idDepartamento']	= $idDepartamento;

		$this->load->view('configuracion/catalogosContables/obtenerDepartamento',$data);
	}
	
	public function registrarDepartamento()
	{
		if(!empty ($_POST))
		{
			$departamento=$this->configuracion->registrarDepartamento();
			
			$departamento=="1"?
				$this->session->set_userdata('notificacion','El departamento se ha registrado correctamente'):'';
			
			echo $departamento;
		}
	}
	
	public function editarDepartamento()
	{
		if(!empty ($_POST))
		{
			$departamento=$this->configuracion->editarDepartamento();
			
			$departamento=="1"?
				$this->session->set_userdata('notificacion','El departamento se ha editado correctamente'):'';
			
			echo $departamento;
		}
	}
	
	public function borrarDepartamento($idDepartamento)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$departamento=$this->configuracion->borrarDepartamento($idDepartamento);
		
		$departamento=="1"?
				$this->session->set_userdata('notificacion','El departamento se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar el departamento, esta asociado a cotizaciones y ventas');
				
		redirect('configuracion/catalogosContables','refresh');
	}
	
	//PARA LOS PRODUCTOS
	public function formularioProductos()
	{
		$this->load->view('configuracion/catalogosContables/formularioProductos');
	}
	
	public function obtenerProducto()
	{
		$idProducto			= $this->input->post('idProducto');
		$data['producto']	= $this->configuracion->obtenerProducto($idProducto);
		$data['idProducto']	= $idProducto;
		
		$this->load->view('configuracion/catalogosContables/obtenerProducto',$data);
	}
	
	public function registrarProducto()
	{
		if(!empty ($_POST))
		{
			$producto=$this->configuracion->registrarProducto();
			
			$producto=="1"?
				$this->session->set_userdata('notificacion','El producto se ha registrado correctamente'):'';
			
			echo $producto;
		}
	}
	
	public function editarProducto()
	{
		if(!empty ($_POST))
		{
			$producto=$this->configuracion->editarProducto();
			
			$producto=="1"?
				$this->session->set_userdata('notificacion','El producto se ha editado correctamente'):'';
			
			echo $producto;
		}
	}
	
	public function borrarProducto($idProducto)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$producto=$this->configuracion->borrarProducto($idProducto);
		
		$producto=="1"?
				$this->session->set_userdata('notificacion','El producto se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar el producto, esta asociado a cotizaciones y ventas');
				
		redirect('configuracion/catalogosContables','refresh');
	}
	
	//PARA LOS GASTOS
	public function formularioGastos()
	{
		$this->load->view('configuracion/catalogosContables/formularioProductos');
	}
	
	public function obtenerGasto()
	{
		$idGasto			= $this->input->post('idGasto');
		$data['gasto']		= $this->configuracion->obtenerGasto($idGasto);
		$data['idGasto']	= $idGasto;

		$this->load->view('configuracion/catalogosContables/obtenerGasto',$data);
	}
	
	public function registrarGasto()
	{
		if(!empty ($_POST))
		{
			$gasto=$this->configuracion->registrarGasto();
			
			$gasto=="1"?
				$this->session->set_userdata('notificacion','El gasto se ha registrado correctamente'):'';
			
			echo $gasto;
		}
	}
	
	public function editarGasto()
	{
		if(!empty ($_POST))
		{
			$gasto=$this->configuracion->editarGasto();
			
			$gasto=="1"?
				$this->session->set_userdata('notificacion','El gasto se ha editado correctamente'):'';
			
			echo $gasto;
		}
	}
	
	public function borrarGasto($idGasto)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$gasto=$this->configuracion->borrarGasto($idGasto);
		
		$gasto=="1"?
				$this->session->set_userdata('notificacion','El gasto se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar el gasto, esta asociado a cotizaciones y ventas');
				
		redirect('configuracion/catalogosContables','refresh');
	}
	
	//PARA LOS NOMBRES
	public function formularioNombres()
	{
		echo '
		<script>
		$(document).ready(function()
		{
			$("#txtNombre").autocomplete(
			{
				source:"'.base_url().'configuracion/obtenerNombresRepetidos",
				
				select:function( event, ui)
				{
					notify("El nombre ya esta registrado",500,5000,"error",5,5);
					document.getElementById("txtNombre").reset();
				}
			});
		});
		</script>
		<table class="admintable" width="100%;">
			<tr>
				<td class="key">Nombre:</td>
				<td>
					<input style="width:300px;"  name="txtNombre" id="txtNombre" type="text" class="cajas"  />
				</td>
			</tr>	
		</table>';
	}
	
	public function obtenerNombre()
	{
		$idNombre	=$this->input->post('idNombre');
		$nombre		=$this->configuracion->obtenerNombre($idNombre);
		
		echo '
		<table class="admintable" width="100%;">
			<tr>
				<td class="key">Nombre:</td>
				<td>
					<input  style="width:300px;" name="txtNombre" value="'.$nombre->nombre.'" id="txtNombre" type="text" class="cajas"  />
					<input value="'.$nombre->idNombre.'" id="txtIdNombre" type="hidden" />
				</td>
			</tr>	
		</table>';
	}
	
	public function registrarNombre()
	{
		if(!empty ($_POST))
		{
			$nombre=$this->configuracion->registrarNombre();
			
			$nombre=="1"?
				$this->session->set_userdata('notificacion','El nombre se ha registrado correctamente'):'';
			
			echo $nombre;
		}
	}
	
	public function editarNombre()
	{
		if(!empty ($_POST))
		{
			$nombre=$this->configuracion->editarNombre();
			
			$nombre=="1"?
				$this->session->set_userdata('notificacion','El nombre se ha editado correctamente'):'';
			
			echo $nombre;
		}
	}
	
	public function borrarNombre($idNombre)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$nombre=$this->configuracion->borrarNombre($idNombre);
		
		$nombre=="1"?
				$this->session->set_userdata('notificacion','El nombre se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar el nombre, esta asociado a cotizaciones y ventas');
				
		redirect('configuracion/catalogosContables','refresh');
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------LINEAS ------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function lineas()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];   
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		//$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;    
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['lineas']			= $this->configuracion->obtenerLineas();
		$data["breadcumb"]		= 'Líneas';
		
		$this->load->view("configuracion/lineas/lineas",$data);
		$this->load->view("pie",$Data);
	}
	
	public function formularioLineas()
	{
		$this->load->view("configuracion/lineas/formularioLineas");
	}
	
	public function obtenerLinea()
	{
		$data['linea']	= $this->configuracion->obtenerLinea($this->input->post('idLinea'));
		
		$this->load->view("configuracion/lineas/obtenerLinea",$data);
	}
	
	public function obtenerLineasVentas()
	{
		$data['lineas']	= $this->configuracion->obtenerLineas();
		
		$this->load->view("clientes/ventas/obtenerLineasVentas",$data);
	}
	
	public function registrarLinea()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$linea	= $this->configuracion->registrarLinea();
			$linea[0]=="1"? $this->session->set_userdata('notificacion','La línea se ha registrado correctamente'):'';
			
			echo json_encode($linea);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}

	}
	
	public function editarLinea()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$linea	= $this->configuracion->editarLinea();
			
			$linea[0]=="1"? $this->session->set_userdata('notificacion','La línea se ha editado correctamente'):'';
			
			echo json_encode($linea);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarLinea($idLinea)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$linea=$this->configuracion->borrarLinea($idLinea);
		
		$linea=="1"?
				$this->session->set_userdata('notificacion','La línea se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar la línea, esta asociada a productos');
				
		redirect('configuracion/lineas','refresh');
	}
	
	
	//SUBLINEAS
	
	public function obtenerSubLineas()
	{
		$data['sublineas']		= $this->configuracion->obtenerSubLineas($this->input->post('idLinea'));
		$data['linea']			= $this->configuracion->obtenerLinea($this->input->post('idLinea'));
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$this->load->view("configuracion/lineas/subLineas/obtenerSubLineas",$data);
	}
	
	public function formularioSubLineas()
	{
		$this->load->view("configuracion/lineas/subLineas/formularioSubLineas");
	}
	
	public function obtenerSubLinea()
	{
		$data['sublinea']	= $this->configuracion->obtenerSubLinea($this->input->post('idSubLinea'));
		
		$this->load->view("configuracion/lineas/subLineas/obtenerSubLinea",$data);
	}
	
	public function obtenerSubLineasCatalogo()
	{
		$data['sublineas']	= $this->configuracion->obtenerSubLineas($this->input->post('idLinea'));
		
		$this->load->view("configuracion/lineas/subLineas/obtenerSubLineasCatalogo",$data);
	}
	
	public function obtenerSubLineasVentas()
	{
		$data['sublineas']	= $this->configuracion->obtenerSubLineas($this->input->post('idLinea'));
		
		$this->load->view("clientes/ventas/obtenerSubLineasVentas",$data);
	}
	
	public function registrarSubLinea()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarSubLinea());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}

	}
	
	public function editarSubLinea()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->editarSubLinea());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarSubLinea()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->configuracion->borrarSubLinea($this->input->post('idSubLinea')));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function quitarNotificacionCobro()
	{
		if(!empty ($_POST))
		{
			$notificacion=$this->configuracion->quitarNotificacionCobro();
			echo $notificacion;
		}
	}
	
	public function quitarNotificacionPago()
	{
		if(!empty ($_POST))
		{
			$notificacion=$this->configuracion->quitarNotificacionPago();
			echo $notificacion;
		}
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------SERVICIOS----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function servicios()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['mostrarMenu']	= true;
		$data["breadcumb"]		= 'Servicios';
		
		$this->load->view("configuracion/servicios/servicios",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoServicios()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}
		
		$data['mostrarMenu']= false;
		
		$this->load->view("configuracion/servicios/servicios",$data);
	}
	
	public function obtenerListaServicios()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		
		$data['servicios']		= $this->configuracion->obtenerServicios();
		
		$this->load->view('configuracion/servicios/obtenerServicios',$data);
	}
	
	public function formularioServicios()
	{
		$this->load->view('configuracion/servicios/formularioServicios');
	}
	
	public function obtenerServiciosCrm()
	{
		$data['servicios']		= $this->configuracion->obtenerServicios($this->input->post('cliente'));
		
		$this->load->view('configuracion/servicios/obtenerServiciosCrm',$data);
	}
	
	public function obtenerServicio()
	{
		$data['servicio']		= $this->configuracion->obtenerServicio($this->input->post('idServicio'));
		
		$this->load->view('configuracion/servicios/obtenerServicio',$data);
	}
	
	public function registrarServicio()
	{
		if(!empty ($_POST)) 
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarServicio());
		}
		else 
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarServicio()
	{
		if(!empty ($_POST))	echo $this->configuracion->editarServicio();
		else echo "0";
	}
	
	public function borrarServicio()
	{
		if(!empty ($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				$this->load->view('accesos/index');
				return;
			}
			
			echo $this->configuracion->borrarServicio($this->input->post('idServicio'));
		}
		else
		{
			echo "0";
		}
	}
	
	//PARA ADMINISTRAR LOS EMISORES
	public function facturacion()
	{ 
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			=$this->_csstyle["cassadmin"];
		$Data['csmenu']				=$this->_csstyle["csmenu"];  
		$Data['csvalidate']			=$this->_csstyle["csvalidate"];
		$Data['csui']				=$this->_csstyle["csui"];
		$Data['nameusuario']		=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		=$this->_fechaActual;
		$Data['Jry']				=$this->_jss['jquery'];
		$Data['Jqui']				=$this->_jss['jqueryui'];    
		$Data['permisos']			=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		$data['Categoria']=$this->uri->segment(1);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
			

		$data['emisores']		= $this->configuracion->obtenerEmisores();
		#$data['folios']			= $this->configuracion->obtenerFoliosTotales();
		$data['comprados']		= $this->configuracion->obtenerFoliosComprados();
		$data['consumidos']		= $this->configuracion->obtenerFoliosConsumidosTotal();
		$data['idLicencia']		= $this->idLicencia;
		$data["breadcumb"]		= 'Emisores';
		
		$this->load->view("configuracion/emisores/index",$data);
		
		$this->load->view("pie",$Data);
	}//Index
	
	public function emisores()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('10',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['emisores']	=$this->configuracion->obtenerEmisores();
		$data['permisos']	=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$data['titulo']		="Emisores";
		$data['menuActivo']	='emisores';
		$data['pagina']		='configuracion/emisores/index';
		
		$this->load->view("index",$data);
	}
	
	public function formularioEmisores()
	{
		$data['regimen']	= $this->configuracion->obtenerRegimenFiscal();
		
		$this->load->view("configuracion/emisores/formularioEmisores",$data);
	}
	
	public function obtenerEmisor()
	{
		if (!empty($_POST))
		{
			$data['emisor']		= $this->configuracion->obtenerEmisor($this->input->post('idEmisor'));
			$data['regimen']	= $this->configuracion->obtenerRegimenFiscal();
			
			$this->load->view("configuracion/emisores/obtenerEmisor",$data);
		}
	}
	
	public function registrarEmisor()
	{
		if (!empty($_POST))
		{
			$emisor=$this->configuracion->registrarEmisor();
			
			$emisor=="1"?
				$this->session->set_userdata('notificacion','El emisor se ha registrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al registrar al emisor');
		}
		
		redirect('configuracion/facturacion','refresh');
	}
	
	public function editarEmisor()
	{
		if (!empty($_POST))
		{
			$emisor=$this->configuracion->editarEmisor();
			
			$emisor=="1"?
				$this->session->set_userdata('notificacion','El emisor se ha editado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al editar al emisor o el registro no tuvo cambios');
		}
		
		redirect('configuracion/facturacion','refresh');
	}
	
	public function borrarEmisor()
	{
		if (!empty($_POST))
		{
			$emisor=$this->configuracion->borrarEmisor($this->input->post('idEmisor'));
			echo $emisor;
		}
	}
	
	/*public function subirCertificado()
	{
		$carpeta = 'media/fel/';

		if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) 
		{
			$fileName 		= $_SERVER['HTTP_X_FILE_NAME'];
			$contentLength 	= $_SERVER['CONTENT_LENGTH'];
		} 
		else throw new Exception("Error retrieving headers");

		if (!$contentLength > 0) 
		{
			throw new Exception('Error al subir el ficheros!');
		}

		file_put_contents
		(
			$carpeta . $fileName,
			file_get_contents("php://input")
		);
		
		chmod($carpeta.$fileName, 0777);
	}*/
	
	public function subirCertificado()
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('cer');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaCfdi.$_FILES['file']['name']);

				if(file_exists(carpetaCfdi.$_FILES['file']['name']))
				{
					echo "1";
				}
				else
				{
					echo 'El archivo no se ha cargado correctamente';
				}
			} 
			else 
			{
				echo 'Solo se permiten archivos de certificado(cer)';
			}
		}
	} 
	
	public function procesarCertificado()
	{
		$carpeta 	= 'media/fel/';
		$archivo	=$this->input->post('archivo');
		
		exec("openssl x509 -inform DER -outform PEM -in ".$carpeta.$archivo." -pubkey > ".$carpeta."certificado.txt");
		exec("openssl x509 -in ".$carpeta."certificado.txt -noout -sha1 -startdate >".$carpeta."fechaInicio.txt");
		exec("openssl x509 -in ".$carpeta."certificado.txt -noout -sha1 -enddate >".$carpeta."fechaFin.txt");
		exec("openssl x509 -in ".$carpeta."certificado.txt -noout -sha1 -serial >".$carpeta."serial.txt");
		exec("openssl x509 -in ".$carpeta."certificado.txt -noout -sha1 -subject >".$carpeta."empresa.txt");
		
		//PARA OBTENER EL NUMERO DE CERTIFICADO
		$serial			=leerFichero($carpeta.'serial.txt',"READ","");
		$serial			=str_replace("serial=","",$serial);
		$certificado	="";
		
		for($i=0;$i<strlen($serial);$i++)
		{
			$certificado.=	$i%2>0?$serial[$i]:'';
		}
		
		//PARA OBTENER LA FECHA DE INICIO DEL CERTIFICADO
		$fecha			=leerFichero($carpeta.'fechaInicio.txt',"READ","");
		$fecha			=str_replace("notBefore=","",$fecha);
		$fecha			=explode(" ",$fecha);
		
		$fechaInicio	=$fecha[3]."-".obtenerNumeroMes($fecha[0])."-".$fecha[1];
		
		if($fecha[1]=="")
		{
			$fechaInicio	=$fecha[4]."-".obtenerNumeroMes($fecha[0])."-".$fecha[2];
		}
		
		//PARA OBTENER LA FECHA FINAL DEL CERTIFICADO
		$fecha			=leerFichero($carpeta.'fechaFin.txt',"READ","");
		$fecha			=str_replace("notAfter=","",$fecha);
		$fecha			=explode(" ",$fecha);
		
		$fechaFin	=$fecha[3]."-".obtenerNumeroMes($fecha[0])."-".$fecha[1];
		
		if($fecha[1]=="")
		{
			$fechaFin	=$fecha[4]."-".obtenerNumeroMes($fecha[0])."-".$fecha[2];
		}
		
		$data=array
		(
			0	=>$certificado,
			1	=>$fechaInicio,
			2	=>$fechaFin,
		);
		
		echo json_encode($data);
	}
	
	public function configurarNotificaciones()
	{
		if(!empty ($_POST))
		{
			$this->session->set_userdata('notificacionesActivas','0');
			#$this->configuracion->configurarNotificaciones();
		}
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------FORMAS DE PAGO-----------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function formasPago()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];   
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;    
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['formas']			= $this->configuracion->obtenerFormas();
		$data["breadcumb"]		= 'Formas de pago';
		
		$this->load->view("configuracion/formas/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function formularioFormas()
	{
		$data['cuentas']		= $this->bancos->obtenerCuentas();
		
		$this->load->view("configuracion/formas/formularioFormas",$data);
	}
	
	public function obtenerForma()
	{
		$idForma			= $this->input->post('idForma');
		$data['forma']		= $this->configuracion->obtenerForma($idForma);
		$data['cuentas']	= $this->bancos->obtenerCuentas();
		$data['idForma']	= $idForma;
		
		$this->load->view("configuracion/formas/obtenerForma",$data);
	}
	
	public function registrarForma()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$forma=$this->configuracion->registrarForma();
			
			$forma[0]=="1"?
				$this->session->set_userdata('notificacion','La forma de pago se ha registrado correctamente'):'';
			
			echo json_encode($forma);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarForma()
	{
		if(!empty ($_POST))
		{
			$forma=$this->configuracion->editarForma();
			
			$forma=="1"?
				$this->session->set_userdata('notificacion','La forma de pago se ha editado correctamente'):'';
			
			echo $forma;
		}
	}
	
	public function borrarForma($idForma)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$forma=$this->configuracion->borrarForma($idForma);
		
		$forma=="1"?
				$this->session->set_userdata('notificacion','La forma de pago se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar la forma de pago, esta asociada a ventas y/o compras');
				
		redirect('configuracion/formasPago','refresh');
	}
	
	//CÓDIGO AGRUPADOR
	
	public function obtenerCodigoAgrupador()
	{
		$codigo =$this->configuracion->obtenerCodigoAgrupador($this->input->get('term'));
		
		if($codigo!=null)
		{
			foreach ($codigo as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	//MOTIVOS DE DEVOLUCIÓN
	
	public function motivosDevoluciones()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			= $this->_csstyle["cassadmin"];
		$Data['csmenu']				= $this->_csstyle["csmenu"];   
		$Data['csvalidate']			= $this->_csstyle["csvalidate"];
		$Data['csui']				= $this->_csstyle["csui"];
		$Data['Jry']				= $this->_jss['jquery'];
		$Data['Jqui']				= $this->_jss['jqueryui'];
		$Data['nameusuario']		= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		= $this->_fechaActual;    
		$Data['permisos']			= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			= 'configuracion'; 
		$Data['mostrarMenu']		= true; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
		}

		$this->load->view("configuracion/motivosDevoluciones/motivos",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoMotivos()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		$data['mostrarMenu']	= false; 
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}

		$this->load->view("configuracion/motivosDevoluciones/motivos",$data);
	}
	
	public function obtenerMotivos()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		$data['mostrarMenu']	= false; 
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['motivos']	= $this->catalogos->obtenerMotivos();
		
		$this->load->view("configuracion/motivosDevoluciones/obtenerMotivos",$data);
	}
	
	public function obtenerRegistrosMotivos()
	{
		$data['motivos']	= $this->catalogos->obtenerMotivos();
		
		$this->load->view("configuracion/motivosDevoluciones/obtenerRegistrosMotivos",$data);
	}
	
	public function formularioMotivos()
	{
		$this->load->view("configuracion/motivosDevoluciones/formularioMotivos");
	}

	public function registrarMotivo()
	{
		if (!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			echo json_encode($this->catalogos->registrarMotivo());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}

	public function borrarMotivo()
	{
		if (!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
			
			if($data['permiso'][0]->activo=='0')
			{
				$this->load->view('accesos/index');
				return;
			}
			
			echo $this->catalogos->borrarMotivo($this->input->post('idMotivo'));
		}
		else
		{
			echo "0";
		}
	}
	
	
	public function obtenerMotivo()
	{
		$data['motivo']	= $this->catalogos->obtenerMotivo($this->input->post('idMotivo'));
		
		$this->load->view("configuracion/motivosDevoluciones/obtenerMotivo",$data);
	}
	
	public function editarMotivo()
	{
		if(!empty ($_POST))
		{
			echo $this->catalogos->editarMotivo();
		}
		else
		{
			echo "0";
		}
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------STATUS-------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function status()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$this->load->view("configuracion/status/status",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoStatus()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}

		$this->load->view("configuracion/status/status",$data);
	}
	
	public function obtenerStatus()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$data['status']		= $this->configuracion->obtenerStatus();
		
		$this->load->view('configuracion/status/obtenerStatus',$data);
	}
	
	public function formularioStatus()
	{
		$this->load->view('configuracion/status/formularioStatus');
	}
	
	public function obtenerStatusCrm()
	{
		$data['status']		= $this->configuracion->obtenerStatus($this->input->post('cliente'));
		
		$this->load->view('configuracion/status/obtenerStatusCrm',$data);
	}
	
	public function obtenerStatusEditar()
	{
		$data['status']		= $this->configuracion->obtenerStatusEditar($this->input->post('idStatus'));
		
		$this->load->view('configuracion/status/obtenerStatusEditar',$data);
	}
	
	public function registrarStatus()
	{
		if(!empty ($_POST)) 
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarStatus());
		}
		else echo json_encode(array('0',errorRegistro));
	}
	
	public function editarStatus()
	{
		if(!empty ($_POST))	echo $this->configuracion->editarStatus();
		else echo "0";
	}
	
	public function borrarStatus()
	{
		if(!empty ($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				redirect('principal/permisosUsuario','refresh');
				return;
			}
			
			echo $this->configuracion->borrarStatus($this->input->post('idStatus'));
		}
		else
		{
			echo "0";
		}
	}
	
	
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------STATUS-------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function estatus()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$this->load->view("configuracion/estatus/estatus",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoEstatus()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}

		$this->load->view("configuracion/estatus/estatus",$data);
	}
	
	public function obtenerEstatus()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$data['estatus']		= $this->configuracion->obtenerEstatus($this->input->post('tipo'));
		
		$this->load->view('configuracion/estatus/obtenerEstatus',$data);
	}
	
	public function formularioEstatus()
	{
		$this->load->view('configuracion/estatus/formularioEstatus');
	}
	
	public function obtenerEstatusCrm()
	{
		$data['estatus']		= $this->configuracion->obtenerEstatus($this->input->post('tipo'));
		
		$this->load->view('configuracion/estatus/obtenerEstatusCrm',$data);
	}
	
	public function obtenerEstatusEditar()
	{
		$data['estatus']		= $this->configuracion->obtenerEstatusEditar($this->input->post('idEstatus'));
		
		$this->load->view('configuracion/estatus/obtenerEstatusEditar',$data);
	}
	
	public function registrarEstatus()
	{
		if(!empty ($_POST)) 
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarEstatus());
		}
		else echo json_encode(array('0',errorRegistro));
	}
	
	public function editarEstatus()
	{
		if(!empty ($_POST))	echo $this->configuracion->editarEstatus();
		else echo "0";
	}
	
	public function borrarEstatus()
	{
		if(!empty ($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				redirect('principal/permisosUsuario','refresh');
				return;
			}
			
			echo $this->configuracion->borrarEstatus($this->input->post('idEstatus'));
		}
		else
		{
			echo "0";
		}
	}
	
	//SUBCATEGORÍAS Y CATEGORÍAS
	#----------------------------------------------------------------------------------------------------------#
	#--------------------------------------------------CONVERSION----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function categorias()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['jvalidate']		= $this->_jss['jvalidate'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= 'Categorías';
		
		$this->load->view("configuracion/categorias/categorias",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCategorias()
	{
		$data['categorias']		= $this->configuracion->obtenerCategorias();
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		$this->load->view("configuracion/categorias/obtenerCategorias",$data);
	}
	
	public function formularioCategorias()
	{
		$this->load->view("configuracion/categorias/formularioCategorias");
	}
	
	public function obtenerCategoria()
	{
		if(!empty ($_POST))
		{
			$data['categoria']			= $this->configuracion->obtenerCategoria($this->input->post('idCategoria'));
			
			$this->load->view("configuracion/categorias/obtenerCategoria",$data);
		}
	}
	
	public function registrarCategoria()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarCategoria());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarCategoria()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->configuracion->editarCategoria());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarCategoria()
	{
		if (!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				echo "0";
				return;
			}
	
			echo $this->configuracion->borrarCategoria($this->input->post('idCategoria'));
		}
		else
		{
			echo "0";
		}
	}
	
	//SUBCATEGORÍAS
	public function obtenerSubCategorias()
	{
		if(!empty ($_POST))
		{
			$data['subCategorias']		= $this->configuracion->obtenerSubCategorias($this->input->post('idCategoria'));
			$data['categoria']			= $this->configuracion->obtenerCategoria($this->input->post('idCategoria'));
			$data['permiso']			= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
			
			$this->load->view("configuracion/categorias/subCategorias/obtenerSubCategorias",$data);
		}
	}
	
	public function formularioSubCategorias()
	{
		$this->load->view("configuracion/categorias/subCategorias/formularioSubCategorias");
	}
	
	public function registrarSubCategoria()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarSubCategoria());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}

	public function obtenerSubCategoria()
	{
		if (!empty($_POST))
		{
			$data['subCategoria']		= $this->configuracion->obtenerSubCategoria($this->input->post('idSubCategoria'));
			
			$this->load->view("configuracion/categorias/subCategorias/obtenerSubCategoria",$data);
		}
		else
		{
			echo "0";
		}
	}
	
	public function editarSubCategoria()
	{
		if (!empty($_POST))
		{
			echo json_encode($this->configuracion->editarSubCategoria());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarSubCategoria()
	{
		if (!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				echo "0";
				return;
			}
	
			echo $this->configuracion->borrarSubCategoria($this->input->post('idSubCategoria'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function actualizarLimiteVentas()
	{
		if (!empty($_POST))
		{
			echo $this->configuracion->actualizarLimiteVentas();
		}
		else
		{
			echo "0";
		}
	}
	
	public function autoCompletadoUsuarios()
	{
		$catalogos=$this->configuracion->autoCompletadoUsuarios($this->input->get('term'));
		
		if($catalogos!=null)
		{
			foreach ($catalogos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PARA CFDI 3.3
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function autoCompletadoUnidades()
	{
		$catalogos=$this->configuracion->autoCompletadoUnidades($this->input->get('term'));
		
		if($catalogos!=null)
		{
			foreach ($catalogos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	public function autoCompletadoProductoServicios()
	{
		$catalogos=$this->configuracion->autoCompletadoProductoServicios($this->input->get('term'));
		
		if($catalogos!=null)
		{
			foreach ($catalogos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------PROGRAMAS----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function programas()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][9]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$this->load->view("configuracion/programas/programas",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoProgramas()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][9]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}

		$this->load->view("configuracion/programas/programas",$data);
	}
	
	public function obtenerProgramas($limite=0)
	{
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][9]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$criterio			= $this->input->post('criterio');
		
		$Pag["base_url"]	= base_url()."configuracion/obtenerProgramas/";
		$Pag["total_rows"]	= $this->configuracion->contarProgramas($criterio);
		$Pag["per_page"]	= 10;
		$Pag["num_links"]	= 5;

		$this->pagination->initialize($Pag);

		$data['programas']		= $this->configuracion->obtenerProgramas($Pag["per_page"],$limite,$criterio);
		$data['limite']			= $limite+1;
		
		$this->load->view('configuracion/programas/obtenerProgramas',$data);
	}
	
	public function formularioProgramas()
	{
		$data['periodos']		= $this->configuracion->obtenerPeriodosProgramas();
		$data['grados']			= $this->catalogos->obtenerGrados();
		
		$this->load->view('configuracion/programas/formularioProgramas',$data);
	}
	
	public function obtenerProgramasRegistro()
	{
		$data['programas']		= $this->configuracion->obtenerProgramas();
		
		$this->load->view('configuracion/programas/obtenerProgramasRegistro',$data);
	}
	
	public function obtenerProgramasEditar()
	{
		$data['programa']		= $this->configuracion->obtenerProgramasEditar($this->input->post('idPrograma'));
		$data['periodos']		= $this->configuracion->obtenerPeriodosProgramas();
		$data['grados']			= $this->catalogos->obtenerGrados();
		
		$this->load->view('configuracion/programas/obtenerProgramasEditar',$data);
	}
	
	public function registrarProgramas()
	{
		if(!empty ($_POST)) 
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarProgramas());
		}
		else echo json_encode(array('0',errorRegistro));
	}
	
	public function editarProgramas()
	{
		if(!empty ($_POST))	echo $this->configuracion->editarProgramas();
		else echo "0";
	}
	
	public function borrarProgramas()
	{
		if(!empty ($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				redirect('principal/permisosUsuario','refresh');
				return;
			}
			
			echo $this->configuracion->borrarProgramas($this->input->post('idPrograma'));
		}
		else
		{
			echo "0";
		}
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------CAMPAÑAS-----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function campanas()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][8]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$this->load->view("configuracion/campanas/campanas",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoCampanas()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][8]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}

		$this->load->view("configuracion/campanas/campanas",$data);
	}
	
	public function obtenerCampanas($limite=0)
	{
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][8]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$criterio			= $this->input->post('criterio');
		
		$Pag["base_url"]	= base_url()."configuracion/obtenerCampanas/";
		$Pag["total_rows"]	= $this->configuracion->contarCampanas($criterio);
		$Pag["per_page"]	= 10;
		$Pag["num_links"]	= 5;

		$this->pagination->initialize($Pag);

		$data['campanas']		= $this->configuracion->obtenerCampanas($Pag["per_page"],$limite,$criterio);
		$data['limite']			= $limite+1;
		
		$this->load->view('configuracion/campanas/obtenerCampanas',$data);
	}
	
	public function formularioCampanas()
	{
		$data['programas']		= $this->configuracion->obtenerProgramas();
		
		$this->load->view('configuracion/campanas/formularioCampanas',$data);
	}
	
	public function obtenerCampanasRegistro()
	{
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		
		$this->load->view('configuracion/campanas/obtenerCampanasRegistro',$data);
	}
	
	public function obtenerCampanasEditar()
	{
		$data['campana']		= $this->configuracion->obtenerCampanasEditar($this->input->post('idCampana'));
		$data['programas']		= $this->configuracion->obtenerProgramasCampanas($this->input->post('idCampana'));
		
		$this->load->view('configuracion/campanas/obtenerCampanasEditar',$data);
	}
	
	public function registrarCampanas()
	{
		if(!empty ($_POST)) 
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarCampanas());
		}
		else echo json_encode(array('0',errorRegistro));
	}
	
	public function editarCampanas()
	{
		if(!empty ($_POST))	echo $this->configuracion->editarCampanas();
		else echo "0";
	}
	
	public function borrarCampanas()
	{
		if(!empty ($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				redirect('principal/permisosUsuario','refresh');
				return;
			}
			
			echo $this->configuracion->borrarCampanas($this->input->post('idCampana'));
		}
		else
		{
			echo "0";
		}
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------PROGRAMAS----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function promotores()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][10]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= 'Promotores';

		$this->load->view("configuracion/promotores/promotores",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoPromotores()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][10]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}

		$this->load->view("configuracion/promotores/promotores",$data);
	}
	
	public function obtenerPromotores($limite=0)
	{
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][10]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$criterio			= $this->input->post('criterio');
		$idUsuario			= $this->input->post('idUsuario');
		$idCampana			= $this->input->post('idCampana');
		
		$Pag["base_url"]	= base_url()."configuracion/obtenerPromotores/";
		$Pag["total_rows"]	= $this->configuracion->contarPromotores($criterio,$idUsuario,$idCampana);
		$Pag["per_page"]	= 10;
		$Pag["num_links"]	= 5;

		$this->pagination->initialize($Pag);

		$data['promotores']		= $this->configuracion->obtenerPromotores($Pag["per_page"],$limite,$criterio,$idUsuario,$idCampana);
		$data['usuarios']		= $this->configuracion->obtenerPromotoresRegistro(1);
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		$data['limite']			= $limite+1;
		$data['idUsuario']		= $idUsuario;
		$data['idCampana']		= $idCampana;
		
		$this->load->view('configuracion/promotores/obtenerPromotores',$data);
	}
	
	public function formularioPromotores()
	{
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro(1);
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		
		$this->load->view('configuracion/promotores/formularioPromotores',$data);
	}
	
	public function obtenerPromotoresRegistro()
	{
		$data['promotores']		= $this->configuracion->obtenerPromotores();
		
		$this->load->view('configuracion/promotores/obtenerPromotoresRegistro',$data);
	}
	
	public function obtenerPromotoresEditar()
	{
		$data['promotor']		= $this->configuracion->obtenerPromotoresEditar($this->input->post('idPromotor'));
		
		$this->load->view('configuracion/promotores/obtenerPromotoresEditar',$data);
	}
	
	public function obtenerPromotoresAsignados()
	{
		echo $this->configuracion->obtenerPromotoresAsignados($this->input->post('idPromotor'),$this->input->post('idCampana'));
	}
	
	public function registrarPromotores()
	{
		if(!empty ($_POST)) 
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarPromotores());
		}
		else echo json_encode(array('0',errorRegistro));
	}
	
	public function editarPromotores()
	{
		if(!empty ($_POST))	echo $this->configuracion->editarPromotores();
		else echo "0";
	}
	
	public function borrarPromotores()
	{
		if(!empty ($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				redirect('principal/permisosUsuario','refresh');
				return;
			}
			
			echo $this->configuracion->borrarPromotores($this->input->post('idMeta'));
		}
		else
		{
			echo "0";
		}
	}
	
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------CAUSAS----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function causas()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][10]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= 'Causas';

		$this->load->view("configuracion/causas/causas",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoCausas()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('63',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}

		$this->load->view("configuracion/causas/causas",$data);
	}
	
	public function obtenerCausas($limite=0)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('63',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$criterio		= $this->input->post('criterio');
		$tipo			= $this->input->post('tipo');

		$Pag["base_url"]	= base_url()."configuracion/obtenerCausas/";
		$Pag["total_rows"]	= $this->configuracion->contarCausas($criterio,$tipo);
		$Pag["per_page"]	= 10;
		$Pag["num_links"]	= 5;

		$this->pagination->initialize($Pag);

		$data['causas']		= $this->configuracion->obtenerCausas($Pag["per_page"],$limite,$criterio,0,$tipo);
		$data['limite']		= $limite+1;
		
		$this->load->view('configuracion/causas/obtenerCausas',$data);
	}
	
	public function formularioCausas()
	{
		$this->load->view('configuracion/causas/formularioCausas');
	}
	
	public function obtenerCausasRegistro()
	{
		$tipo			= $this->input->post('tipo');
		
		$data['causas']		= $this->configuracion->obtenerCausas(0,0,'',0,$tipo);
		
		$this->load->view('configuracion/causas/obtenerCausasRegistro',$data);
	}
	
	public function obtenerCausasEditar()
	{
		$data['causa']		= $this->configuracion->obtenerCausasEditar($this->input->post('idCausa'));
		
		$this->load->view('configuracion/causas/obtenerCausasEditar',$data);
	}
	
	public function registrarCausas()
	{
		if(!empty ($_POST)) 
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarCausas());
		}
		else echo json_encode(array('0',errorRegistro));
	}
	
	public function editarCausas()
	{
		if(!empty ($_POST))	echo $this->configuracion->editarCausas();
		else echo "0";
	}
	
	public function borrarCausas()
	{
		if(!empty ($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('63',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				redirect('principal/permisosUsuario','refresh');
				return;
			}
			
			echo $this->configuracion->borrarCausas($this->input->post('idCausa'));
		}
		else
		{
			echo "0";
		}
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------COMISIONES----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function comisiones()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][9]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$this->load->view("configuracion/comisiones/comisiones",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoComisiones()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][13]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}

		$this->load->view("configuracion/comisiones/comisiones",$data);
	}
	
	public function obtenerComisiones($limite=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$idPromotor		= $this->input->post('idPromotor');
		$idCampana		= $this->input->post('idCampana');
		$idPrograma		= $this->input->post('idPrograma');
		$criterio		= $this->input->post('criterio');
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][13]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}

		$Pag["base_url"]	= base_url()."configuracion/obtenerComisiones/";
		$Pag["total_rows"]	= $this->configuracion->contarComisiones($criterio,$idPromotor,$idCampana,$idPrograma,$data['permiso'][17]->activo);
		$Pag["per_page"]	= 20;
		$Pag["num_links"]	= 5;

		$this->pagination->initialize($Pag);

		$data['comisiones']			= $this->configuracion->obtenerComisiones($Pag["per_page"],$limite,$criterio,$idPromotor,$idCampana,$idPrograma,$data['permiso'][17]->activo);
		$data['comisionesTotal']	= $this->configuracion->obtenerComisiones(0,0,$criterio,$idPromotor,$idCampana,$idPrograma,$data['permiso'][17]->activo);
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['programas']			= $this->configuracion->obtenerProgramas();
		$data['usuarios']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['limite']				= $limite+1;
		$data['idPromotor']			= $idPromotor;
		$data['idCampana']			= $idCampana;
		$data['idPrograma']			= $idPrograma;
		$data['registros']			= $Pag["total_rows"];
		
		$this->load->view('configuracion/comisiones/obtenerComisiones',$data);
	}
	
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------PROGRAMAS----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function programasComisiones()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][9]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= 'Comisiones';

		$this->load->view("configuracion/programasComisiones/programas",$data);
		$this->load->view("pie",$Data);
	}

	public function obtenerProgramasComisiones($limite=0)
	{
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][9]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$criterio			= $this->input->post('criterio');
		
		$Pag["base_url"]	= base_url()."configuracion/obtenerProgramasComisiones/";
		$Pag["total_rows"]	= $this->configuracion->contarProgramas($criterio);
		$Pag["per_page"]	= 20;
		$Pag["num_links"]	= 5;

		$this->pagination->initialize($Pag);

		$data['programas']		= $this->configuracion->obtenerProgramas($Pag["per_page"],$limite,$criterio);
		$data['limite']			= $limite+1;
		
		$this->load->view('configuracion/programasComisiones/obtenerProgramas',$data);
	}

	public function obtenerProgramasComisionesEditar()
	{
		$data['programa']		= $this->configuracion->obtenerProgramasEditar($this->input->post('idPrograma'));
		
		$this->load->view('configuracion/programasComisiones/obtenerProgramasEditar',$data);
	}

	public function editarProgramasComisiones()
	{
		if(!empty ($_POST))	echo $this->configuracion->editarProgramasComisiones();
		else echo "0";
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#---------------------------------------------------PERIODOS-----------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function periodos()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		
		if($data['permiso'][5]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$this->load->view("configuracion/periodos/periodos",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoPeriodos()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		
		if($data['permiso'][5]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}

		$this->load->view("configuracion/periodos/periodos",$data);
	}
	
	public function obtenerPeriodos($limite=0)
	{
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		
		if($data['permiso'][5]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$criterio			= $this->input->post('criterio');
		
		$Pag["base_url"]	= base_url()."configuracion/obtenerPeriodos/";
		$Pag["total_rows"]	= $this->configuracion->contarPeriodos($criterio);
		$Pag["per_page"]	= 10;
		$Pag["num_links"]	= 5;

		$this->pagination->initialize($Pag);

		$data['periodos']		= $this->configuracion->obtenerPeriodos($Pag["per_page"],$limite,$criterio);
		$data['limite']			= $limite+1;
		
		$this->load->view('configuracion/periodos/obtenerPeriodos',$data);
	}
	
	public function formularioPeriodos()
	{
		$data['programas']		= $this->configuracion->obtenerProgramas();
		
		$this->load->view('configuracion/periodos/formularioPeriodos',$data);
	}
	
	public function obtenerPeriodosRegistro()
	{
		$data['periodos']		= $this->configuracion->obtenerPeriodos();
		
		$this->load->view('configuracion/periodos/obtenerPeriodosRegistro',$data);
	}
	
	public function obtenerPeriodosEditar()
	{
		$data['periodo']		= $this->configuracion->obtenerPeriodosEditar($this->input->post('idPeriodo'));
		
		$this->load->view('configuracion/periodos/obtenerPeriodosEditar',$data);
	}
	
	public function registrarPeriodos()
	{
		if(!empty ($_POST)) 
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->registrarPeriodos());
		}
		else echo json_encode(array('0',errorRegistro));
	}
	
	public function editarPeriodos()
	{
		if(!empty ($_POST))	echo $this->configuracion->editarPeriodos();
		else echo "0";
	}
	
	public function borrarPeriodos()
	{
		if(!empty ($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				redirect('principal/permisosUsuario','refresh');
				return;
			}
			
			echo $this->configuracion->borrarPeriodos($this->input->post('idPeriodo'));
		}
		else
		{
			echo "0";
		}
	}
	
	//AREAS
	
	public function obtenerConceptosArea()
	{
		$data['conceptos']		= $this->configuracion->obtenerConceptosArea($this->input->post('idArea'));
		
		$this->load->view('clientes/seguimiento/crmTablero/obtenerConceptosArea',$data);
	}
	
	public function revisarCodigoUsuario()
	{
		if(!empty ($_POST)) 
		{
			echo json_encode($this->configuracion->revisarCodigoUsuario());
		}
		else echo json_encode(array('0','Sin respuesta'));
	}
	
	public function revisarAccesoFacturacion()
	{
		if(!empty ($_POST)) 
		{
			echo json_encode($this->configuracion->revisarAccesoFacturacion());
		}
		else echo json_encode(array('-1'));
	}
	
	public function actualizarAccesoFacturacion()
	{
		if(!empty ($_POST)) 
		{
			echo json_encode($this->configuracion->actualizarAccesoFacturacion($this->input->post('accesoFacturacion')));
		}
		else echo json_encode(array('-1'));
	}
	
	public function autoCompletadoUuid()
	{
		$catalogos=$this->configuracion->autoCompletadoUuid($this->input->get('term'));
		
		if($catalogos!=null)
		{
			foreach ($catalogos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
}
?>
