<script>
$(document).ready(function()
{
	$("#txtBuscarProductoLista").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerProductosLista();
		}, 500);
	});
	
	$('#txtFechaInicialRegistro,#txtFechaFinalRegistro').datepicker();
});
</script>

<form id="frmListas" action="javascript:registrarLista()">
    <div class="ui-state-error" ></div>
    <div id="registrandoLista"></div>
    <table class="admintable" width="100%">
    	 <tr>
            <td class="key">Nombre:</td>
            <td>
            	<input type="text" class="cajas"  id="txtNombreLista" name="txtNombreLista" style="width:300px" required="true" />
            </td>
        </tr>

		<tr>
            <td class="key">% Descuento:</td>
            <td>
            	<input type="number" class="cajas"  id="txtPorcentajeDescuento" name="txtPorcentajeDescuento" style="width:100px" max="99.99" min="0.1" step="any" required="true"/>
               
            </td>
        </tr>
        
    	<tr style="display: none">
            <td class="key">Fecha inicial:</td>
            <td>
            	<input type="text" class="cajas"  id="txtFechaInicialRegistro" name="txtFechaInicialRegistro" style="width:100px"  value="<?php echo date('Y-m-d')?>"/>
                &nbsp;&nbsp;
                <input type="checkbox" id="chkVigencia" name="chkVigencia" value="1"  onchange="configurarFechaFinal()" />
                <label>Vigencia</label>
            </td>
        </tr>
        
        <tr style="display:none" id="filaFechaFinal">
            <td class="key">Fecha final:</td>
            <td>
            	<input type="text" class="cajas"  id="txtFechaFinalRegistro" name="txtFechaFinalRegistro" style="width:100px"  value="<?php echo date('Y-m-d')?>"/>
            </td>
        </tr>

        <tr>
            <td align="center" colspan="2">
                <input type="text" class="cajas"  id="txtBuscarProductoLista" style="width:500px"  placeholder="Buscar producto" />
                <input type="hidden" id="txtNumeroProductosLista" name="txtNumeroProductosLista" value="0" />
                &nbsp;&nbsp;
            </td>
        </tr>
    </table>
    <div id="obtenerProductosLista" style="overflow:scroll; height:260px; overflow-x: hidden; overflow-x: auto"></div>
    
    <table class="admintable" id="tablaLista" width="100%">
        <tr>
            <th class="encabezadoPrincipal" width="3%">-</th>
            <th class="encabezadoPrincipal" width="17%">Código interno</th>
            <th class="encabezadoPrincipal" width="25%">Producto</th>
            <th class="encabezadoPrincipal" width="25%">Línea</th>
            <th class="encabezadoPrincipal" width="15%">Precio</th>
            <th class="encabezadoPrincipal" width="15%" style="display: none">Precio nuevo</th>
        </tr>
    </table>
</form>
