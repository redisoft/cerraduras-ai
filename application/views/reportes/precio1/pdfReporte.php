<?php
$this->load->view('reportes/precio1/encabezado');

if($registros!=null)
{
	echo '<table class="admintable" width="100%">';
	$i=1;
	$total=0;
	foreach($registros as $row)
	{
		$impuestos	= $row->importe*($row->ivaPorcentaje/100);
		?>
		<tr <?php echo $i%2>0?'class="sinSombra"':'class="sombreado"'?>> 
			<td width="3%" align="right"><?php echo $i?></td>
			<td width="7%" align="center"><?php echo obtenerFechaMesCortoHora($row->fechaCompra)?></td>
			<td width="15%" align="left"><?php echo $row->empresa?></td>
			<td width="10%" align="center"><?php echo $row->folio?></td>
			<td width="7%" align="center"><?php echo $row->estacion?></td>
			<td width="20%" align="left"><?php echo $row->producto?></td>
			<td width="10%" align="center"><?php echo $row->usuario?></td>
			<td width="7%" align="center"><?php echo $row->formaPago?></td>
			<td width="7%" align="right">$<?php echo number_format($row->importe,2)?></td>
			<td width="7%" align="right">$<?php echo number_format($impuestos,2)?></td>
			<td width="7%" align="right">$ <?php echo number_format($row->importe+$impuestos,2)?></td>
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
