<?php
class Reportes extends CI_Controller
{
	protected $_fechaActual;
	protected $_iduser;
	protected $_csstyle;
    protected $_tables;
    protected $_role;
	protected $cuota;
	protected $tiendaLocal;

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
		
        $datestring   			= "%Y-%m-%d %H:%i:%s";
	    $this->_fechaActual 	= mdate($datestring,now());
		$this->_iduser 			= $this->session->userdata('id');
		$this->_role 			= $this->session->userdata('role');
		$this->_tables 			= $this->config->item('datatables');
		$this->_csstyle 		= $this->config->item('style');
        $this->_jss				= $this->config->item('js');
		
		$this->load->model("crm_modelo","crm");
		
        $this->load->model("modelousuario","modelousuario");
	 	$this->load->model("compras_modelo","compras");
        $this->load->model("reportes_model","reportes");
		$this->load->model("bancos_model","bancos");
		$this->load->model("inventarioproductos_modelo","inventario");
		$this->load->model("modeloclientes","clientes");
		$this->load->model("proveedores_model","proveedores");
		$this->load->model("administracion_modelo","administracion");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("facturacion_modelo","facturacion");
		$this->load->model("arreglos_modelo","arreglos");
		$this->load->model("ventas_model","ventas");
		$this->load->model("control_modelo","control");
		$this->load->model("pedidos_modelo","pedidos");
		$this->load->model("contabilidad_modelo","contabilidad");
		$this->load->model("tiendas_modelo","tiendas");
		$this->load->model("catalogos_modelo","catalogos");
		$this->load->model("estaciones_modelo","estaciones");
		$this->load->model("facturaglobal_modelo","globales");
		$this->load->model("informacion_modelo","informacion");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		
		$this->tiendaLocal		= $this->session->userdata('tiendaLocal');
	}
	
	#========================================================================================================#
	#=========================================CRITERIOS DE ORDENANAMIENTO====================================#
	#========================================================================================================#
	
	public function ordenamientoVentas($criterio)
	{
		$this->session->set_userdata('criterioVentas',$criterio);
		
		redirect('reportes','refresh');
	}
	
	public function ordenamientoCompras($criterio)
	{
		$this->session->set_userdata('criterioCompras',$criterio);
		
		redirect('reportes/reportesCompras','refresh');
	}
	
	public function ordenamientoCobranza($criterio)
	{
		$this->session->set_userdata('criterioCobranza',$criterio);
		
		redirect('reportes/cobranza','refresh');
	}
	
	function descargarExcel($nombre)
	{
		$this->load->helper('download');
		
		$nombreFisico = $nombre.'.xls';
		$descarga = 'Ventas'.date('Y-m-d').'.xls';
		$data = file_get_contents("media/ficheros/$nombreFisico"); 

		force_download($descarga, $data); 
	}
	
	public function index()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['jFicha_cliente']	=$this->_jss['jFicha_cliente'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='reporteVentas'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('21',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		#$data['ventas'] 	= $this->reportes->obtenerVentas($Pag["per_page"],$limite,$inicio,$fin,$idCliente,$idZona,$idUsuario);
		#$data['total'] 		= $this->reportes->sumarVentas($inicio,$fin,$idCliente,$idZona,$idUsuario);

		$data["breadcumb"]	= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de ventas';

		$this->load->view("reportes/ventas/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerVentas($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idZona					= $this->input->post('idZona');
		$idUsuario				= $this->input->post('idUsuario');
		$idEstacion				= $this->input->post('idEstacion');
		$idForma				= $this->input->post('idForma');
		
		$Pag["base_url"]		= base_url()."reportes/obtenerVentas/";
		$Pag["total_rows"]		= $this->reportes->contarVentas($inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion,$idForma);//Total de Registros
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 4;
		
		$this->pagination->initialize($Pag);
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('21',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		
		$data['ventas'] 		= $this->reportes->obtenerVentas($Pag["per_page"],$limite,$inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion,$idForma);
		$data['total'] 			= $this->reportes->sumarVentas($inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion,$idForma);
		$data['zonas'] 			= $this->configuracion->obtenerZonas();
		$data['usuarios']		= $this->configuracion->obtenerListaUsuarios();
		$data['estaciones']		= $this->estaciones->obtenerRegistros();
		$data['formas']			= $this->configuracion->obtenerFormas();
		$data['idZona'] 		= $idZona;
		$data['idUsuario'] 		= $idUsuario;
		$data['idEstacion']		= $idEstacion;
		$data['idForma']		= $idForma;

		$this->load->view("reportes/ventas/obtenerVentas",$data); 
	}
	
	public function reporteVentas()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idZona					= $this->input->post('idZona');
		$idUsuario				= $this->input->post('idUsuario');
		$idEstacion				= $this->input->post('idEstacion');
		$idForma				= $this->input->post('idForma');
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ventas',''); //Registrar bitácora

		$data['ventas'] 	= $this->reportes->obtenerVentas(0,0,$inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion,$idForma);
		$data['total'] 		= $this->reportes->sumarVentas($inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion,$idForma);
		$data['inicio'] 	= $inicio;
		$data['fin'] 		= $fin;
		$data['reporte']	= 'reportes/ventas/reporteVentas';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Legal-L','','',5,5,40.7,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'ReporteVentas.pdf','F');
		
		echo 'ReporteVentas';
	}
	
	public function excelVentas()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idZona					= $this->input->post('idZona');
		$idUsuario				= $this->input->post('idUsuario');
		$idEstacion				= $this->input->post('idEstacion');
		$idForma				= $this->input->post('idForma');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Ventas',''); //Registrar bitácora
		
		$data['ventas'] 	= $this->reportes->obtenerVentas(0,0,$inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion,$idForma);
		$data['total'] 		= $this->reportes->sumarVentas($inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion,$idForma);

		$this->load->view('reportes/ventas/excelVentas',$data);
	}
	
	function reportesCompras()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		#$Data['JFuntPagClien']	= $this->_jss['JFuntPagClien'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['menuActivo']		= 'reporteCompras'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);

		//PERMISOS
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('22',$this->session->userdata('rol'));
		
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]	= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de compras';
		
		$this->load->view("reportes/compras/reporteCompras",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerCompras($limite=0)
	{
		$inicio			=$this->input->post('inicio');
		$fin			=$this->input->post('fin');
		$idProveedor	=$this->input->post('idProveedor');
		
		$Pag["base_url"]		= base_url()."reportes/obtenerCompras/";
		$Pag["total_rows"]		=$this->reportes->contarCompras($inicio,$fin,$idProveedor);//Total de Registros
		$Pag["per_page"]		=20;
		$Pag["num_links"]		=5;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('22',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('9',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['compras'] 		= $this->reportes->obtenerCompras($Pag["per_page"],$limite,$inicio,$fin,$idProveedor);
		$data['totalCompras'] 	= $this->reportes->sumarCompras($inicio,$fin,$idProveedor);
		$data['inicio']			=$inicio;
		$data['fin']			=$fin;
		$data['idProveedor']	=$idProveedor;
		
		$this->load->view("reportes/compras/obtenerCompras", $data); 
	}

	public function reporteCompras($inicio,$fin,$idProveedor)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Compras',''); //Registrar bitácora

		$data['compras'] 		= $this->reportes->obtenerCompras(0,0,$inicio,$fin,$idProveedor);
		$data['totalCompras'] 	= $this->reportes->sumarCompras($inicio,$fin,$idProveedor);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte'] 		= 'reportes/compras/comprasPdf';

		$html	=$this->load->view('reportes/principal',$data,true);

		
		$this->mpdf->mPDF('en-x','Letter-L','','',3,3,40.7,10,2,0);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'Compras.pdf','F');
		
		echo 'Compras';
	}
	
	public function excelCompras($inicio,$fin,$idProveedor)
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Compras',''); //Registrar bitácora
		
		$data['compras']		=$this->reportes->obtenerCompras(0,0,$inicio,$fin,$idProveedor);
		$data['totalCompras'] 	= $this->reportes->sumarCompras($inicio,$fin,$idProveedor);

		$this->load->view("reportes/compras/excelCompras",$data); 
	}
	
	public function cobranza($inicio='fecha',$fin='fecha',$idCliente=0,$limite=0)
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='cobranza'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		$Pag["base_url"]		= base_url()."reportes/cobranza/".$inicio.'/'.$fin.'/'.$idCliente.'/';
		$Pag["total_rows"]		=$this->reportes->contarCobranza($inicio,$fin,$idCliente);//Total de Registros
		$Pag["per_page"]		=30;
		$Pag["num_links"]		=5;
		$Pag["uri_segment"]		=6;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('23',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['ventas'] 		= $this->reportes->obtenerVentasCobranza($Pag["per_page"],$limite,$inicio,$fin,$idCliente);
		$data['totalCobranza'] 	= $this->reportes->sumarVentasCobranza($inicio,$fin,$idCliente);
		$data['inicio'] 		=$inicio;
		$data['fin'] 			=$fin;
		$data['idCliente'] 		=$idCliente;
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de cobranza';

		$this->load->view("reportes/cobranza/cobranza",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function reporteCobranza($inicio,$fin,$idCliente)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Cobranza',''); //Registrar bitácora

		$data['ventas'] 		= $this->reportes->obtenerVentasCobranza(0,0,$inicio,$fin,$idCliente);
		$data['totalCobranza'] 	= $this->reportes->sumarVentasCobranza($inicio,$fin,$idCliente);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte']		= 'reportes/cobranza/reporteCobranza';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',5,5,40,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'ReporteCobranza.pdf','F');
		
		echo 'ReporteCobranza';
	}
	
	public function excelCobranza($inicio,$fin,$idCliente)
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Cobranza',''); //Registrar bitácora
		
		$data['ventas'] 		= $this->reportes->obtenerVentasCobranza(0,0,$inicio,$fin,$idCliente);
		$data['totalCobranza'] 	= $this->reportes->sumarVentasCobranza($inicio,$fin,$idCliente);
		
		$this->load->view('reportes/cobranza/excelCobranza',$data);
	}
	
	#--------------------------------BUSQUEDA PARA VENTAS------------------------------------#
	function busquedaFechaVentas($fecha)
	{
		if($fecha=='todas')
		{
			$fecha="";
		}
		
		$idCliente="";
		
	 	$this->session->set_userdata('idClienteVenta',$idCliente);
		$this->session->set_userdata('fechaVentaReporte',$fecha);
		$this->session->set_userdata('idIdentificadorVenta','');
		
		redirect('reportes/index','refresh');
	}
	
	function busquedaClienteVentas($idCliente)
	{
		if($idCliente=='todos')
		{
			$idCliente="";
		}
		
	 	$this->session->set_userdata('idClienteVenta',$idCliente);
		$this->session->set_userdata('idUsuarioVenta','');
		
		redirect('reportes/index','refresh');
	}
	
	function busquedaUsuarioVentas($idUsuario)
	{
		if($idUsuario=='todos')
		{
			$idUsuario="";
		}
		
	 	$this->session->set_userdata('idUsuarioVenta',$idUsuario);
		$this->session->set_userdata('idClienteVenta','');
		$this->session->set_userdata('fechaVentaReporte','');
		
		redirect('reportes/index','refresh');
	}
	
	function busquedaIdentificadorVentas($identificador)
	{
		if($identificador=='todos')
		{
			$identificador="";
		}
		
	 	$this->session->set_userdata('idIdentificadorVenta',$identificador);
		$this->session->set_userdata('idClienteVenta','');
		$this->session->set_userdata('fechaVentaReporte','');
		
		redirect('reportes/index','refresh');
	}
	
	function busquedaProductosVentas($idProducto)
	{
		if($idProducto=='todos')
		{
			$idProducto="";
		}
		
		$this->session->set_userdata('idProductoVenta',$idProducto);
	 	$this->session->set_userdata('idIdentificadorVenta','');
		$this->session->set_userdata('idClienteVenta','');
		$this->session->set_userdata('fechaVentaReporte','');
		
		redirect('reportes/productosVentas','refresh');
	}
	
	function productosVentas($Limite=0)
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['jFicha_cliente']	=$this->_jss['jFicha_cliente'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='reporteVentas'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		$Pag["base_url"]		= base_url()."reportes/productosVentas/";
		$Pag["total_rows"]		=$this->reportes->contarVentasProducto();//Total de Registros
		$Pag["per_page"]		=20;
		$Pag["num_links"]		=5;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('13',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['clientes'] = $this->clientes->obtenerClientes();
		$data['ventas'] = $this->reportes->obtenerVentasProducto($Pag["per_page"],$Limite);

		$this->load->view("reportes/reporteProductos",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function descargarExcelReportes($fichero,$reporte)
	{
		$this->load->helper('download');
		
		$nombreFisico 	= $fichero.'.xls';
		$descarga 		= $reporte.'_'.date('Y-m-d').'.xls';
		$data 			= file_get_contents("media/ficheros/$nombreFisico"); 

		force_download($descarga, $data); 
	}
	
	
	//REPORTE DE INGRESOS
	public function ingresos()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='ingresos'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('25',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['cuentas']		= $this->bancos->obtenerCuentas();
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de ingresos';
		
		$this->load->view("reportes/ingresos/ingresos", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerIngresos($limite=0)
	{
		$inicio				=$this->input->post('inicio');
		$fin				=$this->input->post('fin');
		$idCuenta			=$this->input->post('idCuenta');
		
		$idDepartamento		= $this->input->post('idDepartamento');
		$idProducto			= $this->input->post('idProducto');
		$idGasto			= $this->input->post('idGasto');
		$cliente			= $this->input->post('cliente');
		$idIngreso			= $this->input->post('idIngreso');
		$criterio			= $this->input->post('criterio');
		
		$idDepartamento		=strlen($idDepartamento)==0?0:$idDepartamento;
		$idProducto			=strlen($idProducto)==0?0:$idProducto;
		$idGasto			=strlen($idGasto)==0?0:$idGasto;
		
		//-----------------------------PAGINACION--------------------------------------
		$paginacion["base_url"]		= base_url()."reportes/obtenerIngresos/";
		$paginacion["total_rows"]	=$this->reportes->contarIngresos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$cliente,$idIngreso,$criterio);
		$paginacion["per_page"]		=50;
		$paginacion["num_links"]	=5;
		
		$this->pagination->initialize($paginacion);
		
		$data['departamentos'] 	= $this->administracion->obtenerDepartamentos();
		$data['productos'] 		= $this->administracion->obtenerProductos();
		$data['gastos'] 		= $this->administracion->obtenerTipoGasto();
		$data['ingresos'] 		= $this->reportes->obtenerIngresos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$cliente,$paginacion['per_page'],$limite,$idIngreso,$criterio);
		$data['sumaIngresos'] 	= $this->reportes->sumarIngresos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$cliente,$idIngreso,$criterio);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('25',$this->session->userdata('rol'));
		$data['permisoIngresos']= $this->configuracion->obtenerPermisosBoton('13',$this->session->userdata('rol'));
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['idCuenta'] 		= $idCuenta;
		$data['idDepartamento'] = $idDepartamento;
		$data['idProducto'] 	= $idProducto;
		$data['idGasto'] 		= $idGasto;
		$data['idCliente'] 		= $cliente;
		$data['idIngreso'] 		= $idIngreso;
		$data['idDepartamento'] = $idDepartamento;
		$data['idProducto'] 	= $idProducto;
		$data['idGasto'] 		= $idGasto;
		$data['criterio'] 		= $criterio;
		$data['limite'] 		= $limite+1;
		
		if(sistemaActivo=='IEXE')
		{
			$this->load->view('reportes/ingresos/iexe/obtenerIngresos',$data);
		}
		else
		{
			$this->load->view('reportes/ingresos/obtenerIngresos',$data);
		}
	}
	
	public function reporteIngresos()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$inicio				=$this->input->post('inicio');
		$fin				=$this->input->post('fin');
		$idCuenta			=$this->input->post('idCuenta');
		
		$idDepartamento		= $this->input->post('idDepartamento');
		$idProducto			= $this->input->post('idProducto');
		$idGasto			= $this->input->post('idGasto');
		$cliente			= $this->input->post('cliente');
		$idIngreso			= $this->input->post('idIngreso');
		$criterio			= $this->input->post('criterio');
		
		$idDepartamento		=strlen($idDepartamento)==0?0:$idDepartamento;
		$idProducto			=strlen($idProducto)==0?0:$idProducto;
		$idGasto			=strlen($idGasto)==0?0:$idGasto;
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ingresos',''); //Registrar bitácora

		$data['ingresos'] 		= $this->reportes->obtenerIngresos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$cliente,0,0,$idIngreso,$criterio);
		$data['sumaIngresos'] 	= $this->reportes->sumarIngresos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$cliente,$idIngreso,$criterio);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte'] 		= sistemaActivo=='IEXE'?'reportes/ingresos/iexe/reporteIngresos':'reportes/ingresos/reporteIngresos';

		$html=$this->load->view('reportes/principal',$data,true);

		
		$this->mpdf->mPDF('en-x','Legal-L','','',2,2,27,47,2,0);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);

