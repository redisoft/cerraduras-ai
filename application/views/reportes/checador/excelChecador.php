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

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(25);



$i=1;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':K'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE DE CHECADOR');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Personal');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Puesto');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Departamento');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Día');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Hora entrada');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Hora checado entrada');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Diferencia minutos entrada');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Hora salida');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Hora checado salida');
$excel->getActiveSheet()->setCellValue('K'.$i, 'Diferencia minutos salida');

$i++;

foreach($checador as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->nombre, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->puesto, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->departamento, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->dia, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($row->horaInicialPersonal, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('G'.$i)->setValueExplicit($row->horaEntrada, PHPExcel_Cell_DataType::TYPE_STRING);
	
	if($row->retardoMinutos!=0)
	{
		$excel->getActiveSheet()->getCell('H'.$i)->setValueExplicit($row->retardoMinutos>0?$row->retardoMinutos.' a favor':$row->retardoMinutos*(-1).' en contra', PHPExcel_Cell_DataType::TYPE_STRING);
	}
	
	$excel->getActiveSheet()->getCell('I'.$i)->setValueExplicit($row->horaFinalPersonal, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('J'.$i)->setValueExplicit($row->horaSalida, PHPExcel_Cell_DataType::TYPE_STRING);
	
	if($row->horaSalida!=null)
	{
		$excel->getActiveSheet()->getCell('K'.$i)->setValueExplicit( $row->salidaMinutos>0?$row->salidaMinutos.' a favor':$row->salidaMinutos*(-1).' en contra', PHPExcel_Cell_DataType::TYPE_STRING);
	}

	$i++;
}

$fichero= rand(50,90);

$excel->getActiveSheet()->setTitle('Checador');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;