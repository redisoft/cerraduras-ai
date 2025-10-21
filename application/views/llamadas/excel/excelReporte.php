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

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(32);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(32);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(50);

$excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(40);


$i=1;

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':M'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':M'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE DE QUEJAS');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':M'.$i)->getFont()->setName('Arial Black');

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Estatus');
$excel->getActiveSheet()->setCellValue('C'.$i, 'CRM');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Alumno');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Matrícula');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Teléfono');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Programa');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Responsable');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Comentario');

$excel->getActiveSheet()->setCellValue('J'.$i, 'Fecha/Hora');
$excel->getActiveSheet()->setCellValue('K'.$i, 'Tiempo de respuesta en minutos');
$excel->getActiveSheet()->setCellValue('L'.$i, 'Área');
$excel->getActiveSheet()->setCellValue('M'.$i, 'Concepto');


$i++;

foreach($llamadas as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit(($row->idZona!=2?$row->estatus:'Baja'), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->status, PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->alumno.($row->idZona==2?"\n(Baja)":''));
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->matricula, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($row->telefono, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('G'.$i)->setValueExplicit($row->programa, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('H'.$i)->setValueExplicit($row->responsable, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('I'.$i)->setValueExplicit($row->comentarios.$row->bitacora, PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->getCell('J'.$i)->setValueExplicit(strlen($row->fechaResuelta)>0?obtenerFechaMesCortoHora($row->fechaResuelta.' '.$row->horaResuelta):'', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('K'.$i)->setValueExplicit(strlen($row->fechaResuelta)>0?$row->diferenciaResuelta:'', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('L'.$i)->setValueExplicit($row->area, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('M'.$i)->setValueExplicit($row->concepto, PHPExcel_Cell_DataType::TYPE_STRING);
	
	#$excel->getActiveSheet()->getStyle('I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$i++;
}

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Quejas');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;
?>
