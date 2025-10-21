<?php
echo'
<div class="ui-state-error" ></div>
<table style="width:100%" class="admintable">
	<tr>
		<td class="key">Cuenta contable:</td>
		<td>
			<input type="text" class="cajas" id="txtBuscarCuentaContable" name="txtBuscarCuentaContable" style="width:300px" placeholder="'.$material->cuenta.'" value="'.$material->cuenta.'" readonly="readonly" />
			<input type="hidden" id="txtIdCuentaCatalogo" name="txtIdCuentaCatalogo" value="'.$material->idCuentaCatalogo.'" />
			
			<label style="cursor:pointer; float:right; margin-right: 170px" onclick="formularioAsociarCuenta()" title="Agregar cuenta" >
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img src="'.base_url().'img/contabilidad.png" width="28"  /><br />
				Agregar cuenta
			</label>
		</td>
	</tr>
		
	<tr>
		<td class="key">'.(sistemaActivo=='IEXE'?'Insumo':'Materia prima').':</td>
		<td>
			<input id="materiaPrima" name="materiaPrima" class="cajas" style="width:70%" value="'.$material->nombre.'" />
		</td>
	</tr>

	<tr>
		<td class="key">Inventario:</td>
		<td>'.number_format($material->inventario-$material->salidas,4).'</td>
	</tr>
	
	
	
	<td class="key">Unidad:</td>
		<td>
		<select name="txtUnidad" id="txtUnidad" onchange="obtenerConversiones()" class="cajas" style="width:200px;" >
			<option value="0">Seleccione</option>';
		 
			   foreach($unidades as $row) 
			   { 
				  $seleccionado=$row['idUnidad']==$material->idUnidad?'selected="selected"':'';
				  echo'<option '.$seleccionado.' value="'.$row['idUnidad'].'">'.$row['descripcion'].'</option>';
			   }
			echo'
			</select>
		 </td>
	</tr>
	
	<td class="key">Conversión:</td>
		<td id="obtenerConversiones">
		<select name="selectConversiones" id="selectConversiones" class="cajas" style="width:200px;" >
			<option value="0">Seleccione</option>';
			
			 foreach($conversiones as $row) 
			 { 
				 $seleccionado=$row->idConversion==$material->idConversion?'selected="selected"':'';
				 echo'<option '.$seleccionado.' value="'.$row->idConversion.'">'.$row->nombre.' ('.$row->referencia.')</option>';
			 }
			   
			echo'
			</select>
		 </td>
	</tr>
	
	<td class="key">Categoría / Subcategoría:</td>
		<td>
		<select name="selectSubCategoria" id="selectSubCategoria" class="cajas" style="width:400px;" >
			<option value="0">Seleccione</option>';
		 
			   foreach($subCategorias as $row) 
			   { 
				  echo'<option '.($row->idSubCategoria==$material->idSubCategoria?'selected="selected"':'').' value="'.$row->idSubCategoria.'">'.$row->categoria.' / '.$row->nombre.'</option>';
			   }
			echo'
			</select>
		 </td>
	</tr>
	
	<tr>
		<td class="key">Costo:</td>
		<td>
			<input id="costoMateria" name="costoMateria" class="cajas" value="'.round($material->costoMaterial,decimales).'" maxlength="10" onkeypress="return soloDecimales(event)" />
			<input type="hidden" id="txtIdMaterial" name="" class="cajas" value="'.$idMaterial.'" />
		</td>
	</tr>
	
	<input type="hidden" id="txtIdProveedor" value="'.$material->idProveedor.'" />
	<input type="hidden" id="txtIdProveedorPasado" value="'.$material->idProveedor.'" /> ';
	
	if($relaciones==1)
	{
		if($this->materiales->comprobarComprasMaterial($idMaterial,$idProveedor)==0)
		{
			echo'
			<tr>
			  <td  class="key">Proveedor:</td>
			  <td>
				  
				  <input type="text" value="'.$material->empresa.'" class="cajas" id="txtBusquedaProveedor"  style="width:500px" />
				  <script>
					$("#txtBusquedaProveedor").autocomplete(
					{
						source:"'.base_url().'configuracion/obtenerProveedores",
						
						select:function( event, ui)
						{
							$("#txtIdProveedor").val(ui.item.idProveedor)
						}
					});
				  </script>
			  </td>
			</tr>';
		}
	}
	else
	{
		echo '
		<tr>
			<td class="key">Proveedor:</td>
			<td>'.$material->empresa.'</td>
		</tr>';
	}
	
	
	echo'
	<tr>
		<td class="key">Código interno:</td>
		<td>
			<input id="txtCodigoInternoEditar" name="txtCodigoInternoEditar" class="cajas" style="width:70%" value="'.$material->codigoInterno.'" />
		</td>
	</tr>
	<tr>
		<td class="key">Cantidad mínima:</td>
		<td>
			<input id="txtCantidadMinimaEditar" name="txtCantidadMinimaEditar" class="cajas" style="width:70%" value="'.round($material->stockMinimo,decimales).'"  maxlength="10" onkeypress="return soloDecimales(event)"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Impuesto:</td>
		<td>
			<select class="cajas" id="selectImpuestos" name="selectImpuestos" style="width:150px" onchange="calcularImpuestoProducto(0)">';
			foreach($impuestos as $row)
			{
				#if($row->idImpuesto<3)
				{
					echo '<option '.($row->idImpuesto==$material->idImpuesto?'selected="selected"':'').' value="'.$row->idImpuesto.'|'.$row->tasa.'">'.$row->nombre.'('.($row->exento=='0'?number_format($row->tasa,decimales):'Exento').')</option>';
				}
			}
			echo'
			</select>
			
		</td>
	</tr>';
	
	
	if(sistemaActivo=='olyess')
	{
		echo '
		<tr>
			<td class="key">Precio antes de impuestos:</td>
			<td>
				<input type="text" class="cajas" name="txtPrecioA" value="'.round($material->precio,decimales).'" id="txtPrecioA" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="calcularImpuestoProducto(0)"/>
			</td>
		</tr>
		<tr>
			<td class="key">Precio con impuestos:</td>
			<td>
				<input type="text" class="cajas" name="txtPrecioImpuestos" value="'.round($material->precioImpuestos,decimales).'" id="txtPrecioImpuestos" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="calcularImpuestoProducto(1)"/>
			</td>
		</tr>';
	}
	
	
echo'
</table>';