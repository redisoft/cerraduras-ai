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



$excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);


$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Detalles de producto');
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);

$i++;
$excel->setActiveSheetIndex(0)->mergeCells('B'.$i.':E'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$excel->getActiveSheet()->getStyle('A'.$i.':A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Código');
$excel->getActiveSheet()->setCellValue('B'.$i, $producto->codigoInterno);
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);

$i++;
$excel->setActiveSheetIndex(0)->mergeCells('B'.$i.':E'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$excel->getActiveSheet()->getStyle('A'.$i.':A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Producto');
$excel->getActiveSheet()->setCellValue('B'.$i, $producto->nombre);
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);

$i++;
$excel->setActiveSheetIndex(0)->mergeCells('B'.$i.':E'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$excel->getActiveSheet()->getStyle('A'.$i.':A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Unidad');
$excel->getActiveSheet()->setCellValue('B'.$i, $producto->unidad);
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);


$i++;
$excel->setActiveSheetIndex(0)->mergeCells('B'.$i.':E'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$excel->getActiveSheet()->getStyle('A'.$i.':A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Línea');
$excel->getActiveSheet()->setCellValue('B'.$i, $producto->linea);
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);

$i++;
$excel->setActiveSheetIndex(0)->mergeCells('B'.$i.':E'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$excel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$excel->getActiveSheet()->getStyle('A'.$i.':A'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Inventario');
$excel->getActiveSheet()->setCellValue('B'.$i, $producto->stock);
$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//COMPRAS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$cantidad	= 0;
if($compras!=null)
{
	$i++;
	$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
	$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Detalles de entradas por compras');

	$i++;
	$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setName('Arial Black');
	
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
	$excel->getActiveSheet()->setCellValue('B'.$i, 'Precio');
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit('Orden', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('D'.$i, 'Proveedor');
	$excel->getActiveSheet()->setCellValue('E'.$i, 'Cantidad');
	
	$i++;
	$total	= 0;
	
	foreach($compras as $row)
	{
		$cantidad	+=$row->cantidad;
		
		$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('B'.$i, $row->precio);
		$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->nombre, PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->proveedor, PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('E'.$i, $row->cantidad);
		
		$excel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode('$0.00');
		$excel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('0.00');
		
		$i++;
	}
	
	$excel->getActiveSheet()->setCellValue('D'.$i, 'Total');
	$excel->getActiveSheet()->setCellValue('E'.$i, $cantidad);
	
	$excel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('0.00');
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);
}

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//RECEPCIONES
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$cantidad	= 0;
if($recepciones!=null)
{
	$i++;
	$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
	$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Detalles de entradas por traspasos');

	$i++;
	$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setName('Arial Black');
	
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
	$excel->getActiveSheet()->setCellValue('B'.$i, 'Tienda origen');
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit('Folio', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('D'.$i, 'Cantidad');
	
	$i++;
	$total	= 0;
	
	foreach($recepciones as $row)
	{
		$cantidad	+=$row->cantidad;
		
		$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->sucursal, PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('C'.$i, $row->folio);
		$excel->getActiveSheet()->setCellValue('D'.$i, $row->cantidad);
		
		$excel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
		
		$i++;
	}
	
	$excel->getActiveSheet()->setCellValue('C'.$i, 'Total');
	$excel->getActiveSheet()->setCellValue('D'.$i, $cantidad);
	
	$excel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
	$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setSize(10);
}

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//NO ENTREGADOS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$cantidad	= 0;
if($registros!=null)
{
	$i++;
	$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
	$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Detalles de entradas por producto no entregado');

	$i++;
	$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setName('Arial Black');
	
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
	$excel->getActiveSheet()->setCellValue('B'.$i, 'Nota');
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit('Comentarios', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('D'.$i, 'Cantidad');
	
	$i++;
	$total	= 0;
	
	foreach($registros as $row)
	{
		$cantidad	+=$row->noEntregados;
		
		$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->estacion.$row->folio, PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->comentarios, PHPExcel_Cell_DataType::TYPE_STRING);
		
		$excel->getActiveSheet()->setCellValue('D'.$i, $row->noEntregados);
		
		$excel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
		
		$i++;
	}
	
	$excel->getActiveSheet()->setCellValue('C'.$i, 'Total');
	$excel->getActiveSheet()->setCellValue('D'.$i, $cantidad);
	
	$excel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
	$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setSize(10);
}


//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//VENTAS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$cantidad	= 0;
if($ventas!=null)
{
	$i++;
	$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
	$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Detalles de salidas por ventas');
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);


	$i++;
	$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setName('Arial Black');
	
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
	$excel->getActiveSheet()->setCellValue('B'.$i, 'Precio');
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit('Orden', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('D'.$i, 'Cliente');
	$excel->getActiveSheet()->setCellValue('E'.$i, 'Cantidad');
	
	$i++;
	$total	= 0;
	
	foreach($ventas as $row)
	{
		$cantidad	+=$row->cantidad;
		
		$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('B'.$i, $row->precio);
		$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->ordenCompra, PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->empresa, PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('E'.$i, $row->cantidad);
		
		$excel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode('$0.00');
		$excel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('0.00');
		
		$i++;
	}
	
	$excel->getActiveSheet()->setCellValue('D'.$i, 'Total');
	$excel->getActiveSheet()->setCellValue('E'.$i, $cantidad);
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
	
	$excel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('0.00');
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);
}

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//ENVIOS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$cantidad	= 0;
if($envios!=null)
{
	$i++;
	$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
	$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Detalles de salidas por traspasos');

	$i++;
	$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setName('Arial Black');
	
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
	$excel->getActiveSheet()->setCellValue('B'.$i, 'Tienda destino');
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit('Folio', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('D'.$i, 'Cantidad');
	
	$i++;
	$total	= 0;
	
	foreach($envios as $row)
	{
		$cantidad	+=$row->cantidad;
		
		$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->sucursal, PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('C'.$i, $row->folio);
		$excel->getActiveSheet()->setCellValue('D'.$i, $row->cantidad);
		
		$excel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
		
		$i++;
	}
	
	$excel->getActiveSheet()->setCellValue('C'.$i, 'Total');
	$excel->getActiveSheet()->setCellValue('D'.$i, $cantidad);
	
	$excel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
	$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->getFont()->setSize(10);
}


//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//AJUSTE MANUAL
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$cantidad	= 0;
if($movimientos!=null)
{
	$i++;
	$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':E'.$i.'');
	$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Ajuste manual');

	$i++;
	$excel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->setName('Arial Black');
	
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
	$excel->getActiveSheet()->setCellValue('B'.$i, 'Cantidad');
	$excel->getActiveSheet()->setCellValue('B'.$i, 'Movimiento');
	$excel->getActiveSheet()->setCellValue('D'.$i, 'Invntario anterior');
	$excel->getActiveSheet()->setCellValue('D'.$i, 'Invntario actual');
	
	$i++;
	$total	= 0;
	
	foreach($movimientos as $row)
	{
		$cantidad	+=$row->cantidad;
		
		$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('B'.$i, $row->cantidad);
		
		$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($row->movimiento, PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('D'.$i, $row->inventarioAnterior);
		$excel->getActiveSheet()->setCellValue('E'.$i, $row->inventarioActual);

		$excel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode('0.00');
		$excel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('0.00');
		$excel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('0.00');
		
		$i++;
	}
	
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Total');
	$excel->getActiveSheet()->setCellValue('B'.$i, $cantidad);
	
	$excel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode('0.00');
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setSize(10);
}


//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//MOVIMIENTO DIARIO
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$cantidad	= 0;
if($diario!=null)
{
	$i++;
	$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':B'.$i.'');
	$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Movimiento diario');

	$i++;
	$excel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getFont()->setName('Arial Black');
	
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
	$excel->getActiveSheet()->setCellValue('B'.$i, 'Cantidad');
	
	
	$i++;
	$total	= 0;
	
	foreach($diario as $row)
	{
		$cantidad	+=$row->stock;
		
		$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
		$excel->getActiveSheet()->setCellValue('B'.$i, $row->stock);
		
		$excel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode('0.00');
		
		$i++;
	}
	
	$excel->getActiveSheet()->setCellValue('A'.$i, 'Total');
	$excel->getActiveSheet()->setCellValue('B'.$i, $cantidad);
	
	$excel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode('0.00');
	$excel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getFont()->setName('Arial Black');
	$excel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getFont()->setSize(10);
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$fichero= rand(50,60);

$excel->getActiveSheet()->setTitle('Entradas y salidas');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',"media/ficheros/".$fichero.".xls"));

echo $fichero;

?>
