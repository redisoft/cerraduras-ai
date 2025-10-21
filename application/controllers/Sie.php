<?php
class Sie extends CI_Controller
{
	protected $fecha;
	protected $idUsuario;
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
	    $this->fecha 			= mdate($datestring,now());
		$this->idUsuario		= $this->session->userdata('id');
		$this->_role 			= $this->session->userdata('role');
		$this->_tables 			= $this->config->item('datatables');
		$this->_csstyle 		= $this->config->item('style');
		
		$this->load->model("crm_modelo","crm");
        $this->load->model("modelousuario","usuarios");
        $this->load->model("modeloclientes","clientes");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("creditos_modelo","creditos");
		$this->load->model("motivos_modelo","motivos");
		$this->load->model("sie_modelo","sie");
		$this->load->model("proyeccion_modelo","proyeccion");
		$this->load->model("reportes_model","reportes");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
  	}
	
	public function index($idEscenario=0)
	{
		$data['titulo'] 		= "Saldos";
		$data['usuario'] 		= $this->usuarios->getUsuarios($this->idUsuario);
		$data['fecha'] 			= $this->fecha;

		$data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
		$data['menuActivo']		= 'finanzas'; 
		$data['subMenu']		= 'escenario'.$idEscenario; 
		$data['pagina']			= 'sie/index'; 

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('64',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$data['pagina']		= 'accesos/index'; 
		}
		
		$data["breadcumb"]		= '<li class="breadcrumb-item"><a href="'.base_url().'sie/index">Finanzas</a></li><li class="breadcrumb-item active">Saldos</li>';
		$data['idEscenario']	= $idEscenario; 

		$this->load->view("sie/paginaPrincipal", $data);
	}
	
	public function inicio()
	{
		$Data['title'] 			= "Panel de Administración";
		$Data['cassadmin'] 		= $this->_csstyle["cassadmin"];
		$Data['csmenu'] 		= $this->_csstyle["csmenu"];
		$Data['csvalidate'] 	= $this->_csstyle["csvalidate"];
		$Data['csui'] 			= $this->_csstyle["csui"];
		$Data['nameusuario'] 	= $this->usuarios->getUsuarios($this->idUsuario);
		$Data['Fecha_actual'] 	= $this->fecha;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'sie'; 
		$Data['subMenu']		= 'saldosSie'; 
		
		$this->load->view("cabezera", $Data);
		$this->load->view('header', $Data);
		$this->load->view("principal", $Data);
	
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('64',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= 'Sie > Información financiera > Saldos';

		$this->load->view("sie/index", $data);
		$this->load->view("pie", $Data);
	}
	
	public function obtenerInformacionFinanciera()
	{
		$fecha								= $this->input->post('fecha');
		$idEscenario						= $this->input->post('idEscenario');
		
		$data['financiera']					= $this->sie->obtenerFinanciera();
		$data['cuentas']					= $this->sie->obtenerSaldoCuentas();
		$data['fechaFinanciera']			= $fecha;
		
		$dia								= obtenerFechaSeparada($fecha,'dia');
		$ultima								= $this->reportes->obtenerUltimaFecha($fecha);
		
		if($dia==15)
		{
			$fecha							= $this->input->post('fecha');
			$fecha2							= $this->input->post('fecha');
			$fecha3							= $this->reportes->obtenerUltimaFecha($fecha);
			$fecha4							= $this->reportes->obtenerFechaFinCriterio($fecha2,1,'month');
		}
		
		if($dia<15)
		{
			$fecha							= $this->input->post('fecha');
			$fecha2							= obtenerFechaSeparada($fecha,'anio').'-'.obtenerFechaSeparada($fecha,'mes').'-15';
			$fecha3							= $this->reportes->obtenerUltimaFecha($fecha);
			$fecha4							= $this->reportes->obtenerFechaFinCriterio($fecha2,1,'month');
		}
		
		if($dia>15 and $fecha!=$ultima)
		{
			$fecha							= $this->input->post('fecha');
			$fecha2							= $this->reportes->obtenerUltimaFecha($fecha);
			$fecha3							= $this->reportes->obtenerFechaFinCriterio($fecha2,15,'day');
			$fecha4							= $this->reportes->obtenerUltimaFecha($fecha3);
		}
		
		if($fecha==$ultima)
		{
			$fecha							= $this->input->post('fecha');
			$fecha2							= $this->reportes->obtenerFechaFinCriterio($fecha,15,'day');
			$fecha3							= $this->reportes->obtenerUltimaFecha($fecha2);
			$fecha4							= $this->reportes->obtenerFechaFinCriterio($fecha2,1,'month');
		}
		
		
		/*$data['saldoDia']					= $data['financiera']->efectivo+$data['financiera']->cuentas+$this->proyeccion->sumarIngresosFecha($fecha)-$this->proyeccion->sumarEgresosFecha($fecha);
		$data['saldoDia2']					= $data['financiera']->efectivo+$data['financiera']->cuentas+$this->proyeccion->sumarIngresosFecha($fecha2)-$this->proyeccion->sumarEgresosFecha($fecha2);
		$data['saldoDia3']					= $data['financiera']->efectivo+$data['financiera']->cuentas+$this->proyeccion->sumarIngresosFecha($fecha3)-$this->proyeccion->sumarEgresosFecha($fecha3);
		$data['saldoDia4']					= $data['financiera']->efectivo+$data['financiera']->cuentas+$this->proyeccion->sumarIngresosFecha($fecha4)-$this->proyeccion->sumarEgresosFecha($fecha4);*/
		
		$data['saldoDia']					= $data['financiera']->efectivo+$data['cuentas']+$this->proyeccion->sumarIngresosFecha($fecha,$idEscenario)-$this->proyeccion->sumarEgresosFecha($fecha,$idEscenario);
		$data['saldoDia2']					= $data['financiera']->efectivo+$data['cuentas']+$this->proyeccion->sumarIngresosFecha($fecha2,$idEscenario)-$this->proyeccion->sumarEgresosFecha($fecha2,$idEscenario);
		$data['saldoDia3']					= $data['financiera']->efectivo+$data['cuentas']+$this->proyeccion->sumarIngresosFecha($fecha3,$idEscenario)-$this->proyeccion->sumarEgresosFecha($fecha3,$idEscenario);
		$data['saldoDia4']					= $data['financiera']->efectivo+$data['cuentas']+$this->proyeccion->sumarIngresosFecha($fecha4,$idEscenario)-$this->proyeccion->sumarEgresosFecha($fecha4,$idEscenario);
		
		
		
		$data['fecha']					= $fecha;
		$data['fecha2']					= $fecha2;
		$data['fecha3']					= $fecha3;
		$data['fecha4']					= $fecha4;
		
		#print_r($data);
		
		
		$this->load->view('sie/informacionFinanciera/obtenerInformacionFinanciera',$data);
	}
	
	public function obtenerSaldosDia()
	{
		$fecha							= $this->input->post('fecha').'-01';
		$fecha2							= $this->input->post('fecha').'-15';
		$fecha3							= $this->reportes->obtenerUltimaFecha($fecha);
		$fecha4							= $this->reportes->obtenerFechaFinCriterio($fecha2,1,'month');
		
		$data['saldoDia']				= $this->sie->obtenerIngresosDia($fecha)-$this->sie->obtenerEgresosDia($fecha);
		$data['saldoDia2']				= $this->sie->obtenerIngresosDia($fecha2)-$this->sie->obtenerEgresosDia($fecha2);
		$data['saldoDia3']				= $this->sie->obtenerIngresosDia($fecha3)-$this->sie->obtenerEgresosDia($fecha3);
		$data['saldoDia4']				= $this->sie->obtenerIngresosDia($fecha4)-$this->sie->obtenerEgresosDia($fecha4);
		$data['fecha']					= $fecha;
		$data['fecha2']					= $fecha2;
		$data['fecha3']					= $fecha3;
		$data['fecha4']					= $fecha4;
		
		$this->load->view('sie/informacionFinanciera/obtenerSaldosDia',$data);
	}
	
	public function obtenerGraficaSaldosFecha()
	{
		$fecha							= $this->input->post('fecha');
		$idEscenario					= $this->input->post('idEscenario');
		$data['financiera']				= $this->sie->obtenerFinanciera();
		$data['cuentas']				= $this->sie->obtenerSaldoCuentas();

		$data['saldoDia']				= $data['financiera']->efectivo+$data['cuentas']+$this->proyeccion->sumarIngresosFecha($fecha,$idEscenario);
		$data['egresos']				= $this->proyeccion->sumarEgresosFecha($fecha,$idEscenario);
		$data['fecha']					= $fecha;
		$data['importe']				= $this->input->post('importe');
		
		$this->load->view('sie/informacionFinanciera/obtenerGraficaSaldosFecha',$data);
	}
	
	public function obtenerDetalleSaldoFecha()
	{
		$fecha							= $this->input->post('fecha');
		$numero							= $this->input->post('numero');
		$idEscenario					= $this->input->post('idEscenario');
		
		$data['ingresos']				= $this->proyeccion->obtenerIngresosFechaGrupo($fecha,$idEscenario);
		$data['egresos']				= $this->proyeccion->obtenerEgresosFechaGrupo($fecha,$idEscenario);
		$data['financiera']				= $this->sie->obtenerFinanciera();
		$data['cuentas']				= $this->sie->obtenerSaldoCuentas();
		$data['fecha']					= $fecha;
		
		$this->load->view('sie/informacionFinanciera/obtenerDetalleSaldoFecha',$data);
	}
	
	//CRÉDITOS
	
	public function creditos()
	{
		$data['titulo'] 		= "Créditos";
		$data['usuario'] 		= $this->usuarios->getUsuarios($this->idUsuario);
		$data['fecha'] 			= $this->fecha;

		$data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
		$data['menuActivo']		= 'finanzas'; 
		$data['subMenu']		= 'creditos'; 
		$data['pagina']			= 'sie/creditos/grafica'; 

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('64',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$data['pagina']		= 'accesos/index'; 
		}
		
		$data["breadcumb"]		= '<li class="breadcrumb-item"><a href="'.base_url().'sie/index">Finanzas</a></li><li class="breadcrumb-item active">Créditos</li>';

		$this->load->view("sie/paginaPrincipal", $data);
	}

	//GRÁFICA Y DETALLES DE CRÉDITOS
	public function obtenerGraficaCreditos()
	{
		$data['creditos']			= $this->creditos->obtenerCreditos();
		$data['total']				= $this->creditos->obtenerTotalAdeudos();
		
		$this->load->view('sie/creditos/obtenerGraficaCreditos',$data);
	}
	
	public function obtenerCreditosDetalles($limite=0)
	{
		$data['creditos'] 		= $this->creditos->obtenerCreditos();

		$this->load->view("sie/creditos/obtenerCreditosDetalles",$data);
	}
	
	
	//EGRESOS
	
	public function egresos()
	{
		$data['titulo'] 		= "Egresos";
		$data['usuario'] 		= $this->usuarios->getUsuarios($this->idUsuario);
		$data['fecha'] 			= $this->fecha;

		$data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
		$data['menuActivo']		= 'finanzas'; 
		$data['subMenu']		= 'egresos'; 
		$data['pagina']			= 'sie/egresos/index'; 

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('64',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$data['pagina']		= 'accesos/index'; 
		}
		
		$data["breadcumb"]		= '<li class="breadcrumb-item"><a href="'.base_url().'sie/index">Finanzas</a></li><li class="breadcrumb-item active">Egresos</li>';

		$this->load->view("sie/paginaPrincipal", $data);
	}
	

	//GRÁFICA Y DETALLES DE CRÉDITOS
	public function obtenerGraficaEgresos()
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
			
		$data['egresos']			= $this->sie->obtenerEgresosNivel($inicio,$fin);
		
		$this->load->view('sie/egresos/obtenerGrafica',$data);
	}
	
	public function obtenerDetallesEgresosConceptos($limite=0)
	{
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		
		$this->load->library('paginacion');

		$Pag["base_url"]			= base_url()."sie/obtenerDetallesEgresosConceptos/";
		$Pag["total_rows"]			= $this->sie->contarDetallesEgresosConceptos($inicio,$fin);
		$Pag["per_page"]			= 15;
		$Pag["num_links"]			= 5;
		$Pag["uri_segment"]			= 3;
		
		$this->paginacion->initialize($Pag);

		$data['egresos'] 			= $this->sie->obtenerDetallesEgresosConceptos($Pag["per_page"],$limite,$inicio,$fin);
		$data['inicio']  			= $limite+1;

		$this->load->view("sie/egresos/obtenerDetallesEgresosConceptos",$data);
	}
	
	
	//CALENDARIO DE PAGOS
	
	public function calendarioPagos()
	{
		$data['titulo'] 		= "Calendario de pagos";
		$data['usuario'] 		= $this->usuarios->getUsuarios($this->idUsuario);
		$data['fecha'] 			= $this->fecha;

		$data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$data['configuracion']	= $this->configuracion->obtenerConfiguraciones(1);
		$data['menuActivo']		= 'calendarioPagos'; 
		$data['subMenu']		= 'calendarioPagos'; 
		$data['pagina']			= 'sie/calendario/index'; 

		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('64',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$data['pagina']		= 'accesos/index'; 
		}
		
		$data["breadcumb"]		= '<li class="breadcrumb-item"><a href="'.base_url().'sie/index">Finanzas</a></li><li class="breadcrumb-item active">Calendario de pagos</li>';

		$this->load->view("sie/paginaPrincipal", $data);
	}

	public function obtenerCalendarioPagos()
	{
		$mes		= $this->input->post('mes');
		$anio		= $this->input->post('anio');
		
		$prefs = array 
		(
			'show_next_prev'  => TRUE,
			'next_prev_url'   => 'obtenerCalendarioPagos('
		);


		$this->load->library('calendar',$prefs);
		
		$data['egresos']	= $this->proyeccion->obtenerEgresosMes($mes,$anio);
		$data['mes']		= $mes;
		$data['anio']		= $anio;
		
		#var_dump($data);
		$this->load->view('sie/calendario/obtenerCalendarioPagos',$data);
	}
	
	public function obtenerDetallesCalendario($limite=0)
	{
		$fecha					= $this->input->post('fecha');
		
		$data['egresos'] 		= $this->proyeccion->obtenerEgresosDia($fecha);
		$data['creditos'] 		= $this->creditos->obtenerCreditosDia($fecha);
		$data['fecha'] 			= $fecha;

		$this->load->view("sie/calendario/obtenerDetallesCalendario",$data);
	}
	
	
}
?>
