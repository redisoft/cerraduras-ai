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



$excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':B'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Alumnos con matrícula no encontrados');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getFont()->setName('Arial Black');

$excel->getActiveSheet()->setCellValue('A'.$i, 'Matrícula');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Periodo');


$i++;
$total=0;

for($m=0;$m<count($alumnos);$m++)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($alumnos[$m]->Matricula, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($alumnos[$m]->Periodo, PHPExcel_Cell_DataType::TYPE_STRING);

	$i++;
}

$excel->getActiveSheet()->setTitle('Alumnos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/alumnosNoEncontrados.xls"));

#echo 'alumnosNoEncontrados.xls';

?>