<?php
error_reporting(0);
$file = carpetaFicheros."formatoProduccion.xls";

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
		$producto['nombre']			= trim($data->val($row,1));
		$producto['descripcion']	= trim($data->val($row,2));
		
		$producto['idUnidad']		= $this->importar->obtenerUnidad(trim($data->val($row,3)));
		$producto['codigoInterno']	= trim($data->val($row,4));
		$producto['codigoBarras']	= trim($data->val($row,5));
		#$producto['costo']			= trim($data->val($row,5));
		$producto['sku']			= trim($data->val($row,6));		
		$producto['upc']			= trim($data->val($row,7));	
		$producto['precioA']		= trim($data->val($row,8));	
		
		$producto['idDepartamento']	= $this->catalogos->registrarCatalogoNombre(trim($data->val($row,10)),'departamentos');
		$producto['idMarca']		= $this->catalogos->registrarCatalogoNombre(trim($data->val($row,11)),'marcas');
		
		$producto['stock']			= trim($data->val($row,12));
		$producto['idImpuesto']		= $this->configuracion->obtenerImpuestoTasa(trim($data->val($row,13)));
		
		$producto['idLinea']		= $this->importar->obtenerLinea(trim($data->val($row,9)));	
		$producto['fecha']			= $fecha;
		$producto['idUsuario']		= $idUsuario;
		$producto['reventa']		= 0;
		$producto['idPeriodo']		= 1;
		
		$produccion['nombre']		= trim($data->val($row,1));
		/*$produccion['unidad']		= trim($data->val($row,2));
		$produccion['codigoInterno']= trim($data->val($row,3));
		$produccion['costo']		= 0;
		$produccion['sku']			= trim($data->val($row,5));		
		$produccion['upc']			= trim($data->val($row,6));	
		$produccion['precioA']		= trim($data->val($row,7));	
		$produccion['idLinea']		= $idLinea;	
		$produccion['fechaRegistro']= $fecha;
		$produccion['reventa']		= 0;*/

		$this->importar->registrarProduccion($producto,$produccion);
	}
	
	$this->configuracion->registrarBitacora('Importar productos','Explosión de materiales',''); //Registrar bitácora
	
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
		
		$this->session->set_userdata('notificacion','Los productos se han importado correctamente');
		echo "1";
	}
	
}