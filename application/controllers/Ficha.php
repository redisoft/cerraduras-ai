<?php
class Ficha extends CI_Controller
{
    protected $_fechaActual;
    protected $_iduser;
    protected $_csstyle;
    protected $_tables;
    protected $_Variables;
    protected $_role;
	protected $cuota;

    function __construct()
	{
		parent::__construct();
	
		if( ! $this->redux_auth->logged_in() )
		{//verificar si el el usuario ha iniciado sesion
			redirect(base_url().'login');
		}
		
		$this->config->load('js',TRUE);
		$this->config->load('style', TRUE);
		$this->config->load('datatables', TRUE);
		
		$datestring   			= "%Y-%m-%d %H:%i:%s";
		$this->_fechaActual 	= mdate($datestring,now());
		$this->_iduser 			= $this->session->userdata('id');
		$this->_role 			= $this->session->userdata('role');
		$this->_tables 			= $this->config->item('datatables');
		$this->_csstyle 		= $this->config->item('style');
		$this->_jss				=$this->config->item('js');
		
		$this->load->model("ventas_model","ventas");
		$this->load->model("modelousuario","usuario");
		$this->load->model("modeloclientes","clientes");
		$this->load->model("inventario_model","inventario");
		$this->load->model('bancos_model','bancos');
		$this->load->model('proveedores_model','proveedores');
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("administracion_modelo","administracion");
		$this->load->model("contabilidad_modelo","contabilidad");
		$this->load->model("ventas_modelo","ventasmodelo");
		$this->load->model("inventarioproductos_modelo","inventarioProductos");
		
		$this->load->model("tiendas_modelo","tiendas");
		

		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}

