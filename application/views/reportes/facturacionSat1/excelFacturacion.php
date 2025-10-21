<?php
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
	
$excel = new PHPExcel();

$excel
->getProperties()
->setCreator("Maarten Balliauw")
->setLastModifiedBy("Maarten Balliauw")
->setTitle("Office 2007 XLSX Test Document")
->setSubject("Office 2007 XLSX Test Document")
->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
->setKeywords("office 2007 openxml php")
->setCategory("Test result file");

$i=1;


$excel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getFont()->setName('Arial Black');

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(48);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

$excel->getActiveSheet()->setCellValue('A'.$i, 'FECHA');
$excel->getActiveSheet()->setCellValue('B'.$i, 'DOCUMENTO');
$excel->getActiveSheet()->setCellValue('C'.$i, 'CLIENTE');
$excel->getActiveSheet()->setCellValue('D'.$i, 'EMISOR');
$excel->getActiveSheet()->setCellValue('E'.$i, 'FOLIO Y SERIE');
$excel->getActiveSheet()->setCellValue('F'.$i, 'FACTURADO');
$excel->getActiveSheet()->setCellValue('G'.$i, 'PENDIENTE');

$excel->getActiveSheet()->setCellValue('H'.$i, 'TOTAL');


$i++;
$total=0;

foreach($facturas as $row)
{
	$cancelada	=$row->cancelada==1?'(Cancelada)':'';
	$total		=$row->cancelada==1?0:$row->total;
	$parciales	=0;
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit($row->fecha, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->documento);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->empresa.$cancelada);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->emisor);
	$excel->getActiveSheet()->setCellValue('E'.$i, $row->serie.$row->folio);
	
	if($row->cancelada==0)
	{
		#if($row->parcial==1)
		#{
			$parciales	=$this->reportes->sumarFacturasParciales($row->idCotizacion);
			$excel->getActiveSheet()->setCellValue('F'.$i, $parciales);
		#}
	}
	
	if($row->cancelada==0)
	{
		#if($row->parcial==1)
		#{
			$cotizacion=$this->reportes->obtenerCotizacionFactura($row->idCotizacion)-$parciales;

			$excel->getActiveSheet()->setCellValue('G'.$i, $cotizacion<0?0:$cotizacion);
		#}
	}
	$excel->getActiveSheet()->setCellValue('H'.$i, $total);
	
	$i++;
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$fichero= rand(10000000,99999999);

$excel->getActiveSheet()->setTitle('reporte');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>