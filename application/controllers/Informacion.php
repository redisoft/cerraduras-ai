<?php
class Informacion extends CI_Controller
{
	protected $_fechaActual;
	protected $_iduser;
	protected $_csstyle;
    protected $_tables;
    protected $_role;

	function __construct()
	{
		parent::__construct();

		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
        $this->load->model("modelousuario","modelousuario");
        $this->load->model("modeloclientes","modeloclientes");
		$this->load->model("modelo_configuracion","configuracion");
        $this->load->model("ventas_model","ventas");
		$this->load->model("facturacion_modelo","facturacion");
		$this->load->model("compras_modelo","compras");
		$this->load->model("administracion_modelo","administracion");
		$this->load->model("bancos_model","bancos");
		$this->load->model("proveedores_model","proveedores");
		$this->load->model("materiales_modelo","materiales");
		$this->load->model("inventarioproductos_modelo","inventario");
		$this->load->model("tiendas_modelo","tiendas");
		$this->load->model("informacion_modelo","informacion");
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
	}
	
	public function obtenerVentaInformacion()
	{
		if(!empty($_POST))
		{
			$idCotizacion				=$this->input->post('idCotizacion');
			$data['cotizacion']			=$this->facturacion->obtenerCotizacion($idCotizacion);
			$data['cliente']			=$this->facturacion->obtenerCliente($data['cotizacion']->idCliente);
			$data['productos']			=$this->facturacion->obtenerProductosCotizacion($idCotizacion);

			$this->load->view('informacion/obtenerVentaInformacion',$data);
		}
	}
	
	public function obtenerCompraInformacion()
	{
		if(!empty($_POST))
		{
			$idCompras			=$this->input->post('idCompras');
			$data['compra']		=$this->compras->obtenerCompra($idCompras);
			
			$data['productos']	=$this->compras->obtenerProductosComprados($idCompras);
			
			if($data['compra']->inventario==1)
			{
				$data['productos']	=$this->compras->obtenerInventariosComprados($idCompras);
			}
			
			if($data['compra']->reventa==1)
			{
				$data['productos']	=$this->compras->obtenerReventaComprados($idCompras);
			}
			
			$this->load->view('informacion/obtenerCompraInformacion',$data);
		}
	}
	
	public function obtenerGastoInformacion()
	{
		if(!empty($_POST))
		{
			$idEgreso				=$this->input->post('idEgreso');
			$data['egreso']			=$this->administracion->obtenerEgresoEditar($idEgreso);
			$data['cuenta']			=$this->bancos->obtenerCuenta($data['egreso']->idCuenta);
			$data['banco']			=$data['cuenta']!=null?$this->bancos->obtenerBanco($data['cuenta']->idBanco):null;
			$data['proveedor']		=$this->proveedores->obtenerProveedor($data['egreso']->idProveedor);
			

			$this->load->view('informacion/obtenerGastoInformacion',$data);
		}
	}
	
	public function obtenerCotizacionInformacion()
	{
		if(!empty($_POST))
		{
			$idCotizacion				=$this->input->post('idCotizacion');
			$data['cotizacion']			=$this->facturacion->obtenerCotizacion($idCotizacion);
			$data['cliente']			=$this->facturacion->obtenerCliente($data['cotizacion']->idCliente);
			$data['productos']			=$this->facturacion->obtenerProductosCotizacion($idCotizacion);

			$this->load->view('informacion/obtenerCotizacionInformacion',$data);
		}
	}

	public function obtenerInformacionTienda()
	{
		if(!empty($_POST))
		{
			$data['tienda']			=$this->tiendas->obtenerTienda($this->input->post('idTienda'));

			$this->load->view('informacion/obtenerInformacionTienda',$data);
		}
	}
	
	//MATERIA PRIMA
	public function obtenerInformacionMaterial()
	{
		if(!empty($_POST))
		{
			$idMaterial				= $this->input->post('idMaterial');
			$idProveedor			= $this->input->post('idProveedor');

			$data['material']		= $this->materiales->obtenerMaterialProveedor($idMaterial,$idProveedor);
			$data['compras']		= $this->compras->obtenerInformacionComprasMaterial($idMaterial,$idProveedor);
			$data['salidas']		= $this->materiales->obtenerMermas($idMaterial,$idProveedor);
			$data['permiso']		= $this->configuracion->obtenerPermisosBoton('33',$this->session->userdata('rol'));

			$this->load->view('informacion/obtenerInformacionMaterial',$data);
		}
	}
	
	public function obtenerInformacionCompras()
	{
		if(!empty($_POST))
		{
			$inicio					= $this->input->post('inicio');
			$fin					= $this->input->post('fin');
			$inicio					= strlen($inicio)>3?$inicio:'fecha';
			$fin					= strlen($fin)>3?$fin:'fecha';
			
			$idProducto				= $this->input->post('idProducto');
			$idTienda				= $this->input->post('idTienda');
			$idTienda				= strlen($idTienda)>0?$idTienda:0;
			
			$data['compras']		= $this->compras->obtenerInformacionCompras($idProducto,$idTienda);
			$data['ventas']			= $this->ventas->obtenerInformacionVentas($idProducto,$inicio,$fin,$idTienda);
			$data['producto']		= $this->inventario->obtenerProductoInventario($idProducto,$idTienda);
			$data['envios']			= $this->inventario->obtenerEnviosProductoInventario($idProducto,$idTienda);
			$data['recepciones']	= $this->inventario->obtenerRecepcionesProductoInventario($idProducto,$idTienda);
			$data['permiso']		= $this->configuracion->obtenerPermisosBoton('33',$this->session->userdata('rol'));
			
			$data['movimientos']	= null;#$this->inventario->obtenerInformacionMovimientos($idProducto,$idTienda);
			$data['diario']			= null;#$this->inventario->obtenerInformacionDiario($idProducto);
			$data['idProducto']		= $idProducto;

			$this->load->view('informacion/obtenerInformacionCompras',$data);
		}
	}

