<script>
$(document).ready(function()
{
	$('#txtFechaArribo').datepicker({changeMonth: true, changeYear: true});
	
	$("#txtBuscarMateriaRequisicion").autocomplete(
	{
		source:base_url+'configuracion/obtenerMateriales/0',
		
		select:function( event, ui)
		{
			cargarMateriaRequisicion(ui.item)
		}
	});
});
</script>
<form id="frmRequisicion">
<table class="admintable" width="100%">
	<tr>
    	<th colspan="2" class="encabezadoPrincipal">
        	Detalles de requisición
        </th>
    </tr>
	<tr>
        <td class="key">Requisición:</td>
        <td><?php echo requisicion.$folio?></td>
    </tr>
    
    <tr>
        <td class="key">Fecha requisición:</td>
        <td><?php echo obtenerFechaMesCorto(date('Y-m-d H:i'))?></td>
    </tr>
    
    <tr>
        <td class="key">Fecha requerida:</td>
        <td>
            <input type="text"  name="txtFechaArribo" id="txtFechaArribo" class="cajas" style="width:80px;" readonly="readonly" value="<?php echo date('Y-m-d')?>"/>
        </td>
    </tr>

    <tr>
        <td class="key">Comentarios:</td>
        <td>
            <textarea type="text"  name="txtComentariosRequisicion" id="txtComentariosRequisicion" class="cajas" style="width:300px; height:40px"></textarea>
        </td>
    </tr>
</table>

<table class="admintable" width="100%" id="tablaRequisiciones">
	<tr>
    	<th colspan="4" class="encabezadoPrincipal">
        	<input type="text"  name="txtBuscarMateriaRequisicion" onchange="sugerirMaterialNuevo()" id="txtBuscarMateriaRequisicion" class="cajas" placeholder="Buscar por <?php echo sistemaActivo=='IEXE'?'insumo':'materia prima'?>, código"  style="width:400px;"/>
            <input type="hidden"  name="txtNumeroMateriales" id="txtNumeroMateriales" value="0"/>
        </th>
    </tr>
	<tr>
    	<th width="3%">-</th>
        <th width="60%"><?php echo sistemaActivo=='IEXE'?'Insumos':'materia prima'?></th>
        <th width="15%">Unidad</th>
        <th>Cantidad</th>
    </tr>
</table>
</form>