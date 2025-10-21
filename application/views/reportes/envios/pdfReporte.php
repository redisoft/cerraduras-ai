<?php
$this->load->view('reportes/envios/encabezado');

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
			<td width="9%" align="center"><?php echo obtenerFechaMesCortoHora($row->fechaCompra)?></td>
			<td width="9%" align="center"><?php echo obtenerFechaMesCortoHora($row->fechaEntrega)?></td>
			<td width="27%" align="left"><?php echo $row->empresa.(strlen($row->observaciones)>1?'<br>'.$row->observaciones:'')?></td>
			<td width="8%" align="left"><?php echo $row->ruta?></td>
			<td width="9%" align="left"><?php echo $row->telefono?></td>
			<td width="9%" align="left"><?php echo $row->estacion.$row->folio?></td>
			<td width="5%" align="center"><?php echo $row->folioTicket?></td>
			<td width="5%" align="center"><?php echo $row->factura?></td>
			<td width="8%" align="right">$ <?php echo number_format($row->total,2)?></td>
			<td width="7%" align="right">$ <?php echo number_format($row->saldo,2)?></td>
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
