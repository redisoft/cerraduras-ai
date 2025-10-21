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
$excel->setActiveSheetIndex(0)->mergeCells('A'.$m.':D'.$m.'');

$excel->getActiveSheet()->getStyle('A'.$m.':D'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':D'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':D'.$m)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$m, 'REPORTE DE PRONÓSTICO DE PAGOS');
$excel->getActiveSheet()->getStyle('A'.$m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$m++;

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

$excel->getActiveSheet()->getStyle('A'.$m.':D'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':D'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':D'.$m)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('C'.$m, 'TOTAL');
$excel->getActiveSheet()->setCellValue('D'.$m, $gastos);

$m++;

$excel->getActiveSheet()->getStyle('A'.$m.':D'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':D'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':D'.$m)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$m, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$m, 'Proveedor');
$excel->getActiveSheet()->setCellValue('C'.$m, 'Concepto');
$excel->getActiveSheet()->setCellValue('D'.$m, 'Importe');



$i		=$m+1;

foreach($pronostico as $row)
{
	$proveedor	=$this->proveedores->obtenerProveedor($row->idProveedor);
	
	#$excel->getActiveSheet()->setCellValue('A'.$i, $row->fecha);
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $proveedor!=null?$proveedor->empresa:'');
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->producto);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->pago);


	$i++;
}

$fichero= rand(10000000,99999999);

$excel->getActiveSheet()->setTitle('Reportes');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;