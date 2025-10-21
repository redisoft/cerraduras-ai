<?php
echo '
<script>
$(document).ready(function()
{
	$("#txtNombre").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerPersonal",
		
		select:function( event, ui)
		{
			notify("El nombre del personal ya esta registrado",500,5000,"error",5,5);
			document.getElementById("txtNombre").reset();
		}
	});
});
</script>

<div id="agregandoPersonal"></div>

<ul class="menuTabsCliente">
	<li id="generales" class="cliente activado" onclick="configurarTabsCliente(\'generales\')">Generales</li>
	<li id="contacto" class="cliente" onclick="configurarTabsCliente(\'contacto\')">Contacto emergencia</li>
	<li '.(sistemaActivo!='IEXE'?'style="display:none"':'').' id="documentos" class="cliente" onclick="configurarTabsCliente(\'documentos\')">Documentos</li>
</ul>

<form id="frmPersonal" name="frmPersonal" action="'.base_url().'administracion/agregarPersonal" method="post" enctype="multipart/form-data" >

<div id="div-generales" class="divCliente visible">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Nombre:</td>
			<td>
				<input type="text" class="cajas" id="txtNombre" name="txtNombre" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Número acceso:</td>
			<td>
				<input type="text" class="cajas" id="txtNumeroAcceso" name="txtNumeroAcceso" placeholder="Mínimo 6 digitos" onkeypress="return soloNumerico(event)" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Fecha ingreso:</td>
			<td>
				<input style="width:120px" type="text" class="cajas" id="txtFechaIngreso" name="txtFechaIngreso" value="'.date('Y-m-d').'" readonly="readonly" />
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
						<option value="0">Seleccione</option>
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
						<option value="0">Seleccione</option>
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
						echo '<option value="'.$row->idEstatus.'">'.$row->nombre.'</option>';
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
				<input type="text" class="cajas" id="txtSalario" name="txtSalario" />
			</td>
		</tr>
		<tr>
			<td class="key">Calle:</td>
			<td>
				<input type="text" class="cajas" id="txtCalle" name="txtCalle" />
			</td>
		</tr>
		<tr>
			<td class="key">Número:</td>
			<td>
				<input type="text" class="cajas" id="txtNumero" name="txtNumero" />
			</td>
		</tr>
		<tr>
			<td class="key">Colonia:</td>
			<td>
				<input type="text" class="cajas" id="txtColonia" name="txtColonia" />
			</td>
		</tr>
		<tr>
			<td class="key">Localidad:</td>
			<td>
				<input type="text" class="cajas" id="txtLocalidad" name="txtLocalidad" />
			</td>
		</tr>
		<tr>
			<td class="key">Municipio:</td>
			<td>
				<input type="text" class="cajas" id="txtMunicipio" name="txtMunicipio" />
			</td>
		</tr>
		<tr>
			<td class="key">Estado:</td>
			<td>
				<input type="text" class="cajas" id="txtEstado" name="txtEstado" />
			</td>
		</tr>
		<tr>
			<td class="key">País:</td>
			<td>
				<input type="text" class="cajas" id="txtPais" name="txtPais" />
			</td>
		</tr>
		<tr>
			<td class="key">Código postal:</td>
			<td>
				<input type="text" class="cajas" id="txtCodigoPostal" name="txtCodigoPostal" />
			</td>
		</tr>
		<tr>
			<td class="key">Teléfono:</td>
			<td>
				<input type="text" class="cajas" id="txtTelefono" name="txtTelefono" />
			</td>
		</tr>
		<tr>
			<td class="key">Celular:</td>
			<td>
				<input type="text" class="cajas" id="txtCelular" name="txtCelular" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Correo personal:</td>
			<td>
				<input type="text" class="cajas" id="txtEmail" name="txtEmail" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Correo institucional:</td>
			<td>
				<input type="text" class="cajas" id="txtEmail2" name="txtEmail2" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Fotografia:</td>
			<td>
				<input type="file" class="cajas" id="txtFotografia" name="txtFotografia" value="Seleccione" style="height:25px" />
			</td>
		</tr>
		
		<tr>
			<td class="key">IMSS:</td>
			<td>
				<input type="text" class="cajas" id="txtImss" name="txtImss" />
			</td>
		</tr>
		<tr>
			<td class="key">CURP:</td>
			<td>
				<input type="text" class="cajas" id="txtCurp" name="txtCurp" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Comentarios:</td>
			<td>
				<textarea class="TextArea" id="txtComentarios" name="txtComentarios"></textarea>
			</td>
		</tr>
	</table>
</div>

<div id="div-contacto" class="divCliente visible">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Parentesco:</td>
			<td>
				<input type="text" class="cajas" id="txtContactoParentesco" name="txtContactoParentesco" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Dirección:</td>
			<td>
				<textarea class="TextArea" id="txtContactoDireccion" name="txtContactoDireccion"></textarea>
			</td>
		</tr>
		
		<tr>
			<td class="key">Teléfono:</td>
			<td>
				<input type="text" class="cajas" id="txtContactoTelefono" name="txtContactoTelefono"/>
				<script>
					$("#txtFechaIngreso").datepicker();
				</script>
			</td>
		</tr>
	</table>
</div>

<input type="hidden" id="txtIdPersonalDocumentos" name="txtIdPersonalDocumentos" value="'.$idPersonal.'" />

<div id="div-documentos" class="divCliente">
	<table class="admintable" width="99%">';
		
		foreach($tiposDocumentos as $row)
		{
			echo '
			<tr>
				<td class="key" style="width:40%">'.$row->nombre.'</td>
				<td>
					<input type="file" id="txtComprobanteCliente'.$row->idTipo.'" name="txtComprobanteCliente'.$row->idTipo.'" />
					
					<div id="listaArchivos'.$row->idTipo.'"></div>
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
					url:				 "'.base_url().'administracion/subirArchivoPersonal/'.$row->idTipo.'/'.$idPersonal.'/1",
					maxSize:			25,
					sizeError:			"El archivo es demasiado grande",
					invalidExtError:	"No se permite ese tipo de archivos",
					onFileSuccess:function(file,data)
					{
						data	= eval(data);
						
						switch(data[0])
						{
							case "1":

								$("#listaArchivos'.$row->idTipo.'").append("<div id=\"documento"+data[3]+"\" class=\"contenidoArchivo\"> <img src=\"'.base_url().'img/borrado.png\" onclick=\"borrarDocumentoTemporal("+data[3]+",1)\" />  "+data[1]+"</div>")
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
	</table>
</div>
</form>';