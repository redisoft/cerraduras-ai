
<?php
echo'
<div class="barraherramientas">
	
	<table class="tablaFormularios">
		<tr>
			<td class="etiquetas">RFC:</td>
			<td align="left">'.$poliza->rfc.'</td>
		</tr>
		<tr>
			<td class="etiquetas">Mes:</td>
			<td align="left">'.obtenerMesAnio($poliza->fecha).'</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
	
				<select id="selectTipoPoliza" name="selectTipoPoliza" class="selectTextos" onchange="obtenerConceptosPoliza()">
					<option value="0">Seleccione tipo  de póliza</option>
					<option value="1">Ingreso</option>
					<option value="2">Egreso</option>
					<option value="3">Diario</option>
				</select>
				
				<input type="hidden" id="txtIdPoliza" name="txtIdPoliza" value="'.$poliza->idPoliza.'" />
			</td>
		</tr>
		
	</table>
</div>';

echo '
<div id="obtenerConceptosPoliza"></div>';

