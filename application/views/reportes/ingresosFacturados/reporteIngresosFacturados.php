<?php

if($ingresos!=null)
{
	$this->load->view('reportes/ingresosFacturados/encabezado');
	
	echo'
	<table class="admintable" width="100%">';
		
		$i=1;
		foreach($ingresos as $row)
		{
			$subTotal	= $row->pago/(1+$row->iva);
			$iva		= $row->iva*$subTotal;
			
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td width="5%" align="right">'.$i.'</td>
				<td width="10%" align="center">'.obtenerFechaMesCorto($row->fechaFactura).'</td>
				<td width="10%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
				<td width="35%" align="left">'.$row->cliente.'</td>
				<td width="10%" align="center">'.(strlen($row->facturaIngreso)>0?$row->facturaIngreso:$row->factura).'</td>
				<td width="10%" align="right">$'.number_format($subTotal,2).'</td>
				<td width="10%" align="right">$'.number_format($iva,2).'</td>
				<td width="10%" align="right">$'.number_format($row->pago,2).'</td>
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>';
}
else
{
	echo '<div class="Error_validar">Sin registros de ingresos facturados</div>';
}

?>