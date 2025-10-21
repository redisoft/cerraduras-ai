<?php
$this->load->view('reportes/nomina/encabezado');
if($personal!=null)
{
	echo '
	<table class="admintable" width="100%">';
	
	$i=1;
	foreach($personal as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td style="height:50px" width="5%" align="right">'.$i.'</td>
			<td width="65%">'.$row->nombre.'</td>
			<td width="30%" align="center">
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de personal para nómina</div>';
}
?>