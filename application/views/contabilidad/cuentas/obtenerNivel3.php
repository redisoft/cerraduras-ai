<?php
$i=1;
echo '

<div id="procesandoNivel3"></div>
<div id="borrandoNivel3"></div>

<table class="tablaFormularios">
	<tr>
		<th colspan="2">Detalles de cuenta nivel 2</th>
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
		<th colspan="2">Registrar cuenta nivel 3</th>
	</tr>
	
	<tr>
		<td class="etiquetas">Cuenta:</td>
		<td>
			<input type="text" class="textos" id="txtCuentaNivel3" name="txtCuentaNivel3" placeholder="Nivel 3" />
			<input type="hidden" id="txtIdSubCuenta" name="txtIdSubCuenta" value="'.$cuenta->idSubCuenta.'"/>
		</td>
	</tr>
	
</table>';

if($cuentas!=null)
{
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaNivel3 tr:even").addClass("abajo");
		$("#tablaNivel3 tr:odd").addClass("arriba");  
	});
	</script>
	
	<table class="tablaDatos" id="tablaNivel2">
		<tr>
			<th class="titulos" colspan="3">Lista de cuentas nivel 3</th>
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
				<img src="'.base_url().'img/cuentas.png" title="Subcuentas nivel 4" onclick="obtenerNivel4('.$row->idSubCuenta3.')" />
				<img src="'.base_url().'img/editar.png" title="Editar" onclick="obtenerCuentaNivel3('.$row->idSubCuenta3.')" />
				<img src="'.base_url().'img/borrar.png" title="Borrar" onclick="borrarNivel3('.$row->idSubCuenta3.')" />
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