<?php
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
	
$excel = new PHPExcel();

$excel
->getProperties()
->setCreator("Textil Arte")
->setLastModifiedBy("Textil Arte")
->setTitle("Textil Arte")
->setSubject("Textil Arte")
->setDescription("Textil Arte")
->setKeywords("Textil Arte")
->setCategory("Textil Arte");

$i=1;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':H'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(11);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Ventas por producto');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Cliente');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Venta');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Producto');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Cantidad');
$excel->getActiveSheet()->setCellValue('F'.$i, 'PU');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Descuento');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Importe');

$i++;

foreach($ventas as $row)
{
	#$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	#$excel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->cliente, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->ordenCompra, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fechaCompra), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->producto, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('E'.$i, $row->cantidad);
	$excel->getActiveSheet()->setCellValue('F'.$i, $row->precio);
	$excel->getActiveSheet()->setCellValue('G'.$i, $row->descuento);
	$excel->getActiveSheet()->setCellValue('H'.$i, $row->importe);

	$excel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('0.000');
	$excel->getActiveSheet()->getStyle('F'.$i.':H'.$i)->getNumberFormat()->setFormatCode('$0.000');

	$i++;
}

$fichero	= rand(40,50);

$excel->getActiveSheet()->setTitle('Ventas producto');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;