<script>
opcionesFormasPago()
</script>

<?php
echo'
<form id="frmPagoCompra" name="frmPagoCompra">
	<table class="admintable" width="100%;" >
		<tr>
			<th colspan="2" class="encabezadoPrincipal">Detalles de compra</th>
		</tr>
		<tr>
		
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" class="cajas" id="txtFechaEgreso" name="txtFechaEgreso" value="'.date('Y-m-d H:i').'" style="width:120px" />
				<script>
					$("#txtFechaEgreso").timepicker();
				</script>
			</td>
		</tr>
		<tr>
			<td class="key">Proveeedor: </td>
			<td>'.$compra->empresa.'</td>
		</tr>
		
		<tr>
			<td class="key">Compra: </td>
			<td>'.$compra->nombre.'</td>
		</tr>
		
		<tr>
			<td class="key">Subtotal: </td>
			<td>$'.number_format($compra->subTotal,decimales).'</td>
		</tr>
		
		<tr>
			<td class="key">Descuento: </td>
			<td>$'.number_format($compra->descuento,decimales).'</td>
		</tr>
		
		'.($compra->iva>0?'<tr>
			<td class="key">IVA: </td>
			<td>$'.number_format($compra->iva,decimales).'</td>
		</tr>':'').'
		
		<tr>
			<td class="key">Pago total: </td>
			<td>$'.number_format($compra->total,decimales).'</td>
		</tr>
		
		<tr>
			<td class="key">Monto pagado: </td>
			<td>$'.number_format($total->pago,decimales).'</td>
		</tr>
		
		<tr>
			<td class="key">Saldo: </td>
			<td>$'.number_format($compra->total-$total->pago,decimales).'</td>
		</tr>
		
		<tr>
			<td class="key">Monto a pagar: </td>
			<td>
				<input type="text" class="cajas" id="montoPagar" name="montoPagar" style="width:80px" value="'.($compra->total-$total->pago).'" />
				<input type="hidden" value="'.($compra->total-$total->pago).'" class="cajas" id="T3" style="width:80px" />
				<input type="hidden" value="'.$idCompra.'" class="cajas" 	id="idCompras" name="idCompras" style="width:80px" />
				<input type="hidden" value="'.$compra->idProveedor.'" 		id="txtIdProveedorCompra" name="txtIdProveedorCompra"/>
				<input type="hidden" value="'.$compra->empresa.'" 			id="txtProveedorCompra" name="txtProveedorCompra" />
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Iva:</td>
			<td>
				&nbsp;&nbsp;
				<input type="checkbox" id="chkIva" name="chkIva" '.($compra->iva>0?'checked="checked"':'').' value="1" />
				<input readonly="readonly" type="hidden" style="width:100px" class="cajas" value="'.$compra->ivaPorcentaje.'" id="txtIva" name="txtIva" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Concepto:</td>
			<td>
				<div id="obtenerProductos" style="float:left; width:300px">
					<select class="cajas" id="selectProductos" name="selectProductos" style="width:290px">
						<option value="0">Seleccione</option>';
					
					$idProducto	= $ultimo!=null?$ultimo->idProducto:0;
					
					foreach($conceptos as $row)
					{
						echo '<option '.($idProducto==$row->idProducto?'selected="selected"':'').' value="'.$row->idProducto.'">'.$row->nombre.'</option>';
					}
					
					echo'
					</select>
				</div>
				
				<!--img id="btnProductos" src="'.base_url().'img/agregar.png" width="20" title="Agregar producto" height="20" /-->
				<script>
				$("#btnProductos").click(function(e)
				{
					formularioProductos();
					$("#ventanaFormularioProductos").dialog("open");
				});
				</script>
			</td>
		</tr>';
		
		$descripcion='';
		$i=0;
		foreach($productos as $row)
		{
			$descripcion.=$i==0?$row->nombre:', '.$row->nombre;
			$i++;
		}
		
		echo'
		<tr>
			<td class="key">Descripción del producto:</td>
			<td>
				<textarea type="text" class="TextArea" id="txtDescripcionProducto" name="txtDescripcionProducto" style="height:50px; width:288px">'.$descripcion.'</textarea>
			</td>
		</tr>';
		
		echo'
		<tr>
			<td class="key">Departamento:</td>
			<td>
				<div id="obtenerDepartamentos" style="float:left; width:300px">
					<select class="cajas" id="selectDepartamento" name="selectDepartamento" style="width:290px">
						<option value="0">Seleccione</option>';
					
					$idDepartamento	= $ultimo!=null?$ultimo->idDepartamento:0;
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
			<td class="key">Tipo:</td>
			<td>
				<div id="obtenerTipoGasto" style="float:left; width:300px">
					<select class="cajas" id="selectTipoGasto" name="selectTipoGasto" style="width:290px">
						<option value="0">Seleccione</option>';
						
						$idGasto	= $ultimo!=null?$ultimo->idGasto:0;
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
			<td class="key">Seleccionar forma de pago:</td>
			<td>
				<select id="selectFormas" name="selectFormas" class="cajas" style="width:150px;" onchange="opcionesFormasPago()">';
					/*<option value="1">Efectivo</option>
					<option value="2">Cheque</option>
					<option value="3">Transferencia</option>*/
					
					$idForma	= $ultimo==null?0:$ultimo->idForma;
					foreach($formas as $row)
					{
						if($row->idForma!=4)
						{
							#$seleccionado=$cotizacion->idForma==$row->idForma?'selected="selected"':'';
							$seleccionado='';
							echo '<option '.($idForma==$row->idForma?'selected="selected"':'').' value="'.$row->idForma.'">'.$row->nombre.'</option>';
						}
					}
					
				echo'
				</select>   
			 </td>
		</tr>
		<tr style="display:none;" id="mostrarCheques">
			<td class="key">Número cheque:</td>
			<td>
				<input type="text" class="cajas" id="numeroCheque" name="numeroCheque" />   
			</td>
		</tr>
		
		<tr style="display:none;" id="filaNombre">
			<td class="key">Nombre del receptor:</td>
			<td>
				<input type="text" class="cajas" id="txtNombreReceptor" name="txtNombreReceptor" />
			</td>
		</tr>
		
		<tr style="display:none;" id="mostrarTransferencia">
			<td class="key">Número Transferencia:</td>
			<td>
				<input type="text" class="cajas" id="numeroTransferencia" name="numeroTransferencia" />    
			</td>
		</tr>
		
		<tr style="display:none" id="contenedorNombres">
			<td class="key">Páguese por este documento a:</td>
			<td>
				<div id="obtenerNombres" style="float:left; width:300px">
	
					<select class="cajas" id="selectNombres" name="selectNombres" style="width:290px">
						<option value="0">Seleccione</option>';
						
						$idNombre	= $ultimo!=null?$ultimo->idNombre:0;
						
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
				<select id="listaBancos" name="listaBancos" class="cajas" onchange="buscarCuentas()" style="width:150px" >
					<option value="0">Seleccione</option>';
			
				   $idBanco	= $ultimo!=null?$ultimo->idBanco:0;
				   	foreach($bancos as $row)
				   	{
					  	 echo '<option '.($idBanco==$row->idBanco?'selected="selected"':'').' value="'.$row->idBanco.'" >'.$row->nombre.'</option>';
				   	}
			 
			echo'
				</select>
			</td>
		</tr>
		<tr>
			<td class="key">Cuentas</td>
			<td id="cargarCuenta">
				<select id="cuentasBanco" name="cuentasBanco" class="cajas" style="width:150px;" >
					<option value="0">Seleccione</option>';
					
					if($ultimo!=null)
					{
						foreach($cuentas as $row)
						{
							echo '<option '.($ultimo->idCuenta==$row->idCuenta?'selected="selected"':'').' value="'.$row->idCuenta.'" >'.$row->cuenta.'</option>';
						}
					}
					
				echo'
				</select>
			</td>     
		</tr>';
		
		$seleccionado	= '';
		if($recibido!=null)
		{
			$seleccionado	= $recibido->factura=='0'?'selected="selected"':'';
		}
		
			
		echo'
		<tr>
			<td class="key">Factura/Remisión:</td>
			<td>
				<select class="cajas" id="selectFactura" name="selectFactura" style="width:100px">
					<option value="0">Factura</option>
					<option '.$seleccionado.' value="1">Remisión</option>
				</select>
				<br />
				<input type="text" class="cajas" id="txtFactura" name="txtFactura" style="width:290px" value="'.($recibido!=null?$recibido->remision:'').'" /> 
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
				<input type="file" class="cajas" id="txtArchivoPagoCompra" name="txtArchivoPagoCompra" style="height:25px; width:290px"/>
			</td>     
		</tr>
	</table>
