<?php
$i=1;
echo'
<div class="ui-state-error" ></div>
<table class="admintable" width="99%">
	<tr>
		<th colspan="2">'.$material->nombre.'</th>
	</tr>
	
	<tr>
		<td class="key">Fecha:</td>
		<td>
			<input type="text" id="txtFechaMerma" class="cajas" style="width:120px" value="'.date('Y-m-d H:i').'" /> 
			<script>
				$("#txtFechaMerma").datetimepicker();
			</script>
		</td>
	</tr>
	
	<tr>
		<td class="key">Proveedor:</td>
		<td>'.$material->empresa.'</td>
	</tr>
	
	<tr>
		<td class="key">Inventario:</td>
		<td>'.number_format($material->inventario-$material->salidas,2).'</td>
	</tr>
	
	<tr>
		<td class="key">Cantidad:</td>
		<td>
			<input type="text" id="txtCantidadMerma" class="cajas" /> 
			<input type="hidden" id="txtTotalMaterial" value="'.($material->inventario-$material->salidas).'" class="cajas" />
			<input type="hidden" id="txtIdMaterialMerma" value="'.$material->idMaterial.'" class="cajas" />
			<input type="hidden" id="txtIdProveedorMaterial" value="'.$material->idProveedor.'" class="cajas" /
		</td>
	</tr>
	<tr>
		<td class="key">Comentarios:</td>
		<td>
			<textarea class="TextArea" id="txtComentariosMerma"></textarea>
		</td>
	</tr>
</table>';


if($mermas!=null)
{
	echo'
	<table class="admintable" width="99%">
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Cantidad</th>
			<th>Comentarios</th>
		</tr>';
	
	
	foreach($mermas as $row)
	{
		echo'
		<tr '.($i%2>0?' class="sombreado"':'class="sinSombra"').'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="right">'.number_format($row->cantidad,4).'</td>
			<td>'.$row->comentarios.'</td>
		</tr>';
		
		$i++;
	}
	
	echo'</table>';
}