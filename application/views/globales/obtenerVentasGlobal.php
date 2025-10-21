<?php

if($ventas!=null)
{
	echo '
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagGlobal">'.$this->pagination->create_links().'</ul>
	</div>
	
	<div id="generandoReporte"></div>
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="8" align="right">Total $'.number_format($total->total,decimales).'</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Estación</th>
			<th>Folio venta</th>
			<th>Folio</th>
			<th>Total</th>
			<th>Forma de pago</th>
			<th>Facturada</th>
		</tr>';

	$i=1;
	foreach($ventas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td align="right">' .$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fechaCompra).'</td>
			<td align="left">' .$row->estacion.'</td>
			<td align="center">'.$row->folio.'</td>
			<td align="center">'.$row->folioConta.'</td>
			<td align="right">$'.number_format($row->total,decimales).'</td>
			<td align="center">'.$row->formaPago.'</td>
			<td align="center">'.($row->facturas>0?'Si':'No').'</td>
			
		</tr>';
		
		$i++;
		
	}
	
	echo '</table>
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagGlobal">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registros</div>';
}