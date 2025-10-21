<?php
class Globales extends CI_Controller
{
	protected $_fechaActual;
	protected $_iduser;
	protected $_csstyle;
    protected $_tables;
    protected $_role;
	protected $idTienda;
	protected $cuota;
	protected $precios;
	
	function __construct()
	{
		parent::__construct();

		if( $this->session->userdata('usuarioActivo')!='conta' )
		{
 			redirect(base_url().'login');
 		}
		
		$this->config->load('js',TRUE);
		$this->config->load('datatables', TRUE);
		$this->config->load('style', TRUE);
		
		$datestring  	 	= "%Y-%m-%d %H:%i:%s";
		$this->_fechaActual = date('Y-m-d H:i:s');
		$this->_iduser 		= $this->session->userdata('id');
		$this->_role 		= $this->session->userdata('role');
		$this->_tables 		= $this->config->item('datatables');
		$this->_csstyle 	= $this->config->item('style');
        $this->_jss			= $this->config->item('js');
		$this->idTienda		= $this->session->userdata('idTiendaActiva');

        $this->load->model("modelousuario","usuarios");
        $this->load->model("modeloclientes","clientes");
		$this->load->model("modelo_configuracion","configuracion");
        $this->load->model("ventas_model","ventas");
		$this->load->model("tiendas_modelo","tiendas");
		$this->load->model("arreglos_modelo","arreglos");
		$this->load->model("catalogos_modelo","catalogos");
		$this->load->model("inventarioproductos_modelo","inventarioProductos");
		$this->load->model("nota_modelo","nota");
		$this->load->model("facturacion_modelo","facturacion");
		$this->load->model("administracion_modelo","administracion");
		$this->load->model("bancos_model","bancos");
		$this->load->model("reportes_model","reportes");
		$this->load->model("contabilidad_modelo","contabilidad");
		
		$this->load->model("globales_modelo","globales");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
		
		$this->precios	= $this->session->userdata('precios');
	}

	#========================================================================================================#
	#=============================================  COTIZACIONES ============================================#
	#========================================================================================================#
	
	public function index()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['jvalidate']		= $this->_jss['jvalidate'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'listaReportes'; 
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']		= $this->configuracion->obtenerPermisosId('21',$this->session->userdata('rol'));
		
		if($data['permiso']->leer=='0' and $data['permiso']->escribir=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$data['licencias']			= $this->configuracion->obtenerLicenciasActivas();
		
		$this->load->view("globales/index", $data); //principal lista de clientes
		$this->load->view("pie", $Data);
	}
	
	public function formularioSucursales()
	{
		$data['licencias']			= $this->configuracion->obtenerLicenciasActivas();

		$this->load->view('globales/formularioSucursales',$data);
	}
	
	public function editarNumeroVentas()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->globales->editarNumeroVentas());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}


	public function registrarFacturaGlobal()
	{
		if(!empty($_POST))
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			
			echo json_encode($this->globales->registrarFacturaGlobal());
		}
		else
		{
			echo json_encode(array('0','Error en el registro'));
		}
	}
	
	public function obtenerVentasGlobal($limite=0)
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idLicencia					= $this->input->post('idLicencia');
		$criterio					= $this->input->post('criterio');

		//-----------------------------PAGINACION--------------------------------------
		$paginacion["base_url"]		= base_url()."globales/obtenerVentasGlobal/";
		$paginacion["total_rows"]	= $this->globales->contarVentasGlobal($inicio,$fin,$idLicencia,$criterio);
		$paginacion["per_page"]		= 50;
		$paginacion["num_links"]	= 5;
		
		$this->pagination->initialize($paginacion);
		$data['permiso']			= $this->configuracion->obtenerPermisosId('21',$this->session->userdata('rol'));
		$data['ventas']				= $this->globales->obtenerVentasGlobal($paginacion["per_page"],$limite,$inicio,$fin,$idLicencia,$criterio);
		$data['total']				= $this->globales->sumarVentasGlobal($inicio,$fin,$idLicencia,$criterio);
		$data['inicio'] 			= $inicio;
		$data['fin'] 				= $fin;
		
		$this->load->view('globales/obtenerVentasGlobal',$data);
	}
	
	public function formularioFacturaGlobal()
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idLicencia					= $this->input->post('idLicencia');
		$criterio					= $this->input->post('criterio');
		
		$data['emisores']			= $this->facturacion->obtenerEmisores();
		$data['divisas']			= $this->configuracion->obtenerDivisas();
		$data['configuracion']		= $this->facturacion->obtenerConfiguracion();
		$data['metodos']			= $this->configuracion->obtenerMetodosPago();
		$data['formas']				= $this->configuracion->obtenerFormasPago();
		$data['usos']				= $this->configuracion->obtenerUsosCfdi();
		$data['totales']			= $this->globales->sumarVentasGlobal($inicio,$fin,$idLicencia,$criterio,1);
		
		 $this->load->view('globales/formularioFacturaGlobal',$data);
	}
}
?>
