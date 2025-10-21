<?php
require APPPATH .'/libraries/REST_Controller.php';
require APPPATH .'/libraries/Format.php';

class Tickets extends REST_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model("tickets_modelo","tickets");
	}
	
	public function generarCodigoBarras_get($folio)
	{
		$this->load->library('barcode');
		
		$generator = new barcode();
		
		$image = $generator->output_image('png',"code39ascii",$folio,array('w'=>285,'h'=>80,'ts'=>3,'th'=>11,'ww'=>3));
	}
	
	public function guardarCodigoBarras_get($folio)
	{
		file_put_contents('media/ventas/'.$folio.'.png', file_get_contents(base_url().'tickets/generarCodigoBarras/'.$folio));
	}
	
	public function obtenerCotizacion_post() 
	{
		$idCotizacion     	 	= $this->post('idCotizacion');
		$this->load->library('ccantidadletras');
		
		if($idCotizacion and strlen($idCotizacion)>0)
		{
			$registro		= $this->tickets->obtenerCotizacion($idCotizacion);

			if($registro!=null)
			{
				$this->guardarCodigoBarras_get($registro['folio']);
				
				$this->ccantidadletras->setIdioma("ES");
				$this->ccantidadletras->setNumero($registro['total']);
				$this->ccantidadletras->setMoneda('pesos');//

				$registro['cantidadLetras']	= '(** '.$this->ccantidadletras->PrimeraMayuscula().' **)';
				$registro['total']				= number_format($registro['total'],2);
				$registro['fechaCompra']		= obtenerFechaMesCortoHoraFormato($registro['fechaCompra']);
				
				
				$registro['productos']		= $this->tickets->obtenerProductosVenta($idCotizacion);
				$registro['configuracion']	= $this->tickets->obtenerConfiguraciones($registro['idLicencia']);
				$registro['cliente']		= $this->tickets->obtenerCliente($registro['idCliente']);
				$registro['direccion']		= $this->tickets->obtenerDireccion($registro['idDireccion']);
				
				$this->response($registro, 200);
			}
			else
			{
				$this->response(array('respuesta'=>false,'error'=>'No se encuentra el registro'), 400);
			}
		}
		else
		{
			$this->response(array('respuesta'=>false,'error'=>'Los datos son incorrectos'), 400);
		}
	}
	
	public function obtenerCotizaciones_get() 
	{
		$idCotizacion     	 	= $this->get('idCotizacion');
		$this->load->library('ccantidadletras');

		if($idCotizacion and strlen($idCotizacion)>0)
		{
			$registro	= $this->tickets->obtenerCotizacion($idCotizacion);

			if($registro!=null)
			{
				$this->guardarCodigoBarras_get($registro['folio']);
				
				$this->ccantidadletras->setIdioma("ES");
				$this->ccantidadletras->setNumero($registro['total']);
				$this->ccantidadletras->setMoneda('pesos');//

				$registro['cantidadLetras']	= '(** '.$this->ccantidadletras->PrimeraMayuscula().' **)';
				$registro['total']				= number_format($registro['total'],2);
				$registro['fechaCompra']		= obtenerFechaMesCortoHoraFormato($registro['fechaCompra']);
				
				
				$registro['productos']		= $this->tickets->obtenerProductosVenta($idCotizacion);
				$registro['configuracion']	= $this->tickets->obtenerConfiguraciones($registro['idLicencia']);
				$registro['cliente']		= $this->tickets->obtenerCliente($registro['idCliente']);
				$registro['direccion']		= $this->tickets->obtenerDireccion($registro['idDireccion']);
				
				$this->response($registro, 200);
			}
			else
			{
				$this->response(array('respuesta'=>false,'error'=>'No se encuentra el registro'), 400);
			}
		}
		else
		{
			$this->response(array('respuesta'=>false,'error'=>'Los datos son incorrectos'), 400);
		}
	}
	
	
	public function obtenerRegistrosPadre_post() 
	{
		$usuario     	 	= $this->post('usuario');
		$password     		= $this->post('password');

		if($usuario and strlen($usuario)>0 and $password and strlen($password)>0)
		{
			$registro	= $this->peticiones->obtenerUsuarioPadre($usuario,$password);

			if($registro!=null)
			{
				$alumnos	= $this->peticiones->obtenerAlumnosPadre($registro['idPadre']);
				$eventos	= $this->peticiones->obtenerEventosPadre($registro['idPadre']);
				$numero		= count($alumnos);
				
				$i=0;
				foreach($alumnos as $row)
				{
					$row['avisos']		= $this->peticiones->obtenerAvisosAlumno($row['idCliente']);
					$row['proyectos']	= $this->peticiones->obtenerProyectosAlumno($row['idCliente']);
					$row['asistencias']	= $this->peticiones->obtenerAsistenciasAlumno($row['idCliente']);
					$row['resumen']		= $this->peticiones->obtenerResumenAlumno($row['idCliente']);
					#$row['eventos']		= $this->peticiones->obtenerEventosAlumno($row['idCliente']);
					
					$alumnos[$i]	= $row;
					
					$i++;
				}
				
				$respuesta	= array('padre'=>$registro,'eventos'=>$eventos,'numeroHijos'=>$numero,'hijos'=>$alumnos);
				
				$this->response($respuesta, 200);
			}
			else
			{
				$this->response(array('respuesta'=>false,'error'=>'El usuario y/o password son incorrectos'), 400);
			}
		}
		else
		{
			$this->response(array('respuesta'=>false,'error'=>'Los datos son incorrectos'), 400);
		}
	}
	
	public function obtenerRegistrosProfesor_post() 
	{
		$idProfesor     	 	= $this->post('idProfesor');

		if($idProfesor and strlen($idProfesor)>0)
		{
			$registro	= $this->peticiones->obtenerProfesor($idProfesor);

			if($registro!=null)
			{
				$grupos		= $this->peticiones->obtenerGruposProfesor($registro['idProfesor']);
				$eventos	= $this->peticiones->obtenerEventosProfesor($registro['idProfesor']);
				
				
				$g=0;
				foreach($grupos as $gru)
				{
					$i=0;
					
					$alumnos		= $this->peticiones->obtenerAlumnosGrupo($gru['idGrupo']);
					
					foreach($alumnos as $row)
					{
						$row['avisos']		= $this->peticiones->obtenerAvisosAlumno($row['idCliente']);
						$row['proyectos']	= $this->peticiones->obtenerProyectosAlumno($row['idCliente']);
						$row['asistencias']	= $this->peticiones->obtenerAsistenciasAlumno($row['idCliente']);
						$row['resumen']		= $this->peticiones->obtenerResumenAlumno($row['idCliente']);
						#$row['eventos']		= $this->peticiones->obtenerEventosAlumno($row['idCliente']);
						
						$alumnos[$i]	= $row;
						
						$i++;
					}
					
					$grupos[$g]['alumnos']=$alumnos;
					$g++;
					
				}
				
				
				$respuesta	= array('profesor'=>$registro,'grupos'=>$grupos,'eventos'=>$eventos);
				
				$this->response($respuesta, 200);
			}
			else
			{
				$this->response(array('respuesta'=>false,'error'=>'El profesor no existe'), 400);
			}
		}
		else
		{
			$this->response(array('respuesta'=>false,'error'=>'Los datos son incorrectos'), 400);
		}
	}
	
	public function obtenerPagosAlumno_post() 
	{
		$idAlumno     	 	= $this->post('idAlumno');

		if($idAlumno and strlen($idAlumno)>0)
		{
			$registro	= $this->peticiones->obtenerAlumno($idAlumno);

			if($registro!=null)
			{
				$pagos		= $this->peticiones->obtenerPagosAlumno($registro['idCliente']);
				
				$respuesta	= array('alumno'=>$registro,'pagos'=>$pagos);
				
				$this->response($respuesta, 200);
			}
			else
			{
				$this->response(array('respuesta'=>false,'error'=>'El alumno no existe'), 400);
			}
		}
		else
		{
			$this->response(array('respuesta'=>false,'error'=>'Los datos son incorrectos'), 400);
		}
	}
	
	public function obtenerReciboPago_get() 
	{
		$this->response(array('respuesta'=>true,'recibo'=>base_url().'descarga/descargarRecibo'), 200);	
	}
	
	
}
?>
