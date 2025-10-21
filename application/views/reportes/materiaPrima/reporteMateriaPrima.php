<?php
$this->load->view('reportes/materiaPrima/encabezado');

if($materiaPrima!=null)
{
	echo '<table class="admintable" width="100%">';
	
	$i=1;
	foreach($materiaPrima as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td width="5%" align="right">'.$i.'</td>
			<td width="13%" align="center">'.$row->codigoInterno.'</td>
			<td width="26%" align="left">'.$row->nombre.'</td>
			<td width="13%" align="center">'.$row->proveedor.'</td>
			<td width="13%" align="center">'.$row->unidad.'</td>
			<td width="10%" align="center">'.number_format($row->existencia,decimales).'</td>
			<td width="10%" align="right">$'.number_format($row->costo,2).'</td>
			<td width="10%" align="right">$'.number_format($row->existencia*$row->costo,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de inventarios</div>';
}
?>