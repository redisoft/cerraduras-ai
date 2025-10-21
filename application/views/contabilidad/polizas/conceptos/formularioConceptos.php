<script>
$(document).ready(function()
{
	$("#txtFechaConcepto").datepicker();
	
	$("#txtBuscarCfdi").autocomplete(
	{
		source:base_url+'configuracion/obtenerComprobantes',
		
		select: function(event,ui)
		{
			 obtenerDatosXmlSistema(ui.item.idFactura);
		}
	});
});
</script>
<?php
echo'
<div id="registrandoInformacion"></div>
<form id="frmConceptos" name="frmConceptos">
	
	<input type="hidden" id="txtNumeroTransacciones" name="txtNumeroTransacciones" value="0"/>
	
	<table class="tablaFormularios">
		<tr>
			<th colspan="4">
			
				<div style="" id="subirImagen" class="custom-input-file" onclick="seleccionarFichero()">
					Póliza
					<input class="input-file" type="file" id="txtXml"/>
					<img src="'.base_url().'img/xml.png" onclick="" title="Importar xml" />
				</div>
			</th>	
		</tr>
		<tr>
			<td class="etiquetas">Buscar CFDI:</td>
			<td colspan="3">
				<input type="text" class="textosUuid" id="txtBuscarCfdi" name="txtBuscarCfdi" maxlength="300" style="width:800px" placeholder="Buscar por RFC, cliente, folio, serie, tipo" />
			</td
		</tr>
		<tr>
			<td class="etiquetas">Tipo de póliza:</td>
			<td>
				<select id="selectTipo" name="selectTipo" class="selectTextos" onchange="opcionesTipoPoliza(1)">
					<option value="1">Ingreso</option>
					<option value="2">Egreso</option>
					<option value="3">Diario</option>
				</select>
				
				<div id="tipoIngreso">
					<select id="selectTipoIngreso" name="selectTipoIngreso" class="selectTextos" style="margin-top:2px; width: 140px">
						<option value="1">Cobrado</option>
						<option value="0">No cobrado</option>
					</select>
				</div>
				
				<div id="tipoEgreso" style="display:none">
					<select id="selectTipoEgreso" name="selectTipoEgreso" class="selectTextos" style="margin-top:2px; width: 140px">
						<option value="1">Pagado</option>
						<option value="0">No pagado</option>
					</select>
				</div>
			</td>
		
		
			<td class="etiquetas">Número:</td>
			<td>
				<span id="lblPoliza">'.$polizas->polizaIngresos.'</span><input type="text" class="textosFechas" id="txtNumero" name="txtNumero" maxlength="50" value="'.$numero.'" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Fecha:</td>
			<td>
				<input type="text" class="textosFechas" id="txtFechaConcepto" name="txtFechaConcepto" readonly="readonly" value="'.$fecha.'" />
				<input type="hidden" id="txtFechaCatalogo" name="txtFechaCatalogo" value="'.$this->input->post('fecha').'" />
			</td>
		
			<td class="etiquetas">Concepto:</td>
			<td>
				<input type="text" class="textosUuid" id="txtConcepto" name="txtConcepto" maxlength="300" />
			</td>
		</tr>
	</table>
	
		
	<table class="tablaDatos" id="tablaTransacciones" style="margin-top:2px">
		<tr>
			<th class="titulos">-</th>
			<th class="titulos">Cuenta</th>
			<!--<th class="titulos">Descripción de la cuenta</th>-->
			<th class="titulos">Concepto</th>
			<th class="titulos">Productos o servicios</th>
			<th class="titulos">Debe</th>
			<th class="titulos">Haber</th>
			<th class="titulos">UUID</th>
		</tr>
	</table>
	
	<table class="tablaDatos" id="tablaCheques" style="display:none; margin-top:2px">
		<tr>
			<th class="titulos" colspan="8">Cheques</th>	
		</tr>
		<tr>
			<th>#</th>
			<th>Número</th>
			<th>Banco</th>
			<th>Cuenta origen</th>
			<th>Fecha</th>
			<th>Monto</th>
			<th>Beneficiario</th>
			<th>RFC</th>
		</tr>
	</table>
	
	<table class="tablaDatos" id="tablaTransferencias" style="display:none; margin-top:2px">
		<tr>
			<th class="titulos" colspan=7">Transferencias</th>	
		</tr>
		<tr>
			<th>#</th>
			<th>Cuenta y banco origen</th>
			<th>Monto</th>
			<th>Cuenta y banco destino</th>
			<th>Fecha</th>
			<th>Beneficiario</th>
			<th>RFC</th>
		</tr>
	</table>
	
	<table class="tablaDatos" id="tablaMetodos" style="display:none; margin-top:2px">
		<tr>
			<th class="titulos" colspan=8">Otros métodos de pago</th>	
		</tr>
		<tr>
			<th>#</th>
			<th>Método de pago</th>
			<th>Fecha</th>
			<th>Beneficiario</th>
			<th>RFC</th>
			<th>Monto</th>
			<th>Moneda</th>
			<th>Tipo cambio</th>
		</tr>
	</table>
	
	<div style="display:none" id="obtenerDatosXml" class="">No se ha cargado un archivo xml</div>

</form>';
