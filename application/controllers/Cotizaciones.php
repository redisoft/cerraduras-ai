<?php
class Cotizaciones extends CI_Controller
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
		$this->load->model("contabilidad_modelo","contabilidad");
		$this->load->model("estaciones_modelo","estaciones");
			
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
  	}

	#========================================================================================================#
	#=============================================  COTIZACIONES ============================================#
	#========================================================================================================#
	
	public function index()
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
		$Data['menuActivo']		= 'cotizaciones'; 
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
		
		$data["breadcumb"]		= '<a href="'.base_url().'clientes">Clientes</a> > Cotizaciones';

		$this->load->view("cotizaciones/index",$data);
		$this->load->view("pie",$Data);
	}

	public function obtenerCotizaciones($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$orden					= $this->input->post('orden');
		$idEstacion				= $this->input->post('idEstacion');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."cotizaciones/obtenerCotizaciones/";
		$Pag["total_rows"]		= $this->ventas->contarCotizaciones($criterio,$inicio,$fin,$data['permiso'][4]->activo,$idEstacion,$data['permiso'][5]->activo);//Total de Registros
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		$data['cotizaciones']	= $this->ventas->obtenerCotizaciones($Pag["per_page"],$limite,$criterio,$inicio,$fin,$orden,$data['permiso'][4]->activo,$idEstacion,$data['permiso'][5]->activo);
		$data['estaciones']		= $this->estaciones->obtenerRegistros();
		$data['permisoCrm']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['limite']			= $limite+1;
		$data['orden']			= $orden;
		$data['idEstacion']		= $idEstacion;
		$data['desglose']		= $this->input->post('desglose');
		
		$this->load->view('cotizaciones/obtenerCotizaciones',$data);
	}
	
	public function borrarCotizacion()
	{
		if(!empty($_POST))
		{
			echo $this->clientes->borrarCotizacion($this->input->post('idCotizacion'));
		}
	}
	
	public function obtenerDetallesCotizacion()
	{
		$idCotizacion			= $this->input->post('idCotizacion');
		$data['cotizacion'] 	= $this->ventas->obtenerRemision($idCotizacion);
		$data['cliente'] 		= $this->ventas->obtenerCliente($data['cotizacion']->idCliente);
		$data['folio'] 			= $this->clientes->obtenerFolio(0);
		$data['idCotizacion'] 	= $idCotizacion;
		
		$this->load->view('cotizaciones/obtenerDetallesCotizacion',$data);
	}
	
	public function convertirOrdenVenta()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			$venta	=$this->ventas->convertirOrdenVenta();
			
			$venta[0]=="1"?$this->session->set_userdata('notificacion','La venta se ha registrado correctamente'):'';
				
			echo json_encode($venta);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function llamadas($idCliente=0)
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
		$Data['menuActivo']		= 'llamadas'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		
		$data['permisoVenta']		= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoContacto']	= $this->configuracion->obtenerPermisosBoton('6',$this->session->userdata('rol'));
		$data['permisoFactura']		= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		$data['permisoCrm']			= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));
		$data['permisoCotizacion']	= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		$data['permisoSie']			= $this->configuracion->obtenerPermisosBoton('64',$this->session->userdata('rol'));
		$data['permisoMatricula']	= $this->configuracion->obtenerPermisosBoton('68',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data["breadcumb"]		= '<a href="'.base_url().'clientes">Clientes</a> > Seguimientos';
		$data["cliente"]		= $this->clientes->obtenerClienteEmpresa($idCliente);
		$data["idCliente"]		= $idCliente;
		$data["idRol"]			= $this->_role;
		
		if($idCliente>0)
		{
			$data["breadcumb"]		= '<a href="'.base_url().'clientes">Clientes</a> > <a href="'.base_url().'clientes/index/'.$idCliente.'">'.$data['cliente'].'</a> > Seguimientos';
		}

		$this->load->view("llamadas/index",$data);
		$this->load->view("pie",$Data);
	}

	public function obtenerLlamadas($limite=0)
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
		
		$Pag["base_url"]		= base_url()."cotizaciones/obtenerLlamadas/";
		$Pag["total_rows"]		= $this->clientes->contarLlamadas($criterio,$inicio,$fin,$idStatus,$idServicio,$data['permiso'][4]->activo,$idUsuarioRegistro,$idResponsable,$idEstatus,$idPrograma);//Total de Registros
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		$data['llamadas']			= $this->clientes->obtenerLlamadas($Pag["per_page"],$limite,$criterio,$inicio,$fin,$idStatus,$idServicio,$data['permiso'][4]->activo,$idUsuarioRegistro,$idResponsable,$idEstatus,$idPrograma);
		$data['status']				= $this->configuracion->obtenerStatus(1);
		$data['estatus']			= $this->configuracion->obtenerEstatus(0);
		$data['servicios']			= $this->configuracion->obtenerServicios(1);
		$data['responsables']		= $this->configuracion->obtenerResponsables(1);
		$data['promotores']			= $this->configuracion->obtenerResponsables($data['permiso'][4]->activo,0,1);
		$data['programas']			= $this->configuracion->obtenerProgramas();
		
		$data['idPrograma']			= $idPrograma;
		$data['idStatus']			= $idStatus;
		$data['idServicio']			= $idServicio;
		$data['idUsuarioRegistro']	= $idUsuarioRegistro;
		$data['idResponsable']		= $idResponsable;
		$data['idEstatus']			= $idEstatus;
		$data['limite']				= $limite+1;
		$data['registros']			= $Pag["total_rows"];
		
		if(sistemaActivo=='IEXE')
		{
			$this->load->view('llamadas/obtenerLlamadasIexe',$data);
		}
		else
		{
			$this->load->view('llamadas/obtenerLlamadas',$data);
		}
		
	}
	
	public function excelReporteLlamadas()
	{
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idStatus				= $this->input->post('idStatus');
		$idServicio				= $this->input->post('idServicio');
		$idUsuarioRegistro		= $this->input->post('idUsuarioRegistro');
		$idResponsable			= $this->input->post('idResponsable');
		$idEstatus				= $this->input->post('idEstatus');
		$idPrograma				= $this->input->post('idPrograma');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('4',$this->session->userdata('rol'));

		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('excel/PHPExcel');

		$data['llamadas']			= $this->clientes->obtenerLlamadas(0,0,$criterio,$inicio,$fin,$idStatus,$idServicio,$data['permiso'][4]->activo,$idUsuarioRegistro,$idResponsable,$idEstatus,$idPrograma);

		$this->load->view('llamadas/excel/excelReporte',$data);
	}
	
	//COTIZACIONES DESASIGNADAS
	public function obtenerCotizacionAsignada()
	{
		$idCotizacion			= $this->input->post('idCotizacion');
		$data['cotizacion'] 	= $this->ventas->obtenerRemision($idCotizacion);
		$data['cliente'] 		= $this->ventas->obtenerCliente($data['cotizacion']->idCliente);
		$data['motivos']		= $this->motivos->obtenerMotivos(1000,0);
		$data['idCotizacion'] 	= $idCotizacion;
		
		$this->load->view('cotizaciones/obtenerCotizacionAsignada',$data);
	}
	
	public function registrarCotizacionAsignada()
	{
		if(!empty($_POST))
		{
			echo $this->ventas->registrarCotizacionAsignada();
		}
		else
		{
			echo "0";
		}
	}
	
	public function desasignadas()
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
		$Data['menuActivo']		='cotizaciones'; 
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
		
		$data["breadcumb"]		= '<a href="'.base_url().'clientes">Clientes</a> > Cotizaciones no asignadas';

		$this->load->view("cotizaciones/desasignadas",$data);
		$this->load->view("pie",$Data);
	}

	public function obtenerCotizacionesAsignadas($limite=0)
	{
		$criterio					= $this->input->post('criterio');
		$idMotivo					= $this->input->post('idMotivo');
		
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		
		$Pag["base_url"]			= base_url()."cotizaciones/obtenerCotizacionesAsignadas/";
		$Pag["total_rows"]			= $this->ventas->contarCotizacionesAsignadas($criterio,$idMotivo,$data['permiso'][4]->activo);//Total de Registros
		$Pag["per_page"]			= 30;
		$Pag["num_links"]			= 5;
		
		$this->pagination->initialize($Pag);
		
		$data['cotizaciones']		= $this->ventas->obtenerCotizacionesAsignadas($Pag["per_page"],$limite,$criterio,$idMotivo,$data['permiso'][4]->activo);
		$data['motivos']			= $this->motivos->obtenerMotivos(100,0);
		$data['idMotivo']			= $idMotivo;
		$data['limite']				= $limite+1;
		
		$this->load->view('cotizaciones/obtenerCotizacionesAsignadas',$data);
	}
	
	#========================================================================================================#
	#=============================================  PROCESADAS ============================================#
	#========================================================================================================#
	
	public function procesadas()
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
		$Data['menuActivo']		='cotizaciones'; 
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
		
		$data["breadcumb"]		= '<a href="'.base_url().'clientes">Clientes</a> > Cotizaciones procesadas';

		$this->load->view("cotizaciones/procesadas",$data);
		$this->load->view("pie",$Data);
	}

	public function obtenerProcesadas($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."cotizaciones/obtenerProcesadas/";
		$Pag["total_rows"]		= $this->ventas->contarProcesadas($criterio,$data['permiso'][4]->activo);//Total de Registros
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		$data['cotizaciones']		= $this->ventas->obtenerProcesadas($Pag["per_page"],$limite,$criterio,$data['permiso'][4]->activo);
		$data['limite']				= $limite+1;
		
		$this->load->view('cotizaciones/obtenerProcesadas',$data);
	}
	
	public function obtenerVentaEditar()
	{
		$idCotizacion			= $this->input->post('idCotizacion');
		$data['cotizacion']		= $this->clientes->obtenerCotizacion($idCotizacion);
		$data['cliente']		= $this->clientes->obtenerCliente($data['cotizacion']->idCliente);
		$data['productos']		= $this->clientes->detalleProductosRemision($idCotizacion);
		$data['ivas']			= $this->configuracion->obtenerIvas();
		$data['claveDescuento']	= $this->configuracion->obtenerUsuarioDescuento($this->_iduser);
		
		$this->load->view('clientes/ventas/obtenerVentaEditar',$data);
	}
	
	public function obtenerContactosClienteCotizacion()
	{
		if(!empty($_POST))
		{
			$data['contactos']	= $this->clientes->obtenerContactos($this->input->post('idCliente'));
			
			$this->load->view('clientes/contactos/obtenerContactosClienteCotizacion',$data);
		}
		else
		{
			echo '0';
		}
	}
}
?>