	public function obtenerCompras($limite=0)
	{
		$idProducto				= $this->input->post('idProducto');

		$Pag["base_url"]		= base_url()."informacion/obtenerEntradasCompras/";
		$Pag["total_rows"]		= $this->informacion->contarCompras($idProducto);
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;

		$this->pagination->initialize($Pag);

		$data['compras'] 		= $this->informacion->obtenerCompras($Pag["per_page"],$limite,$idProducto);
		$data['total'] 			= $this->informacion->sumarCompras($idProducto);
		$data['limite']			= $limite+1;

		$this->load->view('informacion/obtenerCompras',$data);
	}

	public function obtenerEntradasTraspasos($limite=0)
	{
		$idProducto				= $this->input->post('idProducto');

		$Pag["base_url"]		= base_url()."informacion/obtenerEntradasTraspasos/";
		$Pag["total_rows"]		= $this->informacion->contarEntradasTraspasos($idProducto);
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;

		$this->pagination->initialize($Pag);

		$data['recepciones'] 	= $this->informacion->obtenerEntradasTraspasos($Pag["per_page"],$limite,$idProducto);
		$data['total'] 			= $this->informacion->sumarEntradasTraspasos($idProducto);
		$data['limite']			= $limite+1;

		$this->load->view('informacion/obtenerEntradasTraspasos',$data);
	}
	
	public function obtenerVentas($limite=0)
	{
		$idProducto				= $this->input->post('idProducto');

		$Pag["base_url"]		= base_url()."informacion/obtenerVentas/";
		$Pag["total_rows"]		= $this->informacion->contarVentas($idProducto);
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;

		$this->pagination->initialize($Pag);

		$data['ventas'] 		= $this->informacion->obtenerVentas($Pag["per_page"],$limite,$idProducto);
		$data['total'] 			= $this->informacion->sumarVentas($idProducto);
		$data['limite']			= $limite+1;

		$this->load->view('informacion/obtenerVentas',$data);
	}

	public function obtenerEnvios($limite=0)
	{
		$idProducto				= $this->input->post('idProducto');

		$Pag["base_url"]		= base_url()."informacion/obtenerEnvios/";
		$Pag["total_rows"]		= $this->informacion->contarEnvios($idProducto);
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;

		$this->pagination->initialize($Pag);

		$data['envios'] 		= $this->informacion->obtenerEnvios($Pag["per_page"],$limite,$idProducto);
		$data['total'] 			= $this->informacion->sumarEnvios($idProducto);
		$data['limite']			= $limite+1;

		$this->load->view('informacion/obtenerEnvios',$data);
	}

	public function obtenerMovimientos($limite=0)
	{
		$idProducto				= $this->input->post('idProducto');

		$Pag["base_url"]		= base_url()."informacion/obtenerMovimientos/";
		$Pag["total_rows"]		= $this->informacion->contarMovimientos($idProducto);
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;

		$this->pagination->initialize($Pag);

		$data['movimientos'] 	= $this->informacion->obtenerMovimientos($Pag["per_page"],$limite,$idProducto);
		$data['total'] 			= $this->informacion->sumarMovimientos($idProducto);
		$data['limite']			= $limite+1;

		$this->load->view('informacion/obtenerMovimientos',$data);
	}

	public function obtenerDiario($limite=0)
	{
		$idProducto				= $this->input->post('idProducto');

		$Pag["base_url"]		= base_url()."informacion/obtenerDiario/";
		$Pag["total_rows"]		= $this->informacion->contarDiario($idProducto);
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;

		$this->pagination->initialize($Pag);

		$data['diario'] 		= $this->informacion->obtenerDiario($Pag["per_page"],$limite,$idProducto);
		$data['total'] 			= $this->informacion->sumarDiario($idProducto);
		$data['limite']			= $limite+1;

		$this->load->view('informacion/obtenerDiario',$data);
	}

	public function obtenerEntradasEntregas($limite=0)
	{
		$idProducto				= $this->input->post('idProducto');

		$Pag["base_url"]		= base_url()."informacion/obtenerEntradasEntregas/";
		$Pag["total_rows"]		= $this->informacion->contarEntradasEntrega($idProducto);
		$Pag["per_page"]		= 20;
		$Pag["num_links"]		= 5;
		$Pag["uri_segment"]		= 3;

		$this->pagination->initialize($Pag);

		$data['registros'] 		= $this->informacion->obtenerEntradasEntregas($Pag["per_page"],$limite,$idProducto);
		$data['total'] 			= $this->informacion->sumarEntradasEntrega($idProducto);
		$data['limite']			= $limite+1;

		$this->load->view('informacion/obtenerEntradasEntregas',$data);
	}
}
?>
