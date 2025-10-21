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



$i=1;
			
echo '<input type="hidden" id="txtIdCompraRecibido" value="'.$idCompras.'" />';

if($compras!=null)
{
	echo'
	<table class="admintable" style="width:100%">';
		
		if($compra->totalRecibido==0 and $compra->cerrada=='0')
		{
			echo'
			<tr>
				<td colspan="8" style="border:none"></td>
				<td align="center" style="border:none" colspan="2">
					<img src="'.base_url().'img/success.png" title="Recibir todos los productos" onclick="formularioRecibirTodosMateriales('.$compra->idCompras.')" width="22" height="22"><br />
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
		$iva			= $compra->ivaPorcentaje>0?$row->total*($compra->ivaPorcentaje/100):0;
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
		
		if($row->totalRecibido == $row->cantidad)
		{
			#echo '<img src="'.base_url().'img/success.png" width="20" title="Se ha recibido completamente"';
		}
		else
		{
			/*echo('<input type="checkbox" id="chkCompras'.$row->idDetalle.'" value="'.$row->idDetalle.'" 
			onchange="confirmarRecibirCompra('.$row->idDetalle.','.$row->idCompra.',\''.$row->cantidad.'\',\''.$row->totalRecibido.'\')" />');*/
			
			#echo '<img src="'.base_url().'img/success.png" width="20" title="Se ha recibido completamente"';
		}
		
		echo '<img id="btnRecibir'.$i.'" onclick="productosRecibidos('.$row->idDetalle.')" src="'.base_url().'img/success.png" width="20" title="Recibiendo compras" id="btnRecibirProductos'.$i.'" /><br />
		<a id="a-btnRecibir'.$i.'">Recibido</a>';
		
		if($compra->cerrada=='1')
		{
			echo '
			<script>
				desactivarBotonSistema(\'btnRecibir'.$i.'\');
			</script>';
		}
		
		echo'
		</td>
		</tr>';
		
		$i++;
	}
	
	echo'</table>';
}