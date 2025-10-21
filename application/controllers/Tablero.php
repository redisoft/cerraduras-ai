<?php
class Tablero extends CI_Controller
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
		{//verificar si el el usuario ha iniciado sesion
 			redirect(base_url().'login');
 		}
		
		$this->config->load('js',TRUE);
		$this->config->load('style', TRUE);
		
		$datestring   			= "%Y-%m-%d %H:%i:%s";
	    $this->_fechaActual 	= mdate($datestring,now());
		$this->_iduser 			= $this->session->userdata('id');
		$this->_role 			= $this->session->userdata('role');
		$this->_csstyle 		= $this->config->item('style');
        $this->_jss				= $this->config->item('js');

        $this->load->model("modeloclientes","clientes");
		$this->load->model("ordenes_modelo","ordenes");
		$this->load->model("compras_modelo","compras");
		$this->load->model("facturacion_modelo","facturacion");
		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("bancos_model","bancos");
		$this->load->model("tablero_modelo","tablero");
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
  	}
	
	public function obtenerCotizacion()
	{
		if(!empty($_POST))
		{
			$idCotizacion			=$this->input->post('idCotizacion');
			$data['cotizacion']		=$this->facturacion->obtenerCotizacion($idCotizacion);
			$data['cliente']		=$this->facturacion->obtenerCliente($data['cotizacion']->idCliente);
			$data['productos']		=$this->facturacion->obtenerProductosCotizacion($idCotizacion);

			$this->load->view('tablero/obtenerCotizacion',$data);
		}
	}
	
	public function obtenerVenta()
	{
		if(!empty($_POST))
		{
			$idCotizacion				= $this->input->post('idCotizacion');
			$data['cotizacion']			= $this->facturacion->obtenerCotizacion($idCotizacion);
			$data['cliente']			= $this->facturacion->obtenerCliente($data['cotizacion']->idCliente);
			$data['productos']			= $this->facturacion->obtenerProductosCotizacion($idCotizacion);
			$data['idCotizacion']		= $idCotizacion;

			$this->load->view('tablero/obtenerVenta',$data);
		}
	}
	
	public function obtenerCompra()
	{
		if(!empty($_POST))
		{
			$idCompras			= $this->input->post('idCompras');
			$data['compra']		= $this->compras->obtenerCompra($idCompras);
			$data['modulo']		= 'comprasPDF';
			
			$data['productos']	= $this->compras->obtenerProductosComprados($idCompras);
			
			if($data['compra']->inventario==1)
			{
				$data['productos']	= $this->compras->obtenerInventariosComprados($idCompras);
				$data['modulo']		= 'comprasPDFInventarios';
			}
			
			if($data['compra']->reventa==1)
			{
				$data['productos']	= $this->compras->obtenerReventaComprados($idCompras);
				$data['modulo']		= 'comprasPDFProductos';
			}
			
			if($data['compra']->servicios=='1')
			{
				$data['productos']	= $this->compras->obtenerServiciosComprados($idCompras);
				$data['modulo']		= 'comprasPDFServicios';
			}
			
			$this->load->view('tablero/obtenerCompra',$data);
		}
	}
	
	//=====================================================================================================================//
	//============================================OBTENER COBROS CLIENTES==================================================//
	//=====================================================================================================================//

	public function obtenerCobrosClientes()
	{
		$idCotizacion	=$this->input->post('idCotizacion');
		
		$data['cotizacion']		=$this->clientes->obtenerCotizacionVenta($idCotizacion);
		$data['pagos']			=$this->clientes->obtenerPagos($idCotizacion);
		$data['total']			=$this->clientes->obtenerPagado($idCotizacion);
		$data['bancos']			=$this->bancos->obtenerBancos();
		
		$this->load->view('tablero/obtenerCobrosClientes',$data);
	}
	
	//=====================================================================================================================//
	//============================================OBTENER PAGOS PROVEEDOR==================================================//
	//=====================================================================================================================//
	public function obtenerPagosCompras()
	{
		$idCompra=$this->input->post('idCompra');
		
		$data['compra']		=$this->compras->comprasPagos($idCompra);
		$data['bancos']		=$this->bancos->obtenerBancos();
		$data['total']		=$this->compras->obtenerTotal($idCompra);
		$data['pagos']		=$this->compras->obtenerPagos($idCompra);
		
		$this->load->view('tablero/obtenerPagosCompras',$data);
	}
	
	public function obtenerDetallesFactura()
	{
		if(!empty($_POST))
		{
			$idFactura				= $this->input->post('idFactura');
			
			$data['factura'] 		= $this->facturacion->obtenerFactura($idFactura);
			
			if($data['factura']->documento!='Recibo de Nómina')
			{
				$data['cliente']		= $this->facturacion->obtenerCliente($data['factura']->idCliente);
				$data['productos']		= $this->tablero->obtenerConceptosCfdi($idFactura);
				$data['acceso'] 		= 'crearFactura';
	
				$this->load->view('tablero/obtenerDetallesFactura',$data);
			}
			else
			{
				$data['empleado']		= $this->tablero->obtenerEmpleado($idFactura);
				$data['percepciones']	= $this->tablero->obtenerPercepciones($idFactura);
				$data['deducciones']	= $this->tablero->obtenerDeducciones($idFactura);
				$data['acceso'] 		= 'reciboNomina';
				
				$this->load->view('tablero/obtenerDetallesRecibo',$data);
			}
		}
	}	
}
?>
