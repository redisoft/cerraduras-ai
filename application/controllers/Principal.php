<?php
class Principal extends CI_Controller
{
	private $_template;
	protected $_fechaActual;
	protected $_iduser;
	protected $_csstyle;
	protected $_jss;	
	protected $idLicencia;
	protected $cuota;
	
	function __construct()
	{
		parent::__construct();
		
		#if(!$this->redux_auth->logged_in())
		#{
 		#	redirect(base_url().'login');
 		#}
		
		if($this->session->userdata('id')=="")
		{
 			redirect(base_url().'login');
 		} 		 		
        
		$this->config->load('style', TRUE);
		$this->config->load('js',TRUE);
		
		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->_fechaActual 	= mdate($datestring,now());
	    $this->_iduser 			= $this->session->userdata('id');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		$this->_csstyle 		= $this->config->item('style');
		$this->_jss				=$this->config->item('js');
		
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("inventarioproductos_modelo","inventario");
		$this->load->model("produccion_modelo","produccion");
		$this->load->model("ventas_model","ventas");
		$this->load->model("tablero_modelo","tablero");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
 	}
	
	public function tokenGoogle()
	{
		echo 'Tokencito: '.$this->session->userdata('oauth_access_token');
	}
	
	public function index()
	{
		$Data['title']				= "Panel de Administración";	
		$Data['cassadmin']			= $this->_csstyle["cassadmin"];
		$Data['csmenu']				= $this->_csstyle["csmenu"];
		$Data['csui']				= $this->_csstyle["csui"];
		$Data['csvalidate']			= $this->_csstyle["csvalidate"];
		$Data['Jry']				= $this->_jss['jquery'];	  	 	
		$Data['Jqui']				= $this->_jss['jqueryui'];   
		$Data['nameusuario']		= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		= $this->_fechaActual;
		#$Data['ventas'] 			= $this->ventas->obtenerVentasUnidades();
		$Data['productos'] 			= null; #$this->inventario->obtenerProductosGraficas();
		$Data['menuActivo'] 		= 'inicio';
		
		$usuario					= $this->configuracion->obtenerUsuario($this->_iduser);
		$rol						= $this->configuracion->obtenerRol($usuario->idRol); // Obtener el rol para mostrar o no las graficas
		
		$this->session->set_userdata('rol',$usuario->idRol);
		$this->session->set_userdata('nombreUsuarioSesion',$usuario->nombre.' '.$usuario->apellidoPaterno.' '.$usuario->apellidoMaterno);
		$this->session->set_userdata('usuarioActivo',$usuario->usuario);
		$this->session->set_userdata('superAdministradorProducsoft',$usuario->superAdmin);
 		$this->session->set_userdata('idCotizacionRemision',"0");
		$this->session->set_userdata('idFacturaImpresion',"0");
		$this->session->set_userdata('idTiendaActiva','0');
		$this->session->set_userdata('nombreTiendaActiva','');
		$this->session->set_userdata('usuarioTienda','0');
		
		$this->session->set_userdata('checador',$usuario->checador);
		$this->session->set_userdata('reportes',$usuario->reportes);
		
		if($usuario->idTienda!=0)
		{
			$this->session->set_userdata('idTiendaActiva',$usuario->idTienda);
			$this->session->set_userdata('nombreTiendaActiva','');
			$this->session->set_userdata('usuarioTienda','1');
		}
		
		$permisos	= $this->configuracion->obtenerPermisosRolAcceso($this->session->userdata('rol'));
		
		#Criterios de ordenamiento
		$this->session->set_userdata('criterioProspectos','a');
		$this->session->set_userdata('criterioClientes','a');
		$this->session->set_userdata('criterioProductos','a');
		$this->session->set_userdata('criterioProveedores','a');
		$this->session->set_userdata('criterioMateriales','a');
		
		$this->session->set_userdata('criterioVentas','z');
		$this->session->set_userdata('criterioCompras','z');
		$this->session->set_userdata('criterioCobranza','z');
		$this->session->set_userdata('criterioFacturas','a');
		$this->session->set_userdata('criteriIngresos','a');
		$this->session->set_userdata('criterioEgresos','a');
		
		$row	= $this->configuracion->obtenerConfiguracion();
		
		$this->session->set_userdata('iva',$row['factor']); 
		$this->session->set_userdata('nombreEmpresa',$row['nombre']); 
		$this->session->set_userdata('identificador',$row['identificador']);  
		$this->session->set_userdata('estilo',$row['color']);  
		$this->session->set_userdata('logotipo',$row['id']."_".$row['logotipo']);
		$this->session->set_userdata('codigoBorrado',$row['codigoBorrado']);  
		$this->session->set_userdata('codigoEditar',$row['codigoEditar']);
		$this->session->set_userdata('codigoImportar',$row['codigoImportar']);
		$this->session->set_userdata('codigoCancelar',$usuario->claveCancelacion);  
		$this->session->set_userdata('conexionGmail',$row['conexionGmail']);    
		$this->session->set_userdata('notificacionesActivas',$row['notificaciones']);
		$this->session->set_userdata('ordenProductos',$row['ordenProductos']);
		$this->session->set_userdata('tiendaLocal',$row['tiendaLocal']);
		$this->session->set_userdata('impresoraLocal',$row['impresoraLocal']);
		
		$this->session->set_userdata('registroVentas',$row['registroVentas']);
		$this->session->set_userdata('idCookie',$row['idCookie']);
		
		if(isset($row['precios']))
		{
			$this->session->set_userdata('precios',$row['precios']);
		}
		else
		{
			$this->session->set_userdata('precios',0);
		}
		
		
		#Facturación
		$this->session->set_userdata('serie',$row['serie']); 
		$this->session->set_userdata('folioInicio',$row['folioInicio']); 
		$this->session->set_userdata('folioFinal',$row['folioFinal']);  
		
		$fecha=$row['fechaCosto'];
		
		$this->session->set_userdata('gastoMes',substr($fecha,5,2));
		$this->session->set_userdata('gastoAnio',substr($fecha,0,4));
		
		#-------------------------------------DETECTAR LOS MENUS ACTIVOS------------------------------------------#
		#return;
		
		/*if($usuario->idRol==5)
		{
			redirect('pedidos');
		}
		
		if($usuario->idRol==6)
		{
			redirect('compras/administracion');
		}*/
		
		//EL USUARIO ESTA RESERVADO PARA IEXE
		if($usuario->usuario=='conta')
		{
			redirect('globales','refresh');
		}
		
		
		
		#exit;
		
		
		if($usuario->idRol==3)
		{
			redirect('administracion/checador');
		}
		
		#redirect('dashboard','refresh');
		
		foreach($permisos as $row)
		{
			if($row->activo=='1')
			{
				$parametro="";
				
				/*switch($row->url)
				{
					case "compras/precompras/":
						$parametro	= date('Y-m-d').'/'.date('Y-m-d');
					break;
					
					default:
						$parametro	= '';
					break;
				}*/

				redirect($row->url.$parametro,'refresh');
			}
		}
		
		
		
		/*foreach($permisos as $row)
		{
			if($row->leer==1 or $row->escribir==1)
			{
				$parametro="";
				
				switch($row->url)
				{
					case "reportes/cobranza":
					$parametro	= date('Y-m-d');
					break;
					
					case "principal/tableroControl/":
					$parametro	= date('Y-m-d');
					break;
					
					default:
					$parametro	= '';
					break;
				}
				
				if($this->session->userdata('rol')==1)
				{
					redirect('principal/tableroControl/','refresh');
				}
				
				redirect($row->url.$parametro,'refresh');
			}
		}*/
		
		redirect('login/logout','refresh');
	}

