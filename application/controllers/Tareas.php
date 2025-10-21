<?php
class Tareas extends CI_Controller
{
    protected $_fechaActual;
    protected $_iduser;
    protected $_csstyle;
    protected $_tables;
    protected $_Variables;
    protected $_role;

    function __construct()
	{
		 parent::__construct();
	
		if( ! $this->redux_auth->logged_in() )
		{//verificar si el el usuario ha iniciado sesion
			#redirect(base_url().'login');
		}
        $datestring   = "%Y-%m-%d";
		$this->_fechaActual = mdate($datestring,now());

         $this->_iduser = $this->session->userdata('id');
         $this->_role = $this->session->userdata('role');

         $this->config->load('datatables', TRUE);
         $this->_tables = $this->config->item('datatables');

        $this->load->model("modelousuario","modelousuario");
        $this->load->model("modeloclientes","clientes");
        $this->load->model("inventario_model","modeloinventario");        
        $this->load->model('proveedores_model','proveedores');
        $this->load->model("modelo_configuracion","configuracion");
		$this->load->model("compras_modelo","compras");
		$this->load->model("ventas_model","ventas");
		$this->load->model("facturacion_modelo","facturacion");
		$this->load->model("soporte_modelo","soporte");
		
       #$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
	}
	
	public function enviarNotificaciones($criterio)
	{
		$this->notificarSeguimiento($criterio);
		$this->notificarPronostico($criterio);
	}
	
	public function notificarSeguimiento($criterio)
	{
		$fecha			=$this->clientes->obtenerFechaAnterior($this->_fechaActual,1,'day');
		
		if($criterio=='diario')
		{
			$fecha		=$this->_fechaActual;
		}
		
		$responsables	= $this->clientes->obtenerListaResponsables($fecha);
		$configuracion	= $this->configuracion->obtenerConfiguraciones(1);
		
		foreach($responsables as $res)
		{
			$seguimiento		= $this->clientes->obtenerSeguimientoResponsables($fecha,$res->idResponsable);
			$mensaje			= '';
			
			foreach($seguimiento as $row)
			{
				$mensaje		.='<strong>Fecha: </strong>'.obtenerFechaMesCortoHora($row->fecha).'<br />';
				$mensaje		.='<strong>Cliente: </strong>'.$row->empresa.'<br />';
				$mensaje		.='<strong>Status: </strong> '.$row->status.'<br />';
				
				if($row->idStatus!=11)
				{
					$mensaje		.='<strong>Servicio: </strong> '.$row->servicio.'<br />';
					
				}
				
				$mensaje		.='<strong>Seguimiento: </strong>'.obtenerFechaMesCortoHora($row->fechaCierre).'<br />';
				$mensaje		.='<strong>Lugar: </strong> '.$row->lugar.'<br />';
				$mensaje		.='<strong>Comentarios: </strong> '.$row->comentarios.'
				<br /><br />
				<hr>
				<br /><br />';
			}
			
			if(strlen($mensaje)>0)
			{
				$this->enviarCorreoSeguimiento($res->correo,$mensaje,$configuracion,$fecha);
			}
		}
	}
	
	public function notificarSeguimientoTiempo()
	{
		$seguimiento		= $this->clientes->obtenerSeguimientosTiempo();
		$configuracion		= $this->configuracion->obtenerConfiguraciones(1);
		$mensaje			= '';
		
		foreach($seguimiento as $row)
		{
			$mensaje			= '';
			
			$mensaje		.='<strong>Fecha: </strong>'.obtenerFechaMesCortoHora($row->fecha).'<br />';
			$mensaje		.='<strong>Cliente: </strong>'.$row->empresa.'<br />';
			$mensaje		.='<strong>Status: </strong> '.$row->status.'<br />';
			
			if($row->idStatus!=11)
			{
				$mensaje		.='<strong>Servicio: </strong> '.$row->servicio.'<br />';
				
			}
			
			$mensaje		.='<strong>Seguimiento: </strong>'.obtenerFechaMesCortoHora($row->fechaCierre).'<br />';
			
			$mensaje		.='<strong>Lugar: </strong> '.$row->lugar.'<br />';
			$mensaje		.='<strong>Comentarios: </strong> '.$row->comentarios.'
			<br /><br />
			<hr>
			<br /><br />';
			
			if(strlen($mensaje)>0)
			{
				#echo 'Email: '.(strlen($row->email)>3?$row->email:$row->correo.'<br /><br />';
				#echo 'Mensaje: '.$mensaje;
				$this->enviarCorreoSeguimiento(strlen($row->email)>3?$row->email:$row->correo,$mensaje,$configuracion,$row->fechaCierre);
			}
		}
	}
	
