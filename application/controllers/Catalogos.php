<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Catalogos extends CI_Controller 
{
	protected $_jss;
	protected $_csstyle;
	protected $cuota;
	  
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('catalogos_modelo','catalogos');
		$this->load->model('modelo_configuracion','configuracion');
		$this->load->model('modelousuario','usuarios');
		#$this->load->model('facturacion_modelo','facturacion');
		
		if( ! $this->redux_auth->logged_in() )
		{
			redirect(base_url().'login');
		}
		
		$this->config->load('js',TRUE);
		$this->config->load('style', TRUE);
		
		$this->_fechaActual 	= mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_iduser 			= $this->session->userdata('id');
		$this->_csstyle 		= $this->config->item('style');
		$this->_jss				= $this->config->item('js');
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}
	
	//DEPARTAMENTOS
	public function departamentos()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['jvalidate']		= $this->_jss['jvalidate'];
		$Data['nameusuario']	= $this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= 'Departamentos';
		
		$this->load->view("configuracion/departamentos/departamentos",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerDepartamentos()
	{
		$data['departamentos']		= $this->catalogos->obtenerDepartamentos();
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		$this->load->view("configuracion/departamentos/obtenerDepartamentos",$data);
	}
	
	public function formularioDepartamentos()
	{
		$this->load->view("configuracion/departamentos/formularioDepartamentos");
	}
	
	public function obtenerDepartamento()
	{
		if(!empty ($_POST))
		{
			$data['departamento']	= $this->catalogos->obtenerDepartamento($this->input->post('idDepartamento'));
			
			$this->load->view("configuracion/departamentos/obtenerDepartamento",$data);
		}
	}
	
	public function registrarDepartamento()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->catalogos->registrarDepartamento());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarDepartamento()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->catalogos->editarDepartamento());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarDepartamento()
	{
		if (!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']	= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				echo "0";
				return;
			}
	
			echo $this->catalogos->borrarDepartamento($this->input->post('idDepartamento'));
		}
		else
		{
			echo "0";
		}
	}
	
	//MARCAS
	public function marcas()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];   
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];
		$Data['jvalidate']		= $this->_jss['jvalidate'];
		$Data['nameusuario']	= $this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;    
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'configuracion'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]		= 'Marcas';
		
		$this->load->view("configuracion/marcas/marcas",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerMarcas()
	{
		$data['marcas']			= $this->catalogos->obtenerMarcas();
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		$this->load->view("configuracion/marcas/obtenerMarcas",$data);
	}
	
	public function formularioMarcas()
	{
		$this->load->view("configuracion/marcas/formularioMarcas");
	}
	
	public function obtenerMarca()
	{
		if(!empty ($_POST))
		{
			$data['marca']	= $this->catalogos->obtenerMarca($this->input->post('idMarca'));
			
			$this->load->view("configuracion/marcas/obtenerMarca",$data);
		}
	}
	
	public function registrarMarca()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->catalogos->registrarMarca());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarMarca()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->catalogos->editarMarca());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarMarca()
	{
		if (!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']	= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				echo "0";
				return;
			}
	
			echo $this->catalogos->borrarMarca($this->input->post('idMarca'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerMarcasProveedor($idProveedor=0)
	{
		$registros			= $this->catalogos->obtenerMarcasProveedor($idProveedor);
		
		if($registros!=null)
		{
			foreach ($registros as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	//VARIABLES
	#-----------------------------------------------------------------------------------------------------------------------------------------#
	public function variables()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		
		/*if($data['permiso']->leer=='0' and $data['permiso']->escribir=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}*/
		
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
		$Data['menuActivo']			= 'variables'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);    
		$this->load->view("principal",$Data);
		$this->load->view("inventarioProductos/variables/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}

	public function obtenerVariables()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		$data['variables']		= $this->catalogos->obtenerVariables();
		
		$this->load->view("inventarioProductos/variables/obtenerVariables",$data);
	}
	
	public function formularioVariables()
	{
		$this->load->view("inventarioProductos/variables/formularioVariables");
	}
	
	public function obtenerVariable()
	{
		if (!empty($_POST))
		{
			$data['variable']	=$this->catalogos->obtenerVariable($this->input->post('idVariable'));
			$this->load->view("inventarioProductos/variables/obtenerVariable",$data);
		}
	}
	
	public function registrarVariable()
	{
		if (!empty($_POST))
		{
			echo $this->catalogos->registrarVariable();
		}
		else
		{
			echo "0";
		}
	}
	
	public function editarVariable()
	{
		if (!empty($_POST))
		{
			echo $this->catalogos->editarVariable();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarVariable()
	{
		if (!empty($_POST))
		{
			echo $this->catalogos->borrarVariable($this->input->post('idVariable'),$this->input->post('tipo'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function listaVariables()
	{
		$data['permiso'] 	= $this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		$data['variable']	= $this->catalogos->obtenerTipoVariable($this->input->post('tipo'));
		
		$this->load->view("inventarioProductos/variables/index",$data);
	}
	
	//AUTOCOMPLETADOS CATÁLOGOS
	
	public function obtenerCatalogos($tipo,$numero=0,$idLinea=0)
	{
		$datos = $this->catalogos->obtenerCatalogos($this->input->get('term'),$tipo,$numero,$idLinea);
		
		if($datos!=null)
		{
			foreach ($datos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
	
	//NIVELES
	
	public function listaNiveles1()
	{
		$data['permiso'] 	= $this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		
		$this->load->view("administracion/niveles/nivel1/index",$data);
	}

	public function obtenerNiveles1($limite=0)
	{
		$criterio				= $this->input->post('criterio');

		$Pag["base_url"]		= base_url()."catalogos/obtenerNiveles1/";
		$Pag["total_rows"]		= $this->catalogos->contarNiveles1($criterio);
		$Pag["per_page"]		= 10;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;
		
		$this->pagination->initialize($Pag);

		$data['niveles'] 		= $this->catalogos->obtenerNiveles1($Pag["per_page"],$limite,$criterio);
		$data['inicio']  		= $limite+1;

		$this->load->view("administracion/niveles/nivel1/obtenerNiveles",$data);
	}
	
	public function formularioNiveles1()
	{
		$this->load->view("administracion/niveles/nivel1/formularioNiveles");
	}
	
	public function obtenerNivel1()
	{
		if (!empty($_POST))
		{
			$data['nivel']	= $this->catalogos->obtenerNivel1($this->input->post('idNivel1'));
			
			$this->load->view("administracion/niveles/nivel1/obtenerNivel",$data);
		}
	}
	
	public function registrarNivel1()
	{
		if (!empty($_POST))
		{
			echo $this->catalogos->registrarNivel1();
		}
		else
		{
			echo "0";
		}
	}
	
	public function editarNivel1()
	{
		if (!empty($_POST))
		{
			echo $this->catalogos->editarNivel1();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarNivel1()
	{
		if (!empty($_POST))
		{
			echo $this->catalogos->borrarNivel1($this->input->post('idNivel1'));
		}
		else
		{
			echo "0";
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//NIVEL 2
	
	public function listaNiveles2()
	{
		$data['permiso'] 	= $this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		
		$this->load->view("administracion/niveles/nivel2/index",$data);
	}

	public function obtenerNiveles2($limite=0)
	{
		$criterio				= $this->input->post('criterio');

		$Pag["base_url"]		= base_url()."catalogos/obtenerNiveles2/";
		$Pag["total_rows"]		= $this->catalogos->contarNiveles2($criterio);
		$Pag["per_page"]		= 10;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;
		
		$this->pagination->initialize($Pag);

		$data['niveles'] 		= $this->catalogos->obtenerNiveles2($Pag["per_page"],$limite,$criterio);
		$data['inicio']  		= $limite+1;

		$this->load->view("administracion/niveles/nivel2/obtenerNiveles",$data);
	}
	
	public function formularioNiveles2()
	{
		$data['nivel1']	= $this->catalogos->obtenerNiveles1();
		
		$this->load->view("administracion/niveles/nivel2/formularioNiveles",$data);
	}
	
	public function obtenerNivel2()
	{
		if (!empty($_POST))
		{
			$data['nivel']	= $this->catalogos->obtenerNivel2($this->input->post('idNivel2'));
			
			$this->load->view("administracion/niveles/nivel2/obtenerNivel",$data);
		}
	}
	
	public function registrarNivel2()
	{
		if (!empty($_POST))
		{
			echo $this->catalogos->registrarNivel2();
		}
		else
		{
			echo "0";
		}
	}
	
	public function editarNivel2()
	{
		if (!empty($_POST))
		{
			echo $this->catalogos->editarNivel2();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarNivel2()
	{
		if (!empty($_POST))
		{
			echo $this->catalogos->borrarNivel2($this->input->post('idNivel2'));
		}
		else
		{
			echo "0";
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//NIVEL 3
	
	public function listaNiveles3()
	{
		$data['permiso'] 	= $this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		
		$this->load->view("administracion/niveles/nivel3/index",$data);
	}

	public function obtenerNiveles3($limite=0)
	{
		$criterio				= $this->input->post('criterio');

		$Pag["base_url"]		= base_url()."catalogos/obtenerNiveles3/";
		$Pag["total_rows"]		= $this->catalogos->contarNiveles3($criterio);
		$Pag["per_page"]		= 10;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;
		
		$this->pagination->initialize($Pag);

		$data['niveles'] 		= $this->catalogos->obtenerNiveles3($Pag["per_page"],$limite,$criterio);
		$data['inicio']  		= $limite+1;

		$this->load->view("administracion/niveles/nivel3/obtenerNiveles",$data);
	}
	
	public function formularioNiveles3()
	{
		$data['nivel2']	= $this->catalogos->obtenerNiveles2();
		
		$this->load->view("administracion/niveles/nivel3/formularioNiveles",$data);
	}
	
	public function obtenerNivel3()
	{
		if (!empty($_POST))
		{
			$data['nivel']	= $this->catalogos->obtenerNivel3($this->input->post('idNivel3'));
			
			$this->load->view("administracion/niveles/nivel3/obtenerNivel",$data);
		}
	}
	
	public function registrarNivel3()
	{
		if (!empty($_POST))
		{
			echo $this->catalogos->registrarNivel3();
		}
		else
		{
			echo "0";
		}
	}
	
	public function editarNivel3()
	{
		if (!empty($_POST))
		{
			echo $this->catalogos->editarNivel3();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarNivel3()
	{
		if (!empty($_POST))
		{
			echo $this->catalogos->borrarNivel3($this->input->post('idNivel3'));
		}
		else
		{
			echo "0";
		}
	}
	
	
	//OBTENER NIVELES PARA REGISTRO
	
	public function obtenerNiveles1Catalogo()
	{
		#if (!empty($_POST))
		{
			$data['nivel']	= $this->catalogos->obtenerNiveles1();
			
			$this->load->view("administracion/niveles/nivel1/obtenerNivelesCatalogo",$data);
		}
	}
	
	public function obtenerNiveles2Catalogo()
	{
		if (!empty($_POST))
		{
			$data['nivel']	= $this->catalogos->obtenerNiveles2Catalogo($this->input->post('idNivel1'));
			
			$this->load->view("administracion/niveles/nivel2/obtenerNivelesCatalogo",$data);
		}
	}
	
	public function obtenerNiveles3catalogo()
	{
		if (!empty($_POST))
		{
			$data['nivel']	= $this->catalogos->obtenerNiveles3Catalogo($this->input->post('idNivel2'));
			
			$this->load->view("administracion/niveles/nivel3/obtenerNivelesCatalogo",$data);
		}
	}
	
	public function obtenerNiveles2Busqueda()
	{
		if (!empty($_POST))
		{
			$data['niveles']	= $this->catalogos->obtenerNiveles2Catalogo($this->input->post('idNivel1'));
			
			$this->load->view("administracion/niveles/nivel2/obtenerNiveles2Busqueda",$data);
		}
	}
	
	public function obtenerNiveles3Busqueda()
	{
		if (!empty($_POST))
		{
			$data['niveles']	= $this->catalogos->obtenerNiveles3Catalogo($this->input->post('idNivel2'));
			
			$this->load->view("administracion/niveles/nivel3/obtenerNiveles3Busqueda",$data);
		}
	}
	
	public function obtenerPadres()
	{
		$datos = $this->catalogos->obtenerPadres($this->input->get('term'));
		
		if($datos!=null)
		{
			foreach ($datos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
}
