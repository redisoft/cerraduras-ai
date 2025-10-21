<?php
#error_reporting(0);
$file = carpetaFicheros."alumnos.xls";

$data_['success'] = FALSE;
$this->load->library('excel_reader2');

$data = new Excel_Reader2($file, false, 'UTF-8');

if($data->status == TRUE)
{

	$rows = $data->rowcount($sheet_index=0);
	$cols = $data->colcount($sheet_index=0);

	$col=1;	  
	
	for($row = 2; $row <= $rows ; $row++)
	{
		$cliente['matricula']			= $data->val($row,1);
		$cliente['nombre']				= $data->val($row,2);
		$cliente['apellido']			= $data->val($row,3);
		$cliente['email']				= $data->val($row,4);

		$this->db->insert('clientes_alumnos',$cliente);
	}

	$this->session->set_userdata('notificacion','Los clientes se han importado correctamente');
	
}