#		$this->mpdf->Output();
		$this->mpdf->Output('media/ficheros/reporteIngresos.pdf','F');
		echo 'reporteIngresos';
	}
	
	//EL EXCEL DE LOS INGRESOS
	public function excelIngresos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idIngreso)
	{
		$criterio			= $this->input->post('criterio');
		$cliente			= $this->input->post('cliente');
		
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Ingresos',''); //Registrar bitácora
		
		$data['sumaIngresos']	= $this->reportes->sumarIngresos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$cliente,$idIngreso,$criterio);
		$data['ingresos']		=$this->reportes->obtenerIngresos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$cliente,0,0,$idIngreso,$criterio);
		
		if(sistemaActivo=='IEXE')
		{
			$this->load->view('reportes/ingresos/iexe/excelIngresos',$data);
		}
		else
		{
			$this->load->view('reportes/ingresos/excelIngresos',$data);
		}
	}

	public function nomina($limite=0)
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='nomina'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		//-----------------------------PAGINACION--------------------------------------
		#$paginacion["base_url"]		= base_url()."reportes/ingresos/";
		#$paginacion["total_rows"]	=$this->reportes->contarIngresos();//Total de Registros
		#$paginacion["per_page"]		=20;
		#$paginacion["num_links"]	=5;
		
		#$this->pagination->initialize($paginacion);
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('13',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		#$data['ingresos'] 		= $this->reportes->obtenerIngresos($paginacion["per_page"],$limite);
		#$data['bancos'] 		= $this->bancos->obtenerBancos();
		
		$this->load->view("reportes/nomina/index", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function reporteNomina($inicio,$fin,$idPersonal=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');

		$data['personal'] 	= $this->reportes->obtenerNomina(0,0,$idPersonal);
		$data['inicio'] 	= $inicio;
		$data['fin'] 		= $fin;
		$data['reporte'] 	= 'reportes/nomina/reporteNomina';

		$html=$this->load->view('reportes/principal',$data,true);

		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,30,47,2,0);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	public function obtenerNomina($limite=0)
	{
		$paginacion["base_url"]		= base_url()."reportes/obtenerNomina/";
		$paginacion["total_rows"]	=$this->reportes->contarNomina();
		$paginacion["per_page"]		=20;
		$paginacion["num_links"]	=5;
		
		$this->pagination->initialize($paginacion);
		
		$inicio		=$this->input->post('inicio');
		$fin		=$this->input->post('fin');
		$idPersonal	=$this->input->post('idPersonal');
		
		$dias				=$this->reportes->obtenerDias($inicio,$fin);
		$dias++;
		$data['personal'] 	= $this->reportes->obtenerNomina($paginacion["per_page"],$limite,$idPersonal);
		$data['total'] 		= $this->reportes->sumarNomina($idPersonal,$dias);
		$data['dias'] 		= $dias;
		
		$this->load->view("reportes/nomina/obtenerNomina", $data); 
	}
	
	public function pagarNomina()
	{
		if(!empty($_POST))
		{
			$nomina	=$this->administracion->pagarNomina();
			echo $nomina;
		}
	}
	
	public function formularioNomina()
	{
		$this->load->view("reportes/nomina/formularioNomina"); 
	}
	
	public function gastos()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='gastosReporte';
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS 
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('26',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['cuentas']		= $this->bancos->obtenerCuentas();
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de egresos';
		
		$this->load->view("reportes/gastos/gastos", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerGastos($limite=0)
	{
		$inicio				=$this->input->post('inicio');
		$fin				=$this->input->post('fin');
		$idCuenta			=$this->input->post('idCuenta');
		$idDepartamento		=$this->input->post('idDepartamento');
		$idProducto			=$this->input->post('idProducto');
		$idGasto			=$this->input->post('idGasto');
		$idProveedor		=$this->input->post('idProveedor');
		$criterio			= $this->input->post('criterio');
		
		$idDepartamento		=strlen($idDepartamento)==0?0:$idDepartamento;
		$idProducto			=strlen($idProducto)==0?0:$idProducto;
		$idGasto			=strlen($idGasto)==0?0:$idGasto;
		
		//-----------------------------PAGINACION--------------------------------------
		$paginacion["base_url"]		= base_url()."reportes/obtenerGastos/";
		$paginacion["total_rows"]	=$this->reportes->contarGastos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idProveedor,$criterio);
		$paginacion["per_page"]		=50;
		$paginacion["num_links"]	=5;
		
		$this->pagination->initialize($paginacion);
		
		$data['departamentos'] 		= $this->administracion->obtenerDepartamentos();
		$data['productos'] 			= $this->administracion->obtenerProductos();
		$data['tipos'] 				= $this->administracion->obtenerTipoGasto();
		
		$data['gastos'] 			= $this->reportes->obtenerGastos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idProveedor,$paginacion['per_page'],$limite,$criterio);
		$data['sumaGastos'] 		= $this->reportes->sumarGastos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idProveedor,$criterio);
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('26',$this->session->userdata('rol'));
		$data['inicio'] 			= $inicio;
		$data['fin'] 				= $fin;
		$data['idCuenta'] 			= $idCuenta;
		$data['idDepartamento'] 	= $idDepartamento;
		$data['idProducto'] 		= $idProducto;
		$data['idGasto'] 			= $idGasto;
		$data['idProveedor'] 		= $idProveedor;
		$data['criterio'] 			= $criterio;
		$data['limite'] 			= $limite+1;
		
		$this->load->view('reportes/gastos/obtenerGastos',$data);
	}
	
	public function reporteGastos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idProveedor,$criterio)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Egresos',''); //Registrar bitácora

		$data['gastos'] 	= $this->reportes->obtenerGastos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idProveedor,0,0,$criterio);
		$data['sumaGastos'] = $this->reportes->sumarGastos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idProveedor,$criterio);
		$data['inicio'] 	= $inicio;
		$data['fin'] 		= $fin;
		$data['reporte'] 	= 'reportes/gastos/reporteGastos';

		$html=$this->load->view('reportes/principal',$data,true);

		
		$this->mpdf->mPDF('en-x','Legal-L','','',2,2,40,10,2,0);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}

	//EL EXCEL DE LOS INGRESOS
	public function excelGastos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idProveedor)
	{
		$criterio			= $this->input->post('criterio');
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Egresos',''); //Registrar bitácora
		
		$data['sumaGastos'] = $this->reportes->sumarGastos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idProveedor,$criterio);
		$data['gastos']		= $this->reportes->obtenerGastos($inicio,$fin,$idCuenta,$idDepartamento,$idProducto,$idGasto,$idProveedor,0,0,$criterio);
		
		$this->load->view('reportes/gastos/excelGastos',$data);	
	}
	
	//REPORTE DE FACTURACIÓN ELECTRONICA
	public function facturacion()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='facturacionReporte';
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS 
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']	=$this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));

		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['emisores']		= $this->facturacion->obtenerEmisores();
		$data["tiendaLocal"]	= $this->tiendaLocal;
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de facturación';
		
		$this->load->view("reportes/facturacion/index", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerFacturas($limite=0)
	{
		set_time_limit(0); 
		
		$fecha						= $this->input->post('fecha');
		$mes						= strlen($fecha)>4?substr($fecha,5,2):'mes';
		$anio						= strlen($fecha)>4?substr($fecha,0,4):'anio';
		$idCliente					= $this->input->post('idCliente');
		$idFactura					= $this->input->post('idFactura');
		$idEmisor					= $this->input->post('idEmisor');
		$tipo						= $this->input->post('tipo');
		$canceladas					= $this->input->post('canceladas');
		$idEstacion					= $this->input->post('idEstacion');
		
		#-----------------------------PAGINACION--------------------------------------#
		$paginacion["base_url"]		= base_url()."reportes/obtenerFacturas/";
		$paginacion["total_rows"]	= $this->reportes->contarFacturas($mes,$anio,$idCliente,$idFactura,$idEmisor,$tipo,$canceladas,$idEstacion);
		$paginacion["per_page"]		= 20;
		$paginacion["num_links"]	= 5;
		
		$this->pagination->initialize($paginacion);
		
		$data['facturas'] 			= $this->reportes->obtenerFacturas($mes,$anio,$idCliente,$paginacion["per_page"],$limite,$idFactura,$idEmisor,$tipo,$canceladas,1,$idEstacion);
		$data['total'] 				= $this->reportes->sumarFacturas($mes,$anio,$idCliente,$idFactura,$idEmisor,$tipo,$canceladas,$idEstacion);
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		$data['estaciones']			= $this->estaciones->obtenerRegistros();
		$data['mes']				= $mes;
		$data['anio']				= $anio;
		$data['idCliente']			= $idCliente;
		$data['idFactura']			= $idFactura;
		$data['idEmisor']			= $idEmisor;
		$data['tipo']				= $tipo;
		$data['idEstacion']			= $idEstacion;
		$data["tiendaLocal"]		= $this->tiendaLocal;
		$data['numero']				= $limite+1;
		
		$this->load->view('reportes/facturacion/obtenerFacturas',$data);
	}
	
	public function reporteFacturacion($mes,$anio,$idEmisor=0,$tipo=0,$descarga=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$canceladas					= $this->input->post('canceladas');
		$idEstacion					= $this->input->post('idEstacion');
		$idCliente					= $this->input->post('idCliente');
		$idFactura					= $this->input->post('idFactura');
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Facturación',''); //Registrar bitácora

		$data['facturas'] 	= $this->reportes->obtenerFacturas($mes,$anio,$idCliente,0,0,$idFactura,$idEmisor,$tipo,$canceladas,1,$idEstacion);
		$data['mes'] 		=$mes;
		$data['anio'] 		=$anio;
		$data['reporte'] 	='reportes/facturacion/reporteFacturacion';

		$html				=$this->load->view('reportes/principal',$data,true);
		#$pie				=$this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter-L','','',7,7,35,16,7,10);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		if($descarga==1)
		{
			$this->mpdf->Output(carpetaFicheros.'ReporteFacturacion.pdf','F');
			
			echo 'ReporteFacturacion';
		}
		else
		{
			$this->mpdf->Output();
		}
	}
	
	public function excelFacturacion($mes,$anio,$idEmisor=0,$tipo=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');
		$canceladas					= $this->input->post('canceladas');
		$idEstacion					= $this->input->post('idEstacion');
		$idCliente					= $this->input->post('idCliente');
		$idFactura					= $this->input->post('idFactura');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Facturación',''); //Registrar bitácora

		$data['facturas'] 	= $this->reportes->obtenerFacturas($mes,$anio,$idCliente,0,0,$idFactura,$idEmisor,$tipo,$canceladas,1,$idEstacion);
		
		$this->load->view('reportes/facturacion/excelFacturacion',$data);
	}
	
	public function obtenerFacturasCliente($limite=0)
	{
		$fecha			=$this->input->post('fecha');
		$mes			=strlen($fecha)>4?substr($fecha,5,2):'mes';
		$anio			=strlen($fecha)>4?substr($fecha,0,4):'anio';
		$idCliente		=$this->input->post('idCliente');
		$idFactura		=0;
		
		#-----------------------------PAGINACION--------------------------------------#
		$paginacion["base_url"]		= base_url()."reportes/obtenerFacturasCliente/";
		$paginacion["total_rows"]	=$this->reportes->contarFacturas($mes,$anio,$idCliente,0,0,0);
		$paginacion["per_page"]		=8;
		$paginacion["num_links"]	=5;
		
		$this->pagination->initialize($paginacion);

		$data['facturas'] 		= $this->reportes->obtenerFacturas($mes,$anio,$idCliente,$paginacion["per_page"],$limite,0,0,0);
		$data['total'] 			= $this->reportes->sumarFacturas($mes,$anio,$idCliente,$idFactura,0,0);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		$data['mes']			= $mes;
		$data['anio']			= $anio;
		$data['idCliente']		= $idCliente;
		
		$this->load->view('reportes/facturacion/obtenerFacturasCliente',$data);
	}

	public function zipearFacturas($mes,$anio,$idEmisor=0,$tipo=0)
	{
		$this->load->helper('qrlib');
		#$this->load->library('../controllers/pdf');
		
		$this->configuracion->registrarBitacora('Exportar a zip','Reportes - Facturación',''); //Registrar bitácora
		
		$canceladas					= $this->input->post('canceladas');
		$idEstacion					= $this->input->post('idEstacion');
		$idCliente					= $this->input->post('idCliente');
		$idFactura					= $this->input->post('idFactura');
		
		$data['facturas'] 		= $this->reportes->obtenerFacturas($mes,$anio,$idCliente,0,0,$idFactura,$idEmisor,$tipo,$canceladas,1,$idEstacion);
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
		$data['mes']			= $mes;
		$data['anio']			= $anio;
		$data['idCliente']		= $idCliente;
		$data['idFactura']		= $idFactura;
		$data['idEmisor']		= $idEmisor;
		#$data['pdf']			= new $this->pdf;
		
		$this->load->view('reportes/facturacion/zipearFacturas',$data);
	}
	
	public function zipearFactura()
	{
		if(!empty($_POST))
		{
			$this->load->helper('qrlib');
			#$this->load->library('../controllers/pdf');
			
			$data['factura'] 		= $this->reportes->obtenerFactura($this->input->post('idFactura'));
			$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
			$data['idFactura']		= $this->input->post('idFactura');
			#$data['pdf']			= new $this->pdf;
			
			$this->load->view('reportes/facturacion/zipearFactura',$data);
		}
		else
		{
			echo "0";
		}
	}
	
	public function descargaZip($nombre)
	{
		$this->load->helper('download');

		$data = file_get_contents("media/fel/".$nombre); // Read the file's contents

		force_download($nombre, $data); 
	}
	
	//REPORTE DE FACTURACIÓN ELECTRONICA
	public function facturacionPagos()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'facturacionReporte';
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS 
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']	=$this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));

		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['emisores']		= $this->facturacion->obtenerEmisores();
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de pagos';
		
		$this->load->view("reportes/facturacion/pagos/index", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerFacturasPagos($limite=0)
	{
		$fecha						= $this->input->post('fecha');
		$mes						= strlen($fecha)>4?substr($fecha,5,2):'mes';
		$anio						= strlen($fecha)>4?substr($fecha,0,4):'anio';
		$idCliente					= $this->input->post('idCliente');
		$idFactura					= $this->input->post('idFactura');
		$idEmisor					= $this->input->post('idEmisor');
		$tipo						= $this->input->post('tipo');
		
		#-----------------------------PAGINACION--------------------------------------#
		$paginacion["base_url"]		= base_url()."reportes/obtenerFacturas/";
		$paginacion["total_rows"]	= $this->reportes->contarFacturasPagos($mes,$anio,$idCliente,$idFactura,$idEmisor,$tipo);
		$paginacion["per_page"]		= 20;
		$paginacion["num_links"]	= 5;
		
		$this->pagination->initialize($paginacion);
		
		$data['facturas'] 			= $this->reportes->obtenerFacturasPagos($mes,$anio,$idCliente,$paginacion["per_page"],$limite,$idFactura,$idEmisor,$tipo);
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		$data['mes']				= $mes;
		$data['anio']				= $anio;
		$data['idCliente']			= $idCliente;
		$data['idFactura']			= $idFactura;
		$data['idEmisor']			= $idEmisor;
		$data['tipo']				= $tipo;
		
		$this->load->view('reportes/facturacion/pagos/obtenerFacturasPagos',$data);
	}
	
	//REPORTE DE FLUJO DE EFECTIVO
	public function flujoEfectivo()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];

		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='flujoEfectivo'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('27',$this->session->userdata('rol'));
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de flujo de efectivo';
		
		$this->load->view("reportes/flujoEfectivo/flujoEfectivo",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerFlujoEfectivo()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('27',$this->session->userdata('rol'));
		
		$this->load->view("reportes/flujoEfectivo/obtenerFlujoEfectivo",$data); 
	}
	
	public function reporteFlujo($mes,$anio)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Flujo de efectivo',''); //Registrar bitácora

		$data['cuentas'] 	=$this->bancos->obtenerCuentas();
		$data['mes'] 		=$mes;
		$data['anio'] 		=$anio;
		$data['reporte'] 	='reportes/flujoEfectivo/reporteFlujo';

		$html				=$this->load->view('reportes/principal',$data,true);
		#$pie				=$this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',15,10,31,16,7,10);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	public function excelFlujoEfectivo($mes,$anio)
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Flujo de efectivo',''); //Registrar bitácora
		
		$data['cuentas'] 	=$this->bancos->obtenerCuentas();
		$data['mes'] 		=$mes;
		$data['anio'] 		=$anio;
		
		$this->load->view('reportes/flujoEfectivo/excelFlujoEfectivo',$data);
	}
	
	//REPORTE DE AUXILIAR DE PROVEEDORES
	public function auxiliarProveedores()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];

		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['jvalidate']		= $this->_jss['jvalidate'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		#$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['menuActivo']		= 'auxiliarProveedores'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('28',$this->session->userdata('rol'));
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['proveedores']	=$this->reportes->obtenerProveedores(); 
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de auxiliar de proveedores';

		$this->load->view("reportes/auxiliarProveedores/auxiliarProveedores",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerAuxiliarProveedores()
	{
		$inicio					=$this->input->post('inicio');
		$fin					=$this->input->post('fin');
		$inicio					=strlen($inicio)>7?$inicio:'fecha';
		$fin					=strlen($fin)>7?$fin:'fecha';	
		$idProveedor			=$this->input->post('idProveedor');
		
		$data['auxiliar']		= $this->reportes->obtenerAuxiliarProveedores($inicio,$fin,$idProveedor);
		$data['total']			= $this->reportes->sumarAuxiliarProveedores($inicio,$fin,$idProveedor);
		$data['proveedor']		= $this->reportes->obtenerProveedor($idProveedor);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('28',$this->session->userdata('rol'));
		$data['inicio']			= $inicio;
		$data['fin']			= $fin;
		$data['idProveedor']	= $idProveedor;
		
		$this->load->view("reportes/auxiliarProveedores/obtenerAuxiliarProveedores",$data); 
	}
	
	public function reporteAuxiliar($inicio,$fin,$idProveedor)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Auxiliar de proveedores',''); //Registrar bitácora
		
		$proveedor			=$this->reportes->obtenerProveedor($idProveedor);
		
		$data['auxiliar'] 	=$this->reportes->obtenerAuxiliarProveedores($inicio,$fin,$idProveedor);
		$data['total']		=$this->reportes->sumarAuxiliarProveedores($inicio,$fin,$idProveedor);
		$data['proveedor'] 	=$proveedor!=null?$proveedor->empresa:'Todos los proveedores';
		$data['reporte'] 	='reportes/auxiliarProveedores/auxiliarPDF';
		$data['inicio'] 	=$inicio;
		$data['fin'] 		=$fin;

		$html				=$this->load->view('reportes/principal',$data,true);
		#$pie				=$this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',5,5,45,16,7,10);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	public function excelAuxiliarProveedores($inicio,$fin,$idProveedor)
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Auxiliar de proveedores',''); //Registrar bitácora
		
		$proveedor			=$this->reportes->obtenerProveedor($idProveedor);
		$data['auxiliar'] 	=$this->reportes->obtenerAuxiliarProveedores($inicio,$fin,$idProveedor);
		$data['total']		=$this->reportes->sumarAuxiliarProveedores($inicio,$fin,$idProveedor);
		$data['proveedor'] 	=$proveedor!=null?$proveedor->empresa:'Todos los proveedores';
		$data['inicio'] 	=$inicio;
		$data['fin'] 		=$fin;
		
		$this->load->view('reportes/auxiliarProveedores/excelAuxiliarProveedores',$data);
	}
	
	//REPORTE DE PRONOSTICO DE PAGOS
	public function pronosticoPagos()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];

		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['menuActivo']		='pronosticoPagos'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('32',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		$data['proveedores']		=$this->reportes->obtenerProveedores(); 

		$this->load->view("reportes/pronosticoPagos/pronosticoPagos",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function reportePronostico($idProveedor,$fechaInicio,$fechaFin)
	{
		$this->load->library('mpdf/mpdf');
		$this->load->library('ccantidadletras');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Pronóstico de pagos',''); //Registrar bitácora

		$data['proveedores'] 		= $this->compras->obtenerProveedoresCompras($idProveedor,$fechaInicio,$fechaFin);
		$data['fechaInicio']		=$fechaInicio;
		$data['fechaFin']			=$fechaFin;
		$data['reporte']			='reportes/pronosticoPagos/pronosticoPDF';
		#$this->load->view('compras/pronostico',$data);
		
		$html	=$this->load->view('reportes/principal',$data,true);
		#$pie	=$this->load->view('reportes/pie',$data,true);
		#$pie=$this->load->view('clientes/formatos/paquetes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter-L','','',10,10,41,16,7,10);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->useSubstitutions=false; 
		$this->mpdf->simpleTables = true;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	
	public function obtenerPronostico()
	{
		$idProveedor	=$this->input->post('idProveedor');
		$fechaInicio	=$this->input->post('fechaInicio');
		$fechaFin		=$this->input->post('fechaFin');
		
		$fechaInicio	=strlen($fechaInicio)>7?$fechaInicio:'fecha';
		$fechaFin		=strlen($fechaFin)>7?$fechaFin:'fecha';
		$proveedores	=$this->compras->obtenerProveedoresCompras($idProveedor,$fechaInicio,$fechaFin);
		
		if($proveedores!=null)
		{
			echo '
			<table class="admintable" width="100%">
				<tr>
					<th colspan="2">Proveedores</th>
					<th colspan="4">
						Desglose de saldos por pagar en días
						<img src="'.base_url().'img/pdf.png" width="22" 
						onclick="window.open(\''.base_url().'reportes/reportePronostico/'.$idProveedor.'/'.$fechaInicio.'/'.$fechaFin.'\')"
					</th>
				</tr>
				<tr>
					<th>Compras</th>
					<th width="13%">Saldo</th>
					<th width="13%">1-7</th>
					<th width="13%">8-14</th>
					<th width="13%">15-21</th>
					<th width="13%">22 o más</th>
				</tr>';
			
			$i=1;
			foreach($proveedores as $row)
			{
				echo'
				<tr>
					<td class="totales" align="left" colspan="9">'.$i.' '.$row->empresa.'</td>
				</tr>';
				
				#$compras=$this->compras->obtenerComprasProveedor($row->idProveedor,$fechaInicio,$fechaFin);
				$compras	=$this->reportes->obtenerComprasProveedor($fechaInicio,$fechaFin,$row->idProveedor);
				
				$total		=0;
				$total1		=0;
				$total8		=0;
				$total14	=0;
				$total22	=0;
				
				foreach($compras as $compra)
				{
					$pagado		=$this->reportes->sumarPagadoCompra($compra->idCompras);
					$saldo		=$compra->total-$pagado;
					$pronostico	=$this->compras->obtenerDiferenciaFecha($compra->fechaCompra);
					
					$pro1	=0;
					$pro8	=0;
					$pro14	=0;
					$pro22	=0;
					
					switch($pronostico)
					{
						case $pronostico>=1 and $pronostico<=7:
						$pro1	=$saldo;
						break;
						
						case $pronostico>=8 and $pronostico<=14:
						$pro8	=$saldo;
						break;
						
						case $pronostico>=15 and $pronostico<=21:
						$pro14	=$saldo;
						break;
						
						case $pronostico>21:
						$pro22	=$saldo;
						break;
					}
					
					$total 		+=$saldo;
					$total1		+=$pro1;
					$total8		+=$pro8;
					$total14	+=$pro14;
					$total22	+=$pro22;
				
					echo'
					<tr>
						<td class="sinBordes"  align="right">';
							echo $compra->nombre.' | '.substr($compra->fechaCompra,0,10);
							echo'
						</td>
						<td width="13%" class="sinBordes" align="right">$'.number_format($saldo,2).'</td>
						<td width="13%" class="sinBordes" align="right">$'.number_format($pro1,2).'</td>
						<td width="13%" class="sinBordes" align="right">$'.number_format($pro8,2).'</td>
						<td width="13%" class="sinBordes" align="right">$'.number_format($pro14,2).'</td>
						<td width="13%" class="sinBordes" align="right">$'.number_format($pro22,2).'</td>
						
					</tr>';
				}
				
				echo '
				<tr>
					<td width=""  class="sinBordes" align="center"></td>
					<td width="13%" align="right" class="totales">$'.number_format($total,2).'</td>
					<td width="13%" align="right" class="totales">$'.number_format($total1,2).'</td>
					<td width="13%" align="right" class="totales">$'.number_format($total8,2).'</td>
					<td width="13%" align="right" class="totales">$'.number_format($total14,2).'</td>
					<td width="13%" align="right" class="totales">$'.number_format($total22,2).'</td>
					
					
				</tr>';
			
				$i++;
			}
			
			echo '</table>';
		}
		else
		{
			echo '<div class="Error_validar">Sin registro de pronóstico</div>';
		}
	}
	
	#PARA EL REPORTE DE CAJA CHICA
	
	public function cajaChica()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];

		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['menuActivo']		='cajaChica'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('29',$this->session->userdata('rol'));
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de caja chica';
		
		$this->load->view("reportes/cajaChica/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function reporteCajaChica($mes,$anio,$criterio)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		$this->load->library('ccantidadletras');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Caja chica',''); //Registrar bitácora
		
		$data['cajaChica']	=$this->reportes->obtenerReporteCajaChica(0,0,$mes,$anio,$criterio);
		$data['sumaCaja']	=$this->reportes->sumarReporteCajaChica($mes,$anio,$criterio);
		$data['reporte'] 	='reportes/cajaChica/cajaChicaPdf';

		$html=$this->load->view('reportes/principal',$data,true);

		$this->mpdf->mPDF('en-x','Letter-L','','',10,10,32,10,5,10);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	public function obtenerCajaChica($limite=0)
	{
		$fecha		=$this->input->post('fecha');
		$criterio	=$this->input->post('criterio');
		$criterio	=strlen($criterio)>0?$criterio:'criterio';
		
		$mes		=substr($fecha,5,2);
		$anio		=substr($fecha,0,4);
		
		$url		=base_url()."reportes/obtenerCajaChica/";
		$registros	=$this->reportes->contarReporteCajaChica($mes,$anio,$criterio);
		$numero		=5;
		$links		=5;
		$uri		=3;
		
		$paginador	=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		$data['cajaChica']	= $this->reportes->obtenerReporteCajaChica($numero,$limite,$mes,$anio,$criterio);
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('29',$this->session->userdata('rol'));
		$data['criterio']	= $criterio;
		$data['mes']		= $mes;
		$data['anio']		= $anio;

		$this->load->view("reportes/cajaChica/obtenerCajaChica",$data); 
	}
	
	public function excelCajaChica($mes,$anio,$criterio='criterio')
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Caja chica',''); //Registrar bitácora
		
		$data['cajaChica']	=$this->reportes->obtenerReporteCajaChica(0,0,$mes,$anio,$criterio);
		$data['sumaCaja']	=$this->reportes->sumarReporteCajaChica($mes,$anio,$criterio);

		$this->load->view("reportes/cajaChica/excelCajaChica",$data); 
	}
	
	#FLUJO DE CAJA CHICA
	#----------------------------------------------------------------------------------------------------------------#
	
	public function flujoCajaChica()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];

		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='flujoCajaChica'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('30',$this->session->userdata('rol'));
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]	= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de flujo de caja chica ';
		
		$this->load->view("reportes/flujoCaja/flujoCaja",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function reporteFlujoCaja($mes,$anio)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Flujo de caja chica',''); //Registrar bitácora
		
		$data['cajas'] 		=$this->administracion->obtenerCajasChicas();
		$data['mes'] 		=$mes;
		$data['anio'] 		=$anio;
		$data['reporte'] 	='reportes/flujoCaja/reporteFlujo';

		$html				=$this->load->view('reportes/principal',$data,true);
		#$pie				=$this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',15,10,31,16,7,10);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	public function obtenerFlujoCaja()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('30',$this->session->userdata('rol'));
		$this->load->view('reportes/flujoCaja/obtenerFlujoCaja',$data);
	}
	
	public function excelFlujoCaja($mes,$anio)
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Flujo de caja chica',''); //Registrar bitácora

		$data['cajas']	=$this->administracion->obtenerCajasChicas();
		$data['anio']	=$anio;
		$data['mes']	=$mes;
		
		$this->load->view('reportes/flujoCaja/excelFlujoCaja',$data);
	}
	
	
	//PARA EL REPORTE DE PRONOSTICO DE INGRESOS
	public function pronosticoIngresos()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='pronosticoIngresos'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('31',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['cuentas']	= $this->bancos->obtenerCuentas();
		$data["breadcumb"]	= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de pronóstico de cobros';
		
		$this->load->view("reportes/pronosticoIngresos/index", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerPronosticoIngresos()
	{
		$inicio				 	= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idCliente			 	= $this->input->post('idCliente');

		$data['pronostico'] 	= $this->reportes->obtenerPronosticoIngresos($inicio,$fin,$idCliente);
		$data['ingresos'] 		= $this->reportes->sumarPronosticoIngresos($inicio,$fin,$idCliente);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('31',$this->session->userdata('rol'));
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['idCliente'] 		= $idCliente;

		$this->load->view("reportes/pronosticoIngresos/obtenerPronosticoIngresos", $data); 
	}
	
	public function reportePronosticoIngresos($inicio,$fin,$idCliente)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Pronóstico de cobros',''); //Registrar bitácora
		
		$data['pronostico'] = $this->reportes->obtenerPronosticoIngresos($inicio,$fin,$idCliente);
		$data['ingresos']	= $this->reportes->sumarPronosticoIngresos($inicio,$fin,$idCliente);
		$data['inicio'] 	= $inicio;
		$data['fin'] 		= $fin;
		$data['reporte'] 	= 'reportes/pronosticoIngresos/reportePronosticoIngresos';

		$html=$this->load->view('reportes/principal',$data,true);

		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,36,47,2,0);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	
	
	//EL EXCEL DEL PRONÓSTICO DE INGRESOS
	public function excelPronosticoIngresos($inicio,$fin,$idCliente)
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Pronóstico de cobros',''); //Registrar bitácora
		
		$data['pronostico']		=$this->reportes->obtenerPronosticoIngresos($inicio,$fin,$idCliente);
		$data['ingresos'] 		= $this->reportes->sumarPronosticoIngresos($inicio,$fin,$idCliente);
		
		$this->load->view("reportes/pronosticoIngresos/excelPronosticoIngresos", $data); 
	}
	
	//PARA EL REPORTE DE PRONOSTICO DE PAGOS
	public function pronosticoGastos()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='pronosticoGastos'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('32',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['cuentas']	= $this->bancos->obtenerCuentas();
		$data["breadcumb"]	= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de pronóstico de pagos';
		
		$this->load->view("reportes/pronosticoGastos/index", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerPronosticoGastos()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idProveedor			= $this->input->post('idProveedor');

		$data['pronostico'] 	= $this->reportes->obtenerPronosticoGastos($inicio,$fin,$idProveedor);
		$data['gastos'] 		= $this->reportes->sumarPronosticoGastos($inicio,$fin,$idProveedor);	
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('32',$this->session->userdata('rol'));
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['idProveedor'] 	= $idProveedor;
		
		$this->load->view("reportes/pronosticoGastos/obtenerPronosticoGastos", $data); 
	}
	
	public function reportePronosticoGastos($inicio,$fin,$idProveedor)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');

		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Pronóstico de gastos',''); //Registrar bitácora
		
		$data['pronostico']	= $this->reportes->obtenerPronosticoGastos($inicio,$fin,$idProveedor);
		$data['gastos']	= $this->reportes->sumarPronosticoGastos($inicio,$fin,$idProveedor);
		$data['inicio'] 	= $inicio;
		$data['fin'] 		= $fin;
		$data['reporte'] 	= 'reportes/pronosticoGastos/reportePronosticoGastos';

		$html=$this->load->view('reportes/principal',$data,true);

		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,36,47,2,0);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}

	//EL EXCEL DEL PRONÓSTICO DE INGRESOS
	public function excelPronosticoGastos($inicio,$fin,$idProveedor)
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Pronóstico de pagos',''); //Registrar bitácora
		
		$data['gastos']			= $this->reportes->sumarPronosticoGastos($inicio,$fin,$idProveedor);
		$data['pronostico']		= $this->reportes->obtenerPronosticoGastos($inicio,$fin,$idProveedor);
		
		$this->load->view("reportes/pronosticoGastos/excelPronosticoGastos", $data); 
	}
	
	//REPORTE DE INVENTARIOS
	public function descargarPdfReportes($fichero,$reporte)
	{
		$this->load->helper('download');
		
		$nombreFisico 	= $fichero.'.pdf';
		$descarga 		= $reporte.'_'.date('Y-m-d').'.pdf';
		$data 			= file_get_contents("media/ficheros/$nombreFisico"); 

		force_download($descarga, $data); 
	}
	
	public function descargarPdfPrevia($fichero,$reporte)
	{
		$this->load->helper('download');
		
		$descarga 		= $reporte.'_'.date('Y-m-d').'.pdf';
		$data 			= file_get_contents(carpetaCfdi.$fichero.'.pdf'); 

		force_download($descarga, $data); 
	}
	
	public function inventarios()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='reporteInventarios'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('33',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de inventarios';
		$data['tiendas']		= $this->configuracion->obtenerTiendas();
		
		$this->load->view("reportes/inventarios/index", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerInventarios($limite=0)
	{
		set_time_limit(0); 
		
		$criterio		= $this->input->post('criterio');
		$idLinea		= $this->input->post('idLinea');
		$idUnidad		= $this->input->post('idUnidad');
		$idTienda		= $this->input->post('idTienda');
		
		$idLinea		= strlen($idLinea)>0?$idLinea:'0';
		$idUnidad		= strlen($idUnidad)>0?$idUnidad:'0';
		#-----------------------------PAGINACION--------------------------------------#
		$paginacion["base_url"]		= base_url()."reportes/obtenerInventarios/";
		$paginacion["total_rows"]	= $this->reportes->contarInventarios($criterio,$idLinea,$idUnidad,$idTienda);
		$paginacion["per_page"]		= 50;
		$paginacion["num_links"]	= 5;
		
		$this->pagination->initialize($paginacion);

		$data['inventarios'] 		= $this->reportes->obtenerInventarios($paginacion["per_page"],$limite,$criterio,$idLinea,$idUnidad,$idTienda);
		$data['unidades'] 			= $this->reportes->obtenerInventariosUnidades();
		$data['total']		 		= $this->reportes->sumarInventarios($criterio,$idLinea,$idUnidad,$idTienda);
		$data['arreglos']			= null;#$this->arreglos->obtenerCodigos($this->reportes->obtenerInventarios(0,0,$idProducto,0,0,$idTienda));
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('33',$this->session->userdata('rol'));
		#$data['unidades']			= $this->configuracion->seleccionarUnidades();
		$data['idProducto'] 		= 0;
		$data['idLinea'] 			= $idLinea;
		$data['idUnidad'] 			= $idUnidad;
		$data['limite'] 			= $limite;
		
		$this->load->view('reportes/inventarios/obtenerInventarios',$data);
	}
	
	public function reporteInventarios($idProducto=0,$idLinea=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$idTienda		= $this->input->post('idTienda');
		$idUnidad		= $this->input->post('idUnidad');
		$criterio		= $this->input->post('criterio');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Inventarios',''); //Registrar bitácora
		
		$data['inventarios'] 		= $this->reportes->obtenerInventarios(0,0,$criterio,$idLinea,$idUnidad,$idTienda);
		$data['total']		 		= $this->reportes->sumarInventarios($criterio,$idLinea,$idUnidad,$idTienda);
		$data['configuracion'] 		= $this->configuracion->obtenerConfiguracionActual();

		$data['idProducto'] 		= $idProducto;
		$data['reporte'] 			='reportes/inventarios/reporteInventarios';

		$html				=$this->load->view('reportes/principal',$data,true);
		$pie				=$this->load->view('reportes/pie',$data,true);

		if(strlen($data['configuracion']->logotipo)>2 and file_exists('img/logos/'.$data['configuracion']->id.'_'.$data['configuracion']->logotipo))
		{
			$this->mpdf->mPDF('en-x','Letter-L','','',3,3,45,16,7,10);
		}
		else
		{
			$this->mpdf->mPDF('en-x','Letter-L','','',3,3,29,16,7,10);
		}

		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->useSubstitutions	= false; 
		$this->mpdf->simpleTables 		= true;
		$this->mpdf->mirrorMargins 		= 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/reporteInventarios.pdf','F');
		#$this->mpdf->Output();		
		echo 'reporteInventarios';
	}
	
	public function excelInventarios($idProducto=0,$idLinea=0)
	{
		$this->load->library('excel/PHPExcel');
		
		$idTienda		= $this->input->post('idTienda');
		$idUnidad		= $this->input->post('idUnidad');
		$criterio		= $this->input->post('criterio');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Inventarios',''); //Registrar bitácora

		$data['inventarios'] 		= $this->reportes->obtenerInventarios(0,0,$criterio,$idLinea,$idUnidad,$idTienda);
		$data['total']		 		= $this->reportes->sumarInventarios($criterio,$idLinea,$idUnidad,$idTienda);
		
		$this->load->view('reportes/inventarios/excelInventarios',$data);
	}
	
	public function reporteSalidasEntradas($idProducto=0,$idLinea=0,$modelo='0')
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Inventarios - Entradas y salidas',''); //Registrar bitácora

		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$inicio					= strlen($inicio)>3?$inicio:'fecha';
		$fin					= strlen($fin)>3?$fin:'fecha';
		$idTienda				= $this->input->post('idTienda');
		
		$idProducto				= $this->input->post('idProducto');
		#$idLicencia				= $this->input->post('idLicencia');
		$idLicencia				= 1;
		$data['compras']		= $this->compras->obtenerInformacionCompras($idProducto,$idTienda);
		$data['ventas']			= $this->ventas->obtenerInformacionVentas($idProducto,$inicio,$fin,$idTienda);
		$data['producto']		= $this->inventario->obtenerProductoInventario($idProducto,$idTienda);
		$data['envios']			= $this->inventario->obtenerEnviosProductoInventario($idProducto,$idTienda);
		$data['recepciones']	= $this->inventario->obtenerRecepcionesProductoInventario($idProducto,$idTienda);
		$data['movimientos']	= $this->inventario->obtenerInformacionMovimientos($idProducto,$idTienda);
		$data['diario']			= $this->inventario->obtenerInformacionDiario($idProducto);
		$data['registros'] 		= $this->informacion->obtenerEntradasEntregas(0,0,$idProducto);

		$data['reporte'] 		= 'reportes/inventarios/reporteSalidasEntradas';

		$html				=$this->load->view('reportes/principal',$data,true);
		$pie				=$this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter-L','','',8,8,30,16,7,10);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->useSubstitutions	= false; 
		$this->mpdf->simpleTables 		= true;
		$this->mpdf->mirrorMargins 		= 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/SalidasEntradas.pdf','F');
		#$this->mpdf->Output();		
		echo 'SalidasEntradas';
	}
	
	public function excelSalidasEntradas($idProducto=0,$idLinea=0,$modelo='0')
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Inventarios - Entradas y salidas',''); //Registrar bitácora

		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$inicio					= strlen($inicio)>3?$inicio:'fecha';
		$fin					= strlen($fin)>3?$fin:'fecha';
		$idTienda				= $this->input->post('idTienda');
		
		$idProducto				= $this->input->post('idProducto');
		$idLicencia				= 1;
		$data['compras']		= $this->compras->obtenerInformacionCompras($idProducto,$idLicencia);
		$data['ventas']			= $this->ventas->obtenerInformacionVentas($idProducto,$inicio,$fin,$idLicencia);
		$data['producto']		= $this->inventario->obtenerProductoInventario($idProducto,$idLicencia);
		$data['envios']			= $this->inventario->obtenerEnviosProductoInventario($idProducto,$idTienda);
		$data['recepciones']	= $this->inventario->obtenerRecepcionesProductoInventario($idProducto,$idTienda);
		$data['movimientos']	= $this->inventario->obtenerInformacionMovimientos($idProducto,$idTienda);
		$data['diario']			= $this->inventario->obtenerInformacionDiario($idProducto);
		$data['registros'] 		= $this->informacion->obtenerEntradasEntregas(0,0,$idProducto);
		
		$this->load->view('reportes/inventarios/excelSalidasEntradas',$data);
	}
	
	//PARA EL REPORTE DE LOS PAGOS
	public function pagos()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['menuActivo']		='pagos'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('34',$this->session->userdata('rol'));
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]	= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de pagos';
		
		$this->load->view("reportes/pagos/index", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function reportePagos($inicio,$fin,$idProveedor)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Pagos',''); //Registrar bitácora

		$data['compras'] 		= $this->reportes->obtenerPagos(0,0,$inicio,$fin,$idProveedor);
		$data['totalCompras'] 	= $this->reportes->sumarPagos($inicio,$fin,$idProveedor);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte'] 		= 'reportes/pagos/pagosPdf';

		$html	=$this->load->view('reportes/principal',$data,true);

		
		$this->mpdf->mPDF('en-x','Letter','','',3,3,36,10,2,0);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	public function obtenerPagos($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idProveedor			= $this->input->post('idProveedor');
		
		$Pag["base_url"]		= base_url()."reportes/obtenerPagos/";
		$Pag["total_rows"]		= $this->reportes->contarPagos($inicio,$fin,$idProveedor);//Total de Registros
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('34',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['compras'] 		= $this->reportes->obtenerPagos($Pag["per_page"],$limite,$inicio,$fin,$idProveedor);
		$data['totalCompras'] 	= $this->reportes->sumarPagos($inicio,$fin,$idProveedor);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('34',$this->session->userdata('rol'));
		$data['inicio']			= $inicio;
		$data['fin']			= $fin;
		$data['idProveedor']	= $idProveedor;
		
		$this->load->view("reportes/pagos/obtenerPagos", $data); 
	}
	
	public function excelPagos($inicio,$fin,$idProveedor)
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Pagos',''); //Registrar bitácora
		
		$data['compras']		=$this->reportes->obtenerPagos(0,0,$inicio,$fin,$idProveedor);
		$data['totalCompras'] 	= $this->reportes->sumarPagos($inicio,$fin,$idProveedor);

		$this->load->view("reportes/pagos/excelPagos",$data); 
	}
	
	//REPORTE DE MOBILIARIO
	
	public function mobiliario()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='reporteMobiliario'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('35',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]	= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de mobiliario/equipo';
		
		$this->load->view("reportes/mobiliario/index", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerMobiliario($limite=0)
	{
		$idInventario	=$this->input->post('idInventario');
		$idProveedor	=$this->input->post('idProveedor');
		
		#$idLinea		=strlen($idLinea)>0?$idLinea:'0';
		#$modelo			=strlen($modelo)>0?$modelo:'0';
		#-----------------------------PAGINACION--------------------------------------#
		$paginacion["base_url"]		= base_url()."reportes/obtenerMobiliario/";
		$paginacion["total_rows"]	=$this->reportes->contarMobiliario($idInventario,$idProveedor);
		$paginacion["per_page"]		=50;
		$paginacion["num_links"]	=5;
		
		$this->pagination->initialize($paginacion);

		$data['inventarios'] 		= $this->reportes->obtenerMobiliario($paginacion["per_page"],$limite,$idInventario,$idProveedor);
		$data['total']		 		= $this->reportes->sumarMobiliario($idInventario,$idProveedor);
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('35',$this->session->userdata('rol'));
		#$data['arreglos']			=$this->arreglos->obtenerCodigos($this->reportes->obtenerInventarios(0,0,$idProducto,0,0));
		$data['idInventario'] 		=$idInventario;
		$data['idProveedor'] 		=$idProveedor;
		$data['limite'] 			=$limite;
		
		$this->load->view('reportes/mobiliario/obtenerMobiliario',$data);
	}
	
	public function reporteMobiliario($idInventario=0,$idProveedor=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Mobiliario/equipo',''); //Registrar bitácora

		$data['inventarios'] 		= $this->reportes->obtenerMobiliario(0,0,$idInventario,$idProveedor);
		$data['total']		 		= $this->reportes->sumarMobiliario($idInventario,$idProveedor);
		$data['idProducto'] 		=$idProducto;
		$data['reporte'] 			='reportes/mobiliario/reporteMobiliario';

		$html				=$this->load->view('reportes/principal',$data,true);
		$pie				=$this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',8,8,45,16,7,10);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->useSubstitutions	= false; 
		$this->mpdf->simpleTables 		= true;
		$this->mpdf->mirrorMargins 		= 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/reporteMobiliario.pdf','F');
		#$this->mpdf->Output();		
		echo 'reporteMobiliario';
	}
	
	public function excelMobiliario($idInventario=0,$idProveedor=0)
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Mobiliario/equipo',''); //Registrar bitácora

		$data['inventarios'] 		= $this->reportes->obtenerMobiliario(0,0,$idInventario,$idProveedor);
		$data['total']		 		= $this->reportes->sumarMobiliario($idInventario,$idProveedor);
		
		$this->load->view('reportes/mobiliario/excelMobiliario',$data);
	}
	
	public function lista()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['menuActivo']		='listaReportes'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		$data["breadcumb"]		= 'Reportes';
		
		$this->load->view("reportes/lista",$data); 
		$this->load->view("pie", $Data);
	}
	
	//------------------------------------------------------------------------------------------------------
	//REPORTE DE DEPÓSITOS
	//------------------------------------------------------------------------------------------------------
	public function depositos()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['menuActivo']		= 'depositos'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']=$this->configuracion->obtenerPermisosBoton('36',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		
		$data['emisores']		= $this->configuracion->obtenerEmisores();
		$data['cuentas']		= $this->bancos->obtenerCuentas();
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de depositos';
		
		$this->load->view("reportes/depositos/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerDepositos($limite=0)
	{
		$fecha					= $this->input->post('fecha');
		$idEmisor				= $this->input->post('idEmisor');
		$idCuenta				= $this->input->post('idCuenta');
		
		$Pag["base_url"]		= base_url()."reportes/obtenerDepositos/";
		$Pag["total_rows"]		= $this->reportes->contarDepositos($fecha,$idCuenta,$idEmisor);
		$Pag["per_page"]		= 50;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('14',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['depositos'] 		= $this->reportes->obtenerDepositos($Pag["per_page"],$limite,$fecha,$idCuenta,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarDepositos($fecha,$idCuenta,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['cuenta'] 		= $idCuenta>0?$this->bancos->obtenerCuenta($idCuenta):null;
		$data['idEmisor']		= $idEmisor;
		$data['idCuenta']		= $idCuenta;
		$data['fecha']			= $fecha;
		$data['limite']			= $limite+1;
		
		$this->load->view("reportes/depositos/obtenerDepositos", $data); 
	}
	
	public function reporteDepositos()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Depositos',''); //Registrar bitácora
		
		$fecha					= $this->input->post('fecha');
		$idEmisor				= $this->input->post('idEmisor');
		$idCuenta				= $this->input->post('idCuenta');
		
		$this->load->library('mpdf/mpdf');

		$data['depositos'] 		= $this->reportes->obtenerDepositos(0,0,$fecha,$idCuenta,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarDepositos($fecha,$idCuenta,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['cuenta'] 		= $idCuenta>0?$this->bancos->obtenerCuenta($idCuenta):null;
		$tamano					= $idEmisor>0?41:40;
		$data['idEmisor']		= $idEmisor;
		$data['idCuenta']		= $idCuenta;
		$data['fecha']			= $fecha;
		$data['reporte'] 		= 'reportes/depositos/reporteDepositos';
		
		if($idCuenta>0 and $idEmisor>0)
		{
			$tamano=44.6;
		}

		$html	= $this->load->view('reportes/principal',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',3,3,$tamano,10,2,2);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/depositos.pdf','F');
		
		echo 'depositos';
	}

	public function excelDepositos()
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Depositos',''); //Registrar bitácora
		
		$fecha					= $this->input->post('fecha');
		$idEmisor				= $this->input->post('idEmisor');
		$idCuenta				= $this->input->post('idCuenta');
		
		$data['depositos'] 		= $this->reportes->obtenerDepositos(0,0,$fecha,$idCuenta,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarDepositos($fecha,$idCuenta,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['cuenta'] 		= $idCuenta>0?$this->bancos->obtenerCuenta($idCuenta):null;
		$data['fecha']			= $fecha;

		$this->load->view("reportes/depositos/excelDepositos",$data); 
	}
	
	//------------------------------------------------------------------------------------------------------
	//REPORTE DE RETIROS
	//------------------------------------------------------------------------------------------------------
	public function retiros()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['menuActivo']		= 'depositos'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		#----------------------------------PERMISOS------------------------------------#
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('37',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data['emisores']		= $this->configuracion->obtenerEmisores();
		$data['cuentas']		= $this->bancos->obtenerCuentas();
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de retiros';
		
		$this->load->view("reportes/retiros/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerRetiros($limite=0)
	{
		$fecha					= $this->input->post('fecha');
		$idEmisor				= $this->input->post('idEmisor');
		$idCuenta				= $this->input->post('idCuenta');
		
		$Pag["base_url"]		= base_url()."reportes/obtenerRetiros/";
		$Pag["total_rows"]		= $this->reportes->contarRetiros($fecha,$idCuenta,$idEmisor);
		$Pag["per_page"]		= 50;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('37',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['retiros'] 		= $this->reportes->obtenerRetiros($Pag["per_page"],$limite,$fecha,$idCuenta,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarRetiros($fecha,$idCuenta,$idEmisor);
		$data['cuenta'] 		= $idCuenta>0?$this->bancos->obtenerCuenta($idCuenta):null;
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['idEmisor']		= $idEmisor;
		$data['idCuenta']		= $idCuenta;
		$data['fecha']			= $fecha;
		$data['limite']			= $limite+1;
		
		$this->load->view("reportes/retiros/obtenerRetiros", $data); 
	}
	
	public function reporteRetiros()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Retiros',''); //Registrar bitácora
		
		$fecha					= $this->input->post('fecha');
		$idEmisor				= $this->input->post('idEmisor');
		$idCuenta				= $this->input->post('idCuenta');
		
		$this->load->library('mpdf/mpdf');

		$data['retiros'] 		= $this->reportes->obtenerRetiros(0,0,$fecha,$idCuenta,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarRetiros($fecha,$idCuenta,$idEmisor);
		$data['cuenta'] 		= $idCuenta>0?$this->bancos->obtenerCuenta($idCuenta):null;
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$tamano					= $idEmisor>0?41:40;
		$data['idCuenta']		= $idCuenta;
		$data['fecha']			= $fecha;
		$data['reporte'] 		= 'reportes/retiros/reporteRetiros';
		
		if($idCuenta>0 and $idEmisor>0)
		{
			$tamano=44.6;
		}

		$html	= $this->load->view('reportes/principal',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',3,3,$tamano,10,2,2);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/retiros.pdf','F');
		
		echo 'retiros';
	}

	public function excelRetiros()
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Retiros',''); //Registrar bitácora
		
		$fecha					= $this->input->post('fecha');
		$idEmisor				= $this->input->post('idEmisor');
		$idCuenta				= $this->input->post('idCuenta');
		
		$data['retiros'] 		= $this->reportes->obtenerRetiros(0,0,$fecha,$idCuenta,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarRetiros($fecha,$idCuenta,$idEmisor);
		$data['cuenta'] 		= $idCuenta>0?$this->bancos->obtenerCuenta($idCuenta):null;
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['fecha']			= $fecha;

		$this->load->view("reportes/retiros/excelRetiros",$data); 
	}
	
	//------------------------------------------------------------------------------------------------------
	//INGRESOS FACTURADOS
	//------------------------------------------------------------------------------------------------------
	public function ingresosFacturados()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['menuActivo']		= 'ingresosFacturados'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		#----------------------------------PERMISOS------------------------------------#
		
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('38',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data['emisores']		= $this->configuracion->obtenerEmisores();
		$data['cuentas']		= $this->bancos->obtenerCuentas();
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de ingresos facturados';
		
		$this->load->view("reportes/ingresosFacturados/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerIngresosFacturados($limite=0)
	{
		$fecha					= $this->input->post('fecha');
		$idEmisor				= $this->input->post('idEmisor');
		$idCuenta				= $this->input->post('idCuenta');
		
		$Pag["base_url"]		= base_url()."reportes/obtenerIngresosFacturados/";
		$Pag["total_rows"]		= $this->reportes->contarIngresoFacturados($fecha,$idCuenta,$idEmisor);
		$Pag["per_page"]		= 50;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('38',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['ingresos'] 		= $this->reportes->obtenerIngresosFacturados($Pag["per_page"],$limite,$fecha,$idCuenta,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarIngresosFacturados($fecha,$idCuenta,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['cuenta'] 		= $idCuenta>0?$this->bancos->obtenerCuenta($idCuenta):null;
		$data['idEmisor']		= $idEmisor;
		$data['idCuenta']		= $idCuenta;
		$data['fecha']			= $fecha;
		$data['limite']			= $limite+1;
		
		$this->load->view("reportes/ingresosFacturados/obtenerIngresosFacturados", $data); 
	}
	
	public function reporteIngresosFacturados()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ingresos facturados',''); //Registrar bitácora
		
		$fecha					= $this->input->post('fecha');
		$idEmisor				= $this->input->post('idEmisor');
		$idCuenta				= $this->input->post('idCuenta');
		
		$this->load->library('mpdf/mpdf');

		$data['ingresos'] 		= $this->reportes->obtenerIngresosFacturados(0,0,$fecha,$idCuenta,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarIngresosFacturados($fecha,$idCuenta,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['cuenta'] 		= $idCuenta>0?$this->bancos->obtenerCuenta($idCuenta):null;
		$tamano					= $idEmisor>0?45:44;
		
		if($idCuenta>0 and $idEmisor>0) $tamano=48.5;
		
		$data['idEmisor']		= $idEmisor;
		$data['idCuenta']		= $idCuenta;
		$data['fecha']			= $fecha;
		$data['reporte'] 		= 'reportes/ingresosFacturados/reporteIngresosFacturados';

		$html	= $this->load->view('reportes/principal',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',3,3,$tamano,10,2,2);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/ingresosFacturados.pdf','F');
		
		echo 'ingresosFacturados';
	}

	public function excelIngresosFacturados()
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Ingresos facturados',''); //Registrar bitácora
		
		$fecha					= $this->input->post('fecha');
		$idEmisor				= $this->input->post('idEmisor');
		$idCuenta				= $this->input->post('idCuenta');
		
		$data['ingresos'] 		= $this->reportes->obtenerIngresosFacturados(0,0,$fecha,$idCuenta,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarIngresosFacturados($fecha,$idCuenta,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['cuenta'] 		= $idCuenta>0?$this->bancos->obtenerCuenta($idCuenta):null;
		$data['fecha']			= $fecha;

		$this->load->view("reportes/ingresosFacturados/excelIngresosFacturados",$data); 
	}
	
	//------------------------------------------------------------------------------------------------------
	//RELACIÓN DE PROVEEDORES
	//------------------------------------------------------------------------------------------------------
	public function relacionProveedores()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['menuActivo']		= 'ingresosFacturados'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('40',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data['emisores']		= $this->configuracion->obtenerEmisores();
		$data['cuentas']		= $this->bancos->obtenerCuentas();
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de relación de proveedores';
		
		$this->load->view("reportes/relacionProveedores/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerRelacionProveedores($limite=0)
	{
		$anio					= $this->input->post('anio');
		$idProveedor			= $this->input->post('idProveedor');
		$idEmisor				= $this->input->post('idEmisor');
		
		$Pag["base_url"]		= base_url()."reportes/obtenerRelacionProveedores/";
		$Pag["total_rows"]		= $this->reportes->contarRelacionProveedores($anio,$idProveedor,$idEmisor);
		$Pag["per_page"]		= 50;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('40',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['relacion'] 		= $this->reportes->obtenerRelacionProveedores($Pag["per_page"],$limite,$anio,$idProveedor,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarRelacionProveedores($anio,$idProveedor,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['idProveedor']	= $idProveedor;
		$data['idEmisor']		= $idEmisor;
		$data['anio']			= $anio;
		$data['limite']			= $limite+1;
		
		$this->load->view("reportes/relacionProveedores/obtenerRelacionProveedores", $data); 
	}
	
	public function reporteRelacionProveedores()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Relación proveedores',''); //Registrar bitácora
		
		$anio					= $this->input->post('anio');
		$idProveedor			= $this->input->post('idProveedor');
		$idEmisor				= $this->input->post('idEmisor');
		
		$this->load->library('mpdf/mpdf');

		$data['relacion'] 		= $this->reportes->obtenerRelacionProveedores(0,0,$anio,$idProveedor,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarRelacionProveedores($anio,$idProveedor,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['idProveedor']	= $idProveedor;
		$data['idEmisor']		= $idEmisor;
		$data['anio']			= $anio;
		$data['reporte'] 		= 'reportes/relacionProveedores/reporteRelacionProveedores';

		$html	= $this->load->view('reportes/principal',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter-L','','',3,3,36.4,10,2,2);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/relacionProveedores.pdf','F');
		
		echo 'relacionProveedores';
	}

	public function excelRelacionProveedores()
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Relación proveedores',''); //Registrar bitácora
		
		$anio					= $this->input->post('anio');
		$idProveedor			= $this->input->post('idProveedor');
		$idEmisor				= $this->input->post('idEmisor');
		
		$data['relacion'] 		= $this->reportes->obtenerRelacionProveedores(0,0,$anio,$idProveedor,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarRelacionProveedores($anio,$idProveedor,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['idProveedor']	= $idProveedor;
		$data['idEmisor']		= $idEmisor;
		$data['anio']			= $anio;

		$this->load->view("reportes/relacionProveedores/excelRelacionProveedores",$data); 
	}
	
	//------------------------------------------------------------------------------------------------------
	//GASTOS FACTURADOS
	//------------------------------------------------------------------------------------------------------
	public function gastosFacturados()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['menuActivo']		= 'gastosFacturados'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']=$this->configuracion->obtenerPermisosBoton('39',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data['cuentas']		= $this->bancos->obtenerCuentas();
		$data['emisores']		= $this->configuracion->obtenerEmisores();
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de gastos facturados';
		
		$this->load->view("reportes/gastosFacturados/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerGastosFacturados($limite=0)
	{
		$fecha					= $this->input->post('fecha');
		$idEmisor				= $this->input->post('idEmisor');
		$anio					= substr($fecha,0,4);
		$mes					= substr($fecha,5,2);
		
		$Pag["base_url"]		= base_url()."reportes/obtenerGastosFacturados/";
		$Pag["total_rows"]		= $this->reportes->contarGastosFacturados($mes,$anio,$idEmisor);
		$Pag["per_page"]		= 50;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('39',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['gastos'] 		= $this->reportes->obtenerGastosFacturados($Pag["per_page"],$limite,$mes,$anio,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarGastosFacturados($mes,$anio,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['fecha']			= $fecha;
		$data['limite']			= $limite+1;
		
		$this->load->view("reportes/gastosFacturados/obtenerGastosFacturados", $data); 
	}
	
	public function reporteGastosFacturados()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Gastos facturados',''); //Registrar bitácora
		
		$fecha					= $this->input->post('fecha');
		$idEmisor				= $this->input->post('idEmisor');
		$anio					= substr($fecha,0,4);
		$mes					= substr($fecha,5,2);
		
		$this->load->library('mpdf/mpdf');

		$data['gastos'] 		= $this->reportes->obtenerGastosFacturados(0,0,$mes,$anio,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarGastosFacturados($mes,$anio,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['fecha']			= $fecha;
		$data['reporte'] 		= 'reportes/gastosFacturados/reporteGastosFacturados';

		$html	= $this->load->view('reportes/principal',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter-L','','',3,3,36.4,10,2,2);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/gastosFacturados.pdf','F');
		
		echo 'gastosFacturados';
	}

	public function excelGastosFacturados()
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Gastos facturados',''); //Registrar bitácora
		
		$fecha					= $this->input->post('fecha');
		$idEmisor				= $this->input->post('idEmisor');
		$anio					= substr($fecha,0,4);
		$mes					= substr($fecha,5,2);
		
		$data['gastos'] 		= $this->reportes->obtenerGastosFacturados(0,0,$mes,$anio,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarGastosFacturados($mes,$anio,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['fecha']			= $fecha;

		$this->load->view("reportes/gastosFacturados/excelGastosFacturados",$data); 
	}
	
	//------------------------------------------------------------------------------------------------------
	//RELACIÓN CLIENTES
	//------------------------------------------------------------------------------------------------------
	public function relacionClientes()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['menuActivo']		= 'relacionClientes'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']=$this->configuracion->obtenerPermisosBoton('41',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data['emisores']		= $this->configuracion->obtenerEmisores();
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de relación de clientes';
		
		$this->load->view("reportes/relacionClientes/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerRelacionClientes($limite=0)
	{
		$anio					= $this->input->post('anio');
		$idEmisor				= $this->input->post('idEmisor');
		
		$Pag["base_url"]		= base_url()."reportes/obtenerRelacionClientes/";
		$Pag["total_rows"]		= $this->reportes->contarRelacionClientes($anio,$idEmisor);
		$Pag["per_page"]		= 50;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('41',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['relacion'] 		= $this->reportes->obtenerRelacionClientes($Pag["per_page"],$limite,$anio,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarRelacionClientes($anio,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['idEmisor']		= $idEmisor;
		$data['anio']			= $anio;
		$data['limite']			= $limite+1;
		
		$this->load->view("reportes/relacionClientes/obtenerRelacionClientes", $data); 
	}
	
	public function reporteRelacionClientes()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Relación clientes',''); //Registrar bitácora
		
		$anio					= $this->input->post('anio');
		$idEmisor				= $this->input->post('idEmisor');
		
		$this->load->library('mpdf/mpdf');

		$data['relacion'] 		= $this->reportes->obtenerRelacionClientes(0,0,$anio,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarRelacionClientes($anio,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['idEmisor']		= $idEmisor;
		$data['anio']			= $anio;
		$tamano					= 36.4;
		
		if($idEmisor>0) $tamano=36.4;
		
		$data['idEmisor']		= $idEmisor;
		$data['reporte'] 		= 'reportes/relacionClientes/reporteRelacionClientes';

		$html	= $this->load->view('reportes/principal',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',3,3,$tamano,10,2,2);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/relacionClientes.pdf','F');
		
		echo 'relacionClientes';
	}

	public function excelRelacionClientes()
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Relación clientes',''); //Registrar bitácora
		
		$anio					= $this->input->post('anio');
		$idEmisor				= $this->input->post('idEmisor');
		
		$data['relacion'] 		= $this->reportes->obtenerRelacionClientes(0,0,$anio,$idEmisor);
		$data['totales'] 		= $this->reportes->sumarRelacionClientes($anio,$idEmisor);
		$data['emisor'] 		= $idEmisor>0?$this->configuracion->obtenerEmisor($idEmisor):null;
		$data['idEmisor']		= $idEmisor;
		$data['anio']			= $anio;

		$this->load->view("reportes/relacionClientes/excelRelacionClientes",$data); 
	}
	
	//------------------------------------------------------------------------------------------------------
	//UTILIDAD
	//------------------------------------------------------------------------------------------------------
	public function utilidad()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['menuActivo']		= 'ingresosFacturados'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('42',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data['emisores']		= $this->configuracion->obtenerEmisores();
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de utilidad';
		
		$this->load->view("reportes/utilidad/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerUtilidad()
	{
		$fecha					= $this->input->post('fecha');
		$data['emisores']		= $this->configuracion->obtenerEmisores($this->input->post('idEmisor'));
		$data['fecha']			= $fecha;
		
		$this->load->view("reportes/utilidad/obtenerUtilidad", $data); 
	}
	
	public function reporteUtilidad()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Utilidad',''); //Registrar bitácora
		
		$fecha					= $this->input->post('fecha');
		
		$this->load->library('mpdf/mpdf');

		$data['emisores']		= $this->configuracion->obtenerEmisores($this->input->post('idEmisor'));
		$data['fecha']			= $fecha;
		$data['reporte'] 		= 'reportes/utilidad/reporteUtilidad';

		$html	= $this->load->view('reportes/principal',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter-L','','',3,3,25,10,2,2);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/utilidad.pdf','F');
		
		echo 'utilidad';
	}

	public function excelUtilidad()
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Utilidad',''); //Registrar bitácora
		
		$fecha					= $this->input->post('fecha');
		
		$data['emisores']		= $this->configuracion->obtenerEmisores($this->input->post('idEmisor'));
		$data['fecha']			= $fecha;

		$this->load->view("reportes/utilidad/excelUtilidad",$data); 
	}

	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<*  VENTAS POR PRODUCTO  **<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	public function reporteVentasProducto()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ventas por producto',''); //Registrar bitácora
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idCliente				= $this->input->post('idCliente');
		$idCotizacion			= $this->input->post('idCotizacion');
		$idProducto				= $this->input->post('idProducto');
		$ordenVentas			= $this->input->post('ordenVentas');

		$data['ventas'] 		= $this->ventas->obtenerVentasProducto(0,0,$inicio,$fin,$idCliente,$idCotizacion,$idProducto,$ordenVentas);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte']		= 'ventas/ventasProducto/reporteVentasProducto';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter-L','','',5,5,35,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$this->mpdf->Output('media/ficheros/VentasProducto.pdf','F');
		
		echo 'VentasProducto';
	}
	
	public function excelVentasProducto()
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Ventas por producto',''); //Registrar bitácora
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idCliente				= $this->input->post('idCliente');
		$idCotizacion			= $this->input->post('idCotizacion');
		$idProducto				= $this->input->post('idProducto');
		$ordenVentas			= $this->input->post('ordenVentas');

		$data['ventas'] 		= $this->ventas->obtenerVentasProducto(0,0,$inicio,$fin,$idCliente,$idCotizacion,$idProducto,$ordenVentas);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		
		$this->load->view('ventas/ventasProducto/excelVentasProducto',$data);
	}
	
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<*  VENTAS POR SERVICIO  **<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	//*>>**>*>**<*<**<*<*<**<*<*<**<*<*>**<*<**<*<**<*<*<**<*<*<**<*<*<*<**<*<*<***<*<*<**<*<*<*<**<*<*<*<*<**<*>*>**>*>*>*<*
	public function reporteVentasServicio()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ventas por servicio',''); //Registrar bitácora
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idCliente				= $this->input->post('idCliente');
		$idCotizacion			= $this->input->post('idCotizacion');
		$idProducto				= $this->input->post('idProducto');
		$ordenVentas			= $this->input->post('ordenVentas');

		$data['ventas'] 		= $this->ventas->obtenerVentasServicio(0,0,$inicio,$fin,$idCliente,$idCotizacion,$idProducto,$ordenVentas);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte']		= 'ventas/ventasServicio/reporteVentasServicio';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter-L','','',5,5,35,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$this->mpdf->Output('media/ficheros/VentasServicio.pdf','F');
		
		echo 'VentasServicio';
	}
	
	public function excelVentasServicio()
	{
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Ventas por servicio',''); //Registrar bitácora
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idCliente				= $this->input->post('idCliente');
		$idCotizacion			= $this->input->post('idCotizacion');
		$idProducto				= $this->input->post('idProducto');
		$ordenVentas			= $this->input->post('ordenVentas');

		$data['ventas'] 		= $this->ventas->obtenerVentasServicio(0,0,$inicio,$fin,$idCliente,$idCotizacion,$idProducto,$ordenVentas);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		
		$this->load->view('ventas/ventasServicio/excelVentasServicio',$data);
	}
	
	
	//REPORTE DE MATERIA PRIMA
	public function materiaPrima()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'reporteInventarios'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('53',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de inventario de materia prima';
		
		$this->load->view("reportes/materiaPrima/index", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerMateriaPrima($limite=0)
	{
		$criterio					= $this->input->post('criterio');

		#-----------------------------PAGINACION--------------------------------------#
		$paginacion["base_url"]		= base_url()."reportes/obtenerMateriaPrima/";
		$paginacion["total_rows"]	= $this->reportes->contarMateriaPrima($criterio);
		$paginacion["per_page"]		= 50;
		$paginacion["num_links"]	= 5;
		
		$this->pagination->initialize($paginacion);

		$data['materiaPrima'] 		= $this->reportes->obtenerMateriaPrima($paginacion["per_page"],$limite,$criterio);
		$data['total']		 		= $this->reportes->sumarMateriaPrima($criterio);
		#$data['arreglos']			= $this->arreglos->obtenerCodigos($this->reportes->obtenerInventarios(0,0,$idProducto,0,0));
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('33',$this->session->userdata('rol'));
		$data['limite'] 			= $limite;
		
		$this->load->view('reportes/materiaPrima/obtenerMateriaPrima',$data);
	}
	
	public function reporteMateriaPrima()
	{
		$criterio					= $this->input->post('criterio');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Materia prima',''); //Registrar bitácora
		
		$this->load->library('mpdf/mpdf');

		$data['materiaPrima'] 		= $this->reportes->obtenerMateriaPrima(0,0,$criterio);
		$data['total']		 		= $this->reportes->sumarMateriaPrima($criterio);
		$data['reporte'] 			='reportes/materiaPrima/reporteMateriaPrima';

		$html						= $this->load->view('reportes/principal',$data,true);
		$pie						= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter-L','','',8,8,40.5,16,7,10);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->useSubstitutions	= false; 
		$this->mpdf->simpleTables 		= true;
		$this->mpdf->mirrorMargins 		= 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/inventarioMateriaPrima.pdf','F');

		echo 'inventarioMateriaPrima';
	}
	
	public function excelMateriaPrima()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$criterio					= $this->input->post('criterio');
		
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Materia prima',''); //Registrar bitácora

		$data['materiaPrima'] 		= $this->reportes->obtenerMateriaPrima(0,0,$criterio);
		$data['total']		 		= $this->reportes->sumarMateriaPrima($criterio);
		
		$this->load->view('reportes/materiaPrima/excelMateriaPrima',$data);
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE MOVIMIENTOS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function historialMovimientos()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
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
	
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('54',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}

		$data['idLicencia']		= 1;
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de historial de movimientos';
		
		$this->load->view("reportes/historialMovimientos/historialMovimientos", $data); //principal lista de clientes
		$this->load->view("pie", $Data);
	}
	
	public function obtenerHistorialMovimientos($limite=0)
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idLicencia					= 1;
		$usuario					= $this->input->post('usuario');
		$modulo						= $this->input->post('modulo');

		//-----------------------------PAGINACION--------------------------------------
		$paginacion["base_url"]		= base_url()."reportes/obtenerHistorialMovimientos/";
		$paginacion["total_rows"]	= $this->reportes->contarHistorialMovimientos($inicio,$fin,$idLicencia,$usuario,$modulo);
		$paginacion["per_page"]		= 50;
		$paginacion["num_links"]	= 5;
		
		$this->pagination->initialize($paginacion);
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('54',$this->session->userdata('rol'));
		$data['movimientos']		= $this->reportes->obtenerHistorialMovimientos($paginacion["per_page"],$limite,$inicio,$fin,$idLicencia,$usuario,$modulo);
		
		$data['usuarios'] 			= $this->reportes->obtenerUsuariosHistorialMovimientos($inicio,$fin,$idLicencia);
		$data['modulos']			= $this->reportes->obtenerModulosHistorialMovimientos($inicio,$fin,$idLicencia);
		
		$data['inicio'] 			= $inicio;
		$data['fin'] 				= $fin;
		$data['usuario'] 			= $usuario;
		$data['modulo'] 			= $modulo;
		$data['limite'] 			= $limite+1;
		
		$this->load->view('reportes/historialMovimientos/obtenerHistorialMovimientos',$data);
	}
	
	public function reporteHistorialMovimientos()
	{
		$this->configuracion->registrarBitacora('Exportar pdf','Reportes - Historial de movimientos',''); //Registrar bitácora
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idLicencia				= 1;
		$usuario				= $this->input->post('usuario');
		$modulo					= $this->input->post('modulo');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');

		$data['movimientos']	= $this->reportes->obtenerHistorialMovimientos(0,0,$inicio,$fin,$idLicencia,$usuario,$modulo);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte'] 		= 'reportes/historialMovimientos/reporteHistorialMovimientos';
		$html					= $this->load->view('reportes/principal',$data,true);
		$pie					= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter-L','','',3,3,30,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/historialMovimientos.pdf','F');
		#var_dump($data['movimientos']);
		echo 'historialMovimientos';
	}
	
	//EL EXCEL DE LOS MOVIMIENTOS
	public function excelHistorialMovimientos()
	{
		$this->configuracion->registrarBitacora('Exportar excel','Reportes - Historial de movimientos',''); //Registrar bitácora
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idLicencia				= 1;
		$usuario				= $this->input->post('usuario');
		$modulo					= $this->input->post('modulo');
		
		$this->load->library('excel/PHPExcel');

		$data['movimientos']	= $this->reportes->obtenerHistorialMovimientos(0,0,$inicio,$fin,$idLicencia,$usuario,$modulo);

		$this->load->view("reportes/historialMovimientos/excelHistorialMovimientos", $data);
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE VENTA DE SERVICIOS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function ventaServicios()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
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
	
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('61',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}

		$data['idLicencia']		= 1;
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de venta de servicios';
		
		$this->load->view("reportes/ventaServicios/index", $data); //principal lista de clientes
		$this->load->view("pie", $Data);
	}
	
	public function obtenerVentaServicios($limite=0)
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$criterio					= $this->input->post('criterio');

		//-----------------------------PAGINACION--------------------------------------
		$paginacion["base_url"]		= base_url()."reportes/obtenerVentaServicios/";
		$paginacion["total_rows"]	= $this->reportes->contarVentaServicios($inicio,$fin,$criterio);
		$paginacion["per_page"]		= 50;
		$paginacion["num_links"]	= 5;
		
		$this->pagination->initialize($paginacion);
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('61',$this->session->userdata('rol'));
		$data['servicios']			= $this->reportes->obtenerVentaServicios($paginacion["per_page"],$limite,$inicio,$fin,$criterio);
		
		$data['inicio'] 			= $inicio;
		$data['fin'] 				= $fin;
		$data['criterio'] 			= $criterio;
		$data['limite'] 			= $limite+1;
		
		$this->load->view('reportes/ventaServicios/obtenerVentaServicios',$data);
	}
	
	public function reporteVentaServicios()
	{
		$this->configuracion->registrarBitacora('Exportar pdf','Reportes - Venta de servicios',''); //Registrar bitácora
		
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$criterio					= $this->input->post('criterio');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');

		$data['servicios']		= $this->reportes->obtenerVentaServicios(0,0,$inicio,$fin,$criterio);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte'] 		= 'reportes/ventaServicios/reporteVentaServicios';
		$html					= $this->load->view('reportes/principal',$data,true);
		$pie					= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter-L','','',3,3,30,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/ventaServicios.pdf','F');
		#var_dump($data['movimientos']);
		echo 'ventaServicios';
	}
	
	//EL EXCEL DE LOS INGRESOS
	public function excelVentaServicios()
	{
		$this->configuracion->registrarBitacora('Exportar excel','Reportes - Venta de servicios',''); //Registrar bitácora
		
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$criterio					= $this->input->post('criterio');
		
		$this->load->library('excel/PHPExcel');

		$data['servicios']		= $this->reportes->obtenerVentaServicios(0,0,$inicio,$fin,$criterio);

		$this->load->view("reportes/ventaServicios/excelVentaServicios", $data);
	}
	
	//REPORTE DE FACTURACIÓN ELECTRONICA DEL SAT
	public function facturacionSat()
	{
		$this->reportes->procesarFacturasSat(); //LLENAR LOS CAMPOS FALTANTES
		
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='facturacionReporte'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']	=$this->configuracion->obtenerPermisosBoton('43',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie", $Data);
			return;
		}

		$data['emisores']		= $this->reportes->obtenerEmisoresSat(); 
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de facturación Sat';

		$this->load->view("reportes/facturacionSat/index", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerFacturasSat($limite=0)
	{
		$fecha					= $this->input->post('fecha');
		$mes					= strlen($fecha)>4?substr($fecha,5,2):'mes';
		$anio					= strlen($fecha)>4?substr($fecha,0,4):'anio';
		$criterio				= $this->input->post('criterio');
		$recibida				= $this->input->post('recibida');
		$emisor					= $this->input->post('emisor');
		
		#-----------------------------PAGINACION--------------------------------------#
		$paginacion["base_url"]		= base_url()."reportes/obtenerFacturasSat/";
		$paginacion["total_rows"]	=$this->reportes->contarFacturasSat($mes,$anio,$criterio,$recibida,$emisor);
		$paginacion["per_page"]		=20;
		$paginacion["num_links"]	=5;
		
		$this->pagination->initialize($paginacion);

		$data['facturas'] 		= $this->reportes->obtenerFacturasSat($mes,$anio,$paginacion["per_page"],$limite,$criterio,$recibida,$emisor);
		$data['permiso']		= $this->configuracion->obtenerPermisosId('53',$this->session->userdata('rol'));
		
		$data['mes']			= $mes;
		$data['anio']			= $anio;
		$data['criterio']		= $criterio;
		$data['emisor']			= $emisor;
		$data['limite']			= $limite;
		
		
		$this->load->view('reportes/facturacionSat/obtenerFacturas',$data);
	}
	
	public function zipearFacturasSat()
	{
		#$this->load->library('../controllers/pdf','pdf');
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$fecha					= $this->input->post('fecha');
		$mes					= strlen($fecha)>4?substr($fecha,5,2):'mes';
		$anio					= strlen($fecha)>4?substr($fecha,0,4):'anio';
		$criterio				= $this->input->post('criterio');
		$recibida				= $this->input->post('recibida');
		$emisor					= $this->input->post('emisor');
		
		$data['facturas'] 		= $this->reportes->obtenerFacturasSat($mes,$anio,0,0,$criterio,$recibida,$emisor);
		#$data['pdf']			= new $this->pdf;
		$data['mes']			= $mes;
		$data['anio']			= $anio;
		$data['criterio']		= $criterio;
		$data['emisor']			= $emisor;
		
		$this->load->view('reportes/facturacionSat/zipearFacturasSat',$data);
	}
	
	
	public function descargaZipSat($nombre)
	{
		$this->load->helper('download');

		$data = file_get_contents("media/sat/".$nombre); // Read the file's contents

		force_download($nombre, $data); 
	}
	
	function descargarXMLSat($idFactura) #Descargar el archivo XML
	{
		$this->load->helper('download');
		
		$carpeta	= "media/sat/";
		$factura	= $this->reportes->obtenerFacturaSat($idFactura);
		$fichero	= $factura->rfcEmisor.'_'.obtenerFechaMesCorto($factura->fecha).'_'.$factura->serie.$factura->folio.'.xml';
		
		guardarFichero($carpeta.$fichero,$factura->xml);

		$data 		= file_get_contents($carpeta.$fichero); 
		
		force_download($fichero, $data); 
	}
	
	/*public function obtenerFacturasSat($limite=0)
	{
		$fecha						= $this->input->post('fecha');
		$mes						= strlen($fecha)>4?substr($fecha,5,2):'mes';
		$anio						= strlen($fecha)>4?substr($fecha,0,4):'anio';
		$criterio					= $this->input->post('criterio');
		$recibida					= $this->input->post('recibida');
		
		#-----------------------------PAGINACION--------------------------------------#
		$paginacion["base_url"]		= base_url()."reportes/obtenerFacturasSat/";
		$paginacion["total_rows"]	= $this->reportes->contarFacturasSat($mes,$anio,$criterio,$recibida);
		$paginacion["per_page"]		= 20;
		$paginacion["num_links"]	= 5;
		
		$this->pagination->initialize($paginacion);

		$data['facturas'] 			= $this->reportes->obtenerFacturasSat($mes,$anio,$paginacion["per_page"],$limite,$criterio,$recibida);
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('43',$this->session->userdata('rol'));
		
		$data['mes']				= $mes;
		$data['anio']				= $anio;
		$data['criterio']			= $criterio;
		$data['limite']				= $limite;
		
		$this->load->view('reportes/facturacionSat/obtenerFacturas',$data);
	}
	
	public function zipearFacturasSat()
	{
		$this->load->library('../controllers/pdf','pdf');
		
		
		$fecha					= $this->input->post('fecha');
		$mes					= strlen($fecha)>4?substr($fecha,5,2):'mes';
		$anio					= strlen($fecha)>4?substr($fecha,0,4):'anio';
		$criterio				= $this->input->post('criterio');
		$recibida				= $this->input->post('recibida');
		$emisor					= $this->input->post('emisor');
		
		$data['facturas'] 		= $this->reportes->obtenerFacturasSat($mes,$anio,0,0,$criterio,$recibida,$emisor);
		$data['pdf']			= new $this->pdf;
		$data['mes']			= $mes;
		$data['anio']			= $anio;
		$data['criterio']		= $criterio;
		$data['emisor']			= $emisor;
		
		$this->load->view('reportes/facturacionSat/zipearFacturasSat',$data);
	}*/
	
	//SALIDA DE CONTROL
	public function salidaControl($idSalida)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		#$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ventas por servicio',''); //Registrar bitácora

		$data['salida']			= $this->control->obtenerSalidaControl($idSalida);
		$data['materiales']		= $this->control->obtenerMaterialesSalidaControl($idSalida);
		$data['reporte']		= 'materiales/control/salidas/pdf/salidaControl';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter-L','','',5,5,25.4,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$this->mpdf->Output('SalidaControl.pdf','D');
		
		#echo 'VentasServicio';
	}
	
	//PEDIDOS
	public function pedido($idPedido)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		#$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ventas por servicio',''); //Registrar bitácora

		$data['pedido']			= $this->pedidos->obtenerPedido($idPedido);
		$data['productos']		= $this->pedidos->obtenerProductosPedido($idPedido);
		$data['idRol']			= $this->_role;
		$data['idRol']			= 1;
		$data['reporte']		= 'pedidos/pdf/pedido';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter-L','','',5,5,25.4,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$this->mpdf->Output('Pedido '.pedidos.$data['pedido']->folio.'.pdf','D');
	}
	
	//PEDIDOS
	public function pedidoReporte($idPedido)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		#$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ventas por servicio',''); //Registrar bitácora

		$data['pedido']			= $this->pedidos->obtenerPedido($idPedido);
		$data['productos']		= $this->pedidos->obtenerProductosPedido($idPedido);
		$data['total']			= $this->pedidos->obtenerTotalesPedido($idPedido);
		$data['impuestos']		= $this->pedidos->obtenerImpuestosPedido($idPedido);
		$data['report']			= $this->pedidos->obtenerReportePedido($idPedido);
		$data['idRol']			= $this->_role;
		$data['idRol']			= 1;
		$data['reporte']		= 'pedidos/pdf/'.($data['pedido']->idLinea!=4?'reporte':'reportePasteles');

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter-L','','',5,5,25.4,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$this->mpdf->Output('Pedido '.($data['pedido']->idLinea==2?frances:bizcocho).$data['pedido']->folio.'.pdf','D');
	}
	
	public function obtenerReportePanaderos($limite=0)
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idLinea					= $this->input->post('idLinea');
		$orden						= $this->input->post('orden');

		//-----------------------------PAGINACION--------------------------------------
		$paginacion["base_url"]		= base_url()."reportes/obtenerReportePanaderos/";
		$paginacion["total_rows"]	= $this->reportes->contarReportePanaderos($idLinea,$inicio,$fin);
		$paginacion["per_page"]		= 25;
		$paginacion["num_links"]	= 5;
		
		$this->pagination->initialize($paginacion);
		
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('54',$this->session->userdata('rol'));
		$data['pedidos']			= $this->reportes->obtenerReportePanaderos($paginacion["per_page"],$limite,$idLinea,$inicio,$fin,$orden);
		$data['total']				= $this->reportes->sumarReportePanaderos($idLinea,$inicio,$fin);
		$data['lineas']				= $this->configuracion->obtenerLineas();
		
		$data['inicio'] 			= $inicio;
		$data['fin'] 				= $fin;
		$data['idLinea'] 			= $idLinea;
		$data['limite']				= $limite;
		$data['orden']				= $orden;
		
		$this->load->view('reportes/panaderos/obtenerReportePanaderos',$data);
	}
	
	public function reportePanaderos()
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idLinea					= $this->input->post('idLinea');
		$orden						= $this->input->post('orden');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		#$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ventas por servicio',''); //Registrar bitácora

		$data['pedidos']		= $this->reportes->obtenerReportePanaderos(0,0,$idLinea,$inicio,$fin,$orden);
		$data['total']			= $this->reportes->sumarReportePanaderos($idLinea,$inicio,$fin);
		$data['lineas']			= $this->configuracion->obtenerLineas(1);
		$data['idRol']			= $this->_role;
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['idLinea'] 		= $idLinea;
		$data['reporte']		= 'reportes/panaderos/reportePanaderos';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Legal-L','','',2,2,43,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$this->mpdf->Output('media/ficheros/reportePanaderos.pdf','F');
		
		echo 'reportePanaderos';
	}
	
	public function excelPanaderos()
	{
		#$this->configuracion->registrarBitacora('Exportar excel','Reportes - Venta de servicios',''); //Registrar bitácora
		
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idLinea					= $this->input->post('idLinea');
		$orden						= $this->input->post('orden');
		
		$this->load->library('excel/PHPExcel');

		$data['pedidos']		= $this->reportes->obtenerReportePanaderos(0,0,$idLinea,$inicio,$fin,$orden);
		$data['total']			= $this->reportes->sumarReportePanaderos($idLinea,$inicio,$fin);
		$data['lineas']			= $this->configuracion->obtenerLineas(1);
		$data['idRol']			= $this->_role;
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['idLinea'] 		= $idLinea;

		$this->load->view("reportes/panaderos/excelPanaderos", $data);
	}
	
	//BALANZA DE COMPROBACIÓN
	public function reporteBalanza()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= '';
		$filtro					= $this->input->post('filtro');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		#$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ventas por servicio',''); //Registrar bitácora

		$data['cuentas']	    = $this->contabilidad->obtenerCuentasCatalogoBalanza($criterio,'',$inicio,$fin);	
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['filtro'] 		= $filtro;
		$data['reporte']		= 'contabilidad/balanza/reportes/reporteBalanza';

		$html	= $this->load->view('reportes/principal',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter-L','','',5,5,29.8,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$this->mpdf->Output('media/ficheros/balanza.pdf','F');
		
		echo 'balanza';
	}
	
	public function excelBalanza()
	{
		#$this->configuracion->registrarBitacora('Exportar excel','Reportes - Venta de servicios',''); //Registrar bitácora
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= '';
		$filtro					= $this->input->post('filtro');
		
		$this->load->library('excel/PHPExcel');

		$data['cuentas']	    = $this->contabilidad->obtenerCuentasCatalogoBalanza($criterio,'',$inicio,$fin);	
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['filtro'] 		= $filtro;

		$this->load->view("contabilidad/balanza/reportes/excelBalanza", $data);
	}
	
	//REPORTE CONTEO
	public function reporteConteos()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		#$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ventas por servicio',''); //Registrar bitácora

		$data['conteo']			= $this->pedidos->obtenerConteo($this->input->post('idConteo'));
		$data['productos']		= $this->pedidos->obtenerProductosConteo($this->input->post('idConteo'));
		$data['reporte']		= 'pedidos/conteos/detallesConteo';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',2,2,10,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$this->mpdf->Output('media/ficheros/Conteos.pdf','F');
		
		echo 'Conteos';
	}
	
	//VENTAS REPORTE
	
	public function ventasReporte()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['jvalidate']		= $this->_jss['jvalidate'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='reporteVentas'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		#----------------------------------PERMISOS------------------------------------#
		/*$data['permiso']		= $this->configuracion->obtenerPermisosBoton('21',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}*/
		
		if($this->session->userdata('reportes')!='1')
		{
			redirect('clientes','refresh');
		}

		$data["breadcumb"]		= 'Reporte de ventas';
		$data['limiteVentas']	= $this->configuracion->obtenerLimiteVentas();

		$this->load->view("reportes/ventasReporte/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function imprimirTicketReporteVentas()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idZona					= $this->input->post('idZona');
		$idUsuario				= $this->input->post('idUsuario');
		$tipoVenta				= $this->input->post('tipoVenta');
		
		/*$inicio					= '2017-11-01';
		$fin					= '2017-11-07';
		$criterio				= '';
		$idZona					= 0;
		$idUsuario				= 0;
		$tipoVenta				= 0;*/
		
		$data['ventas']			= $this->reportes->obtenerVentasReporteLinea($inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta);
		$data['fecha']			= $fin;

		$this->load->view('reportes/ventasReporte/ticket',$data);
	}
	
	public function obtenerVentasReporte($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idZona					= $this->input->post('idZona');
		$idUsuario				= $this->input->post('idUsuario');
		$tipoVenta				= $this->input->post('tipoVenta');
		
		$Pag["base_url"]		= base_url()."reportes/obtenerVentasReporte/";
		$Pag["total_rows"]		= $this->reportes->contarVentasReporte($inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta);//Total de Registros
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 4;
		
		$this->pagination->initialize($Pag);
		
		#$data['permiso']		= $this->configuracion->obtenerPermisosBoton('21',$this->session->userdata('rol'));
		#$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		
		$data['ventas'] 	= $this->reportes->obtenerVentasReporte($Pag["per_page"],$limite,$inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta);
		$data['total'] 		= $this->reportes->sumarVentasReporte($inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta);
		$data['zonas'] 		= $this->configuracion->obtenerZonas();
		$data['usuarios']	= $this->configuracion->obtenerListaUsuarios();
		$data['idZona'] 	= $idZona;
		$data['idUsuario'] 	= $idUsuario;

		$this->load->view("reportes/ventasReporte/obtenerVentas",$data); 
	}
	
	public function reporteVentasReporte()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idZona					= $this->input->post('idZona');
		$idUsuario				= $this->input->post('idUsuario');
		$tipoVenta				= $this->input->post('tipoVenta');
		
		$this->load->library('mpdf/mpdf');


		$data['ventas'] 	= $this->reportes->obtenerVentasReporte(0,0,$inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta);
		$data['total'] 		= $this->reportes->sumarVentasReporte($inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta);
		$data['inicio'] 	= $inicio;
		$data['fin'] 		= $fin;
		$data['reporte']	= 'reportes/ventasReporte/reporteVentas';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Legal-L','','',5,5,40.7,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'ReporteVentas.pdf','F');
		
		echo 'ReporteVentas';
	}
	
	public function excelVentasReporte()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idZona					= $this->input->post('idZona');
		$idUsuario				= $this->input->post('idUsuario');
		$tipoVenta				= $this->input->post('tipoVenta');
		
		$this->load->library('excel/PHPExcel');

		$data['ventas'] 		= $this->reportes->obtenerVentasReporte(0,0,$inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta);
		$data['total'] 			= $this->reportes->sumarVentasReporte($inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta);

		$this->load->view('reportes/ventasReporte/excelVentas',$data);
	}
	
	//FACTURACIÓN PREVIA
	
	public function vistaPreviaVentaFactura()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$data['cliente'] 		= $this->clientes->obtenerDatosCliente($this->input->post('txtIdCliente'));
		$data['configuracion'] 	= $this->facturacion->obtenerEmisor($this->input->post('selectEmisores'));
		$data['divisa'] 		= $this->configuracion->obtenerDivisa($this->input->post('selectDivisas'));
		$data['reporte']		= 'reportes/factura/previa/factura';

		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($this->input->post('txtTotal'));
		$this->ccantidadletras->setMoneda($data['divisa']->nombre);//
		
		$data['cantidadLetra']	= $this->ccantidadletras->PrimeraMayuscula();

		$html					= $this->load->view('reportes/factura/principal',$data,true);
		$pie 					= $this->load->view('facturacion/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,62,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaCfdi.'vistaPrevia.pdf','F');
	}
	
	public function vistaPreviaFactura()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$data['cotizacion'] 	= $this->ventas->obtenerCotizacion($this->input->post('txtIdCotizacion'));
		$data['detalles'] 		= $this->facturacion->obtenerProductosCotizacion($this->input->post('txtIdCotizacion'));
		#$data['cliente'] 		= $this->clientes->obtenerDatosCliente($data['cotizacion']->idCliente);
		$data['cliente'] 		= $this->clientes->obtenerDireccionesEditar($this->input->post('selectDireccionesCfdi'));
		$data['configuracion'] 	= $this->facturacion->obtenerEmisor($this->input->post('selectEmisores'));
		$data['divisa'] 		= $this->configuracion->obtenerDivisa(1);
		$data['reporte']		= 'reportes/factura/previa/facturaPrevia';

		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['cotizacion']->total);
		$this->ccantidadletras->setMoneda($data['divisa']->nombre);//
		
		$data['cantidadLetra']	= $this->ccantidadletras->PrimeraMayuscula();

		$html					= $this->load->view('reportes/factura/principal',$data,true);
		$pie 					= $this->load->view('facturacion/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,62,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaCfdi.'vistaPrevia.pdf','F');
	}
	
	
	public function vistaPreviaFacturaGlobal()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$inicio					= $this->input->post('txtInicio');
		$fin					= $this->input->post('txtFin');
		$tipo					= $this->input->post('selectTipoDocumento');
		
		$data['totales']		= $this->globales->obtenerTotalesVentaFactura($inicio,$fin,$tipo);
		$data['ventas']			= $this->globales->obtenerFoliosVentaFactura($inicio,$fin,$tipo);
		$data['cliente'] 		= $this->clientes->obtenerDireccionesEditar($this->input->post('selectDirecciones'));
		$data['configuracion'] 	= $this->facturacion->obtenerEmisor($this->input->post('selectEmisoresGlobal'));
		$data['canceladas']		= $this->globales->obtenerFoliosVentaFacturaCanceladas($inicio,$fin,$tipo);
		$data['divisa'] 		= $this->configuracion->obtenerDivisa(1);
		$data['reporte']		= 'reportes/factura/previa/global';

		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['totales']->total);
		$this->ccantidadletras->setMoneda('pesos');//
		
		$data['cantidadLetra']	= $this->ccantidadletras->PrimeraMayuscula();

		$html					= $this->load->view('reportes/factura/principal',$data,true);
		$pie 					= $this->load->view('facturacion/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,62,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaCfdi.'vistaPrevia.pdf','F');
		
		echo json_encode(array('1','PDF'));
	}
	
	//VENTAS REPORTE
	
	public function ventasContadora()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['jvalidate']		= $this->_jss['jvalidate'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='reporteVentas'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('20',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$data["breadcumb"]		= 'Ventas contadora';
		$data['limiteVentas']	= $this->configuracion->obtenerLimiteVentas();

		$this->load->view("reportes/ventasContadora/index",$data); 
		$this->load->view("pie", $Data);
	}

	public function obtenerVentasContadora($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');

		$data['ventas'] 	= $this->reportes->obtenerVentasContadora($inicio,$fin,$criterio);

		$this->load->view("reportes/ventasContadora/obtenerVentasContadora",$data); 
	}
	
	public function reporteVentasContadora()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');

		$this->load->library('mpdf/mpdf');


		$data['ventas'] 		= $this->reportes->obtenerVentasContadora($inicio,$fin,$criterio);
		$data['configuracion'] 	= $this->configuracion->obtenerConfiguraciones(1);
		$data['inicio'] 	= $inicio;
		$data['fin'] 		= $fin;
		$data['reporte']	= 'reportes/ventasContadora/reporteVentasContadora';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',5,5,32,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'VentasContadora.pdf','F');
		
		echo 'VentasContadora';
	}
	
	public function excelVentasContadora()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$inicio				= $this->input->post('inicio');
		$fin				= $this->input->post('fin');
		$criterio			= $this->input->post('criterio');

		$this->load->library('excel/PHPExcel');

		$data['ventas'] 		= $this->reportes->obtenerVentasContadora($inicio,$fin,$criterio);
		$data['configuracion'] 	= $this->configuracion->obtenerConfiguraciones(1);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;

		$this->load->view('reportes/ventasContadora/excelVentasContadora',$data);
	}
	
	//VENTAS POR DEPARTAMENTO
	public function reporteVentasLineas()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idZona					= $this->input->post('idZona');
		$idUsuario				= $this->input->post('idUsuario');
		$idEstacion				= $this->input->post('idEstacion');
		
		$this->load->library('mpdf/mpdf');


		$data['ventas'] 	= $this->reportes->obtenerVentasLineas($inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion);
		#$data['total'] 		= $this->reportes->sumarVentasReporte($inicio,$fin,$criterio,$idZona,$idUsuario,$tipoVenta);
		$data['inicio'] 	= $inicio;
		$data['fin'] 		= $fin;
		$data['reporte']	= 'reportes/ventas/ventasLinea/ventasLinea';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		if(strlen($this->session->userdata('logotipo'))>0 and file_exists('img/logos/'.$this->session->userdata('logotipo')))
		{
			$this->mpdf->mPDF('en-x','Legal-L','','',5,5,40.7,10,2,0);
		}
		else
		{
			$this->mpdf->mPDF('en-x','Legal-L','','',5,5,20,10,2,0);
		}
		
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'VentasLinea.pdf','F');
		
		echo 'VentasLinea';
	}
	
	public function excelVentasLineas()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idZona					= $this->input->post('idZona');
		$idUsuario				= $this->input->post('idUsuario');
		$idEstacion				= $this->input->post('idEstacion');
		
		$this->load->library('excel/PHPExcel');
		
		#$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Ventas',''); //Registrar bitácora
		
		$data['ventas'] 	= $this->reportes->obtenerVentasLineas($inicio,$fin,$criterio,$idZona,$idUsuario,$idEstacion);

		$this->load->view('reportes/ventas/ventasLinea/excelLinea',$data);
	}
	
	//REPORTE DE PEDIDOS
	public function pedidos()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['jFicha_cliente']	=$this->_jss['jFicha_cliente'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='reporteVentas'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('21',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		
		#$data['ventas'] 	= $this->reportes->obtenerVentas($Pag["per_page"],$limite,$inicio,$fin,$idCliente,$idZona,$idUsuario);
		#$data['total'] 		= $this->reportes->sumarVentas($inicio,$fin,$idCliente,$idZona,$idUsuario);

		$data["breadcumb"]	= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de ventas';

		$this->load->view("reportes/pedidos/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerPedidos($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idZona					= 0;
		$idUsuario				= 0;
		
		$Pag["base_url"]		= base_url()."reportes/obtenerPedidos/";
		$Pag["total_rows"]		= $this->reportes->contarPedidos($inicio,$fin,$criterio,$idZona,$idUsuario);//Total de Registros
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 4;
		
		$this->pagination->initialize($Pag);
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('21',$this->session->userdata('rol'));
		$data['empresa'] 		= $this->configuracion->obtenerConfiguraciones(1);
		$data['pedidos'] 		= $this->reportes->obtenerPedidos($Pag["per_page"],$limite,$inicio,$fin,$criterio,$idZona,$idUsuario);
		#$data['total'] 		= $this->reportes->sumarVentas($inicio,$fin,$criterio,$idZona,$idUsuario);
		#$data['zonas'] 		= $this->configuracion->obtenerZonas();
		#$data['usuarios']	= $this->configuracion->obtenerListaUsuarios();
		$data['idZona'] 		= $idZona;
		$data['idUsuario'] 		= $idUsuario;

		$this->load->view("reportes/pedidos/obtenerPedidos",$data); 
	}
	
	public function pedidoTicket($idCotizacion)
	{
		$this->load->library('ccantidadletras');
		
		$data['cotizacion']		= $this->reportes->obtenerPedido($idCotizacion);
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
		$data['tienda']			= $this->tiendas->obtenerTiendaVenta($data['cotizacion']->idTienda);
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['cotizacion']->total);
		$this->ccantidadletras->setMoneda('Pesos');//
		
		$data['cantidadLetras']	= $this->ccantidadletras->PrimeraMayuscula();
		
		$this->load->view('reportes/pedidos/pedidoTicket',$data);
	}
	
	public function pedidosReporte($idCotizacion)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$data['pedido'] 	= $this->reportes->obtenerPedido($idCotizacion);
		$data['cliente'] 	= $this->ventas->obtenerCliente($data['pedido']->idCliente);
		$data['productos'] 	= $this->ventas->obtenerProductos($data['pedido']->idCotizacion);
		$data['domicilio'] 	= $this->ventas->obtenerServicioDomicilio($data['pedido']->idCotizacion);
		$data['empresa'] 	= $this->configuracion->obtenerConfiguraciones(1);
		$data['tienda'] 	= $this->tiendas->obtenerTienda($data['pedido']->idTienda);
		$data['direccion'] 	= $this->clientes->obtenerDireccionEntrega($data['pedido']->idDireccion);
		$data['reporte'] 	= 'reportes/pedidos/pedidoReporte';
		$data['titulo'] 	= 'Venta';
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['pedido']->total);
		$this->ccantidadletras->setMoneda('Pesos');//
		$data['cantidadLetra']= $this->ccantidadletras->PrimeraMayuscula();

		$html				= $this->load->view('reportes/pedidos/principal',$data,true);
		$pie				= $this->load->view('reportes/pieCotizacion',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('Pedido.pdf','D');
	}
	
	public function pedidoVenta($idCotizacion)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$data['venta'] 		= $this->ventas->obtenerCotizacion($idCotizacion);
		$data['cliente'] 	= $this->ventas->obtenerCliente($data['venta']->idCliente);
		$data['productos'] 	= $this->ventas->obtenerProductos($data['venta']->idCotizacion);
		$data['empresa'] 	= $this->configuracion->obtenerConfiguraciones(1);
		$data['tienda'] 	= null;
		$data['reporte'] 	= 'reportes/pedidos/pedidoVenta';
		$data['titulo'] 	= 'Venta';
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['pedido']->total);
		$this->ccantidadletras->setMoneda('Pesos');//
		$data['cantidadLetra']= $this->ccantidadletras->PrimeraMayuscula();

		$html				= $this->load->view('reportes/pedidos/principal',$data,true);
		$pie				= $this->load->view('reportes/pieCotizacion',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('Venta.pdf','D');
	}
	
	public function reportePedidos()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idZona					= $this->input->post('idZona');
		$idUsuario				= $this->input->post('idUsuario');
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ventas',''); //Registrar bitácora

		$data['ventas'] 	= $this->reportes->obtenerVentas(0,0,$inicio,$fin,$criterio,$idZona,$idUsuario);
		$data['total'] 		= $this->reportes->sumarVentas($inicio,$fin,$criterio,$idZona,$idUsuario);
		$data['inicio'] 	= $inicio;
		$data['fin'] 		= $fin;
		$data['reporte']	= 'reportes/ventas/reporteVentas';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Legal-L','','',5,5,40.7,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'ReporteVentas.pdf','F');
		
		echo 'ReporteVentas';
	}
	
	public function excelPedidos()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idZona					= $this->input->post('idZona');
		$idUsuario				= $this->input->post('idUsuario');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Ventas',''); //Registrar bitácora
		
		$data['ventas'] 	= $this->reportes->obtenerVentas(0,0,$inicio,$fin,$criterio,$idZona,$idUsuario);
		$data['total'] 		= $this->reportes->sumarVentas($inicio,$fin,$criterio,$idZona,$idUsuario);

		$this->load->view('reportes/ventas/excelVentas',$data);
	}
	
	public function cambiarEstado()
	{
		if(!empty($_POST))
		{
			error_reporting(0);
			
			echo $this->reportes->cambiarEstado($this->input->post('idPedido'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function formularioRepartidores()
	{
		$data['pedido'] 	= $this->reportes->obtenerPedido($this->input->post('idCotizacion'));
		$data['personal'] 	= $this->administracion->obtenerPersonalRegistro();

		$this->load->view("reportes/pedidos/formularioRepartidores",$data); 
	}
	
	public function editarRepartidor()
	{
		if(!empty($_POST))
		{
			error_reporting(0);
			
			echo $this->reportes->editarRepartidor($this->input->post('idPedido'));
		}
		else
		{
			echo "0";
		}
	}
	
	//REPORTE DE RECURSOS HUMANOS
	public function recursosHumanos()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['jvalidate']		= $this->_jss['jvalidate'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'reporteVentas'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton(60,$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		

		$data["breadcumb"]	= '<a href="'.base_url().'reportes/lista">Reportes</a> > Recursos humanos';

		$this->load->view("reportes/recursosHumanos/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerRecursosHumanos($limite=0)
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton(60,$this->session->userdata('rol'));
		$data['activos'] 		= $this->administracion->obtenerPersonalActivo();
		$data['inactivos'] 		= $this->administracion->obtenerPersonalInactivo();
		$data['documentos']		= $this->catalogos->obtenerTiposDocumentos();

		$this->load->view("reportes/recursosHumanos/obtenerRecursosHumanos",$data); 
	}
	
	
	//LLAMADAS
	public function obtenerLlamadasProspectos($limite=0)
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol')); //PERMISOS DE PROMOTORES
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$src					= $this->input->post('src');

		$Pag["base_url"]		= base_url()."reportes/obtenerLlamadasProspectos/";
		$Pag["total_rows"]		= $this->reportes->contarLlamadasProspectos($inicio,$fin,$src);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 4;
		
		$this->pagination->initialize($Pag);

		
		$data['llamadas'] 		= $this->reportes->obtenerLlamadasProspectos($Pag["per_page"],$limite,$inicio,$fin,$src);
		$data['promotores']		= $this->configuracion->obtenerPromotoresExtensiones($data['permiso'][5]->activo);
		$data['src'] 			= $src;
		$data['inicio'] 		= $limite+1;

		$this->load->view("clientes/prospectos/llamadas/obtenerLlamadasProspectos",$data); 
	}
	
	//ATRASOS
	public function obtenerAtrasos($limite=0)
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol')); //PERMISOS DE PROMOTORES
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idUsuario				= $this->input->post('idUsuario');
		$registros				= $this->input->post('registros');
		$criterio				= $this->input->post('criterio');
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		$Pag["base_url"]		= base_url()."reportes/obtenerAtrasos/";
		$Pag["total_rows"]		= $this->reportes->contarAtrasos($inicio,$fin,$idUsuario,$registros,$criterio,$inicio,$fin);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 4;
		
		$this->pagination->initialize($Pag);

		
		$data['atrasos'] 		= $this->reportes->obtenerAtrasos($Pag["per_page"],$limite,$inicio,$fin,$idUsuario,$registros,$criterio,$inicio,$fin);
		$data['atrasosTotal'] 	= $this->reportes->obtenerAtrasos(0,0,$inicio,$fin,$idUsuario,$registros,$criterio,$inicio,$fin);
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['idUsuario'] 		= $idUsuario;
		$data['inicio'] 		= $limite+1;
		$data['totalRegistros']	= $Pag["total_rows"];
		$data['editar'] 		= $this->input->post('editar');


		$this->load->view("clientes/prospectos/atrasos/obtenerAtrasos",$data); 
	}
	
	//
	public function excelAtrasos()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idUsuario				= $this->input->post('idUsuario');
		$registros				= $this->input->post('registros');
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['atrasos'] 		= $this->reportes->obtenerAtrasos(0,0,$inicio,$fin,$idUsuario,$registros,$criterio,$inicio,$fin);

		$this->load->view('clientes/prospectos/atrasos/excelAtrasos',$data);
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//REPORTE DE CHECADOR
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function checador()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
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

		$this->load->view("reportes/checador/checador", $data); //principal lista de clientes
		$this->load->view("pie", $Data);
	}
	
	public function obtenerChecador($limite=0)
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idLicencia					= $this->input->post('idLicencia');
		$criterio					= $this->input->post('criterio');

		//-----------------------------PAGINACION--------------------------------------
		$paginacion["base_url"]		= base_url()."reportes/obtenerChecador/";
		$paginacion["total_rows"]	= $this->reportes->contarChecador($inicio,$fin,$idLicencia,$criterio);
		$paginacion["per_page"]		= 50;
		$paginacion["num_links"]	= 5;
		
		$this->pagination->initialize($paginacion);
		$data['permiso']			= $this->configuracion->obtenerPermisosId('21',$this->session->userdata('rol'));
		$data['checador']			= $this->reportes->obtenerChecador($paginacion["per_page"],$limite,$inicio,$fin,$idLicencia,$criterio);
		$data['inicio'] 			= $inicio;
		$data['fin'] 				= $fin;
		
		$this->load->view('reportes/checador/obtenerChecador',$data);
	}
	
	public function reporteChecador()
	{
		$this->configuracion->registrarBitacora('Exportar pdf reporte de checador','Reportes',''); //Registrar bitácora
		
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idLicencia					= $this->input->post('idLicencia');
		$criterio					= $this->input->post('criterio');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');

		$data['checador']		= $this->reportes->obtenerChecador(0,0,$inicio,$fin,$idLicencia,$criterio);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte'] 		= 'reportes/checador/reporteChecador';
		$html					= $this->load->view('reportes/principal',$data,true);
		$pie					= $this->load->view('reportes/pie',$data,true);
		
		if(strlen($this->session->userdata('logotipo'))>0 and file_exists('img/logos/'.$this->session->userdata('logotipo')))
		{
			$this->mpdf->mPDF('en-x','Letter-L','','',3,3,34,10,2,0);
		}
		else
		{
			$this->mpdf->mPDF('en-x','Letter-L','','',3,3,23,10,2,0);
		}

		
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'Checador.pdf','F');
		#var_dump($data['movimientos']);
		echo 'Checador';
	}
	
	//EL EXCEL DE LOS INGRESOS
	public function excelChecador()
	{
		$this->configuracion->registrarBitacora('Exportar excel reporte de checador','Reportes',''); //Registrar bitácora
		
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idLicencia					= $this->input->post('idLicencia');
		$criterio					= $this->input->post('criterio');
		
		$this->load->library('excel/PHPExcel');

		$data['checador']			= $this->reportes->obtenerChecador(0,0,$inicio,$fin,$idLicencia,$criterio);

		$this->load->view("reportes/checador/excelChecador", $data);
	}
	
	//REPORTE DE ENVÍOS
	public function envios()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='envios'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('65',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de envíos';
		$data['personal']		= $this->administracion->obtenerPersonalRegistro(4);
		$data['vehiculos']		= $this->administracion->obtenerVehiculos();

		$this->load->view("reportes/envios/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerEnvios($limite=0)
	{
		$inicio					= $this->input->post('inicio').' 00:00:00';
		$fin					= $this->input->post('fin').' 23:59:59';
		$criterio				= $this->input->post('criterio');
		$idRuta					= $this->input->post('idRuta');
		$cobrados				= $this->input->post('cobrados');
		$fecha					= $this->input->post('fecha');
		$idPersonal				= $this->input->post('idPersonal');
		$folioTicket			= $this->input->post('folioTicket');
	
		$Pag["base_url"]		= base_url()."reportes/obtenerEnvios";
		$Pag["total_rows"]		= $this->reportes->contarEnvios($inicio,$fin,$criterio,$idRuta,$cobrados,$idPersonal,$folioTicket);
		$Pag["per_page"]		= 50;
		$Pag["num_links"]		= 5;
		
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('65',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['ventas'] 		= $this->reportes->obtenerEnvios($Pag["per_page"],$limite,$inicio,$fin,$criterio,$idRuta,$cobrados,$fecha,$idPersonal,0,$folioTicket);
		$data['totalCobranza'] 	= $this->reportes->sumarEnvios($inicio,$fin,$criterio,$idRuta,$cobrados,$idPersonal,$folioTicket);
		$data['rutas']			= $this->catalogos->obtenerRutas();
		$data['personal']		= $this->administracion->obtenerPersonalRegistro(4);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['criterio'] 		= $criterio;
		$data['idRuta'] 		= $idRuta;
		$data['idPersonal'] 	= $idPersonal;
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de envíos';

		$this->load->view("reportes/envios/obteneReporte",$data); 
	}

	public function formularioEntregas()
	{
		$inicio					= $this->input->post('inicio').' 00:00:00';
		$fin					= $this->input->post('fin').' 23:59:59';
		$criterio				= $this->input->post('criterio');
		$idRuta					= $this->input->post('idRuta');
		$cobrados				= $this->input->post('cobrados');
		$fecha					= $this->input->post('fecha');
		$idPersonal				= $this->input->post('idPersonal');
		$folioTicket			= $this->input->post('folioTicket');
		$folioIndividual		= $this->input->post('folioIndividual');
	
		$data['personal']		= $this->administracion->obtenerRegistroPersonal($idPersonal);
		$data['registros'] 		= $this->reportes->obtenerEnvios(0,0,$inicio,$fin,$criterio,$idRuta,$cobrados,$fecha,$idPersonal,1,$folioTicket,$folioIndividual);

		$this->load->view("reportes/envios/entregas/formularioRegistro",$data); 
	}
	
	public function reporteEnvios()
	{
		$inicio					= $this->input->post('inicio').' 00:00:00';
		$fin					= $this->input->post('fin').' 23:59:59';
		$criterio				= $this->input->post('criterio');
		$idRuta					= $this->input->post('idRuta');
		$cobrados				= $this->input->post('cobrados');
		$fecha					= $this->input->post('fecha');
		$idPersonal				= $this->input->post('idPersonal');
		$folioTicket			= $this->input->post('folioTicket');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Envíos',''); //Registrar bitácora

		$data['ventas'] 		= $this->reportes->obtenerEnvios(0,0,$inicio,$fin,$criterio,$idRuta,$cobrados,$fecha,$idPersonal,0,$folioTicket);
		$data['totalCobranza'] 	= $this->reportes->sumarEnvios($inicio,$fin,$criterio,$idRuta,$cobrados,$idPersonal,$folioTicket);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte']		= 'reportes/envios/pdfReporte';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		if(strlen($this->session->userdata('logotipo')) and file_exists('img/logos/'.$this->session->userdata('logotipo')))	
		{
			$this->mpdf->mPDF('en-x','Letter-L','','',2,2,40,10,2,0);
		}
		else
		{
			$this->mpdf->mPDF('en-x','Letter-L','','',2,2,26,10,2,0);
		}
		
		
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'ReporteEnvios.pdf','F');
		
		echo 'ReporteEnvios';
	}
	
	public function excelEnvios()
	{
		$inicio					= $this->input->post('inicio').' 00:00:00';
		$fin					= $this->input->post('fin').' 23:59:59';
		$criterio				= $this->input->post('criterio');
		$idRuta					= $this->input->post('idRuta');
		$cobrados				= $this->input->post('cobrados');
		$fecha					= $this->input->post('fecha');
		$idPersonal				= $this->input->post('idPersonal');
		$folioTicket			= $this->input->post('folioTicket');
		
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Envíos',''); //Registrar bitácora
		
		$data['ventas'] 		= $this->reportes->obtenerEnvios(0,0,$inicio,$fin,$criterio,$idRuta,$cobrados,$fecha,$idPersonal,0,$folioTicket);
		$data['totalCobranza'] 	= $this->reportes->sumarEnvios($inicio,$fin,$criterio,$idRuta,$cobrados,$idPersonal,$folioTicket);
		
		$this->load->view('reportes/envios/excelReporte',$data);
	}

	public function ticketEnvios()
	{
		if(!empty($_POST))
		{
			$inicio					= $this->input->post('FechaDia');
			$fin					= $this->input->post('FechaDia2');
			$criterio				= $this->input->post('txtCriterioBusqueda');
			$idRuta					= $this->input->post('selectRutas');
			$cobrados				= $this->input->post('selectCobrados');
			$fecha					= $this->input->post('fecha');
		
			$data['ventas'] 		= $this->reportes->obtenerEnviosTicket();
			$data['personal']		= $this->administracion->obtenerRegistroPersonal($this->input->post('idPersonalRegistro'));
			$data['vehiculo']		= $this->administracion->obtenerVehiculo($this->input->post('idVehiculoRegistro'));
			$data['inicio'] 		= $inicio;
			$data['fin'] 			= $fin;

			if($data['ventas']!=null and $data['personal'] !=null)
			{
				echo json_encode($this->reportes->registrarRelacionTicket($data['ventas'],$this->input->post('idPersonalRegistro'),$this->input->post('idVehiculoRegistro')));
			}
			else
			{
				echo json_encode(array('0','Error en el registro'));
			}

		}
		else
		{
			echo json_encode(array('0','Error en el registro'));
		}
	}
	
	/*public function ticketEnvios()
	{
		$inicio					= $this->input->post('FechaDia');
		$fin					= $this->input->post('FechaDia2');
		$criterio				= $this->input->post('txtCriterioBusqueda');
		$idRuta					= $this->input->post('selectRutas');
		$cobrados				= $this->input->post('selectCobrados');
		$fecha					= $this->input->post('fecha');
		
		$data['ventas'] 		= $this->reportes->obtenerEnviosTicket();
		$data['personal']		= $this->administracion->obtenerRegistroPersonal($this->input->post('idPersonalRegistro'));
		$data['vehiculo']		= $this->administracion->obtenerVehiculo($this->input->post('idVehiculoRegistro'));
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;

		if($data['ventas']!=null and $data['personal'] !=null)
		{
			$this->reportes->registrarRelacionTicket($data['ventas'],$this->input->post('idPersonalRegistro'),$this->input->post('idVehiculoRegistro'));
		}

		
		$this->load->view('reportes/envios/ticket',$data);
	}*/

	public function obtenerReporteEntregas($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idVehiculo				= $this->input->post('idVehiculo');
		$idPersonal				= $this->input->post('idPersonal');
	
		$Pag["base_url"]		= base_url()."reportes/obtenerReporteEntregas";
		$Pag["total_rows"]		= $this->reportes->contarReporteEntregas($criterio,$inicio,$fin,$idPersonal,$idVehiculo);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);

		$data['registros'] 		= $this->reportes->obtenerReporteEntregas($Pag["per_page"],$limite,$criterio,$inicio,$fin,$idPersonal,$idVehiculo);
		$data['limite'] 		= $limite+1;

		$this->load->view("reportes/envios/entregas/obteneReporte",$data); 
	}

	public function pdfReporteEntregas($idTicket)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Envíos - Entregas','');

		$data['registro'] 		= $this->reportes->obtenerTicket($idTicket);
		$data['registros'] 		= $this->reportes->obtenerDetallesTicket($data['registro']->idTicket);
		
		$data['configuracion'] 	= $this->configuracion->obtenerConfiguracionActual();
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte']		= 'reportes/envios/entregas/pdf';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',5,5,5,10,2,0);
		
		
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		#$this->mpdf->Output(carpetaFicheros.'Entregas.pdf','D');
		$this->mpdf->Output();
	}

	public function obtenerReporteInventario($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$folio					= $this->input->post('folio');
		$folioTicket			= $this->input->post('folioTicket');
	
		$Pag["base_url"]		= base_url()."reportes/obtenerReporteInventario";
		$Pag["total_rows"]		= $this->reportes->contarReporteInventario($criterio,$inicio,$fin,$folio,$folioTicket);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;

		$this->pagination->initialize($Pag);
		
		$data['registros'] 		= $this->reportes->obtenerReporteInventario($Pag["per_page"],$limite,$criterio,$inicio,$fin,$folio,$folioTicket);
		$data['limite'] 		= $limite+1;

		$this->load->view("reportes/envios/inventario/obteneReporte",$data); 
	}

	public function pdfInventario()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$folio					= $this->input->post('folio');
		$folioTicket			= $this->input->post('folioTicket');

		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Envíos - Inventario entregas','');

		$data['registros'] 		= $this->reportes->obtenerReporteInventario(0,0,$criterio,$inicio,$fin,$folio,$folioTicket);
		
		$data['configuracion'] 	= $this->configuracion->obtenerConfiguracionActual();
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte']		= 'reportes/envios/inventario/pdf';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter-L','','',5,5,24.6,5,2,0);
		
		
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'Inventario.pdf','F');

		echo 'Inventario';
	}

	public function excelInventario()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$folio					= $this->input->post('folio');
		$folioTicket			= $this->input->post('folioTicket');
		
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Envíos - Inventario entregas',''); //Registrar bitácora
		
		$data['registros'] 		= $this->reportes->obtenerReporteInventario(0,0,$criterio,$inicio,$fin,$folio,$folioTicket);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		
		$this->load->view('reportes/envios/inventario/excel',$data);
	}

	
	
	
	public function ticketVentas()
	{
		$criterio					= $this->input->post('txtBusquedaVentas');
		$inicio						= $this->input->post('txtFechaInicioVentas');
		$fin						= $this->input->post('txtFechaFinVentas');
		$idCliente					= $this->input->post('selectClientesBusqueda');
		$idCotizacion				= 0;
		$idFactura					= 1;#$this->input->post('selectFacturasBusqueda');
		$ordenVentas				= 'asc';
		$idEstacion					= $this->input->post('selectEstaciones');
		$traspasos					= $this->input->post('selectVentasTraspasos');
		$saldo						= $this->input->post('chkSaldo')=='1'?'1':'0';
		
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));

		$data['ventas'] 		= $this->clientes->obtenerVentasClientesReporte(0,0,$criterio,$inicio,$fin,$idCliente,$idCotizacion,$idFactura,$ordenVentas,$data['permiso'][4]->activo,$idEstacion,$traspasos,$saldo);
		$data['configuracion']	= $this->configuracion->obtenerConfiguracionActual();
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;

		
		$this->load->view('reportes/ventas/ticket',$data);
	}
	
	//REPORTE DE ENVÍOS
	public function corteDiario()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='envios'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('23',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Corte diario';

		$this->load->view("reportes/corteDiario/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function corteCatalogo()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('23',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$this->load->view("reportes/corteDiario/index",$data); 

	}
	
	public function obtenerCorteDiario()
	{
		$fecha						= $this->input->post('fecha');
	
		$data['facturas'] 			= $this->reportes->obtenerFoliosCorteFactura($fecha);
		$data['remisiones'] 		= $this->reportes->obtenerFoliosCorteRemisión($fecha);
		$data['prefacturas'] 		= $this->reportes->obtenerFoliosCortePrefactura($fecha);
		$data['fecha'] 				= $fecha;

		$this->load->view("reportes/corteDiario/obteneReporte",$data); 
	}
	
	public function reporteCorteDiario()
	{
		$fecha						= $this->input->post('fecha');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Corte diario',''); //Registrar bitácora

		$data['facturas'] 			= $this->reportes->obtenerFoliosCorteFactura($fecha);
		$data['remisiones'] 		= $this->reportes->obtenerFoliosCorteRemisión($fecha);
		$data['prefacturas'] 		= $this->reportes->obtenerFoliosCortePrefactura($fecha);
		$data['fecha'] 				= $fecha;
		$data['reporte']			= 'reportes/corteDiario/pdfReporte';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter-L','','',2,2,30,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'CorteDiario.pdf','F');
		
		echo 'CorteDiario';
	}
	
	
	//REPORTE DE INGRESOS
	public function pagosCredito()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='ingresos'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('25',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['cuentas']		= $this->bancos->obtenerCuentas();
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de pago créditos';
		
		$this->load->view("reportes/pagosCredito/index", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerPagosCredito($limite=0)
	{
		$inicio				= $this->input->post('inicio');
		$fin				= $this->input->post('fin');
		$criterio			= $this->input->post('criterio');
		
		//-----------------------------PAGINACION--------------------------------------
		$paginacion["base_url"]		= base_url()."reportes/obtenerPagosCredito/";
		$paginacion["total_rows"]	=$this->reportes->contarPagosCredito($inicio,$fin,$criterio);
		$paginacion["per_page"]		=50;
		$paginacion["num_links"]	=5;
		
		$this->pagination->initialize($paginacion);
		
		$data['ingresos'] 		= $this->reportes->obtenerPagosCredito($paginacion['per_page'],$limite,$inicio,$fin,$criterio);
		$data['totales'] 		= $this->reportes->sumarPagosCredito($inicio,$fin,$criterio);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('25',$this->session->userdata('rol'));
		$data['permisoIngresos']= $this->configuracion->obtenerPermisosBoton('13',$this->session->userdata('rol'));
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['criterio'] 		= $criterio;
		$data['limite'] 		= $limite+1;
		
		$this->load->view('reportes/pagosCredito/obtenerReporte',$data);
	}
	
	public function reportePagosCredito()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$inicio				= $this->input->post('inicio');
		$fin				= $this->input->post('fin');
		$criterio			= $this->input->post('criterio');
		
		#$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Ingresos',''); //Registrar bitácora

		$data['ingresos'] 		= $this->reportes->obtenerPagosCredito(0,0,$inicio,$fin,$criterio);
		$data['totales'] 		= $this->reportes->sumarPagosCredito($inicio,$fin,$criterio);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte'] 		= 'reportes/pagosCredito/reportePdf';

		$html					= $this->load->view('reportes/principal',$data,true);

		
		$this->mpdf->mPDF('en-x','Legal-L','','',2,2,29.5,5,2,0);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);

#		$this->mpdf->Output();
		$this->mpdf->Output('media/ficheros/PagosCredito.pdf','F');
		echo 'PagosCredito';
	}
	
	//EL EXCEL DE LOS INGRESOS
	public function excelPagosCredito()
	{
		$inicio				= $this->input->post('inicio');
		$fin				= $this->input->post('fin');
		$criterio			= $this->input->post('criterio');
		
		$this->load->library('excel/PHPExcel');
		
		#$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Ingresos',''); //Registrar bitácora
		
		$data['ingresos'] 		= $this->reportes->obtenerPagosCredito(0,0,$inicio,$fin,$criterio);
		$data['totales'] 		= $this->reportes->sumarPagosCredito($inicio,$fin,$criterio);
		
		$this->load->view('reportes/pagosCredito/reporteExcel',$data);
	}
	
	//REPORTE DE PREFACTURAS
	public function prefacturas()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='envios'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('65',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de prefacturas';

		$this->load->view("reportes/prefacturas/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerPrefacturas($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');

	
		$Pag["base_url"]		= base_url()."reportes/obtenerPrefacturas";
		$Pag["total_rows"]		= $this->reportes->contarPrefacturas($inicio,$fin,$criterio);//Total de Registros
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('23',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['registros'] 		= $this->reportes->obtenerPrefacturas($Pag["per_page"],$limite,$inicio,$fin,$criterio);
		#$data['totalCobranza'] 	= $this->reportes->sumarEnvios($inicio,$fin,$criterio,$idRuta);

		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['criterio'] 		= $criterio;

		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de Remisión/Prefactura';

		$this->load->view("reportes/prefacturas/obteneReporte",$data); 
	}
	
	public function reportePrefacturas()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Remisión/Prefactura',''); //Registrar bitácora

		$data['registros'] 		= $this->reportes->obtenerPrefacturas(0,0,$inicio,$fin,$criterio);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte']		= 'reportes/prefacturas/pdfReporte';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',2,2,30.1,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'ReportePrefacturas.pdf','F');
		
		echo 'ReportePrefacturas';
	}
	
	public function excelPrefacturas()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Remisión/Prefactura',''); //Registrar bitácora
		
		$data['registros'] 		= $this->reportes->obtenerPrefacturas(0,0,$inicio,$fin,$criterio);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		
		$this->load->view('reportes/prefacturas/excelReporte',$data);
	}
	
	//REPORTE DE EFECTIVO
	public function caja()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='reportes'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('66',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['cuentas']		= $this->bancos->obtenerCuentas();
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Caja';
		
		$this->load->view("reportes/caja/index", $data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerCaja($limite=0)
	{
		$fecha					= $this->input->post('fecha');
	
		$data['registros'] 		= $this->reportes->obtenerRegistrosCaja($fecha);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('66',$this->session->userdata('rol'));

		$this->load->view('reportes/caja/obtenerReporte',$data);
	}
	
	public function reporteCaja()
	{
		$fecha					= $this->input->post('fecha');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Caja',''); //Registrar bitácora

		$data['registros'] 		= $this->reportes->obtenerRegistrosCaja($fecha);
		$data['fecha'] 			= $fecha;
		$data['reporte']		= 'reportes/caja/reportePdf';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',2,2,30.1,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'Caja.pdf','F');
		
		echo 'Caja';
	}
	
	public function excelCaja()
	{
		$fecha					= $this->input->post('fecha');
		
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Caja',''); //Registrar bitácora
		
		$data['registros'] 		= $this->reportes->obtenerRegistrosCaja($fecha);
		
		$this->load->view('reportes/caja/reporteExcel',$data);
	}
	
	//RETIROS REPORTE
	public function obtenerReporteRetiros($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idEstacion				= $this->input->post('idEstacion');

	
		$Pag["base_url"]		= base_url()."reportes/obtenerReporteRetiros";
		$Pag["total_rows"]		= $this->reportes->contarReporteRetiros($inicio,$fin,$idEstacion);//Total de Registros
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		/*#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('23',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}*/
		
		$data['registros'] 		= $this->reportes->obtenerReporteRetiros($Pag["per_page"],$limite,$inicio,$fin,$idEstacion);
		#$data['totalCobranza'] 	= $this->reportes->sumarEnvios($inicio,$fin,$criterio,$idRuta);

		$data['inicio'] 		= $inicio;
		$data['limite'] 		= $limite+1;


		$this->load->view("reportes/retirosReporte/obtenerRegistros",$data); 
	}
	
    public function imprimirTicketVales($idEgreso=0)
	{
        $data['registro']			= $this->reportes->obtenerRegistroValesRetiros($idEgreso);
        $data['usuario']			= $this->modelousuario->obtenerUsuarioRegistro($this->_iduser);

        $this->load->view('ventas/caja/valesRetiros/ticket',$data);
	}
    
	//RETIROS REPORTE
	public function obtenerVentasEfectivo($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idEstacion				= $this->input->post('idEstacion');

	
		$Pag["base_url"]		= base_url()."reportes/obtenerVentasEfectivo";
		$Pag["total_rows"]		= $this->reportes->contarReporteVentasEfectivo($inicio,$fin,$idEstacion);//Total de Registros
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		/*#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('23',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}*/
		
		$data['registros'] 		= $this->reportes->obtenerReporteVentasEfectivo($Pag["per_page"],$limite,$inicio,$fin,$idEstacion);
		$data['total'] 			= $this->reportes->sumarReporteVentasEfectivo($inicio,$fin,$idEstacion);
		#$data['totalCobranza'] 	= $this->reportes->sumarEnvios($inicio,$fin,$criterio,$idRuta);

		$data['inicio'] 		= $inicio;
		$data['limite'] 		= $limite+1;


		$this->load->view("reportes/ventasEfectivo/obtenerRegistros",$data); 
	}

	//REPORTE DE PRECIO 1
	public function precio1()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual'] 	= $this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='envios'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('67',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		
		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Reporte de prefacturas';

		$this->load->view("reportes/precio1/index",$data); 
		$this->load->view("pie", $Data);
	}
	
	public function obtenerPrecio1($limite=0)
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idEstacion				= $this->input->post('idEstacion');
		$idUsuario				= $this->input->post('idUsuario');

	
		$Pag["base_url"]		= base_url()."reportes/obtenerPrecio1";
		$Pag["total_rows"]		= $this->reportes->contarPrecio1($inicio,$fin,$criterio,$idEstacion,$idUsuario);//Total de Registros
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('67',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['registros'] 		= $this->reportes->obtenerPrecio1($Pag["per_page"],$limite,$inicio,$fin,$criterio,$idEstacion,$idUsuario);
		$data['usuarios']		= $this->configuracion->obtenerListaUsuarios();
		$data['estaciones']		= $this->estaciones->obtenerRegistros();
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['criterio'] 		= $criterio;
		$data['idEstacion'] 	= $idEstacion;
		$data['idUsuario'] 		= $idUsuario;

		$data["breadcumb"]		= '<a href="'.base_url().'reportes/lista">Reportes</a> > Precio 1';

		$this->load->view("reportes/precio1/obteneReporte",$data); 
	}
	
	public function reportePrecio1()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idEstacion				= $this->input->post('idEstacion');
		$idUsuario				= $this->input->post('idUsuario');
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Reportes - Precio 1',''); //Registrar bitácora

		$data['registros'] 		= $this->reportes->obtenerPrecio1(0,0,$inicio,$fin,$criterio,$idEstacion,$idUsuario);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		$data['reporte']		= 'reportes/precio1/pdfReporte';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter-L','','',2,2,26,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'reportePrecio1.pdf','F');
		
		echo 'reportePrecio1';
	}
	
	public function excelPrecio1()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$criterio				= $this->input->post('criterio');
		$idEstacion				= $this->input->post('idEstacion');
		$idUsuario				= $this->input->post('idUsuario');
		
		$this->load->library('excel/PHPExcel');
		
		$this->configuracion->registrarBitacora('Exportar a excel','Reportes - Precio 1',''); //Registrar bitácora
		
		$data['registros'] 		= $this->reportes->obtenerPrecio1(0,0,$inicio,$fin,$criterio,$idEstacion,$idUsuario);
		$data['inicio'] 		= $inicio;
		$data['fin'] 			= $fin;
		
		$this->load->view('reportes/precio1/excelReporte',$data);
	}
}
?>
