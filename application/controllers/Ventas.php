<?php
class Ventas extends CI_Controller
{
	protected $_fechaActual;
	protected $_iduser;
	protected $_csstyle;
    protected $_tables;
    protected $_role;
	protected $idTienda;
	protected $cuota;
	protected $precios;
	protected $tiendaLocal;
	protected $registroVentas;
	
	function __construct()
	{
		parent::__construct();

		if( ! $this->redux_auth->logged_in() )
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
		#$this->load->model("nota_modelo","nota");
		$this->load->model("facturacion_modelo","facturacion");
		$this->load->model("administracion_modelo","administracion");
		$this->load->model("bancos_model","bancos");
		$this->load->model("reportes_model","reportes");
		$this->load->model("contabilidad_modelo","contabilidad");
		
		$this->load->model("venta_modelo","venta");
		$this->load->model("facturaventa_modelo","facturaventa");
		$this->load->model("temporal_modelo","temporal");
		$this->load->model("estaciones_modelo","estaciones");
		
		#$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= true; #$this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
		
		$this->precios			= $this->session->userdata('precios');
		$this->tiendaLocal		= $this->session->userdata('tiendaLocal');
		$this->registroVentas	= $this->session->userdata('registroVentas');
	}

	#========================================================================================================#
	#=============================================  COTIZACIONES ============================================#
	#========================================================================================================#
	
	public function index($idCliente=0)
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'ventasMenu'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data["mostrarMenu"]	= false;
		$data["idCliente"]		= $idCliente;
		$data["idCotizacion"]	= 0;
		$data["seccion"]		= 'ventas';
		$data["breadcumb"]		= '<a href="'.base_url().'clientes">Clientes</a>  > Ventas';
		
