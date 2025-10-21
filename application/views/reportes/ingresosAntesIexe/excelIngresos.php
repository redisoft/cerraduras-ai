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
$excel->setActiveSheetIndex(0)->mergeCells('A'.$m.':J'.$m.'');

$excel->getActiveSheet()->getStyle('A'.$m.':J'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':J'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':J'.$m)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$m, 'REPORTE DE INGRESOS');
$excel->getActiveSheet()->getStyle('A'.$m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$m++;

$excel->getActiveSheet()->getStyle('A'.$m.':J'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':J'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':J'.$m)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('I'.$m, 'TOTAL');
$excel->getActiveSheet()->setCellValue('J'.$m, $sumaIngresos);
$excel->getActiveSheet()->getStyle('J'.$m)->getNumberFormat()->setFormatCode('$0.00');


$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

$m++;

$excel->getActiveSheet()->getStyle('A'.$m.':J'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':J'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':J'.$m)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$m, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$m, 'Cliente');
$excel->getActiveSheet()->setCellValue('C'.$m, 'Concepto');
$excel->getActiveSheet()->setCellValue('D'.$m, 'Descripción del producto');
$excel->getActiveSheet()->setCellValue('E'.$m, 'Departamento');
$excel->getActiveSheet()->setCellValue('F'.$m, 'Tipo');
$excel->getActiveSheet()->setCellValue('G'.$m, 'Factura / Remisión');
$excel->getActiveSheet()->setCellValue('H'.$m, 'Subtotal');
$excel->getActiveSheet()->setCellValue('I'.$m, 'Iva');
$excel->getActiveSheet()->setCellValue('J'.$m, 'Total');

$i		=$m+1;

foreach($ingresos as $row)
{
	$producto		=$this->configuracion->obtenerProducto($row->idProducto);
	$departamento	=$this->configuracion->obtenerDepartamento($row->idDepartamento);
	$gasto			=$this->configuracion->obtenerGasto($row->idGasto);
	
	#$excel->getActiveSheet()->setCellValue('A'.$i, $row->fecha);
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->cliente);
	$excel->getActiveSheet()->setCellValue('C'.$i, $producto!=null?$producto->nombre:'');
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->producto);
	$excel->getActiveSheet()->setCellValue('E'.$i, $departamento!=null?$departamento->nombre:'');
	$excel->getActiveSheet()->setCellValue('F'.$i, $gasto!=null?$gasto->nombre:'');
	$excel->getActiveSheet()->setCellValue('G'.$i, $row->factura);
	$excel->getActiveSheet()->setCellValue('H'.$i, $row->subTotal);
	$excel->getActiveSheet()->setCellValue('I'.$i, $row->ivaTotal);
	$excel->getActiveSheet()->setCellValue('J'.$i, $row->pago);
	
	$excel->getActiveSheet()->getStyle('H'.$i.':J'.$i)->getNumberFormat()->setFormatCode('$0.00');

	$i++;
}

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Reportes');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;