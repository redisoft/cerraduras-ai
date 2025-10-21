<table class="admintable" width="100%">
	<tr>
    	<th colspan="2" class="encabezadoPrincipal">
        	Detalles de producto
        </th>
    </tr>
	<tr>
        <td class="key">Ordeb:</td>
        <td><?php echo pedidos.$producto->folio?></td>
    </tr>
    
    <tr>
        <td class="key">Producto:</td>
        <td><?php echo $producto->producto?></td>
    </tr>
</table>

<table class="admintable" width="100%" id="tablaPedidos">
	<tr>
    	<th colspan="3" class="encabezadoPrincipal">
        	Detalles de producido
            <input type="hidden"  name="txtIdDetalle" id="txtIdDetalle" value="<?php echo $producto->idDetalle?>"/>
        </th>
    </tr>
	<tr>
        <th>Fecha</th>
        <th>Cantidad</th>
        <th>Acciones</th>
    </tr>
    
    <?php
	$i=0;
    foreach($producidos as $row)
	{
		echo '
		<tr id="filaPedido'.$i.'" '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">
				 <input type="text" class="cajas" style="width:100px" maxlength="10"  name="txtCantidadProducido'.$row->idProducido.'" id="txtCantidadProducido'.$row->idProducido.'" value="'.round($row->cantidad,decimales).'"/>
			</td>
			<td align="center">
				<img src="'.base_url().'img/editar.png" width="22" onclick="accesoEditarProducidoProducto('.$row->idProducido.')" width="22" />
				<img src="'.base_url().'img/borrar.png" width="22" onclick="accesoBorrarProducidoProducto('.$row->idProducido.')" width="22" />
				<br />
				<a>Editar</a>
				<a>Borrar</a>
			</td>
		</tr>';
		
		$i++;
	}
	?>
</table>
