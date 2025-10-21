<?php
class Produccion extends CI_Controller
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
		
		 $this->config->load('js',TRUE);
		 $this->config->load('style', TRUE);
		 
        $this->_fechaActual 	= mdate("%Y-%m-%d %H:%i:%s",now());
        $this->_iduser 			= $this->session->userdata('id');
        $this->_csstyle 		= $this->config->item('style');
  	    $this->_jss				= $this->config->item('js');
		
	 	$this->load->model("bancos_model","bancos");
		$this->load->model("modelousuario","usuario");
   	    $this->load->model("modeloclientes","clientes");
		$this->load->model("catalogos_modelo","catalogos");
		$this->load->model("inventario_model","inventario");
		$this->load->model("produccion_modelo","produccion");
		$this->load->model("materiales_modelo","materiales");
	    $this->load->model("proveedores_model","proveedores");
		$this->load->model('facturacion_modelo','facturacion');
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("contabilidad_modelo","contabilidad");
		$this->load->model("administracion_modelo","administracion");
		$this->load->model("inventarioproductos_modelo","productos");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
    }

	#========================================================================================================#
	#=============================================AUTOCOMPLETADOS============================================#
	#========================================================================================================#

	public function busquedaProduccion($idProductoProduccion)
	{
		if($idProductoProduccion=='nada')
		{
			$idProductoProduccion="";
		}
		
	 	$this->session->set_userdata('idProductoProduccion',$idProductoProduccion);
		
		redirect('produccion/index','refresh');
	}
	
	public function index($idProducto=0,$limite=0)
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0);
		
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		= $this->_csstyle["cassadmin"];
		$Data['csmenu']			= $this->_csstyle["csmenu"];
		$Data['csvalidate']		= $this->_csstyle["csvalidate"];
		$Data['csui']			= $this->_csstyle["csui"];
		$Data['nameusuario']	= $this->usuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	= $this->_fechaActual;
		$Data['Jquical']		= $this->_jss['jquerycal'];
		$Data['Jry']			= $this->_jss['jquery'];
		$Data['JFuntInventario']= $this->_jss['JFuntInventario'];
		$Data['Jqui']			= $this->_jss['jqueryui'];                  
		$Data['jFicha_cliente']	= $this->_jss['jFicha_cliente']; 
		$Data['permisos']		= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		= 'analisis'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		$Pag["base_url"]		= base_url()."produccion/index/0/";
		$Pag["total_rows"]		= $this->produccion->contarProduccion($idProducto);//Total de Registros
		$Pag["per_page"]		= 10;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 4;
		
		$this->pagination->initialize($Pag);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}

		$data['productos'] 				= $this->produccion->obtenerProduccion($Pag["per_page"],$limite,$idProducto);
		#$data['material'] 				= $this->materiales->materiales();
		#$data['colores']				= $this->configuracion->obtenerColores();
		#$data['proveedores']			= $this->inventario->proveedores();
		#$data['unidades'] 				= $this->configuracion->obtenerUnidades();
		#$data['gastosAdministrativos']	= $this->produccion->obtenerGastoUnitario();
		$data['similares']				= $this->produccion->productosSimilares();
		$data['inicio']  				= $limite;
		$data['idProducto'] 			= $idProducto;
		$data["breadcumb"]				= 'Explosión de materiales';
		
		$this->load->view("produccion/index",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}

	public function gastos()
	{
		$Data['title']			= "Panel de Administración";
		$Data['cassadmin']		=$this->_csstyle["cassadmin"];
		$Data['csmenu']			=$this->_csstyle["csmenu"];
		$Data['csvalidate']		=$this->_csstyle["csvalidate"];
		$Data['csui']			=$this->_csstyle["csui"];
		$Data['nameusuario']	=$this->usuario->getUsuarios($this->_iduser);
		$Data['Fecha_actual']	=$this->_fechaActual;
		$Data['Jquical']		=$this->_jss['jquerycal'];
		$Data['Jry']			=$this->_jss['jquery'];
		/*$Data['JFuntInventario']=$this->_jss['JFuntInventario'];
		$Data['JFuntPagClien']	=$this->_jss['JFuntPagClien'];*/
		$Data['Jqui']			=$this->_jss['jqueryui'];                  
		#$Data['jFicha_cliente']	=$this->_jss['jFicha_cliente']; 
		$Data['permisos']		=$this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']		='gastos'; 
		$Data['conectados']		= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);

		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']	= $this->configuracion->obtenerPermisosBoton('13',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			
			return;
		}
				
		#$data['gastos'] 		= $this->produccion->obtenerGastos($mes,$anio);
		$data['cuentas'] 		= $this->configuracion->obtenerCuentasContables();
		$data['bancos']			= $this->bancos->obtenerBancos();
		$data['cuentasBanco']	= $this->bancos->obtenerCuentas();
		$data["breadcumb"]		= 'Contabilidad';
		
		$this->load->view("administracion/gastos",$data); //principal lista de clientes
		$this->load->view("pie",$Data);
	}
	
	function prebusqueda($filtro)
	{
		if($filtro=='nada')
		{
			$filtro="";
		}
		
		$this->session->set_userdata('busquedaAnalisis',$filtro);
		
		redirect('produccion/index','refresh');
	}

	public function borrarMaterial($idMaterial, $idProducto,$inicio)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		if($this->produccion->borrarProductoMaterial($idMaterial,$idProducto)=="1")
		{
			redirect("produccion/index/".$inicio,"refresh");
		}
	}
	
    public function borrarProducto($idProducto)
	{
		if($this->produccion->borrarProducto($idProducto)!=NULL)
		{
			if($this->produccion->rel_producto($idProducto)!=NULL)
			{
				redirect("/produccion/","refresh");
			}
			
			redirect("/produccion/","refresh");
		}
	}

	public function registrarProduccion()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			$producto=$this->produccion->registrarProduccion();
				
			if($producto[0]=="1")
			{
				$this->session->set_userdata('notificacion','El producto se ha registrado correctamente');
			}
			
			echo json_encode($producto);
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}

	#-------------------------------------------------------------------------------------------------#
	#									AGREGAR MATERIAL A PRODUCTO									  #
	#-------------------------------------------------------------------------------------------------#
	
	public function agregarMaterialProducto()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->produccion->agregarMaterialProducto());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarMaterialProduccion() //Aqui se edita el material de productos
	{
		if (!empty($_POST))
		{
			echo $this->produccion->editarMaterialProduccion();
		}
		else
		{
			echo "0";
		}
	}
	
	public function obtenerMaterialEditar($idProducto,$idMaterial) //Muestra la informacion de materiales
	{
		$data['material']	= $this->produccion->materialEditar($idProducto,$idMaterial);
		$data['idProducto']	= $idProducto;
		
		$this->load->view('produccion/materiales/obtenerMaterialEditar',$data);
	}
	
	public function editarProducto() //Aqui se edita el material de productos
	{
		if (!empty($_POST))
		{
			$producto	= $this->produccion->editarProducto();
			
			if($producto=="1")
			{
				$this->session->set_userdata('notificacion','El producto se ha editado correctamente');
			}
			
			redirect('produccion','refresh');  
		}
	}
	
	public function obtenerProducto() //Muestra la informacion de materiales
	{
		$data['producto']		= $this->produccion->obtenerProducto($this->input->post('idProducto')); 
		$data['lineas']			= $this->configuracion->obtenerLineas(); 
		$data['subLineas']		= $this->configuracion->obtenerSubLineas($data['producto']->idLinea); 
		$data['idProducto']		= $this->input->post('idProducto');
		$data['configuracion'] 	= $this->configuracion->obtenerConfiguraciones(1);
		$data['departamentos'] 	= $this->catalogos->obtenerDepartamentos();
		$data['marcas'] 		= $this->catalogos->obtenerMarcas();
		$data['unidades'] 		= $this->configuracion->seleccionarUnidades();
		$data['impuestos'] 		= $this->configuracion->obtenerImpuestos();
		
		$this->load->view('produccion/obtenerProducto',$data);
	}
	
	public function formularioLineas()
	{
		$this->load->view('produccion/formularioLineas');
	}
	
	public function obtenerLineas()
	{
		$lineas 				= $this->configuracion->obtenerLineas();
		
		echo'
		<select class="cajas" id="selectLineas" name="selectLineas" style="width:280px" '.(sistemaActivo!='pinata'?' onchange="obtenerSubLineasCatalogo()" ':'').'>';
			
			if($lineas==null)
			{
				echo '<option value="0">Seleccione</option>';
			}
			
			foreach($lineas as $row)
			{
				echo'<option value="'.$row->idLinea.'">'.$row->nombre.'</option>';	
				#echo'<option '.($row->idLinea=='124'?'selected="selected"':'').' value="'.$row->idLinea.'">'.$row->nombre.'</option>';	
			}
			
		echo'</select>';
		
		echo '
		<script>
			obtenerSubLineasCatalogo()
		</script>';
	}
	
	public function agregarLinea()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->configuracion->agregarLinea());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function formularioProduccion()
	{
		$data['unidades'] 			= $this->configuracion->obtenerUnidades();
		$data['configuracion'] 		= $this->configuracion->obtenerConfiguraciones(1);
		$data['departamentos'] 		= $this->catalogos->obtenerDepartamentos();
		$data['marcas'] 			= $this->catalogos->obtenerMarcas();
		$data['unidades'] 			= $this->configuracion->seleccionarUnidades();
		$data['impuestos'] 			= $this->configuracion->obtenerImpuestos();

		$this->load->view('produccion/formularioProduccion',$data);
	}

	public function agregarEstandar()
	{
		if(!empty($_POST))
		{
			$agregar=$this->materiales->addProducto();
		}
	}

	public function cambiarCostoGlobal()
	{
		$global=$this->produccion->editarGastoGlobal();
		
		if($global=='1')
		{
			$this->session->set_userdata('notificacion','Se ha definir correctamente el gasto administrativo');
		}
		else
		{
			$this->session->set_userdata('errorNotificacion','Error al definir el gasto administrativo','error');
		}
		
		redirect('produccion','refresh');
	}
	
	public function actualizarCosto()
	{
		$costoStandar=$this->materiales->costoEstandar();
		
		print("$ "+ number_format($costoStandar->total,2));
	}
	
	public function borrarEstandar($idMaterial)
	{
		$costoStandar=$this->produccion->borrarEstandar($idMaterial);
		
		redirect('produccion','refresh');
	}
	
	public function registrarSimilares()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->produccion->registrarSimilares());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function agregarCategoria()
	{
		if(!empty($_POST))
		{
			$categoria=$this->produccion->agregarCategoria();
			print($categoria);
		}
	}
	
	public function borrarCategoria($idCategoria)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$categoria=$this->produccion->borrarCategoria($idCategoria);
		
		redirect('produccion/index/0','refresh');
	}
	
	public function borrarProductoCategoria($idCategoria,$pagina)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('19',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$producto=$this->produccion->borrarProductoCategoria($idCategoria);
		
		if($producto=="0")
		{
			$this->session->set_userdata('errorNotificacion','El producto esta asociado a ventas u ordenes de producción, no puede borrarse');
		}
		else
		{
			$this->session->set_userdata('notificacion','El producto se ha borrado correctamente');
		}
		
		
		redirect('produccion/index/'.$pagina,'refresh');
	}

	public function coloripy()
	{
		$this->produccion->coloripy();
		
		redirect('produccion','refresh');
	}
	
	
	//TODO ESTO ES DE ADMINISTRACIÓN
	//OTROS INGRESOS
	//-------------------------------------------------------------------------------------------------------------------
	
	public function subirFicheros($idIngreso=0)
	{
		if (!empty($_FILES)) 
		{
			$archivoTemporal	= $_FILES['file']['tmp_name'];
			$xml				= $this->input->post('xml');

			//Validar tipos de archivos
			if($xml=='0')
			{
				$extensiones 		= array('jpg','jpeg','gif','png','tif','bmp','pdf','doc','docx','xls','xlsx','txt','rar','zip','xps','oxps','xml');
			}
			else
			{
				$extensiones 		= array('xml','pdf');
			}
			
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				$idComprobante	= $this->administracion->subirFicheros($idIngreso,$_FILES['file']['name'],$_FILES['file']['size'],$xml);
				
				if($idComprobante>0)
				{
					move_uploaded_file($archivoTemporal,carpetaIngresos.$idComprobante.'_'.$_FILES['file']['name']);

					if(file_exists(carpetaIngresos.$idComprobante.'_'.$_FILES['file']['name']))
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
				echo 'No se permiten estos archivos'.($xml=='1'?', debe ser un xml o un pdf':'');
			}
		}
	} 

	public function descargarFichero($idComprobante) #Descargar el archivo XML
	{
		$this->load->helper('download');

		$comprobante	=$this->administracion->obtenerComprobante($idComprobante);

		$fichero 	= $comprobante->idComprobante.'_'.$comprobante->nombre;
		$descarga 	= $comprobante->nombre;
		$data 		= file_get_contents("media/ficheros/comprobantes/$fichero"); 
		
		force_download($descarga, $data); 
	}
	
	public function obtenerOtrosIngresos($limite=0)
	{
		$idProducto					= $this->input->post('idProducto');
		$idDepartamento				= $this->input->post('idDepartamento');
		$idGasto					= $this->input->post('idGasto');
		
		$url						= base_url()."produccion/obtenerOtrosIngresos/";
		$registros					= $this->administracion->contarOtrosIngresos($idProducto,$idDepartamento,$idGasto);
		$numero						= 10;
		$links						= 5;
		$uri						= 3;
		
		$paginador					= $this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		$data['ingresos']			= $this->administracion->obtenerOtrosIngresos($numero,$limite,$idProducto,$idDepartamento,$idGasto);
		$data['totales']			= $this->administracion->sumarOtrosIngresos($idProducto,$idDepartamento,$idGasto);
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('13',$this->session->userdata('rol'));
		
		$data['departamentos']		= $this->administracion->obtenerDepartamentos(1);
		$data['productos']			= $this->administracion->obtenerProductos(1);
		$data['gastos']				= $this->administracion->obtenerTipoGasto(1);
		$data['idProducto']			= $idProducto;
		$data['idDepartamento']		= $idDepartamento;
		$data['idGasto']			= $idGasto;
		$data['inicio']				= $limite+1;
		$data['registros']			= $registros;

		$this->load->view('administracion/obtenerOtrosIngresos',$data);
	}
	
	public function formularioOtrosIngresos()
	{
		$data['bancos']			= $this->bancos->obtenerBancos(1);
		$data['periodos']		= $this->configuracion->obtenerPeriodosProduccion();
		$data['formas']			= $this->configuracion->seleccionarFormas();
		$data['ivas']			= $this->configuracion->obtenerIvas();
		$data['impuestos']		= $this->configuracion->obtenerImpuestos();
		
		$data['variables1']			= $this->catalogos->obtenerVariablesTipo(1);
		$data['variables2']			= $this->catalogos->obtenerVariablesTipo(2);
		$data['variables3']			= $this->catalogos->obtenerVariablesTipo(3);
		$data['variables4']			= $this->catalogos->obtenerVariablesTipo(4);
		
		$data['cliente']		= $this->clientes->obtenerCliente($this->input->post('idCliente'));
		
		if(sistemaActivo=='IEXE')
		{
			$data['periodos']			= $this->configuracion->obtenerPeriodos();
		}
		
		$this->load->view('administracion/formularioOtrosIngresos',$data);
	}
	
	public function formularioCobrosPreinscritos()
	{
		$data['bancos']			= $this->bancos->obtenerBancos(1);
		$data['periodos']		= $this->configuracion->obtenerPeriodosProduccion();
		$data['formas']			= $this->configuracion->seleccionarFormas();
		$data['ivas']			= $this->configuracion->obtenerIvas();
		$data['impuestos']		= $this->configuracion->obtenerImpuestos();
		
		$data['variables1']			= $this->catalogos->obtenerVariablesTipo(1);
		$data['variables2']			= $this->catalogos->obtenerVariablesTipo(2);
		$data['variables3']			= $this->catalogos->obtenerVariablesTipo(3);
		$data['variables4']			= $this->catalogos->obtenerVariablesTipo(4);
		
		$data['cliente']		= $this->clientes->obtenerCliente($this->input->post('idCliente'));
		
		$this->load->view('clientes/preinscritos/formularioCobrosPreinscritos',$data);
	}
	
	#EDITAR OTROS INGRESOS
	public function obtenerIngresoEditar()
	{
		$idIngreso					= $this->input->post('idIngreso');
		
		$data['bancos']				= $this->bancos->obtenerBancos(1);
		$data['ingreso']			= $this->administracion->obtenerIngresoEditar($idIngreso);
		
		$data['cuenta']				= $this->bancos->obtenerCuenta($data['ingreso']->idCuenta);
		$data['banco']				= $data['cuenta']!=null?$data['cuenta']->idBanco:0;
		$data['cuentas']			= $this->bancos->obtenerCuentasBanco($data['banco']);
		
		$data['departamentos']		= $this->administracion->obtenerDepartamentos(1);
		$data['nombres']			= $this->administracion->obtenerNombres();
		$data['productos']			= $this->administracion->obtenerProductos(1);
		$data['gastos']				= $this->administracion->obtenerTipoGasto(1);
		$data['cliente']			= $this->clientes->obtenerCliente($data['ingreso']->idCliente);
		$data['formas']				= $this->configuracion->seleccionarFormas();
		$data['idIngreso']			= $idIngreso;
		$data['ivas']				= $this->configuracion->obtenerIvas();
		$data['cuentasContables']	= $this->administracion->obtenerCuentasContablesIngreso($idIngreso);
		$data['impuestos']			= $this->configuracion->obtenerImpuestos();
		
		$data['variables1']			= $this->catalogos->obtenerVariablesTipo(1);
		$data['variables2']			= $this->catalogos->obtenerVariablesTipo(2);
		$data['variables3']			= $this->catalogos->obtenerVariablesTipo(3);
		$data['variables4']			= $this->catalogos->obtenerVariablesTipo(4);
		
		if(sistemaActivo=='IEXE')
		{
			$data['periodos']			= $this->configuracion->obtenerPeriodos();
		}
		
		$this->load->view('administracion/obtenerIngresoEditar',$data);
	}
	
	public function editarIngreso()
	{
		if(!empty($_POST))
		{
			$ingresos=$this->administracion->editarIngreso();
			echo $ingresos;
		}
	}
	
	public function borrarIngreso()
	{
		if(!empty($_POST))
		{
			$idIngreso=$this->input->post('idIngreso');
			$ingresos=$this->administracion->borrarIngreso($idIngreso);
			echo $ingresos;
		}
	}
	
	//FORMULARIO PARA FACTURAR INGRESO
	public function formularioFacturaIngreso()
	{
		$data['ingreso']			= $this->administracion->obtenerIngresoEditar($this->input->post('idIngreso'));
		$data['emisores']			= $this->facturacion->obtenerEmisores();
		$data['divisas']			= $this->configuracion->obtenerDivisas();
		
		$data['metodos']			= $this->configuracion->obtenerMetodosPago();
		$data['formas']				= $this->configuracion->obtenerFormasPago();
		$data['usos']				= $this->configuracion->obtenerUsosCfdi();
		
		if(sistemaActivo=='IEXE')
		{
			$data['programa']		= $this->clientes->obtenerProgramaCliente($data['ingreso']->idCliente);
		}

		$this->load->view('administracion/ingresos/cfdi/formularioFacturaIngreso',$data);
	}
	
	//DEPARTAMENTOS
	//-------------------------------------------------------------------------------------------------------------------------------------
	public function formularioDepartamentos()
	{
		$this->load->view('administracion/formularioDepartamentos');
	}
	
	public function obtenerDepartamentos()
	{
		$data['departamentos']	=$this->administracion->obtenerDepartamentos($this->input->post('tipo'));
		
		$this->load->view('administracion/obtenerDepartamentos',$data);
	}
	
	public function registrarDepartamento()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->administracion->registrarDepartamento());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	//NOMBRES
	//-------------------------------------------------------------------------------------------------------------------------------------
	public function formularioNombres()
	{
		$this->load->view('administracion/formularioNombres');
	}
	
	public function obtenerNombres()
	{
		$data['nombres']	=$this->administracion->obtenerNombres();
		
		$this->load->view('administracion/obtenerNombres',$data);
	}
	
	public function registrarNombre()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->administracion->registrarNombre());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	//CATALOGOS PRODUCTOS
	//-------------------------------------------------------------------------------------------------------------------------------------
	public function formularioProductos()
	{
		$this->load->view('administracion/formularioProductos');
	}
	
	public function obtenerProductos()
	{
		$data['productos']	=$this->administracion->obtenerProductos($this->input->post('tipo'));
		
		$this->load->view('administracion/obtenerProductos',$data);
	}
	
	public function registrarProducto()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->administracion->registrarProducto());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	//CATALOGOS TIPO DE GASTO
	//-------------------------------------------------------------------------------------------------------------------------------------
	public function formularioTipoGasto()
	{
		$this->load->view('administracion/formularioTipoGasto');
	}
	
	public function obtenerTipoGasto()
	{
		$data['gastos']	= $this->administracion->obtenerTipoGasto($this->input->post('tipo'));
		
		$this->load->view('administracion/obtenerTipoGasto',$data);
	}
	
	public function registrarTipoGasto()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->administracion->registrarTipoGasto());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerCuentas($idBanco)
	{
		$cuentas	=$this->bancos->obtenerCuentasBanco($idBanco);
		
		echo'<select id="selectCuentas" class="cajasSelect" style="width:250px"> 
			<option value="0"> Seleccione</option>';
		
		if($cuentas!=null)
		{
			foreach($cuentas as $cuenta)
			{
				echo'<option value="'.$cuenta->idCuenta.'">'.$cuenta->cuenta.'</option>';
			}
		}
		echo'</select>';
	}
	
	//REGISTRAR INGRESO
	//-------------------------------------------------------------------------------------------------------------------------------------
	public function registrarIngreso()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->administracion->registrarIngreso());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	//OTROS EGRESOS
	//-------------------------------------------------------------------------------------------------------------------
	
	public function subirFicherosEgresos($idEgreso=0)
	{
		if (!empty($_FILES)) 
		{
			$archivoTemporal	= $_FILES['file']['tmp_name'];
			$xml				= $this->input->post('xml');

			//Validar tipos de archivos
			if($xml=='0')
			{
				$extensiones 		= array('jpg','jpeg','gif','png','tif','bmp','pdf','doc','docx','xls','xlsx','txt','rar','zip','xps','oxps','xml');
			}
			else
			{
				$extensiones 		= array('xml','pdf');
			}
			
			$archivo 			= pathinfo($_FILES['file']['name']);

			if (in_array($archivo['extension'],$extensiones)) 
			{
				$idComprobante	= $this->administracion->subirFicherosEgreso($idEgreso,$_FILES['file']['name'],$_FILES['file']['size'],$xml);
				
				if($idComprobante>0)
				{
					move_uploaded_file($archivoTemporal,carpetaEgresos.$idComprobante.'_'.$_FILES['file']['name']);

					if(file_exists(carpetaEgresos.$idComprobante.'_'.$_FILES['file']['name']))
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
				echo 'No se permiten estos archivos'.($xml=='1'?', debe ser un xml o un pdf':'');
			}
		}
	} 

	public function descargarFicheroEgreso($idComprobante) #Descargar el archivo XML
	{
		$this->load->helper('download');

		$comprobante	=$this->administracion->obtenerComprobanteEgreso($idComprobante);

		$fichero 		= $comprobante->idComprobante.'_'.$comprobante->nombre;
		$descarga 		= $comprobante->nombre;
		$data 			= file_get_contents("media/ficheros/comprobantesEgresos/$fichero"); 
		
		force_download($descarga, $data); 
	}
	
	public function obtenerOtrosEgresos($limite=0)
	{
		$idPersonal					= $this->input->post('idPersonal');
		
		$idNivel1					= $this->input->post('idNivel1');
		$idNivel2					= $this->input->post('idNivel2');
		$idNivel3					= $this->input->post('idNivel3');
		
		$url						= base_url()."produccion/obtenerOtrosEgresos/";
		$registros					= $this->administracion->contarOtrosEgresos($idNivel1,$idNivel2,$idNivel3,$idPersonal);
		$numero						= 10;
		$links						= 5;
		$uri						= 3;
		
		$paginador	=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		$data['egresos']			= $this->administracion->obtenerOtrosEgresos($numero,$limite,$idNivel1,$idNivel2,$idNivel3,$idPersonal);
		$data['totales']			= $this->administracion->sumarOtrosEgresos($idNivel1,$idNivel2,$idNivel3,$idPersonal);
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('13',$this->session->userdata('rol'));
		
		$data['departamentos']		= $this->administracion->obtenerDepartamentos(2);
		$data['productos']			= $this->administracion->obtenerProductos(2);
		$data['gastos']				= $this->administracion->obtenerTipoGasto(2);
		$data['personal']			= $this->administracion->obtenerPersonalRegistro();
		
		$data['niveles1']				= $this->catalogos->obtenerNiveles1();
		$data['niveles2']				= $this->catalogos->obtenerNiveles2Catalogo($idNivel1);
		$data['niveles3']				= $this->catalogos->obtenerNiveles3Catalogo($idNivel2);
	
		$data['idNivel1']			= $idNivel1;
		$data['idNivel2']			= $idNivel2;
		$data['idNivel3']			= $idNivel3;
		$data['idPersonal']			= $idPersonal;
		$data['inicio']				= $limite+1;
		
		$this->load->view('administracion/obtenerOtrosEgresos',$data);
	}
	
	
	
	
	
	//REGISTRAR EGRESO
	//-------------------------------------------------------------------------------------------------------------------------------------
	public function registrarEgreso()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}

			echo json_encode($this->administracion->registrarEgreso());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarEgreso()
	{
		if(!empty($_POST))
		{
			$ingresos	=$this->administracion->editarEgreso();
			
			$global		=$this->produccion->editarGastoGlobal();
			
			echo $ingresos;
		}
	}
	
	public function borrarEgreso()
	{
		if(!empty($_POST))
		{
			$idEgreso	=$this->input->post('idEgreso');
			$egresos	=$this->administracion->borrarEgreso($idEgreso);
			
			#$global		=$this->produccion->editarGastoGlobal();
			
			echo $egresos;
		}
	}
	
	//TRASPASOS
	//-------------------------------------------------------------------------------------------------------------------------------------
	public function formularioTraspasos()
	{
		$data['cuentas']		=$this->bancos->obtenerCuentas();
		
		$this->load->view('administracion/formularioTraspasos',$data);
	}
	
	public function obtenerSaldoOrigen()
	{
		$idCuenta		=$this->input->post('idCuenta');
		$ingresos		=$this->bancos->obtenerIngresosCuenta($idCuenta);
		$egresos		=$this->bancos->obtenerEgresosCuenta($idCuenta);
		$saldo			=$ingresos-$egresos;
		
		echo' <input type="hidden" id="txtSaldoOrigen" value="'.$saldo.'" />
		<label> Saldo: '.number_format($saldo,2).'</label>';
	}
	
	public function obtenerSaldoDestino()
	{
		$idCuenta		=$this->input->post('idCuenta');
		$ingresos		=$this->bancos->obtenerIngresosCuenta($idCuenta);
		$egresos		=$this->bancos->obtenerEgresosCuenta($idCuenta);
		$saldo			=$ingresos-$egresos;
		
		echo' <input type="hidden" id="txtSaldoDestino" value="'.$saldo.'" />
		<label> Saldo: '.number_format($saldo,2).'</label>';
	}
	
	public function obtenerCuentasDestino()
	{
		$idCuenta		=$this->input->post('idCuenta');
		$cuentas		=$this->bancos->obtenerCuentas($idCuenta);
		
		echo' <select id="selectCuentaDestino" name="selectCuentaDestino" class="cajas" style="width:250px;" onchange="obtenerSaldoDestino()" >
				<option value="0">Seleccione</option>';

			   foreach($cuentas as $row)
			   {
				   print('<option value="'.$row->idCuenta.'" >'.(strlen($row->cuenta)>0?$row->cuenta:$row->tarjetaCredito).', '.$row->nombre.'</option>');
			   }
			 
		echo'</select>
		<div style="padding-left:6px" id="saldoCuentaDestino"></div>';
	}
	
	public function obtenerTraspasos($limite=0)
	{
		$url		=base_url()."produccion/obtenerTraspasos/";
		$registros	=$this->administracion->contarTraspasos();
		$numero		=10;
		$links		=5;
		$uri		=3;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);
		
		$data['traspasos']		= $this->administracion->obtenerTraspasos($numero,$limite);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('13',$this->session->userdata('rol'));
		
		$this->load->view('administracion/obtenerTraspasos',$data);
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
			
			echo json_encode($this->administracion->registrarTraspaso());
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
			echo $this->administracion->borrarTraspaso();
		}
	}

	public function obtenerCajaChica($limite=0)
	{
		/*$url=base_url()."produccion/obtenerCajaChica/";
		$registros=$this->administracion->contarCajaChica();
		$numero=10;
		$links=5;
		$uri=3;
		
		$paginador=$this->paginas->paginar($url,$registros,$numero,$links,$uri);
		$this->pagination->initialize($paginador);*/
		
		$idEgreso			=$this->input->post('idEgreso');
		$data['egresos']	=$this->administracion->obtenerCajaChica($idEgreso);
		$data['idEgreso']	=$idEgreso;
		
		$this->load->view('administracion/obtenerCajaChica',$data);
	}
	
	public function formularioCajaChica()
	{
		$idEgreso=$this->input->post('idEgreso');
		
		$data['monto']		=$this->bancos->obtenerMontoEgreso($idEgreso);
		$data['caja']		=$this->bancos->obtenerSumaCaja($idEgreso);
		$data['idEgreso']	=$idEgreso;
		
		$this->load->view('administracion/formularioCajaChica',$data);
	}
	
	public function obtenerCajaChicaEditar()
	{
		$idEgreso	=$this->input->post('idEgreso');
		$idCaja		=$this->input->post('idCaja');
		
		$data['cajaChica']	=$this->administracion->obtenerRegistroCajaChica($idCaja);
		$data['monto']		=$this->bancos->obtenerMontoEgreso($idEgreso);
		$data['caja']		=$this->bancos->obtenerSumaCaja($idEgreso);
		$data['idCaja']		=$idCaja;
		$data['idEgreso']	=$idEgreso;

		$this->load->view('administracion/obtenerCajaChicaEditar',$data);
	}

	//REGISTRAR EGRESO
	//-------------------------------------------------------------------------------------------------------------------------------------
	public function registrarCajaChica()
	{
		if(!empty($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->administracion->registrarCajaChica());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function editarCajaChica()
	{
		if(!empty($_POST))
		{
			$cajaChica=$this->administracion->editarCajaChica();
			
			#$global=$this->produccion->editarGastoGlobal();
			
			echo $cajaChica;
		}
	}
	
	public function borrarCajaChica()
	{
		if(!empty($_POST))
		{
			$idCaja=$this->input->post('idCaja');
			$cajaChica=$this->administracion->borrarCajaChica($idCaja);
			
			#$global=$this->produccion->editarGastoGlobal();
			
			echo $cajaChica;
		}
	}
	
	//FORMULARIO PARA AGREGAR LOS MATERIALES AL PRODUCTO
	public function formularioAgregarMateriales() 
	{
		$data['producto']		=$this->produccion->obtenerProducto($this->input->post('idProducto')); 
		
		$this->load->view('produccion/materiales/formularioAgregarMateriales',$data);
	}
}
?>
