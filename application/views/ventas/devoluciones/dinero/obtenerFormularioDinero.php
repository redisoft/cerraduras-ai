<?php
echo '
<div class="ui-state-error" ></div>';
echo'
<form id="frmDinero">
	
	<input type="hidden" id="txtDineroAbierto" name="txtDineroAbierto" value="1"/>
	
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" class="cajas" id="txtFechaEgreso" name="txtFechaEgreso" style="width:130px" value="'.date('Y-m-d H:i').'" />
				<script>
					$("#txtFechaEgreso").timepicker();
				</script>
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Proveedor:</td>
			<td>
				<input placeholder="Seleccione proveedor" type="text" class="cajas" id="txtBuscarProveedor" style="width:550px" /> 
				<input type="hidden" id="txtIdProveedor" name="txtIdProveedor" value="0"/>  
				<script>
				$(document).ready(function()
				{
					$("#txtBuscarProveedor").autocomplete(
					{
						source:"'.base_url().'configuracion/obtenerProveedores",
						
						select:function( event, ui)
						{
							$("#txtIdProveedor").val(ui.item.idProveedor)
						}
					});
				});
				</script> 
			</td>     
		</tr>
		
		<tr>
			<td class="key">Cliente:</td>
			<td>'.$cotizacion->cliente.'</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Materia prima:</td>
			<td>
				<input type="hidden" id="txtIdMaterial" name="txtIdMaterial" value="0"/>
			</td>
		</tr>
		<tr>
			<td class="key">Descripción del producto:</td>
			<td>
				<input type="text" class="cajas" id="txtDescripcionProducto" name="txtDescripcionProducto" style="width:250px" value="Devolución" />
				
			</td>
		</tr>
		
		<tr>
			<td class="key">Orden de venta:</td>
			<td>'.$cotizacion->ordenCompra.'</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Cantidad:</td>
			<td>
				<input type="text" class="cajas" id="txtCantidad" name="txtCantidad" value="1"  style="width:100px" onkeypress="return soloDecimales(event)"/>
				
				<input type="hidden" id="txtSubTotalDinero" 	name="txtSubTotalDinero" 		value="0"/>
				<input type="hidden" id="txtIvaDinero" 			name="txtIvaDinero" 			value="0"/>
				<input type="hidden" id="txtImporteDinero" 		name="txtImporteDinero" 		value="0"/>
				
				<input type="hidden" value="'.($cotizacion->ivaPorcentaje>0?$cotizacion->ivaPorcentaje/100:0).'" id="txtIvaPorcentajeDinero" name="txtIvaPorcentajeDinero" />
				<input type="hidden" value="'.$cotizacion->descuentoPorcentaje.'" id="txtDescuentoPorcentajeDinero" name="txtDescuentoPorcentajeDinero" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Subtotal:</td>
			<td id="lblSubTotalDinero">$0.00</td>
		</tr>
		
		<tr>
			<td class="key">Descuento:</td>
			<td id="lblDescuentoDinero">$0.00</td>
		</tr>
		
		<tr>
			<td class="key">IVA:</td>
			<td id="lblIvaDinero">$0.00</td>
		</tr>
		
		<tr>
			<td class="key">Total:</td>
			<td id="lblTotalDinero">$0.00</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Iva:</td>
			<td>
				&nbsp;&nbsp;
				<input type="checkbox" id="chkIva" name="chkIva" onchange="checarIvaMateriaPrima()" value="1" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Concepto:</td>
			<td>
				<div id="obtenerProductos" style="float:left; width:300px">
					<select class="cajas" id="txtConcepto" name="txtConcepto" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($productos as $row)
						{
							echo '<option value="'.$row->idProducto.'">'.$row->nombre.'</option>';
						}
						
					echo'
					</select>
				</div>

				<input style="display:none" type="checkbox" id="chkCajaChica" name="chkCajaChica" onchange="checarComprobarCaja()" value="1" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Tipo:</td>
			<td>
				<div id="obtenerTipoGasto" style="float:left; width:300px">
					<select class="cajas" id="selectTipoGasto" name="selectTipoGasto" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($gastos as $row)
						{
							echo '<option value="'.$row->idGasto.'">'.$row->nombre.'</option>';
						}
					echo'
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<td class="key">Departamento:</td>
			<td>
				<div id="obtenerDepartamentos" style="float:left; width:300px">
					<select class="cajas" id="selectDepartamento" name="selectDepartamento" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($departamentos as $row)
						{
							echo '<option value="'.$row->idDepartamento.'">'.$row->nombre.'</option>';
						}
					echo'
					</select>
				</div>
			</td>
		</tr>
	
		<tr>
			<td class="key">Forma de pago:</td>
			<td>
				<select id="selectFormas" name="selectFormas" class="cajas" style="width:150px;" onchange="opcionesFormasPago()">';
				
					foreach($formas as $row)
					{
						if($row->idForma!=4)
						{
							echo '<option value="'.$row->idForma.'">'.$row->nombre.'</option>';
						}
					}

				echo'
				</select>   
			</td>
		</tr>
		
		<tr id="contenedorNombres" style="display:none">
			<td class="key">Paguese por este documento a:</td>
			<td>
				<div id="obtenerNombres" style="float:left; width:300px">
	
					<select class="cajas" id="selectNombres" name="selectNombres" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($nombres as $row)
						{
							echo '<option value="'.$row->idNombre.'">'.$row->nombre.'</option>';
						}
						
					echo'
					</select>
				</div>

			</td>
		</tr>
		<tr style="display:none" id="filaPeriodicidad">
			<td class="key">Periodicidad:</td>
			<td>
				<select id="selectPeriodos" name="selectPeriodos" class="cajas" style="width:100px">';
				
				foreach($periodos as $row)
				{
					echo '<option value="'.$row->idPeriodo.'">'.$row->nombre.'</option>';
				}
					
				echo'
				</select>   
				Repetir <input class="cajas" type="text" id="txtRepetir" name="txtRepetir" value="1" name="txtRepetir" style="width:100px" />
			</td>
		</tr>
		<tr style="display:none;" id="mostrarCheques">
			<td class="key">Número cheque:</td>
			<td>
				<input type="text" class="cajas" id="txtNumeroCheque" name="txtNumeroCheque" />   
			</td>
		</tr>
		
		<tr style="display:none;" id="mostrarTransferencia">
			<td class="key">Número Transferencia:</td>
			<td>
				<input type="text" class="cajas" id="txtNumeroTransferencia" name="txtNumeroTransferencia" />
			</td>
		</tr>
		
		<tr style="display:none;" id="filaNombre">
			<td class="key">Nombre del receptor:</td>
			<td>
				<input type="text" class="cajas" id="txtNombreReceptor" name="txtNombreReceptor" />
			</td>
		</tr>
		
		<tr>
		<td class="key">Banco:</td>
		<td> 
		 <select id="listaBancos" name="listaBancos" class="cajas" style="width:150px;" onchange="buscarCuentas()" >
			<option value="0">Seleccione</option>';
	
			   foreach($bancos as $row)
			   {
				   echo '<option value="'.$row->idBanco.'" >'.$row->nombre.'</option>';
			   }
			 
			echo'
			</select>
		</td>
	</tr>
	<tr>
		<td class="key">Cuenta:</td>
		<td id="cargarCuenta">
			<select id="cuentasBanco" name="cuentasBanco" class="cajas" style="width:150px;" >
				<option value="0">Seleccione</option>
			</select>
		</td>     
	</tr>
	
	<tr>
		<td class="key">Factura/Remisión:</td>
		<td>
			<select id="selectFacturaRemision" name="selectFacturaRemision" class="cajas" style="width:150px;" >
				<option value="0">Factura</option>
				<option value="1">Remisión</option>
			</select><br />
			
			<input type="text" class="cajas" id="txtRemision" name="txtRemision" style="width:200px" /> 
		</td>     
	</tr>

	<tr>
		<td class="key">Comentarios:</td>
		<td>
			<textarea class="TextArea" id="txtComentarios" name="txtComentarios" style="height:35px; width:290px"></textarea>
		</td>     
	</tr>
	<tr style="display:none">
		<td class="key">Comprobante:</td>
		<td>
			<input type="file" id="archivoEgreso" name="archivoEgreso" />
		</td>     
	</tr>
	</table>
</form>';