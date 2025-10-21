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

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(48);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(25);

$i=1;

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':K'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE DE VENTAS');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('K'.$i, 'Total: $'.$total);

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setName('Arial Black');

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Cliente');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Venta');
$excel->getActiveSheet()->setCellValue('D'.$i, $this->session->userdata('identificador'));
$excel->getActiveSheet()->setCellValue('E'.$i, 'Agente de ventas');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Subtotal');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Descuento');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Impuesto');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Total');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Abono');
$excel->getActiveSheet()->setCellValue('K'.$i, 'Saldo');

$i++;
$total=0;

foreach($ventas as $row)
{
	
	$cancelada=0;

	if($row->idFactura!=0)
	{
		$sql="select cancelada from facturas
		where idFactura='$row->idFactura' 
		and cancelada='1'";
		
		if($this->db->query($sql)->num_rows()>0) 
		{
			$cancelada=1;
		}
	}
	
	if($cancelada==0)
	{
		$total		+=$row->total;
		$descuento	= $row->descuento>0?$row->subTotal*($row->descuento/100):0;
		$iva		= ($row->subTotal-$descuento)*$row->iva;
		
		$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCorto($row->fechaCompra), PHPExcel_Cell_DataType::TYPE_STRING);
		
		$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->empresa, PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->ordenCompra.' '.($row->idTienda>0?'('.$row->tienda.')':''), PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('D'.$i, $row->identificador);
		$excel->getActiveSheet()->setCellValue('E'.$i, $row->usuario);

		$excel->getActiveSheet()->setCellValue('F'.$i, $row->subTotal);
		$excel->getActiveSheet()->setCellValue('G'.$i, number_format($row->descuento,decimales).' ( '.number_format($row->descuentoPorcentaje,decimales).'%)');
		$excel->getActiveSheet()->setCellValue('H'.$i, number_format($row->iva,decimales));
		$excel->getActiveSheet()->setCellValue('I'.$i, $row->total);
		$excel->getActiveSheet()->setCellValue('J'.$i, $row->pagado);
		$excel->getActiveSheet()->setCellValue('K'.$i, $row->total-$row->pagado);
		
		$excel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode('$0.000');
		$excel->getActiveSheet()->getStyle('H'.$i.':K'.$i)->getNumberFormat()->setFormatCode('$0.000');
		#$excel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode('$0.000');
		
		
		$excel->getActiveSheet()->setCellValue('K'.$i, $row->cancelada=='1'?0:$row->total);
		
		$i++;
	}
}

/*$excel->getActiveSheet()->getStyle('F'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('F'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('F'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('F'.$i, $total);*/

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Ventas');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;
?>
