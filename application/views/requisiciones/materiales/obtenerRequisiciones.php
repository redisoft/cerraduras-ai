<?php
if($requisiciones!=null)
{
	echo '
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagRequisiciones">'.$this->pagination->create_links().'</ul>
	 </div>
	<table class="admintable" width="100%">
		<tr>
			<th>#</th>
			<th>Fecha requisición</th>
			<th>Usuario</th>
			<th>Fecha arribo</th>
			<th>Requisición</th>
			<th>Comentarios</th>
			<th width="15%">Acciones</th>
		</tr>';
	
	$i	= $limite;
	foreach($requisiciones as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		$onclick	= 'onclick="obtenerDetallesRequisicion('.$row->idRequisicion.')" title="Dar click para ver detalles"';
		
		echo '
		<tr '.$estilo.' id="filaRequisicion'.$row->idRequisicion.'">
			<td align="right" '.$onclick.'>'.$i.'</td>
			<td align="center" '.$onclick.'>'.obtenerFechaMesCorto($row->fechaRequisicion).'</td>
			<td align="left"  '.$onclick.'>'.$row->usuario.'</td>
			<td align="center" '.$onclick.'>'.obtenerFechaMesCorto($row->fechaArribo).'</td>
			<td align="left"  '.$onclick.'>'.requisicion.$row->folio.'</td>
			<td align="left"  '.$onclick.'>'.substr($row->comentarios,0,20).'</td>
			<td align="center">
				<img id="btnEditarRequisicion'.$i.'" src="'.base_url().'img/editar.png" title="Editar requisición" width="22" style="cursor:pointer" onclick="obtenerRequisicion('.$row->idRequisicion.')"/>
				&nbsp;
				<img id="btnBorrarRequisicion'.$i.'" src="'.base_url().'img/borrar.png" width="20" height="20" title="Borrar" onclick="borrarRequisicion('.$row->idRequisicion.');" style="cursor:pointer;"/>

				<br />
				<a id="a-btnEditarRequisicion'.$i.'">Editar</a>
				<a id="a-btnBorrarRequisicion'.$i.'">Borrar</a>';
					
				if($permiso[1]->activo==0 or $row->autorizadaCompra=='1')
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnEditarRequisicion'.$i.'\');
					</script>';
				}

				if($permiso[2]->activo==0 or $row->autorizadaCompra=='1')
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnBorrarRequisicion'.$i.'\');
					</script>';
				}
	
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagRequisiciones">'.$this->pagination->create_links().'</ul>
	 </div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de requisiciones</div>';
}