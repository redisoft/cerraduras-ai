<?php
class Materiales extends CI_Controller
{
    private $_template;
    protected $_fechaActual;
    protected $_iduser;
    protected $_csstyle;
    protected $cuota;
	protected $idRol;
	
	function __construct() //Constructor
	{
		parent::__construct();

		if( ! $this->redux_auth->logged_in() )
		{
			redirect(base_url().'login');
		}
		
		$this->_fechaActual = mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_iduser = $this->session->userdata('id');
		$this->config->load('style', TRUE);
		$this->_csstyle = $this->config->item('style');
		$this->config->load('js',TRUE);
		$this->_jss=$this->config->item('js');
		
		$this->idRol 			= $this->session->userdata('rol');
		
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("modeloclientes","modeloclientes");
		$this->load->model("materiales_modelo","materiales");
		$this->load->model("proveedores_model","modeloproveedores");
		$this->load->model("inventario_model","inventario");
		$this->load->model("control_modelo","control");
		$this->load->model("modelo_configuracion","configuracion");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}

	public function index()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			= $this->_csstyle["cassadmin"];
		$Data['csmenu']				= $this->_csstyle["csmenu"];
		$Data['csvalidate']			= $this->_csstyle["csvalidate"];
		$Data['csui']				= $this->_csstyle["csui"];
		$Data['nameusuario']		= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		= $this->_fechaActual;
		$Data['Jry']				= $this->_jss['jquery'];
		$Data['JFuntInventario']	= $this->_jss['JFuntInventario'];
		$Data['Jqui']				= $this->_jss['jqueryui'];           
		$Data['Jquical']			= $this->_jss['jquerycal'];       
		$Data['jFicha_cliente']		= $this->_jss['jFicha_cliente'];  
		$Data['permisos']			= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			= 'materiales'; 
		$Data['conectados']			= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);    
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('18',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]			= sistemaActivo=='IEXE'?'Insumos':'Materia prima';

		$this->load->view("materiales/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerMateriales($limite=0)
	{
		$criterio			= $this->input->post('criterio');
		$orden				= $this->input->post('orden');
		
		$Pag["base_url"]	= base_url()."materiales/obtenerMateriales/";
		$Pag["total_rows"]	= $this->materiales->contarMateriales($criterio);
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
		
		$data['proveedores']	= $this->inventario->proveedores();
		$data['materiales'] 	= $this->materiales->obtenerMateriales($Pag["per_page"],$limite,$criterio,$orden);
		$data['unidades'] 		= $this->configuracion->obtenerUnidades();
		$data['inicio']  		= $limite+1;
		$data['orden']  		= $orden;

		$this->load->view("materiales/obtenerMateriales",$data);
	}
 
	public function registrarMateriaPrima() #Almacenar un nuevo material
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->materiales->registrarMateriaPrima());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarMaterial()
	{
		#----------------------------------PERMISOS------------------------------------#
		if(!empty($_POST))
		{
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('18',$this->session->userdata('rol'));
			
			if($data['permiso'][0]->activo=='0')
			{
				$this->load->view('accesos/index');
				return;
			}
			
			echo $this->materiales->borrarProductoMaterial($this->input->post('idMaterial'),$this->input->post('idProveedor'));
		}
		else
		{
			echo "0";
		}
	}

	public function editarMaterial($idMaterial,$idProveedor)
	{
		$data['material']		= $this->materiales->obtenerMaterialProveedor($idMaterial,$idProveedor);
		$data['relaciones']		= $this->materiales->comprobarNumeroRelaciones($idMaterial);
		$data['unidades'] 		= $this->configuracion->obtenerUnidades();
		$data['conversiones'] 	= $this->configuracion->obtenerConversiones($data['material']->idUnidad);
		$data['subCategorias'] 	= $this->configuracion->obtenerSubCategoriasCategorias();
		$data['configuracion'] 	= $this->configuracion->obtenerConfiguraciones(1);
		$data['impuestos'] 		= $this->configuracion->obtenerCatalogoImpuestos();
		$data['idMaterial']		= $idMaterial;
		$data['idProveedor']	= $idProveedor;
		
		$this->load->view('materiales/obtenerMaterial',$data);
	}
	
	function formularioMateriaPrima()
	{
		$data['unidades'] 		= $this->configuracion->obtenerUnidades();
		$data['subCategorias'] 	= $this->configuracion->obtenerSubCategoriasCategorias();
		$data['configuracion'] 	= $this->configuracion->obtenerConfiguraciones(1);
		$data['impuestos'] 		= $this->configuracion->obtenerCatalogoImpuestos();
		
		$this->load->view('materiales/formularioMateriaPrima',$data);
	}
	
	public function confirmarEditar()
	{
		if(!empty($_POST))
		{
			echo $this->materiales->confirmarEditar();
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerTodosProveedores()
	{
		if(!empty($_POST))
		{
			$idMaterial				= $this->input->post('idMaterial');
			$data['material']		= $this->materiales->obtenerMaterial($idMaterial);
			$data['proveedores']	= $this->inventario->proveedores();
			$data['idMaterial']		= $idMaterial;
			
			$this->load->view('materiales/proveedores/obtenerTodosProveedores',$data);
		}
	}
	
	public function agregarProveedorMaterial()
	{
		if(!empty($_POST))
		{
			
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->materiales->agregarProveedorMaterial());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function registrarMerma()
	{
		if(!empty($_POST))
		{
			$merma=$this->materiales->registrarMerma();
			
			if($merma=="1")
			{
				$this->session->set_userdata('notificacion','Se ha registrado correctamente la merma');
			}
			
			echo $merma;
		}
	}
	
	public function obtenerMermas()
	{
		if(!empty($_POST))
		{
			$idMaterial			= $this->input->post('idMaterial');
			$idProveedor		= $this->input->post('idProveedor');
			
			$data['mermas']		= $this->materiales->obtenerMermas($idMaterial,$idProveedor);
			$data['material']	= $this->materiales->obtenerMaterialProveedor($idMaterial,$idProveedor);
			
			$this->load->view('materiales/obtenerMermas',$data);
		}
	}
	
	public function obtenerCatalogoMateriales()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('18',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}

		$this->load->view("materiales/index",$data);
	}
	

	//CONTROL DE MATERIA PRIMA
	public function controlMateriaPrima()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			= $this->_csstyle["cassadmin"];
		$Data['csmenu']				= $this->_csstyle["csmenu"];
		$Data['csvalidate']			= $this->_csstyle["csvalidate"];
		$Data['csui']				= $this->_csstyle["csui"];
		$Data['nameusuario']		= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		= $this->_fechaActual;
		$Data['Jry']				= $this->_jss['jquery'];
		$Data['JFuntInventario']	= $this->_jss['JFuntInventario'];
		$Data['Jqui']				= $this->_jss['jqueryui'];           
		$Data['Jquical']			= $this->_jss['jquerycal'];       
		$Data['jFicha_cliente']		= $this->_jss['jFicha_cliente'];  
		$Data['permisos']			= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			= 'controlMateria'; 
		$Data['conectados']			= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);    
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('57',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]			= '<a href="'.base_url().'materiales">Materia prima</a> > Avíos';

		$this->load->view("materiales/control/salidas/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerSalidasControl($limite=0)
	{
		$criterio			= $this->input->post('criterio');
		$inicio				= $this->input->post('inicio');
		$fin				= $this->input->post('fin');
		
		$Pag["base_url"]	= base_url()."materiales/obtenerSalidasControl/";
		$Pag["total_rows"]	= $this->control->contarSalidasControl($criterio,$inicio,$fin);
		$Pag["per_page"]	= 25;
		$Pag["num_links"]	= 5;

		$this->pagination->initialize($Pag);

		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('57',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['salidas'] 	= $this->control->obtenerSalidasControl($Pag["per_page"],$limite,$criterio,$inicio,$fin);
		$data['inicio']  	= $limite+1;

		$this->load->view('materiales/control/salidas/obtenerSalidasControl',$data);
	}
	
	public function formularioSalidaControl()
	{	
		$data['folio']		= $this->control->obtenerFolioSalida();
		$data['tiendas']	= $this->configuracion->obtenerTiendas();
		
		$this->load->view('materiales/control/salidas/formularioSalidaControl',$data);
	}
	
	public function registrarSalidaControl()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
				
			echo json_encode($this->control->registrarSalidaControl());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerSalidaControl()
	{	
		$data['salida']		= $this->control->obtenerSalidaControl($this->input->post('idSalida'));
		$data['materiales']	= $this->control->obtenerMaterialesSalidaControl($this->input->post('idSalida'));
		$data['tiendas']	= $this->configuracion->obtenerTiendas();
		
		$this->load->view('materiales/control/salidas/obtenerSalidaControl',$data);
	}
	
	public function editarSalidaControl()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
				
			echo json_encode($this->control->editarSalidaControl());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarSalidaControl()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->control->borrarSalidaControl($this->input->post('idSalida')));
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	//DEVUELTOS
	
	public function obtenerDevueltosControl()
	{	
		$data['salida']		= $this->control->obtenerSalidaControl($this->input->post('idSalida'));
		$data['materiales']	= $this->control->obtenerMaterialesSalidaControl($this->input->post('idSalida'));
		$data['idRol']		= $this->idRol;
		
		$this->load->view('materiales/control/salidas/obtenerDevueltosControl',$data);
	}
	
	public function registrarDevueltosControl()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->control->registrarDevueltosControl());
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
}
?>
