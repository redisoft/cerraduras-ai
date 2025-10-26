<?php
echo '
<script>
$("#txtBuscarCliente").autocomplete(
{
	source:"'.base_url().'configuracion/obtenerClientes",
	
	select:function( event, ui)
	{
		$("#txtIdCliente").val(ui.item.idCliente);
		obtenerBancos();
		obtenerProductosVenta()
	}
});
</script>

<div style="width:49%; float: left">
	
	<div class="listaVentas">
		<table class="admintable" width="100%" id="tablaVentas">
		</table>
	</div>
	

	<table class="admintable" width="100%">
		<tr>
			<td colspan="3">
				<input placeholder="PÚBLICO GENERAL" type="text" class="cajas" id="txtBuscarCliente" style="width:500px"  />
				<input type="hidden" id="txtIdCliente" value="1" />
				<img src="'.base_url().'img/clientes.png" onclick="formularioClientes(\'venta\')" title="Nuevo cliente" width="22" />
			</td>
		</tr>

		<tr>
			<td>
				<select id="selectDivisas" name="selectDivisas" class="cajas" style="width:120px">';
					
					foreach($divisas as $row)
					{
						echo '<option value="'.$row->idDivisa.'">'.$row->nombre.' ('.$row->tipoCambio.')</option>';
					}
				
				echo'
				</select>
			</td>

			<td>
				&nbsp;<input type="checkbox" id="chkFacturar" name="chkFacturar"  />
			</td>

			<td>
				<input type="text" style="width:100px" class="cajas" id="txtDiasCredito" name="txtDiasCredito" value="0" />
			</td>
		</tr>
		
		<tr>
			<td>
				<select class="cajas" id="selectMostrador" name="selectMostrador" style="width:120px">
					<option value="0">Mostrador</option>
					<option value="1">Envío</option>
				</select>
			</td>
			
			<td>
				<input type="text" style="width:170px" class="cajas" id="txtFormaPago" name="txtFormaPago" value="Pago en una sola exhibición" />
			</td>
			
			<td>
				<input type="text" style="width:210px" class="cajas" id="txtCondicionesPago" name="txtCondicionesPago" value="30 días a partir de la fecha de entrega" />
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				
				<select style="width:300px" id="selectEmisores" name="selectEmisores" class="cajas" onchange="obtenerFolio()">
					<option value="0">Seleccione</option>';
				
				foreach($emisores as $row)
				{
					$seleccionado=$row->idEmisor==$cliente->idEmisor?'selected="selected"':'';
					echo '<option '.$seleccionado.' value="'.$row->idEmisor.'">(Serie '.$row->serie.') '.$row->rfc.', '.$row->nombre.'</option>';
				}
				
			echo'
			</td>
			<td id="obtenerFolio" colspan="2">
				Seleccionar emisor
			</td>
			
		</tr>
		
		<tr>
			<td colspan="2">
				<input type="text" style="width:120px" class="cajas" id="txtFechaVenta" name="txtFechaVenta" value="'.date('Y-m-d H:i').'" />
				<script>
					$("#txtFechaVenta").timepicker()
				</script>
			</td>
			<td colspan="2">
				<textarea id="txtObservacionesVenta" name="txtObservacionesVenta" class="TextArea" style="width:200px" ></textarea>
			</td>
		</tr>
		
	</table>
</div>

<div style="width:49%; float: right">
	
	<input type="hidden" id="txtIdLinea" name="txtIdLinea" value="0"/>
	
	<input type="text" class="cajas" id="txtBuscarProducto" onkeyup="obtenerProductosVenta()" style="width:300px" placeholder="Buscar productos"  />
	
	<div class="lineasPuntoVenta" >';
		foreach($lineas as $row)
		{
			echo '
			<div class="puntoVenta" onclick="definirLinea('.$row->idLinea.')">';
			
			if(file_exists(carpetaProductos.$row->imagen) and strlen($row->imagen)>4)
			{
				echo '<img src="'.base_url().carpetaProductos.$row->imagen.'"  align="center" />';
			}
			else
			{
				echo '<img src="'.base_url().carpetaProductos.'default.png" />';
			}
			
			echo'
				<section>'.$row->nombre.'</section>
			</div>';
		}
	echo'
	</div>
<div id="obtenerProductosVenta"></div>
</div>

<table class="admintable" width="100%" style="display:none" >
<tr>
	<td class="key">Subtotal:</td>
	<td><input readonly="readonly" style="width:150px; type="text" class="cajas" id="txtSubTotal" value="0.00" /></td>
</tr>
<tr>
	<td class="key">IVA :</td>
	<td align="left">
		<select id="txtIva" class="cajas" style="width:100px" onchange="calcularTotales()">
			<option>'.$ivas->iva.'</option>	
			<option>'.$ivas->iva2.'</option>
			<option>'.$ivas->iva3.'</option>
		</select>
		<!--input readonly="readonly" style="width:150px; type="text" class="cajas" id="txtIva"  value="'.$this->session->userdata('iva').'" /-->
	</td>
</tr>
<tr>
	<td class="key">Total:</td>
	<td>
		<input readonly="readonly" style="width:150px; type="text" class="cajas" id="txtTotal" value="0.00" />
	</td>
</tr>
<tr>
	<td class="key">Pago:</td>
	<td>
		<input style="width:150px; type="text" class="cajas" id="txtPago" value="0.00" onkeyup="calcularCambio()" />
	</td>
</tr>
<tr>
	<td class="key">Cambio:</td>
	<td>
		<input style="width:150px; type="text" readonly="readonly" class="cajas" id="txtCambio" value="0.00" />
	</td>
</tr>

<tr>
	<td class="key">Forma de cobro:</td>
	<td>
		<select id="selectFormas" name="selectFormas" class="cajas" style="width:150px;" onchange="opcionesFormasPago(); opcionesFormasPagoVentas()">';
			foreach($formas as $row)
			{
				if($row->idForma!=4)
				{
					$seleccionado=$cotizacion->idForma==$row->idForma?'selected="selected"':'';
					echo '<option '.$seleccionado.' value="'.$row->idForma.'">'.$row->nombre.'</option>';
				}
			}

		echo'
		</select>   
	 </td>
</tr>
<tr style="display:none;" id="mostrarCheques">
	<td class="key">Número cheque:</td>
	<td>
		<input type="text" class="cajas" style="width:150px" id="numeroCheque" name="numeroCheque" />   
	</td>
</tr>
<tr style="display:none;" id="mostrarTransferencia">
	<td class="key">Número Transferencia:</td>
	<td>
	<input type="text" class="cajas" style="width:150px" id="numeroTransferencia" name="numeroTransferencia" />    </td>
</tr>

<tr style="display:none;" id="filaNombre">
	<td class="key">Nombre del receptor:</td>
	<td>
		<input type="text" class="cajas" style="width:150px" id="txtNombreReceptor" name="txtNombreReceptor" />
	</td>
</tr>

<tr style="display:none;" id="filaBanco">
	<td class="key">Bancos:</td>
	<td id="obtenerBancos"> 
	 <select id="listaBancos" name="listaBancos" class="cajas" style="width:150px;" onchange="buscarCuentas()" >
		<option selected="selected" value="1">Efectivo</option>';

		echo'</select>
		</td>
	</tr>
	<tr style="display:none;" id="filaCuenta">
		<td class="key">Cuentas</td>
		<td id="cargarCuenta">
		<select id="cuentasBanco" name="cuentasBanco" class="cajas" style="width:150px;" >
			<option value="1">Efectivo</option>
		</select>
	</td>     
</tr>

</table>';
