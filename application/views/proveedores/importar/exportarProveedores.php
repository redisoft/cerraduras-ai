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


$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':O'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':O'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Proveedores');
$excel->getActiveSheet()->getStyle('A'.$i.':O'.$i)->getFont()->setSize(11);


$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':O'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':O'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(16);
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('O')->setWidth(25);



$excel->getActiveSheet()->setCellValue('A'.$i, 'Empresa');
$excel->getActiveSheet()->setCellValue('B'.$i, 'RFC');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Calle');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Número');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Colonia');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Código postal');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Localidad');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Municipio');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Estado');
$excel->getActiveSheet()->setCellValue('J'.$i, 'País');
$excel->getActiveSheet()->setCellValue('K'.$i, 'Teléfono');
$excel->getActiveSheet()->setCellValue('L'.$i, 'Fax');
$excel->getActiveSheet()->setCellValue('M'.$i, 'Email');
$excel->getActiveSheet()->setCellValue('N'.$i, 'Página web');
$excel->getActiveSheet()->setCellValue('O'.$i, 'Vende');

$i++;

foreach($proveedores as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->empresa, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->rfc, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->domicilio, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->numero, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->colonia, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($row->codigoPostal, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('G'.$i)->setValueExplicit($row->localidad, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('H'.$i)->setValueExplicit($row->municipio, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('I'.$i)->setValueExplicit($row->estado, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('J'.$i)->setValueExplicit($row->pais, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('K'.$i)->setValueExplicit($row->lada.' '.$row->telefono, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('L'.$i)->setValueExplicit($row->ladaFax.' '.$row->fax, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('M'.$i)->setValueExplicit($row->email, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('N'.$i)->setValueExplicit($row->website, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('O'.$i)->setValueExplicit($row->vende, PHPExcel_Cell_DataType::TYPE_STRING);


	#$excel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
	$i++;
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$excel->getActiveSheet()->setTitle('Proveedores');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',carpetaImportar."Proveedores.xls"));

echo 'Proveedores';

?>