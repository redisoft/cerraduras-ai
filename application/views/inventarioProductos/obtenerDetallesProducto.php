<?php
echo'

<script>
$(document).ready(function()
{
	$("#txtUnidad").autocomplete(
	{
		source:"'.base_url().'configuracion/autoCompletadoUnidades",
		select: function(event,ui)
		{
			$("#txtIdUnidad").val(ui.item.idUnidad);
		}
	});
	
	$("#txtClaveProductoServicio").autocomplete(
	{
		source:"'.base_url().'configuracion/autoCompletadoProductoServicios",
		select: function(event,ui)
		{
			$("#txtIdClave").val(ui.item.idClave);
		}
	});
});
</script>

<div class="ui-state-error" ></div>
<form id="frmEditarProducto" name="frmEditarProducto">
	<table class="admintable" width="100%">
		<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
			<td class="key">Cuenta contable:</td>
			<td>
				<input type="text" class="cajas" id="txtBuscarCuentaContable" name="txtBuscarCuentaContable" style="width:300px" placeholder="'.$producto->cuenta.'" value="'.$producto->cuenta.'" readonly="readonly" />
				<input type="hidden" id="txtIdCuentaCatalogo" name="txtIdCuentaCatalogo" value="'.$producto->idCuentaCatalogo.'" />
				
				<label style="cursor:pointer; float:right; margin-right: 90px" onclick="formularioAsociarCuenta()" title="Agregar cuenta" >
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/contabilidad.png" width="28"  /><br />
					Agregar cuenta
				</label>
			</td>
		</tr>
		
		<tr>
			<td class="key">Nombre producto</td>
			<td>';
				
				echo"<input type='text' class='cajas' style='width:98%' id='txtNombre' name='txtNombre' value='".$producto->descripcion."' />";
				
				echo'
				<input type="hidden" id="txtIdProducto" name="txtIdProducto" value="'.$idProducto.'" />
			</td>
		</tr>
		
	<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
		<td class="key">Descripción:</td>
		<td>
			<textarea class="TextArea" name="txtDescripcion" id="txtDescripcion" style="height: 40px; width:98%;">'.$producto->descripcionProducto.'</textarea>  
		</td>
	</tr>
	
	<tr>
		<td class="key">Departamento:</td>
		<td>
			<div id="obtenerDepartamentos" style="float:left; width:300px">
				<select class="cajas" id="selectDepartamentos" name="selectDepartamentos" style="width:280px">
					<option value="0">Seleccione</option>';
				
				foreach($departamentos as $row)
				{
					echo '<option '.($row->idDepartamento==$producto->idDepartamento?'selected="selected"':'').' value="'.$row->idDepartamento.'">'.$row->nombre.'</option>';
				}
				
				echo'
				</select>
			</div>
			<!-- <img onclick="formularioLineas()" src="'.base_url().'img/agregar.png" width="20" title="Agregar línea" height="20" /> -->
		</td>
	</tr>
	
	<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
		<td class="key">Marca:</td>
		<td>
			<div id="obtenerMarcas" style="float:left; width:300px">
				<select class="cajas" id="selectMarcas" name="selectMarcas" style="width:280px">
					<option value="0">Seleccione</option>';
				
				foreach($marcas as $row)
				{
					echo '<option '.($row->idMarca==$producto->idMarca?'selected="selected"':'').' value="'.$row->idMarca.'">'.$row->nombre.'</option>';
				}
				
				echo'
				</select>
			</div>
			<!-- <img onclick="formularioLineas()" src="'.base_url().'img/agregar.png" width="20" title="Agregar línea" height="20" /> -->
		</td>
	</tr>
	
	<tr>
		<td class="key">Línea:</td>
		<td>
			<div id="obtenerLineas" style="float:left; width:300px">
				<select class="cajas" id="selectLineas" name="selectLineas" style="width:280px"  '.(sistemaActivo!='pinata'?' onchange="obtenerSubLineasCatalogo()" ':'').'>
					<option value="0">Seleccione</option>';
					
					foreach($lineas as $row)
					{
						$seleccionado=$row->idLinea==$producto->idLinea?'selected="selected"':'';
						
						echo '<option '.$seleccionado.' value="'.$row->idLinea.'">'.$row->nombre.'</option>';
					}
					
				echo'
				</select>
			</div>
			<!-- <img onclick="formularioLineas()" src="'.base_url().'img/agregar.png" width="20" title="Agregar línea" height="20" /> -->
		</td>
	</tr>
	
	<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
		<td class="key">Sublinea:</td>
		<td>
			<div id="obtenerSubLineas" style="float:left; width:300px">
				<select class="cajas" id="selectSubLineas" name="selectSubLineas" style="width:280px">
					<option value="0">Seleccione</option>';
					
					foreach($subLineas as $row)
					{
						echo '<option '.($row->idSubLinea==$producto->idSubLinea?'selected="selected"':'').' value="'.$row->idSubLinea.'">'.$row->nombre.'</option>';
					}
					
				echo'
				</select>
			</div>
			 <!--<img onclick="formularioLineas()" src="'.base_url().'img/agregar.png" width="20" title="Agregar línea" height="20" /> -->
		</td>
	</tr>';
	
	if(sistemaActivo=='olyess')
	{
		echo '
		<tr >
			<td class="key">Rebadas:</td>
			<td>
				<input type="text" name="txtNumeroRebanadas" id="txtNumeroRebanadas" class="cajas" style="width:100px;" onkeypress="return soloNumerico(event)" value="'.round($producto->rebanadas,decimales).'" /> 
				
				<strong>Precio rebanada</strong>
				
				<input type="text" name="txtPrecioRebanada" id="txtPrecioRebanada" class="cajas" style="width:100px;" onkeypress="return soloDecimales(event)" value="'.round($producto->precioRebanada,decimales).'" /> 
			</td>
		</tr>';
	}
	
	echo'
	<tr>
		<td class="key">Unidad</td>
		<td>
			<input type="text" class="cajas" name="txtUnidad" id="txtUnidad" style="width: 300px" placeholder="Seleccione" value="'.$producto->unidad.'" />
			<input type="hidden" id="txtIdUnidad" name="txtIdUnidad" value="'.$producto->idUnidad.'" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Clave producto / servicio:</td>
		<td>
			<input type="text" 		class="cajas" id="txtClaveProductoServicio" name="txtClaveProductoServicio" placeholder="Seleccione" value="'.$producto->claveProducto.'" style="width:300px"/>
			<input type="hidden" 	id="txtIdClave" name="txtIdClave" value="'.$producto->idClave.'" />
		</td>
	</tr>

	
	<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
		<td class="key">Imagen</td>
		<td class="imagenesListaProducto">
			<input type="file" id="userfile" name="userfile" class="cajas" style="height:30px; width: 230px" onchange="comprobarArchivo()" />
			&nbsp;&nbsp;';
			
			$imagen='<img src="'.base_url().carpetaProductos.'default.png" style="margin-left: 10px"  />';
	
			if(file_exists(carpetaProductos.$idProducto.'_'.$producto->imagen) and strlen($producto->imagen)>3)
			{
				$imagen='<img src="'.base_url().carpetaProductos.$idProducto.'_'.$producto->imagen.'" style="margin-left: 10px" />';
			}
			
			echo $imagen;
			
		echo'</td>
	</tr>	
	
	<tr>
	<td class="key">Código de barras</td>
	<td>
		<script>
			$("#codigoEditar").barcode("'.$producto->codigoBarras.'", "code39",{barWidth:1, barHeight:40})
		</script>
		<br />
		<input type="text" class="cajas" id="txtCodigoBarras" name="txtCodigoBarras" value="'.$producto->codigoBarras.'"/>
		<br />
		<br />
		<div id="codigoEditar"></div>
	</td>
