<?php
if($idRol!=5)
{
	?>
	<table class="admintable" width="100%">
		<tr>
			<th colspan="2" class="encabezadoPrincipal">
				Detalles de pedido
			</th>
		</tr>
        <tr>
            <td class="key">Línea:</td>
           <td><?php echo $pedido->linea?></td>
        </tr>
		<tr>
			<td class="key">Pedido:</td>
			<td><?php echo ($pedido->idLinea==2?frances:bizcocho).$pedido->folio?></td>
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
    	<th colspan="<?php echo $idRol!=5?'4':'1'?>" class="encabezadoPrincipal">
        	Detalles de productos
        </th>
    </tr>
	<tr>
        <th width="40%">Producto</th>
        <?php
        if($idRol!=5)
		{
		 	echo '
			<th>Cantidad pedido</th>
			<th>Cantidad producido</th>
			<th>Diferencia</th>';
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
			<td>'.$row->producto.'</td>
			'.($idRol!=5?'<td align="center">'.number_format($row->cantidad,decimales).'</td>
			<td align="center">'.number_format($row->producido,decimales).'</td>
			<td align="center">'.number_format($row->producido-$row->cantidad,decimales).'</td>':'').'
		</tr>';
		
		$i++;
	}
	?>
</table>
