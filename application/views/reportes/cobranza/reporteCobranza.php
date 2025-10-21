<?php
$this->load->view('reportes/cobranza/encabezado');

if($ventas!=null)
{
	echo '<table class="admintable" width="100%">';
	$i=1;
	$total=0;
	foreach($ventas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$dias		=0;
		
		if($row->idFactura>0)
		{
			$dias	=$this->reportes->obtenerDiasRestantes($row->fechaVencimiento);
		}
		
		$dias	=$dias<0?'<label style="color:red">'.($dias*-1).'</label>':$dias;
		
		?>
		<tr <?php echo $estilo?>>
			<td width="4%"><?php echo $i?></td>
			<td width="10%" align="center"><?php echo obtenerFechaMesCorto($row->fechaCompra)?></td>
			<td width="20%" align="left"><?php echo $row->empresa?></td>
			<td width="12%" align="left"><?php echo $row->telefono?></td>
			<td width="10%" align="left"><?php echo $row->ordenCompra?></td>
			<td width="15%" align="center"><?php echo obtenerFechaMesCorto($row->fechaVencimiento)?></td>
			<td width="13%" align="center">
			<?php echo $dias;?>
			</td>
			<td width="16%" align="right">$ <?php echo number_format($row->saldo,2)?></td>
		</tr>

		<?php
		$i++;
	}

	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de cobranza</div>';
}
?>