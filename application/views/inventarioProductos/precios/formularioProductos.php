<?php
echo '
<script>
$(document).ready(function()
{
	$("#nombre").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerProductosInventarioRepetido",
		
		select:function( event, ui)
		{
			notify("El producto ya esta registrado",500,5000,"error",5,5);
			document.getElementById("nombre").reset();
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
<form id="frmAgregarProducto" name="frmAgregarProducto" accept-charset="utf-8">
<table class="admintable" width="100%;">
	<tr '.(tipoUsuario=='pinata'?'style="display:none"':'').'>
		<td class="key">Cuenta contable:</td>
		<td>
			<input type="text" class="cajas" id="txtBuscarCuentaContable" name="txtBuscarCuentaContable" style="width:300px" placeholder="Inventario"  readonly="readonly"/>
			<input type="hidden" id="txtIdCuentaCatalogo" name="txtIdCuentaCatalogo" value="82" />
			
			<label style="cursor:pointer; float:right; margin-right: 90px" onclick="formularioAsociarCuenta()" title="Agregar cuenta" >
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img src="'.base_url().'img/contabilidad.png" width="28"  /><br />
				Agregar cuenta
			</label>
		</td>
	</tr>
	
	<tr>
		<td class="key">Nombre producto:</td>
		<td>
			<input type="text" name="txtNombre" id="txtNombre" class="cajas" style="width:98%;" /> 
		</td>
	</tr>
	
	<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
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
	
	<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
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
			<div id="obtenerLineas" style="float:left; width:300px">
				<select class="cajas" id="selectLineas" name="selectLineas" style="width:280px"   '.(sistemaActivo!='pinata'?' onchange="obtenerSubLineasCatalogo()" ':'').'>
					<option value="0">Seleccione</option>
				</select>
			</div>
			 <!--<img onclick="formularioLineas()" src="'.base_url().'img/agregar.png" width="20" title="Agregar línea" height="20" /> -->
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
	</tr>';

	if(sistemaActivo=='olyess')
	{
		echo '
		<tr >
			<td class="key">Rebadas:</td>
			<td>
				<input type="text" name="txtNumeroRebanadas" id="txtNumeroRebanadas" class="cajas" style="width:100px;" onkeypress="return soloNumerico(event)" /> 
				
				<strong>Precio rebanada</strong>
				
				<input type="text" name="txtPrecioRebanada" id="txtPrecioRebanada" class="cajas" style="width:100px;" onkeypress="return soloDecimales(event)" /> 
			</td>
		</tr>';
	}
	
	
	echo'
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


<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
	<td class="key">Proveedor</td>
	<td>
		<input type="hidden" class="cajas" id="selectProveedores" name="selectProveedores" value="'.(tipoUsuario!='demo'?'1':'0').'" >
		<input type="text" style="width:98%" class="cajas" id="txtBuscarProveedor" name="txtBuscarProveedor" placeholder="Seleccione" value="'.(tipoUsuario!='demo'?'Demo':'').'">
		<script>
		$("#txtBuscarProveedor").autocomplete(
		{
			source:"'.base_url().'configuracion/obtenerProveedores",
			
			select:function( event, ui)
			{
				$("#selectProveedores").val(ui.item.idProveedor);
			}
		});
		</script>
	</td>
</tr>

<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
	<td class="key">Imagen</td>
	<td>
		<input type="file" id="userfile" name="userfile" class="cajas" style="height:30px; width: 230px" onchange="comprobarArchivo()" />
	</td>
</tr>';	
 
  $random	=rand(100000000,999999999);
  $codigo	=str_replace("5","9",$random);
  
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
	<tr '.(tipoUsuario=='pinata'?'style="display:none"':'').'>
		<td class="key">Código interno:</td>
		<td>
			<input type="text" name="txtCodigoInterno" id="txtCodigoInterno" class="cajas" style="width:30%;" /> 
		</td>
	</tr>
	
	<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
		<td class="key">SKU:</td>
		<td>
			<input type="text" name="txtSku" id="txtSku" class="cajas" style="width:30%;" /> 
		</td>
	</tr>
	
	<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
		<td class="key">UPC:</td>
		<td>
			<input type="text" name="txtUpc" id="txtUpc" class="cajas" style="width:30%;" /> 
		</td>
	</tr>
	
	<tr>
		<td class="key">Inventario inicial:</td>
		<td>
			<input type="text" class="cajas" name="txtInventarioInicial" value="'.(sistemaActivo=='pinata'?'1000':'0').'"  id="txtInventarioInicial" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
		</td>
	</tr>

<tr '.(sistemaActivo=='pinata'?'style="display:none"':'').'>
	<td class="key">Costo:</td>
	<td>
		<input type="text" class="cajas" name="txtCosto" value="" id="txtCosto" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
	</td>
</tr>

<tr>
	<td class="key">'.obtenerNombrePrecio(1).':</td>
	<td>
		<input type="text" class="cajas" name="txtPrecioA" value="0" id="txtPrecioA" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" />
	</td>
</tr>


<tr>
	<td class="key">'.obtenerNombrePrecio(2).':</td>
	<td>
		<input type="text" class="cajas" name="txtPrecioB" id="txtPrecioB" style="width:20%" value="0" onkeypress="return soloDecimales(event)" maxlength="15"/>
	</td>
</tr>
<tr>
	<td class="key">'.obtenerNombrePrecio(3).':</td>
	<td>
		<input type="text" class="cajas" name="txtPrecioC" value="0" id="txtPrecioC" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
	</td>
</tr>
<tr>
	<td class="key">'.obtenerNombrePrecio(4).':</td>
	<td>
		<input type="text" class="cajas" name="txtPrecioD" value="0" id="txtPrecioD" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
	</td>
</tr>
<tr>
	<td class="key">'.obtenerNombrePrecio(5).':</td>
	<td>
		<input type="text" class="cajas" name="txtPrecioE" value="0" id="txtPrecioE" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15"/>
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
				$seleccionado	= '';
				if($row->idImpuesto==4 and sistemaActivo=='pinata')
				{
					$seleccionado='selected="selected"';
				}
				
				echo '<option '.$seleccionado.' value="'.$row->idImpuesto.'|'.$row->tasa.'">'.$row->nombre.'('.($row->exento=='0'?number_format($row->tasa,decimales):'Exento').')</option>';
			}
		}
		else
		{
			foreach($impuestos as $row)
			{
				#if($row->idImpuesto<3)
				{
					echo '<option value="'.$row->idImpuesto.'|'.$row->tasa.'">'.$row->nombre.'('.($row->exento=='0'?number_format($row->tasa,decimales):'Exento').')</option>';
				}
			}
		}
		
			
		echo'
		</select>
		
	</td>
</tr>

</table>
</form>';