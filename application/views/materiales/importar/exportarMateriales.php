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
$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Materia prima');
$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setSize(11);


$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(19);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(19);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(19);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Código interno');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Materia prima ');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Proveedor');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Unidad');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Conversión');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Costo');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Costo promedio');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Inventario');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Cantidad mínima');

$i++;

foreach($materiales as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->codigoInterno, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->nombre.($row->produccion!=0?'(Producido en la empresa)':''), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->nombreProveedor, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->descripcion, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($this->materiales->obtenerConversion($row->idConversion), PHPExcel_Cell_DataType::TYPE_STRING);

	$excel->getActiveSheet()->setCellValue('F'.$i, $row->costo);
	$excel->getActiveSheet()->setCellValue('G'.$i, $row->costoPromedio);
	$excel->getActiveSheet()->setCellValue('H'.$i, $row->inventario-$row->salidas);
	$excel->getActiveSheet()->setCellValue('I'.$i, $row->stockMinimo);



	$excel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode('$0.00');
	$excel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode('$0.00');
	$excel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode('0.0000');
	$excel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode('0.0000');
	
	$i++;
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$excel->getActiveSheet()->setTitle('Materia prima');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',carpetaImportar."Materiales.xls"));

echo 'Materiales';

?>