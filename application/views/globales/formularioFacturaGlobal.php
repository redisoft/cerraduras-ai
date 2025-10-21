<script>
$(document).ready(function()
{
	$("#txtBuscarClientes").autocomplete(
	{
		source:base_url+'configuracion/obtenerClientes',
		
		select:function( event, ui)
		{
			$('#txtIdClienteGlobal').val(ui.item.idCliente);
		}
	});
	
	$("#txtBuscarUnidad").autocomplete(
	{
		source:base_url+'configuracion/autoCompletadoUnidades',
		select: function(event,ui)
		{
			$("#txtUnidad").val(ui.item.nombre);
			$("#txtClaveUnidad").val(ui.item.clave);
		}
	});
	
	$("#txtBuscarClave").autocomplete(
	{
		source:base_url+"configuracion/autoCompletadoProductoServicios",
		select: function(event,ui)
		{
			$("#txtClaveProducto").val(ui.item.clave);
			$("#txtClaveDescripcion").val(ui.item.nombre);
		}
	});
	
	obtenerFolioEmisor();
});
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
	
	<tr id="filaRangoFechas" style="display:none">
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
	
	<tr id="filaRangoNotas" style="display:none" style="display:none">
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
			<input type="text" style="width:600px" class="cajas" id="txtBuscarClientes" name="txtBuscarClientes" placeholder="VENTAS AL PÚBLICO EN GENERAL" />
			<input type="hidden" id="txtIdClienteGlobal" name="txtIdClienteGlobal" value="1"  />
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
			<select id="selectMetodoPago" name="selectMetodoPago" class="cajas" style="width:400px" >';
			
			foreach($metodos as $row)	
			{
				echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
			}
			
		echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Forma  y cuenta de pago:</td>
		<td>
			<select id="selectFormaPago" name="selectFormaPago" class="cajas" style="width:400px">';
			
			foreach($formas as $row)	
			{
				echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
			}
			
			echo'
			</select>
			
			<input type="text"  style="width:120px" class="cajas" id="txtCuentaPago" name="txtCuentaPago" value="" placeholder="Cuenta" />
		</td>
	</tr>

	<tr>
		<td class="key">Condiciones de pago:</td>
		<td>
			<input type="text" style="width:400px" class="cajas" id="txtCondiciones" name="txtCondiciones" value="Contado" />
		</td>
	</tr>
	
	
	
	<tr>
		<td class="key">Unidad:</td>
		<td>
			<input type="text" id="txtBuscarUnidad" name="txtBuscarUnidad" value="ACT, Actividad" class="cajas" style="width:500px"  />
			<input type="hidden" id="txtUnidad" name="txtUnidad" value="Actividad" class="cajas" style="width:500px" />
			<input type="hidden" id="txtClaveUnidad" name="txtClaveUnidad" value="ACT" class="cajas" style="width:500px" />
		</td>
	</tr>	
	
	<tr>
		<td class="key">Clave producto:</td>
		<td>
			<input type="text" id="txtBuscarClave" name="txtBuscarClave" value="01010101, No existe en el catálogo" class="cajas" style="width:500px"  />
			<input type="hidden" id="txtClaveProducto" 		name="txtClaveProducto" 		value="01010101"  />
				<input type="hidden" id="txtClaveDescripcion" 	name="txtClaveDescripcion" 		value="No existe en el catálogo" />
		</td>
	</tr>
	
</table>

<div id="obtenerTotalesFactura">
	<input type="hidden" id="txtTotalesFacturaGlobal" name="txtTotalesFacturaGlobal" value="'.$totales->total.'"  />
	<input type="hidden" id="txtConceptoGlobal" name="txtConceptoGlobal" value=""  />
	<table class="admintable" style="width:100%">
		<tr>
			<td class="key">Subtotal:</td>
			<td>$'.number_format($totales->subTotal,decimales).'</td>
		</tr>
		
		<tr>
			<td class="key">Descuento:</td>
			<td>$'.number_format($totales->descuento,decimales).'</td>
		</tr>
		
		<tr>
			<td class="key" >IVA:</td>
			<td>$'.number_format($totales->iva,decimales).'</td>
		</tr>
		<tr>
			<td class="key">Total:</td>
			<td>$'.number_format($totales->total,decimales).'</td>
		</tr>
	</table>
</div>
</form>';
?>