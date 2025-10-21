<?php
class Pedidos extends CI_Controller
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
		
		$this->_fechaActual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_iduser			= $this->session->userdata('id');
		$this->idRol 			= $this->session->userdata('rol');
		
		$this->config->load('style', TRUE);
		$this->_csstyle = $this->config->item('style');
		$this->config->load('js',TRUE);
		$this->_jss=$this->config->item('js');
		
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("modeloclientes","modeloclientes");
		$this->load->model("materiales_modelo","materiales");
		$this->load->model("proveedores_model","modeloproveedores");
		$this->load->model("inventario_model","inventario");
		$this->load->model("pedidos_modelo","pedidos");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("catalogos_modelo","catalogos");
		
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
		$Data['menuActivo']			= 'pedidos'; 
		$Data['conectados']			= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);    
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('56',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['fechas']				= $this->catalogos->obtenerDiasSemanaActual();
		$data["breadcumb"]			= 'Orden de producción';

		$this->load->view("pedidos/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerPedidos($limite=0)
	{
		$criterio			= $this->input->post('criterio');
		$inicio				= $this->input->post('inicio');
		$fin				= $this->input->post('fin');
		$orden				= $this->input->post('orden');
		
		$Pag["base_url"]	= base_url()."pedidos/obtenerPedidos/";
		$Pag["total_rows"]	= $this->pedidos->contarPedidos($criterio,$inicio,$fin);
		$Pag["per_page"]	= 25;
		$Pag["num_links"]	= 5;

		$this->pagination->initialize($Pag);

		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('56',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['pedidos'] 	= $this->pedidos->obtenerPedidos($Pag["per_page"],$limite,$criterio,$inicio,$fin,$orden);
		$data['inicio']  	= $limite+1;
		$data['idRol']		= $this->idRol;
		$data['orden']		= $orden;

		$this->load->view('pedidos/obtenerPedidos',$data);
	}
	
	public function formularioPedidos()
	{	
		$data['folio']			= $this->pedidos->obtenerFolioPedido();
		$data['tiendas']		= $this->configuracion->obtenerTiendas();
		$data['lineas']			= $this->configuracion->obtenerLineas();
		$data['usuario']		= $this->modelousuario->getUsuarios($this->_iduser);
		
		$this->load->view('pedidos/formularioPedidos',$data);
	}
	
	public function obtenerBuscadorLinea()
	{	
		$data['idLinea']			= $this->input->post('idLinea');
		
		$this->load->view('pedidos/obtenerBuscadorLinea',$data);
	}
	
	public function registrarPedido() #Almacenar un nuevo material
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->pedidos->registrarPedido());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}

	public function obtenerPedido()
	{	
		$data['pedido']		= $this->pedidos->obtenerPedido($this->input->post('idPedido'));
		$data['tiendas']	= $this->configuracion->obtenerTiendas();
		$data['productos']	= $this->pedidos->obtenerProductosPedido($this->input->post('idPedido'));
		
		$this->load->view('pedidos/obtenerPedido',$data);
	}
	
	public function editarPedido()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
				
			echo json_encode($this->pedidos->editarPedido());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarPedido()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->pedidos->borrarPedido($this->input->post('idPedido')));
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	public function cancelarPedido()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->pedidos->cancelarPedido($this->input->post('idPedido')));
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	//PRODUCIDOS
	
	public function obtenerProducidoPedido()
	{	
		$data['pedido']		= $this->pedidos->obtenerPedido($this->input->post('idPedido'));
		$data['productos']	= $this->pedidos->obtenerProductosPedido($this->input->post('idPedido'));
		$data['idRol']		= $this->idRol;
		
		$this->load->view('pedidos/obtenerProducidoPedido',$data);
	}
	
	public function registrarProducido()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->pedidos->registrarProducido());
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	public function obtenerProducidosProducto()
	{	
		$data['producto']	= $this->pedidos->obtenerProductoPedido($this->input->post('idDetalle'));
		$data['producidos']	= $this->pedidos->obtenerProducidosProducto($this->input->post('idDetalle'));
		
		$this->load->view('pedidos/producido/obtenerProducidosProducto',$data);
	}
	
	public function editarProducidoProducto()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->pedidos->editarProducidoProducto());
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	public function borrarProducidoProducto()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->pedidos->borrarProducidoProducto($this->input->post('idProducido')));
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	//REPORTE DE PEDIDO
	public function formularioReporte()
	{	
		$data['pedido']		= $this->pedidos->obtenerPedido($this->input->post('idPedido'));
		$data['total']		= $this->pedidos->obtenerTotalesPedido($this->input->post('idPedido'));
		$data['impuestos']	= $this->pedidos->obtenerImpuestosPedido($this->input->post('idPedido'));
		$data['reporte']	= $this->pedidos->obtenerReportePedido($this->input->post('idPedido'));
		
		if($data['pedido']->idLinea!=4)
		{
			$this->load->view('pedidos/reporte/formularioReporte',$data);
		}
		else
		{
			$this->load->view('pedidos/reporte/formularioReportePasteles',$data);
		}
		
	}
	
	public function registrarReporte()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->pedidos->registrarReporte());
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	//CONTEO
	public function conteo()
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
		$Data['menuActivo']			= 'conteo'; 
		$Data['conectados']			= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);    
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('56',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['fechas']				= $this->catalogos->obtenerDiasSemanaActual();
		$data["breadcumb"]			= 'Conteo de pan';

		$this->load->view("pedidos/conteos/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerConteos($limite=0)
	{
		$criterio			= $this->input->post('criterio');
		$inicio				= $this->input->post('inicio');
		$fin				= $this->input->post('fin');
		$orden				= $this->input->post('orden');
		
		$Pag["base_url"]	= base_url()."pedidos/obtenerConteos/";
		$Pag["total_rows"]	= $this->pedidos->contarConteos($criterio,$inicio,$fin);
		$Pag["per_page"]	= 25;
		$Pag["num_links"]	= 5;

		$this->pagination->initialize($Pag);

		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('56',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['conteos'] 	= $this->pedidos->obtenerConteos($Pag["per_page"],$limite,$criterio,$inicio,$fin,$orden);
		$data['inicio']  	= $limite+1;
		$data['idRol']		= $this->idRol;
		$data['orden']		= $orden;

		$this->load->view('pedidos/conteos/obtenerConteos',$data);
	}
	
	public function formularioConteos()
	{	
		$data['folio']			= $this->pedidos->obtenerFolioConteo();
		$data['tiendas']		= $this->configuracion->obtenerTiendas();
		$data['usuario']		= $this->modelousuario->getUsuarios($this->_iduser);
		$data['productos']		= $this->pedidos->obtenerProductosPedidosConteo();
		
		$this->load->view('pedidos/conteos/formularioConteos',$data);
	}
	
	public function registrarConteo()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->pedidos->registrarConteo());
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	public function detallesConteo()
	{	
		$data['conteo']			= $this->pedidos->obtenerConteo($this->input->post('idConteo'));
		$data['productos']		= $this->pedidos->obtenerProductosConteo($this->input->post('idConteo'));
		
		$this->load->view('pedidos/conteos/detallesConteo',$data);
	}

	public function obtenerConteo()
	{	
		$data['conteo']			= $this->pedidos->obtenerConteo($this->input->post('idConteo'));
		$data['productos']		= $this->pedidos->obtenerProductosConteo($this->input->post('idConteo'));
		
		$this->load->view('pedidos/conteos/obtenerConteo',$data);
	}
	
	public function editarConteo()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->pedidos->editarConteo());
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	public function borrarConteo()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->pedidos->borrarConteo($this->input->post('idConteo')));
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
}
?>
