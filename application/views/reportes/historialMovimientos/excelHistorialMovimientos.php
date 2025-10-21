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

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(50);


$i=1;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':F'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE DE HISTORIAL DE MOVIMENTOS');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Hora');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Usuario');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Modulo');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Acción');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Descripción');

$i++;

foreach($movimientos as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit(obtenerHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->nombre.'('.$row->usuario.')', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->modulo, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->accion, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($row->descripcion, PHPExcel_Cell_DataType::TYPE_STRING);

	$i++;
}

$fichero= rand(50,90);

$excel->getActiveSheet()->setTitle('Historial movimientos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;