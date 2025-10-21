<?php
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
	
$excel = new PHPExcel();

$excel
->getProperties()
->setCreator("Textil Arte")
->setLastModifiedBy("Textil Arte")
->setTitle("Textil Arte")
->setSubject("Textil Arte")
->setDescription("Textil Arte")
->setKeywords("Textil Arte")
->setCategory("Textil Arte");

$i=1;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'RELACIÓN CLIENTES - '.$anio);
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getRowDimension('2')->setRowHeight(50);
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':D'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$datos="";
$datos.=$emisor!=null?"\n ".$emisor->nombre:'';
$datos.=$emisor!=null?"\n ".$emisor->rfc:'';

#$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($datos, PHPExcel_Cell_DataType::TYPE_STRING);
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i);
$excel->setActiveSheetIndex(0)->mergeCells('E'.$i.':E'.$i);
$excel->getActiveSheet()->setCellValue('A'.$i, $datos);
$excel->getActiveSheet()->setCellValue('E'.$i, 'Total: '.number_format($totales,3));

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Cliente');
$excel->getActiveSheet()->setCellValue('B'.$i, 'RFC');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Subtotal');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Iva');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Total');

$i++;

foreach($relacion as $row)
{
	$total	= $this->reportes->obtenerRelacionCliente($anio,$idEmisor,$row->idCliente);
			
	$excel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->cliente, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->rfc, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('C'.$i, $total[0]);
	$excel->getActiveSheet()->setCellValue('D'.$i, $total[1]);
	$excel->getActiveSheet()->setCellValue('E'.$i, $total[2]);

	$excel->getActiveSheet()->getStyle('C'.$i.':E'.$i)->getNumberFormat()->setFormatCode('$0.00');

	$i++;
}

$fichero	= rand(40,50);

$excel->getActiveSheet()->setTitle('Relación clientes');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;