<?php
$i=1;
echo'
<div id="generandoReporteSalidasEntradas"></div>
<table class="admintable" width="100%">
	<tr>
		<th class="encabezadoPrincipal" colspan="2">Detalles de materia prima</th>
	</tr>
	<tr>
		<td class="key">Materia prima:</td>
		<td>'.$material->nombre.'</td>
	</tr>
	<tr>
		<td class="key">Proveedor:</td>
		<td>'.$material->empresa.'</td>
	</tr>
	<tr>
		<td class="key">Inventario</td>
		<td>'.round($material->inventario-$material->salidas,decimales).'</td>
	</tr>
	
</table>';

if($compras!=null)
{
	echo'
	<table class="admintable" style="margin-top:3px; width:100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="6">Detalles de entradas por compras</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Precio</th>
			<th>Compra</th>
			<th>Cantidad</th>
		</tr>';
	
	$cantidad=0;
	foreach($compras as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cantidad	+=$row->cantidad;
		echo'
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="right">$ '.number_format($row->precio,2).'</td>
			<td align="center">'.$row->nombre.'</td>
			<td align="center">'.round($row->cantidad,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '
		<tr>
			<td colspan="6" class="totales" align="right">Total: '.round($cantidad,2).'</td>
		</tr>
	</table>';
}
else
{
	#echo '<div class="Error_validar">Sin detalle de entradas</div>';
}

if($salidas!=null)
{
	echo'
	<table class="admintable" style="margin-top:3px; width:100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="5">Detalles de salidas</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Cantidad</th>
			<th>Comentarios</th>
			
		</tr>';
	
	$cantidad=0;
	foreach($salidas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cantidad	+=$row->cantidad;
		
		echo'
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="center">'.round($row->cantidad,2).'</td>
			<td align="center">'.$row->comentarios.'</td>
		</tr>';
		
		$i++;
	}
	
	echo '
		<tr>
			<td colspan="5" class="totales" align="right">Total: '.round($cantidad,2).'</td>
		</tr>
	</table>';
}


