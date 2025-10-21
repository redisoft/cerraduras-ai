<?php
$i=1;

echo '

<div id="procesandoNivel4"></div>
<div id="borrandoNivel4"></div>

<table class="tablaFormularios">
	<tr>
		<th colspan="2">Detalles de cuenta nivel 3</th>
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
		<th colspan="2">Registrar cuenta nivel 4</th>
	</tr>
	
	<tr>
		<td class="etiquetas">Cuenta:</td>
		<td>
			<input type="text" class="textos" id="txtCuentaNivel4" name="txtCuentaNivel4" placeholder="Nivel 4" />
			<input type="hidden" id="txtIdSubCuenta3" name="txtIdSubCuenta3" value="'.$cuenta->idSubCuenta3.'"/>
		</td>
	</tr>
	
</table>';

if($cuentas!=null)
{
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaNivel4 tr:even").addClass("abajo");
		$("#tablaNivel4 tr:odd").addClass("arriba");  
	});
	</script>
	
	<table class="tablaDatos" id="tablaNivel4">
		<tr>
			<th class="titulos" colspan="3">Lista de cuentas nivel 4</th>
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
				<img src="'.base_url().'img/cuentas.png" title="Subcuentas nivel 5" onclick="obtenerNivel5('.$row->idSubCuenta4.')" />
				<img src="'.base_url().'img/editar.png" title="Editar" onclick="obtenerCuentaNivel4('.$row->idSubCuenta4.')" />
				<img src="'.base_url().'img/borrar.png" title="Borrar" onclick="borrarNivel4('.$row->idSubCuenta4.')" />
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