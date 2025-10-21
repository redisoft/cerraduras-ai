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
        <td><?php echo salidas.$salida->folio?></td>
    </tr>
    
    <tr>
        <td class="key">Tienda:</td>
       	<td>
            <select id="selectTiendas" name="selectTiendas" style="width:300px" class="cajas">
            	<option value="0">Matriz</option>
                <?php
                foreach($tiendas as $row)
				{
					echo '<option '.($row->idTienda==$salida->idTienda?'selected="selected"':'').' value="'.$row->idTienda.'">'.$row->nombre.'</option>';
				}
				?>
            </select>
        </td>
    </tr>
    
    <tr>
        <td class="key">Fecha salida:</td>
       	<td>
            <input type="text"  name="txtFechaSalida" id="txtFechaSalida" class="cajas" style="width:80px;" readonly="readonly" value="<?php echo $salida->fechaSalida?>"/>
        </td>
    </tr>
    
    <tr style="display:none">
        <td class="key">Fecha devolución:</td>
        <td>
            <input type="text"  name="txtFechaDevolucion" id="txtFechaDevolucion" class="cajas" style="width:80px;" readonly="readonly" value="<?php echo $salida->fechaDevolucion?>"/>
        </td>
    </tr>

    <tr>
        <td class="key">Comentarios:</td>
        <td>
            <textarea type="text"  name="txtComentarios" id="txtComentarios" class="cajas" style="width:300px; height:40px"><?php echo $salida->comentarios?></textarea>
        </td>
    </tr>
</table>

<table class="admintable" width="100%" id="tablaSalidasControl">
	<tr>
    	<th colspan="5" class="encabezadoPrincipal">
        	<input type="text"  name="txtBuscarMateriaSalida" id="txtBuscarMateriaSalida" class="cajas" placeholder="Buscar por materia prima, código"  style="width:400px;"/>
            <input type="hidden"  name="txtNumeroMateriales" id="txtNumeroMateriales" value="<?php echo count($materiales)?>"/>
            <input type="hidden"  name="txtIdSalida" id="txtIdSalida" value="<?php echo $salida->idSalida?>"/>
        </th>
    </tr>
	<tr>
    	<th width="3%">-</th>
        <th width="15%">Código</th>
        <th width="45%">Materia prima</th>
        <th width="15%">Unidad</th>
        <th>Cantidad</th>
    </tr>
    
    <?php
	$i=0;
    foreach($materiales as $row)
	{
		echo '
		<tr id="filaSalidaControl'.$i.'" '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
			<td><img src="'.base_url().'img/borrar.png" width="18" onclick="quitarMaterialControl('.$i.')"/></td>
			<td>'.$row->codigoInterno.'</td>
			<td>'.$row->material.'</td>
			<td>'.$row->unidad.'</td>
			<td align="center"> <input type="text"  	name="txtCantidadControl'.$i.'" id="txtCantidadControl'.$i.'" class="cajas" style="width:100px;" value="'.round($row->cantidad,decimales).'" onkeypress="return soloDecimales(event)"/></td>
			<input type="hidden"  name="txtIdMaterial'.$i.'" id="txtIdMaterial'.$i.'" value="'.$row->idMaterial.'" />
		</tr>';
		
		$i++;
	}
	?>
</table>
</form>