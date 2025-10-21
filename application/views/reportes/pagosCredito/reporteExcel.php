<?php
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
	
$excel = new PHPExcel();

$excel
->getProperties()
->setCreator("Redisoftsystem")
->setLastModifiedBy("Redisoftsystem")
->setTitle("Redisoftsystem")
->setSubject("Redisoftsystem")
->setDescription("Redisoftsystem")
->setKeywords("Redisoftsystem")
->setCategory("Redisoftsystem");

$m=1;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$m.':I'.$m.'');

$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$m, 'REPORTE DE PAGO CRÉDITOS');
$excel->getActiveSheet()->getStyle('A'.$m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$m++;

$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('H'.$m, 'TOTAL');
$excel->getActiveSheet()->setCellValue('I'.$m, $totales);
$excel->getActiveSheet()->getStyle('I'.$m)->getNumberFormat()->setFormatCode('$0.00');


$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);


$m++;

$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$m, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$m, 'Cliente');
$excel->getActiveSheet()->setCellValue('C'.$m, 'Nota');
$excel->getActiveSheet()->setCellValue('D'.$m, 'Forma de pago');
$excel->getActiveSheet()->setCellValue('E'.$m, 'Banco');
$excel->getActiveSheet()->setCellValue('F'.$m, 'Cuenta');
$excel->getActiveSheet()->setCellValue('G'.$m, 'Factura');
$excel->getActiveSheet()->setCellValue('H'.$m, 'Total venta');
$excel->getActiveSheet()->setCellValue('I'.$m, 'Pago');


$i		=$m+1;

foreach($ingresos as $row)
{
	$factura	= $this->facturacion->obtenerFacturaCancelar($row->idFactura);
	$banco 		= explode('|',$row->banco);
	

	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->cliente);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->estacion.'-'.$row->folio);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->forma);
	$excel->getActiveSheet()->setCellValue('E'.$i, strlen($row->banco)>3?$banco[1]:'');
	$excel->getActiveSheet()->setCellValue('F'.$i, strlen($row->banco)>3?$banco[0]:'');
	$excel->getActiveSheet()->setCellValue('G'.$i, ($factura!=null?$factura->cfdi:$row->factura));

	$excel->getActiveSheet()->setCellValue('H'.$i, $row->total);
	$excel->getActiveSheet()->setCellValue('I'.$i, $row->pago);

	$excel->getActiveSheet()->getStyle('H'.$i.':I'.$i)->getNumberFormat()->setFormatCode('$0.00');

	$i++;
}

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Reportes');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;