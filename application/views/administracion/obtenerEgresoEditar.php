<?php
echo'
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
});
</script>

<form id="frmEgresos" name="frmEgresos">
	<input type="hidden" class="cajas" id="txtTipoRegistroCatalogos" name="txtTipoRegistroCatalogos" value="2"/>
	
	<div class="ui-state-error" ></div>
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input style="width:130px" type="text" class="cajas" id="txtFechaEgreso" name="txtFechaEgreso" value="'.substr($egreso->fecha,0,16).'" />
				<script>
					$("#txtFechaEgreso").timepicker();
				</script>
			</td>
		</tr>';
	
		$proveedor	=$proveedor!=null?$proveedor->empresa:'';
		$readonly	="";
		
		if($egreso->idCompra>0 and $egreso->idProveedor>0)
		{
			$readonly	='readonly="readonly"';
		}
		echo'
		<tr>
			<td class="key">Proveedor:</td>
			<td>
				<input '.$readonly.' value="'.$proveedor.'" placeholder="Seleccione proveedor" type="text" class="cajas" id="txtBuscarProveedor" name="txtBuscarProveedor" style="width:550px" /> 
				<input value="'.$egreso->idProveedor.'" type="hidden" id="txtIdProveedor" name="txtIdProveedor" value="0"/>   
				<img src="'.base_url().'img/agregar.png" width="20" title="Agregar proveedor" height="20" onclick="formularioProveedores()" style="display:none" />
			</td>     
		</tr>
		<input type="hidden" id="txtIdMaterial" value="'.$egreso->idMaterial.'" />';
	
		$readonly="";
		if($egreso->idMaterial>0)
		{
			$readonly	='readonly="readonly"';
			$material	=$this->materiales->obtenerMaterial($egreso->idMaterial);
			echo'
			<tr>
				<td class="key">Materia prima</td>
				<td>
					<input '.$readonly.' value="'.$material->nombre.'" type="text" readonly="readonly" class="cajas" id="txtMaterial" name="txtMaterial" style="width:550px" />
					
				</td>
			</tr>';
		}
		
		echo'
		
		<tr>
			<td class="key">Responsable:</td>
			<td>
				<input placeholder="Seleccione" type="text" class="cajas" id="txtBuscarPersonal" name="txtBuscarPersonal" style="width:550px" value="'.$egreso->personal.'"/> 
				<input type="hidden" id="txtIdPersonal" name="txtIdPersonal" value="'.$egreso->idPersonal.'"/>   
			</td>     
		</tr>
		
		<tr>
			<td class="key">Descripción del producto/servicio:</td>
			<td>
				<input type="text" class="cajas" value="'.(strlen($egreso->productoCatalogo)>1?$egreso->productoCatalogo:$egreso->producto).'" id="txtDescripcionProducto" name="txtDescripcionProducto" style="width:550px" />
				<input type="hidden" id="txtIdProducto" name="txtIdProducto" value="'.$egreso->idProductoCatalogo.'" />
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
				<input '.($egreso->idCompra>0?'readonly="readonly"':'').' type="text" class="cajas" id="txtCantidad" name="txtCantidad" value="'.round($egreso->cantidad,2).'" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Subtotal</td>
			<td >
				<input type="hidden" id="txtIdEgreso" name="txtIdEgreso" value="'.$egreso->idEgreso.'" />
				<input '.($egreso->idCompra>0?'readonly="readonly"':'').' type="text" class="cajas" id="txtImporte" name="txtImporte" onkeypress="return soloDecimales(event)" onchange="calcularIvaImporte()" value="'.round($egreso->subTotal,decimales).'"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Iva</td>
			<td>';
				
				if($egreso->idCompra==0)
				{
					echo'
					<select class="cajas" id="selectIva" name="selectIva" onchange="calcularIvaImporte()">
						<option>16.00</option>
						<option '.(0==$egreso->iva?'selected="selected"':'').' >0.00</option>';
						
						/*foreach($impuestos as $row)
						{
							echo '<option '.($row->tasa==$egreso->iva?'selected="selected"':'').' value="'.$row->tasa.'">'.$row->nombre.' - '.number_format($row->tasa,2).'%</option>';
						}*/
					
					echo'
					</select>';
				}
				else
				{
					echo $egreso->iva.'
					<input type="hidden" class="cajas" id="selectIva" name="selectIva" value="'.$egreso->iva.'">';
				}
				
			
			echo'
			</td>
		</tr>
		
		<tr>
			<td class="key">Total</td>
			<td>
				<input type="hidden" id="txtTotalIva" name="txtTotalIva" value="'.$egreso->ivaTotal.'" />
				<input type="text" class="cajas" id="txtTotal" name="txtTotal" onkeypress="return soloDecimales(event)" onchange="calcularIvaImporteTotal()" '.($egreso->idCompra>0?'readonly="readonly"':'').' value="'.round($egreso->pago,decimales).'" />
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
						echo '<option '.($row->idVariable==$egreso->idVariable1?' selected="selected" ':'').' value="'.$row->idVariable.'">'.$row->nombre.'</option>';
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
						echo '<option '.($row->idVariable==$egreso->idVariable2?' selected="selected" ':'').' value="'.$row->idVariable.'">'.$row->nombre.'</option>';
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
						echo '<option '.($row->idVariable==$egreso->idVariable3?' selected="selected" ':'').' value="'.$row->idVariable.'">'.$row->nombre.'</option>';
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
						echo '<option '.($row->idVariable==$egreso->idVariable4?' selected="selected" ':'').' value="'.$row->idVariable.'">'.$row->nombre.'</option>';
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
							echo '<option '.($row->idNivel1==$egreso->idNivel1?'selected="selected"':'').' value="'.$row->idNivel1.'">'.$row->nombre.'</option>';
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
						<option value="0">Seleccione</option>';
						
						if($nivel2!=null)
						{
							foreach($nivel2 as $row)
							{
								echo '<option '.($row->idNivel2==$egreso->idNivel2?'selected="selected"':'').' value="'.$row->idNivel2.'">'.$row->nombre.'</option>';
							}
						}
						
						
					echo'
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
						<option value="0">Seleccione</option>';
						
						if($nivel3!=null)
						{
							foreach($nivel3 as $row)
							{
								echo '<option '.($row->idNivel3==$egreso->idNivel3?'selected="selected"':'').' value="'.$row->idNivel3.'">'.$row->nombre.'</option>';
							}
						}
					
					echo'
					</select>
				</div>
				
				<img id="btnNivel2" src="'.base_url().'img/agregar.png" width="20" title="Agregar" height="20" onclick="listaNiveles3()" />
				
			</td>
		</tr>
	
		<tr style="display:none">
			<td class="key">Concepto</td>
			<td>
				<div id="obtenerProductos" style="float:left; width:300px">
					<select class="cajas" id="selectProductos" name="selectProductos" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($productos as $row)
						{
							$activo=$row->idProducto==$egreso->idProducto?' selected="selected" ':'';
							
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
		
		<tr style="display:none">
			<td class="key">Tipo:</td>
			<td>
				<div id="obtenerTipoGasto" style="float:left; width:300px">
					<select class="cajas" id="selectTipoGasto" name="selectTipoGasto" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($gastos as $row)
						{
							$activo=$row->idGasto==$egreso->idGasto?' selected="selected" ':'';
							
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
		<tr style="display:none">
			<td class="key">Departamento</td>
			<td>
				<div id="obtenerDepartamentos" style="float:left; width:300px">
					<select class="cajas" id="selectDepartamento" name="selectDepartamento" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($departamentos as $row)
						{
							$activo=$row->idDepartamento==$egreso->idDepartamento?' selected="selected" ':'';
							
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
					foreach($formas as $row)
					{
						$seleccionado	=$egreso->idForma==$row->idForma?'selected="selected"':'';
						echo '<option '.$seleccionado.' value="'.$row->idForma.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>   
			</td>
		</tr>';
		
		$mostrado	=$egreso->idForma=='2'?'':' style="display:none" ';
		echo'
		<tr style="display:none" id="contenedorNombres">
			<td class="key">Paguese por este documento a:</td>
			<td>
				<div id="obtenerNombres" style="float:left; width:300px">
					<select class="cajas" id="selectNombres" name="selectNombres" style="width:290px">
						<option value="0">Seleccione</option>';
						
						foreach($nombres as $row)
						{
							$activo=$row->idNombre==$egreso->idNombre?' selected="selected" ':'';
							
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
		</tr>
		';
		
		$activo=$egreso->idForma=="2"?'  ':' style="display:none;" ';
		
		echo'
		<tr '.$activo.' id="mostrarCheques">
			<td class="key">Número cheque:</td>
			<td>
				<input type="text" value="'.$egreso->cheque.'" class="cajas" id="txtNumeroCheque" name="txtNumeroCheque" />   
			</td>
		</tr>';
		
		$activo=$egreso->idForma=="3"?' ':' style="display:none;" ';
		
		echo'
		<tr '.$activo.' id="mostrarTransferencia">
			<td class="key">Número Transferencia:</td>
			<td>
				<input type="text" value="'.$egreso->transferencia.'" class="cajas" id="txtNumeroTransferencia" name="txtNumeroTransferencia" />
			</td>
		</tr>';
		
		$activo='style="display:none;" ';
		
		if($egreso->idForma=="3" or $egreso->idForma=="2")
		{
			$activo='';
		}
		
		
		echo'
		<tr style="display:none" id="filaNombre">
			<td class="key">Nombre del receptor:</td>
			<td>
				<input type="text" class="cajas" value="'.$egreso->nombreReceptor.'" id="txtNombreReceptor" name="txtNombreReceptor" />
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
				   $activo=$row->idCuenta==$egreso->idCuenta?' selected="selected" ':'';
				   
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
				<option '.($egreso->esRemision=='1'?'selected="selected"':'').' value="1">Remisión</option>
			</select><br />
			
			<input type="text" class="cajas" id="txtRemision" name="txtRemision" style="width:200px" value="'.$egreso->remision.'"/> 
		</td>     
	</tr>
	
	<!--<tr>
		<td class="key">Factura:</td>
		<td>
			<input  value="'.$egreso->factura.'" type="text" class="cajas" id="txtFactura" style="width:300px" /> 
		</td>     
	</tr>-->

	<tr>
		<td class="key">Comentarios:</td>
		<td>
			<textarea class="TextArea" id="txtComentarios" name="txtComentarios" style="height:35px; width:290px">'.$egreso->comentarios.'</textarea>
		</td>     
	</tr>
	</table>
</form>';