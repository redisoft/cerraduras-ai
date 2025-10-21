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

<form id="frmServicios" name="frmServicios">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Nombre servicio:</td>
			<td>
				<input type="text" class="cajas" style="width:98%" id="txtNombreProducto" name="txtNombreProducto"/>
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
			<td class="key">Periodicidad:</td>
			<td>
				<select class="cajas" id="selectPeriodos" name="selectPeriodos" style="width:200px">';
					
					foreach($periodos as $row)
					{
						echo '<option '.($row->idPeriodo==8?'selected="selected"':'').' value="'.$row->idPeriodo.'">'.$row->nombre.'</option>';
					}
					
					echo'
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="key">Plazo:</td>
			<td>
				<input type="text" class="cajas" style="width:100px" id="txtPlazo" name="txtPlazo" value="0" onkeypress="return soloNumerico(event)"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Línea:</td>
			<td>
				<div id="obtenerLineas" style="float:left; width:300px">
					<select class="cajas" id="selectLineas" name="selectLineas" style="width:280px">';
						
						foreach($lineas as $row)
						{
							echo '<option value="'.$row->idLinea.'">'.$row->nombre.'</option>';
						}
						
						echo'
					</select>
				</div>
				
				<img onclick="formularioLineas()" src="'.base_url().'img/agregar.png" width="20" title="Agregar línea" height="20" /> 
				
			</td>
		</tr>
		
		<tr>
			<td class="key">Unidad</td>
			<td>
				<input type="text" class="cajas" name="txtUnidad" id="txtUnidad" style="width: 300px" placeholder="Seleccione" value="E48, Unidad de servicio" />
				<input type="hidden" id="txtIdUnidad" name="txtIdUnidad" value="678" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Clave producto / servicio:</td>
			<td>
				<input type="text" 		class="cajas" id="txtClaveProductoServicio" name="txtClaveProductoServicio" placeholder="Seleccione" value="01010101, No existe en el catálogo" style="width:300px"/>
				<input type="hidden" 	id="txtIdClave" name="txtIdClave" value="1" />
			</td>
		</tr>

		
		<!--<tr>
			<td class="key">Unidad:</td>
			<td>
				<select class="cajas" id="selectUnidades" name="selectUnidades" style="width:280px">
					<option value="0">Seleccione</option>';
					
				foreach($unidades as $row)
				{
					echo '<option value="'.$row->idUnidad.'">'.$row->descripcion.'</option>';
				}
					
				echo'
				</select>
			</td>
		</tr>-->
		
		<tr>
			<td class="key">Código interno:</td>
			<td>
				<input type="text" name="txtCodigoInterno" id="txtCodigoInterno" class="cajas" style="width:200px" /> 
			</td>
		</tr>
		<tr>
			<td class="key">'.obtenerNombrePrecio(1).' antes de impuestos:</td>
			<td>
				<input type="text" name="txtPrecioA" id="txtPrecioA"  class="cajas" style="width:100px;"  onkeypress="return soloDecimales(event)" maxlength="15" onchange="calcularImpuestoProducto(0)" /> 
			</td>
		</tr>
		
		<tr>
			<td class="key">'.obtenerNombrePrecio(1).' con impuestos:</td>
			<td>
				<input type="text" class="cajas" name="txtPrecioImpuestos" value="0" id="txtPrecioImpuestos" style="width:20%" onkeypress="return soloDecimales(event)" maxlength="15" onchange="calcularImpuestoProducto(1)"/>
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">'.obtenerNombrePrecio(2).':</td>
			<td>
				<input type="text" name="txtPrecioB" id="txtPrecioB"  class="cajas" style="width:100px;" value="1" onkeypress="return soloDecimales(event)" maxlength="15" /> 
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">'.obtenerNombrePrecio(3).':</td>
			<td>
				<input type="text" name="txtPrecioC" id="txtPrecioC"  class="cajas" style="width:100px;" value="1" onkeypress="return soloDecimales(event)" maxlength="15"/> 
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">'.obtenerNombrePrecio(4).':</td>
			<td>
				<input type="text" name="txtPrecioD" id="txtPrecioD"  class="cajas" style="width:100px;" value="1" onkeypress="return soloDecimales(event)" maxlength="15" /> 
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">'.obtenerNombrePrecio(5).':</td>
			<td>
				<input type="text" name="txtPrecioE" id="txtPrecioE"  class="cajas" style="width:100px;"  value="1" onkeypress="return soloDecimales(event)" maxlength="15"/> 
			</td>
		</tr>
		
		<tr>
			<td class="key">Impuesto:</td>
			<td>
				<select class="cajas" id="selectImpuestos" name="selectImpuestos" style="width:150px" onchange="calcularImpuestoProducto(0)">';
				
				/*foreach($impuestos as $row)
				{
					echo '<option value="'.$row->idImpuesto.'">'.$row->nombre.'('.number_format($row->tasa,decimales).')</option>';
				}*/
				
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