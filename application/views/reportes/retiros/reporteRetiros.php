<?php

if($retiros!=null)
{
	$this->load->view('reportes/retiros/encabezado');
	
	echo'
	<table class="admintable" width="100%">';
		
		$i=1;
		foreach($retiros as $row)
		{
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td width="5%" align="right">'.$i.'</td>
				<td width="15%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
				<td width="35%" align="left">'.$row->proveedor.'</td>
				<td width="15%" align="center">'.$row->formaPago.'</td>
				<td width="15%" align="center">'.$row->factura.'</td>
				<td width="15%" align="right">$'.number_format($row->pago,2).'</td>
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>';
}
else
{
	echo '<div class="Error_validar">Sin registros de depositos</div>';
}

?>