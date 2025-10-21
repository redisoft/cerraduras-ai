<?php
echo '<input type="hidden" id="txtIdInventario" value="'.$idInventario.'" />';
		
echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Registrar uso</th>
	</tr>
	<tr>
		<td class="key">Existencia:</td>
		<td>
		'.$inventario->cantidad.'
		<input type="hidden" id="txtExistencia" value="'.$inventario->cantidad.'" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Cantidad a usar:</td>
		<td>
			<input type="text" class="cajas" id="txtCantidadUsar" onkeypress="return soloDecimales(event)" maxlength="15" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Comentarios:</td>
		<td>
			<textarea class="TextArea" id="txtComentarios"></textarea>
		</td>
	</tr>
</table>';

if($usos!=null)
{
	echo '
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagUso">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th colspan="4">Usos de '.$inventario->nombre.'</th>
		</tr>
		<tr>
			<th width="3%">#</th>
			<th>Fecha</th>
			<th>Cantidad</th>
			<th>Comentarios</th>
		</tr>';
		
	$i=1;
	foreach($usos as $row)
	{
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td>'.$i.'</td>
			<td align="center">'.$row->fecha.'</td>
			<td align="center">'.number_format($row->cantidad,2).'</td>
			<td>'.$row->comentarios.'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de usos de Mobiliario/equipo</div>';
}