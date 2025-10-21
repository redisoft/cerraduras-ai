<script src="<?php echo base_url()?>js/ventas/entregas.js?v=<?php echo(rand());?>"></script>

<div id="ventanaEntregarProductos" title="Entrega de productos:">
<div id="entregandoProductos"></div>
<div class="ui-state-error" ></div>
    <table class="admintable" width="100%;">
        <tr>
            <td class="key">Fecha:</td>
            <td>
            	<input name="FechaDia" id="FechaDia" type="text" class="cajasSelect" value="<?php echo date("Y-m-d");?>" />
            </td>
        </tr>	
        <tr>
            <td class="key">Cantidad:</td>
            <td>
            	<input type="text" class="cajasSelect" name="txtCantidadEntregar" id="txtCantidadEntregar" />
            </td>
        </tr>	
        <tr>
            <td class="key">Entrego:</td>
            <td>
            	<input type="text" name="txtEntrego" id="txtEntrego" class="cajas" style="width:160px;" /> 
            </td>
        </tr>
    </table>
	<div id="entregaProductos"></div>
</div>

<div style="visibility:hidden">
	<div id="dialog-Entregados" title="Productos entregados:">
		<div id="ErrorEntregados" class="ui-state-error" ></div>
		<div id="productosEntregados"></div>
	</div>
</div>
	
</div>
	
<div id="ventanaEditarEntrega" title="Editar entrega">
	<div id="editandoEntrega"></div>
	<div id="formularioEditarEntrega"></div>
</div>

<div id="ventanaEntregas" title="Entregas">
	<div id="registrandoEntregas"></div>
	<div id="formularioEntregas"></div>
</div>
