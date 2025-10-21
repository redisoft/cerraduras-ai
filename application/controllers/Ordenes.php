<?php
class Ordenes extends CI_Controller
{
    private 	$_template;
    protected 	$_fechaActual;
    protected 	$_iduser;
    protected 	$_csstyle;
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
		
		$this->load->model("modelousuario","usuarios");
		$this->load->model("modeloclientes","clientes");
		$this->load->model("produccion_modelo","produccion");
		$this->load->model("ordenes_modelo","ordenes");
		$this->load->model("materiales_modelo","materiales");
		$this->load->model("proveedores_model","proveedores");
		$this->load->model("inventarioproductos_modelo","inventario");
		
		$this->load->model("modelo_configuracion","configuracion");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}
	
	public function index()
	{
		$Data['title']				= "Panel de Administración";
		$Data['cassadmin']			= $this->_csstyle["cassadmin"];
		$Data['csmenu']				= $this->_csstyle["csmenu"];
		$Data['csvalidate']			= $this->_csstyle["csvalidate"];
		$Data['csui']				= $this->_csstyle["csui"];
		$Data['nameusuario']		= $this->usuarios->getUsuarios($this->_iduser);
		$Data['Fecha_actual']		= $this->_fechaActual;
		$Data['Jquical']			= $this->_jss['jquerycal'];
		$Data['Jry']				= $this->_jss['jquery'];
		$Data['JFuntInventario']	= $this->_jss['JFuntInventario'];
		$Data['Jqui']				= $this->_jss['jqueryui'];                  
		$Data['jFicha_cliente']		= $this->_jss['jFicha_cliente']; 
		$Data['permisos']			= $this->configuracion->obtenerRolPermisos($this->session->userdata('rol'));
		$Data['menuActivo']			= 'ordenesProduccion'; 
		$Data['conectados']			= $this->configuracion->obtenerUsuariosConectados(); //USUARIOS CONECTADOS
		
		$this->load->view("cabezera",$Data);
		$this->load->view('header',$Data);
		$this->load->view("principal",$Data);
		
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('20',$this->session->userdata('rol'));
		
		if($data['permiso'][0]->activo=='0')
		{
			$this->load->view('accesos/index');
			$this->load->view("pie",$Data);
			return;
		}
		
		$data["breadcumb"]			= 'Ordenes de producción';

		$this->load->view("ordenes/index",$data);
		$this->load->view("pie",$Data);
	}
	
	public function obtenerOrdenes($limite=0)
	{
		$data['permiso']			= $this->configuracion->obtenerPermisosBoton('20',$this->session->userdata('rol'));
		
		$Pag["base_url"]			= base_url()."ordenes/obtenerOrdenes/";
		$Pag["total_rows"]			= $this->ordenes->numeroProduccion();//Total de Registros
		$Pag["per_page"]			= 30;
		$Pag["num_links"]			= 5;
		$Pag["uri_segment"]			= 5;
		
		$this->pagination->initialize($Pag);
		
		$data['ordenes'] 		= $this->ordenes->obtenerProduccion($Pag["per_page"],$limite);
		$data['inicio']  		= $limite;

		$this->load->view("ordenes/obtenerOrdenes",$data);
	}
 
	 function prebusqueda($fecha)
	 {
		if($fecha=='nada')
		{
			$fecha="";
		}
		 
		$this->session->set_userdata('fechaOrden',$fecha);
		 
		 redirect('ordenes/index','refresh');
	 }
 
	public function borrarMaterial($idMaterial, $idProducto)
	{
		if($this->produccion->borrarProductoMaterial($idMaterial,$idProducto)!=NULL)
		{
			redirect("/produccion/","refresh");
		}
	}
	
    public function borrarOrden($idOrden)
	{
		#----------------------------------PERMISOS------------------------------------#
		$data['permiso']=$this->configuracion->obtenerPermisosBoton('20',$this->session->userdata('rol'));
		
		if($data['permiso'][3]->activo=='0')
		{
			redirect('principal/permisosUsuario','refresh');
			return;
		}
		
		$this->ordenes->borrarOrdenDetalle($idOrden);//!=NULL)
	//{
		$this->ordenes->borrarOrdenes($idOrden);
		#{
		#redirect("/ordenes/","refresh");
		#}
		redirect("/ordenes/","refresh");
	//	}
	}

	public function agregarOrden()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->ordenes->registrarOrden());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function imprimirOrden()
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
			
			$this->load->library('mpdf/mpdf');

			$data['materiales']			= $this->ordenes->obtenerMaterialesProducto($this->input->post('txtIdProducto'));
			$data['producto']			= $this->ordenes->obtenerProducto($this->input->post('txtIdProducto'));
			$data['cantidadOrden']		= $this->input->post('txtCantidadProduccion');
			$data['folio']				= $this->ordenes->obtenerOrdenFolio();
			
			$data['inicio'] 	= $inicio;
			$data['fin'] 		= $fin;
			$data['reporte']	= 'ordenes/pdf/imprimirOrden';
	
			$html	=$this->load->view('reportes/principal',$data,true);
			$pie	=$this->load->view('reportes/pie',$data,true);
			
			$this->mpdf->mPDF('en-x','Legal-L','','',5,5,20,10,2,0);
			$this->mpdf->SetHTMLFooter($pie);
			$this->mpdf->SetHTMLFooter($pie,'E');
			$this->mpdf->mirrorMargins = 1;
			$this->mpdf->WriteHTML($html);
			$this->mpdf->Output(carpetaFicheros.'Ordenes.pdf','F');
			
			echo json_encode(array("1",'Ordenes'));
			
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}

	public function ordenProducido()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->ordenes->producidoOrden());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}

	public function comprobarMaterialesOrden()
	{
		$data['materiales']			= $this->ordenes->obtenerMaterialesProducto($this->input->post('idProducto'));
		$data['cantidadOrden']		= $this->input->post('cantidad');

		$this->load->view('ordenes/materiales/comprobarMaterialesOrden',$data);
	}
	
	public function buscarDetalles()
	{
		$idOrden				= $this->input->post('idOrden');
		$idRelacion				= $this->input->post('idRelacion');
		
		$data['detalles']		= $this->ordenes->obtenerDetallesProducido($idOrden);
		$data['orden']			= $this->ordenes->obtenerOrden($idOrden);
		$data['totalProducido']	= $this->ordenes->obtenerTotalProducido($idOrden);
		$data['producto']		= $this->ordenes->obtenerProducto($data['orden']->idProducto);
		$data['permiso']		= $this->configuracion->obtenerPermisosBoton('20',$this->session->userdata('rol'));
		$data['idOrden']		= $idOrden;
		$data['idRelacion']		= $idRelacion;
		
		$this->load->view('ordenes/procesos/buscarDetalles',$data);
	}
	
	public function procesosProducido()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->ordenes->procesosProducido());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function obtenerDetallesProceso()
	{
		$idOrden					= $this->input->post('idOrden');
		$idRelacion					= $this->input->post('idRelacion');
		$prioridad					= $this->input->post('prioridad');
		$procesoAnterior			= $this->input->post('procesoAnterior');
		
		$data['detalles']			= $this->ordenes->obtenerDetallesProceso($idRelacion);
		$data['orden']				= $this->ordenes->obtenerOrden($idOrden);
		$data['totalProceso']		= $this->ordenes->obtenerTotalProceso($idRelacion);
		$data['idOrden']			= $idOrden;
		$data['idRelacion']			= $idRelacion;
		$data['prioridad']			= $prioridad;
		$data['procesoAnterior']	= $procesoAnterior;
		
		$this->load->view('ordenes/procesos/obtenerDetallesProceso',$data);
	}
	
	public function formularioProduccion() //Es el formulario para ordenes de producción independientes de las cotizaciones
	{
		$data['folio']		= $this->ordenes->obtenerOrdenFolio();
		$data['procesos']	= $this->configuracion->obtenerProcesos();
		
		$this->load->view('ordenes/formularioProduccion',$data);
	}
	
	public function obtenerMaterialesProducto()
	{
		$data['materiales']			= $this->ordenes->obtenerMaterialesProducto($this->input->post('idProducto'));
		$data['cantidadOrden']		= $this->input->post('cantidadOrden');
		#$data['compras']			= $this->ordenes->obtenerMaterialesProducto($this->input->post('idProducto'));
		

		$this->load->view('ordenes/materiales/obtenerMaterialesProducto',$data);
	}
	
	public function obtenerDetallesOrden()
	{
		$data['orden']			= $this->ordenes->obtenerOrden($this->input->post('idOrden'));

		$this->load->view('ordenes/obtenerDetallesOrden',$data);
	}
	
	 public function cancelarOrden()
	{
		if(!empty ($_POST))
		{
			$orden	= $this->ordenes->cancelarOrden($this->input->post('idOrden'));
			
			$orden==1?$this->session->set_userdata("notificacion",'La orden se ha cancelado correctamente'):'';
			echo $orden;
		}
		else
		{
			echo "0";
		}
	}
	
	//<><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>
	//PROCESOS DE PRODUCCIÓN
	//<><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>
	
	public function formularioAgregarProceso()
	{
		$data['idOrden']	= $this->input->post('idOrden');
		$data['orden']		= $this->ordenes->obtenerOrden($data['idOrden']);
		$data['procesos']	= $this->configuracion->obtenerProcesos($data['idOrden']);
		
		$this->load->view("ordenes/procesos/formularioAgregarProceso",$data);
	}
	
	public function agregarProcesoOrden()
	{
		if(!empty ($_POST))
		{
			if(!$this->cuota)
			{
				echo json_encode(array('0',mensajeCuota));
				return;
			}
			
			echo json_encode($this->ordenes->agregarProcesoOrden());
		}
		else
		{
			echo json_encode(array("0",errorRegistro));
		}
	}
	
	public function borrarProcesoOrden()
	{
		if(!empty ($_POST))
		{
			echo $this->ordenes->borrarProcesoOrden($this->input->post('idRelacion'));
		}
		else
		{
			echo "0";
		}
	}
	
	//EDITAR PRODUCTO TERMINADO
	public function obtenerProductoTerminado()
	{
		$data['idDetalle']			= $this->input->post('idDetalle');
		$data['orden']				= $this->ordenes->obtenerDetalleProductoProducido($data['idDetalle']);

		$this->load->view("ordenes/procesos/obtenerProductoTerminado",$data);
	}
	
	public function editarProductoTerminado()
	{
		if(!empty ($_POST))
		{
			echo $this->ordenes->editarProductoTerminado();
		}
		else
		{
			echo "0";
		}
	}
	
	//BORRAR PRODUCTO TERMINADO
	public function borrarProductoTerminado()
	{
		if(!empty ($_POST))
		{
			echo $this->ordenes->borrarProductoTerminado($this->input->post('idDetalle'));
		}
		else
		{
			echo "0";
		}
	}
}