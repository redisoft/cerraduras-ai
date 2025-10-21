<?php
class Requisiciones extends CI_Controller
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
		
        $this->load->model("modelousuario","usuarios");
		 $this->load->model("modelousuario","modelousuario");
        $this->load->model("modeloclientes","clientes");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("requisiciones_modelo","requisiciones");
		$this->load->model("compras_modelo","compras");
		$this->load->model("proveedores_model","proveedores");
		$this->load->model("materiales_modelo","materiales");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
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
		$Data['JFuntInventario']= $this->_jss['JFuntInventario'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];                  
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'comprasRequisiciones'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('58',$this->session->userdata('rol'));

		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data["breadcumb"]		= 'Requisiciones';

		$this->load->view("requisiciones/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}
	
	public function obtenerRequisiciones($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('58',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."requisiciones/obtenerRequisiciones/";
		$Pag["total_rows"]		= $this->requisiciones->contarRequisiciones($criterio,$inicio,$fin);//Total de Registros
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		$data['requisiciones']	= $this->requisiciones->obtenerRequisiciones($Pag["per_page"],$limite,$criterio,$inicio,$fin);
		$data['limite']			= $limite+1;
		
		$this->load->view('requisiciones/materiales/obtenerRequisiciones',$data);
	}
	
	public function formularioRequisiciones()
	{
		$data['folio'] 			= $this->requisiciones->obtenerFolioRequisicion();
		
		$this->load->view('requisiciones/materiales/formularioRequisiciones',$data);
	}
	
	public function borrarRequisicion()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->requisiciones->borrarRequisicion($this->input->post('idRequisicion')));
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	public function obtenerRequisicion()
	{
		$idRequisicion			= $this->input->post('idRequisicion');
		$data['requisicion'] 	= $this->requisiciones->obtenerRequisicion($idRequisicion);
		$data['materiales']		= $this->requisiciones->obtenerMaterialesRequisicion($idRequisicion);
		
		$this->load->view('requisiciones/materiales/obtenerRequisicion',$data);
	}
	
	public function registrarRequisicion()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
				
			echo json_encode($this->requisiciones->registrarRequisicion());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarRequisicion()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
				
			echo json_encode($this->requisiciones->editarRequisicion());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerDetallesRequisicion()
	{
		$idRequisicion			= $this->input->post('idRequisicion');
		$data['requisicion'] 	= $this->requisiciones->obtenerRequisicion($idRequisicion);
		$data['materiales']		= $this->requisiciones->obtenerMaterialesRequisicion($idRequisicion);
		
		$this->load->view('requisiciones/materiales/obtenerDetallesRequisicion',$data);
	}
	
	//REQUISICIONES DE COMPRAS
	
	public function obtenerRequisicionesCompras($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."requisiciones/obtenerRequisicionesCompras/";
		$Pag["total_rows"]		= $this->requisiciones->contarRequisicionesCompras($criterio,$inicio,$fin);//Total de Registros
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		$data['requisiciones']	= $this->requisiciones->obtenerRequisicionesCompras($Pag["per_page"],$limite,$criterio,$inicio,$fin);
		$data['limite']			= $limite+1;
		
		$this->load->view('requisiciones/compras/obtenerRequisiciones',$data);
	}
	
	public function registrarComprasRequisiones()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
				
			echo json_encode($this->requisiciones->registrarComprasRequisiones());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerRequisicionesProcesadas($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		
		$Pag["base_url"]		= base_url()."requisiciones/obtenerRequisicionesProcesadas/";
		$Pag["total_rows"]		= $this->requisiciones->contarRequisicionesProcesadas($criterio,$inicio,$fin);//Total de Registros
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		$data['requisiciones']	= $this->requisiciones->obtenerRequisicionesProcesadas($Pag["per_page"],$limite,$criterio,$inicio,$fin);
		$data['limite']			= $limite+1;
		
		$this->load->view('requisiciones/compras/procesadas/obtenerRequisicionesProcesadas',$data);
	}
	
	public function formatoRequisicion($idRequisicion)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->configuracion->registrarBitacora('Exportar a pdf','Compras - Requisiciones',''); //Registrar bitácora

		$this->load->library('mpdf/mpdf');

		$data['requisicion'] 	= $this->requisiciones->obtenerRequisicion($idRequisicion);
		$data['materiales']		= $this->requisiciones->obtenerMaterialesRequisicion($idRequisicion);
		$data['compras']		= $this->requisiciones->obtenerComprasRequisicion($idRequisicion);
		
		$data['reporte'] 		= 'requisiciones/compras/procesadas/pdf/formatoRequisicion';

		$html	= $this->load->view('reportes/principal',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter-L','','',3,3,10,10,2,2);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('Requisicion.pdf','D');
	}
	
}
?>
