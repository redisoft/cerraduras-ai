<form id="frmPedidos">
<input type="hidden" id="lineaPedido" name="lineaPedido"	value="<?php echo $pedido->idLinea?>">
<?php
if($idRol!=5)
{
	?>
	<table class="admintable" width="100%">
		<tr>
			<th colspan="2" class="encabezadoPrincipal">
				Detalles de orden
			</th>
		</tr>
		<tr>
			<td class="key">Orden de producción:</td>
			<td>
			<?php 
			if($pedido->idLinea==2) echo frances;
			if($pedido->idLinea==3) echo bizcocho;
			echo $pedido->folio;
			?>
            
            
            </td>
		</tr>
		
		<tr>
			<td class="key">Usuario:</td>
			<td><?php echo $pedido->usuario?></td>
		</tr>
		
		 <tr>
			<td class="key">Tienda:</td>
			<td><?php echo $pedido->tienda?></td>
		</tr>
		
		<tr>
			<td class="key">Fecha pedido:</td>
			<td>
				<?php echo obtenerFechaMesCorto($pedido->fechaPedido)?>
			</td>
		</tr>
	
		<tr>
			<td class="key">Comentarios:</td>
			<td>
            	<?php echo nl2br($pedido->comentarios)?>
			</td>
		</tr>
	</table>
	<?php
	}
?>

<table class="admintable" width="100%" id="tablaPedidos">
	<tr>
    	<th colspan="10" class="encabezadoPrincipal">
        	Detalles de productos
            <input type="hidden"  name="txtNumeroProductos" id="txtNumeroProductos" value="<?php echo count($productos)?>"/>
            <input type="hidden"  name="txtIdPedido" id="txtIdPedido" value="<?php echo $pedido->idPedido?>"/>
        </th>
    </tr>
	<tr>
    	<th width="3%">#</th>
        <th width="35%">Producto</th>
        <?php
        if($idRol!=5)
		{
		 	echo '
			<th>Cantidad pedido</th>
			'.($pedido->idLinea==4?'<th>Peso en kg</th>':'').'
			<th>Cantidad producido</th>
			<th>Diferencia</th>
			<th>Cantidad mermas</th>';
		}
		?>
        <th>Cantidad a producir</th>
        <?php
        if($pedido->idLinea==4)
		{
			echo '<th>Peso en kg</th>';
		}
		
		if($idRol!=5)
		{
			echo '<th>Merma</th>';
		}
		?>
        
       
    </tr>
    
    <?php
	$i=0;
    foreach($productos as $row)
	{
		echo '
		<input type="hidden"  name="txtIdDetalle'.$i.'" id="txtIdDetalle'.$i.'" value="'.$row->idDetalle.'" />
		
		<tr id="filaPedido'.$i.'" '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
			<td>'.($i+1).'</td>
			<td>'.$row->producto.'</td>
			'.($idRol!=5?'
			<td align="center">'.number_format($row->cantidad,decimales).'</td>
			
			'.($pedido->idLinea==4?'
			<td align="center">'.number_format($row->peso,decimales).'</td>':'').'
			
			<td align="center">'.number_format($row->producido,decimales).'
			'.($row->producido-$row->cantidad>0?'<img src="'.base_url().'img/producido.png" width="18" onclick="obtenerProducidosProducto('.$row->idDetalle.')" title="Producidos"/>':'').'</td>
			<td align="center">'.number_format($row->producido-$row->cantidad,decimales).'</td>
			<td align="center">'.number_format($row->mermas,decimales).'</td> ':'').'
			<td align="center"> <input type="text"  	name="txtCantidadProducir'.$i.'" id="txtCantidadProducir'.$i.'" class="cajas" style="width:100px;" value="" onkeypress="return soloDecimales(event)"/></td>
			
			'.($pedido->idLinea==4?'
			<td align="center"><input type="text"  	name="txtPesoProducir'.$i.'" id="txtPesoProducir'.$i.'" class="cajas" style="width:100px;" value="" onkeypress="return soloDecimales(event)"/></td>':'');
			
			if($idRol!=5)
			{
				echo'
				<td>
					<input type="text"  	name="txtMerma'.$i.'" id="txtMerma'.$i.'" class="cajas" style="width:80px;" value="" onkeypress="return soloDecimales(event)"/></td>
				</td>';
			}
			
			
			
			
		echo'
		</tr>';
		
		$i++;
	}
	?>
</table>
</form>
