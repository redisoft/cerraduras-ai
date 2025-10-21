<?php
echo '
<script>
	$("#txtFechaInicialCampana,#txtFechaFinalCampana").datepicker();
</script>
<form id="frmCampana">
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtCampana" id="txtCampana" type="text" class="cajas" style="width:300px"  />
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha inicial:</td>
		<td>
			<input type="text" class="cajas" name="txtFechaInicialCampana" id="txtFechaInicialCampana" style="width:80px" value="'.date('Y-m-d').'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha final:</td>
		<td>
			<input type="text" class="cajas" name="txtFechaFinalCampana" id="txtFechaFinalCampana" style="width:80px" value="'.date('Y-m-d').'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Sin atrasos:</td>
		<td>
			<input type="checkbox" name="chkAtrasos" id="chkAtrasos" value="1"/>
		</td>
	</tr>
</table>

<input type="hidden" name="txtNumeroProgramas" id="txtNumeroProgramas" value="'.count($programas).'"/>';

if($programas!=null)
{
	echo '
	<script>
	$(document).ready(function()
	{
		$("#tablaListaProgramas tr:even").addClass("sinSombra");
		$("#tablaListaProgramas tr:odd").addClass("sombreado");  
	});
	</script>
	
	<table class="admintable" width="100%" id="tablaListaProgramas">
		<tr>
			<th colspan="3" class="encabezadoPrincipal">Programas</th>
		</tr>
		<tr>
			<th width="3%">#</th>
			<th width="70%">Programa</th>
			<th>Seleccionar</th>
		</tr>';
		
		$i=1;
		foreach($programas as $row)
		{
			echo '
			<tr>
				<td>'.$i.'</td>
				<td>'.$row->nombre.'</td>
				<td align="center">
					<input type="checkbox" id="chkPrograma'.$i.'" name="chkPrograma'.$i.'" value="'.$row->idPrograma.'" />
				</td>
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>';
}


echo'
</form>';