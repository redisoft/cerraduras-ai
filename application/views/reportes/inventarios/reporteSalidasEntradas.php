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
			<th class="encabezadoPrincipal" colspan="6">Detalles de entradas por compras</th>
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

if($recepciones!=null)
{
	echo'
	<table class="admintable" style="margin-top:3px; width:100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="5">Detalles de entradas por traspasos</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Tienda origen</th>
			<th>Folio</th>
			<th>Cantidad</th>
		</tr>';
	
	$cantidad=0;
	foreach($recepciones as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cantidad	+=$row->cantidad;
		
		echo'
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="center">'.$row->tienda.'</td>
			<td align="center">'.$row->folio.'</td>
			<td align="center">'.round($row->cantidad,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '
		<tr>
			<td colspan="5" class="totales" align="right">Total: '.round($cantidad,2).'</td>
		</tr>
	</table>';
}

//VENTAS
if($registros!=null)
{
	echo '
	<table class="admintable" id="tablaEntradasEntregasInformacion" style="margin-top:3px; width:100%">
		<tr>
			<th class="encabezadoPrincipal sinBordeDerecha" colspan="5">Detalles de entradas por producto no entregado</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Nota</th>
			<th>Comentarios</th>
			<th>Cantidad</th>
		</tr>';
	
	$cantidad	= 0;
	$i			= 1;
	foreach($registros as $row)
	{
		$cantidad	+=$row->noEntregados;
		
		echo'
		<tr>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="center">'.$row->estacion.$row->folio.'</td>
			<td align="left">'.nl2br($row->comentarios).'</td>
			<td align="center">'.round($row->noEntregados,2).'</td>
		</tr>';

		$i++;
	}
	
	echo '
		<tr>
			<td colspan="5" class="totales" align="right">Total: '.round($cantidad,2).'</td>
		</tr>
	</table>';
}


//VENTAS
if($ventas!=null)
{
	echo'
	<table class="admintable" style="margin-top:3px; width:100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="6">Detalles de salidas por ventas</th>
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

if($envios!=null)
{
	$i=1;
	
	echo'
	<table class="admintable" style="margin-top:3px; width:100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="5">Detalles de salidas por traspasos</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Tienda destino</th>
			<th>Folio</th>
			<th>Cantidad</th>
		</tr>';
	
	$cantidad=0;
	foreach($envios as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cantidad	+=$row->cantidad;
		
		echo'
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="center">'.$row->tienda.'</td>
			<td align="center">'.$row->folio.'</td>
			<td align="center">'.round($row->cantidad,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '
		<tr>
			<td colspan="5" class="totales" align="right">Total: '.round($cantidad,2).'</td>
		</tr>
	</table>';
}

if($movimientos!=null)
{
	echo'
	<table class="admintable" style="margin-top:3px; width:100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="6">Ajuste manual</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Cantidad</th>
			<th>Movimiento</th>
			<th>Inventario anterior</th>
			<th>Inventario actual</th>
		</tr>';
	
	$cantidad=0;
	foreach($movimientos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cantidad	+=$row->cantidad;
		echo'
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="center">'.number_format($row->cantidad,2).'</td>
			<td align="center">'.$row->movimiento.'</td>
			<td align="center">'.number_format($row->inventarioAnterior,2).'</td>
			<td align="center">'.number_format($row->inventarioActual,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '
		<tr>
			<td colspan="6" class="totales" align="right">Total: '.round($cantidad,2).'</td>
		</tr>
	</table>';
}

$i=1;
if($diario!=null)
{
	echo'
	<table class="admintable" style="margin-top:3px; width:100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="3">Movimiento diario</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Cantidad</th>
		</tr>';
	
	$cantidad=0;
	foreach($diario as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cantidad	+=$row->stock;
		echo'
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="center">'.number_format($row->stock,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '
		<tr>
			<td colspan="3" class="totales" align="right">Total: '.round($cantidad,2).'</td>
		</tr>
	</table>';
}

