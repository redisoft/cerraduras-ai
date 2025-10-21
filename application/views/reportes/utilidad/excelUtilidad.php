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

$excel->getActiveSheet()->setCellValue('A'.$i, 'Utilidad ');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Mes');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Emisor');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Ingreso');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Gasto');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Utilidad');

$i++;
$mes	= substr($fecha,5,2);
$anio	= substr($fecha,0,4);
foreach($emisores as $row)
{
	$gastos		= $this->reportes->obtenerGastosProveedoresMes($mes,$anio,$row->idEmisor);
	$ingreso	= $this->reportes->obtenerGastosClientesMes($mes,$anio,$row->idEmisor);

	$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesAnio($fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->nombre, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('C'.$i, $ingreso);
	$excel->getActiveSheet()->setCellValue('D'.$i, $gastos);
	$excel->getActiveSheet()->setCellValue('E'.$i, $ingreso-$gastos);

	$excel->getActiveSheet()->getStyle('C'.$i.':E'.$i)->getNumberFormat()->setFormatCode('$0.000');

	$i++;
}

$fichero	= rand(40,50);

$excel->getActiveSheet()->setTitle('Utilidad');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;