<?php
if($pronostico!=null)
{
	$this->load->view('reportes/pronosticoIngresos/encabezado');
	
	echo '
	<table class="admintable" width="100%">';
	
	$i=1;
	foreach($pronostico as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cliente	=$this->clientes->obtenerCliente($row->idCliente);
		
		echo '
		<tr '.$estilo.'>
			<td width="3%" align="right">'.$i.'</td>
			<td width="15%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td width="35%" align="center">';
				echo $cliente!=null?$cliente->empresa:'';
			echo'</td>
			<td width="30%">'.$row->producto.'</td>
			<td width="17%" align="right">$'.number_format($row->pago,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de pronóstico</div>';
}
?>