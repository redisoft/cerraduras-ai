<script>
$(document).ready(function()
{
	obtenerFolioEmisor();
	$("#txtFechaPago").timepicker();
});
</script>

<?php
echo
'<div id="registrandoPago"></div>
<form id="frmRegistrarPago" name="frmRegistrarPago">
	<table class="admintable" id="tablaFormularioFacturacion" style="width:100%">
		<tr>
			<th colspan="4" style="text-align:center">Datos del pago</th>
		</tr>
		
		<tr>
			<td class="key" style="width: 15%">Emisor:</td>
			<td>
				<select id="selectEmisoresGlobal" name="selectEmisoresGlobal" onchange="obtenerFolioEmisor()" class="cajas" style="width:97%">';
				
				if($emisores!=null)
				{
					foreach($emisores as $row)
					{
						echo '<option value="'.$row->idEmisor.'">'.$row->rfc.', '.$row->nombre.'</option>';
					}
				}
				else
				{
					echo '<option value="0">Registre un emisor</option>';
				}
				
					
				echo'
				</select>
			</td>
			<td class="key" style="width: 15%">Folio:</td>
			<td id="obtenerFolioEmisor"></td>
		</tr>

		<tr>
			<td class="key">Parcialidad:</td>
			<td>
				'.$factura->parcialidad.'
			</td>
			<td class="key">Número de operación:</td>
			
			<td>
				<input type="text" class="cajas" id="txtNumeroOperacion" name="txtNumeroOperacion" style="width: 97%" />
			</td>
		</tr>
	
	
		<tr>
			<td class="key">RFC ordenante:</td>
			<td>
				<input type="text" class="cajas" id="txtRfcOrdenante" name="txtRfcOrdenante"  style="width: 97%" />
			</td>
			<td class="key">Cuenta ordenante:</td>
			<td>
				<input type="text" class="cajas" id="txtCuentaOrdenante" name="txtCuentaOrdenante" style="width: 97%" />
			</td>
		</tr>
	
		<tr>
			<td class="key">RFC beneficiario:</td>
			<td>
				<input type="text" class="cajas" id="txtRfcBeneficiario" name="txtRfcBeneficiario" style="width: 97%" /> 
				<input type="hidden"  id="txtIdFactura" name="txtIdFactura" value="'.$factura->idFactura.'" />
				<input type="hidden"  id="txtNumeroParcialidad" name="txtNumeroParcialidad" value="'.$factura->parcialidad.'" />
			</td>
			<td class="key">Cuenta beneficiario:</td>
			<td>
				<input type="text" class="cajas" id="txtCuentaBeneficiario" name="txtCuentaBeneficiario" style="width: 97%"/>
			</td>
		</tr>
	
	
		
		<tr>
			<td class="key">Fecha de pago:</td>
			
			<td>
				<input type="text" class="cajas" id="txtFechaPago" name="txtFechaPago" value="'.date('Y-m-d H:i').'" readonly="readonly" style="width:120px" />
			</td>
			<td class="key">Forma de pago:</td>
			<td>
				<select class="cajas" id="selectFormaPago" name="selectFormaPago">';
					
					foreach($formas as $row)
					{
						echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
					}
					
				echo'</select>
			</td>
		</tr>
		
		
		<tr>
			<td class="key">Total:</td>
			<td>
				$'.number_format($factura->total,2).'
			</td>
			<td class="key">Saldo:</td>
			<td>
				$'.number_format($factura->total-$factura->saldo,2).'
				<input type="text" class="cajas" id="txtSaldoFactura" name="txtSaldoFactura" value="'.($factura->total-$factura->saldo).'" style="display:none" />
			</td>
		</tr>

		<tr>
			<td class="key">Importe:</td>
			<td>
				<input type="text" class="cajas" id="txtImportePagar" name="txtImportePagar" value="'.($factura->total-$factura->saldo).'"  maxlength="10" onkeypress="return soloDecimales(event)" />
			</td>
			<td class="key">IVA:</td>
			<td>
				<input type="text" class="cajas" id="txtImporteIva16" name="txtImporteIva16" value="'.round($factura->iva,2).'"  maxlength="10" onkeypress="return soloDecimales(event)" />
			</td>
		</tr>
	</table>
</form>';

if($pagos!=null)
{
	echo '
	<table class="admintable" id="tablaFormularioFacturacion" style="width:100%">
		<tr>
			<th>#</th>
			<th>Fecha de pago</th>
			<th>Serie y folio</th>
			<th>Importe</th>
			<th width="20%">Acciones</th>
		</tr>';
	
	$i=1;
	foreach($pagos as $row)
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fechaPago).'</td>
			<td align="center">'.$row->serie.$row->folio.($row->cancelada=='1'?'<i>(Cancelada)</i>':'').'</td>
			<td align="right">$'.number_format($row->importe,2).'</td>
			<td align="left">
				<img onclick="window.open(\''.base_url().'pdf/crearFactura/'.$row->idFactura.'\')" src="'.base_url().'img/pdf.png" width="25" />
				
				<img onclick="window.open(\''.base_url().'facturacion/descargarXML/'.$row->idFactura.'\')" src="'.base_url().'img/xml.png" width="25" style="cursor:pointer" />';
				
				if($row->cancelada=="0")
				{
					echo '&nbsp;&nbsp;
					<img onclick="accesoCancelarCfdi('.$row->idFactura.')" src="'.base_url().'img/cancelar.png" width="25" style="cursor:pointer" title="Cancelar" />';
				}
				
			echo'
			<br />
			<a>PDF &nbsp;&nbsp; 
			XML</a>';
			
			if($row->cancelada=="0")
			{
				echo ' <a>Cancelar</a>';
			}
			
			
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo'
	</table>';
}

?>
