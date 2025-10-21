<?php
error_reporting(0);


echo'
<script>
$(document).ready(function()
{
	$("#txtClaveProductoServicio").keypress(function(e)
	 {
		if(e.which == 13) 
		{
			editarProductoActualizar();
		}
	});
	
	$("#txtBuscarProductoCodigoActualizar").keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerProductosCampos(\'codigoInterno\');
		}
	});
	
	$("#txtBuscarProductoNombre").keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerProductosCampos(\'nombre\');
		}
	});
	
	$("#txtUnidad").autocomplete(
	{
		source:"'.base_url().'configuracion/autoCompletadoUnidades",
		select: function(event,ui)
		{
			$("#txtIdUnidad").val(ui.item.idUnidad);
		}
	});
	
	$("#txtClaveProductoServicio").autocomplete(
	{
		source:"'.base_url().'configuracion/autoCompletadoProductoServicios",
		select: function(event,ui)
		{
			$("#txtIdClave").val(ui.item.idClave);
		}
	});
	
	$("#txtBuscarProductoCodigoActualizar1").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerProductosActualizar/codigoInterno",
		autoFocus: true,
		select: function(event,ui)
		{
			$("#txtNombre").val(ui.item.nombre);
			$("#txtIdProducto").val(ui.item.idProducto);
			$("#txtUnidad").val(ui.item.unidad);
			$("#txtIdUnidad").val(ui.item.idUnidad);
			$("#txtPrecioC").val(ui.item.precioC);
			$("#txtPrecioA").val(ui.item.precioA);
			$("#txtPrecioB").val(ui.item.precioB);
			$("#txtApartirB").val(ui.item.cantidadMayoreo);
			$("#txtCodigoInterno").val(ui.item.codigoInterno);
			
			$("#txtInventarioInicial,#txtInventarioActual").val(ui.item.stock);
			$("#txtStockMinimo").val(ui.item.stockMinimo);
			$("#txtStockMaximo").val(ui.item.stockMaximo);
			$("#txtClaveProductoServicio").val(ui.item.claveProducto);
			$("#txtIdClave").val(ui.item.idClave);
			
			$("#txtCostoProducto").val(ui.item.costo);
			
			setTimeout(function()
			{
				$("#txtBuscarProductoCodigoActualizar").val("");
				$("#txtNombre").focus();
			},300);
		}
	});
	
	$("#txtBuscarProductoNombre1").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerProductosActualizar/nombre",
		autoFocus: true,
		select: function(event,ui)
		{
			$("#txtNombre").val(ui.item.nombre);
			$("#txtIdProducto").val(ui.item.idProducto);
			$("#txtUnidad").val(ui.item.unidad);
			$("#txtIdUnidad").val(ui.item.idUnidad);
			$("#txtPrecioC").val(ui.item.precioC);
			$("#txtPrecioA").val(ui.item.precioA);
			$("#txtPrecioB").val(ui.item.precioB);
			$("#txtApartirB").val(ui.item.cantidadMayoreo);
			$("#txtCodigoInterno").val(ui.item.codigoInterno);
			
			$("#txtInventarioInicial,#txtInventarioActual").val(ui.item.stock);
			$("#txtStockMinimo").val(ui.item.stockMinimo);
			$("#txtStockMaximo").val(ui.item.stockMaximo);
			$("#txtClaveProductoServicio").val(ui.item.claveProducto);
			$("#txtIdClave").val(ui.item.idClave);
			
			$("#txtCostoProducto").val(ui.item.costo);
			
			setTimeout(function()
			{
				$("#txtBuscarProductoNombre").val("");
				$("#txtNombre").focus();
			},300);
		}
	});
});
</script>

