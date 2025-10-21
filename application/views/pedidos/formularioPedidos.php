<script>
$(document).ready(function()
{
	$('#txtFechaPedido').datepicker({changeMonth: true, changeYear: true});
	
	$("#txtBuscarProducto").autocomplete(
	{
		source:base_url+'configuracion/obtenerProductosInventario/3',
		
		select:function( event, ui)
		{
			cargarProductoPedido(ui.item)
		},
		autoFocus: true,
		focus: function( event, ui ) { event.preventDefault(); },
		
	});
	
	$("#txtBuscarProductoFrances").autocomplete(
	{
		source:base_url+'configuracion/obtenerProductosInventario/2',
		
		select:function( event, ui)
		{
			cargarProductoPedido(ui.item)
		},
		autoFocus: true,
		focus: function( event, ui ) { event.preventDefault(); },
	});
	
	/*$("#txtBuscarProductoLinea").autocomplete(
	{
		source:base_url+'configuracion/obtenerProductosInventario/100000',
		
		select:function( event, ui)
		{
			cargarProductoPedido(ui.item)
		},
		autoFocus: true,
		focus: function( event, ui ) { event.preventDefault(); },
	});*/
	
	tipoPedido();
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
       	<td>
        	<input type="hidden" id="txtBizcocho" 	value="<?php echo bizcocho?>">
            <input type="hidden" id="txtFrances" 	value="<?php echo frances?>">
            <input type="hidden" id="txtLinea" 		value="<?php echo ''?>">
            
            <select id="selectLineas" name="selectLineas" style="width:300px" class="cajas" onchange="tipoPedido(this.value)">
            	
                <?php
                foreach($lineas as $row)
				{
					echo '<option value="'.$row->idLinea.'">'.$row->nombre.'</option>';
				}
				?>
            </select>
        </td>
    </tr>
    
	<tr>
        <td class="key">Pedido:</td>
        <td><?php echo '<span id="spnPedido">'.bizcocho.'</span>'.$folio?></td>
    </tr>
    
    <tr>
        <td class="key">Usuario:</td>
        <td><?php echo $usuario?></td>
    </tr>
    
     <tr>
        <td class="key">Tienda:</td>
       	<td>
            <select id="selectTiendas" name="selectTiendas" style="width:300px" class="cajas">
            	<option value="0">Matriz</option>
                <?php
                foreach($tiendas as $row)
				{
					echo '<option value="'.$row->idTienda.'">'.$row->nombre.'</option>';
				}
				?>
            </select>
        </td>
    </tr>
    
    <tr>
        <td class="key">Fecha pedido:</td>
       	<td>
            <input type="text"  name="txtFechaPedido" id="txtFechaPedido" class="cajas" style="width:80px;" readonly="readonly" value="<?php echo date('Y-m-d')?>"/>
        </td>
    </tr>

    <tr>
        <td class="key">Comentarios:</td>
        <td>
            <textarea type="text"  name="txtComentarios" id="txtComentarios" class="cajas" style="width:300px; height:40px"></textarea>
        </td>
    </tr>
</table>

<table class="admintable" width="100%">
	<tr>
    	<th colspan="4" class="encabezadoPrincipal">
        	<input type="text"  name="txtBuscarProducto" id="txtBuscarProducto" class="cajas" placeholder="Buscar por nombre, código"  style="width:400px;"/>
            <input type="text"  name="txtBuscarProductoFrances" id="txtBuscarProductoFrances" class="cajas" placeholder="Buscar por nombre, código"  style="width:400px; display:none"/>
            
            <span id="spnProductoLinea">
            	<!--<input type="text"  name="txtBuscarProductoLinea" id="txtBuscarProductoLinea" class="cajas" placeholder="Buscar por nombre, código"  style="width:400px; display:none"/-->>
            </span>
            
            <input type="hidden"  name="txtNumeroProductos" id="txtNumeroProductos" value="0"/>
        </th>
    </tr>
</table>
<table class="admintable" width="100%" id="tablaPedidos">
	<thead>
	<tr>
    	<th width="3%">-</th>
        <th width="20%">Código</th>
        <th width="60%">Producto</th>
        <th>Cantidad</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>
</form>