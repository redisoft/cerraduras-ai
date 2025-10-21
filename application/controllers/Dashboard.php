<?php
class Dashboard extends CI_Controller
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
		
		if(!$this->redux_auth->logged_in())
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
		$this->_jss				= $this->config->item('js');
		
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("reportes_model","reportes");
		$this->load->model("bancos_model","bancos");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
 	}
	

	public function index()
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
		$Data['menuActivo'] 	= 'dashboard';
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$usuario				= $this->configuracion->obtenerUsuario($this->_iduser);
		$rol					= $this->configuracion->obtenerRol($usuario->idRol); 
		
		if($usuario->checador=='1')
		{
			redirect('administracion/checador','refresh');
		}
		
		if($usuario->reportes=='1')
		{
			redirect('reportes/ventasReporte','refresh');
		}
		
		if(sistemaActivo=='pinata')
		{
			redirect(base_url()."ventas/puntoVenta/0","refresh");
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
		$data['cuentasBanco']	= $this->bancos->obtenerCuentas();
		$data["breadcumb"]		= 'Dashboard';
		#print_r($data);
		
		$this->load->view('dashboard/dashboard',$data);
		$this->load->view("pie",$Data);		
	}

	public function obtenerDashboard()
	{
		if(!empty($_POST))
		{
			$inicio						= $this->input->post('inicio');
			$fin						= $this->input->post('fin');
			$idCuenta					= $this->input->post('idCuenta');
			
			$data['ventas']				= $this->reportes->obtenerVentasMeses($inicio,$fin);
			$data['ventasSemana']		= $this->reportes->obtenerVentasSemana();
			$data['facturas']			= $this->reportes->obtenerFacturasMeses($inicio,$fin);
			$data['ventasProductos']	= $this->reportes->obtenerVentasProductos();
			
			#if(sistemaActivo)
			/*$data['egresos']			= $this->reportes->obtenerEgresosMeses($inicio,$fin);
			$data['ingresos']			= $this->reportes->obtenerIngresosMeses($inicio,$fin);*/
			
			$data['egresos']			= $this->reportes->obtenerEgresosMesesDepartamento($inicio,$fin);
			$data['ingresos']			= $this->reportes->obtenerIngresosMesesDepartamento($inicio,$fin);

			$data['numeroMeses']		= $this->reportes->obtenerDiferenciaFechas($inicio.'-01',$fin.'-01','month');
			$data['meses']				= $this->reportes->obtenerMesesCriterio($data['numeroMeses'],$inicio.'-01');
			$data['ingresosEgresos']	= $this->reportes->obtenerIngresosEgresos();
			$data['idCuenta']			= $idCuenta;
			
			if(sistemaActivo=='IEXE')
			{
				$this->load->view('dashboard/obtenerDashboardIexe',$data);	
			}
			else
			{
				$this->load->view('dashboard/obtenerDashboard',$data);
			}
			
		}
		else
		{
			echo 'Error';
		}
	}
	
	public function obtenerGraficaClientes()
	{
		$data['clientes']	= $this->reportes->obtenerClientesTipos();
		
		$this->load->view('dashboard/obtenerGraficaClientes',$data);
	}

}
?>