	//NOTIFICACIÓN DE SEGUIMIENTO DE  PROVEEDORES
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	public function notificarSeguimientoProveedores($criterio)
	{
		$fecha					= $this->clientes->obtenerFechaAnterior($this->_fechaActual,1,'day');
		
		if($criterio=='diario')
		{
			$fecha				= $this->_fechaActual;
		}
		
		$responsables			= $this->proveedores->obtenerListaResponsables($fecha);
		$configuracion			= $this->configuracion->obtenerConfiguraciones(1);
		
		foreach($responsables as $res)
		{
			$seguimiento		= $this->proveedores->obtenerSeguimientoResponsables($fecha,$res->idResponsable);
			$mensaje			= '';
			
			foreach($seguimiento as $row)
			{
				$mensaje		.='<strong>Fecha: </strong>'.obtenerFechaMesCortoHora($row->fecha).'<br />';
				$mensaje		.='<strong>Proveedor: </strong>'.$row->empresa.'<br />';
				$mensaje		.='<strong>Status: </strong> '.$row->status.'<br />';
				
				if($row->idStatus!=11)
				{
					$mensaje		.='<strong>Servicio: </strong> '.$row->servicio.'<br />';
					
				}
				
				$mensaje		.='<strong>Seguimiento: </strong>'.obtenerFechaMesCortoHora($row->fechaCierre).'<br />';
				$mensaje		.='<strong>Lugar: </strong> '.$row->lugar.'<br />';
				$mensaje		.='<strong>Comentarios: </strong> '.$row->comentarios.'
				<br /><br />
				<hr>
				<br /><br />';
			}
			
			if(strlen($mensaje)>0)
			{
				$this->enviarCorreoSeguimiento($res->correo,$mensaje,$configuracion,$fecha);
			}
		}
	}
	
	public function notificarSeguimientoTiempoProveedores()
	{
		$seguimiento		= $this->proveedores->obtenerSeguimientosTiempo();
		$configuracion		= $this->configuracion->obtenerConfiguraciones(1);
		$mensaje			= '';
		
		foreach($seguimiento as $row)
		{
			$mensaje			= '';
			
			$mensaje		.='<strong>Fecha: </strong>'.obtenerFechaMesCortoHora($row->fecha).'<br />';
			$mensaje		.='<strong>Proveedor: </strong>'.$row->empresa.'<br />';
			$mensaje		.='<strong>Status: </strong> '.$row->status.'<br />';
			
			if($row->idStatus!=11)
			{
				$mensaje		.='<strong>Servicio: </strong> '.$row->servicio.'<br />';
				
			}
			
			$mensaje		.='<strong>Seguimiento: </strong>'.obtenerFechaMesCortoHora($row->fechaCierre).'<br />';
			
			$mensaje		.='<strong>Lugar: </strong> '.$row->lugar.'<br />';
			$mensaje		.='<strong>Comentarios: </strong> '.$row->comentarios.'
			<br /><br />
			<hr>
			<br /><br />';
			
			if(strlen($mensaje)>0)
			{
				#echo 'Email: '.(strlen($row->email)>3?$row->email:$row->correo.'<br /><br />';
				#echo 'Mensaje: '.$mensaje;
				$this->enviarCorreoSeguimiento(strlen($row->email)>3?$row->email:$row->correo,$mensaje,$configuracion,$row->fechaCierre);
			}
		}
	}

	public function enviarCorreoSeguimiento($destinatario,$mensaje,$configuracion,$fecha)
	{
		$asunto			='Lista de seguimientos fecha '.obtenerFechaMesCorto($fecha);

		$this->load->library('email');
		$this->email->from($configuracion->correo,$configuracion->nombre);
		$this->email->to($destinatario);
		$this->email->subject($asunto);
		$this->email->message($mensaje);
		#$this->email->type('html');
		
		if (!$this->email->send())
		{
			#print("0");
		}
		else
		{
			#print("1");
		}
	}
	
