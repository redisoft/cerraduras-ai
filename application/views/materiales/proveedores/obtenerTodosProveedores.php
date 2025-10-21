<?php
echo'
<script>
	$("#txtBuscarProveedor").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerProveedores/0/'.$idMaterial.'",
		
		select:function( event, ui)
		{
			$("#proveedoresMateriales").val(ui.item.idProveedor);
		}
	});
</script>

<div class="ui-state-error" ></div>
<table class="admintable" style="width:100%">
	<tr>
		<td class="key">'.(sistemaActivo=='IEXE'?'Insumo':'Materia prima').':</td>
		<td>'.$material->nombre.'</td>
	</tr>
	<tr>
		<td class="key"> <img src="'.base_url().'img/add.png" width="22" onclick="accesoAgregarProveedorServicio(1)"> Seleccione un proveedor:</td>
		<td>
			
			<input type="text" style="width:90%" class="cajas" id="txtBuscarProveedor" name="txtBuscarProveedor" placeholder="Seleccione">
			<input type="hidden" class="cajas" id="proveedoresMateriales" name="proveedoresMateriales" value="0">
			
			<input type="hidden"  	name="txtAgregarProveedorInsumo" 	id="txtAgregarProveedorInsumo"  value="1"/>
		</td>
	</tr>
	<tr>
		<td class="key">Costo:</td>
		<td>
			<input type="text" class="cajas" id="txtCostoMaterial" style="width:100px" maxlength="10" onkeypress="return soloDecimales(event)" />
			<input type="hidden" id="txtIdMaterialAsociar" value="'.$idMaterial.'" />
		</td>
	</tr>
</table>';