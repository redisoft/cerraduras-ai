<?php
echo '
<table class="admintable" width="100%;">
	<tr>
		<th class="encabezadoPrincipal" colspan="2">
			Registro
		</th>
	</tr>
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtNombreMotivo" id="txtNombreMotivo" type="text" class="cajas" style="width:300px"  />
		</td>
	</tr>	
</table>';

if($motivos!=null)
{
	echo '
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagMotivos">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="3">
				Colores
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Motivo</th>
			<th width="20%">Acciones</th>
		</tr>';

	$i				= $limite;

	foreach($motivos as $row)
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td align="right">'.$i.'</td>
			<td align="left">'.$row->nombre.'</td>
			<td align="center">
				<img id="btnEditarMotivo'.$i.'" src="'.base_url().'img/editar.png"  onclick="obtenerMotivo('.$row->idMotivo.')" width="22" title="Editar motivo" />
				&nbsp;&nbsp;
				<img id="btnBorrarMotivo'.$i.'" src="'.base_url().'img/borrar.png"  onclick="borrarMotivo('.$row->idMotivo.')" width="22" title="Borrar motivo" />
				<br />
				<a id="a-btnEditarMotivo'.$i.'">Editar</a>
				<a id="a-btnBorrarMotivo'.$i.'">Borrar</a>';
				
				if($permiso[2]->activo==0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnEditarMotivo'.$i.'\');
					</script>';
				}

				if($permiso[3]->activo==0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnBorrarMotivo'.$i.'\');
					</script>';
				}
				
			echo'
			</td>
		</tr>';
		
		$i++;
	}

	echo'
		</tr>
	</table>
	
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagMotivos">'.$this->pagination->create_links().'</ul>
	</div>';
}
else 
{
	echo '<div class="Error_validar">Sin registro de motivos</div>';
}

?>