	public function contactos($idCliente)
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			= $this->_csstyle["cassadmin"];
		$Data['csmenu']				= $this->_csstyle["csmenu"];
		$Data['csui']				= $this->_csstyle["csui"];
		$Data['nameusuario']		= $this->usuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		= $this->_fechaActual;
		$Data['Jry']				= $this->_jss['jquery'];
		$Data['Jqui']				= $this->_jss['jqueryui'];
		$Data['permisos']			= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			= 'clientes'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('6',$this->session->userdata('rol'));
		$data['permisoCotizacion']	= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		$data['permisoVenta']		= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
		$data['permisoFactura']		= $this->configuracion->obtenerPermisosBoton('24',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data["cliente"]			= $this->clientes->obtenerCliente($idCliente);
		$data["mostrarMenu"]		= true;
		$data["breadcumb"]			= '<a href="'.base_url().'clientes">Clientes</a> > <a href="'.base_url().'clientes/index/'.$idCliente.'">'.substr($data['cliente']->empresa,0,300).'</a> > Contactos';
		
		$this->load->view("clientes/contactos/contactos",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerCatalogoContactos()
	{
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('6',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			
			return;
		}
		
		$data["cliente"]		= $this->clientes->obtenerCliente($this->input->post('idCliente'));
		$data["mostrarMenu"]	= false;
		
		$this->load->view("clientes/contactos/contactos",$data);
	}
	
	public function formularioContacto()
	{
		$this->load->view("clientes/contactos/formularioContacto");
	}
	
	public function obtenerContactos()
	{
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('6',$this->session->userdata('rol'));

		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$data["contactos"]		= $this->clientes->obtenerContactos($this->input->post('idCliente'),$data['permiso'][4]->activo);
		#$data["editable"]		= $this->input->post('editable');
		
		if(sistemaActivo=='IEXE')
		{
			$data["cliente"]		= $this->clientes->obtenerCliente($this->input->post('idCliente'));
			
			$this->load->view("clientes/contactos/obtenerContactosIexe",$data);
		}
		else
		{
			$this->load->view("clientes/contactos/obtenerContactos",$data);
		}
	}
	
	public function obtenerContacto()
	{
		$data['contacto']	= $this->clientes->obtenerContacto($this->input->post('idContacto'));
		
		$this->load->view("clientes/contactos/obtenerContacto",$data);
	}
	
	public function editarContacto()
	{
		if(!empty($_POST))
		{
			echo $this->clientes->editarContacto();
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerContactoCliente()
	{
		$data['cliente']	= $this->clientes->obtenerCliente($this->input->post('idCliente'));
		
		$this->load->view("clientes/contactos/obtenerContactoCliente",$data);
	}
	
	public function editarContactoCliente()
	{
		if(!empty($_POST))
		{
			echo $this->clientes->editarContactoCliente();
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarContacto()
	{
		if(!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
	
			$data['permiso']			= $this->configuracion->obtenerPermisosBoton('6',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				$this->load->view('accesos/index');
				$this->load->view("pie",$Data);
				
				return;
			}
			
			echo $this->clientes->borrarContacto($this->input->post('idContacto'));
		}
		else
		{
			echo "0";
		}
	}

	public function agregarCotizacion()
	{
		if(!empty($_POST))
		{
			$cotizacion=$this->inventario->agregarCotizacion();
			
			$cotizacion=="1"?
				$this->session->set_userdata('notificacion','La cotización se ha registrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Error al registrar el la cotización');
				
			redirect('clientes/busquedaClienteFicha/'.$this->input->post('id_cli'), 'refresh');
		}
		else 
		{
			redirect('clientes', 'refresh');
		}//else
	}//SaveNewCotizar


	public function editarCotizacion()
	{
		if(!empty($_POST))
		{
			if($this->inventario->editarCotizacion() != NULL)
			{
				$this->session->set_flashdata('message', 
				array('messageType' => 'success','Message' => 'Se modifico correctamente la cotizaci&oacute;n.'));
			}
			else
			{
				$this->session->set_flashdata('message', 
				array('messageType' => 'error','Message' => 'Ocurrio un error al modificar la cotización'));
			}//else
			
			redirect('clientes/busquedaClienteFicha/'.$this->input->post('id_cli')."/", 'refresh');
		}//$_POST
		else 
		{
			redirect('clientes/', 'refresh');
		}	//else
	
	}

	public function realizarPago()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->clientes->realizarPago());			
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerCuentas($idBanco,$idCliente=0)
	{
		$idCliente	= 0;
		$cuentas	= $this->bancos->obtenerCuentasBanco($idBanco,$idCliente);
		
		echo '<select id="cuentasBanco" name="cuentasBanco" class="cajas" style="width:150px">'; 

		if($cuentas!=null)
		{
			foreach($cuentas as $cuenta)
			{
				echo '<option value="'.$cuenta->idCuenta.'">'.(strlen($cuenta->cuenta)>0?$cuenta->cuenta:$cuenta->tarjetaCredito).'</option>';
			}
		}
		else
		{
			echo'<option value="0"> Seleccione</option>';
		}
		
		if($idBanco==1 and $idCliente>0) 
		{
			echo '<option value="1">Efectivo</option>';
		}
		
		echo '</select>';
	}

	public function obtenerCuentasDigitos($idBanco)
	{
		$cuentas	=$this->bancos->obtenerCuentasBanco($idBanco);
		
		echo'<select id="cuentasBanco" name="cuentasBanco" class="cajas" style="width:150px">'; 
		
		if($cuentas!=null)
		{
			foreach($cuentas as $row)
			{
				$cuenta		=strlen($row->cuenta);
				$cuenta		=substr($row->cuenta,$cuenta-4,4);
				
				if($row->cuenta=='Efectivo') $cuenta='Efectivo';
				
				echo'<option value="'.$row->idCuenta.'">'.$cuenta.'</option>';
			}
		}
		
		else
		{
			echo'<option value="0"> Seleccione</option>';
		}
		
		echo'</select>';
	}
	
	public function obtenerBancosCliente($idCliente)
	{
		$bancos		=$this->bancos->obtenerBancosCliente($idCliente);
		
		echo'<select id="listaBancos" name="listaBancos" class="cajas" style="width:150px" onchange="buscarCuentas()" >'; 
		
		if($bancos!=null)
		{
			foreach($bancos as $row)
			{
				echo'<option value="'.$row->idBanco.'">'.$row->nombre.'</option>';
			}
		}
		
		else
		{
			echo'<option value="0"> Seleccione</option>';
		}
		
		echo'</select>';
	}

	public function borrarCobro()
	{
		$idIngreso	=$this->input->post('idIngreso');
		$cobro		=$this->clientes->borrarCobro($idIngreso);
	
		echo $cobro;
	}
//*****************************

	public function clientesZona()
	{
		if(!empty($_POST))
		{
			$idZona=$this->input->post('idZona');
			//print($idZona);
			$clientes=$this->clientes->obtenerClientesZona($idZona);
			
			echo ' <select id="id_cli" name="id_cli" class="cajas" style="width:auto" >';
			
			foreach($clientes as $cliente) 
			{
				echo '<option value="'.$cliente['id'].'">'.$cliente['empresa'].'</option>'; 
			}
			
			echo '</select>';
		}
	}

	public function obtenerCobrosClientes()
	{
		$idCotizacion	=$this->input->post('idCotizacion');
		
		$data['cotizacion']		= $this->ventasmodelo->obtenerVenta($idCotizacion);
		$data['pagos']			= $this->clientes->obtenerPagos($idCotizacion);
		$data['total']			= $this->clientes->obtenerPagado($idCotizacion);
		#$data['cuentas'] 		= $this->configuracion->obtenerCuentasContables();
		$data['bancos']			= $this->bancos->obtenerBancos(1);
		$data['formas']			= $this->configuracion->seleccionarFormas();
		
		$data['departamentos']	= $this->administracion->obtenerDepartamentos();
		$data['nombres']		= $this->administracion->obtenerNombres();
		$data['productos']		= $this->administracion->obtenerProductos();
		$data['gastos']			= $this->administracion->obtenerTipoGasto();
		$data['pago']			= $this->clientes->obtenerUltimoPago($data['cotizacion']->idCliente);
		
		if($data['pago']!=null)
		{
			$data['cuentas']			= $this->bancos->obtenerCuentasBanco($data['pago']->idBanco);
		}
		
		#var_dump($data['pago']);
		$data['idCotizacion']	= $idCotizacion;

		$this->load->view('ventas/obtenerCobrosClientes',$data);
	}

	public function borrarCotizacion($idCotizacion,$idCliente)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$cotizacion	= $this->clientes->borrarCotizacion($idCotizacion);
		
		if($cotizacion=="1")
		{
			redirect('clientes/cotizaciones/'.$idCliente,'refresh');
		}
		else
		{
			redirect('clientes/cotizaciones/'.$idCliente,'refresh');
		}
	}
	
	public function borrarVenta()
	{
		if(!empty($_POST))
		{
		#----------------------------------PERMISOS------------------------------------#
			$data['permiso']			= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				$this->load->view('accesos/index');
				return;
			}
				
			echo json_encode($this->clientes->borrarVenta($this->input->post('idCotizacion')));
			
		}
		else
		{
			echo json_encode(array('0','Error al cancelar la venta'));
		}
	}
	
	public function cancelarVenta()
	{
		if(!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']			= $this->configuracion->obtenerPermisosBoton('5',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				$this->load->view('accesos/index');
				return;
			}
			
			echo json_encode($this->clientes->cancelarVenta($this->input->post('idCotizacion')));
		}
		else
		{
			echo json_encode(array('0','Error al cancelar la venta'));
		}
	}
}
?>
