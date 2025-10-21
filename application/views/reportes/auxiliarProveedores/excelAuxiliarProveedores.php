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


$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(48);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

$i=1;

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'AUXILIAR DE PROVEEDORES');

$i++;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, $proveedor);

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Total: $'.number_format($total,2));

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Num. Orden Com.');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Factura');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Remisión');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Monto');


$i++;
$total	=0;
foreach($auxiliar as $row)
{		
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->orden);
	#$excel->getActiveSheet()->setCellValue('C'.$i, $row->factura);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit(($row->factura=='1'?$row->remision:''), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit(($row->factura=='1'?$row->remision:''), PHPExcel_Cell_DataType::TYPE_STRING);
	#$excel->getActiveSheet()->setCellValue('D'.$i, $row->remision);
	$excel->getActiveSheet()->setCellValue('E'.$i, $row->monto);
	
	$excel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('$0.000');
	
	$i++;
}



$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Auxiliar');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>