<?php

echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Detalles de compra</th>
	</tr>
	<tr>
		<td class="key">Orden:</td>
		<td>'.$compra->nombre.'</td>
	</tr>
	
	<tr>
		<td class="key">Proveedor:</td>
		<td>'.$compra->empresa.'</td>
	</tr>
</table>';


$i			= 1;

if($compras!=null)
{
	echo'
	<table class="admintable" style=" margin-top:2px; width:100%">';
		
		if($compra->totalRecibido==0)
		{
			echo'
			<tr>
				<td colspan="8" style="border:none"></td>
				<td align="center" style="border:none" colspan="2">
					<img src="'.base_url().'img/success.png" title="Recibir todos los productos" onclick="formularioRecibirTodosProductos('.$compra->idCompras.')" width="22" height="22"><br />
					<a>Recibir todo</a>
				</td>
			</tr>';
		}
		
		echo'
		<tr>
			<th colspan="10">Detalles de productos</th>
		</tr>
		
		<tr>
			<th>#</th>
			<th>Producto</th>
			<th>Fecha entrega</th>
			<th>Días</th>
			<th>Cantidad</th>
			<th>Recibidas</th>
			<th>Precio</th>
			<th>Descuento</th>
			<th>Total</th>
			<th>Recibido</th>
		</tr>';
	
	foreach($compras as $row)
	{
		$estilo			= $i%2>0?' class="sinSombra" ':' class="sombreado" ';
		$totalRecibido	= $this->compras->totalRecibido($row->idDetalle);
		$dias			= $this->reportes->obtenerDiasRestantes($row->fechaEntrega);
		
		echo
		'<tr '.$estilo.'>
			<td>'.$i.'</td>
			<td>'.$row->nombre.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fechaEntrega).'</td>
			<td align="center" style="'.($dias<0?'color:red':'').'">'.($dias<0?$dias*-1:$dias).'</td>
			<td align="right">'.number_format($row->cantidad,decimales).'</td>			
			<td align="right">'.number_format($totalRecibido,decimales).'</td>
			<td align="right">$'.number_format($row->precio,decimales).'</td>
			<td align="right">$'.number_format($row->descuento,decimales).'</td>
			<td align="right">$'.number_format($row->total,decimales).'
			<input type="hidden" id="txtProducto'.$row->idDetalle.'" value="'.$row->idMaterial.'" />
			<input type="hidden" id="txtCantidad'.$row->idDetalle.'" value="'.$row->cantidad.'" />
			</td>';
		
		echo '
		<td align="center">';
		
			echo'<img onclick="obtenerProductosRecibidos('.$row->idDetalle.')" src="'.base_url().'img/success.png" width="20" title="Recepción de productos" />
			<br />
			<a>Recibidos</a>';
		
		echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo'</table>';
}