<?php
error_reporting(0);
$file = carpetaFicheros."formatoMateriales.xls";

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
		$material['nombre']			= trim($data->val($row,1));
		$material['idUnidad']		= $this->importar->obtenerUnidad(trim($data->val($row,2)));
		$material['codigoInterno']	= trim($data->val($row,3));
		$material['costo']			= trim($data->val($row,4));
		$material['stockMinimo']	= trim($data->val($row,6));		
		$material['fechaRegistro']	= $fecha;
		
		$proveedor['idProveedor']	= $this->importar->obtenerProveedor(trim($data->val($row,5)));
		$proveedor['costo']			= trim($data->val($row,4));

		$this->importar->registrarMaterial($material,$proveedor);
	}
	
	
	$this->configuracion->registrarBitacora('Importar materia prima','Materia prima'); //Registrar bitácora
	
	if ($this->db->trans_status() === FALSE)
	{
		$this->db->trans_rollback(); 
		$this->db->trans_complete();
		
		echo "0";
	}
	else
	{
		$this->db->trans_commit();
		$this->db->trans_complete();
		
		#$this->session->set_userdata('notificacion','Los materiales se han importado correctamente');
		echo "1";
	}
	
}