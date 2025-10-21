<?php
echo '

<div id="agregandoPersonal"></div>

<ul class="menuTabsCliente">
	<li id="generales" class="cliente activado" onclick="configurarTabsCliente(\'generales\')">Generales</li>
	<li id="contacto" class="cliente" onclick="configurarTabsCliente(\'contacto\')">Contacto emergencia</li>
	<li id="documentos" '.(sistemaActivo!='IEXE'?'style="display:none"':'').' class="cliente" onclick="configurarTabsCliente(\'documentos\')">Documentos</li>
</ul>

<form id="frmPersonal" name="frmPersonal" action="'.base_url().'administracion/editarPersonal" method="post" enctype="multipart/form-data" >
	<div id="div-generales" class="divCliente visible">
		<table class="admintable" width="100%">
			<tr>
				<td class="key">Nombre:</td>
				<td>
					<input type="text" class="cajas" id="txtNombre"     name="txtNombre" value="'.$personal->nombre.'" />
					<input type="hidden" 			 id="txtIdPersonal" name="txtIdPersonal" value="'.$idPersonal.'" />
				</td>
			</tr>
			
			<tr>
				<td class="key">Número acceso:</td>
				<td>
					<input type="text" class="cajas" id="txtNumeroAcceso"     name="txtNumeroAcceso" value="'.$personal->numeroAcceso.'" placeholder="Mínimo 6 digitos" onkeypress="return soloNumerico(event)" />
				</td>
			</tr>
			
			<tr>
				<td class="key">Fecha ingreso:</td>
				<td>
					<input style="width:120px" type="text" class="cajas" id="txtFechaIngreso" name="txtFechaIngreso" value="'.$personal->fechaIngreso.'" readonly="readonly" />
					<script>
						$("#txtFechaIngreso").datepicker();
					</script>
				</td>
			</tr>
			<tr>
				<td class="key">Puesto:</td>
				<td>
					<div id="obtenerPuestos" style="float:left; width:300px">
						<select class="cajas" id="selectPuestos" name="selectPuestos" style="width:290px">
							<option value="0">Seleccione</option>';
							foreach($puestos as $row)
							{
								$seleccionado=$row->idPuesto==$personal->idPuesto?'selected="selected"':'';
								echo '<option '.$seleccionado.' value="'.$row->idPuesto.'">'.$row->nombre.'</option>';
							}
						echo'
						</select>
					</div>
					
					<img onclick="formularioPuestos()" src="'.base_url().'img/agregar.png" width="20" title="Agregar puesto" height="20" />
				</td>
			</tr>
			
			<tr>
				<td class="key">Departamento:</td>
				<td>
					<div id="obtenerDepartamentos" style="float:left; width:300px">
						<select class="cajas" id="selectDepartamentos" name="selectDepartamentos" style="width:290px">
							<option value="0">Seleccione</option>';
							foreach($departamentos as $row)
							{
								$seleccionado=$row->idDepartamento==$personal->idDepartamento?'selected="selected"':'';
								echo '<option '.$seleccionado.' value="'.$row->idDepartamento.'">'.$row->nombre.'</option>';
							}
						echo'
						</select>
					</div>
					
					<img onclick="formularioDepartamentos()" src="'.base_url().'img/agregar.png" width="20" title="Agregar departamento" height="20" />
				</td>
			</tr>
			
			<tr>
			<td class="key">Estatus:</td>
			<td>
				<div id="obtenerEstatus" style="float:left; width:300px">
					<select class="cajas" id="selectEstatus" name="selectEstatus" style="width:290px">
						<option value="0">Seleccione</option>';
					
					foreach($estatus as $row)
					{
						echo '<option '.($row->idEstatus==$personal->idEstatus?'selected="selected"':'').' value="'.$row->idEstatus.'">'.$row->nombre.'</option>';
					}
					
					echo'
					</select>
				</div>
				
				<img onclick="formularioEstatus()" src="'.base_url().'img/agregar.png" width="20" title="Agregar puesto" height="20" />
			</td>
		</tr>
			
			<tr>
				<td class="key">Salario por día:</td>
				<td>
					<input type="text" class="cajas" id="txtSalario" name="txtSalario" value="'.$personal->salario.'" />
				</td>
			</tr>
			<tr>
				<td class="key">Calle:</td>
				<td>
					<input type="text" class="cajas" id="txtCalle" name="txtCalle" value="'.$personal->calle.'"/>
				</td>
			</tr>
			<tr>
				<td class="key">Número:</td>
				<td>
					<input type="text" class="cajas" id="txtNumero" name="txtNumero" value="'.$personal->numero.'" />
				</td>
			</tr>
			<tr>
				<td class="key">Colonia:</td>
				<td>
					<input type="text" class="cajas" id="txtColonia" name="txtColonia" value="'.$personal->colonia.'" />
				</td>
			</tr>
			<tr>
				<td class="key">Localidad:</td>
				<td>
					<input type="text" class="cajas" id="txtLocalidad" name="txtLocalidad" value="'.$personal->localidad.'" />
				</td>
			</tr>
			<tr>
				<td class="key">Municipio:</td>
				<td>
					<input type="text" class="cajas" id="txtMunicipio" name="txtMunicipio" value="'.$personal->municipio.'" />
				</td>
			</tr>
			<tr>
				<td class="key">Estado:</td>
				<td>
					<input type="text" class="cajas" id="txtEstado" name="txtEstado" value="'.$personal->estado.'" />
				</td>
			</tr>
			<tr>
				<td class="key">País:</td>
				<td>
					<input type="text" class="cajas" id="txtPais" name="txtPais" value="'.$personal->pais.'" />
				</td>
			</tr>
			<tr>
				<td class="key">Código postal:</td>
				<td>
					<input type="text" class="cajas" id="txtCodigoPostal" name="txtCodigoPostal" value="'.$personal->codigoPostal.'" />
				</td>
			</tr>
			<tr>
				<td class="key">Teléfono:</td>
				<td>
					<input type="text" class="cajas" id="txtTelefono" name="txtTelefono" value="'.$personal->telefono.'" />
				</td>
			</tr>
			<tr>
				<td class="key">Celular:</td>
				<td>
					<input type="text" class="cajas" id="txtCelular" name="txtCelular" value="'.$personal->celular.'" />
				</td>
			</tr>
			
			<tr>
				<td class="key">Correo personal:</td>
				<td>
					<input type="text" class="cajas" id="txtEmail" name="txtEmail" value="'.$personal->email.'" />
				</td>
			</tr>
			<tr>
				<td class="key">Correo institucional:</td>
				<td>
					<input type="text" class="cajas" id="txtEmail2" name="txtEmail2" value="'.$personal->email2.'" />
				</td>
			</tr>
			
			<tr>
				<td class="key">Fotografia:</td>
				<td>
					<input type="file" class="cajas" id="txtFotografia" name="txtFotografia" value="Seleccione" style="height:25px"   />
				</td>
			</tr>
			
			<tr>
				<td class="key">IMSS:</td>
				<td>
					<input type="text" class="cajas" id="txtImss" name="txtImss" value="'.$personal->imss.'" />
				</td>
			</tr>
			<tr>
				<td class="key">CURP:</td>
				<td>
					<input type="text" class="cajas" id="txtCurp" name="txtCurp" value="'.$personal->curp.'" />
				</td>
			</tr>
			
			<tr>
				<td class="key">Comentarios:</td>
				<td>
					<textarea class="TextArea" id="txtComentarios" name="txtComentarios">'.$personal->comentarios.'</textarea>
				</td>
			</tr>
		</table>
	</div>
	
	<div id="div-contacto" class="divCliente visible">
		<table class="admintable" width="100%">
			<tr>
				<td class="key">Parentesco:</td>
				<td>
					<input type="text" class="cajas" id="txtContactoParentesco" name="txtContactoParentesco"  value="'.$personal->contactoParentesto.'"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Dirección:</td>
				<td>
					<textarea class="TextArea" id="txtContactoDireccion" name="txtContactoDireccion">'.$personal->contactoDireccion.'</textarea>
				</td>
			</tr>
			
			<tr>
				<td class="key">Teléfono:</td>
				<td>
					<input type="text" class="cajas" id="txtContactoTelefono" name="txtContactoTelefono" value="'.$personal->contactoTelefono.'"/>
					<script>
						$("#txtFechaIngreso").datepicker();
					</script>
				</td>
			</tr>
		</table>
	</div>
	
	<div id="div-documentos" class="divCliente">
		<table class="admintable" width="99%">';
		
		foreach($tiposDocumentos as $row)
		{
			$documentos		= $this->administracion->obtenerDocumentosTipoPersonal($row->idTipo,$idPersonal);
			
			echo '
			<tr>
				<td class="key" style="width:40%">'.$row->nombre.'</td>
				<td>
					<input type="file" id="txtComprobanteCliente'.$row->idTipo.'" name="txtComprobanteCliente'.$row->idTipo.'" />
					
					<div id="listaArchivos'.$row->idTipo.'">';
					
					foreach($documentos as $doc)
					{
						echo '
						<div id="documento'.$doc->idDocumento.'" class="contenidoArchivo"> <img src="'.base_url().'img/borrado.png" onclick="accesoBorrarDocumento('.$doc->idDocumento.',0)" /><a title="Descargar documento" href="'.base_url().'administracion/descargarDocumentoPersonal/'.$doc->idDocumento.'">'.$doc->nombre.'</a></div>';
					}
					
					echo'
					</div>
				</td>
			</tr>
			
			<script type="text/javascript">
			$(document).ready(function()
			{ 
				$("#txtComprobanteCliente'.$row->idTipo.'").pekeUpload(
				{
					theme:				"bootstrap",
					btnText:			"Seleccione",
					allowedExtensions:	"jpeg|jpg|png|gif|tif|bmp|pdf|doc|docx|xls|xlsx|txt|rar|zip|xps|oxps|xml|PDF",
					url:				 "'.base_url().'administracion/subirArchivoPersonal/'.$row->idTipo.'/'.$idPersonal.'/0",
					maxSize:			25,
					sizeError:			"El archivo es demasiado grande",
					invalidExtError:	"No se permite ese tipo de archivos",
					onFileSuccess:function(file,data)
					{
						data	= eval(data);
						
						switch(data[0])
						{
							case "1":

								$("#listaArchivos'.$row->idTipo.'").append("<div id=\"documento"+data[3]+"\" class=\"contenidoArchivo\"> <img src=\"'.base_url().'img/borrado.png\" onclick=\"borrarDocumentoTemporal("+data[3]+",0)\" />  "+data[1]+"</div>")
								notify("El documento se ha cargado correctamento",500,4000,""); 
							break;	
							
							case "0":
								notify("El documento es inválido",500,4000,"error"); 
							break;	
						}
					}
				});
			});
		</script>';
		}
			
	echo'
	</div>
</form>';