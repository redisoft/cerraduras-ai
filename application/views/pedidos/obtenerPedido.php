<script>
$(document).ready(function()
{
	$('#txtFechaPedido').datepicker({changeMonth: true, changeYear: true});
	
	$("#txtBuscarProducto").autocomplete(
	{
		source:base_url+'configuracion/obtenerProductosInventario/<?php echo $pedido->idLinea?>',
		
		select:function( event, ui)
		{
			cargarProductoPedido(ui.item)
		}
	});
});
</script>
<form id="frmPedidos">
<table class="admintable" width="100%">
	<tr>
    	<th colspan="2" class="encabezadoPrincipal">
        	Detalles de orden
        </th>
    </tr>
    <tr>
        <td class="key">Línea:</td>
       <td><?php echo $pedido->linea?></td>
    </tr>
    
	<tr>
        <td class="key">Pedido:</td>
       <td>
	   	<?php 
	   	if($pedido->idLinea==2) echo frances;
		if($pedido->idLinea==3) echo bizcocho;
	   	echo $pedido->folio?>
        
         <input type="hidden" id="selectLineas" name="selectLineas"	value="<?php echo $pedido->idLinea?>">
        </td>
    </tr>
    
    <tr>
        <td class="key">Usuario:</td>
        <td><?php echo $pedido->usuario?></td>
    </tr>
    
     <tr>
        <td class="key">Tienda:</td>
       	<td>
            <select id="selectTiendas" name="selectTiendas" style="width:300px" class="cajas">
            	<option value="0">Matriz</option>
                <?php
                foreach($tiendas as $row)
				{
					echo '<option '.($row->idTienda==$pedido->idTienda?'selected="selected"':'').' value="'.$row->idTienda.'">'.$row->nombre.'</option>';
				}
				?>
            </select>
        </td>
    </tr>
    
    <tr>
        <td class="key">Fecha pedido:</td>
       	<td>
            <input type="text"  name="txtFechaPedido" id="txtFechaPedido" class="cajas" style="width:80px;" readonly="readonly" value="<?php echo $pedido->fechaPedido?>"/>
        </td>
    </tr>

    <tr>
        <td class="key">Comentarios:</td>
        <td>
            <textarea type="text"  name="txtComentarios" id="txtComentarios" class="cajas" style="width:300px; height:40px"><?php echo $pedido->comentarios?></textarea>
        </td>
    </tr>
</table>

<table class="admintable" width="100%" id="tablaPedidos">
	<thead>
	<tr>
    	<th colspan="5" class="encabezadoPrincipal">
        	<input type="text"  	name="txtBuscarProducto" id="txtBuscarProducto" class="cajas" placeholder="Buscar por nombre, código"  style="width:400px;"/>
            <input type="hidden"  name="txtNumeroProductos" id="txtNumeroProductos" value="<?php echo count($productos)?>"/>
            <input type="hidden"  name="txtIdPedido" id="txtIdPedido" value="<?php echo $pedido->idPedido?>"/>
        </th>
    </tr>
	<tr>
    	<th width="3%">-</th>
        <th width="20%">Código</th>
        <th width="<?php echo $pedido->idLinea==4?'50%':'60%'?>">Producto</th>
        <th>Cantidad</th>
        <?php echo $pedido->idLinea==4?'<th></th>':''?>
    </tr>
    </thead>
    
    <tbody>
    
    <?php
	$i=0;
    foreach($productos as $row)
	{
		echo '
		<tr id="filaPedido'.$i.'" '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
			<td><img src="'.base_url().'img/borrar.png" width="18" onclick="quitarProductoPedido('.$i.')"/></td>
			<td>'.$row->codigoInterno.'</td>
			<td>'.$row->producto.'</td>
			<td align="center"> <input type="text"  name="txtCantidadPedido'.$i.'" id="txtCantidadPedido'.$i.'" class="cajas" style="width:100px;" value="'.round($row->cantidad,decimales).'" onkeypress="return soloDecimales(event)"/></td>
			<input type="hidden"  name="txtIdProducto'.$i.'" id="txtIdProducto'.$i.'" value="'.$row->idProducto.'" />';
		
			if($pedido->idLinea==4)
			{
				echo '<td align="center"> <input type="text"  name="txtPesoPedido'.$i.'" id="txtPesoPedido'.$i.'" class="cajas" style="width:100px;" value="'.round($row->peso,decimales).'" onkeypress="return soloDecimales(event)"/></td>';
			}
			
		echo'
		</tr>';
		
		$i++;
	}
	?>
    </tbody>
</table>
</form>