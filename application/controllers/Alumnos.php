<?php
require APPPATH .'/libraries/REST_Controller.php';
require APPPATH .'/libraries/Format.php';

class Alumnos extends REST_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model("alumnos_modelo","alumnos");

	}
	
	public function alumno_get() 
	{
		$this->response($this->alumnos->obtenerAlumno1());
	}
	
	function registrarAlumno_post() 
	{
		$nombre     	 	= $this->post('nombre');
		$apellido     		= $this->post('apellidos');
		$telefono      		= $this->post('telefono');
		$tipoTelefono      	= $this->post('tipo_telefono');
		$telefono2      	= $this->post('telefono2');
		$tipoTelefono2      = $this->post('tipo_telefono2');
		$email      		= $this->post('email');
		$estado      		= $this->post('estado');
		$nivelEstudios      = $this->post('nivel_estudios');
		$pregunta      		= $this->post('comentarios');
		$programa      		= $this->post('programa');
		
		if($nombre and strlen($nombre)>0 and $apellido and strlen($apellido)>0 and $telefono and strlen($telefono)>0 and $tipoTelefono and strlen($tipoTelefono)>0 
		 and $email and strlen($email)>0 and $estado  and strlen($estado)>0 and $nivelEstudios and strlen($email)>0 and $programa and strlen($programa)>0)
		{
			$data=array
			(
				'nombre'		=> $nombre,
				'apellido'		=> $apellido,
				'telefono'		=> $telefono,
				'tipoTelefono'	=> $tipoTelefono,
				'telefono2'		=> $telefono2,
				'tipoTelefono2'	=> $tipoTelefono2,
				'email'			=> $email,
				'estado'		=> $estado,
				'nivelEstudios'	=> $nivelEstudios,
				'pregunta'		=> $pregunta,
				'programa'		=> $programa,
			);
			
			$alumno		= $this->alumnos->registrarAlumno($data);
			
			if($alumno[0])
			{
				$this->response($alumno, 200);
			}
			else
			{
				$this->response($alumno, 400);
			}
			
		}
		else
		{
			$this->response(array(false,'Los datos son incorrectos'), 400);
		}
	}
}
?>
