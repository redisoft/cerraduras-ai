<?php
$i=1;

echo '

<div id="procesandoNivel5"></div>
<div id="borrandoNivel5"></div>

<table class="tablaFormularios">
	<tr>
		<th colspan="2">Detalles de cuenta nivel 4</th>
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
		<th colspan="2">Registrar cuenta nivel 5</th>
	</tr>
	
	<tr>
		<td class="etiquetas">Cuenta:</td>
		<td>
			<input type="text" class="textos" id="txtCuentaNivel5" name="txtCuentaNivel5" placeholder="Nivel 5" />
			<input type="hidden" id="txtIdSubCuenta4" name="txtIdSubCuenta4" value="'.$cuenta->idSubCuenta4.'"/>
		</td>
	</tr>
	
</table>';

if($cuentas!=null)
{
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaNivel5 tr:even").addClass("abajo");
		$("#tablaNivel5 tr:odd").addClass("arriba");  
	});
	</script>
	
	<table class="tablaDatos" id="tablaNivel5">
		<tr>
			<th class="titulos" colspan="3">Lista de cuentas nivel 5</th>
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
				<img src="'.base_url().'img/cuentas.png" title="Subcuentas nivel 6" onclick="obtenerNivel6('.$row->idSubCuenta5.')" />
				<img src="'.base_url().'img/editar.png" title="Editar" onclick="obtenerCuentaNivel5('.$row->idSubCuenta5.')" />
				<img src="'.base_url().'img/borrar.png" title="Borrar" onclick="borrarNivel5('.$row->idSubCuenta5.')" />
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="erroresDatos">Sin registros</div>';
}
?>