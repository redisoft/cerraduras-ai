<?php
class Nomina extends CI_Controller
{
    protected $idUsuario;
	protected $idLicencia;
	protected $fecha;
	protected $_iduser;
	protected $cuota;

	function __construct()
	{
		parent::__construct();

		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		$this->config->load('js',TRUE);
		$this->config->load('style', TRUE);
		
		$this->idUsuario 		=$this->session->userdata('id');
		$this->_iduser 			= $this->session->userdata('id');
		$this->fecha 			=date('Y-m-d H:i:s');
		$this->_csstyle 		= $this->config->item('style');
		$this->_jss				=$this->config->item('js');

        $this->load->model("modelousuario","usuarios");
        $this->load->model("facturacion_modelo","facturacion");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("nomina_modelo","nomina");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}

	//PUESTOS
	#-----------------------------------------------------------------------------------------------------------------------------------------#
	public function puestos()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			=$this->_csstyle["cassadmin"];
		$Data['csmenu']				=$this->_csstyle["csmenu"];
		$Data['csvalidate']			=$this->_csstyle["csvalidate"];
		$Data['csui']				=$this->_csstyle["csui"];
		$Data['nameusuario']		=$this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		=$this->fecha;
		$Data['Jry']				=$this->_jss['jquery'];
		$Data['Jqui']				=$this->_jss['jqueryui'];                  
		$Data['permisos']			=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			='puestos'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);    
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('47',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= '<a href="'.base_url().'nomina">Recibos de nómina</a> > Puestos';
		
		$this->load->view("nomina/puestos/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}

	public function obtenerPuestos()
	{
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('47',$this->session->userdata('rol'));
		$data['puestos']	= $this->nomina->obtenerPuestos();
		
		$this->load->view("nomina/puestos/obtenerPuestos",$data);
	}
	
	public function formularioPuestos()
	{
		$this->load->view("nomina/puestos/formularioPuestos");
	}
	
	public function obtenerPuesto()
	{
		if (!empty($_POST))
		{
			$data['puesto']	=$this->nomina->obtenerPuesto($this->input->post('idPuesto'));
			$this->load->view("nomina/puestos/obtenerPuesto",$data);
		}
	}
	
	public function registrarPuesto()
	{
		if (!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->nomina->registrarPuesto());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarPuesto()
	{
		if (!empty($_POST))
		{
			echo $this->nomina->editarPuesto();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarPuesto()
	{
		if (!empty($_POST))
		{
			echo $this->nomina->borrarPuesto($this->input->post('idPuesto'));
		}
		else
		{
			echo "0";
		}
	}
	
	//DEPARTAMENTOS
	#-----------------------------------------------------------------------------------------------------------------------------------------#
	public function departamentos()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			= $this->_csstyle["cassadmin"];
		$Data['csmenu']				= $this->_csstyle["csmenu"];
		$Data['csvalidate']			= $this->_csstyle["csvalidate"];
		$Data['csui']				= $this->_csstyle["csui"];
		$Data['nameusuario']		= $this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		= $this->fecha;
		$Data['Jry']				= $this->_jss['jquery'];
		$Data['Jqui']				= $this->_jss['jqueryui'];                  
		$Data['permisos']			= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			= 'departamentos'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);    
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('46',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= '<a href="'.base_url().'nomina">Recibos de nómina</a> > Departamentos';
		
		$this->load->view("nomina/departamentos/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}

	public function obtenerDepartamentos()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('46',$this->session->userdata('rol'));
		$data['departamentos']	= $this->nomina->obtenerDepartamentos();
		
		$this->load->view("nomina/departamentos/obtenerDepartamentos",$data);
	}
	
	public function formularioDepartamentos()
	{
		$this->load->view("nomina/departamentos/formularioDepartamentos");
	}
	
	public function obtenerDepartamento()
	{
		if (!empty($_POST))
		{
			$data['departamento']	= $this->nomina->obtenerDepartamento($this->input->post('idDepartamento'));
			$this->load->view("nomina/departamentos/obtenerDepartamento",$data);
		}
	}
	
	public function registrarDepartamento()
	{
		if (!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->nomina->registrarDepartamento());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarDepartamento()
	{
		if (!empty($_POST))
		{
			echo $this->nomina->editarDepartamento();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarDepartamento()
	{
		if (!empty($_POST))
		{
			echo $this->nomina->borrarDepartamento($this->input->post('idDepartamento'));
		}
		else
		{
			echo "0";
		}
	}
	
	//PERCEPCIONES
	#-----------------------------------------------------------------------------------------------------------------------------------------#
	public function percepciones()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			=$this->_csstyle["cassadmin"];
		$Data['csmenu']				=$this->_csstyle["csmenu"];
		$Data['csvalidate']			=$this->_csstyle["csvalidate"];
		$Data['csui']				=$this->_csstyle["csui"];
		$Data['nameusuario']		=$this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		=$this->fecha;
		$Data['Jry']				=$this->_jss['jquery'];
		$Data['Jqui']				=$this->_jss['jqueryui'];                  
		$Data['permisos']			=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			='percepciones'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);    
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('49',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= '<a href="'.base_url().'nomina">Recibos de nómina</a> > Percepciones';
		
		$this->load->view("nomina/percepciones/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}

	public function obtenerPercepciones($limite=0)
	{
		$paginador["base_url"]		= base_url()."nomina/obtenerPercepciones/";
		$paginador["total_rows"]	=$this->nomina->contarCatalogoPercepciones();//Total de Registros
		$paginador["per_page"]		=25;
		$paginador["num_links"]		=5;
		
		$this->pagination->initialize($paginador);
		
		$data['permiso']		=$this->configuracion->obtenerPermisosBoton('49',$this->session->userdata('rol'));
		$data['percepciones']	=$this->nomina->obtenerCatalogoPercepciones($paginador["per_page"],$limite);
		$data['limite']			=$limite+1;
		
		
		$this->load->view("nomina/percepciones/obtenerPercepciones",$data);
	}
	
	public function formularioPercepciones()
	{
		$data['percepciones']			=$this->nomina->obtenerPercepciones();
		$this->load->view("nomina/percepciones/formularioPercepciones",$data);
	}
	
	public function obtenerPercepcion()
	{
		if (!empty($_POST))
		{
			$data['percepcion']		=$this->nomina->obtenerPercepcion($this->input->post('idCatalogoPercepcion'));
			$data['percepciones']	=$this->nomina->obtenerPercepciones();
			
			$this->load->view("nomina/percepciones/obtenerPercepcion",$data);
		}
	}
	
	public function registrarPercepcion()
	{
		if (!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->nomina->registrarPercepcion());
		}
		else
		{
			echo json_encode(array('0',errorRegistro));
		}
	}
	
	public function editarPercepcion()
	{
		if (!empty($_POST))
		{
			echo $this->nomina->editarPercepcion();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarPercepcion()
	{
		if (!empty($_POST))
		{
			echo $this->nomina->borrarPercepcion($this->input->post('idCatalogoPercepcion'));
		}
		else
		{
			echo "0";
		}
	}
	
	//DEDUCCIONES
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function deducciones()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			=$this->_csstyle["cassadmin"];
		$Data['csmenu']				=$this->_csstyle["csmenu"];
		$Data['csvalidate']			=$this->_csstyle["csvalidate"];
		$Data['csui']				=$this->_csstyle["csui"];
		$Data['nameusuario']		=$this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		=$this->fecha;
		$Data['Jry']				=$this->_jss['jquery'];
		$Data['Jqui']				=$this->_jss['jqueryui'];                  
		$Data['permisos']			=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			='deducciones'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);    
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('48',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= '<a href="'.base_url().'nomina">Recibos de nómina</a> > Deducciones';
		
		$this->load->view("nomina/deducciones/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}
	
	public function obtenerDeducciones($limite=0)
	{
		$paginador["base_url"]		= base_url()."nomina/obtenerDeducciones/";
		$paginador["total_rows"]	=$this->nomina->contarCatalogoDeducciones();//Total de Registros
		$paginador["per_page"]		=25;
		$paginador["num_links"]		=5;
		
		$this->pagination->initialize($paginador);
		
		$data['permiso']		=$this->configuracion->obtenerPermisosBoton('48',$this->session->userdata('rol'));
		$data['deducciones']	=$this->nomina->obtenerCatalogoDeducciones($paginador["per_page"],$limite);
		$data['limite']			=$limite+1;
		
		
		$this->load->view("nomina/deducciones/obtenerDeducciones",$data);
	}
	
	public function formularioDeducciones()
	{
		$data['deducciones']			=$this->nomina->obtenerDeducciones();
		$this->load->view("nomina/deducciones/formularioDeducciones",$data);
	}
	
	public function obtenerDeduccion()
	{
		if (!empty($_POST))
		{
			$data['deduccion']		=$this->nomina->obtenerDeduccion($this->input->post('idCatalogoDeduccion'));
			$data['deducciones']	=$this->nomina->obtenerDeducciones();
			
			$this->load->view("nomina/deducciones/obtenerDeduccion",$data);
		}
	}
	
	public function registrarDeduccion()
	{
		if (!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->nomina->registrarDeduccion());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarDeduccion()
	{
		if (!empty($_POST))
		{
			echo $this->nomina->editarDeduccion();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarDeduccion()
	{
		if (!empty($_POST))
		{
			echo $this->nomina->borrarDeduccion($this->input->post('idCatalogoDeduccion'));
		}
		else
		{
			echo "0";
		}
	}
	
	//EMPLEADOS
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function empleados()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			=$this->_csstyle["cassadmin"];
		$Data['csmenu']				=$this->_csstyle["csmenu"];
		$Data['csvalidate']			=$this->_csstyle["csvalidate"];
		$Data['csui']				=$this->_csstyle["csui"];
		$Data['nameusuario']		=$this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		=$this->fecha;
		$Data['Jry']				=$this->_jss['jquery'];
		$Data['Jqui']				=$this->_jss['jqueryui'];                  
		$Data['permisos']			=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			='empleados'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);    
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('45',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= '<a href="'.base_url().'nomina">Recibos de nómina</a> > Empleados';
		
		$this->load->view("nomina/empleados/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}
	
	public function obtenerEmpleados($limite=0)
	{
		$paginador["base_url"]		= base_url()."nomina/obtenerEmpleados/";
		$paginador["total_rows"]	=$this->nomina->contarEmpleados();//Total de Registros
		$paginador["per_page"]		=25;
		$paginador["num_links"]		=5;
		
		$this->pagination->initialize($paginador);
		
		$data['permiso']		=$this->configuracion->obtenerPermisosBoton('45',$this->session->userdata('rol'));
		$data['empleados']		=$this->nomina->obtenerEmpleados($paginador["per_page"],$limite);
		$data['limite']			=$limite+1;

		$this->load->view("nomina/empleados/obtenerEmpleados",$data);
	}
	
	public function formularioEmpleados()
	{
		$data['departamentos']		=$this->nomina->obtenerDepartamentos();
		$data['puestos']			=$this->nomina->obtenerPuestos();
		$data['bancos']				=$this->nomina->obtenerBancos();
		$data['regimen']			=$this->nomina->obtenerRegimen();
		$data['riesgo']				=$this->nomina->obtenerRiesgo();
		
		$this->load->view("nomina/empleados/formularioEmpleados",$data);
	}
	
	public function obtenerEmpleado()
	{
		if (!empty($_POST))
		{
			$data['departamentos']		=$this->nomina->obtenerDepartamentos();
			$data['puestos']			=$this->nomina->obtenerPuestos();
			$data['bancos']				=$this->nomina->obtenerBancos();
			$data['regimen']			=$this->nomina->obtenerRegimen();
			$data['riesgo']				=$this->nomina->obtenerRiesgo();
			$data['empleado']			=$this->nomina->obtenerEmpleado($this->input->post('idEmpleado'));
			
			$this->load->view("nomina/empleados/obtenerEmpleado",$data);
		}
	}
	
	public function registrarEmpleado()
	{
		if (!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->nomina->registrarEmpleado());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarEmpleado()
	{
		if (!empty($_POST))
		{
			echo $this->nomina->editarEmpleado();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarEmpleado()
	{
		if (!empty($_POST))
		{
			echo $this->nomina->borrarEmpleado($this->input->post('idEmpleado'));
		}
		else
		{
			echo "0";
		}
	}
	
	//NÓMINA
	#>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function index()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			=$this->_csstyle["cassadmin"];
		$Data['csmenu']				=$this->_csstyle["csmenu"];
		$Data['csvalidate']			=$this->_csstyle["csvalidate"];
		$Data['csui']				=$this->_csstyle["csui"];
		$Data['nameusuario']		=$this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		=$this->fecha;
		$Data['Jry']				=$this->_jss['jquery'];
		$Data['Jqui']				=$this->_jss['jqueryui']; 
		$Data['Jquical']			=$this->_jss['jquerycal'];                 
		$Data['permisos']			=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			='recibos'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);    
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('44',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= 'Recibos de nómina';

		$this->load->view("nomina/recibos/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}
	
	public function obtenerRecibos($limite=0)
	{
		$fecha			=$this->input->post('fecha');
		$mes			=strlen($fecha)>4?substr($fecha,5,2):'mes';
		$anio			=strlen($fecha)>4?substr($fecha,0,4):'anio';
		$criterio		=$this->input->post('criterio');
		
		#-----------------------------PAGINACION--------------------------------------#
		$paginacion["base_url"]		= base_url()."nomina/obtenerRecibos/";
		$paginacion["total_rows"]	=$this->nomina->contarRecibos($mes,$anio,$criterio);
		$paginacion["per_page"]		=20;
		$paginacion["num_links"]	=5;
		
		$this->pagination->initialize($paginacion);

		$data['facturas'] 		= $this->nomina->obtenerRecibos($paginacion["per_page"],$limite,$mes,$anio,$criterio);
		#$data['total'] 			= $this->reportes->sumarFacturas($mes,$anio,$idCliente,$idFactura);
		$data['permiso']		=$this->configuracion->obtenerPermisosBoton('1',$this->session->userdata('rol'));
		$data['mes']			=$mes;
		$data['anio']			=$anio;
		$data['criterio']		=$criterio;
		
		$this->load->view('nomina/recibos/obtenerRecibos',$data);
	}
	
	public function formularioNomina()
	{
		$data['emisores']		=$this->facturacion->obtenerEmisores();
		$data['metodos']		= $this->configuracion->obtenerMetodosPago();
		
		$this->load->view("nomina/recibos/formularioNomina",$data);
	}
	
	public function listaEmpleados()
	{
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('45',$this->session->userdata('rol'));
		
		$this->load->view("nomina/empleados/index",$data);
	}
	
	public function listaPercepciones()
	{
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('49',$this->session->userdata('rol'));
		
		$this->load->view("nomina/percepciones/index",$data);
	}
	
	public function listaDeducciones()
	{
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('48',$this->session->userdata('rol'));
		
		$this->load->view("nomina/deducciones/index",$data);
	}
	
	public function obtenerDiasTrabajados()
	{
		$dias	= $this->nomina->obtenerDiasTrabajados($this->input->post('inicio'),$this->input->post('fin'));
		echo $dias>0?$dias:0;
	}
	
	public function registrarRecibo($idEmpleado)
	{
		if (!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->nomina->registrarRecibo($idEmpleado));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
}
?>
