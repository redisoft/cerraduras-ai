<?php
class Crm extends CI_Controller
{
	protected $_fechaActual;
	protected $idUsuario;
	protected $_csstyle;
    protected $_tables;
    protected $_role;
	protected $idTienda;
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
		$this->idUsuario 		= $this->session->userdata('id');
		$this->_role 			= $this->session->userdata('role');
		$this->_tables 			= $this->config->item('datatables');
		$this->_csstyle 		= $this->config->item('style');

        
		$this->load->model("importar_modelo","importar");
		$this->load->model("crm_modelo","crm");
		$this->load->model("tablero_modelo","tablero");
		$this->load->model("ventas_model","ventas");
		$this->load->model("compras_modelo","compras");
		$this->load->model("modeloclientes","clientes");
	 	$this->load->model("modelousuario","modelousuario");
		$this->load->model("reportes_model","reportes");
		
		$this->load->model("alumnos_modelo","alumnos");
		$this->load->model("catalogos_modelo","catalogos");
		
		$this->load->model("proveedores_model","proveedores");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("administracion_modelo","administracion");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
  	}
	
	public function formularioCrmClientes()
	{
		$data['status']			= $this->configuracion->obtenerStatus(1);
		$data['estatus']		= $this->configuracion->obtenerEstatus($this->input->post('tipo'));
		$data['servicios']		= $this->configuracion->obtenerServicios(1);
		$data['responsables']	= $this->configuracion->obtenerResponsables(1);
		$data['tiempos']		= $this->configuracion->obtenerTiempos();
		$data['folio']			= obtenerFolioSeguimiento($this->crm->obtenerFolioSeguimientoCliente());
		$data['fecha']			= $this->input->post('fecha');
		$data['hora1']			= $this->input->post('hora1');
		$data['hora2']			= $this->input->post('hora2');
		$data['tipo']			= $this->input->post('tipo');
		
		$data['areas']			= $this->configuracion->obteneraAreas();
		$data['conceptos']		= $this->configuracion->obtenerConceptosArea($data['areas'][0]->idArea);
		
		#$data['contactos']		= $this->clientes->obtenerContactos($this->input->post('idCliente'));
		
		$this->load->view('clientes/seguimiento/crmTablero/formularioCrmClientes',$data);
	}
	
	public function obtenerContactosCliente()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('6',$this->session->userdata('rol'));
		
		$data['contactos']		= $this->clientes->obtenerContactos($this->input->post('idCliente'),$data['permiso'][4]->activo);
		
		$this->load->view('clientes/seguimiento/crmTablero/obtenerContactosCliente',$data);
	}

	public function registrarCotizacion()
	{
		if(!empty($_POST))
		{
			echo $this->clientes->registrarCotizacion();
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerTablero()
	{
		$data['permiso']				= $this->configuracion->obtenerPermisosBoton('59',$this->session->userdata('rol'));
		$data['permisoVentas']			= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoCrm']				= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoCotizaciones']	= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		$data['fecha']					= $this->input->post('fecha');
		$data['idUsuario']				= $this->idUsuario;
		
		$this->load->view('tablero/obtenerTablero',$data);
	}
	
	//CRM DE PROVEEDORES
	public function formularioCrmProveedores()
	{
		$data['status']			= $this->configuracion->obtenerStatus(0);
		$data['servicios']		= $this->configuracion->obtenerServicios(0);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['tiempos']		= $this->configuracion->obtenerTiempos();
		$data['folio']			= obtenerFolioSeguimiento($this->crm->obtenerFolioSeguimientoProveedor());
		$data['fecha']			= $this->input->post('fecha');
		$data['hora1']			= $this->input->post('hora1');
		
		$this->load->view('proveedores/seguimiento/crmTablero/formularioCrmProveedores',$data);
	}
	
	public function obtenerContactosProveedor()
	{
		$data['contactos']		= $this->proveedores->obtenerContactos($this->input->post('idProveedor'));
		
		$this->load->view('proveedores/seguimiento/crmTablero/obtenerContactosProveedor',$data);
	}
	
	//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
	//CRM PARA COTIZACIONES Y VENTAS
	//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
	
	public function obtenerSeguimientoServicio($limite=0)
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$idServicio				= $this->input->post('idServicio');
		$idCotizacion			= $this->input->post('idCotizacion');
		$idCliente				= 0;#$this->input->post('idCliente');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$Pag["base_url"]		= base_url()."crm/obtenerSeguimientoServicio/";
		$Pag["total_rows"]		= $this->crm->contarSeguimientoServicio($idCliente,$inicio,$fin,$idServicio,$idCotizacion,$data['permiso'][4]->activo);
		$Pag["per_page"]		= 50;
		$Pag["num_links"]		= 5;
		$this->pagination->initialize($Pag);
		
		$data['idCliente']		= $this->input->post('idCliente');
		$data['seguimientos']	= $this->crm->obtenerSeguimientoServicio($Pag["per_page"],$limite,$idCliente,$inicio,$fin,$idServicio,$idCotizacion,$data['permiso'][4]->activo);
		#$data['seguimiento']	= $this->crm->obtenerClienteSeguimientoCotizacion($idCotizacion);
		$data['seguimiento']	= $this->ventas->obtenerCotizacion($idCotizacion);
		
		$data['idCliente']		= $this->input->post('idCliente');
		$data['idCotizacion']	= $this->input->post('idCotizacion');
		$data['idServicio']		= $this->input->post('idServicio');
		
		$this->load->view('clientes/seguimiento/crmServicios/obtenerSeguimientoServicio',$data);
	}
	
	public function formularioSeguimientoServicios()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('6',$this->session->userdata('rol'));
		
		$data['status']			= $this->configuracion->obtenerStatus(1);
		#$data['servicios']		= $this->configuracion->obtenerServicios(1);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['tiempos']		= $this->configuracion->obtenerTiempos();
		$data['contactos']		= $this->clientes->obtenerContactos($this->input->post('idCliente'),$data['permiso'][4]->activo);
		$data['folio']			= obtenerFolioSeguimiento($this->crm->obtenerFolioSeguimientoCliente());
		$data['idServicio']		= $this->input->post('idServicio');
		
		$this->load->view('clientes/seguimiento/crmServicios/formularioSeguimientoServicios',$data);
	}
	
	public function obtenerSeguimientoEditarServicio()
	{
		$idSeguimiento				=$this->input->post('idSeguimiento');
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoCrm']			= $this->configuracion->obtenerPermisosBoton('6',$this->session->userdata('rol'));
		
		$data['status']				= $this->configuracion->obtenerStatus(1);
		$data['seguimiento']		= $this->clientes->obtenerSeguimiento($idSeguimiento);
		#$data['responsables']		= $this->configuracion->obtenerResponsables();
		$data['responsables']		= $this->configuracion->obtenerResponsables($data['permiso'][4]->activo,$data['seguimiento']->idResponsable);
		$data['servicios']			= $this->configuracion->obtenerServicios(1);
		$data['tiempos']			= $this->configuracion->obtenerTiempos();
		$data['contactos']			= $this->clientes->obtenerContactos($data['seguimiento']->idCliente,$data['permisoCrm'][4]->activo);
		$data['cliente']			= $this->clientes->obtenerCliente($data['seguimiento']->idCliente);
		$data['idSeguimiento']		= $idSeguimiento;
		
		$this->load->view('clientes/seguimiento/crmServicios/obtenerSeguimientoEditarServicio',$data);
	}
	
	//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
	//CRM PARA COMPRAS
	//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
	
	public function obtenerSeguimientoServicioCompras($limite=0)
	{
		$idServicio				= $this->input->post('idServicio');
		$idCompra				= $this->input->post('idCompra');
		$idProveedor			= 0;#$this->input->post('idCliente');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$Pag["base_url"]		= base_url()."crm/obtenerSeguimientoServicioCompras/";
		$Pag["total_rows"]		= $this->crm->contarSeguimientoServicioCompras($idProveedor,$inicio,$fin,$idServicio,$idCompra);
		$Pag["per_page"]		= 200;
		$Pag["num_links"]		= 5;
		$this->pagination->initialize($Pag);
		
		$data['seguimientos']	= $this->crm->obtenerSeguimientoServicioCompras($Pag["per_page"],$limite,$idProveedor,$inicio,$fin,$idServicio,$idCompra);
		$data['seguimiento']	= $this->compras->obtenerCompra($idCompra);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('9',$this->session->userdata('rol'));
		$data['idProveedor']	= $this->input->post('idProveedor');
		$data['idCompra']		= $this->input->post('idCompra');
		$data['idServicio']		= $this->input->post('idServicio');
		
		$this->load->view('proveedores/seguimiento/crmServicios/obtenerSeguimientoServicio',$data);
	}
	
	public function formularioSeguimientoServiciosCompras()
	{
		$data['status']			= $this->configuracion->obtenerStatus(0);
		#$data['servicios']		= $this->configuracion->obtenerServicios(1);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['tiempos']		= $this->configuracion->obtenerTiempos();
		$data['contactos']		= $this->proveedores->obtenerContactos($this->input->post('idProveedor'));
		$data['folio']			= obtenerFolioSeguimiento($this->crm->obtenerFolioSeguimientoProveedor());
		$data['idServicio']		= $this->input->post('idServicio');
		
		$this->load->view('proveedores/seguimiento/crmServicios/formularioSeguimientoServicios',$data);
	}
	
	public function obtenerSeguimientoEditarServicioCompras()
	{
		$idSeguimiento				=$this->input->post('idSeguimiento');
		
		$data['status']				= $this->configuracion->obtenerStatus(0);
		$data['seguimiento']		= $this->proveedores->obtenerSeguimiento($idSeguimiento);
		$data['responsables']		= $this->configuracion->obtenerResponsables();
		$data['servicios']			= $this->configuracion->obtenerServicios(0);
		$data['tiempos']			= $this->configuracion->obtenerTiempos();
		$data['contactos']			= $this->proveedores->obtenerContactos($data['seguimiento']->idProveedor);
		$data['proveedor']			= $this->proveedores->obtenerProveedor($data['seguimiento']->idProveedor);
		$data['idSeguimiento']		= $idSeguimiento;
		
		$this->load->view('proveedores/seguimiento/crmServicios/obtenerSeguimientoEditarServicio',$data);
	}
	
	
	//SEGUIMIENTO A CLIENTES 
	//=============================================================================================================
	
	public function formularioSeguimientoDetalle()
	{
		$idSeguimiento				= $this->input->post('idSeguimiento');
		$data['seguimiento']		= $this->clientes->obtenerSeguimiento($idSeguimiento);
		$data['cliente']			= $this->clientes->obtenerCliente($data['seguimiento']->idCliente);
		$data['responsables']		= $this->configuracion->obtenerResponsables();
		$data['idSeguimiento']		= $idSeguimiento;
		
		$this->load->view('clientes/seguimiento/detalles/formularioSeguimientoDetalle',$data);
	}
	
	public function registrarDetalleSeguimiento()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->crm->registrarDetalleSeguimiento());
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	public function borrarDetalleSeguimiento()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->crm->borrarDetalleSeguimiento($this->input->post('idDetalle')));
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	//EDITAR EL RESPONSABLE
	//=============================================================================================================
	public function formularioEditarResponsable()
	{
		$data['seguimiento']	= $this->clientes->obtenerSeguimiento($this->input->post('idSeguimiento'));
		$data['cliente']		= $this->clientes->obtenerCliente($data['seguimiento']->idCliente);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		
		$this->load->view('clientes/seguimiento/responsables/formularioEditarResponsable',$data);
	}
	
	public function editarResponsable()
	{
		if(!empty($_POST))
		{
			error_reporting(0);
			
			echo json_encode($this->crm->editarResponsable());
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	//BAJA DE PROSPECTOS
	//=============================================================================================================
	
	public function formularioBajas()
	{
		$data['cliente']		= $this->clientes->obtenerCliente($this->input->post('idCliente'));
		$data['causas']			= $this->configuracion->obtenerCausas(0,0,'',0,$this->input->post('tipo'));
		$data['tipo']			= $this->input->post('tipo');
		
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('63',$this->session->userdata('rol'));
		
		$this->load->view('clientes/prospectos/bajas/formularioBajas',$data);
	}
	
	public function registrarBaja()
	{
		if(!empty($_POST))
		{
			echo $this->crm->registrarBaja();
		}
		else
		{
			echo "0";
		}
	}
	
	public function reactivarProspecto()
	{
		if(!empty($_POST))
		{
			echo $this->crm->reactivarProspecto();
		}
		else
		{
			echo "0";
		}
	}
	
	//ENVIAR IMPORTE
	//=============================================================================================================
	
	public function formularioEnviarImporte()
	{
		$data['cliente']		= $this->clientes->obtenerCliente($this->input->post('idCliente'));
		
		$this->load->view('clientes/prospectos/seguimientosDiarios/formularioEnviarImporte',$data);
	}
	
	public function enviarImporte()
	{
		if(!empty($_POST))
		{
			echo $this->crm->enviarImporte();
		}
		else
		{
			echo "0";
		}
	}
	
	//ARCHIVOS DE SEGUIMIENTO
	public function subirArchivosSeguimiento()
	{
		$data	= array('upload_dir'=>carpetaSeguimientoClientes,'max_file_size'=>'1073741824','discard_aborted_uploads'=>false);
		
		$this->load->library('UploadHandler',$data);
	}
	
	public function renombrarArchivos()
	{
		$data['archivos']		= $this->input->post('archivos');
		$data['indice']			= $this->input->post('indice');
		$data['id']				= $this->input->post('id');
		
		$this->load->view('clientes/prospectos/archivos/renombrarArchivos',$data);
	}
	
	public function registrarClienteIexe()
	{
		if(!empty($_POST))
		{
			echo $this->crm->registrarClienteIexe();
		}
		else
		{
			echo "0";
		}
	}
	
	//ALERTAS
	public function obtenerSeguimientoAlertaPasado()
	{
		$data['alertas']		= $this->crm->obtenerSeguimientoAlertaPasadoFechas();
		$this->crm->pararAlertas();

		$this->load->view('clientes/prospectos/alertas/obtenerSeguimientoAlertaPasado',$data);
	}
	
	public function excelAlertas()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['alertas']		= $this->crm->obtenerSeguimientoAlertaPasadoFechas();

		$this->load->view('clientes/prospectos/alertas/excelAlertas',$data);
	}
	
	public function obtenerSeguimientoAlerta()
	{
		$data['alertas']		= $this->crm->obtenerSeguimientoAlerta();
		
		if($data['alertas']!=null) 
		{
		 	$this->crm->procesarSeguimientosAlerta($data['alertas']);
		}
		
	#	print_r($data);
		
		
		if($data['alertas']==null)
		{
			echo '';
			return;
		}
		
		$this->load->view('clientes/prospectos/alertas/obtenerSeguimientoAlertaPasado',$data);
	}
	
	public function comprobarSeguimientoAlertasPasado()
	{
		echo count($this->crm->obtenerSeguimientoAlertaPasadoFechas());
	}
	
	public function atrasos()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		#$data['seguimientos']		= $this->crm->obtenerSeguimientoAtrasos($this->_role!=1?$this->idUsuario:0);
		$data['seguimientos']		= $this->crm->obtenerSeguimientoAtrasos($data['permiso'][17]->activo==0?$this->idUsuario:0,$data['permiso'][17]->activo,0);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Atrasos';
		
		$this->load->view("clientes/prospectos/atrasos/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerSeguimientosAtrasos()
	{
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$nuevos						= $this->input->post('nuevos');
		
		$data['seguimientos']		= $this->crm->obtenerSeguimientoAtrasos($this->input->post('idPromotor'),$data['permiso'][17]->activo,$nuevos);
		$data['fecha']				= $this->_fechaActual;
		$data['nuevos']				= $nuevos;
		
		#$this->load->view('clientes/prospectos/seguimientosDiarios/obtenerSeguimientosDiarios',$data);
		$this->load->view('clientes/prospectos/seguimientosDiarios/obtenerSeguimientosAtrasos',$data);
	}
	
	public function obtenerSeguimientoAtrasos()
	{
		$idSeguimiento				= $this->input->post('idSeguimiento');
		$data['seguimiento']		= $this->clientes->obtenerSeguimiento($idSeguimiento);
		$data['estatus']			= $this->configuracion->obtenerEstatus($data['seguimiento']->tipo);
		$data['cliente']			= $this->clientes->obtenerCliente($data['seguimiento']->idCliente);
		$data['detalles']			= $this->crm->obtenerDetallesSeguimientoFechas($idSeguimiento,date('Y-m-d'));
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['programas']			= $this->configuracion->obtenerProgramas();
		
		#$data['alertas']			= $this->crm->obtenerSeguimientoAlerta();
		$data['alertasPasado']		= $this->crm->obtenerSeguimientoAlertaPasado();
		
		#if($data['alertas']!=null)  $this->crm->procesarSeguimientosAlerta($data['alertas']);
		#if($data['alertasPasado']!=null)  $this->crm->procesarSeguimientosAlerta($data['alertasPasado']);
		
		$data['idSeguimiento']		= $idSeguimiento;
		
		$this->load->view('clientes/prospectos/seguimientosDiarios/obtenerSeguimientoDiario',$data);
	}
	
	//REPORTES
	public function reportes()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		#$data['seguimientos']		= $this->crm->obtenerSeguimientoAtrasos($this->_role!=1?$this->idUsuario:0);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Reporte';
		
		$this->load->view("clientes/prospectos/reportes/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerReporte($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$prospecto				= $this->input->post('prospecto');
		$idPromotor				= $this->input->post('idPromotor');
		
		$seguimientos			= $this->input->post('seguimientos');
		$tipoFecha				= $this->input->post('tipoFecha');
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."crm/obtenerReporte/";
		$Pag["total_rows"]		= $this->crm->contarReporte($criterio,$idFuente,$idPrograma,$idCampana,$prospecto,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$seguimientos,$tipoFecha);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		$this->pagination->initialize($Pag);
		
		$data['prospectos']		= $this->crm->obtenerReporte($Pag["per_page"],$limite,$criterio,$idFuente,$idPrograma,$idCampana,$prospecto,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$seguimientos,$tipoFecha);
		
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		$data['programas']		= $this->configuracion->obtenerProgramas();
		$data['fuentes']		= $this->clientes->obtenerFuentesContacto();

		$data['idFuente']		= $idFuente;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['prospecto']		= $prospecto;
		$data['idPromotor']		= $idPromotor;
		$data['limite']			= $limite+1;
		$data['registros']		= $Pag["total_rows"];
		$data['seguimientos']	= $seguimientos;
		
		$this->load->view('clientes/prospectos/reportes/obtenerReporte',$data);
	}
	
	public function excelReporte()
	{
		$criterio				= $this->input->post('criterio');
		$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$prospecto				= $this->input->post('prospecto');
		$idPromotor				= $this->input->post('idPromotor');
		$seguimientos			= $this->input->post('seguimientos');
		$tipoFecha				= $this->input->post('tipoFecha');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['prospectos']		= $this->crm->obtenerReporte(0,0,$criterio,$idFuente,$idPrograma,$idCampana,$prospecto,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$seguimientos,$tipoFecha);

		$this->load->view('clientes/prospectos/reportes/excelReporte',$data);
	}
	
	
	//REPORTES
	public function reporteBajas()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['programas']			= $this->configuracion->obtenerProgramas();
		$data['seguimientos']		= $this->crm->obtenerSeguimientoAtrasos($this->_role!=1?$this->idUsuario:0);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data['causas']				= $this->configuracion->obtenerCausas();
		$data["breadcumb"]			= 'Reporte';
		
		$this->load->view("clientes/prospectos/reporteBajas/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerReporteBajas($limite=0)
	{
		$idCausa				= $this->input->post('idCausa');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		#$Pag["base_url"]		= base_url()."crm/obtenerReporteBajas/";
		#$Pag["total_rows"]		= $this->crm->contarReporteBajas($idCausa,$idPrograma,$idCampana,$data['permiso'][5]->activo);
		$Pag["per_page"]		= 200;
		$Pag["num_links"]		= 5;
		#$this->pagination->initialize($Pag);
		
		$data['bajas']			= $this->crm->obtenerReportebajas($idPrograma,$idCampana,$data['permiso'][5]->activo,$idCausa,$inicio,$fin);
		
		#$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		
		$data['causas']			= $this->configuracion->obtenerCausas(0,0,'',$idCausa);

		$data['idCausa']		= $idCausa;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['limite']			= $limite+1;
		#$data['registros']		= $Pag["total_rows"];
		$data['registros']		= 1;
		
		$this->load->view('clientes/prospectos/reporteBajas/obtenerReporte',$data);
	}
	
	public function excelReporteBajas()
	{
		$idCausa				= $this->input->post('idCausa');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['bajas']			= $this->crm->obtenerReportebajas($idPrograma,$idCampana,$data['permiso'][5]->activo,$idCausa,$inicio,$fin);
		$data['campana']		= $this->configuracion->obtenerCampanasEditar($idCampana);
		$data['programa']		= $this->configuracion->obtenerProgramasEditar($idPrograma);

		$this->load->view('clientes/prospectos/reporteBajas/excelReporte',$data);
	}
	
	//REPORTES PROMOTORES
	public function reportePromotores()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Reporte';
		
		$this->load->view("clientes/prospectos/reportePromotores/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerReportePromotores($limite=0)
	{
		$idPromotor				= $this->input->post('idPromotor');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		#$Pag["base_url"]		= base_url()."crm/obtenerReporteBajas/";
		#$Pag["total_rows"]		= $this->crm->contarReporteBajas($idCausa,$idPrograma,$idCampana,$data['permiso'][5]->activo);
		$Pag["per_page"]		= 200;
		$Pag["num_links"]		= 5;
		#$this->pagination->initialize($Pag);
		
		$data['prospectos']		= $this->crm->obtenerReportePromotores($idPromotor,$idCampana,$data['permiso'][5]->activo,$inicio,$fin);
		
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		$data['programas']		= $this->configuracion->obtenerProgramas();
		$data['causas']			= $this->configuracion->obtenerCausas();

		$data['idPromotor']		= $idPromotor;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['inicio']			= $inicio;
		$data['fin']			= $fin;
		$data['limite']			= $limite+1;
		#$data['registros']		= $Pag["total_rows"];
		$data['registros']		= 1;
		
		$this->load->view('clientes/prospectos/reportePromotores/obtenerReporte',$data);
	}
	
	public function excelReportePromotores()
	{
		$idPromotor				= $this->input->post('idPromotor');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['prospectos']		= $this->crm->obtenerReportePromotores($idPromotor,$idCampana,$data['permiso'][5]->activo,$inicio,$fin);
		$data['inicio']			= $inicio;
		$data['fin']			= $fin;

		$this->load->view('clientes/prospectos/reportePromotores/excelReporte',$data);
	}
	
	public function obtenerDetalleInscritos()
	{
		$idPromotor				= $this->input->post('idPromotor');
		$idCampana				= $this->input->post('idCampana');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		$data['inscritos']		= $this->crm->obtenerDetalleInscritos($idPromotor,$idCampana,$inicio,$fin);

		$this->load->view('clientes/prospectos/reportePromotores/obtenerDetalleInscritos',$data);
	}
	
	public function editarProgramaProspecto()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->crm->editarProgramaProspecto());
		}
		else
		{
			echo json_encode(array(0,0,0));
		}
	}
	
	public function editarCampanaProspecto()
	{
		if(!empty($_POST))
		{
			echo $this->crm->editarCampanaProspecto();
		}
		else
		{
			echo "0";
		}
	}
	
	public function editarFuenteProspecto()
	{
		if(!empty($_POST))
		{
			echo $this->crm->editarFuenteProspecto();
		}
		else
		{
			echo "0";
		}
	}
	
	//COMISIONES
	public function excelComisiones()
	{
		$idPromotor		= $this->input->post('idPromotor');
		$idCampana		= $this->input->post('idCampana');
		$idPrograma		= $this->input->post('idPrograma');
		$criterio		= $this->input->post('criterio');

		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['comisiones']			= $this->configuracion->obtenerComisiones(0,0,$criterio,$idPromotor,$idCampana,$idPrograma);

		$this->load->view('configuracion/comisiones/excelComisiones',$data);
	}
	
	//NUEVOS
	public function nuevos()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		#$data['nuevos']				= $this->crm->obtenerNuevos($this->_role!=1?$this->idUsuario:0);
		
		$data['nuevos']				= $this->crm->obtenerNuevos($data['permiso'][17]->activo==0?$this->idUsuario:0,$data['permiso'][17]->activo);
		
		
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Nuevos';
		
		$this->load->view("clientes/prospectos/nuevos/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerNuevo()
	{
		$data['cliente']			= $this->clientes->obtenerCliente($this->input->post('idCliente'));
		$data['programas']			= $this->configuracion->obtenerProgramas();
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['fecha']				= $this->_fechaActual;
		
		#$this->load->view('clientes/prospectos/seguimientosDiarios/obtenerSeguimientosDiarios',$data);
		$this->load->view('clientes/prospectos/nuevos/obtenerNuevo',$data);
	}
	
	public function obtenerNuevos()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		$idPromotor					= $this->input->post('idPromotor');
		$data['nuevos']				= $this->crm->obtenerNuevos($idPromotor,$data['permiso'][17]->activo);

		$this->load->view('clientes/prospectos/nuevos/obtenerNuevos',$data);
	}
	
	public function registrarSeguimientoNuevos()
	{
		if(!empty($_POST))
		{
			echo $this->crm->registrarSeguimientoNuevos();
		}
		else
		{
			echo "0";
		}
	}
	
	//PREINSCRITOS
	public function registrarPreinscrito()
	{
		if(!empty($_POST))
		{
			echo $this->crm->registrarPreinscrito();
		}
		else
		{
			echo "0";
		}
	}
	
	public function validarProspecto()
	{
		if(!empty($_POST))
		{
			error_reporting(0);
			echo $this->crm->validarProspecto();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarPreinscrito()
	{
		if(!empty($_POST))
		{
			echo $this->crm->borrarPreinscrito();
		}
		else
		{
			echo "0";
		}
	}

	public function obtenerPreinscritos($limite=0)
	{
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol')); //PERMISOS DE PROMOTORES
		
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		$idUsuario					= $this->input->post('idUsuario');
		$criterio					= $this->input->post('criterio');
		$idCampana					= $this->input->post('idCampana');
		$idPrograma					= $this->input->post('idPrograma');
		$idFuente					= $this->input->post('idFuente');
		$idCampanaOriginal			= $this->input->post('idCampanaOriginal');
		$mes						= $this->input->post('mes');

		$Pag["base_url"]			= base_url()."crm/obtenerPreinscritos/";
		$Pag["total_rows"]			= $this->crm->contarPreinscritos($inicio,$fin,$idUsuario,$criterio,$idCampana,$idPrograma,$data['permiso'][17]->activo,$idFuente,$idCampanaOriginal,$mes);
		$Pag["per_page"]			= 30;
		$Pag["num_links"]			= 4;
		
		$this->pagination->initialize($Pag);

		
		$data['preinscritos'] 		= $this->crm->obtenerPreinscritos($Pag["per_page"],$limite,$inicio,$fin,$idUsuario,$criterio,$idCampana,$idPrograma,$data['permiso'][17]->activo,$idFuente,$idCampanaOriginal,$mes);
		#$data['atrasosTotal'] 		= $this->reportes->obtenerAtrasos(0,0,$inicio,$fin,$idUsuario,$registros,$criterio);
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['programas']			= $this->configuracion->obtenerProgramas();
		$data['fuentes']			= $this->clientes->obtenerFuentesContacto();
		$data['meses']				= $this->catalogos->obtenerMeses();
		$data['idUsuario'] 			= $idUsuario;
		$data['idCampana'] 			= $idCampana;
		$data['idPrograma'] 		= $idPrograma;
		$data['idCampanaOriginal'] 	= $idCampanaOriginal;
		$data['inicio'] 			= $limite+1;
		$data['totalRegistros']		= $Pag["total_rows"];
		$data['editar'] 			= $this->input->post('editar');
		$data['idFuente']			= $idFuente;
		$data['mes']				= $mes;


		$this->load->view("clientes/prospectos/preinscritos/obtenerPreinscritos",$data); 
	}
	
	//
	public function excelPreinscritos()
	{
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idUsuario				= $this->input->post('idUsuario');
		$criterio				= $this->input->post('criterio');
		$idCampana				= $this->input->post('idCampana');
		$idPrograma				= $this->input->post('idPrograma');
		$idFuente				= $this->input->post('idFuente');
		$idCampanaOriginal		= $this->input->post('idCampanaOriginal');
		$mes					= $this->input->post('mes');

		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['preinscritos'] 		= $this->crm->obtenerPreinscritos(0,0,$inicio,$fin,$idUsuario,$criterio,$idCampana,$idPrograma,1,$idFuente,$idCampanaOriginal,$mes);

		$this->load->view('clientes/prospectos/preinscritos/excelPreinscritos',$data);
	}
	
	public function obtenerDetallePreinscritos()
	{
		$idPromotor				= $this->input->post('idPromotor');
		$idCampana				= $this->input->post('idCampana');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		$data['inscritos']		= $this->crm->obtenerDetallePreinscritos($idPromotor,$idCampana,$inicio,$fin);

		$this->load->view('clientes/prospectos/reportePromotores/obtenerDetalleInscritos',$data);
	}
	
	public function registrarTotalesAcademicosProspecto()
	{
		if(!empty($_POST))
		{
			echo $this->crm->registrarTotalesAcademicosProspecto();
		}
		else
		{
			echo "0";
		}
	}
	
	//EDITAR MATRÍCULA
	public function formularioMatricula()
	{
		$data['academico']				= $this->clientes->obtenerAcademicoCliente($this->input->post('idCliente'));
		$data['meses']			= $this->catalogos->obtenerMeses();
		$data['periodos']		= $this->configuracion->obtenerPeriodos();
		
		if($data['academico']==null)
		{
			$this->crm->registrarPeriodoPreinscritoMatricula($this->input->post('idCliente'));
			
			$data['academico']				= $this->clientes->obtenerAcademicoCliente($this->input->post('idCliente'));
		}

		$this->load->view('clientes/preinscritos/formularioMatricula',$data);
	}
	
	public function registrarMatricula()
	{
		if(!empty($_POST))
		{
			echo $this->crm->registrarMatricula();
		}
		else
		{
			echo "0";
		}
	}
	
	//EDITAR SEGUIMIENTO
	public function obtenerEstatusSeguimientoEditar()
	{
		$idSeguimiento			= $this->input->post('idSeguimiento');

		$data['seguimiento']	= $this->clientes->obtenerSeguimiento($idSeguimiento);
		$data['estatus']		= $this->configuracion->obtenerEstatus(0);

		$this->load->view('llamadas/obtenerEstatusSeguimientoEditar',$data);
	}
	
	public function editarEstatusSeguimientoDetalle()
	{
		if(!empty($_POST))
		{
			echo $this->crm->editarEstatusSeguimientoDetalle();
		}
		else
		{
			echo "0";
		}
	}
	
	
	//REPORTES PROMOTORES
	public function reporteProspectos()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Reporte';
		
		$this->load->view("clientes/prospectos/reporteProspectos/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerReporteProspectos($limite=0)
	{
		$idPromotor				= $this->input->post('idPromotor');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$idCampanaOriginal		= $this->input->post('idCampanaOriginal');
		$idFuente				= $this->input->post('idFuente');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		#$Pag["base_url"]		= base_url()."crm/obtenerReporteBajas/";
		#$Pag["total_rows"]		= $this->crm->contarReporteBajas($idCausa,$idPrograma,$idCampana,$data['permiso'][5]->activo);
		$Pag["per_page"]		= 200;
		$Pag["num_links"]		= 5;
		#$this->pagination->initialize($Pag);
		
		$data['prospectos']		= $this->crm->obtenerReporteProspectos($idPromotor,$idCampana,$idPrograma,$idFuente,$data['permiso'][5]->activo,$inicio,$fin,$idCampanaOriginal);
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		$data['programas']		= $this->configuracion->obtenerProgramas();
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['fuentes']		= $this->clientes->obtenerFuentesContacto();

		$data['idPromotor']		= $idPromotor;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['idCampanaOriginal']		= $idCampanaOriginal;
		$data['idFuente']		= $idFuente;
		$data['inicio']			= $inicio;
		$data['fin']			= $fin;
		$data['limite']			= $limite+1;
		#$data['registros']		= $Pag["total_rows"];
		$data['registros']		= 1;
		
		$this->load->view('clientes/prospectos/reporteProspectos/obtenerReporte',$data);
	}
	
	public function excelReporteProspectos()
	{
		$idPromotor				= $this->input->post('idPromotor');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$idCampanaOriginal		= $this->input->post('idCampanaOriginal');
		$idFuente				= $this->input->post('idFuente');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['prospectos']		= $this->crm->obtenerReporteProspectos($idPromotor,$idCampana,$idPrograma,$idFuente,$data['permiso'][5]->activo,$inicio,$fin,$idCampanaOriginal);
		$data['idPromotor']		= $idPromotor;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['idCampanaOriginal']		= $idCampanaOriginal;
		$data['idFuente']		= $idFuente;
		$data['inicio']			= $inicio;
		$data['fin']			= $fin;

		$this->load->view('clientes/prospectos/reporteProspectos/excelReporte',$data);
	}
	
	//EDITAR LOS DATOS DE LOS PROGRAMAS
	public function formularioEditarComision()
	{
		$idCliente					= $this->input->post('idCliente');
		$data['cliente']			= $this->clientes->obtenerCliente($idCliente);
		$data['academicos']			= $this->clientes->obtenerAcademicoCliente($idCliente);
		$data['programa']			= $this->configuracion->obtenerProgramasEditar($data['academicos']!=null?$data['academicos']->idPrograma:0);
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['programas']			= $this->configuracion->obtenerProgramas();
		$data['venta']				= $this->crm->obtenerProgramaVenta($idCliente);

		$this->load->view('configuracion/comisiones/formularioEditarComision',$data);
	}
	
	public function editarComision()
	{
		if(!empty($_POST))
		{
			echo $this->crm->editarComision();
		}
		else
		{
			echo "0";
		}
	}
	
	//HISTORIAL DE SEGUIMIENTOS
	public function obtenerHistorialSeguimiento()
	{
		$idCliente					= $this->input->post('idCliente');
		$data['cliente']			= $this->clientes->obtenerCliente($idCliente);
		$data['detalles']			= $this->crm->obtenerDetallesSeguimientoCliente($idCliente);

		$this->load->view('clientes/prospectos/bajas/obtenerHistorialSeguimiento',$data);
	}
	
	
	//REPORTES DE INSCRITOS
	public function inscritos()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['seguimientos']		= $this->crm->obtenerSeguimientoAtrasos($this->_role!=1?$this->idUsuario:0);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Vel. Inscritos';
		
		$this->load->view("clientes/prospectos/inscritos/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerInscritos($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$prospecto				= $this->input->post('prospecto');
		$idPromotor				= $this->input->post('idPromotor');
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."crm/obtenerInscritos/";
		$Pag["total_rows"]		= $this->crm->contarInscritos($criterio,$idPrograma,$idCampana,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		$this->pagination->initialize($Pag);
		
		$data['prospectos']		= $this->crm->obtenerInscritos($Pag["per_page"],$limite,$criterio,$idPrograma,$idCampana,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin);
		
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		$data['programas']		= $this->configuracion->obtenerProgramas();
		$data['fuentes']		= $this->clientes->obtenerFuentesContacto();

		$data['idFuente']		= $idFuente;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['idPromotor']		= $idPromotor;
		$data['limite']			= $limite+1;
		$data['registros']		= $Pag["total_rows"];
		
		$this->load->view('clientes/prospectos/inscritos/obtenerReporte',$data);
	}
	
	public function excelInscritos()
	{
		$criterio				= $this->input->post('criterio');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$idPromotor				= $this->input->post('idPromotor');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['prospectos']		= $this->crm->obtenerInscritos(0,0,$criterio,$idPrograma,$idCampana,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin);

		$this->load->view('clientes/prospectos/inscritos/excelReporte',$data);
	}
	
	//PAGOS DE ALUMNOS
	public function excelPagos()
	{
		if(sistemaActivo!='IEXE')
		{
			show_404();
			return;
		}

		$criterio				= $this->input->post('criterio');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$idPromotor				= $this->input->post('idPromotor');
		
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['pagos']		= $this->administracion->obtenerPagos(0,0,$criterio,$idPrograma,$idCampana,$idPromotor,$data['permiso'][5]->activo);

		$this->load->view('administracion/iexe/pagos/excelPagos',$data);
	}
	
	//ADMINISTRAR PLANTILLAS
	
	public function obtenerPlantillas()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$tipoPlantilla			= $this->input->post('tipoPlantilla');
		
		$data['plantillas']		= $this->crm->obtenerPlantillas($data['permiso'][19]->activo,$tipoPlantilla);
		
		if($tipoPlantilla==0)
		{
			$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro(1);
		}
		else
		{
			$data['programas']		= $this->configuracion->obtenerProgramas();
		}
		
		$data['tipoPlantilla']	= $tipoPlantilla;

		$this->load->view('clientes/prospectos/plantillas/obtenerPlantillas',$data);
	}
	
	public function obtenerPlantilla()
	{
		$data['plantilla']	= $this->crm->obtenerPlantilla($this->input->post('idPlantilla'));

		$this->load->view('clientes/prospectos/plantillas/obtenerPlantilla',$data);
	}
	
	public function obtenerPlantillaEditar()
	{
		$data['plantilla']	= $this->crm->obtenerPlantilla($this->input->post('idPlantilla'));

		$this->load->view('clientes/prospectos/plantillas/obtenerPlantillaEditar',$data);
	}
	
	public function subirPlantilla($idPlantilla=0)
	{
		if (!empty($_FILES)) 
		{
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('text','html','htm','xhtml','zip');
			
			if($idPlantilla>0)
			{
				$extensiones 		= array('jpg','jpeg','gif','png','tif','bmp','pdf','doc','docx','xls','xlsx','txt','rar','zip','xps','oxps','xml','PDF','ppt','pps','pptx','ppsx');
			}
			
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				$idFichero	= $this->crm->subirPlantilla($_FILES['file']['name'],$_FILES['file']['size'],$idPlantilla,$archivo['extension'],$this->input->post('idUsuario'),$this->input->post('tipoPlantilla'),$this->input->post('idPrograma'));
				
				if($idFichero>0)
				{
					$carpeta	= carpetaPlantillas.'plantilla_'.$idFichero.'/';
					
					if($idPlantilla==0)
					{
						crearDirectorio($carpeta);
					}
					
					if($idPlantilla>0)
					{
						$carpeta	= carpetaPlantillas.'plantilla_'.$idPlantilla.'/';
					}
					
					move_uploaded_file($archivoTemporal,$carpeta.$idFichero.'_'.$_FILES['file']['name']);

					if(file_exists($carpeta.$idFichero.'_'.$_FILES['file']['name']))
					{
						if($archivo['extension']=='zip')
						{
							$zip = new ZipArchive;
							$res = $zip->open($carpeta.$idFichero.'_'.$_FILES['file']['name']);
							
							if ($res === TRUE) 
							{
								$zip->extractTo($carpeta);
								$zip->close();
							} 
						}
						
						echo "1";
					}
					else
					{
						echo 'El comprobante no ha podido subir correctamente';
					}
				}
				else
				{
					echo 'Error al subir el comprobante';
				}
			} 
			else 
			{
				echo 'No se permiten estos archivos';
			}
		}
	} 
	
	public function descargarAdjunto($idPlantilla) #Descargar el archivo XML
	{
		$this->load->helper('download');

		$fichero	= $this->crm->obtenerPlantilla($idPlantilla);
		$carpeta	= carpetaPlantillas.'plantilla_'.$fichero->idPlantillaPadre.'/';
		$archivo 	= $fichero->idPlantilla.'_'.$fichero->nombre;
		$data 		= file_get_contents($carpeta.$archivo); 
		
		force_download($fichero->nombre, $data); 
	}

	public function borrarPlantilla()
	{
		if(!empty($_POST))
		{
			echo $this->crm->borrarPlantilla($this->input->post('idPlantilla'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function editarPlantilla()
	{
		if(!empty($_POST))
		{
			echo $this->crm->editarPlantilla();
		}
		else
		{
			echo "0";
		}
	}
	
	public function editarPromotorPlantilla()
	{
		if(!empty($_POST))
		{
			echo $this->crm->editarPromotorPlantilla();
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerPlantillaEnviar()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		
		$tipoPlantilla			= $this->input->post('tipoPlantilla');
		
		if($tipoPlantilla=='0')
		{
			$criterio				= $this->input->post('criterio');
			$idStatus				= $this->input->post('idStatus');
			$idEstatus				= $this->input->post('idEstatus');
			$idPromotor				= $this->input->post('idPromotor');
			$idTipo					= $this->input->post('idTipo');
			$criterioSeccion		= $this->input->post('criterioSeccion');
			$numeroSeguimientos		= $this->input->post('numeroSeguimientos');
			$idCampana				= $this->input->post('idCampana');
			$idPrograma				= $this->input->post('idPrograma');
			
			$idFuente				= $this->input->post('idFuente');
			$tipoFecha				= $this->input->post('tipoFecha');
			$inicial				= $this->input->post('inicial');
			$final					= $this->input->post('final');
			
			$idServicio				= 0;
			$fecha					= $this->input->post('fecha');
			$fechaFin				= $this->input->post('fechaFin');
			$idResponsable			= 0;
			$fechaMes				= 'mes';
			$idZona					= 0;
			$idResponsable			= 0;
			
			$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
			$data['plantillas']		= $this->crm->obtenerPlantillas($data['permiso'][19]->activo,$tipoPlantilla);
			
			
			$data['clientes']		= $this->clientes->obtenerProspectosUsuario(0,0,$criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][5]->activo,$idZona,'asc',$idEstatus,$idPromotor,$fechaFin,$numeroSeguimientos,$idCampana,$idPrograma,$idFuente,$tipoFecha,$inicial,$final);
		}
		else
		{
			$data['permiso']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
			
			$criterio				= $this->input->post('criterio');
			$inicio					= $this->input->post('inicio');
			$fin					= $this->input->post('fin');
			$idStatus				= $this->input->post('idStatus');
			$idServicio				= $this->input->post('idServicio');
			$idUsuarioRegistro		= $this->input->post('idUsuarioRegistro');
			$idResponsable			= $this->input->post('idResponsable');
			$idEstatus				= $this->input->post('idEstatus');
			$idPrograma				= $this->input->post('idPrograma');
			
			$data['clientes']		= $this->clientes->obtenerLlamadas(0,0,$criterio,$inicio,$fin,$idStatus,$idServicio,$data['permiso'][4]->activo,$idUsuarioRegistro,$idResponsable,$idEstatus,$idPrograma,1);
			$data['plantillas']		= $this->crm->obtenerPlantillas(1,$tipoPlantilla,$idPrograma);
		}
	
		
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);	

		$this->load->view('clientes/prospectos/plantillas/obtenerPlantillaEnviar',$data);
	}
	
	public function enviarPlantilla()
	{
		if(!empty($_POST))
		{
			$data['plantilla']		= $this->crm->obtenerPlantilla($this->input->post('idPlantilla'));
			$data['adjuntos']		= $this->crm->obtenerAdjuntosPlantilla($this->input->post('idPlantilla'));

			$this->load->view('clientes/prospectos/plantillas/enviarPlantilla',$data);
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerPlantillasEnviadas()
	{
		if(!empty($_POST))
		{
			$data['envios']		= $this->crm->obtenerPlantillasEnviadas($this->input->post('idCliente'));

			$this->load->view('clientes/prospectos/plantillas/obtenerPlantillasEnviadas',$data);
		}
		else
		{
			echo "Error";
		}
	}
	
	public function enviarPlantilla1()
	{
		if(!empty($_POST))
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
		
			$criterio				= $this->input->post('criterio');
			$idStatus				= $this->input->post('idStatus');
			$idEstatus				= $this->input->post('idEstatus');
			$idPromotor				= $this->input->post('idPromotor');
			$idTipo					= $this->input->post('idTipo');
			$criterioSeccion		= $this->input->post('criterioSeccion');
			$numeroSeguimientos		= $this->input->post('numeroSeguimientos');
			$idCampana				= $this->input->post('idCampana');
			$idPrograma				= $this->input->post('idPrograma');
			
			$idServicio				= 0;
			$fecha					= $this->input->post('fecha');
			$fechaFin				= $this->input->post('fechaFin');
			$idResponsable			= 0;
			$fechaMes				= 'mes';
			$idZona					= 0;
			$idResponsable			= 0;
			
			
			$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
			$data['clientes']		= $this->clientes->obtenerProspectosUsuario(0,0,$criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][5]->activo,$idZona,'asc',$idEstatus,$idPromotor,$fechaFin,$numeroSeguimientos,$idCampana,$idPrograma);
			$data['plantilla']		= $this->crm->obtenerPlantilla($this->input->post('idPlantilla'));
			$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);	
			
			$this->load->library('email');
			
			#print_r($data['clientes']);
			
			$this->load->view('clientes/prospectos/plantillas/enviarPlantilla',$data);
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerProgramasCampana()
	{
		if(!empty($_POST))
		{
			$data['programas']		= $this->configuracion->obtenerProgramasCampana($this->input->post('idCampana'));

			$this->load->view('clientes/prospectos/obtenerProgramasCampana',$data);
		}
		else
		{
			echo "Error";
		}
	}
	
	public function obtenerProgramasCampanaRegistro()
	{
		if(!empty($_POST))
		{
			$data['programas']		= $this->configuracion->obtenerProgramasCampana($this->input->post('idCampana'));

			$this->load->view('clientes/prospectos/obtenerProgramasCampanaRegistro',$data);
		}
		else
		{
			echo "Error";
		}
	}
	
	//PRIMER CONTACTO
	public function primerContacto()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['seguimientos']		= $this->crm->obtenerSeguimientoAtrasos($this->_role!=1?$this->idUsuario:0);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Reporte';
		
		$this->load->view("clientes/prospectos/primerContacto/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerPrimerContacto($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$idPromotor				= $this->input->post('idPromotor');
		$tipoFecha				= $this->input->post('tipoFecha');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		/*$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$prospecto				= $this->input->post('prospecto');
		$seguimientos			= $this->input->post('seguimientos');*/
		
		$idFuente				= 0;
		$idPrograma				= 0;
		$idCampana				= 0;
		$prospecto				= -1;
		$seguimientos			= 0;
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."crm/obtenerPrimerContacto/";
		$Pag["total_rows"]		= $this->crm->contarPrimerContacto($criterio,$idFuente,$idPrograma,$idCampana,$prospecto,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$seguimientos,$tipoFecha);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		$this->pagination->initialize($Pag);
		
		$data['prospectos']		= $this->crm->obtenerPrimerContacto($Pag["per_page"],$limite,$criterio,$idFuente,$idPrograma,$idCampana,$prospecto,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$seguimientos,$tipoFecha);
		
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		$data['programas']		= $this->configuracion->obtenerProgramas();
		$data['fuentes']		= $this->clientes->obtenerFuentesContacto();

		$data['idFuente']		= $idFuente;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['prospecto']		= $prospecto;
		$data['idPromotor']		= $idPromotor;
		$data['limite']			= $limite+1;
		$data['registros']		= $Pag["total_rows"];
		$data['seguimientos']	= $seguimientos;
		
		$this->load->view('clientes/prospectos/primerContacto/obtenerReporte',$data);
	}
	
	public function excelPrimerContacto()
	{
		$criterio				= $this->input->post('criterio');
		$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$prospecto				= $this->input->post('prospecto');
		$idPromotor				= $this->input->post('idPromotor');
		$seguimientos			= $this->input->post('seguimientos');
		$tipoFecha				= $this->input->post('tipoFecha');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$idFuente				= 0;
		$idPrograma				= 0;
		$idCampana				= 0;
		$prospecto				= -1;
		$seguimientos			= 0;
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['prospectos']		= $this->crm->obtenerPrimerContacto(0,0,$criterio,$idFuente,$idPrograma,$idCampana,$prospecto,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$seguimientos,$tipoFecha);

		$this->load->view('clientes/prospectos/primerContacto/excelReporte',$data);
	}
	
	
	//REPORTES
	public function historialSeguimiento()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['seguimientos']		= $this->crm->obtenerSeguimientoAtrasos($this->_role!=1?$this->idUsuario:0);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Reporte';
		
		$this->load->view("clientes/prospectos/historialSeguimiento/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerHistorialSeguimientos($limite=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));

		$data['fechas']			= $this->reportes->obtenerDiferenciaFechas($inicio,$fin,'day');
		$data['seguimientos']	= $this->crm->sumarSeguimientosPromotor($inicio,$fin);
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);

		$data['inicio']			= $inicio;
		$data['fin']			= $fin;

		$this->load->view('clientes/prospectos/historialSeguimiento/obtenerReporte',$data);
	}
	
	public function excelHistorialSeguimiento()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));

		$data['fechas']			= $this->reportes->obtenerDiferenciaFechas($inicio,$fin,'day');
		$data['seguimientos']	= $this->crm->sumarSeguimientosPromotor($inicio,$fin);
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);

		$data['inicio']			= $inicio;
		$data['fin']			= $fin;

		$this->load->library('excel/PHPExcel');

		$this->load->view('clientes/prospectos/historialSeguimiento/excelReporte',$data);
	}
	
	
	
	
	//REPORTES
	public function repositorio()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['seguimientos']		= $this->crm->obtenerSeguimientoAtrasos($this->_role!=1?$this->idUsuario:0);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Reporte';
		
		$this->load->view("clientes/prospectos/repositorio/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerRepositorio($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."crm/obtenerRepositorio/";
		$Pag["total_rows"]		= $this->crm->contarRepositorio($criterio,$inicio,$fin);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		$this->pagination->initialize($Pag);
		
		$data['prospectos']		= $this->crm->obtenerRepositorio($Pag["per_page"],$limite,$criterio,$inicio,$fin);
		$data['registros']		= $Pag["total_rows"];
		$data['limite']			= $limite+1;
		

		$this->load->view('clientes/prospectos/repositorio/obtenerReporte',$data);
	}
	
	public function excelRepositorio()
	{
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['prospectos']		= $this->crm->obtenerRepositorio(0,0,$criterio,$inicio,$fin);

		$this->load->view('clientes/prospectos/repositorio/excelReporte',$data);
	}
	
	
	public function formularioAsignarPromotor()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol')); //PERMISOS DE PROMOTORES
		
		$data['alumno']			= $this->alumnos->obtenerAlumno($this->input->post('idAlumno'));
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		
		$this->load->view('clientes/prospectos/repositorio/formularioAsignarPromotor',$data);
	}
	
	public function asignarPromotor()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->alumnos->asignarPromotor());
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	public function obtenerDetallesEmbudo()
	{
		$data['detalles']			= $this->configuracion->obtenerDetallesEmbudo($this->input->post('idEmbudo'));

		$this->load->view('clientes/prospectos/embudo/obtenerDetallesEmbudo',$data);
	}
	
	public function contarDetalleEmbudo()
	{
		$data['numero']			= $this->crm->contarDetalleEmbudo($this->input->post('idEmbudo'),$this->input->post('idCliente'));

		$this->load->view('clientes/prospectos/embudo/contarDetalleEmbudo',$data);
	}
	
	//REPORTES
	public function embudo()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['seguimientos']		= $this->crm->obtenerSeguimientoAtrasos($this->_role!=1?$this->idUsuario:0);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Reporte';
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['programas']			= $this->configuracion->obtenerProgramas();
		
		$this->load->view("clientes/prospectos/embudo/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerReporteEmbudo()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		$data['embudo']			= $this->crm->obtenerReporteEmbudo($idPrograma,$idCampana,$inicio,$fin);
		$data['detalles']		= $this->crm->obtenerDetallesEmbudo($data['embudo']);
		
		#print_r($data['detalles']);
		
		#exit;
		
		
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;

		$this->load->view('clientes/prospectos/embudo/obtenerReporte',$data);
	}
	
	public function excelReporteEmbudo()
	{
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['embudo']			= $this->crm->obtenerReporteEmbudo($idPrograma,$idCampana,$inicio,$fin);
		$data['detalles']		= $this->crm->obtenerDetallesEmbudo($data['embudo']);
		
		$data['campana']		= $this->configuracion->obtenerCampanasEditar($idCampana);
		$data['programa']		= $this->configuracion->obtenerProgramasEditar($idPrograma);

		$this->load->view('clientes/prospectos/embudo/excelReporte',$data);
	}
	
	/*public function obtenerReporteEmbudo()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		$data['embudo']			= $this->crm->obtenerReporteEmbudo($idPrograma,$idCampana,$inicio,$fin);
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;

		$this->load->view('clientes/prospectos/embudo/obtenerReporte',$data);
	}
	
	public function excelReporteEmbudo()
	{
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['embudo']			= $this->crm->obtenerReporteEmbudo($idPrograma,$idCampana,$inicio,$fin);
		$data['campana']		= $this->configuracion->obtenerCampanasEditar($idCampana);
		$data['programa']		= $this->configuracion->obtenerProgramasEditar($idPrograma);

		$this->load->view('clientes/prospectos/embudo/excelReporte',$data);
	}*/
	
	//DETALLES
	public function obtenerDetallesCausaBaja()
	{
		$data['detalles']			= $this->catalogos->obtenerDetallesCausaBaja($this->input->post('idCausa'));

		$this->load->view('clientes/prospectos/bajas/obtenerDetalles',$data);
	}
	
	public function obtenerDetallesCausaNocuali()
	{
		$data['detalles']			= $this->catalogos->obtenerDetallesCausaNocuali($this->input->post('idCausa'));

		$this->load->view('clientes/prospectos/nocuali/obtenerDetalles',$data);
	}
	
	//REPORTES
	public function metodo()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Método';

		$this->load->view("clientes/prospectos/metodo/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerMetodo($limite=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$criterio				= $this->input->post('criterio');
		$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$idMetodo				= $this->input->post('idMetodo');
		$idPromotor				= $this->input->post('idPromotor');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."crm/obtenerMetodo/";
		$Pag["total_rows"]		= $this->crm->contarMetodo($criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		$this->pagination->initialize($Pag);
		
		$data['prospectos']			= $this->crm->obtenerMetodo($Pag["per_page"],$limite,$criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin);
		$data['numeroProspectos']	= $this->crm->contarMetodoProspectos($criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin);
		
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		$data['programas']		= $this->configuracion->obtenerProgramas();
		$data['fuentes']		= $this->clientes->obtenerFuentesContacto();
		$data['metodos']		= $this->configuracion->obtenerMetodos();

		$data['idFuente']		= $idFuente;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['idPromotor']		= $idPromotor;
		$data['limite']			= $limite+1;
		$data['registros']		= $Pag["total_rows"];
		$data['idMetodo']		= $idMetodo;
		
		$this->load->view('clientes/prospectos/metodo/obtenerReporte',$data);
	}
	
	public function excelMetodo()
	{
		$criterio				= $this->input->post('criterio');
		$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$idMetodo				= $this->input->post('idMetodo');
		$idPromotor				= $this->input->post('idPromotor');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['prospectos']		= $this->crm->obtenerMetodo(0,0,$criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin);

		$this->load->view('clientes/prospectos/metodo/excelReporte',$data);
	}
	
	//REPORTES
	public function metodoGlobal()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Método global';

		$this->load->view("clientes/prospectos/metodoGlobal/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerMetodoGlobal($limite=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$criterio				= $this->input->post('criterio');
		$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$idMetodo				= $this->input->post('idMetodo');
		$idPromotor				= $this->input->post('idPromotor');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$contactado				= $this->input->post('contactado');
		$cualificado			= $this->input->post('cualificado');
		$interesado				= $this->input->post('interesado');
		$idCausa				= $this->input->post('idCausa');
		$idDetalle				= $this->input->post('idDetalle');

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."crm/obtenerMetodoGlobal/";
		$Pag["total_rows"]		= $this->crm->contarMetodoGlobal($criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$contactado,$cualificado,$interesado,$idCausa,$idDetalle);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		$this->pagination->initialize($Pag);
		
		$data['prospectos']			= $this->crm->obtenerMetodoGlobal($Pag["per_page"],$limite,$criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$contactado,$cualificado,$interesado,$idCausa,$idDetalle);
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['programas']			= $this->configuracion->obtenerProgramas();
		$data['fuentes']			= $this->clientes->obtenerFuentesContacto();
		$data['metodos']			= $this->configuracion->obtenerMetodos();
		
		$data['nocuali']			= $this->configuracion->obtenerNocuali();
		$data['nocualiDetalles']	= $this->catalogos->obtenerRegistrosCausaNocuali();

		$data['idFuente']		= $idFuente;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['idPromotor']		= $idPromotor;
		$data['limite']			= $limite+1;
		$data['registros']		= $Pag["total_rows"];
		$data['idMetodo']		= $idMetodo;
		
		$data['contactado']		= $contactado;
		$data['cualificado']	= $cualificado;
		$data['interesado']		= $interesado;
		$data['idCausa']		= $idCausa;
		$data['idDetalle']		= $idDetalle;
		
		$this->load->view('clientes/prospectos/metodoGlobal/obtenerReporte',$data);
	}
	
	public function excelMetodoGlobal()
	{
		$criterio				= $this->input->post('criterio');
		$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$idMetodo				= $this->input->post('idMetodo');
		$idPromotor				= $this->input->post('idPromotor');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$contactado				= $this->input->post('contactado');
		$cualificado			= $this->input->post('cualificado');
		$interesado				= $this->input->post('interesado');
		$idCausa				= $this->input->post('idCausa');
		$idDetalle				= $this->input->post('idDetalle');

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['prospectos']		= $this->crm->obtenerMetodoGlobal(0,0,$criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$contactado,$cualificado,$interesado,$idCausa,$idDetalle);

		$this->load->view('clientes/prospectos/metodoGlobal/excelReporte',$data);
	}
	
	
	//REPORTES PROSPECTOS
	public function prospectosGlobal()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Método global';
		
		$data['prospectos']			= $this->catalogos->obtenerClientesProspectos();
		$data['nocualiDetalles']	= $this->catalogos->obtenerRegistrosCausaNocuali();
		
		
		
		$this->load->view("clientes/prospectos/prospectosGlobal/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerProspectosGlobal($limite=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$criterio				= $this->input->post('criterio');
		$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$idMetodo				= $this->input->post('idMetodo');
		$idPromotor				= $this->input->post('idPromotor');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		$idProspecto			= $this->input->post('idProspecto');
		$idDetalleProspecto		= $this->input->post('idDetalleProspecto');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."crm/obtenerMetodoGlobal/";
		$Pag["total_rows"]		= $this->crm->contarProspectosGlobal($criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$idProspecto,$idDetalleProspecto);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		$this->pagination->initialize($Pag);
		
		$data['prospectos']			= $this->crm->obtenerProspectosGlobal($Pag["per_page"],$limite,$criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$idProspecto,$idDetalleProspecto);

		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['programas']			= $this->configuracion->obtenerProgramas();
		$data['fuentes']			= $this->clientes->obtenerFuentesContacto();
		$data['metodos']			= $this->configuracion->obtenerMetodos();
		
		

		$data['idFuente']		= $idFuente;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['idPromotor']		= $idPromotor;
		$data['limite']			= $limite+1;
		$data['registros']		= $Pag["total_rows"];
		$data['idMetodo']		= $idMetodo;

		$this->load->view('clientes/prospectos/prospectosGlobal/obtenerReporte',$data);
	}
	
	public function excelProspectosGlobal()
	{
		$criterio				= $this->input->post('criterio');
		$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$idMetodo				= $this->input->post('idMetodo');
		$idPromotor				= $this->input->post('idPromotor');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		$idProspecto			= $this->input->post('idProspecto');
		$idDetalleProspecto		= $this->input->post('idDetalleProspecto');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['prospectos']		= $this->crm->obtenerProspectosGlobal(0,0,$criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$idProspecto,$idDetalleProspecto);

		$this->load->view('clientes/prospectos/prospectosGlobal/excelReporte',$data);
	}
	
	//FACEBOOK
	public function facebook()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['seguimientos']		= $this->crm->obtenerSeguimientoAtrasos($this->_role!=1?$this->idUsuario:0);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Reporte';
		
		$this->load->view("clientes/prospectos/facebook/index",$data);
		$this->load->view("pie",$Data);
	}
	
	//REPORTES FICHA ENVIADA
	public function fichaEnviada()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->idUsuario);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'clientes';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][18]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['idUsuario']			= $this->_role!=1?$this->idUsuario:0;
		$data["breadcumb"]			= 'Ficha enviada';
		
		$data['prospectos']			= $this->catalogos->obtenerClientesProspectos();
		$data['nocualiDetalles']	= $this->catalogos->obtenerRegistrosCausaNocuali();
		
		
		
		$this->load->view("clientes/prospectos/fichaEnviada/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerFichaEnviada($limite=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$criterio				= $this->input->post('criterio');
		$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$idMetodo				= $this->input->post('idMetodo');
		$idPromotor				= $this->input->post('idPromotor');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		$idProspecto			= $this->input->post('idProspecto');
		$idDetalleProspecto		= $this->input->post('idDetalleProspecto');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."crm/obtenerFichaEnviada/";
		$Pag["total_rows"]		= $this->crm->contarFichaEnviada($criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$idProspecto,$idDetalleProspecto);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		$this->pagination->initialize($Pag);
		
		$data['prospectos']			= $this->crm->obtenerFichaEnviada($Pag["per_page"],$limite,$criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$idProspecto,$idDetalleProspecto);

		$data['promotores']			= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['programas']			= $this->configuracion->obtenerProgramas();
		$data['fuentes']			= $this->clientes->obtenerFuentesContacto();
		$data['metodos']			= $this->configuracion->obtenerMetodos();
		
		

		$data['idFuente']		= $idFuente;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['idPromotor']		= $idPromotor;
		$data['limite']			= $limite+1;
		$data['registros']		= $Pag["total_rows"];
		$data['idMetodo']		= $idMetodo;

		$this->load->view('clientes/prospectos/fichaEnviada/obtenerReporte',$data);
	}
	
	public function excelFichaEnviada()
	{
		$criterio				= $this->input->post('criterio');
		$idFuente				= $this->input->post('idFuente');
		$idPrograma				= $this->input->post('idPrograma');
		$idCampana				= $this->input->post('idCampana');
		$idMetodo				= $this->input->post('idMetodo');
		$idPromotor				= $this->input->post('idPromotor');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		$idProspecto			= $this->input->post('idProspecto');
		$idDetalleProspecto		= $this->input->post('idDetalleProspecto');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['prospectos']		= $this->crm->obtenerFichaEnviada(0,0,$criterio,$idFuente,$idPrograma,$idCampana,$idMetodo,$idPromotor,$data['permiso'][5]->activo,$inicio,$fin,$idProspecto,$idDetalleProspecto);

		$this->load->view('clientes/prospectos/fichaEnviada/excelReporte',$data);
	}
	
}
?>
