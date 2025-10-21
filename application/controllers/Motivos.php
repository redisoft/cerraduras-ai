<?php
class Motivos extends CI_Controller
{
    private $_template;
    protected $_fechaActual;
    protected $_iduser;
    protected $_csstyle;
	protected $cuota;

    function __construct()
	{
		parent::__construct();

		if( ! $this->redux_auth->logged_in() )
		{
			redirect(base_url().'login');
		}

		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("motivos_modelo","motivos");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
    }

	#----------------------------------------------------------------------------#
	#						     COLORES										 #
	#----------------------------------------------------------------------------#
	
	public function obtenerListaMotivos($limite=0)
	{
		$Pag["base_url"]		= base_url()."motivos/obtenerListaMotivos/";
		$Pag["total_rows"]		= $this->motivos->contarMotivos();//Total de Registros
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;
		
		$this->pagination->initialize($Pag);
		
		$data['motivos']		= $this->motivos->obtenerMotivos($Pag["per_page"],$limite);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		$data['limite']			= $limite+1;
		
		$this->load->view('cotizaciones/motivos/obtenerListaMotivos',$data);
	}
	
	public function registrarMotivo()
	{
		if (!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->motivos->registrarMotivo());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerMotivo()
	{
		$data['motivo']		= $this->motivos->obtenerMotivo($this->input->post('idMotivo'));

		$this->load->view('cotizaciones/motivos/obtenerMotivo',$data);
	}
	
	public function obtenerMotivos()
	{
		$data['motivos']		= $this->motivos->obtenerMotivos(1000,0);

		$this->load->view('cotizaciones/motivos/obtenerMotivos',$data);
	}
	
	public function editarMotivo()
	{
		if (!empty($_POST))
		{
			echo $this->motivos->editarMotivo();
		}
		else
		{
			echo "0";
		}
	}

	public function borrarMotivo()
	{
		if (!empty($_POST))
		{
			echo $this->motivos->borrarMotivo($this->input->post('idMotivo'));
		}
		else
		{
			echo "1";
		}
	}
}
?>
