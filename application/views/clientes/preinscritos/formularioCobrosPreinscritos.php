<?php
echo '
<script>
$(document).ready(function()
{
	$("#txtBuscarCliente").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerClientes/1/1/1",
		
		select:function( event, ui)
		{
			$("#txtIdCliente").val(ui.item.idCliente)
			
			/*$("#selectProductos").val(ui.item.idProducto)
			$("#selectTipoGasto").val(ui.item.idGasto)
			$("#selectDepartamento").val(ui.item.idDepartamento)
			$("#selectNombres").val(ui.item.idNombre)
			$("#selectFormas").val(ui.item.idForma)
			
			opcionesFormasPago()*/
		}
	});
	
	$("#txtBuscarCuentaContable").autocomplete(
	{
		source:"'.base_url().'cuentas/obtenerCuentasContables",
		
		select:function( event, ui)
		{
			cargarCuentaContable(ui.item)
		}
	});
	
	$("#txtBuscarVenta").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerListaVentas",
		
		select:function( event, ui)
		{
			cargarVentaIngreso(ui.item)
			
			
		}
	});
});
</script>

<div class="ui-state-error" ></div>';
echo'
<form id="frmIngresos" name="frmIngresos">
	<input type="hidden" class="cajas" id="txtTipoRegistroCatalogos" name="txtTipoRegistroCatalogos" value="1"/>
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" class="cajas" id="txtFechaIngreso" name="txtFechaIngreso" style="width:130px" value="'.date('Y-m-d H:i').'" readonly="readonly" />
				<script>
					$("#txtFechaIngreso").timepicker();
				</script>
			</td>
		</tr>
		
		<tr '.(sistemaActivo=='IEXE'?'style="display:none"':'').'>
			<td class="key">Venta:</td>
			<td>
				<input type="text" class="cajas" id="txtBuscarVenta" name="txtBuscarVenta" style="width:300px" placeholder="Seleccione" />
				<input type="hidden" class="cajas" id="txtIdVenta" name="txtIdVenta" value="0"/>
				
				<input type="hidden" class="cajas" id="txtSaldoVenta" name="txtSaldoVenta" value="0"/>
				<input type="hidden" class="cajas" id="txtTasaImpuestoVenta" name="txtTasaImpuestoVenta" value="0"/>
				
				
				
				<label id="lblSaldoVenta"></label>
				
			</td>
		</tr>
		
		<tr>
			<td class="key">'.(sistemaActivo=='IEXE'?'Cliente/Alumno':'Cliente').':</td>
			<td>
				<input placeholder="Seleccione '.(sistemaActivo=='IEXE'?'cliente/alumno':'cliente').'" type="text" class="cajas" id="txtBuscarCliente" style="width:550px" value="'.(isset($cliente->nombre)?$cliente->nombre.' '.$cliente->paterno.' '.$cliente->materno:'').'" /> 
				<input type="hidden" id="txtIdCliente" name="txtIdCliente" value="'.(isset($cliente->idCliente)?$cliente->idCliente:'').'"/>   

				
			</td>     
		</tr>
		<tr style="display:none">
			<td class="key">Producto</td>
			<td>
				<!--<input type="text" class="cajas" id="txtNombreProducto" name="txtNombreProducto" style="width:550px" />-->
				<input type="hidden" id="txtIdProducto" name="txtIdProducto"  	value="0" />
				<input type="hidden" id="txtIdPeriodo" 	name="txtIdPeriodo"  	value="0" />
				<input type="hidden" id="txtServicio" 	name="txtServicio" 		value="0" />
				<script>
				$(document).ready(function()
				{
					$("#txtDescripcionProducto").autocomplete(
					{
						source:"'.base_url().'configuracion/obtenerProductosServicios/0",
						
						select:function( event, ui)
						{
							$("#txtIdProducto").val(ui.item.idProducto)
							$("#txtDescripcionProducto").val(ui.item.nombre)
							//$("#txtIdPeriodo").val(ui.item.idPeriodo)
							//$("#txtServicio").val(ui.item.servicio)
							//document.getElementById("chkIva").checked=true;
							//sugerirPrecios(ui.item.precioA,ui.item.precioB,ui.item.precioC,ui.item.precioD,ui.item.precioE);
						}
					});
				});
				</script>
			</td>
		</tr>
		<tr>
			<td class="key">Descripción del producto/servicio:</td>
			<td>
				<input type="text" class="cajas" id="txtDescripcionProducto" name="txtDescripcionProducto" style="width:550px" placeholder="Seleccione" />
			</td>
		</tr>
		<tr>
			<td class="key">Cantidad:</td>
			<td>
				<input type="text" class="cajas" id="txtCantidad" name="txtCantidad"  value="1" style="width:100px" onkeypress="return soloDecimales(event)" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Subtotal:</td>
			<td >
				<input type="text" class="cajas" id="txtImporte" name="txtImporte" onkeypress="return soloDecimales(event)" onchange="calcularIvaImporte()"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Iva:</td>
			<td>
				<select class="cajas" id="selectIva" name="selectIva" onchange="calcularIvaImporte()">
					<option>16.00</option>
					<option>0.00</option>';
				
					/*foreach($impuestos as $row)
					{
						echo '<option value="'.$row->tasa.'">'.$row->nombre.' - '.number_format($row->tasa,2).'%</option>';
					}*/
						
					echo'
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="key">Total:</td>
			<td>
				<input type="hidden" id="txtTotalIva" name="txtTotalIva" value="0" />
				<input type="text" class="cajas" id="txtTotal" name="txtTotal" onkeypress="return soloDecimales(event)" onchange="calcularIvaImporteTotal()"/>
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Cuentas contables:</td>
			<td>
				<input type="text" class="cajas" id="txtBuscarCuentaContable" name="txtBuscarCuentaContable" style="width:550px" placeholder="Seleccione"/>
				<input type="hidden" id="txtNumeroCuentas" name="txtNumeroCuentas" value="0"/>
				
				<div id="listaCuentasContables" style="margin-top:4px"></div>
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">'.$ivas->variable1.':</td>
			<td>
				
				<select class="cajas" id="selectVariables1" name="selectVariables1" style="width:290px">
					<option value="0">Seleccione</option>';
					
					foreach($variables1 as $row)
					{
						echo '<option value="'.$row->idVariable.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
				
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">'.$ivas->variable2.':</td>
			<td>
				
				<select class="cajas" id="selectVariables2" name="selectVariables2" style="width:290px">
					<option value="0">Seleccione</option>';
					
					foreach($variables2 as $row)
					{
						echo '<option value="'.$row->idVariable.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
				
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">'.$ivas->variable3.':</td>
			<td>
				
				<select class="cajas" id="selectVariables3" name="selectVariables3" style="width:290px">
					<option value="0">Seleccione</option>';
					
					foreach($variables3 as $row)
					{
						echo '<option value="'.$row->idVariable.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
				
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">'.$ivas->variable4.':</td>
			<td>
				
				<select class="cajas" id="selectVariables4" name="selectVariables4" style="width:290px">
					<option value="0">Seleccione</option>';
					
					foreach($variables4 as $row)
					{
						echo '<option value="'.$row->idVariable.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
				
			</td>
		</tr>
		
		<tr>
			<td class="key">Concepto:</td>
			<td>
				<div id="obtenerProductos" style="float:left; width:300px">
					<select class="cajas" id="txtConcepto" name="txtConcepto" style="width:290px">
						<option value="0">Seleccione</option>
					</select>
				</div>
				

				<script>
				$("#btnProductos").click(function(e)
				{
					formularioProductos();
					$("#ventanaFormularioProductos").dialog("open");
				});
				</script>
			</td>
		</tr>
		<tr>
			<td class="key">Tipo:</td>
			<td>
				<div id="obtenerTipoGasto" style="float:left; width:300px">
					<select class="cajas" id="selectTipoGasto" name="selectTipoGasto" style="width:290px">
						<option value="0">Seleccione</option>
					</select>
				</div>

				<script>
				$("#btnTipoGasto").click(function(e)
				{
					formularioTipoGastos();
					$("#ventanaFormularioGastos").dialog("open");
				});
				</script>
			</td>
		</tr>
		<tr>
			<td class="key">Departamento:</td>
			<td>
				<div id="obtenerDepartamentos" style="float:left; width:300px">
					<select class="cajas" id="selectDepartamento" name="selectDepartamento" style="width:290px">
						<option value="0">Seleccione</option>
					</select>
				</div>
				
				
				<script>
				$("#btnDepartamentos").click(function(e)
				{
					formularioDepartamentos();
					$("#ventanaFormularioDepartamentos").dialog("open");
				});
				</script>
			</td>
		</tr>
		<tr>
			<td class="key">Forma de pago:</td>
			<td>
				<select id="selectFormas" name="selectFormas" class="cajas" style="width:150px;" onchange="opcionesFormasPago()">';
				
					foreach($formas as $row)
					{
						#$seleccionado=$cotizacion->idForma==$row->idForma?'selected="selected"':'';
						echo '<option value="'.$row->idForma.'">'.$row->nombre.'</option>';
					}
					/*<option value="Efectivo">Efectivo</option>
					<option value="Cheque">Cheque</option>
					<option value="Transferencia">Transferencia</option>
					<option value="Programado">Programado</option>
					<option>Tarjeta de crédito</option>
					<option>Tarjeta débito</option>*/
				echo'
				</select>   
			</td>
		</tr>
		
		<tr id="contenedorNombres" style="display:none">
			<td class="key">Paguese por este documento a:</td>
			<td>
				<div id="obtenerNombres" style="float:left; width:300px">
					<select class="cajas" id="selectNombres" name="selectNombres" style="width:290px">
						<option value="0">Seleccione</option>
					</select>
				</div>

				<script>
				$("#btnNombres").click(function(e)
				{
					formularioNombres();
					$("#ventanaFormularioNombres").dialog("open");
				});
				</script>
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
				Repetir <input class="cajas" type="text" id="txtRepetir" value="1" name="txtRepetir" style="width:100px" />
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
			 <select id="listaBancos" name="listaBancos" class="cajas" style="width:150px" onchange="buscarCuentas()" >
				<option value="0">Seleccione</option>';
		
				   foreach($bancos as $row)
				   {
					   print('<option value="'.$row->idBanco.'" >'.$row->nombre.'</option>');
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
		<!--<tr>
			<td class="key">Factura/Remisión:</td>
			<td>
				<input type="text" class="cajas" id="txtFactura" style="width:300px" /> 
			</td>     
		</tr>-->
		
		<tr>
			<td class="key">Factura/Remisión:</td>
			<td>
				<select id="selectFacturaRemision" name="selectFacturaRemision" class="cajas" style="width:150px;" >
					<option value="0">Factura</option>
					<option value="1">Remisión</option>
				</select><br />
				
				<input type="text" class="cajas" id="txtFactura" name="txtFactura" style="width:200px" /> 
			</td>     
		</tr>


		<tr>
			<td class="key">Comentarios:</td>
			<td>
				<textarea class="TextArea" id="txtComentarios" name="txtComentarios" style="height:35px; width:290px"></textarea>
			</td>     
		</tr>
		<tr>
			<td class="key">Comprobante:</td>
			<td>
				<input type="file" id="archivoIngreso" name="archivoIngreso" /><br />
				
				<span style="display:none"><a>XML, PDF contabilidad <input type="checkbox" id="chkXml" value="1" name="chkXml" title="XML, PDF contabilidad" /> </a></span>
			</td>     
		</tr>
	</table>
</form>';