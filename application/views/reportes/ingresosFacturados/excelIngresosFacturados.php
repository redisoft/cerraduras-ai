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
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':G'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'INGRESOS FACTURADOS - '.obtenerFechaMesAnio($fecha));
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getRowDimension('2')->setRowHeight(50);
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':D'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$datos="";
$datos.=$emisor!=null?"\n ".$emisor->nombre:'';
$datos.=$cuenta!=null?"\n ".$cuenta->banco.': '.$cuenta->cuenta:'';
$datos.=$emisor!=null?"\n ".$emisor->rfc:'';

$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($datos, PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValue('G'.$i, 'Total: '.number_format($totales,3));

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Fecha de pago');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Cliente');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Factura');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Subtotal');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Iva');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Total');

$i++;

foreach($ingresos as $row)
{
	$subTotal	= $row->pago/(1+$row->iva);
	$iva		= $row->iva*$subTotal;
			
	$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaFactura), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->cliente, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit(strlen($row->facturaIngreso)>0?$row->facturaIngreso:$row->factura, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('E'.$i, $subTotal);
	$excel->getActiveSheet()->setCellValue('F'.$i, $iva);
	$excel->getActiveSheet()->setCellValue('G'.$i, $row->pago);

	$excel->getActiveSheet()->getStyle('E'.$i.':G'.$i)->getNumberFormat()->setFormatCode('$0.000');

	$i++;
}

$fichero	= rand(40,50);

$excel->getActiveSheet()->setTitle('Ingresos facturados');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;