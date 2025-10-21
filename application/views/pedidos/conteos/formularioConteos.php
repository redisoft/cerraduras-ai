<script>
$(document).ready(function()
{
	$('#txtFechaPedido').datepicker({changeMonth: true, changeYear: true});
	
	$("#txtBuscarProducto").autocomplete(
	{
		source:base_url+'configuracion/obtenerProductosInventario/0',
		
		select:function( event, ui)
		{
			cargarProductoConteo(ui.item)
		},
		autoFocus: true,
		focus: function( event, ui ) { event.preventDefault(); },
		
	});
});
</script>
<form id="frmConteos">
<table class="admintable" width="100%">
    
	<tr>
        <td class="key">Conteo:</td>
        <td><?php echo conteos.$folio?></td>
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
            <input type="hidden"  name="txtNumeroProductos" id="txtNumeroProductos" value="<?php echo count($productos)?>"/>
        </th>
    </tr>
</table>
<table class="admintable" width="100%" id="tablaConteos">
	<thead>
        <tr>
            <th width="3%">-</th>
            <th width="20%">Código</th>
            <th width="60%">Producto</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
    	<?php
		$i=0;
        foreach($productos as $row)
		{
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td></td>
				<td>'.$row->codigoInterno.'</td>
				<td>'.$row->nombre.'</td>
				<td align="center">
					<input type="text" class="cajas" id="txtCantidadConteo'.$i.'" name="txtCantidadConteo'.$i.'" onkeypress="return soloDecimales(event)" maxlength="8" style="width:80px;"/>
					<input type="hidden"  id="txtIdProducto'.$i.'" name="txtIdProducto'.$i.'" value="'.$row->idProducto.'"/>
				</td>
			</tr>';
			
			$i++;
		}
		?>
    
    </tbody>
</table>
</form>