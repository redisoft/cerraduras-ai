<?php

if($relacion!=null)
{
	$this->load->view('reportes/relacionClientes/encabezado');
	
	echo'
	<table class="admintable" width="100%">';
		
		$i=1;
		foreach($relacion as $row)
		{
			$total	= $this->reportes->obtenerRelacionCliente($anio,$idEmisor,$row->idCliente);
			
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td width="5%" align="right">'.$i.'</td>
				<td width="35%" align="left">'.$row->cliente.'</td>
				<td width="15%" align="left">'.$row->rfc.'</td>
				<td width="15%" align="right">$'.number_format($total[0],2).'</td>
				<td width="15%" align="right">$'.number_format($total[1],2).'</td>
				<td width="15%" align="right">$'.number_format($total[2],2).'</td>
				
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>';
}
else
{
	echo '<div class="Error_validar">Sin registros de relación de clientes</div>';
}

?>