		$this->load->view("ventas/catalogo/index",$data);
		$this->load->view("pie",$Data);
	}

	public function prebusquedaFacturasCliente($idCliente)
	{
		$this->session->set_userdata('idFacturaCliente',$idCliente);
		
		redirect('ventas/facturasCliente','refresh');
	}
	
	public function entregarProductos()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->ventas->entregarProductos());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function buscarEntregas($idProducto)
	{
		$data['entregas']	= $this->ventas->obtenerEntregas($idProducto);
		
		$this->load->view('ventas/entregas/buscarEntregas',$data);
	}
	
	#-------------------------------------------------------------------------------------------------------------------#
	#ENVIAR TODOS LOS PRODUCTOS
	public function enviarTodosProductos()
	{
		if(!empty($_POST)) 
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->ventas->enviarTodosProductos($this->input->post('idCotizacion')));
		}
		else 
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerVenta()
	{
		if(!empty($_POST))
		{
			$idCotizacion				=$this->input->post('idCotizacion');
			$data['cotizacion']			=$this->facturacion->obtenerCotizacion($idCotizacion);
			$data['cliente']			=$this->facturacion->obtenerCliente($cotizacion->idCliente);
			$data['productos']			=$this->facturacion->obtenerProductosCotizacion($idCotizacion);

			$this->load->view('ventas/obtenerVenta',$data);
			
		}
	}
	
	public function cancelarCotizacion()
	{
		if(!empty($_POST))
		{
			echo $this->ventas->cancelarCotizacion($this->input->post('idCotizacion'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerAcrilico()
	{
		$data['pedido'] 	= $this->reportes->obtenerPedido($this->input->post('idCotizacion'));

		$this->load->view('ventas/pedidos/obtenerAcrilico',$data);
	}
	
	public function registrarAcrilico()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->ventas->registrarAcrilico($this->input->post('idCotizacion')));
		}
		else
		{
			echo json_encode(array('0'));
		}
	}
	
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<*  VENTAS POR PRODUCTO  **<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	public function ventasProducto()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'ventasMenu'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data["seccion"]		= 'ventas';
		$data["breadcumb"]		= '<a href="'.base_url().'clientes">Clientes</a>  > Ventas por producto';
		
		$this->load->view("ventas/ventasProducto/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerVentasProducto($limite=0)
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idCliente					= $this->input->post('idCliente');
		$idCotizacion				= $this->input->post('idCotizacion');
		$idProducto					= $this->input->post('idProducto');
		$ordenVentas				= $this->input->post('ordenVentas');
		
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			
			return;
		}
		
		#----------------------------------PAGINACION------------------------------------#
		$Pag["base_url"]		= base_url()."ventas/obtenerVentasProducto/";
		$Pag["total_rows"]		= $this->ventas->contarVentasProducto($inicio,$fin,$idCliente,$idCotizacion,$idProducto,$data['permiso'][4]->activo);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;

		$this->pagination->initialize($Pag);
		#---------------------------------------------------------------------------------#
		
		$data['ventas'] 		= $this->ventas->obtenerVentasProducto($Pag["per_page"],$limite,$inicio,$fin,$idCliente,$idCotizacion,$idProducto,$ordenVentas,$data['permiso'][4]->activo);
		$data['arreglos'] 		= $this->arreglos->obtenerCodigos($this->ventas->obtenerVentasProducto(0,0,$inicio,$fin,$idCliente,0,0,'asc',$data['permiso'][4]->activo));
		$data['limite'] 		= $limite;
		$data['numero'] 		= $Pag["total_rows"];
		$data['idCliente'] 		= $idCliente;
		$data['idCotizacion'] 	= $idCotizacion;
		$data['idProducto'] 	= $idProducto;
		$data['ordenVentas']	= $ordenVentas;

		$this->load->view("ventas/ventasProducto/obtenerVentasProducto",$data);
	}
	
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<*  VENTAS POR SERVICIO  **<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	public function ventasServicio()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'ventasMenu'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data["seccion"]		= 'ventas';
		$data["breadcumb"]		= '<a href="'.base_url().'clientes">Clientes</a>  > Ventas por servicio';
		
		$this->load->view("ventas/ventasServicio/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerVentasServicio($limite=0)
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idCliente					= $this->input->post('idCliente');
		$idCotizacion				= $this->input->post('idCotizacion');
		$idProducto					= $this->input->post('idProducto');
		$ordenVentas				= $this->input->post('ordenVentas');
		
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			
			return;
		}
		
		#----------------------------------PAGINACION------------------------------------#
		$Pag["base_url"]		= base_url()."ventas/obtenerVentasServicio/";
		$Pag["total_rows"]		= $this->ventas->contarVentasServicio($inicio,$fin,$idCliente,$idCotizacion,$idProducto,$data['permiso'][4]->activo);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;

		$this->pagination->initialize($Pag);
		#---------------------------------------------------------------------------------#
		
		$data['ventas'] 		= $this->ventas->obtenerVentasServicio($Pag["per_page"],$limite,$inicio,$fin,$idCliente,$idCotizacion,$idProducto,$ordenVentas,$data['permiso'][4]->activo);
		$data['arreglos'] 		= $this->arreglos->obtenerCodigos($this->ventas->obtenerVentasServicio(0,0,$inicio,$fin,$idCliente,0,0,'asc',$data['permiso'][4]->activo));
		$data['limite'] 		= $limite;
		$data['numero'] 		= $Pag["total_rows"];
		$data['idCliente'] 		= $idCliente;
		$data['idCotizacion'] 	= $idCotizacion;
		$data['idProducto'] 	= $idProducto;
		$data['ordenVentas']	= $ordenVentas;

		$this->load->view("ventas/ventasServicio/obtenerVentasServicio",$data);
	}
	
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<* DEVOLUCIONES PRODUCTO **<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	
	public function obtenerDevoluciones()
	{
		if(!empty($_POST))
		{
			$idCotizacion				= $this->input->post('idCotizacion');
			$data['cotizacion']			= $this->facturacion->obtenerCotizacion($idCotizacion);
			$data['cliente']			= $this->facturacion->obtenerCliente($data['cotizacion']->idCliente);
			$data['productos']			= $this->ventas->obtenerProductosDevoluciones($idCotizacion);
			$data['devoluciones']		= $this->ventas->obtenerDevoluciones($idCotizacion);
			$data['serie']				= $this->ventas->obtenerSerieDevolucion();
			$data['motivos']			= $this->catalogos->obtenerMotivos();
			$data['tipos']				= $this->catalogos->obtenerTiposDevolucion();

			$this->load->view('ventas/devoluciones/obtenerDevoluciones',$data);
		}
	}
	
	public function registrarDevolucion()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->ventas->registrarDevolucion());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerFormularioDinero()
	{
		if(!empty($_POST))
		{
			/*$idCotizacion				= $this->input->post('idCotizacion');*/
			$data['cotizacion']			= $this->facturacion->obtenerCotizacion($this->input->post('idCotizacion'));
			$data['departamentos']		= $this->administracion->obtenerDepartamentos();
			$data['nombres']			= $this->administracion->obtenerNombres();
			$data['productos']			= $this->administracion->obtenerProductos();
			$data['gastos']				= $this->administracion->obtenerTipoGasto();
			$data['bancos']				= $this->bancos->obtenerBancos();
			$data['periodos']			= $this->configuracion->obtenerPeriodosProduccion();
			$data['formas']				= $this->configuracion->seleccionarFormas();
			
			$this->load->view('ventas/devoluciones/dinero/obtenerFormularioDinero',$data);
		}
	}
	
	//PÚNTO DE VENTA
	public function puntoVenta($idCliente=1)
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'puntoVenta'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('63',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		#$data['idArqueo']		= $this->ventas->comprobarArqueo();
		$data['idArqueo']		= 0;
		$data['cliente']		= $this->clientes->obtenerCliente($idCliente);
		$data["idCliente"]		= $idCliente;
		$data["tiendaLocal"]	= $this->tiendaLocal;
		$data["registroVentas"]	= $this->registroVentas;
		$data["mostrarMenu"]	= false;
		$data["idCotizacion"]	= 0;
		$data["seccion"]		= 'ventas';
		$data["breadcumb"]		= '<a href="'.base_url().'clientes">Clientes</a> '.($data['cliente']!=null?' > <a href="'.base_url().'clientes/index/'.$idCliente.'">'.$data['cliente']->empresa.'</a> ':'').' > Punto de venta';
		
		$this->load->view("ventas/puntoVenta/index",$data);
		$this->load->view("pie",$Data);
	}
	
	//FONDO DE CAJA
	public function formularioPedidos()
	{
		$this->load->view('ventas/pedidos/formularioPedidos');	
	}
	
	//CANCELAR VENTAS DE SERVICIOS
	
	public function cancelarVentaServicios()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->ventas->cancelarVentaServicios($this->input->post('idCotizacionPadre'),$this->input->post('idCotizacion'),$this->input->post('idProduct')));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	//FONDO DE CAJA
	public function formularioFondoCaja()
	{
		$this->load->view('ventas/puntoVenta/fondoCaja/formularioFondoCaja');	
	}
	
	public function registrarFondoCaja()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->ventas->registrarFondoCaja());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function ticketFondo($idIngreso=0)
	{
		$data['ingreso']				= $this->administracion->obtenerIngresoEditar($idIngreso);
		
		$this->load->view('ventas/puntoVenta/fondoCaja/ticket',$data);
	}
	
	//RETIRO DE EFECTIVO
	public function formularioRetiroEfectivo()
	{
		$data['fondoCaja']				= $this->ventas->obtenerTotalFondo();
		$data['efectivo']				= $this->ventas->obtenerTotalEfectivo();
		$data['retiros']				= $this->ventas->obtenerTotalRetiros();
		
		$this->load->view('ventas/puntoVenta/retiroEfectivo/formularioRetiroEfectivo',$data);
	}
	
	public function registrarRetiroEfectivo()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->ventas->registrarRetiroEfectivo());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function ticketRetiro($idEgreso=0)
	{
		$data['egreso']				= $this->administracion->obtenerEgresoEditar($idEgreso);
		
		$this->load->view('ventas/puntoVenta/retiroEfectivo/ticket',$data);
	}
	
	//ARQUEO
	
	public function obtenerArqueo()
	{
		$data['arqueo']				= $this->ventas->obtenerArqueo($this->input->post('idArqueo'));
		$data['denominaciones']		= $this->ventas->obtenerDenominacionesArqueo($this->input->post('idArqueo'));
		
		$data['fondoCaja']			= $this->ventas->obtenerTotalFondo();
		$data['efectivo']			= $this->ventas->obtenerTotalEfectivo();

		#$data['fondoInicial']			= $this->punto->obtenerFondoCaja();
		#$data['efectivo']				= $this->punto->obtenerEfectivo();
		#$data['retiros']				= $this->punto->obtenerRetiroEfectivo();
		#$data['sumaDenominaciones']		= $this->punto->sumarDenominaciones($this->input->post('idArqueo'));
		
		$this->load->view('ventas/puntoVenta/arqueo/obtenerArqueo',$data);
	}
	
	
	public function registrarDenominacion()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->ventas->registrarDenominacion());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerArqueoDetalles()
	{
		$data['arqueo']				= $this->ventas->obtenerArqueo($this->input->post('idArqueo'));

		$this->load->view('ventas/puntoVenta/arqueo/obtenerArqueoDetalles',$data);
	}
	
	public function ticketArqueo($idArqueo=0)
	{
		$data['arqueo']				= $this->ventas->obtenerArqueo($idArqueo);
		$data['denominaciones']		= $this->ventas->obtenerDenominacionesArqueo($idArqueo);
		
		$data['fondoCaja']			= $this->ventas->obtenerTotalFondo();
		$data['efectivo']			= $this->ventas->obtenerTotalEfectivo();
		
		$this->load->view('ventas/puntoVenta/arqueo/ticket',$data);
	}
	
	//VENTAS
	public function obtenerProductosVenta($limite=0)
	{
		#$Pag["base_url"]		= base_url()."clientes/obtenerProductosVenta/";
		#$Pag["total_rows"]		= $this->inventarioProductos->contarProductosVentaCerraduras();
		$Pag["per_page"]		= 40;
		#$Pag["num_links"]		= 5;
		#
		#$this->pagination->initialize($Pag);
		
		$data['productos']			= $this->inventarioProductos->obtenerProductosVentaCerraduras($Pag["per_page"],$limite);
		$data['numeroProductos']	= $this->inventarioProductos->contarProductosVentaCerraduras();

		$data['precios']  		= $this->precios;
		$data['precio1']  		= $this->input->post('precio1');
		
		if($this->precios=='1')
		{
			#$this->load->view('clientes/ventas/obtenerProductosVentaPrecios',$data);

			if(sistemaActivo=='cerraduras')
			{
				$this->load->view('clientes/ventas/obtenerProductosVentaCerraduras',$data);
			}
			else
			{
				$this->load->view('clientes/ventas/obtenerProductosVentaPreciosLista',$data);
			}

		}
		else
		{
			$this->load->view('clientes/ventas/obtenerProductosVenta',$data);
		}
	}
	
	public function obtenerProductoCodigo()
	{
		echo json_encode($this->inventarioProductos->obtenerProductoCodigo($this->input->post('codigoBarras')));
	}
	
	public function obtenerProductoId()
	{
		echo json_encode($this->inventarioProductos->obtenerProductoId($this->input->post('idProducto')));
	}
	
	public function obtenerCorteCaja()
	{
		$data['fondoCaja']			= $this->ventas->obtenerTotalFondo();
		$data['retiros']			= $this->ventas->obtenerTotalRetiros();
		$data['formas']				= $this->ventas->obtenerTotalesFormas();

		$this->load->view('ventas/puntoVenta/corteCaja/obtenerCorteCaja',$data);
	}
	
	public function ticketCorte()
	{
		$data['fondoCaja']			= $this->ventas->obtenerTotalFondo();
		$data['retiros']			= $this->ventas->obtenerTotalRetiros();
		$data['formas']				= $this->ventas->obtenerTotalesFormas();

		$this->load->view('ventas/puntoVenta/corteCaja/ticket',$data);
	}
	
	public function obtenerDireccionesEntrega()
	{
		$data['direcciones']			= $this->clientes->obtenerDireccionesEntrega($this->input->post('idCliente'));
		
		if($data['direcciones']==null)
		{
			$this->clientes->registrarDireccionesNuevas($this->input->post('idCliente'));
			$data['direcciones']		= $this->clientes->obtenerDireccionesEntrega($this->input->post('idCliente'));
		}

		$this->load->view('ventas/pedidos/obtenerDireccionesEntrega',$data);
	}
	
	public function registrarVenta()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->venta->registrarVenta());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	
	public function formularioCfdiVenta()
	{
		$data['emisores']		= $this->facturacion->obtenerEmisores();
		$data['metodos']		= $this->configuracion->obtenerMetodosPago();
		$data['usos']			= $this->configuracion->obtenerUsosCfdi();
		$data['formasSat']		= $this->configuracion->obtenerFormasPago();
		$data['direcciones']	= $this->clientes->obtenerDireccionesFiscales($this->input->post('idCliente'));
		
		
		$this->load->view('ventas/cfdi/formularioCfdiVenta',$data);
	}
	
	public function registrarVentaFactura()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			#echo json_encode($this->facturaventa->registrarVenta());
			
			$registro	= $this->facturaventa->registrarVenta();
			
			if(strlen($registro[3])>0)
			{
				 $this->facturacion->enviarFacturaCreada($registro[2],$registro[3]);
			}
			
			echo  json_encode($registro);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function convertirPreFactura()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->ventas->convertirPreFactura());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	
	
	public function formularioEditarEntrega()
	{
		$idEntrega			= $this->input->post('idEntrega');
		
		$data['producto']	= $this->ventas->obtenerProductoEntrega($idEntrega);
		$data['entregados']	= $this->ventas->obtenerProductoEntregado($data['producto']->idProductoCotizacion);
		
		$this->load->view('ventas/entregas/formularioEditarEntrega',$data);
	}
	
	public function editarEntrega()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->ventas->editarEntrega());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	//CAJA
	public function caja()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'caja'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('64',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['formas']			= $this->configuracion->seleccionarFormas();
		$data['estaciones']		= $this->estaciones->obtenerRegistros();
		$data['usuario']		= $this->usuarios->obtenerUsuario($this->_iduser);
		$data['usuarios']		= $this->ventas->obtenerUsuariosCajero();
		$data['idRol']			= $this->session->userdata('rol');
		$data['idUsuario']		= $this->_iduser;
		
		$data["seccion"]		= 'ventas';
		$data["breadcumb"]		= 'Caja';
		
		$this->load->view("ventas/caja/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerVentaFolio()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->ventas->obtenerVentaFolio());
		}
		else
		{
			echo json_encode(array('idCotizacion'=>0));
		}
	}
	
	public function registrarPagoCaja()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->ventas->registrarPagoCaja());
		}
		else
		{
			echo json_encode(array('0'));
		}
	}
	
	public function formularioValesRetiros()
	{
		$tipoRegistro			= $this->input->post('tipoRegistro');
		
		$data['folio']			= configurarFolioTipo($this->ventas->obtenerFolioTipoRegistro($tipoRegistro));
		$data['usuarios']		= $this->configuracion->obtenerListaUsuarios(1);
		$data['tipoRegistro']	= $tipoRegistro;

		$this->load->view('ventas/caja/valesRetiros/formularioValesRetiros',$data);
	}
	
	public function registrarValesRetiros()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->ventas->registrarValesRetiros());
		}
		else
		{
			echo json_encode(array('0'));
		}
	}
	
	//EDITAR LAS VENTAS 
	
	
	public function obtenerVentaEditar()
	{
		if(!empty($_POST))
		{
			$idCotizacion				=$this->input->post('idCotizacion');
			$data['cotizacion']			=$this->facturacion->obtenerCotizacion($idCotizacion);
			$data['productos']			=$this->facturacion->obtenerProductosCotizacion($idCotizacion);

			$this->load->view('ventas/obtenerVentaEditar',$data);
		}
	}
	
	public function editarVenta()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->ventas->editarVenta());
		}
		else
		{
			echo json_encode(array('0'));
		}
	}
	
	public function formularioCorte()
	{
		error_reporting(0);
		
		$idEstacion					= $this->input->post('idEstacion');
		$fecha						= $this->input->post('fecha');
		$idUsuario					= $this->input->post('idUsuario');
		
		$data['retiros']			= $this->ventas->obtenerValesRetiros(1,$idEstacion,$fecha);
		$data['vales']				= $this->ventas->obtenerValesRetiros(2,$idEstacion,$fecha);
		
		$data['efectivo']			= $this->ventas->obtenerEfectivoFecha($idEstacion,$fecha,$idUsuario);
		$data['tarjetas']			= $this->ventas->obtenerTarjetasFecha($idEstacion,$fecha,$idUsuario);
		$data['formas']				= $this->ventas->obtenerTotalesFormasEstacionFecha($idEstacion,$fecha,$idUsuario);
		$data['inicial']			= $this->ventas->obtenerSaldoInicial($fecha);
		$data['pendiente']			= $this->ventas->obtenerTotalesFormasPendiente($idEstacion,$fecha);
		
		
		
		$data['efectivoFecha']			= $this->ventas->obtenerEfectivoFechaDiferente($idEstacion,$fecha,$idUsuario);
		$data['tarjetasFecha']			= $this->ventas->obtenerTarjetasFechaDiferente($idEstacion,$fecha,$idUsuario);
		$data['formasFecha']			= $this->ventas->obtenerTotalesFormasEstacionFechaDiferente($idEstacion,$fecha,$idUsuario);
		$data['enviosCobrados']			= $this->ventas->obtenerEnviosCobrados($idEstacion,$fecha);
		$data['enviosPendientes']		= $this->ventas->obtenerEnviosPendientes($idEstacion,$fecha);
		#$data['pendienteFecha']			= $this->ventas->obtenerTotalesFormasPendiente($idEstacion,$fecha);
		
		$this->load->view('ventas/caja/corte/formularioCorte',$data);
	}
	
	public function obtenerDetallesPagos()
	{
		$idEstacion					= $this->input->post('idEstacion');
		$fecha						= $this->input->post('fecha');
		$idForma					= $this->input->post('idForma');
	
		$data['forma']				= $this->configuracion->obtenerForma($idForma);
		$data['registros']			= $this->ventas->obtenerVentasFormasEstacionFecha($idEstacion,$fecha,$idForma);
		$data['fecha']				= $fecha;
		
		$this->load->view('ventas/caja/corte/obtenerDetallesPagos',$data);
	}
	
	public function obtenerDetallesPendiente()
	{
		$idEstacion					= $this->input->post('idEstacion');
		$fecha						= $this->input->post('fecha');
	
		$data['registros']			= $this->ventas->obtenerFormasPendiente($idEstacion,$fecha);
		$data['fecha']				= $fecha;
		
		$this->load->view('ventas/caja/corte/obtenerDetallesPendiente',$data);
	}
	
	public function imprimirCorte()
	{
		if(!empty($_POST))
		{
			$idEstacion					= $this->input->post('selectEstaciones');
			$fecha						= $this->input->post('txtFechaCorte');
			$idUsuario					= $this->input->post('selectCajeros');

			$data['retiros']			= $this->ventas->obtenerValesRetiros(1,$idEstacion,$fecha);
			$data['vales']				= $this->ventas->obtenerValesRetiros(2,$idEstacion,$fecha);
			
			/*$data['efectivo']			= $this->ventas->obtenerEfectivo($idEstacion,$fecha);
			$data['tarjetas']			= $this->ventas->obtenerTarjetas($idEstacion,$fecha);
			$data['formas']				= $this->ventas->obtenerTotalesFormasEstacion($idEstacion,$fecha);*/
			
			$data['efectivo']			= $this->ventas->obtenerEfectivoFecha($idEstacion,$fecha,$idUsuario);
			$data['tarjetas']			= $this->ventas->obtenerTarjetasFecha($idEstacion,$fecha,$idUsuario);
			$data['formas']				= $this->ventas->obtenerTotalesFormasEstacionFecha($idEstacion,$fecha,$idUsuario);
			
			$data['inicial']			= $this->ventas->obtenerSaldoInicial($fecha);
			
			$data['estacion']			= $this->estaciones->obtenerRegistro($idEstacion,$fecha);
			
			$data['pendiente']			= $this->ventas->obtenerTotalesFormasPendiente($idEstacion,$fecha);
			$data['enviosCobrados']		= $this->ventas->obtenerEnviosCobrados($idEstacion,$fecha);
			$data['enviosPendientes']	= $this->ventas->obtenerEnviosPendientes($idEstacion,$fecha);
			$data['usuario']			= $this->usuarios->obtenerUsuario($idUsuario);
			$data['fecha']				= $fecha;

			$this->load->view('ventas/caja/corte/imprimirCorte',$data);
		}
	}
	
	public function formularioSaldoInicial()
	{
		$this->load->view('ventas/caja/saldoInicial/formularioRegistro');
	}
	
	public function registrarSaldoInicial()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->ventas->registrarSaldoInicial());
		}
		else
		{
			echo json_encode(array('0'));
		}
	}

	public function registrarEntregas()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->ventas->registrarEntregas());
		}
		else
		{
			echo json_encode(array('0'));
		}
	}

	public function borrarFolioEntregas()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->ventas->borrarFolioEntregas());
		}
		else
		{
			echo json_encode(array('0',errorRegistro));
		}
	}

	public function formularioRegistroEnvio()
	{
		$data['personal']		= $this->administracion->obtenerPersonalRegistro(4);
		$data['vehiculos']		= $this->administracion->obtenerVehiculos();

		$this->load->view('reportes/envios/registro/formularioRegistro',$data);
	}
	
	public function registrarRegistroEnvios()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->reportes->registrarRegistroEnvios());
		}
		else
		{
			echo json_encode(array('0'));
		}
	}

	public function obtenerVentaEnvio()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->ventas->obtenerVentaEnvio());
		}
		else
		{
			echo json_encode(array('idCotizacion'=>0));
		}
	}
}
?>
