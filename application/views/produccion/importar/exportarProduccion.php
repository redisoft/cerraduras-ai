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


$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':J'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Producción');
$excel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->getFont()->setSize(11);


$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':N'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':N'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(45);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

$excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Código barras');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Código interno');
$excel->getActiveSheet()->setCellValue('C'.$i, 'SKU');
$excel->getActiveSheet()->setCellValue('D'.$i, 'UPC');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Producto');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Unidad');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Línea');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Stock');
$excel->getActiveSheet()->setCellValue('I'.$i, obtenerNombrePrecio(1));
$excel->getActiveSheet()->setCellValue('J'.$i, 'Costo');


$i++;

foreach($productos as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->codigoInterno, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->codigoBarras, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->sku, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->upc, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->nombre, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($row->unidad, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('G'.$i)->setValueExplicit($row->linea, PHPExcel_Cell_DataType::TYPE_STRING);

	$excel->getActiveSheet()->setCellValue('H'.$i, $row->stock);
	$excel->getActiveSheet()->setCellValue('I'.$i, $row->precioA);
	$excel->getActiveSheet()->setCellValue('J'.$i, $row->costo);

	$excel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode('0.00');
	$excel->getActiveSheet()->getStyle('I'.$i.':J'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
	$i++;
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$excel->getActiveSheet()->setTitle('Producción');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',carpetaImportar."Produccion.xls"));

echo 'Produccion';

?>