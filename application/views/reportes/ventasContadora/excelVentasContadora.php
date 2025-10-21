<?php
$cantidad	= 0;
$importe	= 0;

foreach($ventas as $row)
{
	$cantidad	+=$row->cantidad;
	$importe	+=$row->importe;
}

PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
	
$excel = new PHPExcel();

$excel
->getProperties()
->setCreator("Maarten Balliauw")
->setLastModifiedBy("Maarten Balliauw")
->setTitle("Office 2007 XLSX Test Document")
->setSubject("Office 2007 XLSX Test Document")
->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
->setKeywords("office 2007 openxml php")
->setCategory("Test result file");

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);

$i=1;

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha: '.obtenerFechaMesCorto(date('Y-m-d')));
$excel->getActiveSheet()->setCellValue('B'.$i, 'Fecha inicial: '.obtenerFechaMesCorto($inicio));
$excel->getActiveSheet()->setCellValue('C'.$i, 'Fecha final: '.obtenerFechaMesCorto($fin));

$i++;

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, $configuracion->nombre);

$i++;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Ventas por departamento');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Departamento');
$excel->getActiveSheet()->setCellValue('B'.$i, "Cantidad \n Total: ".number_format($cantidad,decimales));
$excel->getActiveSheet()->setCellValue('C'.$i, "Total \n Total: $".number_format($importe,decimales));

$i++;
foreach($ventas as $row)
{
	
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->departamento, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->cantidad);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->importe);

	$excel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode('$0.000');
	$i++;
}

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Ventas contadora');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;
?>