	public function conectarApiGoogle()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['menuActivo']		= 'dashboard'; 
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		$this->load->view('google/conectarApi');
		$this->load->view("pie", $Data);
	}

	public function tableroControl()
	{
		if(!$this->session->userdata('oauth_access_token') and $this->session->userdata('conexionGmail')=='1')
		{
			redirect('principal/conectarApiGoogle','refresh');
		}
		
		$Data['title']			= "Panel de Administración";	
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['Jry']			= $this->_jss['jquery'];	  	 	
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		#$Data['ventas'] 		= $this->ventas->obtenerVentasUnidades();
		$Data['productos'] 		= null;#$this->inventario->obtenerProductosGraficas();
		$Data['menuActivo'] 	= 'dashboard';
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$usuario				= $this->configuracion->obtenerUsuario($this->_iduser);
		$rol					= $this->configuracion->obtenerRol($usuario->idRol); 
		
		if($usuario->checador=='1')
		{
			redirect('administracion/checador','refresh');
		}
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		#-------------------------------------DETECTAR LOS MENUS ACTIVOS------------------------------------------#
		
		$data['permiso']	=$this->configuracion->obtenerPermisosBoton('1',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['idUsuario']		= $this->_iduser;
		$data["breadcumb"]		= 'Dashboard';
		
		
		$this->load->view('dashboard/dashboard',$data);
		$this->load->view("pie",$Data);		
	}
	
	public function calendario()
	{
		if(!$this->session->userdata('oauth_access_token') and $this->session->userdata('conexionGmail')=='1')
		{
			redirect('principal/conectarApiGoogle','refresh');
		}
		
		$Data['title']			= "Panel de Administración";	
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['Jry']			= $this->_jss['jquery'];	  	 	
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		#$Data['ventas'] 		= $this->ventas->obtenerVentasUnidades();
		#$Data['productos'] 		= $this->inventario->obtenerProductosGraficas();
		$Data['productos'] 		= null;#$this->inventario->obtenerProductosGraficas();
		$Data['menuActivo'] 	= 'calendario';
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$usuario				= $this->configuracion->obtenerUsuario($this->_iduser);
		$rol					= $this->configuracion->obtenerRol($usuario->idRol); 
		
		if($usuario->checador=='1')
		{
			redirect('administracion/checador','refresh');
		}
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		#-------------------------------------DETECTAR LOS MENUS ACTIVOS------------------------------------------#
		
		$data['permiso']	=$this->configuracion->obtenerPermisosBoton(59,$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			/*$permiso	=$this->configuracion->obtenerPermisoActivo($this->session->userdata('rol'));
			
			if($permiso!=null)
			{
				redirect($permiso->url,'refresh');
			}
			else
			{
				$this->load->view('accesos/index');
				$this->load->view("pie",$Data);
				return;
			}*/
			
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['status']			= $this->configuracion->obtenerStatus(0);
		$data['statusErp']		= $this->configuracion->obtenerStatus(1);
		$data['statusPw']		= $this->configuracion->obtenerStatus(2);
		$data['servicios']		= $this->configuracion->obtenerServicios();
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['mensaje']		= $this->configuracion->obtenerMensaje();
		$data['idUsuario']		= $this->_iduser;
		$data["breadcumb"]		= 'Dashboard';
		
		
		$this->load->view('tableroControl',$data);
		$this->load->view("pie",$Data);		
	}
	
	
	
	function admin()
	{
		if($this->idLicencia!=1)
		{
			redirect('principal/index/'.date('Y'));
		}
		
		$Data['title']= "Panel de Administración";	
		$Data['cassadmin']=$this->_csstyle["cassadmin"];
		$Data['csmenu']=$this->_csstyle["csmenu"];
		$Data['csui']=$this->_csstyle["csui"];
		$Data['Jry']=$this->_jss['jquery'];
		#$Data['JFuntInventario']=$this->_jss['JFuntInventario'];
		
		$Data['Jquical']=$this->_jss['jquerycal'];
		$Data['permisos']=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		
		$Data['Jqui']=$this->_jss['jqueryui'];                  
		$Data['jFicha_cliente']=$this->_jss['jFicha_cliente'];
		$Data['nameusuario']=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']=$this->_fechaActual;
		
		$data['licencias']=$this->configuracion->obtenerLicencias();
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		$this->load->view('admin',$data);
		$this->load->view("pie",$Data);	
	}//function
	
	public function agregarLicencia()
	{
		if(!empty($_POST))
		{
			$licencia=$this->configuracion->agregarLicencia();
			echo $licencia;
		}
	}
	
	public function graficaProduccion()
	{
		if(!empty($_POST))
		{
			$productos=$this->produccion->obtenerGraficaProduccion();
			
			$this->session->set_userdata('productosGrafica',$productos);
			
			print("1");
		}
		else
		{
			print("0");
		}
	}

	public function permisosUsuario()
	{
		$Data['title']= "Panel de Administración";
		$Data['cassadmin']=$this->_csstyle["cassadmin"];
		$Data['csmenu']=$this->_csstyle["csmenu"];
		
		$Data['csui']=$this->_csstyle["csui"];
		////$Data['csuidemo']=$this->_csstyle["csuidemo"];
		
		$Data['nameusuario']=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']=$this->_fechaActual;
		
		$Data['Jry']=$this->_jss['jquery'];
		$Data['Jqui']=$this->_jss['jqueryui'];
		$Data['jFicha_cliente']=$this->_jss['jFicha_cliente'];
		$Data['Jquical']=$this->_jss['jquerycal'];
		
		$Data['permisos']=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		$this->load->view('accesos/index');
		$this->load->view("pie",$Data);
	}
	
	public function obtenerDashboard()
	{
		if(!empty($_POST))
		{
			$this->load->view('dashboard/obtenerDashboard');
		}
		else
		{
			
		}
	}

}
?>
