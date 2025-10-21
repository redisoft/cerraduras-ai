<?php

if($depositos!=null)
{
	$this->load->view('reportes/depositos/encabezado');
	
	echo'
	<table class="admintable" width="100%">';
		
		$i=1;
		foreach($depositos as $row)
		{
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td width="5%" align="right">'.$i.'</td>
				<td width="15%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
				<td width="35%" align="left">'.$row->cliente.'</td>
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