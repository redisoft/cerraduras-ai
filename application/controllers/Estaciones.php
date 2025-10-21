<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Estaciones extends CI_Controller 
{
	protected $_jss;
	protected $_csstyle;
	protected $cuota;
	  
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('catalogos_modelo','catalogos');
		$this->load->model('modelo_configuracion','configuracion');
		$this->load->model('modelousuario','usuarios');
		$this->load->model('estaciones_modelo','estaciones');
		
		if( ! $this->redux_auth->logged_in() )
		{
			redirect(base_url().'login');
		}
		
		$this->config->load('js',TRUE);
		$this->config->load('style', TRUE);
		
		$this->_fechaActual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_iduser 			= $this->session->userdata('id');
		$this->_csstyle 		= $this->config->item('style');
		$this->_jss				= $this->config->item('js');
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}
	
	//DEPARTAMENTOS
	public function index()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['jvalidate']		= $this->_jss['jvalidate'];
		$Data['nameusuario']	= $this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'estaciones'; 
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
		
		$data["breadcumb"]		= 'Estaciones';
		
		$this->load->view("configuracion/estaciones/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerRegistros($limite=0)
	{
		$criterio			= $this->input->post('criterio');
		
		$Pag["base_url"]	= base_url()."estaciones/obtenerRegistros/";
		$Pag["total_rows"]	= $this->estaciones->contarRegistros($criterio);
		$Pag["per_page"]	= 25;
		$Pag["num_links"]	= 5;

		$this->pagination->initialize($Pag);
		#print_r($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('18',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['registros'] 		= $this->estaciones->obtenerRegistros($Pag["per_page"],$limite,$criterio);
		$data['inicio']  		= $limite+1;

		$this->load->view("configuracion/estaciones/obtenerRegistros",$data);
	}
	
	public function formularioRegistro()
	{
		$this->load->view("configuracion/estaciones/formularioRegistro");
	}
	
	public function obtenerRegistro()
	{
		if(!empty ($_POST))
		{
			$data['registro']	= $this->estaciones->obtenerRegistro($this->input->post('idEstacion'));
			
			$this->load->view("configuracion/estaciones/obtenerRegistro",$data);
		}
	}
	
	public function registrarFormulario()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->estaciones->registrarFormulario());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarFormulario()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->estaciones->editarFormulario());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarRegistro()
	{
		if (!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']	= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				echo "0";
				return;
			}
	
			echo json_encode($this->estaciones->borrarRegistro($this->input->post('idEstacion')));
		}
		else
		{
			echo json_encode(array("0",errorBorrado));
		}
	}
}
