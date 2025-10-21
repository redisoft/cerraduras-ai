<?php
$numero		= $numero+17;

foreach($cuentas as $row)
{
	$saldo	= $row->saldo;
	$debe	= $row->debe;
	$haber	= $row->haber;

	if($row->cuentasHijo>0)
	{
		$saldos	= $this->contabilidad->obtenerSaldoCuentas($row->idCuentaCatalogo,$row->cuentasHijo,0);
		$saldo	= $saldos[0];
		$debe	= $saldos[1];
		$haber	= $saldos[2];
	}

	$excel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(($row->numeroCuenta), PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($row->descripcion, PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->setCellValue('C'.$i, $saldo);
	$excel->getActiveSheet()->setCellValue('D'.$i, $debe);
	$excel->getActiveSheet()->setCellValue('E'.$i, $haber);
	$excel->getActiveSheet()->setCellValue('F'.$i, $saldo+$debe-$haber);

	$excel->getActiveSheet()->getStyle('C'.$i.':F'.$i)->getNumberFormat()->setFormatCode('$0.00');
	
	$i++;
	
	
	if($row->cuentasHijo>0)
	{
		$i=$this->contabilidad->obtenerCuentasBalanzaVistaExcel($row->idCuentaCatalogo,$numero,$i,$excel);
	}
}

return $i;
?>