</form>';

echo
'<script>
	$("#tdPagado'.$idCompra.'").html("$'.number_format($total->pago,2).'");
	$("#tdSaldo'.$idCompra.'").html("$'.number_format($compra->total-$total->pago,2).'");
</script>';

if(!empty ($pagos))
{
	echo'
	<table class="admintable" width="100%;" >
		<tr>
			<th class="encabezadoPrincipal" colspan="9">Detalles de pagos</th>
		</tr>
		<tr>
			<th>Fecha</th>
			<th>Forma de pago</th>
			<th>No Transferencia</th>
			<th>No.Cheque</th>
			<th>Cuenta</th>
			<th>Importe</th>
			<th>Factura</th>
			<th>Remisión</th>
			<th>Acciones</th>
		</tr>';
	
	
	$i=1;
		
	foreach($pagos as $row)
	{
		echo'
		<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
			<td align="center" valign="middle">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="center" valign="middle">'.$row->formaPago.'</td>
			<td align="center" valign="middle">'.$row->transferencia.'</td>
			<td align="center" valign="middle">'.$row->cheque.'</td>
			<td align="center" valign="middle">'.$row->cuenta.'</td>
			<td align="center" valign="middle">$ '.number_format($row->pago,2).' </td>
			<td align="center" valign="middle">'.($row->esRemision==0?$row->factura:'').' </td>
			<td align="center" valign="middle">'.($row->esRemision==1?$row->factura:'').' </td>
			<td align="left">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img onclick="obtenerComprobantesEgresos('.$row->idEgreso.','.$idCompra.')" src="'.base_url().'img/subir.png" width="18" title="Comprobantes"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img onclick="accesoBorrarPagoCompraMaterial('.$row->idEgreso.','.$idCompra.')" src="'.base_url().'img/borrar.png" width="18" title="Borrar pago"/>
				<br />
				<a>Comprobantes</a>
				
				<a>Borrar</a>
									
			</td>
		</tr>';
		
		$i++;
	
	}
	
	echo'</table>';
}
?>