<?php
class Compras extends CI_Controller
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

		if(!$this->redux_auth->logged_in())
		{
 			redirect(base_url().'login');
 		}
		
		$this->config->load('js',TRUE);
		$this->config->load('style', TRUE);
		$this->config->load('datatables', TRUE);
		
		$datestring   		= "%Y-%m-%d %H:%i:%s";
	 	$this->_fechaActual = mdate($datestring,now());
		$this->_iduser 		= $this->session->userdata('id');
		$this->_role 		= $this->session->userdata('role');
		$this->_tables 		= $this->config->item('datatables');
		$this->_csstyle 	= $this->config->item('style');
        $this->_jss			= $this->config->item('js');
		
		$this->load->model("crm_modelo","crm");
        $this->load->model("modelousuario","modelousuario");
		$this->load->model("administracion_modelo","administracion");
        $this->load->model("modeloclientes","modeloclientes");
        $this->load->model("ventas_model","ventas");
		$this->load->model("compras_modelo","compras");
		$this->load->model("bancos_model","bancos");
		$this->load->model("proveedores_model","proveedores");
		$this->load->model('modelo_configuracion','configuracion');
		$this->load->model('reportes_model','reportes');
		$this->load->model("materiales_modelo","materiales");
		$this->load->model("contabilidad_modelo","contabilidad");
		$this->load->model("inventarioproductos_modelo","inventarios");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}

	public function index($idProveedor=0)
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
		$Data['menuActivo']		= 'comprasMateria'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['materiales']			= $this->configuracion->obtenerPermisosBoton('10',$this->session->userdata('rol'));
		$data['productos']			= $this->configuracion->obtenerPermisosBoton('11',$this->session->userdata('rol'));
		$data['inventarios']		= $this->configuracion->obtenerPermisosBoton('12',$this->session->userdata('rol'));
		$data['servicios']			= $this->configuracion->obtenerPermisosBoton('52',$this->session->userdata('rol'));
		$data['permisoContactos']	= $this->configuracion->obtenerPermisosBoton('8',$this->session->userdata('rol'));
		
		if($data['materiales'][0]->activo=='0' and $data['productos'][0]->activo=='0' and $data['inventarios'][0]->activo=='0' and $data['servicios'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$data['idProveedor']  	= $idProveedor;
		
		if($idProveedor==0)
		{
			$data["breadcumb"]		= 'Compras';
		}
		else
		{
			$data['proveedor']		= $this->proveedores->obtenerProveedorNombre($idProveedor);
			$data["breadcumb"]		= '<a href="'.base_url().'proveedores">Proveedores</a> > <a href="'.base_url().'proveedores/index/0/'.$idProveedor.'">'.$data['proveedor'].'</a> > Compras ';
		}

		$this->load->view("compras/global/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}
	
	public function obtenerComprasGlobal($limite=0)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['materiales']		= $this->configuracion->obtenerPermisosBoton('10',$this->session->userdata('rol'));
		$data['productos']		= $this->configuracion->obtenerPermisosBoton('11',$this->session->userdata('rol'));
		$data['inventarios']	= $this->configuracion->obtenerPermisosBoton('12',$this->session->userdata('rol'));
		$data['servicios']		= $this->configuracion->obtenerPermisosBoton('52',$this->session->userdata('rol'));
		
		if($data['materiales'][0]->activo=='0' and $data['productos'][0]->activo=='0' and $data['inventarios'][0]->activo=='0' and $data['servicios'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			return;
		}
		
		$idProveedor			= $this->input->post('idProveedor');
		$criterio				= $this->input->post('criterio');
		$inicio					= $this->input->post('inicio');
		$fin					= $this->input->post('fin');
		
		$Pag["base_url"]		= base_url()."compras/obtenerComprasGlobal/";
		$Pag["total_rows"]		= $this->compras->contarComprasGlobal($inicio,$fin,$criterio,$idProveedor,$data['materiales'][0]->activo,$data['productos'][0]->activo,$data['inventarios'][0]->activo,$data['servicios'][0]->activo);
		$Pag["per_page"]		= 30;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;
		
		$this->pagination->initialize($Pag);

		$data['compras'] 		= $this->compras->obtenerComprasGlobal($Pag["per_page"],$limite,$inicio,$fin,$criterio,$idProveedor,$data['materiales'][0]->activo,$data['productos'][0]->activo,$data['inventarios'][0]->activo,$data['servicios'][0]->activo);
		$data['inicio']  		= $limite+1;
		$data['idProveedor']  	= $idProveedor;

		$this->load->view("compras/global/obtenerComprasGlobal",$data);
	}

	public function prebusquedaFecha($fecha)
	{
		if($fecha=='nada')
		{
			$fecha="";			
		}
		
		$this->session->set_userdata('fechaCompras',$fecha);
		
		redirect('compras/administracion','refresh');
	}
	
	#------------------------------OBTENER TODAS LAS COMPRAS---------------------------------#	 
	public function administracion()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['JFuntInventario']=$this->_jss['JFuntInventario'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['Jqui']			=$this->_jss['jqueryui'];                  
		#$Data['jFicha_cliente']	=$this->_jss['jFicha_cliente']; 
		#$Data['JFuntPagClien']	=$this->_jss['JFuntPagClien'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='comprasMateria'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
	
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('10',$this->session->userdata('rol'));
		$data['permisoMateriales']	= $this->configuracion->obtenerPermisosBoton('18',$this->session->userdata('rol'));
		$data['permisoCrm']			= $this->configuracion->obtenerPermisosBoton('9',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['orden'] 			= $this->compras->obtenerConsecutivoCompras();
		$data['idRol'] 			= $this->_role;
		$data["breadcumb"]		= $this->_role!=6?'<a href="'.base_url().'compras"> Compras </a> > Compras de '.(sistemaActivo=='IEXE'?'Insumos':'materia prima').'':'Recepciones';
		$data['configuracion'] 	= $this->configuracion->obtenerConfiguraciones(1);

		$this->load->view("compras/materiales/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}
	
	public function obtenerComprasMateriales($limite=0)
	{
		$inicio		= $this->input->post('inicio');
		$fin		= $this->input->post('fin');
		$criterio	= $this->input->post('criterio');
		
		#----------------------------------PAGINACION------------------------------------#
		$url			= base_url()."compras/obtenerComprasMateriales/";
		$registros		= $this->compras->contarCompras($inicio,$fin,$criterio);
		$numero			= 20;
		$links			= 5;
		$uri			= 6;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('10',$this->session->userdata('rol'));
		$data['permisoMateriales']	= $this->configuracion->obtenerPermisosBoton('18',$this->session->userdata('rol'));
		$data['permisoCrm']			= $this->configuracion->obtenerPermisosBoton('9',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['compras'] 		= $this->compras->obtenerCompras($numero,$limite,$inicio,$fin,$criterio);
		$data['proveedores'] 	= $this->proveedores->obtenerProveedores();
		$data['unidades'] 		= $this->configuracion->obtenerUnidades();
		$data['idRol'] 			= $this->_role;
		
		$data['inicio']  		= $limite;


		$this->load->view("compras/materiales/obtenerComprasMateriales",$data); //principal lista de clientes
	}

	public function registrarCompraMateria()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			$compra	 =$this->compras->registrarCompraMateria();
			
			if($compra[0]=="1")
			{
				#$this->session->set_userdata('notificacion','La compra se ha registrado correctamente');
			}
			
			echo json_encode($compra);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
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

			echo json_encode($this->compras->realizarPago());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
   
   	public function borrarPago()
	{
		if(!empty($_POST))
		{
			echo $this->compras->borrarPago($this->input->post('idPago'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function cerrarCompra()
	{
		if(!empty($_POST))
		{
			echo $this->compras->cerrarCompra($this->input->post('idCompras'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function borrarCompra($idCompras,$seccion)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('10',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$compra	= $this->compras->comprasBorrar($idCompras,$seccion);
		
		if($compra=="1")
		{
			$this->session->set_userdata('notificacion','La compra se ha borrado correctamente');
		}
		
		if($compra=="2")
		{
			$this->session->set_userdata('notificacion','El producto recibo no se pudo borrar ya que no existe en el inventario');
		}
		
		if($compra=="0")
		{
			$this->session->set_userdata('errorNotificacion','Error al borrar la compra');
		}
		
		if($seccion=="compras")
		{
			redirect("compras/administracion","refresh");
		}
		
		if($seccion=="proveedores")
		{
			redirect("proveedores/compras","refresh");
		}
		
		if($seccion=="productos")
		{
			redirect("compras/productos","refresh");
		}
		
		if($seccion=="inventarios")
		{
			redirect("compras/inventarios","refresh");
		}
		
		if($seccion=="servicios")
		{
			redirect("servicios/compras","refresh");
		}
	}
	
	
	public function cancelarCompra($idCompras,$seccion)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('3',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$compra	= $this->compras->cancelarCompra($idCompras,$seccion);
		
		if($compra=="1")
		{
			$this->session->set_userdata('notificacion','La compra se ha cancelado correctamente');
		}
		
		if($compra=="2")
		{
			$this->session->set_userdata('notificacion','El producto recibo no se pudo borrar ya que no existe en el inventario');
		}
		
		if($compra=="0")
		{
			$this->session->set_userdata('errorNotificacion','Error al cancelar la compra');
		}
		
		if($seccion=="compras")
		{
			redirect("compras/administracion","refresh");
		}
		
		if($seccion=="proveedores")
		{
			redirect("proveedores/compras","refresh");
		}
		
		if($seccion=="productos")
		{
			redirect("compras/productos","refresh");
		}
		
		if($seccion=="inventarios")
		{
			redirect("compras/inventarios","refresh");
		}
		
		if($seccion=="servicios")
		{
			redirect("servicios/compras","refresh");
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	#-----------------------------Productos comprados------------------------------#
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function formularioRecibirTodosMateriales()
	{
		if(!empty($_POST))
		{
			$data['compra']		= $this->compras->obtenerCompra($this->input->post('idCompras'));

			$this->load->view('compras/materiales/formularioRecibirTodosMateriales',$data);
		}
	}
	
	public function recibirTodosMateriales()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->compras->recibirTodosMateriales());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerProductosComprados()
	{
		if(!empty($_POST))
		{
			$idCompras			= $this->input->post('idCompras');
			$data['compras']	= $this->compras->obtenerProductosComprados($idCompras);
			$data['compra']		= $this->compras->obtenerCompra($idCompras);
			$data['idCompras']	= $idCompras;

			$this->load->view('compras/materiales/obtenerProductosComprados',$data);
		}
	}
	
	public function productosRecibidos()
	{
		$idDetalle			= $this->input->post('idDetalle');
		$data['recibidos']	= $this->compras->productosRecibidos($idDetalle);
		$data['producto']	= $this->compras->obtenerProductoCompra($idDetalle);
		$data['compra']		= $this->compras->obtenerCompra($data['producto']->idCompra);
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('10',$this->session->userdata('rol'));
		$data['idDetalle']	= $idDetalle;
		
		$this->load->view('compras/materiales/productosRecibidos',$data);
	}
	
	public function obtenerMaterialesCompra($limite=0)
	{
		$url		= base_url()."compras/obtenerMaterialesCompra/";
		$registros	= $this->compras->contarMaterialesInventario();
		$numero		= 6;
		$links		= 5;
		$uri		= 3;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		$data['productos'] 	= $this->compras->obtenerMaterialesInventario($numero,$limite);
		$data['limite'] 	= $limite+1;
		
		$this->load->view('compras/materiales/obtenerMaterialesCompra',$data);
	}
	
	public function borrarMaterialRecibido()
	{
		if(!empty($_POST))
		{
			echo $this->compras->borrarMaterialRecibido();
		}
		else
		{
			echo '0';
		}
	}

	#-----------------------------Confirmar la entrega------------------------------#
	
	#------------------------------OBTENER TODAS LAS COMPRAS DE PRODUCTOS---------------------------------#	 
	public function productos($fecha='fecha',$idCompras=0,$idProveedor=0,$limite=0)
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jqui']			= $this->_jss['jqueryui'];                  
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'comprasProductos'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PAGINACION------------------------------------#
		$url			=base_url()."compras/productos/".$fecha.'/'.$idCompras.'/'.$idProveedor.'/';
		$registros		=$this->compras->contarComprasProductos($fecha,$idCompras,$idProveedor);
		$numero			=20;
		$links			=5;
		$uri			=6;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		#---------------------------------------------------------------------------------#
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('11',$this->session->userdata('rol'));
		$data['permisoProductos']	= $this->configuracion->obtenerPermisosBoton('15',$this->session->userdata('rol'));
		$data['permisoCrm']			= $this->configuracion->obtenerPermisosBoton('9',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['compras'] 		= $this->compras->obtenerComprasProductos($numero,$limite,$fecha,$idCompras,$idProveedor);
		$data['proveedores'] 	= $this->proveedores->obtenerProveedores();
		$data['unidades'] 		= $this->configuracion->obtenerUnidades();
		$data['orden'] 			= $this->compras->obtenerConsecutivoCompras();
		$data['configuracion'] 	= $this->configuracion->obtenerConfiguraciones(1);
		$data['inicio']  		= $limite;
		$data['fecha']  		= $fecha;
		$data['idCompras']  	= $idCompras;
		$data['idProveedor']  	= $idProveedor;
		#$data["breadcumb"]		= '<a href="'.base_url().'compras"> Compras </a> > Compras de productos';
		$data["breadcumb"]		= 'Compras de productos'; //SOLO TEMPORAL


		$this->load->view("compras/productos/productos",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}
	
	public function confirmarRecibirCompra()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			
			echo json_encode($this->compras->confirmarRecibirCompra());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function confirmarDescuento()
	{
		if(!empty($_POST))
		{
			echo $this->compras->confirmarDescuento();
		}
	}
	
	public function comprasPDFProductos($idCompra,$idLicencia)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');

		$data['compra'] 	=$this->compras->obtenerCompra($idCompra);
		#$data['cliente'] =$this->modeloclientes->obtenerDatosCliente($data['factura']->cliente);
		$data['productos'] 	=$this->compras->obtenerPDFProductos($idCompra);
		$data['empresa'] 	=$this->configuracion->obtenerConfiguraciones($idLicencia);
		 
		#print($data['compra']->total);
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['compra']->total);
		$this->ccantidadletras->setMoneda("pesos");//
		$data['cantidadLetra']	= $this->ccantidadletras->PrimeraMayuscula();
		
		$html	= $this->load->view('compras/pdfCompras',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,5);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}

	#==============================================================================================#
	#=============================COMPRAS DE PRODUCTOS DE REVENTA==================================#
	#==============================================================================================#
	public function formularioRecibirTodosProductos()
	{
		if(!empty($_POST))
		{
			$data['compra']		= $this->compras->obtenerCompra($this->input->post('idCompras'));
			$data['licencias']	= $this->configuracion->obtenerLicenciasTraspaso();

			$this->load->view('compras/productos/formularioRecibirTodosProductos',$data);
		}
	}
	
	public function borrarProductoRecibido()
	{
		if(!empty($_POST))
		{
			echo $this->compras->borrarProductoRecibido();
		}
		else
		{
			echo '0';
		}
	}
	
	public function confirmarRecibirCompraProductos()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			
			echo json_encode($this->compras->confirmarRecibirCompraProductos());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function recibirTodosProductos()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			
			echo json_encode($this->compras->recibirTodosProductos());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerProductosRecibidos()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$idDetalle			= $this->input->post('idDetalle');
		$data['recibidos']	= $this->compras->productosRecibidos($idDetalle);
		$data['producto']	= $this->compras->obtenerdDetalleProductoCompra($idDetalle);
		$data['compra']		= $this->compras->obtenerCompra($data['producto']->idCompra);
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('11',$this->session->userdata('rol'));
		$data['licencias']	= $this->configuracion->obtenerLicenciasTraspaso();
		$data['idDetalle']	= $idDetalle;
		
		$this->load->view('compras/productos/obtenerProductosRecibidos',$data);
	}
	
	public function obtenerCompradosProductos()
	{
		if(!empty($_POST))
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			
			$idCompras		    = $this->input->post('idCompras');
			$data['compra']		= $this->compras->obtenerCompra($idCompras);
			$data['compras']	= $this->compras->obtenerCompradosProductos($idCompras);
			$data['idCompras']	= $idCompras;
			
			$this->load->view('compras/productos/obtenerCompradosProductos',$data);
		}
	}
	
	public function registrarCompraProducto()
	{
		if(!empty($_POST))
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			$compra	=$this->compras->registrarCompraProducto();
			
			if($compra[0]=="1")
			{
				$this->session->set_userdata('notificacion','La compra se ha registrado correctamente');
			}
			
			echo json_encode($compra);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerProductosReventa($limite=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$url		= base_url()."compras/obtenerProductosReventa/";
		$registros	= $this->compras->contarProductosReventa();
		$numero		= 6;
		$links		= 5;
		$uri		= 3;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		$data['productos'] 	= $this->compras->obtenerProductosReventa($numero,$limite);
		
		$this->load->view('compras/productos/obtenerProductosReventa',$data);
	}
	
	public function obtenerPagosCompras()
	{
		$idCompra			= $this->input->post('idCompra');
		$data['compra']		= $this->compras->comprasPagos($idCompra);
		$data['bancos']		= $this->bancos->obtenerBancos(1);
		$data['total']		= $this->compras->obtenerTotal($idCompra);
		$data['pagos']		= $this->compras->obtenerPagos($idCompra);
		$data['recibido']	= $this->compras->obtenerDetalleCompraRecibido($idCompra);
		$data['formas']		= $this->configuracion->seleccionarFormas();
		$data['ultimo']		= $this->compras->obtenerUltimoPagoCompras($data['compra']->idProveedor);
		
		$data['departamentos']	= $this->administracion->obtenerDepartamentos();
		$data['nombres']		= $this->administracion->obtenerNombres();
		$data['conceptos']		= $this->administracion->obtenerProductos();
		$data['gastos']			= $this->administracion->obtenerTipoGasto();
		
		if($data['ultimo']!=null)
		{
			$data['cuentas']			= $this->bancos->obtenerCuentasBanco($data['ultimo']->idBanco);
		}
		
		$data['idCompra'] 	= $idCompra;
		
		$data['productos'] 		= $this->compras->obtenerProductosPDF($idCompra);
		
		if($data['compra']->reventa==1)
		{
			$data['productos'] 	=$this->compras->obtenerPDFProductos($idCompra);
		}
		
		if($data['compra']->inventario==1)
		{
			$data['productos'] 		= $this->compras->obtenerPDFInventarios($idCompra);
		}
		
		if($data['compra']->servicios==1)
		{
			$data['productos'] 		= $this->compras->obtenerServiciosComprados($idCompra);
		}
		
		$this->load->view('compras/obtenerPagosCompras',$data);
	}

	public function precioMaterial()
	{
		if(!empty($_POST))
		{
			echo $this->compras->precioMaterial();
		}
		else
		{
			echo "0";
		}
	}
	
	public function inventarios($fecha='fecha',$idCompras=0,$idProveedor=0,$limite=0)
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['nameusuario']	=$this->modelousuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;
		$Data['Jry']			=$this->_jss['jquery'];
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['Jqui']			=$this->_jss['jqueryui'];                  
		#$Data['jFicha_cliente']	=$this->_jss['jFicha_cliente']; 
		#$Data['JFuntPagClien']	=$this->_jss['JFuntPagClien'];
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='comprasInventarios'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PAGINACION------------------------------------#
		$url		=base_url()."compras/inventarios/";
		$registros	=$this->compras->contarComprasInventarios($fecha,$idCompras,$idProveedor);
		$numero		=15;
		$links		=5;
		$uri		=6;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('12',$this->session->userdata('rol'));
		$data['permisoInventario']	= $this->configuracion->obtenerPermisosBoton('17',$this->session->userdata('rol'));
		$data['permisoCrm']			= $this->configuracion->obtenerPermisosBoton('9',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
		
		$data['compras'] 		= $this->compras->obtenerComprasInventarios($numero,$limite,$fecha,$idCompras,$idProveedor);
		$data['proveedores'] 	= $this->proveedores->obtenerProveedores();
		$data['unidades'] 		= $this->configuracion->obtenerUnidades();
		$data['orden'] 			= $this->compras->obtenerConsecutivoCompras();
		$data['configuracion'] 	= $this->configuracion->obtenerConfiguraciones(1);
		$data['inicio']  		= $limite;
		$data['fecha']  		= $fecha;
		$data['idCompras']  	= $idCompras;
		$data['idProveedor']  	= $idProveedor;
		$data["breadcumb"]		= '<a href="'.base_url().'compras"> Compras </a> > Compras de mobiliario/equipo';

		$this->load->view("compras/inventarios/inventarios",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}
	
	public function obtenerProductosInventarios($limite=0)
	{
		$url				= base_url()."compras/obtenerProductosInventarios/";
		$registros			= $this->compras->contarInventarios();
		$numero				= 6;
		$links				= 5;
		$uri				= 3;
		
		$paginador	=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		$data['productos'] = $this->compras->obtenerInventarios($numero,$limite);
		
		$this->load->view("compras/inventarios/obtenerProductosInventarios",$data);
	}
	
	public function comprasPDFInventarios($idCompra,$idLicencia)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');

		$data['compra'] 		= $this->compras->obtenerCompra($idCompra);
		$data['productos'] 		= $this->compras->obtenerPDFInventarios($idCompra);
		$data['empresa'] 		= $this->configuracion->obtenerConfiguraciones($idLicencia);
		 
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['compra']->total);
		$this->ccantidadletras->setMoneda("pesos");//

		$data['cantidadLetra']	=$this->ccantidadletras->PrimeraMayuscula();
		
		$html	= $this->load->view('compras/pdfCompras',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);

		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,5);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	public function registrarCompraInventario()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			$compra	=$this->compras->registrarCompraInventario();
			$compra[0]	==1?$this->session->set_userdata('notificacion','La compra se ha registrado correctamente'):'';
			
			echo json_encode($compra);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerInventariosComprados()
	{
		if(!empty($_POST))
		{
			$idCompras			= $this->input->post('idCompras');
			$data['compras']	= $this->compras->obtenerInventariosComprados($idCompras);
			$data['compra']		= $this->compras->obtenerCompra($idCompras);
			$data['idCompras']	= $idCompras;

			$this->load->view('compras/inventarios/obtenerInventariosComprados',$data);
		}
	}
	
	public function inventariosRecibidos()
	{
		$idDetalle			= $this->input->post('idDetalle');
		$data['recibidos']	= $this->compras->productosRecibidos($idDetalle);
		$data['producto']	= $this->compras->obtenerInventarioCompra($idDetalle);
		$data['compra']		= $this->compras->obtenerCompra($data['producto']->idCompra);
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('12',$this->session->userdata('rol'));
		$data['idDetalle']	= $idDetalle;
		
		$this->load->view('compras/inventarios/inventariosRecibidos',$data);
	}
	
	public function formularioRecibirTodosInventarios()
	{
		if(!empty($_POST))
		{
			$data['compra']		= $this->compras->obtenerCompra($this->input->post('idCompras'));

			$this->load->view('compras/inventarios/formularioRecibirTodosInventarios',$data);
		}
	}
	
	public function recibirTodosInventarios()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->compras->recibirTodosInventarios());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function confirmarRecibirInventario()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			echo json_encode($this->compras->confirmarRecibirInventario());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarInventarioRecibido()
	{
		if(!empty($_POST))
		{
			echo $this->compras->borrarInventarioRecibido();
		}
		else
		{
			echo '0';
		}
	}

	public function comprasPDF($idCompra,$idLicencia)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');

		$data['compra'] 		= $this->compras->obtenerCompra($idCompra);
		$data['productos'] 		= $this->compras->obtenerProductosPDF($idCompra);
		$data['empresa'] 		= $this->configuracion->obtenerConfiguraciones($idLicencia);

		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['compra']->total);
		$this->ccantidadletras->setMoneda("pesos");//
		$CantidadLetras=$this->ccantidadletras->PrimeraMayuscula();
		
		$data['cantidadLetra']	= $CantidadLetras;
		
		$html	= $this->load->view('compras/pdfCompras',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);

		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,5);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	
	public function formularioEnviarCompra()
	{
		$data['compra'] 		= $this->compras->obtenerCompra($this->input->post('idCompras'));
		$data['contactos'] 		= $this->proveedores->obtenerContactos($data['compra']->idProveedor);
		$data['usuario']		= $this->configuracion->obtenerUsuario($this->_iduser);
		$data['usuarios']		= $this->configuracion->obtenerListaUsuarios();
		
		$this->load->view('compras/formularioEnviarCompra',$data);
	}
	
	public function obtenerCompraPDF($idCompra,$idLicencia)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');

		$data['compra'] 		= $this->compras->obtenerCompra($idCompra);
		$data['empresa'] 		= $this->configuracion->obtenerConfiguraciones($idLicencia);
		$data['reporte'] 		= 'compras/pdfCompras';
		
		$data['productos'] 		= $this->compras->obtenerProductosPDF($idCompra);
		
		if($data['compra']->reventa==1)
		{
			$data['productos'] 	=$this->compras->obtenerPDFProductos($idCompra);
		}
		
		if($data['compra']->inventario==1)
		{
			$data['productos'] 		= $this->compras->obtenerPDFInventarios($idCompra);
		}
		
		if($data['compra']->servicios=='1')
		{
			$data['productos'] 		= $this->compras->obtenerServiciosComprados($idCompra);
		}

		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['compra']->total);
		$this->ccantidadletras->setMoneda("pesos");//

		$data['cantidadLetra']	= $this->ccantidadletras->PrimeraMayuscula();

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,5);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$documento='media/cotizaciones/'.$data['compra']->nombre.'.pdf';
		
		$this->mpdf->Output($documento,'F');
		
		return $documento;
	}
	
	public function comprasPDFServicios($idCompra,$idLicencia)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');

		$data['compra'] 	= $this->compras->obtenerCompra($idCompra);
		$data['productos'] 	= $this->compras->obtenerServiciosComprados($idCompra);
		$data['empresa'] 	= $this->configuracion->obtenerConfiguraciones($idLicencia);
		 
		#print($data['compra']->total);
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['compra']->total);
		$this->ccantidadletras->setMoneda("pesos");//
		$data['cantidadLetra']	= $this->ccantidadletras->PrimeraMayuscula();
		
		$html	= $this->load->view('compras/pdfCompras',$data,true);
		$pie	= $this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,5);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}

	
	public function enviarCompra()
	{
		if(!empty($_POST))
		{
			$configuracion		= $this->configuracion->obtenerConfiguracion();
			$idCompra			= $this->input->post('txtIdCompra');
			$compra 			= $this->compras->obtenerCompra($idCompra);
			$mensaje			= $this->input->post('txtMensaje');
			$destinatario		= $this->input->post('txtCorreo');
			$numeroContactos	= $this->input->post('txtNumeroContactos');
			$firma				= $this->input->post('txtFirma');
			$usuario			= $this->configuracion->obtenerUsuario( $this->input->post('selectUsuariosEnviar'));
			
			$email				= $configuracion['correo'];
			$nombre				= $configuracion['nombre'];
			
			if($usuario!=null)
			{
				if(strlen($usuario->correo)>0)
				{
					$email	= $usuario->correo;
					$nombre	= $usuario->nombre.' '.$usuario->apellidoPaterno.' '.$usuario->apellidoMaterno;
				}
			}
			
			for($i=0;$i<=$numeroContactos;$i++)
			{
				if($this->input->post('chkContacto'.$i)=='1')
				{
					$destinatario.=", ".$this->input->post('txtEmailContacto'.$i);
				}
			}
			
			$this->load->library('email');
			$this->email->from($email,$nombre);
			$this->email->to($destinatario);

			$imagen	="";
			
			if(file_exists('img/logos/'.$this->session->userdata('logotipo')))
			{
				$imagen='<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" width="200" /><br /><br />';
			}

			$this->email->attach($this->obtenerCompraPDF($idCompra,1));
			
			$cuerpo=$imagen.$mensaje.'<br />'.nl2br($firma).' <br /> <br /> <strong>Por favor consulte los adjuntos</strong> ';

			$this->email->subject($this->input->post('txtAsunto'));
			$this->email->message($cuerpo);
			
			if (!$this->email->send())
			{
				echo "0";
			}
			else
			{
				$this->configuracion->registrarBitacora('Enviar compra','Compras',$compra->nombre.', Email: '.$destinatario); //Registrar bitácora
				
				echo "1";
			}
				
		}
		else
		{
			echo "2";
		}
	}
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//COMPROBANTES DE COMPRAS
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	
	public function borrarComprobanteCompra()
	{
		if(!empty($_POST))
		{
			echo $this->compras->borrarComprobanteCompra($this->input->post('idComprobante'));
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerComprobantesCompras()
	{
		$idCompra			= $this->input->post('idCompra');
		$idRecibido			= $this->input->post('idRecibido');
		
		$data['ficheros']	= $this->compras->obtenerComprobantesCompras($idCompra,$idRecibido);
		$data['pagos']		= $this->administracion->obtenerComprobantesEgresosCompra($idCompra); //COMPROBANTES DE LOS PAGOS
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('10',$this->session->userdata('rol'));
		$data['idCompra']	= $idCompra;
		$data['idRecibido']	= $idRecibido;
		$data['cuota']		= $this->cuota;
		
		$this->load->view('compras/obtenerComprobantesCompras',$data);
	}
	
	
	
	public function descargarFicheroCompra($idComprobante) #Descargar el archivo XML
	{
		$this->load->helper('download');

		$comprobante	= $this->compras->obtenerComprobanteCompra($idComprobante);

		$fichero 		= $comprobante->idComprobante.'_'.$comprobante->nombre;
		$descarga 		= $comprobante->nombre;
		$data 			= file_get_contents(carpetaCompras.$fichero); 
		
		force_download($descarga, $data); 
	}

	public function subirFicherosCompra($idCompra=0,$idRecibido=0)
	{
		if (!empty($_FILES)) 
		{
			$archivoTemporal	= $_FILES['file']['tmp_name'];

			//Validar tipos de archivos
			$extensiones 		= array('jpg','jpeg','gif','png','tif','bmp','pdf','doc','docx','xls','xlsx','txt','rar','zip','xps','oxps','xml');
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				#$idImagen		= $this->productos->subirImagenProducto($idProducto,$_FILES['file']['name'],$_FILES['file']['size']);
				$idComprobante	= $this->compras->subirFicherosCompra($idCompra,$_FILES['file']['name'],$_FILES['file']['size'],$idRecibido);
				
				if($idComprobante>0)
				{
					move_uploaded_file($archivoTemporal,carpetaCompras.$idComprobante.'_'.$_FILES['file']['name']);

					if(file_exists(carpetaCompras.$idComprobante.'_'.$_FILES['file']['name']))
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
	
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	//COMPRAS DE ANDEN
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function recepcionesAnden()
	{
		if(!empty($_POST))
		{
			$idCompras			= $this->input->post('idCompras');
			$data['compras']	= $this->compras->obtenerProductosComprados($idCompras);
			$data['compra']		= $this->compras->obtenerCompra($idCompras);
			$data['idCompras']	= $idCompras;

			$this->load->view('compras/materiales/anden/recepcionesAnden',$data);
		}
	}
	
	public function recibirMaterialesAnden()
	{
		if(!empty($_POST))
		{
			echo json_encode($this->compras->recibirMaterialesAnden());
		}
		else
		{
			echo json_encode(array("0",'Error'));
		}
	}
	
	public function obtenerProductosAndenCompra($idCompra=0)
	{
		$productos = $this->compras->obtenerProductosAndenCompra($this->input->get('term'),$idCompra);
		
		if($productos!=null)
		{
			foreach ($productos as $row)
			{
				$result[]= $row;
			}
			
			echo json_encode($result);
		}
	}
}
?>
