<?php
class Creditos extends CI_Controller
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
		$this->load->model("catalogos_modelo","catalogos");
		$this->load->model("sie_modelo","sie");
		$this->load->model("reportes_model","reportes");
		$this->load->model("creditos_modelo","creditos");
		
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
		$Data['menuActivo']		= 'creditos'; 
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('66',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$this->load->view("sie/creditos/index", $data);
		$this->load->view("pie", $Data);
	}
	
	public function obtenerCreditos($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$Pag["base_url"]		= base_url()."creditos/obtenerCreditos/";
		$Pag["total_rows"]		= $this->creditos->contarCreditos();
		$Pag["per_page"]		= 15;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;
		
		$this->pagination->initialize($Pag);

		$data['creditos'] 		= $this->creditos->obtenerCreditos($Pag["per_page"],$limite);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('66',$this->session->userdata('rol'));
		$data['inicio']  		= $limite+1;

		$this->load->view("sie/creditos/obtenerCreditos",$data);
	}
	
	public function formularioCreditos()
	{
		$data['frecuencia'] 		= $this->catalogos->obtenerFrecuencias();
		
		$this->load->view("sie/creditos/formularioRegistro",$data);
	}
	
	public function registrarCredito() #Almacenar un nuevo material
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->creditos->registrarCredito());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerCredito()
	{
		$data['registro'] 		= $this->creditos->obtenerCredito($this->input->post('idCredito'));
		$data['frecuencia'] 	= $this->catalogos->obtenerFrecuencias();
		
		$this->load->view("sie/creditos/formularioEditar",$data);
	}
	
	public function editarCredito() #Almacenar un nuevo material
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->creditos->editarCredito());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarCredito() #Almacenar un nuevo material
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->creditos->borrarCredito($this->input->post('idCredito')));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
}
?>
