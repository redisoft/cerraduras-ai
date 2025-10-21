<?php
class Servicios extends CI_Controller
{
    private $_template;
    protected $_fechaActual;
    protected $_iduser;
    protected $_csstyle;
	protected $cuota;

	function __construct()
	{
		parent::__construct();

		if( ! $this->redux_auth->logged_in() )
		{
			redirect(base_url().'login');
		}
		
		$this->config->load('style', TRUE);
		$this->config->load('js',TRUE);
		
		$this->_fechaActual = mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_iduser 		= $this->session->userdata('id');
		$this->_csstyle 	= $this->config->item('style');
		$this->_jss			= $this->config->item('js');
		
		$this->load->model("crm_modelo","crm");
		$this->load->model("compras_modelo","compras");
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("servicios_modelo","servicios");
		$this->load->model("reportes_model","reportes");
		$this->load->model("modeloclientes","modeloclientes");
		$this->load->model("proveedores_model","proveedores");
		$this->load->model("contabilidad_modelo","contabilidad");
		$this->load->model("modelo_configuracion","configuracion");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}
	

	#SERVICIOS
	#====================================================================================================
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
		$Data['menuActivo']		= 'inventarios'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);

		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('51',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$this->load->view("servicios/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerServicios($limite=0)
	{
		$criterio	= $this->input->post('criterio');
		#----------------------------------PAGINACION------------------------------------#
		$url		= base_url()."servicios/obtenerServicios/";
		$registros	= $this->servicios->contarServicios($criterio);
		$numero		= 20;
		$links		= 5;
		$uri		= 3;
		
		$paginador	= $this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('51',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			
			return;
		}
		
		$data['servicios'] 	= $this->servicios->obtenerServicios($numero,$limite,$criterio);
		$data['limite']  	= $limite+1;
		
		$this->load->view("servicios/obtenerServicios",$data);
	}
	
	public function obtenerCatalogoServicios()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('51',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			
			return;
		}

		$this->load->view("servicios/index",$data);
	}
	
	public function formularioServicios()
	{
		$data['unidades']		= $this->configuracion->seleccionarUnidades();
		
		$this->load->view("servicios/formularioServicios",$data);
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
			
			echo json_encode($this->servicios->registrarServicio());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerServicio()
	{
		$data['servicio']		= $this->servicios->obtenerServicio($this->input->post('idServicio'));
		$data['unidades']		= $this->configuracion->seleccionarUnidades();
		
		$this->load->view("servicios/obtenerServicio",$data);
	}
	
	public function editarServicio()
	{
		if(!empty ($_POST))
		{
			echo $this->servicios->editarServicio();
		}
		else
		{
			echo "0";
		}
	}

	public function borrarServicio()
	{
		if(!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('51',$this->session->userdata('rol'));
			
			if($data['permiso'][0]->activo=='0')
			{
				$this->load->view('accesos/index');
				return;
			}
			
			echo $this->servicios->borrarServicio($this->input->post('idServicio'));
		}
		else
		{
			echo "0";
		}
	}
	
	//ASOCIAR PROVEEDORES  A SERVICIOS
	public function formularioAgregarProveedor()
	{
		$data['servicios']		= $this->servicios->obtenerServiciosProveedores($this->input->post('idServicio'));
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('51',$this->session->userdata('rol'));
		$data['idServicio']		= $this->input->post('idServicio');
		$data['opciones']		= $this->input->post('opciones');
		
		$this->load->view("servicios/proveedores/formularioAgregarProveedor",$data);
	}
	
	public function asociarProveedorServicio()
	{
		if(!empty ($_POST))
		{
			
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->servicios->asociarProveedorServicio());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarCostoProveedorServicio()
	{
		if(!empty ($_POST))
		{
			echo $this->servicios->editarCostoProveedorServicio();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarProveedorServicio()
	{
		if(!empty ($_POST))
		{
			echo $this->servicios->borrarProveedorServicio();
		}
		else
		{
			echo "0";
		}
	}
	
	//COMPRAS DE SERVICIOS
	
	public function compras($fecha='fecha',$idCompras=0,$idProveedor=0,$limite=0)
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['JFuntInventario']= $this->_jss['JFuntInventario'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];                  
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'comprasServicios'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PAGINACION------------------------------------#
		$url					= base_url()."servicios/compras/".$fecha.'/'.$idCompras.'/'.$idProveedor;
		$registros				= $this->servicios->contarCompras($fecha,$idCompras,$idProveedor);
		$numero					= 20;
		$links					= 5;
		$uri					= 6;
		
		$paginador				= $this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('52',$this->session->userdata('rol'));
		$data['permisoServicio']	= $this->configuracion->obtenerPermisosBoton('17',$this->session->userdata('rol'));
		$data['permisoCrm']			= $this->configuracion->obtenerPermisosBoton('9',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['compras'] 		= $this->servicios->obtenerCompras($numero,$limite,$fecha,$idCompras,$idProveedor);
		$data['orden'] 			= $this->compras->obtenerConsecutivoCompras();
		$data['configuracion'] 	= $this->configuracion->obtenerConfiguraciones(1);
		$data['inicio']  		= $limite;
		$data['fecha']  		= $fecha;
		$data['idCompras']  	= $idCompras;
		$data['idProveedor']  	= $idProveedor;
		$data["breadcumb"]		= '<a href="'.base_url().'compras"> Compras </a> > Compras de servicios';

		$this->load->view("compras/servicios/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}
	
	public function obtenerServiciosCompra($limite=0)
	{
		$criterio		= $this->input->post('criterio');
		$idProveedor	= $this->input->post('idProveedor');
		#----------------------------------PAGINACION------------------------------------#
		$url			= base_url()."servicios/obtenerServicios/";
		$registros		= $this->servicios->contarServiciosCompra($criterio,$idProveedor);
		$numero			= 20;
		$links			= 5;
		$uri			= 3;
		
		$paginador		= $this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('52',$this->session->userdata('rol'));
			
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['servicios'] 	= $this->servicios->obtenerServiciosCompra($numero,$limite,$criterio,$idProveedor);
		$data['limite']  	= $limite+1;
		
		$this->load->view("compras/servicios/obtenerServiciosCompra",$data);
	}
	
	public function registrarCompra()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$compra=$this->servicios->registrarCompra();
			
			if($compra[0]=="1")
			{
				$this->session->set_userdata('notificacion','La compra se ha registrado correctamente');
			}
			
			echo json_encode($compra);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerProductosComprados()
	{
		if(!empty($_POST))
		{
			$idCompras			= $this->input->post('idCompras');
			$data['compras']	= $this->compras->obtenerServiciosComprados($idCompras);
			$data['compra']		= $this->compras->obtenerCompra($idCompras);
			$data['idCompras']	= $idCompras;

			$this->load->view('compras/servicios/obtenerServiciosComprados',$data);
		}
	}
	
	public function formularioRecibirTodosServicios()
	{
		if(!empty($_POST))
		{
			$data['compra']		= $this->compras->obtenerCompra($this->input->post('idCompras'));

			$this->load->view('compras/servicios/formularioRecibirTodosServicios',$data);
		}
	}
	
	public function recibirTodosServicios()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->servicios->recibirTodosServicios());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function productosRecibidos()
	{
		$idDetalle			= $this->input->post('idDetalle');
		$data['recibidos']	= $this->compras->productosRecibidos($idDetalle);
		$data['servicio']	= $this->compras->obtenerServicioCompra($idDetalle);
		$data['compra']		= $this->compras->obtenerCompra($data['servicio']->idCompra);
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('12',$this->session->userdata('rol'));
		$data['idDetalle']	= $idDetalle;
		
		$this->load->view('compras/servicios/serviciosRecibidos',$data);
	}
	
	public function confirmarRecibirCompra()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->servicios->confirmarRecibirServicio());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarServicioRecibido()
	{
		if(!empty($_POST))
		{
			echo $this->servicios->borrarServicioRecibido();
		}
		else
		{
			echo '0';
		}
	}
}
?>