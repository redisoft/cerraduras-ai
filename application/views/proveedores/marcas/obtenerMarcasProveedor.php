<?php

 echo'
<script>
$(document).ready(function()
{
	$("#txtBuscarMarca").autocomplete(
	{
		source:"'.base_url().'catalogos/obtenerMarcasProveedor/'.$proveedor->idProveedor.'",
		
		select:function( event, ui)
		{
			$("#txtIdMarca").val(ui.item.idMarca)
		}
	});
	
	$("#tablaMarcas tr:even").addClass("sinSombra");
	$("#tablaMarcas tr:odd").addClass("sombreado");  
});
</script>

<form id="frmMarcas">
	<input type="hidden" id="txtIdProveedor" 	name="txtIdProveedor" 	value="'.$proveedor->idProveedor.'" />
	<input type="hidden" id="txtIdMarca" 		name="txtIdMarca" 		value="0" />
	<table class="admintable" width="100%">
		<tr>
			<th colspan="2" class="encabezadoPrincipal">Registro</th>
		</tr>
		<tr>
			<td class="key">Proveedor:</td>
			<td>'.$proveedor->empresa.'</td>
		</tr>
		<tr>
			<td class="key">Marca</td>
			<td>
				<input type="text"  id="txtBuscarMarca" name="txtBuscarMarca" class="cajas" style="width:500px" />

			</td>
		</tr>
	</table>

	<table class="admintable" width="100%" id="tablaMarcas">
		<tr>
			<th colspan="3">
				Marcas
			</th>
		</tr>
		<tr>
			<th width="5%">#</th>
			<th>Nombre</th>
			<th>Acciones</th>
		</tr>';

		$i=1;
		foreach($marcas as $row)
		{
			echo '
			<tr>
				<td>'.$i.'</td>
				<td>'.$row->nombre.'</td>
				<td align="center" width="15%">
					<img onclick="borrarMarcaProveedor('.$row->idRelacion.')" src="'.base_url().'img/borrar.png" width="22" title="Borrar marca" />
					<br />
					<a>Borrar</a>
				</td>
			</tr>';

			$i++;
	}

	echo'</table>
</form>';