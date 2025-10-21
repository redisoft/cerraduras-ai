<?php
$this->load->view('materiales/control/salidas/pdf/encabezado');
$i=1;

echo '
<table class="admintable" width="100%">';
foreach($materiales as $row)
{
	echo '
	<tr id="filaSalidaControl'.$i.'" '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
		<td width="15%">'.$row->codigoInterno.'</td>
		<td width="40%">'.$row->material.'</td>
		<td width="15%" align="center">'.number_format($row->cantidad,decimales).'</td>
		<td width="15%" align="center">'.number_format($row->devueltos,decimales).'</td>
		<td width="15%" align="center">'.number_format($row->cantidad-$row->devueltos,decimales).'</td>
	</tr>';
	
	$i++;
}

echo '</table>';
?>
