<?php

$cantidad=0;

if($ventas!=null)
{
	foreach($ventas as $row)
	{
		$cantidad+=$row->cantidad;
	}
}

if($envios!=null)
{
	foreach($envios as $row)
	{
		if($row->idCotizacion==0)
		{
			$cantidad+=$row->cantidad;	
		}
		
	}
}


if($recepciones!=null)
{
	foreach($recepciones as $row)
	{
		$cantidad-=$row->cantidad;
	}
}

if($compras!=null)
{
	foreach($compras as $row)
	{
		$cantidad-=$row->cantidad;
	}
}


$i=1;
echo'
<div id="generandoReporteSalidasEntradas"></div>
<input type="hidden" id="txtIdProductoInventario" value="'.$idProducto.'" />
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
		<td>'.round($producto->stock,4).'</td>
	</tr>
	<tr>
		<td class="key">Inventario inicial</td>
		<td>'.round($producto->stock==0?$producto->stock-$cantidad:0,4).'</td>
	</tr>';
	
	if($compras!=null or $ventas!=null or $recepciones!=null or $envios!=null)
	{
		echo '
		<tr>
			<th class="encabezadoPrincipal" colspan="2">
				Detalles de entradas y salidas
				
				<img id="btnExportarPdfReporteInformacion" src="'.base_url().'img/pdf.png" width="22" height="22" onclick="reporteSalidasEntradas()" />
				<img id="btnExportarExcelReporteInformacion" src="'.base_url().'img/excel.png" width="22" height="22" onclick="excelSalidasEntradas()" />';
			
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnExportarPdfReporteInformacion\');
						desactivarBotonSistema(\'btnExportarExcelReporteInformacion\');
					</script>';
				}
			
			echo'  
			</th>
		</tr>';	
	}

echo'
</table>

<div id="obtenerComprasInformacion">';

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
else
{
	#echo '<div class="Error_validar">Sin detalle de entradas</div>';
}

echo'</div>

<div id="obtenerEntradasTraspasos">';

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
	$i=1;
	foreach($recepciones as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cantidad	+=$row->cantidad;
		
		echo'
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="center">'.$row->sucursal.'</td>
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

echo '</div>

<div id="obtenerEntradasEntregas">
	
</div>

<div id="obtenerVentasInformacion">';



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
else
{
	#echo '<div class="Error_validar">Sin detalle de salidas</div>';
}

echo '</div>

<div id="obtenerEnviosInformacion">';


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
			<td align="center">'.$row->sucursal.'</td>
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

echo '</div>

<div id="obtenerMovimientosInformacion">';

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
else
{
	#echo '<div class="Error_validar">Sin detalle de entradas</div>';
}

echo '</div>

<div id="obtenerDiarioInformacion">';

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
else
{
	#echo '<div class="Error_validar">Sin detalle de entradas</div>';
}

echo '</div>';