<div class="ui-state-error" ></div>
<form id="frmEditarProducto" name="frmEditarProducto">
	<input type="hidden" id="txtRegistroSucursales" name="txtRegistroSucursales" value="0">
	
	<table class="admintable" width="100%">
		
			<tr>
				<td colspan="2" align="center">
					<input type="text" class="cajas" name="txtBuscarProductoCodigoActualizar" id="txtBuscarProductoCodigoActualizar" style="width: 300px; height: 25px; font-size: 14px " placeholder="Código" />
					
					<input type="text" class="cajas" name="txtBuscarProductoNombre" id="txtBuscarProductoNombre" style="width: 300px; height: 25px; font-size: 14px" placeholder="Nombre"  />
				</td>
			<tr>
			<td class="key">Nom:</td>
			<td>';
				
				echo"<input type='text' class='cajas' style='width:98%' id='txtNombre' name='txtNombre' />";
				
				echo'
				<input type="hidden" id="txtIdProducto" name="txtIdProducto" value="0" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Med:</td>
			<td>
				<input type="text" class="cajas" name="txtUnidad" id="txtUnidad" style="width: 300px" placeholder="Seleccione" value="" />
				<input type="hidden" id="txtIdUnidad" name="txtIdUnidad" value="" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Costo actual:</td>
			<td>
				<input type="text" class="cajas" name="txtCostoProducto" id="txtCostoProducto" style="width: 300px"  value="" />
			</td>
		</tr>
		
		<tr>
			<td class="key">'.obtenerNombrePrecio(3).':</td>
			<td>
				<input type="text" class="cajas" name="txtPrecioC" value="" id="txtPrecioC" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="actualizarPorcentajesPrecios()"/>
				
				&nbsp;&nbsp;
				<label id="lblPorcentajeC">0%</label>
				
				<input type="hidden" class="cajas" name="txtPrecioCActual" id="txtPrecioCActual" value="0"/>
				<input type="hidden" class="cajas" name="txtPrecioAActual" id="txtPrecioAActual" value="0"/>
				<input type="hidden" class="cajas" name="txtPrecioBActual" id="txtPrecioBActual" value="0"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">'.obtenerNombrePrecio(1).':</td>
			<td>
				<input type="text" class="cajas" name="txtPrecioA" value="" id="txtPrecioA" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="actualizarPorcentajesPrecios()"/>
				
				&nbsp;&nbsp;
				<label id="lblPorcentajeA">0%</label>
			</td>
		</tr>
	
		<tr>
			<td class="key">'.obtenerNombrePrecio(2).':</td>
			<td>
				<input type="text" class="cajas" name="txtPrecioB" id="txtPrecioB" value="" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="actualizarPorcentajesPrecios()"/>
				
				&nbsp;&nbsp;
				<label id="lblPorcentajeB">0%</label>';
			
			if(sistemaActivo=='cerraduras')
			{
				echo ' &nbsp;&nbsp;&nbsp;&nbsp; A partir de: <input type="text" class="cajas" name="txtApartirB" id="txtApartirB" style="width:60px" onkeypress="return soloDecimales(event)" value="" maxlength="7"/>';
			}
				
			echo'
			
			</td>
		</tr>
		
		
		<tr >
			<td class="key">CVE de Fabrica:</td>
			<td>
			  <input type="text" name="txtCodigoInterno" value="" id="txtCodigoInterno" class="cajas" style="width:30%;" /> 
			</td>
		</tr>
		
		<tr>
			<td class="key">Existencias:</td>
			<td>
				<input type="text" name="txtInventarioInicial" value="" id="txtInventarioInicial" class="cajas" style="width:20%;" /> 
				<input type="hidden" name="txtInventarioActual" value="0" id="txtInventarioActual" /> 
			</td>
		</tr>
		
		<tr>
			<td class="key">Exist mínimo:</td>
			<td>
				<input type="text" class="cajas" name="txtStockMinimo" id="txtStockMinimo" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" value=""/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Exist máximo:</td>
			<td>
				<input type="text" class="cajas" name="txtStockMaximo" id="txtStockMaximo" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" value=""/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Clave S.A.T:</td>
			<td>
				<input type="text" 		class="cajas" id="txtClaveProductoServicio" name="txtClaveProductoServicio" placeholder="Seleccione" value="" style="width:300px"/>
				<input type="hidden" 	id="txtIdClave" name="txtIdClave" value="" />
			</td>
		</tr>
	</table>
</form>';
