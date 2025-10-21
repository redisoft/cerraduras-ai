<?php
class Proyeccion extends CI_Controller
{
	protected $fecha;
	protected $idUsuario;
	protected $_csstyle;
    protected $_tables;
    protected $_role;
	protected $cuota;

	function __construct()
	{
		parent::__construct();

		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		$this->config->load('datatables', TRUE);	
		$this->config->load('style', TRUE);
		$this->config->load('js',TRUE);
		$this->_jss				= $this->config->item('js');
		 
		$datestring   			= "%Y-%m-%d %H:%i:%s";
	    $this->fecha 			= mdate($datestring,now());
		$this->idUsuario		= $this->session->userdata('id');
		$this->_role 			= $this->session->userdata('role');
		$this->_tables 			= $this->config->item('datatables');
		$this->_csstyle 		= $this->config->item('style');
		
		$this->load->model("crm_modelo","crm");
        $this->load->model("modelousuario","usuarios");
        $this->load->model("modeloclientes","clientes");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("ventas_model","ventas");
		$this->load->model("motivos_modelo","motivos");
		$this->load->model("sie_modelo","sie");
		$this->load->model("reportes_model","reportes");
		$this->load->model("proyeccion_modelo","proyeccion");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
  	}

	
	public function index()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->usuarios->getUsuarios($this->idUsuario);
		$Data['Fecha_actual'] 	= $this->fecha;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'proyeccion'; 
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('65',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$this->load->view("sie/proyeccion/index", $data);
		$this->load->view("pie", $Data);
	}
	
	public function obtenerIngresos($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$cobrado				= $this->input->post('cobrado');
		$idEscenario			= $this->input->post('idEscenario');
		
		$Pag["base_url"]		= base_url()."proyeccion/obtenerIngresos/";
		$Pag["total_rows"]		= $this->proyeccion->contarIngresos($inicio,$fin,$criterio,$idEscenario);
		$Pag["per_page"]		= 15;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;
		
		$this->pagination->initialize($Pag);

		$data['ingresos'] 		= $this->proyeccion->obtenerIngresos($Pag["per_page"],$limite,$inicio,$fin,$criterio,$idEscenario);
		$data['total'] 			= $this->proyeccion->sumarIngresos($inicio,$fin,$criterio,$idEscenario);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('65',$this->session->userdata('rol'));
		$data['inicio']  		= $limite+1;
		
		$data['cobrado']  		= $idEscenario==0?$cobrado:-1;
		$data['idEscenario']  	= $idEscenario;

		$this->load->view("sie/proyeccion/ingresos/obtenerIngresos",$data);
	}
	
	public function formularioIngresos()
	{
		$data['escenarios'] 		= $this->proyeccion->obtenerEscenariosIngresos();
		
		$this->load->view("sie/proyeccion/ingresos/formularioRegistro",$data);
	}
	
	public function registrarIngreso() #Almacenar un nuevo material
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proyeccion->registrarIngreso());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerIngreso()
	{
		$data['escenarios'] 	= $this->proyeccion->obtenerEscenariosIngresos();
		$data['registro'] 		= $this->proyeccion->obtenerIngreso($this->input->post('idIngreso'));
		
		$this->load->view("sie/proyeccion/ingresos/formularioEditar",$data);
	}
	
	public function editarIngreso() #Almacenar un nuevo material
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proyeccion->editarIngreso());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarIngreso() #Almacenar un nuevo material
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proyeccion->borrarIngreso($this->input->post('idIngreso')));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerIngresoCobrado()
	{
		$data['registro'] 		= $this->proyeccion->obtenerIngreso($this->input->post('idIngreso'));
		$data['cuentas']		= $this->configuracion->obtenerCuentasSie();
		$data['financiera']		= $this->sie->obtenerFinanciera();
		
		$this->load->view("sie/proyeccion/ingresos/obtenerIngresoCobrado",$data);
	}
	
	public function definirIngresoCobrado()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proyeccion->definirIngresoCobrado());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}

	//EGRESOS
	public function obtenerEgresos($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$tipoFecha				= $this->input->post('tipoFecha');
		$pagado					= $this->input->post('pagado');
		$idEscenario			= $this->input->post('idEscenario');
		
		$Pag["base_url"]		= base_url()."proyeccion/obtenerEgresos/";
		$Pag["total_rows"]		= $this->proyeccion->contarEgresos($inicio,$fin,$criterio,$tipoFecha,$pagado,$idEscenario);
		$Pag["per_page"]		= 15;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;
		
		$this->pagination->initialize($Pag);

		$data['egresos'] 		= $this->proyeccion->obtenerEgresos($Pag["per_page"],$limite,$inicio,$fin,$criterio,$tipoFecha,$pagado,$idEscenario);
		$data['total'] 			= $this->proyeccion->sumarEgresos($inicio,$fin,$criterio,$tipoFecha,$pagado,$idEscenario);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('65',$this->session->userdata('rol'));
		$data['inicio']  		= $limite+1;
		$data['pagado']  		= $idEscenario==0?$pagado:-1;
		$data['idEscenario']  	= $idEscenario;

		$this->load->view("sie/proyeccion/egresos/obtenerEgresos",$data);
	}
	
	public function formularioEgresos()
	{
		$data['escenarios'] 		= $this->proyeccion->obtenerEscenariosEgresos();
		
		$this->load->view("sie/proyeccion/egresos/formularioRegistro",$data);
	}
	
	public function registrarEgreso() #Almacenar un nuevo material
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proyeccion->registrarEgreso());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerEgreso()
	{
		$data['escenarios'] 	= $this->proyeccion->obtenerEscenariosEgresos();
		$data['registro'] 		= $this->proyeccion->obtenerEgreso($this->input->post('idEgreso'));
		
		$this->load->view("sie/proyeccion/egresos/formularioEditar",$data);
	}
	
	public function obtenerEgresoPagado()
	{
		$data['cuentas']		= $this->configuracion->obtenerCuentasSie();
		$data['registro'] 		= $this->proyeccion->obtenerEgreso($this->input->post('idEgreso'));
		$data['financiera']		= $this->sie->obtenerFinanciera();
		
		$this->load->view("sie/proyeccion/egresos/obtenerEgresoPagado",$data);
	}
	
	public function editarEgreso() #Almacenar un nuevo material
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proyeccion->editarEgreso());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarEgreso() 
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proyeccion->borrarEgreso($this->input->post('idEgreso')));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function definirEgresoPagado()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proyeccion->definirEgresoPagado($this->input->post('idEgreso')));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarFechaPago() 
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proyeccion->editarFechaPago($this->input->post('idEgreso')));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
}
?>
