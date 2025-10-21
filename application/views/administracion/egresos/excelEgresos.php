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


$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':M'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':M'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Egresos');
$excel->getActiveSheet()->getStyle('A'.$i.':M'.$i)->getFont()->setSize(11);

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':M'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':M'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(24);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(27);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(25);


$excel->getActiveSheet()->setCellValue('A'.$i, 'ID');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Proveedor');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Nivel 1');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Monto');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Forma de pago');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Cuenta');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Cheque / Transferencia');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Nombre');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Nivel 2');
$excel->getActiveSheet()->setCellValue('K'.$i, 'Descripción del producto');
$excel->getActiveSheet()->setCellValue('L'.$i, 'Nivel 3');
$excel->getActiveSheet()->setCellValue('M'.$i, 'Comentarios');

$i++;

foreach($egresos as $row)
{
	$proveedor		= $this->proveedores->obtenerProveedor($row->idProveedor);
	$producto		= $this->configuracion->obtenerProducto($row->idProducto);

	$excel->getActiveSheet()->setCellValue('A'.$i, $row->idEgreso);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($proveedor!=null?$proveedor->empresa:'', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->nivel1, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('E'.$i, $row->pago);
	$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($row->formaPago, PHPExcel_Cell_DataType::TYPE_STRING);
	
	if($row->idCuenta>0)
	{
		$cuenta		= $this->bancos->obtenerCuenta($row->idCuenta);
		#echo $cuenta->cuenta.'<br />'.$cuenta->banco;
		$excel->getActiveSheet()->getCell('G'.$i)->setValueExplicit($cuenta->cuenta."\n".$cuenta->banco, PHPExcel_Cell_DataType::TYPE_STRING);
	}

	$excel->getActiveSheet()->getCell('H'.$i)->setValueExplicit($row->cheque.$row->transferencia, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('I'.$i)->setValueExplicit($nombre!=null?$nombre->nombre:'', PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->getCell('J'.$i)->setValueExplicit($row->nivel2, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('K'.$i)->setValueExplicit($row->producto, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('L'.$i)->setValueExplicit($row->nivel3, PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->getCell('M'.$i)->setValueExplicit($row->comentarios, PHPExcel_Cell_DataType::TYPE_STRING);

	$excel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
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
