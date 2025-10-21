<?php
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

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

$i=1;

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Ventas por línea de producto');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->setName('Arial Black');

$excel->getActiveSheet()->setCellValue('A'.$i, 'Departamento');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Cliente');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Venta');

$i++;
$cantidad	= 0;
$importe	= 0;;

foreach($ventas as $row)
{
	$cantidad	+= $row->cantidad;
	$importe	+= $row->importe;
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(($row->departamento), PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->cantidad);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->importe);

	$excel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode('0.00');
	$excel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode('$0.000');

	$i++;
}

$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit('Total', PHPExcel_Cell_DataType::TYPE_STRING);
	
$excel->getActiveSheet()->setCellValue('B'.$i, $cantidad);
$excel->getActiveSheet()->setCellValue('C'.$i, $importe);

$excel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode('0.00');
$excel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode('$0.000');


$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Ventas línea');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;
?>
