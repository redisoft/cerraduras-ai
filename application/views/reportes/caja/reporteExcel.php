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

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);


$i=1;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':C'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE DE CAJA');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Ticket');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Importe');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Hora');


$i++;
$totales=0;
foreach($registros as $row)
{
	if($row->tipoRegistro>0)
	{
		$folio	=	obtenerFolioRegistro($row->tipoRegistro).configurarFolioTipo($row->folio);
		$totales-=$row->importe;
	}
	else
	{
		$folio	= $row->folio.' - '.$row->estacion;
		$totales+=$row->importe;
	}

	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($folio, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->importe);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit(obtenerHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode('$0.00');

	$i++;
}

$excel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit('Total', PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValue('B'.$i, $totales);


$excel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode('$0.00');

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Reportes de caja');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;