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
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(25);

$i=1;

$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':N'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':N'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'REPORTE DE VENTAS');

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':N'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('N'.$i, 'Total: $'.number_format($total,decimales));

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':N'.$i)->getFont()->setName('Arial Black');

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, 'Cliente');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Venta');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Estación');
$excel->getActiveSheet()->setCellValue('E'.$i, $this->session->userdata('identificador'));
$excel->getActiveSheet()->setCellValue('F'.$i, 'Agente de ventas');
$excel->getActiveSheet()->setCellValue('G'.$i, 'CRM');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Forma de pago');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Subtotal');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Descuento');
$excel->getActiveSheet()->setCellValue('K'.$i, 'Impuesto');
$excel->getActiveSheet()->setCellValue('L'.$i, 'Total');
$excel->getActiveSheet()->setCellValue('M'.$i, 'Abono');
$excel->getActiveSheet()->setCellValue('N'.$i, 'Saldo');

$i++;
$total=0;

foreach($ventas as $row)
{
	$impuestos	= $this->reportes->obtenerProductosImpuestosVentas($row->idCotizacion);
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
		$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->estacion, PHPExcel_Cell_DataType::TYPE_STRING);
		
		$excel->getActiveSheet()->setCellValue('E'.$i, $row->identificador);
		$excel->getActiveSheet()->setCellValue('F'.$i, $row->usuario);
		
		if(strlen($row->idSeguimiento)>0)
		{
			$seguimiento	= $this->crm->obtenerUltimoSeguimientoVenta($row->idCotizacion);
			
			if($seguimiento!=null)
			{
				#echo $seguimiento->status.'\n'.obtenerFechaMesCortoHora($seguimiento->fecha);
				
				$excel->getActiveSheet()->setCellValue('G'.$i, $seguimiento->status."\n".obtenerFechaMesCortoHora($seguimiento->fecha));
			}
		}
		
		$excel->getActiveSheet()->getCell('H'.$i)->setValueExplicit($row->formaPago, PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('I'.$i, $row->subTotal);
		$excel->getActiveSheet()->setCellValue('J'.$i, number_format($row->descuento,decimales).' ( '.number_format($row->descuentoPorcentaje,decimales).'%)');
		
		$impuesto		= '';
		
		if($impuestos!=null)
		{
			$impuesto	.='(';
			$im			=0;
			
			foreach($impuestos as $imp)
			{
				$impuesto.=$im==0?number_format($imp->tasa,decimales).'%':', '.number_format($imp->tasa,decimales).'%';
				$im++;
			}
			
			$impuesto.=')';
		}
		
		$excel->getActiveSheet()->setCellValue('K'.$i, number_format($row->iva,decimales).$impuesto);
		$excel->getActiveSheet()->getStyle('K'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		
		
		$excel->getActiveSheet()->setCellValue('L'.$i, $row->total);
		$excel->getActiveSheet()->setCellValue('M'.$i, $row->pagado);
		$excel->getActiveSheet()->setCellValue('N'.$i, $row->total-$row->pagado);
		
		$excel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode('$0.000');
		$excel->getActiveSheet()->getStyle('L'.$i.':N'.$i)->getNumberFormat()->setFormatCode('$0.000');
		#$excel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode('$0.000');
		
		
		$excel->getActiveSheet()->setCellValue('M'.$i, $row->cancelada=='1'?0:$row->total);
		
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
