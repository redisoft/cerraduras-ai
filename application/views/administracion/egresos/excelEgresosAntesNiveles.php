<?php
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
	
$excel = new PHPExcel();

$excel
->getProperties()
->setCreator(empresa)
->setLastModifiedBy(empresa)
->setTitle(empresa)
->setSubject(empresa)
->setDescription(empresa)
->setKeywords(empresa)
->setCategory(empresa);

$i=1;


$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':L'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Egresos');
$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->setSize(11);

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(24);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(27);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(28);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Proveedor');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Concepto');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Monto');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Forma de pago');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Cuenta');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Cheque / Transferencia');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Nombre');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Departamento');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Descripción del producto');
$excel->getActiveSheet()->setCellValue('K'.$i, 'Tipo');
$excel->getActiveSheet()->setCellValue('L'.$i, 'Comentarios');

$i++;

foreach($egresos as $row)
{
	$proveedor		= $this->proveedores->obtenerProveedor($row->idProveedor);
	$producto		= $this->configuracion->obtenerProducto($row->idProducto);
	
	$nombre			= $this->administracion->obtenerNombre($row->idNombre);
	$departamento	= $this->configuracion->obtenerDepartamento($row->idDepartamento);
	$gasto			= $this->configuracion->obtenerGasto($row->idGasto);
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($proveedor!=null?$proveedor->empresa:'', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($producto!=null?$producto->nombre:'', PHPExcel_Cell_DataType::TYPE_STRING);
	#$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->pago, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->pago);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->formaPago, PHPExcel_Cell_DataType::TYPE_STRING);
	
	if($row->idCuenta>0)
	{
		$cuenta		= $this->bancos->obtenerCuenta($row->idCuenta);
		#echo $cuenta->cuenta.'<br />'.$cuenta->banco;
		$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($cuenta->cuenta."\n".$cuenta->banco, PHPExcel_Cell_DataType::TYPE_STRING);
	}

	$excel->getActiveSheet()->getCell('G'.$i)->setValueExplicit($row->cheque.$row->transferencia, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('H'.$i)->setValueExplicit($nombre!=null?$nombre->nombre:'', PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->getCell('I'.$i)->setValueExplicit($departamento!=null?$departamento->nombre:'', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('J'.$i)->setValueExplicit($row->producto, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('K'.$i)->setValueExplicit($gasto!=null?$gasto->nombre:'', PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->getCell('L'.$i)->setValueExplicit($row->comentarios, PHPExcel_Cell_DataType::TYPE_STRING);

	$excel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
	$i++;
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$excel->getActiveSheet()->setTitle('Egresos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',carpetaImportar."Egresos.xls"));

echo 'Egresos';

?>
