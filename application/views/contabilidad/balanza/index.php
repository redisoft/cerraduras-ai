<script src="<?php echo base_url().carpetaJs?>contabilidad/balanza.js"></script>
<script>
$(document).ready(function()
{
	obtenerBalanza();
	
	$("#txtFechaInicial,#txtFechaFinal").datepicker();
});
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >

<?php
echo'
	<table class="toolbar" width="100%">
		<!--<td width="10%">
			<a onclick="formularioBalanza()" >
			<img src="'.base_url().'img/agregar.png" class="botonesMenu" title="Registrar" style="margin-right:9px" width="30"  />
			<br />
			Registrar
			</a>
		</td>-->
		
		<td align="center">
			Fecha de
			<input type="text" id="txtFechaInicial" class="cajas" value="'.date('Y-m-01').'" onchange="obtenerBalanza()" style="width:100px" />
			a
			<input type="text" id="txtFechaFinal" class="cajas" value="'.date('Y-m-d').'" onchange="obtenerBalanza()" style="width:100px"  />
			
			&nbsp;&nbsp;&nbsp;
			
			<input type="radio" id="rdMostrarCuentas" name="rdMostrarCuentas" value="0" checked="checked" onchange="obtenerBalanza()" />
			Mostrar cuentas con valores
			
			<input type="radio" id="rdMostrarCuentas" name="rdMostrarCuentas" value="1" onchange="obtenerBalanza()"/>
			Mostrar cuentas en $0
			
			<input type="radio" id="rdMostrarCuentas" name="rdMostrarCuentas" value="2" onchange="obtenerBalanza()"/>
			Mostrar solo cuentas de mayor
			 
		</td>
		<td width="10%" align="center" style="display:none">
			<img src="'.base_url().'img/excel.png" class="botonesMenu" title="Importar excel" style="margin-right:9px" id="subirExcel"  />
			<br />
			<label>Importar</label>
		</td>
	</table>
	</div>
</div>

<div id="procesandoInformacion"></div>
<div class="contenidoMenuDatos" id="obtenerBalanza" >
	
</div>

<div id="ventanaFormularioBalanza" title="Registrar balanza">
	<div id="formularioBalanza"></div>
</div>

<div id="ventanaEditarBalanza" title="Editar balanza">
	<div id="obtenerBalanzaEditar"></div>
</div>

<div id="ventanaCuentasBalanza" title="Cuentas balanza">
	<div id="obtenerCuentasBalanza"></div>
</div>

<div id="ventanaAgregarCuenta" title="Agregar cuenta">
	<div id="formularioAgregarCuenta"></div>
</div>

<div id="ventanaEditarCuenta" title="Editar cuenta">
	<div id="obtenerCuenta"></div>
</div>

<div id="ventanaCuentasBalanzaIva" title="Detalles de balanza" >
	<div id="obtenerCuentasBalanzaIva"></div>
</div>';
?>
</div>

