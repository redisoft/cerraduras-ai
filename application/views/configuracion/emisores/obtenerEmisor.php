<link href="<?php echo base_url()?>css/pekeUpload/bootstrap/css/bootstrap.css" rel="stylesheet">   
<link href="<?php echo base_url()?>css/pekeUpload/custom.css" rel="stylesheet">   
<script src="<?php echo base_url()?>js/bibliotecas/pekeUpload/pekeUpload.js"></script>
<script src="<?php echo base_url()?>js/certificados.js"></script>
<?php
echo '
<form  name="frmEmisor" id="frmEmisor" action="'.base_url().'configuracion/editarEmisor" method="post" enctype="multipart/form-data">
<table class="admintable" width="100%">
	<tr>
		<td class="key">Empresa: </td>
		<td>';
			
		echo"<input value='".$emisor->nombre."' style='width:400px' type='text' class='cajas' id='txtEmpresa' name='txtEmpresa' />";
			
			echo'<input value="'.$emisor->idEmisor.'" id="txtIdEmisor" name="txtIdEmisor" type="hidden" />
		</td>
	</tr>
	
	<tr>
		<td class="key">RFC: </td>
		<td><input value="'.$emisor->rfc.'" type="text" class="cajas" id="txtRfc" name="txtRfc" /></td>
	</tr>
	
	<tr>
		<td class="key">Calle: </td>
		<td><input value="'.$emisor->calle.'" style="width:400px" type="text" class="cajas" id="txtCalle" name="txtCalle" /></td>
	</tr>
	
	<tr>
		<td class="key">Numero exterior: </td>
		<td><input value="'.$emisor->numeroExterior.'" type="text" class="cajas" id="txtNumeroExterior" name="txtNumeroExterior" /></td>
	</tr>
	
	<tr>
		<td class="key">Numero interior: </td>
		<td><input value="'.$emisor->numeroInterior.'" type="text" class="cajas" id="txtNumeroInterior" name="txtNumeroInterior" /></td>
	</tr>
	
	<tr>
		<td class="key">Colonia: </td>
		<td><input value="'.$emisor->colonia.'" style="width:400px" type="text" class="cajas" id="txtColonia" name="txtColonia" /></td>
	</tr>
	
	<tr>
		<td class="key">Localidad: </td>
		<td><input value="'.$emisor->localidad.'" style="width:400px" type="text" class="cajas" id="txtLocalidad" name="txtLocalidad" /></td>
	</tr>
	
	<tr>
		<td class="key">Municipio: </td>
		<td><input value="'.$emisor->municipio.'" type="text" class="cajas" id="txtMunicipio" name="txtMunicipio" /></td>
	</tr>
	
	<tr>
		<td class="key">Estado: </td>
		<td><input value="'.$emisor->estado.'" type="text" class="cajas" id="txtEstado" name="txtEstado" /></td>
	</tr>
	
	<tr>
		<td class="key">Pais: </td>
		<td><input value="'.$emisor->pais.'" type="text" class="cajas" id="txtPais" name="txtPais" /></td>
	</tr>
	
	<tr>
		<td class="key">Codigo postal: </td>
		<td><input value="'.$emisor->codigoPostal.'" type="text" class="cajas" id="txtCodigoPostal" name="txtCodigoPostal" /></td>
	</tr>
 
	<tr>
		<td class="key">Folio inicial: </td>
		<td><input type="text" value="'.$emisor->folioInicial.'" class="cajas" id="txtFolioInicial" name="txtFolioInicial" readonly="readonly" /></td>
	</tr>
	
	<tr style="display:none">
	  <td class="key">Folio final: </td>
	  <td>
		<input type="text" value="'.$emisor->folioFinal.'" class="cajas" id="txtFolioFinal" name="txtFolioFinal" readonly="readonly"/>
	  </td>
	</tr>
	<tr>
	  <td class="key">Serie: </td>
	  <td>
		<input type="text" value="'.$emisor->serie.'" class="cajas" id="txtSerie" name="txtSerie" readonly="readonly"/>
	  </td>
	</tr>
	
	<tr>
	  <td class="key">Logotipo: </td>
	  <td>
		<input type="file" id="fileImagen" class="cajas"  name="fileImagen" style="height:30px; width: 400px" />
		<img src="'.base_url().'media/fel/'.$emisor->rfc.'/'.$emisor->logotipo.'" style="height:40px; width:60px" />
	  </td>
	</tr>
	
	<tr>
		<td class="key">Certificado: </td>
		<td>
			<input class="cajas" type="file" name="fileCertificado" id="fileCertificado"/>
			<div id="obteniendoCertificado"></div>
			<br />'.$emisor->certificado.'
		</td>
	</tr>
	
	<tr>
	  <td class="key">Número de certificado: </td>
	  <td>
		<input readonly="readonly" type="text" value="'.$emisor->numeroCertificado.'" class="cajas" id="txtNumeroCertificado" name="txtNumeroCertificado" />
	  </td>
	</tr>
	
	<tr>
		<td class="key">Fecha inicio: </td>
		<td>
			<input readonly="readonly" type="text" value="'.$emisor->fechaInicio.'" class="cajas" id="txtFechaInicio" name="txtFechaInicio" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha caducidad: </td>
		<td>
			<input readonly="readonly" type="text" value="'.$emisor->fechaCaducidad.'" class="cajas" id="txtFechaCaducidad" name="txtFechaCaducidad" />
		</td>
	</tr>
	
		<tr>
		  <td class="key">Llave privada: </td>
		  <td>
			<input onchange="comprobarLlave()" type="file" id="fileLlave" class="cajas"  name="fileLlave" style="height:30px; width: 400px"/>
			<br />'.$emisor->llave.'
		  </td>
		</tr>
		<tr>
		  <td class="key">Password de la llave privada: </td>
		  <td>
			<input type="password" value="'.$emisor->passwordLlave.'" class="cajas" id="passwordLlave" name="passwordLlave" />
		  </td>
		</tr>
		 <tr>
		  <td class="key">Número de cuenta (ultimos 4 digitos): </td>
		  <td>
			<input type="text" value="'.$emisor->numeroCuenta.'" class="cajas" id="txtNumeroCuenta" name="txtNumeroCuenta"  />
		  </td>
		</tr>
		
		
		<tr>
			<td class="key">Regimen Fiscal: </td>
			<td>
				<select class="cajas" id="selectRegimenFiscal" name="selectRegimenFiscal" style="width:400px">';
				
				foreach($regimen as $row)
				{
					echo '<option '.($row->idRegimen==$emisor->idRegimen?'selected="selected"':'').' value="'.$row->idRegimen.'">'.$row->clave.', '.$row->nombre.'</option>';
				}
				
				echo'
				</select>
			</td>
		</tr>
		
	  
		  <tr style="display:none">
			<td class="key">Nota al margen: </td>
			<td>
				<textarea class="cajasGrandes" id="txtNotaMargen" name="txtNotaMargen">'.$emisor->notas.'</textarea>
			</td>
		</tr>
		<tr style="display:none">
			<td class="key">Sucursales: </td>
			<td>
				<textarea class="cajasGrandes" id="txtSucursales" name="txtSucursales">'.$emisor->sucursales.'</textarea>
			</td>
		</tr>
	</table>
</form>';
		
?>
