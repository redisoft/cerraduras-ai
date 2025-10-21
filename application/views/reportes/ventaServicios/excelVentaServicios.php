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

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(24);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);


$i=1;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':H'.$i.'');

$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE DE VENTA DE SERVICIOS');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$i++;

$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Venta');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Servicio');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Cliente');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Periodicidad');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Plazo');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Número ciclos pagados');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Precio');

$i++;

foreach($servicios as $row)
{
	$ciclosPagados	= $this->reportes->obtenerCiclosPagados($row->idCotizacion,$row->idProducto);
	$ventas			= $this->reportes->obtenerVentaServiciosDetalle($row->idCotizacion,$row->idProducto);
	
	$subTotal		= $row->importe;
	$descuento		= $row->descuentoPorcentaje>0?$subTotal*($row->descuentoPorcentaje/100):0;
	$iva			= $row->ivaPorcentaje>0?($subTotal-$descuento)*($row->ivaPorcentaje/100):0;
	$total			= $subTotal-$descuento+$iva;
		
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaCompra), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->ordenCompra, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->servicio, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->cliente, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($row->periodicidad, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('F'.$i, $row->plazo);
	$excel->getActiveSheet()->setCellValue('G'.$i, count($ciclosPagados));
	$excel->getActiveSheet()->setCellValue('H'.$i, $total);
	
	$excel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
	$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray(array('font'  => array('color' => array('rgb' => '0404FF'),)));

	$i++;
	
	foreach($ventas as $ven)
	{
		$excel->setActiveSheetIndex(0)->mergeCells('C'.$i.':G'.$i.'');
		$cobrado='';
		if($ven->cancelada=='0')
		{
			if($ven->total==$ven->pagado) $cobrado='Cobrado';
			
			if($ven->total>$ven->pagado and $ven->pagado>0) $cobrado='Cobrado parcialmente';
		}
		
		$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($ven->fechaCompra), PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($ven->pendiente=='0'?$ven->ordenCompra:'', PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('C'.$i, ($ven->cancelada=='1'?'Cancelada':'')." ".$cobrado);
		$excel->getActiveSheet()->setCellValue('H'.$i, $ven->total);
	
		$excel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode('$0.00');
		$excel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$i++;	
	}
}

$fichero= rand(50,90);

$excel->getActiveSheet()->setTitle('Venta Servicios');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;