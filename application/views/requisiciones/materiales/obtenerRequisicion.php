<script>
$(document).ready(function()
{
	$('#txtFechaArribo').datepicker({changeMonth: true, changeYear: true});
	
	$("#txtBuscarMateriaRequisicion").autocomplete(
	{
		source:base_url+'configuracion/obtenerMateriales',
		
		select:function( event, ui)
		{
			cargarMateriaRequisicion(ui.item)
		}
	});
});
</script>
<form id="frmEditarRequisicion">
<table class="admintable" width="100%">
	<tr>
    	<th colspan="2" class="encabezadoPrincipal">
        	Detalles de requisición
        </th>
    </tr>
	<tr>
        <td class="key">Requisición:</td>
        <td><?php echo requisicion.$requisicion->folio?></td>
    </tr>
    
    <tr>
        <td class="key">Fecha requisición:</td>
        <td><?php echo obtenerFechaMesCorto($requisicion->fechaRequisicion)?></td>
    </tr>
    
    <tr>
        <td class="key">Fecha arribo:</td>
        <td>
            <input type="text"  name="txtFechaArribo" id="txtFechaArribo" class="cajas" style="width:80px;" readonly="readonly" value="<?php echo $requisicion->fechaArribo?>"/>
        </td>
    </tr>

    
    
    <tr>
        <td class="key">Comentarios:</td>
        <td>
            <textarea type="text"  name="txtComentariosRequisicion" id="txtComentariosRequisicion" class="cajas" style="width:300px; height:40px"><?php echo $requisicion->comentarios?></textarea>
        </td>
    </tr>
</table>

<table class="admintable" width="100%" id="tablaRequisiciones">
	<tr>
    	<th colspan="4" class="encabezadoPrincipal">
        	<input type="text"  name="txtBuscarMateriaRequisicion" id="txtBuscarMateriaRequisicion" class="cajas" placeholder="Buscar por <?php echo sistemaActivo=='IEXE'?'insumo':'materia prima'?>, código"   style="width:400px;" onchange="sugerirMaterialNuevo()"/>
            <input type="hidden"  name="txtNumeroMateriales" id="txtNumeroMateriales" value="<?php echo count($materiales)?>"/>
            <input type="hidden"  name="txtIdRequisicion" id="txtIdRequisicion" value="<?php echo $requisicion->idRequisicion?>"/>
        </th>
    </tr>
	<tr>
    	<th width="3%">-</th>
        <th width="60%">Materia prima</th>
        <th width="15%">Unidad</th>
        <th>Cantidad</th>
    </tr>
    
    <?php
	$i=0;
    foreach($materiales as $row)
	{
		echo '
		<tr id="filaMaterialRequisicion'.$i.'">
			<td><img src="'.base_url().'img/borrar.png" width="18" onclick="quitarMaterialRequisicion('.$i.')"/></td>
			<td>'.$row->material.'</td>
			<td>'.$row->unidad.'</td>
			<td align="center"> <input type="text"  name="txtCantidadRequisicion'.$i.'" id="txtCantidadRequisicion'.$i.'" value="'.round($row->cantidad,decimales).'" class="cajas" style="width:100px;" onkeypress="return soloDecimales(event)"/></td>
			<input type="hidden"  name="txtIdMaterial'.$i.'" id="txtIdMaterial'.$i.'" value="'.$row->idMaterial.'" />
		</tr>';
		
		$i++;
	}
	?>
</table>
</form>