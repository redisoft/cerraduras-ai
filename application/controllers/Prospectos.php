<?php
class Prospectos extends CI_Controller
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
		$this->load->model("catalogos_modelo","catalogos");
		$this->load->model("prospectos_modelo","prospectos");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
  	}

	public function index()
	{
		$data['titulo'] 		= "Prospectos";
		$data['usuario'] 		= $this->usuarios->getUsuarios($this->idUsuario);
		$data['fecha'] 			= $this->fecha;

		$data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
		$data['menuActivo']		= 'prospectos'; 
		$data['subMenu']		= 'prospectos'; 
		$data['pagina']			= 'sie/prospectos/modulo'; 

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('64',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$data['pagina']		= 'accesos/index'; 
		}
		
		$data["semana"]			= $this->reportes->obtenerDiasSemana(date('Y-m-d'));
		$data["breadcumb"]		= '<li class="breadcrumb-item"><a href="'.base_url().'sie/index1">Sie</a></li><li class="breadcrumb-item active">Prospectos</li>';

		$this->load->view("sie/paginaPrincipal", $data);
	}

	
	public function obtenerProspectos()
	{
		$inicio 				= $this->input->post('inicio');
		$fin 					= $this->input->post('fin');
		
		$semana					= $this->reportes->obtenerDiasSemana($inicio);
		
		$inicio 				= $this->reportes->obtenerFechaFin($semana->diaInicio,2);
		$fin 					= $this->reportes->obtenerFechaFin($inicio,6);

		$data['registros'] 		= $this->prospectos->obtenerProspectos($inicio,$fin,1);
		$data['totales'] 		= $this->prospectos->obtenerProspectos('','',1);
		$data['fin'] 			= $fin;

		$this->load->view("sie/prospectos/obtenerProspectos",$data);
	}
	
	public function obtenerDetallesMatricula($limite=0)
	{
		error_reporting(0);
		
		$data['matriculas'] 	= $this->prospectos->obtenerDetallesMatricula($this->input->post('cuatrimestre'),$this->input->post('licenciatura'));
		$data['cuatrimestre'] 	= $this->input->post('cuatrimestre');
		$data['licenciatura'] 	= $this->input->post('licenciatura');

		$this->load->view("sie/prospectos/obtenerDetallesMatricula",$data);
	}
	
	//CATÁLOGO
	public function listaProspectosSie()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('67',$this->session->userdata('rol'));
		
		$this->load->view("sie/prospectos/index",$data);
	}

	public function obtenerRegistros($limite=0)
	{
		$inicio 				= $this->input->post('inicio');
		$fin 					= $this->input->post('fin');
		
		$Pag["base_url"]		= base_url()."prospectos/obtenerRegistros/";
		$Pag["total_rows"]		= $this->prospectos->contarRegistros($inicio,$fin);
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;
		
		$this->pagination->initialize($Pag);

		$data['registros'] 		= $this->prospectos->obtenerRegistros($Pag["per_page"],$limite,$inicio,$fin);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('67',$this->session->userdata('rol'));
		$data['inicio']  		= $limite+1;

		$this->load->view("sie/prospectos/obtenerRegistros",$data);
	}
	
	public function formularioRegistro()
	{
		$data['tipos']			= $this->catalogos->obtenerTiposMetas();
		$data['grados']			= $this->catalogos->obtenerGrados();
		$data["semana"]			= $this->reportes->obtenerDiasSemana(date('Y-m-d'));
		
		$this->load->view("sie/prospectos/formularioRegistro",$data);
	}
	
	public function registrarInformacion()
	{
		if (!empty($_POST))
		{
			echo $this->prospectos->registrarInformacion();
		}
		else
		{
			echo "0";
		}
	}
	
	public function formularioEditar()
	{
		$data['registro']	= $this->prospectos->obtenerRegistro($this->input->post('idMeta'));
		$data['tipos']		= $this->catalogos->obtenerTiposMetas();
		$data['grados']		= $this->catalogos->obtenerGrados();
		
		$this->load->view("sie/prospectos/formularioEditar",$data);
	}
	
	public function editarInformacion()
	{
		if (!empty($_POST))
		{
			echo $this->prospectos->editarInformacion();
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
			echo $this->prospectos->borrarRegistro($this->input->post('idMeta'));
		}
		else
		{
			echo "0";
		}
	}
	
	//INSCRITOS
	
	public function inscritos()
	{
		$data['titulo'] 		= "Inscritos";
		$data['usuario'] 		= $this->usuarios->getUsuarios($this->idUsuario);
		$data['fecha'] 			= $this->fecha;

		$data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
		$data['menuActivo']		= 'inscritos'; 
		$data['subMenu']		= 'inscritos'; 
		$data['pagina']			= 'sie/inscritos/modulo'; 

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('64',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$data['pagina']		= 'accesos/index'; 
		}
		
		$data["semana"]			= $this->reportes->obtenerDiasSemana(date('Y-m-d'));
		$data["breadcumb"]		= '<li class="breadcrumb-item"><a href="'.base_url().'sie/index1">Sie</a></li><li class="breadcrumb-item active">Inscritos</li>';

		$this->load->view("sie/paginaPrincipal", $data);
	}

	
	
	public function obtenerInscritos()
	{
		$inicio 				= $this->input->post('inicio');
		$fin 					= $this->input->post('fin');
		
		$semana					= $this->reportes->obtenerDiasSemana($inicio);
		
		$inicio 				= $this->reportes->obtenerFechaFin($semana->diaInicio,2);
		$fin 					= $this->reportes->obtenerFechaFin($inicio,6);
		
		#$inicio 				= $semana->diaInicio;
		#$fin 					= $semana->diaFin;
		
		$data['registros'] 		= $this->prospectos->obtenerInscritos($inicio,$fin,2);
		$data['totales'] 		= $this->prospectos->obtenerInscritos('','',2);
		$data['fin'] 			= $fin;

		$this->load->view("sie/inscritos/obtenerInscritos",$data);
	}
}
?>
