<script>
//opcionesFormasPago()
$('#txtDescripcionProducto').focus()
</script>

<?php
echo'
<form id="frmAcrilico" name="frmAcrilico">
	<table class="admintable" width="100%;" >
		<tr>
			<th colspan="2" class="encabezadoPrincipal">Detalles de acrílico</th>
		</tr>
		<tr>
		
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" class="cajas" id="txtFechaEgreso" name="txtFechaEgreso" value="'.date('Y-m-d H:i').'" style="width:120px" />
				<input type="hidden"  id="txtIdCotizacion" name="txtIdCotizacion" value="'.$pedido->idCotizacion.'" />
				<script>
					$("#txtFechaEgreso").timepicker();
				</script>
			</td>
		</tr>

		<tr>
			<td class="key">Monto a devolver: </td>
			<td>
				<input type="text" class="cajas" id="txtAcrilico" name="txtAcrilico" style="width:80px" value="'.round($pedido->acrilico,decimales).'" readonly="readonly" />
			</td>
		</tr>

		<tr>
			<td class="key">Descripción del producto:</td>
			<td>
				<textarea type="text" class="TextArea" id="txtDescripcionProducto" name="txtDescripcionProducto" style="height:50px; width:288px">Devolución anticipo acrílico</textarea>
			</td>
		</tr>
		
	</table>
</form>';
