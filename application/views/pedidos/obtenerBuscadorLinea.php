<script>
$(document).ready(function()
{
	$("#txtBuscarProductoLinea").autocomplete(
	{
		source:base_url+'configuracion/obtenerProductosInventario/<?php echo $idLinea?>',
		
		select:function( event, ui)
		{
			cargarProductoPedido(ui.item)
		},
		autoFocus: true,
		focus: function( event, ui ) { event.preventDefault(); },
	});
});
</script>

<input type="text"  name="txtBuscarProductoLinea" id="txtBuscarProductoLinea" class="cajas" placeholder="Buscar por nombre, código"  style="width:400px; "/>