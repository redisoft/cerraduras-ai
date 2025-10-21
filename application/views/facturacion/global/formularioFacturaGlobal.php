<script>
$(document).ready(function()
{
	$("#txtBuscarClientes").autocomplete(
	{
		source:base_url+'configuracion/obtenerClientes',
		
		select:function( event, ui)
		{
			$('#txtIdClienteGlobal').val(ui.item.idCliente);
			
			obtenerDireccionesCliente(ui.item.idCliente);
		}
	});
	
	obtenerFolioEmisor();
	obtenerTotalesFactura();
	
	obtenerDireccionesCliente(1);
});
	
$('#txtFechaFactura').timepicker();
</script>
<?php

echo'
<form id="frmFacturacion" name="frmFacturacion">
<input type="hidden" id="txtComprobante" 	name="txtComprobante" value="ingreso" />

<table class="admintable" width="100%;">
	<tr style="display:none">
		<td class="key">
			Tipo de rango
			
		</td>
		<td>
			<select class="cajas" id="selectTipoRango" name="selectTipoRango" onchange="rangoDatos()">
				<option>Fechas</option>
				<option>Folios</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">
			Tipo
			
		</td>
		<td>
			<select class="cajas" id="selectTipoDocumento" name="selectTipoDocumento" onchange="obtenerTotalesFactura()">
				<option value="0">Remisión</option>
				<option value="1">PREFACTURA</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha:</td>
		<td>
			<input type="text" style="width:120px" class="cajas" id="txtFechaFactura" name="txtFechaFactura" value="'.date('Y-m-d H:i').'" />
		</td>
	</tr>	
	
	<tr id="filaRangoFechas">
		<td class="key">
			Seleccione rango de fechas
			
		</td>
		<td>
			<input style="width:120px" type="text" id="txtInicio" 	name="txtInicio" value="'.date('Y-m-d').'" onchange="obtenerTotalesFactura()" class="cajas" />
			<input style="width:120px" type="text" id="txtFin" 		name="txtFin" value="'.date('Y-m-d').'" onchange="obtenerTotalesFactura()" class="cajas" />
			<script>
				$("#txtInicio,#txtFin").datepicker();
			</script>
		</td>
	</tr>
	
	<tr id="filaRangoNotas" style="display:none">
		<td class="key">
			Seleccione rango de de notas
		</td>
		<td>
			<input style="width:120px" type="text" id="txtFolioInicial" 	name="txtFolioInicial" 		placeholder="Folio inicial" onchange="obtenerTotalesFactura()" class="cajas" onkeypress="return soloNumerico(event)" />
			<input style="width:120px" type="text" id="txtFolioFinal" 		name="txtFolioFinal" 		placeholder="Folio final" 	onchange="obtenerTotalesFactura()" class="cajas" onkeypress="return soloNumerico(event)"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Cliente:</td>
		<td>
			<input type="text" style="width:600px" class="cajas" id="txtBuscarClientes" name="txtBuscarClientes" placeholder="Seleccione cliente" value="Ventas Público en General" readonly />
			<input type="hidden" id="txtIdClienteGlobal" name="txtIdClienteGlobal" value="1"  />
		</td>
	</tr>	
	
	<tr>
		<td class="key">Dirección: </td>

		<td id="obtenerDireccionesCliente">
			<select style="width:550px" id="selectDirecciones" name="selectDirecciones" class="cajas" >
				<option value="0">Seleccione</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Emisor:</td>
		<td>
			<select id="selectEmisoresGlobal" name="selectEmisoresGlobal" onchange="obtenerFolioEmisor()" class="cajas" style="width:400px">';
			
			if($emisores!=null)
			{
				foreach($emisores as $row)
				{
					echo '<option value="'.$row->idEmisor.'">'.$row->nombre.'</option>';
				}
			}
			else
			{
				echo '<option value="0">Registre un emisor</option>';
			}
			
				
			echo'
			</select>
		</td>
	</tr>	
	
	
	<tr>
		<td class="key">Folio:</td>
		<td id="obtenerFolioEmisor">Seleccione emisor</td>
	</tr>
	
	<tr>
		<td class="key">Uso del CFDI:</td>
		<td>
			<select id="selectUsoCfdi" name="selectUsoCfdi" class="cajas" style="width:400px">';
			
			foreach($usos as $row)	
			{
				echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->descripcion.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Método de pago</td>
		<td>
			<select id="txtMetodoPago" name="txtMetodoPago" class="cajas" style="width:400px" >';
			
			foreach($metodos as $row)	
			{
				echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
			}
			
		echo'
			</select>
			
			<!--<input type="text"  style="width:120px" class="cajas" id="txtCuentaPago" name="txtCuentaPago" value="" placeholder="Cuenta" />
			<input type="hidden" style="width:400px" class="cajas" id="txtMetodoPagoTexto" name="txtMetodoPagoTexto" value="01 Efectivo" />-->
		</td>
	</tr>
	
	<tr>
		<td class="key">Forma  y cuenta de pago:</td>
		<td>
			<select id="txtFormaPago" name="txtFormaPago" class="cajas" style="width:400px">';
			
			foreach($formas as $row)	
			{
				echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
			}
			
			echo'
			</select>
			
			<input type="text"  style="width:120px" class="cajas" id="txtCuentaPago" name="txtCuentaPago" value="" placeholder="Cuenta" />
		</td>
	</tr>
	
	<!--<tr>
		<td class="key">Método y cuenta de pago</td>
		<td>
			<select id="txtMetodoPago" name="txtMetodoPago" class="cajas" style="width:400px" onchange="sugerirMetodoPago()">';
			
			foreach($metodos as $row)	
			{
				echo '<option value="'.$row->clave.'">'.$row->clave.' '.$row->concepto.'</option>';
			}
			
		echo'
			</select>
			
			<input type="text"  style="width:120px" class="cajas" id="txtCuentaPago" name="txtCuentaPago" value="" placeholder="Cuenta" />
		
			<input type="hidden" style="width:400px" class="cajas" id="txtMetodoPagoTexto" name="txtMetodoPagoTexto" value="01 Efectivo" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Forma de pago:</td>
		<td>
			<input type="text" style="width:400px" class="cajas" id="txtFormaPago" name="txtFormaPago" value="Pago en una sola exhibición" />
		</td>
	</tr>-->
	
	
	<!-- <tr>
		<td class="key">Condiciones de pago:</td>
		<td>
			<input type="text" style="width:400px" class="cajas" id="txtCondiciones" name="txtCondiciones" value="Contado" />
		</td>
	</tr>-->
	
	<tr>
		<td class="key">Condiciones de pago:</td>
		<td>
			<select id="txtCondiciones" name="txtCondiciones" class="cajas" style="width:400px">
				<option>CONTADO</option>
				<option>CRÉDITO</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Año</td>
		<td >

			<select id="selectAnio" name="selectAnio" class="cajas">';
				
				for($i=date('Y'); $i<=(date('Y')+1);$i++)
				{
					echo '<option>'.$i.'</option>';
				}
			echo'
			</select>
		</td>
	</tr>

	<tr>
		<td class="key">Mes</td>
		<td >
			<select id="selectMeses" name="selectMeses" class="cajas">';
				
				foreach($meses as $row)
				{
					echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->nombre.'</option>';
				}
				
			echo'
			</select>
		</td>
	</tr>

	<tr>
		<td class="key">Periodicidad</td>
		<td>
			<select id="selectPeriodicidad" name="selectPeriodicidad" class="cajas">';
				
				foreach($periodos as $row)
				{
					echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->nombre.'</option>';
				}
				
			echo'
			</select>
		</td>
	</tr>
	
</table>

<div id="obtenerTotalesFactura">
	<input type="hidden" id="txtTotalesFacturaGlobal" name="txtTotalesFacturaGlobal" value="0"  />
	<input type="hidden" id="txtConceptoGlobal" name="txtConceptoGlobal" value=""  />
	<table class="admintable" style="width:100%">
		<tr>
			<td class="key">Concepto</td>
			<td>Seleccione rango de fechas</td>
		</tr>
		
		<tr>
			<td class="key">Subtotal</td>
			<td>'.number_format(0,2).'</td>
		</tr>
		
		<!--<tr>
			<td class="key">Descuento (0%)</td>
			<td>$'.number_format(0,2).'</td>
		</tr>-->
		
		<tr>
			<td class="key" >IVA ('.(0).'%)</td>
			<td>$'.number_format(0,2).'</td>
		</tr>
		<tr>
			<td class="key">Total</td>
			<td>$'.number_format(0,2).'</td>
		</tr>
	</table>
</div>
</form>';
?>