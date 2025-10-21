<?php
$this->load->view('reportes/inventarios/encabezado');

if($inventarios!=null)
{
	echo '<table class="admintable" width="100%">';
	
	$i=1;
	foreach($inventarios as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td width="5%" align="right">'.$i.'</td>
			<td width="13%" align="center">'.$row->codigoInterno.'</td>
			<td width="20%" align="left">'.$row->producto.'</td>
			<td width="10%" align="center">'.$row->linea.'</td>
			<td width="10%" align="center">'.$row->unidad.'</td>
			<td width="7%" align="center">'.$row->stock.'</td>
			<td width="7%" align="right">$'.number_format($row->precioA,2).'</td>
			<td width="7%" align="right">$'.number_format($row->precioA*$row->stock,2).'</td>
			<td width="7%" align="right">$'.number_format($row->precioC,2).'</td>
			<td width="7%" align="right">$'.number_format($row->precioA,2).'</td>
			<td width="7%" align="right">$'.number_format($row->precioB,2).'</td>
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
