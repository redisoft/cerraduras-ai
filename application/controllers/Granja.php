<?php
class Granja extends CI_Controller
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
		$this->_jss				= $this->config->item('js');
		 
		$datestring   			= "%Y-%m-%d %H:%i:%s";
	    $this->_fechaActual 	= mdate($datestring,now());
		$this->_iduser 			= $this->session->userdata('id');
		$this->_role 			= $this->session->userdata('role');
		$this->_tables 			= $this->config->item('datatables');
		$this->_csstyle 		= $this->config->item('style');
		
		$this->load->model("crm_modelo","crm");
        $this->load->model("modelousuario","usuarios");
        $this->load->model("modeloclientes","clientes");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("ventas_model","ventas");
		$this->load->model("motivos_modelo","motivos");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
  	}

	#========================================================================================================#
	#=============================================  COTIZACIONES ============================================#
	#========================================================================================================#
	
	public function inseminacion()
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
		$Data['menuActivo']		='inseminacion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data["breadcumb"]		= 'Granja > Inseminación';

		$this->load->view("granja/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function gestacion()
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
		$Data['menuActivo']		='gestacion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data["breadcumb"]		= 'Granja > Gestación';

		$this->load->view("granja/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function maternidad()
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
		$Data['menuActivo']		='maternidad'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data["breadcumb"]		= 'Granja > Maternidad';

		$this->load->view("granja/index",$data);
		$this->load->view("pie",$Data);
	}

	public function engorda()
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
		$Data['menuActivo']		='engorda'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data["breadcumb"]		= 'Granja > Engorda';

		$this->load->view("granja/index",$data);
		$this->load->view("pie",$Data);
	}
}
?>
