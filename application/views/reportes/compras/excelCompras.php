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

$m=1;
$excel->setActiveSheetIndex(0)->mergeCells('A'.$m.':I'.$m.'');

$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->setSize(10);

$excel->getActiveSheet()->setCellValue('A'.$m, 'REPORTE DE COMPRAS');
$excel->getActiveSheet()->getStyle('A'.$m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$m++;
$excel->getActiveSheet()->getStyle('I'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('I'.$m)->getFont()->setSize(9);
$excel->getActiveSheet()->setCellValue('I'.$m, 'Total: '.$totalCompras);
$excel->getActiveSheet()->getStyle('I'.$m)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


$excel->getActiveSheet()->getColumnDimension('A')->setWidth(17);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

$m++;

$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$m.':I'.$m)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('A'.$m, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$m, 'Proveedor');
$excel->getActiveSheet()->setCellValue('C'.$m, 'Orden');

$excel->getActiveSheet()->setCellValue('D'.$m, 'CRM');

$excel->getActiveSheet()->setCellValue('E'.$m, 'Subtotal');
$excel->getActiveSheet()->setCellValue('F'.$m, 'Descuento');
$excel->getActiveSheet()->setCellValue('G'.$m, 'IVA');
$excel->getActiveSheet()->setCellValue('H'.$m, 'Total');

$excel->getActiveSheet()->setCellValue('I'.$m, 'Saldo');

$i		=$m+1;

foreach($compras as $row)
{
	$pagado		=$this->reportes->obtenerPagadoCompra($row->idCompras);
	
	#$excel->getActiveSheet()->setCellValue('A'.$i, $row->fecha);
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaCompra), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('B'.$i, $row->empresa);
	$excel->getActiveSheet()->setCellValue('C'.$i, $row->nombre);
	
	if(strlen($row->idSeguimiento)>0)
		{
			$seguimiento	= $this->crm->obtenerUltimoSeguimientoCompra($row->idCompras);
			
			if($seguimiento!=null)
			{
				#echo $seguimiento->status.'\n'.obtenerFechaMesCortoHora($seguimiento->fecha);
				
				$excel->getActiveSheet()->setCellValue('D'.$i, $seguimiento->status."\n".obtenerFechaMesCortoHora($seguimiento->fecha));
			}
		}
	
	$excel->getActiveSheet()->setCellValue('E'.$i, $row->subTotal);
	$excel->getActiveSheet()->setCellValue('F'.$i, $row->descuento);
	$excel->getActiveSheet()->setCellValue('G'.$i, $row->iva);
	$excel->getActiveSheet()->setCellValue('H'.$i, $row->total);
	
	$excel->getActiveSheet()->setCellValue('I'.$i, $row->total-$pagado);
	
	$excel->getActiveSheet()->getStyle('E'.$i.':I'.$i)->getNumberFormat()->setFormatCode('$0.000');


	$i++;
}

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Reportes');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;