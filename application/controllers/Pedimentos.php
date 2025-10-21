<?php
class Pedimentos extends CI_Controller
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
		
		$this->config->load('datatables', TRUE);	
		$this->config->load('style', TRUE);
		$this->config->load('js',TRUE);
		$this->_jss				=$this->config->item('js');
		 
		$datestring   			= "%Y-%m-%d %H:%i:%s";
	    $this->_fechaActual 	= mdate($datestring,now());
		$this->_iduser 			= $this->session->userdata('id');
		$this->_role 			= $this->session->userdata('role');
		$this->_tables 			= $this->config->item('datatables');
		$this->_csstyle 		= $this->config->item('style');

        $this->load->model("modelousuario","usuarios");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("ventas_model","ventas");
		$this->load->model("motivos_modelo","motivos");
        $this->load->model("pedimentos_modelo","pedimentos");
        
        $this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
  	}

	#========================================================================================================#
	#=============================================  COTIZACIONES ============================================#
	#========================================================================================================#
	
	public function index()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['nameusuario']	=$this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='pedimentos'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']  = $this->configuracion->obtenerPermisosBoton(15,$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
        
        $data["breadcumb"]		= 'Pedimentos';
        
		$this->load->view("pedimentos/index");
		$this->load->view("pie",$Data);
	}
    
    public function obtenerCatalogoPedimentos()
	{
		$data['permiso'] 	= $this->configuracion->obtenerPermisosBoton(15,$this->session->userdata('rol'));
		
		$this->load->view("pedimentos/index",$data);
	}
	
	public function obtenerRegistrosPedimentos()
	{
		$data['registros']		= $this->pedimentos->obtenerRegistros();
		
		$this->load->view("pedimentos/obtenerRegistrosCatalogo",$data);
	}

	public function obtenerRegistros($limite=0)
	{
		$criterio		= $this->input->post('criterio');
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton(15,$this->session->userdata('rol'));

		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}

		#----------------------------------PAGINACION------------------------------------#
		$Pag["base_url"]	    = base_url()."pedimentos/obtenerRegistros/";
		$Pag["total_rows"]	    = $this->pedimentos->contarRegistros($criterio);
		$Pag["per_page"]	    = 30;
		$Pag["num_links"]	    = 5;

		$this->pagination->initialize($Pag);
		#---------------------------------------------------------------------------------#

		$data['registros']		= $this->pedimentos->obtenerRegistros($Pag["per_page"],$limite,$criterio);
		$data['limite']			= $limite+1;

        $this->load->view('pedimentos/obtenerRegistros',$data);
	}
    
    public function formularioRegistro()
	{
		$this->load->view("pedimentos/formularioRegistro");
	}
    
    public function registrarFormulario()
	{
		if(!empty ($_POST))
		{
			/*if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}*/
			
			echo json_encode($this->pedimentos->registrarFormulario());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
    
    public function formularioEditar()
	{
        $data['registro']	    = $this->pedimentos->obtenerRegistro($this->input->post('idPedimento'));

        $this->load->view("pedimentos/formularioEditar",$data);
	}
    
    public function editarFormulario()
	{
		if(!empty ($_POST))
		{
			/*if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}*/
			
			echo json_encode($this->pedimentos->editarFormulario());
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
			/*#----------------------------------PERMISOS------------------------------------#
			$data['permiso']	= $this->configuracion->obtenerPermisosBoton(1,$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				echo json_encode(array("0",errorBorrado));
                
				return;
			}*/
	
			echo json_encode($this->pedimentos->borrarRegistro($this->input->post('idPedimento')));
		}
		else
		{
			echo json_encode(array("0",errorBorrado));
		}
	}
}
?>
