<?php
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
	
$excel = new PHPExcel();

$excel
->getProperties()
->setCreator(empresa)
->setLastModifiedBy(empresa)
->setTitle(empresa)
->setSubject(empresa)
->setDescription(empresa)
->setKeywords(empresa)
->setCategory(empresa);

$i=1;


$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':I'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Prospectos');
$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setSize(11);


$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);

$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);

$excel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(25);


$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha registro');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Fecha captación');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Prospecto');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Contacto');

$excel->getActiveSheet()->setCellValue('E'.$i, 'Campaña');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Programa');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Teléfono');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Email');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Promotor');

$i++;

foreach($clientes as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaRegistro), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaCaptacion), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->nombre.' '.$row->paterno.' '.$row->materno, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->fuente, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->campana, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($row->programa, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('G'.$i)->setValueExplicit($row->telefono."\n".$row->ladaMovil.' '.$row->movil, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('H'.$i)->setValueExplicit($row->email, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('I'.$i)->setValueExplicit($row->promotor, PHPExcel_Cell_DataType::TYPE_STRING);
	
	$i++;
}

$excel->getActiveSheet()->setTitle('Prospectos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',carpetaImportar."Prospectos.xls"));

echo 'Prospectos';

?>