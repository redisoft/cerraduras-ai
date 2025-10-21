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



$excel->getActiveSheet()->getColumnDimension('A')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':N'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':N'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Reporte de panaderos');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':N'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('N'.$i, 'Total: $'.number_format($total,2));

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':N'.$i)->getFont()->setName('Arial Black');

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Línea');
$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit('Orden', PHPExcel_Cell_DataType::TYPE_STRING);
$excel->getActiveSheet()->setCellValue('D'.$i, 'Total producido');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Mano obra');

$excel->getActiveSheet()->setCellValue('F'.$i, 'Maestro');

$excel->getActiveSheet()->setCellValue('G'.$i, "Maestro \n cuota sindical");
$excel->getActiveSheet()->setCellValue('H'.$i, "Maestro \n prima dominical");


$excel->getActiveSheet()->setCellValue('I'.$i, 'Oficial');

$excel->getActiveSheet()->setCellValue('J'.$i, "Oficial \n cuota sindical");
$excel->getActiveSheet()->setCellValue('K'.$i, "Oficial \n prima dominical");


$excel->getActiveSheet()->setCellValue('L'.$i, 'Cuota sindical');
$excel->getActiveSheet()->setCellValue('M'.$i, 'Prima dominical');
$excel->getActiveSheet()->setCellValue('N'.$i, 'Total');


$i++;

foreach($pedidos as $row)
{
	$reporte	= $this->pedidos->obtenerReportePedido($row->idPedido);
	$total		= $this->pedidos->obtenerTotalesPedido($row->idPedido);
	$impuestos	= $this->pedidos->obtenerImpuestosPedido($row->idPedido);
	
	$linea='';
	if($row->idLinea==2) $linea=frances;
	if($row->idLinea==3)  $linea=bizcocho;
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaPedido), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->linea, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($linea.$row->folio, PHPExcel_Cell_DataType::TYPE_STRING);
	#$excel->getActiveSheet()->setCellValue('D'.$i, $row->producido);
	$excel->getActiveSheet()->setCellValue('D'.$i, $total+$impuestos);
	
	$excel->getActiveSheet()->setCellValue('E'.$i, $reporte!=null?$reporte->manoTotal:0);
	
	$excel->getActiveSheet()->setCellValue('F'.$i, $reporte!=null?$reporte->maestro:0);
	$excel->getActiveSheet()->setCellValue('G'.$i, $reporte!=null?$reporte->maestro*$reporte->cuotaSindical/100:0);
	$excel->getActiveSheet()->setCellValue('H'.$i, $reporte!=null?$reporte->maestro*$reporte->primaDominical/100:0);
	
	
	$excel->getActiveSheet()->setCellValue('I'.$i, $reporte!=null?$reporte->oficial:0);
	$excel->getActiveSheet()->setCellValue('J'.$i, $reporte!=null?$reporte->oficial*$reporte->cuotaSindical/100:0,decimales);
	$excel->getActiveSheet()->setCellValue('K'.$i, $reporte!=null?$reporte->oficial*$reporte->primaDominical/100:0,decimales);
	
	$excel->getActiveSheet()->setCellValue('L'.$i, $reporte!=null?$reporte->cuotaTotal:0);
	$excel->getActiveSheet()->setCellValue('M'.$i, $reporte!=null?$reporte->primaTotal:0);
	$excel->getActiveSheet()->setCellValue('N'.$i, $reporte!=null?$reporte->manoTotal+$reporte->primaTotal-$reporte->cuotaTotal:0);
	
	#$excel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
	$excel->getActiveSheet()->getStyle('D'.$i.':N'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
	$i++;
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Panaderos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>