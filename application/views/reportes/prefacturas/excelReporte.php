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
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(18);


$i=1;
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setName('Arial Black');
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':F'.$i);
$excel->getActiveSheet()->setCellValue('A'.$i, 'Reporte de prefacturas');

/*$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Total: $'.number_format($totalCobranza,2));*/

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha prefactura');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Prefactura');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Fecha remisión');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Remisión');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Cliente');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Total');


$i++;
$total=0;

foreach($registros as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaCompra), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->folio);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaRemision), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->folioRemision);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->empresa, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('F'.$i, $row->total);
	
	$excel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode('$0.000');
	
	$i++;
}


$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Prefacturas');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>