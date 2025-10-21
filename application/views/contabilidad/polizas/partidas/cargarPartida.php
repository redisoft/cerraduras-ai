<?php
echo '

<tr id="filaPartida'.$par.'">
	<td align="center"><img src="'.base_url().'img/borrar.png" width="22" onclick="quitarPartida('.$par.')" /></td>
	<td align="center" id="numeroPartida'.$par.'"></td>
	<td align="center"><input type="text" class="cajas" id="txtBuscarNumeroCuenta'.$par.'" name="txtBuscarNumeroCuenta'.$par.'" style="width:150px" /></td>
	<td align="center"><input type="text" class="cajas" id="txtBuscarNombreCuenta'.$par.'" name="txtBuscarNombreCuenta'.$par.'" style="width:150px" /></td>
	<td align="center"><input type="text" class="cajas" id="txtConcepto'.$par.'" name="txtConcepto'.$par.'" style="width:200px" maxlength="300"/></td>
	<td align="right"><input type="text" class="cajas cajasDerecha" id="txtCargo'.$par.'" name="txtCargo'.$par.'" style="width:100px" onchange="sumarPartidas()" onkeypress="return soloDecimales(event)" maxlength="15" value="0"/></td>
	<td align="right"><input type="text" class="cajas cajasDerecha" id="txtAbono'.$par.'" name="txtAbono'.$par.'" style="width:100px" onchange="sumarPartidas()" onkeypress="return soloDecimales(event)" maxlength="15" value="0"/></td>
	
	<input type="hidden" id="txtPartida'.$par.'"	 		name="txtPartida'.$par.'" 		value="'.$par.'" />
	<input type="hidden" id="txtIdCuentaCatalogo'.$par.'" 	name="txtIdCuentaCatalogo'.$par.'" 	value="0" />
</tr>

<script>
$(document).ready(function()
{
	$("#txtBuscarNumeroCuenta'.$par.'").autocomplete(
	{
		source:"'.base_url().'contabilidad/obtenerCuentasContablesFiltro/numeroCuenta",
		
		select:function( event, ui)
		{
			$("#txtIdCuentaCatalogo'.$par.'").val(ui.item.idCuentaCatalogo)
			
			window.setTimeout(function() 
			{
				$("#txtBuscarNumeroCuenta'.$par.'").val(ui.item.numeroCuenta)
				$("#txtBuscarNombreCuenta'.$par.'").val(ui.item.descripcion)
			}, 100);  
		}
	});
	
	$("#txtBuscarNombreCuenta'.$par.'").autocomplete(
	{
		source:"'.base_url().'contabilidad/obtenerCuentasContablesFiltro/nombreCuenta",
		
		select:function( event, ui)
		{
			$("#txtIdCuentaCatalogo'.$par.'").val(ui.item.idCuentaCatalogo)
			
			window.setTimeout(function() 
			{
				$("#txtBuscarNumeroCuenta'.$par.'").val(ui.item.numeroCuenta)
				$("#txtBuscarNombreCuenta'.$par.'").val(ui.item.descripcion)
			}, 100);  		
		}
	});
});

</script>';