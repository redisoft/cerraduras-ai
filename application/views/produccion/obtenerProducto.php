<?php
print
'
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

<form id="frmEditarProducto" name="frmEditarProducto" method="post" action="'.base_url().'produccion/editarProducto" enctype="multipart/form-data"> 
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Producto:</td>
		<td>
			<input type="text" name="txtNombreEditar" id="txtNombreEditar" 
		class="cajas" style="width:98%;" value="'.$producto->nombre.'"/> </td>
	</tr>
	
	<tr>
		<td class="key">Descripción:</td>
		<td>
			<textarea class="TextArea" name="txtDescripcion" id="txtDescripcion" style="height: 40px; width:98%;">'.$producto->descripcion.'</textarea>  
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
	
	<tr>
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
				<select class="cajas" id="selectLineas" name="selectLineas" style="width:280px">
					<option value="0">Seleccione</option>';
					
					foreach($lineas as $row)
					{
						$seleccionado=$row->idLinea==$producto->idLinea?'selected="selected"':'';
						
						echo '<option '.$seleccionado.' value="'.$row->idLinea.'">'.$row->nombre.'</option>';
					}
					
				echo'
				</select>
			</div>
			 <img onclick="formularioLineas()" src="'.base_url().'img/agregar.png" width="20" title="Agregar línea" height="20" /> 
		</td>
	</tr>
	
	<tr>
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
	</tr>

	<tr>
		
		<input type="hidden" name="txtIdProductoEditar" id="txtIdProductoEditar" value="'.$idProducto.'" />
		<input type="hidden" name="txtMateriaPrimaEditar" id="txtMateriaPrimaEditar" value="'.$producto->materiaPrima.'" />
		
	<!--<tr>
		<td class="key">Unidad:</td>
		<td>
			<input type="text" name="txtUnidadProducto1" value="'.$producto->unidad.'" id="txtUnidadProducto1" class="cajas" style="width:20%;"   /> 
		</td>
	</tr>-->
	
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
	
	
	 <td class="key">Imagen</td>
	<td>
		<input type="file" id="userfile1" name="userfile1" class="cajas" style="height:30px" onchange="comprobarArchivoEditar()" />
		&nbsp;&nbsp;';
		
		$imagen='<img src="'.base_url().carpetaProductos.'default.png" style="width:50px; height:50px;  margin-left: 10px"  />';

		if(file_exists(carpetaProductos.$idProducto.'_'.$producto->imagen))
		{
			$imagen='<img src="'.base_url().carpetaProductos.$idProducto.'_'.$producto->imagen.'" style="width:50px; height:50px; margin-left: 10px" />';
		}
		
		echo $imagen;
	
	echo'
	</td>
	</tr>	

	<tr>
		<td class="key">Código de barras</td>
		<td>
			<script>
				$("#codigoNuevoEditar").barcode("'.$producto->codigoBarras.'", "code93",{barWidth:1, barHeight:40})
			</script>
	
			<div id="codigoNuevoEditar"></div>
			<br />
			<input class="cajas" type="text" id="txtCodigoBarrasEditar" name="txtCodigoBarrasEditar" value="'.$producto->codigoBarras.'"/>
		</td>
	</tr>

	<tr>
		<td class="key">Código interno</td>
		<td>
			<input type="text" class="cajas" name="txtCodigoInternoEditar" value="'.$producto->codigoInterno.'" id="txtCodigoInternoEditar" style="width:30%"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">SKU:</td>
		<td>
			<input type="text" name="txtSku" id="txtSku" class="cajas" style="width:30%;" value="'.$producto->sku.'" /> 
		</td>
	</tr>
	
	<tr>
		<td class="key">UPC:</td>
		<td>
			<input type="text" name="txtUpc" id="txtUpc" class="cajas" style="width:30%;" value="'.$producto->upc.'" /> 
		</td>
	</tr>';
	
	if($producto->materiaPrima==0)
	{
		echo'
		<tr>
			<td class="key">'.obtenerNombrePrecio(1).' antes de impuestos:</td>
			<td>
				<input type="text" class="cajas" name="txtPrecioA" value="'.round($producto->precioA,decimales).'" id="txtPrecioA" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="calcularImpuestoProducto(0)"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">'.obtenerNombrePrecio(1).' con impuestos:</td>
			<td>
				<input type="text" class="cajas" name="txtPrecioImpuestos" value="'.round($producto->precioImpuestos,decimales).'" id="txtPrecioImpuestos" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="calcularImpuestoProducto(1)"/>
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
							echo '<option '.($row->idImpuesto==$producto->idImpuesto?'selected="selected"':'').' value="'.$row->idImpuesto.'|'.$row->tasa.'">'.$row->nombre.'('.($row->exento=='0'?number_format($row->tasa,decimales):'Exento').')</option>';
						}
					}
				
					
				echo'
				</select>
				
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">'.obtenerNombrePrecio(2).':</td>
			<td>
			<input type="text" class="cajas" name="utilidadBEditar" value="'.round($producto->precioB,4).'" id="utilidadBEditar" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">'.obtenerNombrePrecio(3).':</td>
			<td>
			<input type="text" class="cajas" name="utilidadCEditar" value="'.round($producto->precioC,4).'"" id="utilidadCEditar" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
			</td>
		</tr>
		<tr style="display:none">
			<td class="key">'.obtenerNombrePrecio(4).':</td>
			<td>
			<input type="text" class="cajas" name="utilidadDEditar" value="'.round($producto->precioD,4).'"" id="utilidadDEditar" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">'.obtenerNombrePrecio(5).':</td>
			<td>
			<input type="text" class="cajas" name="utilidadEEditar" value="'.round($producto->precioE,4).'"" id="utilidadEEditar" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
			</td>
		</tr>';
	}
	
echo'</table>';
?>