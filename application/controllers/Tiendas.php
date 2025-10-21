<?php
class Tiendas extends CI_Controller
{
	private $_template;
	protected $_fechaActual;
	protected $_iduser;
	protected $_csstyle;
	protected $_jss;	
	protected $idLicencia;
	protected $idTienda;
	protected $cuota;
	
	function __construct()
	{
		parent::__construct();

		if($this->session->userdata('id')=="")
		{
 			redirect(base_url().'login');
 		} 		 		
        
		$this->config->load('js',TRUE);
		$this->config->load('style', TRUE);
		
		$datestring   		= "%Y-%m-%d %H:%i:%s";
		$this->_fechaActual = mdate($datestring,now());
	    $this->_iduser 		= $this->session->userdata('id');
		$this->idLicencia 	= $this->session->userdata('idLicencia');
		$this->_csstyle 	= $this->config->item('style');
		$this->_jss			=$this->config->item('js');
		$this->idTienda 	= $this->session->userdata('idTiendaActiva');
		
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("tiendas_modelo","tiendas");
		$this->load->model("bancos_model","bancos");
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("modeloclientes","clientes");
		$this->load->model("inventarioproductos_modelo","inventarioProductos");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
 	}

	public function registrarVenta()
	{
		if(!empty($_POST))
		{
			$venta=$this->tiendas->realizarVenta();
			echo $venta;
		}
	}

