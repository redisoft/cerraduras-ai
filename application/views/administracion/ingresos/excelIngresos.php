<?php
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

$i=1;


$excel->setActiveSheetIndex(0)->mergeCells('A'.$i.':K'.$i.'');
$excel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->setCellValue('A'.$i, 'Ingresos');
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setSize(11);

$i++;
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('A'.$i.':K'.$i)->getFont()->setSize(10);

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(24);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

$excel->getActiveSheet()->setCellValue('A'.$i, 'Fecha');
$excel->getActiveSheet()->setCellValue('B'.$i, sistemaActivo=='IEXE'?'Cliente/Alumno ':'Cliente');
$excel->getActiveSheet()->setCellValue('C'.$i, 'Concepto');
$excel->getActiveSheet()->setCellValue('D'.$i, 'Monto');
$excel->getActiveSheet()->setCellValue('E'.$i, 'Forma de pago');
$excel->getActiveSheet()->setCellValue('F'.$i, 'Cheque / Transferencia');
$excel->getActiveSheet()->setCellValue('G'.$i, 'Nombre');
$excel->getActiveSheet()->setCellValue('H'.$i, 'Departamento');
$excel->getActiveSheet()->setCellValue('I'.$i, 'Descripción del producto');
$excel->getActiveSheet()->setCellValue('J'.$i, 'Tipo');
$excel->getActiveSheet()->setCellValue('K'.$i, 'Comentarios');

$i++;

foreach($ingresos as $row)
{
	$cliente		= $this->clientes->obtenerCliente($row->idCliente);
	$producto		= $this->configuracion->obtenerProducto($row->idProducto);
	$nombre			= $this->administracion->obtenerNombre($row->idNombre);
	$departamento	= $this->configuracion->obtenerDepartamento($row->idDepartamento);
	$gasto			= $this->configuracion->obtenerGasto($row->idGasto);
	
	$cuenta		= $this->administracion->obtenerCuentaBancoIngreso($row->idCuenta);
	
	$formaPago	= $row->formaPago;
	
	if($cuenta!=null)
	{
		$formaPago.= "\n";
		$formaPago.= strlen($cuenta->cuenta)>0?$cuenta->cuenta:$cuenta->tarjetaCredito;
		$formaPago.= "\n".$cuenta->banco;
	}
	
	
	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(obtenerFechaMesCortoHora($row->fecha), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($cliente!=null?$cliente->empresa:'', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('C'.$i)->setValueExplicit($producto!=null?$producto->nombre:'', PHPExcel_Cell_DataType::TYPE_STRING);
	#$excel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($row->pago, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('D'.$i, $row->pago);
	
	
	$excel->getActiveSheet()->setCellValue('E'.$i, $formaPago);
	#$excel->getActiveSheet()->getCell('E'.$i)->setValueExplicit($formaPago, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('F'.$i)->setValueExplicit($row->cheque.$row->transferencia, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('G'.$i)->setValueExplicit($nombre!=null?$nombre->nombre:'', PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->getCell('H'.$i)->setValueExplicit($departamento!=null?$departamento->nombre:'', PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('I'.$i)->setValueExplicit($row->producto, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('J'.$i)->setValueExplicit($gasto!=null?$gasto->nombre:'', PHPExcel_Cell_DataType::TYPE_STRING);
	
	$excel->getActiveSheet()->getCell('K'.$i)->setValueExplicit($row->comentarios, PHPExcel_Cell_DataType::TYPE_STRING);

	$excel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
	$i++;
}

/*$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setARGB('00200F');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setName('Arial Black');
$excel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(9);

$excel->getActiveSheet()->setCellValue('D'.$i, $total);*/

$excel->getActiveSheet()->setTitle('Ingresos');
$excel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

$objWriter->save(str_replace('.php', '.xls',carpetaImportar."Ingresos.xls"));

echo 'Ingresos';

?>