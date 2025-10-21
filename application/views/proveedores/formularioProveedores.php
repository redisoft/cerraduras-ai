<?php
/*$config['center'] 				= 'Puebla, Mexico';
$config['zoom'] 				= '13';
$config['loadAsynchronously'] 	= true;
$config['https'] 				= true;
$config['map_height'] 			= '300px';
$config['map_width'] 			= '500px';
$config['posicionY'] 			= '11%';
$config['posicionX'] 			= '47%';
$config['posicion'] 			= 'absolute';

$config['map_div_id'] 			= 'mapaProveedores';

$this->googlemaps->initialize($config);

$map 					= $this->googlemaps->create_map();

echo $map['js'];
echo $map['html'];*/

 echo'
<script>
$(document).ready(function()
{
	//loadScript();
	
	$("#empresa").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerProveedores",
		
		select:function( event, ui)
		{
			notify("El proveedor ya esta registrado",500,5000,"error",5,5);
			document.getElementById("empresa").reset();
		}
	});
});
</script>';

echo '
<div id="recargarMapa"></div>
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Cuenta contable:</td>
		<td>
			<input type="text" class="cajas" id="txtBuscarCuentaContable" name="txtBuscarCuentaContable" style="width:300px" placeholder="Proveedores nacionales"  readonly="readonly"/>
			<input type="hidden" id="txtIdCuentaCatalogo" name="txtIdCuentaCatalogo" value="251" />
			
			<label style="cursor:pointer; float:right; margin-right: 300px" onclick="formularioAsociarCuenta()" title="Agregar cuenta" >
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img src="'.base_url().'img/contabilidad.png" width="28"  /><br />
				Agregar cuenta
			</label>
		</td>
	</tr>
	
	<tr>
		<td class="key">Saldo inicial:</td>
		<td>
			<input type="text" class="cajas" id="txtSaldoInicial" name="txtSaldoInicial" style="width:100px" onkeypress="return soloDecimales(event)" value="0" />
		</td>
	</tr>
	
	<tr>
		<td class="key">* Empresa:</td>
		<td>
			<input type="text" class="cajas" id="empresa" name="empresa" style="width:650px" />
		</td>
	</tr>
	<tr>
		<td class="key">Vende:</td>
		<td>
			<input type="text" class="cajas" id="txtVende" name="txtVende" style="width:650px" />
		</td>
	</tr>
	 <tr>
		<td class="key">RFC:</td>
		<td>
			<input type="text" class="cajas" name="rfc" id="rfc" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Alias:</td>
		<td>
		<input type="text" class="cajas" name="txtAlias" id="txtAlias" style="width:200px" />
		</td>
	</tr>
	  <tr>
	<td class="key">Latitud</td>
		<td>
			<input type="text" class="cajas" value=""  name="txtLatitud" id="txtLatitud" style="width:200px"/>
		</td>
	</tr>
	
	 <tr>
		<td class="key">Longitud</td>
		<td>
			<input type="text" class="cajas" value=""  name="txtLongitud" id="txtLongitud" style="width:200px"/>
		</td>
	</tr>
	  <tr>
		<td class="key">*Calle:</td>
		<td>
			<textarea class="TextArea" style="width:205px" name="domicilio" id="domicilio"></textarea>
		</td>
	</tr>
	
	<tr>
		<td class="key">Número:</td>
		<td>
		<input type="text" class="cajas" name="txtNumero" id="txtNumero" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Colonia:</td>
		<td>
		<input type="text" class="cajas" name="txtColonia" id="txtColonia" style="width:200px"/>
		</td>
	</tr>
	
	 <tr>
		<td class="key">Código postal:</td>
		<td>
		<input type="text" class="cajas" name="txtCodigoPostal" id="txtCodigoPostal" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Localidad:</td>
		<td>
			<input type="text" class="cajas" name="txtLocalidad" id="txtLocalidad" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Municipio:</td>
		<td>
			<input type="text" class="cajas" name="txtMunicipio" id="txtMunicipio" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Estado:</td>
		<td>
		<input type="text" class="cajas" name="estado" id="estado" style="width:200px" />
		</td>
	</tr>
	
	 <tr>
		<td class="key">País:</td>
		<td>
		<input type="text" class="cajas" name="pais" id="pais" style="width:200px"/>
		</td>
	</tr>

	 <tr>
		<td class="key">* Teléfono</td>
		<td>
			<input placeholder="Lada" type="text" class="cajas" name="txtLada" id="txtLada" style="width:50px" maxlength="10"/>
			<input placeholder="Teléfono" type="text" class="cajas" name="txtTelefono" id="txtTelefono" style="width:140px"/>
		</td>
	</tr>
	
	 <tr>
		<td class="key">Fax</td>
		<td>
			<input placeholder="Lada" type="text" class="cajas" name="txtLadaFax" id="txtLadaFax" style="width:50px" maxlength="10"/>
			<input placeholder="Fax" type="text" class="cajas" name="txtFax" id="txtFax" style="width:140px"/>
		</td>
	</tr>

	 <tr>
		<td class="key">Email:</td>
		<td>
		<input type="text" class="cajas" name="email" id="email" style="width:200px"/>
		</td>
	</tr>
	
	
	 <tr>
		<td class="key">Página web:</td>
		<td>
			<input type="text" class="cajas" name="pagina" id="pagina" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Días de crédito:</td>
		<td>
			<input type="text" class="cajas" name="txtDiasCredito" id="txtDiasCredito" style="width:200px"/>
		</td>
	</tr>
	
	
	<tr>
		<th colspan="2">Contacto</th>
	</tr>
	
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input type="text" class="cajas" name="txtNombreContacto" id="txtNombreContacto" style="width:200px"/>
		</td>
	</tr>
	<tr>
		<td class="key">Teléfono:</td>
		<td>
			<input type="text" class="cajas" name="txtTelefonoContacto" id="txtTelefonoContacto" style="width:200px"/>
		</td>
	</tr>
	<tr>
		<td class="key">Email:</td>
		<td>
			<input type="text" class="cajas" name="txtEmailContacto" id="txtEmailContacto" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Departamento:</td>
		<td>
			<input type="text" class="cajas" name="txtDepartamento" id="txtDepartamento" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Extensión:</td>
		<td>
			<input type="text" class="cajas" name="txtExtension" id="txtExtension" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<th colspan="2">Cuenta de banco</th>
	</tr>
	<tr>
		<td class="key">Banco:</td>
		<td>
			<input type="text" class="cajas" name="txtBanco" id="txtBanco" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Sucursal:</td>
		<td>
			<input type="text" class="cajas" name="txtSucursal" id="txtSucursal" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Cuenta:</td>
		<td>
			<input type="text" class="cajas" name="txtCuenta" id="txtCuenta" style="width:200px"/>
		</td>
	</tr>
	<tr>
		<td class="key">Clabe:</td>
		<td>
			<input type="text" class="cajas" name="txtClabe" id="txtClabe" style="width:200px"/>
		</td>
	</tr>
</table>';