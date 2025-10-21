<?php
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
	
$excel = new PHPExcel();

$excel
->getProperties()
->setCreator("Redisoft")
->setLastModifiedBy("Redisoft")
->setTitle("Redisoft")
->setSubject("Redisoft")
->setDescription("Redisoft")
->setKeywords("Redisoft")
->setCategory("Redisoft");

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(18);



$i=1;
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getFont()->setName('Arial Black');
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':J'.$i);
$excel->getActiveSheet()->setCellValue('A'.$i, 'Reporte de precio 1');

/*$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Total: $'.number_format($totalCobranza,2));*/

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Cliente');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Venta');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Estación');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Producto');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Agente');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Forma de pago');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Subtotal');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Impuestos');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Total');

$i++;

foreach($registros as $row)
{
	$impuestos	= $row->importe*($row->ivaPorcentaje/100);

	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaCompra), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->empresa, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->folio);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->estacion, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->producto, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($row->usuario, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('G'.$i)->setValueExplicit($row->formaPago, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('H'.$i, $row->importe);
	$excel->getActiveSheet()->setCellValue('I'.$i, $impuestos);
	$excel->getActiveSheet()->setCellValue('J'.$i, $row->importe+$impuestos);
	
	$excel->getActiveSheet()->getStyle('H'.$i.':J'.$i)->getNumberFormat()->setFormatCode('$0.000');
	
	$i++;
}


$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Precio 1');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>
