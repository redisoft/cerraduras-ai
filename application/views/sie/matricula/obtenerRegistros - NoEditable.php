<?php
#if($ingresos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;" align="center">
		<ul id="pagination-digg" class="ajax-pagMatriculaSie">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th colspan="8" class="encabezadoPrincipal">
				'.($licenciatura=='1'?'Licenciaturas':'Maestrías').'
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th width="10%">Cuatrimestre</th>
			<th width="30%">Programa</th>
			<th>Ingresos</th>
			<th>Actual</th>
			<th>Resultado</th>
			<th>Meta</th>
			<th width="19%">Acciones</th>
		</tr>';
	
	$i=$inicio;
	
	foreach($matriculas as $row)
	{
		$desercion					= (1-($row->actual/$row->ingresos))*100;
		
		echo'
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td width="5%" align="right">'.$i.'</td>
			<td align="center">'.$row->cuatrimestre.'</td>
			<td>'.$row->programa.'</td>
			<td align="center">'.$row->ingresos.'</td>
			<td align="center">'.$row->actual.'</td>
			<td align="center">'.round($desercion,decimales).'%</td>
			<td align="center">'.round($row->meta,decimales).'%</td>
			<td align="center">
				<img id="btnEditarMatriculaSie'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" onclick="borrarMatriculaSie('.$row->idMatricula.')" />
				<br />
				<a id="a-btnBorrarMatriculaSie'.$i.'">Borrar</a>';

				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarMatriculaSie'.$i.'\');
					</script>';
				}
			
			echo'
			</td>
		</tr>';

		$i++;
	}
	
	echo '</table>
	
	<div style="width:90%; margin-top:1%;" align="center">
		<ul id="pagination-digg" class="ajax-pagMatriculaSie">'.$this->pagination->create_links().'</ul>
	</div>';
}
