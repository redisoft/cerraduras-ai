<?php
echo '
<script>
$(document).ready(function()
{
	$("#txtNombreProducto").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerInventarioProduccion",
		
		select:function( event, ui)
		{
			notify("El producto ya esta registrado",500,5000,"error",5,5);
			document.getElementById("txtNombreProducto").reset();
		}
	});
	
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
<form id="frmNuevoProducto" name="frmNuevoProducto" method="post" action="'.base_url().'produccion/registrarProduccion" enctype="multipart/form-data"> 
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Producto:</td>
		<td>
			<input type="text" name="txtNombreProducto" id="txtNombreProducto" class="cajas" style="width:98%;"   /> 
		</td>
	</tr>
	
	<tr>
		<td class="key">Descripción:</td>
		<td>
			<textarea class="TextArea" name="txtDescripcion" id="txtDescripcion" style="height: 40px; width:98%;"></textarea> 
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
					echo '<option value="'.$row->idDepartamento.'">'.$row->nombre.'</option>';
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
					echo '<option value="'.$row->idMarca.'">'.$row->nombre.'</option>';
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
			<div id="obtenerLineas" style="float:left; width:300px" onchange="obtenerSubLineasCatalogo()">
				<select class="cajas" id="selectLineas" name="selectLineas" style="width:280px">
					<option value="0">Seleccione</option>
				</select>
			</div>
			<!-- <img onclick="formularioLineas()" src="'.base_url().'img/agregar.png" width="20" title="Agregar línea" height="20" />--> 
		</td>
	</tr>
	
	
	<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
		<td class="key">Sublinea:</td>
		<td>
			<div id="obtenerSubLineas" style="float:left; width:300px">
				<select class="cajas" id="selectSubLineas" name="selectSubLineas" style="width:280px">
					<option value="0">Seleccione</option>
				</select>
			</div>
		</td>
	</tr>
	
	
	<tr>
		<td class="key">Unidad</td>
		<td>
			<input type="text" class="cajas" name="txtUnidad" id="txtUnidad" style="width: 300px" placeholder="Seleccione" value="H87, Pieza" />
			<input type="hidden" id="txtIdUnidad" name="txtIdUnidad" value="1070" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Clave producto / servicio:</td>
		<td>
			<input type="text" 		class="cajas" id="txtClaveProductoServicio" name="txtClaveProductoServicio" placeholder="Seleccione" value="01010101, No existe en el catálogo" style="width:300px"/>
			<input type="hidden" 	id="txtIdClave" name="txtIdClave" value="1" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Inventario inicial:</td>
		<td>
			<input type="text" value="0" name="txtInventarioInicial" id="txtInventarioInicial" class="cajas" style="width:30%;"   /> 
		</td>
	</tr>
	
	<tr>
	<td class="key">Imagen</td>
	<td>
		<input type="file" id="userfile" name="userfile" class="cajas" style="height:30px" onchange="comprobarArchivo()" />
	</td>
	</tr>';	
	
	  $random= rand(1000000000,9999999999);
	  $codigo=str_replace("5","9",$random);
	echo'
	<tr>
		<td class="key">Código de barras</td>
		<td>
			<script>
				$("#codigoNuevo").barcode("'.$codigo.'", "code93",{barWidth:1, barHeight:40})
			</script>
	
			<div id="codigoNuevo"></div>
			<br />
			<input class="cajas" type="text" id="txtCodigoBarras" name="txtCodigoBarras" value="'.$codigo.'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Código interno:</td>
		<td>
			<input type="text" name="txtCodigoInterno" id="txtCodigoInterno" class="cajas" style="width:30%;" /> 
		</td>
	</tr>
	
	<tr>
		<td class="key">SKU:</td>
		<td>
			<input type="text" name="txtSku" id="txtSku" class="cajas" style="width:30%;" /> 
		</td>
	</tr>
	
	<tr>
		<td class="key">UPC:</td>
		<td>
			<input type="text" name="txtUpc" id="txtUpc" class="cajas" style="width:30%;" /> 
		</td>
	</tr>
	
	<tr>
		<td class="key">'.obtenerNombrePrecio(1).' antes de impuestos:</td>
		<td>
		<input type="text" class="cajas" name="txtPrecioA"  id="txtPrecioA" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="calcularImpuestoProducto(0)"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">'.obtenerNombrePrecio(1).' con impuestos:</td>
		<td>
			<input type="text" class="cajas" name="txtPrecioImpuestos"  id="txtPrecioImpuestos" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="calcularImpuestoProducto(1)"/>
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
					echo '<option value="'.$row->idImpuesto.'|'.$row->tasa.'">'.$row->nombre.'('.($row->exento=='0'?number_format($row->tasa,decimales):'Exento').')</option>';
				}
			}
				
			echo'
			</select>
			
		</td>
	</tr>

	<tr style="display:none">
		<td class="key">'.obtenerNombrePrecio(2).':</td>
		<td>
		<input type="text" class="cajas" name="utilidadB" id="utilidadB" style="width:20%" value="1" onkeypress="return soloDecimales(event)" maxlength="15"/>
		</td>
	</tr>
	<tr style="display:none">
		<td class="key">'.obtenerNombrePrecio(3).':</td>
		<td>
		<input type="text" class="cajas" name="utilidadC" value="1" id="utilidadC" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
		</td>
	</tr>
	<tr style="display:none">
		<td class="key">'.obtenerNombrePrecio(4).':</td>
		<td>
		<input type="text" class="cajas" name="utilidadD" value="1" id="utilidadD" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
		</td>
	</tr>
	<tr style="display:none">
		<td class="key">'.obtenerNombrePrecio(5).':</td>
		<td>
		<input type="text" class="cajas" name="utilidadE" value="1" id="utilidadE" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
		</td>
	</tr>
	
	
	
	<tr style="display:none">
		<td class="key">Piezas</td>
		<td>
		<input type="text" class="cajas" name="txtPiezas" value="1" id="txtPiezas" style="width:20%"/>
		</td>
	</tr>
	 <tr style="display:none">
		<td class="key">¿Es materia prima?:</td>
		<td>
		<input type="checkbox" name="chkMateriaPrima" onchange="mostrarCamposProducto()" id="chkMateriaPrima" style="margin-left:5px"/>
		</td>
	</tr>
	  <tr id="mostrarUnidades" style="display:none">
		<td class="key">Unidad</td>
		<td>
			<select id="selectUnidadess" name="selectUnidadess" class="cajas" >
				<option value="0">Seleccione</option>';
				
				foreach($unidades as $row)
				{
					#echo '<option value="'.$row['idUnidad'].'">'.$row['descripcion'].'</option>';
				}
				
			echo'
			</select>
		</td>
	</tr>
</table>
</form>';