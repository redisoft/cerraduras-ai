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

$i				=1;
$saldoInicial	=0;
#$excel->getActiveSheet()->getStyle('A'.$m)->getFill()->getStartColor()->setRGB('000000');

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':D'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'SALDOS INICIALES EN BANCOS');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;
foreach($cuentas as $row)
{
	$ingresos		=$this->bancos->obtenerIngresosCuentaInicial($row->idCuenta,$mes,$anio);
	$egresos		=$this->bancos->obtenerEgresosCuentaInicial($row->idCuenta,$mes,$anio);
	
	$saldo			=$ingresos-$egresos;
	$saldoInicial	+=$saldo;
	
	$excel->getActiveSheet()->setCellValue('A'.$i, $row->nombre.' CTA '.$row->cuenta);
	$excel->getActiveSheet()->setCellValue('B'.$i, 'SALDO INICIAL');
	$excel->getActiveSheet()->setCellValue('C'.$i, $saldo);
	
	$i++;
}

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'SUMA TOTAL DE SALDOS INICIALES');

$excel->getActiveSheet()->getStyle('D'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('D'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('D'.$i, $saldoInicial);

#ENTRADAS EN PRODUCTOS
$i++;
$entradas	=$this->administracion->obtenerEntradaProductos($mes,$anio);

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':D'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'ENTRADA A BANCOS O CAJA');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;
$totalEntradas	=0;

foreach($entradas as $row)
{
	$totalEntradas	+=$row->pago;
	
	$excel->getActiveSheet()->setCellValue('A'.$i, $row->producto);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->pago);
	
	$i++;
}

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'SUMAS');

$excel->getActiveSheet()->getStyle('D'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('D'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('D'.$i, $totalEntradas);

#SALIDAS EN PRODUCTOS
$i++;
$salidas		=$this->administracion->obtenerSalidasProductos($mes,$anio);
$totalSalidas	=0;

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':D'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'SALIDAS BANCOS / CAJA');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;
foreach($salidas as $row)
{
	$retenciones	=0;
	$totalSalidas	+=$row->pago-$retenciones;
	
	$excel->getActiveSheet()->setCellValue('A'.$i, $row->departamento);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->pago-$retenciones);
	
	$i++;
}

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'SUMAS');

$excel->getActiveSheet()->getStyle('D'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('D'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('D'.$i, $totalSalidas);

$i++;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'SALDO INICIAL MAS ENTRADAS MENOS SALIDAS');

$excel->getActiveSheet()->getStyle('D'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('D'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('D'.$i, $saldoInicial+$totalEntradas-$totalSalidas);

$i++;
$saldoFinal	=0;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':D'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'SALDOS FINALES EN BANCOS');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;
foreach($cuentas as $row)
{
	$ingresos		=$this->bancos->obtenerIngresosCuentaFinal($row->idCuenta,$mes,$anio);
	$egresos		=$this->bancos->obtenerEgresosCuentaFinal($row->idCuenta,$mes,$anio);
	$saldo			=$ingresos-$egresos;
	$saldoFinal		+=$saldo;
	
	$excel->getActiveSheet()->setCellValue('A'.$i, $row->nombre.' CTA '.$row->cuenta);
	$excel->getActiveSheet()->setCellValue('C'.$i, $saldo);
	
	$i++;
}

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'SUMA TOTAL DE SALDOS FINALES');

$excel->getActiveSheet()->getStyle('D'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('D'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('D'.$i, $saldoFinal);

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Flujo efectivo');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>