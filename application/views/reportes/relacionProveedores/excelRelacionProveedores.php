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
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':F'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Relación proveedores - '.$anio);
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setSize(9);

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$datos="";
$datos.=$emisor!=null?"\n ".$emisor->nombre:'';
$datos.=$emisor!=null?"\n ".$emisor->rfc:'';

$excel->getActiveSheet()->setCellValue('A'.$i, $datos);
$excel->getActiveSheet()->setCellValue('F'.$i, 'Total: '.number_format($totales,3));

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Emisor');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Proveedor');
$excel->getActiveSheet()->setCellValue('C'.$i, 'RFC');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Subtotal');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Iva');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Total');

$i++;

foreach($relacion as $row)
{
	$totales	= $this->reportes->obtenerRelacionProveedor($row->idProveedor,$anio,$idEmisor);
			
	$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->emisor, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->empresa, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->rfc, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('D'.$i, $totales[0]);
	$excel->getActiveSheet()->setCellValue('E'.$i, $totales[1]);
	$excel->getActiveSheet()->setCellValue('F'.$i, $totales[2]);

	$excel->getActiveSheet()->getStyle('D'.$i.':F'.$i)->getNumberFormat()->setFormatCode('$0.000');

	$i++;
}

$fichero	= rand(40,50);

$excel->getActiveSheet()->setTitle('Relación proveedores');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;