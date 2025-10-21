
<?php
echo'
<div class="barraherramientas">
	
	<table class="admintable" width="100%">
		<tr>
			<td class="key">RFC:</td>
			<td align="left">'.$poliza->rfc.'</td>
		</tr>
		<tr>
			<td class="etiquetas">Mes:</td>
			<td align="left">'.obtenerMesAnio($poliza->fecha).'</td>
		</tr>
		<tr>
			<td class="key">Buscar:</td>
			<td align="left">
	
				<select id="selectTipoPoliza" name="selectTipoPoliza" class="cajas" onchange="obtenerConceptosPoliza()">
					<option value="0">Seleccione tipo  de póliza</option>
					<option value="1">Ingreso</option>
					<option value="2">Egreso</option>
					<option value="3">Diario</option>
				</select>
				
				<input type="hidden" id="txtIdPoliza" name="txtIdPoliza" value="'.$poliza->idPoliza.'" />
				<input type="hidden" id="txtFechaPolizaTransaccion" name="txtFechaPolizaTransaccion" value="'.$poliza->fecha.'" />
			</td>
		</tr>
		
	</table>
</div>';

echo '
<div id="obtenerConceptosPoliza"></div>';

