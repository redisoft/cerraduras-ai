<?php
class Listas extends CI_Controller
{
    private $_template;
    protected $_fechaActual;
    protected $_iduser;
    protected $_csstyle;
    protected $cuota;

	function __construct()
	{
		parent::__construct();
		//verificar si el el usuario ha iniciado sesion
		if( ! $this->redux_auth->logged_in() )
		{
			redirect(base_url().'login');
		}
		
		$this->_fechaActual = mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_iduser = $this->session->userdata('id');
		$this->config->load('style', TRUE);
		$this->_csstyle = $this->config->item('style');
		$this->config->load('js',TRUE);
		$this->_jss=$this->config->item('js');
		
		$this->load->model("modeloclientes","modeloclientes");
		$this->load->model("inventarioproductos_modelo","inventario");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("catalogos_modelo","catalogos");
		$this->load->model("listas_modelo","listas");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}

	public function obtenerListas($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$Pag["base_url"]		= base_url()."listas/obtenerListas/";
		$Pag["total_rows"]		= $this->listas->contarListas($criterio,$inicio,$fin);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('15',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['listas'] 		= $this->listas->obtenerListas($Pag["per_page"],$limite,$criterio,$inicio,$fin);
		$data['inicio']  		= $limite;

		$this->load->view("inventarioProductos/listas/obtenerListas",$data);
	}
	
	public function registrarLista()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->listas->registrarLista());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	
	public function obtenerProductosLista($limite=0)
	{
		$criterio		= $this->input->post('criterio');
		
		$paginador["base_url"]		= base_url()."listas/obtenerProductosLista/";
		$paginador["total_rows"]	= $this->listas->contarProductosLista($criterio);
		$paginador["per_page"]		= 15;
		$paginador["num_links"]		= 5;
		
		$this->pagination->initialize($paginador);
		
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('15',$this->session->userdata('rol'));
		$data['productos']			= $this->listas->obtenerProductosLista($paginador["per_page"],$limite,$criterio);
		$data['limite']				= $limite+1;
		
		$this->load->view('inventarioProductos/listas/obtenerProductosLista',$data);
	}
	
	public function formularioListas()
	{
		$this->load->view('inventarioProductos/listas/formularioListas');
	}
	
	public function obtenerLista()
	{
		if(!empty ($_POST))
		{
			$idLista				= $this->input->post('idLista');
			
			$data['productos'] 			= $this->listas->obtenerDetallesLista($idLista);
			$data['lista'] 				= $this->listas->obtenerLista($idLista);
			
			$this->load->view('inventarioProductos/listas/obtenerLista',$data);
		}
	}
	
	public function editarLista()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->listas->editarLista());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarLista()
	{
		if(!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('15',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				$this->load->view('accesos/index');
				return;
			}
			
			echo json_encode($this->listas->borrarLista($this->input->post('idLista')));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function autorizarLista()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			
			echo json_encode($this->listas->autorizarLista($this->input->post('idLista')));
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function listasPdf()
	{
		$idLista				= $this->input->post('idLista');

		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->library('mpdf/mpdf');

		$data['productos'] 			= $this->listas->obtenerDetallesLista($idLista);
		$data['lista'] 				= $this->listas->obtenerLista($idLista);
		$data['reporte'] 			='inventarioProductos/listas/pdf/listasPdf';

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);
		
		$margen				= 32;
		
		if(!file_exists('img/logos/'.$this->session->userdata('logotipo')) or strlen($this->session->userdata('logotipo'))<4)
		{
			$margen				= 26.4;
		}
		
		$this->mpdf->mPDF('en-x','Letter','','',5,5,$margen,15,7,10);
		
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'ListaPrecios.pdf','F');

		echo 'ListaPrecios';
	}
}
?>
