<?php
$i=1;

echo '

<div id="procesandoNivel6"></div>
<div id="borrandoNivel6"></div>

<table class="tablaFormularios">
	<tr>
		<th colspan="2">Detalles de cuenta nivel 5</th>
	</tr>
	<tr>
		<td class="etiquetas">Código agrupador:</td>
		<td>'.$cuenta->codigo.'</td>
	</tr>
	<tr>
		<td class="etiquetas">Cuenta:</td>
		<td>'.$cuenta->nombre.'</td>
	</tr>
	
	<tr>
		<th colspan="2">Registrar cuenta nivel 6</th>
	</tr>
	
	<tr>
		<td class="etiquetas">Cuenta:</td>
		<td>
			<input type="text" class="textos" id="txtCuentaNivel6" name="txtCuentaNivel6" placeholder="Nivel 6" />
			<input type="hidden" id="txtIdSubCuenta5" name="txtIdSubCuenta5" value="'.$cuenta->idSubCuenta5.'"/>
		</td>
	</tr>
	
</table>';

if($cuentas!=null)
{
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaNivel6 tr:even").addClass("arriba");
		$("#tablaNivel6 tr:odd").addClass("abajo");  
	});
	</script>
	
	<table class="tablaDatos" id="tablaNivel6">
		<tr>
			<th class="titulos" colspan="3">Lista de cuentas nivel 6</th>
		</tr>
		<tr>
			<th>No.</th>
			<th>Cuenta</th>
			<th width="20%">Acciones</th>
		</tr>';
	
	foreach($cuentas as $row)
	{
		echo'
		<tr>
			<td class="numeral	">'.$i.'</td>
			<td align="center">'.$row->nombre.'</td>
			<td align="center" class="vinculos">
				<img src="'.base_url().'img/editar.png" title="Editar" onclick="obtenerCuentaNivel6('.$row->idSubCuenta6.')" />
				<img src="'.base_url().'img/borrar.png" title="Borrar" onclick="borrarNivel6('.$row->idSubCuenta6.')" />
			</td>
		<tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="erroresDatos">Sin registros</div>';
}
?>