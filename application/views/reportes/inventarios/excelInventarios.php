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
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':J'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Inventario productos');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Total: $'.number_format($total,2));

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getFont()->setName('Arial Black');

$excel->getActiveSheet()->setCellValue('A'.$i, 'Código');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Artículo');
$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit('U. Medida', PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValue('D'.$i, 'Línea');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Existencia');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Costo unitario');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Valor total');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Precio 1');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Precio venta');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Precio mayoreo');


$i++;
$total=0;

foreach($inventarios as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->codigoInterno, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->producto, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->unidad);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->linea);
	
	$excel->getActiveSheet()->setCellValue('E'.$i, $row->stock);
	$excel->getActiveSheet()->setCellValue('F'.$i, $row->precioA);
	$excel->getActiveSheet()->setCellValue('G'.$i, $row->precioA*$row->stock);
	$excel->getActiveSheet()->setCellValue('H'.$i, $row->precioC);
	$excel->getActiveSheet()->setCellValue('I'.$i, $row->precioA);
	$excel->getActiveSheet()->setCellValue('J'.$i, $row->precioB);

	$excel->getActiveSheet()->getStyle('F'.$i.':J'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
	$i++;
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Inventario productos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>
