<?php 
if(!empty($registros))
{
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaRegistros tr:even").addClass("sombreado");
		$("#tablaRegistros tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<div style="margin-top:10px">
		<ul id="pagination-digg" class="ajax-pagRegistros">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%" id="tablaRegistros">
		<tr>
			<th class="encabezadoPrincipal" style="width:3%;">#</th>
			<th class="encabezadoPrincipal" >Nombre</th>
			<th class="encabezadoPrincipal" style="width:20%;">Acciones</th>               
		</tr>';
		
		$i=$inicio;
		
		foreach($registros as $row)
		{
			echo '
			<tr>
				<td>'.$i.'</td>
				<td>'.$row->nombre.'</td>
				<td align="center">
					<img id="btnEditarRegistro'.$i.'"  onclick="obtenerRegistro('.$row->idEstacion.')" src="'. base_url().'img/editar.png" style="width:22px; height:22px;" title="Editar" />
					&nbsp;&nbsp;
					<img id="btnBorrarRegistro'.$i.'"  onclick="borrarRegistro('.$row->idEstacion.')" src="'. base_url().'img/borrar.png" style="width:22px; height:22px;" title="Borrar" />
					<br />
					<a id="a-btnEditarRegistro'.$i.'" >Editar</a>
					<a id="a-btnBorrarRegistro'.$i.'" >Borrar</a>
				</td>
			</tr>';	
			
			/*if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarRegistro'.$i.'\');
				</script>';
			}*/
			
			$i++;
		}

	echo'
	</table>
	
	<div style="margin-top:0px">
		<ul id="pagination-digg" class="ajax-pagRegistros">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">Sin registros</div>';
}
?>