</tr>	

<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
	<td class="key">Código interno:</td>
	<td>
	  <input type="text" name="txtCodigoInterno" value="'.$producto->codigoInterno.'" id="txtCodigoInterno" class="cajas" style="width:30%;" /> 
	</td>
</tr>

	<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
		<td class="key">SKU:</td>
		<td>
			<input type="text" name="txtSku" id="txtSku" class="cajas" style="width:30%;" value="'.$producto->sku.'" /> 
		</td>
	</tr>
	
	<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
		<td class="key">UPC:</td>
		<td>
			<input type="text" name="txtUpc" id="txtUpc" class="cajas" style="width:30%;" value="'.$producto->upc.'" /> 
		</td>
	</tr>
	<tr>
		<td class="key">Inventario inicial:</td>
		<td>
		  <input type="text" name="txtInventarioInicial" value="'.round($producto->stock,4).'" id="txtInventarioInicial" class="cajas" style="width:20%;" /> 
		</td>
	</tr>

	<tr>
		<td class="key">'.obtenerNombrePrecio(1).' antes de impuestos:</td>
		<td>
			<input type="text" class="cajas" name="txtPrecioA" value="'.round($producto->precioA,4).'" id="txtPrecioA" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="calcularImpuestoProducto(0)"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">'.obtenerNombrePrecio(1).' con impuestos:</td>
		<td>
			<input type="text" class="cajas" name="txtPrecioImpuestos" value="'.round($producto->precioImpuestos,4).'" id="txtPrecioImpuestos" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="calcularImpuestoProducto(1)"/>
		</td>
	</tr>
	
	<tr style="display:none">
		<td class="key">'.obtenerNombrePrecio(2).':</td>
		<td>
		<input type="text" class="cajas" name="txtPrecioB" id="txtPrecioB" value="'.round($producto->precioB,4).'" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
		</td>
	</tr>
	<tr style="display:none">
		<td class="key">'.obtenerNombrePrecio(3).':</td>
		<td>
		<input type="text" class="cajas" name="txtPrecioC" value="'.round($producto->precioC,4).'" id="txtPrecioC" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
		</td>
	</tr>
	<tr style="display:none">
		<td class="key">'.obtenerNombrePrecio(4).':</td>
		<td>
		<input type="text" class="cajas" name="txtPrecioD" value="'.round($producto->precioD,4).'" id="txtPrecioD" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
		</td>
	</tr>
	<tr style="display:none">
		<td class="key">'.obtenerNombrePrecio(5).':</td>
		<td>
		<input type="text" class="cajas" name="txtPrecioE" value="'.round($producto->precioE,4).'" id="txtPrecioE" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Impuesto:</td>
		<td>
			<select class="cajas" id="selectImpuestos" name="selectImpuestos" style="width:150px" onchange="calcularImpuestoProducto(0)">';
			
			if(sistemaActivo=='pinata')
			{
				foreach($impuestos as $row)
				{
					echo '<option '.($row->idImpuesto==$producto->idImpuesto?'selected="selected"':'').' value="'.$row->idImpuesto.'|'.$row->tasa.'">'.$row->nombre.'('.($row->exento=='0'?number_format($row->tasa,decimales):'Exento').')</option>';
				}
			}
			else
			{
				foreach($impuestos as $row)
				{
					#if($row->idImpuesto<3)
					{
						echo '<option '.($row->idImpuesto==$producto->idImpuesto?'selected="selected"':'').' value="'.$row->idImpuesto.'|'.$row->tasa.'">'.$row->nombre.'('.($row->exento=='0'?number_format($row->tasa,decimales):'Exento').')</option>';
					}
				}
			}
			
				
			echo'
			</select>
			
		</td>
	</tr>

	</table>
</form>';

/*<tr>
	<td class="key">Costo</td>
	<td>
		<input type="text" class="cajas" name="txtCosto1" value="'.$producto->costo.'" id="txtCosto1" style="width:20%"/>
	</td>
</tr>*/