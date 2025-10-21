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

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(34);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(17);

$i=1;

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'PAGOS');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Alumno');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Programa');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Concepto');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Importe');



$i++;

foreach($pagos as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->alumno, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->programa, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->producto, PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->setCellValue('E'.$i, $row->pago);
	
	$excel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
	$i++;
}

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Pagos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;
?>
