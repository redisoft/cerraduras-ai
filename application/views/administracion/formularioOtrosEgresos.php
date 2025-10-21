<?php
echo '
<script>
$(document).ready(function()
{
	$("#txtBuscarProveedor").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerProveedores/0/0/0/0/1",
		
		select:function( event, ui)
		{
			$("#txtIdProveedor").val(ui.item.idProveedor)
			
			/*$("#selectProductos").val(ui.item.idProducto>0?ui.item.idProducto:0)
			$("#selectTipoGasto").val(ui.item.idGasto>0?ui.item.idGasto:0)
			$("#selectDepartamento").val(ui.item.idDepartamento>0?ui.item.idDepartamento:0)
			$("#selectNombres").val(ui.item.idNombre>0?ui.item.idNombre:0)
			$("#selectFormas").val(ui.item.idForma>0?ui.item.idForma:0)
			
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
	
	$("#txtBuscarPersonal").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerPersonal",
		
		select:function( event, ui)
		{
			$("#txtIdPersonal").val(ui.item.idPersonal)
		}
	});
	
	$("#txtBuscarCompra").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerOrdenesCompra/1",
		
		select:function( event, ui)
		{
			cargarCompraEgreso(ui.item)
		}
	});
});
</script>

<div class="ui-state-error" ></div>
<form id="frmEgresos" name="frmEgresos">
	<input type="hidden" class="cajas" id="txtTipoRegistroCatalogos" name="txtTipoRegistroCatalogos" value="2"/>
	
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
		
		<tr>
			<td class="key">Compra:</td>
			<td>
				<input type="text" class="cajas" id="txtBuscarCompra" name="txtBuscarCompra" style="width:300px" placeholder="Seleccione" />
				<input type="hidden" class="cajas" id="txtIdCompra" name="txtIdCompra" value="0"/>
				
				<input type="hidden" class="cajas" id="txtSaldoCompra" name="txtSaldoCompra" value="0"/>
				<input type="hidden" class="cajas" id="txtTasaImpuestoCompra" name="txtTasaImpuestoCompra" value="0"/>
				
				
				
				<label id="lblSaldoCompra"></label>
				
			</td>
		</tr>
		
		<tr>
			<td class="key">Proveedor:</td>
			<td>
				<input placeholder="Seleccione proveedor" type="text" class="cajas" id="txtBuscarProveedor" style="width:550px" /> 
				<input type="hidden" id="txtIdProveedor" name="txtIdProveedor" value="0"/>   
				<img src="'.base_url().'img/agregar.png" width="20" title="Agregar proveedor" height="20" onclick="formularioProveedores()" style="display:none" />
			</td>     
		</tr>
		
		<tr style="display:none">
			<td class="key">Materia prima:</td>
			<td>
				<input type="text" class="cajas" id="txtMaterial" name="txtMaterial" style="width:550px" />
				<input type="hidden" id="txtIdMaterial" name="txtIdMaterial" value="0"/>
				<script>
				$(document).ready(function()
				{
					$("#txtMaterial").autocomplete(
					{
						source:"'.base_url().'configuracion/obtenerMateriales",
						
						select:function( event, ui)
						{
							$("#txtIdMaterial").val(ui.item.idMaterial);
							$("#txtDescripcionProducto").val(ui.item.nombre);
							sugerirPrecioMaterial(ui.item.costo);
							
							$("#txtBuscarProveedor").val(ui.item.empresa);
							$("#txtIdProveedor").val(ui.item.idProveedor);
							document.getElementById("txtBuscarProveedor").readOnly=true;
							
							document.getElementById("chkIva").checked=true;
							document.getElementById("chkCajaChica").checked=false;
						}
					});
				});
				</script>
			</td>
		</tr>
		
		
		<tr>
			<td class="key">Responsable:</td>
			<td>
				<input placeholder="Seleccione" type="text" class="cajas" id="txtBuscarPersonal" name="txtBuscarPersonal" style="width:550px" /> 
				<input type="hidden" id="txtIdPersonal" name="txtIdPersonal" value="0"/>   
			</td>     
		</tr>
		
		<tr>
			<td class="key">Descripción del producto / servicio:</td>
			<td>
				<input type="text" class="cajas" id="txtDescripcionProducto" name="txtDescripcionProducto" style="width:250px" />
				
				<input type="hidden" id="txtIdProducto" name="txtIdProducto"  	value="0" />
				<script>
				$(document).ready(function()
				{
					$("#txtDescripcionProducto").autocomplete(
					{
						source:"'.base_url().'configuracion/obtenerProductosServicios/0",
						
						select:function( event, ui)
						{
							$("#txtIdProducto").val(ui.item.idProducto)
						}
					});
				});
				</script>
				
			</td>
		</tr>
		
		<tr>
			<td class="key">Cantidad:</td>
			<td>
				<input type="text" class="cajas" id="txtCantidad" name="txtCantidad" value="1"  style="width:100px" onkeypress="return soloDecimales(event)"/>
			</td>
		</tr>
		
		
		<tr>
			<td class="key">Subtotal</td>
			<td >
				<input type="text" class="cajas" id="txtImporte" name="txtImporte" onkeypress="return soloDecimales(event)" onchange="calcularIvaImporte()"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Impuesto</td>
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
			<td class="key">Total</td>
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
			<td class="key">Nivel 1:</td>
			<td>
				<div id="obtenerNiveles1Catalogo" style="float:left; width:300px">
					<select class="cajas" id="selectNivel1" name="selectNivel1" style="width:290px" onchange="obtenerNiveles2Catalogo()">
						<option value="0">Seleccione</option>';
						
						foreach($nivel1 as $row)
						{
							echo '<option value="'.$row->idNivel1.'">'.$row->nombre.'</option>';
						}
					echo'
					</select>
				</div>
				
				<img id="btnNivel1" src="'.base_url().'img/agregar.png" width="20" title="Agregar" height="20" onclick="listaNiveles1()" />
				
			</td>
		</tr>
		
		<tr>
			<td class="key">Nivel 2:</td>
			<td>
				<div id="obtenerNiveles2Catalogo" style="float:left; width:300px">
					<select class="cajas" id="selectNivel2" name="selectNivel2" style="width:290px">
						<option value="0">Seleccione</option>
					</select>
				</div>
				
				<img id="btnNivel2" src="'.base_url().'img/agregar.png" width="20" title="Agregar" height="20" onclick="listaNiveles2()" />
				
			</td>
		</tr>
		
		<tr>
			<td class="key">Nivel 3:</td>
			<td>
				<div id="obtenerNiveles3Catalogo" style="float:left; width:300px">
					<select class="cajas" id="selectNivel3" name="selectNivel3" style="width:290px">
						<option value="0">Seleccione</option>
					</select>
				</div>
				
				<img id="btnNivel2" src="'.base_url().'img/agregar.png" width="20" title="Agregar" height="20" onclick="listaNiveles3()" />
				
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Concepto:</td>
			<td>
				<div id="obtenerProductos" style="float:left; width:300px">
					<select class="cajas" id="selectProductos" name="selectProductos" style="width:290px">
						<option value="0">Seleccione</option>
					</select>
				</div>
				
				<img id="btnProductos" src="'.base_url().'img/agregar.png" width="20" title="Agregar producto" height="20" />
				<script>
				$("#btnProductos").click(function(e)
				{
					formularioProductos();
					$("#ventanaFormularioProductos").dialog("open");
				});
				</script>
	
				<br />
				<br />
	
				<input type="checkbox" id="chkCajaChica" name="chkCajaChica" onchange="checarComprobarCaja()" value="1" /> <label>¿Es caja chica?</label>
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Tipo:</td>
			<td>
				<div id="obtenerTipoGasto" style="float:left; width:300px">
					<select class="cajas" id="selectTipoGasto" name="selectTipoGasto" style="width:290px">
						<option value="0">Seleccione</option>
					</select>
				</div>
				<img id="btnTipoGasto" src="'.base_url().'img/agregar.png" width="20" title="Agregar Tipo de gasto" height="20" />
				<script>
				$("#btnTipoGasto").click(function(e)
				{
					formularioTipoGastos();
					$("#ventanaFormularioGastos").dialog("open");
				});
				</script>
			</td>
		</tr>
		<tr style="display:none">
			<td class="key">Departamento:</td>
			<td>
				<div id="obtenerDepartamentos" style="float:left; width:300px">
					<select class="cajas" id="selectDepartamento" name="selectDepartamento" style="width:290px">
						<option value="0">Seleccione</option>
					</select>
				</div>
				<img id="btnDepartamentos" src="'.base_url().'img/agregar.png" width="20" title="Agregar departamento" height="20" />
				
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
						echo '<option value="'.$row->idForma.'">'.$row->nombre.'</option>';
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
						<option value="0">Seleccione</option>
					</select>
				</div>
				<img id="btnNombres" src="'.base_url().'img/agregar.png" width="20" title="Agregar nombre" height="20" />
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
				Repetir <input class="cajas" type="text" id="txtRepetir" name="txtRepetir" value="1" name="txtRepetir" style="width:100px" onkeypress="return soloNumerico(event)"/>
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
	
	<!--<tr>
		<td class="key">Remisión:</td>
		<td>
			<input type="text" class="cajas" id="txtRemision" name="txtRemision" style="width:300px" /> 
		</td>     
	</tr>-->
	
		<tr>
			<td class="key">Comentarios:</td>
			<td>
				<textarea class="TextArea" id="txtComentarios" name="txtComentarios" style="height:35px; width:290px"></textarea>
			</td>     
		</tr>
		<tr>
			<td class="key">Comprobante:</td>
			<td>
				<input type="file" id="archivoEgreso" name="archivoEgreso" />
				<br />
				<a>XML, PDF contabilidad <input type="checkbox" id="chkXml" value="1" name="chkXml" title="XML, PDF contabilidad" /> </a>
			</td>     
		</tr>
	</table>
</form>';