<?php
class Redisoft extends CI_Controller
{
    private $_template;
    protected $_fechaActual;
    protected $_iduser;
    protected $_csstyle;
	protected $base;

    function __construct()
	{
		parent::__construct();

		$this->config->load('style', TRUE);
		$this->config->load('js',TRUE);
		
		$this->_fechaActual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_iduser		 	= $this->session->userdata('id');
		$this->_csstyle 		= $this->config->item('style');
		$this->_jss				=$this->config->item('js');
		
		$this->load->model("redisoft_modelo","redisoft");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("modelousuario","modelousuario");
		
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
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='redisoft'; 
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('25',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		#$this->load->view("redisoft/index", $data);
		$this->load->view("pie", $Data);
	}
	
	
	public function obtenerEstadisticas()
	{
		$data['usuarios']	= $this->redisoft->obtenerUsuarios();
		$data['emisores']	= $this->redisoft->obtenerEmisores();
		$data['mensaje']	= $this->redisoft->obtenerMensaje();
		
		$this->load->view("redisoft/obtenerEstadisticas", $data);
	}
	
	public function editarMensaje()
	{
		if(!empty ($_POST))
		{
			echo $this->redisoft->editarMensaje();
		}
	}
	

}
?>
