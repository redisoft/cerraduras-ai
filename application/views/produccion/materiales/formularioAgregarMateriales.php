<script>
$(document).ready(function()
{
	$("#txtBusquedaMaterial").autocomplete(
	{
		source:base_url+'configuracion/obtenerMaterialesProduccion',
		
		select:function( event, ui)
		{
			
			obtenerConversionesProduccion(ui.item.idUnidad);
			$('#selectMateriales').val(ui.item.idMaterial);
			$('#txtIdUnidad').val(ui.item.idUnidad);
		}
	});
});
</script>

<input type="hidden" name="txtIdProduccion" id="txtIdProduccion" class="cajas" style="width:300px" value="<?php echo $producto->idProducto?>" />
<table class="admintable" width="100%;">
    <tr>
      <td class="key">Producto:</td>
      <td><?php echo $producto->nombre?></td>
    </tr>	
    <tr>
      <td class="key">Materia prima:</td>
      <td>
       <input type="hidden" name="selectMateriales" id="selectMateriales" value="0"/>
       
       <input type="text" name="txtBusquedaMaterial" id="txtBusquedaMaterial"  style="width:550px;" class="cajas"  />
        <input type="hidden" name="txtIdUnidad" id="txtIdUnidad" value="0"/> 
      </td>
    </tr>	
    <tr>
		<td class="key">Conversión:</td>
		<td id="cargarConversionesProduccion">
           <select class="cajas" id="selectConversion"  style="width:300px">
            <option value="0">Seleccione</option>
           </select>
        </td>
	</tr>
	<tr>
		<td class="key">Cantidad:</td>
		<td>
       	 	<input type="text" name="txtCantidad" id="txtCantidad" class="cajas" style="width:25%;" value=""  /> 
        
        </td>
	</tr>
</table>