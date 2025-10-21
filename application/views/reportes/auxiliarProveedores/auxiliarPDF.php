<?php
$this->load->view('reportes/auxiliarProveedores/encabezado');

if($auxiliar!=null)
{
	echo'
	<table class="admintable" width="100%">';
	
	$i		=1;
	$total	=0;
	foreach($auxiliar as $row)
	{
		$estilo	=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$monto	=$row->monto;
		
		echo'
		<tr '.$estilo.'>
			<td width="3%" align="center">'.$i.'</td>
			<td width="17%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td width="35%" align="center">'.$row->orden.'</td>
			<td width="15%" align="center">'.($row->factura=='1'?$row->remision:'').'</td>
			<td width="15%" align="center">'.($row->factura=='0'?$row->remision:'').'</td>
			<td width="15%" align="right">$'.number_format($row->monto,2).'</td>
		</tr>';
		$i++;
	}
	
	echo '
	</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de auxiliar de proveedores</div';
}
?>