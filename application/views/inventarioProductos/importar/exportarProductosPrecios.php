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


$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':T'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':T'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Productos');
$excel->getActiveSheet()->getStyle('A'.$i.':T'.$i)->getFont()->setSize(11);


$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':T'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':T'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('T')->setWidth(50);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Código barras');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Código interno');
$excel->getActiveSheet()->setCellValue('C'.$i, 'SKU');
$excel->getActiveSheet()->setCellValue('D'.$i, 'UPC');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Producto');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Descripción');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Unidad');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Clave producto');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Línea');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Sublinea');
$excel->getActiveSheet()->setCellValue('K'.$i, 'Departamento');
$excel->getActiveSheet()->setCellValue('L'.$i, 'Marca');
$excel->getActiveSheet()->setCellValue('M'.$i, 'Stock');
$excel->getActiveSheet()->setCellValue('N'.$i, obtenerNombrePrecio(1));
$excel->getActiveSheet()->setCellValue('O'.$i, obtenerNombrePrecio(2));
$excel->getActiveSheet()->setCellValue('P'.$i, obtenerNombrePrecio(3));
$excel->getActiveSheet()->setCellValue('Q'.$i, obtenerNombrePrecio(4));
$excel->getActiveSheet()->setCellValue('R'.$i, obtenerNombrePrecio(5));


$excel->getActiveSheet()->setCellValue('S'.$i, 'Costo');
$excel->getActiveSheet()->setCellValue('T'.$i, 'Proveedor');


$i++;

foreach($productos as $row)
{
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->codigoBarras, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->codigoInterno, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->sku, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->upc, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->nombre, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($row->descripcion, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('G'.$i)->setValueExplicit($row->unidad, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('H'.$i)->setValueExplicit($row->claveProducto, PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->getCell('I'.$i)->setValueExplicit($row->linea, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('J'.$i)->setValueExplicit($row->sublinea, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('K'.$i)->setValueExplicit($row->departamento, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('L'.$i)->setValueExplicit($row->marca, PHPExcel_Cell_DataType::TYPE_STRING);

	$excel->getActiveSheet()->setCellValue('M'.$i, $row->stock);
	$excel->getActiveSheet()->setCellValue('N'.$i, $row->precioA);
	$excel->getActiveSheet()->setCellValue('O'.$i, $row->precioB);
	$excel->getActiveSheet()->setCellValue('P'.$i, $row->precioC);
	$excel->getActiveSheet()->setCellValue('Q'.$i, $row->precioD);
	$excel->getActiveSheet()->setCellValue('R'.$i, $row->precioE);
	
	$excel->getActiveSheet()->setCellValue('S'.$i, $row->costo);
	$excel->getActiveSheet()->setCellValue('T'.$i, $row->proveedor);

	$excel->getActiveSheet()->getStyle('M'.$i)->getNumberFormat()->setFormatCode('0.00');
	$excel->getActiveSheet()->getStyle('N'.$i.':S'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
	$i++;
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$excel->getActiveSheet()->setTitle('Productos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',carpetaImportar."Productos.xls"));

echo 'Productos';

?>