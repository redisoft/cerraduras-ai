<?php
class Soporte extends CI_Controller
{
    protected $idUsuario;
    protected $fecha;

	function __construct()
	{
		parent::__construct();

		

		$this->fecha 			= date('Y-m-d H:i:s');
		$this->idUsuario 		= $this->session->userdata('id');

        $this->load->model("soporte_modelo","soporte");
		$this->load->model("temporal_modelo","temporal");
	 	$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("inventarioproductos_modelo","inventario");
		$this->load->model("listas_modelo","listas");
		
		$this->load->model("crm_modelo","crm");
		$this->load->model("importar_modelo","importar");
	}

	
	public function procesarMateriaPrima()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->procesarMateriaPrima();
		
		redirect(base_url().'materiales');
	}
	
	public function procesarProductos()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		$this->soporte->procesarProductos();
		
		redirect(base_url().'produccion');
	}
	
	public function procesarClientes()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		$this->soporte->procesarClientes();
		
		redirect(base_url().'clientes');
	}
	
	public function procesarProveedores()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->procesarProveedores();
		
		redirect(base_url().'proveedores');
	}
	
	public function creadorPermisos($idPermiso=0)
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		$this->soporte->creadorPermisos($idPermiso);
		
		redirect('clientes','refresh');
	}
	
	public function sincronizarBaseRemota()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->temporal->sincronizarVentasTemporales();
		
		redirect(base_url().'clientes');
	}
	
	public function registrarStockLicencias()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->registrarStockLicencias();
		
		redirect(base_url().'inventarioProductos');
	}
	
	public function procesarProductosPinata()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->procesarProductosPinata();
		
		redirect(base_url().'inventarioProductos');
	}
	
	public function procesarClientesAlumnos()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->procesarClientesAlumnos();
		
		redirect(base_url().'clientes');
	}
	
	public function creadorPermisosBotones($idBoton=0)
	{
		$this->soporte->creadorPermisosBotones($idBoton);
		
		redirect('configuracion/roles','refresh');
	}
	
	public function procesarClientesZona()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->procesarClientesZona();
		
		redirect(base_url().'clientes');
	}
	
	public function importarClientesMatricula()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
	
		$this->load->view('clientes/importar/importarClientesMatricula',$data);
		
		redirect(base_url().'clientes');
	} 
	
	public function administrarListasVigentes()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->listas->administrarListasVigentes();
		
		redirect(base_url().'materiales');
	}
	
	public function administrarListasPasadas()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->listas->administrarListasPasadas();
		
		redirect(base_url().'materiales');
	}
	
	public function procesarSeguimientosBorrar()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->procesarSeguimientosBorrar();
		
		redirect(base_url().'clientes');
	}
	
	public function procesarProgramasAlumnos()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0);
		
		$this->soporte->procesarProgramasAlumnos();
		
		redirect('ventas');
	}
	
	public function procesarAlumnosInscritos()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0);
		
		$this->soporte->procesarAlumnosInscritos();
		
		redirect('ventas');
	}
	
	public function procesarCorreos()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0);
		
		$this->soporte->procesarCorreos();
		
		redirect('ventas');
	}
	
	public function procesarSeguimientosAtrasos()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0);
		
		$this->soporte->procesarSeguimientosAtrasos();
		
		redirect('ventas');
	}
	
	public function procesarNuevos()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0);
		
		$this->soporte->procesarNuevos();
		
		redirect('clientes/prospectos');
	}
	
	public function procesarSeguimientosClientes()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0);
		
		$this->soporte->procesarSeguimientosClientes();
		
		redirect('clientes/prospectos');
	}
	
	public function procesarClientesErrores()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0);
		
		$this->soporte->procesarClientesErrores();
		
		redirect('clientes/prospectos');
	}
	
	public function procesarCrmPromotores()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0);
		
		$this->soporte->procesarCrmPromotores();
		
		redirect('clientes/prospectos');
	}
	
	public function importarProductosCerraduras()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->importarProductosCerraduras();
		
		redirect(base_url().'inventarioProductos');
	}
	
	public function procesarProspectosPromotor()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->procesarProspectosPromotor();
		
		redirect(base_url().'clientes/prospectos');
	}
	
	public function asignarCampanaSeguimiento()
	{
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
		
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->asignarCampanaSeguimiento();
		
		redirect(base_url().'clientes/prospectos');
	}
	
	public function obtenerUltimaConexion()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->obtenerUltimaConexion();
		
		redirect(base_url().'clientes');
	}
	
	public function registrarStockLicencia()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->registrarStockLicencia();
		
		redirect(base_url().'inventarioProductos');
	}
	
	public function asignarClientesPromotor()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->asignarClientesPromotor();
		
		redirect(base_url().'inventarioProductos');
	}
	
	public function creadorPermisosBotonesRango($boton1=0,$boton2=0)
	{
		$this->soporte->creadorPermisosBotonesRango($boton1,$boton2);
		
		redirect('configuracion/roles','refresh');
	}
	
	public function cambiarSeguimientosFechas()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->cambiarSeguimientosFechas();
		
		redirect(base_url().'inventarioProductos');
	}
	
	public function procesarDireccionesFiscales()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->procesarDireccionesFiscales();
		
		redirect(base_url().'clientes');
	}
	
	public function procesarCotizacionesEstaciones()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->procesarCotizacionesEstaciones();
		
		redirect(base_url().'clientes');
	}
	
	public function procesarStockSucursal()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->procesarStockSucursal();
		
		redirect(base_url().'clientes');
	}
	
	public function codigoBarras()
	{
		$this->load->library('barcode');
		
		$generator = new barcode();

		
		$image = $generator->output_image('png',"code39ascii","500000",array('w'=>474,'h'=>117,'ts'=>2,'th'=>11,'ww'=>3));
		
		#echo $image;
		#imagepng($image);
		#imagedestroy($image);
	}
	
	public function guardar()
	{
		file_put_contents('media/fel/perrita.png', file_get_contents(base_url().'soporte/codigoBarras'));

	}
	
	public function generarCodigoBarras($folio)
	{
		$this->load->library('barcode');
		
		$generator = new barcode();
		
		//$image = $generator->output_image('png',"code39ascii",$folio,array('w'=>474,'h'=>117,'ts'=>3,'th'=>11,'ww'=>3));
		$image = $generator->output_image('png',"code39ascii",$folio,array('w'=>285,'h'=>80,'ts'=>3,'th'=>11,'ww'=>3));
	}
	
	public function guardarCodigoBarras($folio)
	{
		file_put_contents('media/ventas/'.$folio.'.png', file_get_contents(base_url().'soporte/generarCodigoBarras/'.$folio));
	}
	
	public function registrarPreciosProductos()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->registrarPreciosProductos();
		
		redirect(base_url().'clientes');
	}
	
	public function borrarProductosCordobita()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->borrarProductosCordobita();
		
		redirect(base_url().'inventarioProductos');
	}
	
	public function entregarProductosPendientes()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->entregarProductosPendientes();
		
		redirect(base_url().'inventarioProductos');
	}
	
	public function copiarProductosSucursal()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->copiarProductosSucursal();
		
		redirect(base_url().'inventarioProductos');
	}

	public function borrarDuplicados()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->borrarDuplicados();
		
		#redirect(base_url().'inventarioProductos');
	}
}
?>
