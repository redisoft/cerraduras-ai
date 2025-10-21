<?php
echo'
<script>
$(document).ready(function()
{
	$("#txtMaterial").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerMateriales",
		
		select:function( event, ui)
		{
			notify("El '.(sistemaActivo=='IEXE'?'insumo':'material').' ya esta registrado",500,5000,"error",5,5);
			document.getElementById("txtMaterial").reset();
		}
	});
});
</script>
<div class="ui-state-error" ></div>
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Cuenta contable:</td>
		<td>
			<input type="text" class="cajas" id="txtBuscarCuentaContable" name="txtBuscarCuentaContable" style="width:300px" placeholder="Materia prima y materiales" readonly="readonly" />
			<input type="hidden" id="txtIdCuentaCatalogo" name="txtIdCuentaCatalogo" value="83" />
			
			<label style="cursor:pointer; float:right; margin-right: 170px" onclick="formularioAsociarCuenta()" title="Agregar cuenta" >
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img src="'.base_url().'img/contabilidad.png" width="28"  /><br />
				Agregar cuenta
			</label>
		</td>
	</tr>
	
	<tr>
		<td class="key" >'.(sistemaActivo=='IEXE'?'Insumo':'Materia prima').':</td>
		<td>
			<input type="text" name="txtMaterial" id="txtMaterial" class="cajas" style="width:500px;"  maxlength="3000"/> 
		</td>
	</tr>
	
	<tr>
	
	<td class="key">Unidad:</td>
		<td>
		<select name="txtUnidad" id="txtUnidad" onchange="obtenerConversiones()" class="cajas" style="width:200px;" >
			<option value="0">Seleccione</option>';
		 
			   foreach($unidades as $unidad) 
			   { 
				  echo'<option value="'.$unidad['idUnidad'].'">'.$unidad['descripcion'].'</option>';
			   }
			echo'
			</select>
		 </td>
	</tr>
	
	<td class="key">Conversión:</td>
		<td id="obtenerConversiones">
		<select name="selectConversiones" id="selectConversiones" class="cajas" style="width:200px;" >
			<option value="0">Seleccione</option>
			</select>
		 </td>
	</tr>
	
	<td class="key">Categoría / Subcategoría:</td>
		<td>
		<select name="selectSubCategoria" id="selectSubCategoria" class="cajas" style="width:400px;" >
			<option value="0">Seleccione</option>';
		 
			   foreach($subCategorias as $row) 
			   { 
				  echo'<option value="'.$row->idSubCategoria.'">'.$row->categoria.' / '.$row->nombre.'</option>';
			   }
			echo'
			</select>
		 </td>
	</tr>
	
	<tr>
		<td class="key" >Código interno:</td>
		<td>
			<input type="text" name="txtCodigoInterno" id="txtCodigoInterno" class="cajas" style="width:200px;" maxlength="100" /> 
		</td>
	</tr>
	
	<tr>
		<td class="key">Costo:</td>
		<td>
			<input name="T4" style="width:200px;" type="text" class="cajas" id="T4" maxlength="10" onkeypress="return soloDecimales(event)" /> 
		</td>
	</tr>
	<tr>
	  <td  class="key">Proveedor:</td>
	  <td>
		  <input type="hidden" id="txtIdProveedor" value="0" />
		  <input type="text" class="cajas" id="txtBusquedaProveedor"  style="width:500px" placeholder="Seleccione" />
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
	</tr>
	<tr>
		<td  class="key">Cantidad mínima:</td>
		<td>
			<input name="CMINIMA" style="width:200px;" type="text" class="cajas" id="CMINIMA" maxlength="10" onkeypress="return soloDecimales(event)" /> 
		</td>
	</tr>
	
	<tr>
		<td class="key">Inventario inicial:</td>
		<td>
			<input name="txtInventarioInicial" id="txtInventarioInicial" type="text" class="cajasNormales" style="width:20%" maxlength="15" onkeypress="return soloDecimales(event)"  />
		</td>
	</tr>
	
	<!--<tr>
		<td class="key">Impuesto:</td>
		<td>
			<select class="cajas" id="selectImpuestos" name="selectImpuestos" style="width:120px">
				<option value="'.round($configuracion->iva,decimales).'">IVA 1('.number_format($configuracion->iva,decimales).')</option>
				<option value="'.round($configuracion->iva2,decimales).'">IVA 2('.number_format($configuracion->iva2,decimales).')</option>
				<option value="'.round($configuracion->iva3,decimales).'">IVA 3('.number_format($configuracion->iva3,decimales).')</option>
				<option value="'.round($configuracion->ieps,decimales).'">IEPS('.number_format($configuracion->ieps,decimales).')</option>
				<option value="'.round($configuracion->retencionIva,decimales).'">Retención IVA('.number_format($configuracion->retencionIva,decimales).')</option>
				<option value="'.round($configuracion->retencionIsr,decimales).'">Retención ISR('.number_format($configuracion->retencionIsr,decimales).')</option>
				<option value="'.round($configuracion->retencionIeps,decimales).'">Retención IEPS('.number_format($configuracion->retencionIeps,decimales).')</option>
			</select>
			
		</td>
	</tr>-->
	
	
	<tr>
		<td class="key">Impuesto:</td>
		<td>
			<select class="cajas" id="selectImpuestos" name="selectImpuestos" style="width:150px" onchange="calcularImpuestoProducto(0)">';
			foreach($impuestos as $row)
			{
				#if($row->idImpuesto<3)
				{
					echo '<option value="'.$row->idImpuesto.'|'.$row->tasa.'">'.$row->nombre.'('.($row->exento=='0'?number_format($row->tasa,decimales):'Exento').')</option>';
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
				<input type="text" class="cajas" name="txtPrecioA" value="0" id="txtPrecioA" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="calcularImpuestoProducto(0)"/>
			</td>
		</tr>
		<tr>
			<td class="key">Precio con impuestos:</td>
			<td>
				<input type="text" class="cajas" name="txtPrecioImpuestos" value="0" id="txtPrecioImpuestos" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="calcularImpuestoProducto(1)"/>
			</td>
		</tr>';
	}
	
echo'
</table>';