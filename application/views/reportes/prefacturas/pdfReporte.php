<?php
$this->load->view('reportes/prefacturas/encabezado');

if($registros!=null)
{
	echo '<table class="admintable" width="100%">';
	$i=1;
	$total=0;
	foreach($registros as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		?>
		<tr <?php echo $estilo?>>
			<td width="4%"><?php echo $i?></td>
			<td width="15%" align="center"><?php echo obtenerFechaMesCorto($row->fechaCompra)?></td>
			<td width="15%" align="left"><?php echo $row->folio?></td>
			<td width="15%" align="center"><?php echo obtenerFechaMesCorto($row->fechaRemision)?></td>
			<td width="15%" align="left"><?php echo $row->folioRemision?></td>
			<td width="26%" align="left"><?php echo $row->empresa?></td>
			<td width="10%" align="right">$ <?php echo number_format($row->total,2)?></td>
		</tr>

		<?php
		$i++;
	}

	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registros</div>';
}
?>