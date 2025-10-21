<script>
opcionesFormasPago()
</script>
<?php
echo'
<div class="ui-state-error" ></div>
<form id="frmCobroCliente" name="frmCobroCliente">
	<table class="admintable" width="100%;" >
		<tr>
			<th colspan="2" class="encabezadoPrincipal">Detalles de venta</th>
		</tr>
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" class="cajas" id="txtFechaIngreso" name="txtFechaIngreso" value="'.date('Y-m-d H:i').'" style="width:120px" />
				<script>
					$("#txtFechaIngreso").timepicker();
				</script>
			</td>
		</tr>
		<tr>
			<td class="key">Orden de venta: </td>
			<td>'.$cotizacion->ordenCompra.'</td>
		</tr>
		
		<tr>
			<td class="key">Subtotal: </td>
			<td>$ '.number_format($cotizacion->subTotal,2).'</td>
		</tr>
		
		<tr>
			<td class="key">Descuento: </td>
			<td>$ '.number_format($cotizacion->descuento,2).'</td>
		</tr>
		
		<tr>
			<td class="key">IVA: </td>
			<td>$ '.number_format($cotizacion->iva,2).'</td>
		</tr>
		
		<tr>
			<td class="key">Total: </td>
			<td>
				$ '.number_format($cotizacion->total,2).'
				
				<input type="hidden" id="txtIvaPorcentaje" name="txtIvaPorcentaje" value="'.$cotizacion->ivaPorcentaje.'" />
			</td>
		</tr>
		<tr>
			<td class="key">Monto cobrado: </td>
			<td>$ '.number_format($total->pago,2).'</td>
		</tr>
		<tr>
			<td class="key">Deuda: </td>
			<td>$ '.number_format($cotizacion->total-$total->pago,2).'</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Concepto</td>
			<td>
				<div id="obtenerProductos" style="float:left; width:300px">
					<select class="cajas" id="selectProductos" style="width:290px">
						<option value="0">Seleccione</option>
					</select>
				</div>
				
				<!--img id="btnProductos" src="'.base_url().'img/agregar.png" width="20" title="Agregar concepto" height="20" /-->
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
			<td class="key">Monto a cobrar: </td>
			<td>
				<input type="text" class="cajas" id="montoPagar" name="montoPagar" style="width:80px" value="'.($cotizacion->total-$total->pago).'" />
				<input type="hidden" value="'.($cotizacion->total-$total->pago).'" class="cajas" id="T3" style="width:80px" />
				<input type="hidden" value="'.$cotizacion->total.'" class="cajas" id="idVenta" style="width:80px" />
				<input type="hidden" value="'.$idCotizacion.'"  id="txtIdVenta" name="txtIdVenta"/>
				<input type="hidden" value="'.$cotizacion->idCliente.'" id="txtIdClienteCobro" name="txtIdClienteCobro"/>
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Iva</td>
			<td>
				&nbsp;&nbsp;
				<input type="checkbox" id="chkIva" '.($cotizacion->ivaPorcentaje>0?'checked="checked"':'').' />
				<input readonly="readonly" type="hidden" style="width:100px" class="cajas" value="'.$cotizacion->ivaPorcentaje.'" id="txtIva" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Descripción del producto:</td>
			<td>
				<input value="'.$cotizacion->ordenCompra.'" type="text" class="cajas" id="txtDescripcionProducto" name="txtDescripcionProducto" style="width:250px" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Departamento</td>
			<td>
				<div id="obtenerDepartamentos" style="float:left; width:300px">
					<select class="cajas" id="selectDepartamento" name="selectDepartamento" style="width:290px">
						<option value="0">Seleccione</option>';
						
						$idDepartamento	= $pago!=null?$pago->idDepartamento:0;
						#var_dump($pago);
						
						foreach($departamentos as $row)
						{
							echo '<option '.($idDepartamento==$row->idDepartamento?'selected="selected"':'').' value="'.$row->idDepartamento.'">'.$row->nombre.'</option>';
						}
						
					echo'
					</select>
				</div>
				<!--img id="btnDepartamentos" src="'.base_url().'img/agregar.png" width="20" title="Agregar departamento" height="20" /-->
				
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
			<td class="key">Tipo</td>
			<td>
				<div id="obtenerTipoGasto" style="float:left; width:300px">
					<select class="cajas" id="selectTipoGasto" name="selectTipoGasto" style="width:290px">
						<option value="0">Seleccione</option>';
						
						$idGasto	= $pago!=null?$pago->idGasto:0;
						
						foreach($gastos as $row)
						{
							echo '<option '.($idGasto==$row->idGasto?'selected="selected"':'').' value="'.$row->idGasto.'">'.$row->nombre.'</option>';
						}
						
					echo'
					</select>
				</div>
				<!--img id="btnTipoGasto" src="'.base_url().'img/agregar.png" width="20" title="Agregar Tipo de gasto" height="20" /-->
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
			<td class="key">Seleccionar forma de cobro:</td>
			<td>
				<select id="selectFormas" name="selectFormas" class="cajas" style="width:150px;" onchange="opcionesFormasPago()">';
					
					$idForma	= $pago==null?$cotizacion->idForma:$pago->idForma;
					
					
					foreach($formas as $row)
					{
						if($row->idForma!=4)
						{
							echo '<option '.($idForma==$row->idForma?'selected="selected"':'').' value="'.$row->idForma.'">'.$row->nombre.'</option>';
						}
					}
					/*<option value="1">Efectivo</option>
					<option value="2">Cheque</option>
					<option value="3">Transferencia</option>*/
				
				echo'</select>   
			 </td>
		</tr>
		<tr style="display:none;" id="mostrarCheques">
			<td class="key">Número cheque:</td>
			<td>
				<input type="text" class="cajas" id="numeroCheque" name="numeroCheque" />   
			</td>
		</tr>
		<tr style="display:none;" id="mostrarTransferencia">
			<td class="key">Número Transferencia:</td>
			<td>
			<input type="text" class="cajas" id="numeroTransferencia" name="numeroTransferencia" />    </td>
		</tr>
		
		<tr style="display:none;" id="filaNombre">
			<td class="key">Nombre del receptor:</td>
			<td>
				<input type="text" class="cajas" id="txtNombreReceptor" name="txtNombreReceptor" />
			</td>
		</tr>
		
		<tr id="contenedorNombres" style="display:none">
			<td class="key">Páguese por este documento a:</td>
			<td>
				<div id="obtenerNombres" style="float:left; width:300px">
					<select class="cajas" id="selectNombres" name="selectNombres" style="width:290px">
						<option value="0">Seleccione</option>';
						
						$idNombre	= $pago!=null?$pago->idNombre:0;
						
						foreach($nombres as $row)
						{
							echo '<option '.($idNombre==$row->idNombre?'selected="selected"':'').' value="'.$row->idNombre.'">'.$row->nombre.'</option>';
						}
						
					echo'
					</select>
				</div>
				<!--img id="btnNombres" src="'.base_url().'img/agregar.png" width="20" title="Agregar nombre" height="20" /-->
				<script>
				$("#btnNombres").click(function(e)
				{
					formularioNombres();
					$("#ventanaFormularioNombres").dialog("open");
				});
				</script>
			</td>
		</tr>
		
		<tr>
		<td class="key">Bancos:</td>
		<td> 
		 <select id="listaBancos" name="listaBancos" class="cajas" style="width:150px;" onchange="buscarCuentas()" >
			<option value="0">Seleccione</option>';
					
					$idBanco	= $pago!=null?$pago->idBanco:0;
				   	foreach($bancos as $row)
				   	{
					  	 echo '<option '.($idBanco==$row->idBanco?'selected="selected"':'').' value="'.$row->idBanco.'" >'.$row->nombre.'</option>';
				   	}
				 
				echo'</select>
				</td>
				</tr>
				<tr>
				<td class="key">Cuentas</td>
			 <td id="cargarCuenta">
			  <select id="cuentasBanco" name="cuentasBanco" class="cajas" style="width:150px;" >
				 <option value="0">Seleccione</option>';
				
				if($pago!=null)
				{
					foreach($cuentas as $row)
				   	{
					   	echo '<option '.($pago->idCuenta==$row->idCuenta?'selected="selected"':'').' value="'.$row->idCuenta.'" >'.$row->cuenta.'</option>';
				   	}
				}
				
				echo'
				</select>
			</td>     
		</tr>
	
		<tr>
			<td class="key">Factura/Remisión:</td>
			<td>
				<select class="cajas" id="selectFacturaRemision" name="selectFacturaRemision" style="width:100px">
					<option value="0">Factura</option>
					<option value="1">Remisión</option>
				</select><br />
				<input type="text" class="cajas" id="txtFactura"  name="txtFactura" style="width:290px" />
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
				<input type="file" class="cajas" id="txtComprobante" name="txtComprobante" style="height:30px; width:290px" />
			</td>     
		</tr>
	</table>
