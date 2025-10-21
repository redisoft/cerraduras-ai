<?php
#if($ingresos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;" align="center">
		<ul id="pagination-digg" class="ajax-pagNivel2">'.$this->pagination->create_links().'</ul>
	</div>';

	echo'
	<table class="admintable" width="100%">
		<tr>
			<th colspan="4" class="encabezadoPrincipal">
				Nivel 2
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Nombre</th>
			<th>Nivel 1</th>
			<th width="19%">Acciones</th>
		</tr>';
	
	$i=$inicio;
	
	foreach($niveles as $row)
	{
		echo'
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td width="5%" align="right">'.$i.'</td>
			<td>'.$row->nombre.'</td>
			<td>'.$row->nivel1.'</td>
			<td align="center">
				<img id="btnEditar'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" onclick="obtenerNivel2('.$row->idNivel2.')" />
				&nbsp;&nbsp;
				<img id="btnBorrarNivel'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" onclick="borrarNivel2('.$row->idNivel2.')" />
				
				<br />
				
				<a id="a-btnEditar'.$i.'">Editar</a>
				<a id="a-btnBorrarNivel'.$i.'">Borrar</a>';
				
				if($row->relaciones>0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnBorrarNivel'.$i.'\');
					</script>';
				}
			
			
			echo'
			</td>
		</tr>';

		$i++;
	}
	
	echo '</table>';
}
