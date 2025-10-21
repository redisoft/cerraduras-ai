<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#=======================CONSTRUCTOR================================
class Inicio extends CI_CI_Controller 
{
	function __construct() 
	{
		parent::__construct();
		
		$this->load->model('inicio_puesto','puestos');
  	}

	public function formularioPuesto()
	{
		$this->load->view('puestos/agregarPuesto');
	}
	
	public function agregarPuesto()
	{
		$alumno=$this->inicio->agregarPuesto();
		
		echo $puesto;
	}
	
}
  
