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
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'DEPÓSITOS - '.obtenerFechaMesAnio($fecha));
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getRowDimension('2')->setRowHeight(50);
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':D'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$datos="";
$datos.=$emisor!=null?"\n ".$emisor->nombre:'';
$datos.=$cuenta!=null?"\n ".$cuenta->banco.': '.$cuenta->cuenta:'';
$datos.=$emisor!=null?"\n ".$emisor->rfc:'';

#$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($datos, PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValue('A'.$i, $datos);
$excel->getActiveSheet()->setCellValue('E'.$i, 'Total: '.number_format($totales,3));

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Cliente');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Forma de pago');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Factura');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Importe');

$i++;

foreach($depositos as $row)
{
	$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->cliente, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->formaPago, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->factura, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('E'.$i, $row->pago);

	$excel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('0.000');

	$i++;
}

$fichero	= rand(40,50);

$excel->getActiveSheet()->setTitle('Depósitos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;