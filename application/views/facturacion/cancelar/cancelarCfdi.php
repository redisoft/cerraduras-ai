<script>
$(document).ready(function()
{
	$(document).ready(function()
	{
		$("#txtBuscarFolioSustitución").autocomplete(
		{
			source:base_url+"configuracion/autoCompletadoUuid",
			select: function(event,ui)
			{
				$("#txtIdFacturaSustitucion").val(ui.item.idFactura);
				$("#txtUuidSustitucion").val(ui.item.UUID);
			}
		});
	});

});
</script>

<?php
echo'
<form id="frmCancelar">
	<table class="admintable" width="100%">
		<tr>
			<th colspan="2">Detalle de cancelación del CFDI</th>
		</tr>
		<tr>
			<td class="key">Folio:</td>
			<td>'.$factura->serie.$factura->folio.'</td>
		</tr>
			<tr>
			<td class="key">Total:</td>
			<td>$ '.number_format($factura->pago=='0'?$factura->total:$pago->importe,2).'</td>
		</tr>

		<tr>
			<td class="key">Motivo cancelación:</td>
				<td>
				<select class="cajas" id="selectMotivoCancelacion" name="selectMotivoCancelacion" required="true" onchange="opcionesCancelacionCfdi()" style="width: 400px">';

					foreach($cancelaciones as $row)
					{
						echo'<option value="'.$row->clave.'">'.$row->clave.', '.$row->nombre.'</option>';
					}

				echo'</select>

				<label id="obtenerFolio"></label>
			</td>
		</tr>

		<tr id="filaFolioSustitucion">
			<td class="key">Folio sustitución:</td>
			<td>
				<input type="text" class="cajas" id="txtBuscarFolioSustitución" name="txtBuscarFolioSustitución" placeholder="Seleccione" style="width: 400px" />
				<input type="hidden" id="txtIdFacturaSustitucion"  	name="txtIdFacturaSustitucion" 	value="0" />
				<input type="hidden" id="txtUuidSustitucion"  		name="txtUuidSustitucion" 	value="" />
			</td>
		</tr>

		<tr>
			<td class="key">Comentarios:</td>
			<td>
				<textarea style="width:400px" id="motivosCancelacion" name="motivosCancelacion" class="TextArea" ></textarea>
				<input type="hidden" id="txtIdFacturaCancelar" name="txtIdFacturaCancelar" value="'.$idFactura.'" />
			</td>
		</tr>
	</table>
</form>';