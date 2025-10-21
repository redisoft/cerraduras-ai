<?php
class Matricula extends CI_Controller
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
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("motivos_modelo","motivos");
		$this->load->model("sie_modelo","sie");
		$this->load->model("proyeccion_modelo","proyeccion");
		$this->load->model("reportes_model","reportes");
		$this->load->model("matricula_modelo","matricula");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
  	}

	public function index()
	{
		$data['titulo'] 		= "Matrícula";
		$data['usuario'] 		= $this->usuarios->getUsuarios($this->idUsuario);
		$data['fecha'] 			= $this->fecha;

		$data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
		$data['menuActivo']		= 'matricula'; 
		$data['subMenu']		= 'matricula'; 
		$data['pagina']			= 'sie/matricula/modulo'; 

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('64',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$data['pagina']		= 'accesos/index'; 
		}
		
		$data["breadcumb"]		= '<li class="breadcrumb-item"><a href="'.base_url().'sie/index">Sie</a></li><li class="breadcrumb-item active">Matrícula</li>';

		$this->load->view("sie/paginaPrincipal", $data);
	}

	
	public function obtenerMatricula($limite=0)
	{
		error_reporting(0);
		$data['licenciaturas'] 	= $this->matricula->obtenerMatricula(1);
		$data['maestrias'] 		= $this->matricula->obtenerMatricula(0);

		$this->load->view("sie/matricula/obtenerMatricula",$data);
	}
	
	public function obtenerDetallesMatricula($limite=0)
	{
		error_reporting(0);
		
		$data['matriculas'] 	= $this->matricula->obtenerDetallesMatricula($this->input->post('cuatrimestre'),$this->input->post('licenciatura'));
		$data['cuatrimestre'] 	= $this->input->post('cuatrimestre');
		$data['licenciatura'] 	= $this->input->post('licenciatura');

		$this->load->view("sie/matricula/obtenerDetallesMatricula",$data);
	}
	
	public function listaMatriculaSie()
	{
		error_reporting(0);
		
		$data['licenciatura'] 	= $this->input->post('licenciatura');
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('68',$this->session->userdata('rol'));
		
		$this->load->view("sie/matricula/index",$data);
	}

	public function obtenerRegistros($limite=0)
	{
		error_reporting(0);
		
		$licenciatura				= $this->input->post('licenciatura');

		$Pag["base_url"]		= base_url()."matricula/obtenerRegistros/";
		$Pag["total_rows"]		= $this->matricula->contarRegistros($licenciatura);
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;
		
		$this->pagination->initialize($Pag);

		$data['matriculas'] 	= $this->matricula->obtenerRegistros($Pag["per_page"],$limite,$licenciatura);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('68',$this->session->userdata('rol'));
		$data['inicio']  		= $limite+1;
		$data['licenciatura'] 	= $this->input->post('licenciatura');

		$this->load->view("sie/matricula/obtenerRegistros",$data);
	}
	
	public function formularioRegistro()
	{
		$data['programas']	= $this->configuracion->obtenerProgramas(0,0,'',1);
		
		$this->load->view("sie/matricula/formularioRegistro",$data);
	}
	
	public function registrarInformacion()
	{
		if (!empty($_POST))
		{
			echo $this->matricula->registrarInformacion();
		}
		else
		{
			echo "0";
		}
	}

	public function borrarRegistro()
	{
		if (!empty($_POST))
		{
			echo $this->matricula->borrarRegistro($this->input->post('idMatricula'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function editarMatricula()
	{
		if (!empty($_POST))
		{
			echo $this->matricula->editarMatricula($this->input->post('idMatricula'));
		}
		else
		{
			echo "0";
		}
	}
	
	
}
?>
