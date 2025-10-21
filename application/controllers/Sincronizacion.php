<?php
class Sincronizacion extends CI_Controller
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
		
		if( ! $this->redux_auth->logged_in() )
		{
 			redirect(base_url().'login');
 		}
	}

	public function actualizacionLocalNube()
	{
		if(!empty($_POST))
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 

			echo json_encode($this->temporal->actualizacionLocalNube());
		}
		else
		{
			echo json_encode(array('0','Sin datos que procesar'));
		}
	}
	
	
	public function actualizacionNubeLocal()
	{
		if(!empty($_POST))
		{
			ini_set("memory_limit","1500M");
			set_time_limit(0); 
			$this->load->helper('base');

			$sucursal			= $this->temporal->obtenerSucursalActiva();

			$dsn				= obtenerConexion($sucursal);
			$base				= $this->load->database($dsn,TRUE);

			$dbutil				= $this->load->dbutil($base,TRUE);
			$this->load->helper('file');

			$prefs = array
			(
				'tables'        => array('productos', 'productos_inventarios', 'productos_marcas', 'productos_lineas', 'proveedores', 'rel_producto_proveedor', 'productos_departamentos'),
				'format'      	=> 'gzip',            
				'filename'    	=> 'base',    
				'newline'     	=> "\n",
				'add_drop'      => TRUE,   
				'add_insert'    => TRUE,  
			);

			$copiaSeguridad 	= $dbutil->backup($prefs); 
			$base				= "baseRemota.gzip";
			$carpeta			= carpetaFicheros.$base;

			$archivo			= write_file($carpeta,$copiaSeguridad);

			if($archivo)
			{
				echo json_encode($this->temporal->actualizacionNubeLocal($carpeta));
			}
			else
			{
				echo json_encode(array('0','Imposible descargar la información, revise su conexión'));
			}
		}
		else
		{
			echo json_encode(array('0','Sin datos que procesar'));
		}
	}
}
?>
