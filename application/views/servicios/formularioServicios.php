<?php
echo'
<script>
	$("#txtBuscarProveedorServicio").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerProveedores/0/0/0",
		
		select:function( event, ui)
		{
			$("#txtIdProveedorServicio").val(ui.item.idProveedor);
		}
	});
</script>

<form id="frmAgregarServicio" name="frmAgregarServicio">
<table class="admintable" width="100%;">
    <tr>
        <td class="key">Nombre:</td>
        <td>
        	<input type="text" name="txtNombreServicio" id="txtNombreServicio" class="cajas" style="width:500px;" /> 
        </td>
    </tr>
    
    <tr>
        <td class="key">Código:</td>
        <td>
        	<input type="text" name="txtCodigo" id="txtCodigo" class="cajas" style="width:200px" /> 
        </td>
    </tr>
    
    <tr>
        <td class="key">Unidad:</td>
        <td>
        	<!--<input type="text" name="txtUnidad" id="txtUnidad" class="cajas" style="width:200px" /> -->
			
			<select name="selectUnidad" id="selectUnidad" class="cajas" style="width:200px">';
			
			foreach($unidades as $row)
			{
				echo '<option value="'.$row->idUnidad.'">'.$row->descripcion.'</option>';
			}
				
			
			echo'
			</select>
			
        </td>
    </tr>
    
    <tr>
        <td class="key">Proveedor:</td>
        <td>
            <input type="text" name="txtBuscarProveedorServicio" id="txtBuscarProveedorServicio" class="cajas" style="width:450px" placeholder="Seleccione" /> 
            <input type="hidden" name="txtIdProveedorServicio" id="txtIdProveedorServicio" value="0" /> 
			
			<div style="text-align:center; width: 100px">
				<img src="'.base_url().'img/proveedores.png" width="22" onclick="accesoAgregarProveedorServicio(1)" />
				<br>
				<a>Agregar proveedor</a>
			</div>
        </td>
    </tr>
    
    <tr>
        <td class="key">Costo:</td>
        <td>
        	<input type="text" class="cajas" name="txtCostoServicio" id="txtCostoServicio" style="width:200px" onkeypress="return soloDecimales(event)" maxlength="15"/>
        </td>
    </tr>
</table>
</form>';

