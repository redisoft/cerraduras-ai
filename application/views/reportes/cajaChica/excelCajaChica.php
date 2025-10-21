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

$i=1;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':I'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE DE CAJA CHICA');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;
$excel->getActiveSheet()->getStyle('I'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('I'.$i)->getFont()->setSize(9);
$excel->getActiveSheet()->setCellValue('I'.$i, 'Suma caja chica: '.$sumaCaja);
$excel->getActiveSheet()->getStyle('I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(22);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(35);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Concepto');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Monto');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Forma de pago');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Cheque / Trasferencia');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Nombre');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Departamento');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Descripción del producto');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Tipo');

$i++;

foreach($cajaChica as $row)
{
	

	#$excel->getActiveSheet()->setCellValue('A'.$i, $row->fecha);
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	
	$producto	=$this->configuracion->obtenerProducto($row->idProducto);
	$excel->getActiveSheet()->setCellValue('B'.$i, $producto!=null?$producto->nombre:'');
	
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->pago);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->formaPago);
	$excel->getActiveSheet()->setCellValue('E'.$i, $row->cheque.$row->transferencia);
	
	$nombre	=$this->administracion->obtenerNombre($row->idNombre);
	$excel->getActiveSheet()->setCellValue('F'.$i, $nombre!=null?$nombre->nombre:'');

	$departamento	=$this->configuracion->obtenerDepartamento($row->idDepartamento);
	$excel->getActiveSheet()->setCellValue('G'.$i, $departamento!=null?$departamento->nombre:'');
	
	$excel->getActiveSheet()->setCellValue('H'.$i, $row->producto);
	
	$gasto	=$this->configuracion->obtenerGasto($row->idGasto);
	$excel->getActiveSheet()->setCellValue('I'.$i, $gasto!=null?$gasto->nombre:'');
	
	$cajas=$this->administracion->obtenerCajaChica($row->idEgreso);
	
	foreach($cajas as $caja)
	{
		$i++;
		
		$excel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->getFont()->setSize(9);
		
		#$excel->getActiveSheet()->setCellValue('A'.$i, $row->producto);
		$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($caja->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('B'.$i, $caja->concepto);
		$excel->getActiveSheet()->setCellValue('C'.$i, $caja->importe);
	}

	$i++;
}

$fichero= rand(10000000,99999999);

$excel->getActiveSheet()->setTitle('Reportes');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;