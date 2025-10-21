<?php
class InventarioProductos extends CI_Controller
{
    private $_template;
    protected $_fechaActual;
    protected $_iduser;
    protected $_csstyle;
    protected $cuota;
	protected $precios;
	protected $tiendaLocal;
	protected $registroVentas;
	protected $idLicencia;

	function __construct()
	{
		parent::__construct();

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
		
		
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("modeloclientes","modeloclientes");
		$this->load->model("produccion_modelo","modeloproduccion");
		$this->load->model("materiales_modelo","materiales");
		$this->load->model("proveedores_model","proveedores");
		$this->load->model("inventario_model","modeloinventario");
		$this->load->model("inventarioproductos_modelo","inventario");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("tiendas_modelo","tiendas");
		$this->load->model("catalogos_modelo","catalogos");
		$this->load->model("importar_modelo","importar");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
		
		$this->precios	= $this->session->userdata('precios');
		$this->tiendaLocal		= $this->session->userdata('tiendaLocal');
		$this->registroVentas	= $this->session->userdata('registroVentas');
		$this->idLicencia		= $this->session->userdata('idLicencia');
	}
	
	public function imprimirProductos()
	{
		$criterio				= $this->input->post('criterio');
		$orden					= $this->input->post('orden');
		$minimo					= $this->input->post('minimo');
		$codigoInterno			= $this->input->post('codigoInterno');

		$this->load->library('mpdf/mpdf');
		$this->load->library('ccantidadletras');
		
		ini_set("memory_limit","1600M");
		set_time_limit(0); 
		
		$data['productos'] 	= $this->importar->exportarProductos($criterio,$orden,$minimo,$codigoInterno);
		$data['reporte'] 	= 'inventarioProductos/PDF/imprimirProductos';

		$html				= $this->load->view('reportes/principal',$data,true);
		$pie				= $this->load->view('inventarioProductos/PDF/pie',$data,true);
		
		$margen				= 35.1;
		
		if(!file_exists('img/logos/'.$this->session->userdata('logotipo')) or strlen($this->session->userdata('logotipo'))<4)
		{
			$margen				= 21;
		}
		
		$this->mpdf->mPDF('en-x','Letter','','',5,5,$margen,15,7,10);
		
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($piesito,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->useSubstitutions=true; 
		$this->mpdf->simpleTables = true;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output(carpetaFicheros.'InventarioProductos.pdf','F');
		
		echo 'InventarioProductos';
	}

	
	public function enviarProductosTienda()
	{
		if(!empty($_POST))
		{
			$envio=$this->inventario->enviarProductosTienda();
			echo $envio;
		}
	}


	public function index()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			= $this->_csstyle["cassadmin"];
		$Data['csmenu']				= $this->_csstyle["csmenu"];
		$Data['csvalidate']			= $this->_csstyle["csvalidate"];
		$Data['csui']				= $this->_csstyle["csui"];
		$Data['nameusuario']		= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		= $this->_fechaActual;
		$Data['Jry']				= $this->_jss['jquery'];
		$Data['JFuntInventario']	= $this->_jss['JFuntInventario'];
		$Data['Jqui']				= $this->_jss['jqueryui'];                  
		#$Data['jFicha_cliente']		= $this->_jss['jFicha_cliente']; 
		$Data['permisos']			= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			= 'inventarioProductos'; 
		$Data['conectados']			= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);

		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('15',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['tiendas'] 			= $this->tiendas->obtenerTiendasUsuario();
		$data["breadcumb"]			= 'Catálogo de productos';
		$data['tiendaLocal'] 		= $this->tiendaLocal;

		$this->load->view("inventarioProductos/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerProductos($limite=0)
	{
		$criterio				= $this->input->post('criterio');
		$orden					= $this->input->post('orden');
		$minimo					= $this->input->post('minimo');
		$codigoInterno			= $this->input->post('codigoInterno');
		
		#----------------------------------PAGINACION------------------------------------#
		$url					= base_url()."inventarioProductos/obtenerProductos/";
		$registros				= $this->inventario->contarProductos($criterio,$minimo,$codigoInterno);
		$numero					= 25;
		$links					= 5;
		$uri					= 3;
		
		$paginador				= $this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('15',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['productos'] 		= $this->inventario->obtenerProductosPaginado($numero,$limite,$criterio,$orden,$minimo,$codigoInterno);
		$data['inicio']  		= $limite;
		$data['orden']  		= $orden;
		$data['minimo']  		= $minimo;
		$data['precios']  		= $this->precios;
		$data['tiendaLocal'] 	= $this->tiendaLocal;

		$this->load->view("inventarioProductos/obtenerProductos",$data);
	}
	
	public function obtenerCatalogoProductos()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('15',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}

		$this->load->view("inventarioProductos/index",$data);
	}
	
	#SERVICIOS
	#====================================================================================================
	public function servicios()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		#$Data['JFuntInventario']=$this->_jss['JFuntInventario'];
		$Data['Jqui']			= $this->_jss['jqueryui'];                  
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'servicios'; 
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
	
	
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('16',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['periodos'] 			= $this->configuracion->obtenerPeriodosProduccion();
		$data["breadcumb"]			= 'Catálogo de servicios';

		$this->load->view("inventarioProductos/servicios/servicios",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerServicios($limite=0)
	{
		$criterio		= $this->input->post('criterio');
		#----------------------------------PAGINACION------------------------------------#
		$url			= base_url()."inventarioProductos/obtenerServicios/";
		$registros		= $this->inventario->contarServicios($criterio);
		$numero			= 30;
		$links			= 5;
		$uri			= 3;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('16',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['servicios'] 			= $this->inventario->obtenerServiciosPaginado($numero,$limite,$criterio);
		$data['periodos'] 			= $this->configuracion->obtenerPeriodosProduccion();
		$data['inicio']  			= $limite;
		$data['criterio']  			= $criterio;

		$this->load->view("inventarioProductos/servicios/obtenerServicios",$data);
	}
	
	public function formularioServicios()
	{
		$data['periodos']			= $this->configuracion->obtenerPeriodosProduccion();
		$data['lineas']				= $this->configuracion->obtenerLineas();
		$data['configuracion'] 		= $this->configuracion->obtenerConfiguraciones(1);
		$data['departamentos'] 		= $this->catalogos->obtenerDepartamentos();
		$data['marcas'] 			= $this->catalogos->obtenerMarcas();
		$data['impuestos'] 			= $this->configuracion->obtenerImpuestos();
		$data['unidades'] 			= $this->configuracion->seleccionarUnidades();
		
		$this->load->view("inventarioProductos/servicios/formularioServicios",$data);
	}
	
	public function registrarServicio()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$servicio	= $this->inventario->registrarServicio();
			
			$servicio[0]=="1"?
				$this->session->set_userdata('notificacion','El servicio se ha registrado correctamente'):'';
			
			echo json_encode($servicio);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarServicio()
	{
		if(!empty ($_POST))
		{
			$servicio	= $this->inventario->editarServicio();
			
			$servicio=="1"?
				$this->session->set_userdata('notificacion','El servicio se ha editado correctamente'):'';
			
			echo $servicio;
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerServicio($idProducto)
	{
		$data['producto']			= $this->inventario->obtenerDetallesProducto($idProducto);
		$data['periodos']			= $this->configuracion->obtenerPeriodosProduccion();
		$data['lineas']				= $this->configuracion->obtenerLineas();
		$data['configuracion'] 		= $this->configuracion->obtenerConfiguraciones(1);
		$data['departamentos'] 		= $this->catalogos->obtenerDepartamentos();
		$data['marcas'] 			= $this->catalogos->obtenerMarcas();
		$data['impuestos'] 			= $this->configuracion->obtenerImpuestos();
		$data['unidades'] 			= $this->configuracion->seleccionarUnidades();
		
		$this->load->view("inventarioProductos/servicios/obtenerServicio",$data);
	}
	
	

	#INVENTARIOS
	#====================================================================================================
	public function inventarios($limite=0,$idInventario=0)
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jqui']			= $this->_jss['jqueryui'];                  
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'inventarios'; 
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);

		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('17',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}

		$this->load->view("inventarioProductos/inventarios/inventarios",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerInventarios($limite=0)
	{
		$criterio	= $this->input->post('criterio');
		#----------------------------------PAGINACION------------------------------------#
		$url		= base_url()."inventarioProductos/obtenerInventarios/";
		$registros	= $this->inventario->contarInventarios($criterio);
		$numero		= 20;
		$links		= 5;
		$uri		= 3;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('17',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			
			return;
		}
		
		$data['inventarios'] 	= $this->inventario->obtenerInventarios($numero,$limite,$criterio);
		$data['limite']  		= $limite+1;
		
		$this->load->view("inventarioProductos/inventarios/obtenerInventarios",$data);
	}
	
	public function obtenerCatalogoInventarios()
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('17',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}

		$this->load->view("inventarioProductos/inventarios/inventarios",$data);
	}
	
	public function formularioInventarios()
	{
		$this->load->view("inventarioProductos/inventarios/formularioInventarios");
	}
	
	public function registrarInventario()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->inventario->registrarInventario());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarInventario()
	{
		if(!empty($_POST))
		{
			#----------------------------------PERMISOS------------------------------------#
			$data['permiso']=$this->configuracion->obtenerPermisosBoton('17',$this->session->userdata('rol'));
			
			if($data['permiso'][3]->activo=='0')
			{
				$this->load->view('accesos/index');
				return;
			}
			
			echo $this->inventario->borrarInventario($this->input->post('idInventario'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function formularioAgregarProveedor()
	{
		$idInventario			= $this->input->post('idInventario');
		$data['proveedores']	= $this->proveedores->obtenerProveedores();
		$data['idInventario']	= $idInventario;
		
		$this->load->view("inventarioProductos/inventarios/proveedores/formularioAgregarProveedor",$data);
	}
	
	public function asociarProveedorInventario()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			echo json_encode($this->inventario->asociarProveedorInventario());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarInventario()
	{
		if(!empty ($_POST))
		{
			echo $this->inventario->editarInventario();
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerInventario()
	{
		$data['inventario']		= $this->inventario->obtenerInventario($this->input->post('idInventario'));
		$data['proveedores']	= $this->proveedores->obtenerProveedores();
		$data['inventarios']	= $this->inventario->obtenerInventarioProveedor($this->input->post('idInventario'));
		
		$this->load->view("inventarioProductos/inventarios/obtenerInventario",$data);
	}
	
	public function obtenerUsosInventario($limite=0)
	{
		$idInventario	= $this->input->post('idInventario');
		
		#----------------------------------PAGINACION------------------------------------#
		$url					= base_url()."inventarioProductos/obtenerUsosInventario/";
		$registros				= $this->inventario->contarUsosInventario();
		$numero					= 10;
		$links					= 5;
		$uri					= 3;
		
		$paginador				= $this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		$data['usos'] 			= $this->inventario->obtenerUsosInventario($numero,$limite);
		$data['inventario'] 	= $this->inventario->obtenerInventario($idInventario);
		$data['idInventario'] 	= $idInventario;
		
		$this->load->view("inventarioProductos/inventarios/usos/obtenerUsosInventario",$data);
	}
	
	public function registrarUsoInventario()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			echo json_encode($this->inventario->registrarUsoInventario());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarCostoInventario()
	{
		if(!empty ($_POST))
		{
			echo $this->inventario->editarCostoInventario();
		}
		else
		{
			echo "0";
		}
	}

    public function borrarProducto()
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
			
			echo $this->inventario->borrarProducto($this->input->post('idProducto'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarServicioProducto($idProducto)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('16',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$producto	=$this->inventario->borrarServicioProducto($idProducto);
		
		if($producto=="0")
		{
			$this->session->set_userdata('errorNotificacion','El servicio esta asociado a ventas y/o cotizaciones, no puede borrarse');
		}
		else
		{
			$this->session->set_userdata('notificacion','El servicio se ha borrado correctamente');
		}
		
		redirect("inventarioProductos/servicios","refresh");
	}
	
	 public function borrarProductoCaja($idProducto,$id,$pagina)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('15',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$borrar=$this->inventario->borrarProductoCaja($idProducto,$id);
		
		redirect("/inventarioProductos/index/".$pagina,"refresh");
	}

	public function registrarProducto()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			$imagen 	= $_FILES['userfile']['name'];
			$producto	= $this->inventario->registrarProducto($imagen);

			if($producto[1]>0)
			{
				move_uploaded_file($_FILES['userfile']['tmp_name'], carpetaProductos.basename($producto[1]."_".$imagen));
				
				echo json_encode(array('1',registroCorrecto));
			}
			else
			{
				echo json_encode(array("0",errorRegistro));
			}
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}

	public function editarProducto()
	{
		if(!empty ($_POST))
		{
			$imagen 		= $_FILES['userfile']['name'];
			$producto		= $this->inventario->editarProducto($imagen);
			$idProducto		= $this->input->post('txtIdProducto');

			move_uploaded_file($_FILES['userfile']['tmp_name'], carpetaProductos . basename($idProducto."_".$imagen));
			
			echo $producto;
		}
		else
		{
			echo "0";
		}
	}

	public function obtenerDetallesProducto()
	{
		$data['producto']			= $this->inventario->obtenerDetallesProducto($this->input->post('idProducto'));
		$data['lineas']				= $this->configuracion->obtenerLineas(); 
		$data['subLineas']			= $this->configuracion->obtenerSubLineas($data['producto']->idLinea); 
		$data['idProducto']			= $this->input->post('idProducto');
		$data['configuracion'] 		= $this->configuracion->obtenerConfiguraciones(1);
		$data['departamentos'] 		= $this->catalogos->obtenerDepartamentos();
		$data['marcas'] 			= $this->catalogos->obtenerMarcas();
		$data['unidades'] 			= $this->configuracion->seleccionarUnidades();
		$data['impuestos'] 			= $this->configuracion->obtenerCatalogoImpuestos();
		
		if($this->precios=='1')
		{
			$this->load->view('inventarioProductos/precios/obtenerDetallesProducto',$data);
		}
		else
		{
			$this->load->view('inventarioProductos/obtenerDetallesProducto',$data);
		}
	}
	
	public function formularioActualizarProducto()
	{
		if($this->idLicencia==1)
		{
			$data['licencias']	= $this->configuracion->obtenerLicenciasActivas();
			
			$this->load->view('inventarioProductos/precios/formularioActualizarProductoSucursales',$data);
		}
		else
		{
			$this->load->view('inventarioProductos/precios/formularioActualizarProducto');
		}
	}
	
	public function editarProductoActualizar()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->inventario->editarProductoActualizar());
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	public function editarProductoActualizarSucursales()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->inventario->editarProductoActualizarSucursales());
		}
		else
		{
			echo json_encode(array("0"));
		}
	}
	
	public function editarDetalleConfirmar()
	{
		if(!empty ($_POST))
		{
			$producto=$this->inventario->editarProductoDetalleConfirmar();
			print($producto);
		}
	}
	
	public function editarDetalleCaja($idProductoCaja,$idProducto)
	{
		$producto=$this->inventario->obtenerDetalleCaja($idProductoCaja,$idProducto);
		
		print
		('
			<table class="admintable" width="99%">
			<tr>
			<th id="nombreDetalle" colspan="2">Editar detalle de producto</th>
			</tr>
			<tr>
			<td class="key"> Cantidad</td>
			<td><input type="text" class="cajas" id="cantidadProducto" name="cantidadProducto" value="'.number_format($producto->cantidad,0).'" /></td>
			</tr>
			</table>
		');
	}
	
	public function agregarProducto()
	{
		if(!empty ($_POST))
		{
			$inventario=$this->inventario->agregarProductoCaja();
			print($inventario);
		}
	}

	//DETALLES DEL PRODUCTO
	//=================================================================================================================
	public function obtenerDetalleProducto($idProducto)
	{
		$data['producto']	= $this->inventario->obtenerDetallesProducto($idProducto);
		$data['idProducto']	= $idProducto;
		
		if(sistemaActivo=='cerraduras')
		{
			$this->load->view('inventarioProductos/obtenerDetalleProductoCerraduras',$data);
		}
		else
		{
			$this->load->view('inventarioProductos/obtenerDetalleProducto',$data);
		}
		
	}
	
	#AGREGAR PROVEEDOR A PRODUCTO
	#================================================================================================
	
	public function obtenerTodosProveedores()
	{
		if(!empty($_POST))
		{
			$idProducto						= $this->input->post('idProducto');
			$data['proveedores']			= $this->modeloinventario->obtenerProveedores();
			$data['proveedoresAsociados']	= $this->inventario->obtenerProveedoresAsociados($idProducto);
			$data['permiso']				= $this->configuracion->obtenerPermisosBoton('15',$this->session->userdata('rol'));
			$data['idProducto']				= $idProducto;
			$data['editar']					= $this->input->post('editar');
			
			$this->load->view('inventarioProductos/proveedores/obtenerTodosProveedores',$data);
		}
	}
	
	public function editarCostoProveedor()
	{
		if(!empty ($_POST))
		{
			echo $this->inventario->editarCostoProveedor();
		}
		else
		{
			echo "0";
		}
	}
	
	public function asociarProveedorProducto()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->inventario->asociarProveedorProducto());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarProveedorProducto()
	{
		if(!empty ($_POST))
		{
			$inventario=$this->inventario->borrarProveedorProducto();
			echo $inventario;
		}
	}
	
	public function formularioProductos()
	{
		$data['configuracion'] 	= $this->configuracion->obtenerConfiguraciones(1);
		$data['departamentos'] 	= $this->catalogos->obtenerDepartamentos();
		$data['marcas'] 		= $this->catalogos->obtenerMarcas();
		$data['unidades'] 		= $this->configuracion->seleccionarUnidades();
		$data['impuestos'] 		= $this->configuracion->obtenerCatalogoImpuestos();
		$data['precios'] 		= $this->precios;
		
		if($this->precios=='1')
		{
			if(sistemaActivo=='cerraduras')
			{
				$this->load->view('inventarioProductos/precios/formularioProductosCerraduras',$data);	
			}
			else
			{
				$this->load->view('inventarioProductos/precios/formularioProductos',$data);
			}
			
		}
		else
		{
			$this->load->view('inventarioProductos/formularioProductos',$data);
		}		
	}
	
	#ASIGNAR PROVEEDORES A PRODUCTOS
	#================================================================================================
	
	public function formularioAsignarProveedor()
	{
		$this->load->view('inventarioProductos/proveedores/formularioAsignarProveedor');
	}
	
	public function asignarProveedor()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->inventario->asignarProveedor());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	
	#ASIGNAR PORCENTAJES
	#================================================================================================
	
	public function formularioPorcentaje()
	{
		$data['producto']		= $this->inventario->obtenerProducto($this->input->post('idProducto'));
		$data['porcentajes']	= $this->inventario->obtenerPorcentajesProducto($this->input->post('idProducto'));
		
		if($this->idLicencia==1)
		{
			$data['licencias']	= $this->configuracion->obtenerLicenciasActivas();
			
			$this->load->view('inventarioProductos/porcentajes/formularioPorcentajeAlmacen',$data);
		}
		else
		{
			$this->load->view('inventarioProductos/porcentajes/formularioPorcentaje',$data);
		}
		
	}
	
	public function asignarPorcentajes()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->inventario->asignarPorcentajes());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function asignarPorcentajesSucursales()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->inventario->asignarPorcentajesSucursales());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarInventarioSucursal()
	{
		if(!empty ($_POST))
		{
			echo json_encode($this->inventario->borrarInventarioSucursal());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
}
?>
