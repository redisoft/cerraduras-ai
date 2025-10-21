<?php
error_reporting(0);
$totalVentas		= 0;
$totalComisiones	= 0;

foreach ($comisiones as $row)
{
	$comision	= 0;
	
	if($row->numeroPagos>1) 
	{
		$comision		= $row->comision;
		
		if($row->venta!=$row->importe)
		{
			$base			= $row->venta/$row->importe;
			$comision		= $row->comision*$base;
		}
	}
	
	$totalVentas		+= $row->venta;
	$totalComisiones	+= $comision;
}


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

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(32);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);


$i=1;

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':H'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(12);
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE DE COMISIONES');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(10);
$excel->getActiveSheet()->setCellValue('F'.$i, $totalVentas);
$excel->getActiveSheet()->setCellValue('G'.$i, $totalComisiones);
$excel->getActiveSheet()->getStyle('F'.$i.':G'.$i)->getNumberFormat()->setFormatCode('$0.00');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(10);
$excel->getActiveSheet()->getStyle('B'.$i.':H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Promotor');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Campaña');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Programa');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Alumno');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Última conexión');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Venta');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Comisión ');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Pagos');

$i++;

foreach($comisiones as $row)
{
	$comision	= 0;
		
	if($row->numeroPagos>1) 
	{
		$comision=$row->comision;
		
		if($row->venta!=$row->importe)
		{
			$base			= $row->venta/$row->importe;
			$comision		= $row->comision*$base;
		}
	}
	
	#$ultima	= $this->crm->obtenerUltimaConexionPreinscrito($row->matricula);

	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->promotor, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->campana, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->programa, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->alumno, PHPExcel_Cell_DataType::TYPE_STRING);
	#$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit(strlen($ultima)>0?obtenerFechaMesCortoHora($ultima):'', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit(strlen($row->ultimaConexion)>2? obtenerFechaMesCortoHora($row->ultimaConexion):'', PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->setCellValue('F'.$i, $row->venta);
	$excel->getActiveSheet()->setCellValue('G'.$i, $comision);
	$excel->getActiveSheet()->setCellValue('H'.$i, $row->numeroPagos);
	
	$excel->getActiveSheet()->getStyle('F'.$i.':G'.$i)->getNumberFormat()->setFormatCode('$0.00');

	#$excel->getActiveSheet()->getStyle('B'.$i.':K'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$i++;
}

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Comisiones');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;
?>
