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

$i=1;



$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setName('Arial Black');

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(22);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(17);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(17);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Documento');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Emisor');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Cliente/Empleado');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Folio y serie');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Estación');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Subtotal');
$excel->getActiveSheet()->setCellValue('H'.$i, 'IVA');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Total');


$i++;
$total=0;

foreach($facturas as $row)
{
	$cancelada	= $row->cancelada==1?'(Cancelada)':'';
	$subTotal	= $row->cancelada==1?0:$row->subTotal;
	$iva		= $row->cancelada==1?0:$row->iva;
	$total		= $row->cancelada==1?0:$row->total;
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->documento);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->emisor);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->empresa.$cancelada);
	#$excel->getActiveSheet()->setCellValue('E'.$i, $row->serie.$row->folio);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->pendiente=='1'?'':$row->serie.$row->folio.$cancelada, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('F'.$i, $row->estacion);
	$excel->getActiveSheet()->setCellValue('G'.$i, $subTotal);
	$excel->getActiveSheet()->setCellValue('H'.$i, $iva);
	$excel->getActiveSheet()->setCellValue('I'.$i, $total);
	
	$excel->getActiveSheet()->getStyle('G'.$i.':I'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
	$i++;
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Facturación');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>