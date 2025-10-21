<?php
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
	
$excel = new PHPExcel();

$excel
->getProperties()
->setCreator("Redisoft")
->setLastModifiedBy("Redisoft")
->setTitle("Redisoft")
->setSubject("Redisoft")
->setDescription("Redisoft")
->setKeywords("Redisoft")
->setCategory("Redisoft");

$i=1;



$excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':H'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Balanza de comprobación');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Cuenta');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Descripción');
$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit('Saldo inicial', PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValue('D'.$i, 'Cargos');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Abonos');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Saldo final');

$i++;

$totalSaldos	= 0;
$totalDebe		= 0;
$totalHaber		= 0;

foreach($cuentas as $row)
{
	$saldo	= $row->saldo;
	$debe	= $row->debe;
	$haber	= $row->haber;

	if($row->cuentasHijo>0)
	{
		$saldos	= $this->contabilidad->obtenerSaldoCuentas($row->idCuentaCatalogo,$row->cuentasHijo);
		$saldo	= $saldos[0];
		$debe	= $saldos[1];
		$haber	= $saldos[2];
	}
	
	$totalSaldos	+= $saldo;
	$totalDebe		+= $debe;
	$totalHaber		+= $haber;
	
	$mostrar		= false;
	if($filtro==0 and ($saldo>0 or $debe>0 or $haber>0))
	{
		$mostrar=true;
	}
	
	if($filtro==1 and $saldo==0 and $debe==0 and $haber==0)
	{
		$mostrar=true;
		
		$totalSaldos	= 0;
		$totalDebe		= 0;
		$totalHaber		= 0;
	}
	
	if($filtro==2)
	{
		$mostrar=true;
	}
	
	if($mostrar)
	{
		$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(($row->numeroCuenta), PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->descripcion, PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('C'.$i, $saldo);
		$excel->getActiveSheet()->setCellValue('D'.$i, $debe);
		$excel->getActiveSheet()->setCellValue('E'.$i, $haber);
		$excel->getActiveSheet()->setCellValue('F'.$i, $saldo+$debe-$haber);
	
		$excel->getActiveSheet()->getStyle('C'.$i.':F'.$i)->getNumberFormat()->setFormatCode('$0.00');
		
		$i++;
	}
	
	
	if($filtro!=3)
	{
		if($row->cuentasHijo>0)
		{
			$i	= $this->contabilidad->obtenerCuentasBalanzaVistaExcel($row->idCuentaCatalogo,0,$i,$excel,$filtro);
		}
	}
}


$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':B'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit('Totales', PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValue('C'.$i, $totalSaldos);
$excel->getActiveSheet()->setCellValue('D'.$i, $totalDebe);
$excel->getActiveSheet()->setCellValue('E'.$i, $totalHaber);
$excel->getActiveSheet()->setCellValue('F'.$i, $totalSaldos+$totalDebe-$totalHaber);

$excel->getActiveSheet()->getStyle('C'.$i.':F'.$i)->getNumberFormat()->setFormatCode('$0.00');

$i++;

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':B'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit('Diferencias', PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValue('D'.$i, $totalDebe-$totalHaber);

$excel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('$0.00');

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$fichero	= rand(50,60);

$excel->getActiveSheet()->setTitle('Balanza');
$excel->setActiveSheetIndex(0);
$objWriter 	= PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>