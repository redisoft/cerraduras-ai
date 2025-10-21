<?php
class Clientes extends CI_Controller
{
	protected $_fechaActual;
	protected $_iduser;
	protected $_csstyle;
    protected $_tables;
    protected $_role;
	protected $idTienda;
	protected $cuota;
	protected $orden;
	protected $fecha;
	protected $precios;
	protected $idLicencia;
	protected $tiendaLocal;
	protected $registroVentas;

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
	    $this->_fechaActual 	= date('Y-m-d H:i:s');
		$this->_iduser 			= $this->session->userdata('id');
		$this->_role 			= $this->session->userdata('role');
		$this->_tables 			= $this->config->item('datatables');
		$this->_csstyle 		= $this->config->item('style');
		$this->fecha 			= date('Y-m-d');
		
		$this->load->model("crm_modelo","crm");
		$this->load->model("bancos_model","bancos");
	 	$this->load->model("modeloclientes","clientes");
        $this->load->model("modelousuario","modelousuario");
		$this->load->model("facturacion_modelo","facturacion");
		$this->load->model("inventarioproductos_modelo","inventarioProductos");
		$this->load->model("inventario_model","inventario");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("ventas_model","ventas");
		$this->load->model("previa_modelo","previa");
		$this->load->model("facturaventa_modelo","facturaVenta");
		$this->load->model("reportes_model","reportes");
		$this->load->model("tiendas_modelo","tiendas");
		$this->load->model("arreglos_modelo","arreglos");
		$this->load->model("contabilidad_modelo","contabilidad");
		$this->load->model("temporal_modelo","temporal");
		
		$this->load->model("ventas_modelo","ventasmodelo");
		$this->load->model("ventas_model","ventas");
		$this->load->model("catalogos_modelo","catalogos");
		
		$this->load->model("importar_modelo","importar");
		$this->load->model("estaciones_modelo","estaciones");
		
		$this->idTienda 		= $this->session->userdata('idTiendaActiva');
		$this->idLicencia 		= $this->session->userdata('idLicencia');
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota			= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
		
