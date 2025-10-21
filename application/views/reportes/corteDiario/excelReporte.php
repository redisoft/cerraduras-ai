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
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(28);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

$i=1;
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getFont()->setName('Arial Black');
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':J'.$i);
$excel->getActiveSheet()->setCellValue('A'.$i, 'Reporte de envíos');

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Total: $'.number_format($totalCobranza,2));

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Cliente');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Ruta');

$excel->getActiveSheet()->setCellValue('D'.$i, 'Teléfono');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Venta');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Factura');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Fecha vencimiento');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Días de vencimiento');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Importe');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Saldo');


$i++;
$total=0;

foreach($ventas as $row)
{
	$dias		=0;

	if($row->idFactura>0)
	{
		$dias	=$this->reportes->obtenerDiasRestantes($row->fechaVencimiento);
	}

	$dias		=$dias<0?($dias):$dias;

	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaCompra), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->empresa);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->ruta);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->telefono);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->ordenCompra, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($row->factura, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('G'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaVencimiento), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('H'.$i, $dias);
	$excel->getActiveSheet()->setCellValue('I'.$i, $row->total);
	$excel->getActiveSheet()->setCellValue('J'.$i, $row->saldo);
	
	$i++;
}


$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Envíos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>