<?php
#error_reporting(0);
$file = carpetaFicheros."importarEgresos.xls";

$data_['success'] = FALSE;
$this->load->library('excel_reader2');

$data = new Excel_Reader2($file, false, 'UTF-8');

if($data->status == TRUE)
{
	$this->db->trans_start();

	$rows = $data->rowcount($sheet_index=0);
	$cols = $data->colcount($sheet_index=0);

	$col=1;	  
	
	for($row = 3; $row <= $rows ; $row++)
	{
		$idEgreso					= $data->val($row,1);
		
		$niveles					= $this->importar->obtenerNiveles(trim($data->val($row,4)),trim($data->val($row,10)),trim($data->val($row,12)));

		$egreso['idNivel1']			= $niveles[0];
		$egreso['idNivel2']			= $niveles[1];
		$egreso['idNivel3']			= $niveles[2];
		
		$this->db->where('idEgreso',$idEgreso);
		$this->db->update('catalogos_egresos',$egreso);

	}

	if ($this->db->trans_status() === FALSE)
	{
		$this->db->trans_rollback(); 
		$this->db->trans_complete();
		
		#echo "0";
	}
	else
	{
		$this->db->trans_commit();
		$this->db->trans_complete();
		
		#$this->session->set_userdata('notificacion','Los prospectos se han importado correctamente');
		echo "1";
	}
	
}