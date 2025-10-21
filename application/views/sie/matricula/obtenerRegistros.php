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
		<input type="hidden" class="cajas" value="'.$row->idMatricula.'" style="width:60px" id="txtIdMatricula'.$i.'" />
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td width="5%" align="right">'.$i.'</td>
			<td align="center">'.$row->cuatrimestre.'</td>
			<td>'.$row->programa.'</td>
			<td align="center">'.($permiso[2]->activo==1?'<input type="text" onkeypress="return soloNumerico(event)" class="cajas" maxlength="6" value="'.$row->ingresos.'" style="width:60px" onchange="editarMatricula('.$i.',\'ingresos\',\'txtIngresos\')" id="txtIngresos'.$i.'" />':$row->ingresos).'</td>
			<td align="center">'.($permiso[2]->activo==1?'<input type="text" onkeypress="return soloNumerico(event)" class="cajas" maxlength="6" value="'.$row->actual.'" style="width:60px" onchange="editarMatricula('.$i.',\'actual\',\'txtActual\')" id="txtActual'.$i.'" />':$row->actual).'</td>
			<td align="center" id="lblDesercion'.$i.'">'.round($desercion,decimales).'%</td>
			<td align="center">'.($permiso[2]->activo==1?'<input type="text" onkeypress="return soloDecimales(event)" class="cajas" maxlength="4" value="'.round($row->meta,decimales).'" style="width:60px" onchange="editarMatricula('.$i.',\'meta\',\'txtMeta\')" id="txtMeta'.$i.'" />':round($row->meta,decimales)).'%</td>
			<td align="center">
				<img id="btnBorrarMatriculaSie'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" onclick="borrarMatriculaSie('.$row->idMatricula.')" />
				<br />
				<a id="a-btnBorrarMatriculaSie'.$i.'">Borrar</a>';

				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarMatriculaSie'.$i.'\');
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
