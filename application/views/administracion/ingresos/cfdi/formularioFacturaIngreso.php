<?php
echo'
<script>
$(document).ready(function()
{
	$("#txtUnidad").autocomplete(
	{
		source:"'.base_url().'configuracion/autoCompletadoUnidades",
		select: function(event,ui)
		{
			$("#txtClaveUnidad").val(ui.item.clave);
			$("#txtUnidadDescripcion").val(ui.item.nombre);
		}
	});
	
	$("#txtClaveProductoServicio").autocomplete(
	{
		source:"'.base_url().'configuracion/autoCompletadoProductoServicios",
		select: function(event,ui)
		{
			$("#txtClaveProducto").val(ui.item.clave);
			$("#txtClaveDescripcion").val(ui.item.nombre);
		}
	});
});
</script>
<form id="frmFacturaIngreso">
	<input type="hidden" id="txtNotaAbierto" name="txtNotaAbierto" value="1"/>

	<input type="hidden" id="txtSubTotal" 		name="txtSubTotal" 		value="'.$ingreso->subTotal.'"/>
	<input type="hidden" id="txtIvaPorcentaje" 	name="txtIvaPorcentaje" value="'.$ingreso->iva.'"/>
	<input type="hidden" id="txtIva" 			name="txtIva" 			value="'.$ingreso->ivaTotal.'"/>
	<input type="hidden" id="txtTotal" 			name="txtTotal" 		value="'.$ingreso->pago.'"/>
	<input type="hidden" id="txtIdCliente" 		name="txtIdCliente" 	value="'.$ingreso->idCliente.'"/>
	<input type="hidden" id="txtIdIngreso" 		name="txtIdIngreso" 	value="'.$ingreso->idIngreso.'"/>
	
	<table class="admintable" width="100%;">
		<tr>
		  <td class="key">Cliente:</td>
		  <td id="lblRazonSocial">'.$ingreso->razonSocial.'</td>
		</tr>	

		<tr>
			<td class="key">Subtotal</td>
			<td id="lblSubTotal">'.number_format($ingreso->subTotal,2).'</td>
		</tr>
		<tr>
			<td class="key" >IVA ('.number_format($ingreso->iva,2).'%)</td>
			<td id="lblIva">$'.number_format($ingreso->ivaTotal,2).'</td>
		</tr>
		
		<tr>
			<td class="key">Total</td>
			<td><label id="lblTotalNota">$'.number_format($ingreso->pago,2).'</label></td>
		</tr>
		
		<tr>
			<td class="key">Emisor:</td>
			<td>
				
				<select style="width:400px" id="selectEmisores" name="selectEmisores" class="cajas" onchange="obtenerFolio()">
					<option value="0">Seleccione</option>';
				
				foreach($emisores as $row)
				{
					$seleccionado='selected="selected"';#$row->idEmisor==$cliente->idEmisor?'selected="selected"':'';
					echo '<option '.$seleccionado.' value="'.$row->idEmisor.'">(Serie '.$row->serie.') '.$row->rfc.', '.$row->nombre.'</option>';
				}
				
			echo'
			</td>
		</tr>
		
		<tr>
			<td class="key">Folio:</td>
			<td id="obtenerFolio" colspan="2">
				Seleccionar emisor
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Divisa:</td>
			<td>
				<select class="cajas" id="selectDivisas" name="selectDivisas">';
			
			foreach ($divisas as $row)
			{
				$seleccionado=$row->idDivisa==$cotizacion->idDivisa?'selected="selected"':'';
				
				echo '<option '.$seleccionado.' value="'.$row->idDivisa.'">'.$row->nombre.' ($'.$row->tipoCambio.')</option>';
			}
			
			echo'
				</select>
			</td>
		</tr>';
		
		$alumno	= $ingreso->razonSocial;
		if(strlen($ingreso->cliente)>2) $alumno	= $ingreso->cliente;
		if(strlen($ingreso->alumno)>2) $alumno	= $ingreso->alumno;
		
		echo'
		<tr>
			<td class="key">Concepto:</td>
			<td>
				<textarea class="TextArea" id="txtConcepto" name="txtConcepto" style="height:40px; width:400px;">'.($programa!=null?$programa->nombre."\n":'').$ingreso->producto.' - '.$alumno.'</textarea>
			</td>
		</tr>	
		<tr>
			<td class="key">Unidad</td>
			<td>
				<input type="text" class="cajas" name="txtUnidad" id="txtUnidad" style="width: 300px" placeholder="Seleccione" value="E48, Unidad de servicio" />
				
				<input type="hidden" id="txtClaveUnidad" name="txtClaveUnidad" value="E48" />
				<input type="hidden" id="txtUnidadDescripcion" name="txtUnidadDescripcion" value="Unidad de servicio" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Clave producto / servicio:</td>
			<td>
				<input type="text" 		class="cajas" id="txtClaveProductoServicio" name="txtClaveProductoServicio" placeholder="Seleccione" value="86111500, Servicios de aprendizaje a distancia" style="width:300px"/>
				
				<input type="hidden" id="txtClaveProducto" 		name="txtClaveProducto" 	value="86111500" />
				<input type="hidden" id="txtClaveDescripcion" 	name="txtClaveDescripcion" 	value="Servicios de aprendizaje a distancia" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Uso del CFDI:</td>
			<td>
				<select id="selectUsoCfdi" name="selectUsoCfdi" class="cajas" style="width:400px">';
				
				foreach($usos as $row)	
				{
					echo '<option '.($row->clave=='P01'?'selected="selected"':'').' value="'.$row->clave.'">'.$row->clave.', '.$row->descripcion.'</option>';
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
		
		
		
		 <tr>
			<td class="key">Condiciones de pago</td>
			<td>
				<input type="text" style="width:400px" class="cajas" id="txtCondiciones" name="txtCondiciones" value="" />
			</td>
		</tr>
		<tr>
			<td class="key">Observaciones</td>
			<td>
				<textarea class="TextArea" id="txtObservaciones" name="txtObservaciones" style="width:400px; height:60px"></textarea>
			</td>
		</tr>
	</table>
</form>';