</form>';

if(!empty ($pagos))
{
	echo'
	<table class="admintable" width="100%;" >
		<thead>
			<th style="width:130px;">Fecha Hora/Pago</th>
			<th>Forma de cobro</th>
			<th>No Transferencia</th>
			<th>No.Cheque</th>
			<th>Cuenta</th>
			<th style="width:130px;">Cobro</th>
			<th>Acciones</th>';

	$i=1;
	foreach($pagos as $row)
	{
		echo'
		<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
			<td align="center" valign="middle">'.obtenerFechaMesCortoHora($row->fecha).' </td>
			<td align="center" valign="middle">'.$row->formaPago.' </td>
			<td align="center" valign="middle">'.$row->transferencia.' </td>
			<td align="center" valign="middle">'.$row->cheque.' </td>
			<td align="center" valign="middle">'.$row->cuenta.' </td>
			<td align="center" valign="middle">$ '.number_format($row->pago,2).' </td>
			<td align="left">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnComprobantes'.$i.'" src="'.base_url().'img/subir.png" width="22"  onclick="obtenerComprobantes('.$row->idIngreso.')"  title="Comprobantes" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img onclick="accesoBorrarCobro('.$row->idIngreso.')" src="'.base_url().'img/borrar.png" width="18" title="Borrar pago" />
				<br />
				<a id="a-btnComprobantes'.$i.'">Comprobantes</a>
				<a>Borrar</a>
				
			</td>
		</tr>';
		
		$i++;
	}

	echo'</table>';
}