	public function notificarPronostico($criterio)
	{
		$fecha			=$this->clientes->obtenerFechaAnterior($this->_fechaActual,1,'day');
		
		if($criterio=='diario')
		{
			$fecha		=$this->_fechaActual;
		}
		
		$ingresos		=$this->clientes->obtenerPronosticoIngresos($fecha);
		$pagos			=$this->clientes->obtenerPronosticoGastos($fecha);
		$configuracion	=$this->configuracion->obtenerConfiguraciones(1);
		$mensaje		='';
		
		if($ingresos!=null)
		{
			$mensaje		.='<strong style="font-size:18px">Pronóstico de cobros:</strong><br /><br />';
			
			foreach($ingresos as $row)
			{
				$cliente		=$this->clientes->obtenerCliente($row->idCliente);
				$cliente		=$cliente!=null?$cliente->empresa:'';

				$mensaje		.='<strong>Fecha: </strong>'.obtenerFechaMesCortoHora($row->fecha).'<br />';
				$mensaje		.='<strong>Cliente: </strong>'.$cliente.'<br />';
				$mensaje		.='<strong>Importe: </strong> $'.number_format($row->pago,2).'<br />';
				$mensaje		.='<strong>Concepto: </strong> '.$row->producto.'<br />';
				$mensaje		.='<strong>Forma de pago: </strong> '.$row->formaPago.'<br />';
				
				$mensaje		.='<br /><br /><hr><br /><br />';
			}
		}
		
		if($pagos!=null)
		{
			$mensaje			.='<strong style="font-size:18px">Pronóstico de pagos:</strong><br /><br />';
			
			foreach($pagos as $row)
			{
				$proveedor		=$this->proveedores->obtenerProveedor($row->idProveedor);
				$proveedor		=$proveedor!=null?$proveedor->empresa:'';

				$mensaje		.='<strong>Fecha: </strong>'.obtenerFechaMesCortoHora($row->fecha).'<br />';
				$mensaje		.='<strong>Cliente: </strong>'.$proveedor.'<br />';
				$mensaje		.='<strong>Importe: </strong> $'.number_format($row->pago,2).'<br />';
				$mensaje		.='<strong>Concepto: </strong> '.$row->producto.'<br />';
				$mensaje		.='<strong>Forma de pago: </strong> '.$row->formaPago.'<br />';
				
				$mensaje		.='<br /><br /><hr><br /><br />';
			}
		}
		
		if(strlen($mensaje)>0)
		{
			$this->enviarCorreoPronostico($configuracion->correo,$mensaje,$configuracion,$fecha);
		}
	}
	
	public function enviarCorreoPronostico($destinatario,$mensaje,$configuracion,$fecha)
	{
		$asunto			='Pronóstico de pagos y cobros fecha '.obtenerFechaMesCorto($fecha);

		$this->load->library('email');
		$this->email->from($configuracion->correo,$configuracion->nombre);
		$this->email->to($destinatario);
		$this->email->subject($asunto);
		$this->email->message($mensaje);
		#$this->email->type('html');
		
		if (!$this->email->send())
		{
			#print("0");
		}
		else
		{
			#print("1");
		}
	}
	
	public function activarVentasServicios($criterio='')
	{
		$this->ventas->activarVentasServicios($criterio='');
	}
	
	protected $i=1;
	
	public function listar_directorios_ruta($Rupa='')
	{ 
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$ruta=strlen($Rupa)==0?"media/fel/":$Rupa;
		
		
		// abrir un directorio y listarlo recursivo 
		if (is_dir($ruta)) 
		{ 
			if ($dh = opendir($ruta)) 
			{ 
				while (($file = readdir($dh)) !== false) 
				{ 
					//esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio 
					//mostraría tanto archivos como directorios 
					#echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file); 
					
					if (!is_dir($ruta . $file))
					{
						 #echo $ruta.$file.'<br />';
						 
						$trozos 	= explode(".", $file); 
						$extension 	= end($trozos); 

						 #if($file=='cadena.txt' or $file=='sello.txt' or $file=='certificadoImprimir.txt' or $file=='certificado.txt')
						 if($extension=='txt')
						 {
							 unlink($ruta . $file);
							 
							 echo  $this->i.'.- '.$ruta . $file.'<br />';
							 
							 $this->i++;
						 }

						 if($extension=='pdf')
						 {
							 unlink($ruta . $file);
							 
							 echo  $this->i.'.- '.$ruta . $file.'<br />';
							 
							 $this->i++;
						 }
						 
						 if($extension=='xml')
						 {
							 if(strlen($file)<=15)
							 {
								  unlink($ruta . $file);
								  echo $this->i.'.- '.$ruta . $file.'<br />';
								  
								  $this->i++;
							 }
						 }
					}
					
					if (is_dir($ruta . $file) && $file!="." && $file!="..")
					{ 
						//solo si el archivo es un directorio, distinto que "." y ".." 
						#echo "<br>Directorio: $ruta$file"; 
						$this->listar_directorios_ruta($ruta . $file . "/"); 
					} 
				} 
				
				closedir($dh); 
			} 
		}
		
		else 
			echo "<br>No es ruta valida"; 
	}
	
	public function registrarInventarioDiario()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->soporte->registrarInventarioDiario();
	}
}
?>
