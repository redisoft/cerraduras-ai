<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iexe extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		if( ! $this->redux_auth->logged_in() )
		{
 			#redirect(base_url().'login');
 		}

		$this->load->model("modelo_configuracion","configuracion");
		$this->load->model("modelousuario","modelousuario");
		$this->load->model("iexe_modelo","iexe");
		$this->load->model("crm_modelo","crm");
    }
	
	public function index()
	{
		redirect('clientes','refresh');
	}

	public function actualizarAlumnosActivos()
	{
		error_reporting(0);
		ini_set("memory_limit","1500M");
		set_time_limit(0); 

		$url 	= "https://www.iexe.edu.mx/app/seguimiento_egresados/index.php/Alumnos/MatriculasActivas";
		
		$json 		= file_get_contents($url);
		$clientes 	= json_decode($json);
		
		if($clientes!=null)
		{
			//Desactivar registros
			$this->iexe->desactivarClientesActivos();
			
			//ACTIVARLOS POR MATRICULA
			$i=1;
			foreach($clientes as $row)
			{
				$this->iexe->activarClienteMatricula($row->Matricula);
			}
		}
		
		redirect('clientes','refresh');
	}
	
	public function revisarJson()
	{
		$url 	= "https://www.iexe.edu.mx/app/seguimiento_egresados/index.php/Alumnos/MatriculasActivas";
		
		$json 		= file_get_contents($url);
		$clientes 	= json_decode($json);
		
		echo count($clientes);
	}
	
	public function alumnosNoEncontrados()
	{
		error_reporting(0);
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$url 		= "https://www.iexe.edu.mx/app/seguimiento_egresados/index.php/Alumnos/MatriculasActivas";
		$lista		= array();
		$json 		= file_get_contents($url);
		$clientes 	= json_decode($json);
		
		if($clientes!=null)
		{
			$i=1;
			foreach($clientes as $row)
			{
				$cliente	= $this->iexe->obtenerClientesMatricula($row->Matricula);
				
				if($cliente==null)
				{
					$lista[]	= $row;
				}
			}
		}
		
		#echo json_encode($lista);
		
		return ($lista);
	}
	
	public function excelAlumnosNoEncontrados()
	{
		error_reporting(0);
		ini_set("memory_limit","1500M");
		set_time_limit(0); 

		$this->load->library('excel/PHPExcel');

		$data['alumnos']			= $this->alumnosNoEncontrados();
		#$alumnos					= $data['alumnos'];
		
		#echo count($data['alumnos']);
		
		/*for($i=0;$i<count($alumnos);$i++)
		{
			echo ($i+1).' - Matrícula: '.$alumnos[$i]->Matricula.'<br />';
		}
		
		foreach($data['alumnos'] as $row)
		{
			#echo $row.'<br />';
		}*/
		
		
		#var_dump($data['alumnos']);
		#exit;

		$this->load->view("iexe/excelAlumnosNoEncontrados", $data);
		
		//
		
		$this->load->helper('download');
		
		$nombreFisico 	= 'alumnosNoEncontrados.xls';
		$descarga 		= 'alumnosNoEncontrados.xls';
		$data 			= file_get_contents("media/ficheros/$nombreFisico"); 

		force_download($descarga, $data); 
	}
	
	//ACTUALIZAR LOS PERIODOS CON LAS MATRÍCULAS
	
	public function actualizarPeriodosMatricula()
	{
		error_reporting(0);
		ini_set("memory_limit","1500M");
		set_time_limit(0); 

		$url 	= "https://www.iexe.edu.mx/app/seguimiento_egresados/index.php/Alumnos/MatriculasActivas";
		
		$json 		= file_get_contents($url);
		$clientes 	= json_decode($json);
		
		if($clientes!=null)
		{
			$i=1;
			foreach($clientes as $row)
			{
				$periodo	= $this->iexe->obtenerPeriodoNombre($row->Periodo);
				
				if($periodo!=null)
				{
					$cliente	= $this->iexe->obtenerClientesMatricula($row->Matricula);
				
					if($cliente!=null)
					{
						if($this->crm->comprobarPeriodo($cliente->idCliente,$periodo->idPeriodo)==0)
						{
							$this->iexe->registrarPeriodoAlumno($cliente->idCliente,$periodo->idPeriodo);
						}
					}
				}
				
			}
		}
		
		redirect('clientes','refresh');
	}
	
	//REGRESAR ALUMNOS BAJAS
	public function regresarAlumnosBajas()
	{
		error_reporting(0);
		ini_set("memory_limit","1500M");
		set_time_limit(0); 

		$this->iexe->regresarAlumnosBajas();
		
		redirect('clientes','refresh');
	}
	
	public function asignarAlumnoPrograma()
	{
		error_reporting(0);
		ini_set("memory_limit","1500M");
		set_time_limit(0); 

		$this->iexe->asignarAlumnoPrograma();
		
		redirect('clientes','refresh');
	}
	
	//REVISAR ALUMNOS Y COTEJAR CON REDISOFT
	public function procesarConsultasAlumnos()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		 $this->iexe->procesarConsultasAlumnos();
	}
	
	public function revisarAlumnosIexe()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		 $this->iexe->revisarAlumnosIexe();
	}
	
	public function simularProcesarConsultasAlumnos()
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		 $this->iexe->simularProcesarConsultasAlumnos();
	}
}
