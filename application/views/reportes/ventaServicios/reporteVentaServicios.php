<?php
$this->load->view('reportes/ventaServicios/encabezado');

echo '
<table class="admintable" width="100%">
	<tr>
		<th width="3%">#</th>
		<th>Fecha</th>
		<th>Venta</th>
		<th>Servicio</th>
		<th>Cliente</th>
		<th>Periodicidad</th>
		<th>Plazo</th>
		<th>Número ciclos pagados</th>
		<th>Precio</th>
	</tr>';

	$i=1;
	foreach($servicios as $row)
	{
		$estilo			= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$ciclosPagados	= $this->reportes->obtenerCiclosPagados($row->idCotizacion,$row->idProducto);
		$ventas			= $this->reportes->obtenerVentaServiciosDetalle($row->idCotizacion,$row->idProducto);
		
		$subTotal		= $row->importe;
		$descuento		= $row->descuentoPorcentaje>0?$subTotal*($row->descuentoPorcentaje/100):0;
		$iva			= $row->ivaPorcentaje>0?($subTotal-$descuento)*($row->ivaPorcentaje/100):0;
		$total			= $subTotal-$descuento+$iva;
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fechaCompra).'</td>
			<td align="center">'.$row->ordenCompra.'</td>
			<td align="left">'.$row->servicio.'</td>
			<td align="left">'.$row->cliente.'</td>
			<td align="center">'.$row->periodicidad.'</td>
			<td align="center">'.$row->plazo.'</td>
			<td align="center">'.count($ciclosPagados).'</td>
			<td align="right">$'.number_format($total,decimales).'</td>
		</tr>';
		
		foreach($ventas as $ven)
		{
			echo '
			<tr>
				<td align="right"></td>
				<td align="center">'.obtenerFechaMesCorto($ven->fechaCompra).'</td>
				<td align="center">'.($ven->pendiente=='0'?$ven->ordenCompra:'').'</td>
				<td align="left" colspan="5" align="center" style="color: red">';
					
					echo($ven->cancelada=='1'?'<i>Cancelada</i>':'');
					
					if($ven->cancelada=='0')
					{
						if($ven->total==$ven->pagado) echo '<label style="color: red">Cobrado</label>';
						
						if($ven->total>$ven->pagado and $ven->pagado>0) echo '<label style="color: red">Cobrado parcialmente</label>';
					}
				
				echo'
				
				</td>
				<td align="right">$'.number_format($ven->total,decimales).'</td>
			</tr>';
		}
		
		$i++;
		
	}
	
echo '</table>';
?>