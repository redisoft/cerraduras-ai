<?php
$pais							= $proveedor->pais=='México'?"Mexico":$proveedor->pais;
		
#$config['center'] 				= $proveedor->numero.', '.$proveedor->domicilio.', '.$proveedor->localidad.', '.$proveedor->municipio.', '.$proveedor->estado.', '.$pais.', '.$proveedor->codigoPostal;
/*$config['center'] 				= $proveedor->latitud.', '.$proveedor->longitud;
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

$marker['icon'] 				= 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|9999FF|000000';
$marker['position'] 			= $config['center'];
$this->googlemaps->add_marker($marker);
$map 					= $this->googlemaps->create_map();

echo $map['js'];
echo $map['html'];
*/
 echo'
<script>
$(document).ready(function()
{
	//loadScript();
});
</script>';

echo
'<div id="recargarMapa"></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Cuenta contable:</td>
		<td>
			<input type="text" class="cajas" id="txtBuscarCuentaContable" name="txtBuscarCuentaContable" style="width:300px" placeholder="'.$proveedor->cuenta.'" value="'.$proveedor->cuenta.'" readonly="readonly" />
			<input type="hidden" id="txtIdCuentaCatalogo" name="txtIdCuentaCatalogo" value="'.$proveedor->idCuentaCatalogo.'" />
			
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
			<input type="text" class="cajas" id="txtSaldoInicial" name="txtSaldoInicial" style="width:100px" onkeypress="return soloDecimales(event)" value="'.round($proveedor->saldoInicial,decimales).'" />
		</td>
	</tr>
	
	<tr>
		<td class="key">* Empresa</td>
		<td>
			<input value="'.$proveedor->empresa.'" type="text" class="cajas" id="empresa1" name="empresa1" style="width:650px" />
			<input type="hidden" value="'.$idProveedor.'" name="txtIdProveedor" id="txtIdProveedor"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Vende:</td>
		<td>
			<input value="'.$proveedor->vende.'" type="text" class="cajas" id="txtVende" name="txtVende" style="width:650px" />
		</td>
	</tr>
	
	 <tr>
		<td class="key">RFC</td>
		<td>
		<input type="text" class="cajas" value="'.$proveedor->rfc.'" name="rfc" id="rfc1" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Alias</td>
		<td>
			<input type="text" class="cajas" value="'.$proveedor->alias.'" name="txtAlias" id="txtAlias" style="width:200px" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Latitud</td>
		<td>
			<input type="text" class="cajas" value="'.$proveedor->latitud.'"  name="txtLatitud" id="txtLatitud" style="width:200px"/>
		</td>
	</tr>
	
	 <tr>
		<td class="key">Longitud</td>
		<td>
			<input type="text" class="cajas" value="'.$proveedor->longitud.'"  name="txtLongitud" id="txtLongitud" style="width:200px"/>
		</td>
	</tr>
	
	  <tr>
		<td class="key">* Calle</td>
		<td>
			<textarea class="TextArea" style="width:205px" name="domicilio" id="domicilio">'.$proveedor->domicilio.'</textarea>
		</td>
	</tr>
	
	<tr>
		<td class="key">Número</td>
		<td>
			<input type="text" class="cajas" value="'.$proveedor->numero.'" name="txtNumero" id="txtNumero" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Colonia</td>
		<td>
			<input type="text" class="cajas" value="'.$proveedor->colonia.'" name="txtColonia" id="txtColonia" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Código postal</td>
		<td>
			<input type="text" class="cajas" value="'.$proveedor->codigoPostal.'" name="txtCodigoPostal" id="txtCodigoPostal" style="width:200px" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Localidad</td>
		<td>
			<input type="text" class="cajas" value="'.$proveedor->localidad.'" name="txtLocalidad" id="txtLocalidad" style="width:200px" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Municipio</td>
		<td>
			<input type="text" class="cajas" value="'.$proveedor->municipio.'" name="txtMunicipio" id="txtMunicipio" style="width:200px" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Estado</td>
		<td>
			<input type="text" class="cajas" value="'.$proveedor->estado.'" name="estado" id="estado" style="width:200px" />
		</td>
	</tr>
	
	 <tr>
		<td class="key">País</td>
		<td>
			<input type="text" class="cajas" value="'.$proveedor->pais.'" name="pais" id="pais" style="width:200px"/>
		</td>
	</tr>

	 <tr>
		<td class="key">* Teléfono</td>
		<td>
			<input placeholder="Lada" type="text" class="cajas" name="txtLada" id="txtLada" style="width:50px" maxlength="10" value="'.$proveedor->lada.'"/>
			<input placeholder="Teléfono" type="text" class="cajas" name="txtTelefono" id="txtTelefono" style="width:140px" value="'.$proveedor->telefono.'"/>
		</td>
	</tr>
	
	 <tr>
		<td class="key">Fax</td>
		<td>
			<input placeholder="Lada" type="text" class="cajas" name="txtLadaFax" id="txtLadaFax" style="width:50px" maxlength="10" value="'.$proveedor->ladaFax.'"/>
			<input placeholder="Fax" type="text" class="cajas" name="txtFax" id="txtFax" style="width:140px" value="'.$proveedor->fax.'"/>
		</td>
	</tr>

	 <tr>
		<td class="key">Email</td>
		<td>
			<input type="text" class="cajas" value="'.$proveedor->email.'" name="email" id="email1" style="width:200px"/>
		</td>
	</tr>

	 <tr>
		<td class="key">Página web</td>
		<td>
		<input type="text" class="cajas" value="'.$proveedor->website.'" name="pagina" id="pagina1" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Días de crédito:</td>
		<td>
			<input type="text" class="cajas" name="txtDiasCredito" id="txtDiasCredito" value="'.$proveedor->diasCredito.'" style="width:200px"/>
		</td>
	</tr>
	
	</table>';
	

	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="6">
				Cuentas de banco
				<img src="'.base_url().'img/add.png" width="22" title="Agregar cuenta" onclick="formularioCuentas()" />
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Banco</th>
			<th>Sucursal</th>
			<th>Cuenta</th>
			<th>Clabe</th>
			
			<th>Acciones</th>
		</tr>';
		
		$i=1;
		foreach($cuentas as $row)
		{
			echo '
			<tr>
				<td>'.$i.'</td>
				<td>'.$row->banco.'</td>
				<td>'.$row->sucursal.'</td>
				<td>'.$row->cuenta.'</td>
				<td>'.$row->clabe.'</td>
				
				<td align="center">
					<img onclick="obtenerCuenta('.$row->idCuenta.')" src="'.base_url().'img/editar.png" width="22" title="Editar cuenta" />
					&nbsp;&nbsp;
					<img onclick="borrarCuenta('.$row->idCuenta.')" src="'.base_url().'img/borrar.png" width="22" title="Borrar cuenta" />
					<br />
					<a>Editar</a>
					<a>Borrar</a>
				</td>
			</tr>';
			
			$i++;
	}
	
echo'</table>';