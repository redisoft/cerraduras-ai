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

$i=1;



$excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Inventario mobiliario / equipo');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Total: $'.number_format($total,2));

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');

$excel->getActiveSheet()->setCellValue('A'.$i, 'Artículo');
$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit('Proveedor', PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValue('C'.$i, 'Existencia');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Costo unitario');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Valor total');


$i++;
$total=0;

foreach($inventarios as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->nombre, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->empresa);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->cantidad);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->costo);
	$excel->getActiveSheet()->setCellValue('E'.$i, $row->costo*$row->cantidad);
	
	$i++;
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$fichero= rand(10000000,99999999);

$excel->getActiveSheet()->setTitle('Inventario mobiliario equipo');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>