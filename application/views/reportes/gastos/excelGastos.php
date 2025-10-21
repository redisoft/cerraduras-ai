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
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':L'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE DE EGRESOS');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->setSize(9);
$excel->getActiveSheet()->setCellValue('K'.$i, 'TOTAL');
$excel->getActiveSheet()->setCellValue('L'.$i, $sumaGastos);
$excel->getActiveSheet()->getStyle('L'.$i)->getNumberFormat()->setFormatCode('$0.00');

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);


$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':L'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Proveedor');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Descripción del producto');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Forma de pago');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Folio');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Banco');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Cuenta');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Factura');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Remisión');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Subtotal');
$excel->getActiveSheet()->setCellValue('K'.$i, 'Impuestos');
$excel->getActiveSheet()->setCellValue('L'.$i, 'Importe');


$i++;

foreach($gastos as $row)
{
	$proveedor		= $this->proveedores->obtenerProveedor($row->idProveedor);
	$banco 			= explode('|',$row->banco);

	#$excel->getActiveSheet()->setCellValue('A'.$i, obtenerFechaMesCortoHora($row->fecha));
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->setCellValue('B'.$i, $proveedor!=null?$proveedor->empresa:'');
	$excel->getActiveSheet()->setCellValue('C'.$i, strlen($row->productoCatalogo)>1?$row->productoCatalogo:$row->producto);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->forma);
	$excel->getActiveSheet()->setCellValue('E'.$i, $row->cheque.$row->transferencia);
	$excel->getActiveSheet()->setCellValue('F'.$i, strlen($row->banco)>3?$banco[1]:'');
	$excel->getActiveSheet()->setCellValue('G'.$i, strlen($row->banco)>3?$banco[0]:'');
	$excel->getActiveSheet()->setCellValue('H'.$i, $row->esRemision=='0'?$row->remision:'');
	$excel->getActiveSheet()->setCellValue('I'.$i, $row->esRemision=='1'?$row->remision:'');
	$excel->getActiveSheet()->setCellValue('J'.$i, $row->subTotal);
	$excel->getActiveSheet()->setCellValue('K'.$i, $row->ivaTotal);
	$excel->getActiveSheet()->setCellValue('L'.$i, $row->pago);
	
	$excel->getActiveSheet()->getStyle('J'.$i.':L'.$i)->getNumberFormat()->setFormatCode('$0.00');

	$i++;
}

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Reporte de egresos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;
?>