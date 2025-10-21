<?php
class Bancos extends CI_Controller
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
		{//verificar si el el usuario ha iniciado sesion
 			redirect(base_url().'login');
 		}

		$this->config->load('datatables', TRUE);
		$this->config->load('style', TRUE);
		$this->config->load('js',TRUE);
		
		$datestring   			= "%Y-%m-%d %H:%i:%s";
	    $this->_fechaActual 	= mdate($datestring,now());
		$this->_iduser		 	= $this->session->userdata('id');
		$this->_role 			= $this->session->userdata('role');
		$this->_csstyle 		= $this->config->item('style');
		$this->_tables 			= $this->config->item('datatables');
		$this->_table 			= $this->config->item('datatables');
		
		$this->_jss=$this->config->item('js');
		
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("bancos_model","bancos");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
     }

	public function index($limite=0)
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			=$this->_csstyle["cassadmin"];
		$Data['csmenu']				=$this->_csstyle["csmenu"];
		$Data['csvalidate']			=$this->_csstyle["csvalidate"];
		$Data['csui']				=$this->_csstyle["csui"];
		$Data['nameusuario']		=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		=$this->_fechaActual;
		$Data['Jry']				=$this->_jss['jquery'];
		$Data['JFuntBuscaClientes']	=$this->_jss['JFuntBuscaClientes'];
		$Data['jvalidate']			=$this->_jss['jvalidate'];
		$Data['Jqui']				=$this->_jss['jqueryui'];
		$Data['permisos']			=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			='configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$Pag["base_url"]		= base_url()."bancos/index/";
		$Pag["total_rows"]		= $this->bancos->contarBancos();
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;
		
		$this->pagination->initialize($Pag);
		
		$data['bancos'] 		= $this->bancos->obtenerListaBancos($Pag["per_page"],$limite);
		$data["breadcumb"]		= 'Bancos';
		$data["limite"]			= $limite+1;
		
		$this->load->view("configuracion/bancos/index",$data);
		$this->load->view("pie",$data);
	}
	
	public function formularioBancos()
	{
		$this->load->view("configuracion/bancos/formularioBancos");
	}
	
	public function registrarBanco()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$banco=$this->bancos->registrarBanco();
			
			$banco[0]=="1"?
				$this->session->set_userdata('notificacion','El banco se ha registrado correctamente'):'';
			
			echo json_encode($banco);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarBanco()
	{
		if(!empty($_POST))
		{
			$banco=$this->bancos->editarBanco();
			
			$banco=="1"?
				$this->session->set_userdata('notificacion','El banco se ha editado correctamente'):'';
				
			echo $banco;
		}
	}
	
	public function obtenerBanco()
	{
		$data['banco']	= $this->bancos->obtenerBanco($this->input->post('idBanco'));
		
		$this->load->view("configuracion/bancos/obtenerBanco",$data);
	}
	
	public function cuentas()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			=$this->_csstyle["cassadmin"];
		$Data['csmenu']				=$this->_csstyle["csmenu"];
		$Data['csvalidate']			=$this->_csstyle["csvalidate"];
		$Data['csui']				=$this->_csstyle["csui"];
		$Data['nameusuario']		=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		=$this->_fechaActual;
		$Data['Jry']				=$this->_jss['jquery'];
		$Data['JFuntBuscaClientes']	=$this->_jss['JFuntBuscaClientes'];
		$Data['jvalidate']			=$this->_jss['jvalidate'];
		$Data['Jqui']				=$this->_jss['jqueryui'];
		$Data['permisos']			=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			='configuracion'; 
		$Data['conectados']			= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['cuentas'] 	= $this->bancos->obtenerCuentasAdministracion();
		$data['bancos'] 	= $this->bancos->obtenerBancos();
		$data["breadcumb"]		= 'Cuentas';
		
		$this->load->view("configuracion/cuentas/index",$data);
		$this->load->view("pie",$data);
	}
	
	public function formularioCuentas()
	{
		$data['bancos'] 	= $this->bancos->obtenerBancos();
		$data['emisores'] 	= $this->configuracion->obtenerEmisores();
		
		$this->load->view("configuracion/cuentas/formularioCuentas",$data);
	}
	
	public function obtenerCuenta()
	{
		$idCuenta			= $this->input->post('idCuenta');
		$data['bancos'] 	= $this->bancos->obtenerBancos();
		$data['emisores'] 	= $this->configuracion->obtenerEmisores();
		$data['cuenta']		= $this->bancos->obtenerCuenta($idCuenta);
		
		$this->load->view("configuracion/cuentas/obtenerCuenta",$data);
	}
	
	public function registrarCuenta()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$cuenta=$this->bancos->registrarCuenta();
			
			$cuenta[0]=="1"?
				$this->session->set_userdata('notificacion','La cuenta se ha registrado correctamente'):'';
				
			echo json_encode($cuenta);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarCuenta()
	{
		if(!empty($_POST))
		{
			$cuenta	=$this->bancos->editarCuenta();
			
			$cuenta=="1"?
				$this->session->set_userdata('notificacion','La cuenta se ha editado correctamente'):'';
				
			echo $cuenta;
		}
	}
	
	public function borrarCuenta($idCuenta)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$cuenta=$this->bancos->borrarCuenta($idCuenta);
		
		$cuenta=="1"?
				$this->session->set_userdata('notificacion','La cuenta se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar la cuenta porque esta asociada ingresos o egresos');
				
		redirect('bancos/cuentas','refresh');
	}
	
	public function borrarCuentaCliente()
	{
		if(!empty($_POST))
		{
			echo $this->bancos->borrarCuenta($this->input->post('idCuenta'));
		}
	}

	public function borrarBanco($idBanco)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$banco=$this->bancos->borrarBanco($idBanco);
		
		#echo $banco;
		
		$banco=="1"?
				$this->session->set_userdata('notificacion','El banco se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al borrar el banco porque esta asociado a una o más cuentas');
		
		redirect('bancos', 'refresh');
	}
}
?>