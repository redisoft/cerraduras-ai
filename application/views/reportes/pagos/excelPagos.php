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
$excel->setActiveSheetIndex(0)->mergeCells('A'.$m.':F'.$m.'');

$excel->getActiveSheet()->getStyle('A'.$m.':F'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':F'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':F'.$m)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$m, 'REPORTE DE PAGOS');
$excel->getActiveSheet()->getStyle('A'.$m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$m++;
$excel->getActiveSheet()->getStyle('F'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('F'.$m)->getFont()->setSize(9);
$excel->getActiveSheet()->setCellValue('F'.$m, 'Total: '.$totalCompras);
$excel->getActiveSheet()->getStyle('F'.$m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

$m++;

$excel->getActiveSheet()->getStyle('A'.$m.':F'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':F'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':F'.$m)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$m, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$m, 'Proveedor');
$excel->getActiveSheet()->setCellValue('C'.$m, 'Descripción');
$excel->getActiveSheet()->setCellValue('D'.$m, 'Fecha de vencimiento');
$excel->getActiveSheet()->setCellValue('E'.$m, 'Días de vencimiento');

$excel->getActiveSheet()->setCellValue('F'.$m, 'Saldo');

$i		=$m+1;

foreach($compras as $row)
{
	$pagado		=$this->reportes->obtenerPagadoCompra($row->idCompras);
	
	$fecha	=$this->reportes->obtenerFechaFin($row->fechaCompra,$row->diasCredito);
	$dias	=$this->reportes->obtenerDiasRestantes($fecha);
		
	#$excel->getActiveSheet()->setCellValue('A'.$i, $row->fecha);
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaCompra), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->empresa);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->nombre);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit(obtenerFechaMesCorto($fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('E'.$i, $dias);
	
	$excel->getActiveSheet()->setCellValue('F'.$i, $row->total-$pagado);


	$i++;
}

$fichero= rand(10000000,99999999);

$excel->getActiveSheet()->setTitle('Reportes');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;