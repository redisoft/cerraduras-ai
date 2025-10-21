<?php
$this->load->view('reportes/inventarios/encabezadoSalidasEntradas');
$i=1;
echo'
<table class="admintable" width="100%">
	<tr>
		<th class="encabezadoPrincipal" colspan="2">Detalles de producto</th>
	</tr>
	<tr>
		<td class="key">Código</td>
		<td>'.$producto->codigoInterno.'</td>
	</tr>
	<tr>
		<td class="key">Producto</td>
		<td>'.$producto->nombre.'</td>
	</tr>
	<tr>
		<td class="key">Unidad</td>
		<td>'.$producto->unidad.'</td>
	</tr>
	<tr>
		<td class="key">Línea</td>
		<td>'.$producto->linea.'</td>
	</tr>
	<tr>
		<td class="key">Inventario</td>
		<td>'.$producto->stock.'</td>
	</tr>
</table>';

if($compras!=null)
{
	echo'
	<table class="admintable" style="margin-top:3px; width:100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="6">Detalles de entradas</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Precio</th>
			<th>Orden</th>
			<th>Proveedor</th>
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
			<td align="left">'.$row->proveedor.'</td>
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


//VENTAS
if($ventas!=null)
{
	echo'
	<table class="admintable" style="margin-top:3px; width:100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="6">Detalles de salidas</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Precio</th>
			<th>Orden</th>
			<th>Cliente</th>
			<th>Cantidad</th>
		</tr>';
	
	$i			= 1;
	$cantidad 	= 0;
	foreach($ventas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cantidad	+=$row->cantidad;
		
		echo'
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="right">$ '.number_format($row->precio,2).'</td>
			<td align="center">'.$row->ordenCompra.'</td>
			<td align="left">'.$row->empresa.'</td>
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

