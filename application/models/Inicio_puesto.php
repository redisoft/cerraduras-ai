<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#=======================CONSTRUCTOR======================================
class Inicio_CI_Modelo extends CI_CI_Model
{
	function __construct() 
	{
		parent::__construct();
  	}
#========================================================================	
	
	#======================AGREGAR ALUMNO================================
	public function agregarPuesto()
	{
		#$this->input->post('') =$_POST[]; Esto es igual
		
		$data=array
		(
			'nombre'		=>$this->input->post('nombre'),
			
		);
		
		$this->db->insert('recursos_Puestos',$data);
		
		return $this->db->affected_rows() >=1?"1":"0";
	}
	
	#====================================================================
	
	
	
	}
	