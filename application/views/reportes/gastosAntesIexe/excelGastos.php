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

$i=1;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':K'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE DE EGRESOS');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setSize(9);
$excel->getActiveSheet()->setCellValue('J'.$i, 'TOTAL');
$excel->getActiveSheet()->setCellValue('K'.$i, $sumaGastos);
$excel->getActiveSheet()->getStyle('K'.$i)->getNumberFormat()->setFormatCode('$0.00');

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);


$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Proveedor');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Concepto');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Descripción del producto');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Departamento');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Tipo');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Factura');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Remisión');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Subtotal');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Iva');
$excel->getActiveSheet()->setCellValue('K'.$i, 'Importe');


$i++;

foreach($gastos as $row)
{
	$proveedor		=$this->proveedores->obtenerProveedor($row->idProveedor);
	$producto		=$this->configuracion->obtenerProducto($row->idProducto);
	$departamento	=$this->configuracion->obtenerDepartamento($row->idDepartamento);
	$gasto			=$this->configuracion->obtenerGasto($row->idGasto);
	
	#$excel->getActiveSheet()->setCellValue('A'.$i, obtenerFechaMesCortoHora($row->fecha));
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $proveedor!=null?$proveedor->empresa:'');
	$excel->getActiveSheet()->setCellValue('C'.$i, $producto!=null?$producto->nombre:'');
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->producto);
	$excel->getActiveSheet()->setCellValue('E'.$i, $departamento!=null?$departamento->nombre:'');
	$excel->getActiveSheet()->setCellValue('F'.$i, $gasto!=null?$gasto->nombre:'');
	$excel->getActiveSheet()->setCellValue('G'.$i, $row->factura);
	$excel->getActiveSheet()->setCellValue('H'.$i, $row->remision);
	$excel->getActiveSheet()->setCellValue('I'.$i, $row->subTotal);
	$excel->getActiveSheet()->setCellValue('J'.$i, $row->ivaTotal);
	$excel->getActiveSheet()->setCellValue('K'.$i, $row->pago);
	
	$excel->getActiveSheet()->getStyle('I'.$i.':K'.$i)->getNumberFormat()->setFormatCode('$0.00');

	$i++;
}

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Reporte de egresos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;
?>