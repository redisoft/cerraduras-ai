<?php
class Usuario extends CI_Controller
{
	protected $_fechaActual;
	protected $_iduser;
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
		
		$datestring   = "%Y-%m-%d %H:%i:%s";
		$this->_fechaActual = mdate($datestring,now());
		$this->_iduser = $this->session->userdata('id');
		$this->_role = $this->session->userdata('role');
		$this->config->load('datatables', TRUE);
		$this->_tables = $this->config->item('datatables');
		$this->config->load('style', TRUE);
		$this->_csstyle = $this->config->item('style');
		$this->config->load('js',TRUE);
		$this->_jss=$this->config->item('js');
		
		$this->load->model("modelousuario","usuarios");
		$this->load->model("modeloclientes","modeloclientes");
		$this->load->model("modelo_configuracion","configuracion");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}

	function index()
	{
		$Data['title']= "Panel de Administración";
		$Data['cassadmin']=$this->_csstyle["cassadmin"];
		$Data['csmenu']=$this->_csstyle["csmenu"];
		$Data['csvalidate']=$this->_csstyle["csvalidate"];
		$Data['csui']=$this->_csstyle["csui"];
		$Data['nameusuario']=$this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']=$this->_fechaActual;
		$Data['Jry']=$this->_jss['jquery'];
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		$Conte['Categoria']=$this->uri->segment(1);
		$Conte['Clientes']=$this->modeloclientes->getAllUsuario();
		
		$this->load->view("clientes/index",$Conte); //principal lista de clientes
		$this->load->view("pie",$Data);
	}

	public function add()
	{
		$Data['title']= "Panel de Administración";
		$Data['cassadmin']=$this->_csstyle["cassadmin"];
		$Data['csmenu']=$this->_csstyle["csmenu"];
		$Data['csvalidate']=$this->_csstyle["csvalidate"];
		$Data['nameusuario']=$this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']=$this->_fechaActual;
		$Data['Jry']=$this->_jss['jquery'];
		$Data['jvalidate']=$this->_jss['jvalidate'];

		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		$Conte['Categoria']=$this->uri->segment(1);
		$Conte['Serie']=$this->modeloclientes->getGeneraSerieID();
		
		$this->load->view("usuarios/add",$Conte);
		$this->load->view("pie",$Data);
	}//Finn del ADD

	public function saveNewuser()
	{
		if(!empty ($_POST))
		{
			if($this->usuarios->addNuevoUsuario() != NULL)
			{
				$this->session->set_flashdata('message', array('messageType' => 'success','Message' => 'El usuario se ha almacenado correctamente.'));
			}
			else
			{
				$this->session->set_flashdata('message', array('messageType' => 'error','Message' => 'Ocurrio un error al guardar el registro.'));
			}
			
			redirect('usuario','refresh');
		}
		else 
		{
			redirect('usuario/add','refresh');
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//HORARIOS DE USUARIOS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function obtenerHorarios()
	{
		$idUsuario				= $this->input->post('idUsuario');
		$data['horarios']		= $this->usuarios->obtenerHorarios($idUsuario);
		$data['usuario']		= $this->usuarios->obtenerUsuario($idUsuario);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		$this->load->view("configuracion/usuarios/horarios/obtenerHorarios",$data);
	}
	
	public function registrarHorario()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->usuarios->registrarHorario());
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function editarHorario()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->usuarios->editarHorario());
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarHorario()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->usuarios->borrarHorario($this->input->post('idHorario')));
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
}
?>
