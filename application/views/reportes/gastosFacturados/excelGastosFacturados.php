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

$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Gastos facturados - '.obtenerFechaMesAnio($fecha));
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(9);

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$excel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$datos="";
$datos.=$emisor!=null?"\n ".$emisor->nombre:'';
$datos.=$emisor!=null?"\n ".$emisor->rfc:'';


$excel->getActiveSheet()->setCellValue('A'.$i, $datos);

#$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($datos, PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValue('G'.$i, ''.number_format($totales[1],2));
$excel->getActiveSheet()->setCellValue('H'.$i, ''.number_format($totales[0],2));

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Fecha pago');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Emisor');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Proveedor');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Factura');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Subtotal');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Iva');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Total');

$i++;

foreach($gastos as $row)
{
	$iva			= 1+($row->iva/100);
	$subTotal		= $row->pago/$iva;
	$iva			= $row->pago-$subTotal;
			
	$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	#$excel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->emisor, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->empresa, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->factura, PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->setCellValue('F'.$i, $subTotal);
	$excel->getActiveSheet()->setCellValue('G'.$i, $iva);
	$excel->getActiveSheet()->setCellValue('H'.$i, $row->pago);

	$excel->getActiveSheet()->getStyle('F'.$i.':H'.$i)->getNumberFormat()->setFormatCode('$0.000');

	$i++;
}

$fichero	= rand(40,50);

$excel->getActiveSheet()->setTitle('Gastos facturados');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;