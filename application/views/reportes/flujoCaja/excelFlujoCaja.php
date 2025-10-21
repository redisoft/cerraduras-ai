<?php
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
	
$excel = new PHPExcel();

$excel
->getProperties()
->setCreator("Maarten Balliauw")
->setLastModifiedBy("Maarten Balliauw")
->setTitle("Office 2007 XLSX Test Document")
->setSubject("Office 2007 XLSX Test Document")
->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
->setKeywords("office 2007 openxml php")
->setCategory("Test result file");

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);

//PARA LOS SALDOS INICIALES
$i=1;
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');
$excel->getActiveSheet()->setCellValue('A'.$i, "Saldo inicial");

$sumaCajas	=0;
$i++;
foreach($cajas as $row)
{
	$ingreso		=$this->reportes->obtenerIngresoCajaChica($row->idProducto,$mes,$anio);
	$egreso			=$this->reportes->obtenerEgresoCajaChica($row->idProducto,$mes,$anio);
	$saldoInicial	=$ingreso-$egreso;
	
	$sumaCajas	+=$saldoInicial;
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->cajaChica, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $saldoInicial);
	$i++;
}


//PARA LAS ENTRADAS EN CAJA CHICA
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');
$excel->getActiveSheet()->setCellValue('A'.$i, "Entradas en caja chica");

$i++;
foreach($cajas as $row)
{
	$entradas		=$this->administracion->obtenerEntradasCaja($row->idProducto,$mes,$anio);
	#$egreso			=$this->reportes->obtenerEgresoCajaChica($row->idProducto,$mes,$anio);
	#$saldoInicial	=$ingreso-$egreso;

	$sumaCajas	+=$entradas;
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->cajaChica, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $entradas);
	$i++;
}

$excel->getActiveSheet()->setCellValue('A'.$i, 'Suma de entradas en caja');
$excel->getActiveSheet()->setCellValue('C'.$i, $sumaCajas);

//PARA LAS SALIDAS EN CAJA CHICA
$i++;
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');
$excel->getActiveSheet()->setCellValue('A'.$i, "Salidas en caja chica");

$i++;
$salidas	=$this->administracion->obtenerSalidasCaja($mes,$anio);
$salida		=0;
foreach($salidas as $row)
{
	$salida	+=$row->importe;
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->concepto, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->importe);

	$i++;
}

$excel->getActiveSheet()->setCellValue('A'.$i, 'Suma de salidas en caja');
$excel->getActiveSheet()->setCellValue('C'.$i, $salida);

$i++;
$excel->getActiveSheet()->setCellValue('A'.$i, 'Entradas menos salidas');
$excel->getActiveSheet()->setCellValue('C'.$i, $sumaCajas-$salida);

//PARA LOS SALDOS FINALES EN CAJA CHICA
$i++;
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');
$excel->getActiveSheet()->setCellValue('A'.$i, "Saldos en caja");

$i++;
$saldos	=0;
$saldo	=0;
foreach($cajas as $row)
{
	$entradas		=$this->administracion->obtenerEntradasCaja($row->idProducto,$mes,$anio);
	$salida			=$this->administracion->obtenerSalidaCaja($mes,$anio,$row->idProducto);
	#$saldo	=$row->pago-$saldo;
	
	$ingreso		=$this->reportes->obtenerIngresoCajaChica($row->idProducto,$mes,$anio);
	$egreso			=$this->reportes->obtenerEgresoCajaChica($row->idProducto,$mes,$anio);
	$saldo			=$ingreso-$egreso;

	$saldo			=$entradas+$saldo-$salida;
	#$sumaCajas	+=$saldoInicial;
	
	$saldos			+=$saldo;
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->cajaChica, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $saldo);
	$i++;
}

$excel->getActiveSheet()->setCellValue('A'.$i, 'Suma de saldos en caja');
$excel->getActiveSheet()->setCellValue('C'.$i, $saldos);

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Flujo caja');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>