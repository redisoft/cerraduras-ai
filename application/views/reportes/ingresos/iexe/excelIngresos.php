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
$excel->setActiveSheetIndex(0)->mergeCells('A'.$m.':M'.$m.'');

$excel->getActiveSheet()->getStyle('A'.$m.':M'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':M'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':M'.$m)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$m, 'REPORTE DE INGRESOS');
$excel->getActiveSheet()->getStyle('A'.$m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$m++;

$excel->getActiveSheet()->getStyle('A'.$m.':M'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':M'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':M'.$m)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('K'.$m, 'TOTAL');
$excel->getActiveSheet()->setCellValue('L'.$m, $sumaIngresos);
$excel->getActiveSheet()->getStyle('L'.$m)->getNumberFormat()->setFormatCode('$0.00');


$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

$m++;

$excel->getActiveSheet()->getStyle('A'.$m.':M'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':M'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':M'.$m)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$m, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$m, sistemaActivo=='IEXE'?'Alumno':'Cliente');
$excel->getActiveSheet()->setCellValue('C'.$m, 'Matrícula');
$excel->getActiveSheet()->setCellValue('D'.$m, 'Descripción');
$excel->getActiveSheet()->setCellValue('E'.$m, 'Forma de pago');
$excel->getActiveSheet()->setCellValue('F'.$m, 'Folio');
$excel->getActiveSheet()->setCellValue('G'.$m, 'Banco');
$excel->getActiveSheet()->setCellValue('H'.$m, 'Cuenta');
$excel->getActiveSheet()->setCellValue('I'.$m, 'Factura');
$excel->getActiveSheet()->setCellValue('J'.$m, 'Remisión');
$excel->getActiveSheet()->setCellValue('K'.$m, 'Subtotal');
$excel->getActiveSheet()->setCellValue('L'.$m, 'Impuestos');
$excel->getActiveSheet()->setCellValue('M'.$m, 'Total');

$i		=$m+1;

foreach($ingresos as $row)
{
	$factura	= $this->facturacion->obtenerFacturaCancelar($row->idFactura);
	$banco 		= explode('|',$row->banco);
	
	#$excel->getActiveSheet()->setCellValue('A'.$i, $row->fecha);
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->cliente);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->matricula, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('D'.$i, (strlen($row->productoCatalogo)>1?$row->productoCatalogo:$row->producto));
	$excel->getActiveSheet()->setCellValue('E'.$i, $row->forma);
	$excel->getActiveSheet()->setCellValue('F'.$i, $row->cheque.$row->transferencia);
	$excel->getActiveSheet()->setCellValue('G'.$i, strlen($row->banco)>3?$banco[1]:'');
	$excel->getActiveSheet()->setCellValue('H'.$i, strlen($row->banco)>3?$banco[0]:'');
	$excel->getActiveSheet()->setCellValue('I'.$i, $row->remision=='0'?($factura!=null?$factura->cfdi:$row->factura):'');
	$excel->getActiveSheet()->setCellValue('J'.$i, $row->remision=='1'?$row->factura:'');
	$excel->getActiveSheet()->setCellValue('K'.$i, $row->subTotal);
	$excel->getActiveSheet()->setCellValue('L'.$i, $row->ivaTotal);
	$excel->getActiveSheet()->setCellValue('M'.$i, $row->pago);
	$excel->getActiveSheet()->getStyle('J'.$i.':M'.$i)->getNumberFormat()->setFormatCode('$0.00');

	$i++;
}

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Reportes');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;