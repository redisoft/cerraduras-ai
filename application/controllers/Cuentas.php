<?php
class Cuentas extends CI_Controller
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
		
		$this->config->load('js',TRUE);
		$this->config->load('style', TRUE);
		
		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->_fechaActual 	= mdate($datestring,now());
		$this->_iduser 			= $this->session->userdata('id');
		$this->_role 			= $this->session->userdata('role');
		$this->_csstyle 		= $this->config->item('style');
	 	 $this->_jss			= $this->config->item('js');

        $this->load->model("modelousuario","modelousuario");
        $this->load->model("modeloclientes","clientes");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("cuentas_modelo","cuentas");
		$this->load->model("proveedores_model","proveedores");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	//CUENTAS DEL CLIENTE
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	public function obtenerCuentasCliente()
	{
		$idCliente				= $this->input->post('idCliente');

		$data['cuentas']		= $this->cuentas->obtenerCuentasCliente($idCliente);
		$data['cliente']		= $this->clientes->obtenerCliente($idCliente);
		$data['listaCuentas']	= $this->cuentas->obtenerListaCuentasCliente($idCliente);
		
		$this->load->view('clientes/cuentas/obtenerCuentasCliente',$data);
	}

	public function registrarCuentaCliente()
	{
		if(!empty($_POST))
		{
			echo $this->cuentas->registrarCuentaCliente();
		}
		else
		{
			echo "0";
		}
	}

	public function borrarCuentaCliente()
	{
		if(!empty($_POST))
		{
			echo $this->cuentas->borrarCuentaCliente($this->input->post('idRelacion'));
		}
		else
		{
			echo "0";
		}
	}
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	//CUENTAS DEL PROVEEDOR
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	public function obtenerCuentasProveedor()
	{
		$idProveedor				= $this->input->post('idProveedor');

		$data['cuentas']		= $this->cuentas->obtenerCuentasProveedor($idProveedor);
		$data['proveedor']		= $this->proveedores->obtenerProveedor($idProveedor);
		$data['listaCuentas']	= $this->cuentas->obtenerListaCuentasProveedor($idProveedor);
		
		$this->load->view('proveedores/cuentas/obtenerCuentasProveedor',$data);
	}

	public function registrarCuentaProveedor()
	{
		if(!empty($_POST))
		{
			echo $this->cuentas->registrarCuentaProveedor();
		}
		else
		{
			echo "0";
		}
	}

	public function borrarCuentaProveedor()
	{
		if(!empty($_POST))
		{
			echo $this->cuentas->borrarCuentaProveedor($this->input->post('idRelacion'));
		}
		else
		{
			echo "0";
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//PARA LA CONTABILIDAD ELECTRÓNICA
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function obtenerNivel1($limite=0)
	{
		#----------------------------------PAGINACIÓN------------------------------------#
		$paginas["base_url"]	=base_url()."cuentas/obtenerNivel1/";
		$paginas["total_rows"]	=$this->cuentas->contarNivel1($this->input->post('detalle'));
		$paginas["per_page"]	=20;
		$paginas["num_links"]	=5;
		$paginas["uri_segment"]	=3;
		
		$this->pagination->initialize($paginas);
		#--------------------------------------------------------------------------------#
		
		$data['cuentas']	    = $this->cuentas->obtenerNivel1($paginas["per_page"],$limite,$this->input->post('detalle'));	
		$data['limite']		    = $limite+1;
		
		$this->load->view('contabilidad/cuentas/obtenerNivel1',$data);
	}
	
	public function formularioSaldoInicial()
	{	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel2($this->input->post('idSubCuenta'));	

		$this->load->view('contabilidad/cuentas/formularioSaldoInicial',$data);
	}
	
	public function registrarSaldoInicial()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->registrarSaldoInicial());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function obtenerNivel2()
	{	
		$idCuenta	    		= $this->input->post('idCuenta');	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuenta($idCuenta);	
		$data['cuentas']	    = $this->cuentas->obtenerNivel2($idCuenta);	

		$this->load->view('contabilidad/cuentas/obtenerNivel2',$data);
	}
	
	//------------------------------------------------------------------------------------------------//
	//NIVEL 3 DE LAS CUENTAS
	//------------------------------------------------------------------------------------------------//
	public function obtenerNivel3()
	{	
		$idSubCuenta	    	= $this->input->post('idSubCuenta');	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel2($idSubCuenta);	
		$data['cuentas']	    = $this->cuentas->obtenerNivel3($idSubCuenta);	

		$this->load->view('contabilidad/cuentas/obtenerNivel3',$data);
	}
	
	public function registrarNivel3()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->registrarNivel3());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function obtenerCuentaNivel3()
	{	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel3($this->input->post('idSubCuenta3'));	

		$this->load->view('contabilidad/cuentas/obtenerCuentaNivel3',$data);
	}
	
	public function formularioNivel3()
	{	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel2($this->input->post('idSubCuenta'));	

		$this->load->view('contabilidad/cuentas/formularioNivel3',$data);
	}
	
	public function editarNivel3()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->editarNivel3());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarNivel3()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->borrarNivel3($this->input->post('idSubCuenta3')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	//------------------------------------------------------------------------------------------------//
	//NIVEL 4 DE LAS CUENTAS
	//------------------------------------------------------------------------------------------------//
	public function obtenerNivel4()
	{	
		$idSubCuenta3	    	= $this->input->post('idSubCuenta3');	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel3($idSubCuenta3);	
		$data['cuentas']	    = $this->cuentas->obtenerNivel4($idSubCuenta3);	

		$this->load->view('contabilidad/cuentas/obtenerNivel4',$data);
	}
	
	public function registrarNivel4()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->registrarNivel4());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function obtenerCuentaNivel4()
	{	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel4($this->input->post('idSubCuenta4'));	

		$this->load->view('contabilidad/cuentas/obtenerCuentaNivel4',$data);
	}
	
	public function formularioNivel4()
	{	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel3($this->input->post('idSubCuenta3'));	

		$this->load->view('contabilidad/cuentas/formularioNivel4',$data);
	}
	
	public function editarNivel4()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->editarNivel4());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarNivel4()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->borrarNivel4($this->input->post('idSubCuenta4')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	//------------------------------------------------------------------------------------------------//
	//NIVEL 5 DE LAS CUENTAS
	//------------------------------------------------------------------------------------------------//
	public function obtenerNivel5()
	{	
		$idSubCuenta4	    	= $this->input->post('idSubCuenta4');	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel4($idSubCuenta4);	
		$data['cuentas']	    = $this->cuentas->obtenerNivel5($idSubCuenta4);	

		$this->load->view('contabilidad/cuentas/obtenerNivel5',$data);
	}
	
	public function registrarNivel5()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->registrarNivel5());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function obtenerCuentaNivel5()
	{	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel5($this->input->post('idSubCuenta5'));	

		$this->load->view('contabilidad/cuentas/obtenerCuentaNivel5',$data);
	}
	
	public function formularioNivel5()
	{	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel4($this->input->post('idSubCuenta4'));	

		$this->load->view('contabilidad/cuentas/formularioNivel5',$data);
	}
	
	public function editarNivel5()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->editarNivel5());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarNivel5()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->borrarNivel5($this->input->post('idSubCuenta5')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	//------------------------------------------------------------------------------------------------//
	//NIVEL 6 DE LAS CUENTAS
	//------------------------------------------------------------------------------------------------//
	public function obtenerNivel6()
	{	
		$idSubCuenta5	    	= $this->input->post('idSubCuenta5');	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel5($idSubCuenta5);	
		$data['cuentas']	    = $this->cuentas->obtenerNivel6($idSubCuenta5);	

		$this->load->view('contabilidad/cuentas/obtenerNivel6',$data);
	}
	
	public function registrarNivel6()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->registrarNivel6());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function obtenerCuentaNivel6()
	{	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel6($this->input->post('idSubCuenta6'));	

		$this->load->view('contabilidad/cuentas/obtenerCuentaNivel6',$data);
	}
	
	public function formularioNivel6()
	{	
		$data['cuenta']	   	 	= $this->cuentas->obtenerCuentaNivel5($this->input->post('idSubCuenta5'));	

		$this->load->view('contabilidad/cuentas/formularioNivel6',$data);
	}
	
	public function editarNivel6()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->editarNivel6());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarNivel6()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->borrarNivel6($this->input->post('idSubCuenta6')));
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function obtenerArbol()
	{	
		$data['primerNivel']	= $this->cuentas->obtenerNivel1(0,0,$this->input->post('detalle'));	
		$data['detalle']		= $this->input->post('detalle');
		$data['segundoNivel']	= $this->cuentas->obtenerNivel2(0);
		
		$this->load->view('contabilidad/cuentas/obtenerArbolito',$data);
	}
	
	public function formularioAsociarCuenta()
	{	
		$data['gasto']		= $this->configuracion->obtenerGasto($this->input->post('idGasto'));
		$data['cuentas']	= $this->cuentas->obtenerCatalogoMesTextil($this->input->post('idGasto'));
		$data['asociadas']	= $this->cuentas->obtenerCuentasAsociadas($this->input->post('idGasto'));

		$this->load->view('contabilidad/textil/formularioAsociarCuenta',$data);
	}
	
	public function asociarCuentaGasto()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->asociarCuentaGasto());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	public function borrarCuentaGasto()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->cuentas->borrarCuentaGasto());
			
		}
		else
		{
			echo json_encode(array(0=>'0'));
		}
	}
	
	//OBTENER CUENTAS CONTABLES
	public function obtenerCuentasContables()
	{
		$catalogo = $this->cuentas->obtenerCuentasContables($this->input->get('term'));
		
		if($catalogo!=null)
		{
			foreach ($catalogo as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
}
?>
