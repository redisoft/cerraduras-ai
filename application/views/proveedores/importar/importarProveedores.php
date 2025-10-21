<?php
error_reporting(0);
$file = carpetaFicheros."formatoProveedores.xls";

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
		$proveedor['empresa']		= trim($data->val($row,1));
		$proveedor['rfc']			= trim($data->val($row,2));
		$proveedor['domicilio']		= trim($data->val($row,3));
		$proveedor['numero']		= trim($data->val($row,4));
		$proveedor['colonia']		= trim($data->val($row,5));
		$proveedor['codigoPostal']	= trim($data->val($row,6));
		$proveedor['localidad']		= trim($data->val($row,7));
		$proveedor['municipio']		= trim($data->val($row,8));
		$proveedor['estado']		= trim($data->val($row,9));
		$proveedor['pais']			= trim($data->val($row,10));
		$proveedor['lada']			= trim($data->val($row,11));
		$proveedor['telefono']		= trim($data->val($row,12));
		$proveedor['email']			= trim($data->val($row,13));
		$proveedor['website']		= trim($data->val($row,14));
		$proveedor['vende']			= trim($data->val($row,15));
		
		$proveedor['idUsuario']		= $idUsuario;
		$proveedor['fecha']			= $fecha;
		
		$contacto['nombre']			= strlen(trim($data->val($row,16)))>0?trim($data->val($row,16)):trim($data->val($row,1));
		$contacto['telefono']		= trim($data->val($row,17));
		$contacto['extension']		= trim($data->val($row,18));
		$contacto['email']			= trim($data->val($row,19));
		$contacto['departamento']	= trim($data->val($row,20));

		$this->importar->registrarProveedor($proveedor,$contacto);
	}
	
	$this->configuracion->registrarBitacora('Importar proveedores','Proveedores'); //Registrar bitácora
	
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
		
		$this->session->set_userdata('notificacion','Los proveedores se han importado correctamente');
		echo "1";
	}
	
}