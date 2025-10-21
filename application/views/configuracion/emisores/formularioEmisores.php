
<link href="<?php echo base_url()?>css/pekeUpload/bootstrap/css/bootstrap.css" rel="stylesheet">   
<link href="<?php echo base_url()?>css/pekeUpload/custom.css" rel="stylesheet">   
<script src="<?php echo base_url()?>js/bibliotecas/pekeUpload/pekeUpload.js"></script>
<script src="<?php echo base_url()?>js/certificados.js"></script>

<!--<script type="text/javascript">
	$(document).ready(function()
	{ 
		$("#fileCertificado").pekeUpload(
		{
			theme:				'bootstrap',
			btnText:			'Seleccione',
			allowedExtensions:	"cer",
			url:				base_url+'configuracion/subirCertificado',
			maxSize:			25,
			sizeError:			'El archivo es demasiado grande',
			invalidExtError:	'No se permite ese tipo de archivos',
			onFileSuccess:function(file,data)
			{
				//data	= eval(data);
				
				switch(data)
				{
					case "0":
						//alert(data[1])
					break;	
					
					case "1":
						procesarCertificado(file.name);
					break;	
				}
			}
		});
	});
</script>-->

<?php
echo '
<form  name="frmEmisor" id="frmEmisor" action="'.base_url().'configuracion/registrarEmisor" method="post" enctype="multipart/form-data">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Empresa: </td>
			<td><input style="width:500px" type="text" class="cajas" id="txtEmpresa" name="txtEmpresa" /></td>
		</tr>
		
		<tr>
			<td class="key">RFC: </td>
			<td><input type="text" class="cajas" id="txtRfc" name="txtRfc" /></td>
		</tr>
		
		<tr>
			<td class="key">Calle: </td>
			<td><input style="width:400px" type="text" class="cajas" id="txtCalle" name="txtCalle" /></td>
		</tr>
		
		<tr>
			<td class="key">Numero exterior: </td>
			<td><input type="text" class="cajas" id="txtNumeroExterior" name="txtNumeroExterior" /></td>
		</tr>
		
		<tr>
			<td class="key">Numero interior: </td>
			<td><input type="text" class="cajas" id="txtNumeroInterior" name="txtNumeroInterior" /></td>
		</tr>
		
		<tr>
			<td class="key">Colonia: </td>
			<td><input style="width:400px" type="text" class="cajas" id="txtColonia" name="txtColonia" /></td>
		</tr>
		
		<tr>
			<td class="key">Localidad: </td>
			<td><input style="width:400px" type="text" class="cajas" id="txtLocalidad" name="txtLocalidad" /></td>
		</tr>
		
		<tr>
			<td class="key">Municipio: </td>
			<td><input type="text" class="cajas" id="txtMunicipio" name="txtMunicipio" /></td>
		</tr>
		
		<tr>
			<td class="key">Estado: </td>
			<td><input type="text" class="cajas" id="txtEstado" name="txtEstado" /></td>
		</tr>
		
		<tr>
			<td class="key">Pais: </td>
			<td><input type="text" class="cajas" id="txtPais" name="txtPais" value="México" /></td>
		</tr>
		
		<tr>
			<td class="key">Codigo postal: </td>
			<td>
				<input type="text" class="cajas" id="txtCodigoPostal" name="txtCodigoPostal" />
			</td>
		</tr>
	 
		<tr>
			<td class="key">Folio inicial: </td>
			<td>
				<input type="text" class="cajas" id="txtFolioInicial" name="txtFolioInicial" value="1" readonly="readonly" />
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Folio final: </td>
			<td>
				<input type="text" class="cajas" id="txtFolioFinal" name="txtFolioFinal" value="100" value="0"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Serie: </td>
			<td>
				<input type="text" class="cajas" id="txtSerie" name="txtSerie" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Logotipo: </td>
			<td>
				<input type="file" id="fileImagen" class="cajas"  name="fileImagen" style="height:30px; width: 400px" />
			</td>
		</tr>

		<tr>
			<td class="key">Certificado: </td>
			<td>
				<input class="cajas" type="file" name="fileCertificado" id="fileCertificado" />
				<div id="obteniendoCertificado"></div>
			</td>
		</tr>
		
		<tr>
			<td class="key">Número de certificado: </td>
			<td>
				<input readonly="readonly" type="text" class="cajas" id="txtNumeroCertificado" name="txtNumeroCertificado" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Fecha inicio: </td>
			<td>
				<input readonly="readonly" type="text" class="cajas" id="txtFechaInicio" name="txtFechaInicio" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Fecha caducidad: </td>
			<td>
				<input readonly="readonly" type="text" class="cajas" id="txtFechaCaducidad" name="txtFechaCaducidad" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Llave privada: </td>
			<td>
				<input onchange="comprobarLlave()" type="file" id="fileLlave" class="cajas"  name="fileLlave" style="height:30px; width: 400px"/>
			</td>
		</tr>
		<tr>
			<td class="key">Password de la llave privada: </td>
			<td>
				<input type="password" class="cajas" id="passwordLlave" name="passwordLlave" />
			</td>
		</tr>
		<tr>
			<td class="key">Número de cuenta (ultimos 4 digitos): </td>
			<td>
				<input type="text" class="cajas" id="txtNumeroCuenta" name="txtNumeroCuenta"  />
			</td>
		</tr>
		
		
		<tr>
			<td class="key">Regimen Fiscal: </td>
			<td>
				<select class="cajas" id="selectRegimenFiscal" name="selectRegimenFiscal" style="width:400px">';
				
				foreach($regimen as $row)
				{
					echo '<option value="'.$row->idRegimen.'">'.$row->clave.', '.$row->nombre.'</option>';
				}
				
				echo'
				</select>
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Nota al margen: </td>
			<td>
				<textarea class="cajasGrandes" id="txtNotaMargen" name="txtNotaMargen"></textarea>
			</td>
		</tr>
		<tr style="display:none">
			<td class="key">Sucursales: </td>
			<td>
				<textarea class="cajasGrandes" id="txtSucursales" name="txtSucursales"></textarea>
			</td>
		</tr>
	</table>
</form>';
?>