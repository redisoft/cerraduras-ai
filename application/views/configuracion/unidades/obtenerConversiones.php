<?php
echo'
<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Registrar nueva conversión de la unidad "'.$unidad->descripcion.'"</th>
	</tr>
	<tr>
		<td class="key">Nombre</td>
		<td><input type="text" class="cajas" id="txtNombre"></td>
	</tr>
	
	<tr>
		<td class="key">Referencia</td>
		<td><input type="text" class="cajas" id="txtReferencia"></td>
	</tr>
	
	<tr>
		<td class="key">Valor</td>
		<td>
			<input type="text" class="cajas" id="txtValor" onkeypress="return soloDecimales(event)" maxlength="15">
			<input type="hidden" class="cajas" id="txtIdUnidad" value="'.$idUnidad.'">
		</td>
	</tr>
</table>

<table class="admintable" width="99%">
	<tr>
		<th colspan="5">Lista de conversiones</th>
	</tr>
	<tr>
		<th>#</th>
		<th width="50%">Nombre</th>
		<th width="">Referencia</th>
		<th>Valor</th>
		<th>Acciones</th>
	</tr>';

$i=1;
foreach($conversiones as $row)
{
	$estilo	= $i%2>0?'class="sombreado"':'class="sinSombra"';
	
	echo'
	<tr '.$estilo.'>
		<td>'.$i.'</td>
		<td>'.$row->nombre.'</td>
		<td>'.$row->referencia.'</td>
		<td align="right">'.$row->valor.'</td>
		<td align="center">
			 <img id="btnEditarConversion'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" title="Editar" onClick="accesoEditarConversion('.$row->idConversion.')" >
			&nbsp;
			<img id="btnBorrarConversion'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" title="Editar" onClick="accesoBorrarConversion('.$row->idConversion.')" >
			<br />
			<a id="a-btnEditarConversion'.$i.'" >Editar</a>
			<a id="a-btnBorrarConversion'.$i.'" >Borrar</a>';
			
			if($permiso[2]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarConversion'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnBorrarConversion'.$i.'\');
				</script>';
			}
			
		echo'
		</td>
	</tr>';
	
	$i++;
}

echo'</table>';