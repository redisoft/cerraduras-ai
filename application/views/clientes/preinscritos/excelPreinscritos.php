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
$excel->getActiveSheet()->setCellValue('A'.$i, 'Pre-Inscritos');
$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setSize(11);


$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(30);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Alumno');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Programa');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Campaña');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Mes');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Periodo');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Promotor');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Matrícula');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Correo');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Teléfono');


$i++;

foreach($clientes as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->prospecto, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->programa, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->campana, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->mes, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->periodo, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($row->promotor, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('G'.$i)->setValueExplicit($row->matricula, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('H'.$i)->setValueExplicit($row->email, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('I'.$i)->setValueExplicit($row->telefono.' '.$row->movil, PHPExcel_Cell_DataType::TYPE_STRING);
	$i++;
}

$excel->getActiveSheet()->setTitle('Pre-Inscritos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$fichero= rand(50,60);

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>