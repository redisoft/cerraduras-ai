<?php
#if($ingresos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;" align="center">
		<ul id="pagination-digg" class="ajax-pagProspectosSie">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th colspan="8" class="encabezadoPrincipal">
				Metas
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th width="20%">Tipo</th>
			<th width="20%">Grado</th>
			<th>Fecha inicial</th>
			<th>Fecha final</th>
			<th>Meta</th>
			<th width="19%">Acciones</th>
		</tr>';
	
	$i=$inicio;
	
	foreach($registros as $row)
	{
		echo'
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td width="5%" align="right">'.$i.'</td>
			<td align="center">'.$row->tipo.'</td>
			<td align="center">'.$row->grado.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fechaInicial).'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fechaFinal).'</td>
			<td align="center">'.round($row->meta,decimales).'</td>
			<td align="center">
				<img id="btnEditarProspectosSie'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" onclick="formularioEditarProspectosSie('.$row->idMeta.')" />
				<img id="btnBorrarProspectosSie'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" onclick="borrarProspectosSie('.$row->idMeta.')" />
				<br />
				<a id="a-btnEditarProspectosSie'.$i.'">Editar</a>
				<a id="a-btnBorrarProspectosSie'.$i.'">Borrar</a>';
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarProspectosSie'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarProspectosSie'.$i.'\');
					</script>';
				}
			
			echo'
			</td>
		</tr>';

		$i++;
	}
	
	echo '</table>
	
	<div style="width:90%; margin-top:1%;" align="center">
		<ul id="pagination-digg" class="ajax-pagProspectosSie">'.$this->pagination->create_links().'</ul>
	</div>';
}