		$this->precios			= $this->session->userdata('precios');
		$this->tiendaLocal		= $this->session->userdata('tiendaLocal');
		$this->registroVentas	= $this->session->userdata('registroVentas');
		
  	}
	
	public function index()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
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

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['servicios']		= $this->configuracion->obtenerServicios(1);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['grupos']			= $this->clientes->agruparClientesRegistro();
		$data["breadcumb"]		= sistemaActivo=='IEXE'?'Alumnos/Clientes':'Clientes';
		
		$this->load->view("clientes/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerClientes($limite=0)
	{
		$criterio		= trim($this->input->post('criterio'));
		$idStatus		= $this->input->post('idStatus');
		$idServicio		= $this->input->post('idServicio');
		$fecha			= $this->input->post('fecha');
		$idResponsable	= $this->input->post('idResponsable');
		$idTipo			= $this->input->post('idTipo');
		$fechaMes		= $this->input->post('mes');
		$idZona			= $this->input->post('idZona');
		$idPrograma		= $this->input->post('idPrograma');
		$idCampana		= $this->input->post('idCampana');
		$diaPago		= $this->input->post('diaPago');
		$matricula		= $this->input->post('matricula');
		$orden			= $this->input->post('orden');
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}
				
		#----------------------------------PAGINACION------------------------------------#
		$url		= base_url()."clientes/obtenerClientes/";
		$registros	= $this->clientes->contarClientes($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][4]->activo,$idZona,$idPrograma,$diaPago,$idCampana,$matricula);
		$numero		= 20;
		$links		= 5;
		$uri		= 3;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		#---------------------------------------------------------------------------------#
		
		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['clientes']		= $this->clientes->obtenerClientesUsuario($numero,$limite,$criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][4]->activo,$idZona,$idPrograma,$diaPago,$idCampana,$matricula,$orden);
		
		$data['status']			= $this->configuracion->obtenerStatus(1);
		$data['servicios']		= $this->configuracion->obtenerServicios(1);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		
		#$data['cliente']		= $this->clientes->obtenerCliente($idCliente);
		$data['zonas']			= $this->configuracion->obtenerZonas();
		#$data['idCliente']		= $idCliente;
		$data['idStatus']		= $idStatus;
		$data['idResponsable']	= $idResponsable;
		$data['idServicio']		= $idServicio;
		$data['idTipo']			= $idTipo;
		$data['fecha']			= $fecha;
		$data['inicio']  		= $limite+1;
		$data['fechaMes']		= $fechaMes;
		$data['idZona']			= $idZona;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['tipoRegistro']	= $this->input->post('tipoRegistro');
		$data['diaPago']		= $diaPago;
		$data['registros']		= $registros;
		$data['matricula']		= $matricula;
		$data['orden']			= $orden;
		#$data["breadcumb"]		= sistemaActivo=='IEXE'?'Alumnos/Clientes':'Clientes';
		
		if(sistemaActivo=='IEXE')
		{
			$data['colegiaturas']	= $this->clientes->sumarColegiaturas($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][4]->activo,$idZona,$idPrograma,$diaPago,$idCampana);
			$data['diasPago']		= $this->clientes->obtenerDiasPago($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][4]->activo,$idZona,$idPrograma,$diaPago,$idCampana);
			$data['programas']		= $this->configuracion->obtenerProgramas();
			$data['campanas']		= $this->configuracion->obtenerCampanas();
		
			if($data['tipoRegistro']=='clientes')
			{
				$this->load->view('clientes/iexe/obtenerClientes',$data);
			}
			else
			{
				$this->load->view('clientes/prospectos/obtenerProspectos',$data);
			}
		}
		else
		{
			$this->load->view("clientes/obtenerClientes",$data);
		}
	}
	
	public function activos()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
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

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['servicios']		= $this->configuracion->obtenerServicios(1);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['grupos']			= $this->clientes->agruparClientesRegistro();
		$data["breadcumb"]		= 'Alumnos activos';
		
		$this->load->view("clientes/iexe/activos",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerActivos($limite=0)
	{
		$criterio		= trim($this->input->post('criterio'));
		$idStatus		= $this->input->post('idStatus');
		$idServicio		= $this->input->post('idServicio');
		$fecha			= $this->input->post('fecha');
		$idResponsable	= $this->input->post('idResponsable');
		$idTipo			= $this->input->post('idTipo');
		$fechaMes		= $this->input->post('mes');
		$idZona			= $this->input->post('idZona');
		$idPrograma		= $this->input->post('idPrograma');
		$idCampana		= $this->input->post('idCampana');
		$diaPago		= $this->input->post('diaPago');
		$matricula		= $this->input->post('matricula');
		$orden			= $this->input->post('orden');
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}
				
		#----------------------------------PAGINACION------------------------------------#
		$url		= base_url()."clientes/obtenerActivos/";
		$registros	= $this->clientes->contarClientes($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][4]->activo,$idZona,$idPrograma,$diaPago,$idCampana,$matricula);
		$numero		= 20;
		$links		= 5;
		$uri		= 3;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		#---------------------------------------------------------------------------------#
		
		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['clientes']		= $this->clientes->obtenerClientesUsuario($numero,$limite,$criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][4]->activo,$idZona,$idPrograma,$diaPago,$idCampana,$matricula,$orden);
		
		$data['status']			= $this->configuracion->obtenerStatus(1);
		$data['servicios']		= $this->configuracion->obtenerServicios(1);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['programas']		= $this->configuracion->obtenerProgramas();
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		
		#$data['cliente']		= $this->clientes->obtenerCliente($idCliente);
		$data['zonas']			= $this->configuracion->obtenerZonas();
		#$data['idCliente']		= $idCliente;
		$data['idStatus']		= $idStatus;
		$data['idResponsable']	= $idResponsable;
		$data['idServicio']		= $idServicio;
		$data['idTipo']			= $idTipo;
		$data['fecha']			= $fecha;
		$data['inicio']  		= $limite+1;
		$data['fechaMes']		= $fechaMes;
		$data['idZona']			= $idZona;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['tipoRegistro']	= $this->input->post('tipoRegistro');
		$data['diaPago']		= $diaPago;
		$data['registros']		= $registros;
		$data['matricula']		= $matricula;
		$data['orden']			= $orden;
		#$data["breadcumb"]		= sistemaActivo=='IEXE'?'Alumnos/Clientes':'Clientes';
		
		$this->load->view('clientes/iexe/obtenerActivos',$data);
	}
	
	public function preinscritos()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
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

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['servicios']		= $this->configuracion->obtenerServicios(1);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['grupos']			= $this->clientes->agruparClientesRegistro();
		$data["breadcumb"]		= 'Preinscritos';
		
		$this->load->view("clientes/preinscritos/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerPreinscritos($limite=0)
	{
		$criterio		= trim($this->input->post('criterio'));
		$idPromotor		= $this->input->post('idPromotor');
		$idPrograma		= $this->input->post('idPrograma');
		$idCampana		= $this->input->post('idCampana');
		$inicio			= $this->input->post('inicio');
		$fin			= $this->input->post('fin');
		$idFuente		= $this->input->post('idFuente');
		$matricula		= $this->input->post('matricula');
		$mes			= $this->input->post('mes');
		$idPeriodo		= $this->input->post('idPeriodo');
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		$data['permisoPromotor']= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}
				
		#----------------------------------PAGINACION------------------------------------#
		$url		= base_url()."clientes/obtenerPreinscritos/";
		$registros	= $this->clientes->contarPreinscritos($criterio,$idPromotor,$idPrograma,$idCampana,$data['permiso'][4]->activo,$inicio,$fin,$idFuente,$matricula,$mes,$idPeriodo);
		$numero		= 30;
		$links		= 5;
		$uri		= 3;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		#---------------------------------------------------------------------------------#
		
		$data['clientes']		= $this->clientes->obtenerPreinscritos($numero,$limite,$criterio,$idPromotor,$idPrograma,$idCampana,$data['permiso'][4]->activo,$inicio,$fin,$idFuente,$matricula,$mes,$idPeriodo);
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permisoPromotor'][17]->activo);
		$data['idPromotor']		= $idPromotor;
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['registros']		= $registros;
		$data['inicio']			= $limite+1;
		$data['idFuente']		= $idFuente;
		$data['matricula']		= $matricula;
		$data['mes']			= $mes;
		$data['idPeriodo']		= $idPeriodo;
		
		$data['programas']		= $this->configuracion->obtenerProgramas();
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		$data['fuentes']		= $this->clientes->obtenerFuentesContacto();
		
		$data['meses']			= $this->catalogos->obtenerMeses();
		$data['periodos']		= $this->configuracion->obtenerPeriodos();
		
		$this->load->view('clientes/preinscritos/obtenerPreinscritos',$data);
	}
	
	public function excelPreinscritos()
	{
		$criterio		= trim($this->input->post('criterio'));
		$idPromotor		= $this->input->post('idPromotor');
		$idPrograma		= $this->input->post('idPrograma');
		$idCampana		= $this->input->post('idCampana');
		$inicio			= $this->input->post('inicio');
		$fin			= $this->input->post('fin');
		$mes			= $this->input->post('mes');
		$idPeriodo		= $this->input->post('idPeriodo');
		
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol')); //PERMISOS DE PROMOTORES

		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['clientes']		= $this->clientes->obtenerPreinscritos(0,0,$criterio,$idPromotor,$idPrograma,$idCampana,$data['permiso'][4]->activo,$inicio,$fin,$mes,$idPeriodo);

		$this->load->view('clientes/preinscritos/excelPreinscritos',$data);
	}
	
	public function prospectos($criterio='prospectos')
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];   
		#$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'prospectos';   
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		$data['permisoSie']		= $this->configuracion->obtenerPermisosBoton('64',$this->session->userdata('rol'));
		$data['permisoMetas']	= $this->configuracion->obtenerPermisosBoton('67',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['servicios']		= $this->configuracion->obtenerServicios(1);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['criterio']		= $criterio;
		$data["breadcumb"]		= 'Prospectos';
		$data['idRol']			= $this->_role;
		$data['idUsuarioLogin']	= $this->_iduser;
		
		$this->load->view("clientes/prospectos/index",$data);
		$this->load->view("pie",$Data);
	}
	
	
	public function obtenerProspectos($limite=0)
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
		
		$idServicio		= 0;
		$fecha			= $this->input->post('fecha');
		$fechaFin		= $this->input->post('fechaFin');
		$idResponsable	= 0;
		$fechaMes		= 'mes';
		$idZona			= 0;
		$idResponsable	= 0;
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}
				
		#----------------------------------PAGINACION------------------------------------#
		$url		= base_url()."clientes/obtenerProspectos/";
		$registros	= $this->clientes->contarProspectos($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][5]->activo,$idZona,$idEstatus,$idPromotor,$fechaFin,$numeroSeguimientos,$idCampana,$idPrograma,$idFuente,$tipoFecha,$inicial,$final);
		$numero		= 20;
		$links		= 5;
		$uri		= 3;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		#---------------------------------------------------------------------------------#
		
		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['clientes']		= $this->clientes->obtenerProspectosUsuario($numero,$limite,$criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][5]->activo,$idZona,'asc',$idEstatus,$idPromotor,$fechaFin,$numeroSeguimientos,$idCampana,$idPrograma,$idFuente,$tipoFecha,$inicial,$final);
		$data['totalSeguimientos']		= $this->clientes->sumarSeguimientosProspectosUsuario($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][5]->activo,$idZona,'asc',$idEstatus,$idPromotor,$fechaFin,$numeroSeguimientos,$idCampana,$idPrograma,$idFuente,$tipoFecha,$inicial,$final);
		$data['status']			= $this->configuracion->obtenerStatus(1);
		$data['servicios']		= $this->configuracion->obtenerServicios(1);
		#$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro(1);
		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['estatus']		= $this->configuracion->obtenerEstatus(1);
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		$data['programas']		= $this->configuracion->obtenerProgramas();
		$data['fuentes']		= $this->clientes->obtenerFuentesContacto();
		#$data['numeros']		= $this->clientes->obtenerProspectosUsuarioSeguimiento($criterio,$idStatus,$idServicio,$fecha,$idResponsable,$idTipo,$fechaMes,$data['permiso'][5]->activo,$idZona,'asc',$idEstatus,$idPromotor,$fechaFin,$idCampana,$idPrograma,$idFuente);
		
		
		#$data['idCliente']		= $idCliente;
		$data['idStatus']		= $idStatus;
		$data['idResponsable']	= $idResponsable;
		$data['idServicio']		= $idServicio;
		$data['idTipo']			= $idTipo;
		$data['fecha']			= $fecha;
		$data['inicio']  		= $limite+1;
		$data['fechaMes']		= $fechaMes;
		$data['idZona']			= $idZona;
		$data['idEstatus']		= $idEstatus;
		$data['idPromotor']		= $idPromotor;
		$data['criterioSeccion']		= $criterioSeccion;
		$data['registros']		= $registros;
		$data['idCampana']		= $idCampana;
		$data['numeroSeguimientos']		= $numeroSeguimientos;
		$data['idPrograma']		= $idPrograma;
		$data['idFuente']		= $idFuente;
		
		$data['idUsuario']		= $this->_iduser;
		$data['idRol']			= $this->_role;
		
		#$data['idPromotor']		= $idPromotor;

		$this->load->view("clientes/prospectos/obtenerProspectos",$data);
	}
	
	public function bajas($criterio='prospectos')
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
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

		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['servicios']		= $this->configuracion->obtenerServicios(1);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['criterio']		= $criterio;
		$data["breadcumb"]		= 'Bajas';
		$data['idRol']			= $this->_role;
		
		$this->load->view("clientes/prospectos/bajas/index",$data);
		$this->load->view("pie",$Data);
	}
	
	
	public function obtenerBajas($limite=0)
	{
		$criterio			= $this->input->post('criterio');
		$idPromotor			= $this->input->post('idPromotor');
		$idPrograma			= $this->input->post('idPrograma');
		$idCampana			= $this->input->post('idCampana');
		$idCausa			= $this->input->post('idCausa');
		$inicio				= $this->input->post('inicio');
		$fin				= $this->input->post('fin');
		$idFuente			= $this->input->post('idFuente');
		$idDetalle			= $this->input->post('idDetalle');

		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoVenta']	= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']	= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}
				
		#----------------------------------PAGINACION------------------------------------#
		$url		= base_url()."clientes/obtenerBajas/";
		$registros	= $this->clientes->contarBajas($criterio,$data['permiso'][17]->activo,$idCampana,$idPrograma,$idPromotor,$inicio,$fin,$idCausa,$idFuente,$idDetalle);
		$numero		= 20;
		$links		= 5;
		$uri		= 3;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		#---------------------------------------------------------------------------------#
		
		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['clientes']		= $this->clientes->obtenerBajas($numero,$limite,$criterio,$data['permiso'][17]->activo,$idCampana,$idPrograma,$idPromotor,$inicio,$fin,$idCausa,$idFuente,$idDetalle);
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][17]->activo);
		$data['programas']		= $this->configuracion->obtenerProgramas();
		$data['campanas']		= $this->configuracion->obtenerCampanas();
		$data['causas']			= $this->configuracion->obtenerCausas();
		$data['detalles']		= $this->catalogos->obtenerRegistrosCausaBajas();
		$data['fuentes']		= $this->clientes->obtenerFuentesContacto();
		$data['idPrograma']		= $idPrograma;
		$data['idCampana']		= $idCampana;
		$data['idCausa']		= $idCausa;
		$data['inicio']  		= $limite+1;
		$data['idPromotor']		= $idPromotor;
		$data['idFuente']		= $idFuente;
		$data['registros']		= $registros;
		$data['idDetalle']		= $idDetalle;

		$this->load->view("clientes/prospectos/bajas/obtenerBajas",$data);
	}
	
	public function excelBajas()
	{
		$criterio			= $this->input->post('criterio');
		$idPromotor			= $this->input->post('idPromotor');
		$idPrograma			= $this->input->post('idPrograma');
		$idCampana			= $this->input->post('idCampana');
		$idCausa			= $this->input->post('idCausa');
		$inicio				= $this->input->post('inicio');
		$fin				= $this->input->post('fin');
		$idFuente			= $this->input->post('idFuente');
		$idDetalle			= $this->input->post('idDetalle');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['clientes']		= $this->clientes->obtenerBajas(0,0,$criterio,$data['permiso'][17]->activo,$idCampana,$idPrograma,$idPromotor,$inicio,$fin,$idCausa,$idFuente,$idDetalle);

		$this->load->view('clientes/prospectos/bajas/excelBajas',$data);
	}
	
	public function seguimientosDiarios()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];

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
		#$data['seguimientos']		= $this->crm->obtenerSeguimientoDiario($this->_role!=1?$this->_iduser:0,$data['permiso'][17]->activo);
		$data['seguimientos']		= $this->crm->obtenerSeguimientoDiario($data['permiso'][17]->activo==0?$this->_iduser:0,$data['permiso'][17]->activo);
		$data['preinscritos']		= $this->crm->obtenerPreinscritosVigentes();
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['idUsuario']			= $this->_role!=1?$this->_iduser:0;
		$data['alertasPasado']		= null;
		
		if($data['seguimientos']==null)
		{
			$data['alertasPasado']		= $this->crm->obtenerSeguimientoAlertaPasadoFechas();
		}
		
		$data["breadcumb"]			= 'Seguimientos diarios';
		
		$this->load->view("clientes/prospectos/seguimientosDiarios/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerSeguimientosDiarios()
	{
		$fecha			= $this->input->post('fecha');
		$idCampana		= $this->input->post('idCampana');
		$permiso		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol'));
		
		if($fecha==$this->fecha)
		{
			$data['seguimientos']		= $this->crm->obtenerSeguimientoDiario($this->input->post('idPromotor'),$permiso[17]->activo,$idCampana);
		}
		
		if($fecha>$this->fecha)
		{
			$data['seguimientos']		= $this->crm->obtenerSeguimientoDiarioFecha($this->input->post('idPromotor'),$fecha,$permiso[17]->activo,$idCampana);
		}
		
		if($fecha<$this->fecha)
		{
			$data['seguimientos']		= $this->crm->obtenerSeguimientoDiarioFechaAtrasado($this->input->post('idPromotor'),$fecha,$permiso[17]->activo,$idCampana);
		}
		
		
		
		$data['fecha']				= $fecha;
		
		$this->load->view('clientes/prospectos/seguimientosDiarios/obtenerSeguimientosDiarios',$data);
	}
	
	
	public function obtenerSeguimientoDiario()
	{
		$idSeguimiento				= $this->input->post('idSeguimiento');
		$idCliente					= $this->input->post('idCliente');
		
		if($idSeguimiento>0)
		{
			$data['seguimiento']		= $this->clientes->obtenerSeguimiento($idSeguimiento);
			$idCliente					= $data['seguimiento']->idCliente;
			
			$data['cliente']			= $this->clientes->obtenerCliente($idCliente);
		}
		else
		{
			$data['cliente']			= $this->clientes->obtenerCliente($idCliente);
			
			$seguimientoInicial	= array();
			
			$seguimientoInicial['fecha']				= $this->_fechaActual;
			$seguimientoInicial['comentarios']			= '';
			$seguimientoInicial['fechaCierre']			= $this->_fechaActual;
			$seguimientoInicial['horaCierreFin']		= date('H:i:s');
			$seguimientoInicial['idStatus']				= 4;
			$seguimientoInicial['idEstatus']			= 4;
			$seguimientoInicial['tipo']					= 1;
			$seguimientoInicial['idServicio']			= 1;
			$seguimientoInicial['folio']				= $this->crm->obtenerFolioSeguimientoCliente(1);
			
			$seguimientoInicial['idResponsable']		= $data['cliente']->idPromotor;
			$seguimientoInicial['idUsuarioRegistro']	= $data['cliente']->idPromotor;
			$seguimientoInicial['idCliente']			= $idCliente;
			$seguimientoInicial['idContacto']			= $data['cliente']->idContacto;
			
			$idSeguimiento				= $this->importar->registrarSeguimientoInicialCrm($seguimientoInicial);
			
			$data['seguimiento']		= $this->clientes->obtenerSeguimiento($idSeguimiento);
		}
		
		
		$data['estatus']			= $this->configuracion->obtenerEstatus($data['seguimiento']->tipo);
		
		$data['academicos']			= $this->clientes->obtenerAcademicoCliente($idCliente);
		$data['programa']			= $this->configuracion->obtenerProgramasEditar($data['academicos']!=null?$data['academicos']->idPrograma:0);
		$data['detalles']			= $this->crm->obtenerDetallesSeguimientoFechas($data['seguimiento']->idSeguimiento,date('Y-m-d'));
		$data['numero']				= $this->crm->contarDetalleEmbudo(2,$idCliente);
		$data['embudos']			= $this->configuracion->obtenerEmbudos($data['numero']);
		$data['contactado']			= $this->crm->contarDetallesMetodo(1,$idCliente);
		$data['sinRespuesta']		= $this->crm->contarDetallesMetodo(0,$idCliente);
		$data['ultimo']				= $this->crm->obtenerUltimoSeguimiento($idCliente);
		
		
		$data['campanas']			= $this->configuracion->obtenerCampanas();
		$data['programas']			= $this->configuracion->obtenerProgramas();
		$data['diplomados']			= $this->configuracion->obtenerProgramasGrado(4);
		$data['causas']				= $this->configuracion->obtenerCausas();
		$data['causasDetalles']		= $this->catalogos->obtenerRegistrosCausaBajas();
		$data['nocuali']			= $this->configuracion->obtenerNocuali();
		$data['nocualiDetalles']	= $this->catalogos->obtenerRegistrosCausaNocuali();
		$data['fuentes']			= $this->clientes->obtenerFuentesContacto();
		$data['periodos']			= $this->configuracion->obtenerPeriodos();
		$data['metodos']			= $this->configuracion->obtenerMetodos();
		$data['meses']				= $this->catalogos->obtenerMeses();
		
		#$data['alertas']			= $this->crm->obtenerSeguimientoAlerta();
		#$data['alertasPasado']		= $this->crm->obtenerSeguimientoAlertaPasado();
		$data['alertasPasado']		= $this->crm->obtenerSeguimientoAlertaPasadoFechas();
		$data['prospectos']			= $this->catalogos->obtenerClientesProspectos();
		
		#if($data['alertas']!=null)  $this->crm->procesarSeguimientosAlerta($data['alertas']);
		#if($data['alertasPasado']!=null)  $this->crm->procesarSeguimientosAlerta($data['alertasPasado']);
		
		$data['idSeguimiento']		= $idSeguimiento;
		$data['idRol']				= $this->_role;
		
		$this->load->view('clientes/prospectos/seguimientosDiarios/obtenerSeguimientoDiario',$data);
	}
	
	public function registrarDetalleSeguimientoFecha1()
	{
		if(!empty($_POST))
		{
			if($this->input->post('idZona')=='0')
			{
				echo $this->crm->registrarDetalleSeguimientoFecha();
			}
			
			if($this->input->post('idZona')>0)
			{
				//REGISTRAR EL SEGUIMIENTO ANTES DE DARLO DE BAJA
				$this->crm->registrarDetalleSeguimientoFecha(1);
				
				if($this->input->post('idZona')==2)
				{
					echo $this->crm->registrarBajaSeguimiento();
				}
				
				if($this->input->post('idZona')==8)
				{
					echo $this->crm->registrarNocuali();
				}
			}
			
			if($this->input->post('idZona')=='1')
			{
				echo $this->crm->registrarAlumnoSeguimiento();
			}
		}
		else
		{
			echo  "0";
		}
	}
	
	public function revisarMetodosActivos()
	{
		$metodos = $this->input->post('txtNumeroMetodos');
		
		for($i=0;$i<$metodos;$i++)
		{
			if($this->input->post('chkMetodo'.$i)>0)
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function registrarDetalleSeguimientoFecha()
	{
		
		if(!empty($_POST))
		{
			$cualificado	= $this->input->post('selectCualificado');
			$prospecto		= $this->input->post('txtProspectoActivo');
			
			if($prospecto!=1)
			{
				if($this->revisarMetodosActivos())
				{
					if($cualificado=='1' or $cualificado=='2')
					{
						if($this->input->post('selectInteresado')=='0')
						{
							$this->crm->registrarDetalleSeguimientoFecha(1);
							
							echo $this->crm->registrarDetalleNocuali();
						}
						
						if($this->input->post('selectInteresado')!='0')
						{
							echo $this->crm->registrarDetalleSeguimientoFecha();
						}
					}
					
					if($cualificado=='0')
					{
						$this->crm->registrarDetalleSeguimientoFecha(1);
						
						echo $this->crm->registrarNocuali();
					}
				}
				else
				{
					$this->crm->registrarDetalleBajaSeguimientoProspecto();
					
					echo $this->crm->registrarNocualiProspectoBaja();
				}
			}
			else
			{
				if($this->input->post('selectProspectos')==5)
				{
					$detalles		=  explode('|',$this->input->post('selectDetallesProspecto'));
					
					$this->crm->registrarDetalleSeguimientoProspecto(1);
					
					echo $this->crm->registrarDetalleNocualiProspecto($detalles[0]);
				}
				else
				{
					echo $this->crm->registrarDetalleSeguimientoProspecto();
				}
			}
		}
		else
		{
			echo  "0";
		}
	}
	
	public function editarDatosGenerales()
	{
		if(!empty($_POST))
		{
			echo $this->clientes->editarDatosGenerales();
		}
		else
		{
			echo  "0";
		}
	}
	
	public function revisarConexion()
	{
		echo 'La conexión esta activa';
	}

	//PROCESOS DE PRODUCCIÓN PARA INTEGRARLOS DIRECTAMENTE EN LA COTIZACIÓN
	//=================================================================================================
	
	public function procesosProduccion()
	{
		$procesos=$this->configuracion->obtenerProcesos();
		
		$i=1;
		
		if($procesos!=null)
		{
			echo'
			<table class="admintable" width="100%" style="margin-top:3px">
				<tr>
					<th colspan="3">Procesos de producción</th>
				</tr>
				<tr>
					<th>#</th>
					<th width="70%">Proceso</th>
					<th>Seleccionar</th>
				</tr>';
			
			foreach($procesos as $row)
			{
				$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
				echo'
				<tr '.$estilo.'>
					<td>'.$i.'</td>
					<td>'.$row->nombre.'</td>
					<td align="center">
						<input type="checkbox" id="chkProceso'.$i.'" value="'.$row->idProceso.'" />
					</td>
				</tr>';
				$i++;
			}
			
			echo'</table>';
		}
		else
		{
			echo '<div class="Error_validar" style=" width:97%; margin-top:10px; margin-bottom: 5px;">
			Puede registrar mas procesos de producción en la configuración</div><br />';
		}
		
		echo'<input type="hidden" id="txtIndiceProcesos" value="'.$i.'" />';
	}

	#========================================================================================================#
	#=========================================CRITERIOS DE ORDENANAMIENTO====================================#
	#========================================================================================================#
	
	public function ordenamiento($criterio)
	{
		$this->session->set_userdata('criterioClientes',$criterio);
		$this->session->set_userdata('criterioIdClientes','');
		
		redirect('clientes','refresh');
	}
	
	public function ordenamientoProspectos($criterio)
	{
		$this->session->set_userdata('criterioProspectos',$criterio);
		
		redirect('clientes/prospectos','refresh');
	}
	
	#========================================================================================================#
	#=============================================  SEGUIMIENTO  ============================================#
	#========================================================================================================#
	
	public function seguimiento($idCliente)
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['jFicha_cliente']	=$this->_jss['jFicha_cliente'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='clientes'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']=$this->configuracion->obtenerPermisosBoton('1',$this->session->userdata('rol'));
		
		if($data['permiso']->leer=='0' and $data['permiso']->escribir=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$Datos					=$this->clientes->getNameCliente($idCliente);
		
		$data["dclientes"]		=$this->clientes->getNameCliente($idCliente);
		$data["seguimientos"]	=$this->clientes->obtenerSeguimientoCliente($idCliente);
		$data["cliente"]		=$this->clientes->obtenerCliente($idCliente);
		$data["IDC"]			=$idCliente;
		
		$this->load->view("clientes/seguimiento",$data);
		$this->load->view("pie",$Data);
	}
	
	public function agregarSeguimiento()
	{
		if(!empty($_POST))
		{
			$seguimiento=$this->clientes->agregarSeguimiento();
			
			$seguimiento=="1"?
				$this->session->set_userdata('notificacion','El seguimiento se ha registrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al registrar el seguimiento');
				
			echo $seguimiento;
		}
	}
	
	public function registrarSeguimiento()
	{
		if(!empty($_POST))
		{
			error_reporting(0);
			
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->clientes->agregarSeguimiento());
		}
		else
		{
			return array('0',errorRegistro);
		}
	}
	
	public function editarSeguimientoCrm()
	{
		if(!empty($_POST))
		{
			error_reporting(0);
			
			echo $this->clientes->editarSeguimientoCrm();
		}
		else
		{
			echo "0";
		}
	}
	
	public function confirmarSeguimiento()
	{
		if(!empty($_POST))
		{
			$seguimiento=$this->clientes->confirmarSeguimiento();
			
			$seguimiento=="1"?
				$this->session->set_userdata('notificacion','El seguimiento se ha confirmado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al confirmar el seguimiento');
				
			echo $seguimiento;
		}
	}
	
	
	public function confirmarmandoSeguimiento()
	{
		if(!empty($_POST))
		{
			$seguimiento=$this->clientes->confirmarSeguimiento();
			echo $seguimiento;
		}
	}

	#========================================================================================================#
	#=============================================AUTOCOMPLETADOS============================================#
	#========================================================================================================#

	public function prebusqueda($idCliente)
	{
		if($idCliente=="nada")
		{
			$idCliente='';
		}
		
		$this->session->set_userdata('idClienteBusqueda',$idCliente);
		$this->session->set_userdata('idStatusBusqueda','');
		$this->session->set_userdata('idServicioBusqueda','');
		$this->session->set_userdata('fechaSeguimiento','');
		$this->session->unset_userdata('idStatusErp');
		$this->session->unset_userdata('idStatusPw');
		$this->session->unset_userdata('idResponsable');
		$this->session->unset_userdata('idTipoBusqueda');
		
		redirect('clientes','refresh');
	}

	public function prebusquedaCotizacion($idCotizacion)
	{
		if($idCotizacion=="nada")
		{
			$idCotizacion='';
		}
		
		$this->session->set_userdata('idSerieCotizacion',$idCotizacion);
		
		redirect('clientes/ficha','refresh');
	}
	
	public function prebusquedaVentas($idCotizacion)
	{
		if($idCotizacion=="nada")
		{
			$idCotizacion='';
		}
		
		$this->session->set_userdata('idVentaCliente',$idCotizacion);
		
		redirect('clientes/ventas','refresh');
	}

	public function registrarCliente()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$cliente	= $this->clientes->registrarCliente();
			#$cliente[0]=="1"?$this->session->set_userdata('notificacion','Se ha registrado correctamente al cliente'):'';
			
			echo json_encode($cliente);
		}
		else
		{
			echo json_encode(array('0',errorRegistro));
		}
	}
	
	public function editarCliente()
	{
		if(!empty($_POST))
		{
			$cliente=$this->clientes->editarCliente();
			#$cliente=="1"?$this->session->set_userdata('notificacion','Se ha editado correctamente el registro del cliente'):'';
			
			echo $cliente;
		}
	}
	
	public function obtenerMapa()
	{
		$cliente	=$this->clientes->obtenerDatosCliente($this->input->post('idCliente'));
		
		$this->load->library('googlemaps');
		
		$pais		=$cliente->pais=='México'?"Mexico":$cliente->pais;
		
		#$config['center'] 				= $cliente->numero.', '.$cliente->calle.', '.$cliente->localidad.', '.$cliente->municipio.', '.$cliente->estado.', '.$pais.', '.$cliente->codigoPostal;
		$config['center'] 				= $cliente->latitud.', '.$cliente->longitud;
		$config['zoom'] 				= '13';
		$config['loadAsynchronously'] 	= true;
		$config['https'] 				= true;
		$config['map_height'] 			= '540px';
		$config['map_width'] 			= '983px';
		$config['posicionY'] 			= '1%';
		$config['posicionX'] 			= '0%';
		$config['posicion'] 			= 'absolute';
		$config['map_div_id'] 			= 'mapaClientes';

		$this->googlemaps->initialize($config);
		
		$marker['icon'] 				= 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|9999FF|000000';
		$marker['position'] 			= $config['center'];
		$this->googlemaps->add_marker($marker);
		$map 							= $this->googlemaps->create_map();

		echo $map['js'];
		echo $map['html'];
		
		 echo'
		<script>
		$(document).ready(function()
		{
			loadScript();
		});
		</script>';
	}
	
	public function obtenerCliente()
	{
		$idCliente				= $this->input->post('idCliente');
		$data['cliente']		= $this->clientes->obtenerCliente($idCliente);
		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['fuentes']		= $this->clientes->obtenerFuentesContacto();
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['cuentas']		= $this->clientes->obtenerCuentasCliente($idCliente);
		$data['metodos']		= $this->configuracion->obtenerMetodosPago();
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro(1);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['licencias']		= $this->configuracion->obtenerLicenciasActivas();
		$data['clienteSucursal']= $this->clientes->comprobarClienteSucursal();
		$data['sucursalCliente']= $this->clientes->obtenerClienteSucursal($idCliente);
		$data['regimen']		= $this->configuracion->obtenerRegimenFiscal();
		$data['registroSucursal']= $this->clientes->comprobarClienteSucursalRegistro($idCliente);
		
		$data['venta']			= null;
		
		$data['tipoRegistro']	= $this->input->post('tipoRegistro');
		
		$data['idCliente']		= $idCliente;
		$data['idLicencia']		= $this->idLicencia;
		
		if(sistemaActivo=='olyess')
		{
			$data['direcciones']		= $this->clientes->obtenerDireccionesEntrega($idCliente);
			
			if($data['direcciones']==null)
			{
				$this->clientes->registrarDireccionesNuevas($idCliente);
				$data['direcciones']		= $this->clientes->obtenerDireccionesEntrega($idCliente);
			}
		}
		
		$this->load->library('googlemaps');
		
		
		$this->load->view('clientes/registro/obtenerCliente',$data);
	}
	
	public function obtenerDatosFiscales()
	{
		$idCliente				= $this->input->post('idCliente');
		$data['cliente']		= $this->clientes->obtenerDatosCliente($idCliente);
		$data['idCliente']		= $idCliente;

		$this->load->view('clientes/registro/obtenerDatosFiscales',$data);
	}
	
	public function editarDatosFiscales()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->clientes->editarDatosFiscales());
		}
		else
		{
			echo json_encode("0");
		}
	}
	
	
	public function obtenerDireccionesCliente1()
	{
		$idCliente					= $this->input->post('idCliente');
		
		$data['direcciones']		= $this->clientes->obtenerDireccionesEntrega($idCliente);
			
		if($data['direcciones']==null)
		{
			$this->clientes->registrarDireccionesNuevas($idCliente);
			$data['direcciones']		= $this->clientes->obtenerDireccionesEntrega($idCliente);
		}
		
		$data['idCliente']		= $idCliente;

		$this->load->view('clientes/registro/obtenerDireccionesCliente',$data);
	}
	
	public function editarDireccionesCliente()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->clientes->editarDireccionesEntrega($this->input->post('idCliente')));
		}
		else
		{
			echo json_encode("0");
		}
	}
	
	function busquedaZona($filtro)
	{
		$consulta=$filtro;
		
		if($filtro=='nada')
		{
			$filtro="";
			$$consulta="";
			$this->session->set_userdata("nombreZona",$filtro);
		}
		
		$this->session->set_userdata("nombreZona",$filtro);
		$this->session->set_userdata('busquedaCliente','');

		redirect('clientes/index','refresh');
	}
	
	function borrarCliente()
	{
		if(!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			/*$data['permiso']			= $this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				redirect('principal/permisosUsuario','refresh');
				return;
			}*/
		
			echo $this->clientes->borrarCliente($this->input->post('idCliente'));
		}
		else
		{
			echo "0";
		}
	}

	public function cotizaciones($idCliente=0,$limite=0,$idCotizacion=0)
	{		
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			= $this->_csstyle["cassadmin"];
		$Data['csmenu']				= $this->_csstyle["csmenu"];
		$Data['csvalidate']			= $this->_csstyle["csvalidate"];
		$Data['csui']				= $this->_csstyle["csui"];
		$Data['nameusuario']		= $this->modelousuario->getUsuarios($this->_iduser);  
		$Data['Fecha_actual']		= $this->_fechaActual;
		$Data['Jry']				= $this->_jss['jquery'];
		$Data['Jqui']				= $this->_jss['jqueryui'];
		$Data['Jquical']			= $this->_jss['jquerycal'];
		$Data['permisos']			= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			= 'clientes';
		$Data['conectados']			= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		$data['permisoVenta']		= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoContacto']	= $this->configuracion->obtenerPermisosBoton('6',$this->session->userdata('rol'));
		$data['permisoFactura']		= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		$data['permisoCrm']			= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		#----------------------------------PAGINACION------------------------------------#
		$url		= base_url()."clientes/cotizaciones/".$idCliente.'/';
		$registros	= $this->clientes->contarClienteCotizaciones($idCotizacion,$idCliente);
		$numero		= 25;
		$links		= 5;
		$uri		= 4;
		
		$paginador	= $this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		#---------------------------------------------------------------------------------#

		$data['cotizaciones'] 	= $this->clientes->obtenerClienteCotizaciones($numero,$limite,$idCotizacion,$idCliente);
		$data['totalRemision'] 	= $this->clientes->obtenerTotalRemisiones();
		$data["cliente"]		= $this->clientes->obtenerCliente($idCliente);
 		$data["idCliente"]		= $idCliente;
		$data["idCotizacion"]	= $idCotizacion;
		
		$data["breadcumb"]		= '<a href="'.base_url().'clientes">Clientes</a> > <a href="'.base_url().'clientes/index/'.$idCliente.'">'.substr($data['cliente']->empresa,0,300).'</a> > Cotizaciones';
		
		$this->load->view("clientes/cotizacion/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function busquedaClienteVenta($idCliente,$idCotizacion)
	{
		$this->session->set_userdata('idClienteFicha',$idCliente);
		
		redirect('clientes/ventas/0/'.$idCotizacion,'refresh');
	}
	
	public function ventas($idCliente=0,$idCotizacion=0)
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			= $this->_csstyle["cassadmin"];
		$Data['csmenu']				= $this->_csstyle["csmenu"];
		$Data['csvalidate']			= $this->_csstyle["csvalidate"];
		$Data['csui']				= $this->_csstyle["csui"];
		$Data['nameusuario']		= $this->modelousuario->getUsuarios($this->_iduser);  
		$Data['Fecha_actual']		= $this->_fechaActual;
		$Data['Jquical']			= $this->_jss['jquerycal'];
		$Data['Jry']				= $this->_jss['jquery'];
		$Data['Jqui']				= $this->_jss['jqueryui'];
		#$Data['jFicha_cliente']		= $this->_jss['jFicha_cliente'];
		$Data['permisos']			= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			= 'clientes';
		$Data['conectados']			= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoCotizacion']	= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		$data['permisoContacto']	= $this->configuracion->obtenerPermisosBoton('6',$this->session->userdata('rol'));
		$data['permisoFactura']		= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data["cliente"]		= $this->clientes->obtenerCliente($idCliente);
		$data["idCliente"]		= $idCliente;
		$data["mostrarMenu"]	= true;
		$data["seccion"]		= 'ventasClientes';
		$data["idCotizacion"]	= $idCotizacion;
		
		
		$data["breadcumb"]		= '<a href="'.base_url().'clientes">Clientes</a> > <a href="'.base_url().'clientes/index/'.$idCliente.'">'.substr($data['cliente']->empresa,0,300).'</a> > Ventas';
		
		$this->load->view("ventas/catalogo/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerVentas($limite=0)
	{
		set_time_limit(0); 
		
		$criterio					= $this->input->post('criterio');
		$inicio						= $this->input->post('inicio').' 00:00:00';
		$fin						= $this->input->post('fin').' 23:59:59';
		$idCliente					= $this->input->post('idCliente');
		$idCotizacion				= $this->input->post('idCotizacion');
		$idFactura					= $this->input->post('idFactura');
		$ordenVentas				= $this->input->post('ordenVentas');
		$idEstacion					= $this->input->post('idEstacion');
		$traspasos					= $this->input->post('traspasos');
		$saldo						= $this->input->post('saldo');
		
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoCrm']			= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');

			return;
		}
		
		#----------------------------------PAGINACION------------------------------------#
		$url				= base_url()."clientes/obtenerVentas/";
		$registros			= $this->clientes->contarCotizacionesClientes($criterio,$inicio,$fin,$idCliente,$idCotizacion,$idFactura,$data['permiso'][4]->activo,$idEstacion,$traspasos,$saldo);
		$numero				= 25;
		$links				= 5;
		$uri				= 3;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		#---------------------------------------------------------------------------------#
		
		$data['ventas'] 		= $this->clientes->obtenerVentasClientes($numero,$limite,$criterio,$inicio,$fin,$idCliente,$idCotizacion,$idFactura,$ordenVentas,$data['permiso'][4]->activo,$idEstacion,$traspasos,$saldo);
		$data['totales']		= $data['permiso'][6]->activo==1?$this->clientes->sumarVentasClientes($criterio,$inicio,$fin,$idCliente,$idCotizacion,$idFactura,$data['permiso'][4]->activo,$idEstacion,$traspasos,$saldo):null;
		$data['facturas'] 		= $this->clientes->obtenerFacturaCotizaciones($criterio,$inicio,$fin,$idCliente,$idCotizacion,0,$idEstacion,$traspasos,$saldo);
		$data['clientes'] 		= $this->clientes->obtenerVentasRegistroClientes($numero,$limite,'',$inicio,$fin,$idCliente,0,0,'desc',$data['permiso'][4]->activo,$idEstacion,$traspasos,$saldo);
		$data['estaciones']		= $this->estaciones->obtenerRegistros();
		$data['idCliente'] 		= $idCliente;
		$data['idCotizacion'] 	= $idCotizacion;
		$data['idFactura'] 		= $idFactura;
		$data['ordenVentas']	= $ordenVentas;
		$data['idEstacion']		= $idEstacion;
		$data['seccion']		= $this->input->post('seccion');
		$data['saldo']			= $saldo;
		$data['limite']			= $limite+1;
		
		$this->load->view("ventas/catalogo/obtenerVentas",$data);
	}

	public function registrarContactoCliente()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->clientes->registrarContactoCliente());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}

	public function editar_contacto($id)
	{
		$Data['title']= "Panel de Administración";
		$Data['cassadmin']=$this->_csstyle["cassadmin"];
		$Data['csmenu']=$this->_csstyle["csmenu"];
		$Data['csvalidate']=$this->_csstyle["csvalidate"];
		
		$Data['nameusuario']=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']=$this->_fechaActual;
		
		$Data['Jry']=$this->_jss['jquery'];
		$Data['jvalidate']=$this->_jss['jvalidate'];
		$Data['permisos']=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		$Conte['Categoria']=$this->uri->segment(1);
		$Conte['Serie']=$this->clientes->getGeneraSerieID();
		
		$Conte['contactos']=$this->clientes->get_contacto($id);
		//$Conte['u']=$id;
		//echo $Conte['contactos'];
		$this->load->view("clientes/contactos/editar",$Conte);
		
		$this->load->view("pie",$Data);
	}

	public function numberFormat()
	{
		$Retorna="";
		
		if(!empty($_POST))
		{
			$Retorna=number_format(floatval($this->input->post('numero')),$this->input->post('decimal'));
		}
		else
		{
			$Retorna="0";
		}
		
		print($Retorna);
	}//numberFormat

	public function FechActual()
	{
		$T=$this->input->post('Fecha');
		
		switch($T)
		{
			case "n": print(date("d-m-Y")); break;
			case "m": print(date("Y-m-d")); break;
		}//switch
	}
	
	//SEGUIMIENTO A CLIENTES 
	//=============================================================================================================
	
	public function obtenerSeguimientoEditar()
	{
		$idSeguimiento				= $this->input->post('idSeguimiento');
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoContactos']	= $this->configuracion->obtenerPermisosBoton('6',$this->session->userdata('rol'));
		$data['seguimiento']		= $this->clientes->obtenerSeguimiento($idSeguimiento);
		$data['status']				= $this->configuracion->obtenerStatus(1);
		$data['estatus']			= $this->configuracion->obtenerEstatus($data['seguimiento']->tipo);
		
		
		#$data['responsablesRegistro']	= $this->configuracion->obtenerResponsables($data['permiso'][4]->activo,$data['seguimiento']->idResponsable);
		$data['responsablesRegistro']	= $this->configuracion->obtenerResponsables($data['permiso'][4]->activo,$data['seguimiento']->idResponsable);
		$data['responsables']			= $this->configuracion->obtenerResponsables();
		
		$data['servicios']			= $this->configuracion->obtenerServicios(1);
		$data['tiempos']			= $this->configuracion->obtenerTiempos();
		$data['contactos']			= $this->clientes->obtenerContactos($data['seguimiento']->idCliente,$data['permisoContactos'][4]->activo);
		$data['cliente']			= $this->clientes->obtenerCliente($data['seguimiento']->idCliente);
		$data['idSeguimiento']		= $idSeguimiento;
		$data['idRol']				= $this->_role;
		
		if($data['seguimiento']->tipo=='0')
		{
			$this->load->view('clientes/seguimiento/obtenerSeguimientoEditar',$data);
		}
		else
		{
			$this->load->view('clientes/seguimiento/obtenerSeguimientoEditarProspectos',$data);
		}
	}
	
	public function seguimientoClientes($limite=0)
	{
		$idCliente				= $this->input->post('idCliente');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$tipo					= $this->input->post('tipo');
		
		$Pag["base_url"]		= base_url()."clientes/seguimientoClientes/";
		$Pag["total_rows"]		= $this->clientes->contarSeguimientoCliente($idCliente,$inicio,$fin,$data['permiso'][4]->activo,$tipo);//Total de Registros
		$Pag["per_page"]		= 10;
		$Pag["num_links"]		= 5;
		$this->pagination->initialize($Pag);
		
		$data['idCliente']		= $this->input->post('idCliente');
		$data['seguimientos']	= $this->clientes->obtenerSeguimientoCliente($Pag["per_page"],$limite,$idCliente,$inicio,$fin,$data['permiso'][4]->activo,$tipo);
		$data['cliente']		= $this->clientes->obtenerCliente($data['idCliente']);
		
		if(sistemaActivo=='IEXE')
		{
			if($tipo=='0')
			{
				$this->load->view('clientes/seguimiento/seguimientoClientesIexe',$data);
			}
			else
			{
				$this->load->view('clientes/seguimiento/seguimientoClientesProspectos',$data);
			}
			
		}
		else
		{
			$this->load->view('clientes/seguimiento/seguimientoClientes',$data);
		}
	} 
	
	public function formularioSeguimiento()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('6',$this->session->userdata('rol'));
		
		$data['status']			= $this->configuracion->obtenerStatus(1);
		$data['estatus']		= $this->configuracion->obtenerEstatus($this->input->post('tipo'));
		$data['servicios']		= $this->configuracion->obtenerServicios(1);
		$data['promotores']		= $this->configuracion->obtenerResponsables(0,0,1);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['tiempos']		= $this->configuracion->obtenerTiempos();
		$data['contactos']		= $this->clientes->obtenerContactos($this->input->post('idCliente'),$data['permiso'][4]->activo);
		$data['folio']			= obtenerFolioSeguimiento($this->crm->obtenerFolioSeguimientoCliente($this->input->post('tipo')));
		$data['idCliente']		= $this->input->post('idCliente');
		$data['tipo']			= $this->input->post('tipo');
		$data['idRol']			= $this->_role;
		
		if($data['tipo']=='0')
		{
			$this->load->view('clientes/seguimiento/formularioSeguimiento',$data);
		}
		else
		{
			$data['cliente']		= $this->clientes->obtenerCliente($this->input->post('idCliente'));
			$data['imagen']			= rand(1000000,9999999);
			
			$this->load->view('clientes/seguimiento/formularioSeguimientoProspectos',$data);
		}
	}
	
	public function obtenerSeguimiento()
	{
		if(!empty($_POST))
		{
			$idSeguimiento			= $this->input->post('idSeguimiento');
			$data['idSeguimiento']	= $this->input->post('idSeguimiento');
			$data['seguimiento']	= $this->clientes->obtenerSeguimiento($idSeguimiento);
			$data['detalles']		= $this->crm->obtenerDetallesSeguimiento($idSeguimiento);
			
			if($data['seguimiento']!=null)
			{
				$data['contacto']		= $this->clientes->obtenerContacto($data['seguimiento']->idContacto);
				$data['archivos']		= $this->clientes->obtenerArchivosSeguimiento($idSeguimiento);
			}
			
			if($data['seguimiento']->tipo=='0')
			{
				$this->load->view('clientes/seguimiento/obtenerSeguimiento',$data);
			}
			else
			{
				$this->load->view('clientes/seguimiento/obtenerSeguimientoProspecto',$data);
			}
			
			
		}
		else
		{
			echo 'Error al obtener el formulario';
		}
	}

	#SEGUIMIENTO ERP
	public function formularioErp()
	{
		$data['status']			=$this->configuracion->obtenerStatus(1);
		$data['responsables']	=$this->configuracion->obtenerResponsables();
		
		$this->load->view('clientes/seguimiento/formularioErp',$data);
	}
	
	public function registrarSeguimientoErp()
	{
		if(!empty($_POST))
		{
			$seguimiento=$this->clientes->registrarSeguimientoErp();
			echo $seguimiento;
		}
	}
	
	public function obtenerErp()
	{
		$idSeguimiento				=$this->input->post('idSeguimiento');
		$data['idSeguimiento']		=$idSeguimiento;
		$data['status']				=$this->configuracion->obtenerStatus(1);
		$data['seguimiento']		=$this->clientes->obtenerErpSeguimiento($idSeguimiento);
		$data['responsables']		=$this->configuracion->obtenerResponsables();
		
		$this->load->view('clientes/seguimiento/obtenerErp',$data);
	}
	
	public function editarSeguimientoErp()
	{
		if(!empty($_POST))
		{
			echo $this->clientes->editarSeguimientoErp();
		}
		else
		{
			echo  "0";
		}
	}
	
	public function borrarSeguimientoErp()
	{
		if(!empty($_POST))
		{
			echo $this->clientes->borrarSeguimientoErp($this->input->post('idSeguimiento'));
		}
		else
		{
			echo  "0";
		}
	}
	
	public function seguimientoErp()
	{
		$idCliente				=$this->input->post('idCliente');
		$data['idCliente']		=$idCliente;
		$data['seguimientos']	=$this->clientes->obtenerSeguimientoErp($idCliente);
		$data['permiso']		=$this->configuracion->obtenerPermisosBoton('1',$this->session->userdata('rol'));
		
		$this->load->view('clientes/seguimiento/seguimientoErp',$data);
	}

	//QUITAR LAS NOTIFICACIONES DE SERVICIO
	public function quitarNotificacion()
	{
		if(!empty($_POST))
		{
			$notificacion=$this->clientes->quitarNotificacion();
			echo $notificacion;
		}
	}
	
	public function obtenerNotas()
	{
		$idCliente				=$this->input->post('idCliente');
		$data['idCliente']		=$idCliente;
		$data['notas']			=$this->clientes->obtenerNotas($idCliente);
		$data['permiso']		=$this->configuracion->obtenerPermisosBoton('1',$this->session->userdata('rol'));

		$this->load->view('clientes/seguimiento/obtenerNotas',$data);
	}
	
	public function formularioRegistrarNota()
	{
		$data['responsables']	=$this->configuracion->obtenerResponsables();
		
		$this->load->view('clientes/seguimiento/formularioRegistrarNota',$data);
	}
	
	public function registrarNota()
	{
		if(!empty($_POST))
		{
			$nota	=$this->clientes->registrarNota();
			echo $nota;
		}
	}
	
	public function editarNota()
	{
		if(!empty($_POST))
		{
			$nota	=$this->clientes->editarNota();
			echo $nota;
		}
	}
	
	public function obtenerNota()
	{
		$idNota					=$this->input->post('idNota');
		$data['idNota']			=$idNota;
		$data['nota']			=$this->clientes->obtenerNota($idNota);
		$data['responsables']	=$this->configuracion->obtenerResponsables();
		
		$this->load->view('clientes/seguimiento/obtenerNota',$data);
	}
	
	public function borrarNota()
	{
		if(!empty($_POST))
		{
			$idNota			=$this->input->post('idNota');
			$nota			=$this->clientes->borrarNota($idNota);
			echo $nota;
		}
	}

	public function obtenerFicheros()
	{
		$idCliente			= $this->input->post('idCliente');
		$data['idCliente']	= $idCliente;
		$data['ficheros']	= $this->clientes->obtenerFicheros($idCliente);
		$data['cuota']		= $this->cuota;
		
		$this->load->view('clientes/ficheros/obtenerFicheros',$data);
	}
	
	public function subirFicheros($idCliente=0)
	{
		if (!empty($_FILES)) 
		{
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('jpg','jpeg','gif','png','tif','bmp','pdf','doc','docx','xls','xlsx','txt','rar','zip','xps','oxps','xml','mp4');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				$idFichero	= $this->clientes->subirFicheros($idCliente,$_FILES['file']['name'],$_FILES['file']['size']);
				
				if($idFichero>0)
				{
					move_uploaded_file($archivoTemporal,carpetaClientes.$idFichero.'_'.$_FILES['file']['name']);

					if(file_exists(carpetaClientes.$idFichero.'_'.$_FILES['file']['name']))
					{
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

	public function borrarFichero()
	{
		if(!empty($_POST))
		{
			$idFichero	=$this->input->post('idFichero');
			$fichero	=$this->clientes->borrarFichero($idFichero);
			echo $fichero;
		}
	}
		
	function descargarFichero($idFichero) #Descargar el archivo XML
	{
		$this->load->helper('download');

		$fichero	= $this->clientes->obtenerFichero($idFichero);
		$archivo 	= $fichero->idFichero.'_'.$fichero->nombre;
		$data 		= file_get_contents(carpetaClientes.$archivo); 
		
		force_download($fichero->nombre, $data); 
	}
	
	
	public function obtenerProyectos()
	{
		$idCliente				= $this->input->post('idCliente');
		$data['idCliente']		= $idCliente;
		$data['proyectos']		= $this->clientes->obtenerProyectos($idCliente);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('1',$this->session->userdata('rol'));

		$this->load->view('clientes/seguimiento/obtenerProyectos',$data);
	}
	
	public function obtenerProyecto()
	{
		$idSeguimiento			=$this->input->post('idSeguimiento');
		$data['idSeguimiento']	=$idSeguimiento;
		$data['status']			=$this->configuracion->obtenerStatus(3);
		$data['responsables']	=$this->configuracion->obtenerResponsables();
		$data['seguimiento']	=$this->clientes->obtenerSeguimiento($idSeguimiento);
		
		$this->load->view('clientes/seguimiento/obtenerProyecto',$data);
	}
	
	#SEGUIMIENTO PROYECTO
	public function formularioProyectos()
	{
		$data['status']			=$this->configuracion->obtenerStatus(3);
		$data['responsables']	=$this->configuracion->obtenerResponsables();
		
		$this->load->view('clientes/seguimiento/formularioProyectos',$data);
	}
	
	public function registrarProyecto()
	{
		if(!empty($_POST))
		{
			$proyecto=$this->clientes->registrarProyecto();
			echo $proyecto;
		}
	}
	
	public function editarProyecto()
	{
		if(!empty($_POST))
		{
			$proyecto=$this->clientes->editarProyecto();
			echo $proyecto;
		}
	}

	public function obtenerProductosVenta($limite=0)
	{
		$Pag["base_url"]		= base_url()."clientes/obtenerProductosVenta/";
		$Pag["total_rows"]		= $this->inventarioProductos->contarProductosVenta();//Total de Registros
		$Pag["per_page"]		= 50;
		$Pag["num_links"]		= 5;
		$data['precios']  		= $this->precios;
		
		$this->pagination->initialize($Pag);
		
		$data['productos']		= $this->inventarioProductos->obtenerProductosVenta($Pag["per_page"],$limite);

		#$this->load->view('clientes/ventas/obtenerProductosVenta',$data);
		
		if($this->precios=='1')
		{
			$this->load->view('clientes/ventas/obtenerProductosVentaPrecios',$data);
		}
		else
		{
			$this->load->view('clientes/ventas/obtenerProductosVenta',$data);
		}
		
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
			
			if($this->input->post('tipoVenta')==0)
			{
				$venta=$this->clientes->registrarVenta();
				
				echo json_encode($venta);
			}
			else
			{
				echo json_encode($this->ventasmodelo->registrarVenta());
			}
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function imprimirTicket($idCotizacion,$jquery=0)
	{
		$this->load->library('ccantidadletras');
		
		$data['productos']		= $this->clientes->obtenerProductosVenta($idCotizacion);
		$data['venta']			= $this->ventasmodelo->obtenerVenta($idCotizacion);
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones($this->idLicencia);
		$data['tienda']			= $this->tiendas->obtenerTiendaVenta($data['venta']->idTienda);
		$data['cliente']		= $this->clientes->obtenerCliente($data['venta']->idCliente);
		$data['jquery']			= $jquery;
		$data['direccion']		= $this->clientes->obtenerDireccionesEditar($data['venta']->idDireccion);
		
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['venta']->total);
		$this->ccantidadletras->setMoneda('pesos');//
		
		$data['cantidadLetras']	= $this->ccantidadletras->PrimeraMayuscula();
		
		if($data['venta']->estatus=='1')
		{
			#$this->load->view('clientes/tickets/ticketVenta',$data);
			$this->load->view('clientes/tickets/ticketVentaNuevo',$data);
		}
		else
		{
			$this->load->view('clientes/tickets/ticketCotizacionNuevo',$data);
		}
	}
	
	public function imprimirTicketPdf($idCotizacion)
	{
		$this->load->library('mpdf/mpdf');
		$this->load->library('ccantidadletras');
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$data['productos']		= $this->clientes->obtenerProductosVenta($idCotizacion);
		$data['venta']			= $this->ventasmodelo->obtenerVenta($idCotizacion);
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones($this->idLicencia);
		$data['tienda']			= $this->tiendas->obtenerTiendaVenta($data['venta']->idTienda);
		$data['cliente']		= $this->clientes->obtenerCliente($data['venta']->idCliente);
		
		$data['direccion']		= $this->clientes->obtenerDireccionesEditar($data['venta']->idDireccion);
		
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['venta']->total);
		$this->ccantidadletras->setMoneda('pesos');//
		
		$data['cantidadLetras']	= $this->ccantidadletras->PrimeraMayuscula();
		
		$html					= $this->load->view('clientes/tickets/ticketVentaPdf',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output($data['venta']->folio.'.pdf','D');
		#$this->mpdf->Output();
	}
	
	public function imprimirTicketVenta($idCotizacion)
	{
		$data['productos']		=$this->ventasmodelo->obtenerProductosVenta($idCotizacion);
		$data['venta']			=$this->ventasmodelo->obtenerCotizacionVenta($idCotizacion);
		$data['configuracion']	=$this->configuracion->obtenerConfiguraciones($this->idLicencia);
		$data['tienda']			=$this->tiendas->obtenerTiendaVenta($data['venta']->idTienda);
		
		$this->load->view('clientes/ticketVenta',$data);
	}
	
	public function formularioVentas()
	{
		#$data['bancos']			= $this->bancos->obtenerBancos();
		#$data['divisas']		= $this->configuracion->obtenerDivisas();
		#$data['ivas']			= $this->configuracion->obtenerIvas();
		#$data['formas']			= $this->configuracion->seleccionarFormas();
		#$data['emisores']		= $this->facturacion->obtenerEmisores();
		$data['lineas']			= $this->configuracion->obtenerLineas();
		$data['claveDescuento']	= $this->configuracion->obtenerUsuarioDescuento($this->_iduser);
		$data['cliente']		= $this->clientes->obtenerCliente($this->input->post('idCliente')>0?$this->input->post('idCliente'):1);
		$data['limiteVentas']	= $this->configuracion->obtenerLimiteVentas();
		$data['ventasF4']		= $this->ventasmodelo->sumarVentasDia();
		$data['serie']			= "COT-".date('Y-m-d').'-'.$this->inventario->obtenerId();
		$data['usuarios']		= $this->configuracion->obtenerUsuariosVentas();
		$data['idLicencia']		= $this->idLicencia;
		$data["registroVentas"]	= $this->registroVentas;
		$data["tiendaLocal"]	= $this->tiendaLocal;
		
		$this->load->view('clientes/ventas/formularioVentasCerraduras',$data);
	}
	
	public function formularioCobros()
	{
		$data['bancos']			= $this->bancos->obtenerBancos();
		$data['divisas']		= $this->configuracion->obtenerDivisas();
		$data['ivas']			= $this->configuracion->obtenerIvas();
		$data['formas']			= $this->configuracion->seleccionarFormas();
		
		$data['idTienda']		= $this->idTienda;
		$data['reutilizar']		= $this->input->post('reutilizar');
		$data['correo']			= $this->input->post('correo');
		
		$data['iva']			= $this->clientes->obtenerCotizacionVentaIva($this->input->post('idCotizacion'));
		
		$data['emisores']		= $this->facturacion->obtenerEmisores();
		$data['metodos']		= $this->configuracion->obtenerMetodosPago();
		$data['usos']			= $this->configuracion->obtenerUsosCfdi();
		$data['formasSat']		= $this->configuracion->obtenerFormasPago();
		$data['usuarios']		= $this->configuracion->obtenerUsuariosVentas();
		$data['rutas']			= $this->catalogos->obtenerRutas();
		$data['cliente']		= $this->clientes->obtenerCliente($this->input->post('idCliente'));
		$data['direcciones']	= $this->clientes->obtenerDirecciones($this->input->post('idCliente'),3);
		
		$data['usuario']		= $this->input->post('usuario');
		$data['idUsuario']		= $this->input->post('idUsuario');
		
		
		$this->load->view('clientes/ventas/formularioCobros',$data);
	}
	
	public function formularioCobrosCotizaciones()
	{
		$data['bancos']			= $this->bancos->obtenerBancos();
		$data['divisas']		= $this->configuracion->obtenerDivisas();
		$data['ivas']			= $this->configuracion->obtenerIvas();
		$data['formas']			= $this->configuracion->seleccionarFormas();
		
		$data['idTienda']		= $this->idTienda;
		$data['reutilizar']		= $this->input->post('reutilizar');
		$data['correo']			= $this->input->post('correo');
		
		$data['iva']			= $this->clientes->obtenerCotizacionVentaIva($this->input->post('idCotizacion'));
		
		$data['emisores']		= $this->facturacion->obtenerEmisores();
		$data['metodos']		= $this->configuracion->obtenerMetodosPago();
		$data['usos']			= $this->configuracion->obtenerUsosCfdi();
		$data['formasSat']		= $this->configuracion->obtenerFormasPago();
		$data['usuarios']		= $this->configuracion->obtenerUsuariosVentas();
		$data['rutas']			= $this->catalogos->obtenerRutas();
		$data['cliente']		= $this->clientes->obtenerCliente($this->input->post('idCliente'));
		$data['direcciones']	= $this->clientes->obtenerDirecciones($this->input->post('idCliente'),3);
		
		$data['usuario']		= $this->input->post('usuario');
		$data['idUsuario']		= $this->input->post('idUsuario');
		
		$this->load->view('clientes/ventas/formularioCobrosCotizaciones',$data);
	}
	
	public function actualizarMapa()
	{
		$this->load->library('googlemaps');

		$latitud		=$this->input->post('latitud');
		$longitud		=$this->input->post('longitud');

		#$config['center'] 				= $numero.', '.$calle.', '.$localidad.', '.$municipio.', '.$estado.', '.$pais.', '.$codigoPostal;
		$config['center'] 				= $latitud.', '.$longitud;
		#$marker['position'] 			= '37.429, -122.1519';
		$config['zoom'] 				= '13';
		#$config['drawing'] 				= true;
		#$config['drawingDefaultMode'] 	= 'circle';
		#$config['drawingModes'] 		= array('circle','rectangle','polygon');
		$config['loadAsynchronously'] 	= true;
		$config['https'] 				= true;
		$config['map_height'] 			= '300px';
		$config['map_width'] 			= '500px';
		$config['posicionY'] 			= '23%';
		$config['posicionX'] 			= '47%';
		$config['posicion'] 			= 'absolute';
		
		$config['map_div_id'] 			= 'mapaClientes';

		$this->googlemaps->initialize($config);
		
		$marker['icon'] 				= 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|9999FF|000000';
		$marker['position'] 			= $config['center'];
		$this->googlemaps->add_marker($marker);
		$map 					= $this->googlemaps->create_map();

		echo $map['js'];
		echo $map['html'];
		
		 echo'
		<script>
		$(document).ready(function()
		{
			loadScript();
		});

		</script>';
	}
	
	public function formularioClientes()
	{
		$this->load->library('googlemaps');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol')); //PERMISOS DE PROMOTORES
		
		$data['emisores'] 		= $this->configuracion->obtenerEmisores();
		$data['bancos'] 		= $this->bancos->obtenerBancos();
		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['metodos']		= $this->configuracion->obtenerMetodosPago();
		$data['regimen']		= $this->configuracion->obtenerRegimenFiscal();
		$data['tipoRegistro']	= $this->input->post('tipoRegistro');
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['licencias']		= $this->configuracion->obtenerLicenciasActivas();
		$data['numeroCliente']	= $this->clientes->obtenerNumeroCliente();
		$data['clienteSucursal']= $this->clientes->comprobarClienteSucursal();
		
		$data['idCliente']		= rand(1000000,10000000);
		$data['idLicencia']		= $this->idLicencia;
		
		$this->load->view('clientes/registro/formularioClientes',$data);
	}
	
	public function formularioClientesVentas()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol')); //PERMISOS DE PROMOTORES
		
		$data['emisores'] 		= $this->configuracion->obtenerEmisores();
		$data['bancos'] 		= $this->bancos->obtenerBancos();
		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['metodos']		= $this->configuracion->obtenerMetodosPago();
		$data['regimen']		= $this->configuracion->obtenerRegimenFiscal();
		$data['tipoRegistro']	= $this->input->post('tipoRegistro');
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['licencias']		= $this->configuracion->obtenerLicenciasActivas();
		$data['numeroCliente']	= $this->clientes->obtenerNumeroCliente();
		$data['clienteSucursal']= $this->clientes->comprobarClienteSucursal();
		
		$data['idCliente']		= rand(1000000,10000000);
		$data['idLicencia']		= $this->idLicencia;
		
		$this->load->view('ventas/clientes/formularioClientesVentas',$data);
	}
	
	public function registrarClienteVenta()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
		 	echo json_encode($this->clientes->registrarClienteVenta());
		}
		else
		{
			echo json_encode(array('0',errorRegistro));
		}
	}
	
	public function formularioProspectos()
	{
		$this->load->library('googlemaps');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('62',$this->session->userdata('rol')); //PERMISOS DE PROMOTORES
		
		$data['emisores'] 		= $this->configuracion->obtenerEmisores();
		$data['bancos'] 		= $this->bancos->obtenerBancos();
		$data['zonas']			= $this->configuracion->obtenerZonas();
		$data['metodos']		= $this->configuracion->obtenerMetodosPago();
		$data['tipoRegistro']	= $this->input->post('tipoRegistro');
		$data['promotores']		= $this->configuracion->obtenerPromotoresRegistro($data['permiso'][5]->activo);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['fuentes']		= $this->clientes->obtenerFuentesContacto();
		
		$data['idCliente']		= rand(1000000,10000000);
		
		if(sistemaActivo=='IEXE')
		{
			$data['programas']			= $this->configuracion->obtenerProgramas();
			$data['tiposDocumentos']	= $this->catalogos->obtenerTiposDocumentosCliente();
			$data['campanas']			= $this->configuracion->obtenerCampanas();
			
			//BORRAR DOCUMENTOS TEMPORALES QUE NO SE GUARDARON
			$temporal		= $this->clientes->verificarDocumentosTemporal();
			
			if($temporal>0)
			{
				$this->clientes->borrarDocumentosTemporales();
			}
		}

		$this->load->view('clientes/prospectos/formularioProspectos',$data);
	}
	
	public function obtenerFuentesContacto()
	{
		$fuentes=$this->clientes->obtenerFuentesContacto();
		
		 echo '
		 <select class="cajas" id="selectFuente" name="selectFuente" style="width:290px">
			<option value="0">Seleccione</option>';
			
			foreach($fuentes as $row)
			{
				echo '<option value="'.$row->idFuente.'">'.$row->nombre.'</option>';
			}
		echo'
		</select>';
	}
	
	public function formularioFuentesContacto()
	{
		 echo '
		 <table class="admintable" width="100%">
		 	<tr>
				<td class="key">Contacto</td>
				<td>
					<input type="text" class="cajas" id="txtFuente" style="width:200px" />
				</td>
			</tr>
		 </table>';
	}
	
	public function registrarFuenteContacto()
	{
		if(!empty($_POST))
		{
			$fuente=$this->clientes->registrarFuenteContacto();
			echo $fuente;
		}
	}

	public function formularioCotizaciones()
	{
		$idCliente				= $this->input->post('idCliente');
		$data['idCliente']		= $this->input->post('idCliente');
		$data['cliente']		= $this->clientes->obtenerCliente($idCliente);
		$data['claveDescuento']	= $this->configuracion->obtenerUsuarioDescuento($this->_iduser);
		$data['bancos']			= $this->bancos->obtenerBancos();
		$data['divisas']		= $this->configuracion->obtenerDivisas();
		$data['ivas']			= $this->configuracion->obtenerIvas();
		$data['lineas']			= $this->configuracion->obtenerLineas();
		$data['serie']			= "COT-".date('Y-m-d').'-'.$this->inventario->obtenerId();
		
		$this->load->view('clientes/cotizacion/formularioCotizacion',$data);
	}
	
	public function formularioProcesarCotizacion()
	{
		$data['divisas']		= $this->configuracion->obtenerDivisas();
		$data['ivas']			= $this->configuracion->obtenerIvas();
		$data['diasCredito']	= $this->clientes->obtenerDiasCredito($this->input->post('idCliente'));
		$data['usuario']		= $this->configuracion->obtenerUsuario($this->_iduser);
		$data['usuarios']		= $this->configuracion->obtenerListaUsuarios();
		
		$this->load->view('clientes/cotizacion/formularioProcesarCotizacion',$data);
	}
	
	public function formularioCotizacionesClientes()
	{
		$data['bancos']			=$this->bancos->obtenerBancos();
		$data['divisas']		=$this->configuracion->obtenerDivisas();
		$data['ivas']			=$this->configuracion->obtenerIvas();
		$data['serie']			="COT-".date('Y-m-d').'-'.$this->inventario->obtenerId();
		
		$this->load->view('clientes/cotizacion/formularioCotizacionCliente',$data);
	}
	
	public function obtenerDiasCredito()
	{
		echo round($this->clientes->obtenerDiasCredito($this->input->post('idCliente')));
	}
	
	public function registrarCotizacion()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			echo json_encode($this->clientes->registrarCotizacion());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerCotizacion()
	{
		$idCotizacion			= $this->input->post('idCotizacion');
		$data['cotizacion']		= $this->clientes->obtenerCotizacionVentaDetalle($idCotizacion);
		$data['productos']		= $this->clientes->obtenerProductosVenta($idCotizacion);
		$data['contactos']		= $this->clientes->obtenerContactos($data['cotizacion']->idCliente);
		$data['claveDescuento']	= $this->configuracion->obtenerUsuarioDescuento($this->_iduser);
		$data['lineas']			= $this->configuracion->obtenerLineas();
		$data['bancos']			= $this->bancos->obtenerBancos();
		$data['divisas']		= $this->configuracion->obtenerDivisas();
		$data['ivas']			= $this->configuracion->obtenerIvas();

		$this->load->view('clientes/cotizacion/obtenerCotizacion',$data);
	}
	
	public function formularioEditarCotizacion()
	{
		$data['divisas']		= $this->configuracion->obtenerDivisas();
		$data['ivas']			= $this->configuracion->obtenerIvas();
		$data['cotizacion']		= $this->clientes->obtenerCotizacionVentaDetalle($this->input->post('idCotizacion'));
		$data['usuario']		= $this->configuracion->obtenerUsuario($data['cotizacion']->idUsuario);
		$data['usuarios']		= $this->configuracion->obtenerListaUsuarios();
		
		$this->load->view('clientes/cotizacion/formularioEditarCotizacion',$data);
	}
	
	public function editarCotizacion()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->clientes->editarCotizacion());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}

	public function mapas()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['jFicha_cliente']	=$this->_jss['jFicha_cliente']; 
		$Data['Jqui']			=$this->_jss['jqueryui'];   
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='prospectos'; 
		
		$this->load->library('googlemaps');

		$config['center'] 				= 'Puebla, Mexico';
		$config['zoom'] 				= '13';
		$config['drawing'] 				= true;
		$config['drawingDefaultMode'] 	= 'circle';
		$config['drawingModes'] 		= array('circle','rectangle','polygon');
		$this->googlemaps->initialize($config);
		
		$data['map'] 					= $this->googlemaps->create_map();
		
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		$this->load->view("clientes/mapas",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}

	public function formularioCorreo()
	{
		$data['serie']		= $this->input->post('serie');
		$data['correo']		= $this->input->post('correo');
		$data['cotizacion']	= $this->ventas->obtenerCotizacion($this->input->post('idCotizacion'));
		$data['contactos']	= $this->clientes->obtenerContactos($data['cotizacion']->idCliente);
		$data['usuario']	= $this->configuracion->obtenerUsuario($this->_iduser);
		$data['usuarios']	= $this->configuracion->obtenerListaUsuarios();
		$data['historial']	= $this->configuracion->obtenerHistorialEnvios($this->input->post('idCotizacion'),1);
		
		$this->load->view("ventas/enviar/formularioCorreo",$data);
	}
	
	public function pdfFisicoVenta($idCotizacion)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		#$remision 			= $this->ventas->obtenerRemision($idCotizacion);
		$data['cotizacion'] = $this->ventas->obtenerRemision($idCotizacion);
		$data['cliente'] 	= $this->ventas->obtenerCliente($data['cotizacion']->idCliente);
		$data['productos'] 	= $this->ventas->obtenerProductos($data['cotizacion']->idCotizacion);
		$data['empresa'] 	= $this->configuracion->obtenerConfiguraciones($this->idLicencia);
		#$data['reporte'] 	='clientes/venta';
		$data['reporte'] 	= 'clientes/venta/remision';
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['cotizacion']->total);
		$this->ccantidadletras->setMoneda($data['cotizacion']->divisa);//
		$CantidadLetras			=$this->ccantidadletras->PrimeraMayuscula();
		
		$data['cantidadLetra']	=$CantidadLetras;

		/*$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);*/
		
		$html	=$this->load->view('reportes/principal',$data,true);
		#$pie	=$this->load->view('clientes/cotizaciones/pie',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',10,10,10,47,2,5);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$documento='media/cotizaciones/'.$data['cotizacion']->ordenCompra.'.pdf';
		
		$this->mpdf->Output($documento,'F');
		
		$this->orden	= $data['cotizacion']->ordenCompra;
		
		return $documento;
	}
	
	public function enviar()
	{
		if(!empty($_POST))
		{
			$configuracion	= $this->configuracion->obtenerConfiguracion();
			$usuario		= $this->configuracion->obtenerUsuario( $this->input->post('idUsuario'));
			
			$email			= $configuracion['correo'];
			$nombre			= $configuracion['nombre'];
			
			if($usuario!=null)
			{
				if(strlen($usuario->correo)>0)
				{
					$email	= $usuario->correo;
					$nombre	= $usuario->nombre.' '.$usuario->apellidoPaterno.' '.$usuario->apellidoMaterno;
				}
			}
			
			$idCotizacion	= $this->input->post('idCotizacion');
			$mensaje		= $this->input->post('mensaje');
			$destinatario	= $this->input->post('correo');
			$asunto			= $this->input->post('asunto');
			$firma			= $this->input->post('firma');
			
			$this->load->library('email');
			$this->email->from($email,$nombre);
			$this->email->to($destinatario);

			$imagen			= "";
			
			if(file_exists('img/logos/'.$this->session->userdata('logotipo')))
			{
				$imagen		= '<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="200" /><br /><br />';
			}

			$this->email->attach($this->pdfFisicoVenta($idCotizacion));
			
			$cuerpo			= $imagen.$mensaje.'<br />'.nl2br($firma).' <br /> <br /> <strong>Por favor consulte los adjuntos</strong>'.$link;

			$this->email->subject($asunto);
			$this->email->message($cuerpo);
			
			if(!$this->email->send())
			{
				echo "0";
			}
			else
			{
				$this->configuracion->registrarBitacora('Enviar venta','Ventas',$this->orden.', Email: '.$destinatario); //Registrar bitácora
				$this->configuracion->registrarHistorialEnvios($destinatario,'1',$this->input->post('idUsuario'),$idCotizacion); //Registrar historial
				
				echo "1";
			}
				
		}
		else
		{
			echo "2";
		}
	}
	
	//PARA ENVIAR LAS COTIZACIONES POR CORREO
	public function formularioCorreoCotizacion()
	{
		$idCotizacion		= $this->input->post('idCotizacion');
		$data['cotizacion']	= $this->clientes->obtenerCotizacionVenta($idCotizacion);
		$data['usuario']	= $this->configuracion->obtenerUsuario($this->_iduser);
		$data['usuarios']	= $this->configuracion->obtenerListaUsuarios();
		$data['historial']	= $this->configuracion->obtenerHistorialEnvios($idCotizacion,0);
		
		$this->load->view('cotizaciones/enviar/formularioCorreo',$data);
	}
	
	public function pdfFisicoCotizacion($idCotizacion,$desglose)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$data['cotizacion'] = $this->ventas->obtenerRemision($idCotizacion);
		$data['cliente'] 	= $this->ventas->obtenerCliente($data['cotizacion']->idCliente);
		$data['productos'] 	= $this->ventas->obtenerProductos($data['cotizacion']->idCotizacion);
		$data['desglose']	= $desglose;

		$data['empresa'] 	=$this->configuracion->obtenerConfiguraciones($this->idLicencia);
		$data['reporte'] 	='clientes/cotizacion/cotizacion';
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['cotizacion']->total);
		$this->ccantidadletras->setMoneda($data['cotizacion']->divisa);//
		$CantidadLetras		=$this->ccantidadletras->PrimeraMayuscula();
		
		$data['cantidadLetra']=$CantidadLetras;

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,5);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$documento='media/cotizaciones/'.$data['cotizacion']->serie.'.pdf';
		
		$this->mpdf->Output($documento,'F');
		$this->orden	= $data['cotizacion']->serie;
		
		return $documento;
	}
	
	public function enviarCotizacion()
	{
		if(!empty($_POST))
		{
			$configuracion	= $this->configuracion->obtenerConfiguracion();
			$usuario		= $this->configuracion->obtenerUsuario( $this->input->post('idUsuario'));
			
			$email			= $configuracion['correo'];
			$nombre			= $configuracion['nombre'];
			
			if($usuario!=null)
			{
				if(strlen($usuario->correo)>0)
				{
					$email	= $usuario->correo;
					$nombre	= $usuario->nombre.' '.$usuario->apellidoPaterno.' '.$usuario->apellidoMaterno;
				}
			}
			
			$idCotizacion	= $this->input->post('idCotizacion');
			$mensaje		= $this->input->post('mensaje');
			$destinatario	= $this->input->post('correo');
			$asunto			= $this->input->post('asunto');
			$firma			= $this->input->post('firma');
			
			$this->load->library('email');
			$this->email->from($email,$nombre);
			$this->email->to($destinatario);

			$imagen	="";
			
			if(file_exists('img/logos/'.$this->session->userdata('logotipo')))
			{
				$imagen='<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="200" /><br /><br />';
			}
			
			$link='';

			$this->email->attach($this->reportes->cotizacionPdf($idCotizacion,$this->input->post('desglose'),1));
			
			$cuerpo=$imagen.$mensaje.'<br />'.nl2br($firma).' <br /><strong>Por favor consulte los adjuntos</strong> '.$link;

			$this->email->subject($asunto);
			$this->email->message
			(
				$cuerpo
			);
			
			if (!$this->email->send())
			{
				print("0");
			}
			else
			{
				$this->configuracion->registrarBitacora('Enviar cotización','Cotizaciones',$this->orden.', Email: '.$destinatario); //Registrar bitácora
				$this->configuracion->registrarHistorialEnvios($destinatario,'0',$this->input->post('idUsuario'),$idCotizacion); //Registrar historial
				
				print("1");
			}
				
		}
		else
		{
			print("2");
		}
	}
	
	//Registrar la previa de la factura
	public function realizarVentaPrevia()
	{
		if(!empty($_POST))
		{
			echo $this->previa->realizarVentaPrevia();
		}
		else
		{
			echo "0";
		}
	}
	
	//Registrar la previa de la factura
	public function registrarVentaFactura()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			echo json_encode($this->facturaVenta->registrarVentaFactura());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	//PARA LOS BANCOS Y LAS CUENTAS
	
	public function formularioBancos()
	{
		echo'
		<table class="admintable" width="100%">
			<tr>
				<td class="key">Nombre:</td>
				<td>
					<input type="text" class="cajas" id="txtNombreBanco" style="width:200px" />
				</td>
			</tr>				
		</table>';
	}
	
	public function formularioCuentasCliente()
	{
		$data['bancos'] 	= $this->bancos->obtenerBancos();
		$data['emisores'] 	= $this->configuracion->obtenerEmisores();
		
		$this->load->view('clientes/cuentas/formularioCuentasCliente',$data);
	}
	
	public function obtenerCuentaCliente()
	{
		$idCuenta			= $this->input->post('idCuenta');
		$data['cuenta'] 	= $this->bancos->obtenerCuenta($idCuenta);
		$data['bancos'] 	= $this->bancos->obtenerBancos();
		$data['emisores'] 	= $this->configuracion->obtenerEmisores();
		$data['idCuenta'] 	= $idCuenta;
		
		$this->load->view('clientes/cuentas/obtenerCuentaCliente',$data);
	}
	
	
	//ENVIAR LA BITÁCORA
	
	public function enviarBitacora()
	{
		if(!empty($_POST))
		{
			
			$idUsuario		= $this->input->post('idResponsable');
			$usuario		= $this->configuracion->obtenerUsuario($idUsuario);

			$this->load->library('email');
			$this->email->from($usuario->correo,$usuario->nombre.' '.$usuario->apellidoPaterno.' '.$usuario->apellidoMaterno);
			$this->email->to($this->input->post('email'));

			$imagen	="";
			
			if(file_exists('img/logos/'.$this->session->userdata('logotipo')))
			{
				$imagen='<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="200" /><br /><br />';
			}

			$mensaje="";
			$mensaje.="Fecha y hora: ".$this->input->post('fecha').'<br />';
			$mensaje.="Responsable: ".$usuario->nombre.' '.$usuario->apellidoPaterno.' '.$usuario->apellidoMaterno.'<br />';
			$mensaje.="Lugar: ".$this->input->post('lugar').'<br />';
			$mensaje.="Bitácora: ".$this->input->post('bitacora').'<br />';
			
			$cuerpo=$imagen.$mensaje;

			$this->email->subject('Bitácora');
			$this->email->message
			(
				$cuerpo
			);
			
			if (!$this->email->send())
			{
				echo "0";
			}
			else
			{
				$this->configuracion->registrarBitacora('Enviar bitácora','Seguimiento', 'Email: '.$this->input->post('email').', Bitácora: '.$this->input->post('bitacora')); //Registrar bitácora
				
				echo "1";
			}
				
		}
		else
		{
			echo "2";
		}
	}
	
	
	//ARCHIVOS DE SEGUIMIENTO
	public function obtenerArchivosSeguimiento()
	{
		$idSeguimiento			= $this->input->post('idSeguimiento');
		
		$data['idSeguimiento']	= $idSeguimiento;
		$data['archivos']		= $this->clientes->obtenerArchivosSeguimiento($idSeguimiento);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['cuota']			= $this->cuota;
		
		$this->load->view('clientes/seguimiento/obtenerArchivosSeguimiento',$data);
	}
	
	public function subirArchivosSeguimiento($idSeguimiento=0)
	{
		if (!empty($_FILES)) 
		{
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('jpg','jpeg','gif','png','tif','bmp','pdf','doc','docx','xls','xlsx','txt','rar','zip','xps','oxps','xml');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				$idArchivo	= $this->clientes->subirArchivosSeguimiento($idSeguimiento,$_FILES['file']['name'],$_FILES['file']['size']);
				
				if($idArchivo>0)
				{
					move_uploaded_file($archivoTemporal,carpetaSeguimientoClientes.$idArchivo.'_'.$_FILES['file']['name']);

					if(file_exists(carpetaSeguimientoClientes.$idArchivo.'_'.$_FILES['file']['name']))
					{
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

	public function borrarArchivoSeguimiento()
	{
		if(!empty($_POST))
		{
			$idArchivo	=$this->input->post('idArchivo');

			echo $this->clientes->borrarArchivoSeguimiento($idArchivo);
		}
	}
		
	function descargarArchivoSeguimiento($idArchivo) #Descargar el archivo XML
	{
		$this->load->helper('download');

		$fichero	= $this->clientes->obtenerArchivoSeguimiento($idArchivo);

		$archivo 	= $fichero->idArchivo.'_'.$fichero->nombre;

		$data = file_get_contents(carpetaSeguimientoClientes.$archivo); 
		
		force_download($fichero->nombre, $data); 
	}
	
	//FICHA TÉCNICA DEL CLIENTE
	public function buscarCliente($idCliente)
	{
		$data['cliente']		= $this->clientes->obtenerCliente($idCliente);
		$data['contactos']		= $this->clientes->obtenerContactos($idCliente);
		$data['cuentas']		= $this->clientes->obtenerCuentasCliente($idCliente);
		
		if(sistemaActivo=='IEXE')
		{
			$data['academico']	= $this->clientes->obtenerAcademicoCliente($idCliente);
			$data['documentos']	= $this->clientes->comprobarDocumentosCliente($idCliente);
		}
		
		$this->load->view('clientes/ficha/fichaTecnica',$data);
	}
	
	public function formularioCorreoFicha()
	{
		$data['cliente']	= $this->clientes->obtenerCliente($this->input->post('idCliente'));
		$data['usuario']	= $this->configuracion->obtenerUsuario($this->_iduser);
		$data['usuarios']	= $this->configuracion->obtenerListaUsuarios();
		
		$this->load->view('clientes/ficha/formularioCorreoFicha',$data);
	}
	
	public function fichaPdf($idCliente,$opcion=1)
	{
		$this->load->library('mpdf/mpdf');
		
		$data['cliente']		= $this->clientes->obtenerCliente($idCliente);
		$data['contactos']		= $this->clientes->obtenerContactos($idCliente);
		$data['cuentas']		= $this->clientes->obtenerCuentasCliente($idCliente);
		$data['reporte']		= 'clientes/ficha/fichaTecnica';

		$html	= $this->load->view('reportes/principal',$data,true);
		$pie 	= $this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter-L','','',10,10,10,10,2,1);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		if($opcion==0)
		{
			$this->mpdf->Output(carpetaFicheros.'FichaCliente.pdf','F');
			
			return carpetaFicheros.'FichaCliente.pdf';
		}
		else
		{
			$this->mpdf->Output('FichaCliente.pdf','D');
			//$this->mpdf->Output();
		}
	}
	
	public function enviarFichaCliente()
	{
		if(!empty($_POST))
		{
			$configuracion	= $this->configuracion->obtenerConfiguracion();
			$usuario		= $this->configuracion->obtenerUsuario( $this->input->post('idUsuario'));
			
			$email			= $configuracion['correo'];
			$nombre			= $configuracion['nombre'];
			
			if($usuario!=null)
			{
				if(strlen($usuario->correo)>0)
				{
					$email	= $usuario->correo;
					$nombre	= $usuario->nombre.' '.$usuario->apellidoPaterno.' '.$usuario->apellidoMaterno;
				}
			}
			
			$idCliente		= $this->input->post('idCliente');
			$mensaje		= $this->input->post('mensaje');
			$destinatario	= $this->input->post('correo');
			$asunto			= $this->input->post('asunto');
			$firma			= $this->input->post('firma');
			
			$this->load->library('email');
			$this->email->from($email,$nombre);
			$this->email->to($destinatario);

			$imagen			= "";
			
			if(file_exists('img/logos/'.$this->session->userdata('logotipo')))
			{
				$imagen		= '<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="200" /><br /><br />';
			}

			$this->email->attach($this->fichaPdf($idCliente,0));
			
			$cuerpo			= $imagen.$mensaje.'<br />'.nl2br($firma).' <br /> <br /> <strong>Por favor consulte los adjuntos</strong>'.$link;

			$this->email->subject($asunto);
			$this->email->message($cuerpo);
			
			if(!$this->email->send())
			{
				echo "0";
			}
			else
			{
				$this->configuracion->registrarBitacora('Enviar bitácora','Seguimiento', 'Email: '.$this->input->post('email').', Bitácora: '.$this->input->post('bitacora')); //Registrar bitácora
				echo "1";
			}
				
		}
		else
		{
			echo "2";
		}
	}
	
	//ESTADO DE CUENTA ALUMNO
	public function obtenerEstadoCuenta()
	{
		$data['cliente']		= $this->clientes->obtenerCliente($this->input->post('idCliente'));
		$data['academicos']		= $this->clientes->obtenerAcademicoCliente($this->input->post('idCliente'));
		
		if($data['academicos']==null)
		{
			$this->clientes->registrarAcademicoCliente($this->input->post('idCliente'));
			
			$data['academicos']		= $this->clientes->obtenerAcademicoCliente($this->input->post('idCliente'));
		}
		
		$data['pagos']			= $this->clientes->obtenerPagosCliente($this->input->post('idCliente'));
		$data['totalPagos']		= $this->clientes->obtenerTotalPagosCliente($this->input->post('idCliente'));
		$data['otrosPagos']		= $this->clientes->obtenerTotalPagosClienteOtros($this->input->post('idCliente'));
		
		$this->load->view('clientes/estadoCuenta/estadoCuenta',$data);
	}
	
	public function reporteEstadoCuenta($idCliente)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');
		
		$data['cliente']		= $this->clientes->obtenerCliente($idCliente);
		$data['academicos']		= $this->clientes->obtenerAcademicoCliente($idCliente);
		$data['pagos']			= $this->clientes->obtenerPagosCliente($idCliente);
		$data['totalPagos']		= $this->clientes->obtenerTotalPagosCliente($idCliente);
		$data['otrosPagos']		= $this->clientes->obtenerTotalPagosClienteOtros($idCliente);
		
		$data['reporte']		= 'clientes/estadoCuenta/estadoCuenta';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',5,5,10,10,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('EstadoCuenta.pdf','D');
	}
	
	//DOCUMENTOS DE LOS ALUMNOS CLIENTES
	
	public function subirArchivoCliente($idTipo=0,$idCliente=0,$temporal=0)
	{
		if (!empty($_FILES)) 
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			
			$id					= rand(1000000,10000000);
		
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('jpg','jpeg','gif','png','tif','bmp','pdf','doc','docx','xls','xlsx','txt','rar','zip','xps','oxps','xml','PDF');
			$archivo 			= pathinfo($_FILES['file']['name']);
			$fichero 			= $id.'_'.$_FILES['file']['name'];

			if (in_array($archivo['extension'],$extensiones)) 
			{
				move_uploaded_file($archivoTemporal,carpetaClientesDocumentos.$fichero);

				if(file_exists(carpetaClientesDocumentos.$fichero))
				{
					$idDocumento	= $this->clientes->registrarDocumentoTemporal($idTipo,$archivo['basename'],$_FILES['file']['size'],$id,$idCliente,$temporal);
					
					echo json_encode(array('1',$fichero,$archivo['extension'],$idDocumento));
				}
				else
				{
					echo json_encode(array('0','Error al mover el archivo, verifique los permisos'));
				}
			} 
			else
			{
				echo json_encode(array('0','No se permiten este tipo de archivos'));
			}
		}
	} 
	
	public function borrarDocumentoTemporal($temporal=1)
	{	
		if(!empty($_POST))
		{
			echo json_encode($this->clientes->borrarDocumentoTemporal($this->input->post('idDocumento'),$temporal));	
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	function descargarDocumentoCliente($idDocumento) #Descargar el archivo XML
	{
		$this->load->helper('download');

		$documento	= $this->clientes->obtenerDocumentoCliente($idDocumento);
		$archivo 	= $documento->id.'_'.$documento->nombre;
		$data 		= file_get_contents(carpetaClientesDocumentos.$archivo); 
		
		force_download($documento->nombre, $data); 
	}
	
	public function obtenerClientesBusqueda($limite=0)
	{
		$criterio			= $this->input->post('criterio');
		
		$Pag["base_url"]	= base_url()."clientes/obtenerClientesBusqueda/";
		$Pag["total_rows"]	= $this->clientes->contarClientesBusqueda($criterio);
		$Pag["per_page"]	= 25;
		$Pag["num_links"]	= 5;

		$this->pagination->initialize($Pag);
	

		$data['clientes'] 		= $this->clientes->obtenerClientesBusqueda($Pag["per_page"],$limite,$criterio);
		$data['inicio']  		= $limite+1;
		$data['idLicencia']  	= $this->idLicencia;
		$data["tiendaLocal"]	= $this->tiendaLocal;

		$this->load->view("ventas/clientes/obtenerClientesBusqueda",$data);
	}
	
	public function editarClienteVenta()
	{	
		if(!empty($_POST))
		{
			echo json_encode($this->clientes->editarClienteVenta());	
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	#----------------------------------------------------------------------------------------------------------#
	#-----------------------------------------------DIRECCIONES------------------------------------------------#
	#----------------------------------------------------------------------------------------------------------#
	
	public function direcciones()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
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

		$this->load->view("clientes/direcciones/direcciones",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoDirecciones()
	{
		$data['idCliente']			= $this->input->post('idCliente');

		$this->load->view("clientes/direcciones/direcciones",$data);
	}
	
	public function obtenerDirecciones()
	{
		$data['direcciones']		= $this->clientes->obtenerDirecciones($this->input->post('idCliente'));
		
		$this->load->view('clientes/direcciones/obtenerDirecciones',$data);
	}
	
	public function formularioDirecciones()
	{
		$data['regimen']		= $this->configuracion->obtenerRegimenFiscal();
		
		$this->load->view('clientes/direcciones/formularioDirecciones',$data);
	}
	
	public function obtenerDireccionesCliente()
	{
		$data['direcciones']		= $this->clientes->obtenerDirecciones($this->input->post('idCliente'),$this->input->post('tipo'));
		
		$this->load->view('clientes/direcciones/obtenerDireccionesCliente',$data);
	}
	
	public function obtenerDireccionesFiscalesCliente()
	{
		$data['direcciones']		= $this->clientes->obtenerDireccionesFiscales($this->input->post('idCliente'),$this->input->post('tipo'));
		
		$this->load->view('clientes/direcciones/obtenerDireccionesCliente',$data);
	}
	
	public function obtenerDireccionesCfdi()
	{
		$data['direcciones']		= $this->clientes->obtenerDireccionesFiscales($this->input->post('idCliente'),$this->input->post('tipo'));
		
		$this->load->view('clientes/direcciones/obtenerDireccionesCfdi',$data);
	}
	
	public function obtenerDireccionesEditar()
	{
		$data['direccion']		= $this->clientes->obtenerDireccionesEditar($this->input->post('idDireccion'));
		$data['regimen']		= $this->configuracion->obtenerRegimenFiscal();
		
		$this->load->view('clientes/direcciones/obtenerDireccionesEditar',$data);
	}
	
	public function registrarDirecciones()
	{
		if(!empty ($_POST)) 
		{
			echo json_encode($this->clientes->registrarDirecciones());
		}
		else echo json_encode(array('0',errorRegistro));
	}
	
	public function editarDirecciones()
	{
		if(!empty ($_POST))	
		{
			echo json_encode($this->clientes->editarDirecciones());	
		}
		else echo json_encode(array('0',errorRegistro));
	}
	
	public function borrarDirecciones()
	{
		if(!empty ($_POST))
		{
			echo $this->clientes->borrarDirecciones($this->input->post('idDireccion'));
		}
		else
		{
			echo "0";
		}
	}
	
	//CONFIGURAR SUCURSALES
	public function obtenerSucursalesCliente()
	{
		$idCliente				= $this->input->post('idCliente');
		
		$data['licencias']		= $this->configuracion->obtenerLicenciasActivas();
		$data['registros']		= $this->clientes->obtenerSucursalesCliente($idCliente);
		$data['cliente']		= $this->clientes->obtenerCliente($idCliente);
		$data['idLicencia']		= $this->idLicencia;
		
		$this->load->view('clientes/sucursales/obtenerSucursalesCliente',$data);
	}
	
	public function registrarSucursalesCliente()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
		 	echo json_encode($this->clientes->registrarSucursalesCliente());
		}
		else
		{
			echo json_encode(array('0',errorRegistro));
		}
	}
}
?>