	public function index()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];   
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;    
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='configuracion'; 
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
		
		$data["breadcumb"]		= 'Tiendas';

		$this->load->view("tiendas/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function formularioTiendas()
	{
		$this->load->view('tiendas/formularioTiendas');
	}
	
	public function obtenerTiendas()
	{
		$data['tiendas']	= $this->tiendas->obtenerTiendas();
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('50',$this->session->userdata('rol'));
		
		$this->load->view('tiendas/obtenerTiendas',$data);
	}
	
	public function registrarTienda()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->tiendas->registrarTienda());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerTienda()
	{
		$data['tienda']	=$this->tiendas->obtenerTienda($this->input->post('idTienda'));
		
		$this->load->view('tiendas/obtenerTienda',$data);
	}
	
	public function editarTienda()
	{
		if(!empty($_POST))
		{
			echo $this->tiendas->editarTienda();
		}
	}
	
	public function borrarTienda()
	{
		if(!empty($_POST))
		{
			echo $this->tiendas->borrarTienda($this->input->post('idTienda'));
		}
	}
	
	//PARA LOS ENVÍOS DE LOS PRODUCTOS
	public function obtenerTraspasos($limite=0)
	{
		$criterio					= $this->input->post('criterio');
		$idLicenciaOrigen			= $this->input->post('idLicenciaOrigen');
		$idLicenciaDestino			= $this->input->post('idLicenciaDestino');
		$inicio						= $this->input->post('inicio');
		$fin						= $this->input->post('fin');
		
		$paginador["base_url"]		= base_url()."tiendas/obtenerTraspasos/";
		$paginador["total_rows"]	= $this->tiendas->contarTraspasos($idLicenciaOrigen,$idLicenciaDestino,$criterio,$inicio,$fin);
		$paginador["per_page"]		= 25;
		$paginador["num_links"]		= 5;
		
		$this->pagination->initialize($paginador);
		
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('30',$this->session->userdata('rol'));
		$data['traspasos']			= $this->tiendas->obtenerTraspasos($paginador["per_page"],$limite,$idLicenciaOrigen,$idLicenciaDestino,$criterio,$inicio,$fin);
		$data['limite']				= $limite+1;
		$data['idLicencia']			= $this->idLicencia;
		
		$this->load->view('traspasos/obtenerTraspasos',$data);
	}
	
	public function reporteEnvios()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		$this->load->library('mpdf/mpdf');
		
		$criterio				= $this->input->post('criterio');
		$idTienda				= $this->input->post('idTienda');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');

		$data['envios']			= $this->tiendas->obtenerEnvios(0,0,$idTienda,$criterio,$inicio,$fin);
		$data['reporte'] 		= 'tiendas/envios/reporteEnvios';
		$data['inicio']  		= $inicio;
		$data['fin']  			= $fin;

		$html					= $this->load->view('reportes/principal',$data,true);
		$pie					= $this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Legal-L','','',5,5,35,16,7,10);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('media/ficheros/reporteEnvios.pdf','F');
	}
	
	public function excelEnvios()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		$this->load->library('excel/PHPExcel');
		
		$criterio				= $this->input->post('criterio');
		$idTienda				= $this->input->post('idTienda');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['envios']			= $this->tiendas->obtenerEnvios(0,0,$idTienda,$criterio,$inicio,$fin);
		
		$this->load->view('tiendas/envios/excelEnvios',$data);	
	}
	
	public function registrarTraspaso()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->tiendas->registrarTraspaso());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerStockTienda()
	{
		if(!empty($_POST))
		{
			echo round($this->tiendas->obtenerStockTienda($this->input->post('idTienda'),$this->input->post('idProducto')),decimales);
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerProductosTraspaso($limite=0)
	{
		$criterio		= $this->input->post('criterio');
		
		$paginador["base_url"]		= base_url()."tiendas/obtenerProductosTraspaso/";
		$paginador["total_rows"]	= $this->tiendas->contarProductosTraspaso($criterio);
		$paginador["per_page"]		= 15;
		$paginador["num_links"]		= 5;
		
		$this->pagination->initialize($paginador);
		
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('30',$this->session->userdata('rol'));
		$data['productos']			= $this->tiendas->obtenerProductosTraspaso($paginador["per_page"],$limite,$criterio);
		$data['limite']				= $limite+1;
		
		$this->load->view('traspasos/obtenerProductosTraspaso',$data);
	}
	
	public function formularioTraspasos()
	{
		$data['licencias']			= $this->configuracion->obtenerLicenciasTraspaso();
		$data['folio']				= $this->tiendas->obtenerFolioTraspaso();
		
		$this->load->view('traspasos/formularioTraspasos',$data);
	}
	
	public function formularioRecepciones()
	{
		$data['traspaso']			= $this->tiendas->obtenerTraspaso($this->input->post('idTraspaso'));
		$data['detalles']			= $this->tiendas->obtenerDetallesTraspaso($this->input->post('idTraspaso'));
		$data['folio']				= $this->tiendas->obtenerFolioRecepcion();
		
		$this->load->view('traspasos/recepciones/formularioRecepciones',$data);
	}
	
	public function registrarRecepcion()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->tiendas->registrarRecepcion());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarTraspaso()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->tiendas->borrarTraspaso($this->input->post('idTraspaso')));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	//ADMINITRACIÓN DE LAS VENTAS EN LAS TIENDAS
	public function ventas()
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
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		$Data['menuActivo']		= 'configuracion'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		/*$data['permiso']		= $this->configuracion->obtenerPermisosBoton('1',$this->session->userdata('rol'));
		
		if($data['permiso']->leer=='0' and $data['permiso']->escribir=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}*/
		
		if($this->idTienda==0)
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		
		$data['tienda']			= $this->tiendas->obtenerTiendaActiva();

		$this->load->view("tiendas/ventas/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerVentas($limite=0)
	{
		#$idTienda	= $this->input->post('idTienda');
		$criterio	= $this->input->post('criterio');
		
		$paginador["base_url"]		= base_url()."tiendas/obtenerVentas/";
		$paginador["total_rows"]	= $this->tiendas->contarVentas($this->idTienda,$criterio);
		$paginador["per_page"]		= 25;
		$paginador["num_links"]		= 5;
		
		$this->pagination->initialize($paginador);
		
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('30',$this->session->userdata('rol'));
		$data['ventas']				= $this->tiendas->obtenerVentas($paginador["per_page"],$limite,$this->idTienda,$criterio);
		$data['limite']				= $limite+1;
	
		$this->load->view('tiendas/ventas/obtenerVentas',$data);
	}
	
	public function obtenerStockSucursales()
	{
		if(!empty($_POST))
		{
			$data['producto']		= $this->tiendas->obtenerProductoDetalles($this->input->post('idProducto'));
			$data['tiendas']		= $this->tiendas->obtenerStockSucursales($this->input->post('idProducto'));

			$this->load->view('tiendas/obtenerStockSucursales',$data);
		}
	}
	
	public function formularioCorte()
	{
		if(!empty($_POST))
		{
			$data['tienda']			= $this->tiendas->obtenerTienda($this->idTienda);
			$data['usuario']		= $this->configuracion->obtenerUsuario($this->_iduser);
			$data['total']			= $this->tiendas->obtenerTotalDia();
			$data['cortes']			= $this->tiendas->obtenerCortesDia();

			$this->load->view('tiendas/corte/formularioCorte',$data);
		}
		else
		{
			echo 'Error en el formulario';
		}
	}
	
	public function registrarCorte()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->tiendas->registrarCorte());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	#-------------------------------------------------------------------------------------------------------------------#
	#ENVIAR PRODUCTOS A TIENDA MEDIANTE VENTAS
	
	public function formularioInventarioFaltante()
	{
		if(!empty($_POST))
		{
			$data['tiendas'] 		= $this->tiendas->obtenerTiendasUsuario();
			$data['idTienda'] 		= $this->idTienda;
			
			$this->load->view('clientes/ventas/formularioInventarioFaltante',$data);
		}
		else
		{
			echo "0";
		}
	}
	
	public function registrarTraspasosVenta()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->tiendas->registrarTraspasosVenta());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
}
?>
