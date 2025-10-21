<?php
error_reporting(0);
$file = carpetaFicheros."formatoProductos.xls";

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
		
		$precioImpuestos			=(float)trim($data->val($row,11));
		$impuesto					=(float)trim($data->val($row,17));
		
		$producto['nombre']			= trim($data->val($row,1));
		$producto['descripcion']	= trim($data->val($row,2));
		
		$producto['idUnidad']		= $this->importar->obtenerClaveUnidad(trim($data->val($row,3)));
		$producto['idClave']		= $this->importar->obtenerClaveProducto(trim($data->val($row,4)));
		

		$producto['codigoInterno']	= trim($data->val($row,5));
		$producto['codigoBarras']	= trim($data->val($row,6));

		$producto['sku']			= trim($data->val($row,9));		
		$producto['upc']			= trim($data->val($row,10));	
		
		#$producto['precioImpuestos']= $precioImpuestos;
		$producto['precioA']		= (float)trim($data->val($row,11));
		$producto['precioB']		= (float)trim($data->val($row,12));
		$producto['precioC']		= (float)trim($data->val($row,13));
		$producto['precioD']		= (float)trim($data->val($row,14));
		$producto['precioE']		= (float)trim($data->val($row,15));
		
		
		$producto['idDepartamento']	= $this->catalogos->registrarCatalogoNombre(trim($data->val($row,18)),'departamentos');
		$producto['idMarca']		= $this->catalogos->registrarCatalogoNombre(trim($data->val($row,19)),'marcas');
		
		$producto['stock']			= trim($data->val($row,20));
		$producto['idImpuesto']		= $this->configuracion->obtenerImpuestoTasa(trim($data->val($row,21)));
			
		$producto['idLinea']		= $this->importar->obtenerLinea(trim($data->val($row,16)));
		$producto['idSubLinea']		= $this->importar->obtenerSubLinea(trim($data->val($row,17)),$producto['idLinea']);
		
		$producto['fecha']			= $fecha;
		$producto['idUsuario']		= $idUsuario;
		$producto['reventa']		= 1;
		$producto['idPeriodo']		= 1;
		
		$produccion['nombre']		= trim($data->val($row,1));

		$proveedor['idProveedor']	= $this->importar->obtenerProveedor(trim($data->val($row,8)));
		$proveedor['precio']		= trim($data->val($row,7));

		$this->importar->registrarProducto($producto,$produccion,$proveedor);
	}
	
	$this->configuracion->registrarBitacora('Importar productos','Catálogo de productos'); //Registrar bitácora
	
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

		echo "1";
	}
	
}