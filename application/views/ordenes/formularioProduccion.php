<script>
$(document).ready(function()
{
	$("#txtBuscarProducto").autocomplete(
	{
		source:base_url+'configuracion/obtenerInventarioProduccion',
		
		select:function( event, ui)
		{
			$('#txtIdProducto').val(ui.item.idProducto);
			obtenerMaterialesProducto();
		}
	});
});

</script>
<?php
$i	= 1;

echo '<form id="frmOrdenes" name="frmOrdenes">';

if($procesos!=null)
{
	echo'
	<table class="admintable" width="100%" style="margin-top:3px">
		<tr>
			<th colspan="3">Procesos de producción</th>
		</tr>
		<tr>
			<th>#</th>
			<th width="70%">Proceso</th>
			<th>Seleccionar</th>
		</tr>';

	foreach($procesos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';

		echo'
		<tr '.$estilo.'>
			<td>'.$i.'</td>
			<td>'.$row->nombre.'</td>
			<td align="center">
				<input type="checkbox" id="chkProceso'.$i.'" name="chkProceso'.$i.'" value="'.$row->idProceso.'" />
			</td>
		</tr>';
		$i++;
	}
	
	echo'</table>';
}
else
{
	echo '<div class="Error_validar" style=" width:97%; margin-top:10px; margin-bottom: 5px;">
	Puede registrar mas procesos de producción en la configuración</div><br />';
}

echo'<input type="hidden" id="txtIndiceProcesos" name="txtIndiceProcesos" value="'.$i.'" />';

echo'
<table class="admintable" width="100%" style="margin-top:3px">
	<tr>
		<th colspan="2">Seleccionar producto</th>
	</tr>
	<tr>
		<td class="key">Orden</td>
		<td>'.folioOrdenes.$folio.'</td>
	</tr>
	<tr>
		<td class="key">Producto</td>
		<td>
			<input type="text" style="width:500px" class="cajas" id="txtBuscarProducto" />
			<input type="hidden" id="txtIdProducto" name="txtIdProducto" value="0" />
		</td>
	</tr>
	<tr>
		<td class="key">Cantidad</td>
		<td>
			<input type="text" style="width:100px" class="cajas" id="txtCantidadProduccion" name="txtCantidadProduccion" value="1" onchange="obtenerMaterialesProducto()" onkeypress="return soloDecimales(event)" maxlength="10" />
		</td>
	</tr>
</table>

<div id="obtenerMaterialesProducto"></div>';

echo '</form>';