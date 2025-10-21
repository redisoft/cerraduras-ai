<script>
$(document).ready(function()
{
	$("#txtFechaEntrega").datepicker();	
	$("#txtHora").timepicker({timeOnly: true});
	
	$("#txtClientePedido").autocomplete(
	{
		source:base_url+'configuracion/obtenerClientes',
		
		select:function( event, ui)
		{
			$("#txtIdCliente").val(ui.item.idCliente);
			$("#txtBuscarCliente").val(ui.item.value);
			obtenerBancos();
			obtenerDireccionesEntrega()
		}
	});
	
	$("#txtBuscarMateriaPrima").autocomplete(
	{
		source:base_url+"configuracion/obtenerMateriales",
		
		select:function( event, ui)
		{
			cargarMateriaPrimaPedido(ui.item)
		}
	});
});
</script>
<?php
echo '


<form id="frmPedidos">
	<input type="hidden" id="txtPedidoActivo" name="txtPedidoActivo" value="1" />
	<table class="admintable" width="100%;">
		<tr>
			<td class="key">Cliente:</td>
			<td>
				<div style="width:310px; float: left">
					<input type="text" class="cajas" id="txtClientePedido" name="txtClientePedido" style="width:300px" placeholder="Seleccione" />
				</div>
				
				<div style="width:100px; float: left" align="center">
					<img src="'.base_url().'img/clientes.png" onclick="formularioClientes(\'venta\')" title="Nuevo cliente" width="22" /><br />
					Agregar cliente
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="key">Dirección de entrega:</td>
			<td>
				<div id="obtenerDireccionesEntrega">
					<select id="selectDirecciones" name="selectDirecciones" class="cajas" style="width:300px">
						<option value="0">Seleccione cliente</option>
					</select>
				</div>
				
				
			</td>
		</tr>
		
		<tr>
			<td class="key">Sabor:</td>
			<td>
				<input type="text" class="cajas" id="txtSabor" name="txtSabor" style="width:300px" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Cobertura:</td>
			<td>
				<input type="text" class="cajas" id="txtCobertura" name="txtCobertura" style="width:300px" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Relleno:</td>
			<td>
				<input type="text" class="cajas" id="txtRelleno" name="txtRelleno" style="width:300px" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Forma:</td>
			<td>
				<input type="text" class="cajas" id="txtForma" name="txtForma" style="width:300px" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Decoración:</td>
			<td>
				<textarea class="TextArea" id="txtDecoracion" name="txtDecoracion" style="height:70px; width:300px" ></textarea>
			</td>
		</tr>
		
		<tr>
			<td class="key">Peso en kg:</td>
			<td>
				<input type="text" class="cajas" id="txtPesoKg" name="txtPesoKg" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="5" onchange="calcularTotalesMateriales()" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Fecha entrega:</td>
			<td>
				<input type="text" class="cajas" id="txtFechaEntrega" name="txtFechaEntrega" style="width:100px" value="'.date('Y-m-d').'" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Hora:</td>
			<td>
				<!--<input type="text" class="cajas" id="txtHora" name="txtHora" style="width:100px" value="'.date('H:i').'" />-->
				
				<select class="cajas" id="selectHoras" name="selectHoras" style="width:50px">';
				
				for($i=0;$i<=23;$i++)
				{
					echo '<option>'.($i<10?'0':'').''.$i.'</option>';
				}
				echo'
				</select>
				
				:
				
				<select class="cajas" id="selectMinutos" name="selectMinutos" style="width:50px">';
				
				for($i=0;$i<60;$i++)
				{
					echo '<option>'.($i<10?'0':'').''.$i.'</option>';
				}
				echo'
				</select>';
				
				
				
				
			echo'
			</td>
		</tr>
		
		<tr>
			<td class="key">¿Pastel especial?:</td>
			<td>
				<input type="checkbox" id="chkEspecial" name="chkEspecial" value="1" onchange="pastelEspecial()"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Descripción:</td>
			<td>
				<textarea class="TextArea" id="txtEspecial" name="txtEspecial" style="height:70px; width:300px" disabled="disabled" onchange="etiquetaProductoEspecial()"></textarea>
			</td>
		</tr>
	</table>
	
	
	<input type="hidden" id="txtNumeroMateriales" name="txtNumeroMateriales" value="0" />
	<table class="admintable" width="100%;" id="tablaMateriales">
		<tr>
			<td colspan="7">
				<input type="text" class="cajas" id="txtBuscarMateriaPrima" name="txtBuscarMateriaPrima" style="width:500px" placeholder="Seleccione materia prima" />
			</td>
		</tr>
		<tr>
			<th width="3%">#</th>
			<th width="20%">Codigo</th>
			<th width="34%">Materia prima</th>
			<th width="10%">Cantidad</th>
			<th width="13%">Unidad</th>
			<th width="10%">Precio</th>
			<th width="10%">Importe</th>
		</tr>
	</table>
	
	
</form>';