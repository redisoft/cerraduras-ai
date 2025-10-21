<?php
class Proveedores extends CI_Controller
{
    private   $_template;
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
		
		$this->config->load('js',TRUE);
		$this->config->load('style', TRUE);
		
		$this->_fechaActual = mdate("%Y-%m-%d %H:%i:%s",now());
		$this->_iduser 		= $this->session->userdata('id');
		$this->_csstyle 	= $this->config->item('style');
		$this->_jss			= $this->config->item('js');
		
		$this->load->model("crm_modelo","crm");
		$this->load->model("bancos_model","bancos");
		$this->load->model('modeloclientes','clientes');
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("proveedores_model","proveedores");
		$this->load->model('modelo_configuracion','configuracion');
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}
	
	#========================================================================================================#
	#=========================================COMPRAS OR. POR PROVEEDORES====================================#
	#========================================================================================================#
	 
	
	public function prebusquedaCompras($idProveedor)
	{
		$this->session->set_userdata('idProveedorCompra',$idProveedor);
		
		redirect('proveedores/compras');
	}
	
	public function compras($Limite=0)
	{
		$Data['title']= "Panel de Administración";
		$Data['cassadmin']=$this->_csstyle["cassadmin"];
		$Data['csmenu']=$this->_csstyle["csmenu"];
		$Data['csvalidate']=$this->_csstyle["csvalidate"];
		$Data['csui']=$this->_csstyle["csui"];
		$Data['nameusuario']=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']=$this->_fechaActual;
		$Data['Jry']=$this->_jss['jquery'];
		$Data['JFuntInventario']=$this->_jss['JFuntInventario'];
		$Data['Jquical']=$this->_jss['jquerycal'];
		$Data['Jqui']=$this->_jss['jqueryui'];                  
		$Data['jFicha_cliente']=$this->_jss['jFicha_cliente']; 
		$Data['JFuntPagClien']=$this->_jss['JFuntPagClien'];
		$Data['permisos']=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']='proveedores'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		$idProveedor=$this->session->userdata('idProveedorCompra');
		
		$Pag["base_url"]= base_url()."proveedores/compras/";
		$Pag["total_rows"]=$this->proveedores->contarCompras($idProveedor);//Total de Registros
		$Pag["per_page"]=15;
		$Pag["num_links"]=5;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('2',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['proveedor'] = $this->proveedores->obtenerDatosProveedor($idProveedor);
		$data['compras'] = $this->proveedores->obtenerCompras($Pag["per_page"],$Limite,$idProveedor);
		$data['inicio']  = $Limite;
		$num_r = count($data['compras']);
		$Conte['per_page']= $num_r;
		//echo $Limite; return;
		
		$this->load->view("proveedores/compras",$data); //principal lista de clientes
		
		$this->load->view("pie",$Data);
	}

	#========================================================================================================#
	#=========================================CRITERIOS DE ORDENAMIENTO====================================#
	#========================================================================================================#
	
	public function ordenamiento($criterio)
	{
		$this->session->set_userdata('criterioProveedores',$criterio);
		
		redirect('proveedores','refresh');
	}

	#========================================================================================================#
	#=============================================AUTOCOMPLETADOS============================================#
	#========================================================================================================#
	
	public function autoCompletadoProveedores()
	{
		if(!empty ($_POST))
		{
			$clientes=$this->configuracion->autoCompletadoProveedores();
			
			if ($clientes!=NULL)
			{
				foreach ($clientes as $row)
				{
					echo '<li onClick="datoEncontrado(\''.$row->idProveedor.'\',\''.str_replace('"',"",$row->empresa).'\');">'.$row->empresa.'</li>';
				}
			}
		}
	}
	
	public function prebusqueda($idProveedor)
	{
		if($idProveedor=="nada")
		{
			$idProveedor='';
		}
		
		$this->session->set_userdata('idProveedorBusqueda',$idProveedor);
		
		redirect('proveedores','refresh');
	}
	
	public function prebusquedaFecha($fecha)
	{
		if($fecha=='nada')
		{
			$fecha="";			
		}
		
		$this->session->set_userdata('fechaCompras',$fecha);
		
		redirect('proveedores/compras','refresh');
	}
	
	public function registrarProveedor()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$proveedor	= $this->proveedores->registrarProveedor();
			
			if($proveedor[0]=="1")
			{
				$this->session->set_userdata('notificacion','Se ha registrado correctamente al proveedor');
			}
			
			echo json_encode($proveedor);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarProveedor()
	{
		if(!empty ($_POST))
		{
			$proveedor=$this->proveedores->editarProveedor();
			
			if($proveedor=="1")
			{
				$this->session->set_userdata('notificacion','Se ha editado correctamente el registro del proveedor');
			}
			
			echo $proveedor;
		}
	}
	
	public function formularioCuentas()
	{
		$this->load->view("proveedores/cuentas/formularioCuentas");
	}
	
	public function obtenerCuenta()
	{
		$idCuenta			= $this->input->post('idCuenta');
		$data['cuenta']		= $this->bancos->obtenerCuentaProveedor($idCuenta);
		$data['idCuenta']	= $this->input->post('idCuenta');
		
		$this->load->view("proveedores/cuentas/obtenerCuenta",$data);
	}
	
	public function borrarCuenta()
	{
		if(!empty ($_POST))
		{
			$idCuenta	=$this->input->post('idCuenta');
			$cuenta		=$this->proveedores->borrarCuenta($idCuenta);
			
			echo $cuenta;
		}
	}
	
	public function editarCuenta()
	{
		if(!empty ($_POST))
		{
			$cuenta=$this->proveedores->editarCuenta();
			echo $cuenta;
		}
	}
	
	public function registrarCuenta()
	{
		if(!empty ($_POST))
		{
			$cuenta=$this->proveedores->registrarCuenta();
			echo $cuenta;
		}
	}
	
	public function obtenerMapa()
	{
		$this->load->library('googlemaps');

		$proveedor		=$this->proveedores->obtenerDatosProveedor($this->input->post('idProveedor'));
		
		$pais			=$proveedor->pais=='México'?"Mexico":$proveedor->pais;
		
		#$config['center'] 				= $proveedor->numero.', '.$proveedor->domicilio.', '.$proveedor->localidad.', '.$proveedor->municipio.', '.$proveedor->estado.', '.$pais.', '.$proveedor->codigoPostal;
		$config['center'] 				= $proveedor->latitud.', '.$proveedor->longitud;
		$config['zoom'] 				= '13';
		$config['loadAsynchronously'] 	= true;
		$config['https'] 				= true;
		$config['map_height'] 			= '540px';
		$config['map_width'] 			= '983px';
		$config['posicionY'] 			= '1%';
		$config['posicionX'] 			= '0%';
		$config['posicion'] 			= 'absolute';
		
		$config['map_div_id'] 			= 'mapaProveedores';

		$this->googlemaps->initialize($config);
		
		$marker['icon'] 				= 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|9999FF|000000';
		$marker['position'] 			= $config['center'];
		$this->googlemaps->add_marker($marker);
		$map 					= $this->googlemaps->create_map();

		echo $map['js'];
		echo $map['html'];
		
		 echo'
		<script>
		$(document).ready(function()
		{
			loadScript();
		});
		</script>';
	}
	
	public function obtenerProveedor()
	{
		#$this->load->library('googlemaps');
		
		$idProveedor			= $this->input->post('idProveedor');
		$data['proveedor']		= $this->proveedores->obtenerDatosProveedor($idProveedor);
		$data['cuentas']		= $this->proveedores->obtenerCuentas($idProveedor);
		$data['idProveedor']	= $idProveedor;
		
		$this->load->view("proveedores/obtenerProveedor",$data);
	}
	
	
	public function obtenerProveedores()
	{
		$proveedores = $this->proveedores->obtenerProveedores();
		
		echo
		'
			<label>Proveedor:</label> 

			<select class="cajas" style="width:auto" id="proveedores" onchange="confirmarProveedor()">';
			
			foreach($proveedores as $row)
			{
				print('<option value="'.$row->id.'">'.$row->empresa.'</option>');
			}
			
			echo '</select>';
	}
	
	public function index($limite=0,$idProveedor=0)
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			= $this->_csstyle["cassadmin"];
		$Data['csmenu']				= $this->_csstyle["csmenu"];
		$Data['csvalidate']			= $this->_csstyle["csvalidate"];
		$Data['csui']				= $this->_csstyle["csui"];
		$Data['nameusuario']		= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		= $this->_fechaActual;
		
		$Data['Jry']				= $this->_jss['jquery'];
		$Data['Jqui']				= $this->_jss['jqueryui'];  
		$Data['permisos']			= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			= 'proveedores'; 
		$Data['conectados']			= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS

		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('7',$this->session->userdata('rol'));
		$data['permisoCrm']			= $this->configuracion->obtenerPermisosBoton('9',$this->session->userdata('rol'));
		$data['permisoContacto']	= $this->configuracion->obtenerPermisosBoton('8',$this->session->userdata('rol'));
		
		$data['materiales']			= $this->configuracion->obtenerPermisosBoton('10',$this->session->userdata('rol'));
		$data['productos']			= $this->configuracion->obtenerPermisosBoton('11',$this->session->userdata('rol'));
		$data['inventarios']		= $this->configuracion->obtenerPermisosBoton('12',$this->session->userdata('rol'));
		$data['servicios']			= $this->configuracion->obtenerPermisosBoton('52',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$Pag["base_url"]			= base_url()."proveedores/index/";
		$Pag["total_rows"]			= $this->proveedores->contarProveedores($idProveedor);//Total de Registros
		$Pag["per_page"]			= 15;
		$Pag["num_links"]			= 5;
	
		$this->pagination->initialize($Pag);
		
		$data['proveedores']		= $this->proveedores->seleccionarProveedores($Pag["per_page"],$limite,$idProveedor);
		$data['inicio']				= $limite+1;
		$data["breadcumb"]			= 'Proveedores';
		$data['idProveedor']		= $idProveedor;
		
		$this->load->view("proveedores/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function borrarProveedor($idProveedor)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('7',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$proveedor=$this->proveedores->borrarProveedor($idProveedor);
		
		$proveedor=="1"?
				$this->session->set_userdata('notificacion','El proveedor se ha borrado correctamente'):
				$this->session->set_userdata('errorNotificacion','Imposible borrar el proveedor, ya que esta asociado con registros de materia prima, productos y/o compras');
				
		redirect('proveedores/index','refresh');
	}

	public function contactos($idProveedor)
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='proveedores'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('8',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data['materiales']			= $this->configuracion->obtenerPermisosBoton('10',$this->session->userdata('rol'));
		$data['productos']			= $this->configuracion->obtenerPermisosBoton('11',$this->session->userdata('rol'));
		$data['inventarios']		= $this->configuracion->obtenerPermisosBoton('12',$this->session->userdata('rol'));
		$data['servicios']			= $this->configuracion->obtenerPermisosBoton('52',$this->session->userdata('rol'));

		$data['contactos']		= $this->proveedores->obtenerContactos($idProveedor);
		$data['proveedor']		= $this->proveedores->obtenerDatosProveedor($idProveedor);
		$data['idProveedor']	= $idProveedor;
		
		$data["breadcumb"]		= '<a href="'.base_url().'proveedores">Proveedores</a> > <a href="'.base_url().'proveedores/index/0/'.$idProveedor.'">'.substr($data['proveedor']->empresa,0,300).'</a> > Contactos';
		
		$this->load->view("proveedores/contactos/index",$data);
		$this->load->view("pie",$Data);
	}

	public function borrarContacto($idContacto,$idProveedor)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('8',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$contacto=$this->proveedores->borrarContacto($idContacto);
		
		$contacto=="1"?
		$this->session->set_userdata('notificacion','El contacto se ha borrado correctamente'):
		$this->session->set_userdata('errorNotificacion','Error al borrar al contacto');
		
		redirect('proveedores/contactos/'.$idProveedor,'refresh');
	}

	public function editar_contacto($id,$proveedor)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('8',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['jvalidate']		=$this->_jss['jvalidate'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='proveedores'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		$Conte['Categoria']=$this->uri->segment(1);
		//$Conte['Serie']=$this->proveedores->getGeneraSerieID();
		
		$Conte['contactos']=$this->proveedores->get_contacto($id);
		$Conte['idproveedor']=$proveedor;
		$Conte['proveedor']=$this->proveedores->obtenerDatosProveedor($proveedor);
		//echo $Conte['contactos'];
		$this->load->view("proveedores/contactos/editar",$Conte);
		
		$this->load->view("pie",$Data);
	}

	public function update_contacto($id)
	{
		if(!empty ($_POST))
		{
			$idproveedor=$this->input->post('oculto');
			
			if($this->proveedores->update_contacto($id) != NULL)
			{
				$this->session->set_flashdata('message', 
				array('messageType' => 'success','Message' => 'El registro se ha actualizado correctamente.'));
			}
			else
			{
				$this->session->set_flashdata('message', 
				array('messageType' => 'error','Message' => 'Ocurrio un error al actualizar el registro.'));
			}
			
			redirect('proveedores/contactos_proveedores/'.$idproveedor,'refresh');
		}
		else 
		{
			redirect('proveedores/editar_contacto/'.$id,'refresh');
		}
	}

	public function registrarContactoProveedor()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			$contacto=$this->proveedores->registrarContactoProveedor();
			
			$contacto[0]=="1"?$this->session->set_userdata('notificacion','El contacto se ha registrado correctamente'):'';
			
			echo json_encode($contacto);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerContacto()
	{
		$data['contacto']	= $this->proveedores->obtenerContacto($this->input->post('idContacto'));
		
		$this->load->view('proveedores/contactos/obtenerContacto',$data);
	}
	
	public function editarContacto()
	{
		if(!empty($_POST))
		{
			$contacto=$this->proveedores->editarContacto();
			$contacto==1?$this->session->set_userdata('notificacion','El contacto se ha editado corractamente'):'';
			
			echo $contacto;		
		}
	}

	public function actualizarMapa()
	{
		$this->load->library('googlemaps');

		$latitud		=$this->input->post('latitud');
		$longitud		=$this->input->post('longitud');
		
		#$config['center'] 				= $numero.', '.$calle.', '.$localidad.', '.$municipio.', '.$estado.', '.$pais.', '.$codigoPostal;
		$config['center'] 				= $latitud.', '.$longitud;
		$config['zoom'] 				= '13';
		$config['loadAsynchronously'] 	= true;
		$config['https'] 				= true;
		$config['map_height'] 			= '300px';
		$config['map_width'] 			= '500px';
		$config['posicionY'] 			= '11%';
		$config['posicionX'] 			= '47%';
		$config['posicion'] 			= 'absolute';
		
		$config['map_div_id'] 			= 'mapaProveedores';

		$this->googlemaps->initialize($config);
		
		$marker['icon'] 				= 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|9999FF|000000';
		$marker['position'] 			= $config['center'];
		$this->googlemaps->add_marker($marker);
		$map 					= $this->googlemaps->create_map();

		echo $map['js'];
		echo $map['html'];
		
		 echo'
		<script>
		$(document).ready(function()
		{
			loadScript();
		});
		</script>';
	}
	
	public function formularioProveedores()
	{
		#$this->load->library('googlemaps');
		
		$data['bancos']		= $this->bancos->obtenerBancos();

		$this->load->view('proveedores/formularioProveedores',$data);
	}
	
	public function obtenerDiasCredito()
	{
		echo round($this->proveedores->obtenerDiasCredito($this->input->post('idProveedor')));
	}
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	//CRM PARA PROVEEDORES
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	
	public function seguimientoProveedores($limite=0)
	{
		$idProveedor			= $this->input->post('idProveedor');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$Pag["base_url"]		= base_url()."clientes/seguimientoProveedores/";
		$Pag["total_rows"]		= $this->proveedores->contarSeguimientoProveedor($idProveedor,$inicio,$fin);//Total de Registros
		$Pag["per_page"]		= 10;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		$data['idProveedor']	= $idProveedor;
		$data['seguimientos']	= $this->proveedores->obtenerSeguimientoProveedor($Pag["per_page"],$limite,$idProveedor,$inicio,$fin);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('9',$this->session->userdata('rol'));
		
		$this->load->view('proveedores/seguimiento/seguimientoProveedores',$data);
	}
	
	public function formularioSeguimiento()
	{
		$data['status']			= $this->configuracion->obtenerStatus(0);
		$data['servicios']		= $this->configuracion->obtenerServicios(0);
		$data['responsables']	= $this->configuracion->obtenerResponsables();
		$data['tiempos']		= $this->configuracion->obtenerTiempos();
		$data['contactos']		= $this->proveedores->obtenerContactos($this->input->post('idProveedor'));
		$data['idProveedor']	= $this->input->post('idProveedor');
		$data['folio']			= obtenerFolioSeguimiento($this->crm->obtenerFolioSeguimientoProveedor());
		#$data['tiempos']		= $this->configuracion->obtenerTiempos();
		
		
		$this->load->view('proveedores/seguimiento/formularioSeguimiento',$data);
	}
	
	public function obtenerSeguimiento()
	{
		if(!empty($_POST))
		{
			$idSeguimiento			= $this->input->post('idSeguimiento');
			$data['idSeguimiento']	= $this->input->post('idSeguimiento');
			$data['seguimiento']	= $this->proveedores->obtenerSeguimiento($idSeguimiento);
			
			if($data['seguimiento']!=null)
			{
				$data['contacto']		= $this->proveedores->obtenerContacto($data['seguimiento']->idContacto);
				$data['archivos']		= $this->proveedores->obtenerArchivosSeguimiento($idSeguimiento);
			}
		
			$this->load->view('proveedores/seguimiento/obtenerSeguimiento',$data);
		}
	}
	
	public function obtenerSeguimientoEditar()
	{
		$idSeguimiento				=$this->input->post('idSeguimiento');
		
		$data['status']				= $this->configuracion->obtenerStatus(0);
		$data['seguimiento']		= $this->proveedores->obtenerSeguimiento($idSeguimiento);
		$data['responsables']		= $this->configuracion->obtenerResponsables();
		$data['servicios']			= $this->configuracion->obtenerServicios(0);
		$data['tiempos']			= $this->configuracion->obtenerTiempos();
		$data['contactos']			= $this->proveedores->obtenerContactos($data['seguimiento']->idProveedor);
		$data['proveedor']			= $this->proveedores->obtenerProveedor($data['seguimiento']->idProveedor);
		$data['idSeguimiento']		 =$idSeguimiento;
		
		$this->load->view('proveedores/seguimiento/obtenerSeguimientoEditar',$data);
	}
	
	public function borrarSeguimiento()
	{
		if(!empty($_POST))
		{
			echo $this->proveedores->borrarSeguimiento($this->input->post('idSeguimiento'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function registrarSeguimiento()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->proveedores->registrarSeguimiento());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarSeguimientoCrm()
	{
		if(!empty($_POST))
		{
			echo $this->proveedores->editarSeguimientoCrm();
		}
		else
		{
			echo "0";
		}
	}
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	//ARCHIVOS DE SEGUIMIENTO
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	
	public function obtenerArchivosSeguimiento()
	{
		$idSeguimiento			= $this->input->post('idSeguimiento');
		
		$data['idSeguimiento']	= $idSeguimiento;
		$data['archivos']		= $this->proveedores->obtenerArchivosSeguimiento($idSeguimiento);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('9',$this->session->userdata('rol'));
		$data['cuota']			= $this->cuota;
		
		$this->load->view('proveedores/seguimiento/archivos/obtenerArchivosSeguimiento',$data);
	}
	
	public function subirArchivosSeguimiento($idSeguimiento=0)
	{
		if (!empty($_FILES)) 
		{
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('jpg','jpeg','gif','png','tif','bmp','pdf','doc','docx','xls','xlsx','txt','rar','zip','xps','oxps','xml');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				$idArchivo	= $this->proveedores->subirArchivosSeguimiento($idSeguimiento,$_FILES['file']['name'],$_FILES['file']['size']);
				
				if($idArchivo>0)
				{
					move_uploaded_file($archivoTemporal,carpetaSeguimientoProveedores.$idArchivo.'_'.$_FILES['file']['name']);

					if(file_exists(carpetaSeguimientoProveedores.$idArchivo.'_'.$_FILES['file']['name']))
					{
						echo "1";
					}
					else
					{
						echo 'El comprobante no ha podido subir correctamente';
					}
				}
				else
				{
					echo 'Error al subir el comprobante';
				}
			} 
			else 
			{
				echo 'No se permiten estos archivos';
			}
		}
	} 
	
	function subirArchivosSeguimientoss($idSeguimiento)
	{
		if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) 
		{
			$fileName 		= $_SERVER['HTTP_X_FILE_NAME'];
			$contentLength 	= $_SERVER['CONTENT_LENGTH'];
		} 
		else throw new Exception("Error retrieving headers");
		
		$path = 'media/seguimiento/';
		
		if (!$contentLength > 0) 
		{
			throw new Exception('Error al subir el ficheros!');
		}
		
		$fileName		=obtenerMinusculas($fileName);
		$idArchivo		=$this->proveedores->subirArchivosSeguimiento($idSeguimiento,$fileName,$contentLength);
		
		if($idArchivo>0)
		{
			$fileName=$idArchivo.'_'.$fileName;
			
			file_put_contents
			(
				$path . $fileName,
				file_get_contents("php://input")
			);
			
			chmod($path.$fileName, 0777);
		}
	}
	
	public function borrarArchivoSeguimiento()
	{
		if(!empty($_POST))
		{
			$idArchivo	=$this->input->post('idArchivo');

			echo $this->proveedores->borrarArchivoSeguimiento($idArchivo);
		}
	}
		
	function descargarArchivoSeguimiento($idArchivo) #Descargar el archivo XML
	{
		$this->load->helper('download');

		$fichero	= $this->proveedores->obtenerArchivoSeguimiento($idArchivo);

		$archivo 	= $fichero->idArchivo.'_'.$fichero->nombre;

		$data = file_get_contents(carpetaSeguimientoProveedores.$archivo); 
		
		force_download($fichero->nombre, $data); 
	}
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	//REPORTES DE SEGUIMIENTOS
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	
	public function seguimientos()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jqui']			=$this->_jss['jqueryui'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='seguimientos'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#

		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('9',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data["breadcumb"]			= '<a href="'.base_url().'proveedores">Proveedores</a> > Seguimientos';

		$this->load->view("proveedores/seguimiento/reporte/index",$data);
		$this->load->view("pie",$Data);
	}

	public function obtenerSeguimientos($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		$idStatus				= $this->input->post('idStatus');
		$idServicio				= $this->input->post('idServicio');
		
		$Pag["base_url"]		= base_url()."proveedores/obtenerSeguimientos/";
		$Pag["total_rows"]		= $this->proveedores->contarSeguimientos($criterio,$inicio,$fin,$idStatus,$idServicio);//Total de Registros
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		
		$this->pagination->initialize($Pag);
		
		$data['seguimientos']	= $this->proveedores->obtenerSeguimientos($Pag["per_page"],$limite,$criterio,$inicio,$fin,$idStatus,$idServicio);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('9',$this->session->userdata('rol'));
		$data['status']			= $this->configuracion->obtenerStatus(0);
		$data['servicios']		= $this->configuracion->obtenerServicios(0);
		$data['idStatus']		= $idStatus;
		$data['idServicio']		= $idServicio;
		$data['limite']			= $limite+1;
		
		$this->load->view('proveedores/seguimiento/reporte/obtenerSeguimientos',$data);
	}
	
	//ENVÍAR LA FICHA TÉCNICA POR CORREO
	public function buscarProveedor($idProveedor)
	{
		$data['proveedor']		= $this->proveedores->obtenerProveedor($idProveedor);
		$data['contactos']		= $this->proveedores->obtenerContactos($data['proveedor']->idProveedor);
		$data['cuentas']		= $this->proveedores->obtenerCuentas($idProveedor);
		
		$this->load->view('proveedores/ficha/buscarProveedor',$data);
	}
	
	public function formularioCorreoFicha()
	{
		$data['proveedor']	= $this->proveedores->obtenerProveedorRegistro($this->input->post('idProveedor'));
		$data['usuario']	= $this->configuracion->obtenerUsuario($this->_iduser);
		$data['usuarios']	= $this->configuracion->obtenerListaUsuarios();
		
		$this->load->view('proveedores/ficha/formularioCorreoFicha',$data);
	}
	
	public function fichaPdf($idProveedor,$opcion=1)
	{
		$this->load->library('mpdf/mpdf');
		
		$data['proveedor']		= $this->proveedores->obtenerProveedor($idProveedor);
		$data['contactos']		= $this->proveedores->obtenerContactos($idProveedor);
		$data['cuentas']		= $this->proveedores->obtenerCuentas($idProveedor);
		$data['reporte']		='proveedores/ficha/buscarProveedor';

		$html	= $this->load->view('reportes/principal',$data,true);
		$pie 	= $this->load->view('reportes/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter-L','','',10,10,10,10,2,1);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		if($opcion==0)
		{
			$this->mpdf->Output(carpetaFicheros.'FichaProveedor.pdf','F');
			
			return carpetaFicheros.'FichaProveedor.pdf';
		}
		else
		{
			$this->mpdf->Output('FichaProveedor.pdf','D');
			//$this->mpdf->Output();
		}
	}
	
	public function enviarFichaProveedor()
	{
		if(!empty($_POST))
		{
			$configuracion	= $this->configuracion->obtenerConfiguracion();
			$usuario		= $this->configuracion->obtenerUsuario( $this->input->post('idUsuario'));
			
			$email			= $configuracion['correo'];
			$nombre			= $configuracion['nombre'];
			
			if($usuario!=null)
			{
				if(strlen($usuario->correo)>0)
				{
					$email	= $usuario->correo;
					$nombre	= $usuario->nombre.' '.$usuario->apellidoPaterno.' '.$usuario->apellidoMaterno;
				}
			}
			
			$idProveedor	= $this->input->post('idProveedor');
			$mensaje		= $this->input->post('mensaje');
			$destinatario	= $this->input->post('correo');
			$asunto			= $this->input->post('asunto');
			$firma			= $this->input->post('firma');
			
			$this->load->library('email');
			$this->email->from($email,$nombre);
			$this->email->to($destinatario);

			$imagen			= "";
			
			if(file_exists('img/logos/'.$this->session->userdata('logotipo')))
			{
				$imagen		= '<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="200" /><br /><br />';
			}

			$this->email->attach($this->fichaPdf($idProveedor,0));
			
			$cuerpo			= $imagen.$mensaje.'<br />'.nl2br($firma).' <br /> <br /> <strong>Por favor consulte los adjuntos</strong>'.$link;

			$this->email->subject($asunto);
			$this->email->message($cuerpo);
			
			if(!$this->email->send())
			{
				echo "0";
			}
			else
			{
				echo "1";
			}
				
		}
		else
		{
			echo "2";
		}
	}
	
	//FICHEROS PROVEEDORES
	
	public function obtenerFicheros()
	{
		$idProveedor			= $this->input->post('idProveedor');
		$data['idProveedor']	= $idProveedor;
		$data['ficheros']		= $this->proveedores->obtenerFicheros($idProveedor);
		$data['cuota']			= $this->cuota;
		
		$this->load->view('proveedores/ficheros/obtenerFicheros',$data);
	}
	
	public function subirFicheros($idProveedor=0)
	{
		if (!empty($_FILES)) 
		{
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('jpg','jpeg','gif','png','tif','bmp','pdf','doc','docx','xls','xlsx','txt','rar','zip','xps','oxps','xml');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				$idFichero	= $this->proveedores->subirFicheros($idProveedor,$_FILES['file']['name'],$_FILES['file']['size']);
				
				if($idFichero>0)
				{
					move_uploaded_file($archivoTemporal,carpetaProveedores.$idFichero.'_'.$_FILES['file']['name']);

					if(file_exists(carpetaProveedores.$idFichero.'_'.$_FILES['file']['name']))
					{
						echo "1";
					}
					else
					{
						echo 'El archivo no ha podido subir correctamente';
					}
				}
				else
				{
					echo 'Error al subir el archivo';
				}
			} 
			else 
			{
				echo 'No se permiten estos archivos';
			}
		}
	} 

	public function borrarFichero()
	{
		if(!empty($_POST))
		{
			$idFichero	=$this->input->post('idFichero');
			$fichero	=$this->proveedores->borrarFichero($idFichero);
			echo $fichero;
		}
	}
		
	function descargarFichero($idFichero) #Descargar el archivo XML
	{
		$this->load->helper('download');

		$fichero	= $this->proveedores->obtenerFichero($idFichero);
		$archivo 	= $fichero->idFichero.'_'.$fichero->nombre;
		$data 		= file_get_contents(carpetaProveedores.$archivo); 
		
		force_download($fichero->nombre, $data); 
	}
	
	#ASIGNAR PORCENTAJES
	#================================================================================================
	
	public function formularioPorcentaje()
	{
		$data['proveedor']		= $this->proveedores->obtenerProveedor($this->input->post('idProveedor'));
		$data['porcentajes']	= $this->proveedores->obtenerPorcentajesProveedor($this->input->post('idProveedor'));
		
		$this->load->view('proveedores/porcentajes/formularioPorcentaje',$data);
	}
	
	public function asignarPorcentajes()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proveedores->asignarPorcentajes());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	#MARCAS PROVEEDORES
	#================================================================================================
	
	public function obtenerMarcasProveedor()
	{
		$data['proveedor']		= $this->proveedores->obtenerProveedor($this->input->post('idProveedor'));
		$data['marcas']			= $this->proveedores->obtenerMarcasProveedor($this->input->post('idProveedor'));
		
		$this->load->view('proveedores/marcas/obtenerMarcasProveedor',$data);
	}
	
	public function registrarMarcaProveedor()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proveedores->registrarMarcaProveedor());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarMarcaProveedor()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proveedores->borrarMarcaProveedor());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarInventarioProveedor()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->proveedores->borrarInventarioProveedor());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
		
}
?>
