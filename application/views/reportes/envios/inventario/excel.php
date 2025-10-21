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
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(40);


$i=1;
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':H'.$i);
$excel->getActiveSheet()->setCellValue('A'.$i, 'Inventario entregas');


$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Nota');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Folio');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Código interno');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Producto');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Entregado');
$excel->getActiveSheet()->setCellValue('G'.$i, 'No entregado');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Comentarios');

$excel->getActiveSheet()->getStyle('F'.$i.':G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;
foreach($registros as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fechaCompra), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->estacion.$row->folio, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->folioTicket);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->codigoInterno, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->producto, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('F'.$i, $row->cantidadEntregada);
	$excel->getActiveSheet()->setCellValue('G'.$i, ($row->noEntregados));
	$excel->getActiveSheet()->getCell('H'.$i)->setValueExplicit($row->comentarios, PHPExcel_Cell_DataType::TYPE_STRING);

	$excel->getActiveSheet()->getStyle('F'.$i.':G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$i++;
}

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Inventario entregas');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>
