<?php
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
	
$excel = new PHPExcel();

$excel
->getProperties()
->setCreator("Redisoftsystem")
->setLastModifiedBy("Redisoftsystem")
->setTitle("Redisoftsystem")
->setSubject("Redisoftsystem")
->setDescription("Redisoftsystem")
->setKeywords("Redisoftsystem")
->setCategory("Redisoftsystem");

$i=1;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':D'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE DE PRONÓSTICO DE COBROS');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

$excel->getActiveSheet()->setCellValue('C'.$i, 'TOTAL:');
$excel->getActiveSheet()->setCellValue('D'.$i, $ingresos);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Cliente');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Concepto');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Importe');

$i++;
foreach($pronostico as $row)
{
	$cliente	=$this->clientes->obtenerCliente($row->idCliente);
	
	#$excel->getActiveSheet()->setCellValue('A'.$i, $row->fecha);
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $cliente!=null?$cliente->empresa:'');
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->producto);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->pago);


	$i++;
}

$fichero= rand(10000000,99999999);

$excel->getActiveSheet()->setTitle('Reportes');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;