<?php

if($gastos!=null)
{
	$this->load->view('reportes/gastosFacturados/encabezado');
	
	echo'
	<table class="admintable" width="100%">';
		
		$i	= 1;
		
		foreach($gastos as $row)
		{
			$iva			= 1+($row->iva/100);
			$subTotal		= $row->pago/$iva;
			$iva			= $row->pago-$subTotal;
			
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td width="3%" align="right">'.$i.'</td>
				<td width="8%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
				<td width="8%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
				<td width="12%" align="left">'.$row->emisor.'</td>
				<td width="25%" align="left">'.$row->empresa.'</td>
				<td width="10%" align="left">'.$row->factura.'</td>
				<td width="12%" align="right">$'.number_format($subTotal,2).'</td>
				<td width="10%" align="right">$'.number_format($iva,2).'</td>
				<td width="12%" align="right">$'.number_format($row->pago,2).'</td>
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>';
}
else
{
	echo '<div class="Error_validar">Sin registros de relación de proveedores</div>';
}

?>