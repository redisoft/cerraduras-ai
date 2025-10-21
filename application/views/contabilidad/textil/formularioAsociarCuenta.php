<?php
$i=1;

echo '
<input type="hidden" id="txtIdGasto" name="txtIdGasto" value="'.$gasto->idGasto.'" />
<table class="admintable" width="100%">
	<tr>
		<td class="key">Tipo:</td>
		<td>'.$gasto->nombre.'</td>
	</tr>
	
	<tr>
		<td class="key">Cuenta:</td>
		<td>
			<select class="cajas" id="selectCuentaCatalogo" name="selectCuentaCatalogo" style="width:400px">
				<option value="0">Seleccione</option>';
				
				foreach($cuentas as $row)
				{
					echo '<option value="'.$row->idCuentaCatalogo.'">'.$row->numeroCuenta.'('.($row->naturaleza=='A'?'Acreedora':'Deudora').', '.$row->descripcion.', '.obtenerMesAnio($row->fecha).')</option>';
				}
			echo'
			</select>
		</td>
	</tr>
</table>';

if($asociadas!=null)
{
	echo '
	<script>
	$(document).ready(function()
	{
		$("#tablaCuentasAsociadas tr:even").addClass("sinSombra");
		$("#tablaCuentasAsociadas tr:odd").addClass("sombreado");  
	});
	</script>
	<table class="admintable" width="100%" id="tablaCuentasAsociadas">
		<tr>
			<th colspan="7">Detalle de cuentas</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Código agrupador</th>
			<th>Número de cuenta</th>
			<th>Descripción</th>
			<th>Naturaleza</th>
			<th>Mes</th>
			<th>Acciones</th>
		</tr>';
		
		$i=1;
		foreach($asociadas as $row)
		{
			echo '
			<tr>
				<td>'.$i.'</td>
				<td>'.$row->codigoAgrupador.'</td>
				<td>'.$row->numeroCuenta.'</td>
				<td>'.$row->descripcion.'</td>
				<td>'.($row->naturaleza=='A'?'Acreedora':'Deudora').'</td>
				<td>'.obtenerMesAnio($row->fecha).'</td>
				<td class="vinculos">
					<img src="'.base_url().'img/borrar.png" onclick="borrarCuentaGasto('.$row->idRelacion.')" />
					<br />
					Borrar
				</td>
			</tr>';
		}
		
	echo'
	</table>';
}

?>