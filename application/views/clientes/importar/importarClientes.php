<?php
error_reporting(0);
$file = carpetaFicheros."formatoClientes.xls";

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
		$cliente['empresa']			= $data->val($row,1);
		$cliente['rfc']				= $data->val($row,2);
		$cliente['calle']			= $data->val($row,3);
		$cliente['numero']			= $data->val($row,4);
		$cliente['colonia']			= $data->val($row,5);
		$cliente['codigoPostal']	= $data->val($row,6);
		$cliente['localidad']		= $data->val($row,7);
		$cliente['municipio']		= $data->val($row,8);
		$cliente['estado']			= $data->val($row,9);
		$cliente['pais']			= $data->val($row,10);
		$cliente['lada']			= $data->val($row,11);
		$cliente['telefono']		= $data->val($row,12);
		$cliente['email']			= $data->val($row,13);
		$cliente['web']				= $data->val($row,14);
		$cliente['serviciosProductos']	= $data->val($row,15);
		
		$cliente['idUsuario']		= $idUsuario;
		$cliente['fechaRegistro']	= $fecha;
		
		$contacto['nombre']			= $data->val($row,16);
		$contacto['lada']			= $data->val($row,17);
		$contacto['telefono']		= $data->val($row,18);
		$contacto['email']			= $data->val($row,19);
		$contacto['direccion']		= $data->val($row,21);
		$contacto['puesto']			= $data->val($row,21);
		$contacto['fechaRegistro']	= $fecha;

		$this->importar->registrarCliente($cliente,$contacto);
	}
	
	$this->configuracion->registrarBitacora('Importar clientes','Clientes'); //Registrar bitácora
	
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
		
		$this->session->set_userdata('notificacion','Los clientes se han importado correctamente');
		echo "1";
	}
	
}