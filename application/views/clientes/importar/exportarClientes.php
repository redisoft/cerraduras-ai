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


$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':U'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Clientes');
$excel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getFont()->setSize(11);


$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getFont()->setSize(10);

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
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(16);
$excel->getActiveSheet()->getColumnDimension('O')->setWidth(16);
$excel->getActiveSheet()->getColumnDimension('P')->setWidth(16);
$excel->getActiveSheet()->getColumnDimension('Q')->setWidth(16);
$excel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('U')->setWidth(25);

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
$excel->getActiveSheet()->setCellValue('M'.$i, 'Email 1');
$excel->getActiveSheet()->setCellValue('N'.$i, 'Email 2');
$excel->getActiveSheet()->setCellValue('O'.$i, 'Email 3');
$excel->getActiveSheet()->setCellValue('P'.$i, 'Email 4');
$excel->getActiveSheet()->setCellValue('Q'.$i, 'Email 5');
$excel->getActiveSheet()->setCellValue('R'.$i, 'Página web 1');
$excel->getActiveSheet()->setCellValue('S'.$i, 'Página web 2');
$excel->getActiveSheet()->setCellValue('T'.$i, 'Página web 3');
$excel->getActiveSheet()->setCellValue('U'.$i, 'Servicios/Productos');

$i++;

foreach($clientes as $row)
{
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->empresa, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->rfc, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->calle, PHPExcel_Cell_DataType::TYPE_STRING);
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
	$excel->getActiveSheet()->getCell('N'.$i)->setValueExplicit($row->email2, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('O'.$i)->setValueExplicit($row->email3, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('P'.$i)->setValueExplicit($row->email4, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('Q'.$i)->setValueExplicit($row->email5, PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->getCell('R'.$i)->setValueExplicit($row->web, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('S'.$i)->setValueExplicit($row->web2, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('T'.$i)->setValueExplicit($row->web3, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('U'.$i)->setValueExplicit($row->serviciosProductos, PHPExcel_Cell_DataType::TYPE_STRING);

	#$excel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
	$i++;
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$excel->getActiveSheet()->setTitle('Clientes');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',carpetaImportar."Clientes.xls"));

echo 'Clientes';

?>