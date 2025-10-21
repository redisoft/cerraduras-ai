<script>
$(document).ready(function()
{
	$('#txtFechaDevolucion,#txtFechaSalida').datepicker({changeMonth: true, changeYear: true});
	
	$("#txtBuscarMateriaSalida").autocomplete(
	{
		source:base_url+'configuracion/obtenerMateriales',
		
		select:function( event, ui)
		{
			cargarMateriaControl(ui.item)
		}
	});
});
</script>
<form id="frmSalidaControl">
<table class="admintable" width="100%">
	<tr>
    	<th colspan="2" class="encabezadoPrincipal">
        	Detalles de salida
        </th>
    </tr>
	<tr>
        <td class="key">Control:</td>
        <td><?php echo salidas.$folio?></td>
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
        <td class="key">Fecha salida:</td>
       	<td>
            <input type="text"  name="txtFechaSalida" id="txtFechaSalida" class="cajas" style="width:80px;" readonly="readonly" value="<?php echo date('Y-m-d')?>"/>
        </td>
    </tr>
    
    <tr style="display:none">
        <td class="key">Fecha devolución:</td>
        <td>
            <input type="text"  name="txtFechaDevolucion" id="txtFechaDevolucion" class="cajas" style="width:80px;" readonly="readonly" value="<?php echo date('Y-m-d')?>"/>
        </td>
    </tr>

    <tr>
        <td class="key">Comentarios:</td>
        <td>
            <textarea type="text"  name="txtComentarios" id="txtComentarios" class="cajas" style="width:300px; height:40px"></textarea>
        </td>
    </tr>
</table>

<table class="admintable" width="100%" id="tablaSalidasControl">
	<tr>
    	<th colspan="5" class="encabezadoPrincipal">
        	<input type="text"  name="txtBuscarMateriaSalida" id="txtBuscarMateriaSalida" class="cajas" placeholder="Buscar por materia prima, código"  style="width:400px;"/>
            <input type="hidden"  name="txtNumeroMateriales" id="txtNumeroMateriales" value="0"/>
        </th>
    </tr>
	<tr>
    	<th width="3%">-</th>
        <th width="15%">Código</th>
        <th width="45%">Materia prima</th>
        <th width="15%">Unidad</th>
        <th>Cantidad</th>
    </tr>
</table>
</form>