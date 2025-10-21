<?php
echo'
<div class="ui-state-error" ></div>
<form id="frmIngresos" name="frmIngresos">
	<input type="hidden" class="cajas" id="txtTipoRegistroCatalogos" name="txtTipoRegistroCatalogos" value="1"/>
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input style="width:130px" type="text" class="cajas" id="txtFechaIngreso" name="txtFechaIngreso" value="'.substr($ingreso->fecha,0,16).'" />
				<script>
					$("#txtFechaIngreso").timepicker();
				</script>
			</td>
		</tr>';
	
		$cliente	= $ingreso->cliente;
		
		if(sistemaActivo=='IEXE')
		{
			$cliente	= strlen($ingreso->alumno)>0?$ingreso->alumno:$ingreso->cliente;
		}
		
		$readonly="";
		if($ingreso->idVenta>0 or $ingreso->idFactura>0)
		{
			$readonly=' readonly="readonly" ';
		}
		echo'
		<tr>
			<td class="key">'.(sistemaActivo=='IEXE'?'Cliente/Alumno':'Cliente').':</td>
			<td>
				<input '.$readonly.' value="'.$cliente.'" placeholder="Seleccione '.(sistemaActivo=='IEXE'?'cliente/alumno':'cliente').'" type="text" class="cajas" id="txtBuscarCliente" style="width:550px" /> 
				<input type="hidden" id="txtIdCliente" name="txtIdCliente" value="'.$ingreso->idCliente.'"/>   
				<img src="'.base_url().'img/agregar.png" width="20" title="Agregar cliente" height="20" onclick="formularioClientes()" style="display:none" />
				<script>
				$(document).ready(function()
				{
					$("#txtBuscarCliente").autocomplete(
					{
						source:"'.base_url().'configuracion/obtenerClientes/1/1/1",
						
						select:function( event, ui)
						{
							$("#txtIdCliente").val(ui.item.idCliente)
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
				});
				</script>
			</td>     
		</tr>
		<input type="hidden" id="txtIdProducto" name="txtIdProducto" value="'.$ingreso->idProductoCatalogo.'" />'; //Para validar el checkbox
		
		#$readonly="";
		if($ingreso->idProductoCatalogo>0)
		{
			#$readonly	='readonly="readonly"';
			$producto	=$this->productos->obtenerProducto($ingreso->idProductoCatalogo);
			echo'
			<tr style="display:none">
				<td class="key">Producto</td>
				<td>
					<input  value="'.$producto->nombre.'" type="text" readonly="readonly" class="cajas" id="txtNombreProducto" name="txtNombreProducto" style="width:550px" />
					
				</td>
			</tr>';
		}
		
		echo'
		<tr>
			<td class="key">Descripción del producto/servicio:</td>
			<td>
				<input '.$readonly.' type="text" placeholder="Seleccione" class="cajas" value="'.(strlen($ingreso->productoCatalogo)>1?$ingreso->productoCatalogo:$ingreso->producto).'" id="txtDescripcionProducto" name="txtDescripcionProducto" style="width:550px" />
				
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
			<td class="key">Cantidad</td>
			<td>
				<input type="text" '.$readonly.' class="cajas" id="txtCantidad" name="txtCantidad" value="'.round($ingreso->cantidad,decimales).'" style="width:100px" onkeypress="return soloDecimales(event)"/>
			</td>
		</tr>';
	
		
		
		echo'
		<tr>
			<td class="key">Subtotal</td>
			<td >
				<input type="hidden" id="txtIdIngreso" name="txtIdIngreso" value="'.$idIngreso.'" />
				<input type="text" class="cajas" id="txtImporte" name="txtImporte" onkeypress="return soloDecimales(event)" onchange="calcularIvaImporte()" value="'.round($ingreso->subTotal,decimales).'" '.$readonly.' />
			</td>
		</tr>
		<tr>
			<td class="key">Iva</td>
			<td>';
		
			if($ingreso->idVenta==0)
			{
				echo'
				<select class="cajas" id="selectIva" name="selectIva" onchange="calcularIvaImporte()">
					<option>16.00</option>
					<option '.(0==$ingreso->iva?'selected="selected"':'').' >0.00</option>';
						
					/*foreach($impuestos as $row)
					{
						echo '<option '.($row->tasa==$ingreso->iva?'selected="selected"':'').' value="'.$row->tasa.'">'.$row->nombre.' - '.number_format($row->tasa,2).'%</option>';
					}*/
					
					echo'
				</select>';
			}
			else
			{
				echo $ingreso->iva.'
				<input type="hidden" class="cajas" id="selectIva" name="selectIva" value="'.$ingreso->iva.'">';
			}
		
		echo'
			</td>
		</tr>
		<tr>
			<td class="key">Total</td>
			<td>
				<input type="hidden" id="txtTotalIva" name="txtTotalIva" value="'.$ingreso->ivaTotal.'" />
				<input type="text" class="cajas" id="txtTotal" name="txtTotal" onkeypress="return soloDecimales(event)" onchange="calcularIvaImporteTotal()" '.$readonly.' value="'.round($ingreso->pago,decimales).'"/>
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Cuentas contables:</td>
			<td>
				<input type="text" class="cajas" id="txtBuscarCuentaContable" name="txtBuscarCuentaContable" style="width:550px" placeholder="Seleccione"/>
				<input type="hidden" id="txtNumeroCuentas" name="txtNumeroCuentas" value="'.count($cuentasContables).'"/>
				
				<div id="listaCuentasContables" style="margin-top:4px">';
					
					$i=0;
					foreach($cuentasContables as $row)
					{
						echo '
						<div id="filaCuentaContable'.$i.'">
							<img src="'.base_url().'img/borrar.png" title="Borrar" onclick="borrarCuentaContable('.$i.')" width="18" />
							Referencia contable: '.$row->numeroCuenta.', Descripción: '.$row->descripcion.'
							<input type="hidden" value="'.$row->idCuentaCatalogo.'" id="txtIdCuentaCatalogo'.$i.'" name="txtIdCuentaCatalogo'.$i.'" />
						</div>';
						
						$i++;
					}
					
				echo'
				</div>
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">'.$ivas->variable1.':</td>
			<td>
				
				<select class="cajas" id="selectVariables1" name="selectVariables1" style="width:290px">
					<option value="0">Seleccione</option>';
					
					foreach($variables1 as $row)
					{
						echo '<option '.($row->idVariable==$ingreso->idVariable1?' selected="selected" ':'').' value="'.$row->idVariable.'">'.$row->nombre.'</option>';
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
						echo '<option '.($row->idVariable==$ingreso->idVariable2?' selected="selected" ':'').' value="'.$row->idVariable.'">'.$row->nombre.'</option>';
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
						echo '<option '.($row->idVariable==$ingreso->idVariable3?' selected="selected" ':'').' value="'.$row->idVariable.'">'.$row->nombre.'</option>';
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
						echo '<option '.($row->idVariable==$ingreso->idVariable4?' selected="selected" ':'').' value="'.$row->idVariable.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
				
			</td>
		</tr>';
		
		if(sistemaActivo=='IEXE')
		{
			echo '
			<tr>
				<td class="key">Periodo:</td>
				<td>
					<select class="cajas" id="selectPeriodosRegistro" name="selectPeriodosRegistro" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($periodos as $row)
						{
							echo '<option '.($row->idPeriodo==$ingreso->idPeriodo?' selected="selected" ':'').' value="'.$row->idPeriodo.'">'.$row->nombre.' ('.obtenerFechaMesCorto($row->fechaInicial).'-'.obtenerFechaMesCorto($row->fechaFinal).')</option>';
						}
						
					echo'
					</select>
				</td>
			</tr>';
		}
		
		echo'
	
		
		<tr>
			<td class="key">Concepto</td>
			<td>
				<div id="obtenerProductos" style="float:left; width:300px">
					<select class="cajas" id="selectProductos" name="selectProductos" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($productos as $row)
						{
							$activo=$row->idProducto==$ingreso->idProducto?' selected="selected" ':'';
							
							echo'<option '.$activo.' value="'.$row->idProducto.'">'.$row->nombre.'</option>';
						}
						
					echo'
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
			</td>
		</tr>
		
		<tr>
			<td class="key">Tipo de gasto</td>
			<td>
				<div id="obtenerTipoGasto" style="float:left; width:300px">
					<select class="cajas" id="selectTipoGasto" name="selectTipoGasto" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($gastos as $row)
						{
							$activo=$row->idGasto==$ingreso->idGasto?' selected="selected" ':'';
							
							echo'<option '.$activo.' value="'.$row->idGasto.'">'.$row->nombre.'</option>';
						}
						
					echo'
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
		
		<tr>
			<td class="key">Departamento</td>
			<td>
				<div id="obtenerDepartamentos" style="float:left; width:300px">
					<select class="cajas" id="selectDepartamento" name="selectDepartamento" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($departamentos as $row)
						{
							$activo=$row->idDepartamento==$ingreso->idDepartamento?' selected="selected" ':'';
							
							echo'<option '.$activo.' value="'.$row->idDepartamento.'">'.$row->nombre.'</option>';
						}
						
					echo'
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
					
					#$activo=$ingreso->idForma=="Efectivo"?' selected="selected" ':'';
					foreach($formas as $row)
					{
						$seleccionado	=$ingreso->idForma==$row->idForma?'selected="selected"':'';
						echo '<option '.$seleccionado.' value="'.$row->idForma.'">'.$row->nombre.'</option>';
					}
					
				echo'
				</select>   
			</td>
		</tr>';
		
		$mostrado	=$ingreso->idForma=='2'?'':' style="display:none" ';
		echo'
		<tr style="display:none" id="contenedorNombres">
			<td class="key">Paguese por este documento a:</td>
			<td>
				<div id="obtenerNombres" style="float:left; width:300px">
					<select class="cajas" id="selectNombres" name="selectNombres" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($nombres as $row)
						{
							$activo=$row->idNombre==$ingreso->idNombre?' selected="selected" ':'';
							
							echo'<option '.$activo.' value="'.$row->idNombre.'">'.$row->nombre.'</option>';
						}
						
					echo'
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
		</tr>';
		
		$activo=$ingreso->idForma=="2"?'  ':' style="display:none;" ';
		
		echo'
		<tr '.$activo.' id="mostrarCheques">
			<td class="key">Número cheque:</td>
			<td>
				<input type="text" value="'.$ingreso->cheque.'" class="cajas" id="txtNumeroCheque" name="txtNumeroCheque" />   
			</td>
		</tr>';
		
		$activo=$ingreso->idForma=="3"?' ':' style="display:none;" ';
		
		echo'
		<tr '.$activo.' id="mostrarTransferencia">
			<td class="key">Número Transferencia:</td>
			<td>
				<input type="text" value="'.$ingreso->transferencia.'" class="cajas" id="txtNumeroTransferencia" name="txtNumeroTransferencia" />
			</td>
		</tr>';
		
		$activo='style="display:none;" ';
		
		if($ingreso->idForma=="3" or $ingreso->idForma=="2")
		{
			$activo='';
		}
		
		echo'
		<tr style="display:none" id="filaNombre">
			<td class="key">Nombre del receptor:</td>
			<td>
				<input type="text" class="cajas" value="'.$ingreso->nombreReceptor.'" id="txtNombreReceptor" name="txtNombreReceptor" />
			</td>
		</tr>
		
		<tr>
		<td class="key">Banco:</td>
		<td> 
		 <select id="listaBancos" name="listaBancos" class="cajas" style="width:150px;" onchange="buscarCuentas()" >
			<option value="0">Seleccione</option>';
	
			   foreach($bancos as $row)
			   {
				   $activo=$row->idBanco==$banco?' selected="selected" ':'';
				   
				   echo'<option '.$activo.' value="'.$row->idBanco.'" >'.$row->nombre.'</option>';
			   }
			 
			echo'
			</select>
		</td>
	</tr>
	<tr>
		<td class="key">Cuenta:</td>
		<td id="cargarCuenta">
			<select id="cuentasBanco" name="cuentasBanco" class="cajas" style="width:150px;" >
			 <option value="0">Seleccione</option>';
			  
			  foreach($cuentas as $row)
			   {
				   $activo=$row->idCuenta==$ingreso->idCuenta?' selected="selected" ':'';
				   
				   echo'<option '.$activo.' value="'.$row->idCuenta.'" >'.$row->cuenta.'</option>';
			   }
			   
			echo'
			</select>
		</td>     
	</tr>
	
	<tr>
		<td class="key">Factura/Remisión:</td>
		<td>
			<select id="selectFacturaRemision" name="selectFacturaRemision" class="cajas" style="width:150px;" >
				<option value="0">Factura</option>
				<option '.($ingreso->remision=='1'?'selected="selected"':'').' value="1">Remisión</option>
			</select><br />
			
			<input type="text" class="cajas" id="txtFactura" name="txtFactura" style="width:200px" value="'.$ingreso->factura.'"  /> 
		</td>     
	</tr>
	
	<!--<tr>
		<td class="key">Factura/Remisión:</td>
		<td>
			<input type="text" class="cajas" id="txtFactura" name="txtFactura" style="width:300px" value="'.$ingreso->factura.'" /> 
		</td>     
	</tr>-->
	
	<tr>
		<td class="key">Comentarios:</td>
		<td>
			<textarea class="TextArea" id="txtComentarios" name="txtComentarios" style="height:35px; width:290px">'.$ingreso->comentarios.'</textarea>
		</td>     
	</tr>
	</table